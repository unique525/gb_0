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

    /**
     * 取得奖项名称
     * @param int $lotterySetId 奖项设置id
     * @param bool $withCache 是否从缓冲中取
     * @return string 频道名称
     */
    public function GetLotterySetName($lotterySetId, $withCache)
    {
        $result = "";
        if ($lotterySetId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'lottery_set_data';
            $cacheFile = 'lottery_set_get_lottery_set_name.cache_' . $lotterySetId . '';
            $sql = "SELECT LotterySetName FROM " . self::TableName_LotterySet . " WHERE LotterySetId=:LotterySetId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("LotterySetId", $lotterySetId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }

        return $result;
    }

    /**
     * 获取抽奖的所有奖项设置
     * @param $lotterySetId
     * @return array|int
     */
    public function GetOne($lotterySetId){
        $result=null;
        if($lotterySetId>0){
            $sql="SELECT * FROM ".self::TableName_LotterySet." WHERE LotterySetId=:LotterySetId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("LotterySetId", $lotterySetId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
        }
        return $result;
    }
} 