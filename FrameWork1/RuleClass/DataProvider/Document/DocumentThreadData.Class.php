<?php

/**
 * Description of DocumentThreadData
 * 咨询问答模块前台数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Document
 * @author Liujunyi
 */
class DocumentThreadData extends BaseFrontData {
    /**
     * 表名
     */

    const tableName = "cst_documentthread";

    /**
     * 表关键字段名
     */
    const tableIdName = "documentthreadid";

    /**
     * 修改发布时间及发布状态
     * @param string $publishDate 发布时间
     * @param int $state 信息状态号
     * @param int $tableIdValue 咨询问答ID号
     * @return int 修改影响的行数
     */
    public function ModifyPublishDate($publishDate, $state, $tableIdValue) {
        $dataProperty = new DataProperty();
        $sql = "UPDATE " . self::tableName . " SET `PublishDate`=:publishdate,`State`=:state WHERE documentthreadid=:documentthreadid";
        $dataProperty->AddField("publishdate", $publishDate);
        $dataProperty->AddField("state", $state);
        $dataProperty->AddField("documentthreadid", $tableIdValue);
        $dboPerator = DBOperator::getInstance();
        $result = $dboPerator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 更新数据信息,如点击量,回复数,表态功能等
     * @param string $str 信息字段值累加
     * @param int $tableIdValue 咨询问答ID号
     * @return int 修改影响的行数
     */
    public function UpdateCount($str, $tableIdValue) {
        if (strlen($str) > 0) {
            $sql = "UPDATE " . self::tableName . " SET " . $str . "=" . $str . " + 1 WHERE documentthreadid=:documentthreadid";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("documentthreadid", $tableIdValue);
            $dboPerator = DBOperator::getInstance();
            $result = $dboPerator->Execute($sql, $dataProperty);
            return $result;
        } else {
            return -1;
        }
    }

    /**
     * 改变信息状态,可进行审核,删除等操作
     * @param int $tableIdValue 咨询问答ID号
     * @param int $state 咨询问答状态
     * @return int 修改影响的行数
     */
    public function UpdateState($tableIdValue, $state) {
        $sql = "UPDATE " . self::tableName . " SET state=:state WHERE documentthreadid=:documentthreadid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("documentthreadid", $tableIdValue);
        $dataProperty->AddField("state", $state);
        $dboPerator = DBOperator::getInstance();
        $result = $dboPerator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 根据threadid对应的channelid
     * @param int $tableIdValue 咨询问答ID号
     * @return int 返回频道ID号
     */
    public function GetChannelID($tableIdValue) {
        $sql = "SELECT documentchannelid FROM " . self::tableName . " WHERE documentthreadid=:documentthreadid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("documentthreadid", $tableIdValue);
        $dboPerator = DBOperator::getInstance();
        $result = $dboPerator->ReturnInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 根据threadid对应的channelid
     * @param string $tableIdValue 咨询问答ID号列表
     * @return arr 返回频道ID号数组
     */
    public function GetDocumentChannelID($tableIdValue) {
        $sql = "SELECT documentchannelid FROM " . self::tableName . " WHERE documentthreadid in (" . $tableIdValue . ")";
        $dboPerator = DBOperator::getInstance();
        $result = $dboPerator->ReturnArray($sql, null);
        return $result;
    }

    /**
     * 根据tableIdName到得subject
     * @param int $tableIdValue 咨询问答ID号
     * @return string 返回标题
     */
    public function GetSubject($tableIdValue) {
        $dataProperty = new DataProperty();
        $sql = "SELECT subject FROM " . self::tableName . " WHERE " . self::tableIdName . "=:" . self::tableIdName;
        $dataProperty->AddField(self::tableIdName, $tableIdValue);
        $dboPerator = DBOperator::getInstance();
        $result = $dboPerator->ReturnString($sql, $dataProperty);
        return $result;
    }

    /**
     * 得到信息状态值state
     * @param int $tableIdValue 咨询问答ID号
     * @return int 返回咨询问答状态值 
     */
    public function GetState($tableIdValue) {
        $sql = "SELECT State as state FROM " . self::tableName . " WHERE documentthreadid=:documentthreadid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("documentthreadid", $tableIdValue);
        $dboPerator = DBOperator::getInstance();
        $result = $dboPerator->ReturnInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 根据documentthreadid得到发布时间
     * @param int $tableIdValue 咨询问答ID号
     * @return string 返回发布时间
     */
    public function GetPublishDate($tableIdValue) {
        $sql = "SELECT publishdate FROM " . self::tableName . " WHERE documentthreadid=:documentthreadid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("documentthreadid", $tableIdValue);
        $dboPerator = DBOperator::getInstance();
        $result = $dboPerator->ReturnString($sql, $dataProperty);
        return $result;
    }

    /**
     * 得到信息提交时间GetCreateDate
     * @param int $tableIdValue 咨询问答ID号
     * @return string 返回创建时间
     */
    public function GetCreateDate($tableIdValue) {
        $sql = "SELECT createdate FROM " . self::tableName . " WHERE documentthreadid=:documentthreadid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("documentthreadid", $tableIdValue);
        $dboPerator = DBOperator::getInstance();
        $result = $dboPerator->ReturnString($sql, $dataProperty);
        return $result;
    }

    /**
     * 按用户组统计
     * @param string $dealUserGroupId 会员组ID号
     * @return int 返回统计结果
     */
    public function GetUserGroupCount($dealUserGroupId) {
        $dataProperty = new DataProperty();
        $sql = "SELECT  count(*) FROM " . self::tableName . "  WHERE dealusergroupid like :searchkey";
        $dataProperty->AddField("searchkey", "%{" . $dealUserGroupId . "}%");
        $dboPerator = DBOperator::getInstance();
        $allCount = $dboPerator->ReturnInt($sql, $dataProperty);
        return $allCount;
    }

    /**
     * 根据咨询问答ID号取得相关信息
     * @param int $tableIdValue 咨询问答ID号
     * @param int $state 状态值
     * @return arr 返回咨询问答相关信息 
     */
    public function GetFrontViewById($tableIdValue, $state = 30) {
        $dataProperty = new DataProperty();
        $sql = "SELECT dt.documentchannelid,dt.username, dt.subject,dt.guestname,dt.createdate,dc.documentchannelname,dp.content FROM  " . self::tableName . " dt,cst_documentchannel dc, cst_documentpost dp WHERE dp.documentthreadid=dt.documentthreadid AND dp.isthread=1 AND dt.documentchannelid=dc.documentchannelid AND dt.documentthreadid=:documentthreadid AND dt.state=:state";
        $dataProperty->AddField("documentthreadid", $tableIdValue);
        $dataProperty->AddField("state", $state);
        $dboPerator = DBOperator::getInstance();
        $result = $dboPerator->ReturnRow($sql, $dataProperty);
        return $result;
    }

    /**
     * 根据tid取部门回复usergroupid
     * @param int $tableIdValue 咨询问答ID号
     * @return string 返回职能部门ID号
     */
    public function GetDealUserGroup($tableIdValue) {
        $dataProperty = new DataProperty();
        $sql = "SELECT dealusergroupid FROM  " . self::tableName . "  WHERE documentthreadid=:documentthreadid";
        $dataProperty->AddField("documentthreadid", $tableIdValue);
        $dboPerator = DBOperator::getInstance();
        $result = $dboPerator->ReturnString($sql, $dataProperty);
        if (strlen($result) > 1) {
            $result = str_ireplace("{", ",", $result);
            $result = str_ireplace("}", "", $result);
            $result = explode(",", $result);
        }
        return $result;
    }

    /**
     * 会员中心部门列表调用如律师,职能部门等
     * @param int $pageBegin 起始行数
     * @param int $pageSize 每页显示数
     * @param int $allCount 总数
     * @param string $searchKey 查询关键字
     * @param int $documentThreadId 咨询问答ID号
     * @param int $documentChannelId 频道ID号
     * @param int $userId 用户ID号
     * @param string $dealUserGroupId 职能部门ID号
     * @param int $state 状态值
     * @param int $resultType 信息类型:0所有信息;1取未回复的信息;2取回复过的信息
     * @return arr 返回取得数组相关信息
     */
    public function GetUserThreadList($pageBegin, $pageSize, &$allCount, $searchKey, $documentThreadId, $documentChannelId, $userId, $dealUserGroupId = 0, $state = 30, $resultType = 0, $siteId = 2) {
        $searchSql = "";
        $userRoleData = new UserRoleData();
        $myUserGroupId = $userRoleData->GetUserGroupID($userId, $siteId, 0);        //访问人的所在的用户组ID

        $dataProperty = new DataProperty();
        if (!empty($searchKey) && $searchKey != "undefined") {
            $searchSql .= " AND (t.subject like :searchkey1 OR t.usernameid like :searchkey2 OR t.username like :searchkey3)";
            $dataProperty->AddField("searchkey1", "%" . $searchKey . "%");
            $dataProperty->AddField("searchkey2", "%" . $searchKey . "%");
            $dataProperty->AddField("searchkey3", "%" . $searchKey . "%");
        }
        if ($documentChannelId > 0) {
            $searchSql .= " AND t.documentchannelid=:documentchannelid ";
            $dataProperty->AddField("documentchannelid", $documentChannelId);
        }
        if ($documentThreadId > 0) {
            $searchSql .= " AND t.documentthreadid=:documentthreadid ";
            $dataProperty->AddField("documentthreadid", $documentThreadId);
        }
        if ($myUserGroupId >= 3) {
            $myUserGroupId = "{" . $myUserGroupId . "}";
            $searchSql .= " AND t.dealusergroupid like :dealusergroupid ";
            $dataProperty->AddField("dealusergroupid", "%" . $myUserGroupId . "%");
        } elseif ($myUserGroupId > 0) {
            if ($dealUserGroupId >= 3) {
                $dealUserGroupId = "{" . $dealUserGroupId . "}";
                $searchSql .= " AND t.dealusergroupid like :dealusergroupid ";
                $dataProperty->AddField("dealusergroupid", "%" . $dealUserGroupId . "%");
            }
        }
        if (intval($resultType) == 1) {        //取未回复的信息
            $searchSql .= " AND t.documentthreadid not in (
                SELECT documentthreadid FROM cst_documentpost WHERE cst_documentpost.isthread=0 AND cst_documentpost.userid=:userid
                ) ";
            $dataProperty->AddField("userid", $userId);
        } elseif (intval($resultType) == 2) {       //取回复过的信息
            $searchSql .= " AND t.documentthreadid in (
                SELECT documentthreadid FROM cst_documentpost WHERE cst_documentpost.isthread=0 AND cst_documentpost.userid=:userid
                ) ";
            $dataProperty->AddField("userid", $userId);
        }
        if ($state >= 0) {
            $searchSql .= " AND t.state=:state ";
            $dataProperty->AddField("state", $state);
        }
        $searchSql .= " ";
        $sql = "SELECT
            t.documentchannelid,t.documentthreadid,t.subject,t.createdate,t.username,t.userid,t.guestname,
            c.documentchannelname
            FROM
            " . self::tableName . " t, cst_documentchannel c
            WHERE t.documentchannelid=c.documentchannelid AND c.siteid=2  " . $searchSql . " ORDER BY t.CreateDate desc LIMIT " . $pageBegin . "," . $pageSize . "";

        $dboPerator = DBOperator::getInstance();
        $result = $dboPerator->ReturnArray($sql, $dataProperty);
        //统计总数
        $sql = "";
        $sql = "SELECT
           count(t.documentthreadid)
            FROM
            " . self::tableName . " t, cst_documentchannel c
            WHERE t.documentchannelid=c.documentchannelid AND c.siteid=2  " . $searchSql;
        $dboPerator = DBOperator::getInstance();
        $allCount = $dboPerator->ReturnInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 前台搜索及列表页面时使用  可根据cid,documentthreadtypeid,state进行分类查询
     * @param int $documentChannelId 频道ID号
     * @param int $pageBegin 起始行数
     * @param int $pageSize 每页显示数
     * @param int $allCount 总数
     * @param string $searchKey 查询关键字
     * @param int $order 排序
     * @param int $documentThreadTypeId 咨询问答主题分类ID号
     * @param int $state 状态值
     * @return arr 返回取得数组相关信息
     */
    public function GetHtmlList($documentChannelId, $pageBegin, $pageSize, &$allCount, $searchKey, $order = 0, $documentThreadTypeId = 0, $state = 30, $threadtag = "") {
        $searchSql = "";
        if (intval($order) === 1) {
            $orderSplit = "t.createdate DESC";
        } elseif (intval($order) === 2) {
            $orderSplit = "t.viewcount DESC, t.postcount DESC";
        } elseif (intval($order) === 3) {
            $orderSplit = "t.postcount  DESC, t.viewcount DESC";
        } elseif (intval($order) === 4) {
            $orderSplit = "t.publishdate  DESC, t.createdate DESC";
        } else {
            $orderSplit = "t.createdate DESC";
        }
        $dataProperty = new DataProperty();
        if (!empty($threadtag) && $threadtag != "undefined") {
            $threadtag = "{" . $threadtag . "}";
            $searchSql .= " AND (t.threadtag like :searchkey4)";
            $dataProperty->AddField("searchkey4", "%" . $threadtag . "%");
        }
        if (!empty($searchKey) && $searchKey != "undefined") {
            $searchSql .= " AND (t.subject like :searchkey1 OR t.username like :searchkey2 OR t.userid like :searchkey3)";
            $dataProperty->AddField("searchkey1", "%" . $searchKey . "%");
            $dataProperty->AddField("searchkey2", "%" . $searchKey . "%");
            $dataProperty->AddField("searchkey3", "%" . $searchKey . "%");
        }
        if ($documentThreadTypeId > 0) {
            $searchSql .= " AND t.documentthreadtypeid in (" . $documentThreadTypeId . ")";
        }
        if ($documentChannelId > 0) {
            $searchSql .= " AND t.documentchannelid in (" . $documentChannelId . ")";
        }
        if ($state >= 0) {
            $searchSql .= " AND t.state=:state ";
            $dataProperty->AddField("state", $state);
        }
        $sql = "SELECT
            t.documentthreadid,t.documentchannelid,t.publishdate,t.subject,t.postcount,t.viewcount,t.createdate,t.guestname,t.username,t.remark,t.state as changestate,
            c.documentchannelname
            FROM
            " . self::tableName . " t,cst_documentchannel c
            WHERE t.documentchannelid=c.documentchannelid AND t.documentthreadid>0 " . $searchSql . " ORDER BY " . $orderSplit . " LIMIT " . $pageBegin . "," . $pageSize . "";
        $dboPerator = DBOperator::getInstance();
        $result = $dboPerator->ReturnArray($sql, $dataProperty);
        //统计总数
        $sql = "";
        $sql = "SELECT count(t.documentthreadid) FROM " . self::tableName . " t WHERE 1=1 " . $searchSql . "";
        $allCount = $dboPerator->ReturnInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 前台咨询调用,根据用户组ID进行
     * @param int $userGroupId 用户组ID
     * @param int $pageBegin 开始数
     * @param int $pageSize 每页显示数
     * @param int $allCount 总数
     * @param int $order 排序方式
     * @param int $state 状态,30已发布
     * @param string $threadTag 标签
     * @return arr  返回查询到的数据结果集
     */
    public function GetFrontListByUserGroupID($userGroupId, $pageBegin, $pageSize, &$allCount, $siteId, $order = 0, $state = 30, $threadTag = "") {
        $searchSql = "";
        $dataProperty = new DataProperty();

        if (intval($order) === 1) {
            $orderSplit = "t.createdate DESC";
        } elseif (intval($order) === 2) {
            $orderSplit = "t.viewcount DESC, t.postcount DESC";
        } elseif (intval($order) === 3) {
            $orderSplit = "t.postcount  DESC, t.viewcount DESC";
        } elseif (intval($order) === 4) {
            $orderSplit = "t.publishdate  DESC, t.createdate DESC";
        } else {
            $orderSplit = "t.createdate DESC";
        }

        if ($siteId > 0) {
            $searchSql .= " AND t.documentchannelid in ( select documentchannelid from cst_documentchannel where cst_documentchannel.documentchanneltype='2' and cst_documentchannel.siteid=:siteid)";
            $dataProperty->AddField("siteid", $siteId);
        }

        if (!empty($threadTag) && $threadTag != "undefined") {
            $threadtag = "{" . $threadTag . "}";
            $searchSql .= " AND (t.threadtag like :searchkey)";
            $dataProperty->AddField("searchkey", "%" . $threadTag . "%");
        }

        if ($userGroupId > 0) {
            $dealUserGroupId = "{" . $userGroupId . "}";
            $searchSql .= " AND (t.dealusergroupid like :dealusergroupid)";
            $dataProperty->AddField("dealusergroupid", "%" . $dealUserGroupId . "%");
        }
        if ($state >= 0) {
            $searchSql .= " AND t.state=:state ";
            $dataProperty->AddField("state", $state);
        }
        $sql = "SELECT
            t.documentthreadid,t.documentchannelid,t.publishdate,t.subject,t.postcount,t.viewcount,t.createdate,t.guestname,t.username,t.remark,t.state as changestate,
            ug.usergroupshortname,ug.usergroupname
            FROM
            " . self::tableName . " t,cst_usergroup ug
            WHERE ug.usergroupid=:usergroupid " . $searchSql . " ORDER BY " . $orderSplit . " LIMIT " . $pageBegin . "," . $pageSize . "";
        $dataProperty->AddField("usergroupid", $userGroupId);

        $dboPerator = DBOperator::getInstance();
        $result = $dboPerator->ReturnArray($sql, $dataProperty);

        $searchSql = "";
        $dataProperty = new DataProperty();

        if (!empty($threadTag) && $threadTag != "undefined") {
            $threadtag = "{" . $threadTag . "}";
            $searchSql .= " AND (t.threadtag like :searchkey)";
            $dataProperty->AddField("searchkey", "%" . $threadTag . "%");
        }

        if ($siteId > 0) {
            $searchSql .= " AND t.documentchannelid in ( select documentchannelid from cst_documentchannel where cst_documentchannel.documentchanneltype='2' and cst_documentchannel.siteid=:siteid)";
            $dataProperty->AddField("siteid", $siteId);
        }

        if ($userGroupId > 0) {
            $dealUserGroupId = "{" . $userGroupId . "}";
            $searchSql .= " AND (t.dealusergroupid like :dealusergroupid)";
            $dataProperty->AddField("dealusergroupid", "%" . $dealUserGroupId . "%");
        }
        if ($state >= 0) {
            $searchSql .= " AND t.state=:state ";
            $dataProperty->AddField("state", $state);
        }
        //统计总数
        $sql = "SELECT count(t.documentthreadid) FROM " . self::tableName . " t WHERE 1=1 " . $searchSql;
        $allCount = $dboPerator->ReturnInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 根据用户是否回复取相关信息
     * @param int $userGroupId 用户组ID号
     * @param int $pageBegin 开始数
     * @param int $pageSize 每页显示数
     * @param int $allCount 总数
     * @param int $siteId 站点ID号
     * @param int $order 排序方式
     * @param int $state 状态,30已发布
     * @param int $userId 用户ID号
     * @param int $isPost 是否回复标识
     * @param int $documentChannelId 频道ID号
     * @return arr 返回列表数据集
     */
    public function GetFrontListByUserPost($userGroupId, $pageBegin, $pageSize, &$allCount, $siteId = 0, $order = 0, $state = 30, $userId = 0, $isPost = 0, $documentChannelId = 0) {
        $searchSql = "";
        $dataProperty = new DataProperty();

        if (intval($order) === 1) {
            $orderSplit = "t.createdate DESC";
        } elseif (intval($order) === 2) {
            $orderSplit = "t.viewcount DESC, t.postcount DESC";
        } elseif (intval($order) === 3) {
            $orderSplit = "t.postcount  DESC, t.viewcount DESC";
        } elseif (intval($order) === 4) {
            $orderSplit = "t.publishdate  DESC, t.createdate DESC";
        } else {
            $orderSplit = "t.createdate DESC";
        }

        if ($siteId > 0) {
            $searchSql .= " AND t.documentchannelid in ( select documentchannelid from cst_documentchannel where cst_documentchannel.documentchanneltype='2' and cst_documentchannel.siteid=:siteid)";
            $dataProperty->AddField("siteid", $siteId);
        }

        if ($userGroupId >= 3) {        //职能部门则取各自对应的信息
            $dealUserGroupId = "{" . $userGroupId . "}";
            $searchSql .= " AND (t.dealusergroupid=:dealusergroupid)";
            $dataProperty->AddField("dealusergroupid", $dealUserGroupId);
        }

        if ($documentChannelId > 0) {
            $searchSql .= " AND t.documentchannelid=:documentchannelid ";
            $dataProperty->AddField("documentchannelid", $documentChannelId);
        }

        if ($state >= 0) {
            $searchSql .= " AND t.state=:state ";
            $dataProperty->AddField("state", $state);
        }

        if (intval($isPost) == 1) {        //取未回复的信息
            $searchSql .= " AND t.documentthreadid not in (
                SELECT documentthreadid FROM cst_documentpost WHERE cst_documentpost.isthread=0 AND cst_documentpost.userid=:userid
                ) ";
            $dataProperty->AddField("userid", $userId);
        } elseif (intval($isPost) == 2) {       //取回复过的信息
            $searchSql .= " AND t.documentthreadid in (
                SELECT documentthreadid FROM cst_documentpost WHERE cst_documentpost.isthread=0 AND cst_documentpost.userid=:userid
                ) ";
            $dataProperty->AddField("userid", $userId);
        }

        $sql = "SELECT t.documentthreadid,t.state,t.documentchannelid,t.publishdate,t.subject,t.postcount,DATE_FORMAT(t.publishdate,'%Y') as year,DATE_FORMAT(t.publishdate,'%m') as month,DATE_FORMAT(t.publishdate,'%d') as day,t.viewcount,t.createdate,t.guestname,t.username,t.remark,t.state as changestate,(select documentchannelname from cst_documentchannel where cst_documentchannel.documentchannelid=t.documentchannelid ) as documentchannelname
            FROM
            " . self::tableName . " t
            WHERE 1=1 " . $searchSql . " ORDER BY " . $orderSplit . " LIMIT " . $pageBegin . "," . $pageSize . "";

        $dboPerator = DBOperator::getInstance();
        $result = $dboPerator->ReturnArray($sql, $dataProperty);

        //统计总数
        $sql = "";
        if ($siteId > 0) {
            $searchSql .= " AND t.documentchannelid in ( select documentchannelid from cst_documentchannel where cst_documentchannel.documentchanneltype='2' and cst_documentchannel.siteid=:siteid)";
            $dataProperty->AddField("siteid", $siteId);
        }

        if ($userGroupId >= 3) {
            $dealUserGroupId = "{" . $userGroupId . "}";
            $searchSql .= " AND (t.dealusergroupid=:dealusergroupid)";
            $dataProperty->AddField("dealusergroupid", $dealUserGroupId);
        }

        if ($documentChannelId > 0) {
            $searchSql .= " AND t.documentchannelid=:documentchannelid ";
            $dataProperty->AddField("documentchannelid", $documentChannelId);
        }

        if ($state >= 0) {
            $searchSql .= " AND t.state=:state ";
            $dataProperty->AddField("state", $state);
        }

        if (intval($isPost) == 1) {        //取未回复的信息
            $searchSql .= " AND t.documentthreadid not in (
                SELECT documentthreadid FROM cst_documentpost WHERE cst_documentpost.isthread=0 AND cst_documentpost.userid=:userid
                ) ";
            $dataProperty->AddField("userid", $userId);
        } elseif (intval($isPost) == 2) {       //取回复过的信息
            $searchSql .= " AND t.documentthreadid in (
                SELECT documentthreadid FROM cst_documentpost WHERE cst_documentpost.isthread=0 AND cst_documentpost.userid=:userid
                ) ";
            $dataProperty->AddField("userid", $userId);
        }
        $sql = "SELECT count(t.documentthreadid) FROM " . self::tableName . " t WHERE 1=1 " . $searchSql;
        $allCount = $dboPerator->ReturnInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 前台聚合调用用
     * @param int $documentChannelId 频道ID号
     * @param int $topCount 所取条数
     * @param int $order 排序方式
     * @param int $documentThreadTypeId 咨询问答主题分类ID号
     * @param int $state 状态,30已发布
     * @param int $srchFrom 查询时间间隔
     * @return arr 返回列表数据集
     */
    public function GetOrderList($documentChannelId, $topCount, $order = 0, $documentThreadTypeId = 0, $state = 30, $srchFrom = 0) {
        $searchSql = "";
        if (intval($order) === 1) {
            $orderSplit = "t.createdate DESC";
        } elseif (intval($order) === 2) {
            $orderSplit = "t.viewcount DESC, t.postcount DESC";
        } elseif (intval($order) === 3) {
            $orderSplit = "t.postcount  DESC, t.viewcount DESC";
        } elseif (intval($order) === 4) {
            $orderSplit = "t.publishdate  DESC, t.createdate DESC";
        } else {
            $orderSplit = "t.createdate DESC";
        }

        $dataProperty = new DataProperty();
        if ($documentThreadTypeId > 0) {
            $searchSql .= " AND t.documentthreadtypeid in (" . $documentThreadTypeId . ")";
        }
        if ($documentChannelId > 0) {
            $searchSql .= " AND t.documentchannelid in (" . $documentChannelId . ")";
        }
        if ($state >= 0) {
            $searchSql .= " AND t.state=:state ";
            $dataProperty->AddField("state", $state);
        }
        if ($srchFrom > 0) {
            $searchSql .= " AND UNIX_TIMESTAMP(sysdate())-UNIX_TIMESTAMP(t.createdate)<= " . $srchFrom;
        }
        $sql = "SELECT
            t.documentthreadid,t.documentchannelid,t.publishdate,t.subject,t.postcount,t.viewcount,t.createdate,t.guestname,t.username,t.remark,t.state as changestate
            FROM
            " . self::tableName . " t
            WHERE t.documentthreadid>0 " . $searchSql . " ORDER BY " . $orderSplit . " LIMIT " . $topCount . "";
        $dboPerator = DBOperator::getInstance();
        $result = $dboPerator->ReturnArray($sql, $dataProperty);
        return $result;
    }

}

?>
