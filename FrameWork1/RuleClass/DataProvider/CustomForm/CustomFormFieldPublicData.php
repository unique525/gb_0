<?php

/**
 * 前台 活动表单 数据类
 *
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Customform
 * @author 525
 */
class CustomFormFieldPublicData extends BaseManageData {



    /**
     * 通过字段id获取该字段的类型
     * @param int $customFormFieldId
     * @param boolean $withCache
     * @return int 字段类型
     */
    public function GetCustomFormFieldType($customFormFieldId, $withCache) {
        $result=-1;

        if ($customFormFieldId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'custom_form_field_data';
            $cacheFile = 'custom_form_field_get_type.cache_' . $customFormFieldId . '.php';
            $sql = "SELECT CustomFormFieldType FROM " . self::TableName_CustomFormField . " WHERE CustomFormFieldId = :CustomFormFieldId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("CustomFormFieldId", $customFormFieldId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }


    /**
     * 取得表单下的唯一性字段
     * @param int $customFormId
     * @return array 字段数据集 （id,type）
     */
    public function GetUniqueField($customFormId) {
        $result=null;

        if ($customFormId > 0) {
            $sql = "SELECT CustomFormFieldId,CustomFormFieldType FROM " . self::TableName_CustomFormField . " WHERE CustomFormId = :CustomFormId AND IsUnique=1;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("CustomFormId", $customFormId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 根据字段名称获取list
     * @param int $customFormId
     * @param string $customFormFieldName
     * @return array 字段数据集 （id,type）
     */
    public function GetListByName($customFormId,$customFormFieldName) {
        $result=null;

        if ($customFormId > 0) {
            $sql = "SELECT CustomFormFieldId,CustomFormFieldType FROM " . self::TableName_CustomFormField . " WHERE CustomFormId = :CustomFormId AND CustomFormFieldName=:CustomFormFieldName ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("CustomFormId", $customFormId);
            $dataProperty->AddField("CustomFormFieldName", $customFormFieldName);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }
}

?>