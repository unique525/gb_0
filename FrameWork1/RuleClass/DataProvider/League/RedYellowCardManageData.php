<?php
/**
 * Created by PhpStorm.
 * User: a525
 * Date: 16-4-22
 * Time: 上午11:43
 */
class RedYellowCardManageData extends BaseManageData
{


    /**
     * 新增
     * @param array $httpPostData $_post数组
     * @param int $manageUserId
     * @return int 新增id
     */
    public function Create($httpPostData,$manageUserId) {
        $result=-1;
        $dataProperty = new DataProperty();
        $addFieldName = "ManageUserId";
        $addFieldValue = $manageUserId;
        $preNumber = "";
        $addFieldNames = array();
        $addFieldValues = array();
        if (!empty($httpPostData)) {
            $sql = parent::GetInsertSql($httpPostData,self::TableName_RedYellowCard, $dataProperty, $addFieldName, $addFieldValue, $preNumber, $addFieldNames, $addFieldValues);
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        }
        return $result;
    }



    /**
     * 修改
     * @param array $httpPostData $_POST数组
     * @param int $redYellowCardId 频道id
     * @param int $manageUserId
     * @return int 返回影响的行数
     */
    public function Modify($httpPostData, $redYellowCardId,$manageUserId)
    {
        $result = -1;
        $dataProperty = new DataProperty();
        $addFieldName = "ManageUserId";
        $addFieldValue = $manageUserId;
        $preNumber = "";
        $addFieldNames = array();
        $addFieldValues = array();

        if (!empty($httpPostData)) {
            $sql = parent::GetUpdateSql($httpPostData, self::TableName_RedYellowCard, self::TableId_RedYellowCard, $redYellowCardId, $dataProperty, $addFieldName, $addFieldValue, $preNumber, $addFieldNames, $addFieldValues);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 获取league数据集
     * @param $leagueId
     * @return array
     */
    public function GetAllListOfLeague(
        $leagueId)
    {
        $result = array();
        if ($leagueId > 0) {
            $sql = 'SELECT '.' g.*,t.TeamName as TeamName,ma.MemberName as MemberName FROM ' . self::TableName_RedYellowCard . ' g
             LEFT OUTER JOIN '.self::TableName_Team.' t ON g.TeamId=t.TeamId
             LEFT OUTER JOIN '.self::TableName_Member.' ma ON g.MemberId=ma.MemberId
             WHERE g.LeagueId=:LeagueId AND g.State!='.MatchData::STATE_REMOVED .';';
            $dataProperty = new DataProperty();
            $dataProperty->AddField("LeagueId", $leagueId);
            $result=$this->dbOperator->GetArrayList($sql,$dataProperty);
        }
        return $result;
    }


    /**
     * 获取match数据集
     * @param $matchId
     * @return array
     */
    public function GetAllListOfMatch(
        $matchId)
    {
        $result = array();
        if ($matchId > 0) {
            $sql = 'SELECT '.' * FROM ' . self::TableName_RedYellowCard . '
             WHERE MatchId=:MatchId AND State!='.MatchData::STATE_REMOVED .';';
            $dataProperty = new DataProperty();
            $dataProperty->AddField("MatchId", $matchId);
            $result=$this->dbOperator->GetArrayList($sql,$dataProperty);
        }
        return $result;
    }

    /**
     * 返回一行数据
     * @param int $redYellowCardId id
     * @return array|null 取得对应数组
     */
    public function GetOne($redYellowCardId)
    {
        $result = null;
        if ($redYellowCardId > 0) {
            $sql = "SELECT "." * FROM " . self::TableName_RedYellowCard . " WHERE RedYellowCardId=:RedYellowCardId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("RedYellowCardId", $redYellowCardId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 取得比赛id
     * @param int $redYellowCardId 频道id
     * @param bool $withCache 是否从缓冲中取
     * @return int 站点id
     */
    public function GetMatchId($redYellowCardId, $withCache)
    {
        $result = -1;
        if ($redYellowCardId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'red_yellow_card_data';
            $cacheFile = 'red_yellow_card_get_match_id.cache_' . $redYellowCardId . '';
            $sql = "SELECT "." MatchId FROM " . self::TableName_RedYellowCard . " WHERE RedYellowCardId=:RedYellowCardId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("RedYellowCardId", $redYellowCardId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }


    /**
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_RedYellowCard){
        return parent::GetFields(self::TableName_RedYellowCard);
    }




}