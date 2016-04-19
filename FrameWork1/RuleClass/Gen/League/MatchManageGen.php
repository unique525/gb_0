<?php
/**
 * Created by PhpStorm.
 * User: 525
 * Date: 2016/3/16
 * Time: 21:56
 */
class MatchManageGen extends BaseManageGen implements IBaseManageGen
{

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function Gen()
    {
        $result = "";
        $method = Control::GetRequest("m", "");
        switch ($method) {
            case "create":
                $result = self::GenCreate();
                break;
            case "modify":
                $result = self::GenModify();
                break;
            case "list":
                $result = self::GenList();
                break;
            case "import":
                $result = self::GenImport();
                break;
        }

        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }


    /**
     * 新增
     * @return string 执行结果
     */
    private function GenCreate(){
        $tempContent = Template::Load("league/match_deal.html","common");
        $resultJavaScript="";
        $leagueId=Control::GetRequest("league_id",-1);
        $tabIndex=Control::GetRequest("tab_index",0);


        $matchManageData=new MatchManageData();
        //////////////判断是否有操作权限///////////////////

        $leagueManageData=new LeagueManageData();
        $siteId=$leagueManageData->GetSiteId($leagueId,true);
        $manageUserId = Control::GetManageUserId();
        $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
        $canExplore = $manageUserAuthorityManageData->CanManageLeague($siteId, $manageUserId);
        if (!$canExplore) {
            die(Language::Load('channel', 4));
        }


        parent::ReplaceFirst($tempContent);

        if (intval($leagueId) > 0) {
            if (!empty($_POST)) {
                $matchId = $matchManageData->Create($_POST, $leagueId);

                //记入操作log
                $operateContent = "Create match：league：" . $leagueId .",POST FORM:".implode("|",$_POST).";\r\nResult:". $matchId;
                self::CreateManageUserLog($operateContent);

                if ($matchId > 0) {

                    Control::ShowMessage(Language::Load('lottery', 1));//提交成功!
                    $closeTab = Control::PostRequest("CloseTab",0);
                    if($closeTab == 1){
                        //$resultJavaScript .= Control::GetCloseTab();
                        Control::GoUrl("/default.php?secu=manage&mod=match&m=list&league_id=$leagueId&tab_index=$tabIndex");
                    }else{
                        Control::GoUrl($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']);
                    }



                }else{
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('lottery', 2));//提交失败!插入或修改数据库错误！
                }


            }


            $replaceArr = array(
                "{HomeTeamName}" => "",
                "{GuestTeamName}" => "",
                "{TabIndex}" => $tabIndex,
                "{display}" => "inline"
            );
            $tempContent = strtr($tempContent, $replaceArr);
            $tempContent = str_ireplace("{LeagueId}", $leagueId, $tempContent);
            $tempContent = str_ireplace("{SiteId}", $siteId, $tempContent);


            $arrField = $matchManageData->GetFields();
            parent::ReplaceWhenCreate($tempContent, $arrField);

            //替换掉{s XXX}的内容
            $patterns = '/\{s_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
        } else {
            $resultJavaScript .= Control::GetJqueryMessage(Language::Load('lottery', 5));//id错误！;
        }
        parent::ReplaceEnd($tempContent);
        $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
        return $tempContent;
    }



    /**
     * 编辑
     * @return string 执行结果
     */
    private function GenModify(){
        $tempContent = Template::Load("league/match_deal.html","common");
        $resultJavaScript="";
        $matchId=Control::GetRequest("match_id",-1);
        $tabIndex=Control::GetRequest("tab_index",0);


        $matchManageData=new MatchManageData();
        //////////////判断是否有操作权限///////////////////

        $leagueId=$matchManageData->GetLeagueId($matchId,TRUE);
        $leagueManageData=new LeagueManageData();
        $siteId=$leagueManageData->GetSiteId($leagueId,true);
        $manageUserId = Control::GetManageUserId();
        $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
        $canExplore = $manageUserAuthorityManageData->CanManageLeague($siteId, $manageUserId);
        if (!$canExplore) {
            die(Language::Load('channel', 4));
        }


        parent::ReplaceFirst($tempContent);

        if (intval($matchId) > 0) {
            if (!empty($_POST)) {
                $modifySuccess = $matchManageData->Modify($_POST, $matchId);

                //记入操作log
                $operateContent = "Modify match：match：" . $matchId .",POST FORM:".implode("|",$_POST).";\r\nResult:". $modifySuccess;
                self::CreateManageUserLog($operateContent);

                if ($modifySuccess > 0) {

                    Control::ShowMessage(Language::Load('lottery', 1));//提交成功!
                    $closeTab = Control::PostRequest("CloseTab",0);
                    if($closeTab == 1){
                        //$resultJavaScript .= Control::GetCloseTab();
                        Control::GoUrl("/default.php?secu=manage&mod=match&m=list&league_id=$leagueId&tab_index=$tabIndex");
                    }else{
                        Control::GoUrl($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']);
                    }



                }else{
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('lottery', 2));//提交失败!插入或修改数据库错误！
                }


            }

            $arrOneMatch = $matchManageData->GetOne($matchId);
            if(!empty($arrOneMatch)){
                Template::ReplaceOne($tempContent, $arrOneMatch);
            }else{
                $resultJavaScript .= Control::GetJqueryMessage(Language::Load('lottery', 4));//原有数据获取失败！请谨慎修改！
            }



            $replaceArr = array(
                "{TabIndex}" => $tabIndex,
                "{display}" => "none"
            );
            $tempContent = strtr($tempContent, $replaceArr);
            $tempContent = str_ireplace("{MatchId}", $matchId, $tempContent);
            $tempContent = str_ireplace("{LeagueId}", $leagueId, $tempContent);
            $tempContent = str_ireplace("{SiteId}", $siteId, $tempContent);


            //替换掉{s XXX}的内容
            $patterns = '/\{s_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
        } else {
            $resultJavaScript .= Control::GetJqueryMessage(Language::Load('lottery', 5));//id错误！;
        }
        parent::ReplaceEnd($tempContent);
        $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
        return $tempContent;
    }




    /**
     * 生成赛事列表页面
     */
    private function GenList()
    {
        $result="";

        $leagueId = Control::GetRequest("league_id", 0);
        $manageUserId=Control::GetManageUserId();

        $leagueManageData=new LeagueManageData();
        $siteId=$leagueManageData->GetSiteId($leagueId,true);
        ///////////////判断是否有操作权限///////////////////
        $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
        $canExplore = $manageUserAuthorityManageData->CanManageLeague($siteId, $manageUserId);
        if (!$canExplore) {
            die(Language::Load('channel', 4));
        }








        $tempContent = Template::Load("league/match_list.html","common");

        $pageSize = Control::GetRequest("ps", 20);
        $pageIndex = Control::GetRequest("p", 1);
        $searchKey = Control::GetRequest("search_key", "");
        $searchKey = urldecode($searchKey);

        $pageBegin = ($pageIndex - 1) * $pageSize;
        $allCount = 0;

        $matchManageData = new MatchManageData();
        $arrList = $matchManageData->GetListPager($leagueId,$pageBegin, $pageSize, $allCount, $searchKey);

        $listName = "match_list";
        if(count($arrList)>0){
            Template::ReplaceList($tempContent, $arrList, $listName);
            $styleNumber = 1;
            $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html", "common");
            $isJs = FALSE;
            $navUrl = "default.php?secu=manage&mod=match&m=list&site_id=$leagueId&p={0}&ps=$pageSize";
            $jsFunctionName = "";
            $jsParamList = "";
            $pagerButton = Pager::ShowPageButton($pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex ,$styleNumber = 1, $isJs, $jsFunctionName, $jsParamList);

            $tempContent = str_ireplace("{PagerButton}", $pagerButton, $tempContent);
        }else{
            Template::RemoveCustomTag($tempContent, $listName);
            $tempContent = str_ireplace("{PagerButton}", Language::Load("document", 7), $tempContent);
        }


        Template::ReplaceList($tempContent, $arrList, $listName);

        //删除缓冲(没做modify暂时放这里)
        parent::DelAllCache();

        $tempContent = str_ireplace("{SiteId}", $siteId, $tempContent);
        $tempContent = str_ireplace("{LeagueId}", $leagueId, $tempContent);
        parent::ReplaceEnd($tempContent);



        return $tempContent;
    }



    /**
     * 导入
     * @return string 执行结果
     */
    private function GenImport(){
        $tempContent = Template::Load("league/match_import.html","common");
        $resultJavaScript="";
        $leagueId=Control::GetRequest("league_id",-1);
        $tabIndex=Control::GetRequest("tab_index",0);


        if (intval($leagueId) > 0) {
            $leagueManageData=new LeagueManageData();
            $siteId=$leagueManageData->GetSiteId($leagueId,true);
            //////////////判断是否有操作权限///////////////////
            $manageUserId = Control::GetManageUserId();
            $manageUserAuthority = new ManageUserAuthorityManageData();
            $can = $manageUserAuthority->CanManageLeague($siteId, $manageUserId);
            if ($can != 1) {
                Control::ShowMessage(Language::Load('custom_form', 3));
                return "";
            }

            parent::ReplaceFirst($tempContent);

            if (!empty($_POST)) {
                $importJson=$_POST["import_json"];
                $importArray=json_decode($importJson,true);

                $matchManageData=new MatchManageData();
                $createDate=date('Y-m-d H:i:s', time());
                $result = $matchManageData->Import($importArray,$leagueId,$createDate);
                //记入操作log
                $operateContent = "import match：league_id：" . $leagueId .",POST FORM:".implode("|",$_POST).";\r\nResult:". $result;
                self::CreateManageUserLog($operateContent);

                if ($result > 0) {

                    Control::ShowMessage(Language::Load('lottery', 1));//提交成功!
                    $closeTab = Control::PostRequest("CloseTab",0);
                    if($closeTab == 1){
                        //$resultJavaScript .= Control::GetCloseTab();
                        Control::GoUrl("/default.php?secu=manage&mod=match&m=list&league_id=$leagueId&tab_index=$tabIndex");
                    }else{
                        Control::GoUrl($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']);
                    }



                }else{
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('lottery', 2));//提交失败!插入或修改数据库错误！
                }


            }




            $replaceArr = array(
                "{TabIndex}" => $tabIndex,
                "{display}" => "inline"
            );
            $tempContent = strtr($tempContent, $replaceArr);
            $tempContent = str_ireplace("{SiteId}", $siteId, $tempContent);
            $tempContent = str_ireplace("{LeagueId}", $leagueId, $tempContent);

            //$arrField = $siteManageData->GetFields();
            //parent::ReplaceWhenCreate($tempContent, $arrField);

            //去掉s开头的标记 {s_xxx_xxx}
            $patterns = '/\{s_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
            //去掉c开头的标记 {c_xxx}
            $patterns = '/\{c_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
            //去掉r开头的标记 {r_xxx_xxx}
            $patterns = '/\{r_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);

        } else {
            $resultJavaScript .= Control::GetJqueryMessage(Language::Load('lottery', 5));//id错误！;
        }
        parent::ReplaceEnd($tempContent);
        $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
        return $tempContent;
    }


}