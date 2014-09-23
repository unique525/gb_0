<?php
/**
 * 后台管理 活动 数据类
 *
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_site_ad
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
     * 获取活动分页列表
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
            $sqlCount = "SELECT count(*) FROM " . self::TableName_SiteAd . " WHERE SiteId=:SiteId AND state<100 " . $searchSql . " ;";
            $allCount = $this->dbOperator->GetInt($sqlCount, $dataProperty);
        }
        return $result;
    }
}
?>