<?php

declare(strict_types=1);

use Hyperf\Crontab\Crontab;

return [
    'enable' => true,
    // 通过配置文件定义的定时任务
    'crontab' => [
        // Callback类型定时任务（默认）
        (new Crontab())->setName('AppNotify')->setRule('* * * * *')->setCallback([App\Task\AppNotify::class, 'execute'])->setMemo('App消息推送'),
    ],
];
