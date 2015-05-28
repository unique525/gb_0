<?php

/**
 * 公共 论坛主题 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Forum
 * @author zhangchi
 */
class ForumTopicData {


    /*****************  主题类型   ForumTopicClass   *******************/

    /**
     * 主题类型 普通主题
     */
    const FORUM_TOPIC_CLASS_NORMAL = 0;

    /**
     * 主题类型 精华主题
     */
    const FORUM_TOPIC_CLASS_BEST = 1;

    /*****************   主题访问方式  ForumTopicAccess   *******************/

    /**
     * 主题访问方式 无限制
     */
    const FORUM_TOPIC_ACCESS_NORMAL = 0;

    /**
     * 主题访问方式 禁止回复
     */
    const FORUM_TOPIC_ACCESS_BAN_REPLY = 1;

    /**
     * 主题访问方式 悬赏金钱
     */
    const FORUM_TOPIC_ACCESS_REWARD_MONEY = 10;

    /**
     * 主题访问方式 悬赏积分
     */
    const FORUM_TOPIC_ACCESS_REWARD_SCORE = 11;

    /**
     * 主题访问方式 悬赏点券
     */
    const FORUM_TOPIC_ACCESS_REWARD_POINT = 12;

    /**
     * 主题访问方式 悬赏魅力
     */
    const FORUM_TOPIC_ACCESS_REWARD_CHARM = 13;

    /**
     * 主题访问方式 悬赏经验
     */
    const FORUM_TOPIC_ACCESS_REWARD_EXP = 14;



    /**
     * 主题访问方式 出售金钱
     */
    const FORUM_TOPIC_ACCESS_SALE_MONEY = 20;

    /**
     * 主题访问方式 出售积分
     */
    const FORUM_TOPIC_ACCESS_SALE_SCORE = 21;

    /**
     * 主题访问方式 出售点券
     */
    const FORUM_TOPIC_ACCESS_SALE_POINT = 22;

    /**
     * 主题访问方式 出售魅力
     */
    const FORUM_TOPIC_ACCESS_SALE_CHARM = 23;

    /**
     * 主题访问方式 出售经验
     */
    const FORUM_TOPIC_ACCESS_SALE_EXP = 24;


    /**
     * 主题访问方式 限制XX金钱以上查看
     */
    const FORUM_TOPIC_ACCESS_VIEW_LIMIT_MONEY = 30;

    /**
     * 主题访问方式 限制XX积分以上查看
     */
    const FORUM_TOPIC_ACCESS_VIEW_LIMIT_SCORE = 31;

    /**
     * 主题访问方式 限制XX点券以上查看
     */
    const FORUM_TOPIC_ACCESS_VIEW_LIMIT_POINT = 32;

    /**
     * 主题访问方式 限制XX魅力以上查看
     */
    const FORUM_TOPIC_ACCESS_VIEW_LIMIT_CHARM = 33;

    /**
     * 主题访问方式 限制XX经验以上查看
     */
    const FORUM_TOPIC_ACCESS_VIEW_LIMIT_EXP = 34;

    /**
     * 主题访问方式 限制某人查看
     */
    const FORUM_TOPIC_ACCESS_VIEW_LIMIT_SOMEONE = 35;

    /**
     * 主题访问方式 限制好友查看
     */
    const FORUM_TOPIC_ACCESS_VIEW_LIMIT_FRIEND = 36;

    /**
     * 主题访问方式 限制某身份会员查看
     */
    const FORUM_TOPIC_ACCESS_VIEW_LIMIT_USER_GROUP = 37;

    /**
     * 主题访问方式 限制XX发帖数以上查看
     */
    const FORUM_TOPIC_ACCESS_VIEW_LIMIT_POST_COUNT = 38;

    /**
     * 主题访问方式 限制注册时间以前查看
     */
    const FORUM_TOPIC_ACCESS_VIEW_LIMIT_REG_TIME = 39;

    /**
     * 主题访问方式 限制在线时间以上查看
     */
    const FORUM_TOPIC_ACCESS_VIEW_LIMIT_ONLINE_TIME = 40;

    /*****************   主题审核（授权）方式  ForumTopicAudit   *******************/

    /**
     * 主题审核（授权）方式 不限制
     */
    const FORUM_TOPIC_AUDIT_NORMAL = 0;

    /**
     * 主题审核（授权）方式 先审后发
     */
    const FORUM_TOPIC_AUDIT_PRE_AUDIT = 1;

    /**
     * 主题审核（授权）方式 先发后审
     */
    const FORUM_TOPIC_AUDIT_AFTER_AUDIT = 2;

    /**
     * 主题审核（授权）方式 锁定（只允许管理员访问）
     */
    const FORUM_TOPIC_AUDIT_LOCK = 1;

    /**
     * 主题审核（授权）方式 关闭
     */
    const FORUM_TOPIC_AUDIT_CLOSE = 1;

    /**
     * 主题状态 普通
     */
    const FORUM_TOPIC_STATE_NORMAL = 0;
    /**
     * 主题状态 已删除
     */
    const FORUM_TOPIC_STATE_REMOVED = 100;

    /**
     * 主题排序 普通
     */
    const FORUM_TOPIC_SORT_NORMAL = 0;
    /**
     * 主题排序 版块置顶
     */
    const FORUM_TOPIC_SORT_BOARD_TOP = 10;
    /**
     * 主题排序 分区置顶
     */
    const FORUM_TOPIC_SORT_REGION_TOP = 20;
    /**
     * 主题排序 全站置顶
     */
    const FORUM_TOPIC_SORT_ALL_TOP = 30;
} 