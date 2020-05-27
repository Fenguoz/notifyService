<?php

declare(strict_types=1);

namespace App\Amqp\Consumer;

use App\Model\Subscription;
use App\Service\NotifyService;
use App\Service\TemplateConfigService;
use Hyperf\Amqp\Result;
use Hyperf\Amqp\Annotation\Consumer;
use Hyperf\Amqp\Message\ConsumerMessage;
use Hyperf\Amqp\Message\Type;
use Hyperf\Redis\RedisFactory;
use Hyperf\Server\ServerFactory;
use Hyperf\Di\Annotation\Inject;

/**
 * @Consumer(nums=1)
 */
class TopicOrderConsumer extends ConsumerMessage
{
    /**
     * @Inject
     * @var Subscription
     */
    protected $subscription;

    /**
     * @Inject
     * @var NotifyService
     */
    protected $notifyService;

    /**
     * @Inject
     * @var TemplateConfigService
     */
    protected $templateConfigService;

    protected $exchange = 'topic.order';
    protected $type = Type::TOPIC;
    protected $queue = 'topic.order';
    protected $routingKey = 'topic.order.*';

    public function consume($data): string
    {
        $redis = $this->container->get(RedisFactory::class)->get('default');
        $server = $this->container->get(ServerFactory::class)->getServer()->getServer();

        $param = json_decode($data, true);
        $subscribes = $this->subscription
            ->where([
                'type' => 'topic',
            ])
            ->where('relational', 'like', '%,' . $param['type_id'] . ',%')
            ->get();
        foreach ($subscribes as $subscribe) {
            $content = $this->templateConfigService->getTemplateContent($param['type_id'], $subscribe->user_type, $param['ext_param']);

            $mq_data = [
                'type' => 'topic',
                'type_id' => $param['type_id'],
                'content' => $content,
                'target_id' => $param['target_id'],
                'sender_id' => $param['sender_id'],
                'sender_type' => $param['sender_type'],
                'receiver_id' => $param['receiver_id'],
                'receiver_type' => $param['receiver_type'],
            ];

            $user_key = 'ws_' . $subscribe->user_type . '_' . $subscribe->user_id;
            $fd = $redis->get($user_key);
            if($fd){
                $result = $server->push((int) $fd, json_encode([
                    'code' => 200,
                    'message' => 'success',
                    'data' => $mq_data
                ]));//return type bool
                if ($result == 1) { //推送成功
                    //to do...
                } else { //推送失败
                    $result = $this->notifyService->add($mq_data);
                }
            }else{
                $result = $this->notifyService->add($mq_data);
            }
        }

        return Result::ACK;
    }
}
