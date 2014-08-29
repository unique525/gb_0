<?php

/**
 * 后台管理 调试 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_DebugLog
 * @author zhangchi
 */
class DebugLogManageData extends BaseManageData {

    /**
     * 新增调试内容
     * @param string $debugLogContent 调试内容
     * @return int 返回新增结果
     */
    public function Create($debugLogContent) {
        $dataProperty = new DataProperty();
        $sql = "INSERT INTO ".self::TableName_DebugLog ." (DebugLogContent,createdate) VALUES (:DebugLogContent,now());";
        $dataProperty->AddField("DebugLogContent", $debugLogContent);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

} 