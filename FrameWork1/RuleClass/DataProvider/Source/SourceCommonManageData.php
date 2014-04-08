<?php
/**
 * 后台管理 通用来源 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Source
 * @author zhangchi
 */
class SourceCommonManageData extends BaseManageData {

    /**
     * 新增
     * @param array $httpPostData $_POST数组
     * @return int 新增的通用来源id
     */
    public function Create($httpPostData) {
        $dataProperty = new DataProperty();
        $sql = parent::GetInsertSql($httpPostData, self::TableName_SourceCommon, $dataProperty);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 修改
     * @param array $httpPostData $_POST数组
     * @param int $sourceCommonId 通用来源id
     * @return int 操作结果
     */
    public function Modify($httpPostData, $sourceCommonId) {
        $dataProperty = new DataProperty();
        $sql = parent::GetUpdateSql($httpPostData,self::TableName_SourceCommon, self::TableId_SourceCommon, $sourceCommonId, $dataProperty);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 删除到回收站(State 100)
     * @param int $sourceCommonId 通用来源id
     * @return int 操作结果
     */
    public function RemoveToBin($sourceCommonId) {
        $sql = "UPDATE " . self::TableName_SourceCommon . " SET State=100 WHERE " . self::TableId_SourceCommon . "=:" . self::TableId_SourceCommon . ";";
        $dataProperty = new DataProperty();
        $dataProperty->AddField(self::TableId_SourceCommon, $sourceCommonId);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得全部数据列表
     * @return array 全部数据列表
     */
    public function GetList() {
        $sql = "SELECT * FROM " . self::TableName_SourceCommon . " WHERE State<100 ORDER BY Sort DESC;";
        $result = $this->dbOperator->GetArrayList($sql, null);
        return $result;
    }
} 