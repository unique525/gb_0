<?php
/**
 * 前台 抽奖用户 数据类
 *
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Lottery
 * @author 525
 */
class LotteryUserPublicData extends BasePublicData {

    /**
     * 新增
     * @param $lotteryId
     * @param $userId
     * @param $tableType
     * @param $tableId
     * @param $createDate
     * @return int
     */
    public function Create($lotteryId,$userId,$tableType,$tableId,$createDate){
        $result=-1;
        if ($lotteryId>0) {
            $sql="INSERT INTO ".self::TableName_LotteryUser." (LotteryId,UserId,TableType,TableId,CreateDate) VALUES(:LotteryId,:UserId,:TableType,:TableId,:CreateDate);";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("LotteryId",$lotteryId);
            $dataProperty->AddField("UserId",$userId);
            $dataProperty->AddField("TableType",$tableType);
            $dataProperty->AddField("TableId",$tableId);
            $dataProperty->AddField("CreateDate",$createDate);
            $result=$this->dbOperator->LastInsertId($sql,$dataProperty);
        }
        return $result;
    }

    /**
     * 检查用户是否已存在（通次抽奖活动，按手机号码）
     * @param $lotteryId
     * @param $userMobile
     * @return array
     */
    public function CheckRepeat($lotteryId,$userMobile){
        $result=null;
        if($lotteryId>0){
            $sql="SELECT * FROM ".self::TableName_LotteryUser." WHERE LotteryId=:LotteryId AND UserMobile=:UserMobile ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("LotteryId",$lotteryId);
            $dataProperty->AddField("UserMobile",$userMobile);
            $result=$this->dbOperator->GetArray($sql,$dataProperty);
        }
        return $result;

    }

    /**
     * 参与次数加一
     * @param $lotteryUserId
     * @return int
     */
    public function TimesAdd($lotteryUserId){
        $result=-1;
        if($lotteryUserId>0){
            $sql="UPDATE ".self::TableName_LotteryUser." SET Times=Times+1 WHERE LotteryUserId=:LotteryUserId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("LotteryUserId",$lotteryUserId);
            $result=$this->dbOperator->Execute($sql,$dataProperty);
        }
        return $result;
    }


    /**
     * 取得参与次数
     * @param int $userId 用户id
     * @param int $lotteryId id
     * @return int
     */
    public function GetCount($userId, $lotteryId)
    {
        $result = 1;
        if ( $userId > 0) {
            $sql = "SELECT COUNT(*) FROM ".self::TableName_LotteryUser." WHERE UserId=:UserId AND LotteryId=:LotteryId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId", $userId);
            $dataProperty->AddField("LotteryId", $lotteryId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty,false,"","");
        }
        return $result;
    }

    /**
     * 取得参与次数
     * @param int $userId 用户id
     * @param int $tableType 试题分类id
     * @param int $tableId 试题id
     * @param int $withCache
     * @return int 单选的非必选题抽取数量
     */
    public function GetLotteryTimeCount($userId, $tableType, $tableId,$withCache)
    {
        $result = 1;
        if ($tableId > 0 && $userId > 0) {
            //$cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'exam_user_paper_data';
            //$cacheFile = 'exam_user_paper_get_score.cache_' . $examUserPaperId . '';
            $sql = "SELECT COUNT(*) FROM ".self::TableName_LotteryUser." WHERE UserId=:UserId AND TableType=:TableType AND TableId=:TableId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId", $userId);
            $dataProperty->AddField("TableType", $tableType);
            $dataProperty->AddField("TableId", $tableId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty,false,"","");
        }
        return $result;
    }
} 