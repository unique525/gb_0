<?php

/**
 * 活动表单页面生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_CustomForm
 * @author 525
 */
class CustomFormContentManageGen extends BaseManageGen implements IBaseManageGen {

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

    private function GenCreate(){

    }

    private function GenModify(){

    }

    /**
     * 获取表单字段内容列表
     * @return string 字段内容列表html页面
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
        $can = $manageUserAuthority->CanExplore($siteId, $channelId, $manageUserId);
        if ($can != 1) {
            Control::ShowMessage(Language::Load('document', 26));
            return "";
        }
        ////////////////////////////////////////////////////
        $siteManageData = new SiteManageData();
        $siteUrl = $siteManageData->GetSiteUrl($siteId,FALSE);


        $tempContent = Template::Load("customForm/custom_form_content_list.html");

        $type = Control::GetRequest("type", "");

        if ($customFormId > 0) {

            $customFormFieldManageData = new CustomFormFieldManageData();
            $arrFieldList = $customFormFieldManageData->GetList($customFormId);

            $customFormContentManageData = new CustomFormContentManageData();
            $arrayList = $customFormContentManageData->GetList($customFormId);

            if (count($arrayList) > 0) {
                for($i = 0; $i<count($arrayList); $i++){

                }
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
        $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
        Template::RemoveCMS($tempContent);
        return $tempContent;
    }

}

?>
