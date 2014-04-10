<?php

/**
 * 活动表单记录生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_CustomForm
 * @author 525
 */
class CustomFormRecordManageGen extends BaseManageGen implements IBaseManageGen {

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
     * 后台新增一条活动表单记录
     * @return string 新增表单记录字段内容表的html页面
     */
    private function GenCreate() {
        $tempContent = Template::Load("CustomForm/CustomFormRecord_Deal.html");
        $manageUserId = Control::GetManageUserID();
        $customFormId = Control::GetRequest("custom_form_id", 0);
        $customFormData = new CustomFormManageData();
        $channelId = $customFormData->GetChannelID($customFormId, FALSE);
        $tabIndex = Control::GetRequest("tab", 0);
        $pageIndex = Control::GetRequest("p", 1);
        $siteId = 0;
        parent::ReplaceFirst($tempContent);

        if ($customFormId > 0) {
            $channelData = new ChannelManageData();
            $siteId = $channelData->GetSiteID($channelId, 0);

            ///////////////判断是否有操作权限///////////////////
            $manageUserAuthority = new ManageUserAuthorityManageData();
            $can = $manageUserAuthority->CanCreate($siteId, $channelId, $manageUserId);
            if ($can != 1) {
                Control::ShowMessage(Language::Load('document', 26));
                Control::GoUrl("index.php?a=custom_form_record&m=list&custom_form_id=" . $customFormId);
                return "";
            }

            $customFormContentTable = self::_genCustomFormContentTable($customFormId);

            ////////////////////////////////////////////////////
            $replaceArr = array(
                "{custom_form_record_id}" => '',
                "{custom_form_id}" => $customFormId,
                "{cid}" => $channelId,
                "{site_id}" => $siteId,
                "{channel_id}" => $channelId,
                "{manage_user_id}" => $manageUserId,
                "{tab}" => $tabIndex,
                "{page_index}" => $pageIndex,
                "{custom_form_content_table}" => $customFormContentTable
            );
            $tempContent = strtr($tempContent, $replaceArr);

            parent::ReplaceWhenAdd($tempContent, 'cst_customformfield');

            if (!empty($_POST)) {
                $customFormRecordManageData = new CustomFormRecordManageData();
                $newId = $customFormRecordManageData->Create($_POST);

                if ($newId > 0) {

                    //新增内容表
                    $customFormContentManageData = new CustomFormContentManageData();
                    $customFormContentManageData->CreateOrModify($_POST,$newId);
                }

                //加入操作log

                $operateContent = "CustomFormRecord：CustomFormRecordD：" . $newId . "；result：" . $newId;
                self::CreateManageUserLog($operateContent);

                Control::ShowMessage(Language::Load('document', 1));
                Control::GoUrl("index.php?a=customformrecord&m=list&customformid=" . $customFormId);
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
     * 后台修改表单记录字段内容
     * @return type
     */
    private function GenModify() {
        $tempContent = Template::Load("CustomForm/custom_form_record_deal.html");
        $manageUserId = Control::GetManageUserID();
        $customFormRecordId = Control::GetRequest("id", 0);
        $customFormId = Control::GetRequest("custom_form_id", 0);
        $customFormManageData = new CustomFormManageData();
        $channelId = $customFormManageData->GetChannelID($customFormId, FALSE);
        $tabIndex = Control::GetRequest("tab", 0);
        $pageIndex = Control::GetRequest("p", 1);
        $siteId = 0;
        parent::ReplaceFirst($tempContent);

        if ($customFormRecordId > 0) {
            $channelManageData = new ChannelManageData();
            $siteId = $channelManageData->GetSiteID($channelId, FALSE);

            ///////////////判断是否有操作权限///////////////////

            $manageUserAuthority = new ManageUserAuthorityManageData();
            $can = $manageUserAuthority->CanModify($siteId, $channelId, $manageUserId);
            if ($can != 1) {
                Control::ShowMessage(Language::Load('document', 26));
                Control::GoUrl("index.php?a=custom_form_record&m=list&custom_form_id=" . $customFormId . "&cid=" . $channelId . "&p=" . $pageIndex . "&tab=" . $tabIndex);
                return "";
            }
            //操作他人的权限

            $createUserId = $customFormManageData->GetManageUserID($customFormId, FALSE);
            if ($createUserId !== $manageUserId) { //操作人不是发布人
                $can = $manageUserAuthority->CanDoOthers($siteId, $channelId, $manageUserId);
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

            $customFormContentData = new CustomFormContentManageData();
            $arrContentList = $customFormContentData->GetList($customFormRecordId);
            $customFormContentTable = self::_genCustomFormContentTable($customFormId, $arrContentList);

            ////////////////////////////////////////////////////
            $replaceArray = array(
                "{custom_form_id}" => $customFormId,
                "{cid}" => $channelId,
                "{siteid}" => $siteId,
                "{channel_id}" => $channelId,
                "{manage_user_id}" => $manageUserId,
                "{tab}" => $tabIndex,
                "{page_index}" => $pageIndex,
                "{custom_form_content_table}" => $customFormContentTable
            );
            $tempContent = strtr($tempContent, $replaceArray);

            $customFormRecordData = new CustomFormRecordManageData();

            $arrList = $customFormRecordData->GetOne($customFormRecordId);
            Template::ReplaceOne($tempContent, $arrList, 1);

            if (!empty($_POST)) {

                $result = $customFormRecordData->Modify($_POST,$customFormRecordId);

                if ($result > 0) {

                    //修改内容表

                    $customFormContentData->CreateOrModify($_POST,$customFormRecordId);
                }

                //加入操作log
                $operateContent = "CustomFormRecord：CustomFormRecordD：" . $customFormRecordId . "；result：" . $customFormRecordId;
                self::CreateManageUserLog($operateContent);

                Control::GoUrl("index.php?a=custom_form_record&m=list&custom_form_id=" . $customFormId . "&cid=" . $channelId . "&p=" . $pageIndex . "&tab=" . $tabIndex);
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




    //前台
    public function GenFrontNew() {
        if (!empty($_POST)) {
            $customFormRecordData = new CustomFormRecordData();
            $newid = $customFormRecordData->Create();

            if ($newid > 0) {
                //新增内容表
                $customFormContentData = new CustomFormContentData();
                //$customFormContentData->CreateOrModify($newid);
                $CustomFormRecordID = $newid;
                if (!empty($_POST) && !empty($CustomFormRecordID)) {
                    //先删除旧数据
                    $customFormContentData->Delete($CustomFormRecordID);
                    $_userId = Control::GetUserID();
                    //读取表单 cf_customformid_customformfieldid
                    foreach ($_POST as $key => $value) {
                        if (strpos($key, "cf_") === 0) { //
                            $arr = Format::ToSplit($key, '_');
                            if (count($arr) == 3) {
                                $_customFormId = $arr[1];
                                $_customFormFieldId = $arr[2];
                                //为数组则转化为逗号分割字符串,对应checkbox应用
                                if (is_array($value)) {
                                    $value = implode(",", $value);
                                }
                                $value = stripslashes($value);
                                $customFormContentData->Create($CustomFormRecordID, $_customFormId, $_customFormFieldId, $_userId, $value);
                            }
                        }
                    }
                }
                Control::ShowMessage(Language::Load('customform', 1));
                $jscode = 'javascript:history.go(-1);';
                Control::RunJS('window.close(true);');
                //return $newid;
            } else {
                Control::ShowMessage(Language::Load('customform', 0));
                Control::RunJS('history.go(-1);');
                return -10;
            }
        }
        if (!empty($_FILES)) {
            foreach ($_FILES as $key => $value) {
                if (strpos($key, "cf_") === 0) { //
                    $arr = Format::ToSplit($key, '_');
                    if (count($arr) == 3) {
                        $_customFormId = $arr[1];
                        $_customFormFieldId = $arr[2];
                        //为数组则转化为逗号分割字符串,对应checkbox应用
                        if (is_array($value)) {
                            $value = implode(",", $value);
                        }
                        $value = stripslashes($value);
                        $customFormContentData->Create($CustomFormRecordID, $_customFormId, $_customFormFieldId, $_userId, $value);
                    }
                }
                if (strpos($key, "cff_") === 0) { //
                    $commonGen = new CommonGen();
                    $type = 19; //customform file
                    $returntype = 1;
                    $uploadfileid = 0;
                    $filePath = $commonGen->UploadFile($key, $type, $returntype, $uploadfileid);
                    $arr = Format::ToSplit($key, '_');
                    if (count($arr) == 3) {
                        $_customFormId = $arr[1];
                        $_customFormFieldId = $arr[2];
                        //为数组则转化为逗号分割字符串,对应checkbox应用
                        $value = $filePath;
                        if (is_array($value)) {
                            $value = implode(",", $value);
                        }
                        $value = stripslashes($value);
                        $customFormContentData->Create($CustomFormRecordID, $_customFormId, $_customFormFieldId, $_userId, $value);
                    }
                }
            }
        }
    }

    /**
     * 取得表单记录内容表格
     * @param int $customFormId 表单id
     * @param array $arrContentList 表单内容数据集
     * @return string 表单记录内容HTML表格
     */
    private function _genCustomFormContentTable($customFormId, $arrContentList = null) {
        $customFormContentTable = '';

        //生成字段
        $customFormFieldManageData = new CustomFormFieldManageData();
        $arrFieldList = $customFormFieldManageData->GetList($customFormId);

        for ($f = 0; $f < count($arrFieldList); $f++) {
            $customFormFieldID = intval($arrFieldList[$f]["CustomFormFieldID"]);
            $customFormFieldType = intval($arrFieldList[$f]["CustomFormFieldType"]);
            $inputValue = "";
            if (!empty($arrContentList)) {
                for ($k = 0; $k < count($arrContentList); $k++) {
                    if ($arrContentList[$k]["CustomFormFieldID"] == $customFormFieldID) {
                        switch ($customFormFieldType) {
                            case 0:
                                $inputValue = $arrContentList[$k]["ContentOfInt"];
                                break;
                            case 1:
                                $inputValue = $arrContentList[$k]["ContentOfString"];
                                break;
                            case 2:
                                $inputValue = $arrContentList[$k]["ContentOfText"];
                                break;
                            case 3:
                                $inputValue = $arrContentList[$k]["ContentOfFloat"];
                                break;
                            case 4:
                                $inputValue = $arrContentList[$k]["ContentOfDatetime"];
                                break;
                            case 5:
                                $inputValue = $arrContentList[$k]["ContentOfBlob"];
                                break;
                        }
                    }
                }
            }
            $inputName = 'cf_' . $customFormId . '_' . $arrFieldList[$f]["CustomFormFieldID"];

            $addClass = '';
            $addStyle = '';
            $inputText = '';
            switch ($customFormFieldType) {
                case 0: //int
                    $addClass = 'class="inputnumber"';
                    $addStyle = 'style=" width: 60px;"';
                    $inputText = '<input name="' . $inputName . '" id="' . $inputName . '" value="' . $inputValue . '" type="text" ' . $addClass . ' ' . $addStyle . ' />';
                    break;
                case 1: //string
                    $addClass = 'class="inputbox"';
                    $addStyle = 'style=" width: 300px;"';
                    $inputText = '<input name="' . $inputName . '" id="' . $inputName . '" value="' . $inputValue . '" type="text" ' . $addClass . ' ' . $addStyle . ' />';
                    break;
                case 2: //text
                    $addClass = 'class="inputbox"';
                    $addStyle = 'style=" width: 300px;height:100px;"';
                    $inputText = '<textarea name="' . $inputName . '" id="' . $inputName . '" ' . $addClass . ' ' . $addStyle . ' >' . $inputValue . '</textarea>';
                    break;
                case 3: //float
                    $addClass = 'class="inputprice"';
                    $addStyle = 'style=" width: 60px;"';
                    $inputText = '<input name="' . $inputName . '" id="' . $inputName . '" value="' . $inputValue . '" type="text" ' . $addClass . ' ' . $addStyle . ' />';
                    break;
                case 4: //date
                    $addClass = 'class="inputbox"';
                    $addStyle = 'style=" width: 100px;"';
                    $inputText = '<input name="' . $inputName . '" id="' . $inputName . '" value="' . $inputValue . '" type="text" ' . $addClass . ' ' . $addStyle . ' />';
                    break;
            }
            $customFormContentTable .= '<tr>';
            $customFormContentTable .= '<td class="speline" height="30" align="right">' . $arrFieldList[$f]["CustomFormFieldName"] . '：</td>';
            $customFormContentTable .= '<td class="speline">' . $inputText . '</td>';
            $customFormContentTable .= '</tr>';
        }

        return $customFormContentTable;
    }

    /**
     * 列表
     * @return <type>
     */
    private function GenList() {
        $manageUserId = Control::GetManageUserID();
        $customFormId = Control::GetRequest("custom_form_id", 0);
        $customFormManageData = new CustomFormManageData();
        $channelId = $customFormManageData->GetChannelID($customFormId, FALSE);
        $channelManageData = new ChannelManageData();
        $siteId = $channelManageData->GetSiteID($channelId,1);
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


        //$documentChannelType = $documentChannelData->GetDocumentChannelType($documentchannelid);
        $tempContent = Template::Load("CustomForm/custom_form_record_list.html");

        $type = Control::GetRequest("type", "");

        if ($customFormId > 0) {

            $customFormFieldManageData = new CustomFormFieldManageData();
            $arrFieldList = $customFormFieldManageData->GetListForContent($customFormId);

            $customFormRecordManageData = new CustomFormRecordManageData();


            $pageSize = Control::GetRequest("ps", 20);
            //if ($documentChannelType !== 1) {
            //    $pageSize = 16;
            //}

            $pageIndex = Control::GetRequest("p", 1);
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $allCount = 0;
            $arrRecordList = $customFormRecordManageData->GetListPager($customFormId, $pageBegin, $pageSize, $allCount);
            $pagerTemplate = Template::Load("pager.html");
            $isJs = false;
            $jsFunctionName = "";
            $jsParamList = "";
            $pagerUrl = "?a=custom_form_record&m=list&custom_form_id=" . $customFormId . "&cid=" . $channelId . "&p={0}&tab=2";
            $pagerButton = Pager::ShowPageButton($pagerTemplate, $pagerUrl, $allCount, $pageSize, $pageIndex, $isJs, $jsFunctionName, $jsParamList);

            $customFormContentManageData = new CustomFormContentManageData();
            $listTemp="";
            if (count($arrRecordList) > 0) {
                $listTemp = '<table width="99%" class="docgrid" cellpadding="0" cellspacing="0">';
                $listTemp .= '<tr class="gridtitle">';
                $listTemp .= '<td style="padding-left:2px;"></td>';
                for ($f = 0; $f < count($arrFieldList); $f++) {
                    $listTemp .= '<td style="padding-left:2px;">' . $arrFieldList[$f]["CustomFormFieldName"] . '</td>';
                }
                $listTemp .= '<td style="padding-left:2px;width:40px;">状态</td>';
                $listTemp .= '<td style="padding-left:2px;width:180px;">时间</td>';
                $listTemp .= '</tr>';
                for ($i = 0; $i < count($arrRecordList); $i++) {
                    $customFormRecordID = intval($arrRecordList[$i]["CustomFormRecordID"]);

                    $listTemp .= '<tr class="griditem">';
                    $listTemp .= '<td class="speline2" style="padding-left:2px;"><a href="../custom_form/index.php?a=custom_form_record&m=edit&customformid=' . $customFormId . '&id=' . $customFormRecordID . '&cid=' . $channelId . '&p=' . $pageIndex . '&tab=2"><img class="edit_doc" src="../images/edit.gif" alt="编辑" title="编辑" /></a></td>';

                    $arrContentList = $customFormContentManageData->GetList($customFormRecordID);

                    for ($j = 0; $j < count($arrFieldList); $j++) {
                        $customFormFieldID = $arrFieldList[$j]["CustomFormFieldID"];
                        $customFormFieldName = $arrFieldList[$j]["CustomFormFieldName"];
                        $customFormFieldType = $arrFieldList[$j]["CustomFormFieldType"];
                        $listTemp .= '<td class="speline2" style="padding-left:2px;">';
                        for ($k = 0; $k < count($arrContentList); $k++) {

                            if ($arrContentList[$k]["CustomFormFieldID"] == $customFormFieldID) {
                                switch (intval($customFormFieldType)) {
                                    case 0:
                                        $listTemp .= $arrContentList[$k]["ContentOfInt"];
                                        break;
                                    case 1:
                                        $listTemp .= $arrContentList[$k]["ContentOfString"];
                                        break;
                                    case 2:
                                        $listTemp .= $arrContentList[$k]["ContentOfText"];
                                        break;
                                    case 3:
                                        $listTemp .= $arrContentList[$k]["ContentOfFloat"];
                                        break;
                                    case 4:
                                        $listTemp .= $arrContentList[$k]["ContentOfDatetime"];
                                        break;
                                    case 5:
                                        $listTemp .= $arrContentList[$k]["ContentOfBlob"];
                                        break;
                                }
                            }
                        }
                        $listTemp .= '</td>';
                    }
                    $listTemp .= '<td class="speline2" style="padding-left:2px;">' . Format::ToState($arrRecordList[$i]["State"], "customformrecord") . '</td>';
                    $listTemp .= '<td class="speline2" style="padding-left:2px;">' . $arrRecordList[$i]["CreateDate"] . '</td>';
                    $listTemp .= '</tr>';
                }
                $listTemp .= '</tabel>';
            }
            $replace_arr = array(
                "{custom_form_id}" => $customFormId,
                "{cid}" => 0,
                "{id}" => 0,
                "{site_url}" => $siteUrl,
                "{list_temp}" => $listTemp,
                "{pager_button}" => $pagerButton
            );
            $tempContent = strtr($tempContent, $replace_arr);
        }


        parent::ReplaceEnd($tempContent);
        Template::RemoveCMS($tempContent);
        return $tempContent;
    }

}

?>