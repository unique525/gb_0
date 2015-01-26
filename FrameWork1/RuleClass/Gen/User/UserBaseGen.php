<?php

/**
 * 基类 会员 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_User
 * @author zhangchi
 */
class UserBaseGen {

    /**
     * 重复的用户账号
     */
    const REGISTER_ERROR_REPEAT_USER_NAME = -1;

    /**
     * 重复的用户邮箱
     */
    const REGISTER_ERROR_REPEAT_USER_EMAIL = -2;

    /**
     * 重复的手机号码
     */
    const REGISTER_ERROR_REPEAT_USER_MOBILE = -3;

    /**
     * 参数错误
     */
    const REGISTER_ERROR_PARAMETER = -4;

    /**
     * 注册失败
     */
    const REGISTER_ERROR_FAIL_REGISTER = -5;

    /**
     * 格式错误
     */
    const REGISTER_ERROR_FORMAT = -6;

    /**
     * 注册成功
     */
    const REGISTER_SUCCESS = 1;

    /**
     * 没有重复的
     */
    const REGISTER_NO_REPEAT = 0;

    /**
     * 非法参数
     */
    const LOGIN_ILLEGAL_PARAMETER = -2;

    /**
     * 账号密码错误
     */
    const LOGIN_ERROR_USER_PASS = -1;
} 