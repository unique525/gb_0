<?php
/**
 * 前台 频道模板库模板 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Template
 * @author 525
 */
class TemplateLibraryChannelContentPublicData extends BasePublicData {

    /**
     * 取得动态模板内容
     * @param int $siteId 站点id
     * @param int $templateType 模板类型
     * @param string $templateTag 模板标签
     * @param bool $withCache 是否启用缓冲
     * @return string 动态模板内容
     */
    public function GetTemplateLibraryChannelContentForDynamic(
        $siteId,
        $templateType,
        $templateTag,
        $withCache
    ){
        $result = "";
        if($templateType == ChannelTemplateData::CHANNEL_TEMPLATE_TYPE_DYNAMIC ){ //动态模板
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'channel_template_data';
            $cacheFile = "channel_get_channel_template_content.cache
                            _site_id_".$siteId."_type_".$templateType."
                            _tag_".$templateTag;
            $sql = "SELECT TemplateContent AS ChannelTemplateContent
                    FROM " . self::TableName_TemplateLibraryChannelContent . "
                    WHERE
                            TemplateType=:TemplateType
                        AND
                            TemplateTag=:TemplateTag
                        AND
                            SiteId = :SiteId
                        AND
                            State<".ChannelTemplateData::STATE_REMOVED."
                        ;";

            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);
            $dataProperty->AddField("TemplateType", $templateType);
            $dataProperty->AddField("TemplateTag", $templateTag);
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
     * @param int $siteId 站点id
     * @param int $channelTemplateType 模板类型
     * @param string $channelTemplateTag 模板标签
     * @param bool $withCache 是否启用缓冲
     * @return string 动态模板内容
     */
    public function GetChannelTemplateLibraryContentForMobileForDynamic(
        $siteId,
        $channelTemplateType,
        $channelTemplateTag,
        $withCache
    ){
        $result = "";
        if($channelTemplateType == ChannelTemplateData::CHANNEL_TEMPLATE_TYPE_DYNAMIC ){ //动态模板
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
                        AND
                            SiteId = :SiteId
                        AND
                            State<".ChannelTemplateData::STATE_REMOVED."
                        ;";

            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);
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
     * @param int $siteId 站点id
     * @param int $channelTemplateType 模板类型
     * @param string $channelTemplateTag 模板标签
     * @param bool $withCache 是否启用缓冲
     * @return string 动态模板内容
     */
    public function GetChannelTemplateLibraryContentForPadForDynamic(
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
            $sql = "SELECT ChannelTemplateContentForPad
                    FROM " . self::TableName_ChannelTemplate . "
                    WHERE
                            ChannelTemplateType=:ChannelTemplateType
                        AND
                            ChannelTemplateTag=:ChannelTemplateTag
                        AND
                            SiteId = :SiteId
                        AND
                            State<".ChannelTemplateData::STATE_REMOVED."
                        ;";

            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);
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