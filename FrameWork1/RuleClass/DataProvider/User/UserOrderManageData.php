<?php
/**
 * 后台管理 会员订单 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * Time: 下午12:09
 */
class UserOrderManageData extends BaseManageData{

    public function GetFields($tableName = self::TableName_UserOrder){
        return parent::GetFields(self::TableName_UserOrder);
    }


    /**
     *
     * 为线下订单新增
     * @param string $userOrderNumber
     * @param string $createDate
     * @param int $userId
     * @param float $allPrice
     * @param int $userOrderState
     * @param int $siteId
     * @param int $userOrderTableType
     * @return int
     */
    public function CreateForOfflineOrder($userOrderNumber,$createDate,$userId,$allPrice,$userOrderState,$siteId,$userOrderTableType){
        $result=-1;
        if($userOrderNumber!=""&&$userId>0&&$siteId>0){
            $sql="INSERT INTO ".self::TableName_UserOrder." (UserOrderNumber,CreateDate,UserId,AllPrice,State,SiteId,UserOrderTableType) VALUES (:UserOrderNumber,:CreateDate,:UserId,:AllPrice,:State,:SiteId,:UserOrderTableType) ";

            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserOrderNumber",$userOrderNumber);
            $dataProperty->AddField("CreateDate",$createDate);
            $dataProperty->AddField("UserId",$userId);
            $dataProperty->AddField("AllPrice",$allPrice);
            $dataProperty->AddField("State",$userOrderState);
            $dataProperty->AddField("SiteId",$siteId);
            $dataProperty->AddField("UserOrderTableType",$userOrderTableType);
            $result = $this->dbOperator->Execute($sql,$dataProperty);
        }


        return $result;
    }
    /**
     * 修改
     * @param array $httpPostData $_POST数组
     * @param int $userOrderId 会员订单Id
     * @param int $siteId 站点Id
     * @return int 影响的行数
     */
    public function Modify($httpPostData,$userOrderId,$siteId){
        $result = -1;
        if($userOrderId > 0 && $siteId > 0 && !empty($httpPostData)){
            $dataProperty = new DataProperty();
            $sql = parent::GetUpdateSql($httpPostData,self::TableName_UserOrder,self::TableId_UserOrder,$userOrderId,$dataProperty,"SiteId",$siteId);
            $result = $this->dbOperator->Execute($sql,$dataProperty);
        }
        return $result;
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
        $result = null;
        if($siteId > 0){
            $sql = "SELECT uo.*,u.UserName,u.UserMobile FROM ".self::TableName_UserOrder." uo LEFT JOIN "
                .self::TableName_User." u ON uo.UserId = u.UserId WHERE uo.SiteId = :SiteId
                 ORDER BY PayDate DESC,CreateDate DESC LIMIT ".$pageBegin.",".$pageSize.";";
            $sqlCount = "SELECT count(*) FROM ".self::TableName_UserOrder." uo LEFT JOIN "
                .self::TableName_User." u ON uo.UserId = u.UserId WHERE uo.SiteId = :SiteId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId",$siteId);
            $result = $this->dbOperator->GetArrayList($sql,$dataProperty);
            $allCount = $this->dbOperator->GetInt($sqlCount,$dataProperty);
        }
        return $result;
    }

    public function GetListForSearch($siteId,$userOrderNumber,$state,$beginDate,$endDate,$pageBegin,$pageSize,&$allCount,$searchKey=""){
        $result = null;
        if($siteId > 0){
            $sql = "SELECT uo.*,u.UserName,u.UserMobile FROM ".self::TableName_UserOrder." uo LEFT JOIN ".self::TableName_User
                ." u ON uo.UserId = u.UserId WHERE uo.SiteId = :SiteId ";
            $sqlCount = "SELECT count(*) FROM ".self::TableName_UserOrder." uo WHERE uo.SiteId = :SiteId ";

            $dataProperty = new DataProperty();

            $addSql = "";
            if ($beginDate !="" || $endDate != "") {
                if ($beginDate !="" && $endDate == "") {
                    $addSql = " AND uo.CreateDate > date(:BeginDate) ";
                    $dataProperty->AddField("BeginDate", $beginDate);
                } else  if($beginDate =="" && $endDate != ""){
                    $addSql = " AND uo.CreateDate < data(:EndDate) ";
                    $dataProperty->AddField("EndDate", $endDate);
                }else  if($beginDate !="" && $endDate != ""){
                    $addSql = " AND uo.CreateDate > date(:BeginDate) AND uo.CreateDate < date(:EndDate) ";
                    $dataProperty->AddField("BeginDate", $beginDate);
                    $dataProperty->AddField("EndDate", $endDate);
                }
                $sql = $sql . $addSql;
                $sqlCount = $sqlCount . $addSql;
            }

            if ($userOrderNumber != "") {
                $addSql = " AND uo.UserOrderNumber LIKE :UserOrderNumber";
                $dataProperty->AddField("UserOrderNumber", "%" . $userOrderNumber . "%");
                $sql = $sql . $addSql;
                $sqlCount = $sqlCount . $addSql;
            }

            if ($state != "") {
                if($state == 10 || $state == 0){
                    $addSql = " AND (uo.State = :State1 OR uo.State = :State2) ";
                    $dataProperty->AddField("State1", "0");
                    $dataProperty->AddField("State2", "10");
                }else{
                    $addSql = " AND uo.State = :State";
                    $dataProperty->AddField("State", $state);
                }
                $sql = $sql . $addSql;
                $sqlCount = $sqlCount . $addSql;
            }

            if ($searchKey != "") {
                    $addSql = " AND (u.UserName='$searchKey' OR u.UserMobile='$searchKey' OR u.UserEmail='$searchKey') ";
                $sql = $sql . $addSql;
                $sqlCount = $sqlCount . $addSql;
            }

            $sql = $sql . " ORDER BY uo.CreateDate DESC LIMIT " . $pageBegin . "," . $pageSize;
            $dataProperty->AddField("SiteId",$siteId);

            $result = $this->dbOperator->GetArrayList($sql,$dataProperty);
            $allCount = $this->dbOperator->GetInt($sqlCount,$dataProperty);
        }
        return $result;
    }

    /**
     * 取得订单状态
     * @param int $userOrderId 订单id
     * @param bool $withCache 是否从缓冲中取
     * @return string 订单状态
     */
    public function GetState($userOrderId, $withCache)
    {
        $result = -1;
        if ($userOrderId > 0) {
            $cacheDir = UserOrderData::GetCachePath($userOrderId);
            $cacheFile = 'user_order_get_state.cache_' . $userOrderId . '';
            $sql = "SELECT State FROM " . self::TableName_UserOrder . " WHERE UserOrderId=:UserOrderId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserOrderId", $userOrderId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
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
            $sql = "SELECT uo.*,uri.ReceivePersonName,uri.Address,uri.Mobile FROM ".self::TableName_UserOrder." uo LEFT OUTER JOIN ".self::TableName_UserReceiveInfo." uri ON uo.UserReceiveInfoId = uri.UserReceiveInfoId WHERE uo.UserOrderId = :UserOrderId AND uo.SiteId = :SiteId; ";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserOrderId",$userOrderId);
            $dataProperty->AddField("SiteId",$siteId);
            $result = $this->dbOperator->GetArray($sql,$dataProperty);
        }
        return $result;
    }

    /**
     * 查询会员订单的总价
     * @param int $userOrderId 会员订单Id
     * @return float|int 会员订单的总价
     */
    public function GetAllPrice($userOrderId){
        $result = -1;
        if($userOrderId > 0){
            $sql = "SELECT AllPrice FROM ".self::TableName_UserOrder." WHERE UserOrderId = :UserOrderId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserOrderId",$userOrderId);
            $result = $this->dbOperator->GetFloat($sql,$dataProperty);
        }
        return $result;
    }


    /**
     * 取得订单状态
     * @param int $userOrderId 订单id
     * @param bool $withCache 是否从缓冲中取
     * @return string 订单状态
     */
    public function GetSiteId($userOrderId, $withCache)
    {
        $result = -1;
        if ($userOrderId > 0) {
            $cacheDir = UserOrderData::GetCachePath($userOrderId);
            $cacheFile = 'user_order_get_site_id.cache_' . $userOrderId . '';
            $sql = "SELECT SiteId FROM " . self::TableName_UserOrder . " WHERE UserOrderId=:UserOrderId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserOrderId", $userOrderId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }

        return $result;
    }

    /**
     * 取得订单状态
     * @param int $userOrderId 订单id
     * @param bool $withCache 是否从缓冲中取
     * @return string 订单状态
     */
    public function GetUserReceiveInfoId($userOrderId, $withCache)
    {
        $result = -1;
        if ($userOrderId > 0) {
            $cacheDir = UserOrderData::GetCachePath($userOrderId);
            $cacheFile = 'user_order_get_user_receive_info_id.cache_' . $userOrderId . '';
            $sql = "SELECT UserReceiveInfoId FROM " . self::TableName_UserOrder . " WHERE UserOrderId=:UserOrderId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserOrderId", $userOrderId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }

        return $result;
    }

    public function GetListForExportExcel($beginDate,$endDate,$siteId){
        $result = null;
        if($siteId > 0 && $beginDate < $endDate){
            $dataProperty = new DataProperty();
            $sql = "SELECT
                            uop1.UserOrderId,
                            uo.CreateDate,
                            p.ProductId,
                            p.ProductName,
                            uop1.ProductPrice,
                            uop1.SaleCount,
                            uop1.SubTotal,
                            uop2.PayPrice,
                            uo.State,
                            p.ProductTag,
                            u.UserName,
                            uri.ReceivePersonName,
                            uri.Mobile,
                            uri.District,
                            uri.Address,
                            uop2.CreateDate AS PayDate
                        FROM
                            ".self::TableName_UserOrderProduct." uop1
                            LEFT JOIN ".self::TableName_UserOrder." uo ON uo.UserOrderId = uop1.UserOrderId
                            LEFT JOIN ".self::TableName_User." u ON uo.UserId = u.UserId
                            LEFT JOIN ".self::TableName_UserReceiveInfo." uri ON uo.UserReceiveInfoId = uri.UserReceiveInfoId
                            LEFT JOIN ".self::TableName_UserOrderPay." uop2 ON uo.UserOrderId = uop2.UserOrderId
                            LEFT JOIN ".self::TableName_Product." p ON uop1.ProductId = p.ProductId
                        WHERE
                            uo.CreateDate > date(:BeginDate) AND uo.CreateDate < date(:EndDate)
                        GROUP BY
                            uop1.UserOrderProductId;";
            $dataProperty->AddField("BeginDate",$beginDate);
            $dataProperty->AddField("EndDate",$endDate);
            $result = $this->dbOperator->GetArrayList($sql,$dataProperty);
        }
        return $result;
    }

    /**
     * 检查是否已存在订单
     * @param $userId
     * @param $userOrderType
     * @return int
     */
    public function CheckRepeatForOfflineOrder($userId,$userOrderType=1){  //1:电子报类型订单
        $result = -1;
        if($userId > 0){
            $dataProperty = new DataProperty();
            $sql = "SELECT UserOrderId FROM ".self::TableName_UserOrder." WHERE UserId=:UserId AND UserOrderType=:UserOrderType FOR UPDATE;";
            $dataProperty->AddField("UserId",$userId);
            $dataProperty->AddField("UserOrderType",$userOrderType);
            $result = $this->dbOperator->GetInt($sql,$dataProperty);
        }
        return $result;
    }
}