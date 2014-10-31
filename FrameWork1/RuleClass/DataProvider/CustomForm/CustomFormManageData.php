<?php

/**
 * 后台管理 活动表单 数据类
 *
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Customform
 * @author 525
 */
class CustomFormManageData extends BaseManageData {
    /**
     * 新增表单
     * @param array $httpPostData $_post数组
     * @return int 新增表单id
     */
    public function Create($httpPostData) {
        $result=-1;
        if(!empty($httpPostData)){
            $dataProperty = new DataProperty();
            $sql = parent::GetInsertSql($httpPostData,self::TableName_CustomForm, $dataProperty);
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 修改
     * @param array $httpPostData $_post数组
     * @param int $customFormId 表单id
     * @return int 执行结果
     */
    public function Modify($httpPostData,$customFormId) {
        $result=-1;
        if(!empty($httpPostData)&&$customFormId>0){
            $dataProperty = new DataProperty();
            $sql = parent::GetUpdateSql($httpPostData,self::TableName_CustomForm, self::TableId_CustomForm, $customFormId, $dataProperty);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 获取自定义页面分页列表
     * @param int $channelId 频道id
     * @param int $pageBegin 起始页码
     * @param int $pageSize 每页大小
     * @param int $allCount 总大小
     * @return array 表单数据集
     */
    public function GetListPager($channelId, $pageBegin, $pageSize, &$allCount) {
        $result=-1;
        if($channelId>0){
            $searchSql = "";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);

            $sql = "
                SELECT
                *
                FROM
                " . self::TableName_CustomForm . "
                WHERE ChannelId=:ChannelId " . $searchSql . " ORDER BY Sort DESC, CreateDate DESC LIMIT " . $pageBegin . "," . $pageSize . " ;";

            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
            $sqlCount = "SELECT count(*) FROM " . self::TableName_CustomForm . " WHERE ChannelId=:ChannelId " . $searchSql . " ;";
            $allCount = $this->dbOperator->GetInt($sqlCount, $dataProperty);
        }
        return $result;
    }

    /**
     * 获取管理员ID
     * @param int $customFormId 表单id
     * @param boolean $withCache 是否使用缓存
     * @return int type 取得的管理员id
     */
    public function GetManageUserId($customFormId,$withCache) {

        $result = -1;
        if ($customFormId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'custom_form_data';
            $cacheFile = 'custom_form_get_Manage_user_id.cache_' . $customFormId . '.php';
            $sql = "SELECT ManageUserId FROM " . self::TableName_CustomForm . " WHERE CustomFormId = :CustomFormId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("CustomFormId", $customFormId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 通过ID获取一条记录
     * @param int $customFormId 表单id
     * @return array 表单数据
     */
    public function GetOne($customFormId) {
        $result=-1;
        if($customFormId>0){
            $sql = "SELECT * FROM " . self::TableName_CustomForm . " WHERE CustomFormId = :CustomFormId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("CustomFormId", $customFormId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 改变状态
     * @param int $customFormId 表单id
     * @param int $state 状态
     * @return int 执行结果
     */

    public function ModifyState($customFormId, $state) {
        $result=-1;
        if($customFormId>0){
            $sql = "UPDATE " . self::TableName_CustomForm . " SET State = :State WHERE CustomFormId = :CustomFormId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("State", $state);
            $dataProperty->AddField("CustomFormId", $customFormId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 获取表单状态
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

    /**
     * 获取创建日期
     * @param int $customFormId 表单id
     * @param boolean $withCache 是否使用缓存
     * @return string 表单创建日期
     */
    public function GetCreateDate($customFormId,$withCache) {
        $result = -1;
        if ($customFormId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'custom_form_data';
            $cacheFile = 'custom_form_get_create_date.cache_' . $customFormId . '.php';
            $sql = "SELECT CreateDate FROM " . self::TableName_CustomForm . " WHERE CustomFormId = :CustomFormId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("CustomFormId", $customFormId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

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

}

?>
