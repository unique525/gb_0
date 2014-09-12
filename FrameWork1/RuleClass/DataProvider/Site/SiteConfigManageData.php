<?php

/**
 * 后台管理 站点配置 后台数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Site
 * @author zhangchi
 */
class SiteConfigManageData extends BaseManageData
{
    /**
     * 返回某一站点下所有配置项列表
     * @param int $siteId 站点id
     * @return array 配置列表
     */
    public function GetList($siteId)
    {
        if ($siteId > 0) {
            $sql = "SELECT * FROM " . self::TableName_SiteConfig . " WHERE SiteId=:SiteId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
            return $result;
        } else {
            return null;
        }
    }
}

?>
