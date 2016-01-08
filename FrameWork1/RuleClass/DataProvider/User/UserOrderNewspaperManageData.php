<?php
/**
 * 后台管理 会员订单 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * Time: 下午12:09
 */
class UserOrderNewspaperManageData extends BaseManageData{


    /**
     * @param $siteId
     * @param $channelId
     * @param $userOrderId
     * @param $userId
     * @param $createDate
     * @param $salePrice
     * @param $beginDate
     * @param $endDate
     * @return int
     */
    public function CreateForOfflineOrder($siteId,$channelId,$userOrderId,$userId,$createDate,$salePrice,$beginDate,$endDate){
        $result=-1;
        if($userId>0&&$siteId>0&&$channelId>0&&$userOrderId>0){
            $sql="INSERT INTO ".self::TableName_UserOrderNewspaper. "
                    (SiteId,ChannelId,UserOrderId,UserId,CreateDate,SalePrice,BeginDate,EndDate)
             VALUES (:SiteId,:ChannelId,:UserOrderId,:UserId,:CreateDate,:SalePrice,:BeginDate,:EndDate);";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId",$siteId);
            $dataProperty->AddField("ChannelId",$channelId);
            $dataProperty->AddField("UserOrderId",$userOrderId);
            $dataProperty->AddField("UserId",$userId);
            $dataProperty->AddField("CreateDate",$createDate);
            $dataProperty->AddField("SalePrice",$salePrice);
            $dataProperty->AddField("BeginDate",$beginDate);
            $dataProperty->AddField("EndDate",$endDate);
            $result = $this->dbOperator->Execute($sql,$dataProperty);
        }


        return $result;
    }

    /**
     * 检查是否已存在订单
     * @param $userId
     * @param $userOrderId
     * @return int
     */
    public function CheckRepeatForOfflineOrder($userId,$userOrderId){
        $result=-1;
        if($userId>0&&$userOrderId>0){
            $sql="SELECT UserOrderNewspaperId FROM ".self::TableName_UserOrderNewspaper. " WHERE UserId=:UserId AND UserOrderId=:UserOrderId FOR UPDATE;";

            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId",$userId);
            $dataProperty->AddField("UserOrderId",$userOrderId);
            $result = $this->dbOperator->GetInt($sql,$dataProperty);

        }
        return $result;
    }


    /**
     * 获取用户的所有订单
     * @param $userId
     * @return array
     */
    public function GetOrderOfUser($userId){
        $result=array();
        if($userId>0){
            $sql="SELECT * FROM ".self::TableName_UserOrderNewspaper. " WHERE UserId=:UserId;";

            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId",$userId);
            $result = $this->dbOperator->GetArrayList($sql,$dataProperty);

        }
        return $result;
    }



    /**
     * 获取结束时间
     * @param $userOrderNewspaperId
     * @return array
     */
    public function GetInfoForUpdate($userOrderNewspaperId){
        $result=array();
        if($userOrderNewspaperId>0){
            $sql="SELECT EndDate,SalePrice FROM ".self::TableName_UserOrderNewspaper. " WHERE UserOrderNewspaperId=:UserOrderNewspaperId FOR UPDATE;";

            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserOrderNewspaperId",$userOrderNewspaperId);
            $result = $this->dbOperator->GetArray($sql,$dataProperty);

        }
        return $result;
    }


    /**
     *
     * 续期
     * @param $userOrderNewspaperId
     * @param $userOrderId
     * @param $salePrice
     * @param $endDate
     * @return int
     */
    public function UpdateOrderForRenewal($userOrderNewspaperId,$userOrderId,$salePrice,$endDate){
        $result=-1;
        if($userOrderNewspaperId>0){
            $sql="UPDATE ".self::TableName_UserOrderNewspaper. " SET SalePrice=$salePrice,EndDate='$endDate',UserOrderId=$userOrderId WHERE UserOrderNewspaperId=:UserOrderNewspaperId;";
$debug=new DebugLogManageData();
            $debug->Create($sql);

            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserOrderNewspaperId",$userOrderNewspaperId);
            $result = $this->dbOperator->Execute($sql,$dataProperty);

        }
        return $result;
    }

} 