<?php

/**
 * 前台 活动表单 数据类
 *
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Customform
 * @author 525
 */
class CustomFormPublicData extends BasePublicData {
    /**
     * 返回表单所在频道ID
     * @param int $customFormId 表单id
     * @param boolean $withCache 是否使用缓存
     * @return int 表单所在频道id
     */
    public function GetChannelId($customFormId,$withCache) {
        $result = -1;
        if ($customFormId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'custom_form_data';
            $cacheFile = 'custom_form_get_channel_id.cache_' . $customFormId . '.php';
            $sql = "SELECT ChannelId FROM " . self::TableName_CustomForm . " WHERE CustomFormId = :CustomFormId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("CustomFormId", $customFormId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }
    /**
     * 返回表单启用状态
     * @param int $customFormId 表单id
     * @param boolean $withCache 是否使用缓存
     * @return int 表单状态
     */
    public function GetState($customFormId,$withCache) {
        $result = -1;
        if ($customFormId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'custom_form_data';
            $cacheFile = 'custom_form_get_state.cache_' . $customFormId . '.php';
            $sql = "SELECT State FROM " . self::TableName_CustomForm . " WHERE CustomFormId = :CustomFormId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("CustomFormId", $customFormId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }


}

?>