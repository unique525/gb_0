<?php

/**
 * 后台管理 会员订单产品 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author yin
 */
class UserOrderProductManageData extends BaseManageData
{
    const STATE_REMOVED = 100;

    public function GetFields($tableName = self::TableName_UserOrderProduct){
        return parent::GetFields(self::TableName_UserOrderProduct);
    }

    /**
     * 修改
     * @param array $httpPostData $_POST数组
     * @param int $userOrderProductId 会员订单产品Id
     * @param int $userOrderId 会员订单Id
     * @return int 执行结果
     */
    public function Modify($httpPostData,$userOrderProductId,$userOrderId){
        $result = 0;
        if(!empty($httpPostData) && $userOrderProductId > 0){
            $dataPropertyUserOrderProduct = new DataProperty();//会员订单产品表DataProperty
            $dataPropertyUserOrder = new DataProperty();//会员订单表DataProperty
            $sqlUserOrderProduct = parent::GetUpdateSql($httpPostData,self::TableName_UserOrderProduct,self::TableId_UserOrderProduct,
                $userOrderProductId,$dataPropertyUserOrderProduct);//修改会员订单产品的信息
            $sqlUserOrder = "UPDATE ".self::TableName_UserOrder." uo SET uo.AllPrice =
                (uo.SendPrice + (SELECT SUM(uop.Subtotal) FROM ".self::TableName_UserOrderProduct." uop
                WHERE uop.UserOrderId = :UserOrderId1 AND uop.State < :State GROUP BY uop.UserOrderId))
                WHERE uo.UserOrderId = :UserOrderId2;";//重新计算会员订单的总价
            $dataPropertyUserOrder->AddField("UserOrderId1",$userOrderId);
            $dataPropertyUserOrder->AddField("UserOrderId2",$userOrderId);
            $dataPropertyUserOrder->AddField("State",self::STATE_REMOVED);
            $arrSql = Array(
                $sqlUserOrderProduct,
                $sqlUserOrder
            );
            $arrDataProperty = Array(
                $dataPropertyUserOrderProduct,
                $dataPropertyUserOrder
            );
            $result = $this->dbOperator->ExecuteBatch($arrSql,$arrDataProperty);//批量执行
        }
        return $result;
    }

    /**
     * 修改会员订单产品的状态
     * @param int $userOrderId 会员订单Id
     * @param int $userOrderProductId 会员订单产品Id
     * @param int $state 状态
     * @return int 执行结果
     */
    public function ModifyState($userOrderId,$userOrderProductId,$state){
        $result = -1;
        if($userOrderProductId > 0 && $state >= 0){
            $dataPropertyUserOrderProduct = new DataProperty();//会员订单产品表DataProperty
            $dataPropertyUserOrder = new DataProperty();//会员订单表DataProperty
            $sqlUserOrderProduct = "UPDATE ".self::TableName_UserOrderProduct." SET State = :State
                WHERE UserOrderProductId = :UserOrderProductId;";//修改会员订单产品的状态
            $sqlUserOrder = "UPDATE ".self::TableName_UserOrder." uo SET uo.AllPrice =
                (uo.SendPrice + (SELECT SUM(uop.Subtotal) FROM ".self::TableName_UserOrderProduct." uop
                WHERE uop.UserOrderId = :UserOrderId1 AND uop.State < :State2 GROUP BY uop.UserOrderId))
                WHERE uo.UserOrderId = :UserOrderId2;";//重新计算会员订单的总价

            $dataPropertyUserOrderProduct->AddField("State",$state);
            $dataPropertyUserOrderProduct->AddField("UserOrderProductId",$userOrderProductId);
            $dataPropertyUserOrder->AddField("State2",self::STATE_REMOVED);
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

            $result = $this->dbOperator->ExecuteBatch($arrSql,$arrDataProperty);//批量执行
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
                AND uop.State < :State
                AND p.ProductId = uop.ProductId
                AND pp.ProductPriceId = uop.ProductPriceId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);
            $dataProperty->AddField("State", self::STATE_REMOVED);
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
            $sql = "SELECT uop.*,p.ProductName,p.ProductTag,pp.ProductPriceIntro,pp.ProductUnit
                FROM " . self::TableName_UserOrderProduct . " uop," . self::TableName_Product . " p," . self::TableName_ProductPrice . " pp
                WHERE uop.ProductId = p.ProductId
                AND uop.State < :State
                AND uop.ProductPriceId = pp.ProductPriceId
                AND uop.UserOrderId = :UserOrderId
                AND uop.SiteId = :SiteId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserOrderId", $userOrderId);
            $dataProperty->AddField("State", UserOrderData::STATE_REMOVED);
            $dataProperty->AddField("SiteId", $siteId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }
}