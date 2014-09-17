<?php

/**
 * 后台管理 频道模板 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Channel
 * @author zhangchi
 */
class ChannelTemplateManageData extends BaseManageData
{

    /**
     * 联动发布，只发布在本频道下
     */
    const PUBLISH_TYPE_LINKAGE_ONLY_SELF = 0;
    /**
     * 联动发布，只发布在触发频道下，有可能是本频道，也有可能是继承频道
     */
    const PUBLISH_TYPE_LINKAGE_ONLY_TRIGGER = 1;
    /**
     * 联动发布，发布在所有继承树关系的频道下
     */
    const PUBLISH_TYPE_LINKAGE_ALL = 2;
    /**
     * 非联动发布，只发布在本频道下
     */
    const PUBLISH_TYPE_ONLY_SELF = 10;
    /**
     * 不发布
     */
    const PUBLISH_TYPE_NOT_PUBLISH = 20;
    /**
     * 资讯详细页模板
     */
    const PUBLISH_TYPE_DOCUMENT_NEWS_DETAIL = 30;
    /**
     * 活动详细页模板
     */
    const PUBLISH_TYPE_ACTIVITY_DETAIL = 31;

    /**
     * 普通模板
     */
    const CHANNEL_TEMPLATE_TYPE_NORMAL = 0;

    /**
     * 动态模板
     */
    const CHANNEL_TEMPLATE_TYPE_DYNAMIC = 1;

    /**
     * 状态：正常
     */
    const STATE_NORMAL = 0;
    /**
     * 状态：已删
     */
    const STATE_REMOVED = 100;

    /**
     * 新增频道模板
     * @param array $httpPostData $_POST数组
     * @return int 新增的频道模板id
     */
    public function Create($httpPostData)
    {
        $result = -1;
        $dataProperty = new DataProperty();
        if (!empty($httpPostData)) {
            $sql = parent::GetInsertSql($httpPostData, self::TableName_Channel, $dataProperty);
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 取得频道模板的内容
     * @param int $channelTemplateId 频道模板id
     * @param bool $withCache 是否从缓冲中取
     * @return string 频道模板的内容
     */
    public function GetChannelTemplateContent($channelTemplateId, $withCache)
    {
        $result = "";
        if ($channelTemplateId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'channel_template_data';
            $cacheFile = 'channel_template_get_channel_template_content.cache_' . $channelTemplateId . '';
            $sql = "SELECT ChannelTemplateContent FROM " . self::TableName_ChannelTemplate . " WHERE ChannelTemplateId = :ChannelTemplateId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelTemplateId", $channelTemplateId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 取得频道模板发布方式
     * @param int $channelTemplateId 频道模板id
     * @param bool $withCache 是否从缓冲中取
     * @return int 频道模板的发布方式
     */
    public function GetPublishType($channelTemplateId, $withCache)
    {
        $result = -1;
        if ($channelTemplateId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'channel_template_data';
            $cacheFile = 'channel_template_get_publish_type.cache_' . $channelTemplateId . '';
            $sql = "SELECT PublishType FROM " . self::TableName_ChannelTemplate . " WHERE ChannelTemplateId = :ChannelTemplateId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelTemplateId", $channelTemplateId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 取得当前频道下所有非详细页发布模板
     * @param int $channelId 频道id
     * @return array|null 发布模板
     */
    public function GetListForPublish($channelId){
        $result = null;

        if($channelId>0){
            $sql = "SELECT * FROM " . self::TableName_ChannelTemplate . "
             WHERE ChannelId=:ChannelId
                AND
                   State<".self::STATE_REMOVED."
                AND
                   PublishType IN (
                            ".self::PUBLISH_TYPE_LINKAGE_ALL.",
                            ".self::PUBLISH_TYPE_LINKAGE_ONLY_SELF.",
                            ".self::PUBLISH_TYPE_LINKAGE_ONLY_TRIGGER.",
                            ".self::PUBLISH_TYPE_ONLY_SELF."
                            );";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 取得当前频道下对应发布方式的模板
     * @param int $channelId 频道id
     * @param int $publishType 发布方式
     * @param int $channelTemplateType 模板类型 0普通 1动态
     * @return array|null 发布模板
     */
    public function GetListByPublishType($channelId, $publishType, $channelTemplateType = ChannelTemplateManageData::CHANNEL_TEMPLATE_TYPE_NORMAL){

        $result = null;

        if($channelId>0){
            $sql = "SELECT * FROM " . self::TableName_ChannelTemplate . "
             WHERE ChannelId=:ChannelId
                AND
                   State<".self::STATE_REMOVED."
                AND
                   PublishType = :PublishType
                AND
                   ChannelTemplateType = :ChannelTemplateType
                   ;";

            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);
            $dataProperty->AddField("PublishType", $publishType);
            $dataProperty->AddField("ChannelTemplateType", $channelTemplateType);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;

    }
} 