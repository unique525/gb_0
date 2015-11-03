<?php

/**
 * 前台活动表单记录生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_CustomForm
 * @author 525
 */
class CustomFormRecordPublicGen extends BasePublicGen implements IBasePublicGen
{


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
     *  唯一内容的字段已存在此内容
     */
    const REPEAT_CONTENT_IN_UNIQUE_FIELD = -8;
    /**
     *  随机字段的字段名重复
     */
    const REPEAT_FIELD_NAME = -9;
    /**
     *  找不到随机字段
     */
    const CANNOT_FIND_FIELD_FOR_RANDOM = -10;
    /**
     *  字段类型错误（随机数字段只允许int型）
     */
    const FALSE_CUSTOM_FORM_FIELD_TYPE = -11;


    /**
     * 主生成方法(继承接口)
     * @return string 返回输出的HTML内容
     */
    function GenPublic()
    {
        $result = "";
        $method = Control::GetRequest("m", "");
        switch ($method) {
            case "create":
                $result = self::Create();
                break;
            case "async_create":
                $result = self::AsyncCreate();
                break;
            case "async_get_count":
                $result = self::AsyncGetCount();
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
     * 取得表单计数
     */
    private function AsyncGetCount()
    {
        $result = 0;
        $customFormId = Control::PostOrGetRequest("f_custom_form_id", "0");
        if ($customFormId > 0) {
            $customFormRecordPublicData = new CustomFormRecordPublicData();
            $result = $customFormRecordPublicData->GetCount($customFormId);
        }
        return Control::GetRequest("jsonpcallback", "") . '(' . $result . ')';
    }

    /**
     * 前新增一条活动表单记录(非异步，用于上传文件)
     * @return string 新增表单记录字段内容表的html页面
     */
    private function Create()
    {
        $userId = 0; //游客


        $customFormId = Control::PostOrGetRequest("f_CustomFormId", "0");
        $customFromPublicData = new CustomFormPublicData();
        $customFormRecordPublicData = new CustomFormRecordPublicData();
        $customFormFieldPublicData = new CustomFormFieldPublicData();
        $customFormContentPublicData = new CustomFormContentPublicData();

        $newId = 0;
        $state = $customFromPublicData->GetState($customFormId, true);
        if ($state == 100) { //100:停用
            return Control::GetRequest("jsonpcallback", "") . '(' . "活动不可用" . ')';
        }
        if ($customFormId > 0) {
            //先检查字段中是否有设置唯一的字段， 该字段是否有重复
            $arrayUnique = $customFormFieldPublicData->GetUniqueField($customFormId);
            $isRepeat = 0;
            foreach ($arrayUnique as $uniqueField) {
                $uniqueContent = Control::GetRequest("cf_" . $customFormId . "_" . $uniqueField["CustomFormFieldId"], "");
                $repeat = $customFormContentPublicData->CheckRepeat($customFormId, $uniqueField["CustomFormFieldId"], $uniqueField["CustomFormFieldType"], $uniqueContent);

                if ($repeat > 0) {
                    $isRepeat = 1;
                }
            }
            if ($isRepeat == 0) {
                $createDate = date("Y-m-d H:i:s", time());
                $newId = $customFormRecordPublicData->Create($customFormId, $userId, $createDate);
            } else {
                $result = "提交失败" . (DefineCode::CUSTOM_FORM_RECORD_PUBLIC + self::REPEAT_CONTENT_IN_UNIQUE_FIELD);
                return Control::GetRequest("jsonpcallback", "") . '(' . $result . ')';
            }

        }

        if ($newId > 0) {

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

                        $customFormFieldType = $customFormFieldPublicData->GetCustomFormFieldType($customFormFieldId, FALSE);
                        $contentId = $customFormContentPublicData->Create($newId, $customFormId, $customFormFieldId, $userId, $value, $customFormFieldType);

                        if ($contentId < 0) {
                            $result = "报名失败:" . (DefineCode::CUSTOM_FORM_RECORD_PUBLIC + self::DATABASE_CREATE_FAILED_WHEN_CONTENT_CREATING); //."field_id:".$customFormFieldId;
                            return Control::GetRequest("jsonpcallback", "") . '(' . $result . ')';
                        }
                    }
                }
            }

            //附件操作
            if (!empty($_FILES)) {
                foreach ($_FILES as $key => $value) {
                    if (!empty($_FILES[$key]["tmp_name"])) {
                        $arr = Format::ToSplit($key, '_');
                        if (count($arr) == 3) {
                            $fileCustomFormId = $arr[1];
                            $fileCustomFormFieldId = $arr[2];
                            $fileType = $_FILES[$key]["type"];
                            $fileName = "Attachment_" . $fileCustomFormFieldId . "_" . pathinfo($_FILES[$key]["name"], PATHINFO_EXTENSION);
                            $fileData = file_get_contents($_FILES[$key]["tmp_name"]);
                            $fileContentId = $customFormContentPublicData->CreateAttachment(
                                $newId,
                                $fileCustomFormId,
                                $fileCustomFormFieldId,
                                $userId,
                                $fileData,
                                $fileName,
                                $fileType
                            );
                            if ($fileContentId < 0) {
                                $result = "附件提交失败:" . (DefineCode::CUSTOM_FORM_RECORD_PUBLIC + self::DATABASE_MODIFY_FAILED_WHEN_CONTENT_CREATING); //."field_id:".$fileCustomFormFieldId;

                                return Control::GetRequest("jsonpcallback", "") . '(' . $result . ')';
                            }
                        }
                    }
                }
            }


            /**随机数字段操作  （抽奖结果，保留查询码等功能）**/
            $randomFieldName = Control::GetRequest("random_field_name", ""); //获取对应custom form field的name
            if ($randomFieldName != "") { //如果request内请求了随机字段
                $arrayRandomField = $customFormFieldPublicData->GetListByName($customFormId, $randomFieldName);
                if (count($arrayRandomField) <= 0 || $arrayRandomField == null) {
                    $result = DefineCode::CUSTOM_FORM_RECORD_PUBLIC + self::CANNOT_FIND_FIELD_FOR_RANDOM; //后台没有设置随机字段，字段找不到
                    return Control::GetRequest("jsonpcallback", "") . '(' . $result . ')';
                }
                if (count($arrayRandomField) > 1) {
                    $result = DefineCode::CUSTOM_FORM_RECORD_PUBLIC + self::REPEAT_FIELD_NAME; //字段有重复
                    return Control::GetRequest("jsonpcallback", "") . '(' . $result . ')';
                }
                if ($arrayRandomField[0]["CustomFormFieldType"] != 0) {
                    $result = DefineCode::CUSTOM_FORM_RECORD_PUBLIC + self::FALSE_CUSTOM_FORM_FIELD_TYPE; //字段类型错误（随机数字段只允许int型）
                    return Control::GetRequest("jsonpcallback", "") . '(' . $result . ')';
                }
                $randomResult = self::GetRandomResult($customFormId, $arrayRandomField[0]["CustomFormFieldId"]);
                $customFormContentPublicData->DeleteOneContent($newId, $arrayRandomField[0]["CustomFormFieldId"]); //删除旧数据（如果有）
                $contentId = $customFormContentPublicData->Create($newId, $customFormId, $arrayRandomField[0]["CustomFormFieldId"], $userId, $randomResult, $arrayRandomField[0]["CustomFormFieldType"]);

                $randomResult += 100; //以防跟下面的”成功=1“混淆
                //前台获取:
                //result>100：对应奖项 result-100
                //result=100：不获奖
                //result=200：奖项剩余0
                return Control::GetRequest("jsonpcallback", "") . '(' . $randomResult . ')';


            }

            $result = "提交成功！"; //成功
            return Control::GetRequest("jsonpcallback", "") . '(' . $result . ')';
        } else {
            $result = "提交失败:" . (DefineCode::CUSTOM_FORM_RECORD_PUBLIC + self::DATABASE_INPUT_FAILED); //失败
            return Control::ShowMessage("jsonpcallback", "") . '(' . $result . ')';
            //return DefineCode::CUSTOM_FORM_RECORD_PUBLIC+self::DATABASE_INPUT_FAILED;
        }


    }


    /**
     * 前新增一条活动表单记录
     * @return string 新增表单记录字段内容表的html页面
     */
    private function AsyncCreate()
    {
        $userId = Control::GetUserId();


        $customFormId = Control::PostOrGetRequest("f_CustomFormId", "0");
        $customFromPublicData = new CustomFormPublicData();
        $customFormRecordPublicData = new CustomFormRecordPublicData();
        $customFormFieldPublicData = new CustomFormFieldPublicData();
        $customFormContentPublicData = new CustomFormContentPublicData();

        $newId = 0;
        $state = $customFromPublicData->GetState($customFormId, true);
        if ($state == 100) { //100:停用
            return Control::GetRequest("jsonpcallback", "") . '(' . "-1" . ')';
        }

        if ($customFormId > 0) {
            //先检查字段中是否有设置唯一的字段， 该字段是否有重复
            $arrayUnique = $customFormFieldPublicData->GetUniqueField($customFormId);
            $isRepeat = 0;
            foreach ($arrayUnique as $uniqueField) {
                $uniqueContent = Control::PostOrGetRequest("cf_" . $customFormId . "_" . $uniqueField["CustomFormFieldId"], "");
                $repeat = $customFormContentPublicData->CheckRepeat($customFormId, $uniqueField["CustomFormFieldId"], $uniqueField["CustomFormFieldType"], $uniqueContent);

                if ($repeat > 0) {
                    $isRepeat = 1;
                }
            }
            if ($isRepeat == 0) {
                $createDate = date("Y-m-d H:i:s", time());
                $newId = $customFormRecordPublicData->Create($customFormId, $userId, $createDate);
            } else {
                $result = DefineCode::CUSTOM_FORM_RECORD_PUBLIC + self::REPEAT_CONTENT_IN_UNIQUE_FIELD;
                return Control::GetRequest("jsonpcallback", "") . '(' . $result . ')';
            }

        }
        if ($newId > 0) {

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

                        $customFormFieldType = $customFormFieldPublicData->GetCustomFormFieldType($customFormFieldId, FALSE);
                        $contentId = $customFormContentPublicData->Create($newId, $customFormId, $customFormFieldId, $userId, $value, $customFormFieldType);

                        if ($contentId < 0) {
                            $result = DefineCode::CUSTOM_FORM_RECORD_PUBLIC + self::DATABASE_CREATE_FAILED_WHEN_CONTENT_CREATING; //."field_id:".$customFormFieldId;
                            return Control::GetRequest("jsonpcallback", "") . '(' . $result . ')';
                        }
                    }
                }
            }

            //附件操作
            if (!empty($_FILES)) {
                foreach ($_FILES as $key => $value) {
                    if (!empty($_FILES[$key]["tmp_name"])) {
                        $arr = Format::ToSplit($key, '_');
                        if (count($arr) == 3) {
                            $fileCustomFormId = $arr[1];
                            $fileCustomFormFieldId = $arr[2];
                            $fileType = $_FILES[$key]["type"];
                            $fileName = "Attachment_" . $fileCustomFormFieldId . "_" . pathinfo($_FILES[$key]["name"], PATHINFO_EXTENSION);
                            $fileData = file_get_contents($_FILES[$key]["tmp_name"]);
                            $fileContentId = $customFormContentPublicData->CreateAttachment(
                                $newId,
                                $fileCustomFormId,
                                $fileCustomFormFieldId,
                                $userId,
                                $fileData,
                                $fileName,
                                $fileType
                            );
                            if ($fileContentId < 0) {
                                $result = DefineCode::CUSTOM_FORM_RECORD_PUBLIC + self::DATABASE_MODIFY_FAILED_WHEN_CONTENT_CREATING; //."field_id:".$fileCustomFormFieldId;

                                return Control::GetRequest("jsonpcallback", "") . '(' . $result . ')';
                            }
                        }
                    }
                }
            }


            /**随机数字段操作  （抽奖结果，保留查询码等功能）**/
            $randomFieldName = Control::GetRequest("random_field_name", ""); //获取对应custom form field的name
            if ($randomFieldName != "") { //如果request内请求了随机字段
                $arrayRandomField = $customFormFieldPublicData->GetListByName($customFormId, $randomFieldName);
                if (count($arrayRandomField) <= 0 || $arrayRandomField == null) {
                    $result = DefineCode::CUSTOM_FORM_RECORD_PUBLIC + self::CANNOT_FIND_FIELD_FOR_RANDOM; //后台没有设置随机字段，字段找不到
                    return Control::GetRequest("jsonpcallback", "") . '(' . $result . ')';
                }
                if (count($arrayRandomField) > 1) {
                    $result = DefineCode::CUSTOM_FORM_RECORD_PUBLIC + self::REPEAT_FIELD_NAME; //字段有重复
                    return Control::GetRequest("jsonpcallback", "") . '(' . $result . ')';
                }
                if ($arrayRandomField[0]["CustomFormFieldType"] != 0) {
                    $result = DefineCode::CUSTOM_FORM_RECORD_PUBLIC + self::FALSE_CUSTOM_FORM_FIELD_TYPE; //字段类型错误（随机数字段只允许int型）
                    return Control::GetRequest("jsonpcallback", "") . '(' . $result . ')';
                }
                $randomResult = self::GetRandomResult($customFormId, $arrayRandomField[0]["CustomFormFieldId"]);
                $customFormContentPublicData->DeleteOneContent($newId, $arrayRandomField[0]["CustomFormFieldId"]); //删除旧数据（如果有）
                $contentId = $customFormContentPublicData->Create($newId, $customFormId, $arrayRandomField[0]["CustomFormFieldId"], $userId, $randomResult, $arrayRandomField[0]["CustomFormFieldType"]);

                $randomResult += 100; //以防跟下面的”成功=1“混淆
                //前台获取:
                //result>100：对应奖项 result-100
                //result=100：不获奖
                //result=200：奖项剩余0
                return Control::GetRequest("jsonpcallback", "") . '(' . $randomResult . ')';


            }

            $result = 1; //成功
            return Control::GetRequest("jsonpcallback", "") . '(' . $result . ')';
        } else {
            $result = -1; //失败
            return Control::GetRequest("jsonpcallback", "") . '(' . $result . ')';
            //return DefineCode::CUSTOM_FORM_RECORD_PUBLIC+self::DATABASE_INPUT_FAILED;
        }


    }

    /**
     * 取得随机数，用于抽奖、查询码等功能
     * 具体随机规则暂写在该函数内
     * 由于具体规则是一个二维数组，今后可考虑用json，xml或其他文本存储在某个地方做调用
     *
     * （抽奖功能已有独立gen   此方法作废，或可改为获取随机码的方法）
     */
    private function GetRandomResult($customFormId, $customFormFieldId)
    {
        $result = 0;
        $arrayWinType = array();
        $winType = array();


        $winType["chance"] = 1; //获奖对应概率  随机值小于该值且大于之前奖的值则获此奖  （一次roll点圆桌理论）
        $winType["total"] = 50; //设奖数，若纪录内已达到该值，则返回0：不获奖
        $arrayWinType[] = $winType; //数组的index 即为获奖对应码，若得此奖则纪录该值+1  例：一等奖代表$arrayWinType[0]  获奖则纪录0+1=1

        $winType["chance"] = 2;
        $winType["total"] = 50;
        $arrayWinType[] = $winType;

        $missResult = 0; //不获奖，若没得奖则纪录该值

        $random = rand(1, 100);

        $result = 0; //0 表示没奖  X表示第X个奖
        for ($i = 0; $i < count($arrayWinType); $i++) { //循环奖项  看中了哪个奖
            if ($random <= $arrayWinType[$i]["chance"]) {
                $result = $i + 1; //因为i从0开始
                break;
            } else {
                continue;
            }
        }

        if ($result > 0) {
            //检查该奖项是否有剩余
            $customFormContentPublicData = new CustomFormContentPublicData();
            $count = $customFormContentPublicData->CheckRepeat($customFormId, $customFormFieldId, 0, $result); //0='ContentOfInt'
            if ($count >= $arrayWinType[$result - 1]["total"]) { //$result-1取数组的正确位置
                $result = 100; //奖已发完
            }
        }

        return $result;
    }


}

?>