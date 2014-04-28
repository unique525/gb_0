<?php
/**
 * 后台管理 资讯 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Document
 * @author zhangchi
 */
class DocumentNewsManageData extends BaseManageData
{
    /** 文档状态定义 */

    /**
     * 新稿
     */
    const STATE_NEW = 0;
    /**
     * 已编
     */
    const STATE_MODIFY = 1;
    /**
     * 返工
     */
    const STATE_REDO = 2;
    /**
     * 一审
     */
    const STATE_FIRST_VERIFY = 11;
    /**
     * 二审
     */
    const STATE_SECOND_VERIFY = 12;
    /**
     * 三审
     */
    const STATE_THIRD_VERIFY = 13;
    /**
     * 终审
     */
    const STATE_FINAL_VERIFY = 14;
    /**
     * 已否
     */
    const STATE_REFUSE = 20;
    /**
     * 已发
     */
    const STATE_PUBLISHED = 30;


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
     * @param string $titlePic1 题图1
     * @param string $titlePic2 题图2
     * @param string $titlePic3 题图3
     * @param string $titlePicMobile 移动客户端题图
     * @param string $titlePicPad 平板客户端题图
     * @return int 新增的频道id
     */
    public function Create($httpPostData, $titlePic1 = '', $titlePic2 = '', $titlePic3 = '', $titlePicMobile = '', $titlePicPad = '')
    {
        $result = -1;
        $dataProperty = new DataProperty();
        $addFieldName = "";
        $addFieldValue = "";
        $preNumber = "";
        $addFieldNames = array("TitlePic1", "TitlePic2", "TitlePic3", "TitlePicMobile", "TitlePicPad");
        $addFieldValues = array($titlePic1, $titlePic2, $titlePic3, $titlePicMobile, $titlePicPad);
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
     * @param string $titlePic1 题图1
     * @param string $titlePic2 题图2
     * @param string $titlePic3 题图3
     * @param string $titlePicMobile 移动客户端题图
     * @param string $titlePicPad 平板客户端题图
     * @return int 返回影响的行数
     */
    public function Modify($httpPostData, $documentNewsId, $titlePic1 = "", $titlePic2 = "", $titlePic3 = "", $titlePicMobile = "", $titlePicPad = "") {
        $dataProperty = new DataProperty();
        $addFieldNames = array();
        $addFieldValues = array();
        if (!empty($titlePic1)) {
            $addFieldNames[] = "TitlePic";
            $addFieldValues[] = $titlePic1;
        }
        if (!empty($titlePic2)) {
            $addFieldNames[] = "TitlePic2";
            $addFieldValues[] = $titlePic2;
        }
        if (!empty($titlePic3)) {
            $addFieldNames[] = "TitlePic3";
            $addFieldValues[] = $titlePic3;
        }
        if (!empty($titlePicMobile)) {
            $addFieldNames[] = "TitlePicMobile";
            $addFieldValues[] = $titlePicMobile;
        }
        if (!empty($titlePicPad)) {
            $addFieldNames[] = "TitlePicPad";
            $addFieldValues[] = $titlePicPad;
        }
        $sql = parent::GetUpdateSql($httpPostData, self::TableName_DocumentNews, self::TableId_DocumentNews, $documentNewsId, $dataProperty, "", "", "", $addFieldNames, $addFieldValues);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
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
     * 取得后台资讯列表数据集
     * @param int $channelId 频道id
     * @param int $pageBegin 起始记录行
     * @param int $pageSize 页大小
     * @param int $allCount 记录总数
     * @param string $searchKey 查询字符
     * @param int $searchType 查询下拉框的类别
     * @param int $isSelf 是否只显示当前登录的管理员录入的资讯
     * @param int $manageUserId 后台管理员id
     * @return array 资讯列表数据集
     */
    public function GetList($channelId, $pageBegin, $pageSize, &$allCount, $searchKey = "", $searchType = 0, $isSelf = 0, $manageUserId = 0)
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
                $searchSql = " AND (DocumentNewsTitle LIKE :SearchKey1 OR ManageUserName LIKE :SearchKey2 OR username LIKE :SearchKey3 OR DocumentNewsTag LIKE :SearchKey4)";
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

        $sql = "
            SELECT
            DocumentNewsId,DocumentNewsType,DocumentNewsTitle,State,Sort,ChannelId,PublishDate,
            CreateDate,ManageUserId,ManageUserName,UserName,DocumentNewsTitleColor,DocumentNewsTitleBold,TitlePic1,RecLevel,Hit
            FROM
            " . self::TableName_DocumentNews . "
            WHERE ChannelId=:ChannelId AND State<100 " . $searchSql . " " . $conditionManageUserId . " ORDER BY Sort DESC, CreateDate DESC LIMIT " . $pageBegin . "," . $pageSize . ";";

        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

        $dataProperty = new DataProperty();
        $dataProperty->AddField("ChannelId", $channelId);
        if (strlen($searchKey) > 0 && $searchKey != "undefined") {
            $searchSql = " AND (DocumentNewsTitle LIKE :SearchKey1 OR ManageUserName LIKE :SearchKey2 OR username LIKE :SearchKey3 OR DocumentNewsTag LIKE :SearchKey4)";
            $dataProperty->AddField("SearchKey1", "%" . $searchKey . "%");
            $dataProperty->AddField("SearchKey2", "%" . $searchKey . "%");
            $dataProperty->AddField("SearchKey3", "%" . $searchKey . "%");
            $dataProperty->AddField("SearchKey4", "%" . $searchKey . "%");
        }
        $sql = "SELECT count(*) FROM " . self::TableName_DocumentNews . " WHERE ChannelId=:ChannelId AND State<100 " . $conditionManageUserId . " " . $searchSql . ";";
        $allCount = $this->dbOperator->GetInt($sql, $dataProperty);

        return $result;
    }

    /**
     * 拖动排序
     * @param array $arrDocumentNewsId 待处理的文档编号数组
     */
    public function ModifySort($arrDocumentNewsId)
    {
        if (count($arrDocumentNewsId) > 1) { //大于1条时排序才有意义
            $strDocumentNewsId = Join(',', $arrDocumentNewsId);
            $sql = "SELECT max(Sort) FROM " . self::TableName_DocumentNews . " WHERE DocumentNewsId IN ($strDocumentNewsId)";
            $maxSort = $this->dbOperator->GetInt($sql, null);
            $arrSql = array();
            for ($i = 0; $i < Count($arrDocumentNewsId); $i++) {
                $newSort = $maxSort - $i;
                if ($newSort < 0) {
                    $newSort = 0;
                }
                $sql = "UPDATE " . self::TableName_DocumentNews . " SET Sort=$newSort WHERE DocumentNewsId=$arrDocumentNewsId[$i];";
                $arrSql[] = $sql;
            }
            $this->dbOperator->ExecuteBatch($arrSql, null);
        }
    }

    /**
     * 修改锁定状态和时间
     * @param int $documentNewsId 文档id
     * @param int $lockEdit 是否锁定
     * @param int $adminUserId 操作管理员id
     * @return int 操作结果
     */
    public function ModifyLockEdit($documentNewsId, $lockEdit, $adminUserId)
    {
        $result = 0;
        if ($documentNewsId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_DocumentNews . " SET `LockEdit`=:LockEdit,LockEditDate=now(),LockEditAdminUserId=:LockEditAdminUserId WHERE DocumentNewsId=:DocumentNewsId;";
            $dataProperty->AddField("DocumentNewsId", $documentNewsId);
            $dataProperty->AddField("LockEdit", $lockEdit);
            $dataProperty->AddField("LockEditAdminUserId", $adminUserId);
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
            $sql = "UPDATE " . self::TableName_DocumentNews . " SET `State`=:State WHERE DocumentNewsId=:DocumentNewsId;";
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
}

?>
