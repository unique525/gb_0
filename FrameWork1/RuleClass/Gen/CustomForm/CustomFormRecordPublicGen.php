<?php

/**
 * 前台活动表单记录生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_CustomForm
 * @author 525
 */
class CustomFormRecordPublicGen extends BasePublicGen implements IBasePublicGen {


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
    function GenPublic() {
        $result = "";
        $method = Control::GetRequest("m", "");
        switch ($method) {
            case "create":
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


    /**
     * 前新增一条活动表单记录
     * @return string 新增表单记录字段内容表的html页面
     */
private function GenCreate(){
    $userId = 0;//游客

    if (!empty($_POST)) {
        $customFormRecordPublicData = new CustomFormRecordPublicData();
        $newId = $customFormRecordPublicData->Create($_POST);
        if ($newId > 0) {

            //新增内容表
            $customFormContentPublicData = new CustomFormContentPublicData();
            $customFormFieldPublicData = new CustomFormFieldPublicData();
            //先删除旧数据
            $customFormContentPublicData->Delete($newId);
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

                        $customFormFieldType = $customFormFieldPublicData->GetCustomFormFieldType($customFormFieldId,FALSE);
                        $contentId=$customFormContentPublicData->Create($newId, $customFormId, $customFormFieldId, $userId, $value, $customFormFieldType);

                        if($contentId<0){
                            return DefineCode::CUSTOM_FORM_RECORD_PUBLIC+self::DATABASE_CREATE_FAILED_WHEN_CONTENT_CREATING."field_id:".$customFormFieldId;
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
                            $fileContentId=$customFormContentPublicData->CreateAttachment(
                                $newId,
                                $fileCustomFormId,
                                $fileCustomFormFieldId,
                                $userId,
                                $fileData,
                                $fileName,
                                $fileType
                            );
                            if($fileContentId<0){
                                return DefineCode::CUSTOM_FORM_RECORD_PUBLIC+self::DATABASE_MODIFY_FAILED_WHEN_CONTENT_CREATING."field_id:".$fileCustomFormFieldId;
                            }
                        }
                    }
                }
            }



            Control::ShowMessage(Language::Load('custom_form', 1));
        }else{
            Control::ShowMessage(Language::Load('custom_form', 2));
            //return DefineCode::CUSTOM_FORM_RECORD_PUBLIC+self::DATABASE_INPUT_FAILED;
        }


    }

    
}



}

?>