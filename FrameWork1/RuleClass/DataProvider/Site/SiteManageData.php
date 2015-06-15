<?php

/**
 * 后台管理 站点 后台数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Site
 * @author zhangchi
 */
class SiteManageData extends BaseManageData {


    /**
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_Site){
        return parent::GetFields(self::TableName_Site);
    }

    /**
     * 新增站点
     * @param array $httpPostData $_POST数组
     * @return int 新增的站点id
     */
    public function Create($httpPostData)
    {
        $result = -1;
        $dataProperty = new DataProperty();
        $addFieldName = "";
        $addFieldValue = "";
        $preNumber = "";
        $addFieldNames = array();
        $addFieldNames[] = "ManageUserId";
        $addFieldNames[] = "CreateDate";
        $addFieldValues = array();
        $addFieldValues[] = Control::GetManageUserId();
        $addFieldValues[] = date("Y-m-d H:i:s", time());
        if (!empty($httpPostData)) {
            $sql = parent::GetInsertSql(
                $httpPostData,
                self::TableName_Site,
                $dataProperty,
                $addFieldName,
                $addFieldValue,
                $preNumber,
                $addFieldNames,
                $addFieldValues
            );
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 修改站点
     * @param array $httpPostData $_POST数组
     * @param int $siteId 站点id
     * @return int 返回影响的行数
     */
    public function Modify($httpPostData, $siteId)
    {
        $result = -1;
        $dataProperty = new DataProperty();
        $addFieldName = "";
        $addFieldValue = "";
        $preNumber = "";
        $addFieldNames = array();
        $addFieldValues = array();
        if (!empty($httpPostData)) {
            $sql = parent::GetUpdateSql(
                $httpPostData,
                self::TableName_Site,
                self::TableId_Site,
                $siteId,
                $dataProperty,
                $addFieldName,
                $addFieldValue,
                $preNumber,
                $addFieldNames,
                $addFieldValues
            );
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 修改状态
     * @param int $siteId 站点id
     * @param int $state 状态
     * @return int 操作结果
     */
    public function ModifyState($siteId, $state)
    {
        $result = 0;
        if ($siteId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_Site . " SET `State`=:State WHERE ".self::TableId_Site."=:".self::TableId_Site.";";
            $dataProperty->AddField(self::TableId_Site, $siteId);
            $dataProperty->AddField("State", $state);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 修改站点题图的上传文件id
     * @param int $siteId 站点id
     * @param int $titlePicUploadFileId 题图上传文件id
     * @return int 操作结果
     */
    public function ModifyTitlePic(
        $siteId,
        $titlePicUploadFileId
    )
    {
        $result = -1;
        if($siteId>0){
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_Site . " SET
                    TitlePicUploadFileId = :TitlePicUploadFileId
                    WHERE SiteId = :SiteId
                    ;";
            $dataProperty->AddField("TitlePicUploadFileId", $titlePicUploadFileId);
            $dataProperty->AddField("SiteId", $siteId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }

    /**
     * 根据后台管理员id返回此管理员可以管理的站点列表数据集，下拉站点菜单使用
     * @param int $manageUserId 后台管理员id
     * @return array 站点列表数据集
     */
    public function GetListForSelect($manageUserId) {
        $dataProperty = new DataProperty();
        if ($manageUserId == 1) {
            $sql = "SELECT * FROM ".self::TableName_Site." WHERE State<100 ORDER BY Sort DESC,convert(SiteName USING gbk);";
        } else {

            //自己创建的站点和自己后台帐号分组有权限的站点

            $sql = "SELECT * FROM ".self::TableName_Site." WHERE State<100 AND
                SiteId IN
                ( SELECT SiteId FROM ".self::TableName_Site." WHERE ManageUserId=:ManageUserId1
                  UNION
                  SELECT SiteId FROM ".self::TableName_ManageUserAuthority."
                    WHERE ManageUserGroupId IN
                        (SELECT ManageUserGroupId FROM ".self::TableName_ManageUser." WHERE ManageUserId=:ManageUserId2)
                  UNION
                  SELECT SiteId FROM ".self::TableName_ManageUserAuthority."
                    WHERE ManageUserId = :ManageUserId3 AND ManageUserGroupId=0
                )
                 ORDER BY Sort DESC,convert(SiteName USING gbk);";


            $dataProperty->AddField("ManageUserId1", $manageUserId);
            $dataProperty->AddField("ManageUserId2", $manageUserId);
            $dataProperty->AddField("ManageUserId3", $manageUserId);

        }
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        return $result;
    }

    /**
     * 根据后台管理员id返回此管理员可以管理的站点列表数据集
     * @param int $pageBegin 分页起始位置
     * @param int $pageSize 分页大小
     * @param int $allCount 记录总数（输出参数）
     * @param string $searchKey 查询关键字
     * @param int $searchType 查询字段类型
     * @param int $manageUserId 后台管理员id
     * @return array 站点列表数据集
     */
    public function GetList($pageBegin, $pageSize, &$allCount, $searchKey, $searchType, $manageUserId) {
        $dataProperty = new DataProperty();
        $searchSql = "";
        if ($manageUserId == 1) {
            //查询
            if (strlen($searchKey) > 0 && $searchKey != "undefined") {
                if ($searchType == 0) { //站点名称
                    $searchSql = " AND (s.SiteName like :SearchKey)";
                    $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
                } else { //站点名称
                    $searchSql = " AND (s.SiteName like :SearchKey)";
                    $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
                }
            }
            $sql = "SELECT s.*,mu.ManageUserName FROM ".self::TableName_Site." s,".self::TableName_ManageUser." mu
                        WHERE s.ManageUserId=mu.ManageUserId $searchSql
                        ORDER BY s.Sort DESC,convert(s.SiteName USING gbk)
                        LIMIT " . $pageBegin . "," . $pageSize . ";";
            $sqlCount = "SELECT Count(*) FROM ".self::TableName_Site."s,".self::TableName_ManageUser." mu
                        WHERE s.ManageUserId=mu.ManageUserId $searchSql;";
        } else {

            //自己创建的站点和自己后台帐号分组有权限的站点

            //查询
            if (strlen($searchKey) > 0 && $searchKey != "undefined") {
                if ($searchType == 0) { //站点名称
                    $searchSql = " AND (s.SiteName like :SearchKey)";
                    $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
                } else { //站点名称
                    $searchSql = " AND (s.SiteName like :SearchKey)";
                    $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
                }
            }

            $sql = "SELECT s.*,mu.ManageUserName FROM ".self::TableName_Site." s,".self::TableName_ManageUser." mu

                    WHERE
                        s.ManageUserId=mu.ManageUserId
                        AND
                        s.SiteId IN
                        (   SELECT SiteId FROM ".self::TableName_Site." WHERE ManageUserId=:ManageUserId1
                            UNION
                            SELECT SiteId FROM ".self::TableName_ManageUserAuthority."
                                WHERE ManageUserGroupId IN
                                (SELECT ManageUserGroupId FROM ".self::TableName_ManageUser." WHERE ManageUserId=:ManageUserId2)
                        ) $searchSql
                    ORDER BY s.Sort DESC,convert(s.SiteName USING gbk)
                    LIMIT " . $pageBegin . "," . $pageSize . ";";
            $sqlCount = "SELECT Count(*) FROM ".self::TableName_Site." WHERE
                SiteId IN
                ( SELECT SiteId FROM ".self::TableName_Site." WHERE ManageUserId=:ManageUserId1
                  UNION
                  SELECT SiteId FROM ".self::TableName_ManageUserAuthority."
                    WHERE ManageUserGroupId IN
                        (SELECT ManageUserGroupId FROM ".self::TableName_ManageUser." WHERE ManageUserId=:ManageUserId2)
                 ) $searchSql
                 ;";


            $dataProperty->AddField("ManageUserId1", $manageUserId);
            $dataProperty->AddField("ManageUserId2", $manageUserId);
        }
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        $allCount = $this->dbOperator->GetInt($sqlCount, $dataProperty);
        return $result;
    }

    /**
     * 取得站点状态
     * @param int $siteId 站点id
     * @param bool $withCache 是否从缓冲中取
     * @return string 站点状态
     */
    public function GetState($siteId, $withCache) {
        $result = "";
        if ($siteId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'site_data';
            $cacheFile = 'site_get_state.cache_' . $siteId . '';
            $sql = "SELECT State FROM " . self::TableName_Site . " WHERE SiteId=:SiteId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 取得站点网址
     * @param int $siteId 站点id
     * @param bool $withCache 是否从缓冲中取
     * @return string 站点网址
     */
    public function GetSiteUrl($siteId, $withCache) {
        $result = "";
        if ($siteId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'site_data';
            $cacheFile = 'site_get_site_url.cache_' . $siteId . '';
            $sql = "SELECT SiteUrl FROM " . self::TableName_Site . " WHERE SiteId=:SiteId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 取得站点名
     * @param int $siteId 站点id
     * @param bool $withCache 是否从缓冲中取
     * @return string 站点网址
     */
    public function GetSiteName($siteId, $withCache) {
        $result = "";
        if ($siteId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'site_data';
            $cacheFile = 'site_get_site_name.cache_' . $siteId . '';
            $sql = "SELECT SiteName FROM " . self::TableName_Site . " WHERE SiteId=:SiteId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }


    /**
     * 返回一行数据
     * @param int $siteId 站点id
     * @return array|null 取得对应数组
     */
    public function GetOne($siteId){
        $result = null;
        if($siteId>0){
            $sql = "SELECT * FROM
                        " . self::TableName_Site . "
                    WHERE SiteId=:SiteId
                    ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 修改站点题图的上传文件id
     * @param int $siteId 站点id
     * @param int $titlePicUploadFileId 题图1上传文件id
     * @return int 操作结果
     */
    public function ModifyTitlePicUploadFileId($siteId, $titlePicUploadFileId)
    {
        $result = -1;
        if($siteId>0){
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_Site . " SET
                    TitlePicUploadFileId = :TitlePicUploadFileId

                    WHERE SiteId = :SiteId
                    ;";
            $dataProperty->AddField("TitlePicUploadFileId", $titlePicUploadFileId);
            $dataProperty->AddField("SiteId", $siteId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }

    /**
     * 取得题图的上传文件id
     * @param int $siteId 站点id
     * @param bool $withCache 是否从缓冲中取
     * @return int 题图的上传文件id
     */
    public function GetTitlePicUploadFileId($siteId, $withCache)
    {
        $result = -1;
        if ($siteId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'site_data';
            $cacheFile = 'site_get_title_pic_upload_file_id.cache_' . $siteId . '';
            $sql = "SELECT TitlePicUploadFileId FROM " . self::TableName_Site . "

                    WHERE SiteId = :SiteId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);
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

}

?>
