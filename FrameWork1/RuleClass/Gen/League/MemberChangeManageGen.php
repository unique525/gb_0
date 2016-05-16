<?php
/**
 * Created by PhpStorm.
 * User: 525
 * Date: 2016/3/16
 * Time: 21:56
 */
class MemberChangeManageGen extends BaseManageGen implements IBaseManageGen
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
            case "mobile_create":
                $result = self::GenMobileCreate();
                break;
            case "modify":
                $result = self::GenModify();
                break;
            case "mobile_modify":
                $result = self::GenMobileModify();
                break;
            case "list":
                $result = self::GenList();
                break;
            case "import":
                $result = self::GenImport();
                break;
            case "async_check_repeat":
                $result = self::AsyncCheckRepeat();
                break;
        }

        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    /**
     * 新增
     * @return string 执行结果
     */
    private function GenMobileCreate(){
        $resultJavaScript="";
        $matchId=Control::GetRequest("match_id",-1);


        $matchManageData=new MatchManageData();
        $leagueManageData=new LeagueManageData();
        //////////////判断是否有操作权限///////////////////
        $leagueId=$matchManageData->GetLeagueId($matchId,true);
        $siteId=$leagueManageData->GetSiteId($leagueId,true);
        $manageUserId = Control::GetManageUserId();
        $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
        $canExplore = $manageUserAuthorityManageData->CanManageLeague($siteId, $manageUserId);
        if (!$canExplore) {
            die(Language::Load('channel', 4));
        }



        $memberChangeManageData=new MemberChangeManageData();
        if (intval($matchId) > 0) {
            if (!empty($_POST)) {
                //使用取出的league id
                if(isset($_POST["LeagueId"])){
                    $_POST["LeagueId"]=$leagueId;
                }
                $memberChangeId = $memberChangeManageData->Create($_POST,$manageUserId);
                //记入操作log
                $operateContent = "Create member change：match：" . $matchId .",POST FORM:".implode("|",$_POST).";\r\nResult:". $memberChangeId;
                self::CreateManageUserLog($operateContent);

                if ($memberChangeId > 0) {

                    Control::ShowMessage(Language::Load('lottery', 1));//提交成功!
                    $closeTab = Control::PostRequest("CloseTab",0);
                    if($closeTab == 1){
                        //$resultJavaScript .= Control::GetCloseTab();
                        Control::GoUrl("/a/match/m_get_one/match_detail/match_id=$matchId.html");
                    }else{
                        Control::GoUrl($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']);
                    }



                }else{
                    Control::ShowMessage(Language::Load('lottery', 2));//提交失败!插入或修改数据库错误！
                }


            }

            $tempContent = Template::Load("league/match_event_member_change_deal_mobile.html","common");
            parent::ReplaceFirst($tempContent);

            $oneMatch=$matchManageData->GetOne($matchId);
            Template::ReplaceOne($tempContent,$oneMatch);
            $replaceArr = array(
                "{LeagueId}" =>$leagueId,
                "{display}" => "inline"
            );
            $tempContent = strtr($tempContent, $replaceArr);
            $tempContent = str_ireplace("{SiteId}", $siteId, $tempContent);
            $tempContent = str_ireplace("{Select1}", 'selected=""', $tempContent);


            $arrField = $memberChangeManageData->GetFields();
            parent::ReplaceWhenCreate($tempContent, $arrField);




            $memberManageData=new MemberManageData();
            $homeList=$memberManageData->GetListOfTeam($oneMatch["HomeTeamId"]);
            $listName = "home_list";
            $listName2 = "home_list_assistor";
            if(!empty($homeList)){
                Template::ReplaceList($tempContent, $homeList, $listName);
                Template::ReplaceList($tempContent, $homeList, $listName2);
            }else{
                Template::RemoveCustomTag($tempContent, $listName);
                Template::RemoveCustomTag($tempContent, $listName2);
            }
            $guestList=$memberManageData->GetListOfTeam($oneMatch["GuestTeamId"]);
            $listName = "guest_list";
            $listName2 = "guest_list_assistor";
            if(!empty($guestList)){
                Template::ReplaceList($tempContent, $guestList, $listName);
                Template::ReplaceList($tempContent, $guestList, $listName2);
            }else{
                Template::RemoveCustomTag($tempContent, $listName);
                Template::RemoveCustomTag($tempContent, $listName2);
            }





            //替换掉{s XXX}的内容
            $patterns = '/\{s_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
        } else {
            Control::ShowMessage(Language::Load('lottery', 5));//id错误！;
        }
        parent::ReplaceEnd($tempContent);
        $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
        return $tempContent;
    }



    /**
     * 编辑
     * @return string 执行结果
     */
    private function GenMobileModify(){
        $resultJavaScript="";
        $memberChangeId=Control::PostOrGetRequest("member_change_id",-1);

        $memberChangeManageData=new MemberChangeManageData();
        $matchManageData=new MatchManageData();
        $leagueManageData=new LeagueManageData();
        //////////////判断是否有操作权限///////////////////
        $matchId=$memberChangeManageData->GetMatchId($memberChangeId,true);
        $leagueId=$matchManageData->GetLeagueId($matchId,true);
        $siteId=$leagueManageData->GetSiteId($leagueId,true);
        $manageUserId = Control::GetManageUserId();
        $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
        $canExplore = $manageUserAuthorityManageData->CanManageLeague($siteId, $manageUserId);
        if (!$canExplore) {
            die(Language::Load('channel', 4));
        }



        if (intval($memberChangeId) > 0) {
            if (!empty($_POST)) {
                //使用取出的league id
                if(isset($_POST["LeagueId"])){
                    $_POST["LeagueId"]=$leagueId;
                }
                $result = $memberChangeManageData->Modify($_POST,$memberChangeId,$manageUserId);

                //记入操作log
                $operateContent = "Modify member change：card：" . $memberChangeId .",POST FORM:".implode("|",$_POST).";\r\nResult:". $result;
                self::CreateManageUserLog($operateContent);

                if ($memberChangeId > 0) {

                    Control::ShowMessage(Language::Load('lottery', 1));//提交成功!
                    $closeTab = Control::PostRequest("CloseTab",0);
                    if($closeTab == 1){
                        //$resultJavaScript .= Control::GetCloseTab();
                        Control::GoUrl("/a/match/m_get_one/match_detail/match_id=$matchId.html");
                    }else{
                        Control::GoUrl($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']);
                    }



                }else{
                    Control::ShowMessage(Language::Load('lottery', 2));//提交失败!插入或修改数据库错误！
                }


            }

            $tempContent = Template::Load("league/match_event_member_change_deal_mobile.html","common");
            parent::ReplaceFirst($tempContent);

            $oneMatch=$matchManageData->GetOne($matchId);
            Template::ReplaceOne($tempContent,$oneMatch);


            $oneMemberChange=$memberChangeManageData->GetOne($memberChangeId);
            Template::ReplaceOne($tempContent,$oneMemberChange);

            $state=$oneMemberChange["State"];
            $tempContent = str_ireplace("{select$state}", "selected='selected'", $tempContent);
            $tempContent = str_ireplace("{select100}", "", $tempContent);
            $tempContent = str_ireplace("{select1}", "", $tempContent);



            $replaceArr = array(
                "{LeagueId}" =>$leagueId,
                "{display}" => "inline"
            );
            $tempContent = strtr($tempContent, $replaceArr);
            $tempContent = str_ireplace("{SiteId}", $siteId, $tempContent);


            $arrField = $memberChangeManageData->GetFields();
            parent::ReplaceWhenCreate($tempContent, $arrField);




            $memberManageData=new MemberManageData();
            $homeList=$memberManageData->GetListOfTeam($oneMatch["HomeTeamId"]);
            $listName = "home_list";
            $listName2 = "home_list_assistor";
            if(!empty($homeList)){
                Template::ReplaceList($tempContent, $homeList, $listName);
                Template::ReplaceList($tempContent, $homeList, $listName2);
            }else{
                Template::RemoveCustomTag($tempContent, $listName);
                Template::RemoveCustomTag($tempContent, $listName2);
            }
            $guestList=$memberManageData->GetListOfTeam($oneMatch["GuestTeamId"]);
            $listName = "guest_list";
            $listName2 = "guest_list_assistor";
            if(!empty($guestList)){
                Template::ReplaceList($tempContent, $guestList, $listName);
                Template::ReplaceList($tempContent, $guestList, $listName2);
            }else{
                Template::RemoveCustomTag($tempContent, $listName);
                Template::RemoveCustomTag($tempContent, $listName2);
            }




            //替换掉{s XXX}的内容
            $patterns = '/\{s_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
        } else {
            Control::ShowMessage(Language::Load('lottery', 5));//id错误！;
        }
        parent::ReplaceEnd($tempContent);
        $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
        return $tempContent;
    }



    /**
     * 生成列表页面
     */
    private function GenList()
    {
        $result="";

        $siteId = Control::GetRequest("site_id", 0);
        $manageUserId=Control::GetManageUserId();

        ///////////////判断是否有操作权限///////////////////
        $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
        $canExplore = $manageUserAuthorityManageData->CanManageLeague($siteId, $manageUserId);
        if (!$canExplore) {
            die(Language::Load('channel', 4));
        }








        $tempContent = Template::Load("league/member_list.html","common");

        $pageSize = Control::GetRequest("ps", 20);
        $pageIndex = Control::GetRequest("p", 1);
        $searchKey = Control::GetRequest("search_key", "");
        $searchKey = urldecode($searchKey);

        $pageBegin = ($pageIndex - 1) * $pageSize;
        $allCount = 0;

        $memberManageData = new MemberManageData();
        $arrList = $memberManageData->GetListPager($siteId,$pageBegin, $pageSize, $allCount, $searchKey);

        $listName = "member_list";
        if(count($arrList)>0){
            Template::ReplaceList($tempContent, $arrList, $listName);
            $styleNumber = 1;
            $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html", "common");
            $isJs = FALSE;
            $navUrl = "default.php?secu=manage&mod=member&m=list&site_id=$siteId&p={0}&ps=$pageSize";
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
        parent::ReplaceEnd($tempContent);



        return $tempContent;
    }





}