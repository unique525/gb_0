<?php

/**
 * 后台管理 管理员帐号 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Manage
 * @author zhangchi
 */
class ManageUserManageGen extends BaseManageGen implements IBaseManageGen
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
            case "modify_password":
                $result = self::GenModifyUserPass();
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

        $resultJavaScript = "";

        if ($manageUserId > 0) {
            $manageUserManageData = new ManageUserManageData();
            $tempContent = Template::Load("manage/manage_user_deal.html", "common");
            parent::ReplaceFirst($tempContent);

            if (!empty($_POST)) {

                $httpPostData = $_POST;

                $manageUserIdOfNew = $manageUserManageData->Create($httpPostData);
                //加入操作日志
                $operateContent = 'Create Manage User,POST FORM:' . implode('|', $_POST) . ';\r\nResult:manageUserId:' . $manageUserIdOfNew;
                self::CreateManageUserLog($operateContent);

                if ($manageUserIdOfNew > 0) {

                    $closeTab = Control::PostRequest("CloseTab", 0);
                    if ($closeTab == 1) {
                        //Control::CloseTab();
                        $resultJavaScript .= Control::GetCloseTab();
                    } else {
                        Control::GoUrl($_SERVER["PHP_SELF"] . "?" . $_SERVER['QUERY_STRING']);
                    }
                } else {
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('manage_user', 3)); //新增失败！

                }

            }

            $manageUserGroupManageData = new ManageUserGroupManageData();
            $arrayOfManageUserGroupList = $manageUserGroupManageData->GetAll();
            $listName = "manage_user_group_list";
            Template::ReplaceList($tempContent, $arrayOfManageUserGroupList, $listName);

            $fields = $manageUserManageData->GetFields();
            parent::ReplaceWhenCreate($tempContent, $fields);

            $patterns = '/\{s_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);

            parent::ReplaceEnd($tempContent);


            $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);

        } else {
            $tempContent = Language::Load("manage_user", 9);
        }
        return $tempContent;
    }

    /**
     * 编辑
     * @return string 模板内容页面
     */
    private function GenModify()
    {
        $tempContent = Template::Load("manage/manage_user_deal.html", "common");
        $manageUserIdOfGet = Control::GetRequest("manage_user_id", 0);
        $resultJavaScript = "";
        $manageUserId = Control::GetManageUserId();

        if ($manageUserIdOfGet > 0 && $manageUserId > 0) {

            parent::ReplaceFirst($tempContent);

            $manageUserManageData = new ManageUserManageData();


            if (!empty($_POST)) {
                $httpPostData = $_POST;

                $result = $manageUserManageData->Modify($httpPostData, $manageUserIdOfGet);
                //加入操作日志
                $operateContent = 'Modify Manage User,POST FORM:' . implode('|', $_POST) . ';\r\nResult:result:' . $result;
                self::CreateManageUserLog($operateContent);

                if ($result > 0) {

                    //删除缓冲
                    parent::DelAllCache();
                    $closeTab = Control::PostRequest("CloseTab", 0);
                    if ($closeTab == 1) {
                        $resultJavaScript .= Control::GetCloseTab();
                    } else {
                        Control::GoUrl($_SERVER["PHP_SELF"] . "?" . $_SERVER['QUERY_STRING']);
                    }
                } else {
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('manage_user', 4)); //编辑失败！
                }
            }

            $manageUserGroupManageData = new ManageUserGroupManageData();
            $arrayOfManageUserGroupList = $manageUserGroupManageData->GetAll();
            $listName = "manage_user_group_list";
            Template::ReplaceList($tempContent, $arrayOfManageUserGroupList, $listName);


            //加载原有数据
            $arrOne = $manageUserManageData->GetOne($manageUserIdOfGet);
            Template::ReplaceOne($tempContent, $arrOne);

            $patterns = '/\{s_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
        }
        parent::ReplaceEnd($tempContent);
        $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
        return $tempContent;
    }


    private function GenModifyUserPass()
    {
        $templateContent = "";
        $manageUserId = Control::GetManageUserId();
        $resultJavaScript = "";

        if ($manageUserId > 0) {
            $templateContent = Template::Load("manage/manage_user_modify_manage_user_pass.html", "common");
            parent::ReplaceFirst($templateContent);

            $templateContent = str_ireplace("{ManageUserName}", Control::GetManageUserName(), $templateContent);

            if (!empty($_POST)) {

                $manageUserPassOfOld = Control::PostRequest("f_ManageUserPassOfOld", "");
                $manageUserPassOfNew = Control::PostRequest("f_ManageUserPassOfNew", "");

                $manageUserManageData = new ManageUserManageData();
                $currentManageUserPass = $manageUserManageData->GetManageUserPass($manageUserId, false);
                if ($currentManageUserPass == $manageUserPassOfOld) {
                    $result = $manageUserManageData->ModifyManageUserPass($manageUserId, $manageUserPassOfNew);
                    if ($result > 0) {
                        $resultJavaScript = Control::GetJqueryMessage(Language::Load('manage_user', 13));
                    } else {
                        $resultJavaScript = Control::GetJqueryMessage(Language::Load('manage_user', 14));
                    }
                } else {
                    $resultJavaScript = Control::GetJqueryMessage(Language::Load('manage_user', 15));
                }

            }

            parent::ReplaceEnd($templateContent);
            $templateContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $templateContent);
        }


        return $templateContent;
    }


    /**
     * 修改状态 状态值定义在Data类中
     * @return string 返回Jsonp修改结果
     */
    private function AsyncModifyState()
    {
        $result = -1;
        $manageUserIdOfGet = Control::GetRequest("manage_user_id", 0);
        $state = Control::GetRequest("state", -1);
        $manageUserId = Control::GetManageUserId();

        if ($manageUserIdOfGet > 0 && $state >= 0 && $manageUserId > 0) {
            //判断权限
            /**********************************************************************
             ******************************判断是否有操作权限**********************
             **********************************************************************/
            $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
            $siteId = 0;
            $channelId = 0;
            $can = $manageUserAuthorityManageData->CanManageUserModify($siteId, $channelId, $manageUserId);
            if (!$can) {
                $result = -10;
            } else {
                $manageUserManageData = new ManageUserManageData();
                $result = $manageUserManageData->ModifyState($manageUserIdOfGet, $state);
                //加入操作日志
                $operateContent = 'Modify State Manage User,GET PARAM:' . implode('|', $_GET) . ';\r\nResult:' . $result;
                self::CreateManageUserLog($operateContent);
            }
        }
        return Control::GetRequest("jsonpcallback", "") . '({"result":' . $result . '})';
    }


    /**
     * 返回列表页面
     * @return mixed|string
     */
    private function GenList()
    {
        $manageUserId = Control::GetManageUserId();

        $siteId = 0;
        $channelId = 0;

        ///////////////判断是否有操作权限///////////////////
        $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
        $canExplore = $manageUserAuthorityManageData->CanManageUserExplore($siteId, $channelId, $manageUserId);
        if (!$canExplore) {
            return "";
        }

        //load template
        $tempContent = Template::Load("manage/manage_user_list.html", "common");

        parent::ReplaceFirst($tempContent);

        ////////////////////////////////////////////////////
        ///////////////输出权限到页面///////////////////
        ////////////////////////////////////////////////////

        $pageSize = Control::GetRequest("ps", 40);
        $pageIndex = Control::GetRequest("p", 1);
        $searchKey = Control::GetRequest("search_key", "");
        $searchType = Control::GetRequest("search_type", -1);
        $searchKey = urldecode($searchKey);

        $manageUserManageData = new ManageUserManageData();


        if ($pageIndex > 0) {

            $manageUserGroupId = Control::GetRequest("manage_user_group_id", -1);

            $pageBegin = ($pageIndex - 1) * $pageSize;
            $tagId = "manage_user_list";
            $allCount = 0;

            $arrList = $manageUserManageData->GetList($pageBegin, $pageSize, $allCount, $searchKey, $searchType, $manageUserGroupId);
            if (count($arrList) > 0) {
                Template::ReplaceList($tempContent, $arrList, $tagId);

                $styleNumber = 1;
                $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html", "common");
                $isJs = FALSE;
                $navUrl = "default.php?secu=manage&mod=manage_user&manage_user_group_id=$manageUserGroupId&m=list&p={0}&ps=$pageSize";
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
                $tempContent = str_ireplace("{pager_button}", Language::Load("manage_user", 8), $tempContent);
            }
        }

        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }
} 