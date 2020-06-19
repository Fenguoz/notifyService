<?php

declare(strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Model\Model;

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
class Notify extends Model
{
    protected $primaryKey = 'code';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notify';
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
    protected $casts = ['status' => 'integer', 'sort' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    public static function getConfigByCode(string $code): array
    {
        $config = self::query()->where('code', $code)->value('config');
        return $config ? json_decode($config, true) : [];
    }
    
    public static function getStatusByCode(string $code): int
    {
        return self::query()->where('code', $code)->value('status') ?? 0;
    }
}
