<?php
/**
 * Created by PhpStorm.
 * User: a525
 * Date: 16-4-22
 * Time: 上午11:08
 */
class GoalManageData extends BaseManageData
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
            $sql = parent::GetInsertSql($httpPostData,self::TableName_Goal, $dataProperty, $addFieldName, $addFieldValue, $preNumber, $addFieldNames, $addFieldValues);
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        }
        return $result;
    }



    /**
     * 修改
     * @param array $httpPostData $_POST数组
     * @param int $goalId 频道id
     * @param int $manageUserId
     * @return int 返回影响的行数
     */
    public function Modify($httpPostData, $goalId,$manageUserId)
    {
        $result = -1;
        $dataProperty = new DataProperty();
        $addFieldName = "ManageUserId";
        $addFieldValue = $manageUserId;
        $preNumber = "";
        $addFieldNames = array();
        $addFieldValues = array();

        if (!empty($httpPostData)) {
            $sql = parent::GetUpdateSql($httpPostData, self::TableName_Goal, self::TableId_Goal, $goalId, $dataProperty, $addFieldName, $addFieldValue, $preNumber, $addFieldNames, $addFieldValues);
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
            $sql = 'SELECT '.' g.*,t.TeamName as TeamName,ma.MemberName as PersonName1,mb.MemberName as PersonName2 FROM ' . self::TableName_Goal . ' g
             LEFT OUTER JOIN '.self::TableName_Team.' t ON g.TeamId=t.TeamId
             LEFT OUTER JOIN '.self::TableName_Member.' ma ON g.MemberId=ma.MemberId
             LEFT OUTER JOIN '.self::TableName_Member.' mb ON g.AssistorId=mb.MemberId
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
            $sql = 'SELECT '.' * FROM ' . self::TableName_Goal . '
             WHERE MatchId=:MatchId AND State!='.MatchData::STATE_REMOVED .';';
            $dataProperty = new DataProperty();
            $dataProperty->AddField("MatchId", $matchId);
            $result=$this->dbOperator->GetArrayList($sql,$dataProperty);
        }
        return $result;
    }

    /**
     * 返回一行数据
     * @param int $goalId id
     * @return array|null 取得对应数组
     */
    public function GetOne($goalId)
    {
        $result = null;
        if ($goalId > 0) {
            $sql = "SELECT "." * FROM " . self::TableName_Goal . " WHERE GoalId=:GoalId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("GoalId", $goalId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 取得比赛id
     * @param int $goalId 频道id
     * @param bool $withCache 是否从缓冲中取
     * @return int 站点id
     */
    public function GetMatchId($goalId, $withCache)
    {
        $result = -1;
        if ($goalId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'goal_data';
            $cacheFile = 'goal_get_match_id.cache_' . $goalId . '';
            $sql = "SELECT "." MatchId FROM " . self::TableName_Goal . " WHERE GoalId=:GoalId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("GoalId", $goalId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }


    public function GetRankOfMemberInLeague($leagueId,$topCount=1000,$withCache=false){
        $result=array();
        if($leagueId>0){
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'goal_data';
            $cacheFile = 'goal_get_rank_of_member_in_league_id.cache_league_' . $leagueId . '';
            $sql = 'select '.' g.*,COUNT(g.GoalId) as Count,m.MemberName,m.Number,t.TeamName,t.TeamShortName from '.self::TableName_Goal.' g
             LEFT OUTER JOIN '.self::TableName_Member.' m ON m.MemberId=g.MemberId
             LEFT OUTER JOIN '.self::TableName_Team.' t ON t.TeamId=g.TeamId
             WHERE g.LeagueId=:LeagueId AND g.State<100 AND g.type<9
            GROUP BY g.MemberId ORDER BY Count DESC LIMIT '.$topCount.' ;';
            $dataProperty = new DataProperty();
            $dataProperty->AddField("LeagueId", $leagueId);
            $result = $this->GetInfoOfArrayList($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }


    /**
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_Goal){
        return parent::GetFields(self::TableName_Goal);
    }




}