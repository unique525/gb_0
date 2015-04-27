<?php

/**
 * 前台 活动表单内容 数据类
 *
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Customform
 * @author 525
 */
class CustomFormContentPublicData extends BaseManageData {


    /**
     * 新增表单记录的内容
     * @param int $customFormRecordId 被操作的表单记录的id
     * @param int $customFormId 被操作的表单的id
     * @param int $customFormFieldId 被操作的表单字段id
     * @param int $userId 操作用户的id
     * @param mixed $content 新增的内容
     * @param int $customFormFieldType 字段类型
     * @return int 执行结果
     */
    public function Create($customFormRecordId, $customFormId, $customFormFieldId, $userId, $content, $customFormFieldType = 1) {
        $result=-1;
        if($customFormRecordId>0&&$customFormId>0&&$customFormFieldId>0){
            $dataProperty = new DataProperty();
            $dataProperty->AddField("CustomFormRecordId", $customFormRecordId);
            $dataProperty->AddField("CustomFormId", $customFormId);
            $dataProperty->AddField("CustomFormFieldId", $customFormFieldId);
            $dataProperty->AddField("UserId", $userId);

            $sql="";

            switch ($customFormFieldType) {
                case 0:
                    $dataProperty->AddField("ContentOfInt", $content);
                    $sql = "INSERT INTO " . self::TableName_CustomFormContent . " (CustomFormRecordId,CustomFormId,CustomFormFieldId,UserId,ContentOfInt) VALUES (:CustomFormRecordId,:CustomFormId,:CustomFormFieldId,:UserId,:ContentOfInt) ;";
                    break;
                case 1:
                    $dataProperty->AddField("ContentOfString", $content);
                    $sql = "INSERT INTO " . self::TableName_CustomFormContent . " (CustomFormRecordId,CustomFormId,CustomFormFieldId,UserId,ContentOfString) VALUES (:CustomFormRecordId,:CustomFormId,:CustomFormFieldId,:UserId,:ContentOfString) ;";
                    break;
                case 2:
                    $dataProperty->AddField("ContentOfText", $content);
                    $sql = "INSERT INTO " . self::TableName_CustomFormContent . " (CustomFormRecordId,CustomFormId,CustomFormFieldId,UserId,ContentOfText) VALUES (:CustomFormRecordId,:CustomFormId,:CustomFormFieldId,:UserId,:ContentOfText) ;";
                    break;
                case 3:
                    $dataProperty->AddField("ContentOfFloat", $content);
                    $sql = "INSERT INTO " . self::TableName_CustomFormContent . " (CustomFormRecordId,CustomFormId,CustomFormFieldId,UserId,ContentOfFloat) VALUES (:CustomFormRecordId,:CustomFormId,:CustomFormFieldId,:UserId,:ContentOfFloat) ;";
                    break;
                case 4:
                    $dataProperty->AddField("ContentOfDatetime", $content);
                    $sql = "INSERT INTO " . self::TableName_CustomFormContent . " (CustomFormRecordId,CustomFormId,CustomFormFieldId,UserId,ContentOfDatetime) VALUES (:CustomFormRecordId,:CustomFormId,:CustomFormFieldId,:UserId,:ContentOfDatetime) ;";
                    break;
            }
            if ($sql!="") {
                $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
            }
        }
        return $result;
    }



    /**
     * 按CustomFormRecordId 删除该表单下所有记录的内容
     * @param int $customFormRecordId 被操作的表单记录的id
     * @return int 执行结果
     */
    public function Delete($customFormRecordId) {
        $result="-1";
        if($customFormRecordId>0){
            $dataProperty = new DataProperty();
            $dataProperty->AddField("CustomFormRecordId", $customFormRecordId);
            $sql = "DELETE FROM ".self::TableName_CustomFormContent." WHERE CustomFormRecordId=:CustomFormRecordId ;";
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 按CustomFormRecordId，CustomFormFieId删除内容项
     * @param int $customFormRecordId 表单记录的id
     * @param int $customFormFieId 表单字段的id
     * @return int 执行结果
     */
    public function DeleteOneContent($customFormRecordId,$customFormFieId) {
        $result="-1";
        if($customFormRecordId>0){
            $dataProperty = new DataProperty();
            $dataProperty->AddField("CustomFormRecordId", $customFormRecordId);
            $dataProperty->AddField("CustomFormFieldId", $customFormFieId);
            $sql = "DELETE FROM ".self::TableName_CustomFormContent." WHERE CustomFormRecordId=:CustomFormRecordId AND  CustomFormFieldId=:CustomFormFieldId ;";
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 按$customFormRecordId获取表单记录下所有记录内容的列表
     * @param int $customFormRecordId 表单记录id
     * @return array 表单记录数据
     */
    public function GetList($customFormRecordId) {
        $result=-1;
        if($customFormRecordId>0){
            $dataProperty = new DataProperty();
            $dataProperty->AddField("CustomFormRecordId", $customFormRecordId);
            $sql = "SELECT * FROM " . self::TableName_CustomFormContent . " WHERE CustomFormRecordId=:CustomFormRecordId ORDER BY Sort DESC ;";
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }

 /**
 * 创建附件
 * @param int $customFormRecordId 被操作的表单记录的id
 * @param int $customFormId 被操作的表单的id
 * @param int $customFormFieldId 被操作的表单字段id
 * @param int $userId 操作用户的id
 * @param mixed $content 新增的内容
 * @param int $fileName 格式化文件名 存入ContentOfText字段
 * @param int $fileType 文件type 存入ContentOfString字段
 * @return int 新增id
 */
    public function CreateAttachment($customFormRecordId, $customFormId, $customFormFieldId, $userId, $content, $fileName, $fileType){
        $result = -1;
        if($customFormRecordId>0&&$customFormId>0&&$customFormFieldId>0 && !empty($content)){
            $sql = "INSERT INTO
                        " . self::TableName_CustomFormContent . "
                    (CustomFormRecordId,CustomFormId,CustomFormFieldId,UserId,ContentOfBlob,ContentOfText,ContentOfString)
                    VALUES (:CustomFormRecordId,:CustomFormId,:CustomFormFieldId,:UserId,:ContentOfBlob,:ContentOfText,:ContentOfString) ;";
            $dataProperty = new DataProperty();
            //$dataProperty->AddField("attachment", $attachment, PDO::PARAM_LOB);
            $dataProperty->AddField("ContentOfBlob", $content);
            $dataProperty->AddField("CustomFormRecordId", $customFormRecordId);
            $dataProperty->AddField("CustomFormId", $customFormId);
            $dataProperty->AddField("CustomFormFieldId", $customFormFieldId);
            $dataProperty->AddField("UserId", $userId);
            $dataProperty->AddField("ContentOfText", $fileName);
            $dataProperty->AddField("ContentOfString", $fileType);
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        }

        return $result;
    }


    /**
     * 检查表单字段内容是否有重复项
     * @param int $customFormId 表单的id
     * @param int $customFormFieldId 字段id
     * @param int $customFormFieldType 字段类型
     * @param string $customFormContent 字段内容
     * @return int 重复数
     */
    public function CheckRepeat($customFormId, $customFormFieldId, $customFormFieldType, $customFormContent){
        $result = 0;
        if($customFormId>0&&$customFormFieldId>0){
            $contentType="";
            $contentSelection="";
            switch($customFormFieldType){
                case 0:
                    $contentType="ContentOfInt";
                    $contentSelection=" AND $contentType = $customFormContent ";
                    break;
                case 1:
                    $contentType="ContentOfString";
                    $contentSelection=" AND $contentType = '$customFormContent' ";
                    break;
                case 2:
                    $contentType="ContentOfText";
                    $contentSelection=" AND $contentType = '$customFormContent' ";
                    break;
                case 3:
                    $contentType="ContentOfFloat";
                    $contentSelection=" AND $contentType = $customFormContent ";
                    break;
                case 4:
                    $contentType="ContentOfDateTime";
                    break;
                case 5:
                    $contentType="ContentOfBlob";
                    break;
            }
            $sql = "SELECT COUNT(*) FROM
                        " . self::TableName_CustomFormContent . "
                    WHERE CustomFormId=:CustomFormId AND CustomFormFieldId=:CustomFormFieldId $contentSelection ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("CustomFormId", $customFormId);
            $dataProperty->AddField("CustomFormFieldId", $customFormFieldId);
            $result = $this->dbOperator->GetInt($sql, $dataProperty);
        }

        return $result;
    }

}

?>
