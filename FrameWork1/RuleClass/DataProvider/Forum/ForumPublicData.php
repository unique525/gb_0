<?php

/**
 * 前台 论坛 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Forum
 * @author zhangchi
 */
class ForumPublicData extends BasePublicData {



    /**
     * 更新版块的最后回复信息（发表主题时）
     * @param int $forumId
     * @param int $lastForumTopicId
     * @param string $lastForumTopicTitle
     * @param string $lastUserName
     * @param int $lastUserId
     * @param string $lastPostTime
     * @param string $lastPostInfo
     * @return int 执行结果
     */
    public function UpdateForumInfoWhenCreateTopic($forumId, $lastForumTopicId, $lastForumTopicTitle, $lastUserName, $lastUserId, $lastPostTime, $lastPostInfo) {

        $result = -1;
        if($forumId>0){
            $sql = "UPDATE " . self::TableName_Forum . "
                    SET NewCount=NewCount+1,TopicCount=TopicCount+1,LastForumTopicId=:LastForumTopicId,LastForumTopicTitle=:LastForumTopicTitle,LastUserName=:LastUserName,LastUserId=:LastUserId,LastPostTime=:LastPostTime,LastPostInfo=:LastPostInfo
                    WHERE ForumId=:ForumId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ForumId", $forumId);
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
     * 根据版块等级取得版块列表
     * @param int $siteId 站点id
     * @param int $forumRank 版块等级
     * @param bool $withCache
     * @return array|null 版块列表
     */
    public function GetListByForumRank(
        $siteId,
        $forumRank,
        $withCache = false
    ) {
        $result = null;
        if($siteId>0 && $forumRank>=0){

            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'forum_data';
            $cacheFile = 'forum_get_list_by_forum_rank.cache_' . $siteId . '_' . $forumRank;

            $sql = "
            SELECT f.*,
                        uf1.UploadFilePath AS ForumPic1UploadFilePath,

                        uf2.UploadFilePath AS ForumPic2UploadFilePath

            FROM
            " . self::TableName_Forum . " f
            LEFT OUTER JOIN " .self::TableName_UploadFile." uf1 ON (f.ForumPic1UploadFileId=uf1.UploadFileId)
            LEFT OUTER JOIN " .self::TableName_UploadFile." uf2 ON (f.ForumPic2UploadFileId=uf2.UploadFileId)

            WHERE f.State<".ForumData::STATE_REMOVED." AND f.ForumRank=:ForumRank AND f.SiteId=:SiteId ORDER BY f.Sort DESC;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ForumRank", $forumRank);
            $dataProperty->AddField("SiteId", $siteId);

            $result = $this->GetInfoOfArrayList($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);

        }


        return $result;
    }

    /**
     * 
     * @param int $siteId
     * @param int $parentId
     * @return array
     */
    public function GetListByParentId($siteId, $parentId) {
        $sql = "SELECT * FROM " . self::TableName_Forum . " WHERE ParentId=:ParentId AND SiteId=:SiteId ORDER BY Sort DESC";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("ParentId", $parentId);
        $dataProperty->AddField("SiteId", $siteId);
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得论坛最后回复信息
     * @param int $forumId 论坛id
     * @param bool $withCache 是否从缓冲中取
     * @return string 论坛最后回复信息
     */
    public function GetLastPostInfo($forumId, $withCache)
    {
        $result = "";
        if ($forumId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'forum_data';
            $cacheFile = 'channel_get_last_post_info.cache_' . $forumId . '';
            $sql = "SELECT ChannelName FROM " . self::TableName_Forum . " WHERE ForumId=:ForumId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ForumId", $forumId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
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

}

?>
