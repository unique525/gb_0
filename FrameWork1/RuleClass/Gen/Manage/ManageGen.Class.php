<?php

/**
 * 后台管理员登录生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Manage
 * @author zhangchi
 */
class ManageGen extends BaseFrontGen implements IBaseFrontGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenFront() {
        $result = "";
        $action = Control::GetRequest("a", "");
        switch ($action) {
            case "login":
                $result = self::Login();
                break;
            case "logout":
                self::Logout();
                break;
            case "verifytype":
                $result = self::AsyncGenVerifyType();
                break;
        }

        return $result;
    }

    /**
     * 后台登录方法
     * @return string 返回模板页面
     */
    private function Login() {
        $result = "";
        $tempContent = Template::Load("manage/login.html");

        parent::ReplaceEnd($tempContent);

        if (!empty($_POST)) {
            $isSecurityIp = parent::IsSecurityIp();
            $adminUserManageData = new AdminUserManageData();
            $adminUserName = Control::PostRequest("adminusername", "");
            $adminUserPass = Control::PostRequest("adminuserpass", "");

            $adminUserId = $adminUserManageData->Login($adminUserName, $adminUserPass);

            //加入操作log
            $operateContent = "AdminUser Login:AdminUserId$adminUserId;AdminUserName:$adminUserName;AdminUserPass:$adminUserPass";
            $adminUserLogManageData = new AdminUserLogManageData();
            $adminUserLogManageData->Insert($operateContent);

            $domain = null;
            include ROOTPATH . '/FrameWork1/SystemInc/domain.inc.php';
            $funcDomain = $domain['func'];
            $icmsDomain = $domain['icms'];

            if ($adminUserId > 0) {
                if (!$isSecurityIp) { //不是内网IP才需要附加认证
                    $adminUserManageData = new AdminUserManageData();
                    $openEnLogin = $adminUserManageData->GetOpenEnLogin($adminUserName);
                    $openSmsLogin = $adminUserManageData->GetOpenSmsLogin($adminUserName);
                    $openOtpLogin = $adminUserManageData->GetOpenOtpLogin($adminUserName);

                    if (isset($openEnLogin) && !empty($openEnLogin)) { //允许外网登录
                        if (isset($openOtpLogin) && !empty($openOtpLogin)) { //使用了Otp认证
                            $arrAdminUserInfo = $adminUserManageData->GetOne($adminUserId);
                            $otp = Control::PostRequest("adminuserotppass", "");
                            $otpAuthKey = $arrAdminUserInfo["OtpAuthKey"];
                            $otpCurrSucc = $arrAdminUserInfo["OtpCurrsucc"];
                            $otpCurrDft = $arrAdminUserInfo["OtpCurrdft"];
                            if ($this->CheckOtp($otp, $otpAuthKey, $otpCurrSucc, $otpCurrDft) == 0) {
                                //验证成功写入成功值和漂移值
                                $adminUserManageData->UpdateOtpValue($adminUserId, $otpCurrSucc, $otpCurrDft);
                                Control::SetAdminUserCookie($adminUserId, $adminUserName);
                                //后台管理中func域名和icms同步登录cookie

                                if ($funcDomain != $icmsDomain) {
                                    $funcDomain = str_ireplace("http://", "", $funcDomain);
                                    Control::SetAdminUserCookie($adminUserId, $adminUserName, 24, $funcDomain);
                                }

                                $userId = $adminUserManageData->GetUserID($adminUserId);
                                $userName = $adminUserManageData->GetUserName($adminUserId);
                                if ($userId > 0) {
                                    Control::SetUserCookie($userId, $userName);
                                }
                                Control::GoUrl("default.php?secu=manage");
                                //header("Location:index.php");
                            } else {
                                Control::ShowMessage(Language::Load('common', 6));
                            }
                        } else if (isset($openSmsLogin) && !empty($openSmsLogin)) { //使用了短信认证
                            $mobile = $adminUserManageData->GetMobile($adminUserId);
                            if (strlen($mobile) > 3) { //设置了验证手机
                                $smsData = new SMSData();
                                $smsContent = $smsData->GetToSP($mobile);
                                if ($smsContent == $smsCode) {
                                    //后台管理中func域名和icms同步登录cookie

                                    if ($funcDomain != $icmsDomain) {
                                        $funcDomain = str_ireplace("http://", "", $funcDomain);
                                        Control::SetAdminUserCookie($adminUserId, $adminUserName, 24, $funcDomain);
                                    }

                                    Control::SetAdminUserCookie($adminUserId, $adminUserName);
                                    $userId = $adminUserManageData->GetUserID($adminUserId);
                                    $userName = $adminUserManageData->GetUserName($adminUserId);
                                    if ($userId > 0) {
                                        Control::SetUserCookie($userId, $userName);
                                    }
                                    Control::GoUrl("default.php?secu=manage");
                                    //header("Location:index.php");
                                } else {
                                    Control::ShowMessage(Language::Load('common', 4));
                                }
                            }
                        } else {
                            //口令牌和短信验证方式都未开启的话默认登录
                            Control::SetAdminUserCookie($adminUserId, $adminUserName);
                            //后台管理中func域名和icms同步登录cookie

                            if ($funcDomain != $icmsDomain) {
                                $funcDomain = str_ireplace("http://", "", $funcDomain);
                                Control::SetAdminUserCookie($adminUserId, $adminUserName, 24, $funcDomain);
                            }

                            $userId = $adminUserManageData->GetUserID($adminUserId);
                            $userName = $adminUserManageData->GetUserName($adminUserId);
                            if ($userId > 0) {
                                Control::SetUserCookie($userId, $userName);
                            }
                            Control::GoUrl("default.php?secu=manage");
                            //header("Location:index.php");
                        }
                    } else {
                        Control::ShowMessage(Language::Load('common', 8));
                    }
                } else {
                    //后台管理中func域名和icms同步登录cookie
                    Control::SetAdminUserCookie($adminUserId, $adminUserName);

                    if ($funcDomain != $icmsDomain) {
                        $funcDomain = str_ireplace("http://", "", $funcDomain);
                        Control::SetAdminUserCookie($adminUserId, $adminUserName, 24, $funcDomain);
                    }

                    $userId = $adminUserManageData->GetUserID($adminUserId);
                    $userName = $adminUserManageData->GetUserName($adminUserId);
                    if ($userId > 0) {
                        Control::SetUserCookie($userId, $userName);
                    }
                    Control::GoUrl("default.php?secu=manage");
                    //header("Location:index.php");
                }
            } else {
                Control::ShowMessage(Language::Load('common', 3));
            }
        }

        $result = $tempContent;
        return $result;
    }

    /**
     * Otp验证接口函数调用
     * @param type $otp
     * @param type $authKey
     * @param int $currSucc
     * @param int $currDft
     * @return type
     */
    private function CheckOtp($otp, $authKey, &$currSucc, &$currDft) {
        $result = 9;
        if (function_exists('et_checkpwdz201')) {
            $t = time();
            $t0 = 0;
            $x = 60;
            $drift = 0;
            $authwnd = 20;
            $lastsucc = 0;
            $otplen = 6;
            $currSucc = 0;
            $currDft = 0;

            $result = et_checkpwdz201($authKey, $t, $t0, $x, $drift, $authwnd, $lastsucc, $otp, $otplen, $currSucc, $currDft);
        }
        return $result;
    }

    /**
     * 生成是否登录验证的数组JSON结果
     * @return string JSON
     */
    private function AsyncGenVerifyType() {
        $isSecurityIp = false;
        $isSecurityIp = parent::IsSecurityIp();
        $arrVerifyType = array("en" => "0", "otp" => "0", "sms" => "0");
        if (!$isSecurityIp) { //不是内网IP再判断是否需要额外验证
            $adminUserName = Control::GetRequest("adminusername", "");
            $adminUserData = new AdminUserData();
            $openEnLogin = $adminUserData->GetOpenENLogin($adminUserName);
            //允许外网登陆
            if ($openEnLogin === 1) {
                $arrVerifyType["en"] = "1";
                $openSmsLogin = $adminUserData->GetOpenSMSLogin($adminUserName);
                $openOtpLogin = $adminUserData->GetOpenOtpLogin($adminUserName);
                if ($openOtpLogin === 1) { //开启了口令牌认证
                    $arrVerifyType["otp"] = "1";
                }
                if ($openSmsLogin === 1) { //开启了外网短信认证
                    $arrVerifyType["sms"] = "1";
                }
            }
        }
        $arrVerifyType = json_encode($arrVerifyType);
        return $_GET['jsonpcallback'] . "(" . $arrVerifyType . ")";
    }

    /*
     * 后台退出登录
     */
    private function Logout(){
        Control::DelAdminUserCookie();
        session_start();
        session_destroy();
        header("Location:default.php?mod=manage&a=login");
    }
}

?>
