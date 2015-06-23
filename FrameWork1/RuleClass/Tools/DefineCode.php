<?php

/**
 * 提供文件操作相关的工具方法
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Tools
 * @author zhangchi
 */
class DefineCode {

    /**
     * FTP 返回值前缀
     */
    const FTP = -101000;

    /**
     * 文件类 返回值前缀
     */
    const FILE_OBJECT = -110000;

    /**
     * 文件上传类 返回值前缀
     */
    const UPLOAD = -115000;

    /**
     * 图片类　返回值前缀
     */
    const IMAGE_OBJECT = -120000;

    /**
     * 数据缓存类 返回值前缀
     */
    const DATA_CACHE = -125000;

    /**
     * 发布类 返回值前缀
     */
    const PUBLISH = -130000;

    /**
     * 会员 后台管理 返回值前缀
     */
    const USER_MANAGE = -140000;

    /**
     * 会员 前台 返回值前缀
     */
    const USER_PUBLIC = -150000;

    /**
     * 会员 客户端 返回值前缀
     */
    const USER_CLIENT = -155000;

    /**
     * 会员相册 后台管理 返回值前缀
     */
    const USER_ALBUM_MANAGE = -160000;

    /**
     * 会员相册 客户端 返回值前缀
     */
    const USER_ALBUM_CLIENT = -165000;

    /**
     * 会员相册 前台 返回值前缀
     */
    const USER_ALBUM_PUBLIC = -170000;

    /**
     * 会员相册 照片 后台管理 返回值前缀
     */
    const USER_ALBUM_PIC_MANAGE = -175000;

    /**
     * 会员相册 照片 客户端 返回值前缀
     */
    const USER_ALBUM_PIC_CLIENT = -180000;

    /**
     * 会员相册 照片 前台 返回值前缀
     */
    const USER_ALBUM_PIC_PUBLIC = -185000;

    /**
     * 频道 后台管理 返回值前缀
     */
    const CHANNEL_MANAGE = -195000;

    /**
     * 频道 前台 返回值前缀
     */
    const CHANNEL_PUBLIC = -200000;

    /**
     * 文档 后台管理 返回值前缀
     */
    const DOCUMENT_NEWS_MANAGE = -210000;

    /**
     * 活动表单 后台管理 返回值前缀
     */
    const CUSTOM_FORM_MANAGE = -240000;

    /**
     * 活动表单页面  后台管理 返回值前缀
     */
    const CUSTOM_FORM_FIELD_MANAGE = -241000;

    /**
     * 活动表单记录 后台管理 返回值前缀
     */
    const CUSTOM_FORM_RECORD_MANAGE = -242000;

    /**
     * 活动表单记录 前台 返回值前缀
     */
    const CUSTOM_FORM_RECORD_PUBLIC = -243000;

    /**
     * 活动表单记录 前台 返回值前缀
     */
    const CUSTOM_FORM_CONTENT_MANAGE = -250000;

    /**
     * 站点 后台管理 返回值前缀
     */
    const SITE_MANAGE = -255000;

    /**
     * 站点配置 后台管理 返回值前缀
     */
    const SITE_CONFIG_MANAGE = -265000;

    /**
     * 站点配置 前台 返回值前缀
     */
    const SITE_CONFIG_PUBLIC = -275000;

    /**
     * 论坛 后台管理 返回值前缀
     */
    const FORUM_MANAGE = -280000;

    /**
     * 论坛 前台 返回值前缀
     */
    const FORUM_PUBLIC = -285000;

    /**
     * 论坛主题 后台管理 返回值前缀
     */
    const FORUM_TOPIC_MANAGE = -290000;

    /**
     * 论坛主题 前台 返回值前缀
     */
    const FORUM_TOPIC_PUBLIC = -295000;

    /**
     * 后台管理员登录 返回值前缀
     */
    const MANAGE_LOGIN_PUBLIC = -300000;

    /**
     * 后台框架左部导航 返回值前缀
     */
    const MANAGE_MENU_OF_USER_MANAGE = - 305000;

    /**
     * 投票调查 后台管理 返回值前缀
     */
    const VOTE_MANAGE = - 310000;

    /**
     * 活动 后台管理 返回值前缀
     */
    const ACTIVITY_MANAGE = - 315000;
    /**
     * 活动 前台管理 返回值前缀
     */
    const ACTIVITY_PUBLIC = - 317000;
    /**
     * 活动类型 后台管理 返回值前缀
     */
    const ACTIVITY_CLASS_MANAGE = - 320000;
    /**
     * 广告点击记录 前台 返回值前缀
     */
    const SITE_AD_LOG_PUBLIC = - 325000;
    /**
     * 抽奖 后台 返回值前缀
     */
    const LOTTERY_MANAGE = - 335000;
    /**
     * 抽奖 前台 返回值前缀
     */
    const LOTTERY_PUBLIC = - 340000;


}