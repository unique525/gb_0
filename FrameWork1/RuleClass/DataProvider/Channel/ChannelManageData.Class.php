<?php

/**
 * 后台频道数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Channel
 * @author zhangchi
 */
class ChannelManageData extends BaseManageData {

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
        $sql = parent::GetInsertSql(self::TableName_Channel, $dataProperty, $addFieldName, $addFieldValue, $preNumber, $addFieldNames, $addFieldValues);
        $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        $siteId = Control::PostRequest("f_siteid", 0);

        if ($result > 0) {
            //活动类的默认添加Class分类====Ljy
            $channelType = Control::PostRequest("f_documentchanneltype", 1);
            if ($channelType == 6) {
                $activityClassName = "默认";
                $state = 0;
                $activityType = 0;     //0为线下活动
                $activityClsaaData = new ActivityClassData();
                $activityClsaaData->CreateInt($siteId, $result, $activityClassName, $state, $activityType);
            }

            //授权给创建人
            $adminUserId = Control::GetAdminUserId();

            if ($adminUserId > 1) { //只有非ADMIN的要授权
                $adminPopedomData = new AdminPopedomData();
                $adminPopedomData->CreateForDocumentChannel($siteId, $result, $adminUserId);
            }

            //删除缓冲
            $cacheDir = self::CacheDir . DIRECTORY_SEPARATOR . 'docdata';
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
            $adminUserId = Control::GetAdminUserId();
            $documentChannelName = "首页";

            $dataProperty = new DataProperty();
            $sql = "insert into " . self::TableName_Channel . " (siteid,createdate,adminuserid,documentchannelname) values (:siteid,now(),:adminuserid,:documentchannelname)";

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
     * @param int $siteId
     * @param int $adminUserId
     * @return array 
     */
    public function GetListAllForZtree($siteId, $adminUserId) {
        $dataProperty = new DataProperty();
        if ($adminUserId == 1) {
            $sql = "SELECT dc.*,(SELECT Count(*) FROM " . self::TableName_Channel . " WHERE ParentId=dc.ChannelId AND State<100) as ChildCounts FROM " . self::TableName_Channel . " dc WHERE dc.State<100 AND dc.SiteId=:SiteId AND dc.Invisible=0 ORDER BY dc.Sort DESC,dc.ChannelId";
        } else {
            $sql = "SELECT dc.*,(SELECT Count(*) FROM " . self::TableName_Channel . " WHERE ParentId=dc.ChannelId AND State<100) as ChildCounts FROM " . self::TableName_Channel . " dc WHERE dc.State<100 AND dc.SiteId=:SiteId AND dc.Invisible=0 AND dc.ChannelId IN
                (   
                    SELECT ChannelId FROM " . self::TableName_AdminPopedom . " WHERE Explore=1 AND AdminUserId=:AdminUserId
                    UNION
                    SELECT ChannelId FROM " . self::TableName_AdminPopedom . " WHERE Explore=1 AND AdminUserGroupId IN 
                        (
                         SELECT AdminUserGroupId FROM " . self::TableName_AdminUser . " WHERE AdminUserId=:AdminUserId2)
                         UNION 
                         SELECT ChannelId FROM " . self::TableName_Channel . " WHERE SiteId IN (SELECT SiteId FROM " . self::TableName_AdminPopedom . " WHERE Explore=1 AND ChannelId=0 AND AdminUserId=0 AND AdminUserGroupId IN (SELECT AdminUserGroupId FROM " . self::TableName_AdminUser . " WHERE AdminUserId=:AdminUserId3)
                         )
                 ) ORDER BY dc.Sort DESC,dc.ChannelId;";

            $dataProperty->AddField("AdminUserId", $adminUserId);
            $dataProperty->AddField("AdminUserId2", $adminUserId);
            $dataProperty->AddField("AdminUserId3", $adminUserId);
        }
        $dataProperty->AddField("SiteId", $siteId);
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
