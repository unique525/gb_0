<?php
/**
 * 后台管理 过滤字段 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_site
 * @author 525
 */
class SiteFilterManageData  extends BaseManageData{

    /**
     * 新增过滤字段
     * @param array $httpPostData $_post数组
     * @return int 新增过滤字段id
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
            $sql=parent::GetInsertSql($httpPostData, self::TableName_SiteFilter, $dataProperty, $addFieldName, $addFieldValue, $preNumber, $addFieldNames, $addFieldValues);
            $result=$this->dbOperator->LastInsertId($sql,$dataProperty);
        }
        return $result;
    }


    /**
     * 修改
     * @param array $httpPostData $_post数组
     * @param int $siteFilterId 过滤字段id
     * @return int 执行结果
     */
    public function Modify($httpPostData,$siteFilterId){
        $dataProperty = new DataProperty();
        $sql = parent::GetUpdateSql($httpPostData, self::TableName_SiteFilter, self::TableId_SiteFilter, $siteFilterId, $dataProperty);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 删除
     * @param int $siteFilterId 过滤字段id
     * @return int 执行结果
     */
    public function Delete($siteFilterId){
        $dataProperty = new DataProperty();
        $dataProperty->AddField("SiteFilterId", $siteFilterId);
        $sql = "DELETE FROM ".self::TableName_SiteFilter." WHERE SiteFilterId=:SiteFilterId ;";
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
                $searchSql.=" AND SiteFilterWord LIKE :SearchKey ";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            }

            $sql = "
                SELECT
                *
                FROM
                " . self::TableName_SiteFilter . "
                WHERE (SiteId=:SiteId OR SiteId=0) " . $searchSql . " ORDER BY SiteId DESC LIMIT " . $pageBegin . "," . $pageSize . " ;";//取当前站点id=site_id与所有站点id=0的数据

            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
            $sqlCount = "SELECT count(*) FROM " . self::TableName_SiteFilter . " WHERE (SiteId=:SiteId OR SiteId=0) " . $searchSql . " ORDER BY SiteId DESC ;";
            $allCount = $this->dbOperator->GetInt($sqlCount, $dataProperty);
        }
        return $result;
    }

    /**
     * 获取站点过滤字段
     * @param int $siteId 站点id
     * @return array 过滤字段数据集
     */
    public function GetList($siteId) {
        $result=-1;
        if($siteId>0){
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);

            $sql = "
                SELECT
                *
                FROM
                " . self::TableName_SiteFilter . "
                WHERE (SiteId=:SiteId OR SiteId=0) ORDER BY SiteId DESC ;";//取当前站点id=site_id与所有站点id=0的数据

            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 通过ID获取一条记录
     * @param int $siteFilterId 过滤字段id
     * @return array 过滤字段数据
     */
    public function GetOne($siteFilterId) {
        $result=-1;
        if($siteFilterId>0){
            $sql = "SELECT * FROM " . self::TableName_SiteFilter . " WHERE SiteFilterId = :SiteFilterId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteFilterId", $siteFilterId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_SiteFilter){
        return parent::GetFields(self::TableName_SiteFilter);
    }


    /**
     * 修改过滤字段状态
     * @param string $siteFilterId 过滤字段Id
     * @param string $state 状态
     * @return int 执行结果
     */
    public function ModifyState($siteFilterId,$state) {
        $result = -1;
        if ($siteFilterId < 0) {
            return $result;
        }
        $sql = "UPDATE " . self::TableName_SiteFilter . " SET State=:State WHERE SiteFilterId=:SiteFilterId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("SiteFilterId", $siteFilterId);
        $dataProperty->AddField("State", $state);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得过滤字段所在站点id
     * @param string $siteFilterId 过滤字段Id
     * @param bool $withCache 是否从缓冲中取
     * @return int site_id
     */
    public function GetSiteId($siteFilterId,$withCache=FALSE) {
        $result = "";
        if ($siteFilterId > 0) {
            $withCache=FALSE;
            $cacheDir = "";//CACHE_PATH . DIRECTORY_SEPARATOR . '_data';
            $cacheFile = "";//'channel_get_channel_name.cache_' . $siteFilterId . '';
            $sql = "SELECT SiteId FROM " . self::TableName_SiteFilter . " WHERE SiteFilterId=:SiteFilterId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteFilterId", $siteFilterId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 根据$siteFilterArea, $siteFilterWord, $siteId统计$siteFilterId条数
     * @param int $siteFilterArea  过滤字段范围
     * @param string $siteFilterWord  过滤字段字符
     * @param int $siteId  站点id
     * @return int
     */
    public function GetCount($siteFilterArea, $siteFilterWord, $siteId = 0) {
        $sql = "SELECT count(" . self::TableId_SiteFilter . ") FROM " . self::TableName_SiteFilter . " WHERE SiteFilterArea=:SiteFilterArea AND SiteFilterWord=:SiteFilterWord AND SiteId=:SiteId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("SiteFilterArea", $siteFilterArea);
        $dataProperty->AddField("SiteFilterWord", $siteFilterWord);
        $dataProperty->AddField("SiteId", $siteId);
        $result = $this->dbOperator->GetInt($sql, $dataProperty);
        return $result;
    }

}
?>