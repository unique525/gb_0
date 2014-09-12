<?php

/**
 * 前台 会员 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_User
 * @author yin
 */
class UserPublicGen extends BasePublicGen implements IBasePublicGen
{

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenPublic()
    {
        $result = "";
        $method = Control::GetRequest("m", "");
        switch ($method) {
            case "register":
                $result = self::GenRegister();
                break;
            case "async_check_same_name":
                $result = self::AsyncCheckSameName();
                break;
        }

        $result = str_ireplace("{method}", $method, $result);

        return $result;
    }

    /**
     * AJAX 检查是否有相同的注册账号，email，手机号码
     * @return string
     */
    private function AsyncCheckSameName()
    {
        $userName = htmlspecialchars(Control::GetRequest("user_name", ""));
        $userEmail = htmlspecialchars(Control::GetRequest("user_email", ""));
        $userMobile = htmlspecialchars(Control::GetRequest("user_mobile", ""));
        $siteId = intval(Control::GetRequest("site_id", 0));

        if ((!empty($userName) || !empty($userEmail) || !empty($userMobile)) && $siteId > 0) {
            $isSameName = self::IsSameName($userName, $siteId);
            $isSameEmail = self::IsSameEmail($userEmail, $siteId);
            $isSameMobile = self::IsSameMobile($userMobile, $siteId);
            if ($isSameName) {
                return Control::GetRequest("jsonpcallback", "") . '({"result":1})';
            }

            if ($isSameEmail) {
                return Control::GetRequest("jsonpcallback", "") . '({"result":2})';
            }

            if ($isSameMobile) {
                return Control::GetRequest("jsonpcallback", "") . '({"result":3})';
            }

            return Control::GetRequest("jsonpcallback", "") . '({"result":0})';
        } else {
            return Control::GetRequest("jsonpcallback", "") . '({"result":-1})';
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
                    $isSameName = self::IsSameName($userName, $siteId);
                    if ($isSameName) { //检查是否重名
                        return Control::GetRequest("jsonpcallback", "") . '({"result":-2})';
                    }
                } else {
                    return Control::GetRequest("jsonpcallback", "") . '({"result":-3})';
                }
            }

            if (!empty($userEmail)) {
                if (preg_match("/^([a-zA-Z0-9]*[-_]?[a-zA-Z0-9]+)*@([a-zA-Z0-9]*[-_]?[a-zA-Z0-9]+)+[\.][a-zA-Z0-9]{2,4}([\.][a-zA-Z0-9]{2,3})?$/u", $userEmail)) {
                    $isSameEmail = self::IsSameEmail($userEmail, $siteId);
                    if ($isSameEmail) { //检查是否相同的邮箱
                        return Control::GetRequest("jsonpcallback", "") . '({"result":-4})';
                    }
                } else {
                    return Control::GetRequest("jsonpcallback", "") . '({"result":-3})';
                }
            }

            if (!empty($userMobile)) {
                if (preg_match("/^13\d{9}$|^15\d{9}$|^18\d{9}$/", $userMobile)) {
                    $isSameMobile = self::IsSameMobile($userMobile, $siteId);
                    if ($isSameMobile) { //检查是否有相同的手机号码
                        return Control::GetRequest("jsonpcallback", "") . '({"result":-5})';
                    }
                } else {
                    return Control::GetRequest("jsonpcallback", "") . '({"result":-3})';
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
                return Control::GetRequest("jsonpcallback", "") . '({"result":1})';
            } else {
                $debug = new DebugLogManageData();
                $debug->Create($result);
                return Control::GetRequest("jsonpcallback", "") . '({"result":0})';
            }
        } else {
            return Control::GetRequest("jsonpcallback", "") . '({"result":-1})';
        }
    }

    /**
     * 检查是否重名
     * @param string $userName 被检查的用户名
     * @param int $siteId 站点Id
     * @return bool True为是相同的名字 False为没有相同的名字
     */
    private function IsSameName($userName, $siteId)
    {
        $userPublicData = new UserPublicData();
        $isSameName = $userPublicData->CheckSameName($userName, $siteId);
        if ($isSameName > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 检查是否有相同的注册邮箱
     * @param string $userEmail 被检查的用户邮箱
     * @param int $siteId 站点Id
     * @return bool True为是相同的邮箱 False为没有相同的邮箱
     */
    private function IsSameEmail($userEmail, $siteId)
    {
        $userPublicData = new UserPublicData();
        $isSameEmail = $userPublicData->CheckSameEmail($userEmail, $siteId);
        if ($isSameEmail > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 检查是否重名
     * @param string $userMobile 被检查的用户手机号码
     * @param int $siteId 站点Id
     * @return bool True为是相同的手机号码 False为没有相同的手机号码
     */
    private function IsSameMobile($userMobile, $siteId)
    {
        $userPublicData = new UserPublicData();
        $isSameMobile = $userPublicData->CheckSameMobile($userMobile, $siteId);
        if ($isSameMobile > 0) {
            return true;
        } else {
            return false;
        }
    }
}

?>
