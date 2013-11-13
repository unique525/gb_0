<?php

/**
 * 后台站点数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Site
 * @author zhangchi
 */
class SiteManageData extends BaseManageData {

    /**
     * 根据后台管理员id返回此管理员可以管理的站点列表数据集
     * @param int $adminUserId 后台管理员id
     * @return array 站点列表数据集
     */
    public function GetList($adminUserId) {
        if ($adminUserId == 1) {
            $sql = "SELECT * FROM ".self::TableName_Site." WHERE State<10 ORDER BY Sort DESC,convert(SiteName USING gbk);";
        } else {
            $sql = "SELECT * FROM ".self::TableName_Site." WHERE State<10 AND 
                SiteId IN 
                ( SELECT SiteId FROM ".self::TableName_AdminPopedom." WHERE AdminUserId=:adminuserid1 
                  UNION 
                  SELECT SiteId FROM ".self::TableName_AdminPopedom." WHERE AdminUserGroupId IN (SELECT adminusergroupid FROM ".self::TableName_AdminUser." WHERE adminuserid=:adminuserid2)
                 ) 
                 ORDER BY Sort DESC,convert(SiteName USING gbk);";
        }
        $dataProperty = new DataProperty();
        $dataProperty->AddField("adminuserid1", $adminUserId);
        $dataProperty->AddField("adminuserid2", $adminUserId);
        $result = $this->dbOperator->ReturnArray($sql, $dataProperty);
        return $result;
    }
    
    
    /**
     * 取得站点浏览器Title值
     * @param int $siteId 站点id
     * @return string 站点浏览器Title值
     */
    public function GetBrowserTitle($siteId) {
        $sql = "SELECT BrowserTitle FROM ".self::TableName_Site." WHERE SiteId=:SiteId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("SiteId", $siteId);
        $result = $this->dbOperator->ReturnString($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得站点浏览器Description值
     * @param int $siteId 站点id
     * @return string 站点浏览器Description值
     */
    public function GetBrowserDescription($siteId) {
        $sql = "SELECT BrowserDescription FROM ".self::TableName_Site." WHERE SiteId=:SiteId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("SiteId", $siteId);
        $result = $this->dbOperator->ReturnString($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得站点浏览器Keywords值
     * @param int $siteId 站点id
     * @return string 站点浏览器Keywords值
     */
    public function GetBrowserKeywords($siteId) {
        $sql = "SELECT BrowserKeywords FROM ".self::TableName_Site." WHERE SiteId=:SiteId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("SiteId", $siteId);
        $result = $this->dbOperator->ReturnString($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得站点类型id
     * @param int $siteId 站点id
     * @return int 站点类型id
     */
    public function GetSiteTypeId($siteId) {
        $sql = "SELECT SiteTypeId FROM ".self::TableName_Site." WHERE SiteId=:SiteId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("SiteId", $siteId);
        $result = $this->dbOperator->ReturnInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得站点状态
     * @param int $siteId 站点id
     * @return int 站点状态
     */
    public function GetState($siteId) {
        $sql = "SELECT State FROM ".self::TableName_Site." WHERE SiteId=:SiteId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("SiteId", $siteId);
        $result = $this->dbOperator->ReturnInt($sql, $dataProperty);
        return $result;
    }
    
    /**
     * 取得站点Url
     * @param int $siteId 站点id
     * @return string 站点Url
     */
    public function GetSiteUrl($siteId) {
        $sql = "SELECT SiteUrl FROM ".self::TableName_Site." WHERE SiteId=:SiteId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("SiteId", $siteId);
        $result = $this->dbOperator->ReturnString($sql, $dataProperty);
        return $result;
    }

}

?>
