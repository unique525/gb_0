<?php

/**
 * 前台 活动表单 数据类
 *
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Customform
 * @author 525
 */
class CustomFormFieldPublicData extends BasePublicData {



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


}

?>