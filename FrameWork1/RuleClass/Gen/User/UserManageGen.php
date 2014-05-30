<?php

/**
 * 后台管理 会员 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_User
 * @author zhangchi
 */
class UserManageGen extends BaseManageGen implements IBaseManageGen {
    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function Gen() {
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
     * 生成会员管理列表页面
     */
    private function GenList() {
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
        $tempContent = Template::Load("user/user_list.html", "common");


        parent::ReplaceFirst($tempContent);

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
                Template::ReplaceList($tempContent, $arrUserList, $tagId);

                $styleNumber = 1;
                $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html", "common");
                $isJs = FALSE;
                $navUrl = "default.php?secu=manage&mod=user&m=list&site_id=$siteId&p={0}&ps=$pageSize";
                $jsFunctionName = "";
                $jsParamList = "";
                $pagerButton = Pager::ShowPageButton($pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex, $styleNumber, $isJs, $jsFunctionName, $jsParamList);
                $tempContent = str_ireplace("{pager_button}", $pagerButton, $tempContent);
            } else {
                Template::RemoveCustomTag($tempContent, $tagId);
                $tempContent = str_ireplace("{pager_button}", Language::Load("document", 7), $tempContent);
            }
        }

        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }

    /**
     * 编辑会员
     * @return string 模板内容页面
     */
    private function GenModify() {
        $tempContent = Template::Load("user/user_deal.html", "common");
        $userId = Control::GetRequest("user_id", 0);
        $siteId = Control::GetRequest("site_id", 0);

        $adminUserId = Control::GetManageUserId();

        if (intval($userId) > 0 && intval($adminUserId) > 0) {
            ///////////////判断是否有操作权限///////////////////
            $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
            $channelId = 0;
            $can = $manageUserAuthorityManageData->CanUserEdit($siteId, $channelId, $adminUserId);
            ////////////////////////////////////////////////////
            if (!$can) {
                $tempContent = Language::Load('user', 28);
            } else {
                parent::ReplaceFirst($tempContent);
                    $userManageData = new UserManageData();
                    if (!empty($_POST)) { //提交
                        $httpPostData = $_POST;
                        //老帐号名和新帐号名不同时，要检查是否已经存在
                        $oldUserName = Control::PostRequest("OldUserName", "");
                        $newUserName = Control::PostRequest("f_UserName", "");
                        if ($oldUserName != $newUserName) {
                            $hasCount = $userManageData->CheckExistNameForModify($newUserName, $userId);
                            if ($hasCount > 0) {//同站点下不许存在相同的用户名
                                Control::ShowMessage(Language::Load('user', 20));
                                return $tempContent;
                            }
                        }

                        $result = $userManageData->Modify($httpPostData, $userId);
                        if ($result > 0) {
                            Control::ShowMessage(Language::Load('user', 7));
                        } else {
                            Control::ShowMessage(Language::Load('user', 8));
                        }
                        $closeTab = Control::PostRequest("CloseTab",0);
                        if($closeTab == 1){
                            Control::CloseTab();
                        }
                        return "";
                    }

                    $arrOne = $userManageData->GetOne($userId);
                    $parentName = "";
                    if(count($arrOne)>0){
                        $parentId = intval($arrOne["ParentId"]);
                        if($parentId>0){
                            $parentName = $userManageData->GetUserName($parentId,false);
                        }
                    }

                $tempContent = str_ireplace("{UserId}", $userId, $tempContent);
                $tempContent = str_ireplace("{ParentName}", $parentName, $tempContent);
                $isList = false;
                $isManage = false;
                Template::ReplaceOne($tempContent, $arrOne, $isList, $isManage);

            }
            return $tempContent;
        }else{
            return null;
        }
    }


}

?>
