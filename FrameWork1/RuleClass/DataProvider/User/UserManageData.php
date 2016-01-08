<?php

/**
 * 后台管理 会员 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author zhangchi
 */
class UserManageData extends BaseManageData
{

    public function GetFields($tableName = self::TableName_User){
        return parent::GetFields(self::TableName_User);
    }

    public function Create($httpPostData,$siteId){
        $result = -1;
        if(!empty($httpPostData) && $siteId > 0){
            $dataProperty = new DataProperty();
            $sql = parent::GetInsertSql($httpPostData,self::TableName_User,$dataProperty,"SiteId",$siteId);
            $result = $this->dbOperator->LastInsertId($sql,$dataProperty);
        }
        return $result;
    }

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
            if ($searchType == 1) { //会员名, 手机号, 邮箱地址
                $searchSql = " AND ((u.UserName like :SearchKey1)";
                $dataProperty->AddField("SearchKey1", "%" . $searchKey . "%");
                $searchSql .= " OR (u.UserMobile like :SearchKey2)";
                $dataProperty->AddField("SearchKey2", "%" . $searchKey . "%");
                $searchSql .= " OR (u.UserEmail like :SearchKey3))";
                $dataProperty->AddField("SearchKey3", "%" . $searchKey . "%");
            }elseif ($searchType == 2) { //IP
                $searchSql = " AND (u.RegIp like :SearchKey)";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            }
        }

        $sql = "
            SELECT
            u.*,
            uf.UploadFilePath
            FROM
            " . self::TableName_User . " u," . self::TableName_UserInfo . " ui LEFT JOIN ".self::TableName_UploadFile." uf ON ui.AvatarUploadFileId = uf.UploadFileId
            WHERE u.UserId=ui.UserId AND u.SiteId=:SiteId " . $searchSql . " ORDER BY u.CreateDate DESC LIMIT " . $pageBegin . "," . $pageSize . ";";
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

        $sql = "SELECT count(*) FROM " . self::TableName_User . " u," . self::TableName_UserInfo . " ui WHERE u.UserId=ui.UserId AND u.SiteId=:SiteId " . $searchSql . ";";
        $allCount = $this->dbOperator->GetInt($sql, $dataProperty);

        return $result;
    }

    /**
     * 根据会员id修改会员
     * @param array $httpPostData $_POST数组
     * @param int $userId 会员id
     * @return int 返回影响的行数
     */
    public function Modify($httpPostData, $userId)
    {
        $result = -1;
        if ($userId > 0) {
            if (!empty($httpPostData)) {
                $dataProperty = new DataProperty();
                $sql = parent::GetUpdateSql($httpPostData, self::TableName_User, self::TableId_User, $userId, $dataProperty);
                $result = $this->dbOperator->Execute($sql, $dataProperty);
            }
        }
        return $result;
    }

    /**
     * 修改会员密码（包括MD5加密字段）
     * @param int $userId 会员id
     * @param string $userPass 新的会员密码
     * @return int 操作结果
     */
    public function ModifyUserPass($userId, $userPass)
    {
        $result = 0;
        if ($userId > 0) {

            $userPassWithMd5 = md5($userPass);

            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_User . "
                    SET
                    `UserPass`=:UserPass,
                    `UserPassWithMd5`=:UserPassWithMd5
                    WHERE ".self::TableId_User."=:".self::TableId_User.";";
            $dataProperty->AddField(self::TableId_User, $userId);
            $dataProperty->AddField("UserPass", $userPass);
            $dataProperty->AddField("UserPassWithMd5", $userPassWithMd5);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    public function CheckSameUserName($newUserName){
        $result = -1;
        if(isset($newUserName)){
            $sql = "SELECT count(*) FROM ".self::TableName_User." WHERE UserName = :UserName;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserName",$newUserName);
            $result = $this->dbOperator->GetInt($sql,$dataProperty);
        }
        return $result;
    }

    /**
     * 编辑会员时，检查同名帐号是否存在
     * @param string $userName 会员名称
     * @param int $userId 会员id
     * @return int 返回统计数据
     */
    public function GetCountByUserNameNotNowUserId($userName, $userId)
    {
        $result = null;
        if ($userId > 0) {
            $sql = "SELECT Count(*) FROM  " . self::TableName_User . " WHERE UserName=:UserName AND UserId<>:UserId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserName", $userName);
            $dataProperty->AddField("UserId", $userId);
            $result = $this->dbOperator->GetInt($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 根据会员id取得会员帐号
     * @param int $userId 会员id
     * @param bool $withCache 是否从缓冲中取
     * @return string 返回会员名称
     */
    public function GetUserName($userId, $withCache)
    {
        $result = null;
        if ($userId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'user_data';
            $cacheFile = 'user_get_user_name.cache_' . $userId . '';
            $sql = "SELECT UserName FROM " . self::TableName_User . " WHERE UserId=:UserId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId", $userId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 根据userId得到一行信息信息
     * @param int $userId 会员id
     * @return array 会员信息列表数据集
     */
    public function GetOne($userId)
    {
        $result = null;
        if ($userId > 0) {
            $sql = "SELECT * FROM " . self::TableName_User . " WHERE UserId=:UserId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId", $userId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 查找是否已存在用户名、手机号、邮箱号
     * @param string $searchKey 搜索字段
     * @return  int 会员信息列表数据集
     */
    public function CheckRepeat($searchKey)
    {
        $result = -1;
        if ($searchKey != ""&&$searchKey!=null) {
            $sql = "SELECT UserId FROM " . self::TableName_User . " WHERE UserName=:UserName OR UserMobile=:UserMobile OR UserEmail=:UserEmail FOR UPDATE;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserName", $searchKey);
            $dataProperty->AddField("UserMobile", $searchKey);
            $dataProperty->AddField("UserEmail", $searchKey);
            $result = $this->dbOperator->Getint($sql, $dataProperty);
        }
        return $result;
    }

} 