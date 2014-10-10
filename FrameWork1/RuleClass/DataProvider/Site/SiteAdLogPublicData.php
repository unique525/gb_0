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
     * @param int $adContentId
     * @param string $createDate
     * @param string $ipAddress
     * @param string $agent
     * @param string $refDomain
     * @param string $refUrl
     * @param int $isVClick
     * @return int
     */
    public function InsertData($adContentId, $createDate, $ipAddress, $agent, $refDomain, $refUrl, $isVClick = 0) {
        $result="";
        if($adContentId>0){
            $sql = "INSERT INTO " . self::TableName_SiteAdLog . " (AdContentId,CreateDate,IpAddress,Agent,RefDomain,RefUrl,IsVClick) values (:AdContentId,:CreateDate,:IpAddress,:Agent,:RefDomain,:RefUrl,:IsVClick)";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("AdContentId", $adContentId);
            $dataProperty->AddField("CreateDate", $createDate);
            $dataProperty->AddField("IpAddress", $ipAddress);
            $dataProperty->AddField("Agent", $agent);
            $dataProperty->AddField("RefDomain", $refDomain);
            $dataProperty->AddField("RefUrl", $refUrl);
            $dataProperty->AddField("IsVClick", $isVClick);
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        }

        return $result;
    }

}

?>
