<?php
/**
 * 前台 赛事 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_League
 * @author 525
 */
class LeaguePublicData extends BasePublicData
{
    /**
     * 获取列表数据集
     * @param $channelId
     * @param $withCache
     * @return int
     */
    public function GetAllListOfChannel(
        $channelId,
        $withCache = FALSE)
    {
        $result = array();
        if ($channelId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'league_data';
            $cacheFile = 'league_get_all_list_of_channel.cache_' . $channelId .'';
            $sql = 'SELECT * '.'  FROM ' . self::TableName_League . '
             WHERE ChannelId=:ChannelId AND State!='.LeagueData::STATE_REMOVED .';';
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);
            $result = $this->GetInfoOfArrayList($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }


    /**
     * 获取单个赛事
     * @param $leagueId
     * @param bool $withCache
     * @return array
     */
    public function GetOne($leagueId,$withCache=false){
        $result=array();
        if($leagueId>0){
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'league_data';
            $cacheFile = 'league_get_one.cache_' . $leagueId .'';
            $sql = 'SELECT * '.'  FROM ' . self::TableName_League . '
             WHERE LeagueId=:LeagueId;';
            $dataProperty = new DataProperty();
            $dataProperty->AddField("LeagueId", $leagueId);
            $result = $this->GetInfoOfArray($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }




    /**
     * 取得站点id
     * @param int $leagueId
     * @param bool $withCache 是否从缓冲中取
     * @return int 站点id
     */
    public function GetSiteId($leagueId, $withCache)
    {
        $result = -1;
        if ($leagueId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'league_data';
            $cacheFile = 'league_get_site_id.cache_' . $leagueId . '';
            $sql = "SELECT SiteId FROM " . self::TableName_Channel . " WHERE LeagueId = :LeagueId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("LeagueId", $leagueId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }
}