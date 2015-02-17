<?php

/**
 * 前台管理 会员购物车 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author yin
 */
class UserCarPublicData extends BasePublicData
{
    /**
     * 新增
     * @param int $userId 会员id
     * @param int $siteId 站点id
     * @param int $productId 产品id
     * @param int $productPriceId 产品价格id
     * @param int $buyCount 购买数量
     * @param float $salePrice 折后价格
     * @param float $buyPrice 小计价格
     * @param int $productCount 产品库存数量
     * @param int $activityProductId 活动产品id
     * @return int 返回新增结果
     */
    public function Create(
        $userId,
        $siteId,
        $productId,
        $productPriceId,
        $buyCount,
        $salePrice,
        $buyPrice,
        $productCount,
        $activityProductId = 0)
    {
        $result = -1;
        if ($userId > 0 && $productId > 0 && $siteId > 0 && $productPriceId > 0) {
            $buyCount = intval($buyCount);
            if($buyCount<0){
                $buyCount = 0; //不允许增加负数
            }

            $existBuyCount = self::GetBuyCountOfOneProductAndProductPrice(
                $userId,
                $siteId,
                $productId,
                $productPriceId,
                $activityProductId
            );

            $newBuyCount = $existBuyCount + $buyCount;
            if ($newBuyCount > $productCount){
                //如果新的产品数量大于库存数量，不新增，返回-20
                $result = -20;

            }else{

                $dataProperty = new DataProperty();
                $dataProperty->AddField("UserId", $userId);
                $dataProperty->AddField("SiteId", $siteId);
                $dataProperty->AddField("ProductId", $productId);
                $dataProperty->AddField("ProductPriceId", $productPriceId);
                $dataProperty->AddField("ActivityProductId", $activityProductId);


                if ($existBuyCount>0){

                    //修改的时候，小计也要重计一下

                    $sql = "UPDATE " . self::TableName_UserCar . "
                        SET BuyCount = BuyCount + $buyCount,BuyPrice = BuyPrice + $buyPrice
                        WHERE UserId = :UserId
                            AND SiteId = :SiteId
                            AND ProductId = :ProductId
                            AND ProductPriceId = :ProductPriceId
                            AND ActivityProductId = :ActivityProductId
                        ;";

                    $result = $this->dbOperator->Execute($sql,$dataProperty);

                }else{


                    $dataProperty->AddField("BuyCount", $buyCount);
                    $dataProperty->AddField("SalePrice", $salePrice);
                    $dataProperty->AddField("BuyPrice", $buyPrice);
                    $sql = "INSERT INTO " . self::TableName_UserCar. "
                        (
                        UserId,
                        SiteId,
                        ProductId,
                        ProductPriceId,
                        ActivityProductId,
                        BuyCount,
                        SalePrice,
                        BuyPrice,
                        CreateDate
                        )
                        VALUES (
                        :UserId,
                        :SiteId,
                        :ProductId,
                        :ProductPriceId,
                        :ActivityProductId,
                        :BuyCount,
                        :SalePrice,
                        :BuyPrice,
                        now()
                        );";

                    $result = $this->dbOperator->LastInsertId($sql,$dataProperty);
                }

            }


        }
        return $result;
    }

    /**
     * 获取会员购物车的列表
     * @param int $userId 用户Id
     * @return array|null 会员购物车的列表
     */
    public function GetList($userId)
    {
        $result = null;
        if ($userId > 0) {
            $sql = "SELECT uc.* ,
                        up.UploadFilePath,
                        up.UploadFileMobilePath,
                        up.UploadFilePadPath,
                        up.UploadFileThumbPath1,
                        up.UploadFileThumbPath2,
                        up.UploadFileThumbPath3,
                        up.UploadFileWatermarkPath1,
                        up.UploadFileWatermarkPath2,
                        up.UploadFileCompressPath1,
                        up.UploadFileCompressPath2,
                        up.UploadFileTitle,
                        up.UploadFileInfo,


            p.ProductName ,p.ProductId ,p.MarketPrice,pp.ProductPriceValue ,pp.ProductUnit ,pp.ProductPriceIntro ,psp.SendPrice
                            FROM " . self::TableName_UserCar . " uc
                            LEFT JOIN " . self::TableName_ProductSendPrice . " psp ON uc.ProductId = psp.ProductId
                            LEFT JOIN " . self::TableName_ProductPrice . " pp ON uc.ProductPriceId = pp.ProductPriceId
                            LEFT JOIN ". self::TableName_Product . " p ON uc.ProductId = p.ProductId
                            LEFT JOIN " . self::TableName_UploadFile . " up ON p.TitlePic1UploadFileId = up.UploadFileId
                            WHERE uc.UserId = :UserId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId", $userId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 修改购物车中某个商品的购买数量
     * @param int $userCarId 会员购物车Id
     * @param int $buyCount 购买数量
     * @param int $userId 用户Id
     * @return int 影响行数
     */
    public function ModifyBuyCount($userCarId, $buyCount, $userId)
    {
        $result = -1;
        if ($userCarId > 0 && $buyCount > 0 && $userId > 0) {
            $sql = "UPDATE " . self::TableName_UserCar . " SET BuyCount = :BuyCount WHERE UserCarId = :UserCarId AND UserId = :UserId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserCarId", $userCarId);
            $dataProperty->AddField("BuyCount", $buyCount);
            $dataProperty->AddField("UserId", $userId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 删除
     * @param int $userCarId 会员购物车Id
     * @param int $userId 会员Id
     * @return int 影响行数
     */
    public function Delete($userCarId, $userId)
    {
        $result = -1;
        if ($userCarId > 0 && $userId > 0) {
            $sql = "DELETE FROM " . self::TableName_UserCar . " WHERE UserId = :UserId AND UserCarId = :UserCarId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId", $userId);
            $dataProperty->AddField("UserCarId", $userCarId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 根据产品和产品价格id删除
     * @param int $userId
     * @param int $siteId
     * @param int $productId
     * @param int $productPriceId
     * @param int $activityProductId
     * @return int 影响行数
     */
    public function DeleteByProductAndProductPrice(
        $userId,
        $siteId,
        $productId,
        $productPriceId,
        $activityProductId
    ){
        $result = 0;
        if ($userId > 0 && $productId > 0 && $siteId > 0 && $productPriceId > 0) {
            $dataProperty = new DataProperty();

            $sql = "DELETE FROM " . self::TableName_UserCar ."
                    WHERE UserId = :UserId
                        AND SiteId = :SiteId
                        AND ProductId = :ProductId
                        AND ProductPriceId = :ProductPriceId
                        AND ActivityProductId = :ActivityProductId;
                    ";
            $dataProperty->AddField("UserId", $userId);
            $dataProperty->AddField("SiteId", $siteId);
            $dataProperty->AddField("ProductId", $productId);
            $dataProperty->AddField("ProductPriceId", $productPriceId);
            $dataProperty->AddField("ActivityProductId", $activityProductId);

            $result = $this->dbOperator->Execute($sql,$dataProperty);
        }
        return $result;
    }



    public function BatchDelete($strUserCarIds, $userId)
    {
        $result = -1;
        if (!empty($strUserCarIds) && $userId > 0) {
            $sql = "DELETE FROM " . self::TableName_UserCar . " WHERE UserId = :UserId AND UserCarId IN (" . $strUserCarIds . ");";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId", $userId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    public function GetCount($userId, $siteId)
    {
        $result = -1;
        if ($userId > 0 && $siteId > 0) {
            $sql = "SELECT count(*) FROM " . self::TableName_UserCar . " WHERE UserId = :UserId AND SiteId = :SiteId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId", $userId);
            $dataProperty->AddField("SiteId", $siteId);
            $result = $this->dbOperator->GetInt($sql, $dataProperty);
        }
        return $result;
    }

    public function GetListForConfirmUserOrder($strUserCarIds, $userId)
    {
        $result = null;
        $arrUserCarId = explode(",", $strUserCarIds);
        if (count($arrUserCarId) > 0) {
            $sql = "SELECT uc.*,(uc.BuyCount*pp.ProductPriceValue) AS BuyPrice,
                            p.ProductName,
                            p.ChannelId,
                            p.ProductId,
                            pp.ProductPriceId,
                            pp.ProductPriceValue,
                            pp.ProductUnit,
                            pp.ProductPriceIntro
                    FROM " . self::TableName_UserCar . " uc," . self::TableName_ProductPrice . " pp,"
                . self::TableName_Product . " p LEFT JOIN " . self::TableName_UploadFile . " up ON p.TitlePic1UploadFileId = up.UploadFileId
                    WHERE uc.ProductPriceId = pp.ProductPriceId

                    AND uc.ProductId = p.ProductId
                    AND uc.UserId = :UserId AND uc.UserCarId IN (" . $strUserCarIds . ");";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId", $userId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

        }
        return $result;
    }

    /**
     * 取得发货费用的最高值
     * @param $strUserCarIds
     * @param $userId
     * @return float|null
     */
    public function GetSendPriceForConfirmUserOrder($strUserCarIds, $userId)
    {
        $result = null;
        $arrUserCarId = explode(",", $strUserCarIds);
        if (count($arrUserCarId) > 0) {
            $sql = "SELECT max(psp.SendPrice)
                    FROM " . self::TableName_UserCar . " uc," . self::TableName_ProductSendPrice . " psp
                    WHERE uc.ProductId = psp.ProductId
                    AND uc.UserId = :UserId AND uc.UserCarId IN (" . $strUserCarIds . ");";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId", $userId);
            $result = $this->dbOperator->GetFloat($sql, $dataProperty);
        }
        return $result;
    }

    public function GetTotalProductPriceForConfirmUserOrder($strUserCarIds, $userId)
    {
        $result = null;
        $arrUserCarIdList = explode(",", $strUserCarIds);
        if (count($arrUserCarIdList) > 0) {
            $sql = "SELECT sum(uc.BuyCount*pp.ProductPriceValue) AS TotalPrice
                    FROM " . self::TableName_UserCar . " uc," . self::TableName_ProductPrice . " pp
                    WHERE uc.ProductPriceId = pp.ProductPriceId
                    AND uc.UserId = :UserId AND uc.UserCarId IN (" . $strUserCarIds . ");";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId", $userId);
            $result = $this->dbOperator->GetFloat($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 返回某个产品某个价格的购买数量
     * @param int $userId 会员id
     * @param int $siteId 站点id
     * @param int $productId 产品id
     * @param int $productPriceId 产品价格id
     * @param int $activityProductId 活动产品id
     * @return int 某个产品某个价格的购买数量
     */
    public function GetBuyCountOfOneProductAndProductPrice(
        $userId,
        $siteId,
        $productId,
        $productPriceId,
        $activityProductId
    ){
        $result = 0;
        if ($userId > 0 && $productId > 0 && $siteId > 0 && $productPriceId > 0) {
            $dataProperty = new DataProperty();
            //存在同一产品同一站点同一价格时，UPDATE
            $sql = "SELECT BuyCount FROM " . self::TableName_UserCar ."
                    WHERE UserId = :UserId
                        AND SiteId = :SiteId
                        AND ProductId = :ProductId
                        AND ProductPriceId = :ProductPriceId
                        AND ActivityProductId = :ActivityProductId;
                    ";
            $dataProperty->AddField("UserId", $userId);
            $dataProperty->AddField("SiteId", $siteId);
            $dataProperty->AddField("ProductId", $productId);
            $dataProperty->AddField("ProductPriceId", $productPriceId);
            $dataProperty->AddField("ActivityProductId", $activityProductId);

            $result = $this->dbOperator->GetInt($sql,$dataProperty);
        }
        return $result;
    }

    /**
     * 根据会员购物车id取得产品价格id
     * @param int $userCarId 会员id
     * @param bool $withCache 是否从缓冲中取
     * @return string 返回产品价格id
     */
    public function GetProductPriceId($userCarId, $withCache)
    {
        $result = -1;
        if ($userCarId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'user_car_data';
            $cacheFile = 'user_car_get_product_price_id.cache_' . $userCarId . '';
            $sql = "SELECT ProductPriceId FROM " . self::TableName_UserCar . " WHERE UserCarId=:UserCarId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserCarId", $userCarId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }
} 