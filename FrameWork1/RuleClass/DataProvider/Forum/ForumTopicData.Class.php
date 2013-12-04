<?php

/**
 * 前台论坛主题数据类  
 * @category iCMS
 * @package iCMS_Rules_DataProvider_Forum
 * @author zhangchi
 */
class ForumTopicData extends BaseFrontData {

    public function Create($forumTopicTitle, $forumTopicClass, $forumTopicTypeID, $forumTopicAudit, $forumTopicAccess, $forumID, $siteID, $postTime, $userID, $userName, $forumTopicMood, $forumTopicAttach, $forumTopicTag, $titleBold, $titleColor, $titleBgImage) {
        $dataProperty = new DataProperty();
        $sql = "INSERT INTO " . self::tableName . " ( ForumTopicTitle , ForumTopicClass,  ForumTopicTypeID,  ForumTopicAudit,  ForumTopicAccess,  ForumID,  SiteID,  PostTime,  UserID,  UserName,  LastPostUserID,  LastPostUserName, LastPostTime,  ForumTopicMood,  ForumTopicAttach,  ForumTopicTag,  TitleBold,  TitleColor,  TitleBgImage) 
                                              VALUES (:ForumTopicTitle, :ForumTopicClass, :ForumTopicTypeID, :ForumTopicAudit, :ForumTopicAccess, :ForumID, :SiteID, :PostTime, :UserID, :UserName, :LastPostUserID, :LastPostUserName,:LastPostTime, :ForumTopicMood, :ForumTopicAttach, :ForumTopicTag, :TitleBold, :TitleColor, :TitleBgImage);";
        $dbOperator = DBOperator::getInstance();
        $dataProperty->AddField("ForumTopicTitle", $forumTopicTitle);
        $dataProperty->AddField("ForumTopicClass", $forumTopicClass);
        $dataProperty->AddField("ForumTopicTypeID", $forumTopicTypeID);
        $dataProperty->AddField("ForumTopicAudit", $forumTopicAudit);
        $dataProperty->AddField("ForumTopicAccess", $forumTopicAccess);
        $dataProperty->AddField("ForumID", $forumID);
        $dataProperty->AddField("SiteID", $siteID);
        $dataProperty->AddField("PostTime", $postTime);
        $dataProperty->AddField("UserID", $userID);
        $dataProperty->AddField("UserName", $userName);
        $dataProperty->AddField("LastPostUserID", $userID);
        $dataProperty->AddField("LastPostUserName", $userName);
        $dataProperty->AddField("LastPostTime", $postTime);
        $dataProperty->AddField("ForumTopicMood", $forumTopicMood);
        $dataProperty->AddField("ForumTopicAttach", $forumTopicAttach);
        $dataProperty->AddField("ForumTopicTag", $forumTopicTag);
        $dataProperty->AddField("TitleBold", $titleBold);
        $dataProperty->AddField("TitleColor", $titleColor);
        $dataProperty->AddField("TitleBgImage", $titleBgImage);
        $newTopicId = $dbOperator->LastInsertId($sql, $dataProperty);

        if ($newTopicId > 0) {
            
        }

        return $newTopicId;
    }

    /**
     * 
     * @param int $siteId
     * @param type $forumId
     * @param type $pageBegin
     * @param int $pageSize
     * @param type $allCount
     * @return type
     */
    public function GetListPager($siteId, $forumId, $pageBegin, $pageSize, &$allCount) {
        if ($siteId > 0 && $forumId > 0) {

            $sql = "SELECT * FROM " . self::TableName_ForumTopic . " WHERE SiteId=:SiteId AND ForumId=:ForumId AND State<100 ORDER BY Sort DESC,LastPostTime DESC LIMIT " . $pageBegin . "," . $pageSize . "";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);
            $dataProperty->AddField("ForumId", $forumId);
            $dbOperator = DBOperator::getInstance();
            $result = $dbOperator->ReturnArray($sql, $dataProperty);

            $sql = "SELECT count(*) FROM " . self::TableName_ForumTopic . " WHERE SiteId=:SiteId AND ForumId=:ForumId AND State<100 ";
            $allCount = $dbOperator->ReturnInt($sql, $dataProperty);

            return $result;
        }
    }

}

?>
