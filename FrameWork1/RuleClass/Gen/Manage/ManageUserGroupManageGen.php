<?php
/**
 * 后台管理 管理员分组 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Manage
 * @author zhangchi
 */
class ManageUserGroupManageGen extends BaseManageGen implements IBaseManageGen {
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

        $resultJavaScript = "";

        if ($manageUserId > 0) {
            $manageUserGroupManageData = new ManageUserGroupManageData();
            $templateContent = Template::Load("manage/manage_user_group_deal.html", "common");
            parent::ReplaceFirst($templateContent);


            $tagId = "manage_menu_of_column";
            $manageMenuOfColumnManageData = new ManageMenuOfColumnManageData();
            $arrManageMenuOfColumn = $manageMenuOfColumnManageData->GetListOfOpen();
            Template::ReplaceList($templateContent, $arrManageMenuOfColumn, $tagId);



            if (!empty($_POST)) {

                $httpPostData = $_POST;

                $manageUserGroupId = $manageUserGroupManageData->Create($httpPostData);
                //加入操作日志
                $operateContent = 'Create Manage User Group,POST FORM:' .
                    implode('|', $_POST) . ';\r\nResult:manageUserGroupId:' . $manageUserGroupId;
                self::CreateManageUserLog($operateContent);

                if ($manageUserGroupId > 0) {

                    $closeTab = Control::PostRequest("CloseTab", 0);
                    if ($closeTab == 1) {
                        //Control::CloseTab();
                        $resultJavaScript .= Control::GetCloseTab();
                    } else {
                        Control::GoUrl($_SERVER["PHP_SELF"] . "?" . $_SERVER['QUERY_STRING']);
                    }
                } else {
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('manage_user_group', 3)); //新增失败！

                }

            }

            $fields = $manageUserGroupManageData->GetFields();
            parent::ReplaceWhenCreate($templateContent, $fields);

            $patterns = '/\{s_(.*?)\}/';
            $templateContent = preg_replace($patterns, "", $templateContent);

            parent::ReplaceEnd($templateContent);


            $templateContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $templateContent);

        } else {
            $templateContent = Language::Load("manage_user_group", 9);
        }
        return $templateContent;
    }

    /**
     * 编辑
     * @return string 模板内容页面
     */
    private function GenModify()
    {
        $templateContent = Template::Load("manage/manage_user_group_deal.html", "common");
        $manageUserGroupId = Control::GetRequest("manage_user_group_id", 0);
        $resultJavaScript = "";
        $manageUserId = Control::GetManageUserId();

        if ($manageUserGroupId > 0 && $manageUserId > 0) {

            parent::ReplaceFirst($templateContent);

            $manageUserGroupManageData = new ManageUserGroupManageData();


            $tagId = "manage_menu_of_column";
            $manageMenuOfColumnManageData = new ManageMenuOfColumnManageData();
            $arrManageMenuOfColumn = $manageMenuOfColumnManageData->GetListOfOpen();
            Template::ReplaceList($templateContent, $arrManageMenuOfColumn, $tagId);


            //加载原有数据
            $arrOne = $manageUserGroupManageData->GetOne($manageUserGroupId);
            Template::ReplaceOne($templateContent, $arrOne);





            if (!empty($_POST)) {
                $httpPostData = $_POST;

                $result = $manageUserGroupManageData->Modify($httpPostData, $manageUserGroupId);
                //加入操作日志
                $operateContent = 'Modify Manage User Group,POST FORM:' . implode('|', $_POST) . ';\r\nResult:result:' . $result;
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
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('manage_user_group', 4)); //编辑失败！
                }
            }

            $patterns = '/\{s_(.*?)\}/';
            $templateContent = preg_replace($patterns, "", $templateContent);
        }
        parent::ReplaceEnd($templateContent);
        $templateContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $templateContent);
        return $templateContent;
    }


    /**
     * 修改状态 状态值定义在Data类中
     * @return string 返回Jsonp修改结果
     */
    private function AsyncModifyState()
    {
        $result = -1;
        $manageUserGroupId = Control::GetRequest("manage_user_group_id", 0);
        $state = Control::GetRequest("state", -1);
        $manageUserId = Control::GetManageUserId();

        if ($manageUserGroupId > 0 && $state >= 0 && $manageUserId > 0) {
            //判断权限
            /**********************************************************************
             ******************************判断是否有操作权限**********************
             **********************************************************************/
            $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
            $siteId = 0;
            $channelId = 0;
            $can = $manageUserAuthorityManageData->CanManageUserGroupModify($siteId, $channelId, $manageUserId);
            if (!$can) {
                $result = -10;
            } else {
                $manageUserGroupManageData = new ManageUserGroupManageData();
                $result = $manageUserGroupManageData->ModifyState($manageUserGroupId, $state);
                //加入操作日志
                $operateContent = 'Modify State Manage User Group,GET PARAM:' . implode('|', $_GET) . ';\r\nResult:' . $result;
                self::CreateManageUserLog($operateContent);
            }
        }
        return Control::GetRequest("jsonpcallback", "") . '({"result":' . $result . '})';
    }


    /**
     * 返回列表页面
     * @return mixed|string
     */
    private function GenList(){
        $manageUserId = Control::GetManageUserId();

        $siteId = 0;
        $channelId = 0;

        ///////////////判断是否有操作权限///////////////////
        $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
        $canExplore = $manageUserAuthorityManageData->CanManageUserGroupExplore($siteId, $channelId, $manageUserId);
        if (!$canExplore) {
            return "";
        }

        //load template
        $tempContent = Template::Load("manage/manage_user_group_list.html", "common");

        parent::ReplaceFirst($tempContent);

        ////////////////////////////////////////////////////
        ///////////////输出权限到页面///////////////////
        ////////////////////////////////////////////////////

        $pageSize = Control::GetRequest("ps", 40);
        $pageIndex = Control::GetRequest("p", 1);
        $searchKey = Control::GetRequest("search_key", "");
        $searchType = Control::GetRequest("search_type", -1);
        $searchKey = urldecode($searchKey);

        $manageUserGroupManageData = new ManageUserGroupManageData();


        if ($pageIndex > 0) {

            $pageBegin = ($pageIndex - 1) * $pageSize;
            $tagId = "manage_user_group_list";
            $allCount = 0;

            $arrList = $manageUserGroupManageData->GetList(
                $pageBegin,
                $pageSize,
                $allCount,
                $searchKey,
                $searchType
            );
            if (count($arrList) > 0) {
                Template::ReplaceList($tempContent, $arrList, $tagId);

                $styleNumber = 1;
                $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html", "common");
                $isJs = FALSE;
                $navUrl = "default.php?secu=manage&mod=manage_user_group&m=list&p={0}&ps=$pageSize";
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
                $tempContent = str_ireplace("{pager_button}", Language::Load("manage_user_group", 8), $tempContent);
            }
        }

        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }
} 