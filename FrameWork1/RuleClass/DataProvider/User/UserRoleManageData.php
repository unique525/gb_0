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
}