<?php

/**
 * 前台 资讯 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Document
 * @author zhangchi
 */
class DocumentNewsPublicData extends BasePublicData {


    //////////////////////////////////////////////////////////////////////////////
    //////////////////////////////Get List////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////

    /**
     * 取得资讯列表
     * @param int $channelId 频道id
     * @param string $topCount 显示条数  1或 1,10
     * @param int $state 状态
     * @param int $orderBy 排序方式
     * @param bool $withCache 是否使用缓存
     * @return array|null 返回资讯列表
     */
    public function GetList($channelId, $topCount, $state, $orderBy = 0, $withCache = FALSE) {

        $result = null;

        if($channelId>0 && !empty($topCount)){

            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'document_news_data';
            $cacheFile = "document_news_get_list_". $channelId. "_top".$topCount."_state".$state."_order".$orderBy;



            $orderBySql = 'ORDER BY dn.Sort DESC, dn.CreateDate DESC';

            switch($orderBy){

                case 0:
                    $orderBySql = 'ORDER BY dn.Sort DESC, dn.CreateDate DESC';
                    break;

            }


            $selectColumn = '
            dn.DocumentNewsId,
            dn.SiteId,
            dn.ChannelId,
            dn.DocumentNewsTitle,
            dn.DocumentNewsSubTitle,
            dn.DocumentNewsCiteTitle,
            dn.DocumentNewsShortTitle,
            dn.DocumentNewsIntro,
            dn.CreateDate,
            dn.ManageUserId,
            dn.ManageUserName,
            dn.UserId,
            dn.UserName,
            dn.Author,
            dn.State,
            dn.DocumentNewsType,
            dn.DirectUrl,
            dn.ShowDate,
            dn.SourceName,
            dn.DocumentNewsMainTag,
            dn.DocumentNewsTag,
            dn.Sort,
            dn.TitlePic1UploadFileId,
            dn.TitlePic2UploadFileId,
            dn.TitlePic3UploadFileId,
            dn.DocumentNewsTitleColor,
            dn.DocumentNewsTitleBold,
            dn.OpenComment,
            dn.ShowHour,
            dn.ShowMinute,
            dn.ShowSecond,
            dn.IsHot,
            dn.RecLevel,
            dn.ShowPicMethod,
            dn.ClosePosition,
            dn.Hit,
            dn.PublishDate,
            (dn.Hit+dn.VirtualHit) AS AllHit,

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
            uf3.UploadFileCutPath1 AS TitlePic3UploadFileCutPath1,

            c.ChannelName
            ';

            $sql = "SELECT $selectColumn FROM " . self::TableName_DocumentNews . " dn
                    LEFT OUTER JOIN " .self::TableName_Channel." c on dn.ChannelId = c.ChannelId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf1 on dn.TitlePic1UploadFileId=uf1.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf2 on dn.TitlePic2UploadFileId=uf2.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf3 on dn.TitlePic3UploadFileId=uf3.UploadFileId
                WHERE
                    dn.ChannelId=:ChannelId
                    AND dn.State=:State
                $orderBySql LIMIT " . $topCount;
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);
            $dataProperty->AddField("State", $state);


            $result = parent::GetInfoOfArrayList($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);

            //$result = $this->dbOperator->GetArrayList($sql, $dataProperty);

        }




        return $result;
    }

    /**
     * 最新的列表数据集
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param string $topCount 分页参数，如 9 或 3,9(第4至10条)
     * @param int $state 状态
     * @param int $orderBy 排序
     * @param int $showIndex 是否推送首页
     * @param bool $withCache 是否使用缓存
     * @return array|null 返回最新的列表数据集
     */
    public function GetNewList($siteId, $channelId, $topCount, $state, $orderBy = 0, $showIndex=0, $withCache = FALSE) {

        $result = null;

        if(!empty($topCount)){

            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'document_news_data';
            $cacheFile = "document_news_get_new_list_". $channelId
                . "_top".$topCount
                . "_state".$state
                . "_show_index".$showIndex
                . "_order".$orderBy;


            $dataProperty = new DataProperty();

            $orderBySql = 'ORDER BY dn.ShowIndex DESC, dn.Sort DESC, dn.CreateDate DESC';

            switch($orderBy){

                case 0:
                    $orderBySql = 'ORDER BY dn.ShowIndex DESC,  dn.Sort DESC,dn.CreateDate DESC';
                    break;

            }

            $searchShowIndex="";
            if($showIndex>0){
                $searchShowIndex=' AND ShowIndex>=1 ';
            }

            if($channelId>0){
                $searchShowIndex .= " AND dn.ChannelId=:ChannelId ";
                $dataProperty->AddField("ChannelId", $channelId);
            }

            $selectColumn = '
            dn.DocumentNewsId,
            dn.SiteId,
            dn.ChannelId,
            dn.DocumentNewsTitle,
            dn.DocumentNewsSubTitle,
            dn.DocumentNewsCiteTitle,
            dn.DocumentNewsShortTitle,
            dn.DocumentNewsIntro,
            dn.CreateDate,
            dn.ManageUserId,
            dn.ManageUserName,
            dn.UserId,
            dn.UserName,
            dn.Author,
            dn.State,
            dn.DocumentNewsType,
            dn.DirectUrl,
            dn.ShowDate,
            dn.SourceName,
            dn.DocumentNewsMainTag,
            dn.DocumentNewsTag,
            dn.Sort,
            dn.TitlePic1UploadFileId,
            dn.TitlePic2UploadFileId,
            dn.TitlePic3UploadFileId,
            dn.DocumentNewsTitleColor,
            dn.DocumentNewsTitleBold,
            dn.OpenComment,
            dn.ShowHour,
            dn.ShowMinute,
            dn.ShowSecond,
            dn.IsHot,
            dn.RecLevel,
            dn.ShowPicMethod,
            dn.ClosePosition,
            dn.Hit,
            dn.PublishDate,
            (dn.Hit+dn.VirtualHit) AS AllHit,

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
            uf3.UploadFileCutPath1 AS TitlePic3UploadFileCutPath1,

            c.ChannelName

            ';

            $sql = "SELECT $selectColumn FROM " . self::TableName_DocumentNews . "  dn
                    LEFT OUTER JOIN " .self::TableName_Channel." c on dn.ChannelId = c.ChannelId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf1 on dn.TitlePic1UploadFileId=uf1.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf2 on dn.TitlePic2UploadFileId=uf2.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf3 on dn.TitlePic3UploadFileId=uf3.UploadFileId

                WHERE c.SiteId = :SiteId AND dn.State=:State $searchShowIndex
                $orderBySql LIMIT " . $topCount;

            $dataProperty->AddField("SiteId", $siteId);
            $dataProperty->AddField("State", $state);


            $result = parent::GetInfoOfArrayList($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);


            //$result = $this->dbOperator->GetArrayList($sql, $dataProperty);

        }

        return $result;
    }


    /**
     * 取得子节点资讯列表
     * @param int $strChildrenChannelId 子频道id
     * @param int $channelId 频道id
     * @param string $topCount 显示条数  1或 1,10
     * @param int $state 状态
     * @param int $orderBy 排序方式
     * @param int $showIndex 是否推送首页
     * @param bool $withCache 是否使用缓存
     * @return array|null 返回资讯列表
     */
    public function GetListOfChild($strChildrenChannelId, $channelId,$topCount, $state, $orderBy = 0, $showIndex=0, $withCache = FALSE) {
        $result = null;

        if($strChildrenChannelId>0 && !empty($topCount)){


            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'document_news_data';
            $cacheFile = "document_news_get_list_of_child_". $channelId
                . "_top".$topCount
                . "_state".$state
                . "_show_index".$showIndex
                . "_order".$orderBy;


            $orderBySql = 'ORDER BY  dn.ShowIndex DESC, dn.Sort DESC, dn.CreateDate DESC';

            switch($orderBy){

                case 0:
                    $orderBySql = 'ORDER BY  dn.ShowIndex DESC, dn.Sort DESC,dn.CreateDate DESC';
                    break;

            }

            $searchShowIndex="";
            if($showIndex>0){
                $searchShowIndex=' AND ShowIndex>=1 ';
            }

            $selectColumn = '
            dn.DocumentNewsId,
            dn.SiteId,
            dn.ChannelId,
            dn.DocumentNewsTitle,
            dn.DocumentNewsSubTitle,
            dn.DocumentNewsCiteTitle,
            dn.DocumentNewsShortTitle,
            dn.DocumentNewsIntro,
            dn.CreateDate,
            dn.ManageUserId,
            dn.ManageUserName,
            dn.UserId,
            dn.UserName,
            dn.Author,
            dn.State,
            dn.DocumentNewsType,
            dn.DirectUrl,
            dn.ShowDate,
            dn.SourceName,
            dn.DocumentNewsMainTag,
            dn.DocumentNewsTag,
            dn.Sort,
            dn.TitlePic1UploadFileId,
            dn.TitlePic2UploadFileId,
            dn.TitlePic3UploadFileId,
            dn.DocumentNewsTitleColor,
            dn.DocumentNewsTitleBold,
            dn.OpenComment,
            dn.ShowHour,
            dn.ShowMinute,
            dn.ShowSecond,
            dn.IsHot,
            dn.RecLevel,
            dn.ShowPicMethod,
            dn.ClosePosition,
            dn.Hit,
            dn.PublishDate,
            (dn.Hit+dn.VirtualHit) AS AllHit,

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
            uf3.UploadFileCutPath1 AS TitlePic3UploadFileCutPath1,

            c.ChannelName
            ';

            $sql = "SELECT $selectColumn FROM " . self::TableName_DocumentNews . " dn
                        LEFT OUTER JOIN ".self::TableName_Channel." c on dn.ChannelId = c.ChannelId
                        LEFT OUTER JOIN " .self::TableName_UploadFile." uf1 on dn.TitlePic1UploadFileId=uf1.UploadFileId
                        LEFT OUTER JOIN " .self::TableName_UploadFile." uf2 on dn.TitlePic2UploadFileId=uf2.UploadFileId
                        LEFT OUTER JOIN " .self::TableName_UploadFile." uf3 on dn.TitlePic3UploadFileId=uf3.UploadFileId
                    WHERE
                        dn.ChannelId IN ($strChildrenChannelId)

                        AND dn.State=:State
                        $searchShowIndex
                    $orderBySql LIMIT " . $topCount . ";";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("State", $state);

            $result = parent::GetInfoOfArrayList($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);


            //$result = $this->dbOperator->GetArrayList($sql, $dataProperty);

        }

        return $result;
    }


    /**
     * 子节点按推荐级别的列表数据集
     * @param int $strChildrenChannelId 频道id
     * @param int $channelId 频道id
     * @param string $topCount 分页参数，如 9 或 3,9(第4至10条)
     * @param int $state 状态
     * @param string $recLevel 推荐级别
     * @param int $orderBy 排序方式
     * @param bool $withCache 是否使用缓存
     * @return array|null 返回子节点的列表数据集
     */
    public function GetListOfRecLevelChild($strChildrenChannelId, $channelId, $topCount, $state, $recLevel="", $orderBy, $withCache = FALSE) {

        $result = null;

        if(!empty($topCount)){

            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'document_news_data';
            $cacheFile = "document_news_get_list_of_rec_level_child_". $channelId
                . "_top".$topCount
                . "_state".$state
                . "_rec_level".$recLevel
                . "_order".$orderBy;

            $orderBySql = 'ORDER BY dn.ShowDate DESC, dn.RecLevel DESC, dn.Sort DESC, dn.CreateDate DESC';

            switch($orderBy){

                case 0:
                    $orderBySql = 'ORDER BY dn.ShowDate DESC, dn.RecLevel DESC, dn.Sort DESC, dn.CreateDate DESC';
                    break;

            }

            $recLevelSelection=" AND dn.RecLevel Between 1 AND 10 ";
            if($recLevel!=""){
                $recLevelSelection=" AND dn.RecLevel Between ".$recLevel." ";
            }
            $selectColumn = '
            dn.DocumentNewsId,
            dn.SiteId,
            dn.ChannelId,
            dn.DocumentNewsTitle,
            dn.DocumentNewsSubTitle,
            dn.DocumentNewsCiteTitle,
            dn.DocumentNewsShortTitle,
            dn.DocumentNewsIntro,
            dn.CreateDate,
            dn.ManageUserId,
            dn.ManageUserName,
            dn.UserId,
            dn.UserName,
            dn.Author,
            dn.State,
            dn.DocumentNewsType,
            dn.DirectUrl,
            dn.ShowDate,
            dn.SourceName,
            dn.DocumentNewsMainTag,
            dn.DocumentNewsTag,
            dn.Sort,
            dn.TitlePic1UploadFileId,
            dn.TitlePic2UploadFileId,
            dn.TitlePic3UploadFileId,
            dn.DocumentNewsTitleColor,
            dn.DocumentNewsTitleBold,
            dn.OpenComment,
            dn.ShowHour,
            dn.ShowMinute,
            dn.ShowSecond,
            dn.IsHot,
            dn.RecLevel,
            dn.ShowPicMethod,
            dn.ClosePosition,
            dn.Hit,
            dn.PublishDate,
            (dn.Hit+dn.VirtualHit) AS AllHit,

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
            uf3.UploadFileCutPath1 AS TitlePic3UploadFileCutPath1,

            c.ChannelName

            ';

            $sql = "SELECT $selectColumn FROM " . self::TableName_DocumentNews . " dn

                    LEFT OUTER JOIN " .self::TableName_Channel." c on dn.ChannelId = c.ChannelId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf1 on dn.TitlePic1UploadFileId=uf1.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf2 on dn.TitlePic2UploadFileId=uf2.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf3 on dn.TitlePic3UploadFileId=uf3.UploadFileId

                WHERE
                    dn.ChannelId IN ($strChildrenChannelId)
                    AND dn.State=:State
                " . $recLevelSelection . "

                $orderBySql LIMIT " . $topCount;

            $dataProperty = new DataProperty();
            $dataProperty->AddField("State", $state);

            $result = parent::GetInfoOfArrayList($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);

            //$result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }

        return $result;
    }


    /**
     * 根据频道id取当前频道下（包括所有级别子节点）指定推荐级别文档的列表数据集
     * @param int $siteId 站点id
     * @param int $recLevel 推荐级别
     * @param string $topCount 分页参数，如 9 或 3,9(第4至10条)
     * @param int $orderBy 排序
     * @param bool $withCache 是否使用缓存
     * @return array|null 返回最新列表数据集
     */
    public function GetListOfRecLevelBelongSite($siteId, $recLevel ,$topCount, $orderBy = 0, $withCache = FALSE) {

        $result = null;

        if($siteId>0&&!empty($topCount)){

            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'document_news_data';
            $cacheFile = "document_news_get_list_of_rec_level_belong_site_". $siteId
                . "_top".$topCount
                . "_rec_level".$recLevel
                . "_order".$orderBy;

            $orderBySql = 'ORDER BY dn.CreateDate DESC';
            switch($orderBy){
                case 0:
                    $orderBySql = 'ORDER BY dn.CreateDate DESC';
                    break;
            }

            $selectColumn = '
            dn.DocumentNewsId,
            dn.SiteId,
            dn.ChannelId,
            dn.DocumentNewsTitle,
            dn.DocumentNewsSubTitle,
            dn.DocumentNewsCiteTitle,
            dn.DocumentNewsShortTitle,
            dn.DocumentNewsIntro,
            dn.CreateDate,
            dn.ManageUserId,
            dn.ManageUserName,
            dn.UserId,
            dn.UserName,
            dn.Author,
            dn.State,
            dn.DocumentNewsType,
            dn.DirectUrl,
            dn.ShowDate,
            dn.SourceName,
            dn.DocumentNewsMainTag,
            dn.DocumentNewsTag,
            dn.Sort,
            dn.TitlePic1UploadFileId,
            dn.TitlePic2UploadFileId,
            dn.TitlePic3UploadFileId,
            dn.DocumentNewsTitleColor,
            dn.DocumentNewsTitleBold,
            dn.OpenComment,
            dn.ShowHour,
            dn.ShowMinute,
            dn.ShowSecond,
            dn.IsHot,
            dn.RecLevel,
            dn.ShowPicMethod,
            dn.ClosePosition,
            dn.Hit,
            dn.PublishDate,
            (dn.Hit+dn.VirtualHit) AS AllHit,

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
            uf3.UploadFileCutPath1 AS TitlePic3UploadFileCutPath1,

            c.ChannelName

            ';

            $sql = "SELECT $selectColumn FROM " . self::TableName_DocumentNews . "  dn
                    LEFT OUTER JOIN " .self::TableName_Channel." c on dn.ChannelId = c.ChannelId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf1 on dn.TitlePic1UploadFileId=uf1.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf2 on dn.TitlePic2UploadFileId=uf2.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf3 on dn.TitlePic3UploadFileId=uf3.UploadFileId

                WHERE dn.SiteId=:SiteId AND dn.RecLevel=:RecLevel
                AND dn.State=:State
                $orderBySql LIMIT " . $topCount;
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);
            $dataProperty->AddField("RecLevel", $recLevel);
            $dataProperty->AddField("State", DocumentNewsData::STATE_PUBLISHED);

            $result = parent::GetInfoOfArrayList($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);


            //$result = $this->dbOperator->GetArrayList($sql, $dataProperty);

        }

        return $result;
    }

    /**
     * 根据频道id取当前站点下从显示日期向前指定天数文档的列表数据集
     * @param int $siteId 站点id
     * @param int $dayCount 天数
     * @param string $topCount 分页参数，如 9 或 3,9(第4至10条)
     * @param int $orderBy 排序
     * @param bool $withCache 是否使用缓存
     * @return array|null 返回最新列表数据集
     */
    public function GetListOfDayBelongSite($siteId, $dayCount ,$topCount, $orderBy = 0, $withCache = FALSE) {

        $result = null;

        if($siteId>0&&!empty($topCount)){

            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'document_news_data';
            $cacheFile = "document_news_get_list_of_day_belong_site_". $siteId
                . "_top".$topCount
                . "_day_count".$dayCount
                . "_order".$orderBy;

            $orderBySql = 'ORDER BY dn.Sort DESC, dn.CreateDate DESC';
            switch($orderBy){
                case 0:
                    $orderBySql = 'ORDER BY dn.Sort DESC,dn.CreateDate DESC';
                    break;
                case 1:
                    $orderBySql = 'ORDER BY dn.Hit DESC,dn.CreateDate DESC';
                    break;
            }

            $selectColumn = '
            dn.DocumentNewsId,
            dn.SiteId,
            dn.ChannelId,
            dn.DocumentNewsTitle,
            dn.DocumentNewsSubTitle,
            dn.DocumentNewsCiteTitle,
            dn.DocumentNewsShortTitle,
            dn.DocumentNewsIntro,
            dn.CreateDate,
            dn.ManageUserId,
            dn.ManageUserName,
            dn.UserId,
            dn.UserName,
            dn.Author,
            dn.State,
            dn.DocumentNewsType,
            dn.DirectUrl,
            dn.ShowDate,
            dn.SourceName,
            dn.DocumentNewsMainTag,
            dn.DocumentNewsTag,
            dn.Sort,
            dn.TitlePic1UploadFileId,
            dn.TitlePic2UploadFileId,
            dn.TitlePic3UploadFileId,
            dn.DocumentNewsTitleColor,
            dn.DocumentNewsTitleBold,
            dn.OpenComment,
            dn.ShowHour,
            dn.ShowMinute,
            dn.ShowSecond,
            dn.IsHot,
            dn.RecLevel,
            dn.ShowPicMethod,
            dn.ClosePosition,
            dn.Hit,
            dn.PublishDate,
            (dn.Hit+dn.VirtualHit) AS AllHit,

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
            uf3.UploadFileCutPath1 AS TitlePic3UploadFileCutPath1,

            c.ChannelName

            ';

            $sql = "SELECT $selectColumn FROM " . self::TableName_DocumentNews . "  dn
                    LEFT OUTER JOIN " .self::TableName_Channel." c on dn.ChannelId = c.ChannelId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf1 on dn.TitlePic1UploadFileId=uf1.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf2 on dn.TitlePic2UploadFileId=uf2.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf3 on dn.TitlePic3UploadFileId=uf3.UploadFileId

                WHERE
                dn.SiteId=:SiteId
                AND DATEDIFF(NOW(),ShowDate)<:DayCount AND DATEDIFF(NOW(),ShowDate)>0
                AND dn.State=:State
                $orderBySql LIMIT " . $topCount;

            //dn.ChannelId IN (".$channelId.")
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);
            $dataProperty->AddField("DayCount", $dayCount);
            $dataProperty->AddField("State", DocumentNewsData::STATE_PUBLISHED);

            $result = parent::GetInfoOfArrayList($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);

            //$result = $this->dbOperator->GetArrayList($sql, $dataProperty);

        }

        return $result;
    }


    /**
     * 取得分页显示的资讯列表
     * @param int $channelId 频道id
     * @param int $pageBegin 记录开始位置
     * @param int $pageSize 显示数量
     * @param int $allCount 总数量
     * @param int $state 状态
     * @param string $searchKey 查询关键字
     * @param int $parentId 父频道id
     * @param bool $withCache 是否使用缓存
     * @return array 分页显示的资讯列表
     */
    public function GetListForPager(
        $channelId,
        $pageBegin,
        $pageSize,
        &$allCount,
        $state,
        $searchKey = "",
        $parentId = 0,
        $withCache = FALSE
    ) {
        $searchSql = "";
        $dataProperty = new DataProperty();


        $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'document_news_data';
        $cacheFile = "document_news_get_list_of_day_belong_channel_". $channelId
            . "_p".$pageBegin
            . "_ps".$pageSize
            . "_state".$state
            . "_parentId".$parentId;

        if ($parentId > 0) {
            $searchSql .= " AND dn.ChannelId IN (SELECT ChannelId
                                                FROM ".self::TableName_Channel." WHERE ParentId=:ParentId) ";
            $dataProperty->AddField("ParentId", $parentId);
        } else {
            if ($channelId > 0) {
                $searchSql .= " AND dn.ChannelId=:ChannelId ";
                $dataProperty->AddField("ChannelId", $channelId);
            }
        }
        if (strlen($searchKey) > 0 && $searchKey != "undefined") {

            $withCache = FALSE;

            $searchSql .= " AND (
                    DocumentNewsTitle LIKE :SearchKey1
                OR ManageUserName LIKE :SearchKey2
                OR UserName LIKE :SearchKey3
                OR Author LIKE :SearchKey4
                OR DocumentNewsSubTitle LIKE :SearchKey5
                OR DocumentNewsCiteTitle LIKE :SearchKey6
                OR DocumentNewsShortTitle LIKE :SearchKey7

            )";
            $dataProperty->AddField("SearchKey1", "%" . $searchKey . "%");
            $dataProperty->AddField("SearchKey2", "%" . $searchKey . "%");
            $dataProperty->AddField("SearchKey3", "%" . $searchKey . "%");
            $dataProperty->AddField("SearchKey4", "%" . $searchKey . "%");
            $dataProperty->AddField("SearchKey5", "%" . $searchKey . "%");
            $dataProperty->AddField("SearchKey6", "%" . $searchKey . "%");
            $dataProperty->AddField("SearchKey7", "%" . $searchKey . "%");
        }

        $selectColumn = '
            dn.DocumentNewsId,
            dn.SiteId,
            dn.ChannelId,
            dn.DocumentNewsTitle,
            dn.DocumentNewsSubTitle,
            dn.DocumentNewsCiteTitle,
            dn.DocumentNewsShortTitle,
            dn.DocumentNewsIntro,
            dn.CreateDate,
            dn.ManageUserId,
            dn.ManageUserName,
            dn.UserId,
            dn.UserName,
            dn.Author,
            dn.State,
            dn.DocumentNewsType,
            dn.DirectUrl,
            dn.ShowDate,
            dn.SourceName,
            dn.DocumentNewsMainTag,
            dn.DocumentNewsTag,
            dn.Sort,
            dn.TitlePic1UploadFileId,
            dn.TitlePic2UploadFileId,
            dn.TitlePic3UploadFileId,
            dn.DocumentNewsTitleColor,
            dn.DocumentNewsTitleBold,
            dn.OpenComment,
            dn.ShowHour,
            dn.ShowMinute,
            dn.ShowSecond,
            dn.IsHot,
            dn.RecLevel,
            dn.ShowPicMethod,
            dn.ClosePosition,
            dn.Hit,
            dn.PublishDate,

            dn.DocumentNewsContent,
            (dn.Hit+dn.VirtualHit) AS AllHit,

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
            uf3.UploadFileCutPath1 AS TitlePic3UploadFileCutPath1,

            c.ChannelName
            ';

        $sql = "
            SELECT
            $selectColumn
            FROM
            " . self::TableName_DocumentNews . " dn
                    LEFT OUTER JOIN ".self::TableName_Channel." c on dn.ChannelId = c.ChannelId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf1 on dn.TitlePic1UploadFileId=uf1.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf2 on dn.TitlePic2UploadFileId=uf2.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf3 on dn.TitlePic3UploadFileId=uf3.UploadFileId

            WHERE dn.State=:State " . $searchSql . "
            ORDER BY dn.Sort DESC, dn.PublishDate DESC
            LIMIT " . $pageBegin . "," . $pageSize . "";


        $dataProperty->AddField("State", $state);

        $result = parent::GetInfoOfArrayList($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);


        //$result = $this->dbOperator->GetArrayList($sql, $dataProperty);


        $sql = "SELECT count(*) FROM " . self::TableName_DocumentNews . " WHERE State=:State " . $searchSql;
        $allCount = $this->dbOperator->GetInt($sql, $dataProperty);

        return $result;
    }

    /**
     * 取得分页显示的资讯列表(供外部接口)
     * @param int $channelId 频道id
     * @param int $pageBegin 记录开始位置
     * @param int $pageSize 显示数量
     * @param int $allCount 总数量
     * @param int $state 状态
     * @param string $searchKey 查询关键字
     * @param int $parentId 父频道id
     * @param bool $withCache 是否使用缓存
     * @return array 分页显示的资讯列表
     */
    public function GetListForInterface(
        $channelId,
        $pageBegin,
        $pageSize,
        &$allCount,
        $state,
        $searchKey = "",
        $parentId = 0,
        $withCache = FALSE
    ) {
        $searchSql = "";
        $dataProperty = new DataProperty();
        if ($parentId > 0) {
            $searchSql .= " AND dn.ChannelId IN (SELECT ChannelId
                                                FROM ".self::TableName_Channel." WHERE ParentId=:ParentId) ";
            $dataProperty->AddField("ParentId", $parentId);
        } else {
            if ($channelId > 0) {
                $searchSql .= " AND dn.ChannelId=:ChannelId ";
                $dataProperty->AddField("ChannelId", $channelId);
            }
        }
        if (strlen($searchKey) > 0 && $searchKey != "undefined") {
            $searchSql .= " AND (
                    DocumentNewsTitle LIKE :SearchKey1
                OR ManageUserName LIKE :SearchKey2
                OR UserName LIKE :SearchKey3
                OR Author LIKE :SearchKey4
                OR DocumentNewsSubTitle LIKE :SearchKey5
                OR DocumentNewsCiteTitle LIKE :SearchKey6
                OR DocumentNewsShortTitle LIKE :SearchKey7

            )";
            $dataProperty->AddField("SearchKey1", "%" . $searchKey . "%");
            $dataProperty->AddField("SearchKey2", "%" . $searchKey . "%");
            $dataProperty->AddField("SearchKey3", "%" . $searchKey . "%");
            $dataProperty->AddField("SearchKey4", "%" . $searchKey . "%");
            $dataProperty->AddField("SearchKey5", "%" . $searchKey . "%");
            $dataProperty->AddField("SearchKey6", "%" . $searchKey . "%");
            $dataProperty->AddField("SearchKey7", "%" . $searchKey . "%");
        }

        $selectColumn = '
            dn.DocumentNewsId,
            dn.SiteId,
            dn.ChannelId,
            dn.DocumentNewsTitle,
            dn.DocumentNewsSubTitle,
            dn.DocumentNewsCiteTitle,
            dn.DocumentNewsShortTitle,
            dn.DocumentNewsIntro,
            dn.CreateDate,
            dn.ManageUserId,
            dn.ManageUserName,
            dn.UserId,
            dn.UserName,
            dn.Author,
            dn.State,
            dn.DocumentNewsType,
            dn.DirectUrl,
            dn.ShowDate,
            dn.SourceName,
            dn.DocumentNewsMainTag,
            dn.DocumentNewsTag,
            dn.Sort,
            dn.TitlePic1UploadFileId,
            dn.TitlePic2UploadFileId,
            dn.TitlePic3UploadFileId,
            dn.DocumentNewsTitleColor,
            dn.DocumentNewsTitleBold,
            dn.OpenComment,
            dn.ShowHour,
            dn.ShowMinute,
            dn.ShowSecond,
            dn.IsHot,
            dn.RecLevel,
            dn.ShowPicMethod,
            dn.ClosePosition,
            dn.Hit,
            dn.PublishDate,

            dn.DocumentNewsContent,
            (dn.Hit+dn.VirtualHit) AS AllHit,

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
            uf3.UploadFileCutPath1 AS TitlePic3UploadFileCutPath1,

            c.ChannelName,
            s.SiteUrl
            ';

        $sql = "
            SELECT
            $selectColumn
            FROM
            " . self::TableName_DocumentNews . " dn
                    LEFT OUTER JOIN " .self::TableName_Site." s on dn.SiteId=s.SiteId
                    LEFT OUTER JOIN " .self::TableName_Channel." c on dn.ChannelId = c.ChannelId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf1 on dn.TitlePic1UploadFileId=uf1.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf2 on dn.TitlePic2UploadFileId=uf2.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf3 on dn.TitlePic3UploadFileId=uf3.UploadFileId

            WHERE dn.State=:State " . $searchSql . "
            ORDER BY dn.Sort DESC, dn.PublishDate DESC
            LIMIT " . $pageBegin . "," . $pageSize . "";


        $dataProperty->AddField("State", $state);
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);


        $sql = "SELECT count(*) FROM " . self::TableName_DocumentNews . " WHERE State=:State " . $searchSql;
        $allCount = $this->dbOperator->GetInt($sql, $dataProperty);

        return $result;
    }

    public function GetOpenComment($documentNewsId,$withCache){
        $result = -1;
        if($documentNewsId > 0){
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'document_news_data';
            $cacheFile = 'channel_get_children_channel_id.cache_' . $documentNewsId . '';
            $sql = "SELECT OpenComment FROM ".self::TableName_DocumentNews." WHERE DocumentNewsId = :DocumentNewsId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("DocumentNewsId",$documentNewsId);
            $result = $this->GetInfoOfIntValue($sql,$dataProperty,$withCache,$cacheDir,$cacheFile);
        }
        return $result;
    }

    /**
     * 取得节点内已发的条数（动态列表页pager_button用）
     * @param int $channelId id
     * @return int 条数
     */
    public function GetCountInChannel($channelId){
        $result = -1;
        if($channelId > 0){
            $sql = "SELECT COUNT(*) FROM ".self::TableName_DocumentNews." WHERE ChannelId=:ChannelId AND State=30;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId",$channelId);
            $result = $this->dbOperator->GetInt($sql,$dataProperty);
        }
        return $result;
    }

    public function GetChannelId($documentNewsId,$withCache){
        $result = -1;
        if($documentNewsId > 0){
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'document_news_data';
            $cacheFile = 'channel_get_children_channel_id.cache_' . $documentNewsId . '';
            $sql = "SELECT ChannelId FROM ".self::TableName_DocumentNews." WHERE DocumentNewsId = :DocumentNewsId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("DocumentNewsId",$documentNewsId);
            $result = $this->GetInfoOfIntValue($sql,$dataProperty,$withCache,$cacheDir,$cacheFile);
        }
        return $result;
    }


    /**
     * 增加一个点击
     * @param int $documentNewsId id
     * @return int 操作结果
     */
    public function AddHit($documentNewsId){
        $result = -1;
        if($documentNewsId > 0){
            $sql = "UPDATE ".self::TableName_DocumentNews." SET Hit = Hit+1 WHERE DocumentNewsId=:DocumentNewsId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("DocumentNewsId",$documentNewsId);
            $result = $this->dbOperator->Execute($sql,$dataProperty);
        }
        return $result;
    }



    /**
     * 获取点击
     * @param int $documentNewsId id
     * @return int 操作结果
     */
    public function GetHit($documentNewsId){
        $result = null;
        if($documentNewsId > 0){
            $sql = "SELECT Hit,VirtualHit FROM ".self::TableName_DocumentNews." WHERE DocumentNewsId=:DocumentNewsId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("DocumentNewsId",$documentNewsId);
            $result = $this->dbOperator->GetArray($sql,$dataProperty);
        }
        return $result;
    }




    /**
     * 增加一个AgreeCount
     * @param int $documentNewsId
     * @return int 操作结果
     */
    public function AddAgreeCount($documentNewsId){
        $result = -1;
        if($documentNewsId > 0){
            $sql = "UPDATE ".self::TableName_DocumentNews." SET AgreeCount = AgreeCount+1 WHERE DocumentNewsId=:DocumentNewsId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("DocumentNewsId",$documentNewsId);
            $result = $this->dbOperator->Execute($sql,$dataProperty);
        }
        return $result;
    }

    /**
     * 获取AgreeCount
     * @param int $documentNewsId
     * @return int 操作结果
     */
    public function GetAgreeCount($documentNewsId){
        $result = null;
        if($documentNewsId > 0){
            $sql = "SELECT AgreeCount FROM ".self::TableName_DocumentNews." WHERE DocumentNewsId=:DocumentNewsId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("DocumentNewsId",$documentNewsId);
            $result = $this->dbOperator->GetInt($sql,$dataProperty);
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
}

?>
