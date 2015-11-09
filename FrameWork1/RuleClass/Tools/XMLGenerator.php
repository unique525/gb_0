<?php

/**
 * XML数据相关的工具方法
 * @category iCMS
 * @package iCMS_Rules_Tools
 * @author zhangchi
 */
class XMLGenerator {

    /**
     * 生成XML数据（只针对document news表）
     * @param string $channelTitle 频道标题
     * @param string $channelDescription 频道描述
     * @param string $channelLink 频道连接
     * @param string $language 语言
     * @param array $items 子项目数据
     * @param string $pubDate 发布时间
     * @param string $lastBuildDate 最后生成时间
     * @param string $generator 生成者
     * @param string $channelImgUrl 频道图标网址
     * @return string 数据字符串（xml）
     */
    public static function GenForDocumentNews($channelTitle, $channelDescription, $channelLink, $language, $items = null, $pubDate = "", $lastBuildDate = "", $generator = "", $channelImgUrl = "") {
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
            $rss .= "<DocumentNewsId>{$items[$i]['DocumentNewsId']}</DocumentNewsId>\r\n";
            $rss .= "<SiteId>{$items[$i]['SiteId']}</SiteId>\r\n";
            $rss .= "<ChannelId>{$items[$i]['ChannelId']}</ChannelId>\r\n";
            $rss .= "<DocumentNewsTitle><![CDATA[{$items[$i]['DocumentNewsTitle']}]]></DocumentNewsTitle>\r\n";
            $rss .= "<DocumentNewsSubTitle><![CDATA[{$items[$i]['DocumentNewsSubTitle']}]]></DocumentNewsSubTitle>\r\n";
            $rss .= "<DocumentNewsCiteTitle><![CDATA[{$items[$i]['DocumentNewsCiteTitle']}]]></DocumentNewsCiteTitle>\r\n";
            $rss .= "<DocumentNewsShortTitle><![CDATA[{$items[$i]['DocumentNewsShortTitle']}]]></DocumentNewsShortTitle>\r\n";
            $rss .= "<DocumentNewsIntro><![CDATA[{$items[$i]['DocumentNewsIntro']}]]></DocumentNewsIntro>\r\n";
            $rss .= "<CreateDate>{$items[$i]['CreateDate']}</CreateDate>\r\n";
            $rss .= "<ManageUserId>{$items[$i]['ManageUserId']}</ManageUserId>\r\n";
            $rss .= "<ManageUserName><![CDATA[{$items[$i]['ManageUserName']}]]></ManageUserName>\r\n";
            $rss .= "<UserId>{$items[$i]['UserId']}</UserId>\r\n";
            $rss .= "<UserName><![CDATA[{$items[$i]['UserName']}]]></UserName>\r\n";
            $rss .= "<Author><![CDATA[{$items[$i]['Author']}]]></Author>\r\n";
            $rss .= "<State>{$items[$i]['State']}</State>\r\n";
            $rss .= "<DocumentNewsType><![CDATA[{$items[$i]['DocumentNewsType']}]]></DocumentNewsType>\r\n";
            $rss .= "<DirectUrl><![CDATA[{$items[$i]['DirectUrl']}]]></DirectUrl>\r\n";
            $rss .= "<ShowDate>{$items[$i]['ShowDate']}</ShowDate>\r\n";
            $rss .= "<SourceName><![CDATA[{$items[$i]['SourceName']}]]></SourceName>\r\n";
            $rss .= "<DocumentNewsMainTag><![CDATA[{$items[$i]['DocumentNewsMainTag']}]]></DocumentNewsMainTag>\r\n";
            $rss .= "<DocumentNewsTag><![CDATA[{$items[$i]['DocumentNewsTag']}]]></DocumentNewsTag>\r\n";
            $rss .= "<Sort>{$items[$i]['Sort']}</Sort>\r\n";
            $rss .= "<TitlePic1UploadFileId><![CDATA[{$items[$i]['TitlePic1UploadFileId']}]]></TitlePic1UploadFileId>\r\n";
            $rss .= "<TitlePic2UploadFileId><![CDATA[{$items[$i]['TitlePic2UploadFileId']}]]></TitlePic2UploadFileId>\r\n";
            $rss .= "<TitlePic3UploadFileId><![CDATA[{$items[$i]['TitlePic3UploadFileId']}]]></TitlePic3UploadFileId>\r\n";
            $rss .= "<DocumentNewsTitleColor><![CDATA[{$items[$i]['DocumentNewsTitleColor']}]]></DocumentNewsTitleColor>\r\n";
            $rss .= "<DocumentNewsTitleBold><![CDATA[{$items[$i]['DocumentNewsTitleBold']}]]></DocumentNewsTitleBold>\r\n";
            $rss .= "<OpenComment>{$items[$i]['OpenComment']}</OpenComment>\r\n";
            $rss .= "<ShowHour>{$items[$i]['ShowHour']}</ShowHour>\r\n";
            $rss .= "<ShowMinute>{$items[$i]['ShowMinute']}</ShowMinute>\r\n";
            $rss .= "<ShowSecond>{$items[$i]['ShowSecond']}</ShowSecond>\r\n";
            $rss .= "<IsHot>{$items[$i]['IsHot']}</IsHot>\r\n";
            $rss .= "<RecLevel>{$items[$i]['RecLevel']}</RecLevel>\r\n";
            $rss .= "<ShowPicMethod>{$items[$i]['ShowPicMethod']}</ShowPicMethod>\r\n";
            $rss .= "<ClosePosition>{$items[$i]['ClosePosition']}</ClosePosition>\r\n";
            $rss .= "<Hit>{$items[$i]['Hit']}</Hit>\r\n";
            $rss .= "<PublishDate>{$items[$i]['PublishDate']}</PublishDate>\r\n";
            $rss .= "<AllHit>{$items[$i]['AllHit']}</AllHit>\r\n";
            $rss .= "<DocumentNewsUrl><![CDATA[{$items[$i]['DocumentNewsUrl']}]]></DocumentNewsUrl>\r\n";


            $rss .= "<TitlePic1UploadFilePath><![CDATA[{$items[$i]['TitlePic1UploadFilePath']}]]></TitlePic1UploadFilePath>\r\n";
            $rss .= "<TitlePic1UploadFileMobilePath><![CDATA[{$items[$i]['TitlePic1UploadFileMobilePath']}]]></TitlePic1UploadFileMobilePath>\r\n";
            $rss .= "<TitlePic1UploadFilePadPath><![CDATA[{$items[$i]['TitlePic1UploadFilePadPath']}]]></TitlePic1UploadFilePadPath>\r\n";
            $rss .= "<TitlePic1UploadFileThumbPath1><![CDATA[{$items[$i]['TitlePic1UploadFileThumbPath1']}]]></TitlePic1UploadFileThumbPath1>\r\n";
            $rss .= "<TitlePic1UploadFileThumbPath2><![CDATA[{$items[$i]['TitlePic1UploadFileThumbPath2']}]]></TitlePic1UploadFileThumbPath2>\r\n";
            $rss .= "<TitlePic1UploadFileThumbPath3><![CDATA[{$items[$i]['TitlePic1UploadFileThumbPath3']}]]></TitlePic1UploadFileThumbPath3>\r\n";
            $rss .= "<TitlePic1UploadFileWatermarkPath1><![CDATA[{$items[$i]['TitlePic1UploadFileWatermarkPath1']}]]></TitlePic1UploadFileWatermarkPath1>\r\n";
            $rss .= "<TitlePic1UploadFileWatermarkPath2><![CDATA[{$items[$i]['TitlePic1UploadFileWatermarkPath2']}]]></TitlePic1UploadFileWatermarkPath2>\r\n";
            $rss .= "<TitlePic1UploadFileCompressPath1><![CDATA[{$items[$i]['TitlePic1UploadFileCompressPath1']}]]></TitlePic1UploadFileCompressPath1>\r\n";
            $rss .= "<TitlePic1UploadFileCompressPath2><![CDATA[{$items[$i]['TitlePic1UploadFileCompressPath2']}]]></TitlePic1UploadFileCompressPath2>\r\n";
            $rss .= "<TitlePic1UploadFileCutPath1><![CDATA[{$items[$i]['TitlePic1UploadFileCutPath1']}]]></TitlePic1UploadFileCutPath1>\r\n";


            $rss .= "<TitlePic2UploadFilePath>{$items[$i]['TitlePic2UploadFilePath']}</TitlePic2UploadFilePath>\r\n";
            $rss .= "<TitlePic2UploadFileMobilePath>{$items[$i]['TitlePic2UploadFileMobilePath']}</TitlePic2UploadFileMobilePath>\r\n";
            $rss .= "<TitlePic2UploadFilePadPath>{$items[$i]['TitlePic2UploadFilePadPath']}</TitlePic2UploadFilePadPath>\r\n";
            $rss .= "<TitlePic2UploadFileThumbPath1>{$items[$i]['TitlePic2UploadFileThumbPath1']}</TitlePic2UploadFileThumbPath1>\r\n";
            $rss .= "<TitlePic2UploadFileThumbPath2>{$items[$i]['TitlePic2UploadFileThumbPath2']}</TitlePic2UploadFileThumbPath2>\r\n";
            $rss .= "<TitlePic2UploadFileThumbPath3>{$items[$i]['TitlePic2UploadFileThumbPath3']}</TitlePic2UploadFileThumbPath3>\r\n";
            $rss .= "<TitlePic2UploadFileWatermarkPath1>{$items[$i]['TitlePic2UploadFileWatermarkPath1']}</TitlePic2UploadFileWatermarkPath1>\r\n";
            $rss .= "<TitlePic2UploadFileWatermarkPath2>{$items[$i]['TitlePic2UploadFileWatermarkPath2']}</TitlePic2UploadFileWatermarkPath2>\r\n";
            $rss .= "<TitlePic2UploadFileCompressPath1>{$items[$i]['TitlePic2UploadFileCompressPath1']}</TitlePic2UploadFileCompressPath1>\r\n";
            $rss .= "<TitlePic2UploadFileCompressPath2>{$items[$i]['TitlePic2UploadFileCompressPath2']}</TitlePic2UploadFileCompressPath2>\r\n";
            $rss .= "<TitlePic2UploadFileCutPath1>{$items[$i]['TitlePic2UploadFileCutPath1']}</TitlePic2UploadFileCutPath1>\r\n";

            $rss .= "<TitlePic3UploadFilePath>{$items[$i]['TitlePic3UploadFilePath']}</TitlePic3UploadFilePath>\r\n";
            $rss .= "<TitlePic3UploadFileMobilePath>{$items[$i]['TitlePic3UploadFileMobilePath']}</TitlePic3UploadFileMobilePath>\r\n";
            $rss .= "<TitlePic3UploadFilePadPath>{$items[$i]['TitlePic3UploadFilePadPath']}</TitlePic3UploadFilePadPath>\r\n";
            $rss .= "<TitlePic3UploadFileThumbPath1>{$items[$i]['TitlePic3UploadFileThumbPath1']}</TitlePic3UploadFileThumbPath1>\r\n";
            $rss .= "<TitlePic3UploadFileThumbPath2>{$items[$i]['TitlePic3UploadFileThumbPath2']}</TitlePic3UploadFileThumbPath2>\r\n";
            $rss .= "<TitlePic3UploadFileThumbPath3>{$items[$i]['TitlePic3UploadFileThumbPath3']}</TitlePic3UploadFileThumbPath3>\r\n";
            $rss .= "<TitlePic3UploadFileWatermarkPath1>{$items[$i]['TitlePic3UploadFileWatermarkPath1']}</TitlePic3UploadFileWatermarkPath1>\r\n";
            $rss .= "<TitlePic3UploadFileWatermarkPath2>{$items[$i]['TitlePic3UploadFileWatermarkPath2']}</TitlePic3UploadFileWatermarkPath2>\r\n";
            $rss .= "<TitlePic3UploadFileCompressPath1>{$items[$i]['TitlePic3UploadFileCompressPath1']}</TitlePic3UploadFileCompressPath1>\r\n";
            $rss .= "<TitlePic3UploadFileCompressPath2>{$items[$i]['TitlePic3UploadFileCompressPath2']}</TitlePic3UploadFileCompressPath2>\r\n";
            $rss .= "<TitlePic3UploadFileCutPath1>{$items[$i]['TitlePic3UploadFileCutPath1']}</TitlePic3UploadFileCutPath1>\r\n";
            //$rss .= "<content><![CDATA[{$items[$i]['content']}]]></content>\r\n";
            $rss .= "</item>\r\n";
        }

        $rss .= "</channel>\r\n</rss>";
        return $rss;
    }

    /**
     * 为客户端软件生成XML数据（只针对documentnews表）
     * @param string $channelTitle 频道标题
     * @param string $channelDescription 频道描述
     * @param string $channelLink 频道连接
     * @param string $language 语言
     * @param array $items 子项目数据
     * @param string $pubDate 发布时间
     * @param string $lastBuildDate 最后生成时间
     * @param string $generator 生成者
     * @param string $channelImgUrl 频道图标网址
     * @param string $siteUrl 站点网址
     * @return string 数据字符串（xml）
     */
    public static function GenDoucmentNewsForClient($channelTitle, $channelDescription, $channelLink, $language, $items = null, $pubDate = "", $lastBuildDate = "", $generator = "", $channelImgUrl = "", $siteUrl = "") {
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

            $date1 = explode(' ', $items[$i]['PublishDate']);
            $date2 = explode('-', $date1[0]);
            $year = $date2[0];
            $month = $date2[1];
            $day = $date2[2];



            $rss .= "<item>\r\n";
            $rss .= "<ID>{$items[$i]['DocumentNewsID']}</ID>\r\n";
            $rss .= "<SiteID>{$items[$i]['SiteID']}</SiteID>\r\n";
            $rss .= "<link><![CDATA[{$items[$i]['link']}]]></link>\r\n";
            $rss .= "<DocumentChannelID>{$items[$i]['DocumentChannelID']}</DocumentChannelID>\r\n";
            $rss .= "<title><![CDATA[{$items[$i]['DocumentNewsTitle']}]]></title>\r\n";
            $rss .= "<subTitle><![CDATA[{$items[$i]['DocumentNewsSubTitle']}]]></subTitle>\r\n";
            $rss .= "<citeTitle><![CDATA[{$items[$i]['DocumentNewsCiteTitle']}]]></citeTitle>\r\n";
            $rss .= "<shortTitle><![CDATA[{$items[$i]['DocumentNewsShortTitle']}]]></shortTitle>\r\n";
            $rss .= "<description><![CDATA[{$items[$i]['DocumentNewsIntro']}]]></description>\r\n";
            $rss .= "<Content><![CDATA[{$items[$i]['DocumentNewsContent']}]]></Content>\r\n";
            $rss .= "<pubDate>{$items[$i]['CreateDate']}</pubDate>\r\n";
            $rss .= "<userId>{$items[$i]['UserID']}</userId>\r\n";
            $rss .= "<userName><![CDATA[{$items[$i]['userName']}]]></userName>\r\n";
            $rss .= "<author><![CDATA[{$items[$i]['Author']}]]></author>\r\n";
            $rss .= "<type>{$items[$i]['DocumentNewsType']}</type>\r\n";
            $rss .= "<directUrl><![CDATA[{$items[$i]['DirectUrl']}]]></directUrl>\r\n";
            $rss .= "<publishDate>{$items[$i]['PublishDate']}</publishDate>\r\n";
            $rss .= "<showDate>{$items[$i]['ShowDate']}</showDate>\r\n";
            $rss .= "<sourceName><![CDATA[{$items[$i]['SourceName']}]]></sourceName>\r\n";
            $rss .= "<mainTag><![CDATA[{$items[$i]['DocumentNewsMainTag']}]]></mainTag>\r\n";
            $rss .= "<tag><![CDATA[{$items[$i]['DocumentNewsTag']}]]></tag>\r\n";
            $rss .= "<sort>{$items[$i]['Sort']}</sort>\r\n";
            $rss .= "<titlePic><![CDATA[{$items[$i]['TitlePic']}]]></titlePic>\r\n";
            $rss .= "<titlePic2><![CDATA[{$items[$i]['TitlePic2']}]]></titlePic2>\r\n";
            $rss .= "<titlePic3><![CDATA[{$items[$i]['TitlePic3']}]]></titlePic3>\r\n";
            $rss .= "<titlePicMobile><![CDATA[{$items[$i]['TitlePicMobile']}]]></titlePicMobile>\r\n";
            $rss .= "<titlePicPad><![CDATA[{$items[$i]['TitlePicPad']}]]></titlePicPad>\r\n";
            $rss .= "<titleColor><![CDATA[{$items[$i]['DocumentNewsTitleColor']}]]></titleColor>\r\n";
            $rss .= "<titleBold><![CDATA[{$items[$i]['DocumentNewsTitleBold']}]]></titleBold>\r\n";
            $rss .= "<openComment>{$items[$i]['OpenComment']}</openComment>\r\n";
            $rss .= "<showHour>{$items[$i]['ShowHour']}</showHour>\r\n";
            $rss .= "<showMinute>{$items[$i]['ShowMinute']}</showMinute>\r\n";
            $rss .= "<showSecond>{$items[$i]['ShowSecond']}</showSecond>\r\n";
            $rss .= "<isHot>{$items[$i]['IsHot']}</isHot>\r\n";
            $rss .= "<recLevel>{$items[$i]['RecLevel']}</recLevel>\r\n";
            $rss .= "<link>$siteUrl/h/{$items[$i]['DocumentChannelID']}/$year$month$day/{$items[$i]['DocumentNewsID']}.html</link>\r\n";

            //$rss .= "<content><![CDATA[{$items[$i]['content']}]]></content>\r\n";
            $rss .= "</item>\r\n";
        }

        $rss .= "</channel>\r\n</rss>";
        return $rss;
    }

    public static function GenDoucmentNewsContentForClient($channelTitle, $channelDescription, $channelLink, $language, $items = null, $pubDate = "", $lastBuildDate = "", $generator = "", $channelImgUrl = "") {
        $rss = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\r\n";
        //$rss .= "<rss version=\"2.0\">\r\n";
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

        for ($i = 0; $i <= 0; $i++) {
            $rss .= "<item>\r\n";
            $rss .= "<ID>{$items['DocumentNewsID']}</ID>\r\n";
            $rss .= "<SiteID>{$items['SiteID']}</SiteID>\r\n";
            $rss .= "<DocumentChannelID>{$items['DocumentChannelID']}</DocumentChannelID>\r\n";
            $rss .= "<title><![CDATA[{$items['DocumentNewsTitle']}]]></title>\r\n";
            $rss .= "<subTitle><![CDATA[{$items['DocumentNewsSubTitle']}]]></subTitle>\r\n";
            $rss .= "<citeTitle><![CDATA[{$items['DocumentNewsCiteTitle']}]]></citeTitle>\r\n";
            $rss .= "<shortTitle><![CDATA[{$items['DocumentNewsShortTitle']}]]></shortTitle>\r\n";
            $rss .= "<description><![CDATA[{$items['DocumentNewsIntro']}]]></description>\r\n";
            $rss .= "<pubDate>{$items['CreateDate']}</pubDate>\r\n";
            $rss .= "<userId>{$items['UserID']}</userId>\r\n";
            $rss .= "<userName><![CDATA[{$items['userName']}]]></userName>\r\n";
            $rss .= "<author><![CDATA[{$items['Author']}]]></author>\r\n";
            $rss .= "<type>{$items['DocumentNewsType']}</type>\r\n";
            $rss .= "<directUrl><![CDATA[{$items['DirectUrl']}]]></directUrl>\r\n";
            $rss .= "<publishDate>{$items['PublishDate']}</publishDate>\r\n";
            $rss .= "<showDate>{$items['ShowDate']}</showDate>\r\n";
            $rss .= "<sourceName><![CDATA[{$items['SourceName']}]]></sourceName>\r\n";
            $rss .= "<mainTag><![CDATA[{$items['DocumentNewsMainTag']}]]></mainTag>\r\n";
            $rss .= "<tag><![CDATA[{$items['DocumentNewsTag']}]]></tag>\r\n";
            $rss .= "<content><![CDATA[{$items['DocumentNewsContent']}]]></content>\r\n";
            $rss .= "<sort>{$items['Sort']}</sort>\r\n";
            $rss .= "<titlePic><![CDATA[{$items['TitlePic']}]]></titlePic>\r\n";
            $rss .= "<titlePic2><![CDATA[{$items['TitlePic2']}]]></titlePic2>\r\n";
            $rss .= "<titlePic3><![CDATA[{$items['TitlePic3']}]]></titlePic3>\r\n";
            $rss .= "<titlePicMobile><![CDATA[{$items['TitlePicMobile']}]]></titlePicMobile>\r\n";
            $rss .= "<titlePicPad><![CDATA[{$items['TitlePicPad']}]]></titlePicPad>\r\n";
            $rss .= "<titleColor><![CDATA[{$items['DocumentNewsTitleColor']}]]></titleColor>\r\n";
            $rss .= "<titleBold><![CDATA[{$items['DocumentNewsTitleBold']}]]></titleBold>\r\n";
            $rss .= "<openComment>{$items['OpenComment']}</openComment>\r\n";
            $rss .= "<showHour>{$items['ShowHour']}</showHour>\r\n";
            $rss .= "<showMinute>{$items['ShowMinute']}</showMinute>\r\n";
            $rss .= "<showSecond>{$items['ShowSecond']}</showSecond>\r\n";
            $rss .= "<isHot>{$items['IsHot']}</isHot>\r\n";
            $rss .= "<recLevel>{$items['RecLevel']}</recLevel>\r\n";


            //$rss .= "<content><![CDATA[{$items[$i]['content']}]]></content>\r\n";
            $rss .= "</item>\r\n";
        }

        $rss .= "</channel>\r\n";
        return $rss;
    }

    /**
     * 生成XML数据（只针对documentnews表）
     * @param string $channelTitle 频道标题
     * @param string $channelDescription 频道描述
     * @param string $channelLink 频道连接
     * @param string $language 语言
     * @param array $items 子项目数据
     * @param string $pubDate 发布时间
     * @param string $lastBuildDate 最后生成时间
     * @param string $generator 生成者
     * @param string $channelImgUrl 频道图标网址
     * @return string 数据字符串（xml）
     */
    public static function GenForActivity($channelTitle, $channelDescription, $channelLink, $language, $items = null, $pubDate = "", $lastBuildDate = "", $generator = "", $channelImgUrl = "") {
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
            $rss .= "<ActivityID>{$items[$i]['ActivityID']}</ActivityID>\r\n";
            $rss .= "<DocumentChannelID>{$items[$i]['DocumentChannelID']}</DocumentChannelID>\r\n";
            $rss .= "<ActivityClassID>{$items[$i]['ActivityClassID']}</ActivityClassID>\r\n";
            $rss .= "<ActivitySubject><![CDATA[{$items[$i]['ActivitySubject']}]]></ActivitySubject>\r\n";
            $rss .= "<ActivityType><![CDATA[{$items[$i]['ActivityType']}]]></ActivityType>\r\n";
            $rss .= "<UserID><![CDATA[{$items[$i]['UserID']}]]></UserID>\r\n";
            $rss .= "<UserName><![CDATA[{$items[$i]['UserName']}]]></UserName>\r\n";
            $rss .= "<ActivityIntro><![CDATA[{$items[$i]['ActivityIntro']}]]></ActivityIntro>\r\n";
            $rss .= "<PublishDate>{$items[$i]['PublishDate']}</PublishDate>\r\n";
            $rss .= "<CreateDate>{$items[$i]['CreateDate']}</CreateDate>\r\n";
            $rss .= "<BeginDate><![CDATA[{$items[$i]['BeginDate']}]]></BeginDate>\r\n";
            $rss .= "<EndDate><![CDATA[{$items[$i]['EndDate']}]]></EndDate>\r\n";
            $rss .= "<ApplyEndDate>{$items[$i]['ApplyEndDate']}</ApplyEndDate>\r\n";
            $rss .= "<ActivityAddress><![CDATA[{$items[$i]['ActivityAddress']}]]></ActivityAddress>\r\n";
            $rss .= "<ActivityFee><![CDATA[{$items[$i]['ActivityFee']}]]></ActivityFee>\r\n";
            $rss .= "<AssemblyAddress><![CDATA[{$items[$i]['AssemblyAddress']}]]></AssemblyAddress>\r\n";
            $rss .= "<ShowDate>{$items[$i]['ShowDate']}</ShowDate>\r\n";
            $rss .= "<Province><![CDATA[{$items[$i]['Province']}]]></Province>\r\n";
            $rss .= "<City><![CDATA[{$items[$i]['City']}]]></City>\r\n";
            $rss .= "<QQ><![CDATA[{$items[$i]['QQ']}]]></QQ>\r\n";
            $rss .= "<Contact><![CDATA[{$items[$i]['Contact']}]]></Contact>\r\n";
            $rss .= "<TitlePic><![CDATA[{$items[$i]['TitlePic']}]]></TitlePic>\r\n";
            $rss .= "<ActivityRequest><![CDATA[{$items[$i]['ActivityRequest']}]]></ActivityRequest>\r\n";
            $rss .= "<ActivityRemark><![CDATA[{$items[$i]['ActivityRemark']}]]></ActivityRemark>\r\n";
            $rss .= "<UserCountLimit><![CDATA[{$items[$i]['UserCountLimit']}]]></UserCountLimit>\r\n";
            $rss .= "<ApplyUserCount><![CDATA[{$items[$i]['ApplyUserCount']}]]></ApplyUserCount>\r\n";
            $rss .= "<JoinUserCount><![CDATA[{$items[$i]['JoinUserCount']}]]></JoinUserCount>\r\n";
            $rss .= "<RecLevel><![CDATA[{$items[$i]['RecLevel']}]]></RecLevel>\r\n";
            $rss .= "<Hit>{$items[$i]['Hit']}</Hit>\r\n";

            $rss .= "</item>\r\n";
        }

        $rss .= "</channel>\r\n</rss>";
        return $rss;
    }

    public static function GenUserAlbumForClient($picList, $items) {
        $rss = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\r\n";
        $rss .= "<rss version=\"2.0\">\r\n";
        $rss .= "<channel>\r\n";
        $rss .= "<title><![CDATA[{$items['UserAlbumTag']}]]></title>\r\n";
        $rss .= "<description><![CDATA[]]></description>\r\n";
        $rss .= "<link><![CDATA[]]></link>\r\n";
        $rss .= "<language><![CDATA[]]></language>\r\n";
        $rss .= "<item>\r\n";
        $rss .= "<UserAlbumID><![CDATA[{$items['UserAlbumID']}]]></UserAlbumID>\r\n";
        $rss .= " <category><![CDATA[{$items['UserAlbumTag']}]]></category>\r\n";
        $rss .= "<title><![CDATA[{$items['UserAlbumName']}]]></title>\r\n";
        $rss .= "<UserAlbumName><![CDATA[{$items['UserAlbumTag']}]]></UserAlbumName>\r\n";
        $rss .= "<pubDate><![CDATA[{$items['CreateDate']}]]></pubDate>\r\n";
        $rss .= "<description><![CDATA[{$items['UserAlbumIntro']}]]></description>\r\n";
        $rss .= "<State><![CDATA[{$items['State']}]]></State>\r\n";
        $rss .= "<author><![CDATA[{$items['NickName']}]]></author>\r\n";
        for ($j = 0; $j < count($picList); $j++) {
            $rss .= "<UserAlbumPic>\r\n";
            $rss .= "<UserAlbumPicUrl><![CDATA[{$picList[$j]['useralbumpicurl']}]]></UserAlbumPicUrl>\r\n";
            $rss .= "<UserAlbumPicThumbnailUrl><![CDATA[{$picList[$j]['useralbumpicthumbnailurl']}]]></UserAlbumPicThumbnailUrl>\r\n";
            $rss .= "<UserAlbumPicCompressUrl><![CDATA[{$picList[$j]['useralbumpiccompressurl']}]]></UserAlbumPicCompressUrl>\r\n";
            $rss .= "</UserAlbumPic>\r\n";
        }
        $rss .= "<image>\r\n";
        $rss .= "<title><![CDATA[]]></title> \r\n";
        $rss .= "<link><![CDATA[]]></link> \r\n";
        $rss .= "<url><![CDATA[{$items['UserAlbumPicUrlThumbnailUrl']}]]></url>\r\n";
        $rss .= "</image>\r\n";
        $rss .= "<SupporCount><![CDATA[{$items['SupporCount']}]]></SupporCount>\r\n";
        $rss .= "<HitCount><![CDATA[{$items['HitCount']}]]></HitCount>\r\n";
        $rss .= "<CommentCount><![CDATA[{$items['CommentCount']}]]></CommentCount>\r\n";
        $rss .= "<RecCount><![CDATA[{$items['RecCount']}]]></RecCount>\r\n";

        $rss .= "</item>\r\n";
        $rss .= "</channel>\r\n";
        $rss .= "</rss>";
        return $rss;
    }

}

?>
