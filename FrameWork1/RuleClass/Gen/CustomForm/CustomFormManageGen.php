<?php

/**
 * 活动表单页面生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_CustomForm
 * @author 525
 */
class CustomFormManageGen extends BaseManageGen implements IBaseManageGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    function Gen() {
        $result = "";

        $action = Control::GetRequest("a", "");
        switch ($action) {
            case "custom_form_content":
                $customFormContentGen = new CustomFormContentManageGen();
                $result = $customFormContentGen->Gen();
                break;
            case "custom_form_record":
                $customFormRecordGen = new CustomFormRecordManageGen();
                $result = $customFormRecordGen->Gen();
                break;
            case "custom_form_field":
                $customFormFieldGen = new CustomFormFieldManageGen();
                $result = $customFormFieldGen->Gen();
                break;
            case "custom_form_search":        //活动表单页面查询类
                $customFormSearchGen = new CustomFormSearchManageGen();
                $result = $customFormSearchGen->Gen();
                break;
            default:
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
                break;
        }
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
        $tabIndex = Control::GetRequest("tab_count", 0);
        $pageIndex = Control::GetRequest("p", 1);
        $siteId = 0;
        parent::ReplaceFirst($tempContent);

        if ($channelId > 0) {
            $channelData = new ChannelManageData();
            $siteId = $channelData->GetSiteId($channelId,"");

            ///////////////判断是否有操作权限///////////////////
            $manageUserAuthority = new ManageUserAuthorityManageData();
            $can = $manageUserAuthority->CanCreate($siteId, $channelId, $manageUserId);
            if ($can != 1) {
                Control::ShowMessage(Language::Load('document', 26));
                /*$jsCode = 'self.parent.loadcustomformlist(1,"","");self.parent.$("#tabs").tabs("select","#tabs-1");';
                if ($tabIndex > 0) {
                    $jsCode = $jsCode . 'self.parent.$("#tabs").tabs("remove",' . ($tabIndex - 1) . ');';
                }
                Control::RunJS($jsCode);*/
                return "";
            }
            ////////////////////////////////////////////////////
            $replaceArray = array(
                "{site_id}" => $siteId,
                "{channel_id}" => $channelId,
                "{manage_user_id}" => $manageUserId,
                "{tab}" => $tabIndex,
                "{page_index}" => $pageIndex
            );
            $tempContent = strtr($tempContent, $replaceArray);

            $sourceCommonManageData = new SourceCommonManageData();
            $arrayOfTableNameList = $sourceCommonManageData->GetFields("cst_custom_form");
            parent::ReplaceWhenCreate($tempContent, $arrayOfTableNameList);

            if (!empty($_POST)) {
                $customFormManageData = new CustomFormManageData();
                $newId = $customFormManageData->Create($_POST);

                if($newId>0){
                    //加入操作log
                    $operateContent = "CustomForm：CustomFormID：" . $newId . "；result：" . $newId . "；title：" . Control::PostRequest("f_customformsubject", "");
                    self::CreateManageUserLog($operateContent);

                    Control::ShowMessage(Language::Load('document', 1));
                    /*$jsCode = 'self.parent.loadcustomformlist(1,"","");self.parent.$("#tabs").tabs("select","#tabs-1");';

                    if ($tabIndex > 0) {
                        $jsCode = $jsCode . 'self.parent.$("#tabs").tabs("remove",' . ($tabIndex - 1) . ');';
                    }

                    Control::RunJS($jsCode);*/

                }

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
        $customFormId = Control::GetRequest("custom_form_id", 0);
        $userId = Control::GetUserID();
        $tabIndex = Control::GetRequest("tab", 1);
        $pageIndex = Control::GetRequest("p", 1);
        parent::ReplaceFirst($tempContent);

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
                /*$jsCode = 'self.parent.loadcustomformlist(1,"","");self.parent.$("#tabs").tabs("select","#tabs-1");';
                if ($tabIndex > 0) {
                    $jsCode = $jsCode . 'self.parent.$("#tabs").tabs("remove",' . ($tabIndex - 1) . ');';
                }
                Control::RunJS($jsCode);*/
                return "";
            }
            //操作他人的权限

            $createUserId = $customFormManageData->GetManageUserID($customFormId,FALSE);
            if ($createUserId !== $nowManageUserId) { //操作人不是发布人
                $can = $manageUserAuthority->CanDoOthers($siteId, $channelId, $nowManageUserId);
                if ($can != 1) {
                    Control::ShowMessage(Language::Load('document', 26));
                    /*$jsCode = 'self.parent.loadcustomformlist(1,"","");self.parent.$("#tabs").tabs("select","#tabs-1");';
                    if ($tabIndex > 0) {
                        $jsCode = $jsCode . 'self.parent.$("#tabs").tabs("remove",' . ($tabIndex - 1) . ');';
                    }
                    Control::RunJS($jsCode);*/
                    return "";
                }
            }
            ////////////////////////////////////////////////////

            //$channelName = $channelData->GetName($channelId);
            $replaceArray = array(
                "{document_channel_id}" => $channelId,
                "{channel_id}" => $channelId,
                "{custom_form_id}" => $customFormId,
                "{site_id}" => $siteId,
                "{user_id}" => $userId,
                "{tab}" => $tabIndex,
                "{page_index}" => $pageIndex
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

                }

                //加入操作log
                $operateContent = "CustomForm：CustomFormId：" . $customFormId . "; result：" . $customFormId . "；title：" . Control::PostRequest("f_customFormSubject", "");
                self::CreateManageUserLog($operateContent);

                if ($result > 0) {
                    Control::ShowMessage(Language::Load('document', 3));
                    /*$jsCode = 'self.parent.loadcustomformlist(' . $pageIndex . ',"","");self.parent.$("#tabs").tabs("select","#tabs-1");';

                    if ($tabIndex > 0) {
                        $jsCode = $jsCode . 'self.parent.$("#tabs").tabs("remove",' . ($tabIndex - 1) . ');';
                    }

                    Control::RunJS($jsCode);*/
                } else {
                    Control::ShowMessage(Language::Load('document', 4));
                }
            }
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
            return $can;
        }
        ////////////////////////////////////////////////////
        //$siteData = new SiteManageData();
        //$siteUrl = $siteData->GetSiteUrl($siteId);
        $result = Language::Load('document', 7);

        //$documentChannelType = $documentChannelData->GetDocumentChannelType($documentChannelId);
        $tempContent = Template::Load("custom_form/custom_form_list.html","common");

        $type = Control::GetRequest("type", "");
        $pageSize = Control::GetRequest("ps", 20);
        $pageIndex = Control::GetRequest("p", 1);
        $searchKey = Control::GetRequest("search_key", "");
        $searchKey = urldecode($searchKey);
        if (isset($searchKey) && strlen($searchKey) > 0) {
            $can = $manageUserAuthority->CanSearch($siteId, $channelId, $manageUserId);
            if ($can != 1) {
                Control::ShowMessage(Language::Load('document', 26));
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

                /*$pagerTemplate = Template::Load("pager_js.html");
                $isJs = true;
                $jsFunctionName = "loadcustomformlist";
                $jsParamList = ",'" . urlencode($searchKey) . "','" . $type . "'";*/

                $pagerButton = Pager::ShowPageButton($tempContent, "", $allCount, $pageSize, $pageIndex ,$styleNumber = 1, $isJs = false, $jsFunctionName = "" , $jsParamList = "");
                $replaceArray = array(
                    "{channel_id}" => 0,
                    "{cid}" => 0,
                    "{id}" => 0,
                    "{pager_button}" => $pagerButton
                   // "{site_url}" => $siteUrl
                );
                $tempContent = strtr($tempContent, $replaceArray);
                parent::ReplaceEnd($tempContent);
                $result = $tempContent;
            }
        }
        return $result;
    }

}

?>
