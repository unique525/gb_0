<?php

/**
 * 前台站点数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Site
 * @author zhangchi
 */
class SiteData extends BasePublicData {
    /**
     * 表名
     */
    const tableName = "cst_site";

    /**
     * 表关键字段名
     */
    const tableIdName = "siteid";

    /**
     * 取得站点子域名
     * @param int $siteId 站点id
     * @return string 子域名 
     */
    public function GetSubDomain($siteId) {
        $cacheDir = 'data' . DIRECTORY_SEPARATOR . 'sitedata';
        $cacheFile = 'site_subdomain.cache_' . $siteId . '.php';
        if (parent::IsDataCached($cacheDir, $cacheFile)) {
            $sql = "SELECT SubDomain FROM " . self::tableName . " WHERE SiteId=:SiteId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);
            $result = $this->dbOperator->GetString($sql, $dataProperty);
            DataCache::Set($cacheDir, $cacheFile, $result);
        } else {
            $result = DataCache::Get($cacheDir . DIRECTORY_SEPARATOR . $cacheFile);
        }
        return $result;
    }
    
    /**
     * 通过子域名取得站点id
     * @param string $subDomain 站点id子域名 
     * @return int 站点id
     */
    public function GetSiteId($subDomain) {
        $cacheDir = 'data' . DIRECTORY_SEPARATOR . 'sitedata';
        $cacheFile = 'site_siteid.cache_' . $subDomain . '.php';
        if (parent::IsDataCached($cacheDir, $cacheFile)) {
            $sql = "SELECT SiteId FROM " . self::tableName . " WHERE SubDomain=:SubDomain";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SubDomain", $subDomain);
            $result = $this->dbOperator->GetInt($sql, $dataProperty);
            DataCache::Set($cacheDir, $cacheFile, $result);
        } else {
            $result = intval(DataCache::Get($cacheDir . DIRECTORY_SEPARATOR . $cacheFile));
        }
        return $result;
    }

}

?>
