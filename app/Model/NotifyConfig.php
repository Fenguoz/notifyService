<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\Database\Model\SoftDeletes;
/**
 * @property string $code 
 * @property string $name 
 * @property string $desc 
 * @property string $config 
 * @property int $status 
 * @property int $sort 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class NotifyConfig extends Model
{
    // use SoftDeletes;
    // protected $primaryKey = 'code';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notify_config';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['code', 'name', 'desc', 'config', 'status', 'sort'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['status' => 'integer', 'sort' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
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
        $query = $this->query()->select($this->table . '.code', $this->table . '.name', $this->table . '.desc', $this->table . '.config', $this->table . '.status', $this->table . '.sort', $this->table . '.created_at');
        // 循环增加查询条件
        foreach ($where as $k => $v) {
            if ($k === 'name') {
                $query = $query->where($this->table . '.' . $k, 'LIKE', '%' . $v . '%');
                continue;
            }
            if ($k === 'start_time') {
                $query = $query->where($this->table . '.created_at', '>', $v . ' 00:00:00');
                continue;
            }
            if ($k === 'end_time') {
                $query = $query->where($this->table . '.created_at', '<', $v . ' 23:59:59');
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
            if ($k === 'start_time') {
                $query = $query->where($this->table . '.created_at', '>', $v . ' 00:00:00');
                continue;
            }
            if ($k === 'end_time') {
                $query = $query->where($this->table . '.created_at', '<', $v . ' 23:59:59');
                continue;
            }
            $query = $query->where($this->table . '.' . $k, $v);
        }
        $query = $query->count();
        return $query > 0 ? $query : 0;
    }
    public static function getConfigByCode(string $code) : array
    {
        $config = self::query()->where('code', $code)->value('config');
        return $config ? json_decode($config, true) : [];
    }
    public static function getStatusByCode(string $code) : int
    {
        return self::query()->where('code', $code)->value('status') ?? 0;
    }
    public static function isExistCode(string $code) : bool
    {
        return self::query()->where('code', $code)->exists();
    }
}