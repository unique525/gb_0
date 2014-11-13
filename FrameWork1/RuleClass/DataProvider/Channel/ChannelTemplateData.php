<?php
/**
 * 公共 频道模板 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Channel
 * @author zhangchi
 */
class ChannelTemplateData {

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
     * 自定义页面详细页模板
     */
    const PUBLISH_TYPE_SITE_CONTENT_DETAIL = 32;
    /**
     * 分类信息详细页模板
     */
    const PUBLISH_TYPE_INFORMATION_DETAIL = 33;
    /**
     * 产品详细页模板
     */
    const PUBLISH_TYPE_PRODUCT_DETAIL = 34;

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


} 