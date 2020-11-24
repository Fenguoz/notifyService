<?php

namespace App\Rpc\Types;

final class ActionType
{
    const REGISTER = 'public.register';
    const LOGIN = 'public.login';
    const FORGET_PASSWORD = 'public.forget_password';
    const FORGET_PAY_PASSWORD = 'public.forget_pay_password';
    const SET_PAY_PASSWORD = 'public.set_pay_password';
    const CHANGE_PHONE_NUMBER = 'public.change_phone_number';
    const PG_SUCCESS = 'order.pg_success';
    const PG_FAIL = 'order.pg_fail';
    static public $__names = array(
        'public.register' => 'REGISTER',
        'public.login' => 'LOGIN',
        'public.forget_password' => 'FORGET_PASSWORD',
        'public.forget_pay_password' => 'FORGET_PAY_PASSWORD',
        'public.set_pay_password' => 'SET_PAY_PASSWORD',
        'public.change_phone_number' => 'CHANGE_PHONE_NUMBER',
        'order.pg_success' => 'PG_SUCCESS',
        'order.pg_fail' => 'PG_FAIL',
    );
}
