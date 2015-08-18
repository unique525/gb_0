<?php
/**
 * 客户端 电子报 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Newspaper
 * @author zhangchi
 */
class NewspaperClientData extends BaseClientData {
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
}