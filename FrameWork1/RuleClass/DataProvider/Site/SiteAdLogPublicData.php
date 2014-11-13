<?php

/**
 * 前台 广告日志 数据类
 *
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_site
 * @author 525
 */
class SiteAdLogPublicData extends BaseData {
    /**
     * 记录广告点击
     * @param int $siteAdContentId
     * @param string $createDate
     * @param string $ipAddress
     * @param string $webAgent
     * @param string $refererDomain
     * @param string $refererUrl
     * @param int $isVirtualClick
     * @return int
     */
    public function InsertData($siteAdContentId, $createDate, $ipAddress, $webAgent, $refererDomain, $refererUrl, $isVirtualClick = 0) {
        $result="";
        if($siteAdContentId>0){
            $sql = "INSERT INTO " . self::TableName_SiteAdLog . " (SiteAdContentId,CreateDate,IpAddress,WebAgent,RefererDomain,RefererUrl,IsVirtualClick) values (:SiteAdContentId,:CreateDate,:IpAddress,:WebAgent,:RefererDomain,:RefererUrl,:IsVirtualClick)";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteAdContentId", $siteAdContentId);
            $dataProperty->AddField("CreateDate", $createDate);
            $dataProperty->AddField("IpAddress", $ipAddress);
            $dataProperty->AddField("WebAgent", $webAgent);
            $dataProperty->AddField("RefererDomain", $refererDomain);
            $dataProperty->AddField("RefererUrl", $refererUrl);
            $dataProperty->AddField("IsVirtualClick", $isVirtualClick);
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        }

        return $result;
    }

}

?>
