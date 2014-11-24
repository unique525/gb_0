<?php

/**
 * 后台管理 栏目菜单 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Manage
 * @author zhangchi
 */
class ManageMenuOfColumnManageData extends BaseManageData {

    /**
     * 根据后台菜单的id字符串取得后台菜单数据集
     * @param string $manageMenuOfColumnIdValue 后台管理员id
     * @return array 结果数据集
     */
    function GetList($manageMenuOfColumnIdValue) {
        if (strlen($manageMenuOfColumnIdValue) > 0) {
            $manageMenuOfColumnIdValue = Format::RemoveQuote($manageMenuOfColumnIdValue);
            $sql = "SELECT * FROM ".self::TableName_ManageMenuOfColumn." WHERE ".self::TableId_ManageMenuOfColumn." IN (" . $manageMenuOfColumnIdValue . ") ORDER BY Sort DESC";
            $result = $this->dbOperator->GetArrayList($sql, null);
            return $result;
        } else {
            return null;
        }
    }

    /**
     * 取得全部后台菜单数据集
     * @return array 结果数据集
     */
    public function GetListOfAll() {
        $dataProperty = null;
        $sql = "SELECT * FROM " . self::TableName_ManageMenuOfColumn . " ORDER BY Sort DESC;";
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得开启的后台菜单数据集
     * @return array 结果数据集
     */
    public function GetListOfOpen() {
        $dataProperty = null;
        $sql = "SELECT * FROM " . self::TableName_ManageMenuOfColumn . " WHERE State<100 ORDER BY Sort DESC;";
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        return $result;
    }

}

?>
