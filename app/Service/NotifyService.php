<?php

declare(strict_types=1);

namespace App\Service;

use App\Amqp\Producer\RemindProducer;
use App\Amqp\Producer\TopicProducer;
use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Model\Notify;
use Hyperf\Amqp\Producer;
use Hyperf\Di\Annotation\Inject;

class NotifyService extends BaseService
{
    /**
     * @Inject
     * @var Notify
     */
    protected $notify;

    /**
     * @Inject
     * @var Producer
     */
    protected $producer;

    /**
     * @Inject
     * @var SubscriptionTypeService
     */
    protected $subscriptionTypeService;

    /**
     * @Inject
     * @var SubscriptionService
     */
    protected $subscriptionService;

    /**
     * getList
     * 条件获取广告位列表
     * @param array $where 查询条件
     * @param array $order 排序条件
     * @param int $page 页数
     * @param int $limit 条数
     * @return mixed
     */
    public function getList($where = [], $order = ['id' => 'DESC'], $page = 1, $limit = 10)
    {
        return $this->notify->getList($where, $order, $page, $limit);
    }

    /**
     * add
     * 添加
     * @param array $data 数据
     * @return bool
     */
    public function add(array $data): bool
    {
        if (!$data) throw new BusinessException(ErrorCode::PARAMS_ERROR);

        $result = $this->notify->create($data);
        if (!$result) throw new BusinessException(ErrorCode::ADD_ERROR);
        return true;
    }

    /**
     * delete
     * 删除
     * @param int $id 主键ID
     * @return bool
     */
    public function delete(int $id): bool
    {
        if ($id <= 0) throw new BusinessException(ErrorCode::PARAMS_ERROR);

        $info = $this->notify->find($id);
        if (!$info) throw new BusinessException(ErrorCode::DATA_NOT_EXIST);

        if (!$this->notify->delete()) throw new BusinessException(ErrorCode::DELETE_ERROR);
        return true;
    }

    /**
     * send
     * 发送
     * @param int $sender_id 发送者id
     * @param string $sender_type 发送者类型
     * @param int $receiver_id 接收者id
     * @param string $receiver_type 接收者类型
     * @param int $target_id 目标id
     * @param int $type_id 类型(类似模版id)
     * @param array $ext_param 扩展参数(类似模板参数)
     * @return mixed
     */
    public function send(int $sender_id, string $sender_type, int $receiver_id, string $receiver_type, int $target_id = 0, int $type_id, array $ext_param = [])
    {
        $type = $this->subscriptionTypeService->getInfoById($type_id);
        if (!$type) throw new BusinessException(ErrorCode::DATA_NOT_EXIST);

        //是否订阅
        if ($sender_type == 'user' && $target_id > 0) { //用户需要订阅事件
            //指定某些类型判断是否订阅（待定）
            $is_subscribe = $this->subscriptionService->isSubscribe($sender_id, $sender_type, $target_id, $type->parent_id);
            if (!$is_subscribe) {
                $this->subscriptionService->subscribing($sender_id, $sender_type, $target_id, $type->parent_id, 'event');
            }
        }
        if ($sender_type == 'admin') { //管理员需要订阅话题 订阅话题由后台设置

        }

        $data = [
            'type_id' => $type_id,
            'target_id' => $target_id,
            'sender_id' => $sender_id,
            'sender_type' => $sender_type,
            'receiver_id' => $receiver_id,
            'receiver_type' => $receiver_type,
            'ext_param' => $ext_param,
        ];

        //加入消息队列
        $this->producer->produce(new TopicProducer(json_encode($data), 'topic.' . $type->routing_key, 'topic.' . $type->module));
        $this->producer->produce(new RemindProducer(json_encode($data), 'remind.' . $type->routing_key, 'remind.' . $type->module));
    }

    public function read(int $id): bool
    {
        if ($id <= 0) throw new BusinessException(ErrorCode::PARAMS_ERROR);

        return $this->notify
            ->where('id', $id)
            ->update([
                'is_read' => 1
            ]) ? true : false;
    }
}
