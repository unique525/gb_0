<?php
/**
 * 后台 会员权限 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author zhangchi
 */
class UserPopedomManageData extends BaseManageData {

    /**
     * 根据论坛ID和会员ID设置权限值
     * @param int $forumId
     * @param int $userId
     * @param string $userPopedomName
     * @param string $userPopedomValue
     * @return int
     */
    public function SetValueByForumIdAndUserId(
        $forumId,
        $userId,
        $userPopedomName,
        $userPopedomValue
    ) {

        $sql = "SELECT count(*)

                FROM " . self::TableName_UserPopedom . "

                WHERE ForumId=:ForumId
                    AND UserId=:UserId
                    AND UserPopedomName=:UserPopedomName;
                ";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("ForumId", $forumId);
        $dataProperty->AddField("UserId", $userId);
        $dataProperty->AddField("UserPopedomName", $userPopedomName);
        $hasCount = $this->dbOperator->GetInt($sql, $dataProperty);

        if ($hasCount > 0) { //已存在相关配置记录
            $sql = "UPDATE " . self::TableName_UserPopedom . "
                    SET UserPopedomValue=:UserPopedomValue
                    WHERE ForumId=:ForumId
                    AND UserId=:UserId
                    AND UserPopedomName=:UserPopedomName;
                    ";
            $dataProperty->AddField("UserPopedomValue", $userPopedomValue);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        } else {
            $sql = "INSERT INTO " . self::TableName_UserPopedom . "
            (   ForumId,
                UserId,
                UserPopedomName,
                UserPopedomValue
                )
            VALUES (
                :ForumId,
                :UserId,
                :UserPopedomName,
                :UserPopedomValue
                );";
            $dataProperty->AddField("UserPopedomValue", $userPopedomValue);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 根据站点ID和会员组ID设置权限值
     * @param int $siteId
     * @param int $userGroupId
     * @param string $userPopedomName
     * @param string $userPopedomValue
     * @return int
     */
    public function SetValueBySiteIdAndUserGroupId(
        $siteId,
        $userGroupId,
        $userPopedomName,
        $userPopedomValue
    ) {

        $sql = "SELECT count(*)
                FROM " . self::TableName_UserPopedom . "

                WHERE SiteId=:SiteId
                AND UserGroupId=:UserGroupId
                AND UserPopedomName=:UserPopedomName;
                ";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("SiteId", $siteId);
        $dataProperty->AddField("UserGroupId", $userGroupId);
        $dataProperty->AddField("UserPopedomName", $userPopedomName);
        $hasCount = $this->dbOperator->GetInt($sql, $dataProperty);

        if ($hasCount > 0) { //已存在相关配置记录
            $sql = "UPDATE " . self::TableName_UserPopedom . " SET
                    UserPopedomValue=:UserPopedomValue
                    WHERE SiteId=:SiteId
                    AND UserGroupId=:UserGroupId
                    AND UserPopedomName=:UserPopedomName;";
            $dataProperty->AddField("UserPopedomValue", $userPopedomValue);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        } else {
            $sql = "INSERT INTO " . self::TableName_UserPopedom . "
                    (
                    SiteId,
                    UserGroupId,
                    UserPopedomName,
                    UserPopedomValue
                    )
                    VALUES
                    (
                    :SiteId,
                    :UserGroupId,
                    :UserPopedomName,
                    :UserPopedomValue
                    );";
            $dataProperty->AddField("UserPopedomValue", $userPopedomValue);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }
} 