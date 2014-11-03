<?php

/**
 * 广告位页面生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_CustomForm
 * @author 525
 */

class SiteAdManageGen extends BaseManageGen implements IBaseManageGen {




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
            case "create_js":
                $result = self::GenCreateJs();
                break;
            case "pre_show":
                $result = self::GenPreShow();
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
     * 新增广告位
     * @return string 执行结果
     */
    private function GenCreate(){

        $tempContent = Template::Load("site/site_ad_deal.html","common");
        $resultJavaScript="";
        $siteId = Control::GetRequest("site_id", 0);
        $siteName = Control::GetRequest("site_name", "");
        $tabIndex = Control::GetRequest("tab_index", 1);


        parent::ReplaceFirst($tempContent);
        $siteAdManageData = new SiteAdManageData();

        if (intval($siteId) > 0) {
            if (!empty($_POST)) {
                $newSiteAdId = $siteAdManageData->Create($_POST);

                //记入操作log
                $operateContent = "Create site_ad：SiteAdId：" . $newSiteAdId .",POST FORM:".implode("|",$_POST).";\r\nResult:". $newSiteAdId;
                self::CreateManageUserLog($operateContent);

                if ($newSiteAdId > 0) {


                    Control::ShowMessage(Language::Load('site_ad', 1));//提交成功!
                    $closeTab = Control::PostRequest("CloseTab",0);
                    if($closeTab == 1){
                        $resultJavaScript .= Control::GetCloseTab();
                    }else{
                        Control::GoUrl($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']);
                    }
                }else{
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('site_ad', 2));//提交失败!插入或修改数据库错误！
                }
            }



            $crateDate=date('Y-m-d H:i:s');
            $replaceArr = array(
                "{SiteId}" => $siteId,
                "{SiteName}" => $siteName,
                "{TabIndex}" => $tabIndex,
                "{CreateDate}" => $crateDate,
                "{display}" => ""
            );
            $tempContent = strtr($tempContent, $replaceArr);

            $siteAdManageData=new SiteAdManageData();
            $fieldsOfSiteAd = $siteAdManageData->GetFields();
            parent::ReplaceWhenCreate($tempContent, $fieldsOfSiteAd);

            //替换掉{s XXX}的内容
            $patterns = '/\{s_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
        } else {
            $resultJavaScript .= Control::GetJqueryMessage(Language::Load('site_ad', 5));//站点siteid错误！;
        }
        parent::ReplaceEnd($tempContent);
        $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
        return $tempContent;
    }

    /**
     * 编辑广告位
     * @return string 执行结果
     */
    private function GenModify(){
        $tempContent = Template::Load("site/site_ad_deal.html","common");
        $resultJavaScript="";
        $siteId = Control::GetRequest("site_id", 0);
        $siteName = Control::GetRequest("site_name", "");
        $tabIndex = Control::GetRequest("tab_index", 1);
        $siteAdId= Control::GetRequest("site_ad_id",-1);
        $siteAdId=intval($siteAdId);


        parent::ReplaceFirst($tempContent);
        $siteAdManageData = new SiteAdManageData();

        if (intval($siteId) > 0) {
            if (!empty($_POST)) {
                $modifySuccess = $siteAdManageData->Modify($_POST, $siteAdId);

                //记入操作log
                $operateContent = "Modify site_ad：SiteAdId：" . $siteAdId .",POST FORM:".implode("|",$_POST).";\r\nResult:". $modifySuccess;
                self::CreateManageUserLog($operateContent);

                if ($modifySuccess > 0) {


                    Control::ShowMessage(Language::Load('site_ad', 1));//提交成功!
                    $closeTab = Control::PostRequest("CloseTab",0);
                    if($closeTab == 1){
                        $resultJavaScript .= Control::GetCloseTab();
                    }else{
                        Control::GoUrl($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']);
                    }
                }else{
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('site_ad', 2));//提交失败!插入或修改数据库错误！
                }
            }

            $arrOneSiteAd = $siteAdManageData->GetOne($siteAdId);
            if(!empty($arrOneSiteAd)){
                Template::ReplaceOne($tempContent, $arrOneSiteAd);
            }else{
                $resultJavaScript .= Control::GetJqueryMessage(Language::Load('site_ad', 4));//广告位原有数据获取失败！请谨慎修改！
            }



            $replaceArr = array(
                "{SiteName}" => $siteName,
                "{TabIndex}" => $tabIndex,
                "{display}" => "none"
            );
            $tempContent = strtr($tempContent, $replaceArr);


            //替换掉{s XXX}的内容
            $patterns = '/\{s_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
        } else {
            $resultJavaScript .= Control::GetJqueryMessage(Language::Load('site_ad', 5));//站点siteid错误！;
        }
        parent::ReplaceEnd($tempContent);
        $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
        return $tempContent;
    }

    /**
     * 分页列表广告位
     * @return string 执行结果
     */
    private function GenList(){
    $siteId = Control::GetRequest("site_id", 0);
    $siteName = Control::GetRequest("site_name", "");
    $resultJavaScript="";
    $tempContent = Template::Load("site/site_ad_list.html","common");
    $siteAdManageData=new SiteAdManageData();
    if(intval($siteId)>0){
        $pageSize = Control::GetRequest("ps", 20);
        $pageIndex = Control::GetRequest("p", 1);
        $searchKey = Control::GetRequest("search_key", "");
        $pageBegin = ($pageIndex - 1) * $pageSize;
        $allCount = 0;
        $listName = "site_ad";
        $listOfSiteAdArray=$siteAdManageData->GetListPager($siteId,$pageBegin,$pageSize,$allCount,$searchKey);
        if(count($listOfSiteAdArray)>0){
            Template::ReplaceList($tempContent, $listOfSiteAdArray, $listName);

            $styleNumber = 1;
            $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html", "common");
            $isJs = FALSE;
            $navUrl = "default.php?secu=manage&mod=site_ad&m=list&site_id=$siteId&p={0}&ps=$pageSize";
            $jsFunctionName = "";
            $jsParamList = "";
            $pagerButton = Pager::ShowPageButton($pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex ,$styleNumber = 1, $isJs, $jsFunctionName, $jsParamList);


            $replaceArr = array(
                "{f_SiteName}"=>$siteName,
                "{PagerButton}" => $pagerButton
            );
            $tempContent = strtr($tempContent, $replaceArr);
        }else{
            Template::RemoveCustomTag($tempContent, $listName);
            $tempContent = str_ireplace("{PagerButton}", Language::Load("document", 7), $tempContent);
        }
    }else{
        $resultJavaScript.=Control::GetJqueryMessage(Language::Load('site_ad', 3));//站点id错误！
    }
    parent::ReplaceEnd($tempContent);
    $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
    $result = $tempContent;
    return $result;
    }


    /**
     * 生成广告位独立调用JS
     * @return string
     */
    private function GenCreateJs() {
        $result="";
        $messageWindowLastingTime=5000; //提示页面5秒后自动关闭
        $siteAdId = intval(Control::GetRequest("site_ad_id", 0));


        $warns="";
        $publishQueueManageData = new PublishQueueManageData();

        $result = parent::PublishSiteAd($siteAdId, $publishQueueManageData, $warns);
        echo $warns;
        echo "<br>".$result;
        if($warns==""){
            $jsCode = 'setTimeout("window.close()",'.$messageWindowLastingTime.');';
            Control::RunJavascript($jsCode);
        }
        return "";
    }


    /**
     * 修改广告位状态
     * @return string 修改结果
     */
    private function ModifyState()
    {
        //$result = -1;
        $siteAdId = Control::GetRequest("table_id", 0);
        $state = Control::GetRequest("state",0);
        if ($siteAdId > 0) {
            $siteAdManageData = new SiteAdManageData();
            $result = $siteAdManageData->ModifyState($siteAdId,$state);
            //加入操作日志
            $operateContent = 'ModifyState site_ad,Get FORM:' . implode('|', $_GET) . ';\r\nResult:site_ad:' . $result;
            self::CreateManageUserLog($operateContent);
        } else {
            $result = -1;
        }
        return Control::GetRequest("jsonpcallback","") . '({"result":"'.$result.'"})';
    }

    /**
     * URL链接处理
     * @param string $url
     * @return string
     */
    private function GenCheckUrl($url) {
        $result = $url;
        if (!empty($url)) {
            $_pos = stripos($result, "http://");
            if ($_pos === false) {
                $result = "http://" . $result;
            }
        }
        return $result;
    }


    /**
     * 预览
     * @return string 预览页面
     */
    private function GenPreShow() {
        $result="";
        $siteAdId=Control::GetRequest("site_ad_id","0");
        $siteId=Control::GetRequest("site_id","0");
        if(intval($siteId)>0){
            if(intval($siteAdId)>0){
                $tempContent = Template::Load("site/site_ad_pre_show.html","common");
                $replaceArr = array(
                    "{SiteId}"=>$siteId,
                    "{SiteAdId}" => $siteAdId
                );
                $tempContent = strtr($tempContent, $replaceArr);
                parent::ReplaceEnd($tempContent);
                $result=$tempContent;
            }else{
                $result.=Language::Load('site_ad', 6);//广告位site_ad_id错误！
            }
        }else{
            $result.=Language::Load('site_ad', 5);//站点siteid错误！
        }
        return $result;
    }
}
?>