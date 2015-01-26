<?php

/**
 * 客户端 会员 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_User
 * @author zhangchi
 */
class UserClientGen extends BaseClientGen implements IBaseClientGen
{
    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenClient()
    {
        $result = "";
        $function = Control::GetRequest("f", "");
        switch ($function) {

            case "login_by_user_mobile":
                $result = self::GenLoginByUserMobile();
                break;

            case "register_with_user_mobile":
                $result = self::GenRegisterWithUserMobile();
                break;

        }
        $result = str_ireplace("{function}", $function, $result);
        return $result;
    }

    private function GenLoginByUserMobile()
    {
        $result = "[{}]";

        $userMobile = Format::FormatHtmlTag(Control::PostOrGetRequest("UserMobile", ""));
        $userPass = Control::PostOrGetRequest("UserPass", "");
        $regIp = Control::GetIp();

        if (strlen($userMobile) > 0
            && !empty($userPass)
            && !empty($regIp)
        ) {

            $userClientData = new UserClientData();

            $userId = $userClientData->Login($userMobile, $userPass);

            if($userId <= 0){
                $resultCode = UserBaseGen::LOGIN_ERROR_USER_PASS;
            }else {
                $resultCode = UserBaseGen::LOGIN_SUCCESS;
                $withCache = FALSE;
                $arrUserOne = $userClientData->GetOne($userId,$withCache);

                $result = Format::FixJsonEncode($arrUserOne);
            }

        } else {
            $resultCode = UserBaseGen::LOGIN_ILLEGAL_PARAMETER;
        }

        return '{"result_code":"' . $resultCode . '","user":' . $result . '}';
    }

    private function GenRegisterWithUserMobile()
    {
        $result = "[{}]";

        $userMobile = Format::FormatHtmlTag(Control::PostOrGetRequest("UserMobile", ""));
        $userPass = Control::PostOrGetRequest("UserPass", "");
        $noPass = intval(Control::PostOrGetRequest("NoPass", 0));
        $siteId = intval(Control::PostOrGetRequest("SiteId", 0));

        if ($noPass == 10) {
            $userPass = $userMobile;
        }
        $regIp = Control::GetIp();

        if ($siteId > 0
            && strlen($userMobile) > 0
            && strlen($userMobile) < 50
            && !empty($userPass)
            && !empty($regIp)
        ) {


            //重名注册检查以及格式匹配检查
            //~~~~~~~~~~~~~~开始~~~~~~~~~~~~~~~~~

            $userClientData = new UserClientData();

            if (preg_match("/^13\d{9}$|^15\d{9}$|^18\d{9}$/", $userMobile)) {
                $isSameMobile = $userClientData->CheckRepeatUserMobile($userMobile);
                if ($isSameMobile) { //检查是否有相同的手机号码
                    $resultCode = UserBaseGen::REGISTER_ERROR_REPEAT_USER_MOBILE;
                } else {

                    //开始注册
                    //手机号为空
                    $userInfoClientData = new UserInfoClientData();
                    $userRoleClientData = new UserRoleClientData();
                    $siteConfigData = new SiteConfigData($siteId);
                    $userName = "";
                    $userEmail = "";

                    $newUserId = $userClientData->Create($siteId, $userPass, $regIp, $userName, $userEmail, $userMobile);
                    if ($newUserId > 0) {
                        $userInfoClientData->Init($newUserId, $siteId); //插入会员信息表

                        $newMemberGroupId = $siteConfigData->UserDefaultUserGroupIdForRole;
                        $userRoleClientData->Init($newUserId, $siteId, $newMemberGroupId); //插入会员角色表

                        $withCache = FALSE;
                        $arrUserOne = $userClientData->GetOne($newUserId,$withCache);

                        $result = Format::FixJsonEncode($arrUserOne);

                        //Control::SetUserCookie($newUserId, $userName);
                        $resultCode = UserBaseGen::REGISTER_SUCCESS;
                    } else {
                        $resultCode = UserBaseGen::REGISTER_ERROR_FAIL_REGISTER;
                    }
                }
            } else {
                $resultCode = UserBaseGen::REGISTER_ERROR_FORMAT;
            }

        } else {
            $resultCode = UserBaseGen::REGISTER_ERROR_PARAMETER;
        }

        return '{"result_code":"' . $resultCode . '","user":' . $result . '}';
    }
}

?>