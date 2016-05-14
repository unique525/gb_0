<?php
/**
 * Created by PhpStorm.
 * User: 525
 * Date: 2016/3/16
 * Time: 21:56
 */
class MatchPublicGen extends BasePublicGen implements IBasePublicGen
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
            case "m_get_one":
                $result = self::MobileGetOne();
                break;
        }
        return $result;
    }



    /**
     * 生成赛程列表页面
     */
    private function GetPage()
    {
        $result="";
        $channelId = Control::GetRequest("channel_id", 0);

        $leagueId = Control::GetRequest("league_id", 0);
        //$publishDate = Control::GetRequest("publish_date", "");

        if($leagueId>0){

        $leaguePublicData = new LeaguePublicData();
        $siteId = $leaguePublicData->GetSiteId($leagueId, true);
        $defaultTemp = "league_match_list";
        $temp=Control::GetRequest("temp",$defaultTemp);
        $templateContent = parent::GetDynamicTemplateContent(
            $temp,
            $siteId,
            "",
            $templateMode);

        /*******************页面级的缓存 begin********************** */


        $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'default_page';
        $cacheFile = 'match_list_of_league_site_id_' . $siteId .
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

            parent::ReplaceVisitCode($result,$siteId,$leagueId,VisitData::VISIT_TABLE_TYPE_LEAGUE,$leagueId);
        }
        return $result;
    }


    /**
     * 取得模板内容
     * @param $siteId
     * @param $matchId
     * @param $leagueId
     * @param $tempContent
     * @return string
     */
    private function GetTemplateContent(
        $siteId,
        $matchId,
        $leagueId,
        $tempContent
    )
    {
        $leaguePublicData = new LeaguePublicData();
        $arrayOneLeague=$leaguePublicData->GetOne($leagueId,false);
        Template::ReplaceOne($tempContent,$arrayOneLeague);

        if($matchId>0){
            $matchPublicData = new MatchPublicData();
            $arrayOneMatch=$matchPublicData->GetOne($matchId,false);
            Template::ReplaceOne($tempContent,$arrayOneMatch);
        }

        $tempContent = str_ireplace("{ChannelId}", $matchId, $tempContent);
        parent::ReplaceFirst($tempContent);

        $channelPublicData = new ChannelPublicData();
        $currentChannelName = $channelPublicData->GetChannelName($matchId,true);
        $voteIdFromUrl = Control::GetRequest("vote_id","0");
        $tempContent = str_ireplace("{vote_id_from_url}", $voteIdFromUrl, $tempContent);  //替换icms投票标签的id（参数在url内get）
        $tempContent = str_ireplace("{CurrentChannelName}", $currentChannelName, $tempContent);
        $tempContent = str_ireplace("{ChannelName}", $currentChannelName, $tempContent);


        $tempContent =  parent::ReplaceTemplate($tempContent);






        //去掉r开头的标记 {f_xxx_xxx}
        $patterns = '/\{f_(.*?)\}/';
        $tempContent = preg_replace($patterns, "", $tempContent);
        parent::ReplaceSiteInfo($siteId, $tempContent);
        parent::ReplaceChannelInfo($matchId, $tempContent);
        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }


    private function MobileGetOne(){
        $matchId=Control::GetRequest("match_id",0);
        $result="";
        if($matchId>0){

            $leaguePublicData=new LeaguePublicData();
            $matchPublicData=new MatchPublicData();
            $leagueId=$matchPublicData->GetLeagueId($matchId,true);
            $siteId=$leaguePublicData->GetSiteId($leagueId,true);






            $defaultTemp="match_detail";
            $temp=Control::GetRequest("temp",$defaultTemp);
            if($temp=="default"){
                $temp=$defaultTemp;
            }
            $templateContent = parent::GetDynamicTemplateContent(
                $temp,
                $siteId,
                "",
                $templateMode);



            //是否是管理员
            $isManage=0;
            $manageUserId=Control::GetManageUserId();
            if($manageUserId>0){

                //////////////判断是否有操作权限///////////////////
                $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
                $canExplore = $manageUserAuthorityManageData->CanManageLeague($siteId, $manageUserId);
                if ($canExplore) {
                    $isManage=1;
                }
            }

            if($isManage>0){
                $manageUrl='"/default.php?secu=manage&mod="+mod+"&m="+method';
                $editMemberUrl='"/default.php?secu=manage&mod=member&m=mobile_list_of_team_in_match&match_id='.$matchId.'&team_id="';
                $templateContent = str_ireplace("{ManageUrl}", $manageUrl, $templateContent);
                $templateContent = str_ireplace("{EditMember}", $editMemberUrl, $templateContent);
                $templateContent = str_ireplace("{display}", "block", $templateContent);
                $templateContent = str_ireplace("{TagWhere}", "match_manage", $templateContent);
            }else{
                $templateContent = str_ireplace("{ManageUrl}", "", $templateContent);
                $templateContent = str_ireplace("{EditMember}", "", $templateContent);
                $templateContent = str_ireplace("{display}", "none", $templateContent);
                $templateContent = str_ireplace("{TagWhere}", "match", $templateContent);
            }



            /*******************页面级的缓存 begin********************** */

            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'default_page';
            $cacheFile = 'match_default_site_id_' . $siteId .
                '_match_id_'.$matchId.
                '_temp_'.$temp.
                '_lid_'.$leagueId.
                '_mode_' . $templateMode;
            if($temp==$defaultTemp){
                $withCache = false;
            }else{
                $withCache = false;
            }

            if($withCache){
                $pageCache = parent::GetCache($cacheDir, $cacheFile);

                if ($pageCache === false) {
                    $result = self::GetTemplateContent(
                        $siteId,
                        $matchId,
                        $leagueId,
                        $templateContent);
                    parent::AddCache($cacheDir, $cacheFile, $result, 60);
                } else {
                    $result = $pageCache;
                }
            }else{
                $result = self::GetTemplateContent(
                    $siteId,
                    $matchId,
                    $leagueId,
                    $templateContent);
            }


            parent::ReplaceVisitCode($result,$siteId,$leagueId,VisitData::VISIT_TABLE_TYPE_MATCH,$matchId);


        }
        return $result;

    }

}