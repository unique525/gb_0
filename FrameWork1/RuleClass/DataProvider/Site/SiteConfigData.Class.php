<?php

/**
 * 前台站点配置数据类  
 * @category iCMS
 * @package iCMS_Rules_DataProvider_Site
 * @author zhangchi
 */
class SiteConfigData extends BaseFrontData {
    /**
     * 表名
     */
    const tableName = "cst_siteconfig";
    /**
     * 表关键字段名
     */
    const tableIdName = "SiteConfigId";
    
    /**
     * 返回某一站点下所有配置项列表
     * @param int $siteId 站点id
     * @return array 配置项列表
     */
    public function GetList($siteId) {
        $sql = "SELECT * FROM ".self::tableName." WHERE SiteId=:SiteId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("SiteId", $siteId);
        $result = $this->dbOperator->ReturnArray($sql, $dataProperty);
        return $result;
    }
}

?>
