<?php
/**
 * Created by PhpStorm.
 * User: 525
 * Date: 2016/3/19
 * Time: 14:26
 */
class MatchPublicData extends BasePublicData
{



    /**
     * 获取列表数据集
     * @param $leagueId
     * @param $withCache
     * @return int
     */
    public function GetAllListOfLeague(
        $leagueId,
        $withCache = FALSE)
    {
        $result = array();
        if ($leagueId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'match_data';
            $cacheFile = 'match_get_all_list_of_league.cache_' . $leagueId .'';
            $sql = 'SELECT '.' m.*,s.StadiumName,ta.TeamName as HomeTeamName,tb.TeamName as GuestTeamName,
                ta.TeamShortName as HomeTeamShortName,tb.TeamShortName as GuestTeamShortName
                FROM ' . self::TableName_Match . ' m
             LEFT OUTER JOIN '.self::TableName_Team.' ta ON ta.TeamId=m.HomeTeamId
             LEFT OUTER JOIN '.self::TableName_Team.' tb ON tb.TeamId=m.GuestTeamId
             LEFT OUTER JOIN '.self::TableName_Stadium .' s ON s.StadiumId=m.StadiumId
             WHERE m.LeagueId=:LeagueId AND m.State!='.MatchData::STATE_REMOVED .' ORDER BY m.BeginDate,m.BeginTime,m.MatchId;';
            $dataProperty = new DataProperty();
            $dataProperty->AddField("LeagueId", $leagueId);
            $result = $this->GetInfoOfArrayList($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }



    /**
     * 取得league id
     * @param int $matchId
     * @param bool $withCache 是否从缓冲中取
     * @return int 站点id
     */
    public function GetLeagueId($matchId, $withCache)
    {
        $result = -1;
        if ($matchId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'match_data';
            $cacheFile = 'match_get_league_id.cache_' . $matchId . '';
            $sql = "SELECT LeagueId FROM " . self::TableName_Channel . " WHERE MatchId = :MatchId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("MatchId", $matchId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }


    /**
     * 获取单个赛
     * @param $matchId
     * @param bool $withCache
     * @return array
     */
    public function GetOne($matchId,$withCache=false){
        $result=array();
        if($matchId>0){
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'match_data';
            $cacheFile = 'match_get_one.cache_' . $matchId .'';
            $sql = 'SELECT m.*,s.StadiumName,t1.TeamName as HomeTeamName,t2.TeamName as GuestTeamName,
                t1.TeamShortName as HomeTeamShortName,t2.TeamShortName as GuestTeamShortName
             '.'  FROM ' . self::TableName_Match . ' m
            LEFT OUTER JOIN ' . self::TableName_Team . ' t1 ON m.HomeTeamId=t1.TeamId
            LEFT OUTER JOIN ' . self::TableName_Team . ' t2 ON m.GuestTeamId=t2.TeamId
             LEFT OUTER JOIN '.self::TableName_Stadium .' s ON s.StadiumId=m.StadiumId
             WHERE MatchId=:MatchId;';
            $dataProperty = new DataProperty();
            $dataProperty->AddField("MatchId", $matchId);
            $result = $this->GetInfoOfArray($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }



}