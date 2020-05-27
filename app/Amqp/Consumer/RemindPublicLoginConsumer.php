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
class RemindPublicLoginConsumer extends ConsumerMessage
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

    protected $exchange = 'remind.public';
    protected $type = Type::DIRECT;
    protected $queue = 'remind.public.login'; //队列名称
    protected $routingKey = [
        'remind.public.login',
    ];

    public function consume($data): string
    {
        $redis = $this->container->get(RedisFactory::class)->get('default');
        $server = $this->container->get(ServerFactory::class)->getServer()->getServer();

        $param = json_decode($data, true);
        $notify = $this->notifyService->getList([
            'receiver_id' => $param['receiver_id'],
            'receiver_type' => $param['receiver_type'],
            'is_read' => 0,
        ]);

        foreach($notify as $v){
            $user_key = 'ws_' . $param['receiver_type'] . '_' . $param['receiver_id'];
            $fd = $redis->get($user_key);
            if($fd){
                $result = $server->push((int) $fd, json_encode($v));
                if ($result == 1) { //推送成功
                    $this->notifyService->read($v->id);
                } else { //推送失败
                    // to do ...延迟推送机制
                }
            }
        }

        return Result::ACK;
    }
}
