<?php
/**
 * 客户端 资讯 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Document
 * @author zhangchi
 */
class DocumentNewsClientData extends BaseClientData {

    const SELECT_COLUMN_FOR_LIST = "
                dn.DocumentNewsId,
                dn.SiteId,
                dn.ChannelId,
                dn.DocumentNewsType,
                dn.DocumentNewsTitle,
                dn.DocumentNewsShortTitle,
                dn.DocumentNewsSubTitle,
                dn.DocumentNewsCiteTitle,
                dn.DocumentNewsIntro,
                dn.State,
                dn.Sort,
                dn.ChannelId,
                dn.PublishDate,
                dn.CreateDate,
                dn.ManageUserId,
                dn.ManageUserName,
                dn.UserId,
                dn.UserName,
                dn.DocumentNewsTitleColor,
                dn.DocumentNewsTitleBold,
                dn.RecLevel,
                dn.Hit,
                dn.DocumentNewsContent,
                dn.Author,
                dn.DirectUrl,
                dn.ShowDate,
                dn.ShowFullDate,
                dn.SourceName,
                dn.DocumentNewsMainTag,
                dn.DocumentNewsTag,
                dn.TitlePic1UploadFileId,
                dn.TitlePic2UploadFileId,
                dn.TitlePic3UploadFileId,
                dn.OpenComment,
                dn.ShowHour,
                dn.ShowMinute,
                dn.ShowSecond,
                dn.IsHot,
                dn.CommentCount,
                dn.ShowMutiPicInClientList,
                dn.ClientIconType,
                uf1.UploadFilePath AS TitlePic1UploadFilePath,
                uf1.UploadFileMobilePath AS TitlePic1UploadFileMobilePath,
                uf1.UploadFilePadPath AS TitlePic1UploadFilePadPath,
                uf1.UploadFileThumbPath1 AS TitlePic1UploadFileThumbPath1,
                uf1.UploadFileThumbPath2 AS TitlePic1UploadFileThumbPath2,
                uf1.UploadFileThumbPath3 AS TitlePic1UploadFileThumbPath3,
                uf1.UploadFileWatermarkPath1 AS TitlePic1UploadFileWatermarkPath1,
                uf1.UploadFileWatermarkPath2 AS TitlePic1UploadFileWatermarkPath2,
                uf1.UploadFileCompressPath1 AS TitlePic1UploadFileCompressPath1,
                uf1.UploadFileCompressPath2 AS TitlePic1UploadFileCompressPath2,
                uf1.UploadFileCutPath1 AS TitlePic1UploadFileCutPath1,

                uf2.UploadFilePath AS TitlePic2UploadFilePath,
                uf2.UploadFileMobilePath AS TitlePic2UploadFileMobilePath,
                uf2.UploadFilePadPath AS TitlePic2UploadFilePadPath,
                uf2.UploadFileThumbPath1 AS TitlePic2UploadFileThumbPath1,
                uf2.UploadFileThumbPath2 AS TitlePic2UploadFileThumbPath2,
                uf2.UploadFileThumbPath3 AS TitlePic2UploadFileThumbPath3,
                uf2.UploadFileWatermarkPath1 AS TitlePic2UploadFileWatermarkPath1,
                uf2.UploadFileWatermarkPath2 AS TitlePic2UploadFileWatermarkPath2,
                uf2.UploadFileCompressPath1 AS TitlePic2UploadFileCompressPath1,
                uf2.UploadFileCompressPath2 AS TitlePic2UploadFileCompressPath2,
                uf2.UploadFileCutPath1 AS TitlePic2UploadFileCutPath1,

                uf3.UploadFilePath AS TitlePic3UploadFilePath,
                uf3.UploadFileMobilePath AS TitlePic3UploadFileMobilePath,
                uf3.UploadFilePadPath AS TitlePic3UploadFilePadPath,
                uf3.UploadFileThumbPath1 AS TitlePic3UploadFileThumbPath1,
                uf3.UploadFileThumbPath2 AS TitlePic3UploadFileThumbPath2,
                uf3.UploadFileThumbPath3 AS TitlePic3UploadFileThumbPath3,
                uf3.UploadFileWatermarkPath1 AS TitlePic3UploadFileWatermarkPath1,
                uf3.UploadFileWatermarkPath2 AS TitlePic3UploadFileWatermarkPath2,
                uf3.UploadFileCompressPath1 AS TitlePic3UploadFileCompressPath1,
                uf3.UploadFileCompressPath2 AS TitlePic3UploadFileCompressPath2,
                uf3.UploadFileCutPath1 AS TitlePic3UploadFileCutPath1

    ";

    const SELECT_COLUMN_FOR_ONE = "
                        dn.*,
                        uf1.UploadFilePath AS TitlePic1UploadFilePath,
                        uf1.UploadFileMobilePath AS TitlePic1UploadFileMobilePath,
                        uf1.UploadFilePadPath AS TitlePic1UploadFilePadPath,
                        uf1.UploadFileThumbPath1 AS TitlePic1UploadFileThumbPath1,
                        uf1.UploadFileThumbPath2 AS TitlePic1UploadFileThumbPath2,
                        uf1.UploadFileThumbPath3 AS TitlePic1UploadFileThumbPath3,
                        uf1.UploadFileWatermarkPath1 AS TitlePic1UploadFileWatermarkPath1,
                        uf1.UploadFileWatermarkPath2 AS TitlePic1UploadFileWatermarkPath2,
                        uf1.UploadFileCompressPath1 AS TitlePic1UploadFileCompressPath1,
                        uf1.UploadFileCompressPath2 AS TitlePic1UploadFileCompressPath2,
                        uf1.UploadFileCutPath1 AS TitlePic1UploadFileCutPath1,


                        uf2.UploadFilePath AS TitlePic2UploadFilePath,
                        uf2.UploadFileMobilePath AS TitlePic2UploadFileMobilePath,
                        uf2.UploadFilePadPath AS TitlePic2UploadFilePadPath,
                        uf2.UploadFileThumbPath1 AS TitlePic2UploadFileThumbPath1,
                        uf2.UploadFileThumbPath2 AS TitlePic2UploadFileThumbPath2,
                        uf2.UploadFileThumbPath3 AS TitlePic2UploadFileThumbPath3,
                        uf2.UploadFileWatermarkPath1 AS TitlePic2UploadFileWatermarkPath1,
                        uf2.UploadFileWatermarkPath2 AS TitlePic2UploadFileWatermarkPath2,
                        uf2.UploadFileCompressPath1 AS TitlePic2UploadFileCompressPath1,
                        uf2.UploadFileCompressPath2 AS TitlePic2UploadFileCompressPath2,
                        uf2.UploadFileCutPath1 AS TitlePic2UploadFileCutPath1,


                        uf3.UploadFilePath AS TitlePic3UploadFilePath,
                        uf3.UploadFileMobilePath AS TitlePic3UploadFileMobilePath,
                        uf3.UploadFilePadPath AS TitlePic3UploadFilePadPath,
                        uf3.UploadFileThumbPath1 AS TitlePic3UploadFileThumbPath1,
                        uf3.UploadFileThumbPath2 AS TitlePic3UploadFileThumbPath2,
                        uf3.UploadFileThumbPath3 AS TitlePic3UploadFileThumbPath3,
                        uf3.UploadFileWatermarkPath1 AS TitlePic3UploadFileWatermarkPath1,
                        uf3.UploadFileWatermarkPath2 AS TitlePic3UploadFileWatermarkPath2,
                        uf3.UploadFileCompressPath1 AS TitlePic3UploadFileCompressPath1,
                        uf3.UploadFileCompressPath2 AS TitlePic3UploadFileCompressPath2,
                        uf3.UploadFileCutPath1 AS TitlePic3UploadFileCutPath1
                        ";

    /**
     * 全站调用
     * @param int $siteId
     * @param int $pageBegin
     * @param int $pageSize
     * @param string $searchKey
     * @param int $searchType
     * @param int $showInClientIndex
     * @return array
     */
    public function GetListOfSite(
        $siteId,
        $pageBegin,
        $pageSize,
        $searchKey = "",
        $searchType = 0,
        $showInClientIndex = -1){

        $searchKey = Format::FormatSql($searchKey);
        $searchSql = "";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("SiteId", $siteId);
        if (strlen($searchKey) > 0 && $searchKey != "undefined") {
            if ($searchType == 0) { //标题
                $searchSql = " AND (dn.DocumentNewsTitle like :SearchKey)";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            } else if ($searchType == 1) { //来源
                $searchSql = " AND (dn.SourceName like :SearchKey)";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            } else if ($searchType == 2) { //发布人
                $searchSql = " AND (dn.ManageUserName like :SearchKey)";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            } else if ($searchType == 3) { //标签
                $searchSql = " AND (dn.DocumentNewsTag like :SearchKey)";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            } else if ($searchType == 4) { //投稿人
                $searchSql = " AND (dn.UserName like :SearchKey)";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            } else { //模糊
                $searchSql = " AND (dn.DocumentNewsTitle LIKE :SearchKey1
                                    OR dn.ManageUserName LIKE :SearchKey2
                                    OR dn.UserName LIKE :SearchKey3
                                    OR dn.DocumentNewsTag LIKE :SearchKey4)";
                $dataProperty->AddField("SearchKey1", "%" . $searchKey . "%");
                $dataProperty->AddField("SearchKey2", "%" . $searchKey . "%");
                $dataProperty->AddField("SearchKey3", "%" . $searchKey . "%");
                $dataProperty->AddField("SearchKey4", "%" . $searchKey . "%");
            }
        }

        $orderBy = " dn.ShowFullDate DESC ";

        if ($showInClientIndex>-1){

            $searchSql = " AND ShowInClientIndex>=:ShowInClientIndex ";
            $dataProperty->AddField("ShowInClientIndex", $showInClientIndex);
            $orderBy = " dn.ShowInClientIndex DESC, dn.ShowFullDate DESC ";
        }

        $sql = "
            SELECT
                ".self::SELECT_COLUMN_FOR_LIST."
            FROM
            " . self::TableName_DocumentNews . " dn
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf1 on dn.TitlePic1UploadFileId=uf1.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf2 on dn.TitlePic2UploadFileId=uf2.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf3 on dn.TitlePic3UploadFileId=uf3.UploadFileId
            WHERE dn.SiteId=:SiteId AND dn.State=30 AND dn.ShowInClient=1 " . $searchSql . "
            ORDER BY $orderBy LIMIT " . $pageBegin . "," . $pageSize . ";";


        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

        return $result;

    }

    /**
     * 取得资讯列表数据集
     * @param int $channelId 频道id
     * @param int $pageBegin 起始记录行
     * @param int $pageSize 页大小
     * @param string $searchKey 查询字符
     * @param int $searchType 查询下拉框的类别
     * @param int $showInClientIndex 推送到app首页 -1不处理 0不推送 1推送
     * @return array 资讯列表数据集
     */
    public function GetList(
        $channelId,
        $pageBegin,
        $pageSize,
        $searchKey = "",
        $searchType = 0,
        $showInClientIndex = -1
    )
    {
        $searchKey = Format::FormatSql($searchKey);
        $searchSql = "";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("ChannelId", $channelId);
        if (strlen($searchKey) > 0 && $searchKey != "undefined") {
            if ($searchType == 0) { //标题
                $searchSql = " AND (dn.DocumentNewsTitle like :SearchKey)";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            } else if ($searchType == 1) { //来源
                $searchSql = " AND (dn.SourceName like :SearchKey)";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            } else if ($searchType == 2) { //发布人
                $searchSql = " AND (dn.ManageUserName like :SearchKey)";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            } else if ($searchType == 3) { //标签
                $searchSql = " AND (dn.DocumentNewsTag like :SearchKey)";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            } else if ($searchType == 4) { //投稿人
                $searchSql = " AND (dn.UserName like :SearchKey)";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            } else { //模糊
                $searchSql = " AND (dn.DocumentNewsTitle LIKE :SearchKey1
                                    OR dn.ManageUserName LIKE :SearchKey2
                                    OR dn.UserName LIKE :SearchKey3
                                    OR dn.DocumentNewsTag LIKE :SearchKey4)";
                $dataProperty->AddField("SearchKey1", "%" . $searchKey . "%");
                $dataProperty->AddField("SearchKey2", "%" . $searchKey . "%");
                $dataProperty->AddField("SearchKey3", "%" . $searchKey . "%");
                $dataProperty->AddField("SearchKey4", "%" . $searchKey . "%");
            }
        }

        $orderBy = " dn.ShowFullDate DESC ";

        if ($showInClientIndex>-1){

            $searchSql = " AND ShowInClientIndex>=:ShowInClientIndex ";
            $dataProperty->AddField("ShowInClientIndex", $showInClientIndex);

            $orderBy = " dn.ShowInClientIndex DESC, dn.ShowFullDate DESC ";

        }

        $sql = "
            SELECT
                ".self::SELECT_COLUMN_FOR_LIST."
            FROM
            " . self::TableName_DocumentNews . " dn
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf1 on dn.TitlePic1UploadFileId=uf1.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf2 on dn.TitlePic2UploadFileId=uf2.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf3 on dn.TitlePic3UploadFileId=uf3.UploadFileId
            WHERE dn.ChannelId=:ChannelId AND dn.State=30 AND dn.ShowInClient=1 " . $searchSql . "
            ORDER BY $orderBy LIMIT " . $pageBegin . "," . $pageSize . ";";


        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

        return $result;
    }


    /**
     * 取得资讯列表数据集
     * @param int $channelIds 频道id字符串，","分隔，如 1,2,3,4
     * @param int $pageBegin 起始记录行
     * @param int $pageSize 页大小
     * @param string $searchKey 查询字符
     * @param int $searchType 查询下拉框的类别
     * @param int $showInClientIndex 推送到app首页 -1不处理 0不推送 1推送
     * @return array 资讯列表数据集
     */
    public function GetListInChannelId(
        $channelIds,
        $pageBegin,
        $pageSize,
        $searchKey = "",
        $searchType = 0,
        $showInClientIndex = -1
    )
    {
        $channelIds = Format::FormatSql($channelIds);
        $searchKey = Format::FormatSql($searchKey);
        $searchSql = "";
        $dataProperty = new DataProperty();
        if (strlen($searchKey) > 0 && $searchKey != "undefined") {
            if ($searchType == 0) { //标题
                $searchSql = " AND (dn.DocumentNewsTitle like :SearchKey)";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            } else if ($searchType == 1) { //来源
                $searchSql = " AND (dn.SourceName like :SearchKey)";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            } else if ($searchType == 2) { //发布人
                $searchSql = " AND (dn.ManageUserName like :SearchKey)";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            } else if ($searchType == 3) { //标签
                $searchSql = " AND (dn.DocumentNewsTag like :SearchKey)";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            } else if ($searchType == 4) { //投稿人
                $searchSql = " AND (dn.UserName like :SearchKey)";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            } else { //模糊
                $searchSql = " AND (dn.DocumentNewsTitle LIKE :SearchKey1
                                    OR dn.ManageUserName LIKE :SearchKey2
                                    OR dn.UserName LIKE :SearchKey3
                                    OR dn.DocumentNewsTag LIKE :SearchKey4)";
                $dataProperty->AddField("SearchKey1", "%" . $searchKey . "%");
                $dataProperty->AddField("SearchKey2", "%" . $searchKey . "%");
                $dataProperty->AddField("SearchKey3", "%" . $searchKey . "%");
                $dataProperty->AddField("SearchKey4", "%" . $searchKey . "%");
            }
        }

        $orderBy = " dn.ShowFullDate DESC ";

        if ($showInClientIndex>-1){

            $searchSql = " AND ShowInClientIndex>=:ShowInClientIndex ";
            $dataProperty->AddField("ShowInClientIndex", $showInClientIndex);
            $orderBy = " dn.ShowInClientIndex DESC, dn.ShowFullDate DESC ";
        }

        $sql = "
            SELECT
                ".self::SELECT_COLUMN_FOR_LIST."
            FROM
            " . self::TableName_DocumentNews . " dn
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf1 on dn.TitlePic1UploadFileId=uf1.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf2 on dn.TitlePic2UploadFileId=uf2.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf3 on dn.TitlePic3UploadFileId=uf3.UploadFileId
            WHERE dn.ChannelId IN ($channelIds) AND dn.State=30 AND dn.ShowInClient=1 " . $searchSql . "
            ORDER BY $orderBy LIMIT " . $pageBegin . "," . $pageSize . ";";

        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

        return $result;
    }

    /**
     * 取得资讯列表数据集
     * @param int $siteId
     * @param int $documentNewsId
     * @param array $arrLike
     * @param int $top
     * @return array 资讯列表数据集
     */
    public function GetListOfRelative(
        $siteId,
        $documentNewsId,
        $arrLike,
        $top
    )
    {
        $siteId = Format::FormatSql($siteId);
        $likeSql = '';
        $dataProperty = new DataProperty();

        $dataProperty->AddField("SiteId", $siteId);
        $dataProperty->AddField("DocumentNewsId", $documentNewsId);

        if (!empty($arrLike)) {

            foreach($arrLike as $val){

                $likeSql .= " OR (dn.DocumentNewsTitle like '%".$val."%')";

            }


        }

        $pos = stripos(strtolower($likeSql), ' OR');
        if ($pos !== false) {

            $likeSql = substr($likeSql, 3);

        }
        $orderBy = " dn.ShowFullDate DESC ";


        $sql = "
            SELECT
                ".self::SELECT_COLUMN_FOR_LIST."
            FROM
            " . self::TableName_DocumentNews . " dn
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf1 on dn.TitlePic1UploadFileId=uf1.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf2 on dn.TitlePic2UploadFileId=uf2.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf3 on dn.TitlePic3UploadFileId=uf3.UploadFileId
            WHERE dn.SiteId = :SiteId
            AND dn.State=30
            AND dn.DocumentNewsId <> :DocumentNewsId
            AND dn.ShowInClient=1 AND (" . $likeSql . ")
            ORDER BY $orderBy LIMIT " . $top . ";";

        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

        return $result;
    }

    /**
     * 返回一行数据
     * @param int $documentNewsId 资讯id
     * @param bool $withCache 是否使用缓存
     * @return array|null 取得对应数组
     */
    public function GetOne($documentNewsId, $withCache = FALSE){
        $result = null;
        if($documentNewsId>0){
            $sql = "
            SELECT ". self::SELECT_COLUMN_FOR_ONE ."


            FROM
            " . self::TableName_DocumentNews . " dn
            LEFT OUTER JOIN " .self::TableName_UploadFile." uf1 ON (dn.TitlePic1UploadFileId=uf1.UploadFileId)
            LEFT OUTER JOIN " .self::TableName_UploadFile." uf2 ON (dn.TitlePic2UploadFileId=uf2.UploadFileId)
            LEFT OUTER JOIN " .self::TableName_UploadFile." uf3 ON (dn.TitlePic3UploadFileId=uf3.UploadFileId)
            WHERE dn.DocumentNewsId=:DocumentNewsId AND dn.ShowInClient=1;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("DocumentNewsId", $documentNewsId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);

        }
        return $result;
    }


    /**
     * 增加一个评论数
     * @param int $documentNewsId id
     * @return int 操作结果
     */
    public function AddCommentCount($documentNewsId){
        $result = -1;
        if($documentNewsId > 0){
            $sql = "UPDATE ".self::TableName_DocumentNews." SET CommentCount = CommentCount+1 WHERE DocumentNewsId=:DocumentNewsId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("DocumentNewsId",$documentNewsId);
            $result = $this->dbOperator->Execute($sql,$dataProperty);
        }
        return $result;
    }


    public function GetDocumentNewsMainTag($documentNewsId,$withCache){
        $result = "";
        if($documentNewsId > 0){
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'document_news_data';
            $cacheFile = 'channel_get_document_news_main_tag.cache_' . $documentNewsId . '';
            $sql = "SELECT DocumentNewsMainTag FROM ".self::TableName_DocumentNews." WHERE DocumentNewsId = :DocumentNewsId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("DocumentNewsId",$documentNewsId);
            $result = $this->GetInfoOfStringValue($sql,$dataProperty,$withCache,$cacheDir,$cacheFile);
        }
        return $result;
    }


    public function GetDocumentNewsTag($documentNewsId,$withCache){
        $result = "";
        if($documentNewsId > 0){
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'document_news_data';
            $cacheFile = 'channel_get_document_news_tag.cache_' . $documentNewsId . '';
            $sql = "SELECT DocumentNewsTag FROM ".self::TableName_DocumentNews." WHERE DocumentNewsId = :DocumentNewsId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("DocumentNewsId",$documentNewsId);
            $result = $this->GetInfoOfStringValue($sql,$dataProperty,$withCache,$cacheDir,$cacheFile);
        }
        return $result;
    }
}
?>