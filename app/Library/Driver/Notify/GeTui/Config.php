<?php

declare(strict_types=1);

return [
    'app_id' => 'required|string',
    'app_key' => 'required|string',
    'app_secret' => 'required|string',
    'master_secret' => 'required|string',
    'logo' => 'required|url',
    'is_ring' => 'required|boolean',
    'is_vibrate' => 'required|boolean',
    'is_clearable' => 'required|boolean',
    'is_offline' => 'required|boolean',
    'offline_expire_time' => 'required|integer'
];