<?php

declare(strict_types=1);

namespace App\Model;

class Notify extends BaseModel
{
    protected $table = 'notify';

    protected $fillable = [
        'content',
        'type',
        'type_id',
        'target_id',
        'sender_id',
        'sender_type',
        'is_read',
        'receiver_id',
        'receiver_type',
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
        $query = $this->query()->select($this->table . '.id', $this->table . '.content', $this->table . '.type', $this->table . '.type_id', $this->table . '.target_id', $this->table . '.receiver_id', $this->table . '.receiver_type', $this->table . '.sender_id', $this->table . '.sender_type', $this->table . '.is_read');
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
