<?php
/**
 * 后台管理 会员等级  生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author zhangchi
 */

class UserLevelManageGen extends BaseManageGen implements IBaseManageGen{
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
                $result = self::AsyncModifyState();
                break;
        }
        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    /**
     * 新增
     * @return null|string
     */
    private function GenCreate(){
        $templateContent = Template::Load("user/user_level_deal.html","common");
        $siteId = Control::GetRequest("site_id",0);
        $pageSize = Control::GetRequest("ps",0);
        $tabIndex = Control::GetRequest("tab_index",0);
        $pageIndex = Control::GetRequest("p",1);
        if($siteId > 0){
            parent::ReplaceFirst($templateContent);
            $userLevelManageData = new UserLevelManageData();
            $userGroupManageData = new UserGroupManageData();
            $userGroupAllCount = 0;
            $arrUserGroupList = $userGroupManageData->GetList($siteId,0,1000,$userGroupAllCount);
            $tagId = "user_group_list";
            Template::ReplaceList($templateContent,$arrUserGroupList,$tagId);
            if(!empty($_POST)){
                $httpPostData = $_POST;
                $result = $userLevelManageData->Create($httpPostData,$siteId);
                if($result > 0){
                    $closeTab = Control::PostRequest("CloseTab",0);
                    $tabIndex = Control::GetRequest("TabIndex",0);
                    $pageSize = Control::GetRequest("PageSize",27);
                    $pageIndex  = Control::GetRequest("PageIndex",1);
                    if($closeTab == 1){
                        Control::GoUrl("/default.php?secu=manage&mod=user_level&m=list&site_id=".$siteId."&ps=".$pageSize."&p=".$pageIndex."&tab_index=".$tabIndex);
                    }else{
                        Control::GoUrl($_SERVER["HTTP_SELF"]);
                    }
                }
            }
            $replace_arr = array(
                "{TabIndex}" => $tabIndex,
                "{PageSize}" => $pageSize,
                "{PageIndex}" => $pageIndex,
                "{UserLevelId}" => "0",
                "{SiteId}" => $siteId
            );
            $templateContent = strtr($templateContent,$replace_arr);
            parent::ReplaceWhenCreate($templateContent,$userLevelManageData->GetFields());
            parent::ReplaceEnd($templateContent);
            return $templateContent;
        }else{
            return null;
        }
    }

    /**
     * 修改
     * @return null|string
     */
    private function GenModify(){
        $templateContent = Template::Load("user/user_level_deal.html","common");
        $userLevelId = Control::GetRequest("user_level_id",0);
        $siteId = Control::GetRequest("site_id",0);
        $pageSize = Control::GetRequest("ps",0);
        $tabIndex = Control::GetRequest("tab_index",0);
        $pageIndex = Control::GetRequest("p",1);

        if($userLevelId > 0 && $siteId > 0){
            $userLevelManageData = new UserLevelManageData();
            $returnUrl = $_SERVER['HTTP_REFERER'];
            if(!empty($_POST)){
                $httpPostData = $_POST;
                $result = $userLevelManageData->Modify($httpPostData,$userLevelId);
                if($result > 0){
                    $closeTab = Control::PostRequest("CloseTab",0);
                    $tabIndex = Control::PostRequest("TabIndex",0);
                    $pageSize = Control::PostRequest("PageSize",27);
                    $pageIndex  = Control::PostRequest("PageIndex",1);
                    if($closeTab == 1){
                        Control::GoUrl("/default.php?secu=manage&mod=user_level&m=list&site_id=".$siteId."&ps=".$pageSize."&p=".$pageIndex."&tab_index=".$tabIndex);
                    }else{
                        Control::GoUrl($_SERVER["HTTP_SELF"]);
                    }
                }
            }
            $replace_arr = array(
                "{TabIndex}" => $tabIndex,
                "{PageSize}" => $pageSize,
                "{PageIndex}" => $pageIndex,
                "{UserLevelId}" => $userLevelId,
                "{SiteId}" => $siteId,
                "{ReturnUrl}" => $returnUrl
            );
            $templateContent = strtr($templateContent,$replace_arr);

            $userGroupManageData = new UserGroupManageData();
            $arrUserGroupList = $userGroupManageData->GetList($siteId,0,1000,$userGroupAllCount);
            $tagId = "user_group_list";
            Template::ReplaceList($templateContent,$arrUserGroupList,$tagId);

            $arrUserLevelOne = $userLevelManageData->GetOne($userLevelId,$siteId);
            Template::ReplaceOne($templateContent,$arrUserLevelOne);

            parent::ReplaceEnd($templateContent);
            return $templateContent;
        }else{
            return null;
        }
    }

    /**
     * 异步修改状态
     * @return null|string
     */
    private function AsyncModifyState(){
        $userLevelId = Control::GetRequest("user_level_id",0);
        $state = Control::GetRequest("state",0);

        if($userLevelId > 0){
            $userLevelManageData = new UserLevelManageData();
            $result = $userLevelManageData->ModifyState($userLevelId,$state);
            return Control::GetRequest("jsonpcallback","") .'({"result":"'.$result.'"})';
        }else{
            return null;
        }
    }

    /**
     * 获取会员组列表
     * @return mixed|null|string
     */
    private function GenList(){
        $templateContent = Template::Load("user/user_level_list.html","common");
        $siteId = Control::GetRequest("site_id",0);
        $pageIndex = Control::GetRequest("p",1);
        $pageSize = Control::GetRequest("ps",0);

        if($siteId > 0){
            $allCount = 0;
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $userLevelManageData = new UserLevelManageData();
            $arrUserLevelList = $userLevelManageData->GetList($siteId,$pageBegin,$pageSize,$allCount);
            $tagId = "user_level_list";
            if(count($arrUserLevelList) > 0){
                $styleNumber = 1;
                $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html","common");
                $isJs = FALSE;
                $navUrl = "/default.php?secu=manage&mod=user_level&m=list&site_id=$siteId&p={0}&ps=$pageSize";
                $jsFunctionName = "";
                $jsParamList = "";
                $pagerButton = Pager::ShowPageButton($pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex, $styleNumber, $isJs, $jsFunctionName, $jsParamList);
                Template::ReplaceList($templateContent,$arrUserLevelList,$tagId);
                $templateContent = str_ireplace("{pagerButton}", $pagerButton, $templateContent);
            }else{
                Template::RemoveCustomTag($templateContent, $tagId);
                $templateContent = str_ireplace("{pagerButton}","", $templateContent);
            }
            $arrReplace = array(
                "{SiteId}" => $siteId
            );
            $templateContent = strtr($templateContent,$arrReplace);
            parent::ReplaceEnd($templateContent);
            return $templateContent;
        }else{
            return null;
        }
    }
}