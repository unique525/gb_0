<?php
/**
 * 前台管理 会员订单产品 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author yin
 */
class UserOrderProductPublicData extends BasePublicData{

    public function CheckIsBought($userId,$productId){
        $result = -1;
        if($userId > 0 && $productId > 0){
            $sql = "SELECT count(*) FROM ".self::TableName_UserOrderProduct." WHERE UserId = :UserId AND ProductId = :ProductId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId",$userId);
            $dataProperty->AddField("ProductId",$productId);
            $result = $this->dbOperator->GetInt($sql,$dataProperty);
        }
        return $result;
    }
}