<?php
/**
 * 前台 抽奖 数据类
 *
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Lottery
 * @author 525
 */
class LotteryPublicData extends BasePublicData {

    /**
     * 获取抽奖的tableId
     * @param $lotteryId
     * @param $withCache
     * @return int
     */
    public function GetTableType($lotteryId,$withCache){
        $result=-1;
        if($lotteryId > 0){
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'lottery_data';
            $cacheFile = 'lottery_get_table_type.cache_' . $lotteryId . '';
            $sql = "SELECT TableType FROM ".self::TableName_Lottery." WHERE LotteryId=:LotteryId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("LotteryId", $lotteryId);
            $result = $this->GetInfoOfIntValue($sql,$dataProperty,$withCache,$cacheDir,$cacheFile);
        }
        return $result;
    }


    /**
     * 获取抽奖的资格限制
     * @param $lotteryId
     * @param $withCache
     * @return int
     */
    public function GetLimitContent($lotteryId,$withCache){
        $result="-1";
        if($lotteryId > 0){
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'lottery_data';
            $cacheFile = 'lottery_get_limit_content.cache_' . $lotteryId . '';
            $sql = "SELECT LimitContent FROM ".self::TableName_Lottery." WHERE LotteryId=:LotteryId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("LotteryId", $lotteryId);
            $result = $this->GetInfoOfStringValue($sql,$dataProperty,$withCache,$cacheDir,$cacheFile);
        }
        return $result;
    }
}