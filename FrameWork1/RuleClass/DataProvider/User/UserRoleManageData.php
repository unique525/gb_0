<?php
/**
 * 后台管理 会员角色 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author zhangchi
 */
class UserRoleManageData extends BaseManageData {
    public function GetUserGroupId($userId,$siteId = 0,$channelId = 0){
        if ($siteId <= 0 && $channelId <= 0) {
            return null;
        } elseif ($channelId <= 0 && $siteId > 0) {
            $sql = "SELECT UserGroupId from ".self::TableName_UserRole." WHERE UserId=:UserId AND SiteId=:SiteId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId", $userId);
            $dataProperty->AddField("SiteId", $siteId);
            return $this->dbOperator->GetInt($sql, $dataProperty);
        } elseif ($channelId > 0) {
            $sql = "SELECT UserGroupId from ".self::TableName_UserRole." WHERE UserId=:UserId AND ChannelId=:ChannelId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId", $userId);
            $dataProperty->AddField("ChannelId", $channelId);
            return $this->dbOperator->GetInt($sql, $dataProperty);
        }else{
            return null;
        }
    }

    /**
     * 增加或修改会员到站点权限表中
     * @param int $userId 会员ID
     * @param int $userGroupId　数组ID
     * @param int $siteId　站点ID
     * @return int 返回影响的行数
     */
    public function CreateOrModify($userId, $userGroupId, $siteId) {
        $sql = "SELECT Count(*) From ".self::TableName_UserRole." WHERE UserID=:UserID AND SiteID=:SiteID;";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("SiteID", $siteId);
        $dataProperty->AddField("UserID", $userId);
        $dbOperator = DBOperator::getInstance();
        $hasCount = $dbOperator->ReturnInt($sql, $dataProperty);
        if ($hasCount > 0) { //modify
            $dataProperty->AddField("UserGroupId", $userGroupId);
            $sql = "UPDATE ".self::TableName_UserRole." SET UserGroupId=:UserGroupId WHERE UserID=:UserID AND SiteID=:SiteID;";
            $result = $dbOperator->Execute($sql, $dataProperty);
        } else { //INSERT
            $dataProperty->AddField("UserGroupId", $userGroupId);
            $sql = "INSERT INTO ".self::TableName_UserRole." (UserGroupId,UserID,SiteID) VALUES (:UserGroupId,:UserID,:SiteID);";
            $result = $dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }


}