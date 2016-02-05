<?php
/**
 * 客户端 会员权限 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author zhangchi
 */
class UserPopedomClientData extends BaseClientData {
    /**
     * 根据站点ID和会员ID取得配置值（带缓冲）
     * @param int $siteId
     * @param int $userId
     * @param string $userPopedomName
     * @param bool $withCache 是否缓存，默认true
     * @return int
     */
    public function GetValueBySiteIdAndUserId(
        $siteId,
        $userId,
        $userPopedomName,
        $withCache = true
    ) {


        $result = -1;
        if ($siteId > 0 && $userId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'user_popedom_data' . DIRECTORY_SEPARATOR . 'user_' . $userId;
            $cacheFile = 'user_popedom_get_value_by_site_id_and_user_id.cache_site_id'
                . $siteId
                . '_'
                . $userId
                . '_'
                . strtolower($userPopedomName);
            $sql = "SELECT UserPopedomValue FROM " . self::TableName_UserPopedom . "
                    WHERE SiteId=:SiteId
                    AND UserId=:UserId
                    AND UserPopedomName=:UserPopedomName
                    ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);
            $dataProperty->AddField("UserId", $userId);
            $dataProperty->AddField("UserPopedomName", $userPopedomName);
            $result = $this->GetInfoOfStringValue(
                $sql,
                $dataProperty,
                $withCache,
                $cacheDir,
                $cacheFile
            );
        }
        return $result;
    }

    /**
     * 根据站点ID和会员ID取得配置值（带缓冲）
     * @param int $siteId
     * @param int $userGroupId
     * @param string $userPopedomName
     * @param bool $withCache 是否缓存，默认true
     * @return int
     */
    public function GetValueBySiteIdAndUserGroupId(
        $siteId,
        $userGroupId,
        $userPopedomName,
        $withCache = true
    ) {
        $result = "";
        if ($siteId > 0 && $userGroupId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'user_popedom_data' . DIRECTORY_SEPARATOR . 'user_group_' . $userGroupId;
            $cacheFile = 'user_popedom_get_value_by_site_id_and_user_group_id.cache_site_id'
                . $siteId
                . '_'
                . $userGroupId
                . strtolower($userPopedomName);
            $sql = "SELECT UserPopedomValue FROM " . self::TableName_UserPopedom . "
                    WHERE SiteId=:SiteId
                    AND UserGroupId=:UserGroupId
                    AND UserPopedomName=:UserPopedomName
                    ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);
            $dataProperty->AddField("UserGroupId", $userGroupId);
            $dataProperty->AddField("UserPopedomName", $userPopedomName);
            $result = $this->GetInfoOfStringValue(
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