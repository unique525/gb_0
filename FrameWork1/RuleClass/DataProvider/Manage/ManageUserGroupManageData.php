<?php

/**
 * 后台管理 管理员分组 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Manage
 * @author zhangchi
 */
class ManageUserGroupManageData extends BaseManageData {

    public function Create() {
        $dataProperty = new DataProperty();
        $sql = parent::GetInsertSql(self::tableName, $dataProperty);
        $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        return $result;
    }

    public function Modify($tableidvalue) {
        $dataProperty = new DataProperty();
        $sql = parent::GetUpdateSql(self::tableName, self::tableIdName, $tableidvalue, $dataProperty);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    public function RemoveBin($groupid) {
        $sql = "update " . self::tableName . " set state=100 where adminusergroupid=:adminusergroupid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("adminusergroupid", $groupid);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    //根据State的值取得列表
    public function GetList($state = -1) {
        if ($state == -1) {
            $sql = "SELECT adminusergroupid,adminusergroupname,sort,state,adminleftnavids FROM " . self::tableName;
            $result = $this->dbOperator->GetArrayList($sql, null);
        } else {
            $sql = "SELECT adminusergroupid,adminusergroupname,sort,state,adminleftnavids FROM " . self::tableName . " WHERE state=:state";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("state", $state);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }

    //根据用户组ID或用户组名取值
    public function GetAdminUserGroupOne($str, $type) {
        $dataProperty = new DataProperty();
        if ($type == 0) {
            $sql = "SELECT adminusergroupid,adminusergroupname,sort,state,adminleftnavids FROM " . self::tableName . " WHERE adminusergroupid=:adminusergroupid";
            $dataProperty->AddField("adminusergroupid", $str);
        } else {
            $sql = "SELECT adminusergroupid,adminusergroupname,sort,state,adminleftnavids FROM " . self::tableName . " WHERE adminusergroupname=:adminusergroupname";
            $dataProperty->AddField("adminusergroupname", $str);
        }
        $result = $this->dbOperator->GetArray($sql, $dataProperty);
        return $result;
    }

    public function GetListPager($pagebegin, $pagesize, &$allcount, $gruopid=0) {
        $sql = "SELECT adminusergroupid,adminusergroupname,sort,state,adminleftnavids FROM cst_adminusergroup ORDER BY sort DESC LIMIT " . $pagebegin . "," . $pagesize . "";
        $result = $this->dbOperator->GetArrayList($sql, null);
        $sql = "SELECT count(*) FROM " . self::tableName;
        $allcount = $this->dbOperator->GetInt($sql, null);
        return $result;
    }

    /**
     * 根据用户ID取得左边导航权限
     * @param int $manageUserId
     * @return string 左边导航编号分割字符串
     */
    public function GetManageMenuOfColumnIdValue($manageUserId) {
        if($manageUserId>0){
            $dataProperty = new DataProperty();
            $sql = "SELECT ManageMenuOfColumnIdValue FROM " . self::TableName_ManageUserGroup . " WHERE ManageUserGroupId IN (SELECT ManageUserGroupId FROM ".self::TableName_ManageUser." WHERE ManageUserId=:ManageUserId);";
            $dataProperty->AddField("ManageUserId", $manageUserId);
            $result = $this->dbOperator->GetString($sql, $dataProperty);
            return $result;
        }else{
            return null;
        }
    }

}

?>
