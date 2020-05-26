<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

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
     * @Message("操作成功")
     */
    const SUCCESS = 200;

    /**
     * @Message("Internal Server Error!")
     */
    const ERR_SERVER = 500;

    /**
     * @Message("Http Error")
     */
    const GUZZLE_HTTP = 10000;

    /**
     * @Message("设备暂不支持")
     */
    const DEVICE_NOT_SUPPORT = 10001;

    /**
     * @Message("请选择文章")
     */
    const ARTICLE_ID_EMPTY = 10002;

    /**
     * @Message("文章已不存在")
     */
    const ARTICLE_NOT_EXIST = 10003;

    /**
     * @Message("暂无数据")
     */
    const NO_DATA = 10004;

    /**
     * @Message("网络繁忙，请稍后再试")
     */
    const NETWORD_ERROR = 10005;

    /**
     * @Message("更新失败")
     */
    const UPDATE_ERROR = 10006;

    /**
     * @Message("暂未开放，敬请期待")
     */
    const NOT_OPEN = 10007;

    /**
     * @Message("参数错误")
     */
    const PARAMS_ERROR = 10008;

    /**
     * @Message("数据已不存在")
     */
    const DATA_NOT_EXIST = 10009;

    /**
     * @Message("删除失败")
     */
    const DELETE_ERROR = 10010;

    /**
     * @Message("添加失败")
     */
    const ADD_ERROR = 10011;

    /**
     * @Message("请先登陆")
     */
    const AUTHORIZATION_EMPTY = 11000;

    /**
     * @Message("请选择使用场景")
     */
    const SCENE_EMPTY = 12000;


    /**
     * @Message("请输入手机号")
     */
    const MOBILE_EMPTY = 20000;

    /**
     * @Message("请输入密码")
     */
    const PASSWORD_EMPTY = 20001;

    /**
     * @Message("两次输入的密码不一致")
     */
    const ENTERED_PASSWORDS_DIFFER = 20002;

    /**
     * @Message("请输入验证码")
     */
    const VCODE_EMPTY = 20003;

    /**
     * @Message("验证码错误")
     */
    const VCODE_ERROR = 20004;

    /**
     * @Message("邀请码错误")
     */
    const CODE_ERROR = 20005;

    /**
     * @Message("该账号已存在")
     */
    const USER_ALREADY_EXIST = 20006;

    /**
     * @Message("账号或密码错误")
     */
    const ACCOUNT_OR_PASSWORD_ERROR = 20007;

    /**
     * @Message("该账号不存在")
     */
    const USER_NOT_EXIST = 20008;

    /**
     * @Message("请选择币种")
     */
    const COIN_ID_EMPTY = 50000;

    /**
     * @Message("请输入交易数量")
     */
    const EXCAHNGE_NUMBER_EMPTY = 50001;

    /**
     * @Message("剩余数量不足")
     */
    const COIN_NUMBER_ENOUGH = 50002;

    /**
     * @Message("请输入地址")
     */
    const ADDRESS_EMPTY = 50003;

    /**
     * @Message("请输入正确地址")
     */
    const ADDRESS_ERROR = 50004;

    /**
     * @Message("请输入正确币种数量")
     */
    const COIN_AMOUNT_ERROR = 50005;

    /**
     * @Message("请输入币种数量")
     */
    const COIN_AMOUNT_EMPTY = 50006;

    /**
     * @Message("请选择正确币种来源")
     */
    const COIN_FROM_TYPE_ERROR = 50007;

    /**
     * @Message("该账号不存在")
     */
    const ACCOUNT_NOT_EXIST = 50008;

    /**
     * @Message("请选择账单")
     */
    const BILL_ID_EMPTY = 51000;

    /**
     * @Message("请输入交易密码")
     */
    const PAY_PASSWORD_EMPTY = 52000;

    /**
     * @Message("请先设置交易密码")
     */
    const PAY_PASSWORD_NULL = 52001;

    /**
     * @Message("请输入正确交易密码")
     */
    const PAY_PASSWORD_ERROR = 52002;

    /**
     * @Message("请勿重复支付")
     */
    const REPEAT_PAY = 52003;

    /**
     * @Message("剩余数量不足")
     */
    const LACK_OF_BALANCE = 52004;



}
