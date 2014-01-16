<?php

/**
 * 后台管理 权限 后台数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Manage
 * @author zhangchi
 */
class ManageUserAuthorityManageData extends BaseManageData {

    /**
     * 建立频道时授权
     * @param int $siteId 站点id
     * @param int $documentChannelId 频道id
     * @param int $adminUserId 管理员id
     * @return int 创建结果数字，1为成功 
     */
    public function CreateForDocumentChannel($siteId, $documentChannelId, $adminUserId) {
        $sql = "INSERT INTO " . self::tableName . " (
            `SiteID`,
            `DocumentChannelID`,
            `AdminUserGroupID`,
            `AdminUserID`,
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
            `DoSameGroupOthers`,
            `Publish`
            )
            values
	( 
	:siteid, 
	:documentchannelid, 
	0, 
	:adminuserid, 
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
	)";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("siteid", $siteId);
        $dataProperty->AddField("documentchannelid", $documentChannelId);
        $dataProperty->AddField("adminuserid", $adminUserId);
        $result = $this->dbOperator->Execute($sql, $dataProperty);

        if ($result > 0) {
            //删除缓冲
            $cacheDir = 'data' . DIRECTORY_SEPARATOR . 'docdata';
            DataCache::RemoveDir($cacheDir);
        }

        return $result;
    }

    /**
     * 更新权限
     * @param type $siteId
     * @param type $adminUserId
     * @param type $documentChannelId
     * @param type $field
     * @param type $value
     * @return type
     */
    public function Update($siteId, $adminUserId, $documentChannelId, $field, $value) {
        $dataProperty = new DataProperty();
        $dataProperty->AddField("siteid", $siteId);
        $dataProperty->AddField("documentchannelid", $documentChannelId);
        $dataProperty->AddField("adminuserid", $adminUserId);

        $sql = "select count(*) from " . self::tableName . " where siteid=:siteid and documentchannelid=:documentchannelid and adminuserid=:adminuserid";

        $result = $this->dbOperator->GetInt($sql, $dataProperty);

        if ($result > 0) {
            $sql = "update " . self::tableName . " set `" . $field . "`=:" . $field . " where siteid=:siteid and documentchannelid=:documentchannelid and adminuserid=:adminuserid";
        } else {
            $sql = "insert into " . self::tableName . " (siteid,documentchannelid,`" . $field . "`,adminuserid) values (:siteid,:documentchannelid,:" . $field . ",:adminuserid)";
        }
        $dataProperty2 = new DataProperty();
        $dataProperty2->AddField("siteid", $siteId);
        $dataProperty2->AddField("documentchannelid", $documentChannelId);
        $dataProperty2->AddField("adminuserid", $adminUserId);
        $dataProperty2->AddField($field, $value);
        $result = $this->dbOperator->Execute($sql, $dataProperty2);

        return $result;
    }

    /**
     * 为后台帐号分组和站点设置权限（新增或修改）
     * @param type $siteId
     * @param type $adminUserGroupId
     * @param type $explore
     * @param type $create
     * @param type $modify
     * @param type $delete
     * @param type $disabled
     * @param type $search
     * @param type $rework
     * @param type $audit1
     * @param type $audit2
     * @param type $audit3
     * @param type $audit4
     * @param type $refused
     * @param type $doOthers
     * @param type $publish
     * @param type $userExplore
     * @param type $userAdd
     * @param type $userEdit
     * @param type $userDelete
     * @param type $userRoleExplore
     * @param type $userRoleAdd
     * @param type $userRoleEdit
     * @param type $userRoleDelete
     * @param type $userAlbumExplore
     * @param type $userAlbumAdd
     * @param type $userAlbumEdit
     * @param type $userAlbumDelete
     * @param type $userGroupExplore
     * @param type $userLevelExplore
     * @param type $userOrderExplore
     * @param type $manageSite
     * @param type $manageComment
     * @param type $manageTemplateLibrary
     * @param type $manageFilter
     * @param type $manageFtp
     * @param type $manageAd
     * @param type $manageDocumentTag
     * @param type $manageConfig
     * @return type
     */
    public function CreateOrModifyForSiteAndAdminUserGroup(
    $siteId, $adminUserGroupId, $explore, $create, $modify, $delete, $disabled, $search, $rework, $audit1, $audit2, $audit3, $audit4, $refused, $doOthers, $doSameGroupOthers, $publish, $userExplore, $userAdd, $userEdit, $userDelete, $userRoleExplore, $userRoleAdd, $userRoleEdit, $userRoleDelete, $userAlbumExplore, $userAlbumAdd, $userAlbumEdit, $userAlbumDelete, $userGroupExplore, $userLevelExplore, $userOrderExplore, $manageSite, $manageComment, $manageTemplateLibrary, $manageFilter, $manageFtp, $manageAd, $manageDocumentTag, $manageConfig
    ) {
        $result = -1;
        //判断是否存在数据
        $sql = "SELECT Count(*) FROM " . self::tableName . " WHERE SiteID=:SiteID AND AdminUserGroupID=:AdminUserGroupID AND AdminUserID=0 AND DocumentChannelID=0;";

        $dataProperty = new DataProperty();
        $dataProperty->AddField("SiteID", $siteId);
        $dataProperty->AddField("AdminUserGroupID", $adminUserGroupId);

        $hasCount = $this->dbOperator->GetInt($sql, $dataProperty);

        if ($hasCount <= 0) {
            $sql = "INSERT INTO " . self::tableName . "
	(
	`SiteID`,  
	`AdminUserGroupID`,
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
	`DoSameGroupOthers`,
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
	:SiteID, 
	:AdminUserGroupID,
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
        :DoSameGroupOthers,
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
            $dataProperty->AddField("DoSameGroupOthers", $doSameGroupOthers);
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

            $sql = "UPDATE " . self::tableName . "
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
	`DoSameGroupOthers` = :DoSameGroupOthers, 
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
	
	WHERE SiteID=:SiteID AND AdminUserGroupID=:AdminUserGroupID AND AdminUserID=0 AND DocumentChannelID=0;";

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
            $dataProperty->AddField("DoSameGroupOthers", $doSameGroupOthers);
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
     * @param type $siteId
     * @param type $adminUserId 
     */
    public function Remove($siteId, $adminUserId) {
        $sql = "DELETE FROM " . self::tableName . " WHERE siteid=:siteid and adminuserid=:adminuserid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("siteid", $siteId);
        $dataProperty->AddField("adminuserid", $adminUserId);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 根据$siteId，$documentChannelId，$adminUserId取得一条记录
     * @param type $siteId
     * @param type $documentChannelId
     * @param type $adminUserId
     * @return type 
     */
    public function GetOne($siteId, $documentChannelId, $adminUserId) {
        $dataProperty = new DataProperty();
        $dataProperty->AddField("siteid", $siteId);
        $dataProperty->AddField("documentchannelid", $documentChannelId);
        $dataProperty->AddField("adminuserid", $adminUserId);

        $sql = "SELECT * FROM " . self::tableName . " WHERE siteid=:siteid AND documentchannelid=:documentchannelid AND adminuserid=:adminuserid";

        $result = $this->dbOperator->GetRow($sql, $dataProperty);
        return $result;
    }

    /**
     * 根据$siteId，$documentChannelId，$adminUserGroupId取得一条记录（已经缓冲）
     * @param type $siteId
     * @param type $documentChannelId
     * @param type $adminUserGroupId
     * @return type
     */
    public function GetOneByAdminUserGroupId($siteId, $documentChannelId, $adminUserGroupId) {
        $dataProperty = new DataProperty();
        $dataProperty->AddField("siteid", $siteId);
        $dataProperty->AddField("adminusergroupid", $adminUserGroupId);
        if ($documentChannelId > 0) {
            $sql = "SELECT * FROM " . self::tableName . " WHERE siteid=:siteid AND documentchannelid=:documentchannelid AND adminusergroupid=:adminusergroupid AND AdminUserID=0";
            $dataProperty->AddField("documentchannelid", $documentChannelId);
        } else {
            $sql = "SELECT * FROM " . self::tableName . " WHERE siteid=:siteid AND adminusergroupid=:adminusergroupid AND documentchannelid=0 AND AdminUserID=0;";
        }

        $result = $this->dbOperator->GetRow($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得单条权限字段信息
     * @param type $siteid
     * @param type $documentchannelid
     * @param type $adminuserid
     * @param type $fieldname
     * @return type 
     */
    public function GetPopedomField($siteid, $documentchannelid, $adminuserid, $fieldname) {

        if (intval($adminuserid) == 1) {
            return TRUE;
        }
        $dataProperty = new DataProperty();
        $dataProperty->AddField("siteid", $siteid);
        $dataProperty->AddField("documentchannelid", $documentchannelid);
        $dataProperty->AddField("adminuserid", $adminuserid);

        //检查用户频道权限
        $sql = "select `" . $fieldname . "` from " . self::tableName . " where siteid=:siteid and documentchannelid=:documentchannelid and adminuserid=:adminuserid";

        $result = $this->dbOperator->GetInt($sql, $dataProperty);

        if ($result <= 0) {
            //检查用户组频道权限
            $sql = "select `" . $fieldname . "` from " . self::tableName . " where siteid=:siteid and documentchannelid=:documentchannelid and adminusergroupid in (select adminusergroupid from cst_adminuser where adminuserid=:adminuserid)";
            $result = $this->dbOperator->GetInt($sql, $dataProperty);
        }
        if ($result <= 0) {
            //检查用户组站点权限
            $sql = "select `" . $fieldname . "` from " . self::tableName . " where siteid=:siteid and documentchannelid=0 and adminuserid=0 and adminusergroupid in (select adminusergroupid from cst_adminuser where adminuserid=:adminuserid)";
            $dataProperty2 = new DataProperty();
            $dataProperty2->AddField("siteid", $siteid);
            $dataProperty2->AddField("adminuserid", $adminuserid);
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
     * @param type $siteid
     * @param type $documentchannelid
     * @param type $adminuserid
     * @return type 
     */
    public function CanExplore($siteid, $documentchannelid, $adminuserid) {
        return self::GetPopedomField($siteid, $documentchannelid, $adminuserid, "Explore");
    }

    /**
     * 是否有新增权限
     * @param type $siteid
     * @param type $documentchannelid
     * @param type $adminuserid
     * @return type 
     */
    public function CanCreate($siteid, $documentchannelid, $adminuserid) {
        return self::GetPopedomField($siteid, $documentchannelid, $adminuserid, "Create");
    }

    /**
     * 是否有编辑权限
     * @param type $siteid
     * @param type $documentchannelid
     * @param type $adminuserid
     * @return type 
     */
    public function CanModify($siteid, $documentchannelid, $adminuserid) {
        return self::GetPopedomField($siteid, $documentchannelid, $adminuserid, "Modify");
    }

    /**
     * 是否有删除权限
     * @param type $siteid
     * @param type $documentchannelid
     * @param type $adminuserid
     * @return type 
     */
    public function CanDelete($siteid, $documentchannelid, $adminuserid) {
        return self::GetPopedomField($siteid, $documentchannelid, $adminuserid, "Delete");
    }

    /**
     * 是否有停用权限
     * @param type $siteid
     * @param type $documentchannelid
     * @param type $adminuserid
     * @return type 
     */
    public function CanDisabled($siteid, $documentchannelid, $adminuserid) {
        return self::GetPopedomField($siteid, $documentchannelid, $adminuserid, "Disabled");
    }

    /**
     * 是否有查询权限
     * @param type $siteid
     * @param type $documentchannelid
     * @param type $adminuserid
     * @return type 
     */
    public function CanSearch($siteid, $documentchannelid, $adminuserid) {
        return self::GetPopedomField($siteid, $documentchannelid, $adminuserid, "Search");
    }

    public function CanUserExplore($siteid, $documentchannelid, $adminuserid) {
        return self::GetPopedomField($siteid, $documentchannelid, $adminuserid, "UserExplore");
    }

    /**
     * 是否有会员新增权限
     * @param type $siteid
     * @param type $documentchannelid
     * @param type $adminuserid
     * @return type
     */
    public function CanUserAdd($siteid, $documentchannelid, $adminuserid) {
        return self::GetPopedomField($siteid, $documentchannelid, $adminuserid, "UserAdd");
    }

    /**
     * 是否有会员编辑权限
     * @param type $siteid
     * @param type $documentchannelid
     * @param type $adminuserid
     * @return type
     */
    public function CanUserEdit($siteid, $documentchannelid, $adminuserid) {
        return self::GetPopedomField($siteid, $documentchannelid, $adminuserid, "UserEdit");
    }

    /**
     * 是否有会员删除权限
     * @param type $siteid
     * @param type $documentchannelid
     * @param type $adminuserid
     * @return type
     */
    public function CanUserDelete($siteid, $documentchannelid, $adminuserid) {
        return self::GetPopedomField($siteid, $documentchannelid, $adminuserid, "UserDelete");
    }

    public function CanUserRoleExplore($siteid, $documentchannelid, $adminuserid) {
        return self::GetPopedomField($siteid, $documentchannelid, $adminuserid, "UserRoleExplore");
    }

    public function CanUserRoleAdd($siteid, $documentchannelid, $adminuserid) {
        return self::GetPopedomField($siteid, $documentchannelid, $adminuserid, "UserRoleAdd");
    }

    public function CanUserRoleEdit($siteid, $documentchannelid, $adminuserid) {
        return self::GetPopedomField($siteid, $documentchannelid, $adminuserid, "UserRoleEdit");
    }

    public function CanUserRoleDelete($siteid, $documentchannelid, $adminuserid) {
        return self::GetPopedomField($siteid, $documentchannelid, $adminuserid, "UserRoleDelete");
    }

    public function CanUserAlbumExplore($siteid, $documentchannelid, $adminuserid) {
        return self::GetPopedomField($siteid, $documentchannelid, $adminuserid, "UserAlbumExplore");
    }

    public function CanUserAlbumAdd($siteid, $documentchannelid, $adminuserid) {
        return self::GetPopedomField($siteid, $documentchannelid, $adminuserid, "UserAlbumAdd");
    }

    public function CanUserAlbumEdit($siteid, $documentchannelid, $adminuserid) {
        return self::GetPopedomField($siteid, $documentchannelid, $adminuserid, "UserAlbumEdit");
    }

    public function CanUserAlbumDelete($siteid, $documentchannelid, $adminuserid) {
        return self::GetPopedomField($siteid, $documentchannelid, $adminuserid, "UserAlbumDelete");
    }

    /**
     * 是否有返工权限
     * @param type $siteid
     * @param type $documentchannelid
     * @param type $adminuserid
     * @return type 
     */
    public function CanRework($siteid, $documentchannelid, $adminuserid) {
        return self::GetPopedomField($siteid, $documentchannelid, $adminuserid, "Rework");
    }

    /**
     * 是否有一审权限
     * @param type $siteid
     * @param type $documentchannelid
     * @param type $adminuserid
     * @return type 
     */
    public function CanAudit1($siteid, $documentchannelid, $adminuserid) {
        return self::GetPopedomField($siteid, $documentchannelid, $adminuserid, "Audit1");
    }

    /**
     * 是否有二审权限
     * @param type $siteid
     * @param type $documentchannelid
     * @param type $adminuserid
     * @return type 
     */
    public function CanAudit2($siteid, $documentchannelid, $adminuserid) {
        return self::GetPopedomField($siteid, $documentchannelid, $adminuserid, "Audit2");
    }

    /**
     * 是否有三审权限
     * @param type $siteid
     * @param type $documentchannelid
     * @param type $adminuserid
     * @return type 
     */
    public function CanAudit3($siteid, $documentchannelid, $adminuserid) {
        return self::GetPopedomField($siteid, $documentchannelid, $adminuserid, "Audit3");
    }

    /**
     * 是否有终审权限
     * @param type $siteid
     * @param type $documentchannelid
     * @param type $adminuserid
     * @return type 
     */
    public function CanAudit4($siteid, $documentchannelid, $adminuserid) {
        return self::GetPopedomField($siteid, $documentchannelid, $adminuserid, "Audit4");
    }

    /**
     * 是否有已否权限
     * @param type $siteid
     * @param type $documentchannelid
     * @param type $adminuserid
     * @return type 
     */
    public function CanRefused($siteid, $documentchannelid, $adminuserid) {
        return self::GetPopedomField($siteid, $documentchannelid, $adminuserid, "Refused");
    }

    /**
     * 是否有操作他人权限
     * @param type $siteid
     * @param type $documentchannelid
     * @param type $adminuserid
     * @return type 
     */
    public function CanDoOthers($siteid, $documentchannelid, $adminuserid) {
        return self::GetPopedomField($siteid, $documentchannelid, $adminuserid, "DoOthers");
    }

    /**
     * 是否有操作同一组中他人的权限
     * @param type $siteid
     * @param type $documentchannelid
     * @param type $adminuserid
     * @return type
     */
    public function CanDoSameGroupOthers($siteid, $documentchannelid, $adminuserid) {
        return self::GetPopedomField($siteid, $documentchannelid, $adminuserid, "DoSameGroupOthers");
    }

    /**
     * 是否有发布权限
     * @param type $siteid
     * @param type $documentchannelid
     * @param type $adminuserid
     * @return type 
     */
    public function CanPublish($siteid, $documentchannelid, $adminuserid) {
        return self::GetPopedomField($siteid, $documentchannelid, $adminuserid, "Publish");
    }

    /**
     * 是否可以管理站点
     * @param type $siteid
     * @param type $documentchannelid
     * @param type $adminuserid
     * @return type
     */
    public function CanManageSite($siteid, $documentchannelid, $adminuserid) {
        return self::GetPopedomField($siteid, $documentchannelid, $adminuserid, "ManageSite");
    }

    /**
     * 是否可以管理评论
     * @param type $siteid
     * @param type $documentchannelid
     * @param type $adminuserid
     * @return type
     */
    public function CanManageComment($siteid, $documentchannelid, $adminuserid) {
        return self::GetPopedomField($siteid, $documentchannelid, $adminuserid, "ManageComment");
    }

    /**
     * 是否可以管理模板库
     * @param type $siteid
     * @param type $documentchannelid
     * @param type $adminuserid
     * @return type
     */
    public function CanManageTemplateLibrary($siteid, $documentchannelid, $adminuserid) {
        return self::GetPopedomField($siteid, $documentchannelid, $adminuserid, "ManageTemplateLibrary");
    }

    /**
     * 是否可以管理过滤字符
     * @param type $siteid
     * @param type $documentchannelid
     * @param type $adminuserid
     * @return type
     */
    public function CanManageFilter($siteid, $documentchannelid, $adminuserid) {
        return self::GetPopedomField($siteid, $documentchannelid, $adminuserid, "ManageFilter");
    }

    /**
     * 是否可以管理FTP
     * @param type $siteid
     * @param type $documentchannelid
     * @param type $adminuserid
     * @return type
     */
    public function CanManageFtp($siteid, $documentchannelid, $adminuserid) {
        return self::GetPopedomField($siteid, $documentchannelid, $adminuserid, "ManageFtp");
    }

    /**
     * 是否可以管理广告
     * @param type $siteid
     * @param type $documentchannelid
     * @param type $adminuserid
     * @return type
     */
    public function CanManageAd($siteid, $documentchannelid, $adminuserid) {
        return self::GetPopedomField($siteid, $documentchannelid, $adminuserid, "ManageAd");
    }

    /**
     * 是否可以管理标签
     * @param type $siteid
     * @param type $documentchannelid
     * @param type $adminuserid
     * @return type
     */
    public function CanManageDocumentTag($siteid, $documentchannelid, $adminuserid) {
        return self::GetPopedomField($siteid, $documentchannelid, $adminuserid, "ManageDocumentTag");
    }

    /**
     * 是否可以管理配置
     * @param type $siteid
     * @param type $documentchannelid
     * @param type $adminuserid
     * @return type
     */
    public function CanManageConfig($siteid, $documentchannelid, $adminuserid) {
        return self::GetPopedomField($siteid, $documentchannelid, $adminuserid, "ManageConfig");
    }

}

?>
