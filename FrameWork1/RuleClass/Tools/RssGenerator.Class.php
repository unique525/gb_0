<?php

/**
 * RSS2.0生成工具类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Tools
 * @author zhangchi
 */
class RssGenerator {

    /**
     * 生成RSS数据
     * @param string $channelTitle 频道标题
     * @param string $channelDescription 频道描述
     * @param string $channelLink 频道连接
     * @param string $language 语言
     * @param array $items 子项目数据
     * @param string $pubDate 发布时间
     * @param string $lastBuildDate 最后生成时间
     * @param string $generator 生成者
     * @param string $channelImgUrl 频道图标网址
     * @return string RSS数据字符串（xml）
     */
    public static function Gen($channelTitle, $channelDescription, $channelLink, $language, $items = null, $pubDate = "", $lastBuildDate = "", $generator = "", $channelImgUrl = "") {
        $rss = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\r\n";
        $rss .= "<rss version=\"2.0\">\r\n";
        $rss .= "<channel>\r\n";
        $rss .= "<title><![CDATA[{$channelTitle}]]></title>\r\n";
        $rss .= "<description><![CDATA[{$channelDescription}]]></description>\r\n";
        $rss .= "<link>{$channelLink}</link>\r\n";
        $rss .= "<language>{$language}</language>\r\n";

        if (!empty($pubDate))
            $rss .= "<pubDate>{$pubDate}</pubDate>\r\n";
        if (!empty($lastBuildDate))
            $rss .= "<lastBuildDate>{$lastBuildDate}</lastBuildDate>\r\n";
        if (!empty($generator))
            $rss .= "<generator>{$generator}</generator>\r\n";

        //$rss .= "<ttl>5</ttl>\r\n";

        if (!empty($channelImgUrl)) {
            $rss .= "<image>\r\n";
            $rss .= "<title><![CDATA[{$channelTitle}]]></title>\r\n";
            $rss .= "<link>{$channelLink}</link>\r\n";
            $rss .= "<url>{$channelImgUrl}</url>\r\n";
            $rss .= "</image>\r\n";
        }

        for ($i = 0; $i < count($items); $i++) {
            $rss .= "<item>\r\n";
            $rss .= "<title><![CDATA[{$items[$i]['title']}]]></title>\r\n";
            $rss .= "<link>{$items[$i]['link']}</link>\r\n";
            $rss .= "<description><![CDATA[{$items[$i]['description']}]]></description>\r\n";
            $rss .= "<pubDate>{$items[$i]['pubDate']}</pubDate>\r\n";
            $rss .= "<category>{$items[$i]['category']}</category>\r\n";
            $rss .= "<author>{$items[$i]['author']}</author>\r\n";
            //$rss .= "<content><![CDATA[{$items[$i]['content']}]]></content>\r\n";
            $rss .= "</item>\r\n";
        }

        $rss .= "</channel>\r\n</rss>";
        return $rss;
    }

}

?>
