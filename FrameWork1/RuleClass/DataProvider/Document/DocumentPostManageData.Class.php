<?php

/**
 * Description of DocumentPostManageData
 * 咨询问答模块回复后台数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Document
 * @author Liujunyi
 */
class DocumentPostManageData extends BaseManageData {
    /**
     * 表名
     */

    const tableName = "cst_documentpost";

    /**
     * 表关键字段名
     */
    const tableIdName = "documentpostid";

    /**
     * 新增信息
     * @return int 返回新增数据列号
     */
    public function Create() {
        $dataProperty = new DataProperty();
        $sql = parent::GetInsertSql(self::tableName, $dataProperty);
        $dboPerator = DBOperator::getInstance();
        $result = $dboPerator->LastInsertId($sql, $dataProperty);
        return $result;
    }

    /**
     * 修改信息
     * @param int $tableIdValue
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
     * 新增回复信息
     * @return int 返回数据执行结果
     */
    public function Reply() {
        $dataProperty = new DataProperty();
        $sql = parent::GetInsertSql(self::tableName, $dataProperty);
        $dboPerator = DBOperator::getInstance();
        $result = $dboPerator->LastInsertId($sql, $dataProperty);
        return $result;
    }

    /**
     * 根据主贴ID进行删除
     * @param int $documentThreadId
     * @return int 返回数据执行影响的行数
     */
    public function DeleteByThreadId($documentThreadId) {
        $sql = "DELETE FROM " . self::tableName . " WHERE documentthreadid=:documentthreadid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("documentthreadid", $documentThreadId);
        $dboPerator = DBOperator::getInstance();
        $result = $dboPerator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 根据回复信息ID进行删除
     * @param int $tableIdValue
     * @return int 返回数据执行影响的行数
     */
    public function DeleteByPostId($tableIdValue) {
        $sql = "DELETE " . self::tableName . " WHERE " . self::tableIdName . "=:" . self::tableIdName;
        $dataProperty = new DataProperty();
        $dataProperty->AddField(self::tableIdName, $tableIdValue);
        $dboPerator = DBOperator::getInstance();
        $result = $dboPerator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 增加新信息
     * @param int $documentThreadId 主题ID
     * @param int $documentChannelId 频道ID
     * @param int $isThread 是否为主题标识
     * @param string $subject 标题
     * @param string $createDate 创建时间
     * @param int $adminUserId 管理人员ID
     * @param string $adminUserName 管理人员用户名
     * @param int $userId 网友ID
     * @param string $userName 网友名称
     * @param int $userGroupId 网友用户组
     * @param string $guestName 网友游客名称
     * @param int $sort 排序
     * @param string $content 内容
     * @param string $uploadFiles 上传文件ID号
     * @return int 返回数据执行结果
     */
    public function CreatePost($documentThreadId, $documentChannelId, $isThread, $subject, $createDate, $adminUserId, $adminUserName, $userId, $userName, $userGroupId, $guestName, $sort, $content, $uploadFiles, $state = 0) {
        $sql = "INSERT INTO " . self::tableName . " (documentthreadid,documentchannelid,isthread,subject,createdate,adminuserid,adminusername,userid,username,usergroupid,guestname,sort,content,uploadfiles,ipaddress,agent,state) values (:documentthreadid,:documentchannelid,:isthread,:subject,:createdate,:adminuserid,:adminusername,:userid,:username,:usergroupid,:guestname,:sort,:content,:uploadfiles,:ipaddress,:agent,:state)";
        $ip = Control::GetIP();
        $agent = Control::GetOS();
        $agent = $agent . "与" . Control::GetBrowse();
        $content = str_ireplace("../", "/", $content);
        $content = str_ireplace("\\", "/", $content);
        $content = str_ireplace('/"', '"', $content);
        $dataProperty = new DataProperty();
        $dataProperty->AddField("documentthreadid", $documentThreadId);
        $dataProperty->AddField("documentchannelid", $documentChannelId);
        $dataProperty->AddField("isthread", $isThread);
        $dataProperty->AddField("subject", $subject);
        $dataProperty->AddField("createdate", $createDate);
        $dataProperty->AddField("adminuserid", $adminUserId);
        $dataProperty->AddField("adminusername", $adminUserName);
        $dataProperty->AddField("userid", $userId);
        $dataProperty->AddField("username", $userName);
        $dataProperty->AddField("usergroupid", $userGroupId);
        $dataProperty->AddField("guestname", $guestName);
        $dataProperty->AddField("sort", $sort);
        $dataProperty->AddField("content", $content);
        $dataProperty->AddField("uploadfiles", $uploadFiles);
        $dataProperty->AddField("ipaddress", $ip);
        $dataProperty->AddField("agent", $agent);
        $dataProperty->AddField("state", $state);
        $dboPerator = DBOperator::getInstance();
        $insertId = $dboPerator->LastInsertId($sql, $dataProperty);
        return $insertId;
    }

    /**
     * 主贴编辑时进行保存
     * @param int $documentChannelId 频道ID
     * @param string $subject 主题
     * @param string $content 内容
     * @param int $documentThreadId 主题ID
     * @param string $userName 用户名
     * @param string $guestName 用户游客名
     * @param string $uploadFiles 上传文件ID
     * @return int 返回数据执行影响的行数
     */
    public function UpdateThread($documentChannelId, $subject, $content, $documentThreadId, $userName, $guestName, $uploadFiles) {
        $dataProperty = new DataProperty();
        $content = str_ireplace("../", "/", $content);
        $content = str_ireplace("\\", "/", $content);
        $content = str_ireplace('/"', '"', $content);
        $sql = "UPDATE " . self::tableName . "  SET documentchannelid=:documentchannelid, subject=:subject, content=:content, guestname=:guestname, uploadfiles=:uploadfiles WHERE isthread=1 AND documentthreadid=:documentthreadid";
        $dataProperty->AddField("documentchannelid", $documentChannelId);
        $dataProperty->AddField("subject", $subject);
        $dataProperty->AddField("content", $content);
        //$dataProperty->AddField("username", $userName);
        $dataProperty->AddField("documentthreadid", $documentThreadId);
        $dataProperty->AddField("guestname", $guestName);
        $dataProperty->AddField("uploadfiles", $uploadFiles);
        $dboPerator = DBOperator::getInstance();
        $result = $dboPerator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 修改上传附件信息
     * @param string $uploadFile 上传文件ID
     * @param string $content 内容
     * @param int $tableIdValue 回复信息ID
     * @return int 返回数据执行影响的行数
     */
    public function ModifyUploadFile($uploadFile, $content, $tableIdValue) {
        if ($tableIdValue > 0) {
            $content = str_ireplace("../", "/", $content);
            $content = str_ireplace("\\", "/", $content);
            $content = str_ireplace('/"', '"', $content);
            $sql = "UPDATE " . self::tableName . " SET uploadfiles=:uploadfiles, content=:content WHERE " . self::tableIdName . "=:" . self::tableIdName;
            $dataProperty = new DataProperty();
            $dataProperty->AddField(self::tableIdName, $tableIdValue);
            $dataProperty->AddField("uploadfiles", $uploadFile);
            $dataProperty->AddField("content", $content);
            $dboPerator = DBOperator::getInstance();
            $result = $dboPerator->Execute($sql, $dataProperty);
            return $result;
        }
    }

    /**
     * 更新审核状态信息
     * @param int $tableIdValue 回复信息ID
     * @param int $state 状态
     * @param int $stateType 为1表示根据threadid进行处理,为0表示根据postid进行处理
     * @return int 返回数据执行影响的行数
     */
    public function UpdateState($tableIdValue, $state, $stateType) {
        $dataProperty = new DataProperty();
        if ($stateType == 1) {
            $sql = "UPDATE " . self::tableName . " SET state=:state WHERE isthread=1 AND documentthreadid=:documentthreadid";
            $dataProperty->AddField("documentthreadid", $tableIdValue);
        } elseif ($stateType == 0) {
            $sql = "UPDATE " . self::tableName . " SET state=:state WHERE " . self::tableIdName . "=:" . self::tableIdName;
            $dataProperty->AddField(self::tableIdName, $tableIdValue);
        }
        $dataProperty->AddField("state", $state);
        $dboPerator = DBOperator::getInstance();
        $result = $dboPerator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 更新评分数据操作
     * @param int $tableIdValue 回复ID
     * @param string $upStr $upStr 评分对应的字段名
     * @param string $upDate $upDate 更新的值
     * @return int 返回数据执行影响的行数
     * */
    public function UpdateRates($tableIdValue, $upStr, $upDate) {
        $dataProperty = new DataProperty();
        $sql = "UPDATE " . self::tableName . "  SET " . $upStr . "=:" . $upStr . "  WHERE  " . self::tableIdName . "=:" . self::tableIdName;
        if (is_float($upDate)) {
            $dataProperty->AddField($upStr, floatval($upDate));
        } else {
            $dataProperty->AddField($upStr, $upDate);
        }
        $dataProperty->AddField(self::tableIdName, $tableIdValue);
        $dboPerator = DBOperator::getInstance();
        $result = $dboPerator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 删除回复信息
     * @param int $tableIdValue 回复信息ID
     * @return int 返回数据执行影响的行数
     */
    public function DelPost($tableIdValue) {
        $sql = "DELETE " . self::tableName . " WHERE " . self::tableIdName . "=:" . self::tableIdName;
        $dataProperty = new DataProperty();
        $dataProperty->AddField(self::tableIdName, $tableIdValue);
        $dboPerator = DBOperator::getInstance();
        $result = $dboPerator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 主题信息进行移动
     * @param int $documentChannelId 目标频道ID
     * @param string $documentThreadIdArr 原信息的频道ID
     * @return int 返回数据执行影响的行数
     */
    public function Move($documentChannelId, $documentThreadIdArr) {
        $sql = "UPDATE " . self::tableName . " SET `documentchannelid`=:documentchannelid where documentthreadid in (" . $documentThreadIdArr . ")";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("documentchannelid", $documentChannelId);
        $dboPerator = DBOperator::getInstance();
        $result = $dboPerator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 跟贴回复数据处理
     * 可根据cid,tid,usergroupid,userid,state进行分类查询
     * @param int $pageBegin 起始数
     * @param int $pageSize 每页显示总数
     * @param int $allCount 总数
     * @param string $searchKey 查询关键字
     * @param int $documentChannelId 频道ID
     * @param int $documentThreadId 主题信息ID
     * @param int $userGroupId 用户组ID
     * @param int $userId 用户ID
     * @param int $state 状态
     * @return arr 返回数据结果集
     */
    public function GetListPager($pageBegin, $pageSize, &$allCount, $searchKey, $documentChannelId = 0, $documentThreadId = -1, $userGroupId = -1, $userId = -1, $state = -1) {
        $dataProperty = new DataProperty();
        $sql = "SELECT
            p.documentpostid,p.documentthreadid,p.documentchannelid,p.subject,
            p.createdate,p.userid,p.username,p.usergroupid,p.guestname,p.state,p.content,
            c.documentchannelname,
            g.usergroupname
            FROM
            " . self::tableName . "  p, cst_usergroup g, cst_documentchannel c
            WHERE p.isthread=0 AND p.documentchannelid=c.documentchannelid AND p.usergroupid=g.usergroupid";
        if ($documentChannelId > 0) {
            $sql .= " AND p.documentchannelid=:documentchannelid ";
            $dataProperty->AddField("documentchannelid", $documentChannelId);
        }
        if ($documentThreadId > 0) {
            $sql .= " AND p.documentthreadid=:documentthreadid ";
            $dataProperty->AddField("documentthreadid", $documentThreadId);
        }
        if ($userGroupId >= 0) {
            $sql .= " AND p.usergroupid=:usergroupid ";
            $dataProperty->AddField("usergroupid", $userGroupId);
        }
        if ($userId >= 0) {
            $sql .= " AND p.userid=:userid ";
            $dataProperty->AddField("userid", $userId);
        }
        if ($state >= 0) {
            $sql .= " AND p.state=:state ";
            $dataProperty->AddField("state", $state);
        }
        if (!empty($searchKey) && $searchKey != "undefined") {
            $sql .= " AND (p.subject like :searchkey1 or p.username like :searchkey3 or p.content like :searchkey4)";
            $dataProperty->AddField("searchkey1", "%" . $searchKey . "%");
            $dataProperty->AddField("searchkey4", "%" . $searchKey . "%");
            $dataProperty->AddField("searchkey3", "%" . $searchKey . "%");
        }
        $sql .=" ORDER BY p.sort DESC, p.documentpostid DESC LIMIT " . $pageBegin . "," . $pageSize . "";
        $dboPerator = DBOperator::getInstance();
        $result = $dboPerator->ReturnArray($sql, $dataProperty);

        $sql = "SELECT  count(*) FROM " . self::tableName . "  WHERE isthread=0";
        if ($documentChannelId > 0) {
            $sql .= " AND documentchannelid=:documentchannelid ";
            $dataProperty->AddField("documentchannelid", $documentChannelId);
        }
        if ($documentThreadId > 0) {
            $sql .= " AND documentthreadid=:documentthreadid ";
            $dataProperty->AddField("documentthreadid", $documentThreadId);
        }
        if ($userGroupId >= 0) {
            $sql .= " AND usergroupid=:usergroupid ";
            $dataProperty->AddField("usergroupid", $userGroupId);
        }
        if ($userId >= 0) {
            $sql .= " AND userid=:userid ";
            $dataProperty->AddField("userid", $userId);
        }
        if ($state >= 0) {
            $sql .= " AND state=:state ";
            $dataProperty->AddField("state", $state);
        }
        if (!empty($searchKey) > 0 && $searchKey != "undefined") {
            $sql .= " AND (subject like :searchkey1 or username like :searchkey3 or content like :searchkey4)";
            $dataProperty->AddField("searchkey1", "%" . $searchKey . "%");
            $dataProperty->AddField("searchkey4", "%" . $searchKey . "%");
            $dataProperty->AddField("searchkey3", "%" . $searchKey . "%");
        }
        $allCount = $dboPerator->ReturnInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 取回复数
     * @param string $searchKey 查询关键字
     * @param int $documentThreadId 主题ID
     * @param int $userGroupId 用户组ID
     * @param int $state 0,未审核,10正常,100删除
     * @return int 返回总数
     */
    public function GetRowNum($searchKey, $documentThreadId = -1, $userGroupId = -1, $state = -1) {
        $dataProperty = new DataProperty();
        $sql = "SELECT  count(*) FROM " . self::tableName . "  WHERE isthread=0";
        if ($documentThreadId >= 0) {
            $sql .= " AND documentthreadid=:documentthreadid ";
            $dataProperty->AddField("documentthreadid", $documentThreadId);
        }
        if ($userGroupId >= 0) {
            $sql .= " AND usergroupid=:usergroupid ";
            $dataProperty->AddField("usergroupid", $userGroupId);
        }
        if ($state >= 0) {
            $sql .= " AND state=:state ";
            $dataProperty->AddField("state", $state);
        }
        if (!empty($searchKey) && $searchKey != "undefined") {
            $sql .= " AND (subject like :searchkey1 or username like :searchkey3 or content like :searchkey4)";
            $dataProperty->AddField("searchkey1", "%" . $searchKey . "%");
            $dataProperty->AddField("searchkey4", "%" . $searchKey . "%");
            $dataProperty->AddField("searchkey3", "%" . $searchKey . "%");
        }
        $dboPerator = DBOperator::getInstance();
        $allCount = $dboPerator->ReturnInt($sql, $dataProperty);
        return $allCount;
    }

    /**
     * 按用户组统计
     * @param int $userGroupId 用户组ID
     * @return int 返回总数
     */
    public function GetUserGroupCount($userGroupId) {
        $dataProperty = new DataProperty();
        $sql = "SELECT  count(*) FROM " . self::tableName . "  WHERE usergroupid=:usergroupid";
        $dataProperty->AddField("usergroupid", $userGroupId);
        $dboPerator = DBOperator::getInstance();
        $allCount = $dboPerator->ReturnInt($sql, $dataProperty);
        return $allCount;
    }

    /**
     * HTML中取回复数据列表
     * @param string $searchKey 查询关键字
     * @param int $pageBegin 起始数
     * @param int $pageSize 每页显示数
     * @param int $documentThreadId 主题ID
     * @param int $userGroupId 用户组ID
     * @param int $state 状态
     * @return arr 返回数据结果集
     */
    public function GetHtmlListPager($searchKey, $pageBegin, $pageSize, $documentThreadId = -1, $userGroupId = -1, $state = -1) {
        $dataProperty = new DataProperty();
        $sql = "SELECT  documentpostid,documentthreadid,documentchannelid,subject,
            createdate,userid,username,usergroupid,guestname,state,content FROM
            " . self::tableName . "
            WHERE isthread=0 ";
        if ($documentThreadId >= 0) {
            $sql .= " AND documentthreadid=:documentthreadid ";
            $dataProperty->AddField("documentthreadid", $documentThreadId);
        }
        if ($userGroupId >= 0) {
            $sql .= " AND usergroupid=:usergroupid ";
            $dataProperty->AddField("usergroupid", $userGroupId);
        }
        if ($state >= 0) {
            $sql .= " AND state=:state ";
            $dataProperty->AddField("state", $state);
        }
        if (!empty($searchKey) && $searchKey != "undefined") {
            $sql .= " AND (subject like :searchkey1 or username like :searchkey3 or content like :searchkey4)";
            $dataProperty->AddField("searchkey1", "%" . $searchKey . "%");
            $dataProperty->AddField("searchkey4", "%" . $searchKey . "%");
            $dataProperty->AddField("searchkey3", "%" . $searchKey . "%");
        }
        $sql .=" ORDER BY sort DESC, createdate DESC, documentpostid DESC LIMIT " . $pageBegin . "," . $pageSize . "";
        $dboPerator = DBOperator::getInstance();
        $result = $dboPerator->ReturnArray($sql, $dataProperty);
        return $result;
    }

    /**
     * 详细信息浏览
     * @param int $documentThreadId 主题ID
     * @param int $pageBegin 起始数
     * @param int $topCount 要取的条数
     * @param int $isThread  $isThread 10表示取主贴，3取回复部门，0取网友回复
     * @param int $userGroupId 用户ID
     * @return arr 返回数据结果集
     */
    public function GetTedail($documentThreadId, $pageBegin, $topCount, $isThread, $userGroupId) {
        $dataProperty = new DataProperty();
        $sql = "SELECT p.documentpostid, p.guestname, p.state, p.isthread, p.subject, p.username, p.content, p.createdate, p.sort, p.usergroupid, g.usergroupname, p.rating FROM " . self::tableName . " p, cst_usergroup g WHERE p.usergroupid=g.usergroupid AND p.state=10 AND p.documentthreadid=:documentthreadid ";
        $dataProperty->AddField("documentthreadid", $documentThreadId);
        if ($isThread > 0 && $userGroupId < 0) {
            $sql .= " AND p.isthread=1 ";
        } elseif ($userGroupId >= 3) {
            $sql .= " AND p.isthread=0 AND p.usergroupid >= 3 ";
        } else {
            $sql .= " AND p.isthread=0 AND p.usergroupid=:usergroupid ";
            $dataProperty->AddField("usergroupid", $userGroupId);
        }
        $sql .= " ORDER BY p.sort DESC, p.createdate DESC, p.documentpostid DESC LIMIT " . $pageBegin . "," . $topCount;
        $dboPerator = DBOperator::getInstance();
        $result = $dboPerator->ReturnArray($sql, $dataProperty);
        return $result;
    }

    /**
     * 根据type取回复详细内容：1根据threadid取内容，0为根据postid取内容
     * @param int $tableIdValue 回复信息ID
     * @param int $type 分类:1根据threadid取内容，0为根据postid取内容
     * @param int $isThread 根据threadid时需用到
     * @return arr 返回数据结果集
     */
    public function GetOne($tableIdValue, $type, $isThread = 0) {
        $dataProperty = new DataProperty();
        if ($type == 1 && $isThread > 0) {
            $sql = "SELECT p.documentpostid,p.uploadfiles,p.documentthreadid,p.documentchannelid,p.isthread,p.subject,p.createdate,p.userid,p.username,p.usergroupid,p.guestname,p.state,p.content,p.sort,g.usergroupname FROM  " . self::tableName . " p, cst_usergroup g WHERE  p.usergroupid=g.usergroupid AND p.isthread=1 AND p.documentthreadid=:documentthreadid";
            $dataProperty->AddField("documentthreadid", $tableIdValue);
        } else {
            $sql = "SELECT p.documentpostid,p.uploadfiles,p.documentthreadid,p.documentchannelid,p.isthread,p.subject,p.createdate,p.userid,p.username,p.usergroupid,p.guestname,p.state,p.content,p.sort,g.usergroupname FROM  " . self::tableName . " p, cst_usergroup g WHERE p.usergroupid=g.usergroupid AND p.documentpostid=:" . self::tableIdName;
            $dataProperty->AddField(self::tableIdName, $tableIdValue);
        }
        $dboPerator = DBOperator::getInstance();
        $result = $dboPerator->ReturnRow($sql, $dataProperty);
        return $result;
    }

    /**
     * 根据主题ID取到主题对应回复表中的postid
     * @param int $documentThreadId 主题ID
     * @return int 返回回复信息ID
     */
    public function GetPostID($documentThreadId) {
        $dataProperty = new DataProperty();
        $sql = "SELECT documentpostid FROM  " . self::tableName . "  WHERE isthread=1 AND documentthreadid=:documentthreadid";
        $dataProperty->AddField("documentthreadid", $documentThreadId);
        $dboPerator = DBOperator::getInstance();
        $result = $dboPerator->ReturnInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得网名guestname
     * @param int $documentThreadId 主题ID
     * @return string 游客名称
     */
    public function GetGuestName($documentThreadId) {
        $dataProperty = new DataProperty();
        $sql = "SELECT guestname FROM  " . self::tableName . "  WHERE isthread=1 AND documentthreadid=:documentthreadid";
        $dataProperty->AddField("documentthreadid", $documentThreadId);
        $dboPerator = DBOperator::getInstance();
        $result = $dboPerator->ReturnString($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得普通网友回复数
     * @param int $documentThreadId 主题ID
     * @return int 返回回复数
     */
    public function GetPostCount($documentThreadId) {
        $dataProperty = new DataProperty();
        $sql = "SELECT count(*) as postcount FROM  " . self::tableName . "  WHERE isthread<1 AND usergroupid<1 AND documentthreadid=:documentthreadid";
        $dataProperty->AddField("documentthreadid", $documentThreadId);
        $dboPerator = DBOperator::getInstance();
        $result = $dboPerator->ReturnInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 根据 $isThread及 $id取得上传图片信息,$isThread为1根据threadid取,为0根据postid取
     * @param int $tableIdValue 主题ID
     * @param int $isThread 是否主题标识：1根据threadid取,为0根据postid取
     * @return string 上传文件路径
     */
    public function GetUploaFiles($tableIdValue, $isThread) {
        $dataProperty = new DataProperty();
        if ($isThread == 1) {
            $sql = "SELECT uploadfiles FROM " . self::tableName . " WHERE isthread=1 AND documentthreadid=:documentthreadid";
            $dataProperty->AddField("documentthreadid", $tableIdValue);
        } elseif ($isThread == 0) {
            $sql = "SELECT uploadfiles FROM " . self::tableName . " WHERE " . self::tableIdName . "=:" . self::tableIdName;
            $dataProperty->AddField(self::tableIdName, $tableIdValue);
        }
        $dboPerator = DBOperator::getInstance();
        $result = $dboPerator->ReturnString($sql, $dataProperty);
        return $result;
    }

    /**
     * 
     * @param type $documentThreadId 根据threadid取到内容信息
     * @return string 返回回复内容
     */
    public function GetContentByThreadID($documentThreadId) {
        $dataProperty = new DataProperty();
        $sql = "SELECT content FROM " . self::tableName . " WHERE isthread=1 AND documentthreadid=:documentthreadid";
        $dataProperty->AddField("documentthreadid", $documentThreadId);
        $dboPerator = DBOperator::getInstance();
        $result = $dboPerator->ReturnString($sql, $dataProperty);
        return $result;
    }

    /**
     * 根据threadid取主题
     * @param int $documentThreadId 主题ID
     * @return string 返回标题
     */
    public function GetSubjectByThreadID($documentThreadId) {
        $dataProperty = new DataProperty();
        $sql = "SELECT subject FROM " . self::tableName . " WHERE isthread=1 AND documentthreadid=:documentthreadid";
        $dataProperty->AddField("documentthreadid", $documentThreadId);
        $dboPerator = DBOperator::getInstance();
        $result = $dboPerator->ReturnString($sql, $dataProperty);
        return $result;
    }

    /**
     * 按月,按userid统计回复数
     * @param int $userId 网友用户ID
     * @param string $beginDate 起始时间
     * @param stirng $endDate 结束时间
     * @param int $state 状态
     * @return int 返回统计总数
     */
    public function GetCount($userId, $beginDate = 0, $endDate = 0, $state = 0) {
        $searchSql = "";
        if (strlen($beginDate) > 1) {
            $searchSql .= " AND createdate>='" . $beginDate . "'";
        }
        if (strlen($endDate) > 1) {
            $searchSql .= " AND createdate<'" . $endDate . "'";
        }
        $dataProperty = new DataProperty();
        //$sql = "select count(*) from cst_documentpost where (userid=6) and (t.subject like :searchkey1 CreateDate like '%2011-05%'";
        $sql = "SELECT  count(*) FROM " . self::tableName . "  WHERE userid=:userid " . $searchSql;
        $dataProperty->AddField("userid", $userId);
        $dboPerator = DBOperator::getInstance();
        $allCount = $dboPerator->ReturnInt($sql, $dataProperty);
        return $allCount;
    }

}

?>
