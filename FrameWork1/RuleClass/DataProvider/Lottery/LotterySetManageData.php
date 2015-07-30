<?php
/**
 * 后台管理 奖项设置 数据类
 *
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Lottery
 * @author 525
 */
class LotterySetManageData extends BaseManageData{

    /**
     * 新增分类信息
     * @param array $httpPostData $_post数组
     * @param int $lotteryId
     * @return int 新增活动id
     */
    public function Create($httpPostData,$lotteryId) {
        $result=-1;
        $dataProperty = new DataProperty();
        $addFieldName = "LotteryId";
        $addFieldValue = $lotteryId;
        $preNumber = "";
        $addFieldNames = array();
        $addFieldValues = array();
        if (!empty($httpPostData)) {
            $sql = parent::GetInsertSql($httpPostData,self::TableName_LotterySet, $dataProperty, $addFieldName, $addFieldValue, $preNumber, $addFieldNames, $addFieldValues);
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        }
        return $result;
    }
    /**
     * 修改
     * @param array $httpPostData $_post数组
     * @param string $lotterySetId 分类信息id
     * @return int 执行结果
     */
    public function Modify($httpPostData,$lotterySetId) {

        $result = -1;
        $dataProperty = new DataProperty();
        $addFieldName = "";
        $addFieldValue = "";
        $preNumber = "";
        $addFieldNames = array();
        $addFieldValues = array();
        if (!empty($httpPostData)) {
            $sql = parent::GetUpdateSql($httpPostData, self::TableName_LotterySet, self::TableId_LotterySet, $lotterySetId, $dataProperty, $addFieldName, $addFieldValue, $preNumber, $addFieldNames, $addFieldValues);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }
    /**
     * 获取抽奖活动分页列表
     * @param int $lotteryId 抽奖id
     * @param int $pageBegin 起始页码
     * @param int $pageSize 每页大小
     * @param int $allCount 总大小
     * @param string $searchKey 搜索关键字
     * @return array 数据集
     */
    public function GetListPager($lotteryId, $pageBegin, $pageSize, &$allCount, $searchKey) {
        $result=null;
        if($lotteryId>0){
            $searchSql = "";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("LotteryId", $lotteryId);

            if (strlen($searchKey) > 0 && $searchKey != "undefined") {
                $searchSql.=" AND LotterySetName LIKE :SearchKey ";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            }

            $sql = "
                SELECT
                l.*,
                (SELECT COUNT(*) FROM " .self::TableName_LotteryAwardUser." au WHERE au.LotterySetId = l.LotterySetId) as AwardCount
                FROM
                " . self::TableName_LotterySet . " l
                WHERE LotteryId=:LotteryId " . $searchSql . " ORDER BY CreateDate DESC LIMIT " . $pageBegin . "," . $pageSize . " ;";

            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
            $sqlCount = "SELECT count(*) FROM " . self::TableName_LotterySet . " WHERE LotteryId=:LotteryId " . $searchSql . " ;";
            $allCount = $this->dbOperator->GetInt($sqlCount, $dataProperty);
        }
        return $result;
    }

    /**
     * 通过ID获取一条记录
     * @param int $lotterySetId 活动id
     * @return array 活动数据
     */
    public function GetOne($lotterySetId) {
        $result=-1;
        if($lotterySetId>0){
            $sql = "SELECT * FROM " . self::TableName_LotterySet . " WHERE LotterySetId = :LotterySetId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("LotterySetId", $lotterySetId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 通过ID获取抽奖id
     * @param int $lotterySetId 活动id
     * @return int
     */
    public function GetLotteryId($lotterySetId) {
        $result=-1;
        if($lotterySetId>0){
            $sql = "SELECT LotteryId FROM " . self::TableName_LotterySet . " WHERE LotterySetId = :LotterySetId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("LotterySetId", $lotterySetId);
            $result = $this->dbOperator->GetInt($sql, $dataProperty);
        }
        return $result;
    }
    /**
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_LotterySet){
        return parent::GetFields(self::TableName_LotterySet);
    }


    /**
     * 修改同奖项组的同一人中奖限制
     * @param $lotteryId
     * @param $lotterySetGroup
     * @param $oneUserLimit
     * @return int
     */
    public function ModifyOneUserLimitOfGroup($lotteryId,$lotterySetGroup,$oneUserLimit) {

        $result = -1;
        if($lotteryId>0){
            $sql="UPDATE " . self::TableName_LotterySet . " SET OneUserLimit=:OneUserLimit WHERE LotteryId=:LotteryId AND LotterySetGroup=:LotterySetGroup ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("LotteryId", $lotteryId);
            $dataProperty->AddField("OneUserLimit", $oneUserLimit);
            $dataProperty->AddField("LotterySetGroup", $lotterySetGroup);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 修改状态
     * @param string $lotterySetId Id
     * @param string $state 状态
     * @return int 执行结果
     */
    public function ModifyState($lotterySetId,$state) {
        $result = -1;
        if ($lotterySetId < 0) {
            return $result;
        }
        $sql = "UPDATE " . self::TableName_LotterySet . " SET State=:State WHERE LotterySetId=:LotterySetId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("LotterySetId", $lotterySetId);
        $dataProperty->AddField("State", $state);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }
} 