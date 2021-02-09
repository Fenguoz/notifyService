<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model;
/**
 * @property int $id 
 * @property string $name 
 * @property int $parent_id 
 * @property string $module 
 * @property string $action 
 * @property string $routing_key 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class Action extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'action';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'parent_id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}