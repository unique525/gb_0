<?php
/**
 * 后台管理 活动 数据类
 *
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Activity
 * @author 525
 */
class ActivityManageData extends BaseManageData{

    const STATE_FINAL_VERIFY=30;
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
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }
    /**
     * 获取活动分页列表
     * @param int $channelId 频道id
     * @param int $pageBegin 起始页码
     * @param int $pageSize 每页大小
     * @param int $allCount 总大小
     * @param string $searchKey 搜索关键字
     * @return array 活动数据集
     */
    public function GetListPager($channelId, $pageBegin, $pageSize, &$allCount, $searchKey) {
        $result=-1;
        if($channelId>0){
            $searchSql = "";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);

            if (strlen($searchKey) > 0 && $searchKey != "undefined") {
                $searchSql.=" AND ActivityTitle LIKE :SearchKey ";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            }

            $sql = "
                SELECT
                *
                FROM
                " . self::TableName_Activity . "
                WHERE ChannelId=:ChannelId " . $searchSql . " ORDER BY Sort DESC, CreateDate DESC LIMIT " . $pageBegin . "," . $pageSize . " ;";

            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
            $sqlCount = "SELECT count(*) FROM " . self::TableName_Activity . " WHERE ChannelId=:ChannelId " . $searchSql . " ;";
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
            $sql = "SELECT t.*,t1.ActivityClassName
            FROM " . self::TableName_Activity . " t left outer join " . self::TableName_ActivityClass ." t1
            ON t.ActivityClassId=t1.ActivityClassId
            WHERE ActivityId = :ActivityId ;";
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
     * @param int $activityId 频道id
     * @param int $titlePic1UploadFileId 题图1上传文件id
     * @param int $titlePic2UploadFileId 题图2上传文件id
     * @param int $titlePic3UploadFileId 题图3上传文件id
     * @return int 操作结果
     */
    public function ModifyTitlePic($activityId, $titlePic1UploadFileId, $titlePic2UploadFileId, $titlePic3UploadFileId)
    {
        $result = -1;
        if($activityId>0){
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_Activity . " SET
                    TitlePic1UploadFileId = :TitlePic1UploadFileId,
                    TitlePic2UploadFileId = :TitlePic2UploadFileId,
                    TitlePic3UploadFileId = :TitlePic3UploadFileId

                    WHERE ActivityId = :ActivityId

                    ;";
            $dataProperty->AddField("TitlePic1UploadFileId", $titlePic1UploadFileId);
            $dataProperty->AddField("TitlePic2UploadFileId", $titlePic2UploadFileId);
            $dataProperty->AddField("TitlePic3UploadFileId", $titlePic3UploadFileId);
            $dataProperty->AddField("ActivityId", $activityId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }


    /**
     * 修改活动题图1的上传文件id
     * @param int $activityId 活动id
     * @param int $titlePicUploadFileId 题图上传文件id
     * @return int 操作结果
     */
    public function ModifyTitlePic1($activityId, $titlePicUploadFileId)
    {
        $result = -1;
        if($activityId>0){
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_Activity . " SET
                    TitlePic1UploadFileId = :TitlePic1UploadFileId

                    WHERE ActivityId = :ActivityId

                    ;";
            $dataProperty->AddField("TitlePic1UploadFileId", $titlePicUploadFileId);
            $dataProperty->AddField("ActivityId", $activityId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }

    /**
     * 修改活动题图2的上传文件id
     * @param int $activityId 活动id
     * @param int $titlePicUploadFileId 题图上传文件id
     * @return int 操作结果
     */
    public function ModifyTitlePic2($activityId, $titlePicUploadFileId)
    {
        $result = -1;
        if($activityId>0){
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_Activity . " SET
                    TitlePic2UploadFileId = :TitlePic2UploadFileId

                    WHERE ActivityId = :ActivityId

                    ;";
            $dataProperty->AddField("TitlePic2UploadFileId", $titlePicUploadFileId);
            $dataProperty->AddField("ActivityId", $activityId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }

    /**
     * 修改活动题图3的上传文件id
     * @param int $activityId 活动id
     * @param int $titlePicUploadFileId 题图上传文件id
     * @return int 操作结果
     */
    public function ModifyTitlePic3($activityId, $titlePicUploadFileId)
    {
        $result = -1;
        if($activityId>0){
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_Activity . " SET
                    TitlePic3UploadFileId = :TitlePic3UploadFileId

                    WHERE ActivityId = :ActivityId

                    ;";
            $dataProperty->AddField("TitlePic3UploadFileId", $titlePicUploadFileId);
            $dataProperty->AddField("ActivityId", $activityId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }

    /**
     * 修改活动状态
     * @param string $activityId 题目Id
     * @param string $state 状态
     * @return int 执行结果
     */
    public function ModifyState($activityId,$state) {
        $result = -1;
        if ($activityId < 0) {
            return $result;
        }

        $sql = "UPDATE " . self::TableName_Activity . " SET State=:State WHERE ActivityId=:ActivityId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("ActivityId", $activityId);
        $dataProperty->AddField("State", $state);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return  $result;
    }

    /**
     * 同步cst_activity_user表中已通过的人员数到cst_activity表的JoinUserCount字段
     * 同步cst_activity_user表中总人员数到cst_activity表的ApplyUserCount字段
     * @param $activityId int    活动id
     * @return int        int    执行结果
     */
    public function SyncModifyState($activityId)
    {
        $result = -1;

        //同步已参加人员数
        $dataProperty  = new DataProperty();
        $dataProperty->AddField("ActivityId",$activityId);
        $sql = 'SELECT count(State)' .
            ' FROM '  . self::TableName_ActivityUser .
            ' WHERE ActivityId=:ActivityId AND State=1;';
        $joinUserCount = $this->dbOperator->GetInt($sql, $dataProperty);

        if ($joinUserCount >= 0){
            $dataProperty->AddField('JoinUserCount', $joinUserCount);
            $sql = 'UPDATE ' . self::TableName_Activity .
                    ' SET JoinUserCount=:JoinUserCount'.
                    ' WHERE ActivityId=:ActivityId';
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        $dataProperty->RemoveField("JoinUserCount");

        //同步总人数
        $sql = 'SELECT COUNT(*)'.
                ' FROM ' . self::TableName_ActivityUser .
                ' WHERE ActivityId=:ActivityId;';

        $applyUserCount = $this->dbOperator->GetInt($sql, $dataProperty);

        if($applyUserCount >= 0){
            $dataProperty->AddField("ApplyUserCount",$applyUserCount);

            $sql = 'UPDATE ' . self::TableName_Activity .
                ' SET ApplyUserCount=:ApplyUserCount'.
                ' WHERE ActivityId=:ActivityId';
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
    return $result;
    }

    /**
     * 更新报名人数
     * @param int $activityId 活动Id
     * @param int $numberOfSignUp 报名人数
     * @return int 执行结果
     */
    public function UpdateNumberOfSignUp($activityId,$numberOfSignUp) {
        $result = -1;
        if ($activityId < 0||$numberOfSignUp<0) {
            return $result;
        }
        $sql = "UPDATE " . self::TableName_Activity . " SET NumberOfSignUp=:NumberOfSignUp WHERE ActivityId=:ActivityId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("ActivityId", $activityId);
        $dataProperty->AddField("NumberOfSignUp", $numberOfSignUp);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }
    /**
     * 更新参加人数
     * @param int $activityId 活动Id
     * @param int $userCount 参加人数
     * @return int 执行结果
     */
    public function UpdateUserCount($activityId,$userCount) {
        $result = -1;
        if ($activityId < 0||$userCount<0) {
            return $result;
        }
        $sql = "UPDATE " . self::TableName_Activity . " SET UserCount=:UserCount WHERE ActivityId=:ActivityId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("ActivityId", $activityId);
        $dataProperty->AddField("UserCount", $userCount);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }


    /**
     * 取得活动审核状态
     * @param int $activityId 频道id
     * @param bool $withCache 是否从缓冲中取
     * @return int 文档的状态
     */
    public function GetState($activityId, $withCache=FALSE)
    {
        $result = -1;
        if ($activityId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'activity_data';
            $cacheFile = 'activity_get_state.cache_' . $activityId . '';
            $sql = "SELECT State FROM " . self::TableName_Activity . " WHERE ActivityId=:ActivityId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ActivityId", $activityId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }


    /**
     * 取得活动所属频道id
     * @param int $activityId 活动id
     * @param bool $withCache 是否从缓冲中取
     * @return int 所属频道id
     */
    public function GetChannelId($activityId, $withCache=FALSE)
    {
        $result = -1;
        if ($activityId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'activity_data';
            $cacheFile = 'activity_get_channel_id.cache_' . $activityId . '';
            $sql = "SELECT ChannelId FROM " . self::TableName_Activity . " WHERE ActivityId=:ActivityId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ActivityId", $activityId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 修改发布时间只有发布时间为空时才进行操作
     * @param int $activityId 活动id
     * @param int $publishDate 发布时间
     * @return int 操作结果
     */
    public function ModifyPublishDate($activityId, $publishDate)
    {
        $result = 0;
        if ($activityId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_Activity . "
                SET

                    PublishDate=:PublishDate

                WHERE
                        ActivityId=:ActivityId
                    AND PublishDate is NULL

                    ;";


            $dataProperty->AddField("ActivityId", $activityId);
            $dataProperty->AddField("PublishDate", $publishDate);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 取得活动发布时间
     * @param int $activityId 活动id
     * @param bool $withCache 是否从缓冲中取
     * @return string 文档的发布时间
     */
    public function GetPublishDate($activityId, $withCache=FALSE)
    {
        $result = "";
        if ($activityId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'activity_data';
            $cacheFile = 'activity_get_publish_date.cache_' . $activityId . '';
            $sql = "SELECT PublishDate FROM " . self::TableName_Activity . " WHERE ActivityId=:ActivityId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ActivityId", $activityId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }



    /**
     * 拖动排序
     * @param array $arrActivityId 待处理的文档编号数组
     * @return int 操作结果
     */
    public function ModifySortForDrag($arrActivityId)
    {
        if (count($arrActivityId) > 1) { //大于1条时排序才有意义
            $strActivityId = join(',', $arrActivityId);
            $strActivityId = Format::FormatSql($strActivityId);
            $sql = "SELECT max(Sort) FROM " . self::TableName_Activity . " WHERE ActivityId IN ($strActivityId);";
            $maxSort = $this->dbOperator->GetInt($sql, null);
            $arrSql = array();
            for ($i = 0; $i < count($arrActivityId); $i++) {
                $newSort = $maxSort - $i;
                if ($newSort < 0) {
                    $newSort = 0;
                }
                $newSort = intval($newSort);
                $activityId = intval($arrActivityId[$i]);
                $sql = "UPDATE " . self::TableName_Activity . " SET Sort=$newSort WHERE ActivityId=$activityId;";
                $arrSql[] = $sql;
            }
            return $this->dbOperator->ExecuteBatch($arrSql, null);
        }else{
            return -1;
        }
    }


    /**
     * 新增时修改排序号到当前频道的最大排序
     * @param int $channelId
     * @param int $activityId
     * @return int 影响的记录行数
     */
    public function ModifySortWhenCreate($channelId, $activityId) {
        $result = -1;
        if($channelId >0 && $activityId>0){
            $dataProperty = new DataProperty();
            $sql = "SELECT max(Sort) FROM " . self::TableName_Activity . " WHERE ChannelId=:ChannelId;";
            $dataProperty->AddField("ChannelId", $channelId);
            $maxSort = $this->dbOperator->GetInt($sql, $dataProperty);
            $newSort = $maxSort + 1;
            $dataProperty = new DataProperty();
            $dataProperty->AddField("Sort", $newSort);
            $dataProperty->AddField("ActivityId", $activityId);
            $sql = "UPDATE " . self::TableName_Activity . " SET Sort=:Sort WHERE ActivityId=:ActivityId;";
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }
}
?>