<?php

/**
 * 后台管理 会员管理菜单 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Manage
 * @author zhangchi
 */
class ManageMenuOfUserManageData extends BaseManageData {

    /**
     * 返回列表数据集
     * @return array 结果数据集
     */
    public function GetList() {
        $sql = "SELECT * FROM " . self::TableName_ManageMenuOfUser . " WHERE State<100 ORDER BY Sort DESC;";
        $result = $this->dbOperator->GetArrayList($sql, null);
        return $result;
    }
}

?>
