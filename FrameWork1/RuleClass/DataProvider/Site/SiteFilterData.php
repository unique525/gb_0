<?php
/**
 * 公共访问 过滤字段 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Site
 * @author zhangchi
 */
class SiteFilterData extends BaseData {
    /**
     * 获取站点过滤字段
     * @param int $siteId 站点id
     * @param bool $withCache
     * @return array 过滤字段数据集
     */
    public function GetList($siteId, $withCache = false) {
        $result=-1;
        if($siteId>0){

            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'site_filter_data';
            $cacheFile = 'site_filter_get_list_' . $siteId;


            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);

            $sql = "
                SELECT
                *
                FROM
                " . self::TableName_SiteFilter . "
                WHERE (SiteId=:SiteId OR SiteId=0) AND State<100 ORDER BY SiteId DESC ;";//取当前站点id=site_id与所有站点id=0的数据

            $result = $this->GetInfoOfArrayList($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);

        }
        return $result;
    }
} 