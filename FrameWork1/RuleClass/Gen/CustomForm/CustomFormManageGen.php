<?php

/**
 * 活动表单页面生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_CustomForm
 * @author 525
 */
class CustomFormManageGen extends BaseManageGen implements IBaseManageGen {

    /**
     * 节点表单查询结果 空
     */
    const SELECT_CUSTOM_FORM_LIST_RESULT_NULL = -1;
    /**
     * 表单字段名查询结果 空
     */
    const SELECT_CUSTOM_FORM_TABLE_NAME_LIST_RESULT_NULL = -2;
    /**
     * 新增表单写入数据库失败
     */
    const INSERT_NEW_CUSTOM_FORM_FAILED = -3;
    /**
     * 获取被修改表单原属性结果 空
     */
    const GET_CUSTOM_FORM_ATTRIBUTE_RESULT_NULL = -4;
    /**
     * 修改表单属性写入数据库失败
     */
    const UPDATE_CUSTOM_FORM_ATTRIBUTE_FAIL = -5;





    /**
     * 引导方法
     * @return string 返回执行结果
     */
    function Gen() {
        $result = "";
                $method = Control::GetRequest("m", "");
                switch ($method) {
                    case "create":
                        $result = self::GenCreate();
                        break;
                    case "modify":
                        $result = self::GenModify();
                        break;
                    case "list":
                        $result = self::GenList();
                        break;
                }
                $replaceArray = array(
                    "{method}" => $method
                );
                $result = strtr($result, $replaceArray);
        return $result;
    }


    /**
     * 新增表单
     * @return string 执行结果
     */
    private function GenCreate() {
        $tempContent = Template::Load("custom_form/custom_form_deal.html","common");
        $resultJavaScript="";
        $manageUserId = Control::GetManageUserId();
        $channelId = Control::GetRequest("channel_id", 0);
        parent::ReplaceFirst($tempContent);

        if ($channelId > 0) {
            $channelData = new ChannelManageData();
            $siteId = $channelData->GetSiteId($channelId,"");

            ///////////////判断是否有操作权限///////////////////
            $manageUserAuthority = new ManageUserAuthorityManageData();
            $can = $manageUserAuthority->CanChannelCreate($siteId, $channelId, $manageUserId);
            if ($can != 1) {
                Control::ShowMessage(Language::Load('custom_form', 3));
                return "";
            }

            if (!empty($_POST)) {
                $customFormManageData = new CustomFormManageData();
                $newId = $customFormManageData->Create($_POST);

                //记入操作log
                $operateContent = "Create CustomForm：CustomFormId：" . $newId .",POST FORM:".implode("|",$_POST).";\r\nResult:". $newId;
                self::CreateManageUserLog($operateContent);

                if($newId>0){




                    Control::ShowMessage(Language::Load('custom_form', 1));
                    $closeTab = Control::PostRequest("CloseTab",0);
                    if($closeTab == 1){
                        $resultJavaScript .= Control::GetCloseTab();
                    }else{
                        Control::GoUrl($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']);
                        //Control::GoUrl($_SERVER["PHP_SELF"].'?secu=manage&mod=custom_form&m=create');
                    }

                }else{
                    Control::ShowMessage(Language::Load('custom_form', 2));
                    return DefineCode::CUSTOM_FORM_MANAGE+self::INSERT_NEW_CUSTOM_FORM_FAILED;
                }

            }
            ////////////////////////////////////////////////////
            $crateDate=date('Y-m-d H:i:s');
            $replaceArray = array(
                "{SiteId}" => $siteId,
                "{ChannelId}" => $channelId,
                "{ManageUserId}" => $manageUserId,
                "{CreateDate}" => $crateDate,
                "{display}" => ""
            );
            $tempContent = strtr($tempContent, $replaceArray);

            $sourceCommonManageData = new SourceCommonManageData();
            $arrayOfTableNameList = $sourceCommonManageData->GetFields("cst_custom_form");
            if($arrayOfTableNameList>0){
                parent::ReplaceWhenCreate($tempContent, $arrayOfTableNameList);
            }else{
                return DefineCode::CUSTOM_FORM_MANAGE+self::SELECT_CUSTOM_FORM_TABLE_NAME_LIST_RESULT_NULL;
            }


            //去掉s开头的标记 {s_xxx_xxx}
            $patterns = '/\{s_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
            //去掉c开头的标记 {c_xxx}
            $patterns = '/\{c_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
            //去掉r开头的标记 {r_xxx_xxx}
            $patterns = '/\{r_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
        }

        parent::ReplaceEnd($tempContent);
        $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
        return $tempContent;
    }

    /**
     * 修改表单属性
     * @return string 执行结果
     */
    private function GenModify() {
        $tempContent = Template::Load("custom_form/custom_form_deal.html","common");
        $resultJavaScript="";
        parent::ReplaceFirst($tempContent);
        $customFormId = Control::GetRequest("custom_form_id", 0);

        if ($customFormId > 0) {
            $customFormManageData = new CustomFormManageData();
            $channelId = $customFormManageData->GetChannelId($customFormId, 0);
            $channelData = new ChannelManageData();
            $siteId = $channelData->GetSiteId($channelId,"");

            $nowManageUserId = Control::GetManageUserId();
            ///////////////判断是否有操作权限///////////////////
            $manageUserAuthority = new ManageUserAuthorityManageData();
            //编辑权限
            $can = $manageUserAuthority->CanChannelModify($siteId, $channelId, $nowManageUserId);
            if ($can != 1) {
                Control::ShowMessage(Language::Load('custom_form', 3));
                return "";
            }
            //操作他人的权限

            $createUserId = $customFormManageData->GetManageUserId($customFormId,FALSE);
            if ($createUserId !== $nowManageUserId) { //操作人不是发布人
                $can = $manageUserAuthority->CanChannelDoOthers($siteId, $channelId, $nowManageUserId);
                if ($can != 1) {
                    Control::ShowMessage(Language::Load('custom_form', 3));
                    return "";
                }
            }
            ////////////////////////////////////////////////////

            $replaceArray = array(
                "{SiteId}" => $siteId,
                "{ChannelId}" => $channelId,
                "{ManageUserId}" => $createUserId,
                "{CustomFormId}" => $customFormId,
                "{display}" => "none"
            );
            $tempContent = strtr($tempContent, $replaceArray);

            $arrayOfEditingData = $customFormManageData->GetOne($customFormId);
            Template::ReplaceOne($tempContent, $arrayOfEditingData, 0);

            //去掉s开头的标记 {s_xxx_xxx}
            $patterns = '/\{s_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
            //去掉c开头的标记 {c_xxx}
            $patterns = '/\{c_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
            //去掉r开头的标记 {r_xxx_xxx}
            $patterns = '/\{r_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);

            if (!empty($_POST)) {
                $result = $customFormManageData->Modify($_POST,$customFormId);


                //记入操作log
                $operateContent = "Create CustomForm：CustomFormId：" . $customFormId .",POST FORM:".implode("|",$_POST).";\r\nResult:". $result;
                self::CreateManageUserLog($operateContent);

                if ($result > 0) {

                    Control::ShowMessage(Language::Load('custom_form', 1));
                    $closeTab = Control::PostRequest("CloseTab",0);
                    if($closeTab == 1){
                        $resultJavaScript .= Control::GetCloseTab();
                    }else{
                        Control::GoUrl($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']);
                        //Control::GoUrl($_SERVER["PHP_SELF"].'?secu=manage&mod=custom_form&m=list&site_id='.$siteId.'&channel_id='.$channelId);
                    }


                } else {

                    Control::ShowMessage(Language::Load('custom_form', 2));
                    return DefineCode::CUSTOM_FORM_MANAGE+self::UPDATE_CUSTOM_FORM_ATTRIBUTE_FAIL;
                }
            }
        }else{
            return DefineCode::CUSTOM_FORM_MANAGE+self::GET_CUSTOM_FORM_ATTRIBUTE_RESULT_NULL;
        }

        parent::ReplaceEnd($tempContent);
        $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
        return $tempContent;
    }

    /**
     * 取得表单列表
     * @return string 表单列表数据集页面html
     */
    private function GenList() {
        $channelId = Control::GetRequest("channel_id", 0);
        $resultJavaScript="";
        $manageUserId = Control::GetManageUserId();
        $channelData = new ChannelManageData();
        $siteId = $channelData->GetSiteId($channelId,FALSE);
        ///////////////判断是否有操作权限///////////////////
        $manageUserAuthority = new ManageUserAuthorityManageData();
        $can = $manageUserAuthority->CanChannelExplore($siteId, $channelId, $manageUserId);
        if ($can != 1) {
            Control::ShowMessage(Language::Load('custom_form', 3));//您尚未开通操作此功能的权限，如需开通此权限，请联系管理人员！
            return "";
        }
        $result = Language::Load('custom_form', 4);
        $tempContent = Template::Load("custom_form/custom_form_list.html","common");

        $type = Control::GetRequest("type", "");
        $pageSize = Control::GetRequest("ps", 20);
        $pageIndex = Control::GetRequest("p", 1);
        $searchKey = Control::GetRequest("search_key", "");
        $searchKey = urldecode($searchKey);
        if (isset($searchKey) && strlen($searchKey) > 0) {
            ///////////////判断是否有操作权限///////////////////
            $can = $manageUserAuthority->CanChannelSearch($siteId, $channelId, $manageUserId);
            if ($can != 1) {
                Control::ShowMessage(Language::Load('custom_form', 3));
                return "";
            }
        }

        if ($pageIndex > 0 && $channelId > 0) {

            $pageBegin = ($pageIndex - 1) * $pageSize;
            $listName = "custom_form";
            $allCount = 0;
            $customFormData = new CustomFormManageData();
            $arrListOfCustomForm = $customFormData->GetListPager($channelId, $pageBegin, $pageSize, $allCount, $searchKey, $type);
            if (count($arrListOfCustomForm) > 0) {
                Template::ReplaceList($tempContent, $arrListOfCustomForm, $listName);

                $styleNumber = 1;
                $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html", "common");
                $isJs = FALSE;
                $navUrl = "default.php?secu=manage&mod=custom_form&m=list&channel_id=$channelId&p={0}&ps=$pageSize";
                $jsFunctionName = "";
                $jsParamList = "";
                $pagerButton = Pager::ShowPageButton($pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex ,$styleNumber = 1, $isJs, $jsFunctionName, $jsParamList);
                $replaceArray = array(
                    "{channel_id}" => $channelId,
                    "{cid}" => $channelId,
                    "{id}" => 0,
                    "{PagerButton}" => $pagerButton
                );
                $tempContent = strtr($tempContent, $replaceArray);
            }else{
                Template::RemoveCustomTag($tempContent, $listName);
                $tempContent = str_ireplace("{PagerButton}", Language::Load("custom_form", 4), $tempContent);

            }

            parent::ReplaceEnd($tempContent);
            $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
            $result = $tempContent;
        }
        return $result;
    }

}

?>
