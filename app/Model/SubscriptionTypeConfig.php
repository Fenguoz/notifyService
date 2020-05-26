<?php

declare(strict_types=1);

namespace App\Model;

class SubscriptionTypeConfig extends BaseModel
{
    protected $table = 'subscription_type_config';

    protected $fillable = [
        'name',
        'user_type',
        'type_id',
        'relational',
    ];

    /**
     * getList
     * 获取列表
     * @param array $where 查询条件
     * @param array $order 排序条件
     * @param int $page 页数
     * @param int $limit 条数
     * @return array
     */
    public function getList($where = [], $order = [], $page = 1, $limit = 10)
    {
        $query = $this->query()->select($this->table . '.id', $this->table . '.name', $this->table . '.user_type', $this->table . '.type_id', $this->table . '.relational');
        // 循环增加查询条件
        foreach ($where as $k => $v) {
            if ($v || $v != null) {
                $query = $query->where($this->table . '.' . $k, $v);
            }
        }
        // 追加排序
        if ($order && is_array($order)) {
            foreach ($order as $k => $v) {
                $query = $query->orderBy($this->table . '.' . $k, $v);
            }
        }
        
        return $query->paginate($limit, ['*'], 'page', $page);
    }
}
