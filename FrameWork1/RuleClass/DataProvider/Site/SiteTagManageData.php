<?php

/**
 * 后台管理 站点 后台数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Site
 * @author zhangchi
 */
class SiteTagManageData extends BaseManageData {

    /**
     * 新增关键词
     * @param array $httpPostData $_POST数组
     * @return int 新增的关键词id
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
     * 获取站点关键词
     * @param int $siteId 站点id
     * @param bool $withCache 是否缓存
     * @return array  数据集
     */
    public function GetListForPulling($siteId,$withCache=false) {
        $result=-1;
        if($siteId>0){
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'site_data';
            $cacheFile = 'site_get_site_tag.cache_' . $siteId . '';
            $sql = "
                SELECT
                SiteTagName
                FROM
                " . self::TableName_SiteTag . "
                WHERE SiteId=:SiteId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);
            $result = $this->GetInfoOfArrayList($sql,$dataProperty,$withCache,$cacheDir,$cacheFile);
        }

        return $result;
    }


    /**
     * 修改状态
     * @param int $siteTagId 站点siteTagId
     * @param int $state 状态
     * @return int 操作结果
     */
    public function ModifyState($siteTagId, $state)
    {
        $result = 0;
        if ($siteTagId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_SiteTag . " SET `State`=:State WHERE SiteTagId=:SiteTagId;";
            $dataProperty->AddField("SiteTagId", $siteTagId);
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
     * 获取站点关键词分页列表
     * @param int $siteId 站点id
     * @param int $pageBegin 起始页码
     * @param int $pageSize 每页大小
     * @param string $searchKey 搜索关键字
     * @return array 数据集
     */
    public function GetListPager($siteId, $pageBegin, $pageSize, $searchKey,&$allCount) {
        $result=-1;
        if($siteId>0){
            $searchSql = "";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);

            if (strlen($searchKey) > 0 && $searchKey != "undefined") {
                $searchSql.=" AND SiteTagName LIKE :SearchKey ";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            }

            $sql = "
                SELECT
                *
                FROM
                " . self::TableName_SiteTag . "
                WHERE SiteId=:SiteId " . $searchSql . " LIMIT " . $pageBegin . "," . $pageSize . " ;";

            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
            $sqlCount = "SELECT count(*) FROM " . self::TableName_SiteTag . " WHERE SiteId=:SiteId " . $searchSql . " ;";
            $allCount = $this->dbOperator->GetInt($sqlCount, $dataProperty);
        }

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

}

?>
