<?php
/**
 * 前台 会员权限 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author zhangchi
 */
class UserPopedomPublicData extends BasePublicData {

    private $arrBoolValue = array(
        "ForumAllowPostTopic",
        "ForumAllowPostReply",
        "ForumAllowPostAdvancedTopicAccess",
        "ForumAllowPostAdvancedTopicTitle",
        "ForumAllowPostMediaTopic",
        "ForumAllowPostMediaReply",
        "ForumEditSelfPost",
        "ForumEditOtherPost",
        "ForumDeleteSelfPost",
        "ForumDeleteOtherPost",
        "ForumForbidOtherEditMyTopic",
        "ForumForbidOtherEditMyReply",
        "ForumForbidOtherDeleteMyTopic",
        "ForumForbidOtherDeleteMyReply",
        "ForumSetSelfTopicLock",
        "ForumSetSelfTopicBanReply",
        "ForumSetOtherTopicLock",
        "ForumSetOtherTopicBanReply",
        "ForumPostAddMoney",
        "ForumPostAddMoneyForSelf",
        "ForumPostAddCharm",
        "ForumPostAddCharmForSelf",
        "ForumPostAddScore",
        "ForumPostAddScoreForSelf",
        "ForumPostAddExp",
        "ForumPostAddExpForSelf",
        "ForumPostSetBoardTop",
        "ForumPostSetRegionTop",
        "ForumPostSetAllTop",
        "ForumMoveTopic",
        "ForumCopyTopic",
        "ForumSetBestTopic",
        "ForumSetRecTopic",
        "ForumPostAllowUpload",
        "ForumAllowViewAttachment",
        "ForumShowEditInfo",
        "ForumAllowSearch",
        "ForumIgnoreLimit",
        "ForumUserAllowHidden",
        "UserCanPostActivity"
    );
    /**
     * 允许发表主题
     * @var bool
     */
    private $ForumAllowPostTopic; //允许发表主题
    /**
     * 允许发表回复
     * @var bool
     */
    private $ForumAllowPostReply; //允许发表回复
    /**
     * 发表主题允许的最大字符数
     * @var int
     */
    private $ForumPostTopicMaxContentCount; //发表主题允许的最大字符数
    /**
     * 发表回复允许的最大字符数
     * @var int
     */
    private $ForumPostReplyMaxContentCount; //发表回复允许的最大字符数
    /**
     * 发表主题允许的最少字符数
     * @var int
     */
    private $ForumPostTopicMinContentCount; //发表主题允许的最少字符数
    /**
     * 发表回复允许的最少字符数
     * @var int
     */
    private $ForumPostReplyMinContentCount; //发表回复允许的最少字符数
    /**
     * 允许发布高级主题(如回复可见,积分可见等形式的主题)
     * @var bool
     */
    private $ForumAllowPostAdvancedTopicAccess; //允许发布高级主题(如回复可见,积分可见等形式的主题)
    /**
     * 允许发布高级主题标题(如多种颜色,加粗的主题)
     * @var bool
     */
    private $ForumAllowPostAdvancedTopicTitle; //允许发布高级主题标题(如多种颜色,加粗的主题)
    /**
     * 允许发布多媒体主题(如视频、Flash、框架页面等)
     * @var bool
     */
    private $ForumAllowPostMediaTopic; //允许发布多媒体主题(如视频、Flash、框架页面等)
    /**
     * 允许发布多媒体回复(如视频、Flash、框架页面等)
     * @var bool
     */
    private $ForumAllowPostMediaReply; //允许发布多媒体回复(如视频、Flash、框架页面等)
    /**
     * 编辑自己的帖子
     * @var bool
     */
    private $ForumEditSelfPost; //编辑自己的帖子
    /**
     * 编辑其他人的帖子
     * @var bool
     */
    private $ForumEditOtherPost; //编辑其他人的帖子
    /**
     * 删除自己的帖子
     * @var bool
     */
    private $ForumDeleteSelfPost; //删除自己的帖子
    private $ForumDeleteOtherPost; //删除其他人的帖子
    private $ForumForbidOtherEditMyTopic; //禁止其他人编辑自己的主题(一般只有管理员有此权限)
    private $ForumForbidOtherEditMyReply; //禁止其他人编辑自己的回复(一般只有管理员有此权限)
    private $ForumForbidOtherDeleteMyTopic; //禁止其他人删除自己的主题(一般只有管理员有此权限)
    private $ForumForbidOtherDeleteMyReply; //禁止其他人删除自己的回复(一般只有管理员有此权限)
    private $ForumSetSelfTopicLock; //将自己的主题设为锁定状态
    private $ForumSetSelfTopicBanReply; //将自己的主题设为禁止回复状态
    private $ForumSetOtherTopicLock; //将其他人的主题设为锁定状态
    private $ForumSetOtherTopicBanReply; //将其他人的主题设为禁止回复状态
    private $ForumPostAddMoney; //在帖子中给帖主增加{ForumMoneyName}
    private $ForumPostAddMoneyForSelf; //是否允许给自己增加{ForumMoneyName}
    private $ForumPostAddMoneyLimit; //每日限额{ForumMoneyName}
    private $ForumPostAddCharm; //在帖子中给帖主增加{ForumCharmName}
    private $ForumPostAddCharmForSelf; //是否允许给自己增加{ForumCharmName}
    private $ForumPostAddCharmLimit; //每日限额{ForumCharmName}
    private $ForumPostAddScore; //在帖子中给帖主增加{ForumScoreName}
    private $ForumPostAddScoreForSelf; //是否允许给自己增加{ForumScoreName}
    private $ForumPostAddScoreLimit; //每日限额{ForumScoreName}
    private $ForumPostAddExp; //在帖子中给帖主增加{ForumExpName}
    private $ForumPostAddExpForSelf; //是否允许给自己增加{ForumExpName}
    private $ForumPostAddExpLimit; //每日限额{ForumExpName}
    private $ForumPostSetBoardTop; //将主题设为版块置顶
    private $ForumPostSetRegionTop; //将主题设为分区版块置顶
    private $ForumPostSetAllTop; //将主题设为全部版块置顶
    private $ForumMoveTopic; //移动主题(可以移动自己或他人的主题，一般只有版主以上身份有此权限)
    private $ForumCopyTopic; //复制主题(可以复制自己或他人的主题，一般只有版主以上身份有此权限)
    private $ForumSetBestTopic; //将主题设为精华主题
    private $ForumSetRecTopic; //将主题设为推荐主题
    private $ForumPostAllowUpload; //允许在帖子中上传文件
    private $ForumPostAllowUploadType; //帖子中允许上传的文件类型
    private $ForumPostMaxUploadSize; //帖子中允许上传的文件大小（KB）
    private $ForumPostMaxUploadPerDay; //每日最大可上传的文件数
    private $ForumPostMaxUploadPerPost; //每个帖子最大可上传的文件数
    private $ForumAllowViewAttachment; //允许查看帖子中上传的文件
    private $ForumShowEditInfo; //编辑帖子时是否显示编辑信息
    private $ForumAllowSearch; //允许使用论坛搜索功能
    private $ForumIgnoreLimit; //忽视帖子中的所有限制设定（一般只有管理员有此权限）
    private $ForumUserSignMaxContentCount; //会员签名的最大字符数
    private $ForumUserAllowHidden; //允许隐身登录
    private $UserAlbumMaxUploadPerOnce; //会员相册单次最大上传文件数

    private $UserCanPostActivity; //是否允许发布活动
    private $UserSetRecCountLimit;  //给会员相册打推荐分的最大值
    private $UserSetRecCountDayMax; //给会员相册打推荐分的每日上限值


    /**
     * get 配置值
     * @param string $userPopedomName
     * @return boolean
     */
    public function __get($userPopedomName) {
        if ($this->forumId > 0 && $this->userId > 0) {
            $result = self::GetValueByForumIdAndUserId($this->forumId, $this->userId, $userPopedomName);
            if (in_array($userPopedomName, $this->arrBoolValue)) {
                if ($result == 'on') {
                    $result = TRUE;
                } else {
                    $result = FALSE;
                }
            }
            return $result;
        } else if ($this->siteId > 0 && $this->userId > 0) {
            $result = self::GetValueBySiteIdAndUserId($this->siteId, $this->userId, $userPopedomName);
            if (in_array($userPopedomName, $this->arrBoolValue)) {
                if ($result == 'on') {
                    $result = TRUE;
                } else {
                    $result = FALSE;
                }
            }
            return $result;
        }
    }

    /**
     * 根据论坛ID和会员ID设置权限值
     * @param int $forumId
     * @param int $userId
     * @param string $userPopedomName
     * @param string $userPopedomValue
     */
    public function SetValueByForumIdAndUserId($forumId, $userId, $userPopedomName, $userPopedomValue) {

        $sql = "SELECT count(*) FROM " . self::tableName . " WHERE ForumId=:ForumId AND UserId=:UserId AND UserPopedomName=:UserPopedomName";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("ForumId", $forumId);
        $dataProperty->AddField("UserId", $userId);
        $dataProperty->AddField("UserPopedomName", $userPopedomName);
        $dbOperator = DBOperator::getInstance();
        $hasCount = $dbOperator->ReturnInt($sql, $dataProperty);

        if ($hasCount > 0) { //已存在相关配置记录
            $sql = "UPDATE " . self::tableName . " SET UserPopedomValue=:UserPopedomValue WHERE ForumId=:ForumId AND UserId=:UserId AND UserPopedomName=:UserPopedomName";
            $dataProperty->AddField("UserPopedomValue", $userPopedomValue);
            $dbOperator->Execute($sql, $dataProperty);
        } else {
            $sql = "INSERT INTO " . self::tableName . " (ForumId,UserId,UserPopedomName,UserPopedomValue) VALUES (:ForumId,:UserId,:UserPopedomName,:UserPopedomValue)";
            $dataProperty->AddField("UserPopedomValue", $userPopedomValue);
            $dbOperator->Execute($sql, $dataProperty);
        }

        $cachedir = 'data' . DIRECTORY_SEPARATOR . 'userdata' . DIRECTORY_SEPARATOR . $userId;
        $cachefile = 'userpopedom.cache_' . $forumId . '_' . strtolower($userPopedomName);
        DataCache::Set($cachefile, $cachedir, $userPopedomValue);
    }

    /**
     * 根据站点ID和会员组ID设置权限值
     * @param type $SiteId
     * @param type $UserGroupId
     * @param type $UserPopedomName
     * @param type $UserPopedomValue
     */
    public function SetValueBySiteIdAndUserGroupId($SiteId, $UserGroupId, $UserPopedomName, $UserPopedomValue) {

        $sql = "SELECT count(*) FROM " . self::tableName . " WHERE SiteId=:SiteId AND UserGroupId=:UserGroupId AND UserPopedomName=:UserPopedomName";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("SiteId", $SiteId);
        $dataProperty->AddField("UserGroupId", $UserGroupId);
        $dataProperty->AddField("UserPopedomName", $UserPopedomName);
        $dbOperator = DBOperator::getInstance();
        $hasCount = $dbOperator->ReturnInt($sql, $dataProperty);

        if ($hasCount > 0) { //已存在相关配置记录
            $sql = "UPDATE " . self::tableName . " SET UserPopedomValue=:UserPopedomValue WHERE SiteId=:SiteId AND UserGroupId=:UserGroupId AND UserPopedomName=:UserPopedomName";
            $dataProperty->AddField("UserPopedomValue", $UserPopedomValue);
            $dbOperator->Execute($sql, $dataProperty);
        } else {
            $sql = "INSERT INTO " . self::tableName . " (SiteId,UserGroupId,UserPopedomName,UserPopedomValue) VALUES (:SiteId,:UserGroupId,:UserPopedomName,:UserPopedomValue)";
            $dataProperty->AddField("UserPopedomValue", $UserPopedomValue);
            $dbOperator->Execute($sql, $dataProperty);
        }

        $cachedir = 'data' . DIRECTORY_SEPARATOR . 'userdata' . DIRECTORY_SEPARATOR; //. $UserId;
        DataCache::RemoveDir($cachedir);
    }

    /**
     * 根据论坛ID和会员ID取得配置值（带多级查询）（已缓冲）
     * @param type $forumId
     * @param type $userId
     * @param type $userPopedomName
     * @param type $defaultValue
     * @return type
     */
    private function GetValueByForumIdAndUserId($forumId, $userId, $userPopedomName, $defaultValue = '') {
        $cachedir = 'data' . DIRECTORY_SEPARATOR . 'userdata' . DIRECTORY_SEPARATOR . $userId;
        $cachefile = 'userpopedom.cache_forum_' . $forumId . '_' . strtolower($userPopedomName);
        $result = "";

        if (strlen(DataCache::Get($cachedir . DIRECTORY_SEPARATOR . $cachefile)) <= 0) {
            $sql = "SELECT UserPopedomValue FROM " . self::tableName . " WHERE ForumId=:ForumId AND UserId=:UserId AND UserPopedomName=:UserPopedomName";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ForumId", $forumId);
            $dataProperty->AddField("UserId", $userId);
            $dataProperty->AddField("UserPopedomName", $userPopedomName);
            $dbOperator = DBOperator::getInstance();
            $result = $dbOperator->ReturnString($sql, $dataProperty);
            if ($result == FALSE) {
                //没有找到权限值
                //找版块会员组权限
                $forumData = new ForumData();
                $siteId = $forumData->GetSiteId($forumId);

                $userRoleData = new UserRoleData();
                $userGroupId = $userRoleData->GetUserGroupID($userId, $siteId);

                $sql = "SELECT UserPopedomValue FROM " . self::tableName . " WHERE ForumId=:ForumId AND UserGroupId=:UserGroupId AND UserPopedomName=:UserPopedomName";
                $dataProperty2 = new DataProperty();
                $dataProperty2->AddField("ForumId", $forumId);
                $dataProperty2->AddField("UserGroupId", $userGroupId);
                $dataProperty2->AddField("UserPopedomName", $userPopedomName);
                $result = $dbOperator->ReturnString($sql, $dataProperty2);

                if ($result == FALSE) {
                    //没有找到权限值
                    //找站点会员权限

                    $sql = "SELECT UserPopedomValue FROM " . self::tableName . " WHERE SiteId=:SiteId AND UserId=:UserId AND UserPopedomName=:UserPopedomName";
                    $dataProperty3 = new DataProperty();
                    $dataProperty3->AddField("SiteId", $siteId);
                    $dataProperty3->AddField("UserId", $userId);
                    $dataProperty3->AddField("UserPopedomName", $userPopedomName);
                    $result = $dbOperator->ReturnString($sql, $dataProperty3);

                    if ($result == FALSE) {
                        //没有找到权限值
                        //找站点会员组权限
                        $sql = "SELECT UserPopedomValue FROM " . self::tableName . " WHERE SiteId=:SiteId AND UserGroupId=:UserGroupId AND UserPopedomName=:UserPopedomName";
                        $dataProperty4 = new DataProperty();
                        $dataProperty4->AddField("SiteId", $siteId);
                        $dataProperty4->AddField("UserGroupId", $userGroupId);
                        $dataProperty4->AddField("UserPopedomName", $userPopedomName);
                        $result = $dbOperator->ReturnString($sql, $dataProperty4);

                        if ($result == FALSE) {
                            //没有找到权限值
                            //读取默认权限（游客权限）
                            $result = $defaultValue;
                        }
                    }
                }

                //self::SetValueByForumIdAndUserId($ForumId, $UserId, $UserPopedomName, $result);
            } else {
                DataCache::Set($cachefile, $cachedir, $result);
            }
        } else {
            $result = DataCache::Get($cachedir . DIRECTORY_SEPARATOR . $cachefile);
        }

        return $result;
    }


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
        $result = -1;
        if ($siteId > 0 && $userGroupId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'user_popedom_data' . DIRECTORY_SEPARATOR . 'user_group_' . $userGroupId;
            $cacheFile = 'user_popedom_get_value_by_site_id_and_user_group_id.cache_site_id'
                . $siteId
                . '_'
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