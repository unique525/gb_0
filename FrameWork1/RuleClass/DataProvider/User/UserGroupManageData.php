<?php

/**
 * 后台管理 会员组  数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author zhangchi
 */
class UserGroupManageData extends BaseManageData
{


    /**
     * 返回是否锁定等级
     * @param int $userGroupId 会员组id
     * @param bool $withCache 是否缓存
     * @return int
     */
    public function GetIsLock($userGroupId, $withCache)
    {
        if ($userGroupId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'user_group_data';
            $cacheFile = 'user_group_get_is_lock.cache_' . $userGroupId . '';
            $sql = "SELECT IsLock FROM " . self::TableName_UserGroup . " WHERE " . self::TableId_UserGroup . " = :UserGroupId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserGroupId", $userGroupId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
            if ($result > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return null;
        }
    }

    /**
     * 返回对应siteid和state的会员组列表
     * @param int $siteId 站点ID
     * @param int $state 数据状态
     * @return array 会员数组列表数据集
     */
    public function GetList($siteId = 0, $state = 0)
    {
        $dataProperty = new DataProperty();
        $sql = "SELECT * FROM " . self::TableId_ManageUserGroup . " WHERE State=:State AND SiteId=:SiteId";
        $dataProperty->AddField("State", $state);
        $dataProperty->AddField("SiteId", $siteId);
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        return $result;
    }
}