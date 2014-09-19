<?php

/**
 * 公共访问 会员 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author yin
 */
class UserPublicData extends BasePublicData {

    public function CheckLogin($userName,$userPass,$siteId){
        $result = -1;
        if(!empty($userName) && !empty($userPass) && $siteId > 0){
            $sql = "SELECT count(*) FROM ".self::TableName_User." WHERE (UserName = :UserName OR UserEmail = :UserEmail OR UserMobile = :UserMobile) AND UserPass = :UserPass AND SiteId = :SiteId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserName",$userName);
            $dataProperty->AddField("UserEmail",$userName);
            $dataProperty->AddField("UserMobile",$userName);
            $dataProperty->AddField("UserPass",$userPass);
            $dataProperty->AddField("SiteId",$siteId);
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
     * 会员登录
     * @param string $userName 会员帐号
     * @param string $userPass 会员密码
     * @return int 用户Id
     */
    public function Login($userName, $userPass) {
        $result = -1;

        if (!empty($userName) && !empty($userPass)) {

            $dataProperty = new DataProperty();
            $sql = "SELECT " . self::TableId_User . " FROM " . self::TableName_User . " WHERE UserName=:UserName AND UserPass=:UserPass;";
            $dataProperty->AddField('UserName', $userName);
            $dataProperty->AddField('UserPass', $userPass);
            $result = $this->dbOperator->GetInt($sql, $dataProperty);
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
