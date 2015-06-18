<?php
/**
 * 前台 活动成员 数据类
 *
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Activity
 * @author 525
 */
class ActivityUserPublicData extends BasePublicData{


    /**
     * 新增
     * @param $userId
     * @param $activityId
     * @param $createDate
     * @return int
     */
    public function Create($userId,$activityId,$createDate) {
        $result=-1;
        if($userId>0&&$activityId>0){
            $dataProperty=new DataProperty();
            $sql="INSERT INTO ".self::TableName_ActivityUser."(UserId,ActivityId,CreateDate) VALUES (:UserId,:ActivityId,:CreateDate) ;";
            $dataProperty->AddField("UserId",$userId);
            $dataProperty->AddField("ActivityId",$activityId);
            $dataProperty->AddField("CreateDate",$createDate);
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 检查是否有重复项
     * @param $userId
     * @param $activityId
     * @return int
     */
    public function IsRepeat($userId,$activityId){
        $result=0;
        if($userId>0&&$activityId>0){
            $dataProperty=new DataProperty();
            $sql="SELECT COUNT(*) FROM ".self::TableName_ActivityUser." WHERE UserId=:UserId AND ActivityId=:ActivityId ;";
            $dataProperty->AddField("UserId",$userId);
            $dataProperty->AddField("ActivityId",$activityId);
            $result = $this->dbOperator->GetInt($sql, $dataProperty);
        }
        return $result;
    }
} 