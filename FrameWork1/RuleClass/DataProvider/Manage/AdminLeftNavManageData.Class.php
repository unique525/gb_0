<?php

/**
 * 后台管理左边导航后台数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Manage
 * @author zhangchi
 */
class AdminLeftNavManageData extends BaseManageData {
    /**
     * 表名
     */
    const tableName = "cst_adminleftnav";

    /**
     * 表关键字段名
     */
    const tableIdName = "adminleftnavid";

    /**
     * 管理后台左边导航数据集
     * @param int $adminUserId 后台管理员id
     * @param AdminUserGroupManageData $adminUserGroupManageData 后台管理员分组数据对象
     * @return array 结果数据集
     */
    function GetList($adminLeftNavIds) {
        if (strlen($adminLeftNavIds) > 0) {
            $sql = "SELECT * FROM cst_adminleftnav WHERE AdminLeftNavID IN (" . $adminLeftNavIds . ") ORDER BY Sort DESC";
            $result = $this->dbOperator->ReturnArray($sql, null);
            return $result;
        } else {
            return null;
        }
    }

    /**
     * 
     * @return array
     */
    public function GetListForShow() {
        $dataProperty = new DataProperty();
        $sql = "SELECT adminleftnavid,adminleftnavname FROM " . self::tableName;
        $result = $this->dbOperator->ReturnArray($sql, $dataProperty);
        return $result;
    }

}

?>
