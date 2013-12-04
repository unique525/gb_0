<?php

/**
 * 前台站点配置数据类  
 * @category iCMS
 * @package iCMS_Rules_DataProvider_Site
 * @author zhangchi
 */
class SiteConfigData extends BaseFrontData {

    /**
     * 返回站点所有配置项列表
     * @param int $siteId 站点id
     * @return array 站点所有配置项列表 
     */
    public function GetList($siteId) {
        $sql = "SELECT * FROM ".self::TableName_SiteConfig." WHERE SiteId=:SiteId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("SiteId", $siteId);
        $result = $this->dbOperator->ReturnArray($sql, $dataProperty);
        return $result;
    }
}

?>
