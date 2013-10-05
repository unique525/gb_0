<?php

/**
 * Description of DocumentThreadManageData
 * 咨询问答模块后台数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Document
 * @author Liujunyi
 */
class DocumentThreadManageData extends BaseManageData {
    /**
     * 表名
     */

    const tableName = "cst_documentthread";

    /**
     * 表关键字段名
     */
    const tableIdName = "documentthreadid";

    /**
     * 新增信息
     * @return int 返回新增数据列号
     */
    public function Create() {
        $dataProperty = new DataProperty();
        //$sql = parent::GetInsertSql(self::tableName, $dataProperty, "titlepic", $titlepicpath);
        $sql = parent::GetInsertSql(self::tableName, $dataProperty);
        $dboPerator = DBOperator::getInstance();
        $result = $dboPerator->LastInsertId($sql, $dataProperty);
        return $result;
    }

    /**
     * 修改信息
     * @param int $tableIdValue 修改的数据ID号
     * @return int 返回数据执行影响的行数
     */
    public function Modify($tableIdValue) {
        $dataProperty = new DataProperty();
        $sql = parent::GetUpdateSql(self::tableName, self::tableIdName, $tableIdValue, $dataProperty);
        $dboPerator = DBOperator::getInstance();
        $result = $dboPerator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 修改发布时间及发布状态
     * @param string $publishDate 发布时间
     * @param int $state 状态值
     * @param int $tableIdValue 数据序列ID号
     * @return int 返回数据执行影响的行数
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
     * @param int $str 信息字段值累加
     * @param int $tableIdValue 数据序列ID号
     * @return int 返回数据执行影响的行数
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
     * @param int $tableIdValue 数据序列ID号
     * @param int $state 状态值
     * @return int 返回数据执行影响的行数
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
     * 更新网友网名及统计回复数
     * @param int $tableIdValue 数据序列ID号
     * @param int $guestName 
     * @param int $postCount 
     * @return int 返回数据执行影响的行数
     */
    public function UpdateGuest($tableIdValue, $guestName = "", $postCount = 0) {
        $sql = "UPDATE " . self::tableName . " SET guestname=:guestname,postcount=:postcount WHERE documentthreadid=:documentthreadid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("documentthreadid", $tableIdValue);
        $dataProperty->AddField("guestname", $guestName);
        $dataProperty->AddField("postcount", $postCount);
        $dboPerator = DBOperator::getInstance();
        $result = $dboPerator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 删除主题信息
     * @param int $tableIdValue 要删除的信息主ID
     * @param int 返回执行后的数据标识
     */
    public function DeleteThread($tableIdValue) {
        $sql = "DELETE FROM " . self::tableName . " WHERE documentthreadid=:documentthreadid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("documentthreadid", $tableIdValue);
        $dboPerator = DBOperator::getInstance();
        $result = $dboPerator->Execute($sql, $dataProperty);
        if ($result > 0) {
            $documentPostData = new DocumentPostData();
            $delPostResult = $documentPostData->DeleteByThreadId($tableIdValue);
        }
        return $result;
    }

    /**
     * 主题信息移动
     * @param int $documentChannelId
     * @param int $ids 要移动的主题ID列表
     * @return int 返回数据执行影响的行数
     */
    public function Move($documentChannelId, $ids) {
        $result = 0;
        $documentChannelData = new DocumentChannelData();
        $toCidType = $documentChannelData->GetDocumentChannelType($documentChannelId);

        $idsArr = split(",", $ids);
        for ($i = 0; $i < count($idsArr); $i++) {
            if (intval($idsArr[$i]) > 0) {
                $fromCid = self::GetChannelID(intval($idsArr[$i]));
                $fromCidType = $documentChannelData->GetDocumentChannelType($fromCid);
                break;
            }
        }

        if ($toCidType === $fromCidType) {
            $oldCid = self::GetDocumentChannelID($ids);
            $oldCid = Format::format_arr($oldCid);
            $sql = "UPDATE " . self::tableName . " SET `documentchannelid`=:documentchannelid WHERE documentthreadid in (" . $ids . ")";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("documentchannelid", $documentChannelId);
            $dboPerator = DBOperator::getInstance();
            $result = $dboPerator->Execute($sql, $dataProperty);
            if ($result > 0) {
                //加入操作log
                $adminUserLogData = new AdminUserLogData();
                $operateContent = "Thread：Move threadid ：" . $ids . "；newcid：" . $documentChannelId . "；oldcid：" . $oldCid . "；name；" . Control::GetAdminUserName() . "；adminuserid：" . Control::GetAdminUserID();
                $adminUserLogData->Insert($operateContent);
            }
        }
        return $result;
    }

    /**
     * 主题列表数据处理  可根据cid,documentthreadtypeid,state进行分类查询
     * @param int $documentChannelId 频道ID号
     * @param int $pageBegin 起始数
     * @param int $pageSize 每页显示数
     * @param int $allCount 总数
     * @param string $searchKey 查询关键字
     * @param int $documentThreadTypeId 分类主题ID号
     * @param int $state 状态值
     * @return arr 返回主题信息数据列表
     */
    public function GetListPager($documentChannelId, $pageBegin, $pageSize, &$allCount, $searchKey, $documentThreadTypeId = 0, $state = -1) {
        $searchSql = "";
        $dataProperty = new DataProperty();
        if (!empty($searchKey) && $searchKey != "undefined") {
            $searchSql .= " AND (t.subject like :searchkey1 OR t.adminusername like :searchkey2 OR t.username like :searchkey3 OR t.documentthreadid =:searchkey4)";
            $dataProperty->AddField("searchkey1", "%" . $searchKey . "%");
            $dataProperty->AddField("searchkey2", "%" . $searchKey . "%");
            $dataProperty->AddField("searchkey3", "%" . $searchKey . "%");
            $dataProperty->AddField("searchkey4", $searchKey);
        }
        if ($documentChannelId > 0) {
            $searchSql .= " AND t.documentchannelid=:documentchannelid ";
            $dataProperty->AddField("documentchannelid", $documentChannelId);
        }
        if ($documentThreadTypeId > 0) {
            $searchSql .= " AND t.documentthreadtypeid=:documentthreadtypeid ";
            $dataProperty->AddField("documentthreadtypeid", $documentThreadTypeId);
        }
        if ($state >= 0) {
            $searchSql .= " AND t.state=:state ";
            $dataProperty->AddField("state", $state);
        } else {
            $searchSql .= " ";
        }
        $sql = "SELECT
            t.documentthreadid,t.state,t.subject,t.remark,t.createdate,t.username,t.documentthreadtypeid,t.postcount,t.username,
            d.documentthreadtypename
            FROM
            " . self::tableName . " t, cst_documentthreadtype d
            WHERE t.documentthreadtypeid=d.documentthreadtypeid " . $searchSql . " order by t.createdate desc LIMIT " . $pageBegin . "," . $pageSize . "";
        $dboPerator = DBOperator::getInstance();
        $result = $dboPerator->ReturnArray($sql, $dataProperty);
        //统计总数
        $sql = "";
        $sql = "SELECT count(*) FROM " . self::tableName . " t WHERE t.documentthreadid>0 " . $searchSql;
        $allCount = $dboPerator->ReturnInt($sql, $dataProperty);
        return $result;
    }

    /*
      public function GetThreadOne($tableIdValue) {
      $dataProperty = new DataProperty();
      $sql = "SELECT t.documentthreadid, p.* FROM " . self::tableName . " t, cst_documentpost p  WHERE t.state=10 AND p.state=10 AND p.isthread=1 AND t.documentthreadid=p.documentthreadid AND t.documentthreadid=:documentthreadid";
      $dataProperty->AddField("documentthreadid", $tableIdValue);
      $dboPerator = DBOperator::getInstance();
      $result = $dboPerator->ReturnArray($sql, $dataProperty);
      return $result;
      }
     */

    /**
     * 根据channelid查询主贴列表信息
     * @param int $documentChannelId 频道ID号
     * @param int $pageBegin 起始数
     * @param int $pageSize 每页显示数
     * @param int $topCount 要查询的总数
     * @param int $allCount 总数
     * @param int $state 状态值
     * @return arr 返回查到的数据结果集
     */
    public function GetNewList($documentChannelId, $pageBegin, $pageSize, $topCount, &$allCount, $state) {
        $dataProperty = new DataProperty();
        $dboPerator = DBOperator::getInstance();
        if ($pageSize > 0) {
            $sqlc = "SELECT count(*) FROM " . self::tableName . " WHERE state=:state AND documentchannelid=:documentchannelid ";
            $dataProperty->AddField("documentchannelid", $documentChannelId);
            $dataProperty->AddField("state", $state);
            $allCount = $dboPerator->ReturnInt($sqlc, $dataProperty);
            $sql = "SELECT DocumentThreadID AS threadid, DocumentChannelID AS cid, Subject AS subject, CreateDate AS createdate FROM " . self::tableName . " WHERE state=:state AND documentchannelid=:documentchannelid ORDER BY createdate DESC LIMIT " . $pageBegin . "," . $pageSize . "";
        } else {
            $sql = "SELECT DocumentThreadID AS threadid, DocumentChannelID AS cid, Subject AS subject, CreateDate AS createdate FROM " . self::tableName . " WHERE state=:state AND documentchannelid=:documentchannelid ORDER BY createdate DESC LIMIT 0," . $topCount . "";
            $allCount = 0;
        }
        $dataProperty->AddField("documentchannelid", $documentChannelId);
        $dataProperty->AddField("state", $state);
        $result = $dboPerator->ReturnArray($sql, $dataProperty);

        return $result;
    }

    /**
     * 根据threadid对应的channelid
     * @param int $tableIdValue 数据序列ID号
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
     * @param int $tableIdValue 数据序列ID号
     * @return arr 返回频道ID号列表
     */
    public function GetDocumentChannelID($tableIdValue) {
        $sql = "SELECT documentchannelid FROM " . self::tableName . " WHERE documentthreadid in (" . $tableIdValue . ")";
        $dboPerator = DBOperator::getInstance();
        $result = $dboPerator->ReturnArray($sql, null);
        return $result;
    }

    /**
     * 根据tableIdName到得subject
     * @param int $tableIdValue 数据序列ID号
     * @return string 返回主题
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
     * @param int $tableIdValue 数据序列ID号
     * @return int 返回状态值
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
     * @param int $tableIdValue 数据序列ID号
     * @return sring 返回发布时间
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
     * @param int $tableIdValue 数据序列ID号
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
     * @param string $dealUserGroupId 用户组ID号
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
     * 统计回复数
     * @param int $tableIdValue 主题信息ID号
     * @return int 返回统计到总数
     */
    public function GetPostCount($tableIdValue) {
        $dataProperty = new DataProperty();
        $sql = "SELECT  count(*) FROM " . self::tableName . "  WHERE isthread=0 AND state=30 AND documentthreadid=:documentthreadid";
        $dataProperty->AddField("documentthreadid", $tableIdValue);
        $dboPerator = DBOperator::getInstance();
        $allCount = $dboPerator->ReturnInt($sql, $dataProperty);
        return $allCount;
    }

    /**
     * 取到全部documentthreadid
     * @param int $state 状态值
     * @param int $limit 要取的条数
     * @return arr 返回查询到的数据结果集
     */
    public function GetThreadidListAll($state, $limit) {
        //全部发布
        $sql = "SELECT documentthreadid FROM " . self::tableName . " WHERE state=:state AND state<100 AND (publishdate=NULL OR publishdate='0000-00-00 00:00:00' OR PublishDate is null) ORDER BY createdate desc LIMIT 0," . $limit . "";
        //回复数统计
        //$sql = "select documentthreadid FROM " . self::tableName . " WHERE ORDER BY createdate desc limit 0," . $limit . "";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("state", $state);
        $dboPerator = DBOperator::getInstance();
        $result = $dboPerator->ReturnArray($sql, $dataProperty);
        return $result;
    }

    /**
     * 细览主贴信息
     * @param int $tableIdValue 主题信息ID号
     * @return arr 返回查询到的数据结果集
     */
    public function GetOne($tableIdValue) {
        $dataProperty = new DataProperty();
        $sql = "SELECT DocumentChannelID AS documentchannelid, DocumentThreadTypeID AS documentthreadtypeid, UserName AS username, Subject AS subject, ThreadType AS threadtype, ThreadBest AS threadbest, RecLevel AS reclevel, RealName AS realname, Tel AS tel, Email AS email, Address AS address,guestname, Remark AS remark, DealUserGroupID AS dealusergroupid,threadtag FROM  " . self::tableName . "  WHERE documentthreadid=:documentthreadid";
        $dataProperty->AddField("documentthreadid", $tableIdValue);
        $dboPerator = DBOperator::getInstance();
        $result = $dboPerator->ReturnRow($sql, $dataProperty);
        return $result;
    }

    /**
     * 根据主题ID号信息信息
     * @param int $tableIdValue 主题ID号
     * @param int $state 状态值
     * @return arr 返回查询到的数据结果集
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
     * @param int $tableIdValue 主题ID号
     * @return string 返回查询到的用户组ID号
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
     * @param int $pageBegin 起始数
     * @param int $pageSize 每页显示数
     * @param int $allCount 总数
     * @param string $searchKey 查询关键字
     * @param int $documentThreadId 主题ID号
     * @param int $documentChannelId 频道ID号
     * @param int $userId 用户ID号
     * @param int $dealUserGroupId 用户组ID号
     * @param int $state 状态值
     * @param int $resultType 所取信息类型 1为未回复 2为回复过的信息
     * @return arr 返回查询到的数据结果集
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
     * 前台聚合调用 可根据cid,documentthreadtypeid,state进行分类查询
     * @param int $documentChannelId 频道ID号
     * @param int $topCount 要取的条数
     * @param int $order 排序类型
     * @param int $documentThreadTypeId 主题分类ID号
     * @param int $state 状态值
     * @param int $srchFrom 间隔时间
     * @param int $hasSub 是否调用下级频道内容处理 1为调用否则为不调用
     * @return arr 返回查询到的数据结果集
     */
    public function GetList($documentChannelId, $topCount, $order = 0, $documentThreadTypeId = 0, $state = 30, $srchFrom = 0, $hasSub = 0, $userGroupId = -1) {
        $searchSql = "";
        //排序处理
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

        if (!empty($topCount)) {
            $orderSplit .= " LIMIT " . $topCount . "";
        }

        //是否调用下级频道内容处理
        if ((intval($hasSub) === 1) && $documentChannelId > 0) {
            $documentChannelData = new DocumentChannelData();
            $_documentChannelType = 2;      //咨询问答类类
            $arrList = $documentChannelData->GetChildChannelID($documentChannelId, $_documentChannelType);
            if (count($arrList) > 0) {
                $hasSubDocumentChannel = "";
                for ($i = 0; $i < count($arrList); $i++) {
                    $hasSubDocumentChannel .= "," . $arrList[$i]['documentchannelid'];
                }
                $documentChannelId = $documentChannelId . $hasSubDocumentChannel;
            }
        }

        $dataProperty = new DataProperty();
        //UNIX_TIMESTAMP(sysdate())-UNIX_TIMESTAMP(CreateDate)<=2592000
        if (intval($documentThreadTypeId) > 0) {
            $searchSql .= " AND t.documentthreadtypeid in (" . $documentThreadTypeId . ")";
        }
        if (intval($documentChannelId) > 0) {
            $searchSql .= " AND t.documentchannelid in (" . $documentChannelId . ")";
        }
        if (intval($state) >= 0) {
            $searchSql .= " AND t.state=:state ";
            $dataProperty->AddField("state", $state);
        }
        if ($srchFrom > 0) {
            $searchSql .= " AND UNIX_TIMESTAMP(sysdate())-UNIX_TIMESTAMP(t.createdate)<= " . $srchFrom;
        }

        $sql = "SELECT
            t.documentthreadid,t.documentchannelid,t.dealusergroupid,t.publishdate,t.subject,t.postcount,t.viewcount,t.createdate,t.guestname,t.username,t.remark,t.state as changestate,
            c.documentchannelname
            FROM
            " . self::tableName . " t,cst_documentchannel c
            WHERE t.documentchannelid=c.documentchannelid AND t.documentthreadid>0 " . $searchSql . " ORDER BY " . $orderSplit;

        $dboPerator = DBOperator::getInstance();
        $result = $dboPerator->ReturnArray($sql, $dataProperty);
        return $result;
    }

    /**
     * 取主题数据及回复信息
     * @param string $documentChannelId 频道ID号
     * @param int $topCount 要取的条数
     * @param int $order 排序
     * @param int $documentThreadTypeId 主题分类ID号
     * @param int $state 状态值
     * @param int $srchFrom 时间间隔
     * @param int $hasSub 是否调用下级频道内容处理 1为调用否则为不调用
     * @param int $userGroupId 用户组ID号
     * @return arr 返回查询到的数据结果集
     */
    public function GetThreadPostList($documentChannelId, $topCount, $order = 0, $documentThreadTypeId = 0, $state = 30, $srchFrom = 0, $hasSub = 0, $userGroupId = 0) {
        $searchSql = "";
        //排序处理
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

        if (!empty($topCount)) {
            $orderSplit .= " LIMIT " . $topCount . "";
        }

        //是否调用下级频道内容处理
        if (intval($hasSub) === 1) {
            $documentChannelData = new DocumentChannelData();
            $_documentChannelType = 2;       //咨询问答
            $arrList = $documentChannelData->GetChildChannelID($documentChannelId, $_documentChannelType);
            if (count($arrList) > 0) {
                $hasSubDocumentChannel = "";
                for ($i = 0; $i < count($arrList); $i++) {
                    $hasSubDocumentChannel .= "," . $arrList[$i]['documentchannelid'];
                }
                $documentChannelId = $documentChannelId . $hasSubDocumentChannel;
            }
        }

        $dataProperty = new DataProperty();
        //UNIX_TIMESTAMP(sysdate())-UNIX_TIMESTAMP(CreateDate)<=2592000
        if (intval($documentThreadTypeId) > 0) {
            $searchSql .= " AND t.documentthreadtypeid in (" . $documentThreadTypeId . ")";
        }
        if (intval($documentChannelId) > 0) {
            $searchSql .= " AND t.documentchannelid in (" . $documentChannelId . ")";
        }
        if (intval($state) >= 0) {
            $searchSql .= " AND t.state=:state ";
            $dataProperty->AddField("state", $state);
        }
        if ($srchFrom > 0) {
            $searchSql .= " AND UNIX_TIMESTAMP(sysdate())-UNIX_TIMESTAMP(t.createdate)<= " . $srchFrom;
        }

        if ($userGroupId >= 0) {
            $sql = "SELECT
            t.documentthreadid,t.documentchannelid,t.publishdate,t.subject,t.postcount,t.viewcount,t.createdate,t.guestname,t.username,t.state as changestate,
            c.documentchannelname,(SELECT content FROM cst_documentpost WHERE cst_documentpost.isthread=0 AND cst_documentpost.usergroupid=:usergroupid AND t.documentthreadid=documentthreadid limit 1) as post
            FROM
            " . self::tableName . " t,cst_documentchannel c
            WHERE t.documentchannelid=c.documentchannelid AND t.documentthreadid>0 " . $searchSql . " ORDER BY " . $orderSplit;
            $dataProperty->AddField("usergroupid", $userGroupId);
        } else {
            $sql = "SELECT
            t.documentthreadid,t.documentchannelid,t.publishdate,t.subject,t.postcount,t.viewcount,t.createdate,t.guestname,t.username,t.state as changestate,
            c.documentchannelname, (SELECT content FROM cst_documentpost WHERE cst_documentpost.isthread=0 AND t.documentthreadid=documentthreadid limit 1) as post
            FROM
            " . self::tableName . " t,cst_documentchannel c
            WHERE t.documentchannelid=c.documentchannelid AND t.documentthreadid>0 " . $searchSql . " ORDER BY " . $orderSplit;
        }

        $dboPerator = DBOperator::getInstance();
        $result = $dboPerator->ReturnArray($sql, $dataProperty);
        return $result;
    }

    /**
     * 前台搜索及列表页面时使用  可根据cid,documentthreadtypeid,state进行分类查询
     * @param int $documentChannelId 频道ID号
     * @param int $pageBegin 起始数
     * @param int $pageSize 每页显示总数
     * @param int $allCount 总数
     * @param string $searchKey 查询关键字
     * @param int $order 排序
     * @param int $documentThreadTypeId 主题分类ID号
     * @param int $state 状态值
     * @return arr 返回查询到的数据结果集
     */
    public function GetHtmlList($documentChannelId, $pageBegin, $pageSize, &$allCount, $searchKey, $order = 0, $documentThreadTypeId = 0, $state = 30, $threadTag = "") {
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
        if (!empty($threadTag) && $threadTag != "undefined") {
            $threadTag = "{" . $threadTag . "}";
            $searchSql .= " AND (t.threadtag like :searchkey4)";
            $dataProperty->AddField("searchkey4", "%" . $threadTag . "%");
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
            $threadTag = "{" . $threadTag . "}";
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
            $threadTag = "{" . $threadTag . "}";
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
     *  根据用户回复进行前台调用
     * @param int $userGroupId 用户组ID
     * @param int $pageBegin 起始数
     * @param int $pageSize 每页显示总数
     * @param int $allCount 总数
     * @param int $siteId 站点ID
     * @param int $order 排序
     * @param int $state 状态值
     * @param int $userId 用户ID
     * @param int $isPost 是否回复状态
     * @param int $documentChannelId 频道ID
     * @return arr 返回查询到的数据结果集
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
     * 前台聚合调用用，暂时停用
     * @param int $documentChannelId 频道ID号
     * @param int $topCount 要取的条数
     * @param int $order 排序
     * @param int $documentThreadTypeId 主题分类ID号
     * @param int $state 状态值
     * @param int $srchFrom 时间间隔
     * @return arr 返回查询到的数据结果集
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

    /**
     * 根据tableIdName到得站点ID
     * @param int $tableIdValue 主题ID号
     * @return int 返回查询到的站点ID号
     */
    public function GetSiteID($tableIdValue) {
        $sql = "SELECT c.siteid FROM " . self::tableName . " p,cst_documentchannel c WHERE p.documentchannelid=c.documentchannelid AND p." . self::tableIdName . "=:" . self::tableIdName;
        $dataProperty = new DataProperty();
        $dataProperty->AddField(self::tableIdName, $tableIdValue);
        $dboPerator = DBOperator::getInstance();
        $result = $dboPerator->ReturnInt($sql, $dataProperty);
        return $result;
    }

}

?>
