<?php

declare (strict_types=1);
namespace App\Model;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
/**
 * @property int $id 
 * @property string $notify_code 
 * @property int $template_id 
 * @property int $action_id 
 * @property string $created_at 
 * @property string $updated_at 
 * @property-read \App\Model\NotifyTemplate $template
 */
class NotifyTemplateConfig extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notify_template_config';
    /**
     * Add nullable creation and update timestamps to the table.
     *
     * @param int $precision
     */
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['notify_code', 'template_id', 'action_id'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'template_id' => 'integer', 'action_id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
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
        $query = $this->query()->select($this->table . '.id', $this->table . '.notify_code', $this->table . '.template_id', $this->table . '.action_id', $this->table . '.created_at');
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
            $query = $query->where($this->table . '.' . $k, $v);
        }
        $query = $query->count();
        return $query > 0 ? $query : 0;
    }
    public function template()
    {
        return $this->hasOne(NotifyTemplate::class, 'id', 'template_id');
    }
    public static function getTemplate(string $notifyCode, int $actionId)
    {
        $template = self::query()->where('notify_code', $notifyCode)->where('action_id', $actionId)->first();
        if (!$template) {
            throw new BusinessException(ErrorCode::DATA_NOT_EXIST);
        }
        return $template->template;
    }
}