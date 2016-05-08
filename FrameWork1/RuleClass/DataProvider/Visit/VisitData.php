<?php
/**
 * 公共 访问统计数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Visit
 * @author hy
 */
class VisitData {


    /**
     * 站点
     */
    const VISIT_TABLE_TYPE_SITE = 1;
    /**
     * 频道
     */
    const VISIT_TABLE_TYPE_CHANNEL = 2;
    /**
     * 资讯
     */
    const VISIT_TABLE_TYPE_DOCUMENT_NEWS = 3;
    /**
     * 电子报版面
     */
    const VISIT_TABLE_TYPE_NEWSPAPER_PAGE = 4;
    /**
     * 电子报文章
     */
    const VISIT_TABLE_TYPE_NEWSPAPER_ARTICLE = 5;
    /**
     * 产品
     */
    const VISIT_TABLE_TYPE_PRODUCT = 6;
    /**
     * 活动
     */
    const VISIT_TABLE_TYPE_ACTIVITY = 7;
    /**
     * 赛事
     */
    const VISIT_TABLE_TYPE_LEAGUE = 8;
    /**
     * 赛事
     */
    const VISIT_TABLE_TYPE_MATCH = 9;

    /**
     * 统计类型 年
     */
    const STATISTICS_BY_MONTH= 0;
    /**
     * 统计类型 月
     */
    const STATISTICS_BY_DAY = 1;
    /**
     * 统计类型 日
     */
    const STATISTICS_BY_HOUR= 2;
} 