<?php

/**
 * 站点 公众数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Site
 * @author zhangchi
 */
class SitePublicData extends BasePublicData {

    /**
     * 取得站点网址
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
     * 取得站点子域名
     * @param int $bindDomain 绑定的一级域名
     * @param bool $withCache 是否从缓冲中取
     * @return string 子域名
     */
    public function GetSiteIdByBindDomain($bindDomain, $withCache) {
        $result = -1;
        if (strlen($bindDomain) > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'site_data';
            $cacheFile = 'site_get_site_id.cache_' . $bindDomain . '';
            $sql = "SELECT SiteId FROM " . self::TableName_Site . " WHERE FIND_IN_SET(:BindDomain,BindDomain);";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("BindDomain", $bindDomain);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }

        return $result;
    }

    /**
     * 取得站点子域名
     * @param int $siteId 站点id
     * @param bool $withCache 是否从缓冲中取
     * @return string 子域名 
     */
    public function GetSubDomain($siteId, $withCache) {
        $result = "";
        if ($siteId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'site_data';
            $cacheFile = 'site_get_sub_domain.cache_' . $siteId . '';
            $sql = "SELECT SiteId FROM " . self::TableName_Site . " WHERE SiteId=:SiteId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }

        return $result;
    }
    
    /**
     * 通过子域名取得站点id
     * @param string $subDomain 站点id子域名
     * @param bool $withCache 是否从缓冲中取
     * @return int 站点id
     */
    public function GetSiteId($subDomain, $withCache) {

        $result = -1;
        if (strlen($subDomain) > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'site_data';
            $cacheFile = 'site_get_site_id.cache_' . $subDomain . '';
            $sql = "SELECT SiteId FROM " . self::TableName_Site . " WHERE SubDomain=:SubDomain;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SubDomain", $subDomain);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }

        return $result;

    }

    /**
     * 返回一行数据
     * @param int $siteId 站点id
     * @param bool $withCache 是否缓存
     * @return array|null 取得对应数组
     */
    public function GetOne($siteId, $withCache = true){
        $result = null;
        if($siteId>0){

            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'site_data';
            $cacheFile = 'arr_site_get_one.cache_' .
                str_ireplace(",","_",$siteId);

            $sql = "SELECT
                SiteId,
                SiteName,
                SiteUrl,
                BrowserTitle,
                BrowserDescription,
                BrowserKeywords

            FROM " . self::TableName_Site . "

            WHERE SiteId=:SiteId;";

            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);
            $result = $this->GetInfoOfArray($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);

        }
        return $result;
    }

}

?>
