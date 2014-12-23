<?php

/**
 * 前台管理 会员发货信息 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * Time: 下午12:09
 */
class UserOrderSendPublicData extends BasePublicData
{
    /**
     * @param $userOrderId
     * @return array|null
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