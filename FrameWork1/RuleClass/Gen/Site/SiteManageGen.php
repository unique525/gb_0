<?php

/**
 * 后台管理 站点 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Site
 * @author zhangchi
 */
class SiteManageGen extends BaseManageGen implements IBaseManageGen
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
            case "remove_to_bin":
                $result = self::GenRemoveToBin();
                break;
            case "list":
                $result = self::GenList();
                break;
            case "async_modify_state":
                $result = self::AsyncModifyState();
                break;
        }

        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    /**
     * 新增站点
     * @return string 模板内容页面
     */
    private function GenCreate()
    {
        $manageUserId = Control::GetManageUserId();
        $tabIndex = Control::GetRequest("tab_index", 0);
        $resultJavaScript = "";

        if ($manageUserId > 0) {
            $siteManageData = new SiteManageData();
            $tempContent = Template::Load("site/site_deal.html", "common");
            parent::ReplaceFirst($tempContent);

            if (!empty($_POST)) {

                $httpPostData = $_POST;

                $siteId = $siteManageData->Create($httpPostData);
                //加入操作日志
                $operateContent = 'Create Site,POST FORM:' . implode('|', $_POST) . ';\r\nResult:siteId:' . $siteId;
                self::CreateManageUserLog($operateContent);

                if ($siteId > 0) {
                    //删除缓冲
                    parent::DelAllCache();

                    if (!empty($_FILES)) {
                        //title pic1
                        $fileElementName = "file_title_pic";
                        $tableType = UploadFileData::UPLOAD_TABLE_TYPE_SITE; //site
                        $tableId = $siteId;
                        $uploadFile1 = new UploadFile();
                        $uploadFileId = 0;
                        $titlePic1Result = self::Upload(
                            $fileElementName,
                            $tableType,
                            $tableId,
                            $uploadFile1,
                            $uploadFileId
                        );

                        if (intval($titlePic1Result) <= 0) {
                            //上传出错或没有选择文件上传
                        } else {

                        }

                        if ($uploadFileId > 0) {
                            $siteManageData->ModifyTitlePic($siteId, $uploadFileId);
                        }


                    }

                    $channelManageData = new ChannelManageData();
                    $channelManageData->CreateWhenSiteCreate($siteId,$manageUserId,Language::Load('site',10));

                    $closeTab = Control::PostRequest("CloseTab", 0);
                    if ($closeTab == 1) {
                        //Control::CloseTab();
                        //$resultJavaScript .= Control::GetCloseTab();
                        Control::GoUrl("/default.php?secu=manage&mod=site&m=list&tab_index=$tabIndex");
                    } else {
                        Control::GoUrl($_SERVER["PHP_SELF"] . "?" . $_SERVER['QUERY_STRING']);
                    }
                } else {
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('site', 3)); //新增失败！

                }

            }

            $fields = $siteManageData->GetFields();
            parent::ReplaceWhenCreate($tempContent, $fields);

            $patterns = '/\{s_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);

            parent::ReplaceEnd($tempContent);


            $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);

        } else {
            $tempContent = Language::Load("site", 8);
        }
        return $tempContent;
    }

    /**
     * 编辑
     * @return string 模板内容页面
     */
    private function GenModify()
    {
        $tempContent = Template::Load("site/site_deal.html", "common");
        $siteId = Control::GetRequest("site_id", 0);
        $tabIndex = Control::GetRequest("tab_index", 0);
        $resultJavaScript = "";
        $manageUserId = Control::GetManageUserId();

        if ($siteId > 0 && $manageUserId > 0) {

            parent::ReplaceFirst($tempContent);

            $siteManageData = new SiteManageData();

            //加载原有数据
            $arrOne = $siteManageData->GetOne($siteId);
            Template::ReplaceOne($tempContent, $arrOne);


            if (!empty($_POST)) {
                $httpPostData = $_POST;

                $result = $siteManageData->Modify($httpPostData, $siteId);
                //加入操作日志
                $operateContent = 'Modify Site,POST FORM:' . implode('|', $_POST) . ';\r\nResult:result:' . $result;
                self::CreateManageUserLog($operateContent);

                if ($result > 0) {
                    //题图操作
                    if (!empty($_FILES)) {
                        //title pic
                        $fileElementName = "file_title_pic";
                        $tableType = UploadFileData::UPLOAD_TABLE_TYPE_SITE;
                        $tableId = $siteId;
                        $uploadFile = new UploadFile();
                        $uploadFileId = 0;
                        $titlePic1Result = self::Upload(
                            $fileElementName,
                            $tableType,
                            $tableId,
                            $uploadFile,
                            $uploadFileId
                        );

                        if (intval($titlePic1Result) <= 0 && $uploadFileId <= 0) {
                            //上传出错或没有选择文件上传
                        } else {
                            //删除原有题图
                            $oldUploadFileId1 = $siteManageData->GetTitlePicUploadFileId($siteId, false);
                            parent::DeleteUploadFile($oldUploadFileId1);

                            //修改题图
                            $siteManageData->ModifyTitlePicUploadFileId($siteId, $uploadFileId);
                        }
                    }

                    //删除缓冲
                    parent::DelAllCache();
                    $closeTab = Control::PostRequest("CloseTab", 0);
                    if ($closeTab == 1) {
                        //$resultJavaScript .= Control::GetCloseTab();
                        Control::GoUrl("/default.php?secu=manage&mod=site&m=list&tab_index=$tabIndex");
                    } else {
                        Control::GoUrl($_SERVER["PHP_SELF"] . "?" . $_SERVER['QUERY_STRING']);
                    }
                } else {
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('site', 4)); //编辑失败！
                }
            }

            $patterns = '/\{s_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
        }
        parent::ReplaceEnd($tempContent);
        $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
        return $tempContent;
    }


    /**
     * 修改文档状态 状态值定义在Data类中
     * @return string 返回Jsonp修改结果
     */
    private function AsyncModifyState()
    {
        $result = -1;
        $siteId = Control::GetRequest("site_id", 0);
        $state = Control::GetRequest("state", -1);
        $manageUserId = Control::GetManageUserId();

        if ($siteId > 0 && $state >= 0 && $manageUserId > 0) {
            //判断权限
            /**********************************************************************
             ******************************判断是否有操作权限**********************
             **********************************************************************/
            $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
            $channelId = 0;
            $can = $manageUserAuthorityManageData->CanManageSite($siteId, $channelId, $manageUserId);
            if (!$can) {
                $result = -10;
            } else {
                $siteManageData = new SiteManageData();
                $result = $siteManageData->ModifyState($siteId, $state);
                //删除缓冲
                parent::DelAllCache();
                //加入操作日志
                $operateContent = 'Modify State Site,GET PARAM:' . implode('|', $_GET) . ';\r\nResult:' . $result;
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

        ///////////////判断是否有操作权限///////////////////
        //$manageUserAuthorityManageData = new ManageUserAuthorityManageData();
        //$canExplore = $manageUserAuthorityManageData->CanExplore($siteId, $channelId, $manageUserId);
        //if (!$canExplore) {
        //    return ;
        //}

        //load template
        $tempContent = Template::Load("site/site_list.html", "common");

        parent::ReplaceFirst($tempContent);

        ////////////////////////////////////////////////////
        ///////////////输出权限到页面///////////////////
        ////////////////////////////////////////////////////

        $pageSize = Control::GetRequest("ps", 20);
        $pageIndex = Control::GetRequest("p", 1);
        $searchKey = Control::GetRequest("search_key", "");
        $searchType = Control::GetRequest("search_type", -1);
        $searchKey = urldecode($searchKey);

        if ($pageIndex > 0) {
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $tagId = "site_list";
            $allCount = 0;
            $siteManageData = new SiteManageData();
            $arrList = $siteManageData->GetList($pageBegin, $pageSize, $allCount, $searchKey, $searchType, $manageUserId);
            if (count($arrList) > 0) {
                Template::ReplaceList($tempContent, $arrList, $tagId);

                $styleNumber = 1;
                $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html", "common");
                $isJs = FALSE;
                $navUrl = "default.php?secu=manage&mod=site&m=list&p={0}&ps=$pageSize";
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

                $tempContent = str_ireplace(
                    "{pager_button}",
                    $pagerButton,
                    $tempContent
                );

            } else {
                Template::RemoveCustomTag($tempContent, $tagId);
                $tempContent = str_ireplace("{pager_button}", Language::Load("site", 9), $tempContent);
            }
        }

        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }
} 