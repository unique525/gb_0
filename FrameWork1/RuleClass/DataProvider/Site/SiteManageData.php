<?php

/**
 * 后台管理 站点 后台数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Site
 * @author zhangchi
 */
class SiteManageData extends BaseManageData {


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
     * 根据后台管理员id返回此管理员可以管理的站点列表数据集
     * @param int $manageUserId 后台管理员id
     * @return array 站点列表数据集
     */
    public function GetList($manageUserId) {
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
                 )
                 ORDER BY Sort DESC,convert(SiteName USING gbk);";


            $dataProperty->AddField("ManageUserId1", $manageUserId);
            $dataProperty->AddField("ManageUserId2", $manageUserId);
        }
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
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

}

?>
