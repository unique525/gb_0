<?php

/**
 * 后台管理 文档快速前缀 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Document
 * @author zhangchi
 */
class DocumentPreContentManageData extends BaseManageData {
    public function Create(){

    }

    /**
     * 取得快捷内容录入列表
     * @return array 返回快捷内容录入列表
     */
    public function GetList(){
        $sql = "SELECT * FROM " . self::TableName_DocumentPreContent . " WHERE State<100 ORDER BY Sort DESC;";
        return $this->dbOperator->GetArrayList($sql, null);
    }
} 