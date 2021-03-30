<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Model;

use Hyperf\DbConnection\Model\Model as BaseModel;

abstract class Model extends BaseModel
{

    /**
     * getPagesInfo
     * 
     * @param $where
     * @return array
     */
    public function getPagesInfo($where = [], $count = 10, $page = 1)
    {
        $total = $this->getCount($where);
        $totalPage = $total == 0 ? 0 : ceil($total / $count);
        return [
            'page' => (int)$page,
            'count' => (int)$count,
            'total' => (int)$total,
            'total_page' => (int)$totalPage,
        ];
    }

    /**
     * getCount
     * 
     * @param array $where
     * @return int
     */
    public function getCount($where = [])
    {
        $instance = make(get_called_class());

        foreach ($where as $k => $v) {
            if ($k === 'title') {
                $instance = $instance->where($k, 'LIKE', '%' . $v . '%');
                continue;
            }
            $instance = $instance->where($k, $v);
        }

        $count = $instance->count();

        return $count > 0 ? $count : 0;
    }
}
