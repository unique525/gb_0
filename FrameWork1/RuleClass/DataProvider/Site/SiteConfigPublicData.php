<?php

/**
 * 站点配置 公众数据类
 * @category iCMS
 * @package iCMS_Rules_DataProvider_Site
 * @author zhangchi
 */
class SiteConfigPublicData extends BasePublicData {
    
    /**
     * 返回某一站点下所有配置项列表
     * @param int $siteId 站点id
     * @return array 配置项列表
     */
    public function GetList($siteId) {
        $sql = "SELECT * FROM ".self::tableName." WHERE SiteId=:SiteId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("SiteId", $siteId);
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        return $result;
    }
}

?>