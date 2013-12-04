<?php

/**
 * 后台管理员日志后台数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Manage
 * @author zhangchi
 */
class AdminUserLogManageData extends BaseManageData {
    
    /**
     * 表名
     */
    const tableName = "cst_adminuserlog";

    /**
     * 表关键字段名
     */
    const tableIdName = "AdminUserLogId";

    /**
     * 物理删除
     * @param int $adminUserLogId 后台管理员操作日志id
     * @return int 删除结果，大于0则成功
     */
    public function Delete($adminUserLogId) {
        $sql = "DELETE FROM " . self::tableName . " WHERE `" . self::tableIdName . "`=:" . self::tableIdName . "";
        $dataProperty = new DataProperty();
        $dataProperty->AddField(self::tableIdName, $adminUserLogId);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 插入记录
     * @param type $operateContent
     * @return type
     */
    public function Insert($operateContent) {
        $sql = "INSERT INTO " . self::tableName . " (adminuserid,createdate,operatecontent,ipaddress,agent,refdomain,refurl) values (:adminuserid,:createdate,:operatecontent,:ipaddress,:agent,:refdomain,:refurl)";
        $adminUserId = Control::GetAdminUserId();
        $createDate = date("Y-m-d H:i:s", time());
        $ipAddress = Control::GetIp();
        $agent = Control::GetOs() . " and " . Control::GetBrowser();
        $refererUrl = $_SERVER["HTTP_REFERER"];
        $nowUrl = $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
        $refererDomain = strtolower(preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $refererUrl));
        $dataProperty = new DataProperty();
        $dataProperty->AddField("adminuserid", $adminUserId);
        $dataProperty->AddField("createdate", $createDate);
        $dataProperty->AddField("operatecontent", $operateContent . ";ms:" . floor(microtime() * 1000) . " nowurl:" . $nowUrl);
        $dataProperty->AddField("ipaddress", $ipAddress);
        $dataProperty->AddField("agent", $agent);
        $dataProperty->AddField("refdomain", $refererDomain);
        $dataProperty->AddField("refurl", $refererUrl);
        $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        return $result;
    }

    /**
     * 返回分页数据集
     * @param int $pageBegin 起始页
     * @param int $pageSize 页大小
     * @param int $allCount 总数
     * @param string $searchKey 查询条件
     * @return array 结果数据集
     */
    public function GetListPager($pageBegin, $pageSize, &$allCount, $searchKey) {
        $searchSql = "";
        $dataProperty = new DataProperty();
        if (strlen($searchKey) > 0 && $searchKey != "undefined" && $searchKey != "[object]") {
            $searchSql .= " AND (t.operatecontent like :searchkey1 OR t.adminuserid=:searchkey2 OR t.ipaddress like :searchkey3)";
            $dataProperty->AddField("searchkey1", "%" . $searchKey . "%");
            $dataProperty->AddField("searchkey2", $searchKey);
            $dataProperty->AddField("searchkey3", "%" . $searchKey . "%");
        }
        $sql = "SELECT
            t.adminuserlogid,t.operatecontent as operatecontentall,t.createdate,t.ipaddress,LEFT(t.operatecontent,50) as operatecontent,
            d.adminusername
            FROM
            " . self::tableName . " t, cst_adminuser d
            WHERE t.adminuserid=d.adminuserid  " . $searchSql . " order by t.createdate desc LIMIT " . $pageBegin . "," . $pageSize . "";
        $result = $this->dbOperator->ReturnArray($sql, $dataProperty);
        //统计总数
        $sqlCount = "SELECT count(*) FROM
            " . self::tableName . " t, cst_adminuser d
            WHERE t.adminuserid=d.adminuserid  " . $searchSql;
        $allCount = $this->dbOperator->ReturnInt($sqlCount, $dataProperty);
        return $result;
    }

}

?>
