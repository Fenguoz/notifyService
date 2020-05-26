<?php

declare(strict_types=1);

namespace App\Amqp\Producer;

use Hyperf\Amqp\Annotation\Producer;
use Hyperf\Amqp\Message\ProducerMessage;
use Hyperf\Amqp\Message\Type;

/**
 * @Producer()
 */
class TopicProducer extends ProducerMessage
{
    protected $type = Type::TOPIC;
    public function __construct($data, $routingKey, $exchange)
    {
        $this->exchange = $exchange;
        $this->routingKey = $routingKey;
        $this->payload = $data;
    }
}
