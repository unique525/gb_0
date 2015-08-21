<?php

/**
 * 前台 广告内容 数据类
 *
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_site
 * @author 525
 */
class SiteAdContentPublicData extends BaseData {
    /**
     * 取得广告点击记录开启状态
     * @param int $siteAdContentId
     * @param bool $withCache
     * @return int 点击记录开启状态 0：未开启，1：开启
     */
    public function GetOpenCount($siteAdContentId,$withCache=FALSE) {
        $result=-1;
        if($siteAdContentId>0){
            $sql = "SELECT OpenCount FROM ".self::TableName_SiteAdContent." WHERE SiteAdContentId=:SiteAdContentId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteAdContentId", $siteAdContentId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty,$withCache,"","");
        }
        return $result;
    }

    /**
     * 取得广告虚拟点击开启状态
     * @param int $siteAdContentId
     * @param bool $withCache
     * @return int 虚拟点击开启状态 0：未开启，1：开启
     */
    public function GetOpenVirtualClick($siteAdContentId,$withCache=FALSE) {
        $result=-1;
        if($siteAdContentId>0){
            $sql = "SELECT OpenVirtualClick FROM ".self::TableName_SiteAdContent." WHERE SiteAdContentId=:SiteAdContentId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteAdContentId", $siteAdContentId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty,$withCache,"","");
        }
        return $result;
    }


    /**
     * 取得广告虚拟点击每小时限制数
     * @param int $siteAdContentId
     * @param bool $withCache
     * @return int 虚拟点击每小时限制数
     */
    public function GetVirtualClickLimit($siteAdContentId,$withCache=FALSE) {
        $result=-1;
        if($siteAdContentId>0){
            $sql = "SELECT VirtualClickLimit FROM ".self::TableName_SiteAdContent." WHERE SiteAdContentId=:SiteAdContentId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteAdContentId", $siteAdContentId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty,$withCache,"","");
        }
        return $result;
    }


    public function GetVirtualClickInfo($siteAdContentId,$withCache){
        $result=null;
        if($siteAdContentId>0){
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'site_ad_content_data';
            $cacheFile = 'site_ad_content_get_virtual_click_info.cache_' . $siteAdContentId . '';
            $sql = "SELECT OpenVirtualClick,VirtualClickLimit FROM ".self::TableName_SiteAdContent." WHERE SiteAdContentId=:SiteAdContentId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteAdContentId", $siteAdContentId);
            $result = $this->GetInfoOfArray($sql, $dataProperty,$withCache,$cacheDir,$cacheFile);
        }
        return $result;
    }
}

?>