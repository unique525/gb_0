<?php
/**
 * 模板库的数据处理类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Template
 * @author 525
 */
class TemplateLibraryManageData extends BaseManageData {

    
    public function Create($templateLibraryName,$manageUserId,$siteId,$createDate){
        $sql = "INSERT INTO ".self::TableName_TemplateLibrary." (TemplateLibraryName,ManageUserId,SiteId,CreateDate) values (:TemplateLibraryName,:ManageUserId,:SiteId,'$createDate')";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("TemplateLibraryName", $templateLibraryName);
        $dataProperty->AddField("ManageUserId", $manageUserId);
        $dataProperty->AddField("SiteId", $siteId);
        $result = $this->dbOperator->LastInsertId($sql,$dataProperty);
        return $result;
    }


    /**
     * 修改
     * @param array $httpPostData $_post数组
     * @param int $templateLibraryId
     * @return int 执行结果
     */
    public function Modify($httpPostData,$templateLibraryId) {
        $dataProperty = new DataProperty();
        $sql = parent::GetUpdateSql($httpPostData, self::TableName_TemplateLibrary, self::TableId_TemplateLibrary, $templateLibraryId, $dataProperty);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    public function GetListOfPage($siteId,$pageBegin,$pageSize,&$allCount){
        $result=null;
        $dataProperty = new DataProperty();
        $sql = "SELECT t.*,au.ManageUserName,s.SiteName FROM ".self::TableName_TemplateLibrary." t
        LEFT JOIN ".self::TableName_ManageUser." au on au.ManageUserId = t.ManageUserId
        LEFT JOIN ".self::TableName_Site." s on s.SiteId = t.SiteId
        WHERE t.SiteId=:SiteId OR t.SiteId=0 ORDER BY Siteid,Sort LIMIT ".$pageBegin.",".$pageSize;
        $sqlCount = "SELECT count(*) FROM ".self::TableName_TemplateLibrary." WHERE t.SiteId=:SiteId OR t.SiteId=0 ;";
        $dataProperty->AddField("SiteId", $siteId);
        $result = $this->dbOperator->GetArrayList($sql,$dataProperty);
        $allCount = $this->dbOperator->GetInt($sqlCount,$dataProperty);
        return $result;
    }

    /**
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_TemplateLibrary){
        return parent::GetFields(self::TableName_TemplateLibrary);
    }


    /**
     * 通过ID获取一条记录
     * @param int $templateLibraryId id
     * @return array 一条数据
     */
    public function GetOne($templateLibraryId) {
        $result=null;
        if($templateLibraryId>0){
            $sql = "SELECT * FROM " . self::TableName_TemplateLibrary . " WHERE TemplateLibraryId = :TemplateLibraryId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("TemplateLibraryId", $templateLibraryId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 通过ID获取siteId
     * @param int $templateLibraryId id
     * @return int siteId
     */
    public function GetSiteId($templateLibraryId) {
        $result=-1;
        if($templateLibraryId>0){
            $sql = "SELECT SiteId FROM " . self::TableName_TemplateLibrary . " WHERE TemplateLibraryId = :TemplateLibraryId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("TemplateLibraryId", $templateLibraryId);
            $result = $this->dbOperator->GetInt($sql, $dataProperty);
        }
        return $result;
    }
}

?>
