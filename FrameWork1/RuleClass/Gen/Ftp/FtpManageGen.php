<?php

/**
 * ftp管理类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_CustomForm
 * @author 525
 */

class FtpManageGen extends BaseManageGen implements IBaseManageGen {

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
            case "delete":
                $result = self::GenDelete();
                break;
        }
        $replaceArray = array(
            "{method}" => $method
        );
        $result = strtr($result, $replaceArray);
        return $result;
    }


    /**
     * 新增ftp
     * @return string 执行结果
     */
    private function GenCreate() {
        $tempContent = Template::Load("ftp/ftp_deal.html","common");
        $siteId = Control::GetRequest("site_id", "-1");
        $siteName = Control::GetRequest("site_name", "");
        $resultJavaScript="";
        $tabIndex = Control::GetRequest("tab_index", 1);

        if($siteId>=0){

            ///////////////判断是否有操作权限///////////////////
            $manageUserId = Control::GetManageUserId();
            $manageUserAuthority = new ManageUserAuthorityManageData();
            $can = $manageUserAuthority->CanManageFtp($siteId, 0, $manageUserId);
            if ($can != 1) {
                Control::ShowMessage(Language::Load('document', 26), $tempContent);//您尚未开通操作此功能的权限，如需开通此权限，请联系管理人员！
                return "";
            }

            $ftpManageData = new FtpManageData();
            parent::ReplaceFirst($tempContent);
            if (!empty($_POST)) {
                $siteId = Control::PostRequest("f_SiteId", "0");

                $ftpHost = control::PostRequest("f_FtpHost", "");
                if($ftpHost!=""){
                        $newFtpId = $ftpManageData->Create($_POST);
                        //记入操作log
                        $operateContent = "Create ftp：ftpId：" . $newFtpId .",POST FORM:".implode("|",$_POST).";\r\nResult:". $newFtpId;
                        self::CreateManageUserLog($operateContent);

                        if ($newFtpId > 0) {
                            Control::ShowMessage(Language::Load('ftp', 1));//提交成功!
                            $closeTab = Control::PostRequest("CloseTab",0);
                            if($closeTab == 1){
                                $resultJavaScript .= Control::GetCloseTab();
                            }else{
                                Control::GoUrl($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']);
                            }
                        }else{
                            $resultJavaScript .= Control::GetJqueryMessage(Language::Load('ftp', 2));//提交失败!插入或修改数据库错误！
                        }
            }else{
                $resultJavaScript.=Control::GetJqueryMessage(Language::Load('ftp', 3));//ftp host为空！
            }
            }

            $replaceArr = array(
                "{SiteId}" => $siteId,
                "{SiteName}" => $siteName,
                "{TabIndex}" => $tabIndex,
                "{display}" => ""
            );
            $tempContent = strtr($tempContent, $replaceArr);

            $fieldsOfTable = $ftpManageData->GetFields();
            parent::ReplaceWhenCreate($tempContent, $fieldsOfTable);

            //替换掉{s XXX}的内容
            $patterns = '/\{s_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
        } else {
            $resultJavaScript .= Control::GetJqueryMessage(Language::Load('ftp', 5));//站点siteid错误！;
        }
        parent::ReplaceEnd($tempContent);
        $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
        return $tempContent;
    }


    /**
     * 编辑ftp
     * @return string 执行结果
     */
    private function GenModify() {
        $tempContent = Template::Load("ftp/ftp_deal.html","common");
        $siteId = Control::GetRequest("site_id", "-1");
        $siteName = Control::GetRequest("site_name", "");
        $resultJavaScript="";
        $tabIndex = Control::GetRequest("tab_index", 1);
        $ftpId= Control::GetRequest("ftp_id",-1);
        $ftpId=intval($ftpId);
        if($ftpId>0){
            if($siteId>=0){

                ///////////////判断是否有操作权限///////////////////
                $manageUserId = Control::GetManageUserId();
                $manageUserAuthority = new ManageUserAuthorityManageData();
                $can = $manageUserAuthority->CanManageFtp($siteId, 0, $manageUserId);
                if ($can != 1) {
                    Control::ShowMessage(Language::Load('document', 26), $tempContent);//您尚未开通操作此功能的权限，如需开通此权限，请联系管理人员！
                    return "";
                }

                $ftpData = new FtpManageData();
                parent::ReplaceFirst($tempContent);
                if (!empty($_POST)) {
                    $siteId = Control::PostRequest("f_SiteId", "0");
                    $ftpHost = control::PostRequest("f_FtpHost", "");
                    if($ftpHost!=""){

                        $Modified = $ftpData->Modify($_POST,$ftpId);


                        //记入操作log
                        $operateContent = "Modify ftp：FtpId：" . $ftpId .",POST FORM:".implode("|",$_POST).";\r\nResult:". $Modified;
                        self::CreateManageUserLog($operateContent);

                        if ($Modified > 0) {
                            Control::ShowMessage(Language::Load('ftp', 1));//提交成功!
                            $closeTab = Control::PostRequest("CloseTab",0);
                            if($closeTab == 1){
                                $resultJavaScript .= Control::GetCloseTab();
                            }else{
                                Control::GoUrl($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']);
                            }
                        }else{
                            $resultJavaScript .= Control::GetJqueryMessage(Language::Load('ftp', 2));//提交失败!插入或修改数据库错误！
                        }
                    }else{
                        $resultJavaScript.=Control::GetJqueryMessage(Language::Load('ftp', 3));//ftp host为空！
                    }
                }

                $replaceArr = array(
                    "{SiteId}" => $siteId,
                    "{SiteName}" => $siteName,
                    "{TabIndex}" => $tabIndex,
                    "{display}" => "none"
                );
                $tempContent = strtr($tempContent, $replaceArr);


                $arrOneFtp = $ftpData->GetOne($ftpId);
                if(!empty($arrOneFtp)){
                    Template::ReplaceOne($tempContent, $arrOneFtp);
                }else{
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('ftp', 4));//ftp原有数据获取失败！请谨慎修改！
                }



                //替换掉{s XXX}的内容
                $patterns = '/\{s_(.*?)\}/';
                $tempContent = preg_replace($patterns, "", $tempContent);
            } else {
                $resultJavaScript .= Control::GetJqueryMessage(Language::Load('ftp', 5));//站点siteid参数出错！;
            }
        }else{
            $resultJavaScript .= Control::GetJqueryMessage(Language::Load('ftp', 6));//ftp id错误！;
        }
        parent::ReplaceEnd($tempContent);
        $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
        return $tempContent;
    }


    /**
     * ftp分页列表
     * @return string 列表页面html
     */
    private function GenDelete(){
        $result = "";
        $ftpId = Control::GetRequest("table_id", 0);
        $ftpManageData = new FtpManageData();
        ///////////////判断是否有操作权限///////////////////
        $siteId=$ftpManageData->GetSiteId($ftpId);
        $manageUserId = Control::GetManageUserId();
        $manageUserAuthority = new ManageUserAuthorityManageData();
        $can = $manageUserAuthority->CanManageFtp($siteId, 0, $manageUserId);
        if ($can != 1) {
            $result = Language::Load('document', 26);//您尚未开通操作此功能的权限，如需开通此权限，请联系管理人员！
            return Control::GetRequest("jsonpcallback","") . '({"result":"'.$result.'"})';
        }


        if ($ftpId > 0) {
            $result = $ftpManageData->Delete($ftpId);
            //加入操作日志
            $operateContent = 'Delete ftp,Get FORM:' . implode('|', $_GET) . ';\r\nResult:ftp:' . $result;
            self::CreateManageUserLog($operateContent);
        } else {
            $result .= -1;
        }
        return Control::GetRequest("jsonpcallback","") . '({"result":"'.$result.'"})';
    }
    /**
     * ftp分页列表
     * @return string 列表页面html
     */
    private function GenList(){
        $resultJavaScript="";
        $tempContent = Template::Load("ftp/ftp_list.html","common");
        $siteId = Control::GetRequest("site_id", "0");
        $pageSize = Control::GetRequest("ps", 20);
        $pageIndex = Control::GetRequest("p", 1);
        $searchKey = Control::GetRequest("search_key", "");


        if ($pageIndex > 0 && $siteId > 0) {
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $listName = "ftp";
            $allCount = 0;

            ///////////////判断是否有操作权限///////////////////
            $manageUserId = Control::GetManageUserId();
            $manageUserAuthority = new ManageUserAuthorityManageData();
            $can = $manageUserAuthority->CanManageFtp($siteId, 0, $manageUserId);
            if ($can == 1) {
                $ftpManageData = new FtpManageData();
                $listOfFtpArray = $ftpManageData->GetListPager($siteId, $pageBegin, $pageSize, $allCount, $searchKey);
                if(count($listOfFtpArray)>0){
                    Template::ReplaceList($tempContent, $listOfFtpArray, $listName);

                    $styleNumber = 1;
                    $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html", "common");
                    $isJs = FALSE;
                    $navUrl = "default.php?secu=manage&mod=ftp&m=list&site_id=$siteId&p={0}&ps=$pageSize";
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
            $result = Language::Load('ftp', 3);
        }
        return $result;
    }

}
?>