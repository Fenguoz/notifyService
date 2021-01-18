<?php

namespace App\Rpc;

interface NotifyServiceInterface
{
    public function send(int $code, string $action, array $params);

    public function sendBatch(int $code, string $action, array $params);

    public function queue(string $action, array $params, int $sort = 100);
}
