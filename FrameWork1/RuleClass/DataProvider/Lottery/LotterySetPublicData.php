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
     * @param $state
     * @return array|int
     */
    public function GetList($lotteryId,$state){
        $result=null;
        if($lotteryId>0){
            $sql="SELECT * FROM ".self::TableName_LotterySet." WHERE LotteryId=:LotteryId AND State=:State;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("LotteryId", $lotteryId);
            $dataProperty->AddField("State", $state);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }
} 