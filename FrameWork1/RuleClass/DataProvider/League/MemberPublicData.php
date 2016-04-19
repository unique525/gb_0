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
}