<?php

/**
 * 后台管理 会员组  数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author zhangchi
 */
class UserGroupManageData extends BaseManageData
{

    public function Create($httpPostData,$siteId){
        if($siteId > 0){
            $dataProperty = new DataProperty();
            $sql = parent::GetInsertSql($httpPostData,self::TableName_UserGroup,$dataProperty,"SiteId",$siteId);
            $result = $this->dbOperator->Execute($sql,$dataProperty);
            return $result;
        }else{
            return null;
        }
    }

    /**
     * @param array $httpPostData  $_POST数组
     * @param int $userGroupId 会员组ID
     * @return int|null 返回影响的行数
     */
    public function Modify($httpPostData,$userGroupId){
        if($userGroupId > 0){
            $dataProperty = new DataProperty();
            $sql = parent::GetUpdateSql($httpPostData,self::TableName_UserGroup,self::TableId_UserGroup,$userGroupId,$dataProperty);
            $result = $this->dbOperator->Execute($sql,$dataProperty);
            return $result;
        }else{
            return null;
        }
    }

    public function ModifyState($userGroupId,$state){
        if($userGroupId > 0){
            $dataProperty = new DataProperty();
            $sql = "UPDATE ".self::TableName_UserGroup." SET State = :State WHERE UserGroupId = :UserGroupId";
            $dataProperty->AddField("State",$state);
            $dataProperty->AddField("UserGroupId",$userGroupId);
            $result = $this->dbOperator->Execute($sql,$dataProperty);
            return $result;
        }else{
            return null;
        }
    }

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
     * 获取会员组分页列表
     * @param int $siteId 站点ID
     * @param int $pageBegin 从pageBegin开始查询
     * @param int $pageSize 取pageSize条数据
     * @param int $allCount 所有行数
     * @return array|null 相册列表的数组
     */
    public function GetList($siteId,$pageBegin, $pageSize, &$allCount)
    {
        if($siteId > 0){
            $dataProperty = new DataProperty();
            $sql = "SELECT * FROM " . self::TableName_UserGroup ." WHERE SiteId = :SiteId ORDER BY Sort,UserGroupId LIMIT " . $pageBegin . "," . $pageSize .";";
            $sqlCount = "SELECT count(*) FROM " . self::TableName_UserGroup . " WHERE SiteId = :SiteId;";
            $dataProperty->AddField("SiteId",$siteId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
            $allCount = $this->dbOperator->GetInt($sqlCount, $dataProperty);
            return $result;
        }else{
            return null;
        }
    }

    public function GetParentIdByGroupId($userGroupId){
        if($userGroupId > 0){
            $dataProperty = new DataProperty();
            $sql = "SELECT ParentId FROM ".self::TableName_UserGroup." WHERE UserGroupId = :UserGroupId";
            $dataProperty->AddField("UserGroupId",$userGroupId);
            $result = $this->dbOperator->GetInt($sql,$dataProperty);
            return $result;
        }else{
            return null;
        }
    }

    public function GetOne($userGroupId,$siteId){
        if($userGroupId > 0 && $siteId > 0){
            $dataProperty = new DataProperty();
            $sql = "SELECT * FROM ".self::TableName_UserGroup." WHERE UserGroupId = :UserGroupId AND SiteId = :SiteId";
            $dataProperty->AddField("UserGroupId",$userGroupId);
            $dataProperty->AddField("SiteId",$siteId);
            $result = $this->dbOperator->GetArray($sql,$dataProperty);
            return $result;
        }else{
            return null;
        }
    }
}