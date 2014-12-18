<?php
/**
 * 后台管理 会员订单发货信息 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author yin
 */
class UserOrderSendManageData extends BaseManageData{

    public function Create($userOrderId,$acceptPersonName,$acceptAddress,$acceptTel,$acceptTime,$sendCompany){
        $result = -1;
        if($userOrderId > 0 && !empty($acceptPersonName) && !empty($acceptTel)){
            $sql = "INSERT INTO ".self::TableName_UserOrderSend." (UserOrderId,AcceptPersonName,AcceptAddress,AcceptTel,AcceptTime,SendCompany)
                VALUES (:UserOrderId,:AcceptPersonName,:AcceptAddress,:AcceptTel,:AcceptTime,:SendCompany);";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserOrderId",$userOrderId);
            $dataProperty->AddField("AcceptPersonName",$acceptPersonName);
            $dataProperty->AddField("AcceptAddress",$acceptAddress);
            $dataProperty->AddField("AcceptTel",$acceptTel);
            $dataProperty->AddField("AcceptTime",$acceptTime);
            $dataProperty->AddField("SendCompany",$sendCompany);

            $result = $this->dbOperator->LastInsertId($sql,$dataProperty);
        }
        return $result;
    }

    /**
     * @param int $userOrderId 会员订单Id
     * @return array|null 会员所有的收货信息
     */
    public function GetList($userOrderId){
        $result = null;
        if($userOrderId > 0){
            $sql = "SELECT * FROM ".self::TableName_UserOrderSend." WHERE UserOrderId = :UserOrderId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserOrderId",$userOrderId);
            $result = $this->dbOperator->GetArrayList($sql,$dataProperty);
        }
        return $result;
    }
}