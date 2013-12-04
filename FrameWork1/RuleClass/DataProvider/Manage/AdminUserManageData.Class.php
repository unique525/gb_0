<?php

/**
 * 后台管理员后台数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Manage
 * @author zhangchi
 */
class AdminUserManageData extends BaseManageData {

    /**
     * 管理后台登录
     * @param string $adminUserName 帐号
     * @param string $adminUserPass 密码
     * @return int 后台管理员id
     */
    public function Login($adminUserName, $adminUserPass) {
        $dataProperty = new DataProperty();
        $sql = "SELECT " . self::TableId_AdminUser . " FROM " . self::TableName_AdminUser . " WHERE AdminUserName=:AdminUserName AND AdminUserPass=:AdminUserPass AND State<100 AND EndDate>now()";
        $dataProperty->AddField("AdminUserName", $adminUserName);
        $dataProperty->AddField("AdminUserPass", $adminUserPass);
        $result = $this->dbOperator->ReturnInt($sql, $dataProperty);
        return $result;
    }

    public function Create() {
        $dataProperty = new DataProperty();
        $sql = parent::GetInsertSql(self::TableName_AdminUser, $dataProperty);
        $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        return $result;
    }

    public function Modify($adminUserId) {
        $dataProperty = new DataProperty();
        $sql = parent::GetUpdateSql(self::TableName_AdminUser, self::tableIdName, $adminUserId, $dataProperty);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    public function RemoveBin($adminUserId) {
        $sql = "UPDATE " . self::TableName_AdminUser . " SET State=100 WHERE " . self::TableId_AdminUser . "=:" . self::TableId_AdminUser . "";
        $dataProperty = new DataProperty();
        $dataProperty->AddField(self::TableId_AdminUser, $adminUserId);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 根据type修改密码
     * @param <type> $userPass
     * @param <type> $tablevalue
     * @param <type> $type 为0时根据adminuserid修改 1根据adminusername修改
     */
    public function ModifyAdminUserPassByAdminUserName($userPass, $tablevalue, $type = 0) {
        $dataProperty = new DataProperty();
        if (intval($type) === 0) {
            $sql = "UPDATE " . self::TableName_AdminUser . " SET adminuserpass=:adminuserpass WHERE adminuserid=:adminuserid";
            $dataProperty->AddField("adminuserid", $tablevalue);
        } elseif (intval($type === 1)) {
            $sql .= "UPDATE " . self::TableName_AdminUser . " SET adminuserpass=:adminuserpass WHERE adminusername=:adminusername";
            $dataProperty->AddField("adminusername", $tablevalue);
        }
        $dataProperty->AddField("adminuserpass", $userPass);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 修改邮件
     * @param string $email
     * @param int $tableValue
     * @return int 返回执行结果集
     */
    public function UpdateAdminUserEmail($email, $tableValue) {
        $dataProperty = new DataProperty();
        $sql = "UPDATE " . self::TableName_AdminUser . " SET email=:email WHERE adminuserid=:adminuserid";
        $dataProperty->AddField("adminuserid", $tableValue);
        $dataProperty->AddField("email", $email);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 根据管理员id取得密码
     * @param int $adminUserId 管理员id
     * @return string 管理员密码
     */
    public function GetAdminUserPass($adminUserId) {
        $dataProperty = new DataProperty();
        $sql = "SELECT adminuserpass FROM " . self::TableName_AdminUser . " WHERE adminuserid=:adminuserid";
        $dataProperty->AddField("adminuserid", $adminUserId);
        $result = $this->dbOperator->ReturnInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 后台管理员列表管理页面
     * @param <type> $pagebegin
     * @param <type> $pagesize
     * @param <type> $allcount
     * @param <type> $gruopid
     * @param <type> $searchkey
     * @return <type> 
     */
    public function GetListPager($pagebegin, $pagesize, &$allcount, $gruopid = 0, $searchkey = "") {
        $dataProperty = new DataProperty();

        $searchsql = "";
        if (strlen($searchkey) > 0 && $searchkey != "undefined") {
            $searchsql .= " AND (u.adminuserid like :searchkey1 OR u.adminusername like :searchkey2)";
            $dataProperty->AddField("searchkey1", "%" . $searchkey . "%");
            $dataProperty->AddField("searchkey2", "%" . $searchkey . "%");
        }
        if ($gruopid > 0) {
            $searchsql .= " AND u.adminusergroupid=:adminusergroupid ";
            $dataProperty->AddField("adminusergroupid", $gruopid);
        }

        $sql = "SELECT u.adminuserid, u.adminusername, g.adminusergroupname, u.state, u.createdate, u.enddate, u.adminusergroupid FROM " . self::tableName . " u, cst_adminusergroup g WHERE u.adminusergroupid=g.adminusergroupid " . $searchsql . " ORDER BY u.sort DESC, u.createdate DESC LIMIT " . $pagebegin . "," . $pagesize . "";
        $result = $this->dbOperator->ReturnArray($sql, $dataProperty);
        //统计总数
        $sql = "";
        $sql = "SELECT count(*) FROM " . self::TableName_AdminUser . " u WHERE u." . self::tableIdName . " >0 " . $searchsql;
        $allcount = $this->dbOperator->ReturnInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 根据adminuserid 取得 adminusername
     * @param <type> $adminuserid
     * @return <type>
     */
    public function GetAdminUserName($adminuserid) {
        $sql = "SELECT adminusername FROM " . self::TableName_AdminUser . " WHERE adminuserid=:adminuserid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("adminuserid", $adminuserid);
        $result = $this->dbOperator->ReturnString($sql, $dataProperty);
        return $result;
    }

    /**
     * 取用户邮件 
     * @param int $adminUserId
     * @return string 返回查询到的用户邮件
     */
    public function GetAdminUserEmail($adminUserId) {
        $sql = "SELECT email FROM " . self::TableName_AdminUser . " WHERE adminuserid=:adminuserid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("adminuserid", $adminUserId);
        $result = $this->dbOperator->ReturnString($sql, $dataProperty);
        return $result;
    }

    /**
     * 根据帐号取得管理员是否允许外网登陆
     * @param <type> $adminusername
     * @return <type>
     */
    public function GetOpenEnLogin($adminusername) {
        $sql = "SELECT OpenEnLogin FROM " . self::TableName_AdminUser . " WHERE adminusername=:adminusername";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("adminusername", $adminusername);
        $result = $this->dbOperator->ReturnInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 根据帐号取得管理员是否要开启外网登录短信认证
     * @param <type> $adminusername
     * @return <type>
     */
    public function GetOpenSmsLogin($adminusername) {
        $sql = "SELECT OpenSmsLogin FROM " . self::TableName_AdminUser . " WHERE adminusername=:adminusername";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("adminusername", $adminusername);
        $result = $this->dbOperator->ReturnInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 根据帐号取得管理员是否要开启外网登录口令牌认证
     * @param <type> $adminusername
     * @return <type>
     */
    public function GetOpenOtpLogin($adminusername) {
        $sql = "SELECT OtpVerifyLogin FROM " . self::TableName_AdminUser . " WHERE adminusername=:adminusername";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("adminusername", $adminusername);
        $result = $this->dbOperator->ReturnInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得管理员手机号码
     * @param <type> $adminuserid
     * @return <type>
     */
    public function GetMobile($adminuserid) {
        $sql = "SELECT mobile FROM " . self::TableName_AdminUser . " WHERE adminuserid=:adminuserid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("adminuserid", $adminuserid);
        $result = $this->dbOperator->ReturnString($sql, $dataProperty);
        return $result;
    }

    /**
     * 查询所有管理员
     * @param <type> $state
     * @return <type>
     */
    public function GetAdminUserNameList($state) {
        if ($state >= 0) {
            $sql = "SELECT adminuserid,adminusername FROM " . self::TableName_AdminUser . " WHERE state=:state";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("state", $state);
            $result = $this->dbOperator->ReturnArray($sql, $dataProperty);
        } else {
            $sql = "SELECT adminuserid,adminusername FROM " . self::TableName_AdminUser . "";
            $result = $this->dbOperator->ReturnArray($sql, null);
        }
        return $result;
    }

    /**
     * 相关会员用户ID
     * @param <type> $adminuserid
     * @return <type>
     */
    public function GetUserId($adminuserid) {
        $sql = "SELECT userid FROM " . self::TableName_AdminUser . " WHERE adminuserid=:adminuserid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("adminuserid", $adminuserid);
        $result = $this->dbOperator->ReturnString($sql, $dataProperty);
        return $result;
    }

    public function GetUserName($adminuserid) {
        $sql = "SELECT UserName FROM " . self::TableName_AdminUser . " WHERE adminuserid=:adminuserid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("adminuserid", $adminuserid);
        $result = $this->dbOperator->ReturnString($sql, $dataProperty);
        return $result;
    }

    /**
     * 根据adminusergroupid及state取得用户列表
     * @param <type> $adminusergroupid
     * @param <type> $state
     * @return <type>
     */
    public function GetAdminUserList($adminusergroupid = 0, $adminusergroupids = null, $countadminuserid = 0, $state = 0) {

        $searchsql = "";
        $dataProperty = new DataProperty();
        if ($adminusergroupid > 0) {
            $searchsql .= " AND adminusergroupid=:adminusergroupid ";
            $dataProperty->AddField("adminusergroupid", $adminusergroupid);
        } else {
            if (!empty($adminusergroupids)) {
                $searchsql .= " AND adminusergroupid in (";
                $i = 0;
                for (; $i < (count($adminusergroupids) - 1); $i++) {
                    $searchsql .= $adminusergroupids[$i] . ", ";
                }
                $searchsql .= $adminusergroupids[$i] . ") ";
            }
        }
        if ($countadminuserid > 0) {
            $searchsql .= " AND adminuserid=:adminuserid ";
            $dataProperty->AddField("adminuserid", $countadminuserid);
        }
        if ($state >= 0) {
            $searchsql .= " AND state=:state ";
            $dataProperty->AddField("state", $state);
        }
        $sql = "SELECT adminuserid,adminusername,userid,username FROM " . self::TableName_AdminUser . " WHERE " . self::TableId_AdminUser . ">0 " . $searchsql . " order by adminuserid desc";
        $result = $this->dbOperator->ReturnArray($sql, $dataProperty);
        return $result;
    }

    /**
     * 根据adminuserid取得adminusergroupid
     * @param type $tablevalue
     * @return type 
     */
    public function GetAdminUserGroupId($tablevalue) {
        $dataProperty = new DataProperty();
        $sql = "select adminusergroupid from " . self::TableName_AdminUser . " where " . self::TableId_AdminUser . "=:" . self::TableId_AdminUser;
        $dataProperty->AddField(self::tableIdName, $tablevalue);
        $result = $this->dbOperator->ReturnInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 根据$adminUserId取得管理员帐号信息
     * @param int $adminUserId 管理员id
     * @return array 管理员帐号信息数组
     */
    public function GetOne($adminUserId) {
        $sql = "SELECT * FROM " . self::TableName_AdminUser . " WHERE " . self::TableId_AdminUser . "=:AdminUserId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("AdminUserId", $adminUserId);
        $result = $this->dbOperator->ReturnRow($sql, $dataProperty);
        return $result;
    }

    /**
     * 登陆成功则更新令牌成功值和漂移值
     * @param type $tablevalue
     * @return type
     */
    public function UpdateOtpValue($adminuserid, $otpcurrsucc, $otpcurrdft) {
        $sql = "UPDATE " . self::TableName_AdminUser . " SET otpcurrsucc=:otpcurrsucc,otpcurrdft=:otpcurrdft where " . self::TableId_AdminUser . "=:id";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("id", $adminuserid);
        $dataProperty->AddField("otpcurrsucc", $otpcurrsucc);
        $dataProperty->AddField("otpcurrdft", $otpcurrdft);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

}

?>
