<?php

/**
 * 后台管理 会员组  数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author yin
 */
class UserGroupManageData extends BaseManageData
{

    /**
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_UserGroup){
        return parent::GetFields(self::TableName_UserGroup);
    }

    /**
     * 新增
     * @param array $httpPostData $_POST数组
     * @param int $siteId 站点ID
     * @return int|null 最后插入的ID
     */
    public function Create($httpPostData,$siteId){
        $result = -1;
        if($siteId > 0){
            $dataProperty = new DataProperty();
            $sql = parent::GetInsertSql($httpPostData,self::TableName_UserGroup,$dataProperty,"SiteId",$siteId);
            $result = $this->dbOperator->Execute($sql,$dataProperty);
        }
        return $result;
    }

    /**
     * 修改
     * @param array $httpPostData  $_POST数组
     * @param int $userGroupId 会员组ID
     * @return int|null 返回影响的行数
     */
    public function Modify($httpPostData,$userGroupId){
        $result = -1;
        if($userGroupId > 0){
            $dataProperty = new DataProperty();
            $sql = parent::GetUpdateSql($httpPostData,self::TableName_UserGroup,self::TableId_UserGroup,$userGroupId,$dataProperty);
            $result = $this->dbOperator->Execute($sql,$dataProperty);
        }
        return $result;
    }

    /**
     * 修改状态
     * @param int $userGroupId 会员组ID
     * @param int $state 状态
     * @return int 影响的行数
     */
    public function ModifyState($userGroupId,$state){
        $result = -1;
        if($userGroupId > 0){
            $dataProperty = new DataProperty();
            $sql = "UPDATE ".self::TableName_UserGroup." SET State = :State WHERE UserGroupId = :UserGroupId;";
            $dataProperty->AddField("State",$state);
            $dataProperty->AddField("UserGroupId",$userGroupId);
            $result = $this->dbOperator->Execute($sql,$dataProperty);
        }
        return $result;
    }

    /**
     * 返回是否锁定等级
     * @param int $userGroupId 会员组id
     * @param bool $withCache 是否缓存
     * @return int 是否锁定
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
     * @return array|null 多个会员组的数组
     */
    public function GetList($siteId,$pageBegin, $pageSize, &$allCount)
    {
        $result = null;
        if($siteId > 0){
            $dataProperty = new DataProperty();
            $sql = "SELECT * FROM " . self::TableName_UserGroup ." WHERE SiteId = :SiteId ORDER BY Sort,UserGroupId LIMIT " . $pageBegin . "," . $pageSize .";";
            $sqlCount = "SELECT count(*) FROM " . self::TableName_UserGroup . " WHERE SiteId = :SiteId;";
            $dataProperty->AddField("SiteId",$siteId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
            $allCount = $this->dbOperator->GetInt($sqlCount, $dataProperty);
        }
        return $result;
    }

    /**
     * 获取一个会员组的信息
     * @param int $userGroupId 会员组ID
     * @param int $siteId 站点ID
     * @return array|null 单个会员组信息
     */
    public function GetOne($userGroupId,$siteId){
        $result = -1;
        if($userGroupId > 0 && $siteId > 0){
            $dataProperty = new DataProperty();
            $sql = "SELECT * FROM ".self::TableName_UserGroup." WHERE UserGroupId = :UserGroupId AND SiteId = :SiteId;";
            $dataProperty->AddField("UserGroupId",$userGroupId);
            $dataProperty->AddField("SiteId",$siteId);
            $result = $this->dbOperator->GetArray($sql,$dataProperty);
        }
        return $result;
    }
}