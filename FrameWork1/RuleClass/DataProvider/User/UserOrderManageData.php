<?php
/**
 * 后台管理 会员订单 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * Time: 下午12:09
 */
class UserOrderManageData extends BaseManageData{
    public function GetList($siteId,$pageBegin,$pageSize,&$allCount){
        $sql = "SELECT * FROM ".self::TableName_UserOrder." WHERE SiteId = :SiteId ORDER BY CreateDate DESC LIMIT ".$pageBegin.",".$pageSize.";";
        $sqlCount = "SELECT count(*) FROM ".self::TableName_UserOrder." WHERE SiteId = :SiteId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("SiteId",$siteId);
        $result = $this->dbOperator->GetArrayList($sql,$dataProperty);
        $allCount = $this->dbOperator->GetInt($sqlCount,$dataProperty);
        return $result;
    }
}