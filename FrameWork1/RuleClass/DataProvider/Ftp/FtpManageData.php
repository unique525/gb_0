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

    /**
     * 通过ID获取一条记录
     * @param int $ftpId 活动id
     * @return array ftp数据
     */
    public function GetOne($ftpId) {
        $result=-1;
        if($ftpId>0){
            $sql = "SELECT * FROM " . self::TableName_Ftp . " WHERE FtpId = :FtpId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("FtpId", $ftpId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 新增FTP
     * @param array $httpPostData $_post数组
     * @return int 新增FTPid
     */
    public function Create($httpPostData){
        $result=-1;
        $dataProperty = new DataProperty();
        $addFieldName = "";
        $addFieldValue = "";
        $preNumber = "";
        $addFieldNames = array();
        $addFieldValues = array();
        if(!empty($httpPostData)){
            $sql=parent::GetInsertSql($httpPostData, self::TableName_Ftp, $dataProperty, $addFieldName, $addFieldValue, $preNumber, $addFieldNames, $addFieldValues);
            $result=$this->dbOperator->LastInsertId($sql,$dataProperty);
        }
        return $result;
    }


    /**
     * 修改
     * @param array $httpPostData $_post数组
     * @param int $ftpId 过滤字段id
     * @return int 执行结果
     */
    public function Modify($httpPostData,$ftpId){
        $dataProperty = new DataProperty();
        $sql = parent::GetUpdateSql($httpPostData, self::TableName_Ftp, self::TableId_Ftp, $ftpId, $dataProperty);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 删除
     * @param int $ftpId 过滤字段id
     * @return int 执行结果
     */
    public function Delete($ftpId){
        $dataProperty = new DataProperty();
        $dataProperty->AddField("FtpId", $ftpId);
        $sql = "DELETE FROM ".self::TableName_Ftp." WHERE FtpId=:FtpId ;";
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }


    /**
     * 获取过滤字段分页列表
     * @param int $siteId 站点id
     * @param int $pageBegin 起始页码
     * @param int $pageSize 每页大小
     * @param int $allCount 总大小
     * @param string $searchKey 搜索关键字
     * @return array 过滤字段数据集
     */
    public function GetListPager($siteId, $pageBegin, $pageSize, &$allCount, $searchKey) {
        $result=-1;
        if($siteId>0){
            $searchSql = "";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);

            if (strlen($searchKey) > 0 && $searchKey != "undefined") {
                $searchSql.=" AND FtpHost LIKE :SearchKey ";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            }

            $sql = "
                SELECT
                *
                FROM
                " . self::TableName_Ftp . "
                WHERE SiteId=:SiteId " . $searchSql . " ORDER BY SiteId DESC LIMIT " . $pageBegin . "," . $pageSize . " ;";

            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
            $sqlCount = "SELECT count(*) FROM " . self::TableName_Ftp . " WHERE (SiteId=:SiteId OR SiteId=0) " . $searchSql . " ORDER BY SiteId DESC ;";
            $allCount = $this->dbOperator->GetInt($sqlCount, $dataProperty);
        }
        return $result;
    }

    /**
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_Ftp){
        return parent::GetFields(self::TableName_Ftp);
    }


    /**
     * 取得所在站点id
     * @param string $ftpId 过滤字段Id
     * @return int site_id
     */
    public function GetSiteId($ftpId) {
        $result = "";
        if ($ftpId > 0) {
            $sql = "SELECT SiteId FROM " . self::TableName_Ftp . " WHERE FtpId=:FtpId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("FtpId", $ftpId);
            $result = $this->dbOperator->GetInt($sql,$dataProperty);
        }
        return $result;
    }


    /**
     * 将数组中的内容填充到对象中
     * @param array $arr 一条ftp记录
     * @param Ftp $ftp Ftp数据对象
     */
    public function FillFtp($arr, Ftp &$ftp)
    {
        if (!empty($arr)) {

            if (!empty($arr["FtpId"])) {
                $ftp->FtpId = intval($arr["FtpId"]);
            }
            if (!empty($arr["FtpHost"])) {
                $ftp->FtpHost = strval($arr["FtpHost"]);
            }
            if (!empty($arr["FtpPort"])) {
                $ftp->FtpPort = strval($arr["FtpPort"]);
            }
            if (!empty($arr["FtpUser"])) {
                $ftp->FtpUser = strval($arr["FtpUser"]);
            }
            if (!empty($arr["FtpPass"])) {
                $ftp->FtpPass = strval($arr["FtpPass"]);
            }
            if (!empty($arr["RemotePath"])) {
                $ftp->RemotePath = strval($arr["RemotePath"]);
            }
            if (!empty($arr["PasvMode"])) {
                $ftp->PasvMode = strval($arr["PasvMode"]);
            }
            if (!empty($arr["Timeout"])) {
                $ftp->Timeout = strval($arr["Timeout"]);
            }
            if (!empty($arr["SiteId"])) {
                $ftp->SiteId = strval($arr["SiteId"]);
            }
        }
    }
} 