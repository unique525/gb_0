<?php
/**
 * 公共 评论 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Comment
 * @author zhangchi
 */
class CommentData {

    /**
     * 评论类型 文字评论
     */
    const COMMENT_TYPE_TEXT = 0;
    /**
     * 评论类型 短评
     */
    const COMMENT_TYPE_SHORT_TEXT = 1;

    /**
     * 评论模块  相册
     */
    const COMMENT_TABLE_TYPE_OF_USER_ALBUM = 1; //

    /**
     * 评论模块  相片
     */
    const COMMENT_TABLE_TYPE_OF_USER_ALBUM_PIC = 2; //相片

    /**
     * 评论模块  活动
     */
    const COMMENT_TABLE_TYPE_OF_ACTIVE = 3; //活动

    /**
     * 评论模块  产品
     */
    const COMMENT_TABLE_TYPE_OF_PRODUCT = 4; //产品

    /**
     * 评论模块  站点内容
     */
    const COMMENT_TABLE_TYPE_OF_SITE_CONTENT = 5; //站点内容

    /**
     * 评论模块  频道评论
     */
    const COMMENT_TABLE_TYPE_OF_CHANNEL = 6; //频道评论

    /**
     * 评论模块  新闻资讯评论
     */
    const COMMENT_TABLE_TYPE_OF_DOCUMENT_NEWS = 7; //新闻资讯评论

    /**
     * 评论模块  电子报评论
     */
    const COMMENT_TABLE_TYPE_OF_NEWSPAPER = 8;

    /**
     * 评论状态 未审
     */
    const COMMENT_STATE_UN_CHECK= 0;

    /**
     * 评论状态 先审后发
     */
    const COMMENT_STATE_FIRST_CHECK_THEN_PUBLISH = 10;

    /**
     * 评论状态 先发后审
     */
    const COMMENT_STATE_FIRST_PUBLISH_THEN_CHECK = 20;

    /**
     * 评论状态 已审
     */
    const COMMENT_STATE_CHECKED = 30;

    /**
     * 评论状态 已否
     */
    const COMMENT_STATE_REMOVE = 100;

} 