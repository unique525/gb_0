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
    const CREATE_NEW_CUSTOM_FORM_FAILED = -3;
    /**
     * 获取被修改表单原属性结果 空
     */
    const GET_CUSTOM_FORM_ATTRIBUTE_RESULT_NULL = -4;
    /**
     * 修改表单属性写入数据库失败
     */
    const INSERT_CUSTOM_FORM_ATTRIBUTE_FAIL = -5;





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
        $manageUserId = Control::GetManageUserId();
        $channelId = Control::GetRequest("channel_id", 0);
        parent::ReplaceFirst($tempContent);

        if ($channelId > 0) {
            $channelData = new ChannelManageData();
            $siteId = $channelData->GetSiteId($channelId,"");

            ///////////////判断是否有操作权限///////////////////
            $manageUserAuthority = new ManageUserAuthorityManageData();
            $can = $manageUserAuthority->CanCreate($siteId, $channelId, $manageUserId);
            if ($can != 1) {
                Control::ShowMessage(Language::Load('custom_form', 3));
                return "";
            }

            if (!empty($_POST)) {
                $customFormManageData = new CustomFormManageData();
                $newId = $customFormManageData->Create($_POST);
                if($newId>0){
                    //加入操作log
                    $operateContent = "CustomForm：CustomFormID：" . $newId . "；result：" . $newId . "；title：" . Control::PostRequest("f_CustomFormSubject", "");
                    self::CreateManageUserLog($operateContent);

                    Control::ShowMessage(Language::Load('document', 1));
                    $closeTab = Control::PostRequest("CloseTab",0);
                    if($closeTab == 1){
                        Control::CloseTab();
                    }else{
                        echo $closeTab;
                        Control::GoUrl($_SERVER["PHP_SELF"].'?secu=manage&mod=custom_form&m=create');
                    }

                }else{
                    return DefineCode::CUSTOM_FORM_MANAGE+self::CREATE_NEW_CUSTOM_FORM_FAILED;
                }

            }
            ////////////////////////////////////////////////////
            $crateDate=date('Y-m-d H:i:s');
            $replaceArray = array(
                "{SiteId}" => $siteId,
                "{ChannelId}" => $channelId,
                "{ManageUserId}" => $manageUserId,
                "{CreateDate}" => $crateDate
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
            $patterns = "/\{s_(.*?)\}/";
            $tempContent = preg_replace($patterns, "", $tempContent);
            //去掉c开头的标记 {c_xxx}
            $patterns = "/\{c_(.*?)\}/";
            $tempContent = preg_replace($patterns, "", $tempContent);
            //去掉r开头的标记 {r_xxx_xxx}
            $patterns = "/\{r_(.*?)\}/";
            $tempContent = preg_replace($patterns, "", $tempContent);
        }

        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }

    /**
     * 修改表单属性
     * @return string 执行结果
     */
    private function GenModify() {
        $tempContent = Template::Load("custom_form/custom_form_deal.html","common");
        parent::ReplaceFirst($tempContent);
        $customFormId = Control::GetRequest("custom_form_id", 0);

        if ($customFormId > 0) {
            $customFormManageData = new CustomFormManageData();
            $channelId = $customFormManageData->GetChannelID($customFormId, 0);
            $channelData = new channelManageData();
            $siteId = $channelData->GetSiteId($channelId,"");

            $nowManageUserId = Control::GetManageUserId();
            ///////////////判断是否有操作权限///////////////////
            $manageUserAuthority = new ManageUserAuthorityManageData();
            //编辑权限
            $can = $manageUserAuthority->CanModify($siteId, $channelId, $nowManageUserId);
            if ($can != 1) {
                Control::ShowMessage(Language::Load('document', 26));
                return "";
            }
            //操作他人的权限

            $createUserId = $customFormManageData->GetManageUserID($customFormId,FALSE);
            if ($createUserId !== $nowManageUserId) { //操作人不是发布人
                $can = $manageUserAuthority->CanDoOthers($siteId, $channelId, $nowManageUserId);
                if ($can != 1) {
                    Control::ShowMessage(Language::Load('document', 26));
                    return "";
                }
            }
            ////////////////////////////////////////////////////

            $replaceArray = array(
                "{SiteId}" => $siteId,
                "{ChannelId}" => $channelId,
                "{ManageUserId}" => $createUserId,
                "{CustomFormId}" => $customFormId
            );
            $tempContent = strtr($tempContent, $replaceArray);

            $arrayOfEditingData = $customFormManageData->GetOne($customFormId);
            Template::ReplaceOne($tempContent, $arrayOfEditingData, 1);

            //去掉s开头的标记 {s_xxx_xxx}
            $patterns = "/\{s_(.*?)\}/";
            $tempContent = preg_replace($patterns, "", $tempContent);
            //去掉c开头的标记 {c_xxx}
            $patterns = "/\{c_(.*?)\}/";
            $tempContent = preg_replace($patterns, "", $tempContent);
            //去掉r开头的标记 {r_xxx_xxx}
            $patterns = "/\{r_(.*?)\}/";
            $tempContent = preg_replace($patterns, "", $tempContent);

            if (!empty($_POST)) {
                $result = $customFormManageData->Modify($_POST,$customFormId);


                if ($result > 0) {

                    //加入操作log
                    $operateContent = "CustomForm：CustomFormId：" . $customFormId . "; result：" . $customFormId . "；title：" . Control::PostRequest("f_customFormSubject", "");
                    self::CreateManageUserLog($operateContent);

                    Control::ShowMessage(Language::Load('document', 1));

                        Control::GoUrl($_SERVER["PHP_SELF"].'?secu=manage&mod=custom_form&m=list&site_id='.$siteId.'&channel_id='.$channelId);
                } else {

                    Control::ShowMessage(Language::Load('document', 2));
                    return DefineCode::CUSTOM_FORM_MANAGE+self::INSERT_CUSTOM_FORM_ATTRIBUTE_FAIL;
                }
            }
        }else{
            return DefineCode::CUSTOM_FORM_MANAGE+self::GET_CUSTOM_FORM_ATTRIBUTE_RESULT_NULL;
        }

        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }

    /**
     * 取得表单列表
     * @return array 表单列表数据集
     */
    private function GenList() {
        $channelId = Control::GetRequest("channel_id", 0);
        $manageUserId = Control::GetManageUserId();
        $channelData = new ChannelManageData();
        $siteId = $channelData->GetSiteId($channelId,FALSE);
        ///////////////判断是否有操作权限///////////////////
        $manageUserAuthority = new ManageUserAuthorityManageData();
        $can = $manageUserAuthority->CanExplore($siteId, $channelId, $manageUserId);
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
            $can = $manageUserAuthority->CanSearch($siteId, $channelId, $manageUserId);
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
            $arrListOfCustomFormRecord = $customFormData->GetListPager($channelId, $pageBegin, $pageSize, $allCount, $searchKey, $type);
            if (count($arrListOfCustomFormRecord) > 0) {
                Template::ReplaceList($tempContent, $arrListOfCustomFormRecord, $listName,"icms_list");

                $pagerButton = Pager::ShowPageButton($tempContent, "", $allCount, $pageSize, $pageIndex ,$styleNumber = 1, $isJs = false, $jsFunctionName = "" , $jsParamList = "");
                $replaceArray = array(
                    "{channel_id}" => 0,
                    "{cid}" => 0,
                    "{id}" => 0,
                    "{pager_button}" => $pagerButton
                );
                $tempContent = strtr($tempContent, $replaceArray);
                parent::ReplaceEnd($tempContent);
                $result = $tempContent;
            }else{
                $result = DefineCode::CUSTOM_FORM_MANAGE+self::SELECT_CUSTOM_FORM_LIST_RESULT_NULL;
            }
        }
        return $result;
    }

}

?>
