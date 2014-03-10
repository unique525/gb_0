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
            case "customformcontent":
                $customFormContentGen = new CustomFormContentManageGen();
                $result = $customFormContentGen->Gen();
                break;
            case "customformrecord":
                $customFormRecordGen = new CustomFormRecordManageGen();
                $result = $customFormRecordGen->Gen();
                break;
            case "customformfield":
                $customFormFieldGen = new CustomFormFieldManageGen();
                $result = $customFormFieldGen->Gen();
                break;
            case "customformsearch":        //活动表单页面查询类
                $customFormSearchGen = new CustomFormSearchManageGen();
                $result = $customFormSearchGen->Gen();
                break;
            default:
                $method = Control::GetRequest("m", "");
                switch ($method) {
                    case "new":
                        $result = self::GenCreate();
                        break;
                    case "edit":
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
        $tempContent = Template::Load("CustomForm/CustomForm_Deal.html");
        $manageUserId = Control::GetManageUserId();
        $channelId = Control::GetRequest("cid", 0);
        $tabIndex = Control::GetRequest("tab", 0);
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
                $jsCode = 'self.parent.loadcustomformlist(1,"","");self.parent.$("#tabs").tabs("select","#tabs-1");';
                if ($tabIndex > 0) {
                    $jsCode = $jsCode . 'self.parent.$("#tabs").tabs("remove",' . ($tabIndex - 1) . ');';
                }
                Control::RunJS($jsCode);
                return "";
            }
            ////////////////////////////////////////////////////
            $replaceArray = array(
                "{cid}" => $channelId,
                "{siteid}" => $siteId,
                "{channelid}" => $channelId,
                "{manageuserid}" => $manageUserId,
                "{tab}" => $tabIndex,
                "{pageindex}" => $pageIndex
            );
            $tempContent = strtr($tempContent, $replaceArray);

            parent::ReplaceWhenAdd($tempContent, 'cst_customform');

            if (!empty($_POST)) {
                $customFormManageData = new CustomFormManageData();
                $newId = $customFormManageData->Create($_POST);

                //加入操作log
                $operateContent = "CustomForm：CustomFormID：" . $newId . "；result：" . $newId . "；title：" . Control::PostRequest("f_customformsubject", "");
                self::CreateManageUserLog($operateContent);

                Control::ShowMessage(Language::Load('document', 1));
                $jsCode = 'self.parent.loadcustomformlist(1,"","");self.parent.$("#tabs").tabs("select","#tabs-1");';

                if ($tabIndex > 0) {
                    $jsCode = $jsCode . 'self.parent.$("#tabs").tabs("remove",' . ($tabIndex - 1) . ');';
                }

                Control::RunJS($jsCode);
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
        $tempContent = Template::Load("CustomForm/CustomForm_Deal.html");
        $customFormId = Control::GetRequest("id", 0);
        $userId = Control::GetUserID();
        $tabIndex = Control::GetRequest("tab", 1);
        $pageIndex = Control::GetRequest("p", 1);
        parent::ReplaceFirst($tempContent);

        if ($customFormId > 0) {
            $customFormManageData = new CustomFormManageData();
            $channelId = $customFormManageData->GetChannelID($customFormId);
            $channelData = new channelManageData();
            $siteId = $channelData->GetSiteId($channelId,"");

            $nowManageUserId = Control::GetManageUserId();
            ///////////////判断是否有操作权限///////////////////
            $manageUserAuthority = new ManageUserAuthorityManageData();
            //编辑权限
            $can = $manageUserAuthority->CanModify($siteId, $channelId, $nowManageUserId);
            if ($can != 1) {
                Control::ShowMessage(Language::Load('document', 26));
                $jsCode = 'self.parent.loadcustomformlist(1,"","");self.parent.$("#tabs").tabs("select","#tabs-1");';
                if ($tabIndex > 0) {
                    $jsCode = $jsCode . 'self.parent.$("#tabs").tabs("remove",' . ($tabIndex - 1) . ');';
                }
                Control::RunJS($jsCode);
                return "";
            }
            //操作他人的权限

            $createUserId = $customFormManageData->GetAdminUserID($customFormId);
            if ($createUserId !== $nowManageUserId) { //操作人不是发布人
                $can = $manageUserAuthority->CanDoOthers($siteId, $channelId, $nowManageUserId);
                if ($can != 1) {
                    Control::ShowMessage(Language::Load('document', 26));
                    $jsCode = 'self.parent.loadcustomformlist(1,"","");self.parent.$("#tabs").tabs("select","#tabs-1");';
                    if ($tabIndex > 0) {
                        $jsCode = $jsCode . 'self.parent.$("#tabs").tabs("remove",' . ($tabIndex - 1) . ');';
                    }
                    Control::RunJS($jsCode);
                    return "";
                }
            }
            ////////////////////////////////////////////////////

            //$channelName = $channelData->GetName($channelId);
            $replaceArray = array(
                "{documentchannelid}" => $channelId,
                "{cid}" => $channelId,
                "{id}" => $customFormId,
                "{siteid}" => $siteId,
                "{userid}" => $userId,
                "{tab}" => $tabIndex,
                "{pageindex}" => $pageIndex
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
                $operateContent = "CustomForm：CustomFormId：" . $customFormId . "; result：" . $customFormId . "；title：" . Control::PostRequest("f_customformsubject", "");
                self::CreateManageUserLog($operateContent);

                if ($result > 0) {
                    $jsCode = 'self.parent.loadcustomformlist(' . $pageIndex . ',"","");self.parent.$("#tabs").tabs("select","#tabs-1");';

                    if ($tabIndex > 0) {
                        $jsCode = $jsCode . 'self.parent.$("#tabs").tabs("remove",' . ($tabIndex - 1) . ');';
                    }

                    Control::RunJS($jsCode);
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
        $channelId = Control::GetRequest("cid", 0);
        $manageUserId = Control::GetManageUserId();
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
        $siteData = new SiteManageData();
        $siteUrl = $siteData->GetSiteUrl($siteId);

        $result = Language::Load('document', 7);

        //$documentChannelType = $documentChannelData->GetDocumentChannelType($documentChannelId);
        $tempContent = Template::Load("CustomForm/CustomForm_List.html");

        $type = Control::GetRequest("type", "");
        $pageSize = Control::GetRequest("ps", 20);
        $pageIndex = Control::GetRequest("p", 1);
        $searchKey = Control::GetRequest("searchkey", "");
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
            $listName = "customform";
            $allCount = 0;
            $customFormData = new CustomFormManageData();
            $arrList = $customFormData->GetListPager($channelId, $pageBegin, $pageSize, $allCount, $searchKey, $type);

            if (count($arrList) > 0) {
                Template::ReplaceList($tempContent, $arrList, $listName);

                $pagerTemplate = Template::Load("pager_js.html");
                $isJs = true;
                $jsFunctionName = "loadcustomformlist";
                $jsParamList = ",'" . urlencode($searchKey) . "','" . $type . "'";
                $pagerButton = Pager::ShowPageButton($pagerTemplate, "", $allCount, $pageSize, $pageIndex, $isJs, $jsFunctionName, $jsParamList);
                $replaceArray = array(
                    "{documentchannelid}" => 0,
                    "{cid}" => 0,
                    "{id}" => 0,
                    "{pagerbutton}" => $pagerButton,
                    "{siteurl}" => $siteUrl
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
