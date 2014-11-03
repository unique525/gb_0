<?php
/**
 * 后台管理 广告位 数据类
 *
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_site
 * @author 525
 */

class SiteAdManageData  extends BaseManageData{

    /**
     * 新增广告位
     * @param array $httpPostData $_post数组
     * @return int 新增广告id
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
            $sql=parent::GetInsertSql($httpPostData, self::TableName_SiteAd, $dataProperty, $addFieldName, $addFieldValue, $preNumber, $addFieldNames, $addFieldValues);
            $result=$this->dbOperator->LastInsertId($sql,$dataProperty);
        }
        return $result;
    }


    /**
     * 修改
     * @param array $httpPostData $_post数组
     * @param int $siteAdId 广告id
     * @return int 执行结果
     */
    public function Modify($httpPostData,$siteAdId){
        $dataProperty = new DataProperty();
        $sql = parent::GetUpdateSql($httpPostData, self::TableName_SiteAd, self::TableId_SiteAd, $siteAdId, $dataProperty);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }


    /**
     * 获取广告位分页列表
     * @param int $siteId 站点id
     * @param int $pageBegin 起始页码
     * @param int $pageSize 每页大小
     * @param int $allCount 总大小
     * @param string $searchKey 搜索关键字
     * @return array 广告位数据集
     */
    public function GetListPager($siteId, $pageBegin, $pageSize, &$allCount, $searchKey) {
        $result=-1;
        if($siteId>0){
            $searchSql = "";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);

            if (strlen($searchKey) > 0 && $searchKey != "undefined") {
                $searchSql.=" AND SiteAdName LIKE :SearchKey ";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            }

            $sql = "
                SELECT
                *
                FROM
                " . self::TableName_SiteAd . "
                WHERE SiteId=:SiteId " . $searchSql . " LIMIT " . $pageBegin . "," . $pageSize . " ;";

            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
            $sqlCount = "SELECT count(*) FROM " . self::TableName_SiteAd . " WHERE SiteId=:SiteId " . $searchSql . " ;";
            $allCount = $this->dbOperator->GetInt($sqlCount, $dataProperty);
        }
        return $result;
    }

    /**
     * 通过ID获取一条记录
     * @param int $siteAdId 广告位id
     * @return array 广告位数据
     */
    public function GetOne($siteAdId) {
        $result=-1;
        if($siteAdId>0){
            $sql = "SELECT * FROM " . self::TableName_SiteAd . " WHERE SiteAdId = :SiteAdId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteAdId", $siteAdId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_SiteAd){
        return parent::GetFields(self::TableName_SiteAd);
    }


    /**
     * 修改广告位状态
     * @param string $siteAdId 广告位Id
     * @param string $state 状态
     * @return int 执行结果
     */
    public function ModifyState($siteAdId,$state) {
        $result = -1;
        if ($siteAdId < 0) {
            return $result;
        }
        $sql = "UPDATE " . self::TableName_SiteAd . " SET State=:State WHERE SiteAdId=:SiteAdId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("SiteAdId", $siteAdId);
        $dataProperty->AddField("State", $state);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得广告位所在站点id
     * @param string $siteAdId 广告位Id
     * @param bool $withCache 是否从缓冲中取
     * @return int site_id
     */
    public function GetSiteId($siteAdId,$withCache=FALSE) {
            $result = "";
            if ($siteAdId > 0) {
                $withCache=FALSE;
                $cacheDir = "";//CACHE_PATH . DIRECTORY_SEPARATOR . '_data';
                $cacheFile = "";//'channel_get_channel_name.cache_' . $siteAdId . '';
                $sql = "SELECT SiteId FROM " . self::TableName_SiteAd . " WHERE SiteAdId=:SiteAdId;";
                $dataProperty = new DataProperty();
                $dataProperty->AddField("SiteAdId", $siteAdId);
                $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
            }
            return $result;
        }
}
?>