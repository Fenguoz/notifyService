<?php

namespace App\Rpc;

interface NotifyServiceInterface
{   
    public function send(string $notifyDriver, array $config, array $params);

    public function sendBatch(string $notifyDriver, array $config, array $params);
}
