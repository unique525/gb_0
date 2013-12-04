<?php

/**
 * 后台管理分组后台数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Manage
 * @author zhangchi
 */
class AdminUserGroupManageData extends BaseManageData {
    
    /**
     * 表名
     */
    const tableName = "cst_adminusergroup";    

    /**
     * 表关键字段名
     */
    const tableIdName = "adminusergroupid";

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
            $result = $this->dbOperator->ReturnArray($sql, null);
        } else {
            $sql = "SELECT adminusergroupid,adminusergroupname,sort,state,adminleftnavids FROM " . self::tableName . " WHERE state=:state";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("state", $state);
            $result = $this->dbOperator->ReturnArray($sql, $dataProperty);
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
        $result = $this->dbOperator->ReturnRow($sql, $dataProperty);
        return $result;
    }

    public function GetListPager($pagebegin, $pagesize, &$allcount, $gruopid=0) {
        $sql = "SELECT adminusergroupid,adminusergroupname,sort,state,adminleftnavids FROM cst_adminusergroup ORDER BY sort DESC LIMIT " . $pagebegin . "," . $pagesize . "";
        $result = $this->dbOperator->ReturnArray($sql, null);
        $sql = "SELECT count(*) FROM " . self::tableName;
        $allcount = $this->dbOperator->ReturnInt($sql, null);
        return $result;
    }

    /**
     * 根据用户ID取得左边导航权限
     * @param type $adminuserid
     * @return type 
     */
    public function GetAdminLeftNavIDs($adminuserid) {
        $dataProperty = new DataProperty();
        $sql = "SELECT AdminLeftNavIDs FROM " . self::tableName . " WHERE adminusergroupid in (select adminusergroupid from cst_adminuser where adminuserid=:adminuserid)";
        $dataProperty->AddField("adminuserid", $adminuserid);
        $result = $this->dbOperator->ReturnString($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得adminusergroupname 根据管理组ID adminusergroupid 或是管理员ID adminuserid
     * @param <type> $tableidvalue
     * @param <type> $gettype   为0时根据adminusergroupid 否则根据 adminuserid
     * @return <type> 
     */
    public function GetAdminUserGroupName($tableidvalue, $gettype = 0) {
        $dataProperty = new DataProperty();
        if (intval($gettype) === 0) {
            $sql = "SELECT adminusergroupname FROM " . self::tableName . " WHERE " . self::tableIdName . "=:" . self::tableIdName;
            $dataProperty->AddField(self::tableIdName, $tableidvalue);
        } else {
            $sql = "SELECT adminusergroupname FROM " . self::tableName . " WHERE " . self::tableIdName . " in (SELECT adminusergroupid FROM cst_adminuser WHERE adminuserid=:adminuserid)";
            $dataProperty->AddField("adminuserid", $tableidvalue);
        }
        $result = $this->dbOperator->ReturnString($sql, $dataProperty);
        return $result;
    }
}

?>
