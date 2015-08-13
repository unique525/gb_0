<?php

/**
 * 后台管理 站点自定义页面 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Site
 * @author zhangchi
 */
class SiteContentManageGen extends BaseManageGen implements IBaseManageGen {
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
            case "remove_to_bin":
                $result = self::GenRemoveToBin();
                break;
            case "list":
                $result = self::GenList();
                break;
            case "async_publish":
                $result = self::AsyncPublish();
                break;
            case "async_modify_state":
                $result = self::AsyncModifyState();
                break;
        }

        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    /**
     * 新增
     * @return string 模板内容页面
     */
    private function GenCreate()
    {
        $manageUserId = Control::GetManageUserId();
        $channelId = Control::GetRequest("channel_id", 0);

        $pageIndex = Control::GetRequest("p", 1);
        $tabIndex = Control::GetRequest("tab_index", 0);

        $resultJavaScript = "";

        if ($manageUserId > 0 && $channelId>0) {
            $siteContentManageData = new SiteContentManageData();
            $templateContent = Template::Load("site/site_content_deal.html", "common");
            parent::ReplaceFirst($templateContent);

            $channelManageData = new ChannelManageData();
            $siteId = $channelManageData->GetSiteId($channelId, false);

            $templateContent = str_ireplace("{ChannelId}", $channelId, $templateContent);
            $templateContent = str_ireplace("{SiteId}", $siteId, $templateContent);


            if (!empty($_POST)) {

                $httpPostData = $_POST;

                $siteContentId = $siteContentManageData->Create($httpPostData,$manageUserId);
                //加入操作日志
                $operateContent = 'Create Site Content,POST FORM:'
                    . implode('|', $_POST) . ';\r\nResult:siteContentId:' . $siteContentId;
                self::CreateManageUserLog($operateContent);

                if ($siteContentId > 0) {

                    $closeTab = Control::PostRequest("CloseTab", 0);
                    if ($closeTab == 1) {
                        //$resultJavaScript .= Control::GetCloseTab();
                        Control::GoUrl("/default.php?secu=manage&mod=site_content&m=list&channel_id=$channelId&tab_index=$tabIndex&p=$pageIndex");
                    } elseif($closeTab == 2){
                        Control::GoUrl("/default.php?secu=manage&mod=site_content&m=modify&site_content_id=$siteContentId&tab_index=$tabIndex&p=$pageIndex");
                    } else {
                        Control::GoUrl($_SERVER["PHP_SELF"] . "?" . $_SERVER['QUERY_STRING']);
                    }
                } else {
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('site_content', 3)); //新增失败！

                }

            }

            $fields = $siteContentManageData->GetFields();
            parent::ReplaceWhenCreate($templateContent, $fields);

            $patterns = '/\{s_(.*?)\}/';
            $templateContent = preg_replace($patterns, "", $templateContent);




        } else {
            $templateContent = Language::Load("site_content", 8);
        }

        parent::ReplaceEnd($templateContent);
        $templateContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $templateContent);

        return $templateContent;


    }

    /**
     * 编辑
     * @return string 模板内容页面
     */
    private function GenModify()
    {
        $templateContent = Template::Load("site/site_content_deal.html", "common");
        $siteContentId = Control::GetRequest("site_content_id", 0);


        $pageIndex = Control::GetRequest("p", 1);
        $tabIndex = Control::GetRequest("tab_index", 0);

        $resultJavaScript = "";
        $manageUserId = Control::GetManageUserId();

        if ($siteContentId > 0 && $manageUserId > 0) {

            parent::ReplaceFirst($templateContent);

            $siteContentManageData = new SiteContentManageData();

            //加载原有数据
            $arrOne = $siteContentManageData->GetOne($siteContentId);
            Template::ReplaceOne($templateContent, $arrOne);

            $channelId = 0;
            if (count($arrOne)>0){
                $channelId = intval($arrOne["ChannelId"]);
            }


            if (!empty($_POST)) {
                $httpPostData = $_POST;

                $result = $siteContentManageData->Modify($httpPostData, $siteContentId);
                //加入操作日志
                $operateContent = 'Modify Site Content,POST FORM:'
                    . implode('|', $_POST) . ';\r\nResult:result:' . $result;
                self::CreateManageUserLog($operateContent);

                if ($result > 0) {

                    //删除缓冲
                    parent::DelAllCache();
                    $closeTab = Control::PostRequest("CloseTab", 0);
                    if ($closeTab == 1) {
                        //$resultJavaScript .= Control::GetCloseTab();
                        Control::GoUrl("/default.php?secu=manage&mod=site_content&m=list&channel_id=$channelId&tab_index=$tabIndex&p=$pageIndex");
                    } elseif($closeTab == 2){
                        Control::GoUrl("/default.php?secu=manage&mod=site_content&m=modify&site_content_id=$siteContentId&tab_index=$tabIndex&p=$pageIndex");
                    } else {
                        Control::GoUrl($_SERVER["PHP_SELF"] . "?" . $_SERVER['QUERY_STRING']);
                    }
                } else {
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('site', 4)); //编辑失败！
                }
            }

            $patterns = '/\{s_(.*?)\}/';
            $templateContent = preg_replace($patterns, "", $templateContent);
        }
        parent::ReplaceEnd($templateContent);
        $templateContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $templateContent);
        return $templateContent;
    }


    /**
     * 修改文档状态 状态值定义在Data类中
     * @return string 返回Jsonp修改结果
     */
    private function AsyncModifyState()
    {
        $result = -1;
        $siteContentId = Control::GetRequest("site_content_id", 0);
        $state = Control::GetRequest("state", -1);
        $manageUserId = Control::GetManageUserId();

        if ($siteContentId > 0 && $state >= 0 && $manageUserId > 0) {
            $siteContentManageData = new SiteContentManageData();
            $channelId = $siteContentManageData->GetChannelId($siteContentId, false);
            $siteId = $siteContentManageData->GetSiteId($siteContentId, false);

            //判断权限
            /**********************************************************************
             ******************************判断是否有操作权限**********************
             **********************************************************************/
            $manageUserAuthorityManageData = new ManageUserAuthorityManageData();

            $can = $manageUserAuthorityManageData->CanChannelModify($siteId, $channelId, $manageUserId);
            if (!$can) {
                $result = -10;
            } else {
                $result = $siteContentManageData->ModifyState($siteContentId, $state);
                //加入操作日志
                $operateContent = 'Modify State Site Content,GET PARAM:'
                    . implode('|', $_GET) . ';\r\nResult:' . $result;
                self::CreateManageUserLog($operateContent);
            }
        }
        return Control::GetRequest("jsonpcallback", "") . '({"result":' . $result . '})';
    }


    /**
     * 返回列表页面
     * @return string 模板内容页面
     */
    private function GenList()
    {
        $manageUserId = Control::GetManageUserId();

        $channelId = Control::GetRequest("channel_id", 0);
        $siteId = Control::GetRequest("site_id", 0);
        $channelManageData = new ChannelManageData();
        $siteId = $channelManageData->GetSiteId($channelId, false);

        ///////////////判断是否有操作权限///////////////////
        $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
        $canExplore = $manageUserAuthorityManageData->CanChannelExplore($siteId, $channelId, $manageUserId);
        if (!$canExplore) {
            Language::Load("site_content", 7);
        }

        //load template
        $templateContent = Template::Load("site/site_content_list.html", "common");

        parent::ReplaceFirst($templateContent);

        ////////////////////////////////////////////////////
        ///////////////输出权限到页面///////////////////
        ////////////////////////////////////////////////////

        $pageSize = Control::GetRequest("ps", 20);
        $pageIndex = Control::GetRequest("p", 1);
        $searchKey = Control::GetRequest("search_key", "");
        $searchType = Control::GetRequest("search_type", -1);
        $searchKey = urldecode($searchKey);

        if ($pageIndex > 0) {

            $templateContent = str_ireplace("{ChannelId}", $channelId, $templateContent);

            $pageBegin = ($pageIndex - 1) * $pageSize;
            $tagId = "site_content_list";
            $allCount = 0;
            $siteContentManageData = new SiteContentManageData();
            $arrList = $siteContentManageData->GetList(
                $siteId,
                $pageBegin,
                $pageSize,
                $allCount,
                $searchKey,
                $searchType
            );
            if (count($arrList) > 0) {
                Template::ReplaceList($templateContent, $arrList, $tagId);

                $styleNumber = 1;
                $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html", "common");
                $isJs = FALSE;
                $navUrl = "default.php?secu=manage&mod=site_content&m=list&channel_id=$channelId&p={0}&ps=$pageSize";
                $jsFunctionName = "";
                $jsParamList = "";
                $pagerButton = Pager::ShowPageButton(
                    $pagerTemplate,
                    $navUrl,
                    $allCount,
                    $pageSize,
                    $pageIndex,
                    $styleNumber,
                    $isJs,
                    $jsFunctionName,
                    $jsParamList
                );

                $templateContent = str_ireplace(
                    "{pager_button}",
                    $pagerButton,
                    $templateContent
                );

            } else {
                Template::RemoveCustomTag($templateContent, $tagId);
                $templateContent = str_ireplace("{pager_button}",
                    Language::Load("site_content", 9), $templateContent);
            }
        }

        parent::ReplaceEnd($templateContent);
        return $templateContent;
    }



    /**
     * 发布资讯详细页面
     * @return string 返回发布结果
     */
    private function AsyncPublish()
    {
        $result = -1;
        $siteContentId = Control::GetRequest("site_content_id", -1);
        if ($siteContentId > 0) {
            $publishQueueManageData = new PublishQueueManageData();
            $executeTransfer = true;
            $publishChannel = true;
            $result = parent::PublishSiteContent($siteContentId, $publishQueueManageData, $executeTransfer, $publishChannel);

            if ($result == abs(DefineCode::PUBLISH) + BaseManageGen::PUBLISH_SITE_CONTENT_RESULT_FINISHED) {
                //for ($i = 0; $i < count($publishQueueManageData->Queue); $i++) {
                //    $publishQueueManageData->Queue[$i]["Content"] = "";
                //}

                $result = '';
                for ($i = 0;$i< count($publishQueueManageData->Queue); $i++) {

                    $publishResult = "";

                    if(intval($publishQueueManageData->Queue[$i]["Result"]) ==
                        abs(DefineCode::PUBLISH) + BaseManageGen::PUBLISH_TRANSFER_RESULT_SUCCESS
                    ){
                        $publishResult = "Ok";
                    }


                    $result .= $publishQueueManageData->Queue[$i]["DestinationPath"].' -> '.$publishResult
                        .'<br />'
                    ;
                }
                //print_r($publishQueueManageData->Queue);
            }
        }
        return $result;
    }
} 