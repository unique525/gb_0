<?php
/**
 * 前台管理 会员订单产品 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author yin
 */
class UserOrderProductPublicData extends BasePublicData{

    const STATE_REMOVED = 100;

    /**
     * @param $userOrderId
     * @param $siteId
     * @param $productId
     * @param $productIdDes
     * @param $productPriceId
     * @param $saleCount
     * @param $saleCountDes
     * @param $productPrice
     * @param $productPriceDes
     * @param $salePrice
     * @param $salePriceDes
     * @param $subtotal
     * @param $subtotalDes
     * @param $autoSendMessage
     * @return int
     */
    public function Create(
        $userOrderId,
        $siteId,
        $productId,
        $productIdDes,
        $productPriceId,
        $saleCount,
        $saleCountDes,
        $productPrice,
        $productPriceDes,
        $salePrice,
        $salePriceDes,
        $subtotal,
        $subtotalDes,
        $autoSendMessage
    ){
        $result = -1;
        if(
            $userOrderId>0
            && $productId>0
            && $productPriceId>0
            && $saleCount>0
        ){

            $sql = "INSERT INTO ".self::TableName_UserOrderProduct."
                    (
                        UserOrderId,
                        SiteId,
                        ProductId,
                        ProductIdDes,
                        ProductPriceId,
                        SaleCount,
                        SaleCountDes,
                        ProductPrice,
                        ProductPriceDes,
                        SalePrice,
                        SalePriceDes,
                        Subtotal,
                        SubtotalDes,
                        AutoSendMessage,
                        CreateDate
                    )
                    VALUES
                    (
                        :UserOrderId,
                        :SiteId,
                        :ProductId,
                        :ProductIdDes,
                        :ProductPriceId,
                        :SaleCount,
                        :SaleCountDes,
                        :ProductPrice,
                        :ProductPriceDes,
                        :SalePrice,
                        :SalePriceDes,
                        :Subtotal,
                        :SubtotalDes,
                        :AutoSendMessage,
                        now()
                    );

            ";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserOrderId",$userOrderId);
            $dataProperty->AddField("SiteId",$siteId);
            $dataProperty->AddField("ProductId",$productId);
            $dataProperty->AddField("ProductIdDes",$productIdDes);
            $dataProperty->AddField("ProductPriceId",$productPriceId);
            $dataProperty->AddField("SaleCount",$saleCount);
            $dataProperty->AddField("SaleCountDes",$saleCountDes);
            $dataProperty->AddField("ProductPrice",$productPrice);
            $dataProperty->AddField("ProductPriceDes",$productPriceDes);
            $dataProperty->AddField("SalePrice",$salePrice);
            $dataProperty->AddField("SalePriceDes",$salePriceDes);
            $dataProperty->AddField("Subtotal",$subtotal);
            $dataProperty->AddField("SubtotalDes",$subtotalDes);
            $dataProperty->AddField("AutoSendMessage",$autoSendMessage);
            $result = $this->dbOperator->LastInsertId($sql,$dataProperty);

        }


        return $result;
    }


    /**
     * @param $userId
     * @param $productId
     * @param $userOrderId
     * @return int
     */
    public function CheckIsBought($userId,$productId,$userOrderId){
        $result = -1;
        if($userId > 0 && $productId > 0 && $userOrderId > 0){
            $sql = "SELECT count(*) FROM ".self::TableName_UserOrderProduct." uop,".self::TableName_UserOrder." uo"
                ." WHERE  uo.UserId = :UserId AND uop.UserOrderId = uo.UserOrderId AND uop.ProductId = :ProductId"
                ." AND uop.UserOrderId = :UserOrderId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId",$userId);
            $dataProperty->AddField("ProductId",$productId);
            $dataProperty->AddField("UserOrderId",$userOrderId);
            $result = $this->dbOperator->GetInt($sql,$dataProperty);
        }
        return $result;
    }

    /**
     * 获取订单中的产品列表
     * @param int $userOrderId 会员订单Id
     * @param int $userId 会员Id
     * @param int $siteId 站点Id
     * @return array 多个会员订单的数组
     */
    public function GetList($userOrderId,$userId,$siteId)
    {
        $result = null;
        if ($userOrderId > 0 && $userId > 0 && $siteId > 0) {
            $sql = "SELECT uop.*,p.ProductName,pp.ProductPriceIntro,pp.ProductUnit,(SELECT count(*) FROM "
                                .self::TableName_ProductComment." pc WHERE uop.UserOrderProductId = pc.UserOrderProductId) AS CommentCount
                FROM " . self::TableName_UserOrderProduct . " uop, "
                                . self::TableName_Product . " p,"
                                . self::TableName_ProductPrice . " pp,"
                                .self::TableName_UserOrder." uo
                WHERE uop.ProductId = p.ProductId
                AND uop.State < :State
                AND uop.ProductPriceId = pp.ProductPriceId
                AND uop.UserOrderId = uo.UserOrderId
                AND uo.UserId = :UserId
                AND uo.UserOrderId = :UserOrderId
                AND uop.SiteId = :SiteId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserOrderId", $userOrderId);
            $dataProperty->AddField("State", self::STATE_REMOVED);
            $dataProperty->AddField("SiteId", $siteId);
            $dataProperty->AddField("UserId", $userId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }
}