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
     * 返回 array list 形式的单条数据
     * @param int $forumId
     * @return array
     */
    public function GetListByForumId($forumId) {
        $sql = "SELECT * FROM " . self::TableName_Forum . " WHERE ForumId=:ForumId AND State<".ForumData::STATE_REMOVED.";";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("ForumId", $forumId);
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        return $result;
    }

    /**
     * 返回 array list 形式的单条数据
     * @param int $forumId
     * @return array
     */
    public function GetListInForumId($forumId) {
        $forumId = Format::FormatSql($forumId);
        $sql = "SELECT * FROM " . self::TableName_Forum . "

                WHERE ForumId IN ($forumId) AND State<".ForumData::STATE_REMOVED." ORDER BY Sort DESC;";
        $dataProperty = null;
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        return $result;
    }

    /**
     *
     * @param int $siteId
     * @param int $parentId
     * @return array
     */
    public function GetListInParentId($siteId, $parentId) {
        $parentId = Format::FormatSql($parentId);
        $sql = "SELECT f.*,
                        uf1.UploadFilePath AS ForumPic1UploadFilePath,

                        uf2.UploadFilePath AS ForumPic2UploadFilePath

                FROM " . self::TableName_Forum . " f
            LEFT OUTER JOIN " .self::TableName_UploadFile." uf1 ON (f.ForumPic1UploadFileId=uf1.UploadFileId)
            LEFT OUTER JOIN " .self::TableName_UploadFile." uf2 ON (f.ForumPic2UploadFileId=uf2.UploadFileId)

        WHERE f.ParentId IN ($parentId) AND f.SiteId=:SiteId AND f.State<".ForumData::STATE_REMOVED." ORDER BY f.Sort DESC";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("SiteId", $siteId);
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        return $result;
    }

    /**
     *
     * @param int $parentId
     * @return array
     */
    public function GetListByParentId($parentId) {
        $sql = "SELECT f.*,
                        uf1.UploadFilePath AS ForumPic1UploadFilePath,

                        uf2.UploadFilePath AS ForumPic2UploadFilePath

                FROM " . self::TableName_Forum . " f

                LEFT OUTER JOIN " .self::TableName_UploadFile." uf1 ON (f.ForumPic1UploadFileId=uf1.UploadFileId)
                LEFT OUTER JOIN " .self::TableName_UploadFile." uf2 ON (f.ForumPic2UploadFileId=uf2.UploadFileId)


                WHERE ParentId=:ParentId AND State<".ForumData::STATE_REMOVED." ORDER BY Sort DESC";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("ParentId", $parentId);
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
            $sql = "SELECT LastPostInfo FROM " . self::TableName_Forum . " WHERE ForumId=:ForumId;";
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

    /**
     * 取得顶图网址
     * @param int $forumId 论坛版块id
     * @param bool $withCache 是否从缓冲中取
     * @return string 顶图网址
     */
    public function GetTopImageUrl($forumId, $withCache) {
        $result = -1;
        if ($forumId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'forum_data';
            $cacheFile = 'forum_get_top_image_url.cache_' . $forumId . '';
            $sql = "SELECT TopImageUrl FROM " . self::TableName_Forum . " WHERE ForumId =:ForumId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField(self::TableId_Forum, $forumId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 取得背景图网址
     * @param int $forumId 论坛版块id
     * @param bool $withCache 是否从缓冲中取
     * @return string 背景图网址
     */
    public function GetBackgroundUrl($forumId, $withCache) {
        $result = -1;
        if ($forumId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'forum_data';
            $cacheFile = 'forum_get_background_url.cache_' . $forumId . '';
            $sql = "SELECT BackgroundUrl FROM " . self::TableName_Forum . " WHERE ForumId =:ForumId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField(self::TableId_Forum, $forumId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 取得背景图颜色
     * @param int $forumId 论坛版块id
     * @param bool $withCache 是否从缓冲中取
     * @return string 背景图颜色
     */
    public function GetBackgroundColor($forumId, $withCache) {
        $result = -1;
        if ($forumId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'forum_data';
            $cacheFile = 'forum_get_background_color.cache_' . $forumId . '';
            $sql = "SELECT BackgroundColor FROM " . self::TableName_Forum . " WHERE ForumId =:ForumId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField(self::TableId_Forum, $forumId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }


    /**
     * 取得访问限制方式
     * @param int $forumId 论坛版块id
     * @param bool $withCache 是否从缓冲中取
     * @return int 访问限制方式
     */
    public function GetForumAccess($forumId, $withCache) {
        $result = -1;
        if ($forumId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'forum_data';
            $cacheFile = 'forum_get_forum_access.cache_' . $forumId . '';
            $sql = "SELECT ForumAccess FROM " . self::TableName_Forum . " WHERE ForumId =:ForumId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField(self::TableId_Forum, $forumId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 取得访问限制的设置内容
     * @param int $forumId 论坛版块id
     * @param bool $withCache 是否从缓冲中取
     * @return string 访问限制的设置内容
     */
    public function GetForumAccessLimit($forumId, $withCache) {
        $result = "";
        if ($forumId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'forum_data';
            $cacheFile = 'forum_get_forum_access_limit.cache_' . $forumId . '';
            $sql = "SELECT ForumAccessLimit FROM " . self::TableName_Forum . " WHERE ForumId =:ForumId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField(self::TableId_Forum, $forumId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    public function GetSearchResultArray($siteId, $pageBegin, $pageSize, &$allCount, $key)
    {

        $searchSql = "";
        if($key != ''){
            $searchSql = " AND ft.ForumTopicTitle LIKE '%".$key. "%' ";
        }
        $dataProperty = new DataProperty();

        $dataProperty->AddField("SiteId", $siteId);

        $sql = "SELECT".
                        " ft.*,".
                        "ui.AvatarUploadFileId,".
                        "uf.UploadFilePath AS AvatarUploadFilePath,".
                        "uf.UploadFileMobilePath AS AvatarUploadFileMobilePath,".
                        "uf.UploadFilePadPath AS AvatarUploadFilePadPath,".

                        "uf2.UploadFilePath AS ContentUploadFilePath1,".
                        "uf3.UploadFilePath AS ContentUploadFilePath2,".
                        "uf4.UploadFilePath AS ContentUploadFilePath3,".
                        "uf5.UploadFilePath AS ContentUploadFilePath4".

                " FROM".
                        " " .self::TableName_ForumTopic. " ft".
                        " INNER JOIN " . self::TableName_UserInfo . " ui ON (ui.UserId=ft.UserId)".
                        " LEFT OUTER JOIN " .self::TableName_UploadFile. " uf ON (ui.AvatarUploadFileId=uf.UploadFileId)".
                        " LEFT OUTER JOIN " .self::TableName_UploadFile. " uf2 ON (ft.ContentUploadFileId1=uf2.UploadFileId)".
                        " LEFT OUTER JOIN " .self::TableName_UploadFile. " uf3 ON (ft.ContentUploadFileId2=uf3.UploadFileId)".
                        " LEFT OUTER JOIN " .self::TableName_UploadFile. " uf4 ON (ft.ContentUploadFileId3=uf4.UploadFileId)".
                        " LEFT OUTER JOIN " .self::TableName_UploadFile. " uf5 ON (ft.ContentUploadFileId4=uf5.UploadFileId)".

                " WHERE".
                        " ft.SiteId=:SiteId".
                        " AND ft.State<".ForumTopicData::FORUM_TOPIC_STATE_REMOVED.
                        $searchSql.

                " ORDER BY".
                        " ft.Sort DESC,".
                        " ft.PostTime DESC".

                " LIMIT " .$pageBegin. "," .$pageSize. ";";
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

        //统计总数
        $sql = "SELECT count(*)".
                " FROM " . self::TableName_ForumTopic .' ft'.
                " WHERE SiteId=:SiteId "
                .$searchSql.";";
        $allCount = $this->dbOperator->GetInt($sql, $dataProperty);
        return $result;
    }

}

?>
