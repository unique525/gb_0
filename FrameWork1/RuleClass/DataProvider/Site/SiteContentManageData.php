<?php

/**
 * 后台管理 站点自定义页面 后台数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Site
 * @author zhangchi
 */
class SiteContentManageData extends BaseManageData {

    /**
     * 取得站点自定义页面内容
     * @param int $siteContentId 站点自定义页面id
     * @param bool $withCache 是否从缓冲中取
     * @return string 站点自定义页面内容
     */
    public function GetSiteContentValue($siteContentId, $withCache)
    {
        $result = "";
        if ($siteContentId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'site_content_data';
            $cacheFile = 'site_content_get_site_content_value.cache_' . $siteContentId . '';
            $sql = "SELECT SiteContentValue FROM " . self::TableName_SiteContent . " WHERE SiteContentId=:SiteContentId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteContentId", $siteContentId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }
} 