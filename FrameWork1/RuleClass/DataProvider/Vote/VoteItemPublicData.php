<?php

/**
 * 投票调查题目前台数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Vote
 * @author yanjiuyuan
 */
class VoteItemPublicData extends BasePublicData {
    /**
     * 更新题目票数
     * @param int $tableIdValue  唯一ID号
     * @return int 返回执行结果
     */
    public function UpdateCount($tableIdValue) {
        $sqlStr = "update " . self::TableName_VoteItem . "
        set RecordCount = RecordCount+1
        where VoteItemId=:VoteItemId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("VoteItemId", $tableIdValue);
        $result = $this->dbOperator->Execute($sqlStr, $dataProperty);
        return $result;
    }

    /**
     * 批量更新题目票数
     * @param array $VoteItemIdArr    所有提交的题目数据集
     * @return int 返回执行结果
     */
    public function UpdateCountBatch($VoteItemIdArr) {
        $strSql=array();
        $dataPropertyArr=array();
        foreach ($VoteItemIdArr as $value) {
            $VoteItemId = $value[0];  //题目ID
            $voteItemAllCount = $value[1];    //题目所属票数总和
            $dataProperty = new DataProperty();
            $strSql[] = "UPDATE " . self::TableName_VoteItem . "
            SET RecordCount = RecordCount+1,ReCordAllCount = ReCordAllCount+:VoteItemAllCount
            WHERE VoteItemId=:VoteItemId";
            $dataProperty->AddField("VoteItemId", $VoteItemId);
            $dataProperty->AddField("VoteItemAllCount", $voteItemAllCount);
            $dataPropertyArr[] = $dataProperty;
        }

        $result = $this->dbOperator->ExecuteBatch($strSql, $dataPropertyArr);
        return $result;
    }

    /**
     * 根据投票调查id和状态的值取得题目列表
     * @param int $voteId 投票调查id
     * @param int $state  题目的启用状态
     * @return array 返回题目列表数组
     */
    public function GetListByVoteID($voteId,$state = -1) {
        $dataProperty = new DataProperty();
        if ($state == -1) {
            $sqlStr = "SELECT
            VoteItemId,VoteId,VoteItemTitle,Sort,State,VoteItemType,SelectNumMin,SelectNumMax
            FROM " . self::TableName_VoteItem . "
            WHERE VoteId=:VoteId";
        } else {
            $sqlStr = "SELECT
            VoteItemId,VoteId,VoteItemTitle,Sort,State,VoteItemType,SelectNumMin,SelectNumMax
            FROM " . self::TableName_VoteItem . "
            WHERE VoteId=:VoteId and state=:state";
            $dataProperty->AddField("state", $state);
        }
        $dataProperty->AddField("VoteId", $voteId);
        $result = $this->dbOperator->GetArray($sqlStr, $dataProperty);
        return $result;
    }


}

?>
