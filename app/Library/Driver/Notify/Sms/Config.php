<?php

declare(strict_types=1);

return [
    'sign' => 'required|string',
    'url' => 'required|url',
    'method' => 'required|in:get,post',
    'key' => 'required|string',
    'dtype' => 'required|in:json,xml',
];
