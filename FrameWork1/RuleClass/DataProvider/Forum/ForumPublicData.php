<?php

/**
 * 前台 论坛 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Forum
 * @author zhangchi
 */
class ForumPublicData extends BasePublicData {



    /**
     * 更新版块的最后回复信息（发表主题时）
     * @param int $forumId
     * @param int $lastForumTopicId
     * @param string $lastForumTopicTitle
     * @param string $lastUserName
     * @param int $lastUserId
     * @param string $lastPostTime
     * @param string $lastPostInfo
     * @return int 执行结果
     */
    public function UpdateForumInfo($forumId, $lastForumTopicId, $lastForumTopicTitle, $lastUserName, $lastUserId, $lastPostTime, $lastPostInfo) {

        $result = -1;
        if($forumId>0){
            $sql = "UPDATE " . self::TableName_Forum . "
                    SET NewCount=NewCount+1,TopicCount=TopicCount+1,LastForumTopicId=:LastForumTopicId,LastForumTopicTitle=:LastForumTopicTitle,LastUserName=:LastUserName,LastUserId=:LastUserId,LastPostTime=:LastPostTime,LastPostInfo=:LastPostInfo
                    WHERE ForumId=:ForumId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ForumId", $forumId);
            $dataProperty->AddField("LastForumTopicId", $lastForumTopicId);
            $dataProperty->AddField("LastForumTopicTitle", $lastForumTopicTitle);
            $dataProperty->AddField("LastUserName", $lastUserName);
            $dataProperty->AddField("LastUserId", $lastUserId);
            $dataProperty->AddField("LastPostTime", $lastPostTime);
            $dataProperty->AddField("LastPostInfo", $lastPostInfo);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }

    /**
     * 根据版块等级取得版块列表
     * @param int $siteId 站点id
     * @param int $forumRank 版块等级
     * @return array|null 版块列表
     */
    public function GetListByForumRank($siteId, $forumRank) {
        $result = null;
        if($siteId>0 && $forumRank>=0){


            $sql = "
            SELECT f.*,
                        uf1.UploadFilePath AS ForumPic1UploadFilePath,

                        uf2.UploadFilePath AS ForumPic2UploadFilePath

            FROM
            " . self::TableName_Forum . " f
            LEFT OUTER JOIN " .self::TableName_UploadFile." uf1 ON (f.ForumPic1UploadFileId=uf1.UploadFileId)
            LEFT OUTER JOIN " .self::TableName_UploadFile." uf2 ON (f.ForumPic2UploadFileId=uf2.UploadFileId)

            WHERE f.State<".ForumData::STATE_REMOVED." AND f.ForumRank=:ForumRank AND f.SiteId=:SiteId ORDER BY f.Sort DESC;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ForumRank", $forumRank);
            $dataProperty->AddField("SiteId", $siteId);

            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }


        return $result;
    }

    /**
     * 
     * @param int $siteId
     * @param int $parentId
     * @return array
     */
    public function GetListByParentId($siteId, $parentId) {
        $sql = "SELECT * FROM " . self::TableName_Forum . " WHERE ParentId=:ParentId AND SiteId=:SiteId ORDER BY Sort DESC";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("ParentId", $parentId);
        $dataProperty->AddField("SiteId", $siteId);
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        return $result;
    }

    public function GetListPager($pageBegin, $pageSize, &$allCount, $state = -1) {
        $searchSql = "";
        $dataProperty = new DataProperty();
        if ($state >= 0) {
            $searchSql .= " AND f.state=:state ";
            $dataProperty->AddField("state", $state);
        } else {
            $searchSql .= " ";
        }
        $sql = "SELECT
            f.forumid,f.state,f.forumname,f.forumaccess
            FROM
            " . self::TableName_Forum . " f
            WHERE f.forumid>0  " . $searchSql . " ORDER BY f.sort DESC LIMIT " . $pageBegin . "," . $pageSize . "";
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        //统计总数
        $sql = "";
        $sql = "SELECT count(*) FROM " . self::tableName . " f WHERE f.forumid>0 " . $searchSql;
        $allCount = $this->dbOperator->GetInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 根据ForumId取得SiteId
     * @param int $forumId 版块id
     * @return int SiteId
     */
    public function GetSiteId($forumId) {
        if ($forumId > 0) {
            $dataProperty = new DataProperty();
            $sql = "SELECT SiteId FROM " . self::TableName_Forum . " WHERE ForumId=:ForumId";
            $dataProperty->AddField("ForumId", $forumId);
            $result = $this->dbOperator->GetInt($sql, $dataProperty);
            return $result;
        }
    }

    /**
     * 根据ForumId取得ForumRule
     * @param int $forumId 版块id
     * @return string ForumRule 
     */
    public function GetForumRule($forumId) {
        if ($forumId > 0) {
            $dataProperty = new DataProperty();
            $sql = "SELECT ForumRule FROM " . self::TableName_Forum . " WHERE ForumId=:ForumId";
            $dataProperty->AddField("ForumId", $forumId);
            $result = $this->dbOperator->GetString($sql, $dataProperty);
            return $result;
        }
    }

    /**
     * 根据ForumId取得审核类型
     * @param int $forumId 版块id
     * @return int 审核类型
     */
    public function GetForumAuditType($forumId) {
        if ($forumId > 0) {
            $dataProperty = new DataProperty();
            $sql = "SELECT ForumAuditType FROM " . self::TableName_Forum . " WHERE ForumId=:ForumId";
            $dataProperty->AddField("ForumId", $forumId);
            $result = $this->dbOperator->GetInt($sql, $dataProperty);
            return $result;
        }
    }

    /**
     * 取得版块每排显示数量
     * @param int $forumId 版块id
     * @return int 版块每排显示数量
     */
    public function GetShowColumnCount($forumId) {
        $result = 0;
        if ($forumId > 0) {
            $cacheDir = 'data' . DIRECTORY_SEPARATOR . 'forumdata';
            $cacheFile = 'forum_showcolumncount.cache_' . $forumId . '.php';
            if (parent::IsDataCached($cacheDir, $cacheFile)) {
                $dataProperty = new DataProperty();
                $sql = "SELECT ShowColumnCount FROM " . self::TableName_Forum . " WHERE ForumId=:ForumId";
                $dataProperty->AddField("ForumId", $forumId);
                $result = $this->dbOperator->GetInt($sql, $dataProperty);
                DataCache::Set($cacheDir, $cacheFile, $result);
            } else {
                $result = intval(DataCache::Get($cacheDir . DIRECTORY_SEPARATOR . $cacheFile));
            }
        }

        return $result;
    }

    /**
     * 取得一条记录
     * @param int $forumId 版块id
     * @return array 一条记录
     */
    public function GetOne($forumId) {
        $dataProperty = new DataProperty();
        $sql = "SELECT forumid,parentid,forumrank,forumname,forumtype,forumaccess,forumguestaccess,forummode,forumaudittype,forumpic,foruminfo,forumrule,forumadcontent,sort,showonlineuser,autooptopic,autoaddtopictitlepre,usernamecolor,closeupload,state,siteid FROM  " . self::tableName . "  WHERE  " . self::tableIdName . "=:" . self::tableIdName;
        $dataProperty->AddField(self::TableId_Forum, $forumId);
        $result = $this->dbOperator->GetArray($sql, $dataProperty);
        return $result;
    }

}

?>
