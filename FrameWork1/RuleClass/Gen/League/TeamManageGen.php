<?php
/**
 * Created by PhpStorm.
 * User: 525
 * Date: 2016/3/16
 * Time: 21:56
 */
class TeamManageGen extends BaseManageGen implements IBaseManageGen
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
            case "async_check_repeat":
                $result = self::AsyncCheckRepeat();
                break;
            case "async_get_json_with_ids":
                $result = self::AsyncGetJsonWithIds();
                break;
            case "async_get_id_by_name":
                $result = self::AsyncGetIdByName();
                break;
            case "async_join_league":
                $result = self::AsyncJoinLeague();
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
        $tempContent = Template::Load("league/team_deal.html","common");
        $resultJavaScript="";
        $siteId=Control::GetRequest("site_id",-1);
        $tabIndex=Control::GetRequest("tab_index",0);


        $teamManageData=new TeamManageData();
        //////////////判断是否有操作权限///////////////////
        $manageUserId = Control::GetManageUserId();
        $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
        $canExplore = $manageUserAuthorityManageData->CanManageLeague($siteId, $manageUserId);
        if (!$canExplore) {
            die(Language::Load('channel', 4));
        }


        parent::ReplaceFirst($tempContent);

        if (intval($siteId) > 0) {
            if (!empty($_POST)) {
                $teamId = $teamManageData->Create($_POST, $siteId);

                //记入操作log
                $operateContent = "Create team：site：" . $siteId .",POST FORM:".implode("|",$_POST).";\r\nResult:". $teamId;
                self::CreateManageUserLog($operateContent);

                if ($teamId > 0) {

                    Control::ShowMessage(Language::Load('lottery', 1));//提交成功!
                    $closeTab = Control::PostRequest("CloseTab",0);
                    if($closeTab == 1){
                        //$resultJavaScript .= Control::GetCloseTab();
                        Control::GoUrl("/default.php?secu=manage&mod=team&m=list&site_id=$siteId&tab_index=$tabIndex");
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


            $arrField = $teamManageData->GetFields();
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
        $tempContent = Template::Load("league/team_deal.html","common");
        $resultJavaScript="";
        $teamId=Control::GetRequest("team_id",-1);
        $tabIndex=Control::GetRequest("tab_index",0);


        $teamManageData=new TeamManageData();
        //////////////判断是否有操作权限///////////////////

        $siteId=$teamManageData->GetSiteId($teamId,true);
        $manageUserId = Control::GetManageUserId();
        $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
        $canExplore = $manageUserAuthorityManageData->CanManageLeague($siteId, $manageUserId);
        if (!$canExplore) {
            die(Language::Load('channel', 4));
        }


        parent::ReplaceFirst($tempContent);

        if (intval($teamId) > 0) {
            if (!empty($_POST)) {
                $modifySuccess = $teamManageData->Modify($_POST, $teamId);

                //记入操作log
                $operateContent = "Modify team：team：" . $teamId .",POST FORM:".implode("|",$_POST).";\r\nResult:". $modifySuccess;
                self::CreateManageUserLog($operateContent);

                if ($modifySuccess > 0) {

                    Control::ShowMessage(Language::Load('lottery', 1));//提交成功!
                    $closeTab = Control::PostRequest("CloseTab",0);
                    if($closeTab == 1){
                        //$resultJavaScript .= Control::GetCloseTab();
                        Control::GoUrl("/default.php?secu=manage&mod=team&m=list&site_id=$siteId&tab_index=$tabIndex");
                    }else{
                        Control::GoUrl($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']);
                    }



                }else{
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('lottery', 2));//提交失败!插入或修改数据库错误！
                }


            }

            $arrOneMember = $teamManageData->GetOne($teamId);
            if(!empty($arrOneMember)){
                Template::ReplaceOne($tempContent, $arrOneMember);
            }else{
                $resultJavaScript .= Control::GetJqueryMessage(Language::Load('lottery', 4));//原有数据获取失败！请谨慎修改！
            }



            $replaceArr = array(
                "{TabIndex}" => $tabIndex,
                "{display}" => "none"
            );
            $tempContent = strtr($tempContent, $replaceArr);
            $tempContent = str_ireplace("{TeamId}", $teamId, $tempContent);
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

        $siteId = Control::GetRequest("site_id", 0);
        $manageUserId=Control::GetManageUserId();

        ///////////////判断是否有操作权限///////////////////
        $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
        $canExplore = $manageUserAuthorityManageData->CanManageLeague($siteId, $manageUserId);
        if (!$canExplore) {
            die(Language::Load('channel', 4));
        }








        $tempContent = Template::Load("league/team_list.html","common");

        $pageSize = Control::GetRequest("ps", 20);
        $pageIndex = Control::GetRequest("p", 1);
        $searchKey = Control::GetRequest("search_key", "");
        $searchKey = urldecode($searchKey);

        $pageBegin = ($pageIndex - 1) * $pageSize;
        $allCount = 0;

        $teamManageData = new TeamManageData();
        $arrList = $teamManageData->GetListPager($siteId,$pageBegin, $pageSize, $allCount, $searchKey);

        $listName = "team_list";
        if(count($arrList)>0){
            Template::ReplaceList($tempContent, $arrList, $listName);
            $styleNumber = 1;
            $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html", "common");
            $isJs = FALSE;
            $navUrl = "default.php?secu=manage&mod=team&m=list&site_id=$siteId&p={0}&ps=$pageSize";
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



    /**
     * 导入
     * @return string 执行结果
     */
    private function GenImport(){
        $tempContent = Template::Load("league/team_import.html","common");
        $resultJavaScript="";
        $siteId=Control::GetRequest("site_id",-1);
        $tabIndex=Control::GetRequest("tab_index",0);


        //////////////判断是否有操作权限///////////////////
        $manageUserId = Control::GetManageUserId();
        $manageUserAuthority = new ManageUserAuthorityManageData();
        $can = $manageUserAuthority->CanManageLeague($siteId, $manageUserId);
        if ($can != 1) {
            Control::ShowMessage(Language::Load('custom_form', 3));
            return "";
        }

        parent::ReplaceFirst($tempContent);

        if (intval($siteId) > 0) {
            if (!empty($_POST)) {
                $importJson=$_POST["import_json"];
                $importArray=json_decode($importJson,true);

                $teamManageData=new TeamManageData();
                $createDate=date('Y-m-d H:i:s', time());
                $resultIdArray=array();
                $result = $teamManageData->Import($importArray,$siteId,$createDate,$resultIdArray);
                //记入操作log
                $operateContent = "import team：site_id：" . $siteId .",POST FORM:".implode("|",$_POST).";\r\nResult:". $result;
                self::CreateManageUserLog($operateContent);

                if ($result > 0) {
                    $showMessage=Language::Load('lottery', 1);//提交成功!

                    //加入到赛事中
                    $toLeagueId=Control::PostOrGetRequest("to_league_id",0);
                    if($toLeagueId>0){
                        foreach($resultIdArray as &$oneId){
                            if(!isset($oneId["GroupName"])){
                                $oneId["GroupName"]="";
                            }else{
                                $oneId["GroupName"]=strtolower($oneId["GroupName"]);
                            }
                        }
                        $joinLeague=$teamManageData->BatchJoinLeague($resultIdArray,$toLeagueId);
                        if($joinLeague){
                            $showMessage.="\n加入赛事成功！";
                        }else{
                            $showMessage.="\n加入赛事失败！";
                        }
                            $operateContent = "teams join league：league_id：" . $toLeagueId .",POST FORM:".implode("|",$_POST).";\r\nResult:". $joinLeague;
                            self::CreateManageUserLog($operateContent);
                    }
                    Control::ShowMessage($showMessage);//提交成功!
                    $closeTab = Control::PostRequest("CloseTab",0);
                    if($closeTab == 1){
                        //$resultJavaScript .= Control::GetCloseTab();
                        Control::GoUrl("/default.php?secu=manage&mod=team&m=list&site_id=$siteId&tab_index=$tabIndex");
                    }else{
                        Control::GoUrl($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']);
                    }



                }else{
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('lottery', 2));//提交失败!插入或修改数据库错误！
                }


            }


            $leagueManageData=new LeagueManageData();
            $leagueArray=$leagueManageData->GetAvailableList($siteId,date('y-m-d',time()));

            $listName = "league_list";
            if(count($leagueArray)>0){
                Template::ReplaceList($tempContent, $leagueArray, $listName);
            }else{
                Template::RemoveCustomTag($tempContent, $listName);
            }



            $replaceArr = array(
                "{TabIndex}" => $tabIndex,
                "{display}" => "inline"
            );
            $tempContent = strtr($tempContent, $replaceArr);
            $tempContent = str_ireplace("{SiteId}", $siteId, $tempContent);

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


    /**
     * 导入
     * @return string 执行结果
     */
    private function AsyncCheckRepeat(){
        $result="-1";
        $repeatArray=array();
        $siteId=Control::GetRequest("site_id",0);
        $importJson=$_POST["import_json"];
        $fieldName=Control::PostRequest("field_name","");
        $fieldType=Control::PostRequest("field_type","");

        //////////////判断是否有操作权限///////////////////
        $manageUserId = Control::GetManageUserId();
        $manageUserAuthority = new ManageUserAuthorityManageData();
        $can = $manageUserAuthority->CanManageLeague($siteId, $manageUserId);
        if ($can != 1) {
            $result="-10";//没权限
            return Control::GetRequest("jsonpcallback","") . '({"result":"'.$result.'","repeat":'.json_encode($repeatArray).'})';
        }

        if($fieldName!=""&&$importJson!=""&&$siteId>0){
            $importArray=json_decode($importJson,true);
            $checkStr="";
            switch($fieldType){
                case "int":
                    foreach($importArray as $importItem){
                        $checkStr.=','.$importItem[$fieldName];
                    }
                break;
                default:
                    foreach($importArray as $importItem){
                        $checkStr.=',"'.$importItem[$fieldName].'"';
                    }
                break;
            }
            $checkStr=substr($checkStr,1);
            $teamManageData=new TeamManageData();
            $repeatArray=$teamManageData->GetRepeat($checkStr,$fieldName);
            if(!empty($repeatArray)){
                $result="1";
            }else{
                $result="0";
            }
        }
        return Control::GetRequest("jsonpcallback","") . '({"result":"'.$result.'","repeat":'.json_encode($repeatArray).'})';

    }

    private function AsyncGetJsonWithIds(){
        $result="-1";
        $data="";
        $siteId=Control::GetRequest("site_id",0);
        $importJson=$_POST["import_json"];
        $fieldName=Control::PostRequest("field_name","");
        $fieldType=Control::PostRequest("field_type","");
        $queryFieldName=Control::PostRequest("query_field_name",$fieldName);
        $idFieldName=str_ireplace("Name","Id",$fieldName);
        $idFieldName=Control::PostOrGetRequest("field_id_name",$idFieldName);

        //////////////判断是否有操作权限///////////////////
        $manageUserId = Control::GetManageUserId();
        $manageUserAuthority = new ManageUserAuthorityManageData();
        $can = $manageUserAuthority->CanManageLeague($siteId, $manageUserId);
        if ($can != 1) {
            $result="-10";//没权限
            return Control::GetRequest("jsonpcallback","") . '({"result":"'.$result.'","data":'.json_encode($data).'})';
        }

        if($fieldName!=""&&$importJson!=""&&$siteId>0){
            $importArray=json_decode($importJson,true);
            $nameStr="";
            switch($fieldType){
                case "int":
                    foreach($importArray as $importItem){
                        $nameStr.=','.$importItem[$fieldName];
                    }
                    break;
                default:
                    foreach($importArray as $importItem){
                        $nameStr.=',"'.$importItem[$fieldName].'"';
                    }
                    break;
            }
            $nameStr=substr($nameStr,1);
            $teamManageData=new TeamManageData();
            $idArr=$teamManageData->GetIdByFieldValue($nameStr,$queryFieldName,$siteId);

            if(!empty($idArr)){
                $result="1";
            }else{
                $result="0";
            }
            foreach($importArray as &$importItem){
                $added=0;
                foreach($idArr as $id){
                    if($importItem[$fieldName]==$id[$queryFieldName]){

                        $importItem[$idFieldName]=$id["TeamId"];
                        $added=1;
                    }
                }
                if($added==0){
                    $importItem[$idFieldName]=0;
                }
            }
            $data=json_encode($importArray);
        }
        return Control::GetRequest("jsonpcallback","") . '({"result":"'.$result.'","data":'.$data.'})';
    }

    private function AsyncGetIdByName(){
        $result=0;//队名空
        $teamId=-1;
        $teamName=Control::PostOrGetRequest("team_name","");
        $leagueId=Control::PostOrGetRequest("league_id",0);
        $siteId=Control::PostOrGetRequest("site_id",0);

        if($leagueId>0){
            $leagueManageData=new LeagueManageData();
            $siteId=$leagueManageData->GetSiteId($leagueId,true);
        }



        if($teamName!=""&&$siteId>0){
            //////////////判断是否有操作权限///////////////////
            $manageUserId = Control::GetManageUserId();
            $manageUserAuthority = new ManageUserAuthorityManageData();
            $can = $manageUserAuthority->CanManageLeague($siteId, $manageUserId);
            if ($can != 1) {
                $result="-10";//没权限
                return Control::GetRequest("jsonpcallback","") . '({"result":"'.$result.'","team_id":"'.$teamId.'"})';
            }

            $teamManageData=new TeamManageData();
            $teamId=$teamManageData->GetIdByName($teamName);
            $result=1;
            if($teamId>0){
                if($leagueId>0){
                    $checkResult=$teamManageData->CheckInLeague($teamId,$leagueId);
                    if($checkResult<=0){
                        $result=-2;//该队伍未参加赛事
                    }
                }
            }else{
                $result=-1;//找不到队伍
            }
        }
        return Control::GetRequest("jsonpcallback","") . '({"result":"'.$result.'","team_id":'.$teamId.'})';
    }



    private function AsyncJoinLeague(){
        $result=-1;
        $teamId=Control::PostOrGetRequest("team_id",0);
        $leagueId=Control::PostOrGetRequest("league_id",0);
        $groupName=Control::PostOrGetRequest("group_name",0);
        $groupName=strtolower($groupName);
        if($teamId>0&&$leagueId>0){
            $teamManageData=new TeamManageData();
            $result=$teamManageData->JoinLeague($teamId,$leagueId,$groupName);
        }
        return Control::GetRequest("jsonpcallback","") . '({"result":"'.$result.'"})';
    }
}