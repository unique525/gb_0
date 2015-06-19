<?php
/**
 * 前台 抽奖奖项设置 数据类
 *
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Lottery
 * @author 525
 */
class LotterySetPublicData extends BasePublicData {

    /**
     * 获取抽奖的所有奖项设置
     * @param $lotteryId
     * @return array|int
     */
    public function GetList($lotteryId){
        $result=null;
        if($lotteryId>0){
            $sql="SELECT * FROM ".self::TableName_LotterySet." WHERE LotteryId=:LotteryId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("LotteryId", $lotteryId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }
} 