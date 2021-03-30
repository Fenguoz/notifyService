<?php

declare (strict_types=1);
namespace App\Model;

/**
 * @property int $id 
 * @property string $name 
 * @property int $parent_id 
 * @property string $module 
 * @property string $action 
 * @property string $created_at 
 * @property string $updated_at 
 */
class NotifyAction extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notify_action';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'parent_id', 'module', 'action'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'parent_id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
    /**
     * getList
     * 
     * @param array $where 查询条件
     * @param array $order 排序条件
     * @param int $pageSize 页条数
     * @param int $currentPage 页数
     * @return array
     */
    public function getList($where = [], $order = [], $pageSize = 0, $currentPage = 1)
    {
        $query = $this->query()->select($this->table . '.id', $this->table . '.name', $this->table . '.parent_id', $this->table . '.module', $this->table . '.action', $this->table . '.created_at');
        // 循环增加查询条件
        foreach ($where as $k => $v) {
            if ($k === 'name') {
                $query = $query->where($this->table . '.' . $k, 'LIKE', '%' . $v . '%');
                continue;
            }
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
        // 是否分页
        if ($pageSize) {
            $offset = ($currentPage - 1) * $pageSize;
            $query = $query->offset($offset)->limit($pageSize);
        }
        return $query->get();
    }
    /**
     * getCount
     * 
     * @param array $where
     * @return int
     */
    public function getCount($where = [])
    {
        $query = $this->query();
        foreach ($where as $k => $v) {
            if ($k === 'name') {
                $query = $query->where($this->table . '.' . $k, 'LIKE', '%' . $v . '%');
                continue;
            }
            $query = $query->where($this->table . '.' . $k, $v);
        }
        $query = $query->count();
        return $query > 0 ? $query : 0;
    }
}