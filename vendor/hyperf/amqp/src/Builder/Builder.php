<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace Hyperf\Amqp\Builder;

use PhpAmqpLib\Wire\AMQPTable;

class Builder
{
    /**
     * @var bool
     */
    protected $passive = false;

    /**
     * @var bool
     */
    protected $durable = true;

    /**
     * @var bool
     */
    protected $autoDelete = false;

    /**
     * @var bool
     */
    protected $nowait = false;

    /**
     * @var AMQPTable|array
     */
    protected $arguments = [];

    /**
     * @var null|int
     */
    protected $ticket;

    public function isPassive(): bool
    {
        return $this->passive;
    }

    public function setPassive(bool $passive): self
    {
        $this->passive = $passive;
        return $this;
    }

    public function isDurable(): bool
    {
        return $this->durable;
    }

    public function setDurable(bool $durable): self
    {
        $this->durable = $durable;
        return $this;
    }

    public function isAutoDelete(): bool
    {
        return $this->autoDelete;
    }

    public function setAutoDelete(bool $autoDelete): self
    {
        $this->autoDelete = $autoDelete;
        return $this;
    }

    public function isNowait(): bool
    {
        return $this->nowait;
    }

    public function setNowait(bool $nowait): self
    {
        $this->nowait = $nowait;
        return $this;
    }

    /**
     * @return AMQPTable|array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @param AMQPTable|array $arguments
     */
    public function setArguments($arguments): self
    {
        $this->arguments = $arguments;
        return $this;
    }

    /**
     * @return null|int
     */
    public function getTicket()
    {
        return $this->ticket;
    }

    /**
     * @param null|int $ticket
     */
    public function setTicket($ticket): self
    {
        $this->ticket = $ticket;
        return $this;
    }
}
