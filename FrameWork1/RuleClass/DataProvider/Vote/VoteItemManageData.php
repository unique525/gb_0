<?php
/**
 * 投票调查 题目 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Vote
 * @author hy
 */
class VoteItemManageData extends BaseManageData
{
    /**
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_VoteItem){
        return parent::GetFields(self::TableName_VoteItem);
    }

    /**
     * 新增题目
     * @param array $httpPostData $_post数组
     * @return int 返回题目Id
     */
    public function Create($httpPostData) {
        $dataProperty = new DataProperty();
        $sql = parent::GetInsertSql($httpPostData, self::TableName_VoteItem, $dataProperty);
        $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        return $result;
    }


    /**
     * 获取一个题目的数据
     * @param int $voteItemId  题目Id
     * @return array 题目一维数组
     */
    public function GetOne($voteItemId) {
        $sql = "
        SELECT VoteItemId,VoteId,VoteItemTitle,Sort,State,VoteItemType,RecordCount,AddCount,SelectNumMin,SelectNumMax,VoteIntro
        FROM
        " . self::TableName_VoteItem .
        " WHERE VoteItemId=:VoteItemId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("VoteItemId", $voteItemId);
        $result = $this->dbOperator->GetArray($sql, $dataProperty);
        return $result;
    }

    /**
     * 获取题目分页列表
     * @param int $pageBegin   起始页码
     * @param int $pageSize    每页记录数
     * @param int $allCount    记录总数
     * @param int $voteId  投票调查Id
     * @param string $searchKey   查询字符
     * @return array  题目列表数组
     */
    public function GetListForPager($voteId, $pageBegin, $pageSize, &$allCount, $searchKey = "") {
        $result = null;
        if ($voteId < 0) {
            return $result;
        }
        $dataProperty = new DataProperty();
        $dataProperty->AddField("VoteId", $voteId);
        $searchSql = "";
        if (strlen($searchKey) > 0 && $searchKey != "undefined") {
            $searchSql .= " AND (VoteTitle like :searchKey1)";
            $dataProperty->AddField("searchKey1", "%" . $searchKey . "%");
        }
        $sql = "
        SELECT VoteItemId,VoteId,VoteItemTitle,Sort,State,VoteItemType,RecordCount
        FROM " . self::TableName_VoteItem . "
        WHERE VoteId=:VoteId" . $searchSql . "
        ORDER BY Sort DESC,VoteItemId ASC LIMIT " . $pageBegin . "," . $pageSize . "";
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        $sql = "
        SELECT COUNT(*) FROM " . self::TableName_VoteItem . "
        WHERE VoteId=:VoteId"  . $searchSql;
        $allCount = $this->dbOperator->GetInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 修改题目
     * @param array $httpPostData $_post数组
     * @param int $voteItemId  题目Id
     * @return int 执行结果
     */
    public function Modify($httpPostData,$voteItemId) {
        $dataProperty = new DataProperty();
        $sql = parent::GetUpdateSql($httpPostData, self::TableName_VoteItem, self::TableId_VoteItem, $voteItemId, $dataProperty);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 异步修改状态
     * @param string $voteItemId 题目Id
     * @param string $state 状态
     * @return int 执行结果
     */
    public function ModifyState($voteItemId,$state) {
        $result = -1;
        if ($voteItemId < 0) {
            return $result;
        }
        $sql = "UPDATE " . self::TableName_VoteItem . " SET State=:State WHERE VoteItemId=:VoteItemId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("VoteItemId", $voteItemId);
        $dataProperty->AddField("State", $state);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 修改题目的加票数
     * @param int $voteItemId  题目Id
     * @param int $addCount    题目的加票数
     * @return int  执行结果
     */
    public function ModifyAddCount($voteItemId, $addCount) {
        $sql = "UPDATE " . self::TableName_VoteItem . " SET AddCount=:AddCount WHERE VoteItemId=:VoteItemId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("VoteItemId", $voteItemId);
        $dataProperty->AddField("AddCount", $addCount);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 获取投票调查的总票数
     * @param int $voteId   题目所属的投票调查Id
     * @return int  返回投票调查总票数
     */
    public function GetSum($voteId) {
        $sql = "SELECT SUM(AddCount) FROM " . self::TableName_VoteItem . " WHERE State=0 AND VoteId=:VoteId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("VoteId", $voteId);
        $result = $this->dbOperator->GetInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 根据投票调查ID获取题目列表数据
     * @param int $voteId   投票调查ID
     * @param int $state    投票调查状态
     * @param string $order    排序
     * @param int $topCount    题目条数
     * @return array  返回查询题目数组
     */
    public function GetList($voteId,$state,$order = "",$topCount = null) {
        $result = null;
        if ($topCount != null)
            $topCount = " limit " . $topCount;
        switch ($order) {
            default:
                $order = " ORDER BY Sort DESC,VoteItemId ASC ";
                break;
        }
        if($voteId>0)
        {
        $sql = "SELECT VoteId,VoteItemId,VoteItemTitle,VoteItemType,RecordCount,VoteIntro,SelectNumMin,SelectNumMax,
        CASE VoteItemType WHEN '0' THEN 'radio' ELSE 'checkbox' END AS VoteItemTypeName
        FROM " . self::TableName_VoteItem . "
        WHERE State=:State AND VoteId=:VoteId"
        . $order
        . $topCount;
        $dataProperty = new DataProperty();
        $dataProperty->AddField("State", $state);
        $dataProperty->AddField("VoteId", $voteId);
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }

}

?>