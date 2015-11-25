<?php
/**
 * 后台管理 活动成员 数据类
 *
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Activity
 * @author 525
 */
class ActivityUserManageData extends BaseManageData{
    /**
     * 新增活动成员
     * @param array $httpPostData $_post数组
     * @return int 新增活动id
     */
    public function Create($httpPostData) {
        $result=-1;
        $dataProperty = new DataProperty();
        $addFieldName = "";
        $addFieldValue = "";
        $preNumber = "";
        $addFieldNames = array();
        $addFieldValues = array();
        if (!empty($httpPostData)) {
            $sql = parent::GetInsertSql($httpPostData,self::TableName_ActivityUser, $dataProperty, $addFieldName, $addFieldValue, $preNumber, $addFieldNames, $addFieldValues);
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 修改
     * @param array $httpPostData $_post数组
     * @param string $activityUserId 活动成员id
     * @return int 执行结果
     */
    public function Modify($httpPostData,$activityUserId) {
        $dataProperty = new DataProperty();
        $sql = parent::GetUpdateSql($httpPostData, self::TableName_ActivityUser, self::TableId_ActivityUser, $activityUserId, $dataProperty);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    public function Delete($activityUserId){

        $dataProperty = new DataProperty();
        $dataProperty->AddField("ActivityUserId", $activityUserId);
        $sql = 'DELETE FROM ' . self::TableName_ActivityUser .
                ' WHERE ActivityUserId=:ActivityUserId;';
        $result = $this->dbOperator->Execute($sql,$dataProperty);

        return $result;
    }

    /**
     * 获取活动成员分页列表
     * @param int $activityId 活动id
     * @param int $pageBegin 起始页码
     * @param int $pageSize 每页大小
     * @param int $allCount 总大小
     * @return array 活动成员数据集
     */
    public function GetListPager($activityId, $pageBegin, $pageSize, &$allCount) {
        $result=-1;
        if($activityId>0){
            $searchSql = "";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ActivityId", $activityId);

            $sql = "
                SELECT
                *
                FROM
                " . self::TableName_ActivityUser . "
                WHERE ActivityId=:ActivityId " . $searchSql . " ORDER BY Sort DESC, CreateDate DESC LIMIT " . $pageBegin . "," . $pageSize . " ;";

            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
            $sqlCount = "SELECT count(*) FROM " . self::TableName_ActivityUser . " WHERE ActivityId=:ActivityId AND state<100 " . $searchSql . " ;";
            $allCount = $this->dbOperator->GetInt($sqlCount, $dataProperty);
        }
        return $result;
    }

    /**
     * 获取活动成员分页列表
     * @param int $activityId 活动id
     * @param int $pageBegin 起始页码
     * @param int $pageSize 每页大小
     * @param int $searchKey 每页大小
     * @param int $allCount 总大小
     * @return array 活动成员数据集
     */
    public function GetUserListPager($activityId, $pageBegin, $pageSize, $searchKey, &$allCount) {
        $result=-1;
        if($activityId > 0){
            $searchSql = "";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ActivityId", $activityId);

            if (strlen($searchKey) > 0 && $searchKey != "undefined") {
                $searchSql.=" AND u.UserName LIKE :SearchKey ";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            }

            $sql = 'SELECT au.*,'.
                'u.UserName,'.
                'a.ActivityTitle'.
                ' FROM '.self::TableName_ActivityUser.' au'.
                ' INNER JOIN ' .self::TableName_User. ' u ON (u.UserId=au.UserId'.$searchSql.')'.
                ' LEFT OUTER JOIN ' . self::TableName_Activity . ' a ON (a.ActivityId=au.ActivityId)'.
                ' WHERE au.ActivityId=:ActivityId  '.
                ' ORDER BY au.ActivityUserId DESC'.
                ' LIMIT ' . $pageBegin . ',' . $pageSize . ';';
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

            $sqlCounts = 'SELECT COUNT(ActivityUserId)'.
                ' FROM '.self::TableName_ActivityUser.' au'.
                ' INNER JOIN ' .self::TableName_User. ' u ON (u.UserId=au.UserId'.$searchSql.')'.
                ' LEFT OUTER JOIN ' . self::TableName_Activity . ' a ON (a.ActivityId=au.ActivityId)'.
                ' WHERE au.ActivityId=:ActivityId  '.
                ' ORDER BY au.ActivityUserId DESC'.
                ' LIMIT ' . $pageBegin . ',' . $pageSize . ';';
            $allCount = $this->dbOperator->GetInt($sqlCounts, $dataProperty);
        }
        return $result;
    }

    /**
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_ActivityUser){
        return parent::GetFields(self::TableName_ActivityUser);
    }

    /**
     * 修改活动成员状态
     * @param string $activityUserId 活动成员Id
     * @param string $state 状态
     * @return int 执行结果
     */
    public function ModifyState($activityUserId,$state) {
        $result = -1;
        if ($activityUserId < 0) {
            return $result;
        }
        $sql = "UPDATE " . self::TableName_ActivityUser . " SET State=:State WHERE ActivityUserId=:ActivityUserId ;";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("ActivityUserId", $activityUserId);
        $dataProperty->AddField("State", $state);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 获取活动成员数量
     * @param int $activityId 活动成员Id
     * @param int $state 状态：0未审核 30通过审核 100已否
     * @return int 成员数量
     */
    public function GetUserCount($activityId,$state) {
        $result = -1;
        if ($activityId < 0) {
            return $result;
        }
        $sql = "SELECT COUNT(*) FROM" . self::TableName_ActivityUser . " WHERE ActivityId=:ActivityId AND State=:State ;";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("ActivityId", $activityId);
        $dataProperty->AddField("State", $state);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }
} 