<?php

/**
 * 活动表单页面生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_CustomForm
 * @author 525
 */
class CustomFormFieldManageGen extends BaseManageGen implements IBaseManageGen {

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
        $tempContent = Template::Load("CustomForm/custom_form_field_deal.html");
        $manageUserId = Control::GetManageUserID();
        $customFormId = Control::GetRequest("custom_form_id", 0);
        $customFormManageData = new CustomFormManageData();
        $channelId = $customFormManageData->GetChannelID($customFormId,FALSE);
        $tab_index = Control::GetRequest("tab", 0);
        $page_index = Control::GetRequest("p", 1);
        $siteId = 0;
        parent::ReplaceFirst($tempContent);

        if ($customFormId > 0) {
            $channelManageData = new ChannelManageData();
            $siteId = $channelManageData->GetSiteID($channelId, FALSE);

            ///////////////判断是否有操作权限///////////////////
            $manageUserAuthority = new ManageUserAuthorityManageData();
            $can = $manageUserAuthority->CanCreate($siteId, $channelId, $manageUserId);
            if ($can != 1) {
                Control::ShowMessage(Language::Load('document', 26));
                Control::GoUrl("index.php?a=custom_form_field&m=list&custom_form_id=" . $customFormId);
                return "";
            }
            ////////////////////////////////////////////////////
            $replace_arr = array(
                "{custom_form_id}" => $customFormId,
                "{cid}" => $channelId,
                "{siteid}" => $siteId,
                "{channel_id}" => $channelId,
                "{manage_user_id}" => $manageUserId,
                "{tab}" => $tab_index,
                "{page_index}" => $page_index
            );
            $tempContent = strtr($tempContent, $replace_arr);

            parent::ReplaceWhenAdd($tempContent, 'cst_customformfield');

            if (!empty($_POST)) {
                $customFormFieldManageData = new CustomFormFieldManageData();
                $new_id = $customFormFieldManageData->Create($_POST);

                //加入操作log
                $operateContent = "CustomFormField：CustomFormFieldID：" . $new_id . "；result：" . $new_id . "；title：" . Control::PostRequest("f_custom_form_field_name", "");
                self::CreateManageUserLog($operateContent);
                Control::ShowMessage(Language::Load('document', 1));
                Control::GoUrl("index.php?a=custom_form_field&m=list&custom_form_id=" . $customFormId);
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
     * 编辑表单字段
     * @return string 执行结果
     */
    private function GenModify() {
        $tempContent = Template::Load("CustomForm/custom_form_field_deal.html");
        $customFormFieldId = Control::GetRequest("id", 0);
        $userId = Control::GetUserID();
        $tab_index = Control::GetRequest("tab", 1);
        $pageIndex = Control::GetRequest("p", 1);
        parent::ReplaceFirst($tempContent);

        if ($customFormFieldId > 0) {
            $customFormFieldManageData = new CustomFormFieldManageData();
            $customFormId = Control::GetRequest("custom_form_id", 0);
            $customFormManageData = new CustomFormManageData();
            $channelId = $customFormManageData->GetChannelID($customFormId,FALSE);
            $channelManageData = new ChannelManageData();
            $siteId = $channelManageData->GetSiteID($channelId, FALSE);

            $manageUserId = Control::GetManageUserID();
            ///////////////判断是否有操作权限///////////////////
            $manageUserAuthority = new ManageUserAuthorityManageData();
            //编辑权限
            $can = $manageUserAuthority->CanModify($siteId, $channelId, $manageUserId);
            if ($can != 1) {
                Control::ShowMessage(Language::Load('document', 26));
                Control::GoUrl("index.php?a=custom_form_field&m=list&custom_form_id=" . $customFormId);
                return "";
            }
            //操作他人的权限

            $createUserId = $customFormManageData->GetManageUserID($customFormId, FALSE);
            if ($createUserId !== $manageUserId) { //操作人不是发布人
                $can = $manageUserAuthority->CanDoOthers($siteId, $channelId, $manageUserId);
                if ($can != 1) {
                    Control::ShowMessage(Language::Load('document', 26));
                    Control::GoUrl("index.php?a=custom_form_field&m=list&custom_form_id=" . $customFormId);
                    return "";
                }
            }
            ////////////////////////////////////////////////////

            $channelName = $channelManageData->GetChannelName($channelId,FALSE);
            $replace_arr = array(
                "{custom_form_id}" => $customFormId,
                "{channel_id}" => $channelId,
                "{cid}" => $channelId,
                "{id}" => $customFormFieldId,
                "{site_id}" => $siteId,
                "{user_id}" => $userId,
                "{tab}" => $tab_index,
                "{page_index}" => $pageIndex
            );
            $tempContent = strtr($tempContent, $replace_arr);

            $arrayList = $customFormFieldManageData->GetOne($customFormFieldId);
            Template::ReplaceOne($tempContent, $arrayList, 1);

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

                $result = $customFormFieldManageData->Modify($_POST,$customFormFieldId);

                if ($result > 0) {

                }

                //加入操作log
                $operateContent = "CustomFormFieldId：" . $customFormFieldId . "；result：" . $customFormFieldId;
                self::CreateManageUserLog($operateContent);

                if ($result > 0) {
                    Control::GoUrl("index.php?a=custom_form_field&m=list&custom_form_id=" . $customFormId);
                } else {
                    Control::ShowMessage(Language::Load('document', 4));
                }
            }
        }

        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }

    /**
     * 获取表单字段列表
     * @return string 表单字段列表页面
     */
    private function GenList() {
        $manageUserId = Control::GetManageUserID();
        $customFormId = Control::GetRequest("custom_form_id", 0);
        $customFormManageData = new CustomFormManageData();
        $channelId = $customFormManageData->GetChannelID($customFormId,FALSE);
        $channelData = new ChannelManageData();
        $siteId = $channelData->GetSiteID($channelId,FALSE);
        ///////////////判断是否有操作权限///////////////////
        $manageUserAuthority = new ManageUserAuthorityManageData();
        $can = $manageUserAuthority->CanExplore($siteId, $channelId, $manageUserId);
        if ($can != 1) {
            Control::ShowMessage(Language::Load('document', 26));
            return "";
        }
        ////////////////////////////////////////////////////
        $siteManageData = new SiteManageData();
        $siteUrl = $siteManageData->GetSiteUrl($siteId,FALSE);


        $tempContent = Template::Load("customForm/custom_form_field_list.html");

        $type = Control::GetRequest("type", "");

        if ($customFormId > 0) {

            $listName = "custom_form_field";
            $customFormFieldManageData = new CustomFormFieldManageData();
            $arrList = $customFormFieldManageData->GetList($customFormId);

            if (count($arrList) > 0) {
                Template::ReplaceList($tempContent, $arrList, $listName);
            }
        }
        $replace_arr = array(
            "{custom_form_id}" => $customFormId,
            "{cid}" => 0,
            "{id}" => 0,
            "{site_url}" => $siteUrl
        );
        $tempContent = strtr($tempContent, $replace_arr);

        parent::ReplaceEnd($tempContent);
        Template::RemoveCMS($tempContent);
        return $tempContent;
    }

}

?>