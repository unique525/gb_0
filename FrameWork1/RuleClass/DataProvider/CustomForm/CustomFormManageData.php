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
        $dataProperty = new DataProperty();
        if(!empty($httpPostData)){
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
        $dataProperty = new DataProperty();
        if(!empty($httpPostData)){
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
        $searchSql = "";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("ChannelId", $channelId);

        $sql = "
            SELECT
            *
            FROM
            " . self::TableName_CustomForm . "
            WHERE ChannelId=:ChannelId AND State<100 " . $searchSql . " ORDER BY Sort DESC, CreateDate DESC LIMIT " . $pageBegin . "," . $pageSize . "";


        $result = $this->dbOperator->ReturnArray($sql, $dataProperty);
        $sqlCount = "SELECT count(*) FROM " . self::TableName_CustomForm . " WHERE ChannelId=:ChannelId AND state<100 " . $searchSql;
        $allCount = $this->dbOperator->GetInt($sqlCount, $dataProperty);
        return $result;
    }

    /**
     * 获取管理员ID
     * @param int $customFormId 表单id
     * @return int type 取得的管理员id
     */
    public function GetManageUserId($customFormId) {
        $sql = "SELECT AdminUserId FROM " . self::TableName_CustomForm . " WHERE CustomFormId = :CustomFormId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("CustomFormId", $customFormId);
        $result = $this->dbOperator->GetInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 通过ID获取一条记录
     * @param int $customFormId 表单id
     * @return array 表单数据
     */
    public function GetOne($customFormId) {
        $sql = "SELECT * FROM " . self::TableName_CustomForm . " WHERE CustomFormId = :CustomFormId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("CustomFormId", $customFormId);
        $result = $this->dbOperator->ReturnRow($sql, $dataProperty);
        return $result;
    }

    /**
     * 改变状态
     * @param int $customFormId 表单id
     * @param int $state 状态
     * @return int 执行结果
     */

    public function ModifyState($customFormId, $state) {
        $sql = "UPDATE " . self::TableName_CustomForm . " SET State = :State WHERE CustomFormId = :CustomFormId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("State", $state);
        $dataProperty->AddField("CustomFormId", $customFormId);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 获取表单状态
     * @param int $customFormId 表单id
     * @return int 表单状态
     */
    public function GetState($customFormId) {
        $sql = "SELECT State FROM " . self::TableName_CustomForm . " WHERE CustomFormId = :CustomFormId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("CustomFormId", $customFormId);
        $result = $this->dbOperator->GetInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 获取创建日期
     * @param int $customFormId 表单id
     * @return string 表单创建日期
     */
    public function GetCreateDate($customFormId) {
        $sql = "SELECT CreateDate FROM " . self::TableName_CustomForm . " WHERE CustomFormId = :CustomFormId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("CustomFormId", $customFormId);
        $result = $this->dbOperator->ReturnString($sql, $dataProperty);
        return $result;
    }

    /**
     * 返回表单所在频道ID
     * @param int $customFormId 表单id
     * @return int 表单所在频道id
     */
    public function GetChannelId($customFormId) {
        $sql = "SELECT ChannelId FROM " . self::TableName_CustomForm . " WHERE CustomFormId = :CustomFormId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("CustomFormId", $customFormId);
        $result = $this->dbOperator->GetInt($sql, $dataProperty);
        return $result;
    }

}

?>
