<?php

/**
 * 公共 频道 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Channel
 * @author zhangchi
 */
class ChannelData {

    /**
     * 频道状态 正常 0
     */
    const STATE_NORMAL = 0;
    /**
     * 频道状态 删除 100
     */
    const STATE_REMOVED = 100;


    /**
     * 频道类型 站点首页类 0
     */
    const CHANNEL_TYPE_HOME = 0;
    /**
     * 频道类型 新闻信息类 1
     */
    const CHANNEL_TYPE_DOCUMENT_NEWS = 1;
    /**
     * 频道类型 咨询答复类 2
     */
    const CHANNEL_TYPE_THREAD = 2;
    /**
     * 频道类型 图片轮换类 3
     */
    const CHANNEL_TYPE_SLIDER = 3;
    /**
     * 频道类型 产品类 4
     */
    const CHANNEL_TYPE_PRODUCT = 4;
    /**
     * 频道类型 频道结合产品类 5
     */
    const CHANNEL_TYPE_CHANNEL_PRODUCT = 5;
    /**
     * 频道类型 活动类 6
     */
    const CHANNEL_TYPE_ACTIVITY = 6;
    /**
     * 频道类型 在线调查类 7
     */
    const CHANNEL_TYPE_SITE_VOTE = 7;
    /**
     * 频道类型 自定义页面类 8
     */
    const CHANNEL_TYPE_SITE_CONTENT = 8;
    /**
     * 频道类型 友情链接类 9
     */
    const CHANNEL_TYPE_SITE_LINK = 9;
    /**
     * 频道类型 活动表单类 10
     */
    const CHANNEL_TYPE_CUSTOM_FORM = 10;
    /**
     * 频道类型 文字直播类 11
     */
    const CHANNEL_TYPE_TEXT_LIVE = 11;
    /**
     * 频道类型 投票类 12
     */
    const CHANNEL_TYPE_VOTE = 12;
    /**
     * 频道类型 在线测试 13
     */
    const CHANNEL_TYPE_EXAM = 13;
    /**
     * 频道类型 分类信息 14
     */
    const CHANNEL_TYPE_INFORMATION = 14;
    /**
     * 频道类型 电子报 15
     */
    const CHANNEL_TYPE_NEWSPAPER = 15;
    /**
     * 频道类型 外部接口类 50
     */
    const CHANNEL_TYPE_INTERFACE = 50;


    /**
     * 访问方式 公开访问
     */
    const CHANNEL_ACCESS_LIMIT_TYPE_NORMAL = 0;
    /**
     * 访问方式 按会员组加密
     */
    const CHANNEL_ACCESS_LIMIT_TYPE_USER_GROUP = 1;
    /**
     * 访问方式 按会员加密
     */
    const CHANNEL_ACCESS_LIMIT_TYPE_USER = 2;


} 