<?php

/**
 * 前台 论坛主题 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Forum
 * @author zhangchi
 */
class ForumTopicPublicData extends BasePublicData
{

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

    public function Modify(
        $forumTopicId,
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
            strlen($forumTopicTitle) > 0
            && $userId > 0
            && strlen($userName) > 0
        ) {
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ForumTopicTitle", $forumTopicTitle);
            $dataProperty->AddField("ForumTopicTypeId", $forumTopicTypeId);
            $dataProperty->AddField("ForumTopicTypeName", $forumTopicTypeName);
            $dataProperty->AddField("ForumTopicAudit", $forumTopicAudit);
            $dataProperty->AddField("ForumTopicAccess", $forumTopicAccess);
            $dataProperty->AddField("PostTime", $postTime);
            $dataProperty->AddField("UserId", $userId);
            $dataProperty->AddField("UserName", $userName);
            $dataProperty->AddField("ForumTopicMood", $forumTopicMood);
            $dataProperty->AddField("ForumTopicAttach", $forumTopicAttach);
            $dataProperty->AddField("TitleBold", $titleBold);
            $dataProperty->AddField("TitleColor", $titleColor);
            $dataProperty->AddField("TitleBgImage", $titleBgImage);
            $fieldNames = "ForumTopicTitle=:ForumTopicTitle,ForumTopicTypeId=:ForumTopicTypeId,ForumTopicTypeName=:ForumTopicTypeName,ForumTopicAudit=:ForumTopicAudit,ForumTopicAccess=:ForumTopicAccess,PostTime=:PostTime,UserId=:UserId,UserName=:UserName,ForumTopicMood=:ForumTopicMood,ForumTopicAttach=:ForumTopicAttach,TitleBold=:TitleBold,TitleColor=:TitleColor,TitleBgImage=:TitleBgImage";
            $sql = "UPDATE " . self::TableName_ForumTopic . " SET " . $fieldNames . " WHERE forumTopicId =" . $forumTopicId . "";
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 修改主题状态
     * @param int $forumTopicId 主题id
     * @param int $state 状态
     * @return int 操作结果
     */
    public function ModifyState($forumTopicId, $state){
        $result = -1;
        if ($forumTopicId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_ForumTopic . " SET
                    State = :State
                    WHERE ForumTopicId = :ForumTopicId
                    ;";
            $dataProperty->AddField("State", $state);
            $dataProperty->AddField("ForumTopicId", $forumTopicId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }
    /**
     * 修改主题排序
     * @param int $forumTopicId 主题id
     * @param int $sort 排序
     * @return int 操作结果
     */
    public function ModifySort($forumTopicId, $sort){
        $result = -1;
        if ($forumTopicId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_ForumTopic . " SET
                    Sort = :Sort
                    WHERE ForumTopicId = :ForumTopicId
                    ;";
            $dataProperty->AddField("Sort", $sort);
            $dataProperty->AddField("ForumTopicId", $forumTopicId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }

    /**
     * 修改内容中上传文件1
     * @param int $forumTopicId 主题id
     * @param int $contentUploadFileId1 内容中上传文件1
     * @return int 操作结果
     */
    public function ModifyContentUploadFileId1($forumTopicId, $contentUploadFileId1){
        $result = -1;
        if ($forumTopicId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_ForumTopic . " SET
                    ContentUploadFileId1 = :ContentUploadFileId1
                    WHERE ForumTopicId = :ForumTopicId
                    ;";
            $dataProperty->AddField("ContentUploadFileId1", $contentUploadFileId1);
            $dataProperty->AddField("ForumTopicId", $forumTopicId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }

    /**
     * 修改内容中上传文件1
     * @param int $forumTopicId 主题id
     * @param int $contentUploadFileId2 内容中上传文件2
     * @return int 操作结果
     */
    public function ModifyContentUploadFileId2($forumTopicId, $contentUploadFileId2){
        $result = -1;
        if ($forumTopicId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_ForumTopic . " SET
                    ContentUploadFileId2 = :ContentUploadFileId2
                    WHERE ForumTopicId = :ForumTopicId
                    ;";
            $dataProperty->AddField("ContentUploadFileId2", $contentUploadFileId2);
            $dataProperty->AddField("ForumTopicId", $forumTopicId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }

    /**
     * 修改内容中上传文件1
     * @param int $forumTopicId 主题id
     * @param int $contentUploadFileId3 内容中上传文件3
     * @return int 操作结果
     */
    public function ModifyContentUploadFileId3($forumTopicId, $contentUploadFileId3){
        $result = -1;
        if ($forumTopicId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_ForumTopic . " SET
                    ContentUploadFileId3 = :ContentUploadFileId3
                    WHERE ForumTopicId = :ForumTopicId
                    ;";
            $dataProperty->AddField("ContentUploadFileId3", $contentUploadFileId3);
            $dataProperty->AddField("ForumTopicId", $forumTopicId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }

    /**
     * 取得一条信息
     * @param int $forumTopicId 管理员id
     * @return array 管理员帐号信息数组
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
     * 最新主题（可缓存）
     * @param $siteId
     * @param $topCount
     * @param bool $withCache
     * @return array|null
     */
    public function GetListOfNew(
        $siteId,
        $topCount,
        $withCache = false
    )
    {
        if ($siteId > 0) {
            $topCount = intval($topCount);
            if ($topCount <= 0) {
                $topCount = 10;
            }
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'forum_topic_data';
            $cacheFile = 'forum_get_list_of_new.cache_' . $siteId . '_' . $topCount;
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);
            $sql = "
            SELECT
                ft.*
            FROM
            " . self::TableName_ForumTopic . " ft

            WHERE ft.SiteId=:SiteId
            ORDER BY ft.PostTime DESC
            LIMIT $topCount;";
            $result = $this->GetInfoOfArrayList($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);

        } else {
            $result = null;
        }

        return $result;
    }

    /**
     * 热门主题（可缓存）
     * @param $siteId
     * @param $topCount
     * @param bool $withCache
     * @return array|null
     */
    public function GetListOfHot(
        $siteId,
        $topCount,
        $withCache = false
    )
    {
        if ($siteId > 0) {
            $topCount = intval($topCount);
            if ($topCount <= 0) {
                $topCount = 10;
            }
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'forum_topic_data';
            $cacheFile = 'forum_get_list_of_hot.cache_' . $siteId . '_' . $topCount;
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);
            $sql = "
            SELECT
                ft.*
            FROM
            " . self::TableName_ForumTopic . " ft

            WHERE ft.SiteId=:SiteId
            ORDER BY ft.HitCount DESC
            LIMIT $topCount;";
            $result = $this->GetInfoOfArrayList($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);

        } else {
            $result = null;
        }

        return $result;
    }

    /**
     * 最新精华（可缓存）
     * @param $siteId
     * @param $topCount
     * @param bool $withCache
     * @return array|null
     */
    public function GetListOfBest(
        $siteId,
        $topCount,
        $withCache = false
    )
    {
        if ($siteId > 0) {
            $topCount = intval($topCount);
            if ($topCount <= 0) {
                $topCount = 10;
            }
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'forum_topic_data';
            $cacheFile = 'forum_get_list_of_best.cache_' . $siteId . '_' . $topCount;
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);
            $sql = "
            SELECT
                ft.*
            FROM
            " . self::TableName_ForumTopic . " ft

            WHERE ft.SiteId=:SiteId AND ForumTopicClass=".ForumTopicData::FORUM_TOPIC_CLASS_BEST."
            ORDER BY ft.PostTime DESC
            LIMIT $topCount;";
            $result = $this->GetInfoOfArrayList($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);

        } else {
            $result = null;
        }

        return $result;
    }

    /**
     * 分页列表数据集
     * @param int $forumId
     * @param int $pageBegin
     * @param int $pageSize
     * @param int $allCount
     * @return array
     */
    public function GetListPager($forumId, $pageBegin, $pageSize, &$allCount)
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
                        uf.UploadFilePadPath AS AvatarUploadFilePadPath
            FROM
            " . self::TableName_ForumTopic . " ft
            INNER JOIN " . self::TableName_UserInfo . " ui ON (ui.UserId=ft.UserId)

            LEFT OUTER JOIN " . self::TableName_UploadFile . " uf ON (ui.AvatarUploadFileId=uf.UploadFileId)

            WHERE ft.ForumId=:ForumId  " . $searchSql . "
            ORDER BY ft.Sort DESC,ft.PostTime DESC
            LIMIT " . $pageBegin . "," . $pageSize . ";";
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        $dataProperty->AddField("ForumId", $forumId);
        //统计总数
        $sql = "SELECT count(*)
                FROM " . self::TableName_ForumTopic . "
                WHERE ForumId=:ForumId  " . $searchSql . ";";
        $allCount = $this->dbOperator->GetInt($sql, $dataProperty);
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
     * 取得排序
     * @param int $forumTopicId 主题id
     * @param bool $withCache 是否从缓冲中取
     * @return int 排序
     */
    public function GetSort($forumTopicId, $withCache) {
        $result = -1;
        if ($forumTopicId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'forum_topic_data';
            $cacheFile = 'forum_topic_get_sort.cache_' . $forumTopicId . '';
            $sql = "SELECT Sort FROM " . self::TableName_ForumTopic . " WHERE ForumTopicId =:ForumTopicId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField(self::TableId_ForumTopic, $forumTopicId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }
} 