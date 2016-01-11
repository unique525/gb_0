<?php

/**
 * 前台 频道 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Channel
 * @author zhangchi
 */
class ChannelPublicData extends BasePublicData {

    /**
     * 返回一行数据
     * @param int $channelId 频道id
     * @param bool $withCache
     * @return array|null 取得对应数组

    public function GetOne($channelId, $withCache = true){
        $result = null;
        if($channelId>0){

            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'channel_data';
            $cacheFile = 'channel_get_one.cache_' . $channelId;

            $sql = "SELECT * FROM " . self::TableName_Channel . " WHERE ChannelId=:ChannelId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);
            $result = $this->GetInfoOfArray($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);

        }
        return $result;
    }*/


    /**
     * 返回一行数据
     * @param int $channelId 频道id
     * @param bool $withCache
     * @return array|null 取得对应数组
     */
    public function GetOne($channelId, $withCache = true){
        $result = null;
        if($channelId>0){

            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'channel_data';
            $cacheFile = 'channel_get_one.cache_' . $channelId;

            $sql = "SELECT c.*,

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
             FROM " . self::TableName_Channel . " c
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf1 on c.TitlePic1UploadFileId=uf1.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf2 on c.TitlePic2UploadFileId=uf2.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf3 on c.TitlePic3UploadFileId=uf3.UploadFileId
             WHERE c.ChannelId=:ChannelId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);
            $result = $this->GetInfoOfArray($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);

        }
        return $result;
    }

    /**
     * 取得频道名称
     * @param int $channelId 频道id
     * @param bool $withCache 是否从缓冲中取
     * @return string 频道名称
     */
    public function GetChannelName($channelId, $withCache)
    {
        $result = "";
        if ($channelId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'channel_data';
            $cacheFile = 'channel_get_channel_name.cache_' . $channelId . '';
            $sql = "SELECT ChannelName FROM " . self::TableName_Channel . " WHERE ChannelId=:ChannelId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }

        return $result;
    }

    /**
     * 取得访问方式
     * @param int $channelId 频道id
     * @param bool $withCache 是否从缓冲中取
     * @return int 访问方式
     */
    public function GetAccessLimitType($channelId, $withCache)
    {
        $result = 0;
        if ($channelId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'channel_data';
            $cacheFile = 'channel_get_access_limit_type.cache_' . $channelId . '';
            $sql = "SELECT AccessLimitType FROM " . self::TableName_Channel . " WHERE ChannelId=:ChannelId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }

        return $result;
    }


    /**
     * 取得访问限制的内容
     * @param int $channelId 频道id
     * @param bool $withCache 是否从缓冲中取
     * @return string 访问限制的内容
     */
    public function GetAccessLimitContent($channelId, $withCache)
    {
        $result = "";
        if ($channelId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'channel_data';
            $cacheFile = 'channel_get_access_limit_content.cache_' . $channelId . '';
            $sql = "SELECT AccessLimitContent FROM " . self::TableName_Channel . " WHERE ChannelId=:ChannelId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }

        return $result;
    }

    /**
     * 取得子节点id字符串
     * @param int $channelId 频道id
     * @param bool $withCache 是否从缓冲中取
     * @return string 子节点id字符串
     */
    public function GetChildrenChannelId($channelId, $withCache)
    {
        $result = "";
        if ($channelId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'channel_data';
            $cacheFile = 'channel_get_children_channel_id.cache_' . $channelId . '';
            $sql = "SELECT ChildrenChannelId FROM " . self::TableName_Channel . " WHERE ChannelId=:ChannelId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }

        return $result;
    }

    /**
     * 取得站点id
     * @param int $channelId 频道id
     * @param bool $withCache 是否从缓冲中取
     * @return int 站点id
     */
    public function GetSiteId($channelId, $withCache)
    {
        $result = -1;
        if ($channelId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'channel_data';
            $cacheFile = 'channel_get_site_id.cache_' . $channelId . '';
            $sql = "SELECT SiteId FROM " . self::TableName_Channel . " WHERE ChannelId = :ChannelId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 取得上级频道id
     * @param int $channelId 频道id
     * @param bool $withCache 是否从缓冲中取
     * @return int 上级频道id
     */
    public function GetParentChannelId($channelId, $withCache)
    {
        $result = -1;
        if ($channelId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'channel_data';
            $cacheFile = 'channel_get_parent_channel_id.cache_' . $channelId . '';
            $sql = "SELECT ParentId FROM " . self::TableName_Channel . " WHERE ChannelId = :ChannelId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 根据父id获取列表数据集
     * @param int $topCount 显示的条数
     * @param string $parentId 父id，可以是 id,id,id 的形式
     * @param string $order 排序方式
     * @return array|null 列表数据集
     */
    public function GetListByParentId($topCount, $parentId, $order){
        $result = null;
        if($parentId >0){
            $parentId = Format::FormatSql($parentId);
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'channel_data';
            $cacheFile = 'arr_channel_get_list_by_parent_id.cache_' .
                str_ireplace(",","_",$parentId) .
                '_' . $topCount . '_' . $order;
            $cacheContent = DataCache::Get($cacheDir . DIRECTORY_SEPARATOR . $cacheFile);
            if (strlen($cacheContent) <= 2) {

                switch($order){
                    default:
                        $order = "ORDER BY Sort,Createdate,".self::TableId_Channel."";
                        break;
                }
                $sql = "SELECT
                        ChannelId,
                        ChannelName,
                        SiteId,
                        Icon,
                        Rank,
                        ParentId,
                        TitlePic1UploadFileId,
                        TitlePic2UploadFileId,
                        TitlePic3UploadFileId,
                        ChannelBrowserTitle,
                        ChannelBrowserDescription,
                        ChannelBrowserKeywords,
                        CreateDate,
                        Sort,
                        ChannelIntro,
                        ChildrenChannelId

                    FROM ".self::TableName_Channel."
                    WHERE

                        State<100
                        AND ParentId IN ($parentId)
                        AND IsCircle=1
                        $order
                        LIMIT $topCount;
                        ";

                $dataProperty = new DataProperty();
                //$dataProperty->AddField("ParentId", $parentId);
                $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

                DataCache::Set($cacheDir, $cacheFile, Format::FixJsonEncode($result));

            }else{
                $result = Format::FixJsonDecode($cacheContent);
            }
        }
        return $result;
    }

    /**
     * 根据父id获取列表数据集
     * @param int $siteId 站点id
     * @param int $topCount 显示的条数
     * @param int $rank 级别
     * @param string $order 排序方式
     * @return array|null 列表数据集
     */
    public function GetListByRank($siteId, $topCount, $rank, $order){
        $result = null;
        if($siteId >0){
            switch($order){
                default:
                    $order = "ORDER BY Sort DESC,Createdate DESC,".self::TableId_Channel." DESC";
                    break;
            }
            $sql = "SELECT
                        ChannelId,
                        ChannelName,
                        SiteId,
                        Rank,
                        ParentId,
                        TitlePic1UploadFileId,
                        TitlePic2UploadFileId,
                        TitlePic3UploadFileId,
                        BrowserTitle,
                        BrowserDescription,
                        BrowserKeywords,
                        CreateDate,
                        Sort,
                        ChannelIntro

                    FROM ".self::TableName_Channel."
                    WHERE

                        State<100
                        AND Rank=:Rank
                        AND SiteId=:SiteId
                        AND IsCircle=1
                        $order
                        LIMIT $topCount;
                        ";

            echo $sql;
            $dataProperty = new DataProperty();
            $dataProperty->AddField("Rank", $rank);
            $dataProperty->AddField("SiteId", $siteId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 获取频道的评论审核类型
     * @param $channelId
     * @param $withCache
     * @return int
     */
    public function GetOpenComment($channelId,$withCache){
        $result = -1;
        if ($channelId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'channel_data';
            $cacheFile = 'channel_get_channel_open_comment.cache_' . $channelId . '';
            $sql = "SELECT OpenComment FROM " . self::TableName_Channel . " WHERE ChannelId=:ChannelId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }



    /**
     * 取得频道外部接口url
     * @param int $channelId 频道id
     * @param bool $withCache 是否从缓冲中取
     * @return string url
     */
    public function GetPublishApiUrl($channelId, $withCache)
    {
        $result = -1;
        if ($channelId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'channel_data';
            $cacheFile = 'channel_get_publish_api_url.cache_' . $channelId . '';
            $sql = "SELECT PublishApiUrl FROM " . self::TableName_Channel . " WHERE ChannelId=:ChannelId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }


    /**
     * 取得频道外部接口类型
     * @param int $channelId 频道id
     * @param bool $withCache 是否从缓冲中取
     * @return int 类型对应值
     */
    public function GetPublishApiType($channelId, $withCache)
    {
        $result = -1;
        if ($channelId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'channel_data';
            $cacheFile = 'channel_get_publish_api_type.cache_' . $channelId . '';
            $sql = "SELECT PublishApiType FROM " . self::TableName_Channel . " WHERE ChannelId=:ChannelId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }
}

?>
