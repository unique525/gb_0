<?php
/**
 * 前台 活动成员 数据类
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

    /**
     * 根据活动id获取列表数据集
     * @param int $activityId 活动id
     * @param int $topCount 显示的条数
     * @param string $order 排序方式
     * @return array|null 列表数据集
     */
    public function GetListByActivityId($activityId, $order = "", $topCount = null){
        $result = null;
        if ($topCount != null)
            $topCount = " limit " . $topCount;
        else $topCount = "";
        if($activityId >0){
            switch($order){
                default:
                    $order = "ORDER BY ".self::TableId_ActivityUser." DESC,Createdate DESC";
                    break;
            }
            $sql = "SELECT t1.*,t2.UserName
                    FROM ".self::TableName_ActivityUser." t1 left outer join ".self::TableName_User." t2
                    ON t1.UserId=t2.UserId
                    WHERE t1.ActivityId=:ActivityId "
                .$order
                .$topCount;
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ActivityId", $activityId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }
} 