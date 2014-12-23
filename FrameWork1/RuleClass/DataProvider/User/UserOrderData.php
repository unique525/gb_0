<?php

/**
 * 公共 会员订单 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author yin
 */
class UserOrderData extends BaseData {

    /**
     * 状态：新建
     */
    const STATE_NEW = 0;
    /**
     * 状态：未付款
     */
    const STATE_NON_PAYMENT = 10;
    /**
     * 状态：货到付款
     */
    const STATE_PAYMENT_AFTER_RECEIVE = 15;
    /**
     * 状态：已付款，未发货
     */
    const STATE_PAYMENT = 20;
    /**
     * 状态：已发货
     */
    const STATE_SENT = 25;
    /**
     * 状态：交易完成
     */
    const STATE_DONE = 30;
    /**
     * 状态：交易完成，已评价
     */
    const STATE_COMMENT_FINISHED = 31;
    /**
     * 状态：交易关闭
     */
    const STATE_CLOSE = 40;
    /**
     * 状态：申请退款
     */
    const STATE_APPLY_REFUND = 50;
    /**
     * 状态：退款完成
     */
    const STATE_REFUND_FINISHED = 55;
    /**
     * 状态：未评价
     */
    const STATE_UNCOMMENT = 70;
    /**
     * 状态：已删
     */
    const STATE_REMOVED = 100;


    const USER_ORDER_DES_KEY = "SUDK2014";

    /**
     * 生成订单号
     */
    public static function GenUserOrderNumber(){

        $date = strval(date('YmdHis', time()));

        return strtoupper($date.md5(strval(uniqid())));

    }

} 