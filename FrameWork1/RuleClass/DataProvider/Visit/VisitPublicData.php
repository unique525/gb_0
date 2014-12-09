<?php

/**
 * 访问统计数据类 前台
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Visit
 * @author hy
 */
class VisitData extends BasePublicData {

    /**
     * 增加新信息
     * @param int $siteId    站点ID
     * @param int $documentChannelId 频道ID号
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
     * @return int  返回执行结果
     */
    public function Create($siteId, $documentChannelId, $tableType, $tableId, $createDate, $visitTitle, $visitTag, $visitUrl, $ipAddress, $agent, $refDomain, $refUrl) {
        $tableName = parent::CreateAndGetTableName(self::TableName_Visit);
        $sql = "INSERT INTO $tableName (siteid,documentchannelid,tabletype,tableid,createdate,visittitle,visittag,visiturl,ipaddress,agent,refdomain,refurl) values (:siteid,:documentchannelid,:tabletype,:tableid,:createdate,:visittitle,:visittag,:visiturl,:ipaddress,:agent,:refdomain,:refurl)";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("SiteId", $siteId);
        $dataProperty->AddField("ChannelId", $documentChannelId);
        $dataProperty->AddField("TableType", $tableType);
        $dataProperty->AddField("TableId", $tableId);
        $dataProperty->AddField("createdate", $createDate);
        $dataProperty->AddField("VisitTitle", $visitTitle);
        $dataProperty->AddField("VisitTag", $visitTag);
        $dataProperty->AddField("VisitUrl", $visitUrl);
        $dataProperty->AddField("IpAddress", $ipAddress);
        $dataProperty->AddField("agent", $agent);
        $dataProperty->AddField("RefDomain", $refDomain);
        $dataProperty->AddField("RefUrl", $refUrl);
        $dbOperator = DBOperator::getInstance();
        $insertId = $dbOperator->LastInsertId($sql, $dataProperty);
        return $insertId;
    }

}

?>