<?php

declare(strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id 
 * @property int $notify_code 
 * @property int $action_id 
 * @property string $params 
 * @property int $user_id 
 * @property string $user_type 
 * @property int $status 
 * @property int $sort 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class Queue extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'queue';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['notify_code', 'action_id', 'params', 'user_id', 'user_type', 'status', 'sort'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'notify_code' => 'string', 'action_id' => 'integer', 'user_id' => 'integer', 'status' => 'integer', 'sort' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}
