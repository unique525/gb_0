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
    function GenPublic() {
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



    public function GenCreate() {
        if (!empty($_POST)) {
            $customFormRecordData = new CustomFormRecordPublicData();
            $newRecordId = $customFormRecordData->Create($_POST);

            if ($newRecordId > 0) {
                //新增内容表
                $customFormContentData = new CustomFormContentPublicData();
                $CustomFormRecordID = $newRecordId;
                if (!empty($_POST)) {
                    $_userId = Control::GetUserID();
                    //读取表单 cf_customFormId_customFormFieldId
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



}

?>