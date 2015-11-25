<?php

/**
 * 后台管理 站点 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Site
 * @author zhangchi
 */
class ActivityUserManageGen extends BaseManageGen implements IBaseManageGen
{
    public function Gen()
    {
        $result = "";
        $method = Control::GetRequest("m", "");
        switch ($method) {
            case "list":
                $result = self::GenList();
                break;
            case "modify_state":
                $result = self::ModifyState();
                break;
            case "delete_user":
                $result = self::DeleteUser();
                break;
        }

        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    private function GenList(){

        $activityId             = Control::GetRequest("activity_Id", 0);
        $resultJavaScript       = "";
        $tempContent            = Template::Load("activity/activity_user_list.html","common");
        $activityUserManageData = new ActivityUserManageData();

        if(intval($activityId)>0){
            $pageSize  = Control::GetRequest("ps", 20);
            $pageIndex = Control::GetRequest("p", 1);
            $searchKey = Control::GetRequest("search_key", "");
            $searchKey = urldecode($searchKey);
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $allCount  = 0;
            $listName  = "activity_user_list";

            ///////////////判断是否有操作权限///////////////////
            $activityManageData  = new ActivityManageData();
            $channelId           = $activityManageData->GetChannelId($activityId);
            $channelManageData   = new ChannelManageData();
            $siteId              = $channelManageData->GetSiteId($channelId,true);
            $manageUserId        = Control::GetManageUserId();
            $manageUserAuthority = new ManageUserAuthorityManageData();
            $can                 = $manageUserAuthority->CanManageUserModify($siteId, 0, $manageUserId);

            if ($can == 1) {
                $siteTagArray = $activityUserManageData->GetUserListPager($activityId,$pageBegin,$pageSize,$searchKey,$allCount);
                if(count($siteTagArray)>0){
                    Template::ReplaceList($tempContent, $siteTagArray, $listName);
                    $styleNumber = 1;
                    $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html", "common");
                    $isJs = FALSE;
                    $navUrl = "default.php?secu=manage&mod=activity_user&m=list&activity_id=$activityId&p={0}&ps=$pageSize";
                    $jsFunctionName = "";
                    $jsParamList = "";
                    $pagerButton = Pager::ShowPageButton($pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex ,$styleNumber = 1, $isJs, $jsFunctionName, $jsParamList);
                    $tempContent = str_ireplace("{PagerButton}", $pagerButton, $tempContent);
                }else{
                    Template::RemoveCustomTag($tempContent, $listName);
                    $tempContent = str_ireplace("{PagerButton}", Language::Load("document", 7), $tempContent);
                }
            }   else{
                Template::RemoveCustomTag($tempContent, $listName);
                $tempContent = str_ireplace("{PagerButton}", Language::Load('document', 26), $tempContent);//您尚未开通操作此功能的权限，如需开通此权限，请联系管理人员！
            }
        }else{
            $resultJavaScript.=Control::GetJqueryMessage(Language::Load('activity', 23));//活动ID错误！
        }


        parent::ReplaceEnd($tempContent);
        $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
        $result = $tempContent;
        return $result;
    }


    /**
     * 修改活动参加人员的通过状态
     * @return string 修改结果
     */
    private function  ModifyState()
    {

        $result = -1;

        $activityId     = Control::GetRequest("activity_id", 0);
        $activityUserId = Control::GetRequest("activity_user_id", 0);
        $state          = Control::GetRequest("state", '0');
        $manageUserId   = Control::GetManageUserId();

        $activityManageDate = new ActivityManageData();
        $channelId          = $activityManageDate->GetChannelId($activityId);
        $channelManageDate  = new ChannelManageData();
        $siteId             = $channelManageDate->GetSiteId($channelId,true);


        if ($siteId > 0 && $activityUserId >= 0 && $state >= 0 && $manageUserId > 0) {
            /**********************************************************************
             ******************************判断是否有操作权限**********************
             **********************************************************************/
            $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
            $can = $manageUserAuthorityManageData->CanManageSite($siteId, $channelId, $manageUserId);

            if (!$can) {
                $result = -10;
            }
            else {
                $activityUserManageDate = new ActivityUserManageData();
                $result = $activityUserManageDate->ModifyState($activityUserId,$state);

                if ($result == 1){
                    $result = $activityManageDate->SyncModifyState($activityId);
                }

                //删除缓冲
                parent::DelAllCache();
                //加入操作日志
                $operateContent = 'Modify State SiteTag,GET PARAM:' . implode('|', $_GET) . ';\r\nResult:' . $result;
                self::CreateManageUserLog($operateContent);
            }
        }
        return Control::GetRequest("jsonpcallback", "") . '({"result":' . $result . '})';
    }

    private function DeleteUser(){
        $result = -1;

        $activityId     = Control::GetRequest("activity_id", 0);
        $activityUserId = Control::GetRequest("activity_user_id", 0);
        $manageUserId   = Control::GetManageUserId();

        $activityManageDate = new ActivityManageData();
        $channelId          = $activityManageDate->GetChannelId($activityId);
        $channelManageDate  = new ChannelManageData();
        $siteId             = $channelManageDate->GetSiteId($channelId,true);

        if ($siteId > 0 && $activityUserId >= 0 && $manageUserId > 0){
            /**********************************************************************
             ******************************判断是否有操作权限**********************
             **********************************************************************/
            $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
            $can = $manageUserAuthorityManageData->CanManageSite($siteId, $channelId, $manageUserId);

            if (!$can) {
                $result = -10;
            }
            else{
                $activityUserManageDate = new ActivityUserManageData();
                $result = $activityUserManageDate->Delete($activityUserId);

                if ($result == 1){
                    $result = $activityManageDate->SyncModifyState($activityId);
                }
                //删除缓冲
                parent::DelAllCache();
                //加入操作日志
                $operateContent = 'Modify State SiteTag,GET PARAM:' . implode('|', $_GET) . ';\r\nResult:' . $result;
                self::CreateManageUserLog($operateContent);
            }
        }
        return Control::GetRequest("jsonpcallback", "") . '({"result":' . $result . '})';
    }
}