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
        $action = Control::GetRequest("a", "");
        switch ($action) {
            case "login":
                $result = self::GenLogin();
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
            case "homepage":
                $result = self::GenHomePage();
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
        return $templateContent;
    }

    private function GenRegister(){
        $templateFileUrl = "user/user_register.html";
        $templateName = "default";
        $templatePath = "front_template";
        $templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);
        return $templateContent;
    }

    private function AsyncLogin(){
        $userAccount = Control::GetRequest("user_account", "");
        $userPass = Control::GetRequest("user_pass", "");
        $siteId = Control::GetRequest("site_id",0);

        if(!empty($userAccount) && !empty($userPass) && $siteId > 0){
            $userPublicData = new UserPublicData();
            $userId = $userPublicData->CheckLogin($userAccount, $userPass, $siteId);
            if($userId <= 0){
                return Control::GetRequest("jsonpcallback","").'({"result":-1})';
            }else {
                Control::SetUserCookie($userId,$userAccount);
                return Control::GetRequest("jsonpcallback","").'({"result":'.$userId.'})';
            }
        }else{
            return Control::GetRequest("jsonpcallback","").'({"result":-2})';
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
        $siteId = intval(Control::GetRequest("site_id", 0));

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

    private function AsyncGetOne(){
        $userId = intval(Control::GetUserId());
        $siteId = Control::GetRequest("site_id",0);
        if($userId > 0 && $siteId > 0){
            $userInfoPublicData = new UserInfoPublicData();
            $arrUserOne = $userInfoPublicData->GetOne($userId,$siteId);
            return Control::GetRequest("jsonpcallback","").'({"result":' . Format::FixJsonEncode($arrUserOne) . '})';
        }else{
            Control::GoUrl("/default.php?mod=user&a=login");
            return null;
        }
    }

    private function GenHomePage(){
        $userId =Control::GetUserId();
        $siteId = Control::GetRequest("site_id",0);

        if($userId > 0 && $siteId > 0){
            $templateFileUrl = "user/user_center.html";
            $templateName = "default";
            $templatePath = "front_template";
            $templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);

            $userAccount = Control::GetUserName();

            $userInfoPublicData = new UserInfoPublicData();
            $userOrderPublicData = new UserOrderPublicData();
            $userFavoritePublicData = new UserFavoritePublicData();

            //--------替换会员信息--------begin
            $arrUserOne = $userInfoPublicData->GetOne($userId,$siteId);
            Template::ReplaceOne($templateContent,$arrUserOne);
            //------------------------------end

            //-----------替换最近收藏----------begin
            $tagId = "favorite_list";
            $pageBegin = 0;
            $pageSize = 4;
            $allCount = 0;
            $arrUserFavoriteList = $userFavoritePublicData->GetListForRecentUserFavorite($userId,$siteId,$pageBegin,$pageSize,$allCount);
            if(count($arrUserFavoriteList) > 0){
                Template::ReplaceList($templateContent,$arrUserFavoriteList,$tagId);
            }else{
                $templateContent = Template::ReplaceCustomTag($templateContent, $tagId,"您还没有收藏任何产品");
            }
            //------------------------------end

            //------------零散替换--------begin
            $State_UnSettleUserOrder = 0;
            $State_UnCommentUserOrder = 70;
            $unSettleOrderCount = $userOrderPublicData->GetUserOrderCountByState($userId,$siteId,$State_UnSettleUserOrder);
            $unCommentOrderCount = $userOrderPublicData->GetUserOrderCountByState($userId,$siteId,$State_UnCommentUserOrder);

            $arrReplace = array(
                "{user_account}" => $userAccount,
                "{un_settle_order_count}" => $unSettleOrderCount,
                "{un_comment_order_count}" => $unCommentOrderCount
            );
            //-----------------------------end

            $templateContent = strtr($templateContent,$arrReplace);
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
}

?>
