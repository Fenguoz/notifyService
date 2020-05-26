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
namespace Hyperf\Redis;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                \Redis::class => Redis::class,
            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
                'ignore_annotations' => [
                    'mixin',
                ],
            ],
            'publish' => [
                [
                    'id' => 'config',
                    'description' => 'The config of redis client.',
                    'source' => __DIR__ . '/../publish/redis.php',
                    'destination' => BASE_PATH . '/config/autoload/redis.php',
                ],
            ],
        ];
    }
}
