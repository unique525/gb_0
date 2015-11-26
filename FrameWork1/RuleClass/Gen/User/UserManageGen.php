<?php

/**
 * 后台管理 会员 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_User
 * @author zhangchi
 */
class UserManageGen extends BaseManageGen implements IBaseManageGen
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

    private function GenCreate()
    {
        $siteId = Control::GetRequest("site_id", 0);
        $manageUserId = Control::GetManageUserId();
        if (intval($siteId) > 0 && intval($manageUserId) > 0) {
            $templateContent = Template::Load("user/user_deal.html", "common");

            parent::ReplaceFirst($templateContent);
            $templateContent = str_ireplace("{SiteId}", $siteId, $templateContent);
            $resultJavaScript = "";

            $userManageData = new UserManageData();
            $userInfoManageData = new UserInfoManageData();

            parent::ReplaceWhenCreate($templateContent, $userManageData->GetFields());
            if (!empty($_POST)) {
                $httpPostData = Format::FormatHtmlTagInPost($_POST);
                $userName = Control::PostRequest("f_UserName", "");
                $userPass = Control::PostRequest("f_UserPass", "");
                if ((preg_match("/^[\x{4e00}-\x{9fa5}\w]+$/u", $userName) || preg_match("/^([a-zA-Z0-9]*[-_]?[a-zA-Z0-9]+)*@([a-zA-Z0-9]*[-_]?[a-zA-Z0-9]+)+[\.][a-zA-Z0-9]{2,4}([\.][a-zA-Z0-9]{2,3})?$/u",$userName)) && preg_match("/^[a-z0-9A-z]{6,}$/", $userPass)) { //如果没有非法字符
                    $isExistSameUserName = $userManageData->CheckSameUserName($userName); //检查是否有重名的


                    if ($isExistSameUserName != 0) { //如果有重名的
                        $resultJavaScript .= Control::GetJqueryMessage(Language::Load('user', 20));
                    } else { //没有重名的
                        $result = $userManageData->Create($httpPostData, $siteId);

                        //加入操作日志
                        $operateContent = 'Create User,POST FORM:'
                            . implode('|', $_POST) . ';\r\nResult:UserId:' . $result;
                        self::CreateManageUserLog($operateContent);

                        if ($result > 0) {
                            $resultCreateUserInfo = $userInfoManageData->Create($result);
                            //加入操作日志
                            $operateContent = 'Create UserInfo,POST FORM:'
                                . implode('|', $_POST) . ';\r\nResult:UserId:' . $result;
                            self::CreateManageUserLog($operateContent);

                            if ($resultCreateUserInfo > 0) {
                                $closeTab = Control::PostRequest("CloseTab", 0);
                                if ($closeTab == 1) {
                                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('user', 30)) . Control::GetCloseTab();
                                } else {
                                    Control::GoUrl($_SERVER["PHP_SELF"] . "?" . $_SERVER['QUERY_STRING']);
                                }
                            } else {
                                $resultJavaScript .= Control::GetJqueryMessage(Language::Load('user', 31));
                            }
                        } else {
                            $resultJavaScript .= Control::GetJqueryMessage(Language::Load('user', 31));
                        }
                    }
                } else {
                    $resultJavaScript = Control::GetJqueryMessage(Language::Load('user', 41));
                }
            }
            parent::ReplaceEnd($templateContent);
            $templateContent = str_ireplace("{display_by_method}", 'style="display:none"', $templateContent);
            $templateContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $templateContent);
            return $templateContent;
        } else {
            return null;
        }
    }

    /**
     * 生成会员管理列表页面
     */
    private function GenList()
    {
        $siteId = Control::GetRequest("site_id", 0);
        if ($siteId <= 0) {
            return null;
        }
        $manageUserId = Control::GetManageUserId();

        ///////////////判断是否有操作权限///////////////////
        $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
        $channelId = 0;
        $canExplore = $manageUserAuthorityManageData->CanUserExplore($siteId, $channelId, $manageUserId);
        if (!$canExplore) {
            return Language::Load('document', 26);
        }
        //load template
        $templateContent = Template::Load("user/user_list.html", "common");

        parent::ReplaceFirst($templateContent);

        $pageSize = Control::GetRequest("ps", 20);
        $pageIndex = Control::GetRequest("p", 1);
        $searchKey = Control::GetRequest("search_key", "");
        $searchType = Control::GetRequest("search_type", -1);
        $searchKey = urldecode($searchKey);

        if ($pageIndex > 0 && $siteId > 0) {
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $tagId = "user_list";
            $allCount = 0;
            $userManageData = new UserManageData();
            $arrUserList = $userManageData->GetList($siteId, $pageBegin, $pageSize, $allCount, $searchKey, $searchType, $manageUserId);
            if (count($arrUserList) > 0) {
                Template::ReplaceList($templateContent, $arrUserList, $tagId);
                $styleNumber = 1;
                $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html", "common");
                $isJs = FALSE;
                $navUrl = "default.php?secu=manage&mod=user&m=list&site_id=$siteId&p={0}&ps=$pageSize";
                $jsFunctionName = "";
                $jsParamList = "";
                $pagerButton = Pager::ShowPageButton($pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex, $styleNumber, $isJs, $jsFunctionName, $jsParamList);
                $templateContent = str_ireplace("{pager_button}", $pagerButton, $templateContent);
            } else {
                Template::RemoveCustomTag($templateContent, $tagId);
                $templateContent = str_ireplace("{pager_button}", Language::Load("document", 7), $templateContent);
            }
        }

        parent::ReplaceEnd($templateContent);
        return $templateContent;
    }

    /**
     * 编辑会员
     * @return string 模板内容页面
     */
    private function GenModify()
    {
        $userId = Control::GetRequest("user_id", 0);
        $siteId = Control::GetRequest("site_id", 0);

        $adminUserId = Control::GetManageUserId();

        $resultJavaScript = "";
        if (intval($userId) > 0 && intval($adminUserId) > 0) {
            $templateContent = Template::Load("user/user_deal.html", "common");
            parent::ReplaceFirst($templateContent);
            ///////////////判断是否有操作权限///////////////////
            $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
            $channelId = 0;
            $can = $manageUserAuthorityManageData->CanUserEdit($siteId, $channelId, $adminUserId);
            ////////////////////////////////////////////////////
            if (!$can) {
                $templateContent = Language::Load('user', 28);
            } else {
                $userManageData = new UserManageData();
                if (!empty($_POST)) { //提交
                    $httpPostData = Format::FormatHtmlTagInPost($_POST);
                    $userName = Control::PostRequest("f_UserName", "");
                    $userPass = Control::PostRequest("UserPass", "");

                    if($userName != ''){

                        if ((preg_match("/^[\x{4e00}-\x{9fa5}\w]+$/u", $userName) || preg_match("/^([a-zA-Z0-9]*[-_]?[a-zA-Z0-9]+)*@([a-zA-Z0-9]*[-_]?[a-zA-Z0-9]+)+[\.][a-zA-Z0-9]{2,4}([\.][a-zA-Z0-9]{2,3})?$/u",$userName)) && preg_match("/^[a-z0-9A-z]{6,}$/", $userPass)) { //如果没有非法字符
                            //老帐号名和新帐号名不同时，要检查是否已经存在
                            $oldUserName = Control::PostRequest("OldUserName", "");
                            $newUserName = Control::PostRequest("f_UserName", "");
                            if ($oldUserName != $newUserName) {
                                $hasCount = $userManageData->GetCountByUserNameNotNowUserId($newUserName, $userId);
                                if ($hasCount > 0) { //同站点下不许存在相同的用户名
                                    Control::ShowMessage(Language::Load('user', 20));
                                    return $templateContent;
                                }
                            }

                            $result = $userManageData->Modify($httpPostData, $userId);
                            if ($result > 0) {
                                //加入操作日志
                                $operateContent = 'Modify User,POST FORM:' . implode('|', $_POST) . ';\r\nResult:UserId:' . $result;
                                self::CreateManageUserLog($operateContent);
                                if(strlen($userPass)>0){
                                    //修改md5加密的密码
                                    $userManageData->ModifyUserPass(
                                        $userId,
                                        $userPass
                                    );
                                }

                                $resultJavaScript .= Control::GetJqueryMessage(Language::Load('user', 7));
                                $closeTab = Control::PostRequest("CloseTab", 0);
                                if ($closeTab == 1) {
                                    //Control::CloseTab();
                                    $resultJavaScript .= Control::GetCloseTab();
                                } else {
                                    Control::GoUrl($_SERVER["PHP_SELF"] . "?" . $_SERVER['QUERY_STRING']);
                                }
                            } else {
                                $resultJavaScript .= Control::GetJqueryMessage(Language::Load('user', 8));
                            }
                        } else {
                            $resultJavaScript = Control::GetJqueryMessage(Language::Load('user', 41));
                        }
                    }
                    else{

                        $result = $userManageData->Modify($httpPostData, $userId);
                        if ($result > 0) {
                            //加入操作日志
                            $operateContent = 'Modify User,POST FORM:' . implode('|', $_POST) . ';\r\nResult:UserId:' . $result;
                            self::CreateManageUserLog($operateContent);
                            if(strlen($userPass)>0){
                                //修改md5加密的密码
                                $userManageData->ModifyUserPass(
                                    $userId,
                                    $userPass
                                );
                            }

                            $resultJavaScript .= Control::GetJqueryMessage(Language::Load('user', 7));
                            $closeTab = Control::PostRequest("CloseTab", 0);
                            if ($closeTab == 1) {
                                //Control::CloseTab();
                                $resultJavaScript .= Control::GetCloseTab();
                            } else {
                                Control::GoUrl($_SERVER["PHP_SELF"] . "?" . $_SERVER['QUERY_STRING']);
                            }
                        } else {
                            $resultJavaScript .= Control::GetJqueryMessage(Language::Load('user', 8));
                        }
                    }

                }

                $arrOne = $userManageData->GetOne($userId);
                $parentName = "";
                if (count($arrOne) > 0) {
                    $parentId = intval($arrOne["ParentId"]);
                    if ($parentId > 0) {
                        $parentName = $userManageData->GetUserName($parentId, false);
                    }
                }

                $templateContent = str_ireplace("{UserId}", $userId, $templateContent);
                $templateContent = str_ireplace("{ParentName}", $parentName, $templateContent);
                $isList = false;
                $isManage = false;
                Template::ReplaceOne($templateContent, $arrOne, $isList, $isManage);

            }
            parent::ReplaceEnd($templateContent);
            $templateContent = strtr($templateContent, "{display_by_method}", '');
            $templateContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $templateContent);
            return $templateContent;
        } else {
            return null;
        }
    }


}

?>
