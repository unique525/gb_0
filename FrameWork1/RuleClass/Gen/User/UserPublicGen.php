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
                self::GenLogout();
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
            case "async_get_user_mobile":
                $result = self::AsyncGetUserMobile();
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
        $temp = Control::GetRequest("temp","");
        if($temp == "forum"){
            $templateContent = parent::GetDynamicTemplateContent("user_login_for_forum");
            //$templateFileUrl = "user/user_login_for_forum.html";
        }
        else{
            $templateContent = parent::GetDynamicTemplateContent("user_login");
            //$templateFileUrl = "user/user_login.html";
        }
        //$templateName = "default";
        //$templatePath = "front_template";
        //$templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);




        parent::ReplaceFirst($templateContent);
        $reUrl = urlencode(Control::GetRequest("re_url", ""));
        $templateContent = str_ireplace("{ReUrl}", $reUrl, $templateContent);



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
        $temp = Control::GetRequest("temp","");
        if($temp == "forum"){
            $templateContent = parent::GetDynamicTemplateContent("user_register_for_forum");
           // $templateFileUrl = "user/user_register_for_forum.html";
        }
        else{
            //$templateFileUrl = "user/user_register.html";
            $templateContent = parent::GetDynamicTemplateContent("user_register");
        }
        //$templateName = "default";
        //$templatePath = "front_template";
        //$templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);


        parent::ReplaceFirst($templateContent);

        parent::ReplaceEnd($templateContent);

        return $templateContent;
    }

    private function AsyncLogin(){
        $userAccount = Control::GetRequest("user_account", "");
        $userPass = Control::GetRequest("user_pass", "");
        $userPassWithMd5 = Control::GetRequest("user_pass_with_md5", "");
        $siteId = parent::GetSiteIdByDomain();

        if(!empty($userAccount) && (!empty($userPass) || !empty($userPassWithMd5)) && $siteId > 0){
            $userPassWithMd5 = $userPass;
            $userPublicData = new UserPublicData();
            $userId = $userPublicData->Login($userAccount, $userPass, $userPassWithMd5);
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
        $userName = Format::FormatHtmlTag(Control::PostRequest("UserName", "", false));
        $userEmail = Format::FormatHtmlTag(Control::PostRequest("UserEmail", "", false));
        $userMobile = Format::FormatHtmlTag(Control::PostRequest("UserMobile", "", false));
        $userPass = "111111";//Format::FormatHtmlTag(Control::PostRequest("UserPass", "", false));
        $regIp = Control::GetIp();
        $createDate = strval(date('Y-m-d H:i:s', time()));
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


            $newUserId = $userPublicData->Create($siteId, $userPass, $regIp, $userName, $userEmail, $userMobile);
            if ($newUserId > 0) {

                //插入会员信息表
                $realName = Format::FormatHtmlTag(Control::PostOrGetRequest("real_name","", false));
                $nickName = Format::FormatHtmlTag(Control::PostOrGetRequest("nick_name","", false));
                $avatarUploadFileId = Format::FormatHtmlTag(Control::PostOrGetRequest("avatar_upload_fileId",0));
                $userScore = Format::FormatHtmlTag(Control::PostOrGetRequest("user_score",0));
                $userMoney = Format::FormatHtmlTag(Control::PostOrGetRequest("user_money",0));
                $userCharm = Format::FormatHtmlTag(Control::PostOrGetRequest("user_charm",0));
                $userExp = Format::FormatHtmlTag(Control::PostOrGetRequest("user_exp",0));
                $userPoint = Format::FormatHtmlTag(Control::PostOrGetRequest("user_point",0));
                $question = Format::FormatHtmlTag(Control::PostOrGetRequest("question","", false));
                $answer = Format::FormatHtmlTag(Control::PostOrGetRequest("answer","", false));
                $sign = Control::PostOrGetRequest("sign","", false);
                $lastVisitIP = $regIp;
                $lastVisitTime = $createDate;
                $email = Format::FormatHtmlTag(Control::PostOrGetRequest("email","", false));
                $qq = Format::FormatHtmlTag(Control::PostOrGetRequest("qq","", false));
                $country = Format::FormatHtmlTag(Control::PostOrGetRequest("country","", false));
                $comeFrom = Format::FormatHtmlTag(Control::PostOrGetRequest("come_from","", false));
                $honor = Format::FormatHtmlTag(Control::PostOrGetRequest("honor","", false));
                $birthday = Format::FormatHtmlTag(Control::PostOrGetRequest("birthday","", false));
                $gender = Format::FormatHtmlTag(Control::PostOrGetRequest("gender",0));
                $fansCount = Format::FormatHtmlTag(Control::PostOrGetRequest("fans_count",0));
                $idCard = Format::FormatHtmlTag(Control::PostOrGetRequest("id_card","", false));
                $postCode = Format::FormatHtmlTag(Control::PostOrGetRequest("post_code","", false));
                $address = Format::FormatHtmlTag(Control::PostOrGetRequest("address","", false));
                $tel = Format::FormatHtmlTag(Control::PostOrGetRequest("tel",""));
                $mobile = Format::FormatHtmlTag(Control::PostOrGetRequest("mobile",""));
                $province = Format::FormatHtmlTag(Control::PostOrGetRequest("province",""));
                $occupational = Format::FormatHtmlTag(Control::PostOrGetRequest("occupational",""));
                $city = Format::FormatHtmlTag(Control::PostOrGetRequest("city",""));
                $relationship = Format::FormatHtmlTag(Control::PostOrGetRequest("relationship",0));
                $hit = Format::FormatHtmlTag(Control::PostOrGetRequest("hit",0));
                $messageCount = Format::FormatHtmlTag(Control::PostOrGetRequest("message_count",0));
                $userPostCount = Format::FormatHtmlTag(Control::PostOrGetRequest("user_post_count",0));
                $userPostBestCount = Format::FormatHtmlTag(Control::PostOrGetRequest("user_post_best_count",0));
                $userActivityCount = Format::FormatHtmlTag(Control::PostOrGetRequest("user_activity_count",0));
                $userAlbumCount = Format::FormatHtmlTag(Control::PostOrGetRequest("user_album_count",0));
                $userBestAlbumCount = Format::FormatHtmlTag(Control::PostOrGetRequest("user_best_album_count",0));
                $userRecAlbumCount = Format::FormatHtmlTag(Control::PostOrGetRequest("user_rec_album_count",0));
                $userAlbumCommentCount = Format::FormatHtmlTag(Control::PostOrGetRequest("user_album_comment_count",0));
                $userCommissionOwn = Format::FormatHtmlTag(Control::PostOrGetRequest("user_commission_own",0));
                $userCommissionChild = Format::FormatHtmlTag(Control::PostOrGetRequest("user_commission_child",0));
                $userCommissionGrandson = Format::FormatHtmlTag(Control::PostOrGetRequest("user_commission_grandson",0));
                $scrollName = Format::FormatHtmlTag(Control::PostOrGetRequest("scroll_name",0));

                $userInfoPublicData->Create($newUserId, $realName, $nickName,$avatarUploadFileId, $userScore, $userMoney,
                    $userCharm, $userExp, $userPoint, $question, $answer, $sign,
                    $lastVisitIP, $lastVisitTime, $email, $qq, $country, $comeFrom,
                    $honor, $birthday, $gender, $fansCount, $idCard, $postCode,
                    $address, $tel, $mobile, $province, $occupational, $city,
                    $relationship, $hit, $messageCount, $userPostCount, $userPostBestCount,
                    $userActivityCount, $userAlbumCount, $userBestAlbumCount, $userRecAlbumCount,
                    $userAlbumCommentCount, $userCommissionOwn, $userCommissionChild,
                    $userCommissionGrandson,$scrollName);

                //插入会员角色表
                $newMemberGroupId = $siteConfigData->UserDefaultUserGroupIdForRole;
                $userRolePublicData->Init($newUserId, $siteId, $newMemberGroupId);

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

    private function AsyncGetUserMobile(){
        $userId = intval(Control::GetUserId());
        if($userId > 0){

            $userPublicData = new UserPublicData();
            $userMobile = $userPublicData->GetUserMobile($userId, false);
            return Control::GetRequest("jsonpcallback","").
            '({"result":"' . Format::FormatJson($userMobile) . '"})';

        }

        return Control::GetRequest("jsonpcallback","").
        '({"result":""})';
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
            //$templateFileUrl = "user/user_homepage.html";
            //$templateName = "default";
            //$templatePath = "front_template";
            //$templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);
            $templateContent = parent::GetDynamicTemplateContent("user_homepage");
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
            //$templateFileUrl = "user/user_pass_modify.html";
            //$templateName = "default";
            //$templatePath = "front_template";
            //$templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);
            $templateContent = parent::GetDynamicTemplateContent("user_pass_modify");
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
