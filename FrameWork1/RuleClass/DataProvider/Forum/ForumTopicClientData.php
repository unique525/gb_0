<?php

/**
 * 客户端 论坛主题 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Forum
 * @author zhangchi
 */
class ForumTopicClientData extends BaseClientData {


    /**
     * 新增主题
     * @param int $siteId 站点id
     * @param int $forumId 论坛id
     * @param string $forumTopicTitle 主题标题
     * @param int $forumTopicTypeId 论坛主题类型，默认为0，无类型
     * @param string $forumTopicTypeName 论坛主题类型名称，默认为空（冗余字段）
     * @param int $forumTopicAudit 主题审核（授权）方式
     * @param int $forumTopicAccess 主题访问方式
     * @param string $postTime 创建时间
     * @param int $userId 会员id
     * @param string $userName 会员帐号（冗余字段）
     * @param int $forumTopicMood 心情图标
     * @param int $forumTopicAttach 附加图标
     * @param string $titleBold 标题加粗
     * @param string $titleColor 标题颜色
     * @param string $titleBgImage 标题背景图
     * @return int
     */
    public function Create(
        $siteId,
        $forumId,
        $forumTopicTitle,
        $forumTopicTypeId,
        $forumTopicTypeName,
        $forumTopicAudit,
        $forumTopicAccess,
        $postTime,
        $userId,
        $userName,
        $forumTopicMood,
        $forumTopicAttach,
        $titleBold,
        $titleColor,
        $titleBgImage
    )
    {
        $result = -1;


        if (
            $siteId > 0
            && $forumId > 0
            && strlen($forumTopicTitle) > 0
            && $userId > 0
            && strlen($userName) > 0
        ) {


            $sql = "INSERT INTO " . self::TableName_ForumTopic . "
                    (
                    ForumTopicTitle,
                    ForumTopicTypeId,
                    ForumTopicTypeName,
                    ForumTopicAudit,
                    ForumTopicAccess,
                    ForumId,
                    SiteId,
                    PostTime,
                    UserId,
                    UserName,
                    ForumTopicMood,
                    ForumTopicAttach,
                    TitleBold,
                    TitleColor,
                    TitleBgImage
                    )
                    VALUES
                    (
                    :ForumTopicTitle,
                    :ForumTopicTypeId,
                    :ForumTopicTypeName,
                    :ForumTopicAudit,
                    :ForumTopicAccess,
                    :ForumId,
                    :SiteId,
                    :PostTime,
                    :UserId,
                    :UserName,
                    :ForumTopicMood,
                    :ForumTopicAttach,
                    :TitleBold,
                    :TitleColor,
                    :TitleBgImage
                    );";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ForumTopicTitle", $forumTopicTitle);
            $dataProperty->AddField("ForumTopicTypeId", $forumTopicTypeId);
            $dataProperty->AddField("ForumTopicTypeName", $forumTopicTypeName);
            $dataProperty->AddField("ForumTopicAudit", $forumTopicAudit);
            $dataProperty->AddField("ForumTopicAccess", $forumTopicAccess);
            $dataProperty->AddField("ForumId", $forumId);
            $dataProperty->AddField("SiteId", $siteId);
            $dataProperty->AddField("PostTime", $postTime);
            $dataProperty->AddField("UserId", $userId);
            $dataProperty->AddField("UserName", $userName);
            $dataProperty->AddField("ForumTopicMood", $forumTopicMood);
            $dataProperty->AddField("ForumTopicAttach", $forumTopicAttach);
            $dataProperty->AddField("TitleBold", $titleBold);
            $dataProperty->AddField("TitleColor", $titleColor);
            $dataProperty->AddField("TitleBgImage", $titleBgImage);
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 分页列表数据集
     * @param int $forumId
     * @param int $pageBegin
     * @param int $pageSize
     * @return array
     */
    public function GetList($forumId, $pageBegin, $pageSize)
    {
        $searchSql = "";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("ForumId", $forumId);
        $sql = "
            SELECT
                        ft.*,
                        ui.AvatarUploadFileId,
                        uf.UploadFilePath AS AvatarUploadFilePath,
                        uf.UploadFileMobilePath AS AvatarUploadFileMobilePath,
                        uf.UploadFilePadPath AS AvatarUploadFilePadPath,

                        uf2.UploadFilePath AS ContentUploadFilePath1,
                        uf3.UploadFilePath AS ContentUploadFilePath2,
                        uf4.UploadFilePath AS ContentUploadFilePath3,
                        uf5.UploadFilePath AS ContentUploadFilePath4
            FROM
            " . self::TableName_ForumTopic . " ft
            INNER JOIN " . self::TableName_UserInfo . " ui ON (ui.UserId=ft.UserId)

            LEFT OUTER JOIN " . self::TableName_UploadFile . " uf ON (ui.AvatarUploadFileId=uf.UploadFileId)
            LEFT OUTER JOIN " . self::TableName_UploadFile . " uf2 ON (ft.ContentUploadFileId1=uf2.UploadFileId)
            LEFT OUTER JOIN " . self::TableName_UploadFile . " uf3 ON (ft.ContentUploadFileId2=uf3.UploadFileId)
            LEFT OUTER JOIN " . self::TableName_UploadFile . " uf4 ON (ft.ContentUploadFileId3=uf4.UploadFileId)
            LEFT OUTER JOIN " . self::TableName_UploadFile . " uf5 ON (ft.ContentUploadFileId4=uf5.UploadFileId)

            WHERE ft.ForumId=:ForumId  " . $searchSql . "
            ORDER BY ft.Sort DESC,ft.PostTime DESC
            LIMIT " . $pageBegin . "," . $pageSize . ";";
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

        return $result;
    }


    /**
     * 分页列表数据集
     * @param int $userId
     * @param int $pageBegin
     * @param int $pageSize
     * @return array
     */
    public function GetListOfUser($userId, $pageBegin, $pageSize)
    {
        $searchSql = "";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("UserId", $userId);
        $sql = "
            SELECT
                        ft.*,
                        ui.AvatarUploadFileId,
                        uf.UploadFilePath AS AvatarUploadFilePath,
                        uf.UploadFileMobilePath AS AvatarUploadFileMobilePath,
                        uf.UploadFilePadPath AS AvatarUploadFilePadPath,

                        uf2.UploadFilePath AS ContentUploadFilePath1,
                        uf3.UploadFilePath AS ContentUploadFilePath2,
                        uf4.UploadFilePath AS ContentUploadFilePath3
            FROM
            " . self::TableName_ForumTopic . " ft
            INNER JOIN " . self::TableName_UserInfo . " ui ON (ui.UserId=ft.UserId)

            LEFT OUTER JOIN " . self::TableName_UploadFile . " uf ON (ui.AvatarUploadFileId=uf.UploadFileId)
            LEFT OUTER JOIN " . self::TableName_UploadFile . " uf2 ON (ft.ContentUploadFileId1=uf2.UploadFileId)
            LEFT OUTER JOIN " . self::TableName_UploadFile . " uf3 ON (ft.ContentUploadFileId2=uf3.UploadFileId)
            LEFT OUTER JOIN " . self::TableName_UploadFile . " uf4 ON (ft.ContentUploadFileId3=uf4.UploadFileId)

            WHERE ft.UserId=:UserId  " . $searchSql . " AND ft.State<".ForumTopicData::FORUM_TOPIC_STATE_REMOVED."
            ORDER BY ft.Sort DESC,ft.PostTime DESC
            LIMIT " . $pageBegin . "," . $pageSize . ";";
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

        return $result;
    }

    /**
     * 根据参与回帖的用户取得列表信息
     * @param int $userId 会员id
     * @param int $pageBegin
     * @param int $pageSize
     * @return array 帖子信息数组
     */
    public function GetListOfUserPostForum($userId, $pageBegin, $pageSize)
    {

        $searchSql = "";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("UserId", $userId);
        $sql = "
            SELECT
                        ft.*,
                        ui.AvatarUploadFileId,
                        uf.UploadFilePath AS AvatarUploadFilePath,
                        uf.UploadFileMobilePath AS AvatarUploadFileMobilePath,
                        uf.UploadFilePadPath AS AvatarUploadFilePadPath,

                        uf2.UploadFilePath AS ContentUploadFilePath1,
                        uf3.UploadFilePath AS ContentUploadFilePath2,
                        uf4.UploadFilePath AS ContentUploadFilePath3
            FROM
            " . self::TableName_ForumTopic . " ft
            INNER JOIN " . self::TableName_UserInfo . " ui ON (ui.UserId=ft.UserId)

            LEFT OUTER JOIN " . self::TableName_UploadFile . " uf ON (ui.AvatarUploadFileId=uf.UploadFileId)
            LEFT OUTER JOIN " . self::TableName_UploadFile . " uf2 ON (ft.ContentUploadFileId1=uf2.UploadFileId)
            LEFT OUTER JOIN " . self::TableName_UploadFile . " uf3 ON (ft.ContentUploadFileId2=uf3.UploadFileId)
            LEFT OUTER JOIN " . self::TableName_UploadFile . " uf4 ON (ft.ContentUploadFileId3=uf4.UploadFileId)

            WHERE ft.ForumTopicId IN (SELECT ForumTopicId FROM " . self::TableName_ForumPost . " WHERE UserId=:UserId) " . $searchSql . " AND ft.State<".ForumTopicData::FORUM_TOPIC_STATE_REMOVED."
            ORDER BY ft.Sort DESC,ft.PostTime DESC
            LIMIT " . $pageBegin . "," . $pageSize . ";";
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

        return $result;

        /**
        $result = null;
        if($userId>0){
        $sql = "SELECT fp.*,
        ui.AvatarUploadFileId,
        uf.UploadFilePath AS AvatarUploadFilePath,
        uf.UploadFileMobilePath AS AvatarUploadFileMobilePath,
        uf.UploadFilePadPath AS AvatarUploadFilePadPath
        FROM " . self::TableName_ForumPost . " fp

        INNER JOIN " .self::TableName_UserInfo." ui ON (ui.UserId=fp.UserId)

        LEFT OUTER JOIN " .self::TableName_UploadFile." uf ON (ui.AvatarUploadFileId=uf.UploadFileId)

        WHERE fp.UserId=:UserId

        ORDER BY fp.IsTopic DESC, fp.PostTime

        LIMIT " .$pageBegin . "," . $pageSize . ";";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("UserId", $userId);
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
         */
    }

    /**
     * 取得一条信息
     * @param int $forumTopicId 论坛主题id
     * @return array 论坛主题信息数组
     */
    public function GetOne($forumTopicId)
    {
        $sql = "SELECT * FROM " . self::TableName_ForumTopic . " WHERE " . self::TableId_ForumTopic . "=:" . self::TableId_ForumTopic . ";";
        $dataProperty = new DataProperty();
        $dataProperty->AddField(self::TableId_ForumTopic, $forumTopicId);
        $result = $this->dbOperator->GetArray($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得论坛id
     * @param int $forumTopicId 主题id
     * @param bool $withCache 是否从缓冲中取
     * @return int 论坛id
     */
    public function GetForumId($forumTopicId, $withCache) {
        $result = -1;
        if ($forumTopicId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'forum_topic_data';
            $cacheFile = 'forum_topic_get_forum_id.cache_' . $forumTopicId . '';
            $sql = "SELECT ForumId FROM " . self::TableName_ForumTopic . " WHERE ForumTopicId =:ForumTopicId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField(self::TableId_ForumTopic, $forumTopicId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 取得论坛id
     * @param int $forumTopicId 主题id
     * @param bool $withCache 是否从缓冲中取
     * @return int 论坛id
     */
    public function GetForumTopicTitle($forumTopicId, $withCache) {
        $result = "";
        if ($forumTopicId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'forum_topic_data';
            $cacheFile = 'forum_topic_get_forum_topic_title.cache_' . $forumTopicId . '';
            $sql = "SELECT ForumTopicTitle FROM " . self::TableName_ForumTopic . " WHERE ForumTopicId =:ForumTopicId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField(self::TableId_ForumTopic, $forumTopicId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }
} 