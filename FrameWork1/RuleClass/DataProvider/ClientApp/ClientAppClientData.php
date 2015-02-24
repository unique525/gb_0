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
     * @param int $clientAppType APP type(iOS Android etc.)
     * @param bool $withCache 是否使用缓存
     * @return array 客户端最新的版本号
     */
    public function GetNewVersion($siteId, $clientAppType, $withCache){

        $result = null;
        if ($siteId > 0 && $clientAppType>0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'client_app_data';
            $cacheFile = 'arr_client_app_get_new_version_code.cache_' . $siteId . '_' . $clientAppType;
            $cacheContent = DataCache::Get($cacheDir . DIRECTORY_SEPARATOR . $cacheFile);
            if (strlen($cacheContent) <= 2 || !$withCache) {
                $sql = "SELECT *
                    FROM " . self::TableName_Client_App . "
                    WHERE SiteId=:SiteId AND ClientAppType=:ClientAppType
                    ORDER BY UpdateDate DESC
                    LIMIT 1;";
                $dataProperty = new DataProperty();
                $dataProperty->AddField("SiteId", $siteId);
                $dataProperty->AddField("ClientAppType", $clientAppType);
                $result = $this->dbOperator->GetArray($sql, $dataProperty);
                DataCache::Set($cacheDir, $cacheFile, Format::FixJsonEncode($result));

            }else{
                $result = Format::FixJsonDecode($cacheContent);
            }

        }

        return $result;
    }


} 