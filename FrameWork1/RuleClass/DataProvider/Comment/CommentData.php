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