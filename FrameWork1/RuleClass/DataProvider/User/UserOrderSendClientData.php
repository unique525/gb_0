<?php

/**
 * 客户端 会员订单发货信息 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author zhangchi
 */
class UserOrderSendClientData extends BaseClientData {

    /**
     * 取得会员订单发货信息列表
     * @param int $userOrderId 会员订单id
     * @param int $userId 会员id
     * @return array|null 会员订单发货信息列表
     */
    public function GetList($userOrderId, $userId){
        $result = null;
        if($userOrderId > 0){
            $sql = "SELECT uos.*
                        FROM ".self::TableName_UserOrderSend." uos,".self::TableName_UserOrder." uo
                        WHERE uos.UserOrderId = :UserOrderId

                        AND uo.UserId = :UserId

                        ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserOrderId",$userOrderId);
            $dataProperty->AddField("UserId",$userId);
            $result = $this->dbOperator->GetArrayList($sql,$dataProperty);
        }
        return $result;
    }
} 