<?php
/**
 * 后台管理 会员订单 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * Time: 下午12:09
 */
class UserOrderManageData extends BaseManageData{

    /**
     * 修改
     * @param array $httpPostData $_POST数组
     * @param int $userOrderId 会员订单Id
     * @param int $siteId 站点Id
     * @return int 影响的行数
     */
    public function Modify($httpPostData,$userOrderId,$siteId){
        if($userOrderId > 0 && $siteId > 0){
            $dataProperty = new DataProperty();
            $sql = parent::GetUpdateSql($httpPostData,self::TableName_UserOrder,self::TableId_UserOrder,$userOrderId,$dataProperty,"SiteId",$siteId);
            $result = $this->dbOperator->Execute($sql,$dataProperty);
            return $result;
        }else{
            return null;
        }
    }
    /**
     * 获取订单列表
     * @param int $siteId 站点Id
     * @param int $pageBegin 从pageBegin开始查询
     * @param int $pageSize 取pageSize条数据
     * @param int $allCount 所有行数
     * @return array 多个会员订单的数组
     */
    public function GetList($siteId,$pageBegin,$pageSize,&$allCount){
        if($siteId > 0){
            $sql = "SELECT uo.*,ui.NickName AS UserName FROM ".self::TableName_UserOrder." uo,".self::TableName_UserInfo." ui WHERE uo.UserId = ui.UserId AND SiteId = :SiteId ORDER BY CreateDate DESC LIMIT ".$pageBegin.",".$pageSize.";";
            $sqlCount = "SELECT count(*) FROM ".self::TableName_UserOrder." WHERE SiteId = :SiteId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId",$siteId);
            $result = $this->dbOperator->GetArrayList($sql,$dataProperty);
            $allCount = $this->dbOperator->GetInt($sqlCount,$dataProperty);
        return $result;
        }else{
            return null;
        }
    }

    /**
     * 获取一个订单的详细信息
     * @param int $userOrderId 订单Id
     * @param int $siteId 站点Id
     * @return array|null 一个会员订单的数组
     */
    public function GetOne($userOrderId,$siteId){
        if($userOrderId > 0 && $siteId > 0){
            $sql = "SELECT uo.*,uri.ReceivePersonName,uri.Address,uri.Mobile FROM ".self::TableName_UserOrder." uo,".self::TableName_UserReceiveInfo." uri WHERE uo.UserOrderId = :UserOrderId AND uo.SiteId = :SiteId AND uo.UserReceiveInfoId = uri.UserReceiveInfoId; ";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserOrderId",$userOrderId);
            $dataProperty->AddField("SiteId",$siteId);
            $result = $this->dbOperator->GetArray($sql,$dataProperty);
            return $result;
        }else{
            return null;
        }
    }
}