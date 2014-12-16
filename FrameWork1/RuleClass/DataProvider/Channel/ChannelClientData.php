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
     * 根据父id获取列表数据集
     * @param string $parentId 父id，可以是 id,id,id 的形式
     * @param string $order 排序方式
     * @param int $topCount 显示的条数
     * @return array|null 列表数据集
     */
    public function GetListByParentId($parentId, $order = "", $topCount = null){
        $result = null;
        if ($topCount != null)
        {
            $topCount = " limit " . $topCount;
        }
        else {
            $topCount = "";
        }
        if($parentId >0){
            $parentId = Format::FormatSql($parentId);
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'channel_data';
            $cacheFile = 'arr_channel_get_list_by_parent_id_.cache_' .
                str_ireplace(",","_",$parentId) .
                '_' . $topCount . '_' . $order;
            $cacheContent = DataCache::Get($cacheDir . DIRECTORY_SEPARATOR . $cacheFile);
            if (strlen($cacheContent) <= 2) {

                switch($order){
                    default:
                        $order = "ORDER BY Sort DESC,".self::TableId_Channel."";
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
                        BrowserTitle,
                        BrowserDescription,
                        BrowserKeywords,
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
                        $topCount
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

} 