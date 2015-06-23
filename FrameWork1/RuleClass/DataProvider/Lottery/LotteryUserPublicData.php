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
     * @param $createDate
     * @return int
     */
    public function Create($lotteryId,$userId,$createDate){
        $result=-1;
        if ($lotteryId>0) {
            $sql="INSERT INTO ".self::TableName_LotteryUser." (LotteryId,UserId,CreateDate) VALUES(:LotteryId,:UserId,:CreateDate);";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("LotteryId",$lotteryId);
            $dataProperty->AddField("UserId",$userId);
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
} 