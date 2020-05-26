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
class TopicPublicLoginConsumer extends ConsumerMessage
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

    protected $exchange = 'topic.public';
    protected $type = Type::TOPIC;
    protected $queue = 'topic.public';
    protected $routingKey = 'topic.public.*';

    public function consume($data): string
    {
        //to do ...

        return Result::ACK;
    }
}
