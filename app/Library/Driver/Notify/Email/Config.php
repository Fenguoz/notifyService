<?php

declare(strict_types=1);

return [
    'user' => 'required|string',
    'key' => 'required|string',
    'url' => 'required|url',
    'method' => 'required|in:get,post',
    'from' => 'required|email',
];
