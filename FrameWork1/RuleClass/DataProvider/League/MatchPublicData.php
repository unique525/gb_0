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
            $sql = 'SELECT m.*,ta.TeamName as HomeTeamName,tb.TeamName as GuestTeamName FROM ' . self::TableName_Match . ' m
             LEFT OUTER JOIN '.self::TableName_Team.' ta ON ta.TeamId=m.HomeTeamId
             LEFT OUTER JOIN '.self::TableName_Team.' tb ON tb.TeamId=m.GuestTeamId
             WHERE m.LeagueId=:LeagueId AND m.State!='.MatchData::STATE_REMOVED .';';
            $dataProperty = new DataProperty();
            $dataProperty->AddField("LeagueId", $leagueId);
            $result = $this->GetInfoOfArrayList($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }
}