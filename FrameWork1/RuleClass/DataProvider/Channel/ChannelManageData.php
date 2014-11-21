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
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_Channel)
    {
        return parent::GetFields(self::TableName_Channel);
    }


    /**
     * 新增频道
     * @param array $httpPostData $_POST数组
     * @return int 新增的频道id
     */
    public function Create($httpPostData)
    {
        $result = -1;
        $dataProperty = new DataProperty();
        $addFieldName = "";
        $addFieldValue = "";
        $preNumber = "";
        $addFieldNames = array();
        $addFieldValues = array();
        if (!empty($httpPostData)) {
            $sql = parent::GetInsertSql($httpPostData, self::TableName_Channel, $dataProperty, $addFieldName, $addFieldValue, $preNumber, $addFieldNames, $addFieldValues);
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 修改频道
     * @param array $httpPostData $_POST数组
     * @param int $channelId 频道id
     * @return int 返回影响的行数
     */
    public function Modify($httpPostData, $channelId)
    {
        $result = -1;
        $dataProperty = new DataProperty();
        $addFieldName = "";
        $addFieldValue = "";
        $preNumber = "";
        $addFieldNames = array();
        $addFieldValues = array();

        if (!empty($httpPostData)) {
            $sql = parent::GetUpdateSql($httpPostData, self::TableName_Channel, self::TableId_Channel, $channelId, $dataProperty, $addFieldName, $addFieldValue, $preNumber, $addFieldNames, $addFieldValues);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 修改频道题图的上传文件id
     * @param int $channelId 频道id
     * @param int $titlePic1UploadFileId 题图1上传文件id
     * @param int $titlePic2UploadFileId 题图2上传文件id
     * @param int $titlePic3UploadFileId 题图3上传文件id
     * @return int 操作结果
     */
    public function ModifyTitlePic($channelId, $titlePic1UploadFileId, $titlePic2UploadFileId, $titlePic3UploadFileId)
    {
        $result = -1;
        if ($channelId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_Channel . " SET
                    TitlePic1UploadFileId = :TitlePic1UploadFileId,
                    TitlePic2UploadFileId = :TitlePic2UploadFileId,
                    TitlePic3UploadFileId = :TitlePic3UploadFileId

                    WHERE ChannelId = :ChannelId

                    ;";
            $dataProperty->AddField("TitlePic1UploadFileId", $titlePic1UploadFileId);
            $dataProperty->AddField("TitlePic2UploadFileId", $titlePic2UploadFileId);
            $dataProperty->AddField("TitlePic3UploadFileId", $titlePic3UploadFileId);
            $dataProperty->AddField("ChannelId", $channelId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }

    /**
     * 修改频道题图1的上传文件id
     * @param int $channelId 频道id
     * @param int $titlePic1UploadFileId 题图1上传文件id
     * @return int 操作结果
     */
    public function ModifyTitlePic1UploadFileId($channelId, $titlePic1UploadFileId)
    {
        $result = -1;
        if ($channelId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_Channel . " SET
                    TitlePic1UploadFileId = :TitlePic1UploadFileId

                    WHERE ChannelId = :ChannelId
                    ;";
            $dataProperty->AddField("TitlePic1UploadFileId", $titlePic1UploadFileId);
            $dataProperty->AddField("ChannelId", $channelId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }

    /**
     * 修改频道题图2的上传文件id
     * @param int $channelId 频道id
     * @param int $titlePic2UploadFileId 题图2上传文件id
     * @return int 操作结果
     */
    public function ModifyTitlePic2UploadFileId($channelId, $titlePic2UploadFileId)
    {
        $result = -1;
        if ($channelId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_Channel . " SET
                    TitlePic2UploadFileId = :TitlePic2UploadFileId

                    WHERE ChannelId = :ChannelId
                    ;";
            $dataProperty->AddField("TitlePic2UploadFileId", $titlePic2UploadFileId);
            $dataProperty->AddField("ChannelId", $channelId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }

    /**
     * 修改频道题图3的上传文件id
     * @param int $channelId 频道id
     * @param int $titlePic3UploadFileId 题图3上传文件id
     * @return int 操作结果
     */
    public function ModifyTitlePic3UploadFileId($channelId, $titlePic3UploadFileId)
    {
        $result = -1;
        if ($channelId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_Channel . " SET
                    TitlePic3UploadFileId = :TitlePic3UploadFileId

                    WHERE ChannelId = :ChannelId
                    ;";
            $dataProperty->AddField("TitlePic3UploadFileId", $titlePic3UploadFileId);
            $dataProperty->AddField("ChannelId", $channelId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }

    /**
     * 修改频道子节点id字符串
     * @param int $channelId 频道id
     * @param string $childrenChannelId 子节点id字符串
     * @return int 操作结果
     */
    public function ModifyChildrenChannelId($channelId, $childrenChannelId)
    {
        $result = -1;
        if ($channelId > 0) {

            if(strpos($childrenChannelId,',') == 0){
                $childrenChannelId = substr($childrenChannelId,1);
            }

            $oldChildrenChannelId = self::GetChildrenChannelId($channelId, false);

            $childrenChannelId = $oldChildrenChannelId . ',' . $childrenChannelId;

            $arrChildrenChannelId = explode(',', $childrenChannelId);
            //去重复
            $arrChildrenChannelId = array_unique($arrChildrenChannelId);

            $childrenChannelId = implode(',', $arrChildrenChannelId);

            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_Channel . " SET
                    ChildrenChannelId = :ChildrenChannelId

                    WHERE ChannelId = :ChannelId
                    ;";

            if(strpos($childrenChannelId,',') == 0){
                $childrenChannelId = substr($childrenChannelId,1);
            }

            $dataProperty->AddField("ChildrenChannelId", $childrenChannelId);
            $dataProperty->AddField("ChannelId", $channelId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }

    /**
     * 更新子节点id字符串
     * @param int $channelId 频道id
     */
    public function UpdateParentChildrenChannelId($channelId)
    {

        $rank = self::GetRank($channelId, false);

        $childrenChannelId = "";


        while ($rank > 0) {


            $parentId = self::GetParentChannelId($channelId, false);


            $dataProperty = new DataProperty();

            $sql = "SELECT
                    GROUP_CONCAT(ChannelId) FROM " . self::TableName_Channel . "

                WHERE ParentId = :ParentId
                    AND State<100;

                    ";

            $dataProperty->AddField("ParentId", $parentId);
            $childrenChannelId = $childrenChannelId . ',' . $this->dbOperator->GetString($sql, $dataProperty);


            self::ModifyChildrenChannelId($parentId, $childrenChannelId);

            $channelId = $parentId;


            $rank--;

        }


    }

    /**
     * 新增站点时默认新增首页频道
     * @param int $siteId 站点id
     * @param int $manageUserId 管理员id
     * @param string $channelName 新增的频道名称
     * @return int 新增的频道id
     */
    public function CreateWhenSiteCreate($siteId, $manageUserId, $channelName)
    {
        $result = -1;
        if ($siteId > 0 && $manageUserId > 0 && !empty($channelName)) {
            $dataProperty = new DataProperty();
            $sql = "INSERT INTO " . self::TableName_Channel . " (SiteId,CreateDate,ManageUserId,ChannelName) VALUES (:SiteId,now(),:ManageUserId,:ChannelName);";
            $dataProperty->AddField("SiteId", $siteId);
            $dataProperty->AddField("ManageUserId", $manageUserId);
            $dataProperty->AddField("ChannelName", $channelName);
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 返回一行数据
     * @param int $channelId 频道id
     * @return array|null 取得对应数组
     */
    public function GetOne($channelId)
    {
        $result = null;
        if ($channelId > 0) {
            $sql = "SELECT * FROM " . self::TableName_Channel . " WHERE ChannelId=:ChannelId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
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
     * 取得子节点id字符串
     * @param int $channelId 频道id
     * @param bool $withCache 是否从缓冲中取
     * @return string 子节点id字符串
     */
    public function GetChildrenChannelId($channelId, $withCache)
    {
        $result = "";
        if ($channelId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'channel_data';
            $cacheFile = 'channel_get_children_channel_id.cache_' . $channelId . '';
            $sql = "SELECT ChildrenChannelId FROM " . self::TableName_Channel . " WHERE ChannelId=:ChannelId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }

        return $result;
    }

    /**
     * 取得上级频道id
     * @param int $channelId 频道id
     * @param bool $withCache 是否从缓冲中取
     * @return int 上级频道id
     */
    public function GetParentChannelId($channelId, $withCache)
    {
        $result = -1;
        if ($channelId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'channel_data';
            $cacheFile = 'channel_get_parent_channel_id.cache_' . $channelId . '';
            $sql = "SELECT ParentId FROM " . self::TableName_Channel . " WHERE ChannelId = :ChannelId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 取得题图1的上传文件id
     * @param int $channelId 频道id
     * @param bool $withCache 是否从缓冲中取
     * @return int 题图1的上传文件id
     */
    public function GetTitlePic1UploadFileId($channelId, $withCache)
    {
        $result = -1;
        if ($channelId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'channel_data';
            $cacheFile = 'channel_get_title_pic_1_upload_file_id.cache_' . $channelId . '';
            $sql = "SELECT TitlePic1UploadFileId FROM " . self::TableName_Channel . " WHERE ChannelId = :ChannelId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 取得题图2的上传文件id
     * @param int $channelId 频道id
     * @param bool $withCache 是否从缓冲中取
     * @return int 题图2的上传文件id
     */
    public function GetTitlePic2UploadFileId($channelId, $withCache)
    {
        $result = -1;
        if ($channelId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'channel_data';
            $cacheFile = 'channel_get_title_pic_2_upload_file_id.cache_' . $channelId . '';
            $sql = "SELECT TitlePic2UploadFileId FROM " . self::TableName_Channel . " WHERE ChannelId = :ChannelId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 取得题图3的上传文件id
     * @param int $channelId 频道id
     * @param bool $withCache 是否从缓冲中取
     * @return int 题图3的上传文件id
     */
    public function GetTitlePic3UploadFileId($channelId, $withCache)
    {
        $result = -1;
        if ($channelId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'channel_data';
            $cacheFile = 'channel_get_title_pic_3_upload_file_id.cache_' . $channelId . '';
            $sql = "SELECT TitlePic3UploadFileId FROM " . self::TableName_Channel . " WHERE ChannelId = :ChannelId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 取得上级频道名称
     * @param int $channelId 频道id
     * @param bool $withCache 是否从缓冲中取
     * @return string 上级频道名称
     */
    public function GetParentChannelName($channelId, $withCache)
    {
        $result = "";
        if ($channelId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'channel_data';
            $cacheFile = 'channel_get_parent_channel_name.cache_' . $channelId . '';
            $sql = "SELECT ChannelName FROM " . self::TableName_Channel . " WHERE ChannelId = (SELECT ParentId FROM " . self::TableName_Channel . " WHERE ChannelId = :ChannelId);";
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
     * 自动发布
     */
    const PUBLISH_TYPE_AUTO = 1;

    /**
     * 取得频道发布方式
     * @param int $channelId 频道id
     * @param bool $withCache 是否从缓冲中取
     * @return int 站点id
     */
    public function GetPublishType($channelId, $withCache)
    {
        $result = -1;
        if ($channelId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'channel_data';
            $cacheFile = 'channel_get_publish_type.cache_' . $channelId . '';
            $sql = "SELECT PublishType FROM " . self::TableName_Channel . " WHERE ChannelId=:ChannelId;";
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
    public function CheckRepeatPublishPath($siteId, $publishPath, $channelId)
    {
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
        if ($result > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }



    //////////////////////////////////////////////////////////////////////////////
    //////////////////////////////Get List////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////

    /**
     * 返回所有此站点下的频道列表数据集,供后台左部导航使用
     * @param int $siteId 站点id
     * @param int $manageUserId 管理员id
     * @return array|null 频道列表数据集
     */
    public function GetListForManageLeft($siteId, $manageUserId)
    {
        $result = null;
        if ($siteId > 0 && $manageUserId > 0) {
            $dataProperty = new DataProperty();
            if ($manageUserId == 1) {
                $sql = "SELECT
                        c.ChannelId,
                        c.ParentId,
                        c.ChannelType,
                        c.ChannelName,
                        c.Rank,
                        (SELECT count(*) FROM " . self::TableName_Channel . " WHERE ParentId=c.ChannelId AND State<100 AND Invisible=0) AS ChildCount
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
                            (SELECT COUNT(*) FROM " . self::TableName_Channel . " WHERE ParentId=c.ChannelId AND State<100 AND Invisible=0) AS ChildCount
                        FROM " . self::TableName_Channel . " c
                        WHERE
                            c.State<100 AND c.SiteId=:SiteId AND c.Invisible=0
                            AND c.ChannelId in
                                ( SELECT ChannelId FROM " . self::TableName_ManageUserAuthority . " WHERE Explore=1 AND ManageUserId=:ManageUserId
                                  UNION
                                  SELECT ChannelId FROM " . self::TableName_ManageUserAuthority . " WHERE Explore=1 AND ManageUserGroupId IN (SELECT ManageUserGroupId FROM " . self::TableName_ManageUser . " WHERE ManageUserId=:ManageUserId2)
                                  UNION
                                  SELECT ChannelId FROM " . self::TableName_ManageUserAuthority . " WHERE SiteId IN (SELECT SiteId from " . self::TableName_ManageUserAuthority . " WHERE Explore=1 AND ChannelId=0 AND ManageUserId=0 AND ManageUserGroupId IN (SELECT ManageUserGroupId FROM " . self::TableName_ManageUser . " WHERE ManageUserId=:ManageUserId3))
                                )
                        ORDER BY c.Sort DESC,c.ChannelId;";
                $dataProperty->AddField("ManageUserId", $manageUserId);
                $dataProperty->AddField("ManageUserId2", $manageUserId);
                $dataProperty->AddField("ManageUserId3", $manageUserId);
            }
            $dataProperty->AddField("SiteId", $siteId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }

        return $result;
    }


    /**
     * 根据父id获取列表数据集
     * @param int $channelId 频道id
     * @param int $topCount 显示的条数
     * @param string $order 排序方式
     * @return array|null 列表数据集
     */
    public function GetListByParentId($channelId, $topCount, $order)
    {
        $result = null;
        if ($channelId > 0) {
            switch ($order) {
                default:
                    $order = "ORDER BY Sort DESC,Createdate DESC," . self::TableId_Channel . " DESC";
                    break;
            }
            $sql = "SELECT
                        *
                        FROM " . self::TableName_Channel . "
                        WHERE State<100 AND IsCircle=1 AND ParentId=:ChannelId
                        $order
                        LIMIT $topCount";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }


}

?>
