<?php
/**
 * Created by PhpStorm.
 * User: 525
 * Date: 2016/3/19
 * Time: 14:26
 */
class GoalPublicData extends BasePublicData
{



    /**
     * 获取league数据集
     * @param $leagueId
     * @param $withCache
     * @return array
     */
    public function GetAllListOfLeague(
        $leagueId,
        $withCache = FALSE)
    {
        $result = array();
        if ($leagueId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'goal_data';
            $cacheFile = 'goal_get_list_of_league.cache_' . $leagueId .'';
            $sql = 'SELECT g.*,t.TeamName as TeamName,ma.MemberName as PersonName1,mb.MemberName as PersonName2 FROM ' . self::TableName_Goal . ' g
             LEFT OUTER JOIN '.self::TableName_Team.' t ON g.TeamId=t.TeamId
             LEFT OUTER JOIN '.self::TableName_Member.' ma ON g.MemberId=ma.MemberId
             LEFT OUTER JOIN '.self::TableName_Member.' mb ON g.AssistorId=mb.MemberId
             WHERE g.LeagueId=:LeagueId AND g.State!='.MatchData::STATE_REMOVED .';';
            $dataProperty = new DataProperty();
            $dataProperty->AddField("LeagueId", $leagueId);
            $result = $this->GetInfoOfArrayList($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }


    /**
     * 获取match数据集
     * @param $matchId
     * @param $stateMax
     * @param $withCache
     * @return array
     */
    public function GetAllListOfMatch(
        $matchId,
        $stateMax = 100,
        $withCache = FALSE)
    {
        $result = array();
        if ($matchId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'goal_data';
            $cacheFile = 'goal_get_list_of_match.cache_' . $matchId .'';
            //number为暂时解决方案，number对应member不一定固定，之后member of match完善后转到彼表取number
            $sql = 'SELECT g.*,t.TeamName as TeamName,ma.MemberName as MemberName,ma.Number as MemberNumber,mb.MemberName as AssistorName,mb.Number as AssistorNumber FROM ' . self::TableName_Goal . ' g
             LEFT OUTER JOIN '.self::TableName_Team.' t ON g.TeamId=t.TeamId
             LEFT OUTER JOIN '.self::TableName_Member.' ma ON g.MemberId=ma.MemberId
             LEFT OUTER JOIN '.self::TableName_Member.' mb ON g.AssistorId=mb.MemberId
             WHERE g.MatchId=:MatchId AND g.State<'.$stateMax .';';
            $dataProperty = new DataProperty();
            $dataProperty->AddField("MatchId", $matchId);
            $result = $this->GetInfoOfArrayList($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }







}