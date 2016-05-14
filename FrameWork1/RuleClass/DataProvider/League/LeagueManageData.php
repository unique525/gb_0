<?php

/**
 * 后台管理 赛事 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_League
 * @author 525
 */
class LeagueManageData extends BaseManageData
{

    /**
     * 获取赛事分页列表
     * @param int $siteId 频道id
     * @param int $pageBegin 起始页码
     * @param int $pageSize 每页大小
     * @param int $allCount 总大小
     * @param string $searchKey 搜索关键字
     * @return array 活动数据集
     */
    public function GetListPager($siteId, $pageBegin, $pageSize, &$allCount, $searchKey) {
        $result=-1;
        if($siteId>0){
            $searchSql = "";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);

            if (strlen($searchKey) > 0 && $searchKey != "undefined") {
                $searchSql.=" AND InformationTitle LIKE :SearchKey ";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            }

            $sql = "
                SELECT "."
                *
                FROM
                " . self::TableName_League . "
                WHERE SiteId=:SiteId " . $searchSql . " LIMIT " . $pageBegin . "," . $pageSize . " ;";


            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
            $sqlCount = "SELECT "." count(*) FROM " . self::TableName_League . " WHERE SiteId=:SiteId " . $searchSql . " ;";
            $allCount = $this->dbOperator->GetInt($sqlCount, $dataProperty);
        }
        return $result;
    }



    /**
     * 获取赛事分页列表
     * @param int $siteId id
     * @param string $date
     * @return array 活动数据集
     */
    public function GetAvailableList($siteId, $date) {
        $result=-1;
        if($siteId>0){
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);

            $sql = "
                SELECT "."
                *
                FROM
                " . self::TableName_League . "
                WHERE SiteId=:SiteId AND EndDate>" . $date . ";";


            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }




    /**
     * 取得所属站点id
     * @param int $leagueId id
     * @param bool $withCache 是否从缓冲中取
     * @return int 站点id
     */
    public function GetSiteId($leagueId, $withCache)
    {
        $result = -1;
        if ($leagueId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'league_data';
            $cacheFile = 'league_get_site_id.cache_' . $leagueId . '';
            $sql = "SELECT "." SiteId FROM " . self::TableName_League . " WHERE LeagueId=:LeagueId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("LeagueId", $leagueId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }


    /**
     * @param $leagueId
     * @return array
     */
    public function GetOne($leagueId){
        $result = array();
        if ($leagueId > 0) {
            $sql = 'SELECT '.' * FROM '.self::TableName_League.'
             WHERE LeagueId=:LeagueId;';
            $dataProperty = new DataProperty();
            $dataProperty->AddField("LeagueId", $leagueId);
            $result=$this->dbOperator->GetArray($sql,$dataProperty);
        }
        return $result;
    }



    /**
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_League){
        return parent::GetFields(self::TableName_League);
    }


}