<?php

/**
 * 后台管理员登录相关生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Manage
 * @author zhangchi
 */
class ManageLoginPublicGen extends BasePublicGen implements IBasePublicGen
{

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenPublic()
    {
        $result = "";
        $action = Control::GetRequest("a", "");
        switch ($action) {
            case "login":
                $result = self::Login();
                break;
            case "logout":
                self::Logout();
                break;
            case "async_get_verify_type":
                $result = self::AsyncGetVerifyType();
                break;
            case "async_do_login":
                $result = self::AsyncDoLogin();
                break;
        }

        return $result;
    }

    /**
     * 后台登录方法
     * @return string 返回模板页面
     */
    private function Login()
    {
        $tempContent = Template::Load("manage/login.html", "common");
        parent::ReplaceEnd($tempContent);
        $result = $tempContent;
        return $result;
    }



    /**
     * 登录成功
     */
    const MANAGE_LOGIN_RESULT_SUCCESS = 1;

    /**
     * 登录失败，参数错误
     */
    const MANAGE_LOGIN_RESULT_PARAM_ERROR = -1;

    /**
     * 登录失败，原因可能是帐号密码错误，或者您的帐号已经停用或过期
     */
    const MANAGE_LOGIN_RESULT_PASSWORD_ERROR_OR_ACCOUNT_EXPIRED = -3;

    /**
     * 登录失败，口令牌认证失败，请联系管理人员或重新输入口令牌密码
     */
    const MANAGE_LOGIN_RESULT_OPT_FAILURE = -6;

    /**
     * 登录失败，因为此帐号不允许外网使用，请联系管理人员
     */
    const MANAGE_LOGIN_RESULT_INTERNET_FORBIDDEN = -8;

    /**
     * Ajax登录
     * @return string 返回登录结果编码
     */
    private function AsyncDoLogin(){
        if (!empty($_POST)) {
            $isSecurityIp = parent::IsSecurityIp();
            $manageUserManageData = new ManageUserManageData();
            $manageUserName = Control::PostRequest("manage_user_name", "");
            $manageUserPass = Control::PostRequest("manage_user_pass", "");

            $manageUserId = $manageUserManageData->Login($manageUserName, $manageUserPass);

            //加入操作日志
            $operateContent = 'POST FORM:'.implode('|',$_POST).';\r\nResult:manageUserId:'.$manageUserId;
            self::CreateManageUserLog($operateContent);

            if ($manageUserId > 0) {
                if (!$isSecurityIp) { //不是内网IP才需要附加认证
                    $openPublicLogin = $manageUserManageData->GetOpenPublicLogin($manageUserId, true);
                    $otpVerifyLogin = $manageUserManageData->GetOtpVerifyLogin($manageUserId, true);

                    if (isset($openPublicLogin) && !empty($openPublicLogin)) { //允许外网登录
                        if (isset($otpVerifyLogin) && !empty($otpVerifyLogin)) { //使用了Otp认证
                            $arrAdminUserInfo = $manageUserManageData->GetOne($manageUserId);
                            $otpNumber = Control::PostRequest("manage_user_otp_number", "");
                            $otpAuthorityKey = $arrAdminUserInfo["OtpAuthorityKey"];
                            $otpCurrentSuccess = $arrAdminUserInfo["OtpCurrentSuccess"];
                            $otpCurrentDrift = $arrAdminUserInfo["OtpCurrentDrift"];
                            if ($this->CheckOtp($otpNumber, $otpAuthorityKey, $otpCurrentSuccess, $otpCurrentDrift) == 0) {
                                //验证成功写入成功值和漂移值
                                $manageUserManageData->ModifyForOtp($manageUserId, $otpCurrentSuccess, $otpCurrentDrift);
                                self::DoLogin($manageUserId, $manageUserName, $manageUserManageData);
                                $result = self::MANAGE_LOGIN_RESULT_SUCCESS;
                            } else {
                                //登录失败，口令牌认证失败，请联系管理人员或重新输入口令牌密码!
                                $result = self::MANAGE_LOGIN_RESULT_OPT_FAILURE;
                                //Control::ShowMessage(Language::Load('common', 6));
                            }
                        } else {
                            //口令牌和短信验证方式都未开启的话默认登录
                            self::DoLogin($manageUserId, $manageUserName, $manageUserManageData);
                            $result = self::MANAGE_LOGIN_RESULT_SUCCESS;
                        }
                    } else {
                        //登录失败，因为此帐号不允许外网使用，请联系管理人员!
                        $result = self::MANAGE_LOGIN_RESULT_INTERNET_FORBIDDEN;
                        //Control::ShowMessage(Language::Load('common', 8));
                    }
                } else {
                    self::DoLogin($manageUserId, $manageUserName, $manageUserManageData);
                    $result = self::MANAGE_LOGIN_RESULT_SUCCESS;
                }
            } else {
                //登录失败，原因可能是帐号密码错误，或者您的帐号已经停用或过期!
                $result = self::MANAGE_LOGIN_RESULT_PASSWORD_ERROR_OR_ACCOUNT_EXPIRED;
                //Control::ShowMessage(Language::Load('common', 3));
            }
        }else{
            //参数错误，请重试
            $result = self::MANAGE_LOGIN_RESULT_PARAM_ERROR;
        }
        return Control::GetRequest("jsonpcallback","") . '({"result":"' . $result . '"})';
    }

    /**
     * 登录成功后执行的操作
     * @param int $manageUserId 管理员id
     * @param string $manageUserName 管理员帐号
     * @param ManageUserManageData $manageUserManageData 管理员后台数据类
     */
    private function DoLogin($manageUserId,$manageUserName,ManageUserManageData $manageUserManageData){

        Control::SetManageUserCookie($manageUserId, $manageUserName, 10000);

        //后台管理中func域名和icms同步登录cookie
        if (WEBAPP_DOMAIN != MANAGE_DOMAIN) {
            $webAppDomain = str_ireplace("http://", "", WEBAPP_DOMAIN);
            Control::SetManageUserCookie($manageUserId, $manageUserName, 10000, $webAppDomain);
        }

        $userId = $manageUserManageData->GetUserId($manageUserId, true);
        $userName = $manageUserManageData->GetUserName($manageUserId, true);
        if ($userId > 0) {
            Control::SetUserCookie($userId, $userName);
        }
        //Control::GoUrl("default.php?secu=manage");
    }

    /**
     * Otp验证接口函数调用
     * @param string $otpNumber 输入的口令
     * @param string $authorityKey 数据库中的密钥
     * @param int $currentSuccess 数据库中的成功值
     * @param int $currentDrift 数据库中的漂移值
     * @return int 返回值
     */
    private function CheckOtp($otpNumber, $authorityKey, &$currentSuccess, &$currentDrift)
    {
        $result = 9;
        if (function_exists('et_checkpwdz201')) {
            $t = time();
            $t0 = 0;
            $x = 60;
            $drift = 0;
            $authorityWnd = 20;
            $lastSuccess = 0;
            $otpLength = 6;
            $currentSuccess = 0;
            $currentDrift = 0;
            $result = et_checkpwdz201($authorityKey, $t, $t0, $x, $drift, $authorityWnd, $lastSuccess, $otpNumber, $otpLength, $currentSuccess, $currentDrift);
        }
        return $result;
    }

    /**
     * 生成是否开启外网、口令牌、短信登录认证的数组
     * @return string JSON结果
     */
    private function AsyncGetVerifyType()
    {
        $isSecurityIp = parent::IsSecurityIp();
        $arrVerifyType = array("open_public_login" => "0", "otp_verify_login" => "0");
        if (!$isSecurityIp) { //不是内网IP再判断是否需要额外验证
            $manageUserName = Control::GetRequest("manage_user_name", "");
            $manageUserManageData = new ManageUserManageData();
            $openPublicLogin = $manageUserManageData->GetOpenPublicLoginByManageUserName($manageUserName, true);
            //允许外网登陆
            if ($openPublicLogin === 1) {
                $arrVerifyType["open_public_login"] = "1";
                $otpVerifyLogin = $manageUserManageData->GetOtpVerifyLoginByManageUserName($manageUserName, true);
                if ($otpVerifyLogin === 1) { //开启了口令牌认证
                    $arrVerifyType["otp_verify_login"] = "1";
                }
            }
        }
        $arrVerifyType = Format::FixJsonEncode($arrVerifyType);
        return Control::GetRequest("JsonpCallBack","") . "(" . $arrVerifyType . ")";
    }

    /*
     * 后台退出登录
     */
    private function Logout()
    {
        Control::DelManageUserCookie();
        session_start();
        session_destroy();
        header("Location:default.php?mod=manage&a=login");
        die();
    }
}

?>
