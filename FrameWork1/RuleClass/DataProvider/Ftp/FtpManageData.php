<?php
/**
 * 后台管理 FTP 配置 数据类
 * @category iCMS
 * @package iCMS_Rules_DataProvider_Ftp
 * @author zhangchi
 */
class FtpManageData extends BaseManageData {

    public function GetList(){

    }

    /**
     * 根据站点id取得一条ftp记录
     * @param int $siteId 站点id
     * @return array|null 一条ftp记录
     */
    public function GetOneBySiteId($siteId){
        $result = null;
        if($siteId>0){
            $sql = "SELECT * FROM " . self::TableName_Ftp . " WHERE SiteId=:SiteId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
        }
        return $result;
    }

} 