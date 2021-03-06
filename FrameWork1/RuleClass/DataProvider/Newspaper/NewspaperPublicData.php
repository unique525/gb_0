<?php

/**
 * 前台 电子报 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Newspaper
 * @author zhangchi
 */
class NewspaperPublicData extends BasePublicData
{

    /**
     * 创建电子报（导入使用）
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param string $newsPaperTitle 电子报标题
     * @param string $publishDate 发布时间
     * @return int 返回电子报id
     */
    public function CreateForImport($siteId, $channelId, $newsPaperTitle, $publishDate)
    {
        $newspaperId = self::GetNewspaperIdByPublishDateForImport($publishDate);
        if ($newspaperId <= 0) {
            $sql = "INSERT INTO " . self::TableName_Newspaper . "

                        (
                        SiteId,
                        ChannelId,
                        NewspaperTitle,
                        CreateDate,
                        PublishDate
                        ) VALUES
                        (
                        :SiteId,
                        :ChannelId,
                        :NewspaperTitle,
                         now(),
                        :PublishDate
                        );";

            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);
            $dataProperty->AddField("ChannelId", $channelId);
            $dataProperty->AddField("NewspaperTitle", $newsPaperTitle);
            $dataProperty->AddField("PublishDate", $publishDate);
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);

        } else {
            $result = $newspaperId;
        }

        return $result;
    }

    /**
     * 根据发布时间返回电子报id
     * @param string $publishDate 发布时间
     * @return int
     */
    public function GetNewspaperIdByPublishDateForImport($publishDate)
    {
        $result = -1;

        if (strlen($publishDate) > 0) {
            $sql = "SELECT NewspaperId FROM "
                . self::TableName_Newspaper . "
                 WHERE PublishDate=:PublishDate AND State<100;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("PublishDate", $publishDate);
            $result = $this->dbOperator->GetInt($sql, $dataProperty);
        }

        return $result;
    }

    /**
     * 取得最新的一条记录id
     * @param int $channelId
     * @return int
     */
    public function GetNewspaperIdOfNew($channelId)
    {
        $result = -1;
        if ($channelId > 0) {
            $sql = "SELECT NewspaperId FROM " . self::TableName_Newspaper . "

                WHERE ChannelId = :ChannelId

                ORDER BY PublishDate DESC,NewspaperId DESC LIMIT 1;

                ";

            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);
            $result = $this->dbOperator->GetInt($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 取得最新的一条记录id
     * @param int $channelId
     * @param string $publishDate
     * @return int
     */
    public function GetNewspaperIdByPublishDate($channelId, $publishDate)
    {
        $result = -1;
        if ($channelId > 0) {
            $sql = "SELECT NewspaperId FROM " . self::TableName_Newspaper . "
                WHERE ChannelId = :ChannelId
                AND PublishDate>=:PublishDate
                ORDER BY PublishDate,NewspaperId LIMIT 1;
                ";

            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);
            $dataProperty->AddField("PublishDate", $publishDate);
            $result = $this->dbOperator->GetInt($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 取得一条信息
     * @param int $newspaperId 电子报版面id
     * @return array 电子报版面信息数组
     */
    public function GetOne($newspaperId)
    {
        $sql = "SELECT *
                FROM " . self::TableName_Newspaper . "
                WHERE " . self::TableId_Newspaper . "=:" . self::TableId_Newspaper . ";";
        $dataProperty = new DataProperty();
        $dataProperty->AddField(self::TableId_Newspaper, $newspaperId);
        $result = $this->dbOperator->GetArray($sql, $dataProperty);
        return $result;
    }


    /**
     * 取得发布时间
     * @param int $newspaperId 电子报id
     * @param bool $withCache 是否从缓冲中取
     * @return int 发布时间
     */
    public function GetPublishDate($newspaperId, $withCache)
    {
        $result = -1;
        if ($newspaperId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'newspaper_data';
            $cacheFile = 'newspaper_get_publish_date.cache_' . $newspaperId . '';
            $sql = "SELECT PublishDate FROM " . self::TableName_Newspaper . " WHERE NewspaperId=:NewspaperId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperId", $newspaperId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);

            $result = Format::DateStringToDate($result);
        }
        return $result;
    }

    /**
     * 取得所属频道id
     * @param int $newspaperId 电子报id
     * @param bool $withCache 是否从缓冲中取
     * @return int 所属频道id
     */
    public function GetChannelId($newspaperId, $withCache)
    {
        $result = -1;
        if ($newspaperId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'newspaper_data';
            $cacheFile = 'newspaper_get_channel_id.cache_' . $newspaperId . '';
            $sql = "SELECT ChannelId FROM " . self::TableName_Newspaper . " WHERE NewspaperId=:NewspaperId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperId", $newspaperId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }


} 