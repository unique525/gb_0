<?php

/**
 * 前台 频道模板 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Channel
 * @author zhangchi
 */
class ChannelTemplatePublicData extends BasePublicData {


    /**
     * 取得动态模板内容
     * @param int $siteId 站点id (site id 为0时，全系统搜索模板)
     * @param int $channelTemplateType 模板类型
     * @param string $channelTemplateTag 模板标签
     * @param bool $withCache 是否启用缓冲
     * @return string 动态模板内容
     */
    public function GetChannelTemplateContentForDynamic(
        $siteId,
        $channelTemplateType,
        $channelTemplateTag,
        $withCache
    ){

        $result = "";

        if($channelTemplateType == ChannelTemplateData::CHANNEL_TEMPLATE_TYPE_DYNAMIC ){ //动态模板


            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'channel_template_data';
            $cacheFile = "channel_get_channel_template_content.cache
                            _site_id_".$siteId."_type_".$channelTemplateType."
                            _tag_".$channelTemplateTag;

            $dataProperty = new DataProperty();
            $addSql = "";
            if($siteId > 0){
                $addSql = "AND SiteId = :SiteId";
                $dataProperty->AddField("SiteId", $siteId);
            }


            $sql = "SELECT ChannelTemplateContent
                    FROM " . self::TableName_ChannelTemplate . "
                    WHERE
                            ChannelTemplateType=:ChannelTemplateType
                        AND
                            ChannelTemplateTag=:ChannelTemplateTag
                        $addSql
                        AND
                            State<".ChannelTemplateData::STATE_REMOVED."
                        ;";

            $dataProperty->AddField("ChannelTemplateType", $channelTemplateType);
            $dataProperty->AddField("ChannelTemplateTag", $channelTemplateTag);
            $result = $this->GetInfoOfStringValue(
                $sql,
                $dataProperty,
                $withCache,
                $cacheDir,
                $cacheFile
            );


        }

        return $result;
    }

    /**
     * 取得动态模板(客户端)内容
     * @param int $siteId 站点id(site id 为0时，全系统搜索模板)
     * @param int $channelTemplateType 模板类型
     * @param string $channelTemplateTag 模板标签
     * @param bool $withCache 是否启用缓冲
     * @return string 动态模板内容
     */
    public function GetChannelTemplateContentForMobileForDynamic(
        $siteId,
        $channelTemplateType,
        $channelTemplateTag,
        $withCache
    ){
        $result = "";
        if($channelTemplateType == ChannelTemplateData::CHANNEL_TEMPLATE_TYPE_DYNAMIC ){ //动态模板

            $dataProperty = new DataProperty();
            $addSql = "";
            if($siteId > 0){
                $addSql = "AND SiteId = :SiteId";
                $dataProperty->AddField("SiteId", $siteId);
            }

            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'channel_template_data';
            $cacheFile = "channel_get_channel_template_content_for_mobile.cache
                            _site_id_".$siteId."_type_".$channelTemplateType."
                            _tag_".$channelTemplateTag;
            $sql = "SELECT ChannelTemplateContentForMobile
                    FROM " . self::TableName_ChannelTemplate . "
                    WHERE
                            ChannelTemplateType=:ChannelTemplateType
                        AND
                            ChannelTemplateTag=:ChannelTemplateTag
                        $addSql
                        AND
                            State<".ChannelTemplateData::STATE_REMOVED."
                        ;";

            $dataProperty->AddField("ChannelTemplateType", $channelTemplateType);
            $dataProperty->AddField("ChannelTemplateTag", $channelTemplateTag);
            $result = $this->GetInfoOfStringValue(
                $sql,
                $dataProperty,
                $withCache,
                $cacheDir,
                $cacheFile
            );


        }
        return $result;
    }

    /**
     * 取得动态模板(平板)内容
     * @param int $siteId 站点id(site id 为0时，全系统搜索模板)
     * @param int $channelTemplateType 模板类型
     * @param string $channelTemplateTag 模板标签
     * @param bool $withCache 是否启用缓冲
     * @return string 动态模板内容
     */
    public function GetChannelTemplateContentForPadForDynamic(
        $siteId,
        $channelTemplateType,
        $channelTemplateTag,
        $withCache
    ){
        $result = "";
        if($channelTemplateType == ChannelTemplateData::CHANNEL_TEMPLATE_TYPE_DYNAMIC ){ //动态模板
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'channel_template_data';
            $cacheFile = "channel_get_channel_template_content_for_pad.cache
                            _site_id_".$siteId."_type_".$channelTemplateType."
                            _tag_".$channelTemplateTag;

            $dataProperty = new DataProperty();
            $addSql = "";
            if($siteId > 0){
                $addSql = "AND SiteId = :SiteId";
                $dataProperty->AddField("SiteId", $siteId);
            }

            $sql = "SELECT ChannelTemplateContentForPad
                    FROM " . self::TableName_ChannelTemplate . "
                    WHERE
                            ChannelTemplateType=:ChannelTemplateType
                        AND
                            ChannelTemplateTag=:ChannelTemplateTag
                        $addSql
                        AND
                            State<".ChannelTemplateData::STATE_REMOVED."
                        ;";

            $dataProperty->AddField("ChannelTemplateType", $channelTemplateType);
            $dataProperty->AddField("ChannelTemplateTag", $channelTemplateTag);
            $result = $this->GetInfoOfStringValue(
                $sql,
                $dataProperty,
                $withCache,
                $cacheDir,
                $cacheFile
            );


        }
        return $result;
    }
} 