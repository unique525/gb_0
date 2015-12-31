<?php

/**
 * 后台管理 论坛 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Forum
 * @author zhangchi
 */
class ForumManageData extends BaseManageData {

    /**
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_Forum){
        return parent::GetFields(self::TableName_Forum);
    }

    /**
     * 新增论坛版块
     * @param array $httpPostData $_POST数组
     * @return int 新增的论坛版块id
     */
    public function Create($httpPostData) {
        $result = -1;
        $dataProperty = new DataProperty();
        $addFieldName = "";
        $addFieldValue = "";
        $preNumber = "";
        $addFieldNames = array();
        $addFieldValues = array();

        if (!empty($httpPostData)) {
            $sql = parent::GetInsertSql(
                $httpPostData,
                self::TableName_Forum,
                $dataProperty,
                $addFieldName,
                $addFieldValue,
                $preNumber,
                $addFieldNames,
                $addFieldValues
            );
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 修改版块
     * @param array $httpPostData $_POST数组
     * @param int $forumId 版块id
     * @return int 返回影响的行数
     */
    public function Modify($httpPostData, $forumId) {
        $dataProperty = new DataProperty();
        $addFieldNames = array();
        $addFieldValues = array();
        $sql = parent::GetUpdateSql($httpPostData, self::TableName_Forum, self::TableId_Forum, $forumId, $dataProperty, "", "", "", $addFieldNames, $addFieldValues);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }
    
    /**
     * 修改版块状态
     * @param int $forumId 论坛版块id
     * @param int $state 状态
     * @return int 操作结果
     */
    public function ModifyState($forumId, $state) {
        $result = 0;
        if ($forumId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_Forum . " SET `State`=:State WHERE ForumId=:ForumId;";
            $dataProperty->AddField("ForumId", $forumId);
            $dataProperty->AddField("State", $state);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 修改版块图片的上传文件id
     * @param int $forumId 频道id
     * @param int $forumPic1UploadFileId 题图1上传文件id
     * @param int $forumPic2UploadFileId 题图2上传文件id
     * @return int 操作结果
     */
    public function ModifyForumPic($forumId, $forumPic1UploadFileId, $forumPic2UploadFileId)
    {
        $result = -1;
        if($forumId>0){
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_Forum . " SET
                    ForumPic1UploadFileId = :ForumPic1UploadFileId,
                    ForumPic2UploadFileId = :ForumPic2UploadFileId

                    WHERE ForumId = :ForumId

                    ;";
            $dataProperty->AddField("ForumPic1UploadFileId", $forumPic1UploadFileId);
            $dataProperty->AddField("ForumPic2UploadFileId", $forumPic2UploadFileId);
            $dataProperty->AddField("ForumId", $forumId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }

    /**
     * 修改题图1的上传文件id
     * @param int $forumId 版块id
     * @param int $forumPic1UploadFileId 题图1上传文件id
     * @return int 操作结果
     */
    public function ModifyForumPic1UploadFileId($forumId, $forumPic1UploadFileId)
    {
        $result = -1;
        if ($forumId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_Forum . " SET
                    ForumPic1UploadFileId = :ForumPic1UploadFileId
                    WHERE ForumId = :ForumId
                    ;";
            $dataProperty->AddField("ForumPic1UploadFileId", $forumPic1UploadFileId);
            $dataProperty->AddField("ForumId", $forumId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }

    /**
     * 修改题图2的上传文件id
     * @param int $forumId 版块id
     * @param int $forumPic2UploadFileId 题图2上传文件id
     * @return int 操作结果
     */
    public function ModifyForumPic2UploadFileId($forumId, $forumPic2UploadFileId)
    {
        $result = -1;
        if ($forumId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_Forum . " SET
                    ForumPic2UploadFileId = :ForumPic2UploadFileId
                    WHERE ForumId = :ForumId
                    ;";
            $dataProperty->AddField("ForumPic2UploadFileId", $forumPic2UploadFileId);
            $dataProperty->AddField("ForumId", $forumId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }

    /**
     * 重置是否操作为否
     * @param int $siteId 站点id
     * @return int 操作结果
     */
    public function ResetIsOperate($siteId)
    {
        $result = -1;
        if ($siteId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_Forum . " SET
                    IsOperate = 0
                    WHERE SiteId = :SiteId
                    ;";
            $dataProperty->AddField("SiteId", $siteId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }

    /**
     * 修改是否操作
     * @param int $forumId 版块id
     * @param int $isOperate 是否操作
     * @return int 操作结果
     */
    public function ModifyIsOperate($forumId, $isOperate)
    {
        $result = -1;
        if ($forumId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_Forum . " SET
                    IsOperate = :IsOperate
                    WHERE ForumId = :ForumId
                    ;";
            $dataProperty->AddField("IsOperate", $isOperate);
            $dataProperty->AddField("ForumId", $forumId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }

    /**
     * 更新版块的最后回复信息（发表主题时）
     * @param int $forumId
     * @param int $newCount
     * @param int $topicCount
     * @param int $postCount
     * @param int $lastForumTopicId
     * @param string $lastForumTopicTitle
     * @param string $lastUserName
     * @param int $lastUserId
     * @param string $lastPostTime
     * @param string $lastPostInfo
     * @return int 执行结果
     */
    public function UpdateForumInfo(
        $forumId,
        $newCount,
        $topicCount,
        $postCount,
        $lastForumTopicId,
        $lastForumTopicTitle,
        $lastUserName,
        $lastUserId,
        $lastPostTime,
        $lastPostInfo
    ) {

        $result = -1;
        if($forumId>0){
            $sql = "UPDATE " . self::TableName_Forum . "
                    SET
                        NewCount=:NewCount,
                        TopicCount=:TopicCount,
                        PostCount=:PostCount,
                        LastForumTopicId=:LastForumTopicId,
                        LastForumTopicTitle=:LastForumTopicTitle,
                        LastUserName=:LastUserName,
                        LastUserId=:LastUserId,
                        LastPostTime=:LastPostTime,
                        LastPostInfo=:LastPostInfo
                    WHERE ForumId=:ForumId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ForumId", $forumId);
            $dataProperty->AddField("NewCount", $newCount);
            $dataProperty->AddField("TopicCount", $topicCount);
            $dataProperty->AddField("PostCount", $postCount);
            $dataProperty->AddField("LastForumTopicId", $lastForumTopicId);
            $dataProperty->AddField("LastForumTopicTitle", $lastForumTopicTitle);
            $dataProperty->AddField("LastUserName", $lastUserName);
            $dataProperty->AddField("LastUserId", $lastUserId);
            $dataProperty->AddField("LastPostTime", $lastPostTime);
            $dataProperty->AddField("LastPostInfo", $lastPostInfo);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }


    /**
     * 取得上级版块名称
     * @param int $forumId 论坛版块id
     * @param bool $withCache 是否从缓冲中取
     * @return string 上级版块名称
     */
    public function GetForumName($forumId, $withCache) {
        $result = -1;
        if ($forumId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'forum_data';
            $cacheFile = 'forum_get_forum_name.cache_' . $forumId . '';
            $sql = "SELECT ForumName FROM " . self::TableName_Forum . " WHERE ForumId =:ForumId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField(self::TableId_Forum, $forumId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 返回一行数据
     * @param int $forumId 论坛id
     * @return array|null 取得对应数组
     */
    public function GetOne($forumId)
    {
        $result = null;
        if ($forumId > 0) {
            $sql = "SELECT * FROM " . self::TableName_Forum . " WHERE ForumId=:ForumId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ForumId", $forumId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 取得所属站点id
     * @param int $forumId 论坛id
     * @param bool $withCache 是否从缓冲中取
     * @return int 站点id
     */
    public function GetSiteId($forumId, $withCache)
    {
        $result = -1;
        if ($forumId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'forum_data';
            $cacheFile = 'forum_get_site_id.cache_' . $forumId . '';
            $sql = "SELECT SiteId FROM " . self::TableName_Forum . " WHERE ForumId=:ForumId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ForumId", $forumId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 取得上级版块名称
     * @param int $forumId 论坛版块id
     * @param bool $withCache 是否从缓冲中取
     * @return string 上级版块名称
     */
    public function GetParentName($forumId, $withCache) {
        $result = -1;
        if ($forumId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'forum_data';
            $cacheFile = 'forum_get_parent_name.cache_' . $forumId . '';
            $sql = "SELECT ForumName FROM " . self::TableName_Forum . "
                    WHERE ForumId =
                       (SELECT ParentId FROM " . self::TableName_Forum . " WHERE ForumId=:ForumId);";
            $dataProperty = new DataProperty();
            $dataProperty->AddField(self::TableId_Forum, $forumId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 根据版块等级取得版块列表
     * @param int $siteId 站点id
     * @return array 版块列表
     */
    public function GetListForReset($siteId) {
        $result = null;
        if($siteId>0){
            $sql = "SELECT ForumId FROM " . self::TableName_Forum . " WHERE SiteId=:SiteId
             AND IsOperate=0
             LIMIT 1;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 根据版块等级取得版块列表
     * @param int $siteId 站点id
     * @param int $forumRank 版块等级
     * @return array 版块列表
     */
    public function GetListByRank($siteId, $forumRank) {
        $result = null;
        if($siteId>0 && $forumRank>=0){
            $sql = "SELECT * FROM " . self::TableName_Forum . " WHERE ForumRank=:ForumRank AND SiteId=:SiteId ORDER BY Sort DESC;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ForumRank", $forumRank);
            $dataProperty->AddField("SiteId", $siteId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 取得题图1的上传文件id
     * @param int $forumId 论坛版块id
     * @param bool $withCache 是否从缓冲中取
     * @return int 题图1的上传文件id
     */
    public function GetForumPic1UploadFileId($forumId, $withCache)
    {
        $result = -1;
        if ($forumId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'forum_data';
            $cacheFile = 'forum_get_forum_pic_1_upload_file_id.cache_' . $forumId . '';
            $sql = "SELECT ForumPic1UploadFileId FROM " . self::TableName_Forum . " WHERE ForumId = :ForumId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ForumId", $forumId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 取得题图2的上传文件id
     * @param int $forumId 论坛版块id
     * @param bool $withCache 是否从缓冲中取
     * @return int 题图2的上传文件id
     */
    public function GetForumPic2UploadFileId($forumId, $withCache)
    {
        $result = -1;
        if ($forumId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'forum_data';
            $cacheFile = 'forum_get_forum_pic_2_upload_file_id.cache_' . $forumId . '';
            $sql = "SELECT ForumPic2UploadFileId FROM " . self::TableName_Forum . " WHERE ForumId = :ForumId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ForumId", $forumId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }
}

?>
