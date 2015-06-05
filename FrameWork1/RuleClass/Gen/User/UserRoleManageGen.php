<?php
/**
 * 后台管理 会员角色 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_User
 * @author zhangchi
 */
class UserRoleManageGen extends BaseManageGen implements IBaseManageGen {
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
        $canExplore = $manageUserAuthorityManageData->CanUserRoleExplore($siteId, $channelId, $manageUserId);
        if (!$canExplore) {
            return Language::Load('document', 26);
        }
        //load template
        $templateContent = Template::Load("user/user_role_list.html", "common");

        parent::ReplaceFirst($templateContent);

        $pageSize = Control::GetRequest("ps", 20);
        $pageIndex = Control::GetRequest("p", 1);
        $searchKey = Control::GetRequest("search_key", "");
        $searchType = Control::GetRequest("search_type", -1);
        $searchKey = urldecode($searchKey);

        if ($pageIndex > 0 && $siteId > 0) {
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $tagId = "user_role_list";
            $userRoleManageData = new UserRoleManageData();
            $arrUserList = $userRoleManageData->GetList($siteId, $pageBegin, $pageSize, $searchKey, $searchType, $searchType);

            $allCount = $userRoleManageData->GetCount($siteId, $searchKey, $searchType);

            if (count($arrUserList) > 0) {
                Template::ReplaceList($templateContent, $arrUserList, $tagId);
                $styleNumber = 1;
                $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html", "common");
                $isJs = FALSE;
                $navUrl = "default.php?secu=manage&mod=user_role&m=list&site_id=$siteId&p={0}&ps=$pageSize";
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

        $manageUserId = Control::GetManageUserId();

        $resultJavaScript = "";
        if (intval($userId) > 0 && intval($manageUserId) > 0) {
            $templateContent = Template::Load("user/user_role_deal.html", "common");
            parent::ReplaceFirst($templateContent);
            ///////////////判断是否有操作权限///////////////////
            $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
            $channelId = 0;
            $can = $manageUserAuthorityManageData->CanUserRoleEdit($siteId, $channelId, $manageUserId);
            ////////////////////////////////////////////////////
            if (!$can) {
                $templateContent = Language::Load('user', 28);
            } else {
                $userRoleManageData = new UserRoleManageData();
                if (!empty($_POST)) { //提交
                    $newUserGroupId = intval(Control::PostRequest("select_UserGroupId", ""));


                    $result = $userRoleManageData->CreateOrModify(
                        $userId,
                        $newUserGroupId,
                        $siteId
                    );

                    if($result>0){

                        Control::ShowMessage(Language::Load("user_role",1));

                    }


                }

                $userGroupManageData = new UserGroupManageData();
                $arrUserGroup = $userGroupManageData->GetList($siteId,0,1000,$allCount);

                Template::ReplaceList($templateContent, $arrUserGroup, "user_group_list");


                $oldUserGroupId = $userRoleManageData->GetUserGroupId($userId, $siteId);

                $templateContent = str_ireplace("{UserId}", $userId, $templateContent);
                $templateContent = str_ireplace("{OldUserGroupId}", $oldUserGroupId, $templateContent);
                $templateContent = str_ireplace("{SiteId}", $siteId, $templateContent);



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