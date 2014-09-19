<?php

/**
 * 公共 资讯 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_DocumentNews
 * @author zhangchi
 */
class DocumentNewsData {
    /** 文档状态定义 */

    /**
     * 新稿
     */
    const STATE_NEW = 0;
    /**
     * 已编
     */
    const STATE_MODIFY = 1;
    /**
     * 返工
     */
    const STATE_REDO = 2;
    /**
     * 一审
     */
    const STATE_FIRST_VERIFY = 11;
    /**
     * 二审
     */
    const STATE_SECOND_VERIFY = 12;
    /**
     * 三审
     */
    const STATE_THIRD_VERIFY = 13;
    /**
     * 终审
     */
    const STATE_FINAL_VERIFY = 14;
    /**
     * 已否
     */
    const STATE_REFUSE = 20;
    /**
     * 已发
     */
    const STATE_PUBLISHED = 30;

} 