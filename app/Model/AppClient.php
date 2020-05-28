<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AppClient extends BaseModel
{
    public $table = 'app_client';

    protected $fillable = [
        'id',
        'user_id',
        'user_type',
        'client_id'
    ];

    protected $casts = [
        'id' => 'int',
        'user_id' => 'int',
        'user_type' => 'string',
        'client_id' => 'string',
    ];
}
