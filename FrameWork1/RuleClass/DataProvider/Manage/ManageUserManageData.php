<?php

/**
 * 后台管理 管理员 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Manage
 * @author zhangchi
 */
class ManageUserManageData extends BaseManageData
{

    /**
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_ManageUser){
        return parent::GetFields(self::TableName_ManageUser);
    }


    /**
     * 管理后台登录
     * @param string $manageUserName 帐号
     * @param string $manageUserPass 密码
     * @return int 后台管理员id
     */
    public function Login($manageUserName, $manageUserPass)
    {
        $dataProperty = new DataProperty();
        $sql = "SELECT ManageUserId FROM " . self::TableName_ManageUser . " WHERE ManageUserName=:ManageUserName AND ManageUserPass=:ManageUserPass AND State<100 AND EndDate>now();";
        $dataProperty->AddField("ManageUserName", $manageUserName);
        $dataProperty->AddField("ManageUserPass", $manageUserPass);
        $result = $this->dbOperator->GetInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 新增
     * @param array $httpPostData $_POST数组
     * @return int 新增的id
     */
    public function Create($httpPostData)
    {
        $result = -1;
        $dataProperty = new DataProperty();
        $addFieldName = "";
        $addFieldValue = "";
        $preNumber = "";
        $addFieldNames = array("CreateDate");
        $addFieldValues = array(date("Y-m-d H:i:s", time()));
        if (!empty($httpPostData)) {
            $sql = parent::GetInsertSql($httpPostData, self::TableName_ManageUser, $dataProperty, $addFieldName, $addFieldValue, $preNumber, $addFieldNames, $addFieldValues);
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 修改
     * @param array $httpPostData $_POST数组
     * @param int $manageUserId id
     * @return int 返回影响的行数
     */
    public function Modify($httpPostData, $manageUserId)
    {
        $result = -1;
        $dataProperty = new DataProperty();
        $addFieldName = "";
        $addFieldValue = "";
        $preNumber = "";
        $addFieldNames = array();
        $addFieldValues = array();

        if (!empty($httpPostData)) {
            $sql = parent::GetUpdateSql($httpPostData, self::TableName_ManageUser, self::TableId_ManageUser, $manageUserId, $dataProperty, $addFieldName, $addFieldValue, $preNumber, $addFieldNames, $addFieldValues);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 删除管理员到回收站
     * @param int $manageUserId 管理员id
     * @return int 返回影响的行数
     */
    public function RemoveToBin($manageUserId)
    {
        $result = -1;
        if ($manageUserId > 0) {
            $sql = "UPDATE " . self::TableName_ManageUser . " SET State=100 WHERE ManageUserId=:ManageUserId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ManageUserId", $manageUserId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }



    /**
     * 修改状态
     * @param int $manageUserId id
     * @param int $state 状态
     * @return int 操作结果
     */
    public function ModifyState($manageUserId, $state)
    {
        $result = 0;
        if ($manageUserId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_ManageUser . " SET `State`=:State WHERE ".self::TableId_ManageUser."=:".self::TableId_ManageUser.";";
            $dataProperty->AddField(self::TableId_ManageUser, $manageUserId);
            $dataProperty->AddField("State", $state);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 根据管理员列表数据集
     * @param int $pageBegin 分页起始位置
     * @param int $pageSize 分页大小
     * @param int $allCount 记录总数（输出参数）
     * @param string $searchKey 查询关键字
     * @param int $searchType 查询字段类型
     * @param int $manageUserGroupId 管理分组id
     * @return array 根据管理员列表数据集
     */
    public function GetList($pageBegin, $pageSize, &$allCount, $searchKey, $searchType, $manageUserGroupId)
    {
        $dataProperty = new DataProperty();
        $searchSql = "";

        //查询
        if (strlen($searchKey) > 0 && $searchKey != "undefined") {
            if ($searchType == 0) { //名称
                $searchSql = " AND (mu.ManageUserName like :SearchKey)";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            } else { //名称
                $searchSql = " AND (mu.ManageUserName like :SearchKey)";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            }
        }

        if($manageUserGroupId>0){

            $searchSql = " AND mu.ManageUserGroupId=:ManageUserGroupId";
            $dataProperty->AddField("ManageUserGroupId", $manageUserGroupId);

        }

        $limit="";
        if($pageBegin>=0&&$pageSize>=0){
            $limit.=" LIMIT " . $pageBegin . "," . $pageSize;
        }

        $sql = "SELECT mu.*,mug.ManageUserGroupName FROM " . self::TableName_ManageUser . " mu,".self::TableName_ManageUserGroup." mug
                        WHERE
                            mu.State<".ManageUserData::STATE_DELETE."
                            AND mu.ManageUserGroupId = mug.ManageUserGroupId

                            $searchSql

                        ORDER BY mu.Sort DESC,convert(mu.ManageUserName USING gbk)
                         ". $limit . " ;";
        $sqlCount = "SELECT Count(*) FROM " . self::TableName_ManageUser . " mu,".self::TableName_ManageUserGroup." mug
                        WHERE
                            mu.State<".ManageUserData::STATE_DELETE."
                            AND mu.ManageUserGroupId = mug.ManageUserGroupId

                            $searchSql;";

        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        $allCount = $this->dbOperator->GetInt($sqlCount, $dataProperty);
        return $result;
    }


    /**
     * 取得后台管理员帐号
     * @param int $manageUserId 管理员id
     * @param bool $withCache 是否从缓冲中取
     * @return string 后台管理员帐号
     */
    public function GetManageUserName($manageUserId, $withCache)
    {
        $result = "";
        if ($manageUserId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'manage_user_data';
            $cacheFile = 'manage_user_get_manage_user_name.cache_' . $manageUserId . '';
            $sql = "SELECT ManageUserName FROM " . self::TableName_ManageUser . " WHERE ManageUserId=:ManageUserId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ManageUserId", $manageUserId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }

        return $result;
    }

    /**
     * 取得后台管理员密码
     * @param int $manageUserId 管理员id
     * @param bool $withCache 是否从缓冲中取
     * @return string 后台管理员密码
     */
    public function GetManageUserPass($manageUserId, $withCache)
    {
        $result = "";
        if ($manageUserId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'manage_user_data';
            $cacheFile = 'manage_user_get_manage_user_pass.cache_' . $manageUserId . '';
            $sql = "SELECT ManageUserPass FROM " . self::TableName_ManageUser . " WHERE ManageUserId=:ManageUserId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ManageUserId", $manageUserId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }

        return $result;
    }

    /**
     * 取得是否允许外网登陆
     * @param int $manageUserId 管理员id
     * @param bool $withCache 是否从缓冲中取
     * @return string 是否允许外网登陆
     */
    public function GetOpenPublicLogin($manageUserId, $withCache)
    {
        $result = -1;
        if ($manageUserId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'manage_user_data';
            $cacheFile = 'manage_user_get_open_public_login.cache_' . $manageUserId . '';
            $sql = "SELECT OpenPublicLogin FROM " . self::TableName_ManageUser . " WHERE ManageUserId=:ManageUserId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ManageUserId", $manageUserId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }

        return $result;
    }

    /**
     * 取得是否开启外网登录口令牌认证
     * @param int $manageUserId 管理员id
     * @param bool $withCache 是否从缓冲中取
     * @return string 是否开启外网登录口令牌认证
     */
    public function GetOtpVerifyLogin($manageUserId, $withCache)
    {
        $result = -1;
        if ($manageUserId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'manage_user_data';
            $cacheFile = 'manage_user_get_otp_verify_login.cache_' . $manageUserId . '';
            $sql = "SELECT OtpVerifyLogin FROM " . self::TableName_ManageUser . " WHERE ManageUserId=:ManageUserId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ManageUserId", $manageUserId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }

        return $result;
    }

    /**
     * 根据管理员帐号取得是否允许外网登陆
     * @param int $manageUserName 管理员帐号
     * @param bool $withCache 是否从缓冲中取
     * @return string 是否允许外网登陆
     */
    public function GetOpenPublicLoginByManageUserName($manageUserName, $withCache)
    {
        $result = -1;
        if (strlen($manageUserName) > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'manage_user_data';
            $cacheFile = 'manage_user_get_open_public_login_by_manage_user_name.cache_' . $manageUserName . '';
            $sql = "SELECT OpenPublicLogin FROM " . self::TableName_ManageUser . " WHERE ManageUserName=:ManageUserName;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ManageUserName", $manageUserName);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }

        return $result;
    }


    /**
     * 根据管理员帐号取得是否开启外网登录口令牌认证
     * @param int $manageUserName 管理员帐号
     * @param bool $withCache 是否从缓冲中取
     * @return string 是否开启外网登录口令牌认证
     */
    public function GetOtpVerifyLoginByManageUserName($manageUserName, $withCache)
    {
        $result = -1;
        if (strlen($manageUserName) > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'manage_user_data';
            $cacheFile = 'manage_user_get_otp_verify_login_by_manage_user_name.cache_' . $manageUserName . '';
            $sql = "SELECT OtpVerifyLogin FROM " . self::TableName_ManageUser . " WHERE ManageUserName=:ManageUserName;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ManageUserName", $manageUserName);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }

        return $result;
    }


    /**
     * 取得管理员对应的会员id
     * @param int $manageUserId 管理员id
     * @param bool $withCache 是否从缓冲中取
     * @return string 管理员对应的会员id
     */
    public function GetUserId($manageUserId, $withCache)
    {
        $result = "";
        if ($manageUserId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'manage_user_data';
            $cacheFile = 'manage_user_get_user_id.cache_' . $manageUserId . '';
            $sql = "SELECT UserId FROM " . self::TableName_ManageUser . " WHERE ManageUserId=:ManageUserId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ManageUserId", $manageUserId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }

        return $result;
    }


    /**
     * 取得管理员对应的会员帐号名
     * @param int $manageUserId 管理员id
     * @param bool $withCache 是否从缓冲中取
     * @return string 管理员对应的会员帐号名
     */
    public function GetUserName($manageUserId, $withCache)
    {
        $result = "";
        if ($manageUserId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'manage_user_data';
            $cacheFile = 'manage_user_get_user_name.cache_' . $manageUserId . '';
            $sql = "SELECT UserName FROM " . self::TableName_ManageUser . " WHERE ManageUserId=:ManageUserId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ManageUserId", $manageUserId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }

        return $result;
    }

    /**
     * 取得管理员分组id
     * @param int $manageUserId 管理员id
     * @param bool $withCache 是否从缓冲中取
     * @return string 管理员分组id
     */
    public function GetManageUserGroupId($manageUserId, $withCache)
    {
        $result = "";
        if ($manageUserId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'manage_user_data';
            $cacheFile = 'manage_user_get_manage_user_group_id.cache_' . $manageUserId . '';
            $sql = "SELECT ManageUserGroupId FROM " . self::TableName_ManageUser . " WHERE ManageUserId=:ManageUserId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ManageUserId", $manageUserId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }

        return $result;
    }

    /**
     * 根据Id取得管理员帐号信息
     * @param int $manageUserId 管理员id
     * @return array 管理员帐号信息数组
     */
    public function GetOne($manageUserId)
    {
        $result = null;
        if ($manageUserId > 0) {
            $sql = "SELECT * FROM " . self::TableName_ManageUser . " WHERE " . self::TableId_ManageUser . "=:" . self::TableId_ManageUser . "";
            $dataProperty = new DataProperty();
            $dataProperty->AddField(self::TableId_ManageUser, $manageUserId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
        }

        return $result;
    }

    /**
     * 更新令牌成功值和漂移值
     * @param int $manageUserId 管理员id
     * @param string $otpCurrentSuccess 成功值
     * @param string $otpCurrentDrift 漂移值
     * @return int 执行结果
     */
    public function ModifyForOtp($manageUserId, $otpCurrentSuccess, $otpCurrentDrift)
    {
        $result = -1;
        if ($manageUserId > 0) {
            $sql = "UPDATE " . self::TableName_ManageUser . " SET OtpCurrentSuccess=:OtpCurrentSuccess,OtpCurrentDrift=:OtpCurrentDrift WHERE " . self::TableId_ManageUser . "=:" . self::TableId_ManageUser . ";";
            $dataProperty = new DataProperty();
            $dataProperty->AddField(self::TableId_ManageUser, $manageUserId);
            $dataProperty->AddField("OtpCurrentSuccess", $otpCurrentSuccess);
            $dataProperty->AddField("OtpCurrentDrift", $otpCurrentDrift);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }
    /**
     * 管理后台修改密码
     * @param string $manageUserId 账号ID
     * @param string $manageUserPass 密码
     * @return int 后台管理员id
     */
    public function ModifyManageUserPass($manageUserId, $manageUserPass)
    {
        $result = -1;
        if ($manageUserId > 0) {
            $sql = "UPDATE " . self::TableName_ManageUser . " SET ManageUserPass=:ManageUserPass   WHERE " . self::TableId_ManageUser . "=:" . self::TableId_ManageUser . ";";
            $dataProperty = new DataProperty();
            $dataProperty->AddField(self::TableId_ManageUser, $manageUserId);
            $dataProperty->AddField("ManageUserPass", $manageUserPass);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }
}

?>
