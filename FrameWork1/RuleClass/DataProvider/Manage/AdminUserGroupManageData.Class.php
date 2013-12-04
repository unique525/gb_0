<?php
/**
 * 后台管理分组后台数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Manage
 * @author zhangchi
 */
class AdminUserGroupManageData extends BaseManageData {

    /**
     * 新增后台管理分组
     * @return int 新增的后台管理分组id
     */
    public function Create() {
        $dataProperty = new DataProperty();
        $sql = parent::GetInsertSql(self::TableName_AdminUserGroup, $dataProperty);
        $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        return $result;
    }

    /**
     * 修改后台管理分组id
     * @param int $adminUserGroupId 后台管理分组id
     * @return int 执行结果（影响的行数）
     */
    public function Modify($adminUserGroupId) {
        $dataProperty = new DataProperty();
        $sql = parent::GetUpdateSql(self::TableName_AdminUserGroup, self::TableId_AdminUserGroup, $adminUserGroupId, $dataProperty);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 删除到回收站
     * @param int $adminUserGroupId 后台管理分组id
     * @return int 执行结果（影响的行数）
     */
    public function RemoveBin($adminUserGroupId) {
        $sql = "UPDATE " . self::TableName_AdminUserGroup . " SET State=100 WHERE AdminUserGroupId=:AdminUserGroupId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("AdminUserGroupId", $adminUserGroupId);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 根据State的值取得列表
     * @param int $state 状态值
     * @return array 结果数据集
     */
    public function GetList($state = -1) {
        if ($state < 0) {
            $sql = "SELECT * FROM " . self::TableName_AdminUserGroup;
            $result = $this->dbOperator->ReturnArray($sql, null);
        } else {
            $sql = "SELECT * FROM " . self::TableName_AdminUserGroup . " WHERE State=:State";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("State", $state);
            $result = $this->dbOperator->ReturnArray($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 取得一条记录
     * @param int $adminUserGroupId 后台管理分组id
     * @return array 结果行
     */
    public function GetOne($adminUserGroupId) {
        $dataProperty = new DataProperty();
        $sql = "SELECT * FROM " . self::TableName_AdminUserGroup . " WHERE AdminUserGroupId=:AdminUserGroupId";
        $dataProperty->AddField("AdminUserGroupId", $adminUserGroupId);
        $result = $this->dbOperator->ReturnRow($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得一条记录(根据后台管理分组名称)
     * @param string $adminUserGroupName 后台管理分组名称
     * @return array 结果行
     */
    public function GetOneByAdminUserGroupName($adminUserGroupName) {
        $dataProperty = new DataProperty();
        $sql = "SELECT * FROM " . self::TableName_AdminUserGroup . " WHERE AdminUserGroupName=:AdminUserGroupName";
        $dataProperty->AddField("AdminUserGroupName", $adminUserGroupName);
        $result = $this->dbOperator->ReturnRow($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得分页数据集
     * @param int $pageBegin 当前页码
     * @param int $pageSize 每页记录数
     * @param int $allCount 记录总数（输出参数）
     * @return array 取得分页数据集
     */
    public function GetListPager($pageBegin, $pageSize, &$allCount) {
        $sql = "SELECT * FROM " . self::TableName_AdminUserGroup . " ORDER BY Sort DESC LIMIT " . $pageBegin . "," . $pageSize . "";
        $result = $this->dbOperator->ReturnArray($sql, null);
        $sql = "SELECT count(*) FROM " . self::TableName_AdminUserGroup;
        $allCount = $this->dbOperator->ReturnInt($sql, null);
        return $result;
    }

    /**
     * 根据后台管理员id取得开启的左边导航id字符串
     * @param int $adminUserId 后台管理员id
     * @return string 左边导航id字符串
     */
    public function GetAdminLeftNavIds($adminUserId) {
        $dataProperty = new DataProperty();
        $sql = "SELECT AdminLeftNavIds FROM " . self::TableName_AdminUserGroup . " WHERE AdminUserGroupId IN (SELECT AdminUserGroupId FROM " . self::TableName_AdminUser . " WHERE AdminUserId=:AdminUserId)";
        $dataProperty->AddField("AdminUserId", $adminUserId);
        $result = $this->dbOperator->ReturnString($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得后台管理分组名称
     * @param int $adminUserGroupId 后台管理分组id
     * @return string 后台管理分组名称
     */
    public function GetAdminUserGroupName($adminUserGroupId) {
        $dataProperty = new DataProperty();
        $sql = "SELECT AdminUserGroupName FROM " . self::TableName_AdminUserGroup . " WHERE " . self::TableId_AdminUserGroup . "=:" . self::TableId_AdminUserGroup;
        $dataProperty->AddField(self::TableId_AdminUserGroup, $adminUserGroupId);
        $result = $this->dbOperator->ReturnString($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得后台管理分组名称(根据后台管理员id)
     * @param int $adminUserId 后台管理员id
     * @return string 后台管理分组名称
     */
    public function GetAdminUserGroupNameByAdminUserId($adminUserId) {
        $dataProperty = new DataProperty();
        $sql = "SELECT AdminUserGroupName FROM " . self::TableName_AdminUserGroup . " WHERE " . self::TableId_AdminUserGroup . " IN (SELECT AdminUserGroupId FROM " . self::TableName_AdminUser . " WHERE AdminUserId=:AdminUserId)";
        $dataProperty->AddField("AdminUserId", $adminUserId);
        $result = $this->dbOperator->ReturnString($sql, $dataProperty);
        return $result;
    }
}
?>
