<?php
/**
 * 后台管理 频道 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Channel
 * @author zhangchi
 */
class ChannelManageData extends BaseManageData
{
    /**
     * 新增频道
     * @param array $httpPostData $_POST数组
     * @param string $titlePic1 题图1
     * @param string $titlePic2 题图2
     * @param string $titlePic3 题图3
     * @return int 新增的频道id
     */
    public function Create($httpPostData, $titlePic1, $titlePic2, $titlePic3)
    {
        $result = -1;
        $dataProperty = new DataProperty();
        $addFieldName = "";
        $addFieldValue = "";
        $preNumber = "";
        $addFieldNames = array("TitlePic1", "TitlePic2", "TitlePic3");
        $addFieldValues = array($titlePic1, $titlePic2, $titlePic3);
        if (!empty($httpPostData)) {
            $sql = parent::GetInsertSql($httpPostData, self::TableName_Channel, $dataProperty, $addFieldName, $addFieldValue, $preNumber, $addFieldNames, $addFieldValues);
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 新增站点时默认新增首页频道
     * @param int $siteId 站点id
     * @param int $adminUserId 管理员id
     * @param string $channelName 新增的频道名称
     * @return int 新增的频道id
     */
    public function CreateWhenSiteCreate($siteId, $adminUserId, $channelName)
    {
        $result = -1;
        if ($siteId > 0 && $adminUserId > 0 && !empty($channelName)) {
            $dataProperty = new DataProperty();
            $sql = "INSERT INTO " . self::TableName_Channel . " (SiteId,CreateDate,AdminUserId,ChannelName) VALUES (:SiteId,now(),:AdminUserId,:ChannelName);";
            $dataProperty->AddField("SiteId", $siteId);
            $dataProperty->AddField("AdminUserId", $adminUserId);
            $dataProperty->AddField("ChannelName", $channelName);
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 返回所有此站点下的频道列表数据集,供后台左部导航使用
     * @param int $siteId 站点id
     * @param int $adminUserId 管理员id
     * @return array 频道列表数据集
     */
    public function GetListForManageLeft($siteId, $adminUserId)
    {
        $result = null;
        if ($siteId > 0 && $adminUserId > 0) {
            $dataProperty = new DataProperty();
            if ($adminUserId == 1) {
                $sql = "SELECT
                        c.ChannelId,
                        c.ParentId,
                        c.ChannelType,
                        c.ChannelName,
                        c.Rank,
                        (SELECT count(*) FROM " . self::TableName_Channel . " WHERE ParentId=c.ChannelId AND State<100) AS ChildCount
                    FROM " . self::TableName_Channel . " c
                    WHERE
                        c.State<100 AND c.SiteId=:SiteId AND c.Invisible=0
                    ORDER BY c.Sort DESC,c.ChannelId;";
            } else {
                $sql = "SELECT
                            c.ChannelId,
                            c.ParentId,
                            c.ChannelType,
                            c.ChannelName,
                            c.Rank,
                            (SELECT COUNT(*) FROM " . self::TableName_Channel . " WHERE ParentId=c.ChannelId AND State<100) AS ChildCounts
                        FROM " . self::TableName_Channel . " c
                        WHERE
                            c.State<100 AND c.SiteId=:SiteId AND c.Invisible=0
                            AND c.ChannelId in
                                ( SELECT ChannelId FROM " . self::TableName_ManageUserAuthority . " WHERE Explore=1 AND ManageUserId=:ManageUserId
                                  UNION
                                  SELECT ChannelId FROM " . self::TableName_ManageUserAuthority . " WHERE Explore=1 AND ManageUserGroupId IN (SELECT ManageUserGroupId from " . self::TableName_ManageUser . " WHERE ManageUserId=:ManageUserId2)
                                  UNION
                                  SELECT ChannelId FROM " . self::TableName_ManageUserAuthority . " WHERE SiteId IN (SELECT SiteId from " . self::TableName_ManageUserAuthority . " WHERE Explore=1 and ChannelId=0 and ManageUserId=0 and ManageUserGroupId IN (SELECT ManageUserGroupId FROM " . self::TableName_ManageUser . " WHERE ManageUserId=:ManageUserId3))
                                )
                        ORDER BY c.Sort DESC,c.ChannelId;";
                $dataProperty->AddField("ManageUserId", $adminUserId);
                $dataProperty->AddField("ManageUserId2", $adminUserId);
                $dataProperty->AddField("ManageUserId3", $adminUserId);
            }
            $dataProperty->AddField("SiteId", $siteId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }

        return $result;
    }

    /**
     * 取得频道名称
     * @param int $channelId 频道id
     * @param bool $withCache 是否从缓冲中取
     * @return string 频道名称
     */
    public function GetChannelName($channelId, $withCache)
    {
        $result = "";
        if ($channelId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'channel_data';
            $cacheFile = 'channel_get_channel_name.cache_' . $channelId . '';
            $sql = "SELECT ChannelName FROM " . self::TableName_Channel . " WHERE ChannelId=:ChannelId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 取得频道所属站点id
     * @param int $channelId 频道id
     * @param bool $withCache 是否从缓冲中取
     * @return int 站点id
     */
    public function GetSiteId($channelId, $withCache)
    {
        $result = -1;
        if ($channelId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'channel_data';
            $cacheFile = 'channel_get_site_id.cache_' . $channelId . '';
            $sql = "SELECT SiteId FROM " . self::TableName_Channel . " WHERE ChannelId=:ChannelId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 取得频道级别
     * @param int $channelId 频道id
     * @param bool $withCache 是否从缓冲中取
     * @return int 频道级别
     */
    public function GetRank($channelId, $withCache)
    {
        $result = -1;
        if ($channelId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'channel_data';
            $cacheFile = 'channel_get_rank.cache_' . $channelId . '';
            $sql = "SELECT Rank FROM " . self::TableName_Channel . " WHERE ChannelId=:ChannelId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }



    /**
     * 取得频道类型
     * @param int $channelId 频道id
     * @param bool $withCache 是否从缓冲中取
     * @return int 频道类型
     */
    public function GetChannelType($channelId, $withCache)
    {
        $result = -1;
        if ($channelId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'channel_data';
            $cacheFile = 'channel_get_channel_type.cache_' . $channelId . '';
            $sql = "SELECT ChannelType FROM " . self::TableName_Channel . " WHERE ChannelId=:ChannelId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 检查是否设置了重复的发布路径
     * @param int $siteId 站点id
     * @param string $publishPath 要检查的发布路径
     * @param int $channelId 要检查的频道编号，修改时用，新增时，此参数为0
     * @return bool 检查结果，存在重复为是，否则为否
     */
    public function CheckRepeatPublishPath($siteId, $publishPath , $channelId) {
        $dataProperty = new DataProperty();
        if ($channelId > 0) {
            $sql = "SELECT count(*) FROM " . self::TableName_Channel . " WHERE SiteId=:SiteId AND PublishPath=:PublishPath AND ChannelId<>:ChannelId;";
            $dataProperty->AddField("ChannelId", $channelId);
        } else {
            $sql = "SELECT count(*) FROM " . self::TableName_Channel . " WHERE SiteId=:SiteId AND PublishPath=:PublishPath;";
        }
        $dataProperty->AddField("PublishPath", $publishPath);
        $dataProperty->AddField("SiteId", $siteId);
        $result = $this->dbOperator->GetInt($sql, $dataProperty);
        if($result>0){
            return TRUE;
        }else{
            return FALSE;
        }
    }


}

?>
