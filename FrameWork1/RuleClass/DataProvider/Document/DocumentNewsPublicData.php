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
     * @return array|null 返回资讯列表
     */
    public function GetList($channelId, $topCount, $state, $orderBy = 0) {

        $result = null;

        if($channelId>0 && !empty($topCount)){

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
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

        }




        return $result;
    }

    /**
     * 最新的列表数据集
     * @param int $channelId 频道id
     * @param string $topCount 分页参数，如 9 或 3,9(第4至10条)
     * @param int $state 状态
     * @param int $orderBy 排序
     * @return array|null 返回最新的列表数据集
     */
    public function GetNewList($channelId, $topCount, $state, $orderBy = 0) {

        $result = null;

        if(!empty($topCount)){
            $orderBySql = 'ORDER BY dn.Sort DESC, dn.CreateDate DESC';

            switch($orderBy){

                case 0:
                    $orderBySql = 'dn.Sort DESC,dn.CreateDate DESC';
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

                WHERE dn.ChannelId=:ChannelId AND dn.State=:State
                $orderBySql LIMIT " . $topCount;
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);
            $dataProperty->AddField("State", $state);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

        }

        return $result;
    }

    /**
     * 取得子节点资讯列表
     * @param int $channelId 频道id
     * @param string $topCount 显示条数  1或 1,10
     * @param int $state 状态
     * @param int $orderBy 排序方式
     * @return array|null 返回资讯列表
     */
    public function GetListOfChild($channelId, $topCount, $state, $orderBy = 0) {

        $result = null;

        if($channelId>0 && !empty($topCount)){

            $orderBySql = 'ORDER BY dn.Sort DESC, dn.CreateDate DESC';

            switch($orderBy){

                case 0:
                    $orderBySql = 'ORDER BY dn.Sort DESC,dn.CreateDate DESC';
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
                        LEFT OUTER JOIN ".self::TableName_Channel." c on dn.ChannelId = c.ChannelId
                        LEFT OUTER JOIN " .self::TableName_UploadFile." uf1 on dn.TitlePic1UploadFileId=uf1.UploadFileId
                        LEFT OUTER JOIN " .self::TableName_UploadFile." uf2 on dn.TitlePic2UploadFileId=uf2.UploadFileId
                        LEFT OUTER JOIN " .self::TableName_UploadFile." uf3 on dn.TitlePic3UploadFileId=uf3.UploadFileId
                    WHERE
                        dn.ChannelId IN (SELECT ChannelId FROM ".self::TableName_Channel." WHERE ParentId=:ParentId)

                        AND dn.State=:State
                    $orderBySql LIMIT " . $topCount . ";";
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

            $orderBySql = 'ORDER BY dn.Sort DESC, dn.CreateDate DESC';

            switch($orderBy){

                case 0:
                    $orderBySql = 'ORDER BY dn.Sort DESC,dn.CreateDate DESC';
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

                    $orderBySql LIMIT " . $topCount;

            $dataProperty = new DataProperty();
            $dataProperty->AddField("ParentId", $channelId);
            $dataProperty->AddField("State", $state);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }

        return $result;
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
                    dn.ChannelId IN (SELECT ChannelId FROM ".self::TableName_Channel." WHERE ParentId=:ParentId)
                    AND dn.State=:State
                " . $recLevelSelection . "

                $orderBySql LIMIT " . $topCount;

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
     * 根据频道id取当前频道下（包括所有级别子节点）指定推荐级别文档的列表数据集
     * @param int $channelId 频道id
     * @param int $recLevel 推荐级别
     * @param string $topCount 分页参数，如 9 或 3,9(第4至10条)
     * @param int $orderBy 排序
     * @return array|null 返回最新列表数据集
     */
    public function GetListOfRecLevelBelongChannel($channelId, $recLevel ,$topCount, $orderBy = 0) {

        $result = null;

        if($channelId>0&&!empty($topCount)){

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

                WHERE dn.ChannelId IN (".$channelId.")  AND dn.RecLevel=:RecLevel
                AND dn.State=:State
                $orderBySql LIMIT " . $topCount;
            $dataProperty = new DataProperty();
            $dataProperty->AddField("RecLevel", $recLevel);
            $dataProperty->AddField("State", DocumentNewsData::STATE_PUBLISHED);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

        }

        return $result;
    }

    /**
     * 根据频道id取当前频道下（包括所有级别子节点）从显示日期向前指定天数文档的列表数据集
     * @param int $channelId 频道id
     * @param int $dayCount 天数
     * @param string $topCount 分页参数，如 9 或 3,9(第4至10条)
     * @param int $orderBy 排序
     * @return array|null 返回最新列表数据集
     */
    public function GetListOfDayBelongChannel($channelId, $dayCount ,$topCount, $orderBy = 0) {

        $result = null;

        if($channelId>0&&!empty($topCount)){

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

                WHERE dn.ChannelId IN (".$channelId.")
                AND DATEDIFF(NOW(),ShowDate)<:DayCount AND DATEDIFF(NOW(),ShowDate)>0
                AND dn.State=:State
                $orderBySql LIMIT " . $topCount;
            $dataProperty = new DataProperty();
            $dataProperty->AddField("DayCount", $dayCount);
            $dataProperty->AddField("State", DocumentNewsData::STATE_PUBLISHED);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

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
     * @return array 分页显示的资讯列表
     */
    public function GetListForPager(
        $channelId,
        $pageBegin,
        $pageSize,
        &$allCount,
        $state,
        $searchKey = "",
        $parentId = 0
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
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);


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
     * @return array 分页显示的资讯列表
     */
    public function GetListForInterface(
        $channelId,
        $pageBegin,
        $pageSize,
        &$allCount,
        $state,
        $searchKey = "",
        $parentId = 0
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
