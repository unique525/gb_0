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
        }

        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }
    private function GenList(){

        $siteId = Control::GetRequest("site_id", 0);
        $activityId = Control::GetRequest("activity_Id", 0);
        $resultJavaScript="";
        $tempContent = Template::Load("activity/activity_user_list.html","common");
        $activityUserManageData = new ActivityUserManageData();

        if(intval($activityId)>0){
            $pageSize = Control::GetRequest("ps", 20);
            $pageIndex = Control::GetRequest("p", 1);
            $searchKey = Control::GetRequest("search_key", "");
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $allCount = 0;
            $listName = "activity_user_list";

            ///////////////判断是否有操作权限///////////////////
            $manageUserId = Control::GetManageUserId();
            $manageUserAuthority = new ManageUserAuthorityManageData();
            $can = $manageUserAuthority->CanManageUserModify($siteId, 0, $manageUserId);

            if ($can == 1) {
                $siteTagArray=$activityUserManageData->GetUserListPager($activityId,$pageBegin,$pageSize,$searchKey,$allCount);
                if(count($siteTagArray)>0){
                    Template::ReplaceList($tempContent, $siteTagArray, $listName);
                    $styleNumber = 1;
                    $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html", "common");
                    $isJs = FALSE;
                    $navUrl = "default.php?secu=manage&mod=activity_user&m=list&site_id=$siteId&p={0}&ps=$pageSize";
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
            $resultJavaScript.=Control::GetJqueryMessage(Language::Load('site_id', 3));//站点id错误！
        }


        parent::ReplaceEnd($tempContent);
        $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
        $result = $tempContent;
        return $result;
    }

    private function GenCreate(){



    }



    /**
     * 修改关键词状态
     * @return string 修改结果
     */
    private function ModifyState()
    {
        $result = -1;
        $siteId = Control::GetRequest("site_id", 0);
        $siteTagId = Control::GetRequest("site_tag_id", 0);
        $state = Control::GetRequest("state", -1);
        $manageUserId = Control::GetManageUserId();

        if ($siteId > 0 && $siteTagId >= 0 && $state >= 0 && $manageUserId > 0) {
            /**********************************************************************
             ******************************判断是否有操作权限**********************
             **********************************************************************/
            $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
            $channelId = 0;
            $can = $manageUserAuthorityManageData->CanManageSite($siteId, $channelId, $manageUserId);
            if (!$can) {
                $result = -10;
            } else {
                $siteTagManageData = new SiteTagManageData();
                $result = $siteTagManageData->ModifyState($siteTagId, $state);
                //删除缓冲
                DataCache::RemoveDir(CACHE_PATH . '/site_data');
                //加入操作日志
                $operateContent = 'Modify State SiteTag,GET PARAM:' . implode('|', $_GET) . ';\r\nResult:' . $result;
                self::CreateManageUserLog($operateContent);
            }
        }
        return Control::GetRequest("jsonpcallback", "") . '({"result":' . $result . '})';
    }
}