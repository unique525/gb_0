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
            $replace_arr = array(
                "{SiteId}" => $siteId,
                "{SiteName}" => $siteName,
                "{TabIndex}" => $tabIndex,
                "{CreateDate}" => $crateDate,
                "{display}" => ""
            );
            $tempContent = strtr($tempContent, $replace_arr);

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



            $replace_arr = array(
                "{SiteName}" => $siteName,
                "{TabIndex}" => $tabIndex,
                "{display}" => "none"
            );
            $tempContent = strtr($tempContent, $replace_arr);


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


            $replace_arr = array(
                "{f_SiteName}"=>$siteName,
                "{PagerButton}" => $pagerButton
            );
            $tempContent = strtr($tempContent, $replace_arr);
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
        $siteId = intval(Control::GetRequest("site_id", 0));
        $siteAdId = intval(Control::GetRequest("site_ad_id", 0));


        //记入操作log
        $operateContent = "CreateJs site_ad：SiteAdId：" . $siteAdId .",POST FORM:".implode("|",$_POST).";\r\nResult:". "1";
        self::CreateManageUserLog($operateContent);


        if ($siteId > 0) {
            if ($siteAdId > 0) {

            }else{
                $result .= Language::Load('site_ad', 6);//广告位site_ad_id错误！;
            }
        }else{
            $result .= Language::Load('site_ad', 5);//站点siteid错误！;
        }


//$type 为0时返回js地址，否则输出生成成功提示信息
        $siteAdJs = self::GenJsFormatAd($siteAdId);
        //$siteAdJs=code($siteAdJs);
        //解jquery与社区的JS冲突问题
            $replace_arr = array(
                "$" => 'jQuery'
            );
            $siteAdJs = strtr($siteAdJs, $replace_arr);

        $tempDir = '/' . '/ad/' . $siteId;
        $source = $tempDir . '/cscms_' . $siteAdId . ".js";
        FileObject::CreateDir($tempDir);
        FileObject::Write($source, $siteAdJs);


            $siteAdContentManageData = new SiteAdContentManageData();
            $arrayOfSiteAdsToUpload = $siteAdContentManageData->GetList($siteAdId);  //获取广告位所有广告 准备发布上传
            //$_adminuserid = 1;
            //$_rank = 0;
            //$_parentid = 0;
            //$_documentChannelData = new DocumentChannelData();
            //$_documentChannelArr = $_documentChannelData->GetList($siteId, $_adminuserid, $_rank, $_parentid);
            //$_documentChannelID = $_documentChannelArr["0"]["DocumentChannelID"];
            //$_hasFtp = $_documentChannelData->GetHasFtp($_documentChannelID);
            //$_ftptype = 0;

//发布目录和程序目录在同一站点下时，不发布附件
            $isPubAttachment = TRUE; //是否发布附件

            $siteManageData = new SiteManageData();
            if (MANAGE_DOMAIN == WEBAPP_DOMAIN) { //管理平台和功能平台相同时，不发布附件
                $isPubAttachment = FALSE;


                $siteUrl = strtolower($siteManageData->GetSiteUrl($siteId,FALSE));

                $pos = stripos($siteUrl, MANAGE_DOMAIN); //siteUrl = http://www.xxx.com/  icmsDomain = http://www.xxx.com
                if ($pos === false) {//管理平台和目标网站不相同时，发布附件
                    $isPubAttachment = TRUE;
                }
            }

            //$subDomain = $siteManageData->GetSubDomain($siteId); //取得子域名
            //if (!empty($subDomain)) { //子域名不为空时，发布附件
            //    $isPubAttachment = TRUE;
            //}

            if (!$isPubAttachment) {
//发布目录和程序目录在同一站点下并且子域名为空时，不发布附件
            } else {
//发布图片信息到目标服务器
                for ($i = 0; $i < count($arrayOfSiteAdsToUpload); $i++) {
                    if (strlen($arrayOfSiteAdsToUpload[$i]["TitlePicUploadFileId"]) > 0) {
                        $titlePicUploadFileId = $arrayOfSiteAdsToUpload[$i]["TitlePicUploadFileId"];
                        //$_publishTitlePic=
                        //parent::Ftp($_publishTitlePic, $_publishTitlePic, "", $_documentChannelID, $_hasFtp, $_ftptype);
                    }
                }
            }

            $_publishFile = $source;      //目标路径
            //parent::FTP($_publishFile, $_publishFile, "", $_documentChannelID, $_hasFtp, $_ftptype);

        $result .= "/ad/" . $siteId . '/icms_' . $siteAdId . ".js";
        echo Language::Load('site_ad', 10) . "<br><br>" . $result;//广告JS更新成功
        $jsCode = 'setTimeout("window.close()",5000);';
        //Control::RunJavascript($jsCode);
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
     * JS方式格式化广告输出
     * @param int $siteAdId
     * @return string
     */
    private function GenJsFormatAd($siteAdId) {
        $siteAdId = intval($siteAdId);
        $result = "";
        if ($siteAdId > 0) {
            $siteAdManageData = new SiteAdManageData();
            $arrayOfOneSiteAd = $siteAdManageData->GetOne($siteAdId);
            if (count($arrayOfOneSiteAd) > 0) {
                if (intval($arrayOfOneSiteAd["State"]) === 0) {
                    $uploadUrl = "";
                    $showNumber = intval($arrayOfOneSiteAd["ShowNumber"]);//轮换类广告位是否显示轮换数字
                    $siteAdWidth = intval($arrayOfOneSiteAd["SiteAdWidth"]);
                    $siteAdHeight = intval($arrayOfOneSiteAd["SiteAdHeight"]);
                    $showType = intval($arrayOfOneSiteAd["ShowType"]);     //广告位类型 0:图片,1文字,2轮换 3随机,4落幕
                    $siteAdContentManageData = new SiteAdContentManageData();
                    if ($showType < 0) {
                        $showType = 0;
                    }
                    $arrayOfSiteAdContent = $siteAdContentManageData->GetAllAdContent($siteAdId);//取广告位下所有广告
                    if(count($arrayOfSiteAdContent)>0){
                        $tempContent = Template::Load("site/site_ad_js_show_type_" . intval($showType) . ".html","common"); //ShowType 0为图片 1文字 2轮换 3随机 4落幕

                        switch ($showType) {
                            case 0: //图片
                                $arrayOfOneSiteAdContent=$arrayOfSiteAdContent[0];  //取第一条广告
                                $titlePicUploadId=$arrayOfOneSiteAdContent["TitlePicUploadFileId"];
                                $uploadFileManageData=new UploadFileData();
                                $titlePicPath = $uploadFileManageData->GetUploadFilePath($titlePicUploadId,FALSE);//取得题图地址
                                $siteAdContentType=$arrayOfOneSiteAdContent["SiteAdType"];//取得广告类型 GIF SWF


                                if (intval($arrayOfOneSiteAdContent['SiteAdContentId']) > 0) {
                                    $siteAdContentInJs = self::GenFormatAdContentForJs($siteAdId, $arrayOfOneSiteAdContent["SiteAdContentId"], $uploadUrl . $titlePicPath, $siteAdContentType, $siteAdWidth, $siteAdHeight);


                                    $adContentJson = json_encode($arrayOfOneSiteAdContent);

                                    $replace_arr = array(
                                        //"{OpenVirtualClick}" => $arrayOfOneSiteAdContent["OpenVirtualClick"],
                                        //"{VirtualClickLimit}" => $arrayOfOneSiteAdContent["VirtualClickLimit"],
                                        //"{EndDate}" => $arrayOfOneSiteAdContent["EndDate"],
                                        //"{SiteAdContentPicUrl}" => $titlePicPath,
                                        "{SiteAdId}" => $siteAdId,
                                        "{WEBAPP_DOMAIN}" => WEBAPP_DOMAIN,
                                        "{SiteAdWidth}" => $siteAdWidth,
                                        "{SiteAdHeight}" => $siteAdHeight,
                                        "{SiteAdContentId}" => $arrayOfOneSiteAdContent["SiteAdContentId"],
                                        "{SiteAdUrl}" => self::GenCheckUrl($arrayOfOneSiteAdContent["SiteAdUrl"]),
                                        "{AddedVirtualClickCount}" => $arrayOfOneSiteAdContent["AddedVirtualClickCount"],
                                        "{SiteAdContentTitle}" => $arrayOfOneSiteAdContent["SiteAdContentTitle"],
                                        "{ResidenceTime}" => $arrayOfOneSiteAdContent["ResidenceTime"],
                                        "{SiteAdContentInJs}" => $siteAdContentInJs,
                                        "{SiteAdJson}" => $adContentJson
                                    );
                                    $tempContent = strtr($tempContent, $replace_arr);
                                } else {
                                    $result.=Language::Load('site_ad', 12); //广告site_ad_content_id错误!
                                }
                                break;
                            case 1: //文字
                                $adContentJson = json_encode($arrayOfSiteAdContent);
                                $listName = "site_ad_content_list";
                                Template::ReplaceList($tempContent, $arrayOfSiteAdContent, $listName);
                                $replace_arr = array(
                                    "{SiteAdId}" => $siteAdId,
                                    "{WEBAPP_DOMAIN}" => WEBAPP_DOMAIN,
                                    "{SiteAdWidth}" => $siteAdWidth,
                                    "{SiteAdHeight}" => $siteAdHeight,
                                    "{ShowNumber}" => $showNumber,
                                    "{SiteAdJson}" => $adContentJson
                                );
                                $tempContent = strtr($tempContent, $replace_arr);
                                //$result.=Language::Load('site_ad', 12); //广告site_ad_content_id错误!
                                break;
                            case 2:
                                break;
                            case 3:
                                break;
                            case 4:
                                break;
                        }
                        if ($showType === 0) {

                        } elseif (intval($arrayOfOneSiteAd["showtype"]) === 1) {      //文字广告

            } /*elseif (intval($arrayOfOneSiteAd["showtype"]) === 2) {      //2轮换
                $adcontentarr1 = $siteAdContentManageData->GetAdContent($siteAdId);
                if ($adcontentarr1 != null && count($adcontentarr1) > 0) {
                    $arrayOfOneSiteAdContent = array();
                    $arr = array();
                    for ($i = 0; $i < count($adcontentarr1); $i++) {
//$adtype = self::GenFormatAdType($adcontentarr1[$i]["adcontentid"], $_uploadurl . '/' . $adcontentarr1[$i]["titlepic"], $adcontentarr1[$i]["adtype"], $adwidth, $adheight);
                        $siteAdContentInJs = self::GenFormatAdType($siteAdId, $adcontentarr1[$i]["adcontentid"], $uploadUrl . $adcontentarr1[$i]["titlepic"], $adcontentarr1[$i]["adtype"], $siteAdWidth, $siteAdHeight);
                        $arr['adcont'] = $siteAdContentInJs;
                        $arr['adcontentid'] = $adcontentarr1[$i]["adcontentid"];
                        $arr['isadcount'] = $adcontentarr1[$i]["isadcount"];
                        $arr['adurl'] = self::GenCheckUrl($adcontentarr1[$i]["adurl"]);
                        $arr['adtitle'] = $adcontentarr1[$i]["adtitle"];
                        $arr['residencetime'] = $adcontentarr1[$i]["residencetime"];

                        $adarr['id'] = $adcontentarr1[$i]["adcontentid"];
                        $adarr['openvclick'] = $adcontentarr1[$i]["openvclick"];
                        $adarr['vclicklimit'] = $adcontentarr1[$i]["vclicklimit"];
                        $adarr['adurl'] = self::GenCheckUrl($adcontentarr1[$i]["adurl"]);
                        $adarr['enddate'] = $adcontentarr1[$i]["enddate"];
                        $adJson[] = $adarr;

                        $arrayOfOneSiteAdContent[] = $arr;
                    }
                    $adJson = json_encode($adJson);

                    $listName = "adlist";
                    Template::ReplaceList($tempContent, $arrayOfOneSiteAdContent, $listName);
                    $listName = "adlist_item";
                    Template::ReplaceList($tempContent, $arrayOfOneSiteAdContent, $listName);

                    $replace_arr = array(
                        "{adid}" => $siteAdId,
                        "{funcurl}" => $_funcurl,
                        "{adwidth}" => $siteAdWidth,
                        "{adheight}" => $siteAdHeight,
                        "{shownum}" => $showNumber,
                        "{adjson}" => $adJson
                    );
                    $tempContent = strtr($tempContent, $replace_arr);
                } else {
                    return $result;
                }
            } elseif (intval($arrayOfOneSiteAd["showtype"]) === 3) {                      //随机显示
                $arrayOfOneSiteAdContent = $siteAdContentManageData->GetRandAdContent($siteAdId);
                if ($arrayOfOneSiteAdContent != null && count($arrayOfOneSiteAdContent) > 0) {
                    $siteAdContentInJs = self::GenFormatAdType($siteAdId, $arrayOfOneSiteAdContent["adcontentid"], $uploadUrl . $arrayOfOneSiteAdContent["titlepic"], $arrayOfOneSiteAdContent["adtype"], $siteAdWidth, $siteAdHeight);
                    $adarr['id'] = $arrayOfOneSiteAdContent["adcontentid"];
                    $adarr['openvclick'] = $arrayOfOneSiteAdContent["openvclick"];
                    $adarr['vclicklimit'] = $arrayOfOneSiteAdContent["vclicklimit"];
                    $adarr['adurl'] = self::GenCheckUrl($arrayOfOneSiteAdContent["adurl"]);
                    $adarr['enddate'] = $arrayOfOneSiteAdContent["enddate"];
                    $adJson[] = $adarr;
                    $adJson = json_encode($adJson);

                    $listName = "adlist";
                    Template::ReplaceList($tempContent, $arrayOfOneSiteAdContent, $listName);
                    $replace_arr = array(
                        "{adid}" => $siteAdId,
                        "{funcurl}" => $_funcurl,
                        "{adwidth}" => $siteAdWidth,
                        "{adheight}" => $siteAdHeight,
                        "{adcontentid}" => $arrayOfOneSiteAdContent["adcontentid"],
                        "{adcontenisadcount}" => $arrayOfOneSiteAdContent["isadcount"],
                        "{adcontentpicurl}" => $arrayOfOneSiteAdContent["titlepic"],
                        "{adcontenttitle}" => $arrayOfOneSiteAdContent["adtitle"],
                        "{adcontenturl}" => self::GenCheckUrl($arrayOfOneSiteAdContent["adurl"]),
                        "{adcont}" => $siteAdContentInJs,
                        "{adjson}" => $adJson
                    );
                    $tempContent = strtr($tempContent, $replace_arr);
                } else {
                    return $result;
                }
            } elseif (intval($arrayOfOneSiteAd["showtype"]) === 4) {      //4落幕
                $arrayOfOneSiteAdContent = $siteAdContentManageData->GetOneAdContent($siteAdId);
                if ($arrayOfOneSiteAdContent != null && count($arrayOfOneSiteAdContent) > 0) {
                    $siteAdContentInJs = self::GenFormatAdType($siteAdId, $arrayOfOneSiteAdContent["adcontentid"], $uploadUrl . $arrayOfOneSiteAdContent["titlepic"], $arrayOfOneSiteAdContent["adtype"], $siteAdWidth, $siteAdHeight);
                    $adarr['id'] = $arrayOfOneSiteAdContent["adcontentid"];
                    $adarr['openvclick'] = $arrayOfOneSiteAdContent["openvclick"];
                    $adarr['vclicklimit'] = $arrayOfOneSiteAdContent["vclicklimit"];
                    $adarr['adurl'] = self::GenCheckUrl($arrayOfOneSiteAdContent["adurl"]);
                    $adarr['enddate'] = $arrayOfOneSiteAdContent["enddate"];
                    $adJson[] = $adarr;
                    $adJson = json_encode($adJson);
                    $replace_arr = array(
                        "{adid}" => $siteAdId,
                        "{url}" => self::GenCheckUrl($arrayOfOneSiteAdContent["adurl"]),
                        "{adwidth}" => $siteAdWidth,
                        "{adheight}" => $siteAdHeight,
                        "{residencetime}" => $arrayOfOneSiteAdContent["residencetime"],
                        "{funcurl}" => $_funcurl,
                        "{adcont}" => $siteAdContentInJs,
                        "{picurl}" => $arrayOfOneSiteAdContent["titlepic"],
                        "{adjson}" => $adJson
                    );
                    $tempContent = strtr($tempContent, $replace_arr);
                } else {
                    return $result;
                }
            }*/
                        $result .= $tempContent;
                    }else{
                        $result.=Language::Load('site_ad', 11); //该广告位没有可用的广告
                    }


                }else{
                    $result.=Language::Load('site_ad', 9);//当前操作对象不是启用状态！
                }
            }else{
                $result.=Language::Load('site_ad', 8);//获取该条记录数据失败！
            }

        }else{

            $result.=Language::Load('site_ad', 6);//广告位site_ad_id错误！
        }

        return $result;
    }



    /**
     * 按广告显示类型 分类处理广告内容
     * @param int $siteAdId 广告位id
     * @param int $siteAdContentId 广告id
     * @param string $JsFileUrl 广告图片、flash地址
     * @param string $siteAdType 广告文件类型
     * @param int $siteAdWidth 广告位宽度
     * @param int $siteAdHeight 广告位高度
     * @return string
     */
    private function GenFormatAdContentForJs($siteAdId, $siteAdContentId, $JsFileUrl, $siteAdType, $siteAdWidth, $siteAdHeight) {
        $result = "";
        //if ($siteAdId > 0) {
            if (WEBAPP_DOMAIN == MANAGE_DOMAIN) {
                $siteUrl = WEBAPP_DOMAIN;
            } else {
                $siteAdManageData = new SiteAdManageData();
                $siteId = $siteAdManageData->GetSiteID($siteAdId);
                $siteManageData = new SiteManageData();
                $siteUrl = $siteManageData->GetSiteUrl($siteId,FALSE);
                $siteUrl = trim($siteUrl);
                $siteUrl = strtolower($siteUrl);
                if (strlen($siteUrl) > 2) {
                    $end_str = substr($siteUrl, strlen($siteUrl) - 1, 1);    //处理最后一位
                    if ($end_str == "/") {
                        $siteUrl = substr($siteUrl, 0, strlen($siteUrl) - 1);
                    }
                    $_pos = stripos($siteUrl, "http://");
                    if ($_pos === false) {
                        $siteUrl = "http://" . $siteUrl;
                    }
                } else {
                    $siteUrl = "";
                }
            }
        //}
        switch ($siteAdType) {
            case "GIF":
                $result = "<img src='" . $siteUrl . $JsFileUrl . "' width='" . $siteAdWidth . "' border='0' height='" . $siteAdHeight . "' />";
                break;
            case "SWF":     //SWF默认模式
                $result = "<embed src='" . $siteUrl . $JsFileUrl . "' id='" . $siteAdContentId . "_SWF' width='" . $siteAdWidth . "' height='" . $siteAdHeight . "' type='application/x-shockwave-flash' pluginspage='http://www.macromedia.com/go/getflashplayer'></embed>";
                break;
            case "SWFT":    //SWF_透明
                $result = "<embed src='" . $siteUrl . $JsFileUrl . "' id='" . $siteAdContentId . "_SWFT' width='" . $siteAdWidth . "' height='" . $siteAdHeight . "' type='application/x-shockwave-flash' wmode='transparent' pluginspage='http://www.macromedia.com/go/getflashplayer'></embed>";
                break;
            case "SWFO":    //SWF_降级
                $result = "<embed src='" . $siteUrl . $JsFileUrl . "' id='" . $siteAdContentId . "_SWFO' width='" . $siteAdWidth . "' height='" . $siteAdHeight . "' type='application/x-shockwave-flash' wmode='opaque' pluginspage='http://www.macromedia.com/go/getflashplayer'></embed>";
                break;
            case "MMS":    //加载视频
                $result = "<embed src=" . $siteUrl . $JsFileUrl . " id='" . $siteAdContentId . "_MMS' width='" . $siteAdWidth . "' height='" . $siteAdHeight . "' type='application/x-mplayer2' console='Clip1' controls='IMAGEWINDOW,StatusBar' enablecontextmenu='0' showcontrols='0' showstatusbar='1' autostart='1'></embed>";
                break;
        }
        return $result;
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
}
?>