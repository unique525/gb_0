<?php
/**
 * 前台 获奖用户 数据类
 *
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Lottery
 * @author 525
 */
class LotteryAwardUserPublicData extends BasePublicData {


    /**
     * 新增
     * @param int $lotteryId
     * @param int $lotterySetId
     * @param int $lotterySetGroup
     * @param int $userId
     * @param string $createDate
     * @return int
     */
    public function Create($lotteryId,$lotterySetId,$lotterySetGroup,$userId,$createDate){
        $result=-1;
        if ($lotteryId>0) {
            $sql="INSERT INTO ".self::TableName_LotteryAwardUser."
                (LotteryId,LotterySetId,LotterySetGroup,UserId,CreateDate)
                VALUES(:LotteryId,:LotterySetId,:LotterySetGroup,:UserId,:CreateDate);";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("LotteryId",$lotteryId);
            $dataProperty->AddField("LotterySetId",$lotterySetId);
            $dataProperty->AddField("LotterySetGroup",$lotterySetGroup);
            $dataProperty->AddField("UserId",$userId);
            $dataProperty->AddField("CreateDate",$createDate);
            $result=$this->dbOperator->LastInsertId($sql,$dataProperty);
        }
        return $result;
    }


    /**
     * 获取目前为止获得某一奖的人数
     * @param $lotterySetId
     * @return int
     */
    public function GetCountOfOneLotterySet($lotterySetId){
        $result=0;
        if($lotterySetId>0){
            $sql="SELECT COUNT(*) FROM ".self::TableName_LotteryAwardUser."
             WHERE LotterySetId=:LotterySetId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("LotterySetId",$lotterySetId);
            $result=$this->dbOperator->GetInt($sql,$dataProperty);
        }
        return $result;
    }


    /**
     * 获取同一用户获得某一奖的次数（避免重复中大奖）
     * @param $lotteryId
     * @param $lotterySetGroup
     * @param $userId
     * @return int
     */
    public function GetCountOfOneUser($lotteryId,$lotterySetGroup,$userId){
        $result=-1;
        if($lotteryId>0&&$userId>=0){
            $sql="SELECT COUNT(*) FROM ".self::TableName_LotteryAwardUser."
             WHERE LotteryId=:LotteryId AND LotterySetGroup=:LotterySetGroup AND UserId=:UserId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("LotteryId",$lotteryId);
            $dataProperty->AddField("LotterySetGroup",$lotterySetGroup);
            $dataProperty->AddField("UserId",$userId);
            $result=$this->dbOperator->GetInt($sql,$dataProperty);
        }
        return $result;
    }
} 