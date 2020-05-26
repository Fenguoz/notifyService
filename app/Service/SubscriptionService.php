<?php

declare(strict_types=1);

namespace App\Service;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Model\Subscription;
use App\Model\SubscriptionTypeConfig;
use Hyperf\Di\Annotation\Inject;

class SubscriptionService extends BaseService
{
    /**
     * @Inject
     * @var Subscription
     */
    protected $subscription;

    /**
     * @Inject
     * @var SubscriptionTypeConfig
     */
    protected $subscriptionTypeConfig;

    /**
     * getList
     * 条件获取广告位列表
     * @param array $where 查询条件
     * @param array $order 排序条件
     * @param int $page 页数
     * @param int $limit 条数
     * @return mixed
     */
    public function getList($where = [], $order = ['id' => 'DESC'], $page = 0, $limit = 0): array
    {
        return $this->subscription->getList($where, $order, $page, $limit);
    }

    /**
     * subscribing
     * 订阅
     * @param int $user_id 用户ID
     * @param string $user_type 角色（user,admin）
     * @param int $target_id 目标的ID(比如文章ID)
     * @param string $type 消息的所属者(比如文章的作者)
     * @param int $type_id 订阅类型id
     * @return bool
     */
    public function subscribing(int $user_id, string $user_type, int $target_id = 0, int $type_id, string $type = 'event'): bool
    {
        if (!in_array($type, ['topic', 'event'])) throw new BusinessException(ErrorCode::PARAMS_ERROR);
        if (!in_array($user_type, ['user', 'admin'])) throw new BusinessException(ErrorCode::PARAMS_ERROR);

        $relational = $this->subscriptionTypeConfig
            ->where([
                'user_type' => $user_type,
                'type_id' => $type_id,
            ])
            ->value('relational');
        if (!$relational) throw new BusinessException(ErrorCode::DATA_NOT_EXIST);

        $data = $this->subscription->create([
            'target_id' => $target_id,
            'user_id' => $user_id,
            'type_id' => $type_id,
            'user_type' => $user_type,
            'type' => $type,
            'relational' => $relational,
        ]);
        if (!$data) throw new BusinessException(ErrorCode::ADD_ERROR);

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

        $info = $this->subscription->find($id);
        if (!$info) throw new BusinessException(ErrorCode::DATA_NOT_EXIST);

        if (!$this->subscription->delete()) throw new BusinessException(ErrorCode::DELETE_ERROR);
        return true;
    }

    /**
     * isSubscribe
     * 是否订阅
     * @param int $user_id 用户ID
     * @param string $user_type 角色（user,admin）
     * @param int $target_id 目标的ID(比如文章ID)
     * @return bool
     */
    public function isSubscribe(int $user_id, string $user_type, int $target_id, int $type_id): bool
    {
        //多种场景主键ID重复问题待开发
        return $this->subscription->where([
            'user_id' => $user_id,
            'user_type' => $user_type,
            'target_id' => $target_id,
            'type_id' => $type_id,
        ])->first() ? true : false;
    }
}
