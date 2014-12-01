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
     * 状态：已付款，未发货
     */
    const STATE_PAYMENT = 20;



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