<?php
/**
 * Created by PhpStorm.
 * User: 525
 * Date: 2016/3/19
 * Time: 14:26
 */
class OtherEventPublicData extends BasePublicData
{





    /**
     * 获取match数据集
     * @param $matchId
     * @param $stateMax
     * @param $withCache
     * @return array
     */
    public function GetAllListOfMatch(
        $matchId,
        $stateMax=100,
        $withCache = FALSE)
    {
        $result = array();
        if ($matchId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'other_event_data';
            $cacheFile = 'other_event_get_list_of_match.cache_' . $matchId .'';
            $sql = 'SELECT e.*,t.TeamName as TeamName,ma.MemberName as PrincipalMemberName,mb.MemberName as SecondaryMemberName FROM ' . self::TableName_OtherEvent . ' e
             LEFT OUTER JOIN '.self::TableName_Team.' t ON e.TeamId=t.TeamId
             LEFT OUTER JOIN '.self::TableName_Member.' ma ON e.PrincipalMemberId=ma.MemberId
             LEFT OUTER JOIN '.self::TableName_Member.' mb ON e.SecondaryMemberId=mb.MemberId
             WHERE e.MatchId=:MatchId AND e.State<'.$stateMax.';';
            $dataProperty = new DataProperty();
            $dataProperty->AddField("MatchId", $matchId);
            $result = $this->GetInfoOfArrayList($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }







}