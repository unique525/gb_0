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
    const FILE_OBJECT = -102000;

    /**
     * 图片类　返回值前缀
     */
    const IMAGE_OBJECT = -103000;

    /**
     * 数据缓存类 返回值前缀
     */
    const DATA_CACHE = -104000;

    /**
     * 发布类 返回值前缀
     */
    const PUBLISH = -105000;

    /**
     * 会员 后台管理 返回值前缀
     */
    const USER_MANAGE = -106000;

    /**
     * 会员 前台 返回值前缀
     */
    const USER_PUBLIC = -107000;

    /**
     * 会员 客户端 返回值前缀
     */
    const USER_CLIENT = -108000;

    /**
     * 会员相册 后台管理 返回值前缀
     */
    const USER_ALBUM_MANAGE = -109000;

    /**
     * 会员相册 客户端 返回值前缀
     */
    const USER_ALBUM_CLIENT = -200000;

    /**
     * 会员相册 前台 返回值前缀
     */
    const USER_ALBUM_PUBLIC = -201000;

    /**
     * 会员相册 照片 后台管理 返回值前缀
     */
    const USER_ALBUM_PIC_MANAGE = -202000;

    /**
     * 会员相册 照片 客户端 返回值前缀
     */
    const USER_ALBUM_PIC_CLIENT = -203000;

    /**
     * 会员相册 照片 前台 返回值前缀
     */
    const USER_ALBUM_PIC_PUBLIC = -204000;

    /**
     * 频道 后台管理 返回值前缀
     */
    const CHANNEL_MANAGE = -205000;

    /**
     * 频道 前台 返回值前缀
     */
    const CHANNEL_PUBLIC = -206000;

    /**
     * 文档 后台管理 返回值前缀
     */
    const DOCUMENT_NEWS_MANAGE = -207000;

    /**
     * 活动表单 后台管理 返回值前缀
     */
    const CUSTOM_FORM_MANAGE = -208000;

    /**
     * 活动表单页面  后台管理 返回值前缀
     */
    const CUSTOM_FORM_FIELD_MANAGE = -209000;

    /**
     * 活动表单记录 后台管理 返回值前缀
     */
    const CUSTOM_FORM_RECORD_MANAGE = -210000;

    /**
     * 活动表单记录 前台 返回值前缀
     */
    const CUSTOM_FORM_RECORD_PUBLIC = -211000;

    /**
     * 活动表单记录 前台 返回值前缀
     */
    const CUSTOM_FORM_CONTENT_MANAGE = -212000;

    /**
     * 站点 后台管理 返回值前缀
     */
    const SITE_MANAGE = -213000;

    /**
     * 站点配置 后台管理 返回值前缀
     */
    const SITE_CONFIG_MANAGE = -214000;

    /**
     * 站点配置 前台 返回值前缀
     */
    const SITE_CONFIG_PUBLIC = -215000;

    /**
     * 论坛 后台管理 返回值前缀
     */
    const FORUM_MANAGE = -216000;

    /**
     * 论坛 前台 返回值前缀
     */
    const FORUM_PUBLIC = -217000;

    /**
     * 论坛主题 后台管理 返回值前缀
     */
    const FORUM_TOPIC_MANAGE = -218000;

    /**
     * 论坛主题 前台 返回值前缀
     */
    const FORUM_TOPIC_PUBLIC = -219000;

    /**
     * 后台管理员登录 返回值前缀
     */
    const MANAGE_LOGIN_PUBLIC = -300000;

    /**
     * 后台框架左部导航 返回值前缀
     */
    const MANAGE_MENU_OF_USER_MANAGE = - 301000;



}