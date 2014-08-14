<?php
/**
 * 后台管理 会员订单产品 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author yin
 */
class UserOrderProductManageData extends BaseManageData{

    /**
     * 获取订单中的产品列表
     * @param int $userOrderId 站点Id
     * @return array 多个会员订单的数组
     */
    public function GetList($userOrderId){
        $result = null;
        if($userOrderId > 0){
            $sql = "SELECT uop.*,p.ProductName,p.ProductIntro,pp.ProductUnit
                FROM ".self::TableName_UserOrderProduct." uop,".self::TableName_Product." p,".self::TableName_ProductPrice." pp
                WHERE uop.ProductId = p.ProductId
                AND uop.ProductPriceId = pp.ProductPriceId
                AND uop.UserOrderId = :UserOrderId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserOrderId",$userOrderId);
            $result = $this->dbOperator->GetArrayList($sql,$dataProperty);
        }
        return $result;
    }
}