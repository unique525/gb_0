<?php
/**
 * 后台管理 会员订单发货信息 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author yin
 */
class UserOrderSendManageData extends BaseManageData{

    /**
     * @param int $userOrderId 会员订单Id
     * @param int $siteId 站点Id
     * @return array|null 会员所有的收货信息
     */
    public function GetList($userOrderId,$siteId){
        $result = null;
        if($userId > 0){
            $sql = "SELECT * FROM ".self::TableName_UserOrderSend." WHERE UserOrderId = :UserOrderId AND SiteId = :SiteId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserOrderId",$userOrderId);
            $dataProperty->AddField("SiteId",$siteId);
            $result = $this->dbOperator->GetArrayList($sql,$dataProperty);
        }
        return $result;
    }
}