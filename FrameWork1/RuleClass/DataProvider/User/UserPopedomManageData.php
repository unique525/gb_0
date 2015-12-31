<?php
/**
 * 后台 会员权限 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author zhangchi
 */
class UserPopedomManageData extends BaseManageData {

    /**
     * 根据论坛ID和会员ID设置权限值
     * @param int $forumId
     * @param int $userId
     * @param string $userPopedomName
     * @param string $userPopedomValue
     * @return int
     */
    public function SetValueByForumIdAndUserId(
        $forumId,
        $userId,
        $userPopedomName,
        $userPopedomValue
    ) {

        $sql = "SELECT count(*)

                FROM " . self::TableName_UserPopedom . "

                WHERE ForumId=:ForumId
                    AND UserId=:UserId
                    AND UserPopedomName=:UserPopedomName;
                ";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("ForumId", $forumId);
        $dataProperty->AddField("UserId", $userId);
        $dataProperty->AddField("UserPopedomName", $userPopedomName);
        $hasCount = $this->dbOperator->GetInt($sql, $dataProperty);

        if ($hasCount > 0) { //已存在相关配置记录
            $sql = "UPDATE " . self::TableName_UserPopedom . "
                    SET UserPopedomValue=:UserPopedomValue
                    WHERE ForumId=:ForumId
                    AND UserId=:UserId
                    AND UserPopedomName=:UserPopedomName;
                    ";
            $dataProperty->AddField("UserPopedomValue", $userPopedomValue);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        } else {
            $sql = "INSERT INTO " . self::TableName_UserPopedom . "
            (   ForumId,
                UserId,
                UserPopedomName,
                UserPopedomValue
                )
            VALUES (
                :ForumId,
                :UserId,
                :UserPopedomName,
                :UserPopedomValue
                );";
            $dataProperty->AddField("UserPopedomValue", $userPopedomValue);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 根据站点ID和会员组ID设置权限值
     * @param int $siteId
     * @param int $userGroupId
     * @param string $userPopedomName
     * @param string $userPopedomValue
     * @return int
     */
    public function SetValueBySiteIdAndUserGroupId(
        $siteId,
        $userGroupId,
        $userPopedomName,
        $userPopedomValue
    ) {

        $sql = "SELECT count(*)
                FROM " . self::TableName_UserPopedom . "

                WHERE SiteId=:SiteId
                AND UserGroupId=:UserGroupId
                AND UserPopedomName=:UserPopedomName;
                ";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("SiteId", $siteId);
        $dataProperty->AddField("UserGroupId", $userGroupId);
        $dataProperty->AddField("UserPopedomName", $userPopedomName);
        $hasCount = $this->dbOperator->GetInt($sql, $dataProperty);

        if ($hasCount > 0) { //已存在相关配置记录
            $sql = "UPDATE " . self::TableName_UserPopedom . " SET
                    UserPopedomValue=:UserPopedomValue
                    WHERE SiteId=:SiteId
                    AND UserGroupId=:UserGroupId
                    AND UserPopedomName=:UserPopedomName;";
            $dataProperty->AddField("UserPopedomValue", $userPopedomValue);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        } else {
            $sql = "INSERT INTO " . self::TableName_UserPopedom . "
                    (
                    SiteId,
                    UserGroupId,
                    UserPopedomName,
                    UserPopedomValue
                    )
                    VALUES
                    (
                    :SiteId,
                    :UserGroupId,
                    :UserPopedomName,
                    :UserPopedomValue
                    );";
            $dataProperty->AddField("UserPopedomValue", $userPopedomValue);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }

    /**
     * 根据站点ID和会员ID设置权限值
     * @param int $siteId
     * @param int $userId
     * @param string $userPopedomName
     * @param string $userPopedomValue
     * @return int
     */
    public function SetValueBySiteIdAndUserId(
        $siteId,
        $userId,
        $userPopedomName,
        $userPopedomValue
    ) {

        $sql = "SELECT count(*)
                FROM " . self::TableName_UserPopedom . "

                WHERE SiteId=:SiteId
                AND UserId=:UserId
                AND UserPopedomName=:UserPopedomName;
                ";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("SiteId", $siteId);
        $dataProperty->AddField("UserId", $userId);
        $dataProperty->AddField("UserPopedomName", $userPopedomName);
        $hasCount = $this->dbOperator->GetInt($sql, $dataProperty);

        if ($hasCount > 0) { //已存在相关配置记录
            $sql = "UPDATE " . self::TableName_UserPopedom . " SET
                    UserPopedomValue=:UserPopedomValue
                    WHERE SiteId=:SiteId
                    AND UserId=:UserId
                    AND UserPopedomName=:UserPopedomName;";
            $dataProperty->AddField("UserPopedomValue", $userPopedomValue);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        } else {
            $sql = "INSERT INTO " . self::TableName_UserPopedom . "
                    (
                    SiteId,
                    UserId,
                    UserPopedomName,
                    UserPopedomValue
                    )
                    VALUES
                    (
                    :SiteId,
                    :UserId,
                    :UserPopedomName,
                    :UserPopedomValue
                    );";
            $dataProperty->AddField("UserPopedomValue", $userPopedomValue);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }

    /**
     * 根据论坛ID和会员ID取得权限数据列表
     * @param int $forumId
     * @param int $userId
     * @param bool $withCache 是否缓存，默认true
     * @return array 权限数据列表
     */
    public function GetListByForumIdAndUserId(
        $forumId,
        $userId,
        $withCache = true
    ) {
        if($forumId>0 && $userId>0){

            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'user_popedom_data';
            $cacheFile = 'user_popedom_get_list_by_forum_id_and_user_id.cache_'
                .'forum_id'. $forumId . '_user_id' . $userId;
            $sql = "SELECT *
                FROM " . self::TableName_UserPopedom . "
                WHERE SiteId=0
                    AND UserId=:UserId
                    AND UserGroupId=0
                    AND ForumId=:ForumId
                    AND ChannelId=0;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserGroupId", $userId);
            $dataProperty->AddField("ForumId", $forumId);

            return $this->GetInfoOfArrayList($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);

        }else{
            return null;
        }
    }

    /**
     * 根据论坛ID和会员组ID取得权限数据列表
     * @param int $forumId
     * @param int $userGroupId
     * @param bool $withCache 是否缓存，默认true
     * @return array 权限数据列表
     */
    public function GetListByForumIdAndUserGroupId(
        $forumId,
        $userGroupId,
        $withCache = true

    ) {

        if($forumId>0 && $userGroupId>0){

            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'user_popedom_data';
            $cacheFile = 'user_popedom_get_list_by_forum_id_and_user_group_id.cache_'
                .'forum_id'. $forumId . '_user_group_id' . $userGroupId;
            $sql = "SELECT *
                FROM " . self::TableName_UserPopedom . "
                WHERE SiteId=0
                    AND UserId=0
                    AND UserGroupId=:UserGroupId
                    AND ForumId=:ForumId
                    AND ChannelId=0;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserGroupId", $userGroupId);
            $dataProperty->AddField("ForumId", $forumId);

            return $this->GetInfoOfArrayList($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);

        }else{
            return null;
        }

    }

    /**
     * 根据站点ID和会员ID取得权限数据列表
     * @param int $siteId
     * @param int $userId
     * @param bool $withCache 是否缓存，默认true
     * @return array 权限数据列表
     */
    public function GetListBySiteIdAndUserId(
        $siteId,
        $userId,
        $withCache = true
    ) {

        if($siteId>0 && $userId>0){

            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'user_popedom_data';
            $cacheFile = 'user_popedom_get_list_by_site_id_and_user_id.cache_'
                .'site_id'. $siteId . '_user_id' . $userId;
            $sql = "SELECT *
                FROM " . self::TableName_UserPopedom . "
                WHERE SiteId=:SiteId
                    AND UserId=:UserId
                    AND UserGroupId=0
                    AND ForumId=0
                    AND ChannelId=0;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);
            $dataProperty->AddField("UserId", $userId);

            return $this->GetInfoOfArrayList($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);

        }else{
            return null;
        }
    }

    /**
     * 根据站点id和会员组id取得权限数据列表
     * @param int $siteId
     * @param int $userGroupId
     * @param bool $withCache 是否缓存，默认true
     * @return array 权限数据列表
     */
    public function GetListBySiteIdAndUserGroupId(
        $siteId,
        $userGroupId,
        $withCache = true
    ) {

        if($siteId>0 && $userGroupId>0){

            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'user_popedom_data';
            $cacheFile = 'user_popedom_get_list_by_site_id_and_user_group_id.cache_'
                .'site_id'. $siteId . '_user_group_id' . $userGroupId;
            $sql = "SELECT *
                FROM " . self::TableName_UserPopedom . "
                WHERE SiteId=:SiteId
                    AND UserGroupId=:UserGroupId
                    AND UserId=0
                    AND ForumId=0
                    AND ChannelId=0;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);
            $dataProperty->AddField("UserGroupId", $userGroupId);

            return $this->GetInfoOfArrayList($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);

        }else{
            return null;
        }

    }

    /**
     * 初始化会员权限
     * @param int $userId 会员id
     * @param int $userGroupId 会员组id
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $forumId 论坛id
     * @return int 操作结果
     */
    public function Init($userId = 0, $userGroupId = 0, $siteId = 0, $channelId = 0, $forumId = 0) {

        $userId = intval($userId);
        $userGroupId = intval($userGroupId);
        $siteId = intval($siteId);
        $channelId = intval($channelId);
        $forumId = intval($forumId);


        $sql = "INSERT INTO " . self::TableName_UserPopedom . "

	    (UserPopedomName,UserPopedomValue,UserId,UserGroupId,SiteId,ChannelId,ForumId) VALUES

        ('UserSignMaxContentCount','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('UserAlbumMaxUploadPerOnce','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('UserAllowHidden','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('UserCanPostActivity','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('UserSetRecCountLimit','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('UserSetRecCountDayMax','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumAllowPostTopic','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumAllowPostReply','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumAllowPostAdvancedTopicAccess','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumAllowPostMediaTopic','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumAllowPostAdvancedTopicTitle','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumAllowPostMediaReply','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumEditSelfPost','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumEditOtherPost','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumDeleteSelfPost','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumDeleteOtherPost','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumForbidOtherEditMyTopic','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumForbidOtherEditMyReply','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumForbidOtherDeleteMyTopic','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumForbidOtherDeleteMyReply','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumSetSelfTopicLock','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumSetSelfTopicBanReply','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumSetOtherTopicLock','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumSetOtherTopicBanReply','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumPostAddMoney','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumPostAddMoneyForSelf','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumPostAddCharm','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumPostAddCharmForSelf','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumPostAddScore','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumPostAddScoreForSelf','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumPostAddExp','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumPostAddExpForSelf','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumPostSetBoardTop','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumPostSetRegionTop','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumPostSetAllTop','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumMoveTopic','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumCopyTopic','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumSetBestTopic','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumSetRecTopic','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumPostAllowUpload','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumAllowViewAttachment','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumShowEditInfo','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumAllowSearch','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumIgnoreLimit','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumUserAllowHidden','0',$userId,$userGroupId,$siteId,$channelId,$forumId),

        ('ForumPostTopicMaxContentCount','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumPostReplyMaxContentCount','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumPostTopicMinContentCount','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumPostReplyMinContentCount','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumPostAddMoneyLimit','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumPostAddCharmLimit','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumPostAddScoreLimit','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumPostAddExpLimit','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumPostAllowUploadType','jpg,png,gif,bmp',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumPostMaxUploadSize','500',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumPostMaxUploadPerDay','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumPostMaxUploadPerPost','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumUserSignMaxContentCount','0',$userId,$userGroupId,$siteId,$channelId,$forumId)

        ;";
        $result = $this->dbOperator->Execute($sql, null);
        return $result;
    }

    /**
     * 初始化前台管理员权限
     * @param int $userId 会员id
     * @param int $userGroupId 会员组id
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $forumId 论坛id
     * @return int 操作结果
     */
    public function InitManagePopedom($userId = 0, $userGroupId = 0, $siteId = 0, $channelId = 0, $forumId = 0) {

        $userId = intval($userId);
        $userGroupId = intval($userGroupId);
        $siteId = intval($siteId);
        $channelId = intval($channelId);
        $forumId = intval($forumId);


        $sql = "INSERT INTO " . self::TableName_UserPopedom . "

	    (UserPopedomName,UserPopedomValue,UserId,UserGroupId,SiteId,ChannelId,ForumId) VALUES

        ('ForumAllowPostTopic','1',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumAllowPostReply','1',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumAllowPostAdvancedTopicAccess','1',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumAllowPostMediaTopic','1',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumAllowPostAdvancedTopicTitle','1',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumAllowPostMediaReply','1',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumEditSelfPost','1',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumEditOtherPost','1',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumDeleteSelfPost','1',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumDeleteOtherPost','1',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumForbidOtherEditMyTopic','1',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumForbidOtherEditMyReply','1',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumForbidOtherDeleteMyTopic','1',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumForbidOtherDeleteMyReply','1',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumSetSelfTopicLock','1',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumSetSelfTopicBanReply','1',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumSetOtherTopicLock','1',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumSetOtherTopicBanReply','1',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumPostAddMoney','1',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumPostAddMoneyForSelf','1',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumPostAddCharm','1',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumPostAddCharmForSelf','1',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumPostAddScore','1',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumPostAddScoreForSelf','1',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumPostAddExp','1',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumPostAddExpForSelf','1',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumPostSetBoardTop','1',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumPostSetRegionTop','1',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumPostSetAllTop','1',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumMoveTopic','1',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumCopyTopic','1',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumSetBestTopic','1',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumSetRecTopic','1',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumPostAllowUpload','1',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumAllowViewAttachment','1',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumShowEditInfo','1',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumAllowSearch','1',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumIgnoreLimit','1',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumUserAllowHidden','1',$userId,$userGroupId,$siteId,$channelId,$forumId),

        ('ForumPostTopicMaxContentCount','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumPostReplyMaxContentCount','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumPostTopicMinContentCount','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumPostReplyMinContentCount','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumPostAddMoneyLimit','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumPostAddCharmLimit','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumPostAddScoreLimit','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumPostAddExpLimit','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumPostAllowUploadType','*',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumPostMaxUploadSize','5000',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumPostMaxUploadPerDay','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumPostMaxUploadPerPost','0',$userId,$userGroupId,$siteId,$channelId,$forumId),
        ('ForumUserSignMaxContentCount','0',$userId,$userGroupId,$siteId,$channelId,$forumId)

        ;";
        $result = $this->dbOperator->Execute($sql, null);
        return $result;
    }
} 