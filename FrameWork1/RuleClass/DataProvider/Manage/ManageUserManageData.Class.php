<?php

/**
 * 后台管理员后台数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Manage
 * @author zhangchi
 */
class ManageUserManageData extends BaseManageData {
    /**
     * 表名
     */
    const tableName = "cst_adminuser";

    /**
     * 表关键字段名
     */
    const tableIdName = "adminuserid";

    /**
     * 管理后台登录
     * @param string $adminUserName 帐号
     * @param string $adminUserPass 密码
     * @return int 后台管理员id
     */
    public function Login($adminUserName, $adminUserPass) {
        $dataProperty = new DataProperty();
        $sql = "SELECT adminuserid FROM " . self::tableName . " WHERE AdminUserName=:AdminUserName AND AdminUserPass=:AdminUserPass AND State<100 AND EndDate>now()";
        $dataProperty->AddField("AdminUserName", $adminUserName);
        $dataProperty->AddField("AdminUserPass", $adminUserPass);
        $result = $this->dbOperator->GetInt($sql, $dataProperty);
        return $result;
    }

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

    public function RemoveBin($uid) {
        $sql = "update " . self::tableName . " set state=100 where adminuserid=:adminuserid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("adminuserid", $uid);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 根据type修改密码
     * @param <type> $pass
     * @param <type> $tablevalue
     * @param <type> $type 为0时根据adminuserid修改 1根据adminusername修改
     */
    public function UpdateAdminUserPass($pass, $tablevalue, $type = 0) {
        $dataProperty = new DataProperty();
        if (intval($type) === 0) {
            $sql = "UPDATE " . self::tableName . " SET adminuserpass=:adminuserpass WHERE adminuserid=:adminuserid";
            $dataProperty->AddField("adminuserid", $tablevalue);
        } elseif (intval($type === 1)) {
            $sql .= "UPDATE " . self::tableName . " SET adminuserpass=:adminuserpass WHERE adminusername=:adminusername";
            $dataProperty->AddField("adminusername", $tablevalue);
        }
        $dataProperty->AddField("adminuserpass", $pass);
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
        $sql = "UPDATE " . self::tableName . " SET email=:email WHERE adminuserid=:adminuserid";
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
        $sql = "SELECT adminuserpass FROM " . self::tableName . " WHERE adminuserid=:adminuserid";
        $dataProperty->AddField("adminuserid", $adminUserId);
        $result = $this->dbOperator->GetInt($sql, $dataProperty);
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
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        //统计总数
        $sql = "";
        $sql = "SELECT count(*) FROM " . self::tableName . " u WHERE u." . self::tableIdName . " >0 " . $searchsql;
        $allcount = $this->dbOperator->GetInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 根据adminuserid 取得 adminusername
     * @param <type> $adminuserid
     * @return <type>
     */
    public function GetAdminUserName($adminuserid) {
        $sql = "SELECT adminusername FROM " . self::tableName . " WHERE adminuserid=:adminuserid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("adminuserid", $adminuserid);
        $result = $this->dbOperator->GetString($sql, $dataProperty);
        return $result;
    }

    /**
     * 取用户邮件 
     * @param int $adminUserId
     * @return string 返回查询到的用户邮件
     */
    public function GetAdminUserEmail($adminUserId) {
        $sql = "SELECT email FROM " . self::tableName . " WHERE adminuserid=:adminuserid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("adminuserid", $adminUserId);
        $result = $this->dbOperator->GetString($sql, $dataProperty);
        return $result;
    }

    /**
     * 根据帐号取得管理员是否允许外网登陆
     * @param <type> $adminusername
     * @return <type>
     */
    public function GetOpenEnLogin($adminusername) {
        $sql = "SELECT OpenEnLogin FROM " . self::tableName . " WHERE adminusername=:adminusername";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("adminusername", $adminusername);
        $result = $this->dbOperator->GetInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 根据帐号取得管理员是否要开启外网登录短信认证
     * @param <type> $adminusername
     * @return <type>
     */
    public function GetOpenSmsLogin($adminusername) {
        $sql = "SELECT OpenSmsLogin FROM " . self::tableName . " WHERE adminusername=:adminusername";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("adminusername", $adminusername);
        $result = $this->dbOperator->GetInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 根据帐号取得管理员是否要开启外网登录口令牌认证
     * @param <type> $adminusername
     * @return <type>
     */
    public function GetOpenOtpLogin($adminusername) {
        $sql = "SELECT OtpVerifyLogin FROM " . self::tableName . " WHERE adminusername=:adminusername";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("adminusername", $adminusername);
        $result = $this->dbOperator->GetInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得管理员手机号码
     * @param <type> $adminuserid
     * @return <type>
     */
    public function GetMobile($adminuserid) {
        $sql = "SELECT mobile FROM " . self::tableName . " WHERE adminuserid=:adminuserid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("adminuserid", $adminuserid);
        $result = $this->dbOperator->GetString($sql, $dataProperty);
        return $result;
    }

    /**
     * 查询所有管理员
     * @param <type> $state
     * @return <type>
     */
    public function GetAdminUserNameList($state) {
        if ($state >= 0) {
            $sql = "SELECT adminuserid,adminusername FROM " . self::tableName . " WHERE state=:state";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("state", $state);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        } else {
            $sql = "SELECT adminuserid,adminusername FROM " . self::tableName . "";
            $result = $this->dbOperator->GetArrayList($sql, null);
        }
        return $result;
    }

    /**
     * 相关会员用户ID
     * @param <type> $adminuserid
     * @return <type>
     */
    public function GetUserId($adminuserid) {
        $sql = "SELECT userid FROM " . self::tableName . " WHERE adminuserid=:adminuserid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("adminuserid", $adminuserid);
        $result = $this->dbOperator->GetString($sql, $dataProperty);
        return $result;
    }

    public function GetUserName($adminuserid) {
        $sql = "SELECT UserName FROM " . self::tableName . " WHERE adminuserid=:adminuserid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("adminuserid", $adminuserid);
        $result = $this->dbOperator->GetString($sql, $dataProperty);
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
        $sql = "SELECT adminuserid,adminusername,userid,username FROM " . self::tableName . " WHERE " . self::tableIdName . ">0 " . $searchsql . " order by adminuserid desc";
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        return $result;
    }

    /**
     * 根据adminuserid取得adminusergroupid
     * @param type $tablevalue
     * @return type 
     */
    public function GetAdminUserGroupId($tablevalue) {
        $dataProperty = new DataProperty();
        $sql = "select adminusergroupid from " . self::tableName . " where " . self::tableIdName . "=:" . self::tableIdName;
        $dataProperty->AddField(self::tableIdName, $tablevalue);
        $result = $this->dbOperator->GetInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 根据$adminUserId取得管理员帐号信息
     * @param int $adminUserId 管理员id
     * @return array 管理员帐号信息数组
     */
    public function GetOne($adminUserId) {
        $sql = "SELECT * FROM " . self::tableName . " WHERE " . self::tableIdName . "=:AdminUserId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("AdminUserId", $adminUserId);
        $result = $this->dbOperator->GetRow($sql, $dataProperty);
        return $result;
    }

    /**
     * 登陆成功则更新令牌成功值和漂移值
     * @param type $tablevalue
     * @return type
     */
    public function UpdateOtpValue($adminuserid, $otpcurrsucc, $otpcurrdft) {
        $sql = "UPDATE " . self::tableName . " SET otpcurrsucc=:otpcurrsucc,otpcurrdft=:otpcurrdft where " . self::tableIdName . "=:id";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("id", $adminuserid);
        $dataProperty->AddField("otpcurrsucc", $otpcurrsucc);
        $dataProperty->AddField("otpcurrdft", $otpcurrdft);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

}

?>
