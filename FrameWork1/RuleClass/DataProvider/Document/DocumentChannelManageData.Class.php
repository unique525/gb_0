<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DocumentChannelManageData
 *
 * @author zhangchi
 */
class DocumentChannelManageData extends BaseManageData {
    /**
     * 表名
     */
    const tableName = "cst_documentchannel";

    /**
     * 表关键字段名
     */
    const tableIdName = "documentchannelid";

    /**
     * 新增频道
     * @param string $titlePic1 题图1
     * @param string $titlePic2 题图2
     * @param string $titlePic3 题图3
     * @return int 新增的频道id 
     */
    public function Create($titlePic1 = "", $titlePic2 = "", $titlePic3 = "") {
        $dataProperty = new DataProperty();
        $addFieldName = "";
        $addFieldValue = "";
        $preNumber = "";
        $addFieldNames = array("TitlePic1", "TitlePic2", "TitlePic3");
        $addFieldValues = array($titlePic1, $titlePic2, $titlePic3);
        $sql = parent::GetInsertSql(self::tableName, $dataProperty, $addFieldName, $addFieldValue, $preNumber, $addFieldNames, $addFieldValues);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->LastInsertId($sql, $dataProperty);
        $siteId = Control::PostRequest("f_siteid", 0);

        if ($result > 0) {
            //活动类的默认添加Class分类====Ljy
            $documentChannelType = Control::PostRequest("f_documentchanneltype", 1);
            if ($documentChannelType == 6) {
                $activityClassName = "默认";
                $state = 0;
                $activityType = 0;     //0为线下活动
                $activityClsaaData = new ActivityClassData();
                $activityClsaaData->CreateInt($siteId, $result, $activityClassName, $state, $activityType);
            }

            //授权给创建人
            $adminUserId = Control::GetAdminUserID();

            if ($adminUserId > 1) { //只有非ADMIN的要授权
                $adminPopedomData = new AdminPopedomData();
                $adminPopedomData->CreateForDocumentChannel($siteId, $result, $adminUserId);
            }

            //删除缓冲
            $cacheDir = 'data' . DIRECTORY_SEPARATOR . 'docdata';
            DataCache::RemoveDir($cacheDir);
        }

        return $result;
    }

    /**
     * 新增站点时默认新增首页频道
     * @param int $siteId 站点id
     * @return int 新增的频道id
     */
    public function CreateWhenSiteCreate($siteId) {
        if ($siteId > 0) {
            $adminUserId = Control::GetAdminUserID();
            $documentChannelName = "首页";

            $dataProperty = new DataProperty();
            $sql = "insert into " . self::tableName . " (siteid,createdate,adminuserid,documentchannelname) values (:siteid,now(),:adminuserid,:documentchannelname)";

            $dataProperty->AddField("siteid", $siteId);
            $dataProperty->AddField("adminuserid", $adminUserId);
            $dataProperty->AddField("documentchannelname", $documentChannelName);

            $dbOperator = DBOperator::getInstance();
            $result = $dbOperator->LastInsertId($sql, $dataProperty);

            if ($result > 0) {
                //授权给创建人

                if ($adminUserId > 1) { //只有非ADMIN的要授权
                    $adminPopedomData = new AdminPopedomData();
                    $adminPopedomData->CreateForDocumentChannel($siteId, $result, $adminUserId);
                }

                //删除缓冲
                $cacheDir = 'data' . DIRECTORY_SEPARATOR . 'docdata';
                DataCache::RemoveDir($cacheDir);
            }

            return $result;
        }
    }

    /**
     * 修改频道
     * @param int $documentChannelId 频道id
     * @param string $titlePic1 题图1
     * @param string $titlePic2 题图2
     * @param string $titlePic3 题图3
     * @return int 修改影响的行数
     */
    public function Modify($documentChannelId, $titlePic1 = "", $titlePic2 = "", $titlePic3 = "") {
        $addFieldName = "";
        $addFieldValue = "";
        $preNumber = "";
        $addFieldNames = array();
        $addFieldValues = array();
        if (!empty($titlePic1)) {
            $addFieldNames[] = "TitlePic1";
            $addFieldValues[] = $titlePic1;
        }
        if (!empty($titlePic2)) {
            $addFieldNames[] = "TitlePic2";
            $addFieldValues[] = $titlePic2;
        }
        if (!empty($titlePic3)) {
            $addFieldNames[] = "TitlePic3";
            $addFieldValues[] = $titlePic3;
        }
        $dataProperty = new DataProperty();
        $sql = parent::GetUpdateSql(self::tableName, self::tableIdName, $documentChannelId, $dataProperty, $addFieldName, $addFieldValue, $preNumber, $addFieldNames, $addFieldValues);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->Execute($sql, $dataProperty);

        if ($result > 0) {
            //删除缓冲
            $cacheDir = 'data' . DIRECTORY_SEPARATOR . 'docdata';
            DataCache::RemoveDir($cacheDir);
        }

        return $result;
    }

    public function ModifyInvisible($parentId, $invisible) {
        $sql = "UPDATE " . self::tableName . " SET Invisible=:Invisible WHERE ParentId=:ParentId;";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("Invisible", $invisible);
        $dataProperty->AddField("ParentId", $parentId);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 删除频道到回收站
     * @param int $documentChannelId 频道id
     * @return int 修改影响的行数
     */
    function RemoveBin($documentChannelId) {
        $sql = "update cst_documentchannel set state=100 where documentchannelid=:documentchannelid";
        //$sql2 = "update cst_documentchannel set state=100 where documentchannelid in (=:documentchannelid)";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("documentchannelid", $documentChannelId);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->Execute($sql, $dataProperty);

        if ($result > 0) {
            //删除缓冲
            $cacheDir = 'data' . DIRECTORY_SEPARATOR . 'docdata';
            DataCache::RemoveDir($cacheDir);

            self::RemoveBinChild($documentChannelId);
        }

        return $result;
    }

    /**
     * 删除全部下属频道到回收站
     * @param int $documentChannelId 频道id
     */
    public function RemoveBinChild($documentChannelId) {
        $sql = "select DocumentChannelID from cst_documentchannel where parentid=" . $documentChannelId;
        $dbOperator = DBOperator::getInstance();
        $arrChild = $dbOperator->ReturnArray($sql, null);
        for ($i = 0; $i < count($arrChild); $i++) {
            $sql = "update cst_documentchannel set state=100 where documentchannelid=" . $arrChild[$i]["DocumentChannelID"];
            $dbOperator->Execute($sql, null);
            self::RemoveBinChild($arrChild[$i]["DocumentChannelID"]);
        }
    }

    /**
     * 返回所有此站点下的频道列表,ZTREE使用
     * @param type $siteid
     * @param type $adminuserid
     * @return type 
     */
    public function GetListAllForZtree($siteid, $adminuserid) {
        $dataProperty = new DataProperty();
        if ($adminuserid == 1) {
            $sql = "select dc.DocumentChannelID,dc.ParentID,dc.DocumentChannelType,dc.DocumentChannelName,dc.Rank,(select count(*) from cst_documentchannel where parentid=dc.DocumentChannelID AND State<100) as childcounts from cst_documentchannel dc where dc.state<100 and dc.siteid=:siteid AND dc.Invisible=0 order by dc.sort desc,dc.documentchannelid";
        } else {
            $sql = "select dc.DocumentChannelID,dc.ParentID,dc.DocumentChannelType,dc.DocumentChannelName,dc.Rank,(select count(*) from cst_documentchannel where parentid=dc.DocumentChannelID AND State<100) as childcounts from cst_documentchannel dc where dc.state<100 and dc.siteid=:siteid AND dc.Invisible=0 and dc.documentchannelid in  (select documentchannelid from cst_adminpopedom where explore=1 and adminuserid=:adminuserid union select documentchannelid from cst_adminpopedom where explore=1 and adminusergroupid in (select adminusergroupid from cst_adminuser where adminuserid=:adminuserid2) union select documentchannelid from cst_documentchannel where siteid in (select siteid from cst_adminpopedom where explore=1 and documentchannelid=0 and adminuserid=0 and adminusergroupid in (select adminusergroupid from cst_adminuser where adminuserid=:adminuserid3))) order by dc.sort desc,dc.documentchannelid";
            $dataProperty->AddField("adminuserid", $adminuserid);
            $dataProperty->AddField("adminuserid2", $adminuserid);
            $dataProperty->AddField("adminuserid3", $adminuserid);
        }
        $dataProperty->AddField("siteid", $siteid);
        $result = $this->dbOperator->ReturnArray($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得频道所属站点id
     * @param int $documentChannelId 频道id
     * @return int 站点id
     */
    public function GetSiteId($documentChannelId) {
        $sql = "SELECT SiteId FROM " . self::tableName . " WHERE DocumentChannelId=:DocumentChannelId;";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("DocumentChannelId", $documentChannelId);
        $result = $this->dbOperator->ReturnInt($sql, $dataProperty);
        return $result;
    }
    
    /**
     * 取得频道Rank
     * @param int $documentChannelId 频道id
     * @return int 频道Rank
     */
    public function GetRank($documentChannelId) {
        $sql = "SELECT Rank FROM " . self::tableName . " WHERE DocumentChannelId=:DocumentChannelId;";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("DocumentChannelId", $documentChannelId);
        $result = $this->dbOperator->ReturnInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得频道是否定义了FTP
     * @param int $documentChannelId 频道id
     * @return int 频道是否定义了FTP 0:未定义 1:已定义
     */
    public function GetHasFtp($documentChannelId) {
        $sql = "SELECT HasFtp FROM " . self::tableName . " WHERE DocumentChannelId=:DocumentChannelId;";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("DocumentChannelId", $documentChannelId);
        $result = $this->dbOperator->ReturnInt($sql, $dataProperty);
        return $result;
    }
    
    
    /**
     * 取得频道类型编号
     * @param int $documentChannelId 频道id
     * @return int 站点id
     */
    public function GetDocumentChannelType($documentChannelId) {
        $sql = "SELECT DocumentChannelType FROM " . self::tableName . " WHERE documentchannelid=:documentchannelid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("documentchannelid", $documentChannelId);
        $result = $this->dbOperator->ReturnInt($sql, $dataProperty);
        return $result;
    }
    
    

}

?>
