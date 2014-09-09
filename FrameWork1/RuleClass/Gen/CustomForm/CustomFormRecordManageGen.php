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
        $manageUserId = Control::GetManageUserID();
        $customFormId = Control::GetRequest("custom_form_id", 0);
        $customFormData = new CustomFormManageData();
        $channelId = $customFormData->GetChannelID($customFormId, FALSE);
        parent::ReplaceFirst($tempContent);


        if (!empty($_POST)) {
            $customFormRecordManageData = new CustomFormRecordManageData();
            $newId = $customFormRecordManageData->Create($_POST);

            if ($newId > 0) {

                //新增内容表
                $customFormContentManageData = new CustomFormContentManageData();
                $customFormFieldManageData = new CustomFormFieldManageData();
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
                                if($contentId<0){
                                    return DefineCode::CUSTOM_FORM_RECORD_MANAGE+self::DATABASE_CREATE_FAILED_WHEN_CONTENT_CREATING."field_id:".$customFormFieldId;
                                }
                            }
                        }
                    }

                //加入操作log

                $operateContent = "CustomFormRecord：CustomFormRecordD：" . $newId . "；result：" . $newId;
                self::CreateManageUserLog($operateContent);


                Control::ShowMessage(Language::Load('custom_form', 1));
                $closeTab = Control::PostRequest("CloseTab",0);
                if($closeTab == 1){
                    Control::CloseTab();
                }else{
                    Control::GoUrl($_SERVER["PHP_SELF"].'?secu=manage&mod=custom_form_record&m=create&custom_form_id='.$customFormId);
                }

            }else{
                Control::ShowMessage(Language::Load('custom_form', 2));
                return DefineCode::CUSTOM_FORM_RECORD_MANAGE+self::DATABASE_INPUT_FAILED;
            }


        }




        if ($customFormId > 0) {
            $channelData = new ChannelManageData();
            $siteId = $channelData->GetSiteID($channelId, 0);

            ///////////////判断是否有操作权限///////////////////
            $manageUserAuthority = new ManageUserAuthorityManageData();
            $can = $manageUserAuthority->CanCreate($siteId, $channelId, $manageUserId);
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
        $manageUserId = Control::GetManageUserID();
        $customFormRecordId = Control::GetRequest("custom_form_record_id", 0);
        $customFormId = Control::GetRequest("custom_form_id", 0);
        $customFormManageData = new CustomFormManageData();
        $channelId = $customFormManageData->GetChannelID($customFormId, FALSE);
        parent::ReplaceFirst($tempContent);

        if ($customFormRecordId > 0) {
            $channelManageData = new ChannelManageData();
            $siteId = $channelManageData->GetSiteID($channelId, FALSE);

            ///////////////判断是否有操作权限///////////////////

            $manageUserAuthority = new ManageUserAuthorityManageData();
            $can = $manageUserAuthority->CanModify($siteId, $channelId, $manageUserId);
            if ($can != 1) {
                Control::ShowMessage(Language::Load('custom_form', 3));
                return "";
            }
            //操作他人的权限

            $createUserId = $customFormManageData->GetManageUserID($customFormId, FALSE);
            if ($createUserId !== $manageUserId) { //操作人不是发布人
                $can = $manageUserAuthority->CanDoOthers($siteId, $channelId, $manageUserId);
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
                                if($contentId<0){
                                    return DefineCode::CUSTOM_FORM_RECORD_MANAGE+self::DATABASE_MODIFY_FAILED_WHEN_CONTENT_CREATING."field_id:".$customFormFieldId;
                                }
                            }
                        }
                    }

                    //加入操作log

                    $operateContent = "CustomFormRecord：CustomFormRecordD：" . $customFormRecordId . "；result：" . $customFormRecordId;
                    self::CreateManageUserLog($operateContent);


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

                //加入操作log
                $operateContent = "CustomFormRecord：CustomFormRecordD：" . $customFormRecordId . "；result：" . $customFormRecordId;
                self::CreateManageUserLog($operateContent);

                Control::GoUrl($_SERVER["PHP_SELF"].'?secu=manage&mod=custom_form_record&m=list&custom_form_id='.$customFormId);
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
                                $inputValue = $arrContentList[$k]["ContentOfBlob"];
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
        $manageUserId = Control::GetManageUserID();
        $customFormId = Control::GetRequest("custom_form_id", 0);
        $resultJavaScript="";
        $customFormManageData = new CustomFormManageData();
        $channelId = $customFormManageData->GetChannelID($customFormId, FALSE);
        $channelManageData = new ChannelManageData();
        $siteId = $channelManageData->GetSiteID($channelId,1);
        $numberOfSearchKey = Control::GetRequest("number_of_search_key", 0);
        ///////////////判断是否有操作权限///////////////////
        $manageUserAuthority = new ManageUserAuthorityManageData();
        $can = $manageUserAuthority->CanExplore($siteId, $channelId, $manageUserId);
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
                $searchKeyContent=Control::GetRequest("content_1", "");
                $searchKeyField=Control::GetRequest("field_1", 0);
                $searchArray=array(array("type"=>1,"content"=>$searchKeyContent,"field"=>$searchKeyField));
                for($i=2;$i<=$numberOfSearchKey;$i++){
                    $searchKeyContent=Control::GetRequest("content_".$i, "");
                    $searchKeyField=Control::GetRequest("field_".$i, 0);
                    if($searchKeyContent!=""){
                        array_push($searchArray,array("type"=>1,"content"=>$searchKeyContent,"field"=>$searchKeyField));
                    }
                }
                $listOfRecordArray = $customFormRecordManageData->GetListPagerOfContentSearch($customFormId,$pageBegin,$pageSize,$allCount,$searchArray);
            }else{
                $listOfRecordArray = $customFormRecordManageData->GetListPager($customFormId, $pageBegin, $pageSize, $allCount);
            }


            $pagerButton = Pager::ShowPageButton($tempContent, "", $allCount, $pageSize, $pageIndex ,$styleNumber = 1, $isJs = false, $jsFunctionName = "" , $jsParamList = "");

            $listTable="";
            $fieldSelectionForSearch="";
            if (count($listOfRecordArray) > 0) {
                $listTable = self::GetCustomFormRecordListTable($listOfFieldArray,$listOfRecordArray);
                ////搜索字段选择框////
                foreach($listOfFieldArray as $value){
                    $fieldSelectionForSearch.='<option value = "'.$value["CustomFormFieldId"].'" >'.$value["CustomFormFieldName"].'</option>';
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

        $listTable = '<table width="99%" class="doc_grid" cellpadding="0" cellspacing="0">';
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
            $listTable .= '<td class="spe_line" style="padding-left:2px;"><img idvalue="'. $customFormRecordId.'" class="btn_edit_custom_form_record" src="/system_template/default/images/manage/edit.gif" alt="编辑" title="编辑" /></td>';

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
                                $listTable .= $listOfContentArray[$k]["ContentOfBlob"];
                                break;
                        }
                    }
                }
                $listTable .= '</td>';
            }
            $listTable .= '<td class="spe_line" style="padding-left:2px;">' . $listOfRecordArray[$i]["State"] . '</td>';
            $listTable .= '<td class="spe_line" style="padding-left:2px;">' . $listOfRecordArray[$i]["CreateDate"] . '</td>';
            $listTable .= '</tr>';
        }
        $listTable .= '</tabel>';
        return $listTable;
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