<?php

/**
 * 客户端 会员角色 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author yin
 */
class UserRoleClientData extends BaseClientData {
    /**
     * 初始化
     * @param int $userId 会员id
     * @param int $siteId 站点id
     * @param int $userGroupId 会员组id
     * @return int 初始化结果
     */
    public function Init($userId,$siteId,$userGroupId){
        $result = -1;
        if($userId > 0 && $siteId > 0){
            $sqlSelect = "SELECT count(*) FROM " . self::TableName_UserRole . " WHERE UserId=:UserId";
            $selectDataProperty = new DataProperty();
            $selectDataProperty->AddField("UserId", $userId);
            $selectResult = $this->dbOperator->GetInt($sqlSelect, $selectDataProperty);
            if($selectResult == 0){
                $sqlInsert = "INSERT INTO ".self::TableName_UserRole." (UserId,SiteId,UserGroupId) VALUES (:UserId,:SiteId,:UserGroupId);";
                $insertDataProperty = new DataProperty();
                $insertDataProperty->AddField("UserId",$userId);
                $insertDataProperty->AddField("SiteId",$siteId);
                $insertDataProperty->AddField("UserGroupId",$userGroupId);
                $result = $this->dbOperator->GetInt($sqlInsert, $insertDataProperty);
            }else{
                $result = $userId;
            }
        }
        return $result;
    }


    /**
     * 取得会员组id
     * @param int $siteId
     * @param int $userId
     * @param bool $withCache
     * @return int UserGroupId
     */
    public function GetUserGroupId(
        $siteId,
        $userId,
        $withCache = true
    ){
        $result = -1;
        if ($siteId > 0 && $userId > 0) {
            $cacheDir = CACHE_PATH
                . DIRECTORY_SEPARATOR
                . 'user_role_data'
                . DIRECTORY_SEPARATOR
                . 'user_'
                . $userId;
            $cacheFile = 'user_role_get_user_group_id.cache_site_id'
                . $siteId;
            $sql = "SELECT UserGroupId FROM " . self::TableName_UserRole . "
                    WHERE SiteId=:SiteId
                    AND UserId=:UserId
                    ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);
            $dataProperty->AddField("UserId", $userId);

            $result = $this->GetInfoOfIntValue(
                $sql,
                $dataProperty,
                $withCache,
                $cacheDir,
                $cacheFile
            );
        }
        return $result;
    }
}