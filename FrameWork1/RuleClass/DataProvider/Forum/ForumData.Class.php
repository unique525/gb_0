<?php

/**
 * 前台论坛数据类  
 * @category iCMS
 * @package iCMS_Rules_DataProvider_Forum
 * @author zhangchi
 */
class ForumData extends BaseFrontData {
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

    public function GetList($SiteID, $ForumRank) {
        $sql = "SELECT * FROM " . self::tableName . " WHERE ForumRank=:ForumRank AND SiteID=:SiteID ORDER BY sort DESC";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("ForumRank", $ForumRank);
        $dataProperty->AddField("SiteID", $SiteID);
        $result = $this->dbOperator->ReturnArray($sql, $dataProperty);
        return $result;
    }

    public function GetListByParentID($siteid, $parentid) {
        $sql = "SELECT * FROM " . self::tableName . " WHERE parentid=:parentid AND SiteID=:siteid ORDER BY sort DESC";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("parentid", $parentid);
        $dataProperty->AddField("siteid", $siteid);
        $result = $this->dbOperator->ReturnArray($sql, $dataProperty);
        return $result;
    }

    /**
     * 根据ForumId取得SiteId
     * @param type $forumId
     * @return type 
     */
    public function GetSiteId($forumId) {
        if ($forumId > 0) {
            $dataProperty = new DataProperty();
            $sql = "SELECT SiteId FROM " . self::tableName . " WHERE ForumId=:ForumId";
            $dataProperty->AddField("ForumId", $forumId);
            $result = $this->dbOperator->ReturnInt($sql, $dataProperty);
            return $result;
        }
    }

    /**
     * 根据ForumId取得ForumRule
     * @param type $ForumId
     * @return type 
     */
    public function GetForumRule($ForumId) {
        if ($ForumId > 0) {
            $dataProperty = new DataProperty();
            $sql = "SELECT ForumRule FROM " . self::tableName . " WHERE ForumId=:ForumId";
            $dataProperty->AddField("ForumId", $ForumId);
            $result = $this->dbOperator->ReturnString($sql, $dataProperty);
            return $result;
        }
    }

    /**
     * 根据ForumId取得审核类型
     * @param type $ForumId
     * @return type 
     */
    public function GetForumAuditType($ForumId) {
        if ($ForumId > 0) {
            $dataProperty = new DataProperty();
            $sql = "SELECT ForumAuditType FROM " . self::tableName . " WHERE ForumId=:ForumId";
            $dataProperty->AddField("ForumId", $ForumId);
            $result = $this->dbOperator->ReturnInt($sql, $dataProperty);
            return $result;
        }
    }

    public function GetListPager($PageBegin, $pageSize, &$_allCount, $State = -1) {
        $_searchsql = "";
        $_dataProperty = new DataProperty();
        if ($State >= 0) {
            $_searchsql .= " AND f.state=:state ";
            $_dataProperty->AddField("state", $State);
        } else {
            $_searchsql .= " ";
        }
        $_sql = "SELECT
            f.forumid,f.state,f.forumname,f.forumaccess
            FROM
            " . self::tableName . " f
            WHERE f.forumid>0  " . $_searchsql . " ORDER BY f.sort DESC LIMIT " . $PageBegin . "," . $pageSize . "";
        $_result = $this->dbOperator->ReturnArray($_sql, $_dataProperty);
        //统计总数
        $_sql = "";
        $_sql = "SELECT count(*) FROM " . self::tableName . " f WHERE f.forumid>0 " . $_searchsql;
        $_allCount = $this->dbOperator->ReturnInt($_sql, $_dataProperty);
        return $_result;
    }

    /**
     * 根据$TableIDValue到得编辑时所需的信息
     * @param <type> $TableIDValue
     * @return <type>
     */
    public function GetOne($TableIDValue) {
        $_dataProperty = new DataProperty();
        $_sql = "SELECT forumid,parentid,forumrank,forumname,forumtype,forumaccess,forumguestaccess,forummode,forumaudittype,forumpic,foruminfo,forumrule,forumadcontent,sort,showonlineuser,autooptopic,autoaddtopictitlepre,usernamecolor,closeupload,state,siteid FROM  " . self::tableName . "  WHERE  " . self::tableIdName . "=:" . self::tableIdName;
        $_dataProperty->AddField(self::tableIdName, $TableIDValue);
        $_result = $this->dbOperator->ReturnRow($_sql, $_dataProperty);
        return $_result;
    }


}

?>
