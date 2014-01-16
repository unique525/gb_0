<?php

/**
 * 前台论坛数据类  
 * @category iCMS
 * @package iCMS_Rules_DataProvider_Forum
 * @author zhangchi
 */
class ForumData extends BasePublicData {
    /**
     * 表名
     */

    const tableName = "cst_forum";
    /**
     * 表关键字段名
     */
    const tableIdName = "ForumId";

    /**
     * 更新版块的最后回复信息（发表主题时）
     * @param type $forumId
     * @param type $lastTopicId
     * @param type $lastTopicTitle
     * @param type $lastUserName
     * @param type $lastUserId
     * @param type $lastPostTime
     * @param type $lastPostInfo
     * @return type 
     */
    public function UpdateForumInfo($forumId, $lastTopicId, $lastTopicTitle, $lastUserName, $lastUserId, $lastPostTime, $lastPostInfo) {
        $sql = "UPDATE " . self::tableName . " SET NewCount=NewCount+1,TopicCount=TopicCount+1,LastTopicID=:LastTopicID,LastTopicTitle=:LastTopicTitle,LastUserName=:LastUserName,LastUserID=:LastUserID,LastPostTime=:LastPostTime,LastPostInfo=:LastPostInfo WHERE ForumID=:ForumID;";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("ForumID", $forumId);
        $dataProperty->AddField("LastTopicID", $lastTopicId);
        $dataProperty->AddField("LastTopicTitle", $lastTopicTitle);
        $dataProperty->AddField("LastUserName", $lastUserName);
        $dataProperty->AddField("LastUserID", $lastUserId);
        $dataProperty->AddField("LastPostTime", $lastPostTime);
        $dataProperty->AddField("LastPostInfo", $lastPostInfo);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 根据版块等级取得版块列表
     * @param int $siteId 站点id
     * @param int $forumRank 版块等级
     * @return array 版块列表
     */
    public function GetListByRank($siteId, $forumRank) {
        $sql = "SELECT 
                ForumId, 
                SiteId, 
                ForumName, 
                ForumType, 
                ForumMode, 
                ForumAuditType, 
                ForumPic, 
                ForumInfo,
                ForumAdContent,
                ParentId,
                LastTopicId,
                LastTopicTitle,
                LastUserName,
                LastUserId,
                LastPostTime,
                LastPostInfo,
                NewCount, 
                TopicCount, 
                PostCount,
                State,
                ShowColumnCount,
                ForumNameFontColor,
                ForumNameFontBold,
                ForumNameFontSize
            FROM " . self::tableName . " WHERE State<100 AND ForumRank=:ForumRank AND SiteId=:SiteId ORDER BY Sort DESC;";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("ForumRank", $forumRank);
        $dataProperty->AddField("SiteId", $siteId);
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        return $result;
    }

    /**
     * 
     * @param type $siteId
     * @param type $parentId
     * @return type
     */
    public function GetListByParentId($siteId, $parentId) {
        $sql = "SELECT * FROM " . self::tableName . " WHERE parentid=:parentid AND SiteID=:siteid ORDER BY sort DESC";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("parentid", $parentId);
        $dataProperty->AddField("siteid", $siteId);
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
            " . self::tableName . " f
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
            $sql = "SELECT SiteId FROM " . self::tableName . " WHERE ForumId=:ForumId";
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
            $sql = "SELECT ForumRule FROM " . self::tableName . " WHERE ForumId=:ForumId";
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
            $sql = "SELECT ForumAuditType FROM " . self::tableName . " WHERE ForumId=:ForumId";
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
                $sql = "SELECT ShowColumnCount FROM " . self::tableName . " WHERE ForumId=:ForumId";
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
        $dataProperty->AddField(self::tableIdName, $forumId);
        $result = $this->dbOperator->GetRow($sql, $dataProperty);
        return $result;
    }

}

?>
