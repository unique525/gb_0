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
     * 删除
     * @param int $activityUserId 活动会员Id
     * @param int $userId 会员Id
     * @return int 影响行数
     */
    public function Delete($activityUserId,$userId){
        $result = -1;
        if($activityUserId > 0 && $userId > 0){
            $sql = "DELETE FROM ".self::TableName_ActivityUser." WHERE UserId = :UserId AND ActivityUserId = :ActivityUserId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId",$userId);
            $dataProperty->AddField("ActivityUserId",$activityUserId);
            $result = $this->dbOperator->Execute($sql,$dataProperty);
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

    /**
     * 根据活动id获取列表数据集
     * @param int $activityId 活动id
     * @param int $pageBegin
     * @param int $pageSize 显示的条数
     * @param string $order 排序方式
     * @return array|null 列表数据集
     */
    public function GetListByActivityId($activityId, $pageBegin, $pageSize , $order = ""){
        $result = null;

        if($activityId >0){
            switch($order){
                default:
                    $order = "ORDER BY ".self::TableId_ActivityUser." DESC,Createdate DESC";
                    break;
            }
            $sql = "SELECT t1.*,t2.UserName
                    FROM ".self::TableName_ActivityUser." t1 left outer join ".self::TableName_User." t2
                    ON t1.UserId=t2.UserId
                    WHERE t1.ActivityId=:ActivityId $order LIMIT " . $pageBegin . "," . $pageSize . ";";

            $dataProperty = new DataProperty();
            $dataProperty->AddField("ActivityId", $activityId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }
} 