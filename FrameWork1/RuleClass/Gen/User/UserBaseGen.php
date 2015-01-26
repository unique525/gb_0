<?php

/**
 * 基类 会员 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_User
 * @author zhangchi
 */
class UserBaseGen {

    /**
     * 会员注册 重复的会员账号
     */
    const REGISTER_ERROR_REPEAT_USER_NAME = -1;

    /**
     * 会员注册 重复的会员邮箱
     */
    const REGISTER_ERROR_REPEAT_USER_EMAIL = -2;

    /**
     * 会员注册 重复的手机号码
     */
    const REGISTER_ERROR_REPEAT_USER_MOBILE = -3;

    /**
     * 会员注册 参数错误
     */
    const REGISTER_ERROR_PARAMETER = -4;

    /**
     * 会员注册 注册失败
     */
    const REGISTER_ERROR_FAIL_REGISTER = -5;

    /**
     * 会员注册 格式错误
     */
    const REGISTER_ERROR_FORMAT = -6;

    /**
     * 会员注册 注册成功
     */
    const REGISTER_SUCCESS = 1;

    /**
     * 会员注册 没有重复的帐号
     */
    const REGISTER_NO_REPEAT = 0;

    /**
     * 会员登录 非法参数
     */
    const LOGIN_ILLEGAL_PARAMETER = -2;

    /**
     * 会员登录 账号密码错误
     */
    const LOGIN_ERROR_USER_PASS = -1;

    /**
     * 会员登录 成功
     */
    const LOGIN_SUCCESS = 1;
} 