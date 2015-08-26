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
     * 会员订单的业务类型 产品
     */
    const USER_ORDER_TABLE_TYPE_PRODUCT = 0;
    /**
     * 会员订单的业务类型 电子报
     */
    const USER_ORDER_TABLE_TYPE_NEWSPAPER = 1;


    /**
     * 生成订单号
     */
    public static function GenUserOrderNumber(){
        $date = strval(date('YmdHi', time()));
        return strtoupper($date.strval(uniqid()));
    }

    /**
     * 返回缓存文件目录
     * @param mixed $id 业务表id或缓存关键字段
     * @return string 缓存文件目录
     */
    public static function GetCachePath($id){
        return CACHE_PATH . '/user_order_data/' . DataCache::GetSubPath($id);
    }

} 