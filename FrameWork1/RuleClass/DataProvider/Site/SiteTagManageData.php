<?php

/**
 * 后台管理 站点 后台数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Site
 * @author zhangchi
 */
class SiteTagManageData extends BaseManageData {

    /**
     * 新增关键词
     * @param array $httpPostData $_POST数组
     * @return int 新增的关键词id
     */
    public function Create($httpPostData)
    {
        $result = -1;
        $dataProperty = new DataProperty();
        $addFieldName = "";
        $addFieldValue = "";
        $preNumber = "";
        $addFieldNames = array();
        $addFieldValues = array();
        if (!empty($httpPostData)) {
            $sql = parent::GetInsertSql(
                $httpPostData,
                self::TableName_SiteTag,
                $dataProperty,
                $addFieldName,
                $addFieldValue,
                $preNumber,
                $addFieldNames,
                $addFieldValues
            );
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 修改站点
     * @param array $httpPostData $_POST数组
     * @param int $siteId 站点id
     * @return int 返回影响的行数
     */
    public function Modify($httpPostData, $siteId)
    {
        $result = -1;
        $dataProperty = new DataProperty();
        $addFieldName = "";
        $addFieldValue = "";
        $preNumber = "";
        $addFieldNames = array();
        $addFieldValues = array();
        if (!empty($httpPostData)) {
            $sql = parent::GetUpdateSql(
                $httpPostData,
                self::TableName_SiteTag,
                self::TableId_SiteTag,
                $siteId,
                $dataProperty,
                $addFieldName,
                $addFieldValue,
                $preNumber,
                $addFieldNames,
                $addFieldValues
            );
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }



    /**
     * 获取站点关键词
     * @param int $siteId 站点id
     * @param bool $withCache 是否缓存
     * @return array  数据集
     */
    public function GetListForPulling($siteId,$withCache=false) {
        $result=-1;
        if($siteId>0){
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'site_data';
            $cacheFile = 'site_get_site_tag.cache_' . $siteId . '';
            $sql = "
                SELECT
                SiteTagName
                FROM
                " . self::TableName_SiteTag . "
                WHERE SiteId=:SiteId AND State<100;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);
            $result = $this->GetInfoOfArrayList($sql,$dataProperty,$withCache,$cacheDir,$cacheFile);
        }

        return $result;
    }


    /**
     * 修改状态
     * @param int $siteTagId 站点siteTagId
     * @param int $state 状态
     * @return int 操作结果
     */
    public function ModifyState($siteTagId, $state)
    {
        $result = -1;
        if ($siteTagId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_SiteTag . " SET `State`=:State WHERE SiteTagId=:SiteTagId;";
            $dataProperty->AddField("SiteTagId", $siteTagId);
            $dataProperty->AddField("State", $state);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }



    /**
     * 获取站点关键词分页列表
     * @param int $siteId 站点id
     * @param int $pageBegin 起始页码
     * @param int $pageSize 每页大小
     * @param string $searchKey 搜索关键字
     * @param int $allCount
     * @return array 数据集
     */
    public function GetListPager($siteId, $pageBegin, $pageSize, $searchKey,&$allCount) {
        $result=-1;
        if($siteId>0){
            $searchSql = "";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);

            if (strlen($searchKey) > 0 && $searchKey != "undefined") {
                $searchSql.=" AND SiteTagName LIKE :SearchKey ";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            }

            $sql = "
                SELECT
                *
                FROM
                " . self::TableName_SiteTag . "
                WHERE SiteId=:SiteId " . $searchSql . " LIMIT " . $pageBegin . "," . $pageSize . " ;";

            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
            $sqlCount = "SELECT count(*) FROM " . self::TableName_SiteTag . " WHERE SiteId=:SiteId " . $searchSql . " ;";
            $allCount = $this->dbOperator->GetInt($sqlCount, $dataProperty);
        }

        return $result;
    }

    /**
     * 取得站点状态
     * @param int $siteTagId 站点id
     * @param bool $withCache 是否从缓冲中取
     * @return string 站点状态
     */
    public function GetState($siteTagId, $withCache) {
        $result = "";
        if ($siteTagId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'site_tag_data';
            $cacheFile = 'site_tag_get_state.cache_' . $siteTagId . '';
            $sql = "SELECT State FROM " . self::TableName_SiteTag . " WHERE SiteTagId=:SiteTagId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteTagId", $siteTagId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }


    /**
     * 取得站点id
     * @param int $siteTagId 站点id
     * @param bool $withCache 是否从缓冲中取
     * @return string 站点状态
     */
    public function GetSiteId($siteTagId, $withCache) {
        $result = "";
        if ($siteTagId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'site_tag_data';
            $cacheFile = 'site_tag_get_site_id.cache_' . $siteTagId . '';
            $sql = "SELECT SiteId FROM " . self::TableName_SiteTag . " WHERE SiteTagId=:SiteTagId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteTagId", $siteTagId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }


    /**
     * 返回一行数据
     * @param int $siteTagId 站点tag id
     * @return array|null 取得对应数组
     */
    public function GetOne($siteTagId){
        $result = null;
        if($siteTagId>0){
            $sql = "SELECT * FROM
                        " . self::TableName_SiteTag . "
                    WHERE SiteTagId=:SiteTagId
                    ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteTagId", $siteTagId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_SiteTag){
        return parent::GetFields(self::TableName_SiteTag);
    }
}

?>
