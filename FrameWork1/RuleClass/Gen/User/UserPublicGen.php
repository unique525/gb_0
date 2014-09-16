<?php

/**
 * 前台 会员 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_User
 * @author yin
 */
class UserPublicGen extends BasePublicGen implements IBasePublicGen
{
    const ERROR_REPEAT_USER_NAME = -1; //重复的用户账号

    const ERROR_REPEAT_USER_EMAIL = -2;//重复的用户邮箱

    const ERROR_REPEAT_USER_MOBILE = -3;//重复的手机号码

    const ERROR_PARAMETER = -4;//参数错误

    const ERROR_FAIL_REGISTER = -5;//注册失败

    const ERROR_FORMAT = -6;//格式错误

    const SUCCESS_REGISTER = 1;//注册成功

    const NO_REPEAT = 0;//没有重复的

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenPublic()
    {
        $result = "";
        $method = Control::GetRequest("a", "");
        switch ($method) {
            case "register":
                $result = self::GenRegister();
                break;
            case "async_check_repeat":
                $result = self::AsyncCheckRepeat();
                break;
        }

        $result = str_ireplace("{method}", $method, $result);

        return $result;
    }

    /**
     * AJAX 检查是否有相同的注册账号，email，手机号码
     * @return string
     */
    private function AsyncCheckRepeat()
    {
        $userName = htmlspecialchars(Control::GetRequest("user_name", ""));
        $userEmail = htmlspecialchars(Control::GetRequest("user_email", ""));
        $userMobile = htmlspecialchars(Control::GetRequest("user_mobile", ""));
        $siteId = intval(Control::GetRequest("site_id", 0));

        if ((!empty($userName) || !empty($userEmail) || !empty($userMobile)) && $siteId > 0) {
            $isSameName = self::IsRepeatUserName($userName);
            $isSameEmail = self::IsRepeatUserEmail($userEmail);
            $isSameMobile = self::IsRepeatUserMobile($userMobile);
            if ($isSameName) {
                return Control::GetRequest("jsonpcallback", "") . '({"result":'.self::ERROR_REPEAT_USER_NAME.'})';
            }

            if ($isSameEmail) {
                return Control::GetRequest("jsonpcallback", "") . '({"result":'.self::ERROR_REPEAT_USER_EMAIL.'})';
            }

            if ($isSameMobile) {
                return Control::GetRequest("jsonpcallback", "") . '({"result":'.self::ERROR_REPEAT_USER_MOBILE.'})';
            }

            return Control::GetRequest("jsonpcallback", "") . '({"result":'.self::NO_REPEAT.'})';
        } else {
            return Control::GetRequest("jsonpcallback", "") . '({"result":'.self::ERROR_PARAMETER.'})';
        }
    }

    /**
     * 注册
     * @return string
     */
    private function GenRegister()
    {
        $siteId = Control::GetRequest("site_id", 0);
        $userName = htmlspecialchars(Control::PostRequest("UserName", ""));
        $userEmail = htmlspecialchars(Control::PostRequest("UserEmail", ""));
        $userMobile = htmlspecialchars(Control::PostRequest("UserMobile", ""));
        $userPass = htmlspecialchars(Control::PostRequest("UserPass", ""));
        $regIp = Control::GetIp();
        if ($siteId > 0 && (!empty($userName) || !empty($userEmail) || !empty($userMobile)) && !empty($userPass) && !empty($regIp)) {
            //重名注册检查以及格式匹配检查
            //~~~~~~~~~~~~~~开始~~~~~~~~~~~~~~~~~
            if (!empty($userName)) {
                if (preg_match("/^[\x{4e00}-\x{9fa5}\w]+$/u", $userName)) {
                    $isSameName = self::IsRepeatUserName($userName);
                    if ($isSameName) { //检查是否重名
                        return Control::GetRequest("jsonpcallback", "") . '({"result":'.self::ERROR_REPEAT_USER_NAME.'})';
                    }
                } else {
                    return Control::GetRequest("jsonpcallback", "") . '({"result":'.self::ERROR_FORMAT.'})';
                }
            }

            if (!empty($userEmail)) {
                if (preg_match("/^([a-zA-Z0-9]*[-_]?[a-zA-Z0-9]+)*@([a-zA-Z0-9]*[-_]?[a-zA-Z0-9]+)+[\.][a-zA-Z0-9]{2,4}([\.][a-zA-Z0-9]{2,3})?$/u", $userEmail)) {
                    $isSameEmail = self::IsRepeatUserEmail($userEmail);
                    if ($isSameEmail) { //检查是否相同的邮箱
                        return Control::GetRequest("jsonpcallback", "") . '({"result":'.self::ERROR_REPEAT_USER_EMAIL.'})';
                    }
                } else {
                    return Control::GetRequest("jsonpcallback", "") . '({"result":'.self::ERROR_FORMAT.'})';
                }
            }

            if (!empty($userMobile)) {
                if (preg_match("/^13\d{9}$|^15\d{9}$|^18\d{9}$/", $userMobile)) {
                    $isSameMobile = self::IsRepeatUserMobile($userMobile);
                    if ($isSameMobile) { //检查是否有相同的手机号码
                        return Control::GetRequest("jsonpcallback", "") . '({"result":'.self::ERROR_REPEAT_USER_MOBILE.'})';
                    }
                } else {
                    return Control::GetRequest("jsonpcallback", "") . '({"result":'.self::ERROR_FORMAT.'})';
                }
            }
            //~~~~~~~~~~~~~~结束~~~~~~~~~~~~~~~~~
            $userPublicData = new UserPublicData();
            $userInfoPublicData = new UserInfoPublicData();
            $userRolePublicData = new UserRolePublicData();
            $siteConfigData = new SiteConfigData($siteId);
            $result = $userPublicData->Create($siteId, $userPass, $regIp, $userName, $userEmail, $userMobile);
            if ($result > 0) {
                $userInfoPublicData->Init($result, $siteId);//插入会员信息表

                $newMemberGroupId = $siteConfigData->UserDefaultUserGroupIdForRole;
                $userRolePublicData->Init($result, $siteId, $newMemberGroupId);//插入会员角色表

                Control::SetUserCookie($result, $userName);
                return Control::GetRequest("jsonpcallback", "") . '({"result":'.self::SUCCESS_REGISTER.'})';
            } else {
                return Control::GetRequest("jsonpcallback", "") . '({"result":'.self::ERROR_FAIL_REGISTER.'})';
            }
        } else {
            return Control::GetRequest("jsonpcallback", "") . '({"result":'.self::ERROR_PARAMETER.'})';
        }
    }

    /**
     * 检查是否重名
     * @param string $userName 被检查的用户名
     * @return bool True为是相同的名字 False为没有相同的名字
     */
    private function IsRepeatUserName($userName)
    {
        $userPublicData = new UserPublicData();
        $isSameName = $userPublicData->CheckRepeatUserName($userName);
        if ($isSameName > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 检查是否有相同的注册邮箱
     * @param string $userEmail 被检查的用户邮箱
     * @return bool True为是相同的邮箱 False为没有相同的邮箱
     */
    private function IsRepeatUserEmail($userEmail)
    {
        $userPublicData = new UserPublicData();
        $isSameEmail = $userPublicData->CheckRepeatUserEmail($userEmail);
        if ($isSameEmail > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 检查是否重名
     * @param string $userMobile 被检查的用户手机号码
     * @return bool True为是相同的手机号码 False为没有相同的手机号码
     */
    private function IsRepeatUserMobile($userMobile)
    {
        $userPublicData = new UserPublicData();
        $isSameMobile = $userPublicData->CheckRepeatUserMobile($userMobile);
        if ($isSameMobile > 0) {
            return true;
        } else {
            return false;
        }
    }
}

?>
