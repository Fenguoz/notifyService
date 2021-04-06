<?php

namespace App\Service;

use App\Constants\ErrorCode;
use App\Constants\Notify\Notify;
use App\Rpc\NotifyServiceInterface;
use Driver\Notify\NotifyException;
use Hyperf\RpcServer\Annotation\RpcService;

/**
 * @RpcService(name="NotifyService", protocol="jsonrpc-http", server="jsonrpc-http", publishTo="consul")
 */
class NotifyService extends BaseService implements NotifyServiceInterface
{

    /**
     * Send a single notification message
     *
     * @param string    $notifyDriver   Notify driver
     * @param array     $config         Notify config
     * @param array     $params         Parameter data
     * @return bool
     */
    public function send(string $notifyDriver, array $config, array $params)
    {
        if (!in_array($notifyDriver, Notify::$__names)) {
            return $this->error(ErrorCode::DATA_NOT_EXIST);
        }

        try {
            $this->notify->setAdapter($notifyDriver, $params)->setConfig($config)->send();
        } catch (NotifyException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }
        return $this->success();
    }

    /**
     * Send multiple notification messages
     *
     * @param string    $notifyDriver   Notify driver
     * @param array     $config         Notify config
     * @param array     $params         Parameter data
     * @return bool
     */
    public function sendBatch(string $notifyDriver, array $config, array $params)
    {
        if (!in_array($notifyDriver, Notify::$__names)) {
            return $this->error(ErrorCode::DATA_NOT_EXIST);
        }

        try {
            $this->notify->setAdapter($notifyDriver, $params)->setConfig($config)->sendBatch();
        } catch (NotifyException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }
        return $this->success();
    }
}
