<?php

namespace App\Service;

use App\Amqp\Producer\RemindProducer;
use App\Amqp\Producer\TopicProducer;
use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Model\Action;
use App\Model\Notify;
use App\Model\NotifyTemplate;
use App\Model\Queue;
use App\Rpc\Types\NotifyCodeType;
use App\Rpc\NotifyServiceInterface;
use Hyperf\RpcServer\Annotation\RpcService;
use Hyperf\Di\Annotation\Inject;

/**
 * @RpcService(name="NotifyService", protocol="jsonrpc-http", server="jsonrpc-http", publishTo="consul")
 */
class NotifyService extends BaseService implements NotifyServiceInterface
{
    public function send(int $code, int $action, array $params)
    {
        if (!Action::find($action)) return $this->error(ErrorCode::ACTION_EMPTY);
        try {
            $this->notify->set_adapter(NotifyCodeType::$__names[$code], $params)->setConfig(Notify::getConfigByCode(NotifyCodeType::$__names[$code]))->setTemplate($this->getTemplate($code, $action))->templateValue()->send();
        } catch (BusinessException $e) {
            return $this->error($e->getCode());
        }
        return $this->success();
    }

    public function sendBatch(int $code, int $action, array $params)
    {
        if (!Action::find($action)) return $this->error(ErrorCode::ACTION_EMPTY);
        try {
            $this->notify->set_adapter(NotifyCodeType::$__names[$code], $params)->setConfig(Notify::getConfigByCode(NotifyCodeType::$__names[$code]))->setTemplate($this->getTemplate($code, $action))->batchTemplateValue()->sendBatch();
        } catch (BusinessException $e) {
            return $this->error($e->getCode());
        }
        return $this->success();
    }

    private function getTemplate(int $code, int $action)
    {
        $notify_template = NotifyTemplate::query()->where('notify_code', NotifyCodeType::$__names[$code])->where('action_id', $action)->first();
        if (!$notify_template) throw new BusinessException(ErrorCode::DATA_NOT_EXIST);
        return $notify_template->template;
    }

    public function queue(int $action, array $params, int $sort = 100)
    {
        $action = Action::find($action);
        if (!$action) return $this->error(ErrorCode::ACTION_EMPTY);

        //加入消息队列
        $this->producer->produce(new TopicProducer(json_encode($params), 'topic.' . $action->routing_key, 'topic.' . $action->module));
        $this->producer->produce(new RemindProducer(json_encode($params), 'remind.' . $action->routing_key, 'remind.' . $action->module));
        return $this->success();
    }

    public function getActionByModule(string $module)
    {
        $data = Action::select('id', 'name', 'action', 'parent_id')->where('module', $module)->get();
        return $this->success($data);
    }

    public function getActionIdByModuleAction(string $module, string $action)
    {
        $id = Action::where(['module' => $module, 'action' => $action])->value('id');
        if (!$id)  return $this->error(ErrorCode::DATA_NOT_EXIST);
        return $this->success($id);
    }

    public function getActionInfoByActionId(int $action_id)
    {
        $info = Action::select('id', 'name', 'action', 'parent_id')->find($action_id);
        if (!$info)  return $this->error(ErrorCode::DATA_NOT_EXIST);
        return $this->success($info);
    }
}
