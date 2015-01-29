<?php

/**
 * 访问统计数据类 前台
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Visit
 * @author hy
 */
class VisitPublicData extends BasePublicData {

    /**
     * 增加新信息
     * @param int $siteId    站点ID
     * @param int $channelId 频道ID号
     * @param int $tableType 表分类
     * @param int $tableId   表ID号
     * @param string $createDate    访问时间
     * @param string $visitTitle    标题
     * @param string $visitTag   标签
     * @param string $visitUrl  地址
     * @param string $ipAddress IP地址
     * @param string $agent 访问者浏览器
     * @param string $refDomain 来路跨地区
     * @param string $refUrl    来路地址
     * @param string $flagCookie  来路唯一标识符
     * @return int  返回执行结果
     */
    public function Create($siteId, $channelId, $tableType, $tableId, $createDate, $visitTitle, $visitTag, $visitUrl, $ipAddress, $agent, $refDomain, $refUrl, $flagCookie) {
        $tableName = parent::CreateAndGetTableName(self::TableName_Visit);
        $sql = "INSERT INTO $tableName (SiteId,ChannelId,TableType,TableId,CreateDate,VisitTitle,VisitTag,VisitUrl,IpAddress,Agent,RefDomain,RefUrl,FlagCookie) values (:SiteId,:ChannelId,:TableType,:TableId,:CreateDate,:VisitTitle,:VisitTag,:VisitUrl,:IpAddress,:Agent,:RefDomain,:RefUrl,:FlagCookie)";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("SiteId", $siteId);
        $dataProperty->AddField("ChannelId", $channelId);
        $dataProperty->AddField("TableType", $tableType);
        $dataProperty->AddField("TableId", $tableId);
        $dataProperty->AddField("CreateDate", $createDate);
        $dataProperty->AddField("VisitTitle", $visitTitle);
        $dataProperty->AddField("VisitTag", $visitTag);
        $dataProperty->AddField("VisitUrl", $visitUrl);
        $dataProperty->AddField("IpAddress", $ipAddress);
        $dataProperty->AddField("Agent", $agent);
        $dataProperty->AddField("RefDomain", $refDomain);
        $dataProperty->AddField("RefUrl", $refUrl);
        $dataProperty->AddField("FlagCookie", $flagCookie);
        $insertId = $this->dbOperator->LastInsertId($sql, $dataProperty);
        return $insertId;
    }

}

?>
