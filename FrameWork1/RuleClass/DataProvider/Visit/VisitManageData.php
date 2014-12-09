<?php

/**
 * 访问统计数据类 后台
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Visit
 * @author hy
 */
class VisitManageData extends BaseManageData {

    /**
     * 修改isLocation值
     * @param string $tableName 表名
     * @param int $tableIdValue  关键字段值
     * @param <type> $isLocation    要修改isLocation的值
     * @return int 返回修改后结果
     */
    public function ModifyIsLocation($tableName, $tableIdValue, $isLocation) {
        $dataProperty = new DataProperty();
        //$tableName = parent::CreateAndGetTableName(self::tableName);
        $sql = "UPDATE $tableName SET isLocation=:isLocation WHERE " . self::TableName_Visit . "=:" . self::TableId_Visit . "";
        $dataProperty->AddField("isLocation", $isLocation);
        $dataProperty->AddField(self::TableId_Visit, $tableIdValue);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 修改IP地址信息
     * @param string $tableName 表名
     * @param int $tableIdValue
     * @param string $Country   国
     * @param string $province 省
     * @param string $city  城市
     * @param string $operators 运营商
     * @return int 返回执行标识值
     */
    public function ModifyIpLocation($tableName, $tableIdValue, $Country, $province, $city, $operators) {
        $dataProperty = new DataProperty();
        $sql = "UPDATE " . $tableName . " SET Country=:Country,Province=:Province,City=:City,Operators=:Operators WHERE " . self::TableId_Visit . "=:" . self::TableId_Visit . "";

        $dataProperty->AddField("Country", $Country);
        $dataProperty->AddField("Province", $province);
        $dataProperty->AddField("City", $city);
        $dataProperty->AddField("Operators", $operators);
        $dataProperty->AddField(self::TableId_Visit, $tableIdValue);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 按isLocation到记录
     * @param string $tableName 表名
     * @param int $isLocation    映射标识
     * @return array 返回一维数组
     */
    public function GetOneListByIsLocation($tableName, $isLocation = 0) {
        $dataProperty = new DataProperty();
        $dbOperator = DBOperator::getInstance();
        $sql = "SELECT v.visitId,v.IpAddress FROM " . $tableName . " v WHERE v.isLocation=:isLocation Limit 1 ";
        $dataProperty->AddField("isLocation", $isLocation);
        $result = $dbOperator->ReturnRow($sql, $dataProperty);
        return $result;
    }

    /**
     * 按站点查询
     * @param int $pageBegin    起始记录行
     * @param int $pageSize 页大小
     * @param int $allCount 记录总数
     * @param string $searchKey 查询字符
     * @param string $tableName 表名
     * @param int $searchDate   时间间隔
     * @param string $beginDate     起始时间
     * @param string $endDate       结束时间
     * @param int $siteId  站点ID号
     * @return array  返回数据结果集
     * @return <type>
     */
    public function GetCountBySiteId($pageBegin, $pageSize, &$allCount, $searchKey, $tableName, $searchDate = 0, $beginDate = "", $endDate = "", $siteId = 0) {
        $dataProperty = new DataProperty();
        $dbOperator = DBOperator::getInstance();
        $searchSql = "";
        $limitStr = "";
        if (strlen($searchKey) > 0 && $searchKey != "undefined") {
            $searchSql .= " AND (v.VisitTitle like :searchKey1 OR v.VisitTag like :searchKey2 OR v.VisitUrl like :searchKey3)";
            $dataProperty->AddField("searchKey1", "%" . $searchKey . "%");
            $dataProperty->AddField("searchKey2", "%" . $searchKey . "%");
            $dataProperty->AddField("searchKey3", "%" . $searchKey . "%");
        }

        if (strlen($beginDate) > 0) {
            $searchSql .= " AND v.CreateDate>='" . $beginDate . "'";
        }
        if (strlen($endDate) > 0) {
            $searchSql .= " AND v.CreateDate<'" . $endDate . " 24:00:00'";
        }

        //按时间间隔查询 if (!empty($username) && ) {
        //UNIX_TIMESTAMP(sysdate())-UNIX_TIMESTAMP(CreateDate)<=2592000
        if ($searchDate > 0) {
            $searchSql .= " AND UNIX_TIMESTAMP(sysdate())-UNIX_TIMESTAMP(v.CreateDate)<= " . $searchDate;
        }

        if ($pageSize > 0) {
            $limitStr = " LIMIT " . $pageBegin . "," . $pageSize . "";
        }
        if ($siteId > 0) {
            $sql = "SELECT COUNT(*) AS PvCount,v.SiteId
,(SELECT SiteName FROM cst_site WHERE SiteId=v.SiteId)  AS SiteName
,(SELECT SiteUrl FROM cst_site WHERE SiteId=v.SiteId) AS SiteUrl
 FROM " . $tableName . " v WHERE v.SiteId=:SiteId " . $searchSql . $limitStr;
            $sqlCount = "SELECT COUNT(DISTINCT v.SiteId) FROM " . $tableName . " v WHERE v.SiteId=:SiteId " . $searchSql;
            $dataProperty->AddField("SiteId", $siteId);
        } else {
            $sql = "SELECT COUNT(*) AS CountSiteId,v.SiteId
 ,(SELECT SiteName FROM cst_site WHERE SiteId=v.SiteId)  AS SiteName  
 ,(SELECT SiteUrl FROM cst_site WHERE SiteId=v.SiteId)  AS SiteUrl
 FROM " . $tableName . " v WHERE 1=1 " . $searchSql . " GROUP BY v.SiteId ORDER BY CountSiteId DESC " . $limitStr;
            $sqlCount = "SELECT COUNT(DISTINCT v.SiteId) FROM " . $tableName . " v WHERE 1=1 " . $searchSql;
        }
        $result = $dbOperator->ReturnArray($sql, $dataProperty);
        $allCount = $dbOperator->ReturnInt($sqlCount, $dataProperty);
        return $result;
    }

    /**
     * 按频道ID统计 
     * @param int $pageBegin    起始记录行
     * @param int $pageSize 页大小
     * @param int $allCount 记录总数
     * @param string $searchKey 查询字符
     * @param string $tableName 表名
     * @param int $searchDate   时间间隔
     * @param string $beginDate     起始时间
     * @param string $endDate       结束时间
     * @param int $siteId  站点ID号
     * @param int $channelId    频道ID号  
     * @return array  返回数据结果集
     */
    public function GetCountByChannelId($pageBegin, $pageSize, &$allCount, $searchKey, $tableName, $searchDate = 0, $beginDate = "", $endDate = "", $siteId = 0, $channelId = 0) {
        $dataProperty = new DataProperty();
        $dbOperator = DBOperator::getInstance();
        $searchSql = "";
        $limitStr = "";
        if (!empty($searchKey) && strlen($searchKey) > 0 && $searchKey != "undefined") {
            $searchSql .= " AND (v.VisitTitle like :searchKey1 OR v.VisitTag like :searchKey2 OR v.VisitUrl like :searchKey3)";
            $dataProperty->AddField("searchKey1", "%" . $searchKey . "%");
            $dataProperty->AddField("searchKey2", "%" . $searchKey . "%");
            $dataProperty->AddField("searchKey3", "%" . $searchKey . "%");
        }
        if ($siteId > 0) {
            $searchSql .= " AND v.SiteId=:SiteId";
            $dataProperty->AddField("SiteId", $siteId);
        }

        if ($channelId > 0) {
            $searchSql .= " AND v.ChannelId=:ChannelId";
            $dataProperty->AddField("ChannelId", $channelId);
        }
        if (strlen($beginDate) > 0) {
            $searchSql .= " AND v.CreateDate>='" . $beginDate . "'";
        }
        if (strlen($endDate) > 0) {
            $searchSql .= " AND v.CreateDate<'" . $endDate . " 24:00:00'";
        }
        //按时间间隔查询
        if ($searchDate > 0) {
            $searchSql .= " AND UNIX_TIMESTAMP(sysdate())-UNIX_TIMESTAMP(v.CreateDate)<= " . $searchDate;
        }

        if ($pageSize > 0) {
            $limitStr = " LIMIT " . $pageBegin . "," . $pageSize . "";
        }
        $sql = "SELECT COUNT(*) AS CountSiteId,v.SiteId,v.ChannelId
,(SELECT ChannelName FROM cst_channel WHERE ChannelId=v.ChannelId) AS ChannelName
 FROM " . $tableName . " v WHERE 1=1 " . $searchSql . " GROUP BY v.ChannelId ORDER BY CountSiteId DESC " . $limitStr;
        $sqlCount = "SELECT COUNT(DISTINCT v.ChannelId) FROM " . $tableName . " v WHERE 1=1 " . $searchSql;

        $result = $dbOperator->ReturnArray($sql, $dataProperty);
        $allCount = $dbOperator->ReturnInt($sqlCount, $dataProperty);
        return $result;
    }

    /**
     * 搂文档进行统计
     * @param int $pageBegin    起始记录行
     * @param int $pageSize 页大小
     * @param int $allCount 记录总数
     * @param string $searchKey 查询字符
     * @param string $tableName 表名
     * @param int $searchDate   时间间隔
     * @param string $beginDate     起始时间
     * @param string $endDate       结束时间
     * @param int $siteId  站点ID号
     * @param int $channelId    频道ID号
     * @param int $tableType  对应表类型
     * @param int $tableId  对应表ID
     * @param int $top  取单记录数
     * @return array  返回一维数组
     */
    public function GetCountByDocument($pageBegin, $pageSize, &$allCount, $searchKey, $tableName, $searchDate = 0, $beginDate = "", $endDate = "", $siteId = 0, $channelId = 0, $tableType = 0, $tableId = 0, $top = 0) {
        $dataProperty = new DataProperty();
        $dbOperator = DBOperator::getInstance();
        $searchSql = "";
        $limitStr = "";
        if (strlen($searchKey) > 0 && $searchKey != "undefined") {
            $searchSql .= " AND ( v.VisitTitle like :searchKey1 OR v.VisitTag like :searchKey2 OR v.VisitUrl like :searchKey3 OR v.TableId like :searchKey4)";
            $dataProperty->AddField("searchKey1", "%" . $searchKey . "%");
            $dataProperty->AddField("searchKey2", "%" . $searchKey . "%");
            $dataProperty->AddField("searchKey3", "%" . $searchKey . "%");
            $dataProperty->AddField("searchKey4", "%" . $searchKey . "%");
        }
        if ($channelId > 0) {
            $searchSql .= " AND v.ChannelId=:ChannelId";
            $dataProperty->AddField("ChannelId", $channelId);
        }
        if (strlen($beginDate) > 0) {
            $searchSql .= " AND v.CreateDate>='" . $beginDate . "'";
        }
        if (strlen($endDate) > 0) {
            $searchSql .= " AND v.CreateDate<'" . $endDate . " 24:00:00'";
        }
        //按时间间隔查询
        if ($searchDate > 0) {
            $searchSql .= " AND UNIX_TIMESTAMP(sysdate())-UNIX_TIMESTAMP(v.CreateDate)<= " . $searchDate;
        }

        if ($tableType > 0) {
            $searchSql .= " AND v.TableType=:TableType";
            $dataProperty->AddField("TableType", $tableType);
        }
        //如设置单取则单取优先
        if ($top > 0) {
            $limitStr = " LIMIT 0," . $top . "";
        } else {
            if ($pageSize > 0) {
                $limitStr = " LIMIT " . $pageBegin . "," . $pageSize . "";
            }
        }
        //查询具体某条稿子
        if ($tableId > 0) {
            $searchSql .= " AND v.TableId=:TableId";
            $dataProperty->AddField("TableId", $tableId);
        } else {
            $searchSql .= " AND v.TableId>0";
        }
        if ($siteId > 0) {
            $sql = "SELECT COUNT(*) AS CountSiteId,v.TableType,v.TableId,v.VisitUrl,v.VisitTitle FROM " . $tableName . " v WHERE v.SiteId=:SiteId " . $searchSql . " GROUP BY v.TableId ORDER BY CountSiteId DESC " . $limitStr;
            $dataProperty->AddField("SiteId", $siteId);
        } else {
            $sql = "SELECT COUNT(*) AS CountSiteId,v.TableType,v.SiteId,v.TableId,v.VisitUrl,v.VisitTitle FROM " . $tableName . " v WHERE 1=1 " . $searchSql . " GROUP BY v.TableId ORDER BY CountSiteId DESC " . $limitStr;
        }
        $sqlCount = "SELECT COUNT(DISTINCT v.TableId) FROM " . $tableName . " v WHERE 1=1 " . $searchSql;

        $result = $dbOperator->ReturnArray($sql, $dataProperty);
        $allCount = $dbOperator->ReturnInt($sqlCount, $dataProperty);
        return $result;
    }

    /**
     * 按文档访问详细统计列表
     * @param int $pageBegin    起始记录行
     * @param int $pageSize 页大小
     * @param int $allCount 记录总数
     * @param string $tableName 表名
     * @param int $tableType  对应表类型
     * @param int $tableId  对应表ID
     * @return <type> 
     */
    public function GetListByDocument($pageBegin, $pageSize, &$allCount, $tableName, $tableType = 0, $tableId = 0) {
        $dataProperty = new DataProperty();
        $dbOperator = DBOperator::getInstance();
        $searchSql = "";
        if ($tableType > 0) {
            $searchSql .= " AND v.TableType=:TableType";
            $dataProperty->AddField("TableType", $tableType);
        }

        //查询具体某条稿子
        if ($tableId > 0) {
            $searchSql .= " AND v.TableId=:TableId";
            $dataProperty->AddField("TableId", $tableId);
        } else {
            $searchSql .= " AND v.TableId>0";
        }
        $sql = "SELECT v.CreateDate,v.IpAddress,v.VisitTitle,v.RefUrl,v.Country,v.province,v.city,v.operators FROM " . $tableName . " v WHERE 1=1 " . $searchSql . "  ORDER BY " . self::TableId_Visit . " DESC LIMIT " . $pageBegin . "," . $pageSize . "";
        $sqlCount = "SELECT COUNT(*) FROM " . $tableName . " v WHERE 1=1 " . $searchSql;

        $result = $dbOperator->ReturnArray($sql, $dataProperty);
        $allCount = $dbOperator->ReturnInt($sqlCount, $dataProperty);
        return $result;
    }

    /**
     * 搂来路域名进行统计
     * @param int $pageBegin    起始记录行
     * @param int $pageSize 页大小
     * @param int $allCount 记录总数
     * @param string $searchKey 查询字符
     * @param string $tableName 表名
     * @param int $searchDate   时间间隔
     * @param string $beginDate     起始时间
     * @param string $endDate       结束时间
     * @param int $siteId  站点ID号
     * @param int $channelId    频道ID号
     * @param int $tableType  对应表类型
     * @param int $tableId  文档ID号
     * @param int $top  取单记录数
     * @return array  返回数据结果集
     */
    public function GetCountBySource($pageBegin, $pageSize, &$allCount, $searchKey, $tableName, $searchDate = 0, $beginDate = "", $endDate = "", $siteId = 0, $channelId = 0, $tableType = 0, $tableId = 0, $top = 0) {
        $dataProperty = new DataProperty();
        $dbOperator = DBOperator::getInstance();
        $searchSql = "";
        $limitStr = "";
        if (strlen($searchKey) > 0 && $searchKey != "undefined") {
            $searchSql .= " AND (v.VisitTitle like :searchKey1 OR v.VisitTag like :searchKey2 OR v.VisitUrl like :searchKey3)";
            $dataProperty->AddField("searchKey1", "%" . $searchKey . "%");
            $dataProperty->AddField("searchKey2", "%" . $searchKey . "%");
            $dataProperty->AddField("searchKey3", "%" . $searchKey . "%");
        }
        if ($siteId > 0) {
            $searchSql .= " AND v.SiteId=:SiteId";
            $dataProperty->AddField("SiteId", $siteId);
        }

        if ($channelId > 0) {
            $searchSql .= " AND v.ChannelId=:ChannelId";
            $dataProperty->AddField("ChannelId", $channelId);
        }
        if (strlen($beginDate) > 0) {
            $searchSql .= " AND v.CreateDate>='" . $beginDate . "'";
        }
        if (strlen($endDate) > 0) {
            $searchSql .= " AND v.CreateDate<'" . $endDate . " 24:00:00'";
        }
        //按时间间隔查询
        if ($searchDate > 0) {
            $searchSql .= " AND UNIX_TIMESTAMP(sysdate())-UNIX_TIMESTAMP(v.CreateDate)<= " . $searchDate;
        }

        //查询具体某条稿子
        if ($tableId > 0) {
            $searchSql .= " AND v.TableType=:TableType AND v.TableId=:TableId";
            $dataProperty->AddField("TableType", $tableType);
            $dataProperty->AddField("TableId", $tableId);
        } else {
            $searchSql .= " AND v.TableId>0";
        }
        //如设置单取则单取优先
        if ($top > 0) {
            $limitStr = " LIMIT 0," . $top . "";
        } else {
            if ($pageSize > 0) {
                $limitStr = " LIMIT " . $pageBegin . "," . $pageSize . "";
            }
        }
        $sql = "SELECT COUNT(*) AS CountSiteId,v.SiteId,v.TableId,v.RefDomain FROM " . $tableName . " v WHERE 1=1 " . $searchSql . " GROUP BY v.RefDomain ORDER BY CountSiteId DESC " . $limitStr;
        $sqlCount = "SELECT COUNT(DISTINCT v.RefDomain) FROM " . $tableName . " v WHERE 1=1 " . $searchSql;

        $result = $dbOperator->ReturnArray($sql, $dataProperty);
        $allCount = $dbOperator->ReturnInt($sqlCount, $dataProperty);
        return $result;
    }

    /**
     * 按用户统计发稿
     * @param string $searchKey 查询字符
     * @param string $tableName 表名
     * @param string $beginDate     起始时间
     * @param string $endDate       结束时间
     * @param int $adminUserGroupId  用户所在组
     * @param int $adminUserId   用户账号ID
     * @param int $tableType  对应表类型
     * @return int  返回记录数
     */
    public function GetCountByUser($searchKey, $tableName, $beginDate = "", $endDate = "", $adminUserGroupId = 0, $adminUserId = 0, $tableType = 1) {
        $dataProperty = new DataProperty();
        $dbOperator = DBOperator::getInstance();
        $searchSql = "";
        if (strlen($searchKey) > 0 && $searchKey != "undefined") {
            $searchSql .= " AND (v.TableId like :TableId OR v.VisitTitle like :searchKey1 OR v.VisitTag like :searchKey2 OR v.VisitUrl like :searchKey3)";
            $dataProperty->AddField("TableId", $searchKey);
            $dataProperty->AddField("searchKey1", "%" . $searchKey . "%");
            $dataProperty->AddField("searchKey2", "%" . $searchKey . "%");
            $dataProperty->AddField("searchKey3", "%" . $searchKey . "%");
        }
        if (strlen($beginDate) > 1) {
            $searchSql .= " AND v.CreateDate>='" . $beginDate . "'";
        }
        if (strlen($endDate) > 1) {
            $searchSql .= " AND v.CreateDate<'" . $endDate . " 24:00:00'";
        }

        if ($tableType == 1) {
            if ($adminUserId > 0) {
                $searchSql .= " AND v.TableId IN (SELECT DocumentNewsID FROM cst_document_news WHERE ManageUserId=:ManageUserId";
                $dataProperty->AddField("ManageUserId", $adminUserId);
                if (strlen($beginDate) > 1) {
                    $searchSql .= " AND CreateDate>='" . $beginDate . "'";
                }
                if (strlen($endDate) > 1) {
                    $searchSql .= " AND v.CreateDate<'" . $endDate . " 24:00:00'";
                }
                $searchSql .= ")";
            } else {
                if ($adminUserGroupId > 0) {
                    $searchSql .= " AND v.TableId IN (SELECT DocumentNewsId FROM cst_document_news WHERE ManageUserId IN (SELECT ManageUserId FROM cst_manage_user WHERE ManageUserGroupId=:ManageUserGroupId)";
                    $dataProperty->AddField("ManageUserGroupId", $adminUserGroupId);
                    if (strlen($beginDate) > 1) {
                        $searchSql .= " AND CreateDate>='" . $beginDate . "'";
                    }
                    if (strlen($endDate) > 1) {
                        $searchSql .= " AND v.CreateDate<'" . $endDate . " 24:00:00'";
                    }
                    $searchSql .= ")";
                }
            }
        } elseif ($tableType == 2) {
            if ($adminUserId > 0) {
                $searchSql .= " AND v.TableId IN (SELECT DocumentNewsID FROM cst_document_news WHERE ManageUserId=:ManageUserId";
                $dataProperty->AddField("ManageUserId", $adminUserId);
                if (strlen($beginDate) > 1) {
                    $searchSql .= " AND CreateDate>='" . $beginDate . "'";
                }
                if (strlen($endDate) > 1) {
                    $searchSql .= " AND v.CreateDate<'" . $endDate . " 24:00:00'";
                }
                $searchSql .= ")";
            } else {
                if ($adminUserGroupId > 0) {
                    $searchSql .= " AND v.TableId IN (SELECT DocumentNewsId FROM cst_document_news WHERE ManageUserId IN (SELECT ManageUserId FROM cst_manage_user WHERE ManageUserGroupId=:ManageUserGroupId)";
                    $dataProperty->AddField("ManageUserGroupId", $adminUserGroupId);
                    if (strlen($beginDate) > 1) {
                        $searchSql .= " AND CreateDate>='" . $beginDate . "'";
                    }
                    if (strlen($endDate) > 1) {
                        $searchSql .= " AND v.CreateDate<'" . $endDate . " 24:00:00'";
                    }
                    $searchSql .= ")";
                }
            }
        }
        $sql = "SELECT COUNT(v.TableId) FROM " . $tableName . " v WHERE 1=1 " . $searchSql;

        $result = $dbOperator->ReturnInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 按IP地址分析进行统计
     * @param int $pageBegin    起始数
     * @param int $pageSize 每页显示数
     * @param int $allCount 总数
     * @param string $searchKey 查询字符
     * @param string $tableName 表名
     * @param int $searchDate   时间间隔
     * @param string $beginDate     起始时间
     * @param string $endDate       结束时间
     * @param int $searchType    统计归组 0为按Country(国)进行统计，1为Province(省)按进行统计 2为按City(市)进行统计
     * @param string $Country 国家
     * @param string $province 省份
     * @param string $city 城市
     * @param int $siteId  站点ID号
     * @param int $channelId    频道ID号
     * @param int $tableId  对应表ID号
     * @return array  返回记录
     */
    public function GetCountByIpLocation($pageBegin, $pageSize, &$allCount, $searchKey, $tableName, $searchDate = 0, $beginDate = "", $endDate = "", $searchType = 0, $Country = "", $province = "", $city = "", $siteId = 0, $channelId = 0, $tableId = 0) {
        $dataProperty = new DataProperty();
        $dbOperator = DBOperator::getInstance();
        $searchSql = "";
        if (!empty($searchKey) && strlen($searchKey) > 0 && $searchKey != "undefined") {
            $searchSql .= " AND (v.VisitTitle like :searchKey1 OR v.VisitTag like :searchKey2 OR v.VisitUrl like :searchKey3)";
            $dataProperty->AddField("searchKey1", "%" . $searchKey . "%");
            $dataProperty->AddField("searchKey2", "%" . $searchKey . "%");
            $dataProperty->AddField("searchKey3", "%" . $searchKey . "%");
        }

        //按频道ID查询
        if ($channelId > 0) {
            $searchSql .= " AND v.ChannelId=:ChannelId";
            $dataProperty->AddField("ChannelId", $channelId);
        } else {
            if ($siteId > 0) {
                $searchSql .= " AND v.SiteId=:SiteId";
                $dataProperty->AddField("SiteId", $siteId);
            }
        }

        if (strlen($beginDate) > 0) {
            $searchSql .= " AND v.CreateDate>='" . $beginDate . "'";
        }
        if (strlen($endDate) > 0) {
            $searchSql .= " AND v.CreateDate<'" . $endDate . " 24:00:00'";
        }
        //按时间间隔查询
        if ($searchDate > 0) {
            $searchSql .= " AND UNIX_TIMESTAMP(sysdate())-UNIX_TIMESTAMP(v.CreateDate)<= " . $searchDate;
        }

        //查询具体某条稿子
        if ($tableId > 0) {
            $searchSql .= " AND v.TableId=:TableId";
            $dataProperty->AddField("TableId", $tableId);
        }
        //国家
        if (!empty($Country) && strlen($Country) > 0 && $Country != "undefined") {
            $searchSql .= " AND v.Country=:Country";
            $dataProperty->AddField("Country", $Country);
        }
        //按省级
        if (!empty($province) && strlen($province) > 0 && $province != "undefined") {
            $searchSql .= " AND v.province=:province";
            $dataProperty->AddField("province", $province);
        }
        //城市
        if (!empty($city) && strlen($city) > 0 && $city != "undefined") {
            $searchSql .= " AND v.city=:city";
            $dataProperty->AddField("city", $city);
        }
        $sql="";
        $sqlCount="";
        if ($searchType == 0) {     //按Country(国际)进行统计
            $sql = "SELECT COUNT(*) AS CountSiteId,v.SiteId,v.RefDomain,v.Country AS name FROM " . $tableName . " v WHERE 1=1 " . $searchSql . " GROUP BY v.Country ORDER BY CountSiteId DESC LIMIT " . $pageBegin . "," . $pageSize . "";
            $sqlCount = "SELECT COUNT(DISTINCT v.Country) FROM " . $tableName . " v WHERE 1=1 " . $searchSql;
        } elseif ($searchType == 1) {   //按Province(省)进行统计
            $sql = "SELECT COUNT(*) AS CountSiteId,v.SiteId,v.RefDomain,v.province AS name FROM " . $tableName . " v WHERE 1=1 " . $searchSql . " GROUP BY v.province ORDER BY CountSiteId DESC LIMIT " . $pageBegin . "," . $pageSize . "";
            $sqlCount = "SELECT COUNT(DISTINCT v.province) FROM " . $tableName . " v WHERE 1=1 " . $searchSql;
        } elseif ($searchType == 2) {   //按city(城市)进行统计
            $sql = "SELECT COUNT(*) AS CountSiteId,v.SiteId,v.RefDomain,v.city AS name FROM " . $tableName . " v WHERE 1=1 " . $searchSql . " GROUP BY v.city ORDER BY CountSiteId DESC LIMIT " . $pageBegin . "," . $pageSize . "";
            $sqlCount = "SELECT COUNT(DISTINCT v.city) FROM " . $tableName . " v WHERE 1=1 " . $searchSql;
        }

        $result = $dbOperator->ReturnArray($sql, $dataProperty);
        $allCount = $dbOperator->ReturnInt($sqlCount, $dataProperty);
        return $result;
    }

    /**
     * 
     * @param string $tableName 对应表名
     * @return int 
     */
    public function CheckTableName($tableName) {
        $dbOperator = DBOperator::getInstance();
        $sqlHasCount = "SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='$tableName'";

        $hasCount = $dbOperator->ReturnInt($sqlHasCount, null);

        if ($hasCount <= 0) {   //表不存在
            $hasCount = 0;
        }
        return $hasCount;
    }

    /**
     * 取总的浏览数 PV
     * @param string $tableName 表名
     * @param string $refDomain 来源域名
     * @param int $siteId 站点ID
     * @param int $channelId 栏目ID
     * @param int $tableType 文档表分类
     * @param int $tableId 文档ID
     * @return int 返回查询总数
     */
    public function GetCount($tableName, $refDomain = "", $siteId = 0, $channelId = 0, $tableType = 0, $tableId = 0) {
        $dataProperty = new DataProperty();
        $dbOperator = DBOperator::getInstance();
        $searchSql = "";
        if ($siteId > 0) {
            $searchSql .= " AND v.SiteId=:SiteId";
            $dataProperty->AddField("SiteId", $siteId);
        }

        if ($channelId > 0) {
            $searchSql .= " AND v.ChannelId=:ChannelId";
            $dataProperty->AddField("ChannelId", $channelId);
        }
        if ($tableId > 0) {
            $searchSql .= " AND v.TableType=:TableType AND v.TableId=:TableId";
            $dataProperty->AddField("TableType", $tableType);
            $dataProperty->AddField("TableId", $tableId);
        }

        if (!empty($refDomain) && strlen($refDomain) > 0 && $refDomain != "undefined") {
            $searchSql .= " AND v.RefDomain=:RefDomain";
            $dataProperty->AddField("RefDomain", $refDomain);
        }

        $sql = "SELECT COUNT(*) FROM " . $tableName . " v WHERE 1=1 " . $searchSql;

        $result = $dbOperator->ReturnInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 取总的IP数据 独立IP
     * @param string $tableName 表名
     * @param string $refDomain 来源域名
     * @param int $siteId 站点ID
     * @param int $channelId 栏目ID
     * @param int $tableType 文档表分类
     * @param int $tableId 文档ID
     * @return int 返回查询总数
     */
    public function GetIpAddressCount($tableName, $refDomain = "", $siteId = 0, $channelId = 0, $tableType = 0, $tableId = 0) {
        $dataProperty = new DataProperty();
        $dbOperator = DBOperator::getInstance();
        $searchSql = "";
        if ($siteId > 0) {
            $searchSql .= " AND v.SiteId=:SiteId";
            $dataProperty->AddField("SiteId", $siteId);
        }

        if ($channelId > 0) {
            $searchSql .= " AND v.ChannelId=:ChannelId";
            $dataProperty->AddField("ChannelId", $channelId);
        }
        if ($tableId > 0) {
            $searchSql .= " AND v.TableType=:TableType AND v.TableId=:TableId";
            $dataProperty->AddField("TableType", $tableType);
            $dataProperty->AddField("TableId", $tableId);
        }

        if (!empty($refDomain) && strlen($refDomain) > 0 && $refDomain != "undefined") {
            $searchSql .= " AND v.RefDomain=:RefDomain";
            $dataProperty->AddField("RefDomain", $refDomain);
        }

        $sql = "SELECT COUNT(DISTINCT IpAddress) FROM " . $tableName . " v WHERE 1=1 " . $searchSql;
        $result = $dbOperator->ReturnInt($sql, $dataProperty);
        return $result;
    }

}

?>