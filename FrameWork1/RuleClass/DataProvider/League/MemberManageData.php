<?php

/**
 * 后台管理 队员 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_League
 * @author 525
 */
class MemberManageData extends BaseManageData
{



    /**
     * 新增
     * @param array $httpPostData $_post数组
     * @param int $siteId
     * @return int 新增活动id
     */
    public function Create($httpPostData, $siteId) {
        $result=-1;
        $dataProperty = new DataProperty();
        $addFieldName = "SiteId";
        $addFieldValue = $siteId;
        $preNumber = "";
        $addFieldNames = array();
        $addFieldValues = array();
        if (!empty($httpPostData)) {
            $sql = parent::GetInsertSql($httpPostData,self::TableName_Member, $dataProperty, $addFieldName, $addFieldValue, $preNumber, $addFieldNames, $addFieldValues);
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 修改
     * @param array $httpPostData $_post数组
     * @param string $memberId 分类信息id
     * @return int 执行结果
     */
    public function Modify($httpPostData, $memberId) {

        $result = -1;
        $dataProperty = new DataProperty();
        $addFieldName = "";
        $addFieldValue = "";
        $preNumber = "";
        $addFieldNames = array();
        $addFieldValues = array();

        if (!empty($httpPostData)) {
            $sql = parent::GetUpdateSql($httpPostData, self::TableName_Member, self::TableId_Member, $memberId, $dataProperty, $addFieldName, $addFieldValue, $preNumber, $addFieldNames, $addFieldValues);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }




    /**
     * 批量导入
     * @param $importArray
     * @param $siteId
     * @param $createDate
     * @return int
     */
    public function Import($importArray,$siteId,$createDate){
        $result=-1;
        if(!empty($importArray)&&$siteId>0){
            $arrSql=array();
            $arrDataProperty=array();
            foreach($importArray as $key=>$value){
                $dataProperty = new DataProperty();
                $dataProperty->AddField("MemberName",$value["MemberName"]);
                $dataProperty->AddField("TeamId",$value["TeamId"]);
                $dataProperty->AddField("Number",$value["Number"]);
                //$dataProperty->AddField("BirthDay",$value["BirthDay"]);
                $dataProperty->AddField("SiteId",$siteId);
                $arrDataProperty[]=$dataProperty;
                $arrSql[]="INSERT "." INTO ".parent::TableName_Member."
                (MemberName,SiteId,TeamId,Number,BirthDay,CreateTime) VALUES(:MemberName,:SiteId,:TeamId,:Number,'".$value["BirthDay"]."','$createDate'); ";
            }
            //print_r($arrSql);die();
            $result=$this->dbOperator->ExecuteBatch($arrSql,$arrDataProperty);
        }
        return $result;
    }
    /**
     * 获取队员分页列表
     * @param int $siteId id
     * @param int $pageBegin 起始页码
     * @param int $pageSize 每页大小
     * @param int $allCount 总大小
     * @param string $searchKey 搜索关键字
     * @param int $searchType 搜索类型 0:名字
     * @return array 数据集
     */
    public function GetListPager($siteId, $pageBegin, $pageSize, &$allCount, $searchKey="", $searchType=0) {
        $result=array();
        if($siteId>0){
            $searchSql = "";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);


            if (strlen($searchKey) > 0 && $searchKey != "undefined") {
                $searchSql = " AND (m.MemberName like :SearchKey)";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            }

            $sql = "
                SELECT "." m.*,t.TeamName
                FROM
                " . self::TableName_Member . " m
                LEFT OUTER JOIN " .self::TableName_Team . " t ON m.TeamId=t.TeamId
                WHERE m.SiteId=:SiteId " . $searchSql . " LIMIT " . $pageBegin . "," . $pageSize . " ;";


            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
            $sqlCount = "SELECT "." count(*) FROM " . self::TableName_Member . " WHERE SiteId=:SiteId " . $searchSql . " ;";
            $allCount = $this->dbOperator->GetInt($sqlCount, $dataProperty);
        }
        return $result;
    }


    public function GetRepeat($checkStr,$fieldName){
        $result=array();
        if($checkStr!=""&&$fieldName!=""){
            $dataProperty = new DataProperty();
            $sql="SELECT ". "$fieldName FROM ".self::TableName_Member. " WHERE $fieldName IN ($checkStr) ;";
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 取得站点id
     * @param int $memberId id
     * @param bool $withCache 是否从缓冲中取
     * @return int 站点id
     */
    public function GetSiteId($memberId, $withCache)
    {
        $result = -1;
        if ($memberId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'league_data';
            $cacheFile = 'member_get_site_id.cache_' . $memberId . '';
            $sql = "SELECT "." SiteId FROM " . self::TableName_Member . " WHERE MemberId=:MemberId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("MemberId", $memberId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }


    /**
     * 通过ID获取一条记录
     * @param int $memberId 活动id
     * @return array 活动数据
     */
    public function GetOne($memberId) {
        $result=-1;
        if($memberId>0){
            $sql = "SELECT "." m.*,t.TeamName FROM " . self::TableName_Member . " m
             LEFT OUTER JOIN " . self::TableName_Team . " t ON t.TeamId=m.TeamId
             WHERE MemberId = :MemberId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("MemberId", $memberId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_Member){
        return parent::GetFields(self::TableName_Member);
    }

}