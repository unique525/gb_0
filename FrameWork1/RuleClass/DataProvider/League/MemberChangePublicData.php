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
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'member_change_data';
            $cacheFile = 'member_change_get_list_of_match.cache_' . $matchId .'';
            //number为暂时解决方案，number对应member不一定固定，之后member of match完善后转到彼表取number
            $sql = 'SELECT mc.*,
            t.TeamName as TeamName,
            ma.MemberName as OffMemberName,
            ma.Number as OffMemberNumber,
            mb.MemberName as AlternateMemberName,
            mb.Number as AlternateMemberNumber
             FROM ' . self::TableName_MemberChange . ' mc
             LEFT OUTER JOIN '.self::TableName_Team.' t ON mc.TeamId=t.TeamId
             LEFT OUTER JOIN '.self::TableName_Member.' ma ON mc.OffMemberId=ma.MemberId
             LEFT OUTER JOIN '.self::TableName_Member.' mb ON mc.AlternateMemberId=mb.MemberId
             WHERE mc.MatchId=:MatchId AND mc.State<'.$stateMax.';';
            $dataProperty = new DataProperty();
            $dataProperty->AddField("MatchId", $matchId);
            $result = $this->GetInfoOfArrayList($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }







}