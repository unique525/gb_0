<?php

/**
 * 前台会员数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author zhangchi
 */
class UserPublicData extends BasePublicData {

    /**
     * 表名
     */
    const tableName = "cst_user";

    /**
     * 表关键字段名
     */
    const tableidname = "userid";
    
    
    /**
     * 会员登录
     * @param string $userName 会员帐号
     * @param string $userPass 会员密码
     * @param int $siteId 站点id
     * @return type
     */
    public function Login($userName, $userPass, $siteId = 0) {
        $result = 0;

        if (!empty($userName) && !empty($userPass)) {

            $dataProperty = new DataProperty();

            if ($siteId > 0) {
                $sql = "SELECT " . self::tableidname . " FROM " . self::tableName . " WHERE username=:username AND userpass=:userpass AND siteid=:siteid";
                $dataProperty->AddField('siteid', $siteId);
            } else {
                $sql = "SELECT " . self::tableidname . " FROM " . self::tableName . " WHERE username=:username AND userpass=:userpass";
            }
            $dataProperty->AddField('username', $userName);
            $dataProperty->AddField('userpass', $userPass);
            $dboperator = DBOperator::getInstance();
            $userid = $dboperator->ReturnInt($sql, $dataProperty);
            if ($userid <= 0) {
                $result = -3;       //用户密码不对
            } else {
                $result = $userid;       //OK
            }
        }
        return $result;
    }
}

?>
