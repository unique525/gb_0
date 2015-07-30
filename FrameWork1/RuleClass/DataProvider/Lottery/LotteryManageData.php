<?php
/**
 * 后台管理 抽奖 数据类
 *
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Lottery
 * @author 525
 */
class LotteryManageData extends BaseManageData{

    /**
     * 新增
     * @param array $httpPostData $_post数组
     * @param int $channelId 节点id
     * @return int 新增活动id
     */
    public function Create($httpPostData,$channelId) {
        $result=-1;
        $dataProperty = new DataProperty();
        $addFieldName = "ChannelId";
        $addFieldValue = $channelId;
        $preNumber = "";
        $addFieldNames = array();
        $addFieldValues = array();
        if (!empty($httpPostData)) {
            $sql = parent::GetInsertSql($httpPostData,self::TableName_Lottery, $dataProperty, $addFieldName, $addFieldValue, $preNumber, $addFieldNames, $addFieldValues);
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        }
        return $result;
    }
    /**
     * 修改
     * @param array $httpPostData $_post数组
     * @param string $lotteryId 分类信息id
     * @return int 执行结果
     */
    public function Modify($httpPostData,$lotteryId) {

        $result = -1;
        $dataProperty = new DataProperty();
        $addFieldName = "";
        $addFieldValue = "";
        $preNumber = "";
        $addFieldNames = array();
        $addFieldValues = array();

        if (!empty($httpPostData)) {
            $sql = parent::GetUpdateSql($httpPostData, self::TableName_Lottery, self::TableId_Lottery, $lotteryId, $dataProperty, $addFieldName, $addFieldValue, $preNumber, $addFieldNames, $addFieldValues);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }
    /**
     * 获取抽奖活动分页列表
     * @param int $channelId 频道id
     * @param int $pageBegin 起始页码
     * @param int $pageSize 每页大小
     * @param int $allCount 总大小
     * @param string $searchKey 搜索关键字
     * @return array 活动数据集
     */
    public function GetListPager($channelId, $pageBegin, $pageSize, &$allCount, $searchKey) {
        $result=null;
        if($channelId>0){
            $searchSql = "";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);

            if (strlen($searchKey) > 0 && $searchKey != "undefined") {
                $searchSql.=" AND LotteryName LIKE :SearchKey ";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            }

            $sql = "
                SELECT
                *
                FROM
                " . self::TableName_Lottery . "
                WHERE ChannelId=:ChannelId " . $searchSql . " ORDER BY CreateDate DESC LIMIT " . $pageBegin . "," . $pageSize . " ;";

            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
            $sqlCount = "SELECT count(*) FROM " . self::TableName_Lottery . " WHERE ChannelId=:ChannelId " . $searchSql . " ;";
            $allCount = $this->dbOperator->GetInt($sqlCount, $dataProperty);
        }
        return $result;
    }

    /**
     * 通过ID获取一条记录
     * @param int $lotteryId 活动id
     * @return array 活动数据
     */
    public function GetOne($lotteryId) {
        $result=-1;
        if($lotteryId>0){
            $sql = "SELECT * FROM " . self::TableName_Lottery . " WHERE LotteryId = :LotteryId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("LotteryId", $lotteryId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_Lottery){
        return parent::GetFields(self::TableName_Lottery);
    }

    /**
     * @param $lotteryId
     * @return int
     */
    public function GetChannelId($lotteryId){
        $result=-1;
        if($lotteryId>0){
            $sql = "SELECT ChannelId FROM " . self::TableName_Lottery . " WHERE LotteryId = :LotteryId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("LotteryId", $lotteryId);
            $result = $this->dbOperator->GetInt($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 修改状态
     * @param string $lotteryId Id
     * @param string $state 状态
     * @return int 执行结果
     */
    public function ModifyState($lotteryId,$state) {
        $result = -1;
        if ($lotteryId < 0) {
            return $result;
        }
        $sql = "UPDATE " . self::TableName_Lottery . " SET State=:State WHERE LotteryId=:LotteryId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("LotteryId", $lotteryId);
        $dataProperty->AddField("State", $state);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }
} 