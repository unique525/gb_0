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
     * 获取league数据集
     * @param $leagueId
     * @return array
     */
    public function GetAllListOfLeague(
        $leagueId)
    {
        $result = array();
        if ($leagueId > 0) {
            $sql = 'SELECT g.*,t.TeamName as TeamName,ma.MemberName as PersonName1,mb.MemberName as PersonName2 FROM ' . self::TableName_Goal . ' g
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
            $sql = 'SELECT * FROM ' . self::TableName_Goal . '
             WHERE MatchId=:MatchId AND State!='.MatchData::STATE_REMOVED .';';
            $dataProperty = new DataProperty();
            $dataProperty->AddField("MatchId", $matchId);
            $result=$this->dbOperator->GetArrayList($sql,$dataProperty);
        }
        return $result;
    }





}