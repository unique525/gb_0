<?php

/**
 * 投票调查 题目选项 数据类
 * @category iCMS
 * @package iCMS_Rules_DataProvider_Vote
 * @author hy
 */
class VoteSelectItemManageData extends BaseManageData
{
    /**
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_VoteSelectItem){
        return parent::GetFields(self::TableName_VoteSelectItem);
    }

    /**
     * 新建选项
     * @param array $httpPostData $_post数组
     * @param string $titlePicPath 题图路径
     * @return int  返回选项Id
     */
    public function Create($httpPostData,$titlePicPath = "") {
        $dataProperty = new DataProperty();
        $sql = parent::GetInsertSql($httpPostData, self::TableName_VoteSelectItem, $dataProperty,"titlePic", $titlePicPath);
        $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        return $result;
    }

    /**
     * 获取一个选项的数据
     * @param int $voteSelectItemId  选项Id
     * @return array  选项一维数组
     */
    public function GetOne($voteSelectItemId) {
        $sql = "SELECT VoteSelectItemId,VoteItemId,Sort,State,VoteSelectItemTitle,RecordCount,AddCount,TitlePic,DirectUrl
        FROM " . self::TableName_VoteSelectItem . "
        WHERE voteSelectItemId=:voteSelectItemId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("voteSelectItemId", $voteSelectItemId);
        $result = $this->dbOperator->GetArray($sql, $dataProperty);
        return $result;
    }

    /**
     * 修改选项
     * @param array $httpPostData $_post数组
     * @param int $voteSelectItemId
     * @param string $titlePicPath 题图路径
     * @return int  返回执行结果
     */
    public function Modify($httpPostData,$voteSelectItemId,$titlePicPath = "") {
        $dataProperty = new DataProperty();
        $sql = parent::GetUpdateSql($httpPostData, self::TableName_VoteSelectItem, self::TableId_VoteSelectItem, $voteSelectItemId, $dataProperty,"titlePic", $titlePicPath);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 异步修改状态
     * @param string $voteSelectItemId 选项Id
     * @param string $state 状态
     * @return int 执行结果
     */
    public function ModifyState($voteSelectItemId,$state) {
        $result = -1;
        if ($voteSelectItemId < 0) {
            return $result;
        }
        $sql = "UPDATE " . self::TableName_VoteSelectItem . " SET State=:State WHERE VoteSelectItemId=:VoteSelectItemId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("VoteSelectItemId", $voteSelectItemId);
        $dataProperty->AddField("State", $state);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 获取一道题的所有选项列表
     * @param int $voteItemId  题目ID
     * @param int $state   题目选项状态值
     * @return array  返回数据集结果
     */
    public function GetSelect($voteItemId, $state) {
        $sql = "SELECT VoteSelectItemId,VoteSelectItemTitle,RecordCount,AddCount,TitlePic,DirectUrl,TableType,TableId
        FROM " . self::TableName_VoteSelectItem . "
        WHERE State=:state AND VoteItemId=:VoteItemId
        ORDER BY Sort DESC,VoteSelectItemId ASC";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("State", $state);
        $dataProperty->AddField("VoteItemId", $voteItemId);
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        return $result;
    }

    /**
     * 获取一道题目下选项总票数
     * @param int $VoteItemId  选项Id
     * @return int  返回选项总票数
     */
    public function GetSum($VoteItemId) {
        $sql = "SELECT SUM(AddCount) FROM " . self::TableName_VoteSelectItem . " WHERE State=0 AND VoteItemId=:VoteItemId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("VoteItemId", $VoteItemId);
        $result = $this->dbOperator->GetInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 获取选项分页列表
     * @param int $pageBegin   起始页码
     * @param int $pageSize    每页记录数
     * @param int $allCount    记录总数
     * @param int $voteItemId  题目Id
     * @param string $searchKey   查询字符
     * @return array  选项列表数组
     */
    public function GetListForPager($voteItemId, $pageBegin, $pageSize, &$allCount, $searchKey = "") {
        $result = null;
        if ($voteItemId < 0) {
            return $result;
        }
        $dataProperty = new DataProperty();
        $dataProperty->AddField("VoteItemId", $voteItemId);
        $searchSql = "";
        if (strlen($searchKey) > 0 && $searchKey != "undefined") {
            $searchSql .= " AND (VoteTitle LIKE :searchKey1)";
            $dataProperty->AddField("searchKey1", "%" . $searchKey . "%");
        }
        $sql = "
        SELECT VoteItemId,VoteSelectItemId,VoteSelectItemTitle,Sort,State,AddCount,RecordCount
        FROM " . self::TableName_VoteSelectItem . "
        WHERE VoteItemId=:VoteItemId" . $searchSql . "
        ORDER BY Sort DESC,VoteSelectItemId ASC
        LIMIT " . $pageBegin . "," . $pageSize . "";
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        $sql = "
        SELECT COUNT(*) FROM " . self::TableName_VoteSelectItem . "
        WHERE  VoteItemId=:VoteItemId" . $searchSql;
        $allCount = $this->dbOperator->GetInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 修改选项的加票数
     * @param int $voteSelectItemId  选项Id
     * @param int $addCount  选项加票数
     * @return int  执行结果
     */
    public function ModifyAddCount($voteSelectItemId,$addCount) {
        $sql = "UPDATE  " . self::TableName_VoteSelectItem . " SET AddCount=:AddCount WHERE State=0 AND VoteSelectItemId=:VoteSelectItemId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("VoteSelectItemId", $voteSelectItemId);
        $dataProperty->AddField("AddCount", $addCount);
        $result = $this->dbOperator->GetInt($sql, $dataProperty);
        return $result;
    }
}

?>
