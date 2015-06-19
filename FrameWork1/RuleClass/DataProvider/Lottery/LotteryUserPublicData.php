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
     * @param $userName
     * @param $userMobile
     * @param $createDate
     * @return int
     */
    public function Create($lotteryId,$userName,$userMobile,$createDate){
        $result=-1;
        if ($lotteryId>0) {
            $sql="INSERT INTO ".self::TableName_LotteryUser." (LotteryId,UserName,UserMobile,CreateDate) VALUES(:LotteryId,:UserName,:UserMobile,:CreateDate);";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("LotteryId",$lotteryId);
            $dataProperty->AddField("UserName",$userName);
            $dataProperty->AddField("UserMobile",$userMobile);
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

} 