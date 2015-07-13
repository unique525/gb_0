<?php

/**
 * 投票调查题目前台数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Vote
 * @author yanjiuyuan
 */
class VoteSelectItemPublicData extends BasePublicData {
    /**
     * 题目选项提交
     * @param int $voteSelectItemId  题目选项id
     * @return int  返回执行结果
     */
    public function UpdateCount($voteSelectItemId) {
        $sqlStr = "UPDATE " . self::TableName_VoteSelectItem . " SET RecordCount = RecordCount+1 WHERE VoteSelectItemId=:VoteSelectItemId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("VoteSelectItemId", $voteSelectItemId);
        $result = $this->dbOperator->Execute($sqlStr, $dataProperty);
        return $result;
    }

    /**
     * 根据题目选项id集合数组，对应的选项票数加1
     * @param array $voteSelectItemIdArr 投票选项id集合数组
     * @return int 返回执行结果
     */
    public function UpdateCountBatch($voteSelectItemIdArr) {
        $strSql=array();
        $dataPropertyArr=array();
        foreach ($voteSelectItemIdArr as $value) {
            $dataProperty = new DataProperty();
            $strSql[] = "update " . self::TableName_VoteSelectItem . " set RecordCount = RecordCount+1 where VoteSelectItemId=:VoteSelectItemId";
            $dataProperty->AddField("VoteSelectItemId", $value);
            $dataPropertyArr[] = $dataProperty;
        }
        $result = $this->dbOperator->ExecuteBatch($strSql, $dataPropertyArr);
        return $result;
    }


    /**
     * 根据投票调查题目id取得 该题目下票数前X条的数据
     * @param int $voteItemId 投票调查题目id
     * @return array 返回列表数组
     */
    public function GetTopList($voteItemId)
    {
        $result = null;
        if ($voteItemId > 0) {
            $sql = "SELECT * FROM " . self::TableName_VoteSelectItem . " WHERE VoteItemId=:VoteItemId ORDER BY RecordCount DESC";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("VoteItemId", $voteItemId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }
}

?>
