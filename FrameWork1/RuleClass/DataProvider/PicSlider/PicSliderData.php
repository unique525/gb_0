<?php
/**
 * 公共 图片轮换 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_PicSlider
 * @author zhangchi
 */
class PicSliderData {

    /**
     * 新稿 0
     */
    const STATE_NEW = 0;
    /**
     * 已审 30
     */
    const STATE_VERIFY = 30;
    /**
     * 已删除 100
     */
    const STATE_REMOVED = 100;

    /**
     * 资讯
     */
    const PIC_SLIDER_TABLE_TYPE_DOCUMENT_NEWS = 101;
    /**
     * 产品
     */
    const PIC_SLIDER_TABLE_TYPE_PRODUCT = 102;
    /**
     * 活动
     */
    const PIC_SLIDER_TABLE_TYPE_ACTIVITY = 103;
} 