<?php
/**
 * Created by PhpStorm.
 * User: 525
 * Date: 2016/3/19
 * Time: 14:27
 */
class TeamPublicData extends BasePublicData
{



    /**
     * 获取列表数据集
     * @param $leagueId
     * @param $state
     * @param $withCache
     * @return int
     */
    public function GetListOfLeague(
        $leagueId,
        $state,
        $withCache = FALSE)
    {
        $result = array();
        if ($leagueId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'team_data';
            $cacheFile = 'team_get_list_of_league.cache_' . $leagueId .'';
            $sql = 'SELECT '.' tol.*,t.* FROM ' . self::TableName_TeamOfLeague . ' tol
             LEFT OUTER JOIN '.self::TableName_Team.' t ON t.TeamId=tol.TeamId
             WHERE tol.LeagueId=:LeagueId AND State='.$state.'  ORDER BY GroupName,Score DESC,(Goal-LoseGoal) DESC ;';
            $dataProperty = new DataProperty();
            $dataProperty->AddField("LeagueId", $leagueId);
            $result = $this->GetInfoOfArrayList($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }


    /**
     * 获取站点id
     * @param $teamId
     * @param bool $withCache
     * @return int
     */
    public function GetSiteId(
        $teamId,
        $withCache = FALSE)
    {
        $result = -1;
        if ($teamId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'team_data';
            $cacheFile = 'team_get_site_id.cache_team_id_' . $teamId .'';
            $sql = 'SELECT '.' SiteId FROM ' . self::TableName_Team . '
             WHERE TeamId=:TeamId;';
            $dataProperty = new DataProperty();
            $dataProperty->AddField("TeamId", $teamId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }


    /**
     * 获取一条记录
     * @param $teamId
     * @param bool $withCache
     * @return array
     */
    public function GetOne(
        $teamId,
        $withCache = FALSE)
    {
        $result = array();
        if ($teamId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'team_data';
            $cacheFile = 'team_get_one.cache_team_id_' . $teamId .'';
            $sql = 'SELECT * '.' FROM ' . self::TableName_Team . '
             WHERE TeamId=:TeamId;';
            $dataProperty = new DataProperty();
            $dataProperty->AddField("TeamId", $teamId);
            $result = $this->GetInfoOfArray($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }
}