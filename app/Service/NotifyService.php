<?php

namespace App\Service;

use App\Amqp\Producer\RemindProducer;
use App\Amqp\Producer\TopicProducer;
use App\Constants\ErrorCode;
use App\Constants\Notify\Notify;
use App\Exception\BusinessException;
use App\Model\NotifyAction;
use App\Model\NotifyConfig;
use App\Model\NotifyTemplateConfig;
use App\Rpc\NotifyServiceInterface;
use Driver\Notify\NotifyException;
use Hyperf\RpcServer\Annotation\RpcService;
use OpenApi\Annotations\JsonContent;
use OpenApi\Annotations\Parameter;
use OpenApi\Annotations\Post;
use OpenApi\Annotations\Response;

/**
 * @RpcService(name="NotifyService", protocol="jsonrpc-http", server="jsonrpc-http", publishTo="consul")
 */
class NotifyService extends BaseService implements NotifyServiceInterface
{
    /**
     * @Post(
     *     path="/notify/send",
     *     operationId="send",
     *     tags={"NotifyService"},
     *     summary="发送消息/通知",
     *     description="发送消息/通知",
     *     @Parameter(ref="#/components/parameters/code"),
     *     @Parameter(ref="#/components/parameters/action"),
     *     @Parameter(ref="#/components/parameters/params"),
     *     @Response(
     *         response=200,
     *         description="SUCCESS",
     *         @JsonContent(ref="#/components/schemas/success")
     *     )
     * )
     */
    public function send(int $code, string $action, array $params)
    {
        list($module, $action) = explode('.', $action);
        $action_id = NotifyAction::where('module', $module)
            ->where('action', $action)
            ->value('id');
        if (!$action_id) {
            return $this->error(ErrorCode::DATA_NOT_EXIST);
        }

        $notifyCode = Notify::$__names[$code];
        try {
            $this->notify
                ->setAdapter($notifyCode, $params)
                ->setConfig(NotifyConfig::getConfigByCode($notifyCode))
                ->setTemplate(NotifyTemplateConfig::getTemplate($notifyCode, (int)$action_id))
                ->templateValue()
                ->send();
        } catch (BusinessException $e) {
            return $this->error($e->getCode());
        } catch (NotifyException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }
        return $this->success();
    }

    /**
     * @Post(
     *     path="/notify/sendBatch",
     *     operationId="sendBatch",
     *     tags={"NotifyService"},
     *     summary="批量发送消息/通知",
     *     description="批量发送消息/通知",
     *     @Parameter(ref="#/components/parameters/code"),
     *     @Parameter(ref="#/components/parameters/action"),
     *     @Parameter(ref="#/components/parameters/params"),
     *     @Response(
     *         response=200,
     *         description="SUCCESS",
     *         @JsonContent(ref="#/components/schemas/success")
     *     )
     * )
     */
    public function sendBatch(int $code, string $action, array $params)
    {
        list($module, $action) = explode('.', $action);
        $action_id = NotifyAction::where('module', $module)
            ->where('action', $action)
            ->value('id');
        if (!$action_id) {
            return $this->error(ErrorCode::DATA_NOT_EXIST);
        }

        $notifyCode = Notify::$__names[$code];
        try {
            $this->notify
                ->setAdapter($notifyCode, $params)
                ->setConfig(NotifyConfig::getConfigByCode($notifyCode))
                ->setTemplate(NotifyTemplateConfig::getTemplate($notifyCode, (int)$action_id))
                ->batchTemplateValue()
                ->sendBatch();
        } catch (BusinessException $e) {
            return $this->error($e->getCode());
        } catch (NotifyException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }
        return $this->success();
    }

    /**
     * @Post(
     *     path="/notify/queue",
     *     operationId="queue",
     *     tags={"NotifyService"},
     *     summary="投放消息队列",
     *     description="投放消息队列",
     *     @Parameter(ref="#/components/parameters/action"),
     *     @Parameter(ref="#/components/parameters/params"),
     *     @Parameter(ref="#/components/parameters/sort"),
     *     @Response(
     *         response=200,
     *         description="SUCCESS",
     *         @JsonContent(ref="#/components/schemas/success")
     *     )
     * )
     */
    public function queue(string $key, array $params, int $sort = 100)
    {
        list($module, $action) = explode('.', $key);
        $info = NotifyAction::where('module', $module)
            ->where('action', $action)
            ->first();
        if (!$info) {
            return $this->error(ErrorCode::DATA_NOT_EXIST);
        }

        //加入消息队列
        $this->producer->produce(
            new TopicProducer(
                json_encode($params),
                'topic.' . $key,
                'topic.' . $info->module
            )
        );
        $this->producer->produce(
            new RemindProducer(
                json_encode($params),
                'remind.' . $key,
                'remind.' . $info->module
            )
        );
        return $this->success();
    }
}
