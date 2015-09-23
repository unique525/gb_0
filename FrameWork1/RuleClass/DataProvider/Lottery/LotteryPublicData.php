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

    /**
     * 获取可以参加的会员组
     * @param $lotteryId
     * @param $withCache
     * @return int
     */
    public function GetLimitUserGroup($lotteryId,$withCache){
        $result="-1";
        if($lotteryId > 0){
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'lottery_data';
            $cacheFile = 'lottery_get_limit_user_group.cache_' . $lotteryId . '';
            $sql = "SELECT LimitUserGroup FROM ".self::TableName_Lottery." WHERE LotteryId=:LotteryId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("LotteryId", $lotteryId);
            $result = $this->GetInfoOfStringValue($sql,$dataProperty,$withCache,$cacheDir,$cacheFile);
        }
        return $result;
    }



    /**
     * 获取开始时间
     * @param $lotteryId
     * @param $withCache
     * @return string
     */
    public function GetBeginDate($lotteryId,$withCache){
        $result="-1";
        if($lotteryId > 0){
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'lottery_data';
            $cacheFile = 'lottery_get_begin_date.cache_' . $lotteryId . '';
            $sql = "SELECT BeginDate FROM ".self::TableName_Lottery." WHERE LotteryId=:LotteryId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("LotteryId", $lotteryId);
            $result = $this->GetInfoOfStringValue($sql,$dataProperty,$withCache,$cacheDir,$cacheFile);
        }
        return $result;
    }

    /**
     * 获取结束时间
     * @param $lotteryId
     * @param $withCache
     * @return string
     */
    public function GetEndDate($lotteryId,$withCache){
        $result="-1";
        if($lotteryId > 0){
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'lottery_data';
            $cacheFile = 'lottery_get_end_date.cache_' . $lotteryId . '';
            $sql = "SELECT EndDate FROM ".self::TableName_Lottery." WHERE LotteryId=:LotteryId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("LotteryId", $lotteryId);
            $result = $this->GetInfoOfStringValue($sql,$dataProperty,$withCache,$cacheDir,$cacheFile);
        }
        return $result;
    }


    /**
     * 获取抽奖的抽奖方式（几率或比率）
     * @param $lotteryId
     * @param $withCache
     * @return int
     */
    public function GetOddsType($lotteryId,$withCache){
        $result=-1;
        if($lotteryId > 0){
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'lottery_data';
            $cacheFile = 'lottery_get_odds_type.cache_' . $lotteryId . '';
            $sql = "SELECT OddsType FROM ".self::TableName_Lottery." WHERE LotteryId=:LotteryId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("LotteryId", $lotteryId);
            $result = $this->GetInfoOfIntValue($sql,$dataProperty,$withCache,$cacheDir,$cacheFile);
        }
        return $result;
    }


    /**
     * 获取同一会员抽奖次数限制
     * @param int $lotteryId
     * @param bool $withCache
     * @return int
     */
    public function GetOneUserDoLimit($lotteryId,$withCache){
        $result=-1;
        if($lotteryId > 0){
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'lottery_data';
            $cacheFile = 'lottery_get_one_user_do_limit.cache_' . $lotteryId . '';
            $sql = "SELECT OneUserDoLimit FROM ".self::TableName_Lottery." WHERE LotteryId=:LotteryId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("LotteryId", $lotteryId);
            $result = $this->GetInfoOfIntValue($sql,$dataProperty,$withCache,$cacheDir,$cacheFile);
        }
        return $result;
    }
}