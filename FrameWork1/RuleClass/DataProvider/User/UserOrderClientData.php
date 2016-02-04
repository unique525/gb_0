<?php

/**
 * 客户端 会员订单 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author zhangchi
 */
class UserOrderClientData extends BaseClientData {

    /**
     * 新增订单
     * @param string $userOrderName
     * @param $userOrderNumber
     * @param $userOrderNumberDes
     * @param $userId
     * @param $userIdDes
     * @param $cookieId
     * @param $allPrice
     * @param $allPriceDes
     * @param $sendPrice
     * @param $userReceiveInfoId
     * @param $sendTime
     * @param $autoSendMessage
     * @param $siteId
     * @param $createDate
     * @param $createDateDes
     * @param $userOrderTableType
     * @return int
     */
    public function Create(
        $userOrderName,
        $userOrderNumber,
        $userOrderNumberDes,
        $userId,
        $userIdDes,
        $cookieId,
        $allPrice,
        $allPriceDes,
        $sendPrice,
        $userReceiveInfoId,
        $sendTime,
        $autoSendMessage,
        $siteId,
        $createDate,
        $createDateDes,
        $userOrderTableType = UserOrderData::USER_ORDER_TABLE_TYPE_PRODUCT
    )
    {
        $result = -1;
        if (
            strlen($userOrderNumber)>0 &&
            $userId>0 &&
            $siteId > 0
        ) {
            $sql = "INSERT INTO " . self::TableName_UserOrder ."

                    (
                    UserOrderName,
                    UserOrderNumber,
                    UserOrderNumberDes,
                    UserId,
                    UserIdDes,
                    CookieId,
                    AllPrice,
                    AllPriceDes,
                    SendPrice,
                    UserReceiveInfoId,
                    SendTime,
                    AutoSendMessage,
                    SiteId,
                    CreateDate,
                    CreateDateDes,
                    State,
                    UserOrderTableType
                    )
                    VALUES
                    (
                    :UserOrderName,
                    :UserOrderNumber,
                    :UserOrderNumberDes,
                    :UserId,
                    :UserIdDes,
                    :CookieId,
                    :AllPrice,
                    :AllPriceDes,
                    :SendPrice,
                    :UserReceiveInfoId,
                    :SendTime,
                    :AutoSendMessage,
                    :SiteId,
                    :CreateDate,
                    :CreateDateDes,
                    :State,
                    :UserOrderTableType
                    );
            ";

            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserOrderName",$userOrderName);
            $dataProperty->AddField("UserOrderNumber",$userOrderNumber);
            $dataProperty->AddField("UserOrderNumberDes",$userOrderNumberDes);
            $dataProperty->AddField("UserId",$userId);
            $dataProperty->AddField("UserIdDes",$userIdDes);
            $dataProperty->AddField("CookieId",$cookieId);
            $dataProperty->AddField("AllPrice",$allPrice);
            $dataProperty->AddField("AllPriceDes",$allPriceDes);
            $dataProperty->AddField("SendPrice",$sendPrice);
            $dataProperty->AddField("UserReceiveInfoId",$userReceiveInfoId);
            $dataProperty->AddField("SendTime",$sendTime);
            $dataProperty->AddField("AutoSendMessage",$autoSendMessage);
            $dataProperty->AddField("SiteId",$siteId);
            $dataProperty->AddField("CreateDate",$createDate);
            $dataProperty->AddField("CreateDateDes",$createDateDes);
            $dataProperty->AddField("State",UserOrderData::STATE_NON_PAYMENT);
            $dataProperty->AddField("UserOrderTableType",$userOrderTableType);
            $result = $this->dbOperator->LastInsertId($sql,$dataProperty);

        }
        return $result;
    }


    /**
     * 修改订单编号
     * @param int $userOrderId 订单id
     * @param string $userOrderNumber 订单编号
     * @param string $userOrderNumberDes 订单编号加密
     * @return int 操作结果
     */
    public function ModifyUserOrderNumber($userOrderId, $userOrderNumber, $userOrderNumberDes)
    {
        $result = 0;
        if ($userOrderId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_UserOrder . " SET `UserOrderNumber`=:UserOrderNumber,UserOrderNumberDes=:UserOrderNumberDes WHERE ".self::TableId_UserOrder."=:".self::TableId_UserOrder.";";
            $dataProperty->AddField(self::TableId_UserOrder, $userOrderId);
            $dataProperty->AddField("UserOrderNumber", $userOrderNumber);
            $dataProperty->AddField("UserOrderNumberDes", $userOrderNumberDes);
            $result = $this->dbOperator->Execute($sql, $dataProperty);

            $cacheDir = UserOrderData::GetCachePath($userOrderId);
            $cacheFile = 'user_order_get_user_order_number.cache_' . $userOrderId . '';
            DataCache::Remove($cacheDir . DIRECTORY_SEPARATOR . $cacheFile);
        }
        return $result;
    }

    /**
     * 修改状态，不带user id 支付接口使用
     * @param int $userOrderId 订单id
     * @param int $state 状态
     * @return int 操作结果
     */
    public function ModifyStateWithoutUserId($userOrderId,$state)
    {
        $result = 0;
        if ($userOrderId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_UserOrder . " SET `State`=:State WHERE ".self::TableId_UserOrder."=:".self::TableId_UserOrder.";";
            $dataProperty->AddField(self::TableId_UserOrder, $userOrderId);
            $dataProperty->AddField("State", $state);
            $result = $this->dbOperator->Execute($sql, $dataProperty);

            $cacheDir = UserOrderData::GetCachePath($userOrderId);
            $cacheFile = 'user_order_get_state.cache_' . $userOrderId . '';
            DataCache::Remove($cacheDir . DIRECTORY_SEPARATOR . $cacheFile);
        }
        return $result;
    }

    /**
     * 修改支付时间
     * @param int $userOrderId 订单id
     * @param int $payDate 支付时间
     * @return int 操作结果
     */
    public function ModifyPayDate($userOrderId, $payDate)
    {
        $result = 0;
        if ($userOrderId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_UserOrder . " SET `PayDate`=:PayDate WHERE ".self::TableId_UserOrder."=:".self::TableId_UserOrder.";";
            $dataProperty->AddField(self::TableId_UserOrder, $userOrderId);
            $dataProperty->AddField("PayDate", $payDate);
            $result = $this->dbOperator->Execute($sql, $dataProperty);

            $cacheDir = UserOrderData::GetCachePath($userOrderId);
            $cacheFile = 'user_order_get_pay_date.cache_' . $userOrderId . '';
            DataCache::Remove($cacheDir . DIRECTORY_SEPARATOR . $cacheFile);
        }
        return $result;
    }

    /**
     * @param $userId
     * @param $siteId
     * @param $pageBegin
     * @param $pageSize
     * @return array|null
     */
    public function GetList($userId, $siteId,  $pageBegin, $pageSize)
    {
        $result = null;
        if ($userId > 0 && $siteId > 0) {
            $sql = "SELECT
                        *,
                        ( SELECT count(*)
                            FROM ".self::TableName_UserOrderProduct."
                            WHERE UserOrderId=".self::TableName_UserOrder.".UserOrderId) AS ProductCount,

                        (
                            SELECT ProductId
                                FROM ".self::TableName_Product."
                                WHERE
                                    ProductId=
                                        (SELECT ProductId
                                            FROM ".self::TableName_UserOrderProduct."
                                            WHERE UserOrderId=".self::TableName_UserOrder.".UserOrderId
                                            ORDER BY UserOrderProductId
                                            LIMIT 1)
                        ) AS FirstProductId,

                        (
                            SELECT ProductName
                                FROM ".self::TableName_Product."
                                WHERE
                                    ProductId=
                                        (SELECT ProductId
                                            FROM ".self::TableName_UserOrderProduct."
                                            WHERE UserOrderId=".self::TableName_UserOrder.".UserOrderId
                                            ORDER BY UserOrderProductId
                                            LIMIT 1)
                        ) AS FirstProductName,

                        (
                            SELECT uf1.UploadFileMobilePath
                                FROM ".self::TableName_Product." p
                                LEFT OUTER JOIN " .self::TableName_UploadFile." uf1 on p.TitlePic1UploadFileId=uf1.UploadFileId
                                WHERE
                                    p.ProductId=
                                        (SELECT ProductId
                                            FROM ".self::TableName_UserOrderProduct."
                                            WHERE UserOrderId=".self::TableName_UserOrder.".UserOrderId
                                            ORDER BY UserOrderProductId
                                            LIMIT 1)
                        )  AS TitlePic1UploadFileMobilePath,

                        (
                            SELECT SaleCount
                                FROM ".self::TableName_UserOrderProduct."
                                WHERE UserOrderId=".self::TableName_UserOrder.".UserOrderId
                                ORDER BY UserOrderProductId
                                LIMIT 1
                        ) AS FirstProductSaleCount,

                        (
                            SELECT SalePrice
                                FROM ".self::TableName_UserOrderProduct."
                                WHERE UserOrderId=".self::TableName_UserOrder.".UserOrderId
                                ORDER BY UserOrderProductId
                                LIMIT 1
                        ) AS FirstProductSalePrice



                    FROM " . self::TableName_UserOrder
                . " WHERE UserId = :UserId AND SiteId = :SiteId ORDER BY CreateDate DESC LIMIT " . $pageBegin . "," . $pageSize . ";";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId", $userId);
            $dataProperty->AddField("SiteId", $siteId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }

    /**
     *
     * @param $userId
     * @param $siteId
     * @param $state
     * @param $pageBegin
     * @param $pageSize
     * @return array|null
     */
    public function GetListByState($userId, $siteId, $state, $pageBegin, $pageSize)
    {
        $result = null;
        if ($userId > 0 && $siteId > 0) {
            $sql = "SELECT
                        *,
                        ( SELECT count(*)
                            FROM ".self::TableName_UserOrderProduct."
                            WHERE UserOrderId=".self::TableName_UserOrder.".UserOrderId) AS ProductCount,

                        (
                            SELECT ProductId
                                FROM ".self::TableName_Product."
                                WHERE
                                    ProductId=
                                        (SELECT ProductId
                                            FROM ".self::TableName_UserOrderProduct."
                                            WHERE UserOrderId=".self::TableName_UserOrder.".UserOrderId
                                            ORDER BY UserOrderProductId
                                            LIMIT 1)
                        ) AS FirstProductId,

                        (
                            SELECT ProductName
                                FROM ".self::TableName_Product."
                                WHERE
                                    ProductId=
                                        (SELECT ProductId
                                            FROM ".self::TableName_UserOrderProduct."
                                            WHERE UserOrderId=".self::TableName_UserOrder.".UserOrderId
                                            ORDER BY UserOrderProductId
                                            LIMIT 1)
                        ) AS FirstProductName,

                        (
                            SELECT uf1.UploadFileMobilePath
                                FROM ".self::TableName_Product." p
                                LEFT OUTER JOIN " .self::TableName_UploadFile." uf1 on p.TitlePic1UploadFileId=uf1.UploadFileId
                                WHERE
                                    p.ProductId=
                                        (SELECT ProductId
                                            FROM ".self::TableName_UserOrderProduct."
                                            WHERE UserOrderId=".self::TableName_UserOrder.".UserOrderId
                                            ORDER BY UserOrderProductId
                                            LIMIT 1)
                        )  AS TitlePic1UploadFileMobilePath,

                        (
                            SELECT SaleCount
                                FROM ".self::TableName_UserOrderProduct."
                                WHERE UserOrderId=".self::TableName_UserOrder.".UserOrderId
                                ORDER BY UserOrderProductId
                                LIMIT 1
                        ) AS FirstProductSaleCount,

                        (
                            SELECT SalePrice
                                FROM ".self::TableName_UserOrderProduct."
                                WHERE UserOrderId=".self::TableName_UserOrder.".UserOrderId
                                ORDER BY UserOrderProductId
                                LIMIT 1
                        ) AS FirstProductSalePrice


                    FROM " . self::TableName_UserOrder
                . " WHERE UserId = :UserId AND SiteId = :SiteId AND State = :State ORDER BY CreateDate DESC LIMIT " . $pageBegin . "," . $pageSize . ";";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId", $userId);
            $dataProperty->AddField("SiteId", $siteId);
            $dataProperty->AddField("State", $state);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 获取一个订单的详细信息
     * @param int $userOrderId 订单Id
     * @param int $userId 用户Id
     * @param int $siteId 站点Id
     * @return array|null 一个会员订单的数组
     */
    public function GetOne($userOrderId,$userId,$siteId){
        $result = null;
        if($userOrderId > 0 && $userId > 0 && $siteId > 0){
            $sql = "SELECT
                        uo.*,
                        uri.ReceivePersonName,
                        uri.Address,
                        uri.Mobile,
                        uri.City,
                        uri.District
                    FROM " .self::TableName_UserOrder." uo

                    LEFT OUTER JOIN " .self::TableName_UserReceiveInfo." uri on uo.UserReceiveInfoId = uri.UserReceiveInfoId

                    WHERE
                        uo.UserOrderId = :UserOrderId
                    AND uo.UserId = :UserId
                    AND uo.SiteId = :SiteId ;
                    ";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserOrderId",$userOrderId);
            $dataProperty->AddField("UserId",$userId);
            $dataProperty->AddField("SiteId",$siteId);
            $result = $this->dbOperator->GetArray($sql,$dataProperty);
        }
        return $result;
    }

    /**
     * @param $userId
     * @param $siteId
     * @param $state
     * @return int
     */
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

    /**
     * 取得订单总价(不提供缓存)
     * @param int $userOrderId 订单id
     * @return float 订单总价
     */
    public function GetAllPrice($userOrderId){
        $result = -1;
        if($userOrderId>0){

            $sql = "SELECT AllPrice FROM " . self::TableName_UserOrder . " WHERE UserOrderId = :UserOrderId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserOrderId", $userOrderId);
            $result = $this->dbOperator->GetFloat($sql, $dataProperty);

        }

        return $result;
    }


    /**
     * 根据订单编号取得订单id
     * @param string $userOrderNumber 订单编号
     * @param bool $withCache 是否从缓冲中取
     * @return float 订单总价
     */
    public function GetUserOrderIdByUserOrderNumber($userOrderNumber, $withCache){
        $result =-1;
        if(strlen($userOrderNumber)>0){

            $cacheDir = UserOrderData::GetCachePath($userOrderNumber);
            $cacheFile = 'user_order_get_user_order_id_by_user_order_number.cache_' . $userOrderNumber . '';
            $sql = "SELECT UserOrderId FROM " . self::TableName_UserOrder . " WHERE UserOrderNumber=:UserOrderNumber;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserOrderNumber", $userOrderNumber);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);

        }

        return $result;
    }

    /**
     * 修改状态
     * @param int $userOrderId 订单id
     * @param int $state 状态
     * @return int 操作结果
     */
    public function ModifyState($userOrderId,$state)
    {
        $result = 0;
        if ($userOrderId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_UserOrder . " SET `State`=:State WHERE ".self::TableId_UserOrder."=:".self::TableId_UserOrder.";";
            $dataProperty->AddField(self::TableId_UserOrder, $userOrderId);
            $dataProperty->AddField("State", $state);
            $result = $this->dbOperator->Execute($sql, $dataProperty);

            $cacheDir = UserOrderData::GetCachePath($userOrderId);
            $cacheFile = 'user_order_get_state.cache_' . $userOrderId . '';
            DataCache::Remove($cacheDir . DIRECTORY_SEPARATOR . $cacheFile);
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
     * 取得订单名称
     * @param int $userOrderId 订单id
     * @param bool $withCache 是否从缓冲中取
     * @return string 订单名称
     */
    public function GetUserOrderName($userOrderId, $withCache)
    {
        $result = -1;
        if ($userOrderId > 0) {
            $cacheDir = UserOrderData::GetCachePath($userOrderId);
            $cacheFile = 'user_order_get_user_order_name.cache_' . $userOrderId . '';
            $sql = "SELECT UserOrderName FROM " . self::TableName_UserOrder . " WHERE UserOrderId=:UserOrderId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserOrderId", $userOrderId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }

        return $result;
    }


    /**
     * 取得订单编号
     * @param int $userOrderId 订单id
     * @param bool $withCache 是否从缓冲中取
     * @return string 订单编号
     */
    public function GetUserOrderNumber($userOrderId, $withCache)
    {
        $result = -1;
        if ($userOrderId > 0) {
            $cacheDir = UserOrderData::GetCachePath($userOrderId);
            $cacheFile = 'user_order_get_user_order_number.cache_' . $userOrderId . '';
            $sql = "SELECT UserOrderNumber FROM " . self::TableName_UserOrder . " WHERE UserOrderId=:UserOrderId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserOrderId", $userOrderId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }

        return $result;
    }

    /**
     * 取得订单会员id
     * @param int $userOrderId 订单id
     * @param bool $withCache 是否从缓冲中取
     * @return string 订单会员id
     */
    public function GetUserId($userOrderId, $withCache)
    {
        $result = -1;
        if ($userOrderId > 0) {
            $cacheDir = UserOrderData::GetCachePath($userOrderId);
            $cacheFile = 'user_order_get_user_id.cache_' . $userOrderId . '';
            $sql = "SELECT UserId FROM " . self::TableName_UserOrder . " WHERE UserOrderId=:UserOrderId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserOrderId", $userOrderId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }

        return $result;
    }

    /**
     * 修改支付宝交易号
     * @param int $userOrderId 订单id
     * @param int $alipayTradeNo 支付宝交易号
     * @return int 操作结果
     */
    public function ModifyAlipayTradeNo($userOrderId, $alipayTradeNo)
    {
        $result = 0;
        if ($userOrderId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_UserOrder . " SET `AlipayTradeNo`=:AlipayTradeNo WHERE ".self::TableId_UserOrder."=:".self::TableId_UserOrder.";";
            $dataProperty->AddField(self::TableId_UserOrder, $userOrderId);
            $dataProperty->AddField("AlipayTradeNo", $alipayTradeNo);
            $result = $this->dbOperator->Execute($sql, $dataProperty);

            $cacheDir = UserOrderData::GetCachePath($userOrderId);
            $cacheFile = 'user_order_get_alipay_trade_no.cache_' . $userOrderId . '';
            DataCache::Remove($cacheDir . DIRECTORY_SEPARATOR . $cacheFile);
        }
        return $result;
    }

    /**
     * 修改支付宝交易状态
     * @param int $userOrderId 订单id
     * @param int $alipayTradeStatus 支付宝交易状态
     * @return int 操作结果
     */
    public function ModifyAlipayTradeStatus($userOrderId, $alipayTradeStatus)
    {
        $result = 0;
        if ($userOrderId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_UserOrder . " SET `AlipayTradeStatus`=:AlipayTradeStatus WHERE ".self::TableId_UserOrder."=:".self::TableId_UserOrder.";";
            $dataProperty->AddField(self::TableId_UserOrder, $userOrderId);
            $dataProperty->AddField("AlipayTradeStatus", $alipayTradeStatus);
            $result = $this->dbOperator->Execute($sql, $dataProperty);

            $cacheDir = UserOrderData::GetCachePath($userOrderId);
            $cacheFile = 'user_order_get_alipay_trade_status.cache_' . $userOrderId . '';
            DataCache::Remove($cacheDir . DIRECTORY_SEPARATOR . $cacheFile);
        }
        return $result;
    }


    /**
     * 重计订单总价
     * @param $userOrderId
     * @return int
     */
    public function ReCountAllPrice($userOrderId)
    {
        $result = 0;
        if ($userOrderId > 0) {
            $dataProperty = new DataProperty();
            $sql = "SELECT sum(SubTotal) FROM " . self::TableName_UserOrderProduct . " WHERE ".self::TableId_UserOrder."=:".self::TableId_UserOrder.";";
            $dataProperty->AddField(self::TableId_UserOrder, $userOrderId);
            $allPrice = $this->dbOperator->GetFloat($sql, $dataProperty);

            $dataProperty2 = new DataProperty();
            $sql = "UPDATE " . self::TableName_UserOrder . "
                        SET
                        `AllPrice`=:AllPrice,AllPriceDes=:AllPriceDes

                    WHERE ".self::TableId_UserOrder."=:".self::TableId_UserOrder.";";
            $dataProperty2->AddField(self::TableId_UserOrder, $userOrderId);
            $dataProperty2->AddField("AllPrice", $allPrice);
            $dataProperty2->AddField("AllPriceDes", Des::Encrypt($allPrice, UserOrderData::USER_ORDER_DES_KEY));
            $result = $this->dbOperator->Execute($sql, $dataProperty2);

            $cacheDir = UserOrderData::GetCachePath($userOrderId);
            $cacheFile = 'user_order_get_all_price.cache_' . $userOrderId . '';
            DataCache::Remove($cacheDir . DIRECTORY_SEPARATOR . $cacheFile);
        }
        return $result;
    }

}

?>