<?php

/**
 * 广告位页面生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Site
 * @author 525
 */
class SiteFilterManageGen extends BaseManageGen implements IBaseManageGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    function Gen() {
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
            case "modify_state":
                $result = self::ModifyState();
                break;
        }
        $replaceArray = array(
            "{method}" => $method
        );
        $result = strtr($result, $replaceArray);
        return $result;
    }


    /**
     * 新增过滤
     * @return string 执行结果
     */
    private function GenCreate() {
        $tempContent = Template::Load("site/site_filter_deal.html","common");
        $siteId = Control::GetRequest("site_id", "-1");
        $siteName = Control::GetRequest("site_name", "");
        $resultJavaScript="";
        $tabIndex = Control::GetRequest("tab_index", 1);

        if($siteId>=0){

            ///////////////判断是否有操作权限///////////////////
            $manageUserId = Control::GetManageUserId();
            $manageUserAuthority = new ManageUserAuthorityManageData();
            $can = $manageUserAuthority->CanManageFilter($siteId, 0, $manageUserId);
            if ($can != 1) {
                Control::ShowMessage(Language::Load('document', 26), $tempContent);//您尚未开通操作此功能的权限，如需开通此权限，请联系管理人员！
                return "";
            }

            $siteFilterManageData = new SiteFilterManageData();
            parent::ReplaceFirst($tempContent);
            if (!empty($_POST)) {
                $siteId = Control::PostRequest("f_SiteId", "0");
                $siteFilterArea = control::PostRequest("f_SiteFilterArea", 0);
                $siteFilterWord = control::PostRequest("f_SiteFilterWord", "");
                if($siteFilterWord!=""){
                    $alreadyFilter = $siteFilterManageData->GetCount($siteFilterArea,$siteFilterWord,$siteId);  //检查是否存在该过滤
                    if($alreadyFilter==0){

                        $newFilterId = $siteFilterManageData->Create($_POST);
                        parent::DelAllCache();

                        //记入操作log
                        $operateContent = "Create site_filter：SiteFilterId：" . $newFilterId .",POST FORM:".implode("|",$_POST).";\r\nResult:". $newFilterId;
                        self::CreateManageUserLog($operateContent);

                        if ($newFilterId > 0) {
                            Control::ShowMessage(Language::Load('site_filter', 1));//提交成功!
                            $closeTab = Control::PostRequest("CloseTab",0);
                            if($closeTab == 1){
                                $resultJavaScript .= Control::GetCloseTab();
                            }else{
                                Control::GoUrl($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']);
                            }
                        }else{
                            $resultJavaScript .= Control::GetJqueryMessage(Language::Load('site_filter', 2));//提交失败!插入或修改数据库错误！
                        }
                    }else{
                        $resultJavaScript.=Control::GetJqueryMessage(Language::Load('site_filter', 7));//该过滤已经存在！不能重复添加
                    }
                }else{
                    $resultJavaScript.=Control::GetJqueryMessage(Language::Load('site_filter', 3));//过滤字符为空！
                }
            }


            $createDate=date('Y-m-d H:i:s');
            $replaceArr = array(
                "{SiteId}" => $siteId,
                "{SiteName}" => $siteName,
                "{TabIndex}" => $tabIndex,
                "{CreateDate}" => $createDate,
                "{ReadOnly}" => "",
                "{display}" => ""
            );
            $tempContent = strtr($tempContent, $replaceArr);

            $fieldsOfTable = $siteFilterManageData->GetFields();
            parent::ReplaceWhenCreate($tempContent, $fieldsOfTable);

            //替换掉{s XXX}的内容
            $patterns = '/\{s_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
        } else {
            $resultJavaScript .= Control::GetJqueryMessage(Language::Load('site_filter', 5));//站点siteid错误！;
        }
        parent::ReplaceEnd($tempContent);
        $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
        return $tempContent;
    }


    /**
     * 编辑过滤
     * @return string 执行结果
     */
    private function GenModify() {
        $tempContent = Template::Load("site/site_filter_deal.html","common");
        $siteId = Control::GetRequest("site_id", "-1");
        $siteName = Control::GetRequest("site_name", "");
        $resultJavaScript="";
        $tabIndex = Control::GetRequest("tab_index", 1);
        $siteFilterId= Control::GetRequest("site_filter_id",-1);
        $siteFilterId=intval($siteFilterId);
        if($siteFilterId>0){
            if($siteId>=0){

                ///////////////判断是否有操作权限///////////////////
                $manageUserId = Control::GetManageUserId();
                $manageUserAuthority = new ManageUserAuthorityManageData();
                $can = $manageUserAuthority->CanManageFilter($siteId, 0, $manageUserId);
                if ($can != 1) {
                    Control::ShowMessage(Language::Load('document', 26), $tempContent);//您尚未开通操作此功能的权限，如需开通此权限，请联系管理人员！
                    return "";
                }

                $siteFilterManageData = new SiteFilterManageData();
                parent::ReplaceFirst($tempContent);
                if (!empty($_POST)) {
                    $siteId = Control::PostRequest("f_SiteId", "0");
                    $siteFilterWord = control::PostRequest("f_SiteFilterWord", "");
                    if($siteFilterWord!=""){

                            $Modified = $siteFilterManageData->Modify($_POST,$siteFilterId);
                        parent::DelAllCache();

                            //记入操作log
                            $operateContent = "Modify site_filter：SiteFilterId：" . $siteFilterId .",POST FORM:".implode("|",$_POST).";\r\nResult:". $Modified;
                            self::CreateManageUserLog($operateContent);

                            if ($Modified > 0) {
                                Control::ShowMessage(Language::Load('site_filter', 1));//提交成功!
                                $closeTab = Control::PostRequest("CloseTab",0);
                                if($closeTab == 1){
                                    $resultJavaScript .= Control::GetCloseTab();
                                }else{
                                    Control::GoUrl($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']);
                                }
                            }else{
                                $resultJavaScript .= Control::GetJqueryMessage(Language::Load('site_filter', 2));//提交失败!插入或修改数据库错误！
                            }
                    }else{
                        $resultJavaScript.=Control::GetJqueryMessage(Language::Load('site_filter', 3));//过滤字符为空！
                    }
                }

                $replaceArr = array(
                    "{SiteId}" => $siteId,
                    "{SiteName}" => $siteName,
                    "{TabIndex}" => $tabIndex,
                    "{ReadOnly}" => "readonly",
                    "{display}" => "none"
                );
                $tempContent = strtr($tempContent, $replaceArr);


                $arrOneSiteFilter = $siteFilterManageData->GetOne($siteFilterId);
                if(!empty($arrOneSiteFilter)){
                    Template::ReplaceOne($tempContent, $arrOneSiteFilter);
                }else{
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('site_filter', 4));//过滤原有数据获取失败！请谨慎修改！
                }



                //替换掉{s XXX}的内容
                $patterns = '/\{s_(.*?)\}/';
                $tempContent = preg_replace($patterns, "", $tempContent);
            } else {
                $resultJavaScript .= Control::GetJqueryMessage(Language::Load('site_filter', 5));//站点siteid错误！;
            }
        }else{
            $resultJavaScript .= Control::GetJqueryMessage(Language::Load('site_filter', 6));//过滤字符site_filter_id错误！;
        }
        parent::ReplaceEnd($tempContent);
        $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
        return $tempContent;
    }

    /**
     * 过滤字段分页列表
     * @return string 列表页面html
     */
    private function GenList(){
        $resultJavaScript="";
        $tempContent = Template::Load("site/site_filter_list.html","common");
        $siteId = Control::GetRequest("site_id", "0");
        $pageSize = Control::GetRequest("ps", 20);
        $pageIndex = Control::GetRequest("p", 1);
        $searchKey = Control::GetRequest("search_key", "");


        if ($pageIndex > 0 && $siteId > 0) {
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $listName = "site_filter";
            $allCount = 0;

            ///////////////判断是否有操作权限///////////////////
            $manageUserId = Control::GetManageUserId();
            $manageUserAuthority = new ManageUserAuthorityManageData();
            $can = $manageUserAuthority->CanManageFilter($siteId, 0, $manageUserId);
            if ($can == 1) {
                $siteFilterManageData = new SiteFilterManageData();
                $listOfFilterArray = $siteFilterManageData->GetListPager($siteId, $pageBegin, $pageSize, $allCount, $searchKey);
                if(count($listOfFilterArray)>0){
                    Template::ReplaceList($tempContent, $listOfFilterArray, $listName);

                    $styleNumber = 1;
                    $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html", "common");
                    $isJs = FALSE;
                    $navUrl = "default.php?secu=manage&mod=site_filter&m=list&site_id=$siteId&p={0}&ps=$pageSize";
                    $jsFunctionName = "";
                    $jsParamList = "";
                    $pagerButton = Pager::ShowPageButton($pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex ,$styleNumber = 1, $isJs, $jsFunctionName, $jsParamList);


                    $replace_arr = array(
                        "{PagerButton}" => $pagerButton
                    );
                    $tempContent = strtr($tempContent, $replace_arr);
                }else{
                    Template::RemoveCustomTag($tempContent, $listName);
                    $tempContent = str_ireplace("{PagerButton}", Language::Load("document", 7), $tempContent);
                }

            }else{
                Template::RemoveCustomTag($tempContent, $listName);
                $tempContent = str_ireplace("{PagerButton}", Language::Load('document', 26), $tempContent);//您尚未开通操作此功能的权限，如需开通此权限，请联系管理人员！
            }
            parent::ReplaceEnd($tempContent);
            $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
            $result=$tempContent;
        }else{
            $result = Language::Load('site_filter', 3);
        }
        return $result;
    }
    /**
     * 修改过滤状态
     * @return string 修改结果
     */
    private function ModifyState()
    {
        //$result = -1;
        $siteFilterId = Control::GetRequest("table_id", 0);
        $state = Control::GetRequest("state",0);
        if ($siteFilterId > 0) {
            $siteFilterManageData = new SiteFilterManageData();
            $result = $siteFilterManageData->ModifyState($siteFilterId,$state);

            parent::DelAllCache();
            //加入操作日志
            $operateContent = 'ModifyState site_filter,Get FORM:' . implode('|', $_GET) . ';\r\nResult:site_ad:' . $result;
            self::CreateManageUserLog($operateContent);
        } else {
            $result = -1;
        }
        return Control::GetRequest("jsonpcallback","") . '({"result":"'.$result.'"})';
    }
}
?>