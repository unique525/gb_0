<?php

/**
 * 公共访问 会员 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author yin
 */
class UserPublicData extends BasePublicData {

    /**
     * 混合登录
     * @param string $userAccount 会员登录帐号，可以是会员名，会员邮箱，会员手机号码
     * @param string $userPass 会员密码
     * @return int 返回userId
     */
    public function Login($userAccount,$userPass){
        $result = -1;
        $userPassWithMd5 = $userPass;
        $userPassWithMd5 = md5($userPassWithMd5);
        $userPassWithMd5 = str_ireplace('0', '', $userPassWithMd5);
        if(strlen($userPassWithMd5)>20){
            $userPassWithMd5 = substr($userPassWithMd5,0,20);
        }


        if(!empty($userAccount) && (!empty($userPass) || !empty($UserPassWithMd5))){
            $sql = "SELECT UserId FROM ".self::TableName_User."
                        WHERE (UserName = :UserName OR UserEmail = :UserEmail OR UserMobile = :UserMobile)
                            AND (UserPass = :UserPass OR left(REPLACE(UserPassWithMd5, '0', ''),20) = :UserPassWithMd5)
                            AND State=0;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserName",$userAccount);
            $dataProperty->AddField("UserEmail",$userAccount);
            $dataProperty->AddField("UserMobile",$userAccount);
            $dataProperty->AddField("UserPass",$userPass);
            $dataProperty->AddField("UserPassWithMd5",$userPassWithMd5);
            $result = $this->dbOperator->GetInt($sql,$dataProperty);
        }
        return $result;
    }

    public function Create($siteId,$userPass,$regIp,$userName="",$userEmail="",$userMobile=""){
        $result = -1;
        if($siteId > 0
            && (!empty($userName) || !empty($userEmail) || !empty($userMobile)) && !empty($userPass)
            && !empty($regIp)
        ){
            $dataProperty = new DataProperty();
            $sql = "INSERT INTO ".self::TableName_User." (UserName,UserEmail,UserMobile,UserPass,SiteId,CreateDate,RegIp)
                VALUES (:UserName,:UserEmail,:UserMobile,:UserPass,:SiteId,now(),:RegIp);";
            $dataProperty->AddField("UserName",$userName);
            $dataProperty->AddField("UserEmail",$userEmail);
            $dataProperty->AddField("UserMobile",$userMobile);
            $dataProperty->AddField("UserPass",$userPass);
            $dataProperty->AddField("SiteId",$siteId);
            $dataProperty->AddField("RegIp",$regIp);
            $result = $this->dbOperator->LastInsertId($sql,$dataProperty);
        }
        return $result;
    }


    /**
     * 检查相同的用户名
     * @param string $userName 用户名
     * @return int 查询结果
     */
    public function CheckRepeatUserName($userName){
        $result = -1;
        if(!empty($userName)){
            $sql = "SELECT count(*) FROM ".self::TableName_User." WHERE UserName = :UserName;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserName",$userName);
            $result = $this->dbOperator->GetInt($sql,$dataProperty);
        }
        return $result;
    }

    /**
     * 检查相同的伊妹儿
     * @param string $userEmail 用户登录的伊妹儿
     * @return int 查询结果
     */
    public function CheckRepeatUserEmail($userEmail){
        $result = -1;
        if(!empty($userEmail)){
            $sql = "SELECT count(*) FROM ".self::TableName_User." WHERE UserEmail = :UserEmail;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserEmail",$userEmail);
            $result = $this->dbOperator->GetInt($sql,$dataProperty);
        }
        return $result;
    }

    /**
     * 检查相同的手机号码
     * @param string $userMobile 用户登录的手机号码
     * @return int 查询结果
     */
    public function CheckRepeatUserMobile($userMobile){
        $result = -1;
        if(!empty($userMobile)){
            $sql = "SELECT count(*) FROM ".self::TableName_User." WHERE UserMobile = :UserMobile;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserMobile",$userMobile);
            $result = $this->dbOperator->GetInt($sql,$dataProperty);
        }
        return $result;
    }



    public function ModifyUserMobile($userId,$userMobile){
        $result = -1;
        if($userId > 0){
            $sql = "UPDATE ".self::TableName_User." SET UserMobile = :UserMobile
            WHERE UserId = :UserId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId",$userId);
            $dataProperty->AddField("UserMobile",$userMobile);
            $result = $this->dbOperator->Execute($sql,$dataProperty);
        }
        return $result;
    }

    /**
     * 取得会员帐号
     * @param int $userId 会员id
     * @param bool $withCache 是否从缓冲中取
     * @return string 会员帐号
     */
    public function GetUserName($userId, $withCache)
    {
        $result = "";
        if ($userId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'user_data'
                . DIRECTORY_SEPARATOR .$userId;
            $cacheFile = 'user_get_user_name.cache_' . $userId . '';
            $sql = "SELECT UserName FROM " . self::TableName_User . " WHERE UserId=:UserId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId", $userId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 取得会员手机帐号
     * @param int $userId 会员id
     * @param bool $withCache 是否从缓冲中取
     * @return string 会员手机帐号
     */
    public function GetUserMobile($userId, $withCache)
    {
        $result = "";
        if ($userId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'user_data'
                . DIRECTORY_SEPARATOR .$userId;
            $cacheFile = 'user_get_user_mobile.cache_' . $userId . '';
            $sql = "SELECT UserMobile FROM " . self::TableName_User . " WHERE UserId=:UserId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId", $userId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 检测用户密码
     * @param int $userId
     * @param string $userPass
     * @return int 返回检测结果
     */
    public function CheckPassWord($userId, $userPass)
    {
        $result = -1;
        if ($userId > 0) {
            $userPassMd5 = md5($userPass);
            $dataProperty = new DataProperty();
            $sql = "SELECT Count(*) FROM " . self::TableName_User . " WHERE (userPass=:userPass OR left(REPLACE(UserPassWithMd5, '0', ''),20) = :UserPassWithMd5) AND userId=:userId";
            $dataProperty->AddField("userId", $userId);
            $dataProperty->AddField("userPass", $userPass);
            $dataProperty->AddField('UserPassWithMd5', $userPassMd5);
            $result = $this->GetInfoOfIntValue($sql,$dataProperty,false,"","");
        }
        return $result;
    }

    /**
     * 修改用户密码
     * @param int $userId
     * @param string $userPass
     * @return int 返回修改结果
     */
    public function ModifyPassword($userId, $userPass)
    {
        $result = -1;
        $sql = "update " . self::TableName_User . " set userPass = :userPass where userId = :userId";
        if($userId > 0){
            $dataProperty = new DataProperty();
            $dataProperty->AddField("userPass", $userPass);
            $dataProperty->AddField("userId", $userId);
            $result = $this->dbOperator->Execute($sql,$dataProperty);
        }
        return $result;
    }

    /**
     * 取得会员组
     * @param int $userId
     * @param bool $withCache
     * @return int 返回结果
     */
    public function GetUserGroupId($userId, $withCache)
    {
        $result = -1;
        if($userId > 0){
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'user_data'
                . DIRECTORY_SEPARATOR .$userId;
            $cacheFile = 'user_get_user_group_id.cache_' . $userId . '';
            $sql = "SELECT UserGroupId FROM " . self::TableName_User . " where userId = :userId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("userId", $userId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 通过会员账号取得会员信息
     * @param int $userAccount 会员名称
     * @return array 会员手机帐号
     */
    public function GetListByUserAccount($userAccount)
    {
        $result = null;
        if(!empty($userAccount)){
            $sql = "SELECT t.*,t1.Email FROM ".self::TableName_User ." t "
            ." LEFT OUTER JOIN ".self::TableName_UserInfo ." t1 "
            ." ON t.UserId=t1.UserId "
            ." WHERE (t.UserName = :UserName OR t.UserEmail = :UserEmail OR t.UserMobile = :UserMobile)";

            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserName",$userAccount);
            $dataProperty->AddField("UserEmail",$userAccount);
            $dataProperty->AddField("UserMobile",$userAccount);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }












    /************** *********************************************************/
    /************** *********************************************************/
    /********** 临时会员表 ****************************************************/
    /************** *********************************************************/
    /************** *********************************************************/
    /************** *********************************************************/
    /************** *********************************************************/
    /************** *********************************************************/
    /***********       ******************************************************/
    /************     *******************************************************/
    /*************   ********************************************************/
    /************** *********************************************************/


    /**
     * 混合登录
     * @param string $userAccount 会员登录帐号，可以是会员名，会员邮箱，会员手机号码
     * @return int 返回userId
     */
    public function TempUserLogin($userAccount){
        $result = -1;

        $tableName="temp_exam_user";
        if(!empty($userAccount)){
            $sql = "SELECT ExamUserId FROM ".$tableName."
                        WHERE (ExamUserName = :ExamUserName OR LoginName = :LoginName);";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ExamUserName",$userAccount);
            $dataProperty->AddField("LoginName",$userAccount);
            $result = $this->dbOperator->GetInt($sql,$dataProperty);
        }
        return $result;
    }


    /**
     * 取得会员名
     * @param int $userId 会员id
     * @param bool $withCache 是否从缓冲中取
     * @return string 会员帐号
     */
    public function GetTempUserName($userId, $withCache)
    {
        $tableName="temp_exam_user";
        $result = "";
        if ($userId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'user_data'
                . DIRECTORY_SEPARATOR .$userId;
            $cacheFile = 'user_get_temp_user_name.cache_' . $userId . '';
            $sql = "SELECT ExamUserName FROM ".$tableName." WHERE ExamUserId=:ExamUserId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ExamUserId", $userId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }


    /**
     * 取得会员名
     * @param string $userDepartment 部门
     * @param bool $withCache 是否从缓冲中取
     * @return array 会员帐号
     */
    public function GetTempUserNameOfDepartment($userDepartment, $withCache)
    {
        $tableName="temp_exam_user";
        $result = "";
        if ($userDepartment!="") {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'user_data'
                . DIRECTORY_SEPARATOR .$userDepartment;
            $cacheFile = 'user_get_temp_user_name_of_department.cache_' . $userDepartment . '';
            $sql = "SELECT ExamUserName FROM ".$tableName." WHERE UserDepartment='$userDepartment';";
            $dataProperty = new DataProperty();
            $result = $this->GetInfoOfArrayList($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }


    /**
     * 取得登陆名
     * @param string $userDepartment 部门
     * @param string $examUserName 名字
     * @param bool $withCache 是否从缓冲中取
     * @return string 会员帐号
     */
    public function GetLoginName($userDepartment,$examUserName,$withCache)
    {
        $tableName="temp_exam_user";
        $result = "";
        if ($userDepartment!="") {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'user_data'
                . DIRECTORY_SEPARATOR .$userDepartment;
            $cacheFile = 'user_get_login_name.cache_' . $userDepartment . '';
            $sql = "SELECT LoginName FROM ".$tableName." WHERE UserDepartment='$userDepartment' AND ExamUserName='$examUserName';";
            $dataProperty = new DataProperty();
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }


    public function GetTopScoreOfDay($time){
        $tableName="temp_exam_user";
        $sql="SELECT * FROM
        (SELECT e.*,u.ExamUserName,u.UserDepartment,u.IsNecessary FROM cst_exam_user_paper e
        LEFT OUTER JOIN ".$tableName." u ON u.ExamUserId=e.UserId
        where BeginTime>'$time 00:00:00' and BeginTime<'$time 23:59:59' ORDER BY GetScore DESC)
        TableNameTemp1
        GROUP BY UserId ORDER BY GetScore DESC ";
        $result=$this->dbOperator->GetArrayList($sql);
        return $result;
    }
}

?>
