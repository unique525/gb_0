<?php

/**
 * 访问数据统计缓存数据类 后台
 * @category iCMS
 * @package  iCMS_FrameWork1_RuleClass_DataProvider_Visit
 * @author Liujunyi
 */
class VisitResultManageData extends BaseManageData {
    
    /**
     * 修改IsLocation值
     * @param string $tableName  对应表名
     * @param int $tableIdValue  对应表Id
     * @param int $isLocation    要修改IsLocation的值
     * @return int 返回修改后结果
     */
    public function ModifyIsLocation($tableName, $tableIdValue, $isLocation) {
        $dataProperty = new DataProperty();
        //$tableName = parent::CreateAndGetTableName(self::TableName_Visit_Result);
        $sql = "UPDATE $tableName SET IsLocation=:IsLocation WHERE " . self::TableId_Visit_Result . "=:" . self::TableId_Visit_Result . "";
        $dataProperty->AddField("IsLocation", $isLocation);
        $dataProperty->AddField(self::TableId_Visit_Result, $tableIdValue);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 更新统计数据
     * @param int $tableIdValue  对应表Id
     * @param string $createDate 时间
     * @param int $pvCount PV数
     * @param int $pvPercentage PV百分比
     * @param int $ipAddressCount IP数
     * @param int $ipAddressPercentage IP百分比
     * @return int 返回数据执行结果
     */
    public function ModifyCountData($tableIdValue, $createDate = "", $pvCount = 0, $pvPercentage = 0, $ipAddressCount = 0, $ipAddressPercentage = 0) {
        $dataProperty = new DataProperty();
        $sql = "UPDATE " . self::TableName_Visit_Result . " SET PvCount=:PvCount,PvPercentage=:PvPercentage,IpAddressCount=:IpAddressCount,IpAddressPercentage=:IpAddressPercentage,createdate=:createdate WHERE " . self::TableId_Visit_Result . "=:" . self::TableId_Visit_Result . "";
        $dataProperty->AddField("PvCount", $pvCount);
        $dataProperty->AddField("PvPercentage", $pvPercentage);
        $dataProperty->AddField("IpAddressCount", $ipAddressCount);
        $dataProperty->AddField("IpAddressPercentage", $ipAddressPercentage);
        $dataProperty->AddField("createdate", $createDate);
        $dataProperty->AddField(self::TableId_Visit_Result, $tableIdValue);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 新增数据
     * @param string $createDate 时间
     * @param int $siteId 站点ID
     * @param string $siteName 站点名称
     * @param string $siteUrl 站点URL
     * @param int $channelId 栏目ID
     * @param string $channelName 栏目名
     * @param int $tableType 新闻ID所在表类型
     * @param int $tableId 新闻ID
     * @param string $visitTitle 新闻标题
     * @param string $visitUrl 新闻URL
     * @param int $pvCount PV数
     * @param int $pvPercentage PV百分比
     * @param int $ipAddressCount IP数
     * @param int $ipAddressPercentage IP百分比
     * @param int $countType 查询类型
     * @param string $countTableName 表名
     * @param string $refDomain 来源域名
     * @return int 返回执行结果 
     */
    public function Create($createDate, $siteId = 0, $siteName = "", $siteUrl = "", $channelId = 0, $channelName = "", $tableType = 0, $tableId = 0, $visitTitle = "", $visitUrl = "", $pvCount = 0, $pvPercentage = 0, $ipAddressCount = 0, $ipAddressPercentage = 0, $countType = 0, $countTableName = "", $refDomain = "") {
        $sql = "INSERT INTO " . self::TableName_Visit_Result . " (SiteId,SiteName,SiteUrl,ChannelId,ChannelName,TableType,TableId,VisitTitle,VisitUrl,PvCount,PvPercentage,IpAddressCount,IpAddressPercentage,createdate,CountType,CountTableName,RefDomain) values (:SiteId,:SiteName,:SiteUrl,:ChannelId,:ChannelName,:TableType,:TableId,:VisitTitle,:VisitUrl,:PvCount,:PvPercentage,:IpAddressCount,:IpAddressPercentage,:createdate,:CountType,:CountTableName,:RefDomain)";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("SiteId", $siteId);
        $dataProperty->AddField("SiteName", $siteName);
        $dataProperty->AddField("SiteUrl", $siteUrl);
        $dataProperty->AddField("ChannelId", $channelId);
        $dataProperty->AddField("ChannelName", $channelName);
        $dataProperty->AddField("TableType", $tableType);
        $dataProperty->AddField("TableId", $tableId);
        $dataProperty->AddField("VisitTitle", $visitTitle);
        $dataProperty->AddField("VisitUrl", $visitUrl);
        $dataProperty->AddField("PvCount", $pvCount);
        $dataProperty->AddField("PvPercentage", $pvPercentage);
        $dataProperty->AddField("IpAddressCount", $ipAddressCount);
        $dataProperty->AddField("IpAddressPercentage", $ipAddressPercentage);
        $dataProperty->AddField("createdate", $createDate);
        $dataProperty->AddField("CountType", $countType);
        $dataProperty->AddField("CountTableName", $countTableName);
        $dataProperty->AddField("RefDomain", $refDomain);
        $dbOperator = DBOperator::getInstance();
        $insertId = $dbOperator->LastInsertId($sql, $dataProperty);
        return $insertId;
    }

    /**
     * 取得缓存数据列表详细信息
     * @param int $pageBegin 开始数
     * @param int $pageSize 每页显示数
     * @param int $allCount 总数
     * @param int $siteId 站点ID
     * @param int $channelId 栏目ID
     * @param int $tableType 新闻ID所在表类型
     * @param int $tableId 新闻ID
     * @param int $countType 查询类型
     * @param string $countTableName 查询表名
     * @return array 返回查询数字结果集
     */
    public function GetListPagerByCountType($pageBegin, $pageSize, &$allCount, $siteId = 0, $channelId = 0, $tableType = 0, $tableId = 0, $countType = 1, $countTableName = "") {
        $searchSql = "";
        $limitStr = "";
        $dataProperty = new DataProperty();
        if ($siteId > 0) {
            $searchSql .= " AND vr.SiteId=:SiteId";
            $dataProperty->AddField("SiteId", $siteId);
        }
        if ($channelId > 0) {
            $searchSql .= " AND vr.ChannelId=:ChannelId";
            $dataProperty->AddField("ChannelId", $channelId);
        }
        if ($tableId > 0) {
            $searchSql .= " AND vr.TableType=:TableType AND vr.TableId=:TableId";
            $dataProperty->AddField("TableType", $tableType);
            $dataProperty->AddField("TableId", $tableId);
        }

        if ($countType > 0) {
            $searchSql .= " AND vr.CountType=:CountType";
            $dataProperty->AddField("CountType", $countType);
        }

        if (strlen($countTableName) > 0) {
            $searchSql .= " AND vr.CountTableName=:CountTableName";
            $dataProperty->AddField("CountTableName", $countTableName);
        }

        if ($pageSize > 0) {
            $limitStr = "  LIMIT " . $pageBegin . "," . $pageSize . "";
        }
        $sql = "SELECT
            vr.VisitResultId,vr.SiteId,vr.SiteName,vr.RefDomain,vr.SiteUrl,vr.ChannelId,vr.ChannelName,vr.TableType,vr.TableId,vr.VisitTitle,vr.VisitUrl,vr.PvCount,vr.PvPercentage,vr.IpAddressCount,vr.IpAddressPercentage,vr.createdate
            FROM
            " . self::TableName_Visit_Result . " vr WHERE 1=1  " . $searchSql . $limitStr;
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->ReturnArray($sql, $dataProperty);
        //统计总数
        $sql = "SELECT count(*) FROM " . self::TableName_Visit_Result . " vr WHERE 1=1 " . $searchSql;
        $allCount = $dbOperator->ReturnInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 查询站点列表
     * @param string $tableName 对应表名
     * @param int $countType 查询类型
     * @return array 返回数据结果集
     */
    public function GetSiteIdList($tableName, $countType = 1) {
        $dataProperty = new DataProperty();
        $dbOperator = DBOperator::getInstance();
        $sql = "SELECT v.SiteId,v.SiteName,v.SiteUrl,sum(PvCount) as PvSum FROM " . self::TableName_Visit_Result . " v where v.CountTableName=:CountTableName and v.countType=:countType GROUP BY v.SiteId order by PvSum DESC ";
        $dataProperty->AddField("CountTableName", $tableName);
        $dataProperty->AddField("countType", $countType);
        $result = $dbOperator->ReturnArray($sql, $dataProperty);
        return $result;
    }

}

?>