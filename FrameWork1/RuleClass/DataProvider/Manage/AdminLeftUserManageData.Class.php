<?php

/**
 * 后台管理左边导航的会员管理后台数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Manage
 * @author zhangchi
 */
class AdminLeftUserManageData extends BaseManageData {
    
    /**
     * 返回列表数据集
     * @return array 结果数据集
     */
    public function GetList() {
        $sql = "SELECT * FROM " . self::tableName . " WHERE State<100 ORDER BY Sort DESC;";
        $result = $this->dbOperator->ReturnArray($sql, null);
        return $result;
    }
}

?>
