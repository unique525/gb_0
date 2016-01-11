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
        $result = $this->dbOperator->Execute($sqlStr, $dataProperty);
        return $result;
    }

    /**
     * 查询启用题目和选项的ID、票数
     * @param int $voteId  投票ID号
     * @param string $beginDate  开始时间
     * @param string $endDate  结束时间
     * @return array 返回查询到的数据结果集
     */
    public function GetSelectItemList($voteId,$beginDate="",$endDate="") {

        if($beginDate!=""&&$endDate!=""){
            $strSelectDate=" AND PublishDate>='$beginDate' AND PublishDate<='$endDate' ";
        }else{
            $strSelectDate="";
        }

        $dataProperty = new DataProperty();
        $sqlStr = "select t2.VoteItemId,t2.VoteSelectItemId,
        t2.RecordCount as VoteSelectItemRecordCount,
        t.RecordCount as VoteRecordCount,
        t1.RecordAllCount as VoteItemRecordAllCount,
        t2.AddCount as VoteSelectItemAddCount,
        t1.AddCount as VoteItemAddCount
        from " . self::TableName_Vote . " t inner join " . self::TableName_VoteItem . " t1
        on t.VoteId=t1.VoteId inner join " . self::TableName_VoteSelectItem . " t2
        on t1.VoteItemId=t2.VoteItemId
        where t.VoteId=:VoteId and t1.State=0 and t2.State=0 $strSelectDate";
        $dataProperty->AddField("VoteId", $voteId);
        $result = $this->dbOperator->GetArrayList($sqlStr, $dataProperty);
        return $result;
    }

    /**
     * 返回一行投票调查的数据
     * @param int $tableIdValue  唯一ID号
     * @return array 投票调查一维数组
     */
    public function GetVoteRow($tableIdValue) {
        $sqlStr = "SELECT VoteId,SiteId,ChannelId,VoteTitle,State,CreateDate,BeginDate,EndDate,Sort,RecordCount,AddCount,IsCheckCode,IpMaxCount,UserMaxCount,UserScoreNum
        FROM " . self::TableName_Vote . "
        WHERE VoteId=:VoteId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("VoteId", $tableIdValue);
        $result = $this->dbOperator->GetArray($sqlStr, $dataProperty);
        return $result;
    }

    /**
     * 根据投票调查id取得投票调查允许的每ip最大投票数
     * @param int $voteId 投票调查id
     * @param bool $withCache 是否从缓冲中取
     * @return string 每ip最大投票数
     */
    public function GetIpMaxCount($voteId, $withCache)
    {
        $result = -1;
        if ($voteId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'vote_data';
            $cacheFile = 'vote_get_ip_max_count.cache_' . $voteId . '';
            $sql = "SELECT IpMaxCount FROM " . self::TableName_Vote . " WHERE VoteId=:VoteId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("VoteId", $voteId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 根据投票调查id取得投票模板名称
     * @param int $voteId 投票调查id
     * @param bool $withCache 是否从缓冲中取
     * @return string 模板名称
     */
    public function GetTemplateName($voteId, $withCache)
    {
        $result = -1;
        if ($voteId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'vote_data';
            $cacheFile = 'vote_get_template_name.cache_' . $voteId . '';
            $sql = "SELECT TemplateName FROM " . self::TableName_Vote . " WHERE VoteId=:VoteId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("VoteId", $voteId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 根据投票调查id取得投票是否启用验证码标志位
     * @param int $voteId 投票调查id
     * @param bool $withCache 是否从缓冲中取
     * @return string 模板名称
     */
    public function GetIsCheckCode($voteId, $withCache)
    {
        $result = -1;
        if ($voteId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'vote_data';
            $cacheFile = 'vote_get_is_check_code.cache_' . $voteId . '';
            $sql = "SELECT IsCheckCode FROM " . self::TableName_Vote . " WHERE VoteId=:VoteId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("VoteId", $voteId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 根据投票调查id取得投票状态
     * @param int $voteId 投票调查id
     * @param bool $withCache 是否从缓冲中取
     * @return string 模板名称
     */
    public function GetState($voteId, $withCache)
    {
        $result = -1;
        if ($voteId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'vote_data';
            $cacheFile = 'vote_get_state.cache_' . $voteId . '';
            $sql = "SELECT State FROM " . self::TableName_Vote . " WHERE VoteId=:VoteId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("VoteId", $voteId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 获取组id
     * @param int $voteId
     * @param bool $withCache
     * @return int 返回修改结果
     */
    public function GetLimitUserGroupId($voteId, $withCache)
    {
        $result = -1;
        if($voteId > 0){
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'user_data'
                . DIRECTORY_SEPARATOR .$voteId;
            $cacheFile = 'user_get_user_group_id.cache_' . $voteId . '';
            $sql = "SELECT LimitUserGroupId FROM " . self::TableName_Vote . " where VoteId = :VoteId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("VoteId", $voteId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }


    /**
     * 获取组投票标题
     * @param int $voteId
     * @param bool $withCache
     * @return int
     */
    public function GetVoteTitle($voteId, $withCache)
    {
        $result = -1;
        if($voteId > 0){
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'vote_data'
                . DIRECTORY_SEPARATOR .$voteId;
            $cacheFile = 'vote_get_vote_title.cache_' . $voteId . '';
            $sql = "SELECT VoteTitle FROM " . self::TableName_Vote . " where VoteId = :VoteId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("VoteId", $voteId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }
}

?>
