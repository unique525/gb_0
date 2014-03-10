<?php

/**
 * 前台活动表单记录生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_CustomForm
 * @author 525
 */
class CustomFormRecordPublicGen extends BasePublicGen implements IBasePublicGen {

    /**
     * 主生成方法(继承接口)
     * @return string 返回输出的HTML内容
     */
    function Gen() {
        $result = "";
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
        $replace_arr = array(
            "{method}" => $method
        );

        $result = strtr($result, $replace_arr);
        return $result;
    }



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
     *
     * @param type $customformid
     * @return string
     */
    private function _genCustomFormContentTable($customformid, $arrContentList = null) {
        $_customFormContentTable = '';

        //生成字段
        $customFormFieldData = new CustomFormFieldData();
        $arrFieldList = $customFormFieldData->GetList($customformid);

        for ($f = 0; $f < count($arrFieldList); $f++) {
            $_customFormFieldID = intval($arrFieldList[$f]["CustomFormFieldID"]);
            $_customFormFieldType = intval($arrFieldList[$f]["CustomFormFieldType"]);
            $_inputValue = "";
            if (!empty($arrContentList)) {
                for ($k = 0; $k < count($arrContentList); $k++) {
                    if ($arrContentList[$k]["CustomFormFieldID"] == $_customFormFieldID) {
                        switch ($_customFormFieldType) {
                            case 0:
                                $_inputValue = $arrContentList[$k]["ContentOfInt"];
                                break;
                            case 1:
                                $_inputValue = $arrContentList[$k]["ContentOfString"];
                                break;
                            case 2:
                                $_inputValue = $arrContentList[$k]["ContentOfText"];
                                break;
                            case 3:
                                $_inputValue = $arrContentList[$k]["ContentOfFloat"];
                                break;
                            case 4:
                                $_inputValue = $arrContentList[$k]["ContentOfDatetime"];
                                break;
                            case 5:
                                $_inputValue = $arrContentList[$k]["ContentOfBlob"];
                                break;
                        }
                    }
                }
            }
            $_inputName = 'cf_' . $customformid . '_' . $arrFieldList[$f]["CustomFormFieldID"];

            $_addClass = '';
            $_addStyle = '';
            $_inputText = '';
            switch ($_customFormFieldType) {
                case 0: //int
                    $_addClass = 'class="inputnumber"';
                    $_addStyle = 'style=" width: 60px;"';
                    $_inputText = '<input name="' . $_inputName . '" id="' . $_inputName . '" value="' . $_inputValue . '" type="text" ' . $_addClass . ' ' . $_addStyle . ' />';
                    break;
                case 1: //string
                    $_addClass = 'class="inputbox"';
                    $_addStyle = 'style=" width: 300px;"';
                    $_inputText = '<input name="' . $_inputName . '" id="' . $_inputName . '" value="' . $_inputValue . '" type="text" ' . $_addClass . ' ' . $_addStyle . ' />';
                    break;
                case 2: //text
                    $_addClass = 'class="inputbox"';
                    $_addStyle = 'style=" width: 300px;height:100px;"';
                    $_inputText = '<textarea name="' . $_inputName . '" id="' . $_inputName . '" ' . $_addClass . ' ' . $_addStyle . ' >' . $_inputValue . '</textarea>';
                    break;
                case 3: //float
                    $_addClass = 'class="inputprice"';
                    $_addStyle = 'style=" width: 60px;"';
                    $_inputText = '<input name="' . $_inputName . '" id="' . $_inputName . '" value="' . $_inputValue . '" type="text" ' . $_addClass . ' ' . $_addStyle . ' />';
                    break;
                case 4: //date
                    $_addClass = 'class="inputbox"';
                    $_addStyle = 'style=" width: 100px;"';
                    $_inputText = '<input name="' . $_inputName . '" id="' . $_inputName . '" value="' . $_inputValue . '" type="text" ' . $_addClass . ' ' . $_addStyle . ' />';
                    break;
            }
            $_customFormContentTable .= '<tr>';
            $_customFormContentTable .= '<td class="speline" height="30" align="right">' . $arrFieldList[$f]["CustomFormFieldName"] . '：</td>';
            $_customFormContentTable .= '<td class="speline">' . $_inputText . '</td>';
            $_customFormContentTable .= '</tr>';
        }

        return $_customFormContentTable;
    }

    /**
     * 列表
     * @return <type>
     */
    private function GenList() {
        $adminuserid = Control::GetAdminUserID();
        $customformid = Control::GetRequest("customformid", 0);
        $customFormData = new CustomFormData();
        $documentchannelid = $customFormData->GetDocumentChannelID($customformid);
        $documentChannelData = new DocumentChannelData();
        $siteid = $documentChannelData->GetSiteID($documentchannelid);
        ///////////////判断是否有操作权限///////////////////
        $adminPopedomData = new AdminPopedomData();
        $can = $adminPopedomData->CanExplore($siteid, $documentchannelid, $adminuserid);
        if ($can != 1) {
            Control::ShowMessage(Language::Load('document', 26));
            return "";
        }
        ////////////////////////////////////////////////////
        $sitedata = new SiteData();
        $siteUrl = $sitedata->GetSiteUrl($siteid);


        //$documentChannelType = $documentChannelData->GetDocumentChannelType($documentchannelid);
        $tempContent = Template::Load("customform/customformrecord_list.html");

        $type = Control::GetRequest("type", "");

        if ($customformid > 0) {

            $customFormFieldData = new CustomFormFieldData();
            $arrFieldList = $customFormFieldData->GetListForContent($customformid);

            $customFormRecordData = new CustomFormRecordData();


            $pagesize = Control::GetRequest("ps", 20);
            if ($documentChannelType !== 1) {
                $pagesize = 16;
            }

            $pageindex = Control::GetRequest("p", 1);
            $pagebegin = ($pageindex - 1) * $pagesize;
            $allcount = 0;
            $arrRecordList = $customFormRecordData->GetListPager($customformid, $pagebegin, $pagesize, $allcount);
            $pagerTemplate = Template::Load("pager.html");
            $isjs = false;
            $jsfunctionname = "";
            $jsparamlist = "";
            $pagerurl = "?a=customformrecord&m=list&customformid=" . $customformid . "&cid=" . $documentchannelid . "&p={0}&tab=2";
            $pagerbutton = Pager::ShowPageButton($pagerTemplate, $pagerurl, $allcount, $pagesize, $pageindex, $isjs, $jsfunctionname, $jsparamlist);

            $customFormContentData = new CustomFormContentData();

            if (count($arrRecordList) > 0) {
                $listtemp = '<table width="99%" class="docgrid" cellpadding="0" cellspacing="0">';
                $listtemp .= '<tr class="gridtitle">';
                $listtemp .= '<td style="padding-left:2px;"></td>';
                for ($f = 0; $f < count($arrFieldList); $f++) {
                    $listtemp .= '<td style="padding-left:2px;">' . $arrFieldList[$f]["CustomFormFieldName"] . '</td>';
                }
                $listtemp .= '<td style="padding-left:2px;width:40px;">状态</td>';
                $listtemp .= '<td style="padding-left:2px;width:180px;">时间</td>';
                $listtemp .= '</tr>';
                for ($i = 0; $i < count($arrRecordList); $i++) {
                    $_customFormRecordID = intval($arrRecordList[$i]["CustomFormRecordID"]);

                    $listtemp .= '<tr class="griditem">';
                    $listtemp .= '<td class="speline2" style="padding-left:2px;"><a href="../customform/index.php?a=customformrecord&m=edit&customformid=' . $customformid . '&id=' . $_customFormRecordID . '&cid=' . $documentchannelid . '&p=' . $pageindex . '&tab=2"><img class="edit_doc" src="../images/edit.gif" alt="编辑" title="编辑" /></a></td>';

                    $arrContentList = $customFormContentData->GetList($_customFormRecordID);

                    for ($j = 0; $j < count($arrFieldList); $j++) {
                        $_customFormFieldID = $arrFieldList[$j]["CustomFormFieldID"];
                        $_customFormFieldName = $arrFieldList[$j]["CustomFormFieldName"];
                        $_customFormFieldType = $arrFieldList[$j]["CustomFormFieldType"];
                        $listtemp .= '<td class="speline2" style="padding-left:2px;">';
                        for ($k = 0; $k < count($arrContentList); $k++) {

                            if ($arrContentList[$k]["CustomFormFieldID"] == $_customFormFieldID) {
                                switch (intval($_customFormFieldType)) {
                                    case 0:
                                        $listtemp .= $arrContentList[$k]["ContentOfInt"];
                                        break;
                                    case 1:
                                        $listtemp .= $arrContentList[$k]["ContentOfString"];
                                        break;
                                    case 2:
                                        $listtemp .= $arrContentList[$k]["ContentOfText"];
                                        break;
                                    case 3:
                                        $listtemp .= $arrContentList[$k]["ContentOfFloat"];
                                        break;
                                    case 4:
                                        $listtemp .= $arrContentList[$k]["ContentOfDatetime"];
                                        break;
                                    case 5:
                                        $listtemp .= $arrContentList[$k]["ContentOfBlob"];
                                        break;
                                }
                            }
                        }
                        $listtemp .= '</td>';
                    }
                    $listtemp .= '<td class="speline2" style="padding-left:2px;">' . Format::ToState($arrRecordList[$i]["State"], "customformrecord") . '</td>';
                    $listtemp .= '<td class="speline2" style="padding-left:2px;">' . $arrRecordList[$i]["CreateDate"] . '</td>';
                    $listtemp .= '</tr>';
                }
                $listtemp .= '</tabel>';
            }
            $replace_arr = array(
                "{customformid}" => $customformid,
                "{cid}" => 0,
                "{id}" => 0,
                "{siteurl}" => $siteUrl,
                "{listtemp}" => $listtemp,
                "{pagerbutton}" => $pagerbutton
            );
            $tempContent = strtr($tempContent, $replace_arr);
        }


        parent::ReplaceEnd($tempContent);
        Template::RemoveCMS($tempContent);
        return $tempContent;
    }

}

?>