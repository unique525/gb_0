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
        $sqlStr = "update " . self::TableName_VoteItem . " set RecordCount = RecordCount+1 where VoteItemId=:VoteItemId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("VoteItemId", $tableIdValue);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->Execute($sqlStr, $dataProperty);
        return $result;
    }

    /**
     * 批量更新题目票数
     * @param array $VoteItemIdArr    所有提交的题目数据集
     * @return int 返回执行结果
     */
    public function UpdateCountBatch($VoteItemIdArr) {
        foreach ($VoteItemIdArr as $value) {
            $VoteItemId = $value[0];  //题目ID
            $voteItemAllCount = $value[1];    //题目所属票数总和
            $dataProperty = new DataProperty();
            $strSql[] = "update " . self::TableName_VoteItem . " set RecordCount = RecordCount+1,recordallcount = recordallcount+:voteitemallcount where VoteItemId=:VoteItemId";
            $dataProperty->AddField("VoteItemId", $VoteItemId);
            $dataProperty->AddField("voteitemallcount", $voteItemAllCount);
            $dataPropertyarr[] = $dataProperty;
        }
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->ExecuteBatch($strSql, $dataPropertyarr);
        return $result;
    }

    /**
     * 根据voteId和state的值取得列表
     * @param int $id 问卷id
     * @param int $state  题目的启用状态
     * @return arr 返回查询到的state结果集
     */
    public function GetListByVoteID($voteId,$state = -1) {
        $dataProperty = new DataProperty();
        if ($state == -1) {
            $sqlStr = "SELECT VoteItemId,VoteId,VoteItemTitle,Sort,State,VoteItemType,SelectNumMin,SelectNumMax FROM " . self::tablename . " WHERE voteid=:voteid";
        } else {
            $sqlStr = "SELECT VoteItemId,VoteId,VoteItemTitle,Sort,State,VoteItemType,SelectNumMin,SelectNumMax FROM " . self::tablename . " WHERE voteid=:voteid and state=:state";
            $dataProperty->AddField("state", $state);
        }
        $dataProperty->AddField("voteid", $voteId);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->ReturnArray($sqlStr, $dataProperty);
        return $result;
    }


}

?>
