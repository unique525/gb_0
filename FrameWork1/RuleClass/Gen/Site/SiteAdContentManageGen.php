<?php

/**
 * 广告内容页面生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_CustomForm
 * @author 525
 */

class SiteAdContentManageGen extends BaseManageGen implements IBaseManageGen {

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
     * 新增广告
     * @return string 执行结果
     */
    private function GenCreate() {

        $tempContent = Template::Load("site/site_ad_content_deal.html","common");
        $resultJavaScript="";
        $siteAdId=Control::GetRequest("site_ad_id",-1);
        $tabIndex = Control::GetRequest("tab_index", 1);
        $siteId=Control::GetRequest("site_id","");
        $siteName=Control::GetRequest("site_name","");
        $siteAdName=Control::GetRequest("site_ad_name","");
        $widthHeight=Control::GetRequest("width_height","");


        parent::ReplaceFirst($tempContent);
        $siteAdContentManageData = new SiteAdContentManageData();
        if (intval($siteAdId) > 0) {
            if (!empty($_POST)) {
                $warningWhenSuccess="";
                $newSiteAdContentId = $siteAdContentManageData->Create($_POST);

                //记入操作log
                $operateContent = "Create site_ad_content：SiteAdContentId：" . $newSiteAdContentId .",POST FORM:".implode("|",$_POST).";\r\nResult:". $newSiteAdContentId;
                self::CreateManageUserLog($operateContent);


                if ($newSiteAdContentId > 0) {
                    //重新格式化内容
                    $siteAdContent=$siteAdContentManageData->GetContent($newSiteAdContentId);
                    $siteAdType=Control::PostRequest("f_SiteAdType","");//广告文件类型0:GIF,1:SWF,2:SWFT透明,3:SWFO降级,4:MMS
                    if($siteAdContent!=""){
                        $siteAdContent=strtolower($siteAdContent);
                        $contentSet=-1;
                        switch($siteAdType){
                            case 0:
                                $contentFile = substr($siteAdContent, strpos($siteAdContent, '<img'));//格式化广告内容字段 删除多余内容，保留第一个图片标签或其他广告内容标签
                                $contentFile = substr($contentFile, 0, strpos($contentFile, "/>") + strlen("/>"));
                                $contentSet=$siteAdContentManageData->ModifyContent($newSiteAdContentId,$contentFile);
                                break;
                            case 1:
                                $contentFile = substr($siteAdContent, strpos($siteAdContent, "<embed"));
                                $contentFile = substr($contentFile, 0, strpos($contentFile, "</embed>") + strlen("</embed>"));
                                $contentSet=$siteAdContentManageData->ModifyContent($newSiteAdContentId,$contentFile);
                                break;
                            case 2:
                                $contentFile = substr($siteAdContent, strpos($siteAdContent, "<embed"));
                                $contentFile = substr($contentFile, 0, strpos($contentFile, "</embed>") + strlen("</embed>"));
                                $contentSet=$siteAdContentManageData->ModifyContent($newSiteAdContentId,$contentFile);
                                break;
                            case 3:
                                $contentFile = substr($siteAdContent, strpos($siteAdContent, "<embed"));
                                $contentFile = substr($contentFile, 0, strpos($contentFile, "</embed>") + strlen("</embed>"));
                                $contentSet=$siteAdContentManageData->ModifyContent($newSiteAdContentId,$contentFile);
                                break;
                            case 4:
                                $contentFile = substr($siteAdContent, strpos($siteAdContent, "<embed"));
                                $contentFile = substr($contentFile, 0, strpos($contentFile, "</embed>") + strlen("</embed>"));
                                $contentSet=$siteAdContentManageData->ModifyContent($newSiteAdContentId,$contentFile);
                                break;
                        }
                        if($contentSet<0){
                            $warningWhenSuccess .= Language::Load('site_ad', 15);//重新格式化内容失败！广告内容可能错误显示！
                        }
                    }


                    //设置题图
                    $uploadFiles = Control::PostRequest("UploadFiles", "");
                    $arrayOfUploadFile = explode(",", $uploadFiles);
                    if (count($arrayOfUploadFile) > 0) {
                        $last=count($arrayOfUploadFile)-1;
                        $titlePicSet=$siteAdContentManageData->ModifyTitlePic($newSiteAdContentId,$arrayOfUploadFile[$last]);//最后一张的upload file id
                        if($titlePicSet<0){
                            $warningWhenSuccess .= Language::Load('site_ad', 7);//设置题图失败！
                        }
                    }

                    Control::ShowMessage($warningWhenSuccess.Language::Load('site_ad', 1));//提交成功!
                    $closeTab = Control::PostRequest("CloseTab",0);
                    if($closeTab == 1){
                        Control::GoUrl('/default.php?secu=manage&mod=site_ad_content&m=list&site_id='.$siteId.'&site_name='.$siteName.'&site_ad_id='.$siteAdId.'&site_ad_name='.$siteAdName.'&width_height='.$widthHeight);
                        //$resultJavaScript .= Control::GetCloseTab();
                    }else if($closeTab == 2){ //确认并更新js
                        Control::GoUrl('/default.php?secu=manage&mod=site_ad_content&m=list&site_id='.$siteId.'&site_name='.$siteName.'&site_ad_id='.$siteAdId.'&site_ad_name='.$siteAdName.'&width_height='.$widthHeight.'&update_js=1');
                    }else{
                        Control::GoUrl($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']);
                    }
                }else{
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('site_ad', 2));//提交失败!插入或修改数据库错误！
                }
            }



            $crateDate=date('Y-m-d H:i:s');
            $replace_arr = array(
                "{SiteAdId}" => $siteAdId,
                "{TabIndex}" => $tabIndex,
                "{CreateDate}" => $crateDate,
                "{SiteId}" => $siteId,
                "{SiteName}" => $siteName,
                "{SiteAdName}" => $siteAdName,
                "{WidthHeight}" => $widthHeight,
                "{ResidenceTime}"=>"5", //广告默认停留时间设为5
                "{display}" => ""
            );
            $tempContent = strtr($tempContent, $replace_arr);


            $fieldsOfSiteAdContent = $siteAdContentManageData->GetFields();
            parent::ReplaceWhenCreate($tempContent, $fieldsOfSiteAdContent);

            //替换掉{s XXX}的内容
            $patterns = '/\{s_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
        } else {
            $resultJavaScript .= Control::GetJqueryMessage(Language::Load('site_ad', 6));//广告位site_ad_id错误！;
        }
        parent::ReplaceEnd($tempContent);
        $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
        return $tempContent;
    }

    /**
     * 编辑广告
     * @return string 执行结果
     */
    private function GenModify() {
        $tempContent = Template::Load("site/site_ad_content_deal.html","common");
        $resultJavaScript="";
        $siteAdId=Control::GetRequest("site_ad_id",-1);
        $tabIndex = Control::GetRequest("tab_index", 1);
        $siteAdContentId= Control::GetRequest("site_ad_content_id",-1);
        $siteId=Control::GetRequest("site_id","");
        $siteName=Control::GetRequest("site_name","");
        $siteAdName=Control::GetRequest("site_ad_name","");
        $widthHeight=Control::GetRequest("width_height","");
        $siteAdContentId=intval($siteAdContentId);


        parent::ReplaceFirst($tempContent);
        $siteAdContentManageData = new SiteAdContentManageData();

        if (intval($siteAdId) > 0) {
            if (!empty($_POST)) {
                $warningWhenSuccess="";

                $modifySuccess = $siteAdContentManageData->Modify($_POST, $siteAdContentId);

                //记入操作log
                $operateContent = "Modify site_ad_content：SiteAdContentId：" . $siteAdContentId .",POST FORM:".implode("|",$_POST).";\r\nResult:". $modifySuccess;
                self::CreateManageUserLog($operateContent);

                if ($modifySuccess > 0) {

                    //重新格式化内容
                    $siteAdContent=$siteAdContentManageData->GetContent($siteAdContentId);
                    $siteAdType=Control::PostRequest("f_SiteAdType","");//广告文件类型0:GIF,1:SWF,2:SWFT透明,3:SWFO降级,4:html
                    if($siteAdContent!=""){
                        $siteAdContent=strtolower($siteAdContent);
                        $contentSet=-1;
                        switch($siteAdType){
                            case 0:
                                $contentFile = substr($siteAdContent, strpos($siteAdContent, '<img'));//格式化广告内容字段 删除多余内容，保留第一个图片标签或其他广告内容标签  &lt;img  /&gt;
                                $contentFile = substr($contentFile, 0, strpos($contentFile, "/>") + strlen("/>"));
                                $contentSet=$siteAdContentManageData->ModifyContent($siteAdContentId,$contentFile);
                                break;
                            case 1:
                                $contentFile = substr($siteAdContent, strpos($siteAdContent, "<embed"));
                                $contentFile = substr($contentFile, 0, strpos($contentFile, "/>") + strlen("/>"));
                                $contentSet=$siteAdContentManageData->ModifyContent($siteAdContentId,$contentFile);
                                break;
                            case 2:
                                $contentFile = substr($siteAdContent, strpos($siteAdContent, "<embed"));
                                $contentFile = substr($contentFile, 0, strpos($contentFile, "</embed>") + strlen("/>"));
                                $contentSet=$siteAdContentManageData->ModifyContent($siteAdContentId,$contentFile);
                                break;
                            case 3:
                                $contentFile = substr($siteAdContent, strpos($siteAdContent, "<embed"));
                                $contentFile = substr($contentFile, 0, strpos($contentFile, "/>") + strlen("/>"));
                                $contentSet=$siteAdContentManageData->ModifyContent($siteAdContentId,$contentFile);
                                break;
                            case 4:
                                $contentSet=1;
                                break;
                        }
                        if($contentSet<0){
                            $warningWhenSuccess .= Language::Load('site_ad', 15);//重新格式化内容失败！广告内容可能错误显示！
                        }
                    }



                    //设置题图
                    $uploadFiles = Control::PostRequest("UploadFiles", "");
                    $arrayOfUploadFile = explode(",", $uploadFiles);
                    if (count($arrayOfUploadFile) > 0) {
                        $last=count($arrayOfUploadFile)-1;
                        $titlePicSet=$siteAdContentManageData->ModifyTitlePic($siteAdContentId,$arrayOfUploadFile[$last]);
                        if($titlePicSet<0){
                            $warningWhenSuccess .= Language::Load('site_ad', 7);//设置题图失败！
                        }
                    }

                    Control::ShowMessage($warningWhenSuccess.Language::Load('site_ad', 1));//提交成功!
                    $closeTab = Control::PostRequest("CloseTab",0);
                    if($closeTab == 1){
                        Control::GoUrl('/default.php?secu=manage&mod=site_ad_content&m=list&site_id='.$siteId.'&site_name='.$siteName.'&site_ad_id='.$siteAdId.'&site_ad_name='.$siteAdName.'&width_height='.$widthHeight);
                        //$resultJavaScript .= Control::GetCloseTab();
                    }else if($closeTab == 2){ //确认并更新js
                        Control::GoUrl('/default.php?secu=manage&mod=site_ad_content&m=list&site_id='.$siteId.'&site_name='.$siteName.'&site_ad_id='.$siteAdId.'&site_ad_name='.$siteAdName.'&width_height='.$widthHeight.'&update_js=1');
                    }else{
                        Control::GoUrl($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']);
                    }
                }else{
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('site_ad', 2));//提交失败!插入或修改数据库错误！
                }
            }

            $arrOneSiteAdContent = $siteAdContentManageData->GetOne($siteAdContentId);
            if(!empty($arrOneSiteAdContent)){
                Template::ReplaceOne($tempContent, $arrOneSiteAdContent);
            }else{
                $resultJavaScript .= Control::GetJqueryMessage(Language::Load('site_ad', 4));//原有数据获取失败！请谨慎修改！
            }



            $siteAdManageData=new SiteAdManageData();
            $arrayOfOneSiteAd=$siteAdManageData->GetOne($siteAdId);
            $replace_arr = array(
                "{SiteAdWidth}" => $arrayOfOneSiteAd["SiteAdWidth"],
                "{SiteAdHeight}" => $arrayOfOneSiteAd["SiteAdHeight"],
                "{TabIndex}" => $tabIndex,
                "{SiteId}" => $siteId,
                "{SiteName}" => $siteName,
                "{SiteAdId}" => $siteAdId,
                "{SiteAdName}" => $siteAdName,
                "{WidthHeight}" => $widthHeight,
                "{display}" => "none"
            );
            $tempContent = strtr($tempContent, $replace_arr);

            //替换掉{s XXX}的内容
            $patterns = '/\{s_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
        } else {
            $resultJavaScript .= Control::GetJqueryMessage(Language::Load('site_ad', 6));//广告位site_ad_id错误！;
        }
        parent::ReplaceEnd($tempContent);
        $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
        return $tempContent;
    }


    /**
     * 广告分页列表
     * @return string 执行结果
     */
    private function GenList() {
        $siteAdId = Control::GetRequest("site_ad_id", 0);
        $resultJavaScript="";
        $tempContent = Template::Load("site/site_ad_content_list.html","common");
        $siteAdContentManageData=new SiteAdContentManageData();
        if(intval($siteAdId)>0){
            $pageSize = Control::GetRequest("ps", 20);
            $pageIndex = Control::GetRequest("p", 1);
            $searchKey = Control::GetRequest("search_key", "");
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $allCount = 0;
            $listName = "site_ad_content";
            $listOfSiteAdContentArray=$siteAdContentManageData->GetListPager($siteAdId,$pageBegin,$pageSize,$allCount,$searchKey);
            if(count($listOfSiteAdContentArray)>0){
                Template::ReplaceList($tempContent, $listOfSiteAdContentArray, $listName);

                $styleNumber = 1;
                $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html", "common");
                $isJs = FALSE;
                $navUrl = "default.php?secu=manage&mod=site_ad_content&m=list&site_ad_id=$siteAdId&p={0}&ps=$pageSize";
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
            $resultJavaScript.=Control::GetJqueryMessage(Language::Load('site_ad', 6));//广告位site_ad_id错误！
        }
        parent::ReplaceEnd($tempContent);
        $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
        $result = $tempContent;
        return $result;
    }


    /**
     * 修改广告位状态
     * @return string 修改结果
     */
    private function ModifyState()
    {
        //$result = -1;
        $siteAdContentId = Control::GetRequest("table_id", 0);
        $state = Control::GetRequest("state",0);
        if ($siteAdContentId > 0) {
            $siteAdContentManageData = new SiteAdContentManageData();
            $result = $siteAdContentManageData->ModifyState($siteAdContentId,$state);
            //加入操作日志
            $operateContent = 'ModifyState site_ad_content,Get FORM:' . implode('|', $_GET) . ';\r\nResult:site_ad:' . $result;
            self::CreateManageUserLog($operateContent);
        } else {
            $result = -1;
        }
        return Control::GetRequest("jsonpcallback","") . '({"result":"'.$result.'"})';
    }
}

?>