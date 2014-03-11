<?php

/**
 * 投票调查数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Vote
 * @author hy
 */
class VoteManageData extends BaseManageData {

    /**
     * 新增投票调查
     * @param array $httpPostData $_post数组
     * @return int 投票调查Id
     */
    public function Create($httpPostData) {
        $dataProperty = new DataProperty();
        $sqlStr = parent::GetInsertSql($httpPostData,self::TableName_Vote, $dataProperty);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->LastInsertId($sqlStr, $dataProperty);
        return $result;
    }

    /**
     * 获取投票调查列表
     * @param int $state 状态
     * @return array 投票调查列表数组
     */
    public function GetList($state = -1) {
        if ($state == -1) {
            $sqlStr = "SELECT VoteTitle,State,CreateDate,BeginDate FROM " . self::TableName_Vote;
            $dbOperator = DBOperator::getInstance();
            $result = $dbOperator->ReturnArray($sqlStr, null);
        } else {
            $sqlStr = "SELECT VoteTitle,State,CreateDate,BeginDate FROM " . self::TableName_Vote . " WHERE State=:State";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("State", $state);
            $dbOperator = DBOperator::getInstance();
            $result = $dbOperator->ReturnArray($sqlStr, $dataProperty);
        }
        return $result;
    }

    /**
     * 一个投票调查的信息
     * @param int $voteId  投票调查Id
     * @return array 投票调查一维数组
     */
    public function GetOne($voteId) {
        $sqlStr = "SELECT VoteId,SiteId,DocumentChannelId,VoteTitle,State,CreateDate,BeginDate,EndDate,Sort,RecordCount,AddCount,IsCheckCode,IpMaxCount,UserMaxCount,UserScoreNum,TemplateName FROM " . self::TableName_Vote . " WHERE VoteId=:VoteId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("VoteId", $voteId);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->ReturnRow($sqlStr, $dataProperty);
        return $result;
    }

    /**
     * 获取投票调查分页列表
     * @param int $sId 站点Id
     * @param int $cId 频道Id
     * @param int $pageBegin 起始页码
     * @param int $pageSize  每页记录数
     * @param int $allCount  记录总数
     * @param string $searchKey 查询字符
     * @return array  投票调查列表数组
     */
    public function GetListPager($sId, $cId, $pageBegin, $pageSize, &$allCount, $searchKey = "") {
        $dataProperty = new DataProperty();
        $dataProperty->AddField("SiteId", $sId);
        $dataProperty->AddField("DocumentChannelId", $cId);
        $searchSqlStr = "WHERE";
        if (strlen($searchKey) > 0 && $searchKey != "undefined") {
            $searchSqlStr .= " SiteId=:SiteId AND DocumentChannelId=:DocumentChannelId AND (VoteTitle like :searchKey1) AND";
            $dataProperty->AddField("searchKey1", "%" . $searchKey . "%");
        }
        if (strlen($searchSqlStr) > 5)
            $searchSqlStr = substr($searchSqlStr, 0, strlen($searchSqlStr) - 3);
        else
            $searchSqlStr = "WHERE SiteId=:SiteId AND DocumentChannelId=:DocumentChannelId";

        $sqlStr = "SELECT VoteId,VoteTitle,State,CreateDate,BeginDate,EndDate,SiteId,DocumentChannelId FROM " . self::TableName_Vote . " " . $searchSqlStr . "  ORDER BY sort DESC,VoteId DESC LIMIT " . $pageBegin . "," . $pageSize . "";
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->ReturnArray($sqlStr, $dataProperty);
        $sqlStr = "SELECT count(*) FROM " . self::TableName_Vote . $searchSqlStr;
        $allCount = $dbOperator->ReturnInt($sqlStr, $dataProperty);
        return $result;
    }

    /**
     * 修改
     * @param array $httpPostData $_post数组
     * @param int $voteId 投票调查Id
     * @return int 执行结果
     */
    public function Modify($httpPostData,$voteId) {
        $dataProperty = new DataProperty();
        $sqlStr = parent::GetUpdateSql($httpPostData, self::TableName_Vote, self::TableId_Vote, $voteId, $dataProperty);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->Execute($sqlStr, $dataProperty);
        return $result;
    }

    /**
     * 停用投票调查
     * @param int $voteId 投票调查Id
     * @return int 执行结果
     */
    public function RemoveBin($voteId) {
        $sqlStr = "UPDATE " . self::TableName_Vote . " SET State=100 WHERE VoteId=:VoteId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("VoteId", $voteId);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->Execute($sqlStr, $dataProperty);
        return $result;
    }

    /**
     * 修改投票调查的加票数
     * @param int $voteId 投票调查Id
     * @param int $addCount 加票数
     * @return int 执行结果
     */
    public function ModifyAddCount($voteId, $addCount) {
        $sqlStr = "UPDATE " . self::TableName_Vote . " SET AddCount=:AddCount WHERE VoteId=:VoteId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("AddCount", $addCount);
        $dataProperty->AddField("VoteId", $voteId);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->Execute($sqlStr, $dataProperty);
        return $result;
    }

}

?>