<?php
/**
 * 后台管理 会员站点等级 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author zhangchi
 */
class UserSiteLevelManageData extends BaseManageData {
    public function GetUserLevelId($userId,$siteId){
        $sql = "SELECT UserLevelId FROM " . self::TableName_UserSiteLevel . " WHERE UserId=:UserId AND SiteId=:SiteId;";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("UserId", $userId);
        $dataProperty->AddField("SiteId", $siteId);
        $result = $this->dbOperator->ReturnInt($sql, $dataProperty);
        return $result;
    }

    public function ModifyUserLevelId($userId, $siteId, $userLevelId){
        $sql = "SELECT count(*) FROM " . self::TableName_UserSiteLevel . " WHERE UserId=:UserId AND SiteId=:SiteId;";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("UserId", $userId);
        $dataProperty->AddField("SiteId", $siteId);
        $result = $this->dbOperator->GetInt($sql, $dataProperty);

        if($result>0){
            $sql = "UPDATE " . self::TableName_UserSiteLevel . " SET UserLevelId=:UserLevelId WHERE UserId=:UserId AND SiteId=:SiteId;";
        }else{
            $sql = "INSERT INTO " . self::TableName_UserSiteLevel . " (UserLevelId,UserId,SiteId) VALUES (:UserLevelId,:UserId,:SiteId)";
        }
        $dataProperty->AddField("UserLevelId", $userLevelId);

        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }
}