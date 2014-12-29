<?php

/**
 * 活动表单页面生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_CustomForm
 * @author 525
 */
class CustomFormFieldManageGen extends BaseManageGen implements IBaseManageGen {


    /**
     * 表单字段id错误
     */
    const FALSE_CUSTOM_FORM_FIELD_ID = -1;
    /**
     * 写入、修改数据库操作失败
     */
    const INSERT_OR_UPDATE_FAILED = -2;
    /**
     * 表单id错误
     */
    const FALSE_CUSTOM_FORM_ID = -3;



    /**
     * 主生成方法(继承接口)
     * @return string 返回输出的HTML内容
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
        $replace_arr = array(
            "{method}" => $method
        );

        $result = strtr($result, $replace_arr);
        return $result;
    }

    /**
     * 新增表单字段
     * @return string 执行结果
     */
    private function GenCreate() {
        $tempContent = Template::Load("custom_form/custom_form_field_deal.html","common");
        $resultJavaScript="";
        $manageUserId = Control::GetManageUserId();
        $customFormId = Control::GetRequest("custom_form_id", 0);
        $customFormManageData = new CustomFormManageData();
        $channelId = $customFormManageData->GetChannelId($customFormId,FALSE);


        if ($customFormId > 0) {
        $channelManageData = new ChannelManageData();
        $siteId = $channelManageData->GetSiteId($channelId, FALSE);

        ///////////////判断是否有操作权限///////////////////
        $manageUserAuthority = new ManageUserAuthorityManageData();
        $can = $manageUserAuthority->CanChannelCreate($siteId, $channelId, $manageUserId);
        if ($can != 1) {
            Control::ShowMessage(Language::Load('custom_form', 3));
            return "";
        }

        if (!empty($_POST)) {
            $customFormFieldManageData = new CustomFormFieldManageData();
            $new_id = $customFormFieldManageData->Create($_POST);

            //记入操作log
            $operateContent = "Create CustomFormField：CustomFormFieldId：" . $new_id .",POST FORM:".implode("|",$_POST).";\r\nResult:". $new_id;
            self::CreateManageUserLog($operateContent);
            if($new_id>0){



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
                return DefineCode::CUSTOM_FORM_FIELD_MANAGE+self::INSERT_OR_UPDATE_FAILED;
            }
        }


        parent::ReplaceFirst($tempContent);

            ////////////////////////////////////////////////////
            $replaceArray = array(
                "{CustomFormId}" => $customFormId,
                "{ChannelId}" => $channelId,
                "{ManageUserId}" => $manageUserId,
                "{display}" =>""
            );
            $tempContent = strtr($tempContent, $replaceArray);


            $sourceCommonManageData = new SourceCommonManageData();
            $arrayOfTableNameList = $sourceCommonManageData->GetFields("cst_custom_form_field");
            parent::ReplaceWhenCreate($tempContent,$arrayOfTableNameList);

            //去掉s开头的标记 {s_xxx_xxx}
            $patterns = '/\{s_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);

            ///////////////////////////////////////////////////////
        }else{
            return DefineCode::CUSTOM_FORM_FIELD_MANAGE+self::FALSE_CUSTOM_FORM_ID;
        }
        parent::ReplaceEnd($tempContent);
        $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
        return $tempContent;
    }

    /**
     * 编辑表单字段
     * @return string 执行结果
     */
    private function GenModify() {
        $tempContent = Template::Load("custom_form/custom_form_field_deal.html","common");
        $resultJavaScript="";
        $customFormFieldId = Control::GetRequest("custom_form_field_id", 0);

        if (!empty($_POST)) {

            $customFormFieldManageData = new CustomFormFieldManageData();
            $result = $customFormFieldManageData->Modify($_POST,$customFormFieldId);

            //记入操作log
            $operateContent = "Modify CustomFormField：CustomFormFieldId：" . $customFormFieldId .",POST FORM:".implode("|",$_POST).";\r\nResult:". $result;
            self::CreateManageUserLog($operateContent);

            if ($result > 0) {

                Control::ShowMessage(Language::Load('custom_form', 1));
                $closeTab = Control::PostRequest("CloseTab",0);
                if($closeTab == 1){
                    $resultJavaScript .= Control::GetCloseTab();
                }else{
                    Control::GoUrl($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']);
                }

            }else{
                Control::ShowMessage(Language::Load('custom_form', 2));
                return DefineCode::CUSTOM_FORM_FIELD_MANAGE+self::INSERT_OR_UPDATE_FAILED;
            }


        }
        parent::ReplaceFirst($tempContent);

        if ($customFormFieldId > 0) {
            $customFormFieldManageData = new CustomFormFieldManageData();
            $customFormId = Control::GetRequest("custom_form_id", 0);
            $customFormManageData = new CustomFormManageData();
            $channelId = $customFormManageData->GetChannelId($customFormId,FALSE);
            $channelManageData = new ChannelManageData();
            $siteId = $channelManageData->GetSiteId($channelId, FALSE);

            $manageUserId = Control::GetManageUserId();
            ///////////////判断是否有操作权限///////////////////
            $manageUserAuthority = new ManageUserAuthorityManageData();
            //编辑权限
            $can = $manageUserAuthority->CanChannelModify($siteId, $channelId, $manageUserId);
            if ($can != 1) {
                Control::ShowMessage(Language::Load('custom_form', 3));
                return "";
            }
            //操作他人的权限

            $createUserId = $customFormManageData->GetManageUserId($customFormId, FALSE);
            if ($createUserId !== $manageUserId) { //操作人不是发布人
                $can = $manageUserAuthority->CanChannelDoOthers($siteId, $channelId, $manageUserId);
                if ($can != 1) {
                    Control::ShowMessage(Language::Load('custom_form', 3));
                    return "";
                }
            }
            ////////////////////////////////////////////////////

            $replaceArray = array(
                "{CustomFormFieldId}"=>$customFormFieldId,
                "{CustomFormId}" => $customFormId,
                "{ChannelId}" => $channelId,
                "{ManageUserId}" => $manageUserId,
                "{display}" =>"none"
            );
            $tempContent = strtr($tempContent, $replaceArray);

            $arrayList = $customFormFieldManageData->GetOne($customFormFieldId);
            Template::ReplaceOne($tempContent, $arrayList);

            //去掉s开头的标记 {s_xxx_xxx}
            $patterns = '/\{s_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
            //去掉c开头的标记 {c_xxx}
            $patterns = '/\{c_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
            //去掉r开头的标记 {r_xxx_xxx}
            $patterns = '/\{r_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);


        }else{
            return DefineCode::CUSTOM_FORM_FIELD_MANAGE+self::FALSE_CUSTOM_FORM_FIELD_ID;
        }

        parent::ReplaceEnd($tempContent);
        $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
        return $tempContent;
    }

    /**
     * 获取表单字段列表
     * @return string 表单字段列表页面
     */
    private function GenList() {
        $manageUserId = Control::GetManageUserId();
        $customFormId = Control::GetRequest("custom_form_id", 0);
        $resultJavaScript="";
        $customFormManageData = new CustomFormManageData();
        $channelId = $customFormManageData->GetChannelId($customFormId,FALSE);
        $channelData = new ChannelManageData();
        $siteId = $channelData->GetSiteId($channelId,FALSE);
        ///////////////判断是否有操作权限///////////////////
        $manageUserAuthority = new ManageUserAuthorityManageData();
        $can = $manageUserAuthority->CanChannelExplore($siteId, $channelId, $manageUserId);
        if ($can != 1) {
            Control::ShowMessage(Language::Load('custom_form', 3));
            return "";
        }
        ////////////////////////////////////////////////////


        $tempContent = Template::Load("custom_form/custom_form_field_list.html","common");


        if ($customFormId > 0) {
            $noField="";
            $listName = "custom_form_field";
            $customFormFieldManageData = new CustomFormFieldManageData();
            $arrayOfFieldList = $customFormFieldManageData->GetList($customFormId);
            if (count($arrayOfFieldList) > 0) {
                Template::ReplaceList($tempContent, $arrayOfFieldList, $listName);
            }else{
                Template::RemoveCustomTag($tempContent,$listName);
                $noField=Language::Load("custom_form", 5);
            }

        $replace_arr = array(
            "{CustomFormId}" => $customFormId,
            "{NoField}" =>$noField
        );
        $tempContent = strtr($tempContent, $replace_arr);

        parent::ReplaceEnd($tempContent);
            $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
        return $tempContent;
        }else{
            return DefineCode::CUSTOM_FORM_FIELD_MANAGE+self::FALSE_CUSTOM_FORM_FIELD_ID;
        }
    }

}

?>