<?php
/**
 * Created by PhpStorm.
 * User: 525
 * Date: 2016/3/16
 * Time: 21:56
 */
class MemberManageGen extends BaseManageGen implements IBaseManageGen
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
        }

        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    /**
     * 新增
     * @return string 执行结果
     */
    private function GenCreate(){
        $tempContent = Template::Load("league/member_deal.html","common");
        $resultJavaScript="";
        $siteId=Control::GetRequest("site_id",-1);
        $tabIndex=Control::GetRequest("tab_index",0);


        $memberManageData=new MemberManageData();
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
                $memberId = $memberManageData->Create($_POST, $siteId);

                //记入操作log
                $operateContent = "Create member：site：" . $siteId .",POST FORM:".implode("|",$_POST).";\r\nResult:". $memberId;
                self::CreateManageUserLog($operateContent);

                if ($memberId > 0) {

                    Control::ShowMessage(Language::Load('lottery', 1));//提交成功!
                    $closeTab = Control::PostRequest("CloseTab",0);
                    if($closeTab == 1){
                        //$resultJavaScript .= Control::GetCloseTab();
                        Control::GoUrl("/default.php?secu=manage&mod=member&m=list&site_id=$siteId&tab_index=$tabIndex");
                    }else{
                        Control::GoUrl($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']);
                    }



                }else{
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('lottery', 2));//提交失败!插入或修改数据库错误！
                }


            }


            $replaceArr = array(
                "{TeamName}" => "",
                "{TabIndex}" => $tabIndex,
                "{display}" => "inline"
            );
            $tempContent = strtr($tempContent, $replaceArr);
            $tempContent = str_ireplace("{SiteId}", $siteId, $tempContent);


            $arrField = $memberManageData->GetFields();
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
        $tempContent = Template::Load("league/member_deal.html","common");
        $resultJavaScript="";
        $memberId=Control::GetRequest("member_id",-1);
        $tabIndex=Control::GetRequest("tab_index",0);


        $memberManageData=new MemberManageData();
        //////////////判断是否有操作权限///////////////////

        $siteId=$memberManageData->GetSiteId($memberId,true);
        $debug=new DebugLogManageData();
        $debug->Create($siteId);
        $manageUserId = Control::GetManageUserId();
        $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
        $canExplore = $manageUserAuthorityManageData->CanManageLeague($siteId, $manageUserId);
        if (!$canExplore) {
            die(Language::Load('channel', 4));
        }


        parent::ReplaceFirst($tempContent);

        if (intval($memberId) > 0) {
            if (!empty($_POST)) {
                $modifySuccess = $memberManageData->Modify($_POST, $memberId);

                //记入操作log
                $operateContent = "Modify member：member：" . $memberId .",POST FORM:".implode("|",$_POST).";\r\nResult:". $modifySuccess;
                self::CreateManageUserLog($operateContent);

                if ($modifySuccess > 0) {

                    Control::ShowMessage(Language::Load('lottery', 1));//提交成功!
                    $closeTab = Control::PostRequest("CloseTab",0);
                    if($closeTab == 1){
                        //$resultJavaScript .= Control::GetCloseTab();
                        Control::GoUrl("/default.php?secu=manage&mod=member&m=list&site_id=$siteId&tab_index=$tabIndex");
                    }else{
                        Control::GoUrl($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']);
                    }



                }else{
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('lottery', 2));//提交失败!插入或修改数据库错误！
                }


            }

            $arrOneMember = $memberManageData->GetOne($memberId);
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
            $tempContent = str_ireplace("{MemberId}", $memberId, $tempContent);
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



    /**
     * 导入
     * @return string 执行结果
     */
    private function GenImport(){
        $tempContent = Template::Load("league/member_import.html","common");
        $resultJavaScript="";
        $siteId=Control::GetRequest("site_id",-1);
        $tabIndex=Control::GetRequest("tab_index",0);

        if (intval($siteId) > 0) {
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

                $memberManageData=new MemberManageData();
                $createDate=date('Y-m-d H:i:s', time());
                $result = $memberManageData->Import($importArray,$siteId,$createDate);
                //记入操作log
                $operateContent = "import member：site_id：" . $siteId .",POST FORM:".implode("|",$_POST).";\r\nResult:". $result;
                self::CreateManageUserLog($operateContent);

                if ($result > 0) {

                    Control::ShowMessage(Language::Load('lottery', 1));//提交成功!
                    $closeTab = Control::PostRequest("CloseTab",0);
                    if($closeTab == 1){
                        //$resultJavaScript .= Control::GetCloseTab();
                        Control::GoUrl("/default.php?secu=manage&mod=member&m=list&site_id=$siteId&tab_index=$tabIndex");
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
     * 检查重复
     * @return string 执行结果
     */
    private function AsyncCheckRepeat(){
        $result="-1";
        $repeatArray=array();
        $siteId=Control::GetRequest("site_id",0);
        $importJson=$_POST["import_json"];
        $fieldName=Control::PostRequest("field_name","");
        $fieldType=Control::PostRequest("field_type","");

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
            $memberManageData=new MemberManageData();
            $repeatArray=$memberManageData->GetRepeat($checkStr,$fieldName);
            if(!empty($repeatArray)){
                $result="1";
            }else{
                $result="0";
            }
        }
        return Control::GetRequest("jsonpcallback","") . '({"result":"'.$result.'","repeat":'.json_encode($repeatArray).'})';

    }
}