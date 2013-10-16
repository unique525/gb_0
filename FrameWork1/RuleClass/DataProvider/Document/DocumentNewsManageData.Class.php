<?php

/**
 * 后台资讯数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Document
 * @author zhangchi
 */
class DocumentNewsManageData extends BaseManageData {
    /**
     * 表名
     */

    const tableName = "cst_documentnews";

    /**
     * 表关键字段名
     */
    const tableIdName = "DocumentNewsId";

    /**
     * 取得后台资讯列表数据集
     * @param int $documentChannelId 频道id
     * @param int $pageBegin 起始记录行
     * @param int $pageSize 页大小
     * @param int $allCount 记录总数
     * @param type $searchKey 查询字符
     * @param type $searchTypeBox 查询下拉框的类别
     * @param int $isSelf 是否只显示当前登录的管理员录入的资讯
     * @param int $adminUserId 当前管理员id
     * @return array 资讯列表数据集
     */
    public function GetListForManage($documentChannelId, $pageBegin, $pageSize, &$allCount, $searchKey = "", $searchTypeBox = "", $isSelf = 0, $adminUserId = 0) {
        $searchSql = "";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("documentchannelid", $documentChannelId);
        if (strlen($searchKey) > 0 && $searchKey != "undefined") {
            if ($searchTypeBox == "source") {
                $searchSql = " AND (sourcename like :searchkey)";
                $dataProperty->AddField("searchkey", "%" . $searchKey . "%");
            } else {
                $searchSql = " AND (documentnewstitle like :searchkey1 or adminusername like :searchkey2 or username like :searchkey3 or documentnewstag like :searchkey4)";
                $dataProperty->AddField("searchkey1", "%" . $searchKey . "%");
                $dataProperty->AddField("searchkey2", "%" . $searchKey . "%");
                $dataProperty->AddField("searchkey3", "%" . $searchKey . "%");
                $dataProperty->AddField("searchkey4", "%" . $searchKey . "%");
            }
        }
        if ($isSelf === 1 && $adminUserId > 0) {
            $conditionAdminUserId = ' AND adminuserid=' . intval($adminUserId);
        }

        $sql = "
            SELECT
            documentnewsid,documentnewstype,documentnewstitle,state,sort,documentchannelid,publishdate,
            createdate,adminuserid,adminusername,username,documentnewstitlecolor,documentnewstitlebold,titlepic,reclevel,hit
            FROM
            " . self::tableName . "
            WHERE documentchannelid=:documentchannelid AND state<100 " . $searchSql . " " . $conditionAdminUserId . " ORDER BY sort DESC, CreateDate DESC LIMIT " . $pageBegin . "," . $pageSize . "";

        $result = $this->dbOperator->ReturnArray($sql, $dataProperty);

        $dataProperty = new DataProperty();
        $dataProperty->AddField("documentchannelid", $documentChannelId);
        if (strlen($searchKey) > 0 && $searchKey != "undefined") {
            $searchSql = " and (documentnewstitle like :searchkey1 or adminusername like :searchkey2 or username like :searchkey3)";
            $dataProperty->AddField("searchkey1", "%" . $searchKey . "%");
            $dataProperty->AddField("searchkey2", "%" . $searchKey . "%");
            $dataProperty->AddField("searchkey3", "%" . $searchKey . "%");
        }

        $sql = "SELECT count(*) FROM " . self::tableName . " WHERE documentchannelid=:documentchannelid and state<100 " . $conditionAdminUserId . " " . $searchSql;
        $allCount = $this->dbOperator->ReturnInt($sql, $dataProperty);

        return $result;
    }

    /**
     * 拖动排序
     * @param array $arrDocumentNewsId 待处理的文档编号数组
     */
    public function UpdateSort($arrDocumentNewsId) {
        if (count($arrDocumentNewsId) > 1) { //大于1条时排序才有意义
            $strDocumentNewsId = join(',', $arrDocumentNewsId);

            $maxSort = 0;
            $sql = "SELECT max(Sort) FROM " . self::tableName . " WHERE DocumentNewsId IN ($strDocumentNewsId)";
            $maxSort = $this->dbOperator->ReturnInt($sql, null);
            $arrSql = array();
            for ($i = 0; $i < count($arrDocumentNewsId); $i++) {
                $newSort = $maxSort - $i;
                if ($newSort < 0) {
                    $newSort = 0;
                }
                $sql = "UPDATE " . self::tableName . " SET Sort=$newSort WHERE DocumentNewsId=$arrDocumentNewsId[$i];";
                $arrSql[] = $sql;
            }
            $this->dbOperator->ExecuteBatch($arrSql, null);
        }
    }

    /**
     * 修改锁定状态和时间
     * @param int $lockEdit 是否锁定
     * @param int $documentNewsId 文档id
     * @param int $adminUserId 操作管理员id
     * @return int 操作结果
     */
    public function ModifyLockEdit($lockEdit, $documentNewsId, $adminUserId) {
        $result = 0;
        if ($documentNewsId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::tableName . " SET `LockEdit`=:LockEdit,LockEditDate=now(),LockEditAdminUserId=:LockEditAdminUserId WHERE DocumentNewsId=:DocumentNewsId";
            $dataProperty->AddField("LockEdit", $lockEdit);
            $dataProperty->AddField("DocumentNewsId", $documentNewsId);
            $dataProperty->AddField("LockEditAdminUserId", $adminUserId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    
    //////////////////////////////////////////////////////////////////////////////
    //////////////////////////////Get Info////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////

    /**
     * 取得文档的所属频道id
     * @param int $documentNewsId 文档id
     * @return int 所属频道id
     */
    public function GetDocumentChannelId($documentNewsId) {
        $sql = "SELECT DocumentChannelId FROM " . self::tableName . " WHERE DocumentNewsId=:DocumentNewsId;";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("DocumentNewsId", $documentNewsId);
        $result = $this->dbOperator->ReturnInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得文档的管理员(发稿人)id
     * @param int $documentNewsId 文档id
     * @return int 管理员(发稿人)id
     */
    public function GetAdminUserId($documentNewsId) {
        $sql = "SELECT AdminUserId FROM " . self::tableName . " WHERE DocumentNewsId=:DocumentNewsId;";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("DocumentNewsId", $documentNewsId);
        $result = $this->dbOperator->ReturnInt($sql, $dataProperty);
        return $result;
    }
    
    /**
     * 取得文档的状态
     * @param int $documentNewsId 文档id
     * @return int 文档的状态
     */
    public function GetState($documentNewsId){
        $sql = "SELECT State FROM " . self::tableName . " WHERE DocumentNewsId=:DocumentNewsId;";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("DocumentNewsId", $documentNewsId);
        $result = $this->dbOperator->ReturnInt($sql, $dataProperty);
        return $result;
    }
    
    /**
     * 取得文档是否锁定编辑
     * @param int $documentNewsId 文档id
     * @return int 是否锁定编辑 0:未锁定 1:已锁定
     */
    public function GetLockEdit($documentNewsId){
        $sql = "SELECT LockEdit FROM " . self::tableName . " WHERE DocumentNewsId=:DocumentNewsId;";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("DocumentNewsId", $documentNewsId);
        $result = $this->dbOperator->ReturnInt($sql, $dataProperty);
        return $result;
    }
    
    /**
     * 取得文档锁定编辑的时间
     * @param int $documentNewsId 文档id
     * @return string 锁定编辑的时间
     */
    public function GetLockEditDate($documentNewsId){
        $sql = "SELECT LockEditDate FROM " . self::tableName . " WHERE DocumentNewsId=:DocumentNewsId;";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("DocumentNewsId", $documentNewsId);
        $result = $this->dbOperator->ReturnString($sql, $dataProperty);
        return $result;
    }
    
    /**
     * 取得文档锁定编辑的操作管理员id
     * @param int $documentNewsId 文档id
     * @return int 锁定编辑的操作管理员id
     */
    public function GetLockEditAdminUserId($documentNewsId){
        $sql = "SELECT LockEditAdminUserId FROM " . self::tableName . " WHERE DocumentNewsId=:DocumentNewsId;";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("DocumentNewsId", $documentNewsId);
        $result = $this->dbOperator->ReturnInt($sql, $dataProperty);
        return $result;
    }
    
    /**
     * 取得文档的发布时间
     * @param int $documentNewsId 文档id
     * @return string 文档的发布时间
     */
    public function GetPublishDate($documentNewsId){
        $sql = "SELECT PublishDate FROM " . self::tableName . " WHERE DocumentNewsId=:DocumentNewsId;";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("DocumentNewsId", $documentNewsId);
        $result = $this->dbOperator->ReturnString($sql, $dataProperty);
        return $result;
    }
    
    /**
     * 取得文档的内容
     * @param int $documentNewsId 文档id
     * @return string 文档的内容
     */
    public function GetDocumentNewsContent($documentNewsId){
        $sql = "SELECT DocumentNewsContent FROM " . self::tableName . " WHERE DocumentNewsId=:DocumentNewsId;";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("DocumentNewsId", $documentNewsId);
        $result = $this->dbOperator->ReturnString($sql, $dataProperty);
        return $result;
    }

}

?>
