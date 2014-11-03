<?php

/**
 * 后台管理 会员订单 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * Time: 下午12:09
 */
class UserOrderPublicData extends BasePublicData
{

    public function Create($httpPostData, $siteId)
    {
        $result = -1;
        if (!empty($httpPostData) && $siteId > 0) {
            $sql = "";
        }
        return $result;
    }

    public function Modify($httpPostData, $userOrderId, $userId)
    {
        $result = -1;
        if (!empty($httpPostData) && $userOrderId > 0 && $userId > 0) {
            $sql = "";
        }
        return $result;
    }

    public function GetList($userId, $siteId, $state, $pageBegin, $pageSize, &$allCount)
    {
        $result = null;
        if ($userId > 0 && $siteId > 0) {
            $sql = "SELECT * FROM " . self::TableName_UserOrder
                . " WHERE UserId = :UserId AND SiteId = :SiteId AND State = :State LIMIT " . $pageBegin . "," . $pageSize . ";";
            $sqlCount = "SELECT count(*) FROM " . self::TableName_UserOrder
                . " WHERE UserId = :UserId AND SiteId = :SiteId AND State = :State;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId", $userId);
            $dataProperty->AddField("SiteId", $siteId);
            $dataProperty->AddField("State", $state);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
            $allCount = $this->dbOperator->GetInt($sqlCount, $dataProperty);
        }
        return $result;
    }

    /**
     * 获取一个订单的详细信息
     * @param int $userOrderId 订单Id
     * @param int $siteId 站点Id
     * @return array|null 一个会员订单的数组
     */
    public function GetOne($userOrderId,$siteId){
        $result = null;
        if($userOrderId > 0 && $siteId > 0){
            $sql = "SELECT uo.*,uri.ReceivePersonName,uri.Address,uri.Mobile FROM ".self::TableName_UserOrder." uo,".self::TableName_UserReceiveInfo." uri WHERE uo.UserOrderId = :UserOrderId AND uo.SiteId = :SiteId AND uo.UserReceiveInfoId = uri.UserReceiveInfoId; ";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserOrderId",$userOrderId);
            $dataProperty->AddField("SiteId",$siteId);
            $result = $this->dbOperator->GetArray($sql,$dataProperty);
        }
        return $result;
    }

    public function GetUserOrderCountByState($userId, $siteId, $state)
    {
        $result = -1;
        if ($userId > 0 && $siteId > 0) {
            $sql = "SELECT count(*) FROM " . self::TableName_UserOrder . " WHERE UserId = :UserId AND SiteId = :SiteId AND State = :State;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId", $userId);
            $dataProperty->AddField("SiteId", $siteId);
            $dataProperty->AddField("State", $state);
            $result = $this->dbOperator->GetInt($sql, $dataProperty);
        }
        return $result;
    }

}