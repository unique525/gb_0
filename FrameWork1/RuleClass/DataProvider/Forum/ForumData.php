<?php

/**
 * 公共 论坛 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Forum
 * @author zhangchi
 */
class ForumData {

    /**
     * 状态：正常
     */
    const STATE_NORMAL = 0;
    /**
     * 状态：已删除
     */
    const STATE_REMOVED = 100;


    /**
     * 把数组转化成最后回复信息
     * @param array $arrTopicList 要转化的数组
     * @return string
     */
    public static function FormatLastPostInfo($arrTopicList){

        $string = Format::FixJsonEncode($arrTopicList);

        $string = urlencode($string);

        return $string;

    }

    /**
     * 把最后回复信息转化成数组
     * @param string $string 最后回复信息
     * @return array|int|mixed|null 数组
     */
    public static function UnFormatLastPostInfo($string){

        $string = urldecode($string);

        $arr = Format::FixJsonDecode($string);

        return $arr;
    }


    /**
     * 增加一个新的标题到最后回复信息
     * @param $topicTitle
     * @param $lastPostInfo
     * @return string
     */
    public static function AddToLastPostInfo($topicTitle,$lastPostInfo){

        $arr = ForumData::UnFormatLastPostInfo($lastPostInfo);

        array_pop($arr);//将数组最后一个单元弹出（出栈）。

        array_unshift($arr,$topicTitle); //在数组开头插入一个或多个元素。

        return ForumData::FormatLastPostInfo($arr);

    }

} 