<?php
/**
 * 客户端 频道 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Channel
 * @author zhangchi
 */
class ChannelClientData extends BaseClientData {

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
     * 根据频道id号得到当前频道下所有子节点数据
     * @param string $parentId 父频道id
     * @param string $channelId 频道id，可以是 id,id,id 的形式
     * @param string $order 排序方式
     * @param int $topCount 显示的条数
     * @return array|null 列表数据集
     */
    public function GetAllChildListByChannelId($parentId,$channelId, $order = "", $topCount = null){
        $result = null;
        if ($topCount != null)
        {
            $topCount = " LIMIT " . $topCount;
        }
        else {
            $topCount = "";
        }
        if($channelId >0){
            $channelId = Format::FormatSql($channelId);
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'channel_data';
            $cacheFile = 'arr_channel_get_all_child_list_by_channel_id_.cache_' .
                str_ireplace(",","_",$parentId) .
                '_' . $topCount . '_' . $order;
            $cacheContent = DataCache::Get($cacheDir . DIRECTORY_SEPARATOR . $cacheFile);
            if (strlen($cacheContent) <= 2) {

                switch ($order) {
                    case "time_desc":
                        $order = " ORDER BY c.Createdate DESC";
                        break;
                    default:
                        $order = " ORDER BY c.Sort DESC,c.Createdate DESC ";
                        break;
                }
                $selectColumn = "
                        c.*,
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
                $sql = "SELECT

                        $selectColumn

                    FROM ".self::TableName_Channel." c
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf1 on c.TitlePic1UploadFileId=uf1.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf2 on c.TitlePic2UploadFileId=uf2.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf3 on c.TitlePic3UploadFileId=uf3.UploadFileId

                    WHERE
                        ShowInClient = 1
                        AND State<".ChannelData::STATE_REMOVED."
                        AND ChannelId IN ($channelId)
                        $order
                        $topCount
                        ";

                $dataProperty = new DataProperty();
                //$dataProperty->AddField("ParentId", $channelId);
                $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

                DataCache::Set($cacheDir, $cacheFile, Format::FixJsonEncode($result));

            }else{
                $result = Format::FixJsonDecode($cacheContent);
            }
        }
        return $result;
    }

    /**
     * 根据频道类型得到频道数据集
     * @param int $siteId 站点id
     * @param int $channelType 频道类型
     * @param string $order 排序方式
     * @param int $topCount 显示的条数
     * @return array|null 列表数据集
     */
    public function GetListByChannelType(
        $siteId,
        $channelType,
        $order = "",
        $topCount = null
    ){
        $result = null;
        if ($topCount != null)
        {
            $topCount = " LIMIT " . $topCount;
        }
        else {
            $topCount = "";
        }
        if($siteId >0){
            $siteId = Format::FormatSql($siteId);
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'channel_data';
            $cacheFile = 'arr_channel_list_by_channel_type_.cache_' .
                str_ireplace(",","_",$siteId) .
                '_' . $topCount . '_' . $order . '_' . $channelType;
            $cacheContent = DataCache::Get($cacheDir . DIRECTORY_SEPARATOR . $cacheFile);
            if (strlen($cacheContent) <= 2) {

                switch($order){
                    default:
                        $order = " ORDER BY c.Sort DESC ";
                        break;
                }
                $selectColumn = "
                        c.*,
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
                $sql = "SELECT

                        $selectColumn

                    FROM ".self::TableName_Channel." c
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf1 on c.TitlePic1UploadFileId=uf1.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf2 on c.TitlePic2UploadFileId=uf2.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf3 on c.TitlePic3UploadFileId=uf3.UploadFileId

                    WHERE
                        ShowInClient = 1
                        AND State<".ChannelData::STATE_REMOVED."
                        AND SiteId=:SiteId
                        AND ChannelType=:ChannelType
                        $order
                        $topCount
                        ";

                $dataProperty = new DataProperty();
                $dataProperty->AddField("SiteId", $siteId);
                $dataProperty->AddField("ChannelType", $channelType);
                $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

                DataCache::Set($cacheDir, $cacheFile, Format::FixJsonEncode($result));

            }else{
                $result = Format::FixJsonDecode($cacheContent);
            }
        }
        return $result;
    }


    /**
     * 取得站点下的频道数据集，只调用ShowInClientMenu字段为1的频道
     * @param string $siteId 站点id
     * @param string $order 排序方式
     * @param int $topCount 显示的条数
     * @return array|null 列表数据集
     */
    public function GetListBySiteId($siteId, $order = "", $topCount = null){
        $result = null;
        if ($topCount != null)
        {
            $topCount = " LIMIT " . $topCount;
        }
        else {
            $topCount = "";
        }
        if($siteId > 0){
            $siteId = Format::FormatSql($siteId);
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'channel_data';
            $cacheFile = 'arr_channel_list_by_site_id_.cache_' .
                str_ireplace(",","_",$siteId) .
                '_' . $topCount . '_' . $order;
            $cacheContent = DataCache::Get($cacheDir . DIRECTORY_SEPARATOR . $cacheFile);
            if (strlen($cacheContent) <= 2) {

                switch($order){
                    default:
                        $order = " ORDER BY c.Sort DESC ";
                        break;
                }

                $selectColumn = "
                        c.*,
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


                $sql = "
                    SELECT

                            $selectColumn

                    FROM ".self::TableName_Channel." c
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf1 on c.TitlePic1UploadFileId=uf1.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf2 on c.TitlePic2UploadFileId=uf2.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf3 on c.TitlePic3UploadFileId=uf3.UploadFileId

                    WHERE
                        c.ShowInClientMenu = 1
                        AND c.State < ".ChannelData::STATE_REMOVED."
                        AND c.SiteId=:SiteId
                        $order
                        $topCount
                        ";

                $dataProperty = new DataProperty();
                $dataProperty->AddField("SiteId", $siteId);
                $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

                DataCache::Set($cacheDir, $cacheFile, Format::FixJsonEncode($result));

            }else{
                $result = Format::FixJsonDecode($cacheContent);
            }
        }
        return $result;
    }

} 