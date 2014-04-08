<?php

/**
 * 后台管理 权限 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Manage
 * @author zhangchi
 */
class ManageUserAuthorityManageData extends BaseManageData
{

    /**
     * 建立频道时授权
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 管理员id
     * @return int 创建结果数字，1为成功
     */
    public function CreateForChannel($siteId, $channelId, $manageUserId)
    {
        $sql = "INSERT INTO " . self::TableName_ManageUserAuthority . " (
            `SiteId`,
            `ChannelId`,
            `ManageUserGroupId`,
            `ManageUserId`,
            `PopedomLevel`,
            `Explore`, 
            `Create`, 
            `Modify`, 
            `Delete`, 
            `Disabled`, 
            `Search`, 
            `Rework`, 
            `Audit1`, 
            `Audit2`, 
            `Audit3`, 
            `Audit4`, 
            `Refused`, 
            `DoOthers`,
            `DoOthersInSameGroup`,
            `Publish`
            )
            VALUES
	        (
	        :SiteId,
	        :ChannelId,
	            0,
	        :ManageUserId,
	        0,
	        1,
	        1,
	        1,
	        1,
	        1,
	        1,
	        1,
	        1,
	        1,
	        1,
	        1,
	        1,
	        1,
	        1,
            1
	        );";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("SiteId", $siteId);
        $dataProperty->AddField("ChannelId", $channelId);
        $dataProperty->AddField("ManageUserId", $manageUserId);
        $result = $this->dbOperator->Execute($sql, $dataProperty);

        if ($result > 0) {
            //删除缓冲
            DataCache::RemoveDir(CACHE_PATH);
        }

        return $result;
    }

    /**
     * 更新权限
     * @param int $siteId 站点id
     * @param int $manageUserId 后台管理员id
     * @param int $channelId 频道id
     * @param string $fieldName 要更新的字段
     * @param string $value 要更新的值
     * @return int 更新结果
     */
    public function Update($siteId, $manageUserId, $channelId, $fieldName, $value)
    {
        $dataProperty = new DataProperty();
        $dataProperty->AddField("SiteId", $siteId);
        $dataProperty->AddField("ChannelId", $channelId);
        $dataProperty->AddField("ManageUserId", $manageUserId);

        $sql = "SELECT count(*) FROM " . self::TableName_ManageUserAuthority . " WHERE SiteId=:SiteId AND ChannelId=:ChannelId AND ManageUserId=:ManageUserId;";

        $result = $this->dbOperator->GetInt($sql, $dataProperty);

        if ($result > 0) {
            $sql = "UPDATE " . self::TableName_ManageUserAuthority . " SET `" . $fieldName . "`=:" . $fieldName . " where SiteId=:SiteId and ChannelId=:ChannelId and ManageUserId=:ManageUserId;";
        } else {
            $sql = "INSERT INTO " . self::TableName_ManageUserAuthority . " (SiteId,ChannelId,`" . $fieldName . "`,ManageUserId) values (:SiteId,:ChannelId,:" . $fieldName . ",:ManageUserId);";
        }
        $dataProperty2 = new DataProperty();
        $dataProperty2->AddField("SiteId", $siteId);
        $dataProperty2->AddField("ChannelId", $channelId);
        $dataProperty2->AddField("ManageUserId", $manageUserId);
        $dataProperty2->AddField($fieldName, $value);
        $result = $this->dbOperator->Execute($sql, $dataProperty2);

        return $result;
    }

    /**
     * 为后台帐号分组和站点设置权限（新增或修改）
     * @param int $siteId 站点id
     * @param int $manageUserGroupId
     * @param int $explore
     * @param int $create
     * @param int $modify
     * @param int $delete
     * @param int $disabled
     * @param int $search
     * @param int $rework
     * @param int $audit1
     * @param int $audit2
     * @param int $audit3
     * @param int $audit4
     * @param int $refused
     * @param int $doOthers
     * @param int $doOthersInSameGroup
     * @param int $publish
     * @param int $userExplore
     * @param int $userAdd
     * @param int $userEdit
     * @param int $userDelete
     * @param int $userRoleExplore
     * @param int $userRoleAdd
     * @param int $userRoleEdit
     * @param int $userRoleDelete
     * @param int $userAlbumExplore
     * @param int $userAlbumAdd
     * @param int $userAlbumEdit
     * @param int $userAlbumDelete
     * @param int $userGroupExplore
     * @param int $userLevelExplore
     * @param int $userOrderExplore
     * @param int $manageSite
     * @param int $manageComment
     * @param int $manageTemplateLibrary
     * @param int $manageFilter
     * @param int $manageFtp
     * @param int $manageAd
     * @param int $manageDocumentTag
     * @param int $manageConfig
     * @return int
     */
    public function CreateOrModifyForSiteAndAdminUserGroup(
        $siteId, $manageUserGroupId, $explore, $create, $modify, $delete, $disabled, $search, $rework, $audit1, $audit2, $audit3, $audit4, $refused, $doOthers, $doOthersInSameGroup, $publish, $userExplore, $userAdd, $userEdit, $userDelete, $userRoleExplore, $userRoleAdd, $userRoleEdit, $userRoleDelete, $userAlbumExplore, $userAlbumAdd, $userAlbumEdit, $userAlbumDelete, $userGroupExplore, $userLevelExplore, $userOrderExplore, $manageSite, $manageComment, $manageTemplateLibrary, $manageFilter, $manageFtp, $manageAd, $manageDocumentTag, $manageConfig
    )
    {
        //判断是否存在数据
        $sql = "SELECT Count(*) FROM " . self::TableName_ManageUserAuthority . " WHERE SiteId=:SiteId AND ManageUserGroupId=:ManageUserGroupId AND ManageUserId=0 AND ChannelId=0;";

        $dataProperty = new DataProperty();
        $dataProperty->AddField("SiteId", $siteId);
        $dataProperty->AddField("ManageUserGroupId", $manageUserGroupId);

        $hasCount = $this->dbOperator->GetInt($sql, $dataProperty);

        if ($hasCount <= 0) {
            $sql = "INSERT INTO " . self::TableName_ManageUserAuthority . "
	            (
	                `SiteId`,
	                `ManageUserGroupId`,
	                `Explore`,
	                `Create`,
	                `Modify`,
	                `Delete`,
	                `Disabled`,
	                `Search`,
	                `Rework`,
	                `Audit1`,
	                `Audit2`,
	                `Audit3`,
	                `Audit4`,
	                `Refused`,
	                `DoOthers`,
		            `DoOthersInSameGroup`,
		            `Publish`,
		            `UserExplore`,
		            `UserAdd`,
		            `UserEdit`,
		            `UserDelete`,
		            `UserRoleExplore`,
		            `UserRoleAdd`,
		            `UserRoleEdit`,
		            `UserRoleDelete`,
		            `UserAlbumExplore`,
		            `UserAlbumAdd`,
		            `UserAlbumEdit`,
		            `UserAlbumDelete`,
		            `UserGroupExplore`,
		            `UserLevelExplore`,
		            `UserOrderExplore`,
    	            `ManageSite`,
    	            `ManageComment`,
    	            `ManageTemplateLibrary`,
    	            `ManageFilter`,
    	            `ManageFtp`,
    	            `ManageAd`,
    	            `ManageDocumentTag`,
    	            `ManageConfig`
		            )
		            VALUES
		            (
		            :SiteId,
		            :ManageUserGroupId,
		            :Explore,
		            :Create,
		            :Modify,
		            :Delete,
		            :Disabled,
		            :Search,
		            :Rework,
		            :Audit1,
		            :Audit2,
		            :Audit3,
		            :Audit4,
		            :Refused,
		            :DoOthers,
        	        :DoOthersInSameGroup,
		            :Publish,
		            :UserExplore,
		            :UserAdd,
		            :UserEdit,
		            :UserDelete,
		            :UserRoleExplore,
		            :UserRoleAdd,
		            :UserRoleEdit,
		            :UserRoleDelete,
		            :UserAlbumExplore,
		            :UserAlbumAdd,
		            :UserAlbumEdit,
		            :UserAlbumDelete,
		            :UserGroupExplore,
		            :UserLevelExplore,
		            :UserOrderExplore,
    	            :ManageSite,
    	            :ManageComment,
    	            :ManageTemplateLibrary,
    	            :ManageFilter,
    	            :ManageFtp,
    	            :ManageAd,
    	            :ManageDocumentTag,
    	            :ManageConfig
		            );";

            $dataProperty->AddField("Explore", $explore);
            $dataProperty->AddField("Create", $create);
            $dataProperty->AddField("Modify", $modify);
            $dataProperty->AddField("Delete", $delete);
            $dataProperty->AddField("Disabled", $disabled);
            $dataProperty->AddField("Search", $search);
            $dataProperty->AddField("Rework", $rework);
            $dataProperty->AddField("Audit1", $audit1);
            $dataProperty->AddField("Audit2", $audit2);
            $dataProperty->AddField("Audit3", $audit3);
            $dataProperty->AddField("Audit4", $audit4);
            $dataProperty->AddField("Refused", $refused);
            $dataProperty->AddField("DoOthers", $doOthers);
            $dataProperty->AddField("DoOthersInSameGroup", $doOthersInSameGroup);
            $dataProperty->AddField("Publish", $publish);
            $dataProperty->AddField("UserExplore", $userExplore);
            $dataProperty->AddField("UserAdd", $userAdd);
            $dataProperty->AddField("UserEdit", $userEdit);
            $dataProperty->AddField("UserDelete", $userDelete);
            $dataProperty->AddField("UserRoleExplore", $userRoleExplore);
            $dataProperty->AddField("UserRoleAdd", $userRoleAdd);
            $dataProperty->AddField("UserRoleEdit", $userRoleEdit);
            $dataProperty->AddField("UserRoleDelete", $userRoleDelete);
            $dataProperty->AddField("UserAlbumExplore", $userAlbumExplore);
            $dataProperty->AddField("UserAlbumAdd", $userAlbumAdd);
            $dataProperty->AddField("UserAlbumEdit", $userAlbumEdit);
            $dataProperty->AddField("UserAlbumDelete", $userAlbumDelete);
            $dataProperty->AddField("UserGroupExplore", $userGroupExplore);
            $dataProperty->AddField("UserLevelExplore", $userLevelExplore);
            $dataProperty->AddField("UserOrderExplore", $userOrderExplore);
            $dataProperty->AddField("ManageSite", $manageSite);
            $dataProperty->AddField("ManageComment", $manageComment);
            $dataProperty->AddField("ManageTemplateLibrary", $manageTemplateLibrary);
            $dataProperty->AddField("ManageFilter", $manageFilter);
            $dataProperty->AddField("ManageFtp", $manageFtp);
            $dataProperty->AddField("ManageAd", $manageAd);
            $dataProperty->AddField("ManageDocumentTag", $manageDocumentTag);
            $dataProperty->AddField("ManageConfig", $manageConfig);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        } else {

            $sql = "UPDATE " . self::TableName_ManageUserAuthority . "
	                SET
	                    `Explore` = :Explore,
	                    `Create` = :Create,
	                    `Modify` = :Modify,
	                    `Delete` = :Delete,
	                    `Disabled` = :Disabled,
	                    `Search` = :Search,
	                    `Rework` = :Rework,
	                    `Audit1` = :Audit1,
	                    `Audit2` = :Audit2,
	                    `Audit3` = :Audit3,
	                    `Audit4` = :Audit4,
	                    `Refused` = :Refused,
	                    `DoOthers` = :DoOthers,
	                    `DoOthersInSameGroup` = :DoOthersInSameGroup,
	                    `Publish` = :Publish,
	                    `UserExplore` = :UserExplore,
	                    `UserAdd` = :UserAdd,
	                    `UserEdit` = :UserEdit,
	                    `UserDelete` = :UserDelete,
	                    `UserRoleExplore` = :UserRoleExplore,
	                    `UserRoleAdd` = :UserRoleAdd,
	                    `UserRoleEdit` = :UserRoleEdit,
	                    `UserRoleDelete` = :UserRoleDelete,
	                    `UserAlbumExplore` = :UserAlbumExplore,
	                    `UserAlbumAdd` = :UserAlbumAdd,
	                    `UserAlbumEdit` = :UserAlbumEdit,
	                    `UserAlbumDelete` = :UserAlbumDelete,
	                    `UserGroupExplore` = :UserGroupExplore,
	                    `UserLevelExplore` = :UserLevelExplore,
	                    `UserOrderExplore` = :UserOrderExplore,
                        `ManageSite`=:ManageSite,
                        `ManageComment`=:ManageComment,
                        `ManageTemplateLibrary`=:ManageTemplateLibrary,
                        `ManageFilter`=:ManageFilter,
                        `ManageFtp`=:ManageFtp,
                        `ManageAd`=:ManageAd,
                        `ManageDocumentTag`=:ManageDocumentTag,
                        `ManageConfig`=:ManageConfig
	                WHERE SiteId=:SiteId AND ManageUserGroupId=:ManageUserGroupId AND ManageUserId=0 AND ChannelId=0;";

            $dataProperty->AddField("Explore", $explore);
            $dataProperty->AddField("Create", $create);
            $dataProperty->AddField("Modify", $modify);
            $dataProperty->AddField("Delete", $delete);
            $dataProperty->AddField("Disabled", $disabled);
            $dataProperty->AddField("Search", $search);
            $dataProperty->AddField("Rework", $rework);
            $dataProperty->AddField("Audit1", $audit1);
            $dataProperty->AddField("Audit2", $audit2);
            $dataProperty->AddField("Audit3", $audit3);
            $dataProperty->AddField("Audit4", $audit4);
            $dataProperty->AddField("Refused", $refused);
            $dataProperty->AddField("DoOthers", $doOthers);
            $dataProperty->AddField("DoOthersInSameGroup", $doOthersInSameGroup);
            $dataProperty->AddField("Publish", $publish);
            $dataProperty->AddField("UserExplore", $userExplore);
            $dataProperty->AddField("UserAdd", $userAdd);
            $dataProperty->AddField("UserEdit", $userEdit);
            $dataProperty->AddField("UserDelete", $userDelete);
            $dataProperty->AddField("UserRoleExplore", $userRoleExplore);
            $dataProperty->AddField("UserRoleAdd", $userRoleAdd);
            $dataProperty->AddField("UserRoleEdit", $userRoleEdit);
            $dataProperty->AddField("UserRoleDelete", $userRoleDelete);
            $dataProperty->AddField("UserAlbumExplore", $userAlbumExplore);
            $dataProperty->AddField("UserAlbumAdd", $userAlbumAdd);
            $dataProperty->AddField("UserAlbumEdit", $userAlbumEdit);
            $dataProperty->AddField("UserAlbumDelete", $userAlbumDelete);
            $dataProperty->AddField("UserGroupExplore", $userGroupExplore);
            $dataProperty->AddField("UserLevelExplore", $userLevelExplore);
            $dataProperty->AddField("UserOrderExplore", $userOrderExplore);
            $dataProperty->AddField("ManageSite", $manageSite);
            $dataProperty->AddField("ManageComment", $manageComment);
            $dataProperty->AddField("ManageTemplateLibrary", $manageTemplateLibrary);
            $dataProperty->AddField("ManageFilter", $manageFilter);
            $dataProperty->AddField("ManageFtp", $manageFtp);
            $dataProperty->AddField("ManageAd", $manageAd);
            $dataProperty->AddField("ManageDocumentTag", $manageDocumentTag);
            $dataProperty->AddField("ManageConfig", $manageConfig);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }

    /**
     * 删除权限
     * @param int $siteId 站点id
     * @param int $manageUserId 后台管理员id
     * @return int 删除结果
     */
    public function Remove($siteId, $manageUserId)
    {
        $sql = "DELETE FROM " . self::TableName_ManageUserAuthority . " WHERE SiteId=:SiteId AND ManageUserId=:ManageUserId;";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("SiteId", $siteId);
        $dataProperty->AddField("ManageUserId", $manageUserId);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 根据siteId，channelId，manageUserId取得一条记录
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return array 一条权限记录
     */
    public function GetOne($siteId, $channelId, $manageUserId)
    {
        $dataProperty = new DataProperty();
        $dataProperty->AddField("SiteId", $siteId);
        $dataProperty->AddField("ChannelId", $channelId);
        $dataProperty->AddField("ManageUserId", $manageUserId);
        $sql = "SELECT * FROM " . self::TableName_ManageUserAuthority . " WHERE SiteId=:SiteId AND ChannelId=:ChannelId AND ManageUserId=:ManageUserId;";
        $result = $this->dbOperator->GetArray($sql, $dataProperty);
        return $result;
    }

    /**
     * 根据siteId，channelId，manageUserGroupId取得一条记录（已经缓冲）
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserGroupId 后台管理员分组id
     * @return array 一条权限记录
     */
    public function GetOneByManageUserGroupId($siteId, $channelId, $manageUserGroupId)
    {
        $dataProperty = new DataProperty();
        $dataProperty->AddField("SiteId", $siteId);
        $dataProperty->AddField("ManageUserGroupId", $manageUserGroupId);
        if ($channelId > 0) {
            $sql = "SELECT * FROM " . self::TableName_ManageUserAuthority . " WHERE SiteId=:SiteId AND ChannelId=:ChannelId AND ManageUserGroupId=:ManageUserGroupId AND ManageUserId=0;";
            $dataProperty->AddField("ChannelId", $channelId);
        } else {
            $sql = "SELECT * FROM " . self::TableName_ManageUserAuthority . " WHERE SiteId=:SiteId AND ManageUserGroupId=:ManageUserGroupId AND ChannelId=0 AND ManageUserId=0;";
        }

        $result = $this->dbOperator->GetArray($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得单条权限字段信息
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @param string $fieldName 要查询的权限字段
     * @return bool 是否有权限
     */
    public function GetFieldValue($siteId, $channelId, $manageUserId, $fieldName)
    {
        if (intval($manageUserId) == 1) {
            return TRUE;
        }
        $dataProperty = new DataProperty();
        $dataProperty->AddField("SiteId", $siteId);
        $dataProperty->AddField("ChannelId", $channelId);
        $dataProperty->AddField("ManageUserId", $manageUserId);
        //检查用户频道权限
        $sql = "SELECT `" . $fieldName . "` FROM " . self::TableName_ManageUserAuthority . " WHERE SiteId=:SiteId AND ChannelId=:ChannelId AND ManageUserId=:ManageUserId;";
        $result = $this->dbOperator->GetInt($sql, $dataProperty);
        if ($result <= 0) {
            //检查用户组频道权限
            $sql = "SELECT `" . $fieldName . "` FROM " . self::TableName_ManageUserAuthority . " WHERE SiteId=:SiteId AND ChannelId=:ChannelId AND ManageUserGroupId IN (SELECT ManageUserGroupId FROM " . self::TableName_ManageUser . " WHERE ManageUserId=:ManageUserId);";
            $result = $this->dbOperator->GetInt($sql, $dataProperty);
        }
        if ($result <= 0) {
            //检查用户组站点权限
            $sql = "SELECT `" . $fieldName . "` FROM " . self::TableName_ManageUserAuthority . " WHERE SiteId=:SiteId AND ChannelId=0 AND ManageUserId=0 AND ManageUserGroupId IN (SELECT ManageUserGroupId FROM " . self::TableName_ManageUser . " WHERE ManageUserId=:ManageUserId);";
            $dataProperty2 = new DataProperty();
            $dataProperty2->AddField("SiteId", $siteId);
            $dataProperty2->AddField("ManageUserId", $manageUserId);
            $result = $this->dbOperator->GetInt($sql, $dataProperty2);
        }

        if (intval($result) === 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * 是否有浏览权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否有浏览权限
     */
    public function CanExplore($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "Explore");
    }

    /**
     * 是否有新增权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否有新增权限
     */
    public function CanCreate($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "Create");
    }

    /**
     * 是否有编辑权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否有编辑权限
     */
    public function CanModify($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "Modify");
    }

    /**
     * 是否有删除权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否有删除权限
     */
    public function CanDelete($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "Delete");
    }

    /**
     * 是否有停用权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否有停用权限
     */
    public function CanDisabled($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "Disabled");
    }

    /**
     * 是否有查询权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否有查询权限
     */
    public function CanSearch($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "Search");
    }

    /**
     * 是否有浏览会员权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否有浏览会员权限
     */
    public function CanUserExplore($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "UserExplore");
    }

    /**
     * 是否有新增会员权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否有新增会员权限
     */
    public function CanUserAdd($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "UserAdd");
    }

    /**
     * 是否有编辑会员权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否有编辑会员权限
     */
    public function CanUserEdit($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "UserEdit");
    }

    /**
     * 是否有删除会员权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否有删除会员权限
     */
    public function CanUserDelete($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "UserDelete");
    }

    /**
     * 是否有浏览会员角色的权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否有浏览会员角色的权限
     */
    public function CanUserRoleExplore($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "UserRoleExplore");
    }

    /**
     * 是否有新增会员角色的权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否有新增会员角色的权限
     */
    public function CanUserRoleAdd($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "UserRoleAdd");
    }

    /**
     * 是否有编辑会员角色的权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否有编辑会员角色的权限
     */
    public function CanUserRoleEdit($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "UserRoleEdit");
    }

    /**
     * 是否有删除会员角色的权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否有删除会员角色的权限
     */
    public function CanUserRoleDelete($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "UserRoleDelete");
    }

    /**
     * 是否有浏览会员相册的权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否有浏览会员相册的权限
     */
    public function CanUserAlbumExplore($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "UserAlbumExplore");
    }

    /**
     * 是否有新增会员相册的权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否有新增会员相册的权限
     */
    public function CanUserAlbumAdd($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "UserAlbumAdd");
    }

    /**
     * 是否有编辑会员相册的权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否有编辑会员相册的权限
     */
    public function CanUserAlbumEdit($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "UserAlbumEdit");
    }

    /**
     * 是否有删除会员相册的权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否有删除会员相册的权限
     */
    public function CanUserAlbumDelete($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "UserAlbumDelete");
    }

    /**
     * 是否有返工权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否有返工权限
     */
    public function CanRework($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "Rework");
    }

    /**
     * 是否有一审权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否有一审权限
     */
    public function CanAudit1($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "Audit1");
    }

    /**
     * 是否有二审权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否有二审权限
     */
    public function CanAudit2($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "Audit2");
    }

    /**
     * 是否有三审权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否有三审权限
     */
    public function CanAudit3($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "Audit3");
    }

    /**
     * 是否有终审权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否有终审权限
     */
    public function CanAudit4($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "Audit4");
    }

    /**
     * 是否有已否权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否有已否权限
     */
    public function CanRefused($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "Refused");
    }

    /**
     * 是否有操作他人权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否有操作他人权限
     */
    public function CanDoOthers($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "DoOthers");
    }

    /**
     * 是否有操作同一组内他人权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否有操作同一组内他人权限
     */
    public function CanDoOthersInSameGroup($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "DoOthersInSameGroup");
    }


    /**
     * 是否有操作同一组中他人的权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否有操作同一组中他人的权限
     */
    public function CanDoSameGroupOthers($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "DoSameGroupOthers");
    }

    /**
     * 是否有发布权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool  是否有发布权限
     */
    public function CanPublish($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "Publish");
    }

    /**
     * 是否可以管理站点
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否可以管理站点
     */
    public function CanManageSite($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "ManageSite");
    }

    /**
     * 是否可以管理评论
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否可以管理评论
     */
    public function CanManageComment($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "ManageComment");
    }

    /**
     * 是否可以管理模板库
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否可以管理模板库
     */
    public function CanManageTemplateLibrary($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "ManageTemplateLibrary");
    }

    /**
     * 是否可以管理过滤字符
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否可以管理过滤字符
     */
    public function CanManageFilter($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "ManageFilter");
    }

    /**
     * 是否可以管理FTP
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否可以管理FTP
     */
    public function CanManageFtp($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "ManageFtp");
    }

    /**
     * 是否可以管理广告
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否可以管理广告
     */
    public function CanManageAd($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "ManageAd");
    }

    /**
     * 是否可以管理标签
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否可以管理标签
     */
    public function CanManageDocumentTag($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "ManageDocumentTag");
    }

    /**
     * 是否可以管理配置
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否可以管理配置
     */
    public function CanManageConfig($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "ManageConfig");
    }

}

?>
