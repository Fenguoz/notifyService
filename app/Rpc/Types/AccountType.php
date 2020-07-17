<?php

namespace App\Rpc\Types;

final class AccountType
{
    const PHONE_NUMBER_ACCOUNT = 1;
    const EMAIL_ACCOUNT = 2;
    const USERNAME_ACCOUNT = 3;
    static public $__names = array(
        1 => 'phone_number',
        2 => 'email',
        3 => 'username',
    );
}
