<?php
/**
 * Created by PhpStorm.
 * User: 525
 * Date: 2016/3/19
 * Time: 14:26
 */
class MemberPublicData extends BasePublicData
{



    /**
     * 获取列表数据集
     * @param $teamId
     * @param $state
     * @param $withCache
     * @return int
     */
    public function GetListOfTeam(
        $teamId,
        $state,
        $withCache = FALSE)
    {
        $result = array();
        if ($teamId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'member_data';
            $cacheFile = 'member_get_list_of_team.cache_' . $teamId .'';
            $sql = 'SELECT * FROM ' . self::TableName_Member . ' WHERE TeamId=:TeamId AND State='.$state .';';
            $dataProperty = new DataProperty();
            $dataProperty->AddField("TeamId", $teamId);
            $result = $this->GetInfoOfArrayList($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }





    public function GetListOfTeamInMatch($teamId,$matchId,$state=101,$withCache){

        $result=array();
        if($teamId>0&&$matchId>0){
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'member_data';
            $cacheFile = 'member_get_list_of_team_in_match.cache_t_' . $teamId .'_m_'.$matchId;
            $sql="SELECT " . " mom.*,m.MemberName,m.Number FROM ".self::TableName_MemberOfMatch." mom
            LEFT OUTER JOIN ".self::TableName_Member." m ON m.MemberId=mom.MemberId
            WHERE mom.MatchId=:MatchId AND mom.TeamId=:TeamId AND mom.State<:State ORDER BY mom.State,m.Number; ";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("TeamId", $teamId);
            $dataProperty->AddField("MatchId", $matchId);
            $dataProperty->AddField("State", $state);
            $result = $this->GetInfoOfArrayList($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);

        }
        return $result;
    }
}