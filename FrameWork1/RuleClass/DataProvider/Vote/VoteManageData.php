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
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_Vote){
        return parent::GetFields(self::TableName_Vote);
    }

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
     * 异步修改状态
     * @param string $voteId 投票调查Id
     * @param string $state 状态
     * @return int 执行结果
     */
    public function ModifyState($voteId,$state)
    {
        $result = -1;
        if ($voteId < 0) {
            return $result;
        }
        $sql = "UPDATE " . self::TableName_Vote . " SET State=:State WHERE VoteId=:VoteId;";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("VoteId", $voteId);
        $dataProperty->AddField("State", $state);
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
        $sql = "SELECT VoteId,SiteId,ChannelId,VoteTitle,State,CreateDate,BeginDate,EndDate,Sort,RecordCount,AddCount,IsCheckCode,IpMaxCount,UserMaxCount,UserScoreNum,TemplateName,LimitUserGroupId FROM " . self::TableName_Vote . " WHERE VoteId=:VoteId;";
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
        if ($siteId < 0 || $channelId < 0) {
            return $result;
        }
        $dataProperty = new DataProperty();
        $dataProperty->AddField("SiteId", $siteId);
        $dataProperty->AddField("ChannelId", $channelId);
        $searchSql = "";
        if (strlen($searchKey) > 0 && $searchKey != "undefined") {
            $searchSql .= " AND (VoteTitle like :searchKey1) AND";
            $dataProperty->AddField("searchKey1", "%" . $searchKey . "%");
        }

        $sql = "
        SELECT VoteId,VoteTitle,State,CreateDate,BeginDate,EndDate,SiteId,ChannelId
        FROM " . self::TableName_Vote . "
        WHERE SiteId=:SiteId AND ChannelId=:ChannelId ". $searchSql . "
        ORDER BY Sort DESC,VoteId DESC LIMIT " . $pageBegin . "," . $pageSize . ";";
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        $sql = "
        SELECT COUNT(*) FROM " . self::TableName_Vote . "
        WHERE SiteId=:SiteId AND ChannelId=:ChannelId ". $searchSql . ";";
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
        $sql = "UPDATE " . self::TableName_Vote . " SET AddCount=:AddCount WHERE VoteId=:VoteId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("AddCount", $addCount);
        $dataProperty->AddField("VoteId", $voteId);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
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
     * 根据投票调查id取得频道id
     * @param int $voteId 投票调查id
     * @param bool $withCache 是否从缓冲中取
     * @return string 模板名称
     */
    public function GetChannelId($voteId, $withCache)
    {
        $result = -1;
        if ($voteId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'vote_data';
            $cacheFile = 'vote_get_channel_id.cache_' . $voteId . '';
            $sql = "SELECT ChannelId FROM " . self::TableName_Vote . " WHERE VoteId=:VoteId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("VoteId", $voteId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 根据投票调查id取得管理员id
     * @param int $voteId 投票调查id
     * @param bool $withCache 是否从缓冲中取
     * @return string 模板名称
     */
    public function GetManageUserId($voteId, $withCache)
    {
        $result = -1;
        if ($voteId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'vote_data';
            $cacheFile = 'vote_get_channel_id.cache_' . $voteId . '';
            $sql = "SELECT ManageUserId FROM " . self::TableName_Vote . " WHERE VoteId=:VoteId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("VoteId", $voteId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }
}

?>