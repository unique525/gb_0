<?php
/**
 * 客户端 会员 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_User
 * @author zhangchi
 */
class UserClientGen extends BaseClientGen implements IBaseClientGen  {
    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenClient(){
        $result = "";
        $function = Control::GetRequest("f", "");
        switch ($function) {

            case "login":
                $result = self::GenLogin();
                break;

            case "register_with_user_mobile":
                $result = self::GenRegisterWithUserMobile();
                break;

        }
        $result = str_ireplace("{function}", $function, $result);
        return $result;
    }

    private function GenLogin(){
        $result = "";


        return $result;
    }

    private function GenRegisterWithUserMobile(){
        $result = "[{}]";
        $resultCode = 0;

        $userMobile = Format::FormatHtmlTag(Control::PostOrGetRequest("UserMobile", ""));
        $userPass = Control::PostOrGetRequest("UserPass", "");
        $noPass = intval(Control::PostOrGetRequest("NoPass", 0));
        $siteId = intval(Control::PostOrGetRequest("SiteId", 0));

        if ($siteId > 0
            && strlen($userMobile) > 0
            && strlen($userMobile) < 50
            ){

            $regIp = Control::GetIp();
            if ($siteId > 0 && (!empty($userMobile)) && !empty($userPass) && !empty($regIp)) {
                //重名注册检查以及格式匹配检查
                //~~~~~~~~~~~~~~开始~~~~~~~~~~~~~~~~~


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
                $newUserId = $userPublicData->Create($siteId, $userPass, $regIp, $userName, $userEmail, $userMobile);
                if ($newUserId > 0) {
                    $userInfoPublicData->Init($newUserId, $siteId);//插入会员信息表

                    $newMemberGroupId = $siteConfigData->UserDefaultUserGroupIdForRole;
                    $userRolePublicData->Init($newUserId, $siteId, $newMemberGroupId);//插入会员角色表

                    Control::SetUserCookie($newUserId, $userName);
                    return Control::GetRequest("jsonpcallback", "") . '({"result":'.self::SUCCESS_REGISTER.'})';
                } else {
                    return Control::GetRequest("jsonpcallback", "") . '({"result":'.self::ERROR_FAIL_REGISTER.'})';
                }
            } else {
                return Control::GetRequest("jsonpcallback", "") . '({"result":'.self::ERROR_PARAMETER.'})';
            }

        }






        return '{"result_code":"'.$resultCode .'","user":' . $result . '}';
    }
}
?>