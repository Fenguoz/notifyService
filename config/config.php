<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

use Hyperf\Contract\StdoutLoggerInterface;
use Psr\Log\LogLevel;

return [
    'app_name' => env('APP_NAME', 'skeleton'),
    StdoutLoggerInterface::class => [
        'log_level' => [
            LogLevel::ALERT,
            LogLevel::CRITICAL,
            LogLevel::DEBUG,
            LogLevel::EMERGENCY,
            LogLevel::ERROR,
            LogLevel::INFO,
            LogLevel::NOTICE,
            LogLevel::WARNING,
        ],
    ],
    'oauth_url' => env('OAUTH_URL', 'http://micro.zgf.8kpay.com:10000'),
    'user_url' => env('USER_URL', 'http://micro.zgf.8kpay.com:10001'),
    'goods_url' => env('GOODS_URL', 'http://micro.zgf.8kpay.com:10002'),
    'wallet_url' => env('WALLET_URL', 'http://micro.zgf.8kpay.com:10003'),
    'order_url' => env('ORDER_URL', 'http://micro.zgf.8kpay.com:10004'),
    'oauth_grant_type' => env('OAUTH_GRANT_TYPE', 'password'),
    'oauth_client_id' => env('OAUTH_CLIENT_ID', 1),
    'oauth_client_secret' => env('OAUTH_CLIENT_SECRET', 'Se3mV****6uiPb'),
    'client_token' => env('CLIENT_TOKEN', 'eyJ0eXAiOiJKV1Q****a1zhmZlcDSXujq6wCvHEf7ChMHZwGpY'),
];
