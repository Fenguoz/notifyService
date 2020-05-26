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
namespace Hyperf\Amqp;

use Hyperf\Amqp\Pool\PoolFactory;
use Hyperf\Contract\StdoutLoggerInterface;
use Psr\Container\ContainerInterface;

class ConsumerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new Consumer($container, $container->get(PoolFactory::class), $container->get(StdoutLoggerInterface::class));
    }
}
