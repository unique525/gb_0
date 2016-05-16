<?php
/**
 * Created by PhpStorm.
 * User: 525
 * Date: 2016/3/16
 * Time: 21:56
 */
class LeaguePublicGen extends BasePublicGen implements IBasePublicGen
{

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenPublic()
    {
        $result = "";
        $method = Control::GetRequest("a", "");
        switch ($method) {
            case "default":
                $result = self::GetPage();
                break;
        }
        return $result;
    }



    /**
     * 生成赛程列表页面
     */
    private function GetPage()
    {
        $channelId = Control::GetRequest("channel_id", 0);

        $leagueId = Control::GetRequest("league_id", 0);
        //$publishDate = Control::GetRequest("publish_date", "");

        $matchPublicData = new MatchPublicData();
        $leaguePublicData = new LeaguePublicData();



        $channelPublicData = new ChannelPublicData();
        $siteId = $channelPublicData->GetSiteId($channelId, true);
        $defaultTemp = "league_match_list";
        $temp=Control::GetRequest("temp",$defaultTemp);
        $templateContent = parent::GetDynamicTemplateContent(
            $temp,
            $siteId,
            "",
            $templateMode);

        /*******************页面级的缓存 begin********************** */


        $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'default_page';
        $cacheFile = 'league_default_site_id_' . $siteId .
            '_channel_id_'.$channelId.
            '_temp_'.$defaultTemp.
            '_lid_'.$leagueId.
            '_mode_' . $templateMode;
        if($temp==$defaultTemp){
            $withCache = true;
        }else{
            $withCache = false;
        }

        if($withCache){
            $pageCache = parent::GetCache($cacheDir, $cacheFile);

            if ($pageCache === false) {
                $result = self::GetTemplateContent(
                    $siteId,
                    $channelId,
                    $leagueId,
                    $templateContent);
                parent::AddCache($cacheDir, $cacheFile, $result, 60);
            } else {
                $result = $pageCache;
            }
        }else{
            $result = self::GetTemplateContent(
                $siteId,
                $channelId,
                $leagueId,
                $templateContent);
        }

        /*******************页面级的缓存 end  ********************** */

        parent::ReplaceUserInfoPanel($result, $siteId);

        return $result;
    }


    /**
     * 取得模板内容
     * @param $siteId
     * @param $channelId
     * @param $leagueId
     * @param $tempContent
     * @return string
     */
    private function GetTemplateContent(
        $siteId,
        $channelId,
        $leagueId,
        $tempContent
    )
    {
        $leaguePublicData = new LeaguePublicData();
        $arrayOneLeague=$leaguePublicData->GetOne($leagueId,false);
        Template::ReplaceOne($tempContent,$arrayOneLeague);


        $tempContent = str_ireplace("{ChannelId}", $channelId, $tempContent);
        parent::ReplaceFirst($tempContent);

        $channelPublicData = new ChannelPublicData();
        $currentChannelName = $channelPublicData->GetChannelName($channelId,true);
        $voteIdFromUrl = Control::GetRequest("vote_id","0");
        $tempContent = str_ireplace("{vote_id_from_url}", $voteIdFromUrl, $tempContent);  //替换icms投票标签的id（参数在url内get）
        $tempContent = str_ireplace("{CurrentChannelName}", $currentChannelName, $tempContent);
        $tempContent = str_ireplace("{ChannelName}", $currentChannelName, $tempContent);


        $tempContent =  parent::ReplaceTemplate($tempContent);

        parent::ReplaceSiteInfo($siteId, $tempContent);
        parent::ReplaceChannelInfo($channelId, $tempContent);
        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }

}