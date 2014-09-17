<?php

/**
 * 前台管理 会员购物车 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author yin
 */
class UserCarPublicData extends BasePublicData
{
    public function GetList($userId){
        $result = null;
        if($userId > 0){
            $sql = "SELECT uc.*,(uc.BuyCount*pp.ProductPriceValue+psp.SendPrice) AS BuyPrice,p.ProductName,pp.ProductPriceValue,pp.ProductUnit,pp.ProductPriceIntro,psp.SendPrice
                    FROM ".self::TableName_UserCar." uc,".self::TableName_ProductPrice." pp,"
                    .self::TableName_Product." p,".self::TableName_ProductSendPrice." psp
                    WHERE uc.ProductPriceId = pp.ProductPriceId
                    AND uc.ProductId = psp.ProductId
                    AND uc.ProductId = p.ProductId
                    AND uc.UserId = :UserId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId",$userId);
            $result = $this->dbOperator->GetArrayList($sql,$dataProperty);
        }
        return $result;
    }

    public function ModifyBuyCount($userCarId,$buyCount,$userId){
        $result = -1;
        if($userCarId > 0 && $buyCount > 0 && $userId > 0){
            $sql = "UPDATE ".self::TableName_UserCar." SET BuyCount = :BuyCount WHERE UserCarId = :UserCarId AND UserId = :UserId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserCarId",$userCarId);
            $dataProperty->AddField("BuyCount",$buyCount);
            $dataProperty->AddField("UserId",$userId);
            $result = $this->dbOperator->Execute($sql,$dataProperty);
        }
        return $result;
    }

    public function Delete($userCarId,$userId){
        $result = -1;
        if($userCarId > 0 && $userId > 0){
            $sql = "DELETE FROM ".self::TableName_UserCar." WHERE UserId = :UserId AND UserCarId = :UserCarId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId",$userId);
            $dataProperty->AddField("UserCarId",$userCarId);
            $result = $this->dbOperator->Execute($sql,$dataProperty);
        }
        return $result;
    }
} 