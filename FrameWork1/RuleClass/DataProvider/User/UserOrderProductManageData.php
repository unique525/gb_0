<?php

/**
 * 后台管理 会员订单产品 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author yin
 */
class UserOrderProductManageData extends BaseManageData
{
    public function Modify($httpPostData,$userOrderProductId,$userOrderId){
        $result = 0;
        if(!empty($httpPostData) && $userOrderProductId > 0){
            $dataPropertyUserOrderProduct = new DataProperty();
            $dataPropertyUserOrder = new DataProperty();
            $sqlUserOrderProduct = parent::GetUpdateSql($httpPostData,self::TableName_UserOrderProduct,self::TableId_UserOrderProduct,
                $userOrderProductId,$dataPropertyUserOrderProduct);
            $sqlUserOrder = "UPDATE ".self::TableName_UserOrder." uo SET uo.AllPrice =
                (uo.SendPrice + (SELECT SUM(uop.subtotal) FROM ".self::TableName_UserOrderProduct." uop
                WHERE uop.UserOrderId = :UserOrderId1 GROUP BY uop.UserOrderId))
                WHERE uo.UserOrderId = :UserOrderId2";
            $dataPropertyUserOrder->AddField("UserOrderId1",$userOrderId);
            $dataPropertyUserOrder->AddField("UserOrderId2",$userOrderId);
            $arrSql = Array(
                $sqlUserOrderProduct,
                $sqlUserOrder
            );
            $arrDataProperty = Array(
                $dataPropertyUserOrderProduct,
                $dataPropertyUserOrder
            );
            $result = $this->dbOperator->ExecuteBatch($arrSql,$arrDataProperty);
        }
        return $result;
    }

    /**
     * @param int $userOrderProductId 会员订单商品Id
     * @param int $siteId 站点Id
     * @return array|null 订单商品的一条信息
     */
    public function GetOne($userOrderProductId,$siteId)
    {
        $result = null;
        if ($userOrderProductId > 0 && $siteId > 0) {
            $sql = "SELECT uop.*,p.ProductName,pp.ProductPriceIntro,pp.ProductUnit
                FROM " . self::TableName_UserOrderProduct . " uop,".self::TableName_Product." p,".self::TableName_ProductPrice." pp
                WHERE uop.UserOrderProductId = :UserOrderProductId
                AND uop.SiteId = :SiteId
                AND p.ProductId = uop.ProductId
                AND pp.ProductPriceId = uop.ProductPriceId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);
            $dataProperty->AddField("UserOrderProductId", $userOrderProductId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 获取订单中的产品列表
     * @param int $userOrderId 会员订单Id
     * @param int $siteId 站点Id
     * @return array 多个会员订单的数组
     */
    public function GetList($userOrderId,$siteId)
    {
        $result = null;
        if ($userOrderId > 0) {
            $sql = "SELECT uop.*,p.ProductName,pp.ProductPriceIntro,pp.ProductUnit
                FROM " . self::TableName_UserOrderProduct . " uop," . self::TableName_Product . " p," . self::TableName_ProductPrice . " pp
                WHERE uop.ProductId = p.ProductId
                AND uop.ProductPriceId = pp.ProductPriceId
                AND uop.UserOrderId = :UserOrderId
                AND uop.SiteId = :SiteId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserOrderId", $userOrderId);
            $dataProperty->AddField("SiteId", $siteId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }
}