<?php

/**
 * 客户端 会员 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author zhangchi
 */
class UserClientData extends BaseClientData {

    /**
     * 混合登录
     * @param string $userAccount 会员登录帐号，可以是会员名，会员邮箱，会员手机号码
     * @param string $userPass 会员密码(md5)
     * @return int 返回userId
     */
    public function Login($userAccount,$userPass){
        $result = -1;

        if(!empty($userAccount) && !empty($userPass)){

            $sql = "SELECT UserPass,UserId FROM ".self::TableName_User."
                        WHERE (UserName = :UserName OR UserEmail = :UserEmail OR UserMobile = :UserMobile)
                        AND State=0;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserName",$userAccount);
            $dataProperty->AddField("UserEmail",$userAccount);
            $dataProperty->AddField("UserMobile",$userAccount);

            $arrUser = $this->dbOperator->GetArray($sql,$dataProperty);

            if(count($arrUser)>0){

                $userPassInDataBase = $arrUser["UserPass"];
                $userId = intval($arrUser["UserId"]);

                if (md5($userPassInDataBase) == $userPass){
                    //密码正确
                    $result = $userId;
                }else{
                    //密码错误
                    $result = -2;
                }

            }else{
                $result = -3;
            }

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

    /**
     * 根据userId得到一行信息信息
     * @param int $userId 会员id
     * @param bool $withCache 是否缓存
     * @return array 会员信息列表数据集
     */
    public function GetOne($userId, $withCache = false)
    {
        $result = null;
        if ($userId > 0) {
            $sql = "SELECT u.*,ui.* FROM " . self::TableName_User . " u, ".self::TableName_UserInfo." ui
                    WHERE u.UserId = ui.UserId
                    AND u.UserId=:UserId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId", $userId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
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
     * 取得会员帐号
     * @param string $userMobile 会员mobile
     * @param bool $withCache 是否从缓冲中取
     * @return string 会员帐号
     */
    public function GetUserIdByUserMobile($userMobile, $withCache)
    {
        $result = -1;
        if (strlen($userMobile) > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'user_data'
                . DIRECTORY_SEPARATOR .$userMobile;
            $cacheFile = 'user_get_user_id_by_user_mobile.cache_' . $userMobile . '';
            $sql = "SELECT UserId FROM " . self::TableName_User . " WHERE UserMobile=:UserMobile;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserMobile", $userMobile);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 修改用户密码
     * @param string $userMobile
     * @param string $userPass
     * @return int 返回修改结果
     */
    public function ModifyPassword($userMobile, $userPass)
    {
        $result = -1;
        if(strlen($userMobile) > 0){
            $sql = "UPDATE " . self::TableName_User . " SET UserPass = :UserPass WHERE UserMobile = :UserMobile;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserPass", $userPass);
            $dataProperty->AddField("UserMobile", $userMobile);
            $result = $this->dbOperator->Execute($sql,$dataProperty);
        }
        return $result;
    }
}
?>