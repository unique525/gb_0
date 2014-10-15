<?php
/**
 * Created by PhpStorm.
 * User: zcoffice
 * Date: 14-6-25
 * Time: 上午11:48
 */

class ActivityProductPublicData extends BasePublicData{

    public function GetDiscount($activityProductId){
        $result = 0;
        if($activityProductId > 0){
            $sql = "SELECT Discount FROM ".self::TableName_ActivityProduct." WHERE ActivityProductId = :ActivityProductId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ActivityProductId",$activityProductId);
            $result = $this->dbOperator->GetFloat($sql,$dataProperty);
        }
        return $result;
    }
} 