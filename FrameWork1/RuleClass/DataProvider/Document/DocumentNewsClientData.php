<?php
/**
 * 客户端 资讯 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Document
 * @author zhangchi
 */
class DocumentNewsClientData extends BaseClientData {

    /**
     * 取得资讯列表数据集
     * @param int $channelId 频道id
     * @param int $pageBegin 起始记录行
     * @param int $pageSize 页大小
     * @param string $searchKey 查询字符
     * @param int $searchType 查询下拉框的类别
     * @return array 资讯列表数据集
     */
    public function GetList(
        $channelId,
        $pageBegin,
        $pageSize,
        $searchKey = "",
        $searchType = 0
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

        $sql = "
            SELECT
                dn.DocumentNewsId,
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

                uf3.UploadFilePath AS TitlePic3UploadFilePath,
                uf3.UploadFileMobilePath AS TitlePic3UploadFileMobilePath,
                uf3.UploadFilePadPath AS TitlePic3UploadFilePadPath,
                uf3.UploadFileThumbPath1 AS TitlePic3UploadFileThumbPath1,
                uf3.UploadFileThumbPath2 AS TitlePic3UploadFileThumbPath2,
                uf3.UploadFileThumbPath3 AS TitlePic3UploadFileThumbPath3,
                uf3.UploadFileWatermarkPath1 AS TitlePic3UploadFileWatermarkPath1,
                uf3.UploadFileWatermarkPath2 AS TitlePic3UploadFileWatermarkPath2,
                uf3.UploadFileCompressPath1 AS TitlePic3UploadFileCompressPath1,
                uf3.UploadFileCompressPath2 AS TitlePic3UploadFileCompressPath2
            FROM
            " . self::TableName_DocumentNews . " dn
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf1 on dn.TitlePic1UploadFileId=uf1.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf2 on dn.TitlePic2UploadFileId=uf2.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf3 on dn.TitlePic3UploadFileId=uf3.UploadFileId
            WHERE dn.ChannelId=:ChannelId AND dn.State<100 " . $searchSql . "
            ORDER BY dn.Sort DESC, dn.CreateDate DESC LIMIT " . $pageBegin . "," . $pageSize . ";";


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
     * @return array 资讯列表数据集
     */
    public function GetListInChannelId(
        $channelIds,
        $pageBegin,
        $pageSize,
        $searchKey = "",
        $searchType = 0
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

        $sql = "
            SELECT
                dn.DocumentNewsId,
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

                uf3.UploadFilePath AS TitlePic3UploadFilePath,
                uf3.UploadFileMobilePath AS TitlePic3UploadFileMobilePath,
                uf3.UploadFilePadPath AS TitlePic3UploadFilePadPath,
                uf3.UploadFileThumbPath1 AS TitlePic3UploadFileThumbPath1,
                uf3.UploadFileThumbPath2 AS TitlePic3UploadFileThumbPath2,
                uf3.UploadFileThumbPath3 AS TitlePic3UploadFileThumbPath3,
                uf3.UploadFileWatermarkPath1 AS TitlePic3UploadFileWatermarkPath1,
                uf3.UploadFileWatermarkPath2 AS TitlePic3UploadFileWatermarkPath2,
                uf3.UploadFileCompressPath1 AS TitlePic3UploadFileCompressPath1,
                uf3.UploadFileCompressPath2 AS TitlePic3UploadFileCompressPath2
            FROM
            " . self::TableName_DocumentNews . " dn
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf1 on dn.TitlePic1UploadFileId=uf1.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf2 on dn.TitlePic2UploadFileId=uf2.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf3 on dn.TitlePic3UploadFileId=uf3.UploadFileId
            WHERE dn.ChannelId IN ($channelIds) AND dn.State<100 " . $searchSql . "
            ORDER BY dn.Sort DESC, dn.CreateDate DESC LIMIT " . $pageBegin . "," . $pageSize . ";";

        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

        return $result;
    }
}
?>