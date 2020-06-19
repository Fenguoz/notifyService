<?php

namespace App\Rpc;

interface NotifyServiceInterface
{
    public function send(int $code, int $action, array $params);

    public function sendBatch(int $code, int $action, array $params);

    public function queue(int $action, array $params, int $sort = 100);

    public function getActionByModule(string $module);

    public function getActionIdByModuleAction(string $module, string $action);

    public function getActionInfoByActionId(int $action_id);
}
