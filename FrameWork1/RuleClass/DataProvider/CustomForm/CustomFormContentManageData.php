<?php

/**
 * 后台管理 活动表单内容 数据类
 *
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Customform
 * @author 525
 */
class CustomFormContentManageData extends BaseManageData {

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
        $dataProperty = new DataProperty();
        $dataProperty->AddField("CustomFormRecordId", $customFormRecordId);
        $dataProperty->AddField("CustomFormId", $customFormId);
        $dataProperty->AddField("CustomFormFieldId", $customFormFieldId);
        $dataProperty->AddField("UserId", $userId);



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
            case 5:
                $dataProperty->AddField("ContentOfBlob", $content);
                $sql = "INSERT INTO " . self::TableName_CustomFormContent . " (CustomFormRecordId,CustomFormId,CustomFormFieldId,UserId,ContentOfBlob) VALUES (:CustomFormRecordId,:CustomFormId,:CustomFormFieldId,:UserId,:ContentOfBlob) ;";
                break;
        }

        if (!empty($sql)) {
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 按CustomFormRecordId 删除该表单下所有记录的内容
     * @param int $customFormRecordId 被操作的表单记录的id
     * @return int 执行结果
     */
    public function Delete($customFormRecordId) {
        $dataProperty = new DataProperty();
        $dataProperty->AddField("CustomFormRecordId", $customFormRecordId);
        $sql = "DELETE FROM ".self::TableName_CustomFormContent." WHERE CustomFormRecordId=:CustomFormRecordId ;";
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 按$customFormRecordId获取表单记录下所有记录内容的列表
     * @param int $customFormRecordId 表单记录id
     * @return array 表单记录数据
     */
    public function GetList($customFormRecordId) {
        $dataProperty = new DataProperty();
        $dataProperty->AddField("CustomFormRecordId", $customFormRecordId);
        $sql = "SELECT * FROM " . self::TableName_CustomFormContent . " WHERE CustomFormRecordId=:CustomFormRecordId ORDER BY Sort DESC ;";
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        return $result;
    }

}

?>
