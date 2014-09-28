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
     */
    public function Create($userId, $siteId, $productId, $productPriceId, $buyCount, $activityProductId = 0)
    {
        $result = -1;
        if ($userId > 0 && $productId > 0 && $siteId > 0 && $productPriceId > 0) {
            $sql = "INSERT INTO " . self::TableName_UserCar
                    . " (UserId,SiteId,ProductId,ProductPriceId,ActivityProductId,BuyCount,CreateDate)
                    VALUES (:UserId,:SiteId,:ProductId,:ProductPriceId,:ActivityProductId,:BuyCount,now());";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId", $userId);
            $dataProperty->AddField("SiteId", $siteId);
            $dataProperty->AddField("ProductId", $productId);
            $dataProperty->AddField("ProductPriceId", $productPriceId);
            $dataProperty->AddField("BuyCount", $buyCount);
            $dataProperty->AddField("ActivityProductId", $userId);

            $result = $this->dbOperator->LastInsertId($sql,$dataProperty);
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
            $sql = "SELECT uc.*,(uc.BuyCount*pp.ProductPriceValue) AS BuyPrice,p.ProductName,p.ProductId,pp.ProductPriceValue,pp.ProductUnit,pp.ProductPriceIntro,psp.SendPrice
                    FROM " . self::TableName_UserCar . " uc," . self::TableName_ProductPrice . " pp,"
                . self::TableName_Product . " p LEFT JOIN " . self::TableName_UploadFile . " up ON p.TitlePic1UploadFileId = up.UploadFileId,"
                . self::TableName_ProductSendPrice . " psp
                    WHERE uc.ProductPriceId = pp.ProductPriceId
                    AND uc.ProductId = psp.ProductId
                    AND uc.ProductId = p.ProductId
                    AND uc.UserId = :UserId;";
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

    public function BatchDelete($arrUserCarIdString, $userId)
    {
        $result = -1;
        if (!empty($arrUserCarIdString) && $userId > 0) {
            $sql = "DELETE FROM " . self::TableName_UserCar . " WHERE UserId = :UserId AND UserCarId IN (" . $arrUserCarIdString . ");";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId", $userId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    public function GetCarCount($userId, $siteId)
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

    public function GetListForConfirmOrder($arrUserCarIdString, $userId)
    {
        $result = null;
        $arrUserCarIdList = explode(",", $arrUserCarIdString);
        if (count($arrUserCarIdList) > 0) {
            $sql = "SELECT uc.*,(uc.BuyCount*pp.ProductPriceValue) AS BuyPrice,
                            p.ProductName,
                            p.ChannelId,
                            p.ProductId,
                            pp.ProductPriceValue,
                            pp.ProductUnit,
                            pp.ProductPriceIntro,
                            psp.SendPrice
                    FROM " . self::TableName_UserCar . " uc," . self::TableName_ProductPrice . " pp,"
                . self::TableName_Product . " p LEFT JOIN " . self::TableName_UploadFile . " up ON p.TitlePic1UploadFileId = up.UploadFileId,"
                . self::TableName_ProductSendPrice . " psp
                    WHERE uc.ProductPriceId = pp.ProductPriceId
                    AND uc.ProductId = psp.ProductId
                    AND uc.ProductId = p.ProductId
                    AND uc.UserId = :UserId AND uc.UserCarId IN (" . $arrUserCarIdString . ");";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId", $userId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }

    public function GetSendPriceForConfirmOrder($arrUserCarIdString, $userId)
    {
        $result = null;
        $arrUserCarIdList = explode(",", $arrUserCarIdString);
        if (count($arrUserCarIdList) > 0) {
            $sql = "SELECT max(psp.SendPrice)
                    FROM " . self::TableName_UserCar . " uc," . self::TableName_ProductSendPrice . " psp
                    WHERE uc.ProductId = psp.ProductId
                    AND uc.UserId = :UserId AND uc.UserCarId IN (" . $arrUserCarIdString . ");";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId", $userId);
            $result = $this->dbOperator->GetFloat($sql, $dataProperty);
        }
        return $result;
    }

    public function GetTotalProductPriceForConfirmOrder($arrUserCarIdString, $userId)
    {
        $result = null;
        $arrUserCarIdList = explode(",", $arrUserCarIdString);
        if (count($arrUserCarIdList) > 0) {
            $sql = "SELECT sum(uc.BuyCount*pp.ProductPriceValue) AS TotalPrice
                    FROM " . self::TableName_UserCar . " uc," . self::TableName_ProductPrice . " pp
                    WHERE uc.ProductPriceId = pp.ProductPriceId
                    AND uc.UserId = :UserId AND uc.UserCarId IN (" . $arrUserCarIdString . ");";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId", $userId);
            $result = $this->dbOperator->GetFloat($sql, $dataProperty);
        }
        return $result;
    }
} 