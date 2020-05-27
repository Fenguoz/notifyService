<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Setting extends BaseModel
{
    public $table = 'setting';

    public function getList($where = [])
    {
        $query = $this->query()->select($this->table . '.k', $this->table . '.v', $this->table . '.remark', $this->table . '.module');
        // 循环增加查询条件
        foreach ($where as $k => $v) {
            if ($v || $v != null) {
                $query = $query->where($this->table . '.' . $k, $v);
            }
        }
        
        $query = $query->pluck('v','k');
        return $query ? $query->all() : [];
    }

    public function getValueByKey(string $key)
    {
        return $this->query()->where('k', $key)->value('v');
    }

    public function getListByModule(string $module)
    {
        return $this->getList(['module' => $module]);
    }
}
