<?php

declare(strict_types=1);

namespace App\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

/**
 * @Constants
 * 自定义错误码规范如下：
 * 其他-公共，10***
 * 其他-授权，11***
 * 其他-消息，12***
 * 用户，2****
 * 商品，3****
 * 订单，4****
 * 钱包，5****
 * 钱包-账单，51***
 * 钱包-支付，52***
 * 业务，6****
 */
class ErrorCode extends AbstractConstants
{
    /**
     * @Message("SUCCESS")
     */
    const SUCCESS = 200;

    /**
     * @Message("ERR_SERVER")
     */
    const ERR_SERVER = 500;

    /**
     * @Message("FILE_NOT_FOUND")
     */
    const FILE_NOT_FOUND = 10000;

    /**
     * @Message("ADD_ERROR")
     */
    const ADD_ERROR = 10001;

    /**
     * @Message("DATA_NOT_EXIST")
     */
    const DATA_NOT_EXIST = 10002;

    /**
     * @Message("ADAPTER_EMPTY")
     */
    const ADAPTER_EMPTY = 12000;

    /**
     * @Message("ADAPTER_TURN_OFF")
     */
    const ADAPTER_TURN_OFF = 12001;

    /**
     * @Message("ACTION_EMPTY")
     */
    const ACTION_EMPTY = 12002;
}
