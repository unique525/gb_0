<?php

/**
 * 后台管理 操作日志 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Manage
 * @author zhangchi
 */
class ManageUserLogManageData extends BaseManageData {

    /**
     * 物理删除
     * @param int $manageUserLogId 后台管理员操作日志id
     * @return int 删除结果，大于0则成功
     */
    public function Delete($manageUserLogId) {
        $sql = "DELETE FROM " . self::TableName_ManageUserLog . " WHERE `" . self::TableId_ManageUserLog . "`=:" .
            self::TableId_ManageUserLog . "";
        $dataProperty = new DataProperty();
        $dataProperty->AddField(self::TableId_ManageUserLog, $manageUserLogId);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 新增操作日志
     * @param int $manageUserId 管理员id
     * @param string $manageUserName 管理员帐号
     * @param string $ipAddress IP地址
     * @param string $webAgent 浏览器类型
     * @param string $selfUrl 当前运行网址
     * @param string $refererUrl 来源网址
     * @param string $refererDomain 来源域名
     * @param int $userId 会员id
     * @param string $userName 会员帐号名
     * @param string $operateContent 操作内容
     * @return int 执行结果
     */
    public function Create($manageUserId, $manageUserName, $ipAddress, $webAgent, $selfUrl, $refererUrl, $refererDomain, $userId, $userName, $operateContent) {
        $sql = "INSERT INTO " . self::TableName_ManageUserLog . "
                  ( ManageUserId,
                    ManageUserName,
                    CreateDate,
                    OperateContent,
                    IpAddress,
                    WebAgent,
                    SelfUrl,
                    RefererDomain,
                    RefererUrl,
                    UserId,
                    UserName
                    )
                VALUES
                  (
                    :ManageUserId,
                    :ManageUserName,
                     now(),
                    :OperateContent,
                    :IpAddress,
                    :WebAgent,
                    :SelfUrl,
                    :RefererDomain,
                    :RefererUrl,
                    :UserId,
                    :UserName
                  );";

        $dataProperty = new DataProperty();
        $dataProperty->AddField("ManageUserId", $manageUserId);
        $dataProperty->AddField("ManageUserName", $manageUserName);
        $dataProperty->AddField("OperateContent", $operateContent);
        $dataProperty->AddField("IpAddress", $ipAddress);
        $dataProperty->AddField("WebAgent", $webAgent);
        $dataProperty->AddField("SelfUrl", $selfUrl);
        $dataProperty->AddField("RefererDomain", $refererDomain);
        $dataProperty->AddField("RefererUrl", $refererUrl);
        $dataProperty->AddField("UserId", $userId);
        $dataProperty->AddField("UserName", $userName);
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
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        //统计总数
        $sqlCount = "SELECT count(*) FROM
            " . self::tableName . " t, cst_adminuser d
            WHERE t.adminuserid=d.adminuserid  " . $searchSql;
        $allCount = $this->dbOperator->GetInt($sqlCount, $dataProperty);
        return $result;
    }

}

?>
