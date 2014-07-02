<?php
/**
 * Created by PhpStorm.
 * User: yin
 * Date: 14-6-25
 * Time: 下午3:21
 */

class UserGroupManageGen extends BaseManageGen implements IBaseManageGen{
    public function Gen(){
        $result = "";
        $method = Control::GetRequest("m","");

        switch($method){
            case "create":
                $result = self::GenCreate();
                break;
            case "list":
                $result = self::GenList();
                break;
            case "modify":
                $result = self::GenModify();
                break;
            case "modify_state":
                $result = self::GenModifyState();
                break;
        }
        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    private function GenCreate(){
        $templateContent = Template::Load("user/user_group_deal.html","common");
        $siteId = Control::GetRequest("site_id",0);
        if($siteId > 0){
            $userGroupManageData = new UserGroupManageData();
            $returnUrl = $_SERVER['HTTP_REFERER'];
            if(!empty($_POST)){
                $httpPostData = $_POST;
                $result = $userGroupManageData->Create($httpPostData,$siteId);
                if($result > 0){
                    $returnUrl = $httpPostData["return_url"];
                    Control::GoUrl($returnUrl);
                }
            }
            $replace_arr = array(
                "{ReturnUrl}" => $returnUrl,
                "{UserGroupId}" => "1",
                "{SiteId}" => $siteId
            );
            $templateContent = strtr($templateContent,$replace_arr);
            parent::ReplaceWhenCreate($templateContent,$userGroupManageData->GetFields());
            parent::ReplaceEnd($templateContent);
            return $templateContent;
        }else{
            return null;
        }
    }

    private function GenModify(){
        $templateContent = Template::Load("user/user_group_deal.html","common");
        $userGroupId = Control::GetRequest("user_group_id",0);
        $siteId = Control::GetRequest("site_id",0);

        if($userGroupId > 0 && $siteId > 0){
            $userGroupManageData = new UserGroupManageData();
            $returnUrl = $_SERVER['HTTP_REFERER'];
            if(!empty($_POST)){
                $httpPostData = $_POST;
                $result = $userGroupManageData->Modify($httpPostData,$userGroupId);
                if($result > 0){
                    $returnUrl = $httpPostData["return_url"];
                    Control::GoUrl($returnUrl);
                }
            }
            $arrUserGroupOne = $userGroupManageData->GetOne($userGroupId,$siteId);
            Template::ReplaceOne($templateContent,$arrUserGroupOne);

            $replace_arr = array(
                "{UserGroupId}" => $userGroupId,
                "{SiteId}" => $siteId,
                "{ReturnUrl}" => $returnUrl
            );

            $templateContent = strtr($templateContent,$replace_arr);
            parent::ReplaceEnd($templateContent);
            return $templateContent;
        }else{
            return null;
        }
    }

    private function GenModifyState(){
        $userGroupId = Control::GetRequest("user_group_id",0);
        $state = Control::GetRequest("state",0);

        if($userGroupId > 0){
            $userGroupManageData = new UserGroupManageData();
            $result = $userGroupManageData->ModifyState($userGroupId,$state);
            if($result > 0){
                return $_GET['JsonpCallBack'].'({"result":1})';
            }else{
                return $_GET['JsonpCallBack'].'({"result":0})';
            }
        }else{
            return null;
        }
    }

    private function GenList(){
        $templateContent = Template::Load("user/user_group_list.html","common");
        $siteId = Control::GetRequest("site_id",0);
        $pageIndex = Control::GetRequest("p",1);
        $pageSize = Control::GetRequest("ps",0);
        if($siteId > 0){
            $allCount = 0;
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $userGroupManageData = new UserGroupManageData();
            $arrUserGroupList = $userGroupManageData->GetList($siteId,$pageBegin,$pageSize,$allCount);
            $tagId = "user_group_list";
            if(count($arrUserGroupList) > 0){
                $styleNumber = 1;
                $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html","common");
                $isJs = FALSE;
                $navUrl = "default.php?secu=manage&mod=user_group&m=list&p={0}&ps=$pageSize";
                $jsFunctionName = "";
                $jsParamList = "";
                $pagerButton = Pager::ShowPageButton($pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex, $styleNumber, $isJs, $jsFunctionName, $jsParamList);
                Template::ReplaceList($templateContent,$arrUserGroupList,$tagId);
                $templateContent = str_ireplace("{pagerButton}", $pagerButton, $templateContent);
            }else{
                Template::RemoveCustomTag($templateContent, $tagId);
                $templateContent = str_ireplace("{pagerButton}","", $templateContent);
            }
            $replace_arr = array(
                "{SiteId}" => $siteId
            );
            $templateContent = strtr($templateContent,$replace_arr);
            parent::ReplaceEnd($templateContent);
            return $templateContent;
        }else{
            return null;
        }
    }
}