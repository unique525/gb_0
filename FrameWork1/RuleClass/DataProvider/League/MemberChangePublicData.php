<?php
/**
 * Created by PhpStorm.
 * User: 525
 * Date: 2016/3/19
 * Time: 14:26
 */
class MemberChangePublicData extends BasePublicData
{





    /**
     * 获取match数据集
     * @param $matchId
     * @param $withCache
     * @return array
     */
    public function GetAllListOfMatch(
        $matchId,
        $withCache = FALSE)
    {
        $result = array();
        if ($matchId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'member_change_data';
            $cacheFile = 'member_change_get_list_of_match.cache_' . $matchId .'';
            $sql = 'SELECT mc.*,t.TeamName as TeamName,ma.MemberName as OffMemberName,mb.MemberName as AlternateMemberName FROM ' . self::TableName_MemberChange . ' mc
             LEFT OUTER JOIN '.self::TableName_Team.' t ON mc.TeamId=t.TeamId
             LEFT OUTER JOIN '.self::TableName_Member.' ma ON mc.OffMemberId=ma.MemberId
             LEFT OUTER JOIN '.self::TableName_Member.' mb ON mc.AlternateMemberId=mb.MemberId
             WHERE mc.MatchId=:MatchId AND mc.State!='.MatchData::STATE_REMOVED .';';
            $dataProperty = new DataProperty();
            $dataProperty->AddField("MatchId", $matchId);
            $result = $this->GetInfoOfArrayList($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }







}