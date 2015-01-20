<?php
/**
 * 后台管理 资讯 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Document
 * @author zhangchi
 */
class DocumentNewsManageData extends BaseManageData
{


    /**
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_DocumentNews){
        return parent::GetFields(self::TableName_DocumentNews);
    }


    /**
     * 新增资讯
     * @param array $httpPostData $_POST数组
     * @param int $manageUserId 管理员id
     * @param string $manageUserName 管理员帐号
     * @return int 新增的资讯id
     */
    public function Create($httpPostData, $manageUserId, $manageUserName)
    {
        $result = -1;
        $dataProperty = new DataProperty();
        $addFieldName = "";
        $addFieldValue = "";
        $preNumber = "";
        $addFieldNames = array("CreateDate","ManageUserId","ManageUserName");
        $addFieldValues = array(date("Y-m-d H:i:s", time()),$manageUserId,$manageUserName);
        if (!empty($httpPostData)) {
            $sql = parent::GetInsertSql($httpPostData, self::TableName_DocumentNews, $dataProperty, $addFieldName, $addFieldValue, $preNumber, $addFieldNames, $addFieldValues);
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 修改资讯
     * @param array $httpPostData $_POST数组
     * @param int $documentNewsId 资讯id
     * @return int 返回影响的行数
     */
    public function Modify($httpPostData, $documentNewsId) {
        $result = -1;
        if($documentNewsId>0){
            $dataProperty = new DataProperty();
            $addFieldNames = array();
            $addFieldValues = array();
            $sql = parent::GetUpdateSql(
                $httpPostData,
                self::TableName_DocumentNews,
                self::TableId_DocumentNews,
                $documentNewsId,
                $dataProperty,
                "",
                "",
                "",
                $addFieldNames,
                $addFieldValues
            );
            $result = $this->dbOperator->Execute($sql, $dataProperty);

        }
        return $result;
    }


    /**
     * 修改题图的上传文件id
     * @param int $documentNewsId 资讯id
     * @param int $titlePic1UploadFileId 题图1上传文件id
     * @param int $titlePic2UploadFileId 题图2上传文件id
     * @param int $titlePic3UploadFileId 题图3上传文件id
     * @return int 操作结果
     */
    public function ModifyTitlePic($documentNewsId, $titlePic1UploadFileId, $titlePic2UploadFileId, $titlePic3UploadFileId)
    {
        $result = -1;
        if($documentNewsId>0){
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_DocumentNews . " SET
                    TitlePic1UploadFileId = :TitlePic1UploadFileId,
                    TitlePic2UploadFileId = :TitlePic2UploadFileId,
                    TitlePic3UploadFileId = :TitlePic3UploadFileId

                    WHERE DocumentNewsId = :DocumentNewsId

                    ;";
            $dataProperty->AddField("TitlePic1UploadFileId", $titlePic1UploadFileId);
            $dataProperty->AddField("TitlePic2UploadFileId", $titlePic2UploadFileId);
            $dataProperty->AddField("TitlePic3UploadFileId", $titlePic3UploadFileId);
            $dataProperty->AddField("DocumentNewsId", $documentNewsId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }

    /**
     * 修改题图1的上传文件id
     * @param int $documentNewsId 资讯id
     * @param int $titlePic1UploadFileId 题图1上传文件id
     * @return int 操作结果
     */
    public function ModifyTitlePic1UploadFileId($documentNewsId, $titlePic1UploadFileId)
    {
        $result = -1;
        if($documentNewsId>0){
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_DocumentNews . " SET
                    TitlePic1UploadFileId = :TitlePic1UploadFileId

                    WHERE DocumentNewsId = :DocumentNewsId
                    ;";
            $dataProperty->AddField("TitlePic1UploadFileId", $titlePic1UploadFileId);
            $dataProperty->AddField("DocumentNewsId", $documentNewsId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }

    /**
     * 修改题图2的上传文件id
     * @param int $documentNewsId 资讯id
     * @param int $titlePic2UploadFileId 题图2上传文件id
     * @return int 操作结果
     */
    public function ModifyTitlePic2UploadFileId($documentNewsId, $titlePic2UploadFileId)
    {
        $result = -1;
        if($documentNewsId>0){
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_DocumentNews . " SET
                    TitlePic2UploadFileId = :TitlePic2UploadFileId

                    WHERE DocumentNewsId = :DocumentNewsId
                    ;";
            $dataProperty->AddField("TitlePic2UploadFileId", $titlePic2UploadFileId);
            $dataProperty->AddField("DocumentNewsId", $documentNewsId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }

    /**
     * 修改题图3的上传文件id
     * @param int $documentNewsId 资讯id
     * @param int $titlePic3UploadFileId 题图3上传文件id
     * @return int 操作结果
     */
    public function ModifyTitlePic3UploadFileId($documentNewsId, $titlePic3UploadFileId)
    {
        $result = -1;
        if($documentNewsId>0){
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_DocumentNews . " SET
                    TitlePic3UploadFileId = :TitlePic3UploadFileId

                    WHERE DocumentNewsId = :DocumentNewsId
                    ;";
            $dataProperty->AddField("TitlePic3UploadFileId", $titlePic3UploadFileId);
            $dataProperty->AddField("DocumentNewsId", $documentNewsId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }

    /**
     * 删除到回收站
     * @param int $documentNewsId 资讯id
     * @return int 返回影响的行数
     */
    public function RemoveToBin($documentNewsId){
        $result = 0;
        if ($documentNewsId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_DocumentNews . " SET `State`=100 WHERE DocumentNewsId=:DocumentNewsId;";
            $dataProperty->AddField("DocumentNewsId", $documentNewsId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 取得题图1的上传文件id
     * @param int $documentNewsId 资讯id
     * @param bool $withCache 是否从缓冲中取
     * @return int 题图1的上传文件id
     */
    public function GetTitlePic1UploadFileId($documentNewsId, $withCache)
    {
        $result = -1;
        if ($documentNewsId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'document_news_data';
            $cacheFile = 'document_news_get_title_pic_1_upload_file_id.cache_' . $documentNewsId . '';
            $sql = "SELECT TitlePic1UploadFileId FROM " . self::TableName_DocumentNews . " WHERE DocumentNewsId = :DocumentNewsId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("DocumentNewsId", $documentNewsId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 取得题图2的上传文件id
     * @param int $documentNewsId 资讯id
     * @param bool $withCache 是否从缓冲中取
     * @return int 题图2的上传文件id
     */
    public function GetTitlePic2UploadFileId($documentNewsId, $withCache)
    {
        $result = -1;
        if ($documentNewsId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'document_news_data';
            $cacheFile = 'document_news_get_title_pic_2_upload_file_id.cache_' . $documentNewsId . '';
            $sql = "SELECT TitlePic2UploadFileId FROM " . self::TableName_DocumentNews . " WHERE DocumentNewsId = :DocumentNewsId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("DocumentNewsId", $documentNewsId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 取得题图3的上传文件id
     * @param int $documentNewsId 资讯id
     * @param bool $withCache 是否从缓冲中取
     * @return int 题图3的上传文件id
     */
    public function GetTitlePic3UploadFileId($documentNewsId, $withCache)
    {
        $result = -1;
        if ($documentNewsId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'document_news_data';
            $cacheFile = 'document_news_get_title_pic_3_upload_file_id.cache_' . $documentNewsId . '';
            $sql = "SELECT TitlePic3UploadFileId FROM " . self::TableName_DocumentNews . " WHERE DocumentNewsId = :DocumentNewsId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("DocumentNewsId", $documentNewsId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 取得后台资讯列表数据集
     * @param int $channelId 频道id
     * @param int $pageBegin 起始记录行
     * @param int $pageSize 页大小
     * @param int $allCount 记录总数
     * @param string $searchKey 查询字符
     * @param int $searchType 查询下拉框的类别
     * @param int $isSelf 是否只显示当前登录的管理员录入的资讯
     * @param int $manageUserId 查显示某个后台管理员id
     * @param string $sort 排序方式（默认降序）
     * @param string $hit 按HIT排序方式（默认不按hit排）
     * @return array 资讯列表数据集
     */
    public function GetList(
        $channelId,
        $pageBegin,
        $pageSize,
        &$allCount,
        $searchKey = "",
        $searchType = 0,
        $isSelf = 0,
        $manageUserId = 0,
        $sort = "down",
        $hit = ""
    )
    {
        $searchSql = "";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("ChannelId", $channelId);
        if (strlen($searchKey) > 0 && $searchKey != "undefined") {
            if ($searchType == 0) { //标题
                $searchSql = " AND (DocumentNewsTitle like :SearchKey)";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            } else if ($searchType == 1) { //来源
                $searchSql = " AND (SourceName like :SearchKey)";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            } else if ($searchType == 2) { //发布人
                $searchSql = " AND (ManageUserName like :SearchKey)";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            } else if ($searchType == 3) { //标签
                $searchSql = " AND (DocumentNewsTag like :SearchKey)";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            } else if ($searchType == 4) { //投稿人
                $searchSql = " AND (UserName like :SearchKey)";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            } else { //模糊
                $searchSql = " AND (DocumentNewsTitle LIKE :SearchKey1
                                    OR ManageUserName LIKE :SearchKey2
                                    OR UserName LIKE :SearchKey3
                                    OR DocumentNewsTag LIKE :SearchKey4)";
                $dataProperty->AddField("SearchKey1", "%" . $searchKey . "%");
                $dataProperty->AddField("SearchKey2", "%" . $searchKey . "%");
                $dataProperty->AddField("SearchKey3", "%" . $searchKey . "%");
                $dataProperty->AddField("SearchKey4", "%" . $searchKey . "%");
            }
        }
        if ($isSelf === 1 && $manageUserId > 0) {
            $conditionManageUserId = ' AND ManageUserId=' . intval($manageUserId);
        } else {
            $conditionManageUserId = "";
        }

        if ($sort == "up"){
            $sortMethod = "ASC";
        }else{
            $sortMethod = "DESC";
        }

        if ($hit == "up"){
            $hitSortMethod = "Hit ASC,";
        }elseif($hit == "down"){
            $hitSortMethod = "Hit DESC,";
        }else{
            $hitSortMethod = "";
        }

        $sql = "
            SELECT
            DocumentNewsId,DocumentNewsType,DocumentNewsTitle,State,Sort,ChannelId,PublishDate,
            CreateDate,ManageUserId,ManageUserName,UserName,DocumentNewsTitleColor,DocumentNewsTitleBold,
            RecLevel,Hit
            FROM
            " . self::TableName_DocumentNews . "
            WHERE ChannelId=:ChannelId AND State<100 " . $searchSql . " " . $conditionManageUserId . "
            ORDER BY $hitSortMethod Sort $sortMethod, CreateDate DESC LIMIT " . $pageBegin . "," . $pageSize . ";";

        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

        $sql = "SELECT count(*) FROM " . self::TableName_DocumentNews . "
                WHERE ChannelId=:ChannelId AND State<100 " . $conditionManageUserId . " " . $searchSql . ";";
        $allCount = $this->dbOperator->GetInt($sql, $dataProperty);

        return $result;
    }

    /**
     * 修改排序
     * @param int $sort 大于0向上移动，否则向下移动
     * @param int $documentNewsId 资讯id
     * @return int 返回结果
     */
    public function ModifySort($sort, $documentNewsId) {
        $result = 0;
        if ($documentNewsId > 0) {

            $channelId = $this->GetChannelId($documentNewsId, false);

            $currentSort = $this->GetSort($documentNewsId, false);

            if ($sort > 0) { //向上移动
                $dataProperty = new DataProperty();
                $sql = "SELECT
                            Sort
                        FROM " . self::TableName_DocumentNews . "

                        WHERE
                            ChannelId=:ChannelId
                        AND DocumentNewsId<>:DocumentNewsId
                        AND sort>=:CurrentSort

                        ORDER BY Sort DESC LIMIT 1;
                        ";
                $dataProperty->AddField("ChannelId", $channelId);
                $dataProperty->AddField("DocumentNewsId", $documentNewsId);
                $dataProperty->AddField("CurrentSort", $currentSort);
                $newSort = $this->dbOperator->GetInt($sql, $dataProperty);
            } else{//向下移动
                $dataProperty = new DataProperty();
                $sql = "SELECT
                            Sort
                        FROM " . self::TableName_DocumentNews . "

                        WHERE ChannelId=:ChannelId
                        AND DocumentNewsId<>:DocumentNewsId
                        AND Sort<=:CurrentSort

                        ORDER BY Sort LIMIT 1;
                        ";
                $dataProperty->AddField("ChannelId", $channelId);
                $dataProperty->AddField("DocumentNewsId", $documentNewsId);
                $dataProperty->AddField("CurrentSort", $currentSort);
                $newSort = $this->dbOperator->GetInt($sql, $dataProperty);
            }

            if ($newSort < 0) {
                $newSort = 0;
            }

            $newSort = $newSort + $sort;


            //2011.12.8 zc 排序号禁止负数
            if ($newSort < 0) {
                $newSort = 0;
            }

            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_DocumentNews . "
                    SET `Sort`=:NewSort
                    WHERE DocumentNewsId=:DocumentNewsId;";
            $dataProperty->AddField("NewSort", $newSort);
            $dataProperty->AddField("DocumentNewsId", $documentNewsId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 拖动排序
     * @param array $arrDocumentNewsId 待处理的文档编号数组
     * @return int 操作结果
     */
    public function ModifySortForDrag($arrDocumentNewsId)
    {
        if (count($arrDocumentNewsId) > 1) { //大于1条时排序才有意义
            $strDocumentNewsId = join(',', $arrDocumentNewsId);
            $strDocumentNewsId = Format::FormatSql($strDocumentNewsId);
            $sql = "SELECT max(Sort) FROM " . self::TableName_DocumentNews . " WHERE DocumentNewsId IN ($strDocumentNewsId);";
            $maxSort = $this->dbOperator->GetInt($sql, null);
            $arrSql = array();
            for ($i = 0; $i < count($arrDocumentNewsId); $i++) {
                $newSort = $maxSort - $i;
                if ($newSort < 0) {
                    $newSort = 0;
                }
                $newSort = intval($newSort);
                $documentNewsId = intval($arrDocumentNewsId[$i]);
                $sql = "UPDATE " . self::TableName_DocumentNews . " SET Sort=$newSort WHERE DocumentNewsId=$documentNewsId;";
                $arrSql[] = $sql;
            }
            return $this->dbOperator->ExecuteBatch($arrSql, null);
        }else{
            return -1;
        }
    }


    /**
     * 修改发布时间和发布人,只有发布时间为空时才进行操作
     * @param int $documentNewsId 文档id
     * @param int $publishDate 发布时间
     * @param int $manageUserId 操作管理员id
     * @return int 操作结果
     */
    public function ModifyPublishDate($documentNewsId, $publishDate, $manageUserId)
    {
        $result = 0;
        if ($documentNewsId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_DocumentNews . "
                SET

                    PublishDate=:PublishDate,
                    PublishManageUserId=:PublishManageUserId

                WHERE
                        DocumentNewsId=:DocumentNewsId
                    AND PublishDate is NULL

                    ;";


            $dataProperty->AddField("DocumentNewsId", $documentNewsId);
            $dataProperty->AddField("PublishDate", $publishDate);
            $dataProperty->AddField("PublishManageUserId", $manageUserId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 修改锁定状态和时间
     * @param int $documentNewsId 文档id
     * @param int $lockEdit 是否锁定
     * @param int $manageUserId 操作管理员id
     * @return int 操作结果
     */
    public function ModifyLockEdit($documentNewsId, $lockEdit, $manageUserId)
    {
        $result = 0;
        if ($documentNewsId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_DocumentNews . " SET
                        LockEdit=:LockEdit,
                        LockEditDate=now(),
                        LockEditManageUserId=:LockEditManageUserId

                        WHERE DocumentNewsId=:DocumentNewsId;";
            $dataProperty->AddField("DocumentNewsId", $documentNewsId);
            $dataProperty->AddField("LockEdit", $lockEdit);
            $dataProperty->AddField("LockEditManageUserId", $manageUserId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 修改状态
     * @param int $documentNewsId 文档id
     * @param int $state 状态
     * @return int 操作结果
     */
    public function ModifyState($documentNewsId, $state)
    {
        $result = 0;
        if ($documentNewsId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_DocumentNews . " SET State=:State WHERE DocumentNewsId=:DocumentNewsId;";
            $dataProperty->AddField("DocumentNewsId", $documentNewsId);
            $dataProperty->AddField("State", $state);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 新增文档时修改排序号到当前频道的最大排序
     * @param int $channelId
     * @param int $documentNewsId
     * @return int 影响的记录行数
     */
    public function ModifySortWhenCreate($channelId, $documentNewsId) {
        $result = -1;
        if($channelId >0 && $documentNewsId>0){
            $dataProperty = new DataProperty();
            $sql = "SELECT max(Sort) FROM " . self::TableName_DocumentNews . " WHERE ChannelId=:ChannelId;";
            $dataProperty->AddField("ChannelId", $channelId);
            $maxSort = $this->dbOperator->GetInt($sql, $dataProperty);
            $newSort = $maxSort + 1;
            $dataProperty = new DataProperty();
            $dataProperty->AddField("Sort", $newSort);
            $dataProperty->AddField("DocumentNewsId", $documentNewsId);
            $sql = "UPDATE " . self::TableName_DocumentNews . " SET Sort=:Sort WHERE DocumentNewsId=:DocumentNewsId;";
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }


    //////////////////////////////////////////////////////////////////////////////
    //////////////////////////////Get Info////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////

    /**
     * 取得一条信息
     * @param int $documentNewsId 管理员id
     * @return array 管理员帐号信息数组
     */
    public function GetOne($documentNewsId)
    {
        $sql = "SELECT * FROM " . self::TableName_DocumentNews . " WHERE " . self::TableId_DocumentNews. "=:" . self::TableId_DocumentNews . ";";
        $dataProperty = new DataProperty();
        $dataProperty->AddField(self::TableId_DocumentNews, $documentNewsId);
        $result = $this->dbOperator->GetArray($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得文档的所属频道id
     * @param int $documentNewsId 频道id
     * @param bool $withCache 是否从缓冲中取
     * @return int 所属频道id
     */
    public function GetChannelId($documentNewsId, $withCache)
    {
        $result = -1;
        if ($documentNewsId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'document_news_data';
            $cacheFile = 'document_news_get_channel_id.cache_' . $documentNewsId . '';
            $sql = "SELECT ChannelId FROM " . self::TableName_DocumentNews . " WHERE DocumentNewsId=:DocumentNewsId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("DocumentNewsId", $documentNewsId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 取得排序号
     * @param int $documentNewsId 频道id
     * @param bool $withCache 是否从缓冲中取
     * @return int 排序号
     */
    public function GetSort($documentNewsId, $withCache)
    {
        $result = -1;
        if ($documentNewsId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'document_news_data';
            $cacheFile = 'document_news_get_sort.cache_' . $documentNewsId . '';
            $sql = "SELECT Sort FROM " . self::TableName_DocumentNews . " WHERE DocumentNewsId=:DocumentNewsId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("DocumentNewsId", $documentNewsId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 取得文档的所属站点id
     * @param int $documentNewsId 频道id
     * @param bool $withCache 是否从缓冲中取
     * @return int 所属频道id
     */
    public function GetSiteId($documentNewsId, $withCache)
    {
        $result = -1;
        if ($documentNewsId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'document_news_data';
            $cacheFile = 'document_news_get_site_id.cache_' . $documentNewsId . '';
            $sql = "SELECT SiteId FROM " . self::TableName_DocumentNews . " WHERE DocumentNewsId=:DocumentNewsId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("DocumentNewsId", $documentNewsId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 取得文档的管理员(发稿人)id
     * @param int $documentNewsId 频道id
     * @param bool $withCache 是否从缓冲中取
     * @return int 管理员(发稿人)id
     */
    public function GetManageUserId($documentNewsId, $withCache)
    {
        $result = -1;
        if ($documentNewsId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'document_news_data';
            $cacheFile = 'document_news_get_manage_user_id.cache_' . $documentNewsId . '';
            $sql = "SELECT ManageUserId FROM " . self::TableName_DocumentNews . " WHERE DocumentNewsId=:DocumentNewsId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("DocumentNewsId", $documentNewsId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 取得文档的状态
     * @param int $documentNewsId 频道id
     * @param bool $withCache 是否从缓冲中取
     * @return int 文档的状态
     */
    public function GetState($documentNewsId, $withCache)
    {
        $result = -1;
        if ($documentNewsId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'document_news_data';
            $cacheFile = 'document_news_get_state.cache_' . $documentNewsId . '';
            $sql = "SELECT State FROM " . self::TableName_DocumentNews . " WHERE DocumentNewsId=:DocumentNewsId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("DocumentNewsId", $documentNewsId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }


    /**
     * 取得文档是否锁定编辑
     * @param int $documentNewsId 频道id
     * @param bool $withCache 是否从缓冲中取
     * @return int 是否锁定编辑 0:未锁定 1:已锁定
     */
    public function GetLockEdit($documentNewsId, $withCache)
    {
        $result = -1;
        if ($documentNewsId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'document_news_data';
            $cacheFile = 'document_news_get_lock_edit.cache_' . $documentNewsId . '';
            $sql = "SELECT LockEdit FROM " . self::TableName_DocumentNews . " WHERE DocumentNewsId=:DocumentNewsId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("DocumentNewsId", $documentNewsId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 取得文档锁定编辑的时间
     * @param int $documentNewsId 文档id
     * @param bool $withCache 是否从缓冲中取
     * @return string 锁定编辑的时间
     */
    public function GetLockEditDate($documentNewsId, $withCache)
    {
        $result = "";
        if ($documentNewsId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'document_news_data';
            $cacheFile = 'document_news_get_lock_edit_date.cache_' . $documentNewsId . '';
            $sql = "SELECT LockEditDate FROM " . self::TableName_DocumentNews . " WHERE DocumentNewsId=:DocumentNewsId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("DocumentNewsId", $documentNewsId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 取得文档锁定编辑的操作管理员id
     * @param int $documentNewsId 文档id
     * @param bool $withCache 是否从缓冲中取
     * @return int 锁定编辑的操作管理员id
     */
    public function GetLockEditManageUserId($documentNewsId, $withCache)
    {
        $result = -1;
        if ($documentNewsId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'document_news_data';
            $cacheFile = 'document_news_get_lock_edit_manage_user_id.cache_' . $documentNewsId . '';
            $sql = "SELECT LockEditManageUserId FROM " . self::TableName_DocumentNews . " WHERE DocumentNewsId=:DocumentNewsId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("DocumentNewsId", $documentNewsId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 取得文档的发布时间
     * @param int $documentNewsId 文档id
     * @param bool $withCache 是否从缓冲中取
     * @return string 文档的发布时间
     */
    public function GetPublishDate($documentNewsId, $withCache)
    {
        $result = "";
        if ($documentNewsId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'document_news_data';
            $cacheFile = 'document_news_get_publish_date.cache_' . $documentNewsId . '';
            $sql = "SELECT PublishDate FROM " . self::TableName_DocumentNews . " WHERE DocumentNewsId=:DocumentNewsId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("DocumentNewsId", $documentNewsId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 取得文档的内容
     * @param int $documentNewsId 文档id
     * @param bool $withCache 是否从缓冲中取
     * @return string 文档的内容
     */
    public function GetDocumentNewsContent($documentNewsId, $withCache)
    {
        $result = "";
        if ($documentNewsId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'document_news_data';
            $cacheFile = 'document_news_get_document_news_content.cache_' . $documentNewsId . '';
            $sql = "SELECT DocumentNewsContent FROM " . self::TableName_DocumentNews . " WHERE DocumentNewsId=:DocumentNewsId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("DocumentNewsId", $documentNewsId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }


    //////////////////////////////////////////////////////////////////////////////
    //////////////////////////////Get List////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////


    /**
     * 最新的列表数据集
     * @param int $channelId 频道id
     * @param string $topCount 分页参数，如 9 或 3,9(第4至10条)
     * @param int $state 状态
     * @return array|null 返回最新的列表数据集
     */
    public function GetNewList($channelId, $topCount, $state) {

        $result = null;

        if(!empty($topCount)){
            $selectColumn = '
            dn.*,
            uf1.UploadFilePath AS TitlePic1,
            uf2.UploadFilePath AS TitlePic2,
            uf3.UploadFilePath AS TitlePic3,
            c.ChannelName

            ';

            $sql = "SELECT $selectColumn FROM " . self::TableName_DocumentNews . "  dn
                    LEFT OUTER JOIN " .self::TableName_Channel." c on dn.ChannelId = c.ChannelId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf1 on dn.TitlePic1UploadFileId=uf1.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf2 on dn.TitlePic2UploadFileId=uf2.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf3 on dn.TitlePic3UploadFileId=uf3.UploadFileId

                WHERE dn.ChannelId=:ChannelId AND dn.State=:State
                ORDER BY dn.Sort DESC, dn.CreateDate DESC LIMIT " . $topCount;
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);
            $dataProperty->AddField("State", $state);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

        }

        return $result;
    }

    /**
     * 子节点的列表数据集
     * @param int $channelId 频道id
     * @param string $topCount 分页参数，如 9 或 3,9(第4至10条)
     * @param int $state 状态
     * @param int $orderBy 排序方式
     * @return array|null 返回子节点的列表数据集
     */
    public function GetListOfChild($channelId, $topCount, $state, $orderBy) {

        $result = null;

        if(!empty($topCount)){
            $selectColumn = '
            dn.*,
            uf1.UploadFilePath AS TitlePic1,
            uf2.UploadFilePath AS TitlePic2,
            uf3.UploadFilePath AS TitlePic3,
            c.ChannelName

            ';

            $sql = "SELECT $selectColumn FROM " . self::TableName_DocumentNews . " dn

                    LEFT OUTER JOIN " .self::TableName_Channel." c on dn.ChannelId = c.ChannelId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf1 on dn.TitlePic1UploadFileId=uf1.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf2 on dn.TitlePic2UploadFileId=uf2.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf3 on dn.TitlePic3UploadFileId=uf3.UploadFileId

                WHERE
                    dn.ChannelId IN (SELECT ChannelId FROM ".self::TableName_Channel." WHERE ParentId=:ParentId)
                    AND dn.State=:State



                ORDER BY dn.Sort DESC, dn.CreateDate DESC
                LIMIT " . $topCount;

            $dataProperty = new DataProperty();
            $dataProperty->AddField("ParentId", $channelId);
            $dataProperty->AddField("State", $state);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }

        return $result;
    }


    /**
     * 子和孙节点的列表数据集
     * @param int $channelId 频道id
     * @param string $topCount 分页参数，如 9 或 3,9(第4至10条)
     * @param int $state 状态
     * @param int $orderBy 排序方式
     * @return array|null 返回子和孙节点的列表数据集
     */
    public function GetListOfGrandson($channelId, $topCount, $state, $orderBy) {

        $result = null;

        if(!empty($topCount)){
            $selectColumn = '
            dn.*,
            uf1.UploadFilePath AS TitlePic1,
            uf2.UploadFilePath AS TitlePic2,
            uf3.UploadFilePath AS TitlePic3,
            c.ChannelName


            ';

            $sql = "SELECT $selectColumn FROM " . self::TableName_DocumentNews . " dn
                    LEFT OUTER JOIN ".self::TableName_Channel." c on dn.ChannelId = c.ChannelId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf1 on dn.TitlePic1UploadFileId=uf1.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf2 on dn.TitlePic2UploadFileId=uf2.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf3 on dn.TitlePic3UploadFileId=uf3.UploadFileId

                WHERE

                    (dn.ChannelId IN (SELECT ChannelId FROM ".self::TableName_Channel." WHERE ParentId=:ParentId)
                        OR
                     dn.ChannelId IN (SELECT ChannelId FROM ".self::TableName_Channel." WHERE ParentId IN (SELECT ChannelId FROM ".self::TableName_Channel." WHERE ParentId=:ParentId))
                    )
                    AND dn.State=:State



                ORDER BY dn.CreateDate DESC
                LIMIT " . $topCount;

            $dataProperty = new DataProperty();
            $dataProperty->AddField("ParentId", $channelId);
            $dataProperty->AddField("State", $state);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }

        return $result;
    }

    public function GetWaitPublishListOfSiteId($siteId){

    }


    /**
     * 子节点按推荐级别的列表数据集
     * @param int $channelId 频道id
     * @param string $topCount 分页参数，如 9 或 3,9(第4至10条)
     * @param int $state 状态
     * @param string $recLevel 推荐级别
     * @param int $orderBy 排序方式
     * @return array|null 返回子节点的列表数据集
     */
    public function GetListOfRecLevelChild($channelId, $topCount, $state, $recLevel="", $orderBy) {

        $result = null;

        if(!empty($topCount)){
            $recLevelSelection=" AND RecLevel Between 1 AND 10 ";
            if($recLevel!=""){
                $recLevelSelection=" AND RecLevel Between ".$recLevel." ";
            }
            $selectColumn = '
            dn.*,
            uf1.UploadFilePath AS TitlePic1,
            uf2.UploadFilePath AS TitlePic2,
            uf3.UploadFilePath AS TitlePic3,
            c.ChannelName

            ';

            $sql = "SELECT $selectColumn FROM " . self::TableName_DocumentNews . " dn

                    LEFT OUTER JOIN " .self::TableName_Channel." c on dn.ChannelId = c.ChannelId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf1 on dn.TitlePic1UploadFileId=uf1.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf2 on dn.TitlePic2UploadFileId=uf2.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf3 on dn.TitlePic3UploadFileId=uf3.UploadFileId

                WHERE
                    dn.ChannelId IN (SELECT ChannelId FROM ".self::TableName_Channel." WHERE ParentId=:ParentId)
                    AND dn.State=:State
                " . $recLevelSelection . "


                ORDER BY dn.ShowDate DESC, dn.RecLevel DESC, dn.Sort DESC, dn.CreateDate DESC
                LIMIT " . $topCount;

            $dataProperty = new DataProperty();
            $dataProperty->AddField("ParentId", $channelId);
            $dataProperty->AddField("State", $state);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }

        return $result;
    }

    /**
     * 子和孙节点按推荐级别的列表数据集
     * @param int $channelId 频道id
     * @param string $topCount 分页参数，如 9 或 3,9(第4至10条)
     * @param int $state 状态
     * @param string $recLevel 推荐级别
     * @param int $orderBy 排序方式
     * @return array|null 返回子和孙节点的列表数据集
     */
    public function GetListOfRecLevelGrandson($channelId, $topCount, $state, $recLevel="" ,$orderBy) {

        $result = null;

        if(!empty($topCount)){
            $recLevelSelection=" AND RecLevel Between 1 AND 10 ";
            if($recLevel!=""){
                $recLevelSelection=" AND RecLevel Between ".$recLevel." ";
            }
            $selectColumn = '
            dn.*,
            uf1.UploadFilePath AS TitlePic1,
            uf2.UploadFilePath AS TitlePic2,
            uf3.UploadFilePath AS TitlePic3,
            c.ChannelName


            ';

            $sql = "SELECT $selectColumn FROM " . self::TableName_DocumentNews . " dn
                    LEFT OUTER JOIN ".self::TableName_Channel." c on dn.ChannelId = c.ChannelId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf1 on dn.TitlePic1UploadFileId=uf1.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf2 on dn.TitlePic2UploadFileId=uf2.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf3 on dn.TitlePic3UploadFileId=uf3.UploadFileId

                WHERE

                    (dn.ChannelId IN (SELECT ChannelId FROM ".self::TableName_Channel." WHERE ParentId=:ParentId)
                        OR
                     dn.ChannelId IN (SELECT ChannelId FROM ".self::TableName_Channel." WHERE ParentId IN (SELECT ChannelId FROM ".self::TableName_Channel." WHERE ParentId=:ParentId))
                    )
                    AND dn.State=:State
                " . $recLevelSelection . "



                ORDER BY dn.ShowDate DESC, dn.RecLevel DESC, dn.CreateDate DESC
                LIMIT " . $topCount;

            $dataProperty = new DataProperty();
            $dataProperty->AddField("ParentId", $channelId);
            $dataProperty->AddField("State", $state);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }

        return $result;
    }

    /**
     * 以从其他频道取出的数组新增稿件到目的频道 (复制)
     * @param int $targetSiteId 站点id
     * @param int $targetCid 频道id
     * @param array $arrayOfDocumentNewsList DocumentNews数据数组
     * @param int $manageUserId 操作人id
     * @param string $manageUserName 操作人用户名
     * @return int 执行结果
     */
    public function Copy($targetSiteId,$targetCid, $arrayOfDocumentNewsList, $manageUserId, $manageUserName){
        $result=-1;
        if(count($arrayOfDocumentNewsList)>0&&$targetCid>0){
            $sort=self::GetMaxSortOfChannel($targetCid);  //排序号处理
            if($sort<0){
                $sort=0;
            }
            foreach($arrayOfDocumentNewsList as $documentNews){
                $sort+=1;                           //排序号+1
                $sql="INSERT INTO " . self::TableName_DocumentNews . "
                (
                    `SiteId`,
                    `ChannelId`,
                    `DocumentNewsTitle`,
                    `DocumentNewsSubTitle`,
                    `DocumentNewsCiteTitle`,
                    `DocumentNewsShortTitle`,
                    `DocumentNewsIntro`,
                    `CreateDate`,
                    `ManageUserId`,
                    `ManageUserName`,
                    `UserId`,
                    `UserName`,
                    `Author`,
                    `DocumentNewsType`,
                    `DirectUrl`,
                    `DocumentNewsContent`,
                    `PublishDate`,
                    `PublishManageUserId`,
                    `ShowDate`,
                    `SourceName`,
                    `DocumentNewsMainTag`,
                    `DocumentNewsTag`,
                    `Sort`,
                    `TitlePic1UploadFileId`,
                    `TitlePic2UploadFileId`,
                    `TitlePic3UploadFileId`,
                    `DocumentNewsTitleColor`,
                    `DocumentNewsTitleBold`,
                    `OpenComment`,
                    `ShowHour`,
                    `ShowMinute`,
                    `ShowSecond`,
                    `UploadFiles`,
                    `IsHot`,
                    `RecLevel`,
                    `WaitPublish`,
                    `ShowPicMethod`,
                    `IsCopy`,
                    `IsAddToFullText`,
                    `ClosePosition`,
                    `Hit`,
                    `LockEdit`,
                    `LockEditDate`,
                    `LockEditManageUserId`,
                    `CommentCount`
                )
                VALUES
                (
                    :SiteId,
                    :ChannelId,
                    :DocumentNewsTitle,
                    :DocumentNewsSubTitle,
                    :DocumentNewsCiteTitle,
                    :DocumentNewsShortTitle,
                    :DocumentNewsIntro,
                    :CreateDate,
                    :ManageUserId,
                    :ManageUserName,
                    :UserId,
                    :UserName,
                    :Author,
                    :DocumentNewsType,
                    :DirectUrl,
                    :DocumentNewsContent,
                    :PublishDate,
                    :PublishManageUserId,
                    :ShowDate,
                    :SourceName,
                    :DocumentNewsMainTag,
                    :DocumentNewsTag,
                    :Sort,
                    :TitlePic1UploadFileId,
                    :TitlePic2UploadFileId,
                    :TitlePic3UploadFileId,
                    :DocumentNewsTitleColor,
                    :DocumentNewsTitleBold,
                    :OpenComment,
                    :ShowHour,
                    :ShowMinute,
                    :ShowSecond,
                    :UploadFiles,
                    :IsHot,
                    :RecLevel,
                    :WaitPublish,
                    :ShowPicMethod,
                    :IsCopy,
                    :IsAddToFullText,
                    :ClosePosition,
                    :Hit,
                    :LockEdit,
                    :LockEditDate,
                    :LockEditManageUserId,
                    :CommentCount
                ) ;";
                $dataProperty = new DataProperty();
//$dataProperty->AddField("SiteId", $documentNews["SiteId"]);
//$dataProperty->AddField("ChannelId", $documentNews["ChannelId"]);
                $dataProperty->AddField("DocumentNewsTitle", $documentNews["DocumentNewsTitle"]);
                $dataProperty->AddField("DocumentNewsSubTitle", $documentNews["DocumentNewsSubTitle"]);
                $dataProperty->AddField("DocumentNewsCiteTitle", $documentNews["DocumentNewsCiteTitle"]);
                $dataProperty->AddField("DocumentNewsShortTitle", $documentNews["DocumentNewsShortTitle"]);
                $dataProperty->AddField("DocumentNewsIntro", $documentNews["DocumentNewsIntro"]);
                $dataProperty->AddField("CreateDate", $documentNews["CreateDate"]);
//$dataProperty->AddField("ManageUserId", $documentNews["ManageUserId"]);
//$dataProperty->AddField("ManageUserName", $documentNews["ManageUserName"]);
                $dataProperty->AddField("UserId", $documentNews["UserId"]);
                $dataProperty->AddField("UserName", $documentNews["UserName"]);
                $dataProperty->AddField("Author", $documentNews["Author"]);
//$dataProperty->AddField("State", $documentNews["State"]);
                $dataProperty->AddField("DocumentNewsType", $documentNews["DocumentNewsType"]);
                $dataProperty->AddField("DirectUrl", $documentNews["DirectUrl"]);
                $dataProperty->AddField("DocumentNewsContent", $documentNews["DocumentNewsContent"]);
                $dataProperty->AddField("PublishDate", $documentNews["PublishDate"]);
                $dataProperty->AddField("PublishManageUserId", $documentNews["PublishManageUserId"]);
                $dataProperty->AddField("ShowDate", $documentNews["ShowDate"]);
                $dataProperty->AddField("SourceName", $documentNews["SourceName"]);
                $dataProperty->AddField("DocumentNewsMainTag", $documentNews["DocumentNewsMainTag"]);
                $dataProperty->AddField("DocumentNewsTag", $documentNews["DocumentNewsTag"]);
//$dataProperty->AddField("Sort", $documentNews["Sort"]);
                $dataProperty->AddField("TitlePic1UploadFileId", $documentNews["TitlePic1UploadFileId"]);
                $dataProperty->AddField("TitlePic2UploadFileId", $documentNews["TitlePic2UploadFileId"]);
                $dataProperty->AddField("TitlePic3UploadFileId", $documentNews["TitlePic3UploadFileId"]);
                $dataProperty->AddField("DocumentNewsTitleColor", $documentNews["DocumentNewsTitleColor"]);
                $dataProperty->AddField("DocumentNewsTitleBold", $documentNews["DocumentNewsTitleBold"]);
                $dataProperty->AddField("OpenComment", $documentNews["OpenComment"]);
                $dataProperty->AddField("ShowHour", $documentNews["ShowHour"]);
                $dataProperty->AddField("ShowMinute", $documentNews["ShowMinute"]);
                $dataProperty->AddField("ShowSecond", $documentNews["ShowSecond"]);
                $dataProperty->AddField("UploadFiles", $documentNews["UploadFiles"]);
                $dataProperty->AddField("IsHot", $documentNews["IsHot"]);
                $dataProperty->AddField("RecLevel", $documentNews["RecLevel"]);
                $dataProperty->AddField("WaitPublish", $documentNews["WaitPublish"]);
                $dataProperty->AddField("ShowPicMethod", $documentNews["ShowPicMethod"]);
                $dataProperty->AddField("IsCopy", $documentNews["IsCopy"]);
                $dataProperty->AddField("IsAddToFullText", $documentNews["IsAddToFullText"]);
                $dataProperty->AddField("ClosePosition", $documentNews["ClosePosition"]);
                $dataProperty->AddField("Hit", $documentNews["Hit"]);
                $dataProperty->AddField("LockEdit", $documentNews["LockEdit"]);
                $dataProperty->AddField("LockEditDate", $documentNews["LockEditDate"]);
                $dataProperty->AddField("LockEditManageUserId", $documentNews["LockEditManageUserId"]);
                $dataProperty->AddField("CommentCount", $documentNews["CommentCount"]);


                $dataProperty->AddField("ManageUserId", $manageUserId);
                $dataProperty->AddField("ManageUserName", $manageUserName);

                $dataProperty->AddField("ChannelId", $targetCid);
                $dataProperty->AddField("SiteId", $targetSiteId);
                $dataProperty->AddField("Sort", $sort);

                $newId = $this->dbOperator->Execute($sql, $dataProperty);

                $result = $newId;
                /*if ($newId > 0) {
                    //操作上传的附件
                    $sqlUploadFiles = "select UploadFiles from " . self::TableName_DocumentNews . " where DocumentNewsId=" . $newId . "";
                    $stringOfUploadFileId = $this->dbOperator->GetString($sqlUploadFiles, NULL);

                    if (!empty($stringOfUploadFileId)) {
                        $arrOfFileId = Format::ToSplit($stringOfUploadFileId, ',');
                        $newStringOfUploadFiles = "";
                        for ($j = 0; $j < count($arrOfFileId); $j++) {
                            if (!empty($arrOfFileId[$j])) {
                                //复制upload_file
                                $sqlCopyFile = "
                                    INSERT INTO " . self::TableName_UploadFile . "
                                    (
                                    	UploadFileName,
                                    	UploadFileSize,
                                    	UploadFileType,
                                    	UploadFileOrgName,
                                    	UploadFilePath,
                                    	UploadFileTitle,
                                    	UploadFileInfo,
                                    	TableType,
                                    	TableId,
                                    	UploadYear,
                                    	UploadMonth,
                                    	UploadDay,
                                    	AdminUserId,
                                    	UserId,
                                    	CreateDate,
                                    	IsBatchUpload
                                    )

                                    SELECT

                                    UploadFileName,
                                    	UploadFileSize,
                                    	UploadFileType,
                                    	UploadFileOrgName,
                                    	UploadFilePath,
                                    	UploadFileTitle,
                                    	UploadFileInfo,
                                    	TableType,
                                    	" . $newId . ",
                                    	UploadYear,
                                    	UploadMonth,
                                    	UploadDay,
                                    	AdminUserId,
                                    	UserId,
                                    	CreateDate,
                                    	IsBatchUpload

                                    FROM " . self::TableName_UploadFile . " WHERE UploadFileId=" . $arrOfFileId[$j] . ";

                                    ";
                                $newFileId = $this->dbOperator->Execute($sqlCopyFile, NULL);

                                $newStringOfUploadFiles = $newStringOfUploadFiles . "," . $newFileId;
                            }
                        }

                        //修改news表的UploadFiles
                        $sqlModifyUploadFile = "UPDATE " . self::TableName_DocumentNews . " SET UploadFiles='" . $newStringOfUploadFiles . "' WHERE DocumentNewsId=" . $newId . "";
                        $this->dbOperator->Execute($sqlModifyUploadFile, NULL);
                    }
                }*/
            }
        }
        return $result;
    }



    /**
     * 移动文档到另一个频道
     * @param int $targetSiteId 目的站点id
     * @param int $targetChannelId 目的节点id
     * @param array $arrayOfDocumentNewsList 要移动的文档数据集
     * @param int $manageUserId 操作人id
     * @param string $manageUserName 操作人用户名
     * @return int
     */
    public function Move($targetSiteId, $targetChannelId, $arrayOfDocumentNewsList,$manageUserId,$manageUserName) {
        $result=-1;
        if($targetChannelId>0&&count($arrayOfDocumentNewsList)>0){
            $sort=self::GetMaxSortOfChannel($targetChannelId);  //排序号处理
            if($sort<0){
                $sort=0;
            }

            foreach($arrayOfDocumentNewsList as $documentNews){
                $sort++;
                $sql = "UPDATE " . self::TableName_DocumentNews . "
                 SET `SiteId`=:SiteId,`ChannelId`=:ChannelId,`ManageUserId`=:ManageUserId,`ManageUserName`=:ManageUserName,Sort=" . $sort . "
                 WHERE DocumentNewsId =:DocumentNewsId ;";
                $dataProperty = new DataProperty();
                $dataProperty->AddField("SiteId", $targetSiteId);
                $dataProperty->AddField("ChannelId", $targetChannelId);
                $dataProperty->AddField("ManageUserId", $manageUserId);
                $dataProperty->AddField("ManageUserName", $manageUserName);
                $dataProperty->AddField("DocumentNewsId", $documentNews["DocumentNewsId"]);
                $result = $this->dbOperator->Execute($sql, $dataProperty);
            }
        }
        return $result;
    }


    /**
     * 以从newspaper取出的数组新增稿件到频道 (复制)
     * @param int $targetSiteId 站点id
     * @param int $targetCid 频道id
     * @param array $oneNewspaperForCopy newspaper数据数组
     * @param int $manageUserId 操作人id
     * @param string $manageUserName 操作人用户名
     * @return int 执行结果
     */
    public function CopyFromNewsPaperArticle($targetSiteId, $targetCid, $oneNewspaperForCopy, $manageUserId, $manageUserName){
        $result=-1;
        if($oneNewspaperForCopy&&$targetCid>0){
            $sort=self::GetMaxSortOfChannel($targetCid);  //排序号处理
                $sort+=1;                       //排序号+1
                $sql="INSERT INTO " . self::TableName_DocumentNews . "
                (
                    DocumentNewsTitle,
                    DocumentNewsSubTitle,
                    DocumentNewsCiteTitle,
                    DocumentNewsContent,
                    State,
                    CreateDate,
                    ShowDate,
                    ShowHour,
                    ShowMinute,
                    ShowSecond,
                    SourceName,
                    Author,
                    DirectUrl,
                    ManageUserId,
                    ManageUserName,
                    ChannelId,
                    SiteId,
                    Sort
                )
                VALUES
                (
                    :DocumentNewsTitle,
                    :DocumentNewsSubTitle,
                    :DocumentNewsCiteTitle,
                    :DocumentNewsContent,
                    :State,
                    :CreateDate,
                    :ShowDate,
                    :ShowHour,
                    :ShowMinute,
                    :ShowSecond,
                    :SourceName,
                    :Author,
                    :DirectUrl,
                    :ManageUserId,
                    :ManageUserName,
                    :ChannelId,
                    :SiteId,
                    :Sort
                ) ;";
                $dataProperty = new DataProperty();
                $dataProperty->AddField("DocumentNewsTitle", $oneNewspaperForCopy["NewspaperArticleTitle"]);
                $dataProperty->AddField("DocumentNewsSubTitle", $oneNewspaperForCopy["NewspaperArticleSubTitle"]);
                $dataProperty->AddField("DocumentNewsCiteTitle", $oneNewspaperForCopy["NewspaperArticleCiteTitle"]);
                $dataProperty->AddField("DocumentNewsContent", $oneNewspaperForCopy["NewspaperArticleContent"]);
                $dataProperty->AddField("State", $oneNewspaperForCopy["State"]);
                $dataProperty->AddField("CreateDate", $oneNewspaperForCopy["CreateDate"]);


                $dataProperty->AddField("ShowDate", Format::DateStringToDate($oneNewspaperForCopy["CreateDate"]));
                $dataProperty->AddField("ShowHour", Format::GetHour($oneNewspaperForCopy["CreateDate"]));
                $dataProperty->AddField("ShowMinute", Format::GetMinute($oneNewspaperForCopy["CreateDate"]));
                $dataProperty->AddField("ShowSecond", Format::GetSecond($oneNewspaperForCopy["CreateDate"]));

                $dataProperty->AddField("SourceName", $oneNewspaperForCopy["Source"]);
                $dataProperty->AddField("Author", $oneNewspaperForCopy["Author"]);
                $dataProperty->AddField("DirectUrl", $oneNewspaperForCopy["DirectUrl"]);


                $dataProperty->AddField("ManageUserId", $manageUserId);
                $dataProperty->AddField("ManageUserName", $manageUserName);

                $dataProperty->AddField("ChannelId", $targetCid);
                $dataProperty->AddField("SiteId", $targetSiteId);
                $dataProperty->AddField("Sort", $sort);

            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);





            //$result = $this->dbOperator->ExecuteBatch($arraySql, $arrayDataProperty);
        }
        return $result;
    }


    /**
     * 取得频道下最大的排序号 (用于批量移动&复制稿件)
     * @param int $channelId id
     * @return int 对应排序号
     */
    public function GetMaxSortOfChannel($channelId)
    {
        $result = 0;
        if ($channelId > 0) {
            $sql = "select max(sort) as MaxSort from " . self::TableName_DocumentNews . " where ChannelId=:ChannelId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);
            $result = $this->dbOperator->GetInt($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 由id字符串返回对应id稿件的数据集
     * @param string $documentNewsIdString id
     * @return array|null 取得对应数组
     */
    public function GetListByIDString($documentNewsIdString)
    {
        $result = null;
        if ($documentNewsIdString !="") {
            $sql = "SELECT * FROM
            " . self::TableName_DocumentNews . "
            WHERE DocumentNewsId IN ($documentNewsIdString)
            ;";
            $dataProperty = new DataProperty();
//$dataProperty->AddField("NewspaperArticleIdString", $newspaperArticleIdString);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }



    /**
     * 修改UploadFileIds(附件id字符串)
     * @param int $documentNewsId id
     * @param string $strUploadFileId 附件id字符串
     * @return int 操作结果
     */
    public function ModifyUploadFiles($documentNewsId,$strUploadFileId)
    {
        $result = -1;
        if ($documentNewsId>0) {
            $sql = "UPDATE
            " . self::TableName_DocumentNews . "
            SET UploadFiles=:UploadFiles
            WHERE DocumentNewsId=:DocumentNewsId
            ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UploadFiles", $strUploadFileId);
            $dataProperty->AddField("DocumentNewsId", $documentNewsId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 在文档最前端插入内容
     * @param int $documentNewsId id
     * @param string $strPrepend 插入字符串
     * @return int 操作结果
     */
    public function PrependContent($documentNewsId,$strPrepend)
    {
        $result = -1;
        if ($documentNewsId>0) {
            $sql = "UPDATE
            " . self::TableName_DocumentNews . "
            SET DocumentNewsContent=concat('".$strPrepend."',DocumentNewsContent)
            WHERE DocumentNewsId=:DocumentNewsId
            ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("DocumentNewsId", $documentNewsId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

}

?>
