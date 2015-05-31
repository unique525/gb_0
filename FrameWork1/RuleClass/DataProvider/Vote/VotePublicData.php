<?php

/**
 * 投票调查前台数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Vote
 * @author yanjiuyuan
 */
class VotePublicData extends BasePublicData {

    /**
     * 更新投票票数
     * @param int $tableIdValue  唯一ID号
     * @return int  返回执行结果
     */
    public function UpdateCount($tableIdValue) {
        $sqlStr = "update " . self::TableName_Vote . " set RecordCount = RecordCount+1 where VoteId=:VoteId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("VoteId", $tableIdValue);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->Execute($sqlStr, $dataProperty);
        return $result;
    }

    /**
     * 查询启用题目和选项的ID、票数
     * @param int $VoteId  投票ID号
     * @return array 返回查询到的数据结果集
     */
    public function GetSelectItemList($VoteId) {
        $dataProperty = new DataProperty();
        $sqlStr = "select t2.VoteItemId,t2.VoteSelectItemId,t2.RecordCount as VoteSelectItemRecordCount,t.RecordCount as VoteRecordCount,t1.RecordAllCount as VoteItemRecordCount,t2.AddCount as VoteSelectItemAddCount,t1.AddCount as VoteItemAddCount
                    from " . self::TableName_Vote . " t inner join " . self::TableName_VoteItem . " t1 on cv.VoteId=cvi.VoteId inner join " . self::TableName_VoteSelectItem . " t2 on t1.VoteItemId=t2.VoteItemId
                    where t.VoteId=:VoteId and t1.State=0 and t2.State=0";
        $dataProperty->AddField("VoteId", $VoteId);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->ReturnArray($sqlStr, $dataProperty);
        return $result;
    }

    /**
     * 返回一行投票调查的数据
     * @param int $tableIdValue  唯一ID号
     * @return array 投票调查一维数组
     */
    public function GetVoteRow($tableIdValue) {
        $sqlStr = "SELECT VoteId,SiteId,DocumentChannelId,VoteTitle,State,CreateDate,BeginDate,EndDate,Sort,RecordCount,AddCount,IsCheckCode,IpMaxCount,UserMaxCount,UserScoreNum FROM " . self::TableName_Vote . " WHERE VoteId=:VoteId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("VoteId", $tableIdValue);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->ReturnRow($sqlStr, $dataProperty);
        return $result;
    }

}

?>
