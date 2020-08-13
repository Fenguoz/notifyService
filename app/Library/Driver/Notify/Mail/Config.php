<?php

declare(strict_types=1);

return [
    'host' => 'required',
    'port' => 'required',
    'username' => 'required',
    'password' => 'required',
    'send_mail' => 'required|email',
    'send_nickname' => 'required',
];
