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
            `ChannelExplore`,
            `ChannelCreate`,
            `ChannelModify`,
            `ChannelDelete`,
            `ChannelDisabled`,
            `ChannelSearch`,
            `ChannelRework`,
            `ChannelAudit1`,
            `ChannelAudit2`,
            `ChannelAudit3`,
            `ChannelAudit4`,
            `ChannelRefused`,
            `ChannelDoOthers`,
            `ChannelDoOthersInSameGroup`,
            `ChannelPublish`,
            `ChannelManageTemplate`
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
            1,
            1
	        );";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("SiteId", $siteId);
        $dataProperty->AddField("ChannelId", $channelId);
        $dataProperty->AddField("ManageUserId", $manageUserId);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
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
     * 为后台帐号分组设置系统权限（新增或修改）
     * @param int $manageUserGroupId
    * @param int $manageUserTaskManageState
    * @param int $manageUserTaskViewAll
    * @param int $manageUserTaskViewSameGroup
    * @param int $manageUserExplore
    * @param int $manageUserCreate
    * @param int $manageUserModify
    * @param int $manageUserGroupExplore
    * @param int $manageUserGroupCreate
    * @param int $manageUserGroupModify
     * @return int
     */
    public function CreateOrModifyForSystemAuthorityOfManageUserGroup(
         $manageUserGroupId, $manageUserTaskManageState, $manageUserTaskViewAll, $manageUserTaskViewSameGroup, $manageUserExplore, $manageUserCreate, $manageUserModify, $manageUserGroupExplore, $manageUserGroupCreate, $manageUserGroupModify
    )
    {
        //判断是否存在数据
        $sql = "SELECT Count(*) FROM " . self::TableName_ManageUserAuthority . " WHERE SiteId=0 AND ManageUserGroupId=:ManageUserGroupId AND ManageUserId=0 AND ChannelId=0;";

        $dataProperty = new DataProperty();
        $dataProperty->AddField("ManageUserGroupId", $manageUserGroupId);

        $hasCount = $this->dbOperator->GetInt($sql, $dataProperty);
        if ($hasCount <= 0) {
            $sql = "INSERT INTO " . self::TableName_ManageUserAuthority . "
	            (
	                `SiteId`,
	                `ManageUserGroupId`,
                    `ManageUserTaskManageState`,
                    `ManageUserTaskViewAll`,
                    `ManageUserTaskViewSameGroup`,
                    `ManageUserExplore`,
                    `ManageUserCreate`,
                    `ManageUserModify`,
                    `ManageUserGroupExplore`,
                    `ManageUserGroupCreate`,
                    `ManageUserGroupModify`
		            )
		            VALUES
		            (
		            0,
		            :ManageUserGroupId,
    	            :ManageUserTaskManageState,
                    :ManageUserTaskViewAll,
                    :ManageUserTaskViewSameGroup,
                    :ManageUserExplore,
                    :ManageUserCreate,
                    :ManageUserModify,
                    :ManageUserGroupExplore,
                    :ManageUserGroupCreate,
                    :ManageUserGroupModify
		            );";
            $dataProperty->AddField("ManageUserTaskManageState", $manageUserTaskManageState);
            $dataProperty->AddField("ManageUserTaskViewAll", $manageUserTaskViewAll);
            $dataProperty->AddField("ManageUserTaskViewSameGroup", $manageUserTaskViewSameGroup);
            $dataProperty->AddField("ManageUserExplore", $manageUserExplore);
            $dataProperty->AddField("ManageUserCreate", $manageUserCreate);
            $dataProperty->AddField("ManageUserModify", $manageUserModify);
            $dataProperty->AddField("ManageUserGroupExplore", $manageUserGroupExplore);
            $dataProperty->AddField("ManageUserGroupCreate", $manageUserGroupCreate);
            $dataProperty->AddField("ManageUserGroupModify", $manageUserGroupModify);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        } else {

            $sql = "UPDATE " . self::TableName_ManageUserAuthority . "
	                SET
                        `ManageUserTaskManageState` = :ManageUserTaskManageState,
                        `ManageUserTaskViewAll` = :ManageUserTaskViewAll,
                        `ManageUserTaskViewSameGroup` = :ManageUserTaskViewSameGroup,
                        `ManageUserExplore` = :ManageUserExplore,
                        `ManageUserCreate` = :ManageUserCreate,
                        `ManageUserModify` = :ManageUserModify,
                        `ManageUserGroupExplore` = :ManageUserGroupExplore,
                        `ManageUserGroupCreate` = :ManageUserGroupCreate,
                        `ManageUserGroupModify` = :ManageUserGroupModify
	                WHERE SiteId=0 AND ManageUserGroupId=:ManageUserGroupId AND ManageUserId=0 AND ChannelId=0;";
            $dataProperty->AddField("ManageUserTaskManageState", $manageUserTaskManageState);
            $dataProperty->AddField("ManageUserTaskViewAll", $manageUserTaskViewAll);
            $dataProperty->AddField("ManageUserTaskViewSameGroup", $manageUserTaskViewSameGroup);
            $dataProperty->AddField("ManageUserExplore", $manageUserExplore);
            $dataProperty->AddField("ManageUserCreate", $manageUserCreate);
            $dataProperty->AddField("ManageUserModify", $manageUserModify);
            $dataProperty->AddField("ManageUserGroupExplore", $manageUserGroupExplore);
            $dataProperty->AddField("ManageUserGroupCreate", $manageUserGroupCreate);
            $dataProperty->AddField("ManageUserGroupModify", $manageUserGroupModify);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 为后台帐号分组设置站点权限（新增或修改）
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
     * @param int $channelManageTemplate
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
    public function CreateOrModifyForSiteAuthorityOfManageUserGroup(
        $siteId, $manageUserGroupId, $explore, $create, $modify, $delete, $disabled, $search, $rework, $audit1, $audit2, $audit3, $audit4, $refused, $doOthers, $doOthersInSameGroup, $publish, $channelManageTemplate, $userExplore, $userAdd, $userEdit, $userDelete, $userRoleExplore, $userRoleAdd, $userRoleEdit, $userRoleDelete, $userAlbumExplore, $userAlbumAdd, $userAlbumEdit, $userAlbumDelete, $userGroupExplore, $userLevelExplore, $userOrderExplore, $manageSite, $manageComment, $manageTemplateLibrary, $manageFilter, $manageFtp, $manageAd, $manageDocumentTag, $manageConfig
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
	                `ChannelExplore`,
	                `ChannelCreate`,
	                `ChannelModify`,
	                `ChannelDelete`,
	                `ChannelDisabled`,
	                `ChannelSearch`,
	                `ChannelRework`,
	                `ChannelAudit1`,
	                `ChannelAudit2`,
	                `ChannelAudit3`,
	                `ChannelAudit4`,
	                `ChannelRefused`,
	                `ChannelDoOthers`,
		            `ChannelDoOthersInSameGroup`,
		            `ChannelPublish`,
	                `ChannelManageTemplate`,
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
		            :ChannelExplore,
		            :ChannelCreate,
		            :ChannelModify,
		            :ChannelDelete,
		            :ChannelDisabled,
		            :ChannelSearch,
		            :ChannelRework,
		            :ChannelAudit1,
		            :ChannelAudit2,
		            :ChannelAudit3,
		            :ChannelAudit4,
		            :ChannelRefused,
		            :ChannelDoOthers,
        	        :ChannelDoOthersInSameGroup,
		            :ChannelPublish,
	                :ChannelManageTemplate,
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

            $dataProperty->AddField("ChannelExplore", $explore);
            $dataProperty->AddField("ChannelCreate", $create);
            $dataProperty->AddField("ChannelModify", $modify);
            $dataProperty->AddField("ChannelDelete", $delete);
            $dataProperty->AddField("ChannelDisabled", $disabled);
            $dataProperty->AddField("ChannelSearch", $search);
            $dataProperty->AddField("ChannelRework", $rework);
            $dataProperty->AddField("ChannelAudit1", $audit1);
            $dataProperty->AddField("ChannelAudit2", $audit2);
            $dataProperty->AddField("ChannelAudit3", $audit3);
            $dataProperty->AddField("ChannelAudit4", $audit4);
            $dataProperty->AddField("ChannelRefused", $refused);
            $dataProperty->AddField("ChannelDoOthers", $doOthers);
            $dataProperty->AddField("ChannelDoOthersInSameGroup", $doOthersInSameGroup);
            $dataProperty->AddField("ChannelPublish", $publish);
            $dataProperty->AddField("ChannelManageTemplate", $channelManageTemplate);
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
	                    `ChannelExplore` = :ChannelExplore,
	                    `ChannelCreate` = :ChannelCreate,
	                    `ChannelModify` = :ChannelModify,
	                    `ChannelDelete` = :ChannelDelete,
	                    `ChannelDisabled` = :ChannelDisabled,
	                    `ChannelSearch` = :ChannelSearch,
	                    `ChannelRework` = :ChannelRework,
	                    `ChannelAudit1` = :ChannelAudit1,
	                    `ChannelAudit2` = :ChannelAudit2,
	                    `ChannelAudit3` = :ChannelAudit3,
	                    `ChannelAudit4` = :ChannelAudit4,
	                    `ChannelRefused` = :ChannelRefused,
	                    `ChannelDoOthers` = :ChannelDoOthers,
	                    `ChannelDoOthersInSameGroup` = :ChannelDoOthersInSameGroup,
	                    `ChannelPublish` = :ChannelPublish,
	                    `ChannelManageTemplate` = :ChannelManageTemplate,
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
                        `ManageSite` = :ManageSite,
                        `ManageComment` = :ManageComment,
                        `ManageTemplateLibrary` = :ManageTemplateLibrary,
                        `ManageFilter` = :ManageFilter,
                        `ManageFtp` = :ManageFtp,
                        `ManageAd` = :ManageAd,
                        `ManageDocumentTag` = :ManageDocumentTag,
                        `ManageConfig` = :ManageConfig
	                WHERE SiteId=:SiteId AND ManageUserGroupId=:ManageUserGroupId AND ManageUserId=0 AND ChannelId=0;";

            $dataProperty->AddField("ChannelExplore", $explore);
            $dataProperty->AddField("ChannelCreate", $create);
            $dataProperty->AddField("ChannelModify", $modify);
            $dataProperty->AddField("ChannelDelete", $delete);
            $dataProperty->AddField("ChannelDisabled", $disabled);
            $dataProperty->AddField("ChannelSearch", $search);
            $dataProperty->AddField("ChannelRework", $rework);
            $dataProperty->AddField("ChannelAudit1", $audit1);
            $dataProperty->AddField("ChannelAudit2", $audit2);
            $dataProperty->AddField("ChannelAudit3", $audit3);
            $dataProperty->AddField("ChannelAudit4", $audit4);
            $dataProperty->AddField("ChannelRefused", $refused);
            $dataProperty->AddField("ChannelDoOthers", $doOthers);
            $dataProperty->AddField("ChannelDoOthersInSameGroup", $doOthersInSameGroup);
            $dataProperty->AddField("ChannelPublish", $publish);
            $dataProperty->AddField("ChannelManageTemplate", $channelManageTemplate);

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
     * 为后台帐号设置系统权限（新增或修改）
     * @param int $manageUserId
     * @param int $manageUserTaskManageState
     * @param int $manageUserTaskViewAll
     * @param int $manageUserTaskViewSameGroup
     * @param int $manageUserExplore
     * @param int $manageUserCreate
     * @param int $manageUserModify
     * @param int $manageUserGroupExplore
     * @param int $manageUserGroupCreate
     * @param int $manageUserGroupModify
     * @return int
     */
    public function CreateOrModifyForSystemAuthorityOfManageUser(
         $manageUserId, $manageUserTaskManageState, $manageUserTaskViewAll, $manageUserTaskViewSameGroup, $manageUserExplore, $manageUserCreate, $manageUserModify, $manageUserGroupExplore, $manageUserGroupCreate, $manageUserGroupModify
    )
    {
        //判断是否存在数据
        $sql = "SELECT Count(*) FROM " . self::TableName_ManageUserAuthority . " WHERE SiteId=0 AND ManageUserId=:ManageUserId AND ManageUserGroupId=0 AND ChannelId=0;";

        $dataProperty = new DataProperty();
        $dataProperty->AddField("ManageUserId", $manageUserId);

        $hasCount = $this->dbOperator->GetInt($sql, $dataProperty);
        if ($hasCount <= 0) {
            $sql = "INSERT INTO " . self::TableName_ManageUserAuthority . "
	            (
	                `SiteId`,
	                `ManageUserId`,
                    `ManageUserTaskManageState`,
                    `ManageUserTaskViewAll`,
                    `ManageUserTaskViewSameGroup`,
                    `ManageUserExplore`,
                    `ManageUserCreate`,
                    `ManageUserModify`,
                    `ManageUserGroupExplore`,
                    `ManageUserGroupCreate`,
                    `ManageUserGroupModify`
		            )
		            VALUES
		            (
		            0,
		            :ManageUserId,
    	            :ManageUserTaskManageState,
                    :ManageUserTaskViewAll,
                    :ManageUserTaskViewSameGroup,
                    :ManageUserExplore,
                    :ManageUserCreate,
                    :ManageUserModify,
                    :ManageUserGroupExplore,
                    :ManageUserGroupCreate,
                    :ManageUserGroupModify
		            );";

            $dataProperty->AddField("ManageUserTaskManageState", $manageUserTaskManageState);
            $dataProperty->AddField("ManageUserTaskViewAll", $manageUserTaskViewAll);
            $dataProperty->AddField("ManageUserTaskViewSameGroup", $manageUserTaskViewSameGroup);
            $dataProperty->AddField("ManageUserExplore", $manageUserExplore);
            $dataProperty->AddField("ManageUserCreate", $manageUserCreate);
            $dataProperty->AddField("ManageUserModify", $manageUserModify);
            $dataProperty->AddField("ManageUserGroupExplore", $manageUserGroupExplore);
            $dataProperty->AddField("ManageUserGroupCreate", $manageUserGroupCreate);
            $dataProperty->AddField("ManageUserGroupModify", $manageUserGroupModify);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        } else {

            $sql = "UPDATE " . self::TableName_ManageUserAuthority . "
	                SET
                        `ManageUserTaskManageState` = :ManageUserTaskManageState,
                        `ManageUserTaskViewAll` = :ManageUserTaskViewAll,
                        `ManageUserTaskViewSameGroup` = :ManageUserTaskViewSameGroup,
                        `ManageUserExplore` = :ManageUserExplore,
                        `ManageUserCreate` = :ManageUserCreate,
                        `ManageUserModify` = :ManageUserModify,
                        `ManageUserGroupExplore` = :ManageUserGroupExplore,
                        `ManageUserGroupCreate` = :ManageUserGroupCreate,
                        `ManageUserGroupModify` = :ManageUserGroupModify
	                WHERE SiteId=0 AND ManageUserId=:ManageUserId AND ManageUserGroupId=0 AND ChannelId=0;";

            $dataProperty->AddField("ManageUserTaskManageState", $manageUserTaskManageState);
            $dataProperty->AddField("ManageUserTaskViewAll", $manageUserTaskViewAll);
            $dataProperty->AddField("ManageUserTaskViewSameGroup", $manageUserTaskViewSameGroup);
            $dataProperty->AddField("ManageUserExplore", $manageUserExplore);
            $dataProperty->AddField("ManageUserCreate", $manageUserCreate);
            $dataProperty->AddField("ManageUserModify", $manageUserModify);
            $dataProperty->AddField("ManageUserGroupExplore", $manageUserGroupExplore);
            $dataProperty->AddField("ManageUserGroupCreate", $manageUserGroupCreate);
            $dataProperty->AddField("ManageUserGroupModify", $manageUserGroupModify);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 为后台帐号设置站点权限（新增或修改）
     * @param int $siteId 站点id
     * @param int $manageUserId
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
     * @param int $channelManageTemplate
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
    public function CreateOrModifyForSiteAuthorityOfManageUser(
        $siteId, $manageUserId, $explore, $create, $modify, $delete, $disabled, $search, $rework, $audit1, $audit2, $audit3, $audit4, $refused, $doOthers, $doOthersInSameGroup, $publish, $channelManageTemplate, $userExplore, $userAdd, $userEdit, $userDelete, $userRoleExplore, $userRoleAdd, $userRoleEdit, $userRoleDelete, $userAlbumExplore, $userAlbumAdd, $userAlbumEdit, $userAlbumDelete, $userGroupExplore, $userLevelExplore, $userOrderExplore, $manageSite, $manageComment, $manageTemplateLibrary, $manageFilter, $manageFtp, $manageAd, $manageDocumentTag, $manageConfig
    )
    {
        //判断是否存在数据
        $sql = "SELECT Count(*) FROM " . self::TableName_ManageUserAuthority . " WHERE SiteId=:SiteId AND ManageUserId=:ManageUserId AND ManageUserGroupId=0 AND ChannelId=0;";

        $dataProperty = new DataProperty();
        $dataProperty->AddField("SiteId", $siteId);
        $dataProperty->AddField("ManageUserId", $manageUserId);

        $hasCount = $this->dbOperator->GetInt($sql, $dataProperty);
        if ($hasCount <= 0) {
            $sql = "INSERT INTO " . self::TableName_ManageUserAuthority . "
	            (
	                `SiteId`,
	                `ManageUserId`,
	                `ChannelExplore`,
	                `ChannelCreate`,
	                `ChannelModify`,
	                `ChannelDelete`,
	                `ChannelDisabled`,
	                `ChannelSearch`,
	                `ChannelRework`,
	                `ChannelAudit1`,
	                `ChannelAudit2`,
	                `ChannelAudit3`,
	                `ChannelAudit4`,
	                `ChannelRefused`,
	                `ChannelDoOthers`,
		            `ChannelDoOthersInSameGroup`,
		            `ChannelPublish`,
	                `ChannelManageTemplate`,
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
		            :ManageUserId,
		            :ChannelExplore,
		            :ChannelCreate,
		            :ChannelModify,
		            :ChannelDelete,
		            :ChannelDisabled,
		            :ChannelSearch,
		            :ChannelRework,
		            :ChannelAudit1,
		            :ChannelAudit2,
		            :ChannelAudit3,
		            :ChannelAudit4,
		            :ChannelRefused,
		            :ChannelDoOthers,
        	        :ChannelDoOthersInSameGroup,
		            :ChannelPublish,
	                :ChannelManageTemplate,
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

            $dataProperty->AddField("ChannelExplore", $explore);
            $dataProperty->AddField("ChannelCreate", $create);
            $dataProperty->AddField("ChannelModify", $modify);
            $dataProperty->AddField("ChannelDelete", $delete);
            $dataProperty->AddField("ChannelDisabled", $disabled);
            $dataProperty->AddField("ChannelSearch", $search);
            $dataProperty->AddField("ChannelRework", $rework);
            $dataProperty->AddField("ChannelAudit1", $audit1);
            $dataProperty->AddField("ChannelAudit2", $audit2);
            $dataProperty->AddField("ChannelAudit3", $audit3);
            $dataProperty->AddField("ChannelAudit4", $audit4);
            $dataProperty->AddField("ChannelRefused", $refused);
            $dataProperty->AddField("ChannelDoOthers", $doOthers);
            $dataProperty->AddField("ChannelDoOthersInSameGroup", $doOthersInSameGroup);
            $dataProperty->AddField("ChannelPublish", $publish);
            $dataProperty->AddField("ChannelManageTemplate", $channelManageTemplate);
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
	                    `ChannelExplore` = :ChannelExplore,
	                    `ChannelCreate` = :ChannelCreate,
	                    `ChannelModify` = :ChannelModify,
	                    `ChannelDelete` = :ChannelDelete,
	                    `ChannelDisabled` = :ChannelDisabled,
	                    `ChannelSearch` = :ChannelSearch,
	                    `ChannelRework` = :ChannelRework,
	                    `ChannelAudit1` = :ChannelAudit1,
	                    `ChannelAudit2` = :ChannelAudit2,
	                    `ChannelAudit3` = :ChannelAudit3,
	                    `ChannelAudit4` = :ChannelAudit4,
	                    `ChannelRefused` = :ChannelRefused,
	                    `ChannelDoOthers` = :ChannelDoOthers,
	                    `ChannelDoOthersInSameGroup` = :ChannelDoOthersInSameGroup,
	                    `ChannelPublish` = :ChannelPublish,
	                    `ChannelManageTemplate` = :ChannelManageTemplate,
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
                        `ManageSite` = :ManageSite,
                        `ManageComment` = :ManageComment,
                        `ManageTemplateLibrary` = :ManageTemplateLibrary,
                        `ManageFilter` = :ManageFilter,
                        `ManageFtp` = :ManageFtp,
                        `ManageAd` = :ManageAd,
                        `ManageDocumentTag` = :ManageDocumentTag,
                        `ManageConfig` = :ManageConfig
	                WHERE SiteId=:SiteId AND ManageUserId=:ManageUserId AND ManageUserGroupId=0 AND ChannelId=0;";

            $dataProperty->AddField("ChannelExplore", $explore);
            $dataProperty->AddField("ChannelCreate", $create);
            $dataProperty->AddField("ChannelModify", $modify);
            $dataProperty->AddField("ChannelDelete", $delete);
            $dataProperty->AddField("ChannelDisabled", $disabled);
            $dataProperty->AddField("ChannelSearch", $search);
            $dataProperty->AddField("ChannelRework", $rework);
            $dataProperty->AddField("ChannelAudit1", $audit1);
            $dataProperty->AddField("ChannelAudit2", $audit2);
            $dataProperty->AddField("ChannelAudit3", $audit3);
            $dataProperty->AddField("ChannelAudit4", $audit4);
            $dataProperty->AddField("ChannelRefused", $refused);
            $dataProperty->AddField("ChannelDoOthers", $doOthers);
            $dataProperty->AddField("ChannelDoOthersInSameGroup", $doOthersInSameGroup);
            $dataProperty->AddField("ChannelPublish", $publish);
            $dataProperty->AddField("ChannelManageTemplate", $channelManageTemplate);

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
     * 授权频道相关权限
     * @param int $siteId 站点id
     * @param int $manageUserId 后台管理员id
     * @param int $manageUserGroupId 后台管理员组id
     * @param int $manageUserGroupId 后台管理员组id
     * @param array $arrChannelList 相关频道id
     * @param array $arrValueList 权限对应值
     * @return int 结果
     */
    public function ChannelAuthorityModify($siteId,$manageUserId=0, $manageUserGroupId=0, $arrChannelList, $arrValueList)
    {
        $result=-1;
        if($siteId>0&&($manageUserId>0||$manageUserGroupId>0)&&count($arrChannelList)>0&&count($arrValueList)>0){

            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);
            $dataProperty->AddField("ManageUserId", $manageUserId);
            $dataProperty->AddField("ManageUserGroupId", $manageUserGroupId);
            foreach($arrChannelList as $key => $channelId){
                $sql = "SELECT count(*) FROM " . self::TableName_ManageUserAuthority . " WHERE SiteId=:SiteId AND ChannelId=:ChannelId AND ManageUserId=:ManageUserId AND ManageUserGroupId=:ManageUserGroupId;";
                $dataProperty->AddField("ChannelId", $channelId);
                $count = $this->dbOperator->GetInt($sql, $dataProperty);
                if($count<=0){
                    self::CreateEmptyOneForChannel($siteId,$channelId,$manageUserId,$manageUserGroupId);
                }else{
                    self::UpdateToEmptyOneForChannel($siteId,$channelId,$manageUserId,$manageUserGroupId);
                }
            }
            $arraySql=array();
            $arrayDataProperty=array();
            foreach($arrValueList as $key => $value){
                $updateSql="UPDATE " . self::TableName_ManageUserAuthority . " SET ".$value["Field"]."=1 WHERE SiteId=:SiteId AND ChannelId=:ChannelId AND ManageUserId=:ManageUserId AND ManageUserGroupId=:ManageUserGroupId;";
                array_push($arraySql,$updateSql);
                $dataProperty = new DataProperty();
                $dataProperty->AddField("SiteId", $siteId);
                $dataProperty->AddField("ManageUserId", $manageUserId);
                $dataProperty->AddField("ManageUserGroupId", $manageUserGroupId);
                $dataProperty->AddField("ChannelId", $value["ChannelId"]);
                array_push($arrayDataProperty,$dataProperty);
            }
            $result = $this->dbOperator->ExecuteBatch($arraySql, $arrayDataProperty);

        }
        return $result;
    }

    /**
     * 授权时为管理员或组新增该频道的空记录
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @param int $manageUserGroupId 后台管理员组id
     * @return int 结果
     */
    public function CreateEmptyOneForChannel($siteId, $channelId, $manageUserId=0, $manageUserGroupId=0)
    {
        $result=-1;
        if($siteId>0&&$channelId>0&&($manageUserId>0||$manageUserGroupId>0)){
            $sql = "INSERT INTO " . self::TableName_ManageUserAuthority . "
            (SiteId,ChannelId,ManageUserId,ManageUserGroupId)
            VALUES(:SiteId,:ChannelId,:ManageUserId,:ManageUserGroupId) ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);
            $dataProperty->AddField("ChannelId", $channelId);
            $dataProperty->AddField("ManageUserId", $manageUserId);
            $dataProperty->AddField("ManageUserGroupId", $manageUserGroupId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 授权时为管理员或组修改该频道的权限为空
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @param int $manageUserGroupId 后台管理员组id
     * @return int 结果
     */
    public function UpdateToEmptyOneForChannel($siteId, $channelId, $manageUserId=0, $manageUserGroupId=0)
    {
        $result=-1;
        if($siteId>0&&$channelId>0&&($manageUserId>0||$manageUserGroupId>0)){
            $sql = "UPDATE " . self::TableName_ManageUserAuthority . "
            Set

	                    `ChannelExplore` = 0,
	                    `ChannelCreate` = 0,
	                    `ChannelModify` = 0,
	                    `ChannelDelete` = 0,
	                    `ChannelDisabled` = 0,
	                    `ChannelSearch` = 0,
	                    `ChannelRework` = 0,
	                    `ChannelAudit1` = 0,
	                    `ChannelAudit2` = 0,
	                    `ChannelAudit3` = 0,
	                    `ChannelAudit4` = 0,
	                    `ChannelRefused` = 0,
	                    `ChannelDoOthers` = 0,
	                    `ChannelDoOthersInSameGroup` = 0,
	                    `ChannelPublish` = 0,
	                    `ChannelManageTemplate` = 0

            WHERE SiteId=:SiteId AND ChannelId=:ChannelId AND ManageUserId=:ManageUserId AND ManageUserGroupId=:ManageUserGroupId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);
            $dataProperty->AddField("ChannelId", $channelId);
            $dataProperty->AddField("ManageUserId", $manageUserId);
            $dataProperty->AddField("ManageUserGroupId", $manageUserGroupId);
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
     * 根据siteId，channelId，manageUserGroupId取得一条记录
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
     * 根据siteId，channelId，manageUserGroupId取得一条记录（已经缓冲）
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return array 一条权限记录
     */
    public function GetOneByManageUserId($siteId, $channelId, $manageUserId)
    {
        $dataProperty = new DataProperty();
        $dataProperty->AddField("SiteId", $siteId);
        $dataProperty->AddField("ManageUserId", $manageUserId);
        if ($channelId > 0) {
            $sql = "SELECT * FROM " . self::TableName_ManageUserAuthority . " WHERE SiteId=:SiteId AND ChannelId=:ChannelId AND ManageUserId=:ManageUserId AND ManageUserGroupId=0;";
            $dataProperty->AddField("ChannelId", $channelId);
        } else {
            $sql = "SELECT * FROM " . self::TableName_ManageUserAuthority . " WHERE SiteId=:SiteId AND ManageUserId=:ManageUserId AND ChannelId=0 AND ManageUserGroupId=0;";
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
            //检查用户站点权限
            $sql = "SELECT `" . $fieldName . "` FROM " . self::TableName_ManageUserAuthority . " WHERE SiteId=:SiteId AND ChannelId=0 AND ManageUserId=:ManageUserId AND ManageUserGroupId=0;";
            $dataProperty2 = new DataProperty();
            $dataProperty2->AddField("SiteId", $siteId);
            $dataProperty2->AddField("ManageUserId", $manageUserId);
            $result = $this->dbOperator->GetInt($sql, $dataProperty2);
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
    public function CanChannelExplore($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "ChannelExplore");
    }

    /**
     * 是否有新增权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否有新增权限
     */
    public function CanChannelCreate($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "ChannelCreate");
    }

    /**
     * 是否有编辑权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否有编辑权限
     */
    public function CanChannelModify($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "ChannelModify");
    }

    /**
     * 是否有删除权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否有删除权限
     */
    public function CanChannelDelete($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "ChannelDelete");
    }

    /**
     * 是否有停用权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否有停用权限
     */
    public function CanChannelDisabled($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "ChannelDisabled");
    }

    /**
     * 是否有查询权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否有查询权限
     */
    public function CanChannelSearch($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "ChannelSearch");
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
    public function CanChannelRework($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "ChannelRework");
    }

    /**
     * 是否有一审权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否有一审权限
     */
    public function CanChannelAudit1($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "ChannelAudit1");
    }

    /**
     * 是否有二审权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否有二审权限
     */
    public function CanChannelAudit2($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "ChannelAudit2");
    }

    /**
     * 是否有三审权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否有三审权限
     */
    public function CanChannelAudit3($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "ChannelAudit3");
    }

    /**
     * 是否有终审权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否有终审权限
     */
    public function CanChannelAudit4($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "ChannelAudit4");
    }

    /**
     * 是否有已否权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否有已否权限
     */
    public function CanChannelRefused($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "ChannelRefused");
    }

    /**
     * 是否有操作他人权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否有操作他人权限
     */
    public function CanChannelDoOthers($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "ChannelDoOthers");
    }

    /**
     * 是否有操作同一组内他人权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否有操作同一组内他人权限
     */
    public function CanChannelDoOthersInSameGroup($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "ChannelDoOthersInSameGroup");
    }


    /**
     * 是否有操作同一组中他人的权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否有操作同一组中他人的权限
     */
    public function CanChannelDoSameGroupOthers($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "ChannelDoSameGroupOthers");
    }

    /**
     * 是否有发布权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool  是否有发布权限
     */
    public function CanChannelPublish($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "ChannelPublish");
    }

    /**
     * 是否有管理频道模板的权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool  是否有管理频道模板的权限
     */
    public function CanChannelManageTemplate($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "ChannelManageTemplate");
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

    /**
     * 是否有管理员任务修改状态权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否有管理员任务修改状态权限
     */
    public function CanManageUserTaskManageState($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "ManageUserTaskManageState");
    }

    /**
     * 是否有管理员任务查看所有任务权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否有管理员任务查看所有任务权限
     */
    public function CanManageUserTaskViewAll($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "ManageUserTaskViewAll");
    }

    /**
     * 是否有管理员任务查看同一分组任务权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否有管理员任务查看同一分组任务权限
     */
    public function CanManageUserTaskViewSameGroup($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "ManageUserTaskViewSameGroup");
    }


    /**
     * 是否有浏览管理员列表权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否有浏览管理员列表权限
     */
    public function CanManageUserExplore($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "ManageUserExplore");
    }

    /**
     * 是否有新增管理员的权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否有新增管理员的权限
     */
    public function CanManageUserCreate($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "ManageUserCreate");
    }

    /**
     * 是否有编辑管理员的权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否有编辑管理员的权限
     */
    public function CanManageUserModify($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "ManageUserModify");
    }


    /**
     * 是否有浏览管理员分组列表权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否有浏览管理员分组列表权限
     */
    public function CanManageUserGroupExplore($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "ManageUserGroupExplore");
    }

    /**
     * 是否有新增管理员分组的权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否有浏览管理员分组列表权限
     */
    public function CanManageUserGroupCreate($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "ManageUserGroupCreate");
    }

    /**
     * 是否有编辑管理员分组的权限
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否有编辑管理员分组的权限
     */
    public function CanManageUserGroupModify($siteId, $channelId, $manageUserId)
    {
        return self::GetFieldValue($siteId, $channelId, $manageUserId, "ManageUserGroupModify");
    }


    /**
     * 是否有编辑管理员赛事的权限
     * @param int $siteId 站点id
     * @param int $manageUserId 后台管理员id
     * @return bool 是否有编辑管理员分组的权限
     */
    public function CanManageLeague($siteId, $manageUserId)
    {
        return true;
    }

    /**
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_ManageUserAuthority){
        return parent::GetFields(self::TableName_ManageUserAuthority);
    }

}

?>
