<?php

/**
 * 后台管理 快捷内容录入 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Document
 * @author zhangchi
 */
class DocumentQuickContentManageData extends BaseManageData {

    public function Create(){

    }

    /**
     * 取得快捷内容录入列表
     * @return array 返回快捷内容录入列表
     */
    public function GetList(){
        $sql = "SELECT * FROM " . self::TableName_DocumentQuickContent . " WHERE State<100 ORDER BY Sort DESC;";
        return $this->dbOperator->GetArrayList($sql, null);
    }
} 