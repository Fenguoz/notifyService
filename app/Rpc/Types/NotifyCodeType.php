<?php

namespace App\Rpc\Types;

final class NotifyCodeType
{
    const null = 0;
    const Sms = 1;
    const Email = 2;
    const GeTui = 3;
    const Mail = 4;
    const Wechat = 5;
    static public $__names = array(
        0 => null,
        1 => 'Sms',
        2 => 'Email',
        3 => 'GeTui',
        4 => 'Mail',
        5 => 'Wechat',
    );
}
