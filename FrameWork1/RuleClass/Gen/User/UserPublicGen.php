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

    const ILLEGAL_PARAMETER = -2;//非法参数

    const ERROR_USER_PASS = -1;//账号密码错误


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
                $result = self::GenLogin();
                break;
            case "logout":
                $result = self::GenLogout();
                break;
            case "register":
                $result = self::GenRegister();
                break;
            case "async_login":
                $result = self::AsyncLogin();
                break;
            case "async_register":
                $result = self::AsyncRegister();
                break;
            case "async_check_repeat":
                $result = self::AsyncCheckRepeat();
                break;
            case "async_get_one":
                $result = self::AsyncGetOne();
                break;
            case "async_is_login":
                $result = self::AsyncIsLogin();
                break;
            case "homepage":
                $result = self::GenHomePage();
                break;
            case "modify_user_pass": //生成修改密码界面
                $result = self::GenModifyPassword();
                break;
        }

        $result = str_ireplace("{action}", $action, $result);

        return $result;
    }

    private function GenLogin(){
        $templateFileUrl = "user/user_login.html";
        $templateName = "default";
        $templatePath = "front_template";
        $templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);

        parent::ReplaceFirst($templateContent);

        parent::ReplaceEnd($templateContent);

        return $templateContent;
    }

    private function GenLogout(){
        Control::DelUserCookie();
        session_start();
        session_destroy();
        Control::GoUrl("/");
    }

    private function GenRegister(){
        $templateFileUrl = "user/user_register.html";
        $templateName = "default";
        $templatePath = "front_template";
        $templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);


        parent::ReplaceFirst($templateContent);

        parent::ReplaceEnd($templateContent);

        return $templateContent;
    }

    private function AsyncLogin(){
        $userAccount = Control::GetRequest("user_account", "");
        $userPass = Control::GetRequest("user_pass", "");
        $siteId = parent::GetSiteIdByDomain();

        if(!empty($userAccount) && !empty($userPass) && $siteId > 0){
            $userPublicData = new UserPublicData();
            $userId = $userPublicData->Login($userAccount, $userPass, $siteId);
            if($userId <= 0){
                return Control::GetRequest("jsonpcallback","").'({"result":'.self::ERROR_USER_PASS.'})';
            }else {
                Control::SetUserCookie($userId,$userAccount);
                return Control::GetRequest("jsonpcallback","").'({"result":'.$userId.'})';
            }
        }else{
            return Control::GetRequest("jsonpcallback","").'({"result":'.self::ILLEGAL_PARAMETER.'})';
        }
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
        $siteId = parent::GetSiteIdByDomain();

        if ((!empty($userName) || !empty($userEmail) || !empty($userMobile)) && $siteId > 0) {
            $isSameName = self::IsRepeatUserName($userName);
            $isSameEmail = self::IsRepeatUserEmail($userEmail);
            $isSameMobile = self::IsRepeatUserMobile($userMobile);
            if (!empty($userName)  && $isSameName) {
                return Control::GetRequest("jsonpcallback", "") . '({"result":'.self::ERROR_REPEAT_USER_NAME.'})';
            }

            if (!empty($userEmail) && $isSameEmail) {
                return Control::GetRequest("jsonpcallback", "") . '({"result":'.self::ERROR_REPEAT_USER_EMAIL.'})';
            }

            if (!empty($userMobile) && $isSameMobile) {
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
    private function AsyncRegister()
    {
        $siteId = parent::GetSiteIdByDomain();
        $userName = Format::FormatHtmlTag(Control::PostRequest("UserName", ""));
        $userEmail = Format::FormatHtmlTag(Control::PostRequest("UserEmail", ""));
        $userMobile = Format::FormatHtmlTag(Control::PostRequest("UserMobile", ""));
        $userPass = Format::FormatHtmlTag(Control::PostRequest("UserPass", ""));
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
//                if (preg_match("/^13\d{9}$|^15\d{9}$|^18\d{9}$/", $userMobile)) {
//                    $isSameMobile = self::IsRepeatUserMobile($userMobile);
//                    if ($isSameMobile) { //检查是否有相同的手机号码
//                        return Control::GetRequest("jsonpcallback", "") . '({"result":'.self::ERROR_REPEAT_USER_MOBILE.'})';
//                    }
//                } else {
//                    return Control::GetRequest("jsonpcallback", "") . '({"result":'.self::ERROR_FORMAT.'})';
//                }
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

    private function AsyncGetOne(){
        $userId = intval(Control::GetUserId());
        $siteId = parent::GetSiteIdByDomain();
        if($userId > 0 && $siteId > 0){
            $userInfoPublicData = new UserInfoPublicData();
            $arrUserOne = $userInfoPublicData->GetOne($userId,$siteId);
            return Control::GetRequest("jsonpcallback","").'({"result":' . Format::FixJsonEncode($arrUserOne) . '})';
        }else{
            return Control::GetRequest("jsonpcallback","").'({"result":"-1"})';
        }
    }

    /**
     * ajax检查是否登录
     */
    private function AsyncIsLogin(){
        $userId = intval(Control::GetUserId());
        if($userId>0){
            return Control::GetRequest("jsonpcallback","").'({"result":"'.$userId.'"})';
        }else{
            return Control::GetRequest("jsonpcallback","").'({"result":"-1"})';
        }
    }


    private function GenHomePage(){
        $userId =Control::GetUserId();
        $siteId = parent::GetSiteIdByDomain();

        if($userId > 0 && $siteId > 0){
            $templateFileUrl = "user/user_homepage.html";
            $templateName = "default";
            $templatePath = "front_template";
            $templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);

            parent::ReplaceFirst($templateContent);

            $userAccount = Control::GetUserName();

            $userInfoPublicData = new UserInfoPublicData();
            $userOrderPublicData = new UserOrderPublicData();
            $userFavoritePublicData = new UserFavoritePublicData();

            //--------替换会员信息--------begin
            $arrUserOne = $userInfoPublicData->GetOne($userId,$siteId);
            Template::ReplaceOne($templateContent,$arrUserOne);
            //------------------------------end

            //-----------替换最近收藏----------begin
            $tagId = "recent_user_favorite_list";
            $pageBegin = 0;
            $pageSize = 4;
            $allCount = 0;
            $arrUserFavoriteList = $userFavoritePublicData->GetListForRecentUserFavorite($userId,$siteId,$pageBegin,$pageSize,$allCount);
            if(count($arrUserFavoriteList) > 0){
                Template::ReplaceList($templateContent,$arrUserFavoriteList,$tagId);
            }else{
                $templateContent = Template::ReplaceCustomTag($templateContent, $tagId,Language::Load("user",42));
            }
            //------------------------------end

            //------------零散替换--------begin
            $userOrderStateOfNew = UserOrderData::STATE_NON_PAYMENT;
            $userOrderStateOfPayment = UserOrderData::STATE_PAYMENT;
            $userOrderOfNewCount = $userOrderPublicData->GetUserOrderCountByState($userId,$siteId,$userOrderStateOfNew);
            $userOrderOfPaymentCount = $userOrderPublicData->GetUserOrderCountByState($userId,$siteId,$userOrderStateOfPayment);


            $templateContent = str_ireplace("{UserAccount}", $userAccount, $templateContent);
            $templateContent = str_ireplace("{UserOrderOfNewCount}", $userOrderOfNewCount, $templateContent);
            $templateContent = str_ireplace("{UserOrderOfPayment}", $userOrderOfPaymentCount, $templateContent);
            $templateContent = str_ireplace("{SiteId}", $siteId, $templateContent);

            $templateContent = parent::ReplaceTemplate($templateContent);
            parent::ReplaceSiteConfig($siteId,$templateContent);
            parent::ReplaceEnd($templateContent);
            return $templateContent;
        }else{
            Control::GoUrl("/default.php?mod=user&a=login");
            return null;
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

    /**
     * @return mixed|string
     */
    private function GenModifyPassword(){
        $userId = Control::GetUserId();
        if($userId > 0){
            $siteId = parent::GetSiteIdByDomain();
            $templateFileUrl = "user/user_pass_modify.html";
            $templateName = "default";
            $templatePath = "front_template";
            $templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);
            parent::ReplaceFirst($templateContent);


            $templateContent = str_ireplace("{UserId}", strval($userId), $templateContent);
            parent::ReplaceSiteConfig($siteId,$templateContent);
            parent::ReplaceEnd($templateContent);
            return $templateContent;
        }else{
            return "";
        }
    }

}

?>
