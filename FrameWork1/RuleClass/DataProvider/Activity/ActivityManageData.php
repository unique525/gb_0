<?php
/**
 * 后台管理 活动 数据类
 *
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Activity
 * @author 525
 */
class ActivityManageData extends BaseManageData{

    /**
     * 新增活动
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
            $sql = parent::GetInsertSql($httpPostData,self::TableName_Activity, $dataProperty, $addFieldName, $addFieldValue, $preNumber, $addFieldNames, $addFieldValues);
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        }
        return $result;
    }
    /**
     * 修改
     * @param array $httpPostData $_post数组
     * @param string $activityId 活动id
     * @return int 执行结果
     */
    public function Modify($httpPostData,$activityId) {
        $dataProperty = new DataProperty();
        $sql = parent::GetUpdateSql($httpPostData, self::TableName_Activity, self::TableId_Activity, $activityId, $dataProperty);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->Execute($sql, $dataProperty);
        return $result;
    }
    /**
     * 获取活动分页列表
     * @param int $channelId 频道id
     * @param int $pageBegin 起始页码
     * @param int $pageSize 每页大小
     * @param int $allCount 总大小
     * @return array 活动数据集
     */
    public function GetListPager($channelId, $pageBegin, $pageSize, &$allCount) {
        $result=-1;
        if($channelId>0){
            $searchSql = "";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);

            $sql = "
                SELECT
                *
                FROM
                " . self::TableName_Activity . "
                WHERE ChannelId=:ChannelId " . $searchSql . " ORDER BY Sort DESC, CreateDate DESC LIMIT " . $pageBegin . "," . $pageSize . " ;";

            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
            $sqlCount = "SELECT count(*) FROM " . self::TableName_Activity . " WHERE ChannelId=:ChannelId AND state<100 " . $searchSql . " ;";
            $allCount = $this->dbOperator->GetInt($sqlCount, $dataProperty);
        }
        return $result;
    }

    /**
     * 通过ID获取一条记录
     * @param int $activityId 活动id
     * @return array 活动数据
     */
    public function GetOne($activityId) {
        $result=-1;
        if($activityId>0){
            $sql = "SELECT * FROM " . self::TableName_Activity . " WHERE ActivityId = :ActivityId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ActivityId", $activityId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_Activity){
        return parent::GetFields(self::TableName_Activity);
    }


    /**
     * 修改活动题图的上传文件id
     * @param int $activityId 活动id
     * @param int $titlePicUploadFileId 题图上传文件id
     * @return int 操作结果
     */
    public function ModifyTitlePic($activityId, $titlePicUploadFileId)
    {
        $result = -1;
        if($activityId>0){
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_Activity . " SET
                    TitlePicUploadFileId = :TitlePicUploadFileId

                    WHERE ActivityId = :ActivityId

                    ;";
            $dataProperty->AddField("TitlePicUploadFileId", $titlePicUploadFileId);
            $dataProperty->AddField("ActivityId", $activityId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }
}
?>