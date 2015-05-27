<?php
/**
 * 后台管理 会员组  生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author zhangchi
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
                $result = self::AsyncModifyState();
                break;
        }
        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    /**
     * 新增
     * @return null|string 模板内容页面
     */
    private function GenCreate(){
        $templateContent = Template::Load("user/user_group_deal.html","common");
        $siteId = Control::GetRequest("site_id",0);
        $pageSize = Control::GetRequest("ps",0);
        $tabIndex = Control::GetRequest("tab_index",0);
        $pageIndex = Control::GetRequest("p",1);
        $resultJavaScript = "";

        if($siteId > 0){
            $userGroupManageData = new UserGroupManageData();
            if(!empty($_POST)){
                $httpPostData = $_POST;
                $result = $userGroupManageData->Create($httpPostData,$siteId);
                if($result > 0){
                    $closeTab = Control::PostRequest("CloseTab",0);
                    $tabIndex = Control::GetRequest("TabIndex",0);
                    $pageSize = Control::GetRequest("PageSize",27);
                    $pageIndex  = Control::GetRequest("PageIndex",1);
                    if($closeTab == 1){
                        Control::GoUrl("/default.php?secu=manage&mod=user_group&m=list&site_id=".$siteId."&ps=".$pageSize."&p=".$pageIndex."&tab_index=".$tabIndex);
                    }else{
                        Control::GoUrl($_SERVER["PHP_SELF"]);
                    }
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('user_group', 1));
                }else{
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('user_group', 2));
                }
                $operateContent = 'Create UserGroup,POST FORM:'.implode('|',$_POST).';\r\nResultUserGroupId::'.$result;
                self::CreateManageUserLog($operateContent);
            }
            $replace_arr = array(
                "{TabIndex}" => $tabIndex,
                "{PageSize}" => $pageSize,
                "{PageIndex}" => $pageIndex,
                "{UserGroupId}" => "0",
                "{SiteId}" => $siteId
            );
            $templateContent = strtr($templateContent,$replace_arr);
            parent::ReplaceWhenCreate($templateContent,$userGroupManageData->GetFields());
            parent::ReplaceEnd($templateContent);
            $templateContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $templateContent);
            return $templateContent;
        }else{
            return null;
        }
    }

    /**
     * 修改
     * @return null|string 模板内容页面
     */
    private function GenModify(){
        $templateContent = Template::Load("user/user_group_deal.html","common");
        $userGroupId = Control::GetRequest("user_group_id",0);
        $siteId = Control::GetRequest("site_id",0);
        $pageSize = Control::GetRequest("ps",0);
        $tabIndex = Control::GetRequest("tab_index",0);
        $pageIndex = Control::GetRequest("p",1);
        $resultJavaScript = "";

        if($userGroupId > 0 && $siteId > 0){
            $userGroupManageData = new UserGroupManageData();
            $returnUrl = $_SERVER['HTTP_REFERER'];
            if(!empty($_POST)){
                $httpPostData = $_POST;
                $result = $userGroupManageData->Modify($httpPostData,$userGroupId);
                if($result > 0){
                    $closeTab = Control::PostRequest("CloseTab",0);
                    $tabIndex = Control::GetRequest("TabIndex",0);
                    $pageSize = Control::GetRequest("PageSize",27);
                    $pageIndex  = Control::GetRequest("PageIndex",1);
                    if($closeTab == 1){
                        Control::GoUrl("/default.php?secu=manage&mod=user_group&m=list&site_id=".$siteId."&ps=".$pageSize."&p=".$pageIndex."&tab_index=".$tabIndex);
                    }else{
                        Control::GoUrl($_SERVER["PHP_SELF"]);
                    }
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('user_group', 3));
                }else{
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('user_group', 4));
                }
                $operateContent = 'Modify UserGroup,POST FORM:'.implode('|',$_POST).';\r\nResult::'.$result;
                self::CreateManageUserLog($operateContent);
            }
            $arrUserGroupOne = $userGroupManageData->GetOne($userGroupId,$siteId);
            Template::ReplaceOne($templateContent,$arrUserGroupOne);

            $replace_arr = array(
                "{TabIndex}" => $tabIndex,
                "{PageSize}" => $pageSize,
                "{PageIndex}" => $pageIndex,
                "{UserGroupId}" => $userGroupId,
                "{SiteId}" => $siteId,
                "{ReturnUrl}" => $returnUrl
            );

            $templateContent = strtr($templateContent,$replace_arr);
            parent::ReplaceEnd($templateContent);
            $templateContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $templateContent);
            return $templateContent;
        }else{
            return null;
        }
    }

    /**
     * 异步修改状态
     * @return null|string Jsonp结果
     */
    private function AsyncModifyState(){
        $userGroupId = Control::GetRequest("user_group_id",0);
        $state = Control::GetRequest("state",0);

        if($userGroupId > 0){
            $userGroupManageData = new UserGroupManageData();
            $result = $userGroupManageData->ModifyState($userGroupId,$state);
            $operateContent = 'Modify UserGroupState,POST FORM:'.implode('|',$_GET).';\r\nResult::'.$result;
            self::CreateManageUserLog($operateContent);
            if($result > 0){
                return Control::GetRequest("jsonpcallback","") .'({"result":'.$result.'})';
            }
        }else{
            return null;
        }
    }

    /**
     * 获取会员组列表
     * @return mixed|null|string 模板内容页面
     */
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
                $navUrl = "/default.php?secu=manage&mod=user_group&m=list&site_id=$siteId&p={0}&ps=$pageSize";
                $jsFunctionName = "";
                $jsParamList = "";
                $pagerButton = Pager::ShowPageButton($pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex, $styleNumber, $isJs, $jsFunctionName, $jsParamList);
                Template::ReplaceList($templateContent,$arrUserGroupList,$tagId);
                $templateContent = str_ireplace("{pagerButton}", $pagerButton, $templateContent);
            }else{
                Template::RemoveCustomTag($templateContent, $tagId);
                $templateContent = str_ireplace("{pagerButton}","", $templateContent);
            }

            $templateContent = str_ireplace("{SiteId}", $siteId, $templateContent);

            parent::ReplaceEnd($templateContent);
            return $templateContent;
        }else{
            return null;
        }
    }
}