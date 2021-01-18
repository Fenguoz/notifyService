<?php

namespace App\Service;

use App\Amqp\Producer\RemindProducer;
use App\Amqp\Producer\TopicProducer;
use App\Constants\ErrorCode;
use App\Constants\Notify\Notify;
use App\Exception\BusinessException;
use App\Model\Action;
use App\Model\Notify as ModelNotify;
use App\Model\NotifyTemplate;
use App\Rpc\NotifyServiceInterface;
use Driver\Notify\NotifyException;
use Hyperf\RpcServer\Annotation\RpcService;

/**
 * @RpcService(name="NotifyService", protocol="jsonrpc-http", server="jsonrpc-http", publishTo="consul")
 */
class NotifyService extends BaseService implements NotifyServiceInterface
{
    public function send(int $code, string $action, array $params)
    {
        list($module, $action) = explode('.', $action);
        $action_id = Action::where('module', $module)
            ->where('action', $action)
            ->value('id');
        if (!$action_id)
            return $this->error(ErrorCode::DATA_NOT_EXIST);

        $notifyCode = Notify::$__names[$code];
        try {
            $this->notify
                ->setAdapter($notifyCode, $params)
                ->setConfig(ModelNotify::getConfigByCode($notifyCode))
                ->setTemplate(NotifyTemplate::getTemplate($notifyCode, (int)$action_id))
                ->templateValue()
                ->send();
        } catch (BusinessException $e) {
            return $this->error($e->getCode());
        } catch (NotifyException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }
        return $this->success();
    }

    public function sendBatch(int $code, string $action, array $params)
    {
        list($module, $action) = explode('.', $action);
        $action_id = Action::where('module', $module)
            ->where('action', $action)
            ->value('id');
        if (!$action_id)
            return $this->error(ErrorCode::DATA_NOT_EXIST);

        $notifyCode = Notify::$__names[$code];
        try {
            $this->notify
                ->setAdapter($notifyCode, $params)
                ->setConfig(ModelNotify::getConfigByCode($notifyCode))
                ->setTemplate(NotifyTemplate::getTemplate($notifyCode, (int)$action_id))
                ->batchTemplateValue()
                ->sendBatch();
        } catch (BusinessException $e) {
            return $this->error($e->getCode());
        } catch (NotifyException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }
        return $this->success();
    }

    public function queue(string $action, array $params, int $sort = 100)
    {
        list($module, $action) = explode('.', $action);
        $info = Action::where('module', $module)
            ->where('action', $action)
            ->first();
        if (!$info)
            return $this->error(ErrorCode::DATA_NOT_EXIST);

        //加入消息队列
        $this->producer->produce(
            new TopicProducer(
                json_encode($params),
                'topic.' . $info->routing_key,
                'topic.' . $info->module
            )
        );
        $this->producer->produce(
            new RemindProducer(
                json_encode($params),
                'remind.' . $info->routing_key,
                'remind.' . $info->module
            )
        );
        return $this->success();
    }
}
