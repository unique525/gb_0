<?php
/**
 * 表单字段数据类
 *
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Customform
 * @author 525
 */
class CustomFormFieldManageData extends BaseManageData {
    /**
     * 新增字段
     * @param array $httpPostData $_post数组
     * @return int 新增字段id
     */
    public function Create($httpPostData) {
        $result=-1;
        if(!empty($httpPostData)){
            $dataProperty = new DataProperty();
            $sql = parent::GetInsertSql($httpPostData,self::TableName_CustomFormField, $dataProperty);
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 修改
     * @param array $httpPostData $_post数组
     * @param int $customFormFieldId 表单字段id
     * @return int 执行结果
     */
    public function Modify($httpPostData,$customFormFieldId) {
        $result=-1;
        if(!empty($httpPostData)){
            $dataProperty = new DataProperty();
            $sql = parent::GetUpdateSql($httpPostData,self::TableName_CustomFormField, self::TableId_CustomFormField, $customFormFieldId, $dataProperty);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 获取一个表单下所有字段列表
     * @param int $customFormId 表单id
     * @return array 表单下所有字段数据集
     */
    public function GetList($customFormId) {
        $dataProperty = new DataProperty();
        $dataProperty->AddField("CustomFormId", $customFormId);
        $sql = "SELECT * FROM " . self::TableName_CustomFormField . " WHERE CustomFormId=:CustomFormId ORDER BY Sort DESC ";
        $result = $this->dbOperator->ReturnArray($sql, $dataProperty);
        return $result;
    }

    /**
     * 获取一个表单下未隐藏的字段列表
     * @param int $customFormId 表单id
     * @return array 表单下未隐藏的字段数据集
     */
    public function GetListForContent($customFormId) {
        $dataProperty = new DataProperty();
        $dataProperty->AddField("CustomFormId", $customFormId);
        $sql = "SELECT * FROM " . self::TableName_CustomFormField . " WHERE CustomFormId=:CustomFormId and ShowInList=1 ORDER BY Sort DESC ";
        $result = $this->dbOperator->ReturnArray($sql, $dataProperty);
        return $result;
    }

    /**
     * 通过字段ID获取一个字段的所有属性
     * @param int $customFormFieldId
     * @return array 取得的一个字段的所有属性
     */
    public function GetOne($customFormFieldId) {
        $sql = "SELECT * FROM " . self::TableName_CustomFormField . " WHERE CustomFormFieldId = :CustomFormFieldId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("CustomFormFieldId", $customFormFieldId);
        $result = $this->dbOperator->ReturnRow($sql, $dataProperty);
        return $result;
    }


    /**
     * 通过字段id获取该字段的类型
     * @param int $customFormFieldId
     * @return int 字段类型
     */
    public function GetCustomFormFieldType($customFormFieldId) {
        $sql = "SELECT CustomFormFieldType FROM " . self::TableName_CustomFormField . " WHERE CustomFormFieldId = :CustomFormFieldId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("CustomFormFieldId", $customFormFieldId);
        $result = $this->dbOperator->ReturnInt($sql, $dataProperty);
        return $result;
    }
}

?>
