<?php

/**
 * 活动表单记录生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_CustomForm
 * @author 525
 */
class CustomFormRecordManageGen extends BaseManageGen implements IBaseManageGen {


    /**
     * 表单ID错误
     */
    const FALSE_CUSTOM_FORM_ID = -1;
    /**
     * 数据字段取回结果 空
     */
    const SELECT_CUSTOM_FORM_RECORD_TABLE_NAME_LIST_RESULT_NULL = -2;
    /**
     * 新增记录写入数据库失败
     */
    const DATABASE_INPUT_FAILED = -3;
    /**
     * 表单记录ID错误
     */
    const FALSE_CUSTOM_FORM_RECORD_ID = -4;
    /**
     * 修改记录写入数据库失败
     */
    const DATABASE_UPDATE_FAILED = -5;
    /**
     * 新增记录时 记录字段内容写入数据库失败
     */
    const DATABASE_CREATE_FAILED_WHEN_CONTENT_CREATING = -6;
    /**
     * 修改记录时 记录字段内容写入数据库失败
     */
    const DATABASE_MODIFY_FAILED_WHEN_CONTENT_CREATING = -7;
    /**
     * 新增记录 字段内容重复
     */
    const REPEAT_CONTENT_IN_UNIQUE_FIELD = -8;




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
            case "random_list":
                $result = self::GenRandomList();
                break;
            case "search":
                $result = self::GenSearch();
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
        $tempContent = Template::Load("custom_form/custom_form_record_deal.html","common");
        $resultJavaScript="";
        $manageUserId = Control::GetManageUserId();
        $customFormId = Control::GetRequest("custom_form_id", 0);
        $customFormData = new CustomFormManageData();
        $channelId = $customFormData->GetChannelId($customFormId, FALSE);
        $tabIndex = Control::GetRequest("tab_index", 0);
        parent::ReplaceFirst($tempContent);


        if (!empty($_POST)) {


            $customFormRecordManageData = new CustomFormRecordManageData();
            $customFormContentManageData = new CustomFormContentManageData();
            $customFormFieldManageData = new CustomFormFieldManageData();

            $newId=0;

            //检查是否有唯一字段重复
            if($customFormId>0){
                $arrayUnique=$customFormFieldManageData->GetUniqueField($customFormId);
                $isRepeat=0;
                foreach($arrayUnique as $uniqueFiled){
                    $uniqueContent=Control::PostRequest("cf_".$customFormId."_".$uniqueFiled["CustomFormFieldId"],"");
                    $repeat=$customFormContentManageData->CheckRepeat($customFormId,$uniqueFiled["CustomFormFieldId"],$uniqueFiled["CustomFormFieldType"],$uniqueContent);
                    if($repeat>0){
                        $isRepeat=1;
                    }
                }
                if($isRepeat==0){
                    $newId = $customFormRecordManageData->Create($_POST);
                }else{

                    Control::ShowMessage(Language::Load('custom_form', 6));
                    return DefineCode::CUSTOM_FORM_RECORD_MANAGE+self::REPEAT_CONTENT_IN_UNIQUE_FIELD;
                }

            }


            if ($newId > 0) {

                //先删除旧数据
                $customFormContentManageData->Delete($newId);
                    //读取表单 cf_CustomFormId_CustomFormFieldId
                    foreach ($_POST as $key => $value) {
                        if (strpos($key, "cf_") === 0) { //
                            $arr = Format::ToSplit($key, '_');
                            if (count($arr) == 3) {
                                $customFormId = $arr[1];
                                $customFormFieldId = $arr[2];
                                //为数组则转化为逗号分割字符串,对应checkbox应用
                                if (is_array($value)) {
                                    $value = implode(",", $value);
                                }
                                $value = stripslashes($value);

                                $customFormFieldType = $customFormFieldManageData->GetCustomFormFieldType($customFormFieldId,FALSE);
                                $contentId=$customFormContentManageData->Create($newId, $customFormId, $customFormFieldId, $manageUserId, $value, $customFormFieldType);

                                //记入操作log
                                $operateContent = "Create CustomFormRecord：CustomFormRecord：" . $newId .",POST FORM:".implode("|",$_POST).";\r\nResult:". $contentId;
                                self::CreateManageUserLog($operateContent);

                                if($contentId<0){
                                    return DefineCode::CUSTOM_FORM_RECORD_MANAGE+self::DATABASE_CREATE_FAILED_WHEN_CONTENT_CREATING."field_id:".$customFormFieldId;
                                }
                            }
                        }
                    }

                //附件操作
                if (!empty($_FILES)) {
                    foreach($_FILES as $key => $value){
                        if (!empty($_FILES[$key]["tmp_name"])) {
                            $arr = Format::ToSplit($key, '_');
                            if (count($arr) == 3) {
                                $fileCustomFormId = $arr[1];
                                $fileCustomFormFieldId = $arr[2];
                                $fileType = $_FILES[$key]["type"];
                                $fileName = "Attachment_".$fileCustomFormFieldId."_".pathinfo($_FILES[$key]["name"],PATHINFO_EXTENSION);
                                $fileData = file_get_contents($_FILES[$key]["tmp_name"]);
                                $fileContentId=$customFormContentManageData->CreateAttachment(
                                    $newId,
                                    $fileCustomFormId,
                                    $fileCustomFormFieldId,
                                    $manageUserId,
                                    $fileData,
                                    $fileName,
                                    $fileType
                                );
                                if($fileContentId<0){
                                    return DefineCode::CUSTOM_FORM_RECORD_MANAGE+self::DATABASE_MODIFY_FAILED_WHEN_CONTENT_CREATING."field_id:".$fileCustomFormFieldId;
                                }
                            }
                        }
                    }
                }



                Control::ShowMessage(Language::Load('custom_form', 1));
                $closeTab = Control::PostRequest("CloseTab",0);
                if($closeTab == 1){
                    $resultJavaScript .= Control::GetCloseTab();
                }else{
                    Control::GoUrl($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']);
                    //Control::GoUrl($_SERVER["PHP_SELF"].'?secu=manage&mod=custom_form&m=list&site_id='.$siteId.'&channel_id='.$channelId);
                }

            }else{
                Control::ShowMessage(Language::Load('custom_form', 2));
                return DefineCode::CUSTOM_FORM_RECORD_MANAGE+self::DATABASE_INPUT_FAILED;
            }


        }




        if ($customFormId > 0) {
            $channelData = new ChannelManageData();
            $siteId = $channelData->GetSiteId($channelId, 0);

            ///////////////判断是否有操作权限///////////////////
            $manageUserAuthority = new ManageUserAuthorityManageData();
            $can = $manageUserAuthority->CanChannelCreate($siteId, $channelId, $manageUserId);
            if ($can != 1) {
                Control::ShowMessage(Language::Load('custom_form', 3));
                return "";
            }

            $customFormContentTable = self::_genCustomFormContentTable($customFormId);

            ////////////////////////////////////////////////////
            $crateDate=date('Y-m-d H:i:s');
            $replaceArr = array(
                "{CustomFormRecordId}" => '',
                "{CustomFormId}" => $customFormId,
                "{ManageUserId}" => $manageUserId,
                "{CreateDate}" => $crateDate,
                "{CustomFormContentTable}" => $customFormContentTable,
                "{TabIndex}" => $tabIndex,
                "{display}" => ""
            );
            $tempContent = strtr($tempContent, $replaceArr);


            $sourceCommonManageData = new SourceCommonManageData();
            $arrayOfTableNameList = $sourceCommonManageData->GetFields("cst_custom_form_record");
            if($arrayOfTableNameList>0){
                parent::ReplaceWhenCreate($tempContent, $arrayOfTableNameList);
            }else{
                return DefineCode::CUSTOM_FORM_RECORD_MANAGE+self::SELECT_CUSTOM_FORM_RECORD_TABLE_NAME_LIST_RESULT_NULL;
            }
            parent::ReplaceWhenCreate($tempContent,$arrayOfTableNameList);



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
     * 后台修改表单记录字段内容
     * @return string 修改表单记录字段内容表的html页面
     */
    private function GenModify() {
        $tempContent = Template::Load("custom_form/custom_form_record_deal.html","common");
        $resultJavaScript="";
        $manageUserId = Control::GetManageUserId();
        $customFormRecordId = Control::GetRequest("custom_form_record_id", 0);
        $customFormId = Control::GetRequest("custom_form_id", 0);
        $tabIndex = Control::GetRequest("tab_index", 0);
        $customFormManageData = new CustomFormManageData();
        $channelId = $customFormManageData->GetChannelId($customFormId, FALSE);
        parent::ReplaceFirst($tempContent);

        if ($customFormRecordId > 0) {
            $channelManageData = new ChannelManageData();
            $siteId = $channelManageData->GetSiteId($channelId, FALSE);

            ///////////////判断是否有操作权限///////////////////

            $manageUserAuthority = new ManageUserAuthorityManageData();
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

            $customFormContentData = new CustomFormContentManageData();
            $arrContentList = $customFormContentData->GetList($customFormRecordId);
            $customFormContentTable = self::_genCustomFormContentTable($customFormId, $arrContentList);

            ////////////////////////////////////////////////////
            $replaceArray = array(
                "{CustomFormRecordId}" => $customFormRecordId,
                "{CustomFormId}" => $customFormId,
                "{ChannelId}" => $channelId,
                "{ManageUserId}" => $manageUserId,
                "{CustomFormContentTable}" => $customFormContentTable,
                "{TabIndex}" => $tabIndex,
                "{display}" => "none"
            );
            $tempContent = strtr($tempContent, $replaceArray);

            $customFormRecordData = new CustomFormRecordManageData();

            $arrList = $customFormRecordData->GetOne($customFormRecordId);
            Template::ReplaceOne($tempContent, $arrList, 0);

            if (!empty($_POST)) {
                $result = $customFormRecordData->Modify($_POST,$customFormRecordId);

                if ($result > 0) {
                    //修改内容表
                    $customFormContentManageData = new CustomFormContentManageData();
                    $customFormFieldManageData = new CustomFormFieldManageData();
                    //先删除旧数据
                    $customFormContentManageData->Delete($customFormRecordId);
                    //读取表单 cf_CustomFormId_CustomFormFieldId
                    foreach ($_POST as $key => $value) {
                        if (strpos($key, "cf_") === 0) { //
                            $arr = Format::ToSplit($key, '_');
                            if (count($arr) == 3) {
                                $customFormId = $arr[1];
                                $customFormFieldId = $arr[2];
                                //为数组则转化为逗号分割字符串,对应checkbox应用
                                if (is_array($value)) {
                                    $value = implode(",", $value);
                                }
                                $value = stripslashes($value);

                                $customFormFieldType = $customFormFieldManageData->GetCustomFormFieldType($customFormFieldId,FALSE);
                                $contentId=$customFormContentManageData->Create($customFormRecordId, $customFormId, $customFormFieldId, $manageUserId, $value, $customFormFieldType);

                                //记入操作log
                                $operateContent = "Create CustomFormRecord：CustomFormRecord：" . $customFormRecordId .",POST FORM:".implode("|",$_POST).";\r\nResult:". $contentId;
                                self::CreateManageUserLog($operateContent);

                                if($contentId<0){
                                    return DefineCode::CUSTOM_FORM_RECORD_MANAGE+self::DATABASE_MODIFY_FAILED_WHEN_CONTENT_CREATING."field_id:".$customFormFieldId;
                                }
                            }
                        }
                    }


                    //附件操作
                    if (!empty($_FILES)) {
                        foreach($_FILES as $key => $value){
                            if (!empty($_FILES[$key]["tmp_name"])) {
                                $arr = Format::ToSplit($key, '_');
                                if (count($arr) == 3) {
                                    $fileCustomFormId = $arr[1];
                                    $fileCustomFormFieldId = $arr[2];
                                    $fileType = $_FILES[$key]["type"];
                                    $fileName = "Attachment_".$fileCustomFormFieldId."_".pathinfo($_FILES[$key]["name"],PATHINFO_EXTENSION);
                                    $fileData = file_get_contents($_FILES[$key]["tmp_name"]);
                                    $fileContentId=$customFormContentManageData->CreateAttachment(
                                        $customFormRecordId,
                                        $fileCustomFormId,
                                        $fileCustomFormFieldId,
                                        $manageUserId,
                                        $fileData,
                                        $fileName,
                                        $fileType
                                    );
                                    if($fileContentId<0){
                                        return DefineCode::CUSTOM_FORM_RECORD_MANAGE+self::DATABASE_MODIFY_FAILED_WHEN_CONTENT_CREATING."field_id:".$fileCustomFormFieldId;
                                    }
                                }
                            }
                        }
                    }

                    Control::ShowMessage(Language::Load('custom_form', 1));
                    $closeTab = Control::PostRequest("CloseTab",0);
                    if($closeTab == 1){
                        $resultJavaScript .= Control::GetCloseTab();
                    }else{
                        Control::GoUrl($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']);
                        //Control::GoUrl($_SERVER["PHP_SELF"].'?secu=manage&mod=custom_form&m=list&site_id='.$siteId.'&channel_id='.$channelId);
                    }


                }else{
                    return DefineCode::CUSTOM_FORM_RECORD_MANAGE+self::DATABASE_UPDATE_FAILED;
                }
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
        }else{
            return DefineCode::CUSTOM_FORM_RECORD_MANAGE+self::FALSE_CUSTOM_FORM_RECORD_ID;
        }
        parent::ReplaceEnd($tempContent);
        $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
        return $tempContent;
    }




    /**
     * 随机获取表单记录列表
     * @return string 表单列表页面html
     */
    private function GenRandomList() {
        $manageUserId = Control::GetManageUserId();
        $customFormId = Control::GetRequest("custom_form_id", 0);
        $resultJavaScript="";
        $customFormManageData = new CustomFormManageData();
        $channelId = $customFormManageData->GetChannelId($customFormId, FALSE);
        $channelManageData = new ChannelManageData();
        $siteId = $channelManageData->GetSiteId($channelId,1);
        $numberOfSearchKey = Control::GetRequest("number_of_search_key", 0);
        ///////////////判断是否有操作权限///////////////////
        $manageUserAuthority = new ManageUserAuthorityManageData();
        $can = $manageUserAuthority->CanChannelExplore($siteId, $channelId, $manageUserId);





        if ($can != 1) {
            Control::ShowMessage(Language::Load('custom_form', 3));
            return "";
        }
        ////////////////////////////////////////////////////


        $tempContent = Template::Load("custom_form/custom_form_record_list.html", "common");


        if ($customFormId > 0) {

            $customFormFieldManageData = new CustomFormFieldManageData();
            $listOfFieldArray = $customFormFieldManageData->GetListForContent($customFormId);
            $customFormRecordManageData = new CustomFormRecordManageData();


            $pageSize = Control::GetRequest("ps", 20);
            $allCount = 0;

            $beginTime=Control::PostOrGetRequest("begin_time","");
            $endTime=Control::PostOrGetRequest("end_time","");
            $listOfRecordArray = $customFormRecordManageData->GetRandomList($customFormId, $pageSize,$beginTime,$endTime);

            $listTable="";
            $fieldSelectionForSearch="";
            if (count($listOfRecordArray) > 0) {
                $listTable = self::GetCustomFormRecordListTable($listOfFieldArray,$listOfRecordArray);

                //批量更改查询结果的状态
                $state=1;
                $strCustomFormRecordId="";
                for($i=0;$i<count($listOfRecordArray);$i++){
                    $strCustomFormRecordId.=",".$listOfRecordArray[$i]["CustomFormRecordId"];
                }
                $strCustomFormRecordId=substr($strCustomFormRecordId,1);
                $customFormRecordManageData->BatchModifyState($strCustomFormRecordId,$state);
            }else{
                $tempContent = str_ireplace("{PagerButton}", Language::Load("custom_form", 4), $tempContent);
            }
            $replace_arr = array(
                "{CustomFormId}" => $customFormId,
                "{ListTable}" => $listTable,
                "{PagerButton}" => "",
                "{CustomFormFieldCount}"=> count($listOfFieldArray),
                "{FieldSelection}"=>$fieldSelectionForSearch
            );
            $tempContent = strtr($tempContent, $replace_arr);
        }else{
            return DefineCode::CUSTOM_FORM_RECORD_MANAGE+self::FALSE_CUSTOM_FORM_ID;
        }


        parent::ReplaceEnd($tempContent);
        $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
        //Template::RemoveCMS($tempContent);
        return $tempContent;
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
        $arrayOfFieldList = $customFormFieldManageData->GetList($customFormId);

        for ($f = 0; $f < count($arrayOfFieldList); $f++) {
            $customFormFieldId = intval($arrayOfFieldList[$f]["CustomFormFieldId"]);
            $customFormFieldType = intval($arrayOfFieldList[$f]["CustomFormFieldType"]);
            $inputValue = "";
            $customFormContentId = -1;
            if (!empty($arrContentList)) {
                for ($k = 0; $k < count($arrContentList); $k++) {
                    if ($arrContentList[$k]["CustomFormFieldId"] == $customFormFieldId) {
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
                                $customFormContentId = $arrContentList[$k]["CustomFormContentId"];
                                break;
                        }
                    }
                }
            }
            $inputName = 'cf_' . $customFormId . '_' . $arrayOfFieldList[$f]["CustomFormFieldId"];

            $inputText = '';
            switch ($customFormFieldType) {
                case 0: //int
                    $addClass = 'class="input_number"';
                    $addStyle = 'style=" width: 60px;"';
                    $inputText = '<input name="' . $inputName . '" id="' . $inputName . '" value="' . $inputValue . '" type="text" ' . $addClass . ' ' . $addStyle . ' />';
                    break;
                case 1: //string
                    $addClass = 'class="input_box"';
                    $addStyle = 'style=" width: 300px;"';
                    $inputText = '<input name="' . $inputName . '" id="' . $inputName . '" value="' . $inputValue . '" type="text" ' . $addClass . ' ' . $addStyle . ' />';
                    break;
                case 2: //text
                    $addClass = 'class="input_box"';
                    $addStyle = 'style=" width: 300px;height:100px;"';
                    $inputText = '<textarea name="' . $inputName . '" id="' . $inputName . '" ' . $addClass . ' ' . $addStyle . ' >' . $inputValue . '</textarea>';
                    break;
                case 3: //float
                    $addClass = 'class="input_price"';
                    $addStyle = 'style=" width: 60px;"';
                    $inputText = '<input name="' . $inputName . '" id="' . $inputName . '" value="' . $inputValue . '" type="text" ' . $addClass . ' ' . $addStyle . ' />';
                    break;
                case 4: //date
                    $addClass = 'class="input_box"';
                    $addStyle = 'style=" width: 100px;"';
                    $inputText = '<input name="' . $inputName . '" id="' . $inputName . '" value="' . $inputValue . '" type="text" ' . $addClass . ' ' . $addStyle . ' />';
                    break;
                case 5: //blob
                    $addClass = 'class="input_box"';
                    $addStyle = 'style=" width: 200px;margin-right:30px"';
                    $inputText = '<input name="' . $inputName . '" id="' . $inputName . '" value="' . $inputValue . '" type="file" ' . $addClass . ' ' . $addStyle . ' />';
                    $inputText.='<span id="'.$customFormContentId.'" class="btn_download_attachment" style="cursor:pointer">';
                    $inputText.='<a href="/default.php?secu=manage&mod=custom_form_content&m=get_attachment&custom_form_content_id='.$customFormContentId.'" target="_blank">[下载]</a>';
                    $inputText.='</span>';
                    $inputText.='<span id="'.$customFormContentId.'" class="btn_delete_attachment" style="cursor:pointer">[删除]</span>';
                    break;
            }
            $customFormContentTable .= '<tr>';
            $customFormContentTable .= '<td class="spe_line" height="30" align="right">' . $arrayOfFieldList[$f]["CustomFormFieldName"] . '：</td>';
            $customFormContentTable .= '<td class="spe_line">' . $inputText . '</td>';
            $customFormContentTable .= '</tr>';
        }

        return $customFormContentTable;
    }

    /**
     * 取得表单记录列表
     * @return string 表单列表页面html
     */
    private function GenList() {
        $manageUserId = Control::GetManageUserId();
        $customFormId = Control::GetRequest("custom_form_id", 0);
        $resultJavaScript="";
        $customFormManageData = new CustomFormManageData();
        $channelId = $customFormManageData->GetChannelId($customFormId, FALSE);
        $channelManageData = new ChannelManageData();
        $siteId = $channelManageData->GetSiteId($channelId,1);
        $numberOfSearchKey = Control::GetRequest("number_of_search_key", 0);
        ///////////////判断是否有操作权限///////////////////
        $manageUserAuthority = new ManageUserAuthorityManageData();
        $can = $manageUserAuthority->CanChannelExplore($siteId, $channelId, $manageUserId);
        if ($can != 1) {
            Control::ShowMessage(Language::Load('custom_form', 3));
            return "";
        }
        ////////////////////////////////////////////////////


        $tempContent = Template::Load("custom_form/custom_form_record_list.html", "common");


        if ($customFormId > 0) {

            $customFormFieldManageData = new CustomFormFieldManageData();
            $listOfFieldArray = $customFormFieldManageData->GetListForContent($customFormId);
            $customFormRecordManageData = new CustomFormRecordManageData();


            $pageSize = Control::GetRequest("ps", 20);

            $pageIndex = Control::GetRequest("p", 1);
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $allCount = 0;

            ////////// 处理搜索 ////////////
            if($numberOfSearchKey>0){
                $searchArray=array();
                for($i=1;$i<=$numberOfSearchKey;$i++){
                    $searchKeyContent=Control::GetRequest("content_".$i, "");
                    $searchKeyField=Control::GetRequest("field_".$i, 0);
                    $arr = Format::ToSplit($searchKeyField, '_');  //filedId_fieldType
                    if($searchKeyContent!=""){
                        array_push($searchArray,array("type"=>$arr[1],"content"=>$searchKeyContent,"field"=>$arr[0]));
                    }
                }
                $listOfRecordArray = $customFormRecordManageData->GetListPagerOfContentSearch($customFormId,$pageBegin,$pageSize,$allCount,$searchArray);
            }else{
                $listOfRecordArray = $customFormRecordManageData->GetListPager($customFormId, $pageBegin, $pageSize, $allCount);
            }


            $styleNumber = 1;
            $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html", "common");
            $isJs = FALSE;
            $navUrl = "/default.php?secu=manage&mod=custom_form_record&m=list&custom_form_id=$customFormId&p={0}&ps=$pageSize";
            $jsFunctionName = "";
            $jsParamList = "";
            $pagerButton = Pager::ShowPageButton($pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex ,$styleNumber = 1, $isJs, $jsFunctionName, $jsParamList);
            //$pagerButton = Pager::ShowPageButton($tempContent, "", $allCount, $pageSize, $pageIndex ,$styleNumber = 1, $isJs = false, $jsFunctionName = "" , $jsParamList = "");

            $listTable="";
            $fieldSelectionForSearch="";
            if (count($listOfRecordArray) > 0) {
                $listTable = self::GetCustomFormRecordListTable($listOfFieldArray,$listOfRecordArray);

                /**
                 临时导出csv
                 **/
                $isCsv=Control::GetRequest("csv_file","");
                if($isCsv=="1"){
                    $listTable = self::GetCustomFormRecordListCSV($listOfFieldArray,$listOfRecordArray);
                    return $listTable;
                }



                /**
                临时导出csv end
                 **/

                ////搜索字段选择框////
                foreach($listOfFieldArray as $value){
                    $fieldSelectionForSearch.='<option value = "'.$value["CustomFormFieldId"].'_'.$value["CustomFormFieldType"].'" >'.$value["CustomFormFieldName"].'</option>';
                }
            }else{
                $tempContent = str_ireplace("{PagerButton}", Language::Load("custom_form", 4), $tempContent);
            }
            $replace_arr = array(
                "{CustomFormId}" => $customFormId,
                "{ListTable}" => $listTable,
                "{PagerButton}" => $pagerButton,
                "{CustomFormFieldCount}"=> count($listOfFieldArray),
                "{FieldSelection}"=>$fieldSelectionForSearch
            );
            $tempContent = strtr($tempContent, $replace_arr);
        }else{
            return DefineCode::CUSTOM_FORM_RECORD_MANAGE+self::FALSE_CUSTOM_FORM_ID;
        }


        parent::ReplaceEnd($tempContent);
        $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
        //Template::RemoveCMS($tempContent);
        return $tempContent;
    }


    /**
     * 取得表单记录列表的html表格代码
     * @param array $listOfFieldArray 表单字段ID数据
     * @param array $listOfRecordArray 表单记录ID数据
     * @return string 表单列表页面html
     */
    private function GetCustomFormRecordListTable($listOfFieldArray,$listOfRecordArray){

        $listTable = '<table width="100%" class="doc_grid" cellpadding="0" cellspacing="0">';
        $listTable .= '<tr class="grid_title">';
        $listTable .= '<td style="padding-left:2px;"></td>';
        for ($f = 0; $f < count($listOfFieldArray); $f++) {
            $listTable .= '<td style="padding-left:2px;">' . $listOfFieldArray[$f]["CustomFormFieldName"] . '</td>';
        }
        $listTable .= '<td style="padding-left:2px;width:40px;">状态</td>';
        $listTable .= '<td style="padding-left:2px;width:180px;">时间</td>';
        $listTable .= '</tr>';
        for ($i = 0; $i < count($listOfRecordArray); $i++) {
            $customFormRecordId = intval($listOfRecordArray[$i]["CustomFormRecordId"]);

            $listTable .= '<tr class="grid_item">';
            $listTable .= '<td class="spe_line" style="padding-left:2px;"><img idvalue="'. $customFormRecordId.'" class="btn_edit_custom_form_record" src="/system_template/default/images/manage/edit.gif" alt="编辑" title="'.$customFormRecordId.'" /></td>';

            $customFormContentManageData=new CustomFormContentManageData();
            $listOfContentArray = $customFormContentManageData->GetList($customFormRecordId);

            for ($j = 0; $j < count($listOfFieldArray); $j++) {
                $customFormFieldId = $listOfFieldArray[$j]["CustomFormFieldId"];
                $customFormFieldType = $listOfFieldArray[$j]["CustomFormFieldType"];
                $listTable .= '<td class="spe_line" style="padding-left:2px;">';
                for ($k = 0; $k < count($listOfContentArray); $k++) {

                    if ($listOfContentArray[$k]["CustomFormFieldId"] == $customFormFieldId) {
                        switch (intval($customFormFieldType)) {
                            case 0:
                                $listTable .= $listOfContentArray[$k]["ContentOfInt"];
                                break;
                            case 1:
                                $listTable .= $listOfContentArray[$k]["ContentOfString"];
                                break;
                            case 2:
                                $listTable .= $listOfContentArray[$k]["ContentOfText"];
                                break;
                            case 3:
                                $listTable .= $listOfContentArray[$k]["ContentOfFloat"];
                                break;
                            case 4:
                                $listTable .= $listOfContentArray[$k]["ContentOfDatetime"];
                                break;
                            case 5:
                                $listTable.='<a href="/default.php?secu=manage&mod=custom_form_content&m=get_attachment&custom_form_content_id='.$listOfContentArray[$k]["CustomFormContentId"].'" target="_blank">[下载]</a>';
                                $listTable.='</span>';
                                $listTable.='<span id="'.$listOfContentArray[$k]["CustomFormContentId"].'" class="btn_delete_attachment" style="cursor:pointer">[删除]</span>';
                                //$listTable .= $listOfContentArray[$k]["ContentOfBlob"];
                                break;
                        }
                    }
                }
                $listTable .= '</td>';
            }
            $listTable .= '<td class="spe_line2" style="padding-left:2px;">' . $listOfRecordArray[$i]["State"] . '</td>';
            $listTable .= '<td class="spe_line2" style="padding-left:2px;">' . $listOfRecordArray[$i]["CreateDate"] . '</td>';
            $listTable .= '</tr>';
        }
        $listTable .= '</tabel>';
        return $listTable;
    }



    /**
     * 取得表单记录列表的html表格代码
     * @param array $listOfFieldArray 表单字段ID数据
     * @param array $listOfRecordArray 表单记录ID数据
     * @return string 表单列表页面html
     */
    private function GetCustomFormRecordListCSV($listOfFieldArray,$listOfRecordArray){

        $listTable = '';
        for ($f = 0; $f < count($listOfFieldArray); $f++) {
            if($f>0){
                $listTable.=';';
            }
            $listTable .= $listOfFieldArray[$f]["CustomFormFieldName"] . '';
        }
        $listTable .= ';状态';
        $listTable .= ';时间';
        $listTable .= "\n";
        for ($i = 0; $i < count($listOfRecordArray); $i++) {
            $customFormRecordId = intval($listOfRecordArray[$i]["CustomFormRecordId"]);


            $customFormContentManageData=new CustomFormContentManageData();
            $listOfContentArray = $customFormContentManageData->GetList($customFormRecordId);

            for ($j = 0; $j < count($listOfFieldArray); $j++) {
                $customFormFieldId = $listOfFieldArray[$j]["CustomFormFieldId"];
                $customFormFieldType = $listOfFieldArray[$j]["CustomFormFieldType"];
                if($j>0){
                    $listTable .= ';';
                }
                for ($k = 0; $k < count($listOfContentArray); $k++) {

                    if ($listOfContentArray[$k]["CustomFormFieldId"] == $customFormFieldId) {
                        switch (intval($customFormFieldType)) {
                            case 0:
                                $listTable .= $listOfContentArray[$k]["ContentOfInt"];
                                break;
                            case 1:
                                $contentOfString=str_ireplace(";", '；', $listOfContentArray[$k]["ContentOfString"]);
                                $contentOfString='"'.$contentOfString.'"';
                                $listTable .= $contentOfString;
                                break;
                            case 2:
                                $contentOfText=str_ireplace(";", '；',$listOfContentArray[$k]["ContentOfText"]);
                                $contentOfText='"'.$contentOfText.'"';
                                $listTable .= $contentOfText;
                                break;
                            case 3:
                                $listTable .= $listOfContentArray[$k]["ContentOfFloat"];
                                break;
                            case 4:
                                $listTable .= $listOfContentArray[$k]["ContentOfDatetime"];
                                break;
                            case 5:
                                //$listTable.='<a href="/default.php?secu=manage&mod=custom_form_content&m=get_attachment&custom_form_content_id='.$listOfContentArray[$k]["CustomFormContentId"].'" target="_blank">[下载]</a>';
                                //$listTable.='</span>';
                                //$listTable.='<span id="'.$listOfContentArray[$k]["CustomFormContentId"].'" class="btn_delete_attachment" style="cursor:pointer">[删除]</span>';
                                //$listTable .= $listOfContentArray[$k]["ContentOfBlob"];
                                break;
                        }
                    }
                }
            }
            $listTable .= ';' . $listOfRecordArray[$i]["State"] . '';
            $listTable .= ';' . $listOfRecordArray[$i]["CreateDate"] . '';
            $listTable .= "\n";
        }
        $listTable .= '';


        $filename = date('Ymd').'.csv'; //设置文件名
        header("Content-type:text/csv");
        header("Content-Disposition:attachment;filename=".$filename);
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        echo $listTable;

        //return $listTable;
    }

    /**
     * 通过表单字段关键字搜索并取得表单记录列表
     * @return string 表单列表页面html
     */
    private function GenSearch() {
        $customFormRecordManageData=new CustomFormRecordManageData();
       // $array=array(array("type"=>1, "content"=>"姓名或单位名称"));
        //print_r($array);
       // $customFormRecordManageData->GetListPagerOfContentSearch(2,0,20,$allCount,$array);

    }
}

?>