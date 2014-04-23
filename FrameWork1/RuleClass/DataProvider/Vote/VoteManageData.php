<?php

/**
 * 投票调查数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Vote
 * @author hy
 */
class VoteManageData extends BaseManageData
{

    /**
     * 新增投票调查
     * @param array $httpPostData $_post数组
     * @return int 投票调查Id
     */
    public function Create($httpPostData)
    {
        $dataProperty = new DataProperty();
        $sql = parent::GetInsertSql($httpPostData, self::TableName_Vote, $dataProperty);
        $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        return $result;
    }

    /**
     * 停用投票调查
     * @param int $voteId 投票调查Id
     * @return int 执行结果
     */
    public function RemoveToBin($voteId)
    {
        $result = -1;
        if ($voteId < 0) {
            return $result;
        }
        $sql = "UPDATE " . self::TableName_Vote . " SET State=100 WHERE VoteId=:VoteId;";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("VoteId", $voteId);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 修改投票调查
     * @param array $httpPostData $_post数组
     * @param int $voteId 投票调查Id
     * @return int 执行结果
     */
    public function Modify($httpPostData, $voteId)
    {
        $result = -1;
        if ($voteId < 0) {
            return $result;
        }
        $dataProperty = new DataProperty();
        $sql = parent::GetUpdateSql($httpPostData, self::TableName_Vote, self::TableId_Vote, $voteId, $dataProperty);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

//    /**
//     * 获取投票调查列表11
//     * @param int $state 状态
//     * @return array 投票调查列表数组
//     */
//    public function GetList($state = -1) {
//        if ($state == -1) {
//            $sql = "SELECT VoteTitle,State,CreateDate,BeginDate FROM " . self::TableName_Vote . ";";
//            $result = $this->dbOperator->GetArrayList($sql, null);
//        } else {
//            $sql = "SELECT VoteTitle,State,CreateDate,BeginDate FROM " . self::TableName_Vote . " WHERE State=:State;";
//            $dataProperty = new DataProperty();
//            $dataProperty->AddField("State", $state);
//            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
//        }
//        return $result;
//    }

    /**
     * 一个投票调查的信息
     * @param int $voteId 投票调查Id
     * @return array 投票调查一维数组
     */
    public function GetOne($voteId)
    {
        $result = null;
        if ($voteId < 0) {
            return $result;
        }
        $sql = "SELECT VoteId,SiteId,ChannelId,VoteTitle,State,CreateDate,BeginDate,EndDate,Sort,RecordCount,AddCount,IsCheckCode,IpMaxCount,UserMaxCount,UserScoreNum,TemplateName FROM " . self::TableName_Vote . " WHERE VoteId=:VoteId;";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("VoteId", $voteId);
        $result = $this->dbOperator->GetArray($sql, $dataProperty);
        return $result;
    }

    /**
     * 获取投票调查分页列表
     * @param int $siteId 站点Id
     * @param int $channelId 频道Id
     * @param int $pageBegin 起始页码
     * @param int $pageSize 每页记录数
     * @param int $allCount 记录总数
     * @param string $searchKey 查询字符
     * @return array  投票调查列表数组
     */
    public function GetListForPager($siteId, $channelId, $pageBegin, $pageSize, &$allCount, $searchKey = "")
    {
        $result = null;
        if ($siteId < 0 || $channelId < 0 || $pageBegin < 0 || $pageSize < 0) {
            return $result;
        }
        $dataProperty = new DataProperty();
        $dataProperty->AddField("SiteId", $siteId);
        $dataProperty->AddField("ChannelId", $channelId);
        $searchSql = "WHERE";
        if (strlen($searchKey) > 0 && $searchKey != "undefined") {
            $searchSql .= " SiteId=:SiteId AND ChannelId=:ChannelId AND (VoteTitle like :searchKey1) AND";
            $dataProperty->AddField("searchKey1", "%" . $searchKey . "%");
        }
        if (strlen($searchSql) > 5)
            $searchSql = substr($searchSql, 0, strlen($searchSql) - 3);
        else
            $searchSql = "WHERE SiteId=:SiteId AND ChannelId=:ChannelId";

        $sql = "SELECT VoteId,VoteTitle,State,CreateDate,BeginDate,EndDate,SiteId,ChannelId FROM " . self::TableName_Vote . " " . $searchSql . "  ORDER BY sort DESC,VoteId DESC LIMIT " . $pageBegin . "," . $pageSize . ";";
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        $sql = "SELECT count(*) FROM " . self::TableName_Vote . $searchSql . ";";
        $allCount = $this->dbOperator->GetInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 修改投票调查的加票数
     * @param int $voteId 投票调查Id
     * @param int $addCount 加票数
     * @return int 执行结果
     */
    public function ModifyAddCount($voteId, $addCount)
    {
        $result = -1;
        if ($voteId < 0) {
            return $result;
        }
        $sql = "UPDATE " . self::TableName_Vote . " SET AddCount=:AddCount WHERE VoteId=:VoteId;";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("AddCount", $addCount);
        $dataProperty->AddField("VoteId", $voteId);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

}

?>