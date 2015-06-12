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
     * @param string $userPassWithMd5 会员密码MD5
     * @return int 返回userId
     */
    public function Login($userAccount,$userPass,$userPassWithMd5=""){
        $result = -1;
        $userPassWithMd5 = md5($userPassWithMd5);
        if(strlen($userPassWithMd5)>20){
            $userPassWithMd5 = substr($userPassWithMd5,0,20);
        }

        if(!empty($userAccount) && (!empty($userPass) || !empty($UserPassWithMd5))){
            $sql = "SELECT UserId FROM ".self::TableName_User."
                        WHERE (UserName = :UserName OR UserEmail = :UserEmail OR UserMobile = :UserMobile)
                            AND (UserPass = :UserPass OR left(UserPassWithMd5,20) = :UserPassWithMd5);";
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
}

?>
