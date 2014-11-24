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
                AND uop.State < :State
                AND uop.ProductPriceId = pp.ProductPriceId
                AND uop.UserOrderId = :UserOrderId
                AND uop.SiteId = :SiteId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserOrderId", $userOrderId);
            $dataProperty->AddField("State", self::STATE_REMOVED);
            $dataProperty->AddField("SiteId", $siteId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }
}