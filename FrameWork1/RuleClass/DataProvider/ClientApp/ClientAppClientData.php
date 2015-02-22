<?php
/**
 * 客户端 客户端应用程序 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_ClientApp
 * @author zhangchi
 */
class ClientAppClientData extends BaseClientData {

    /**
     * 返回某站点下客户端最新的版本号
     * @param int $siteId 站点id
     * @param bool $withCache 是否使用缓存
     * @return array 客户端最新的版本号
     */
    public function GetNewVersion($siteId, $withCache){

        $result = null;
        if ($siteId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'client_app_data';
            $cacheFile = 'arr_client_app_get_new_version_code.cache_' . $siteId . '';
            $cacheContent = DataCache::Get($cacheDir . DIRECTORY_SEPARATOR . $cacheFile);
            if (strlen($cacheContent) <= 2 || !$withCache) {
                $sql = "SELECT *
                    FROM " . self::TableName_Client_App . "
                    WHERE SiteId=:SiteId
                    ORDER BY UpdateDate DESC
                    LIMIT 1;";
                $dataProperty = new DataProperty();
                $dataProperty->AddField("SiteId", $siteId);
                $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

                DataCache::Set($cacheDir, $cacheFile, Format::FixJsonEncode($result));

            }else{
                $result = Format::FixJsonDecode($cacheContent);
            }

        }

        return $result;
    }


} 