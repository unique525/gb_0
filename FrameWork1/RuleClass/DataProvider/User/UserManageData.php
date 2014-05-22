<?php

/**
 * 后台管理 会员 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author zhangchi
 */
class UserManageData extends BaseManageData {

    /**
     * 取得后台会员列表数据集
     * @param int $siteId 站点id
     * @param int $pageBegin 起始记录行
     * @param int $pageSize 页大小
     * @param int $allCount 记录总数
     * @param string $searchKey 查询字符
     * @param int $searchType 查询下拉框的类别
     * @param int $manageUserId 后台管理员id
     * @return array 会员列表数据集
     */
    public function GetList($siteId, $pageBegin, $pageSize, &$allCount, $searchKey = "", $searchType = 0, $manageUserId = 0)
    {
        $searchSql = "";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("SiteId", $siteId);
        if (strlen($searchKey) > 0 && $searchKey != "undefined") {
            if ($searchType == 0) { //会员名
                $searchSql = " AND (u.UserName like :SearchKey)";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            }
        }

        $sql = "
            SELECT
            u.*,
            ui.Avatar
            FROM
            " . self::TableName_User . " u,". self::TableName_UserInfo ." ui
            WHERE u.UserId=ui.UserId AND u.SiteId=:SiteId " . $searchSql . " ORDER BY u.CreateDate DESC LIMIT " . $pageBegin . "," . $pageSize . ";";

        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

        $sql = "SELECT count(*) FROM " . self::TableName_User . " u,". self::TableName_UserInfo ." ui WHERE u.UserId=ui.UserId AND u.SiteId=:SiteId " . $searchSql . ";";
        $allCount = $this->dbOperator->GetInt($sql, $dataProperty);

        return $result;
    }

    /**
     * 修改会员
     * @param array $httpPostData $_POST数组
     * @param int $userId 会员id
     * @return int 返回影响的行数
     */
    public function Modify($httpPostData, $userId)
    {
        $result = -1;
        if($userId >0){
            $dataProperty = new DataProperty();
            $sql = parent::GetUpdateSql($httpPostData, self::TableName_ManageUser, self::TableId_ManageUser, $userId, $dataProperty);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 编辑会员时，检查同名帐号是否存在
     * @param string $userName 会员名称
     * @param int $userId 会员id
     * @return array 会员列表数据集
     */
    public function CheckExistNameForModify($userName, $userId)
    {
        $sql = "SELECT Count(*) FROM  " . self::TableName_User . " WHERE UserName=:UserName AND UserId<>:UserId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("UserName", $userName);
        $dataProperty->AddField("UserId", $userId);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->ReturnInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 根据userid取得username
     * @param int $userId 会员id
     * @return string 返回会员名称
     */
    public function GetUserName($userId)
    {
        if ($userId > 0) {
            $dataProperty = new DataProperty();
            $sql = "SELECT username FROM " . self::TableName_User . " WHERE userid=:userid";
            $dataProperty->AddField("userid", $userId);
            $dboperator = DBOperator::getInstance();
            $result = $dboperator->ReturnString($sql, $dataProperty);
            return $result;
        }
    }

    /**
     * 根据userid得到一行信息信息
     * @param int $userId 会员id
     * @return array 会员列表数据集
     */
    public function GetRow($userId)
    {
        $dataProperty = new DataProperty();
        $sql = "SELECT userid,username,uid,userarea,siteid,userpass,state,regip,createdate,parentid FROM " . self::TableName_User . " WHERE userid=:userid";
        $dataProperty->AddField("userid", $userId);
        $dboperator = DBOperator::getInstance();
        $result = $dboperator->ReturnRow($sql, $dataProperty);
        return $result;
    }
} 