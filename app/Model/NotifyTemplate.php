<?php

declare(strict_types=1);

namespace App\Model;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id 
 * @property int $notify_code 
 * @property int $template_id 
 * @property int $action_id 
 * @property int $is_vaild 
 * @property string $vaild_column 
 */
class NotifyTemplate extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notify_template';
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
    protected $casts = ['id' => 'integer', 'notify_code' => 'integer', 'template_id' => 'integer', 'action_id' => 'integer', 'is_vaild' => 'integer'];

    public function template()
    {
        return $this->hasOne(Template::class, 'id', 'template_id');
    }

    public static function getTemplate(string $notifyCode, int $actionId)
    {
        $template = self::query()->where('notify_code', $notifyCode)
            ->where('action_id', $actionId)
            ->first();
        if (!$template)
            throw new BusinessException(ErrorCode::DATA_NOT_EXIST);
        return $template->template;
    }
}
