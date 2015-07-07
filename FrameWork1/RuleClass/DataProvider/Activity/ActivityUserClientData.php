<?php
/**
 * 客户端 活动成员 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Activity
 * @author 525
 */
class ActivityUserClientData extends BaseClientData {

    /**
     * 新增
     * @param int $userId
     * @param int $activityId
     * @return int
     */
    public function Create($userId,$activityId) {
        $result=-1;
        if($userId>0&&$activityId>0){
            $dataProperty=new DataProperty();
            $sql="INSERT INTO ".self::TableName_ActivityUser."(UserId,ActivityId,CreateDate)
                  VALUES (:UserId,:ActivityId,now()) ;";
            $dataProperty->AddField("UserId",$userId);
            $dataProperty->AddField("ActivityId",$activityId);
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 检查是否有重复项
     * @param int $userId
     * @param int $activityId
     * @return int
     */
    public function IsRepeat($userId,$activityId){
        $result=0;
        if($userId>0&&$activityId>0){
            $dataProperty=new DataProperty();
            $sql="SELECT COUNT(*) FROM ".self::TableName_ActivityUser."
                  WHERE UserId=:UserId AND ActivityId=:ActivityId ;";
            $dataProperty->AddField("UserId",$userId);
            $dataProperty->AddField("ActivityId",$activityId);
            $result = $this->dbOperator->GetInt($sql, $dataProperty);
        }
        return $result;
    }
} 