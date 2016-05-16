<?php
/**
 * Created by PhpStorm.
 * User: 525
 * Date: 2016/3/16
 * Time: 21:56
 */
class MemberPublicGen extends BasePublicGen implements IBasePublicGen
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

        $result="";
        $teamId = Control::GetRequest("team_id", 0);
        //$publishDate = Control::GetRequest("publish_date", "");

        $teamPublicData = new TeamPublicData();

        $siteId = $teamPublicData->GetSiteId($teamId, true);
        $defaultTemp = "team_member_list";
        $temp=Control::GetRequest("temp",$defaultTemp);
        $templateContent = parent::GetDynamicTemplateContent(
            $temp,
            $siteId,
            "",
            $templateMode);

        /*******************页面级的缓存 begin********************** */


        $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'default_page';
        $cacheFile = 'member_list_of_team_site_id_' . $siteId .
            '_temp_'.$defaultTemp.
            '_tid_'.$teamId.
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
                    $teamId,
                    $templateContent);
                parent::AddCache($cacheDir, $cacheFile, $result, 60);
            } else {
                $result = $pageCache;
            }
        }else{
            $result = self::GetTemplateContent(
                $siteId,
                $teamId,
                $templateContent);
        }

        /*******************页面级的缓存 end  ********************** */

        parent::ReplaceUserInfoPanel($result, $siteId);
        return $result;
    }




    /**
     * 取得模板内容
     * @param $siteId
     * @param $teamId
     * @param $tempContent
     * @return string
     */
    private function GetTemplateContent(
        $siteId,
        $teamId,
        $tempContent
    )
    {
        $teamPublicData = new teamPublicData();
        $arrayOneTeam=$teamPublicData->GetOne($teamId,false);
        Template::ReplaceOne($tempContent,$arrayOneTeam);


        parent::ReplaceFirst($tempContent);

        $voteIdFromUrl = Control::GetRequest("vote_id","0");
        $tempContent = str_ireplace("{vote_id_from_url}", $voteIdFromUrl, $tempContent);  //替换icms投票标签的id（参数在url内get）


        $tempContent =  parent::ReplaceTemplate($tempContent);

        parent::ReplaceSiteInfo($siteId, $tempContent);
        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }

}