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

    const ERROR_FORMAT_USER_NAME = -6;//用户名格式错误

    const ERROR_FORMAT_USER_EMAIL = -7;//邮箱格式错误

    const ERROR_FORMAT_USER_MOBILE = -8;//手机格式错误

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
            case "center":
                $result = self::GenCenter();
                break;
            case "modify_user_pass": //生成修改密码界面
                 $result = self::GenModifyUserPass();
                break;
            case "user_password_forget": //生成找回密码页面
                $result = self::GenRetrieveUserPass();
                break;

            case "async_modify_user_pass": //密码修改操作
                $result = self::AsyncModifyUserPass();
                break;
            case "async_find_user_pass_by_email": //通过邮件找回密码操作
                $result = self::AsyncFindUserPassByEmail();
                break;
            case "get_wx_access_token_oauth2":
                self::GetWxAccessTokenOauth2();
                break;
            case "bind_wx_open_id":
                self::BindWxOpenId();
                break;


            case "get_wx_user_info":
                $result = self::GetWxUserInfo();
                break;
            case "temp_register":  //临时自动注册
                $result = self::TempRegister();
                break;
        }

        $result = str_ireplace("{action}", $action, $result);
        return $result;
    }


    private function GetWxAccessToken(){

        $result = "";

        $siteId = parent::GetSiteIdByDomain();
        if($siteId>0){

            $siteConfigData = new SiteConfigData($siteId);

            $weiXinAppId = $siteConfigData->WeiXinAppId;
            $weiXinAppSecret = $siteConfigData->WeiXinAppSecret;

            if(strlen($weiXinAppId)<=0 || strlen($weiXinAppSecret)<=0){
                die("wx app id or wx app secret is null");
            }


            $weiXinAccessToken = $siteConfigData->WeiXinAccessToken;

            $weiXinAccessTokenGetTime = $siteConfigData->WeiXinAccessTokenGetTime;

            //微信的access token保存时间是7200秒，所以判断当保存时间大约已经过了7000秒时，则重新请求token

            if(  strlen($weiXinAccessToken)<=0 || (time() - $weiXinAccessTokenGetTime)>7000){


                $urlGetAccessToken = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$weiXinAppId&secret=$weiXinAppSecret";

                    //执行 http 请求，返回 json

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $urlGetAccessToken);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//不直接输出，返回到变量

                    /**
                     * 正确时返回的JSON数据包如下：

                     * {"access_token":"ACCESS_TOKEN","expires_in":7200}
                     *
                     */

                    $curl_result = curl_exec($ch); //json

                    $arrResult = Format::FixJsonDecode($curl_result);



                    curl_close($ch);
                    //错误判断
                    if(isset($arrResult["errcode"])){
                        die($arrResult["errmsg"]);
                    }elseif(isset($arrResult["access_token"])){

                        //拿到了token,保存，并更新保存unix时间
                        $result = $arrResult["access_token"];
                        $siteConfigData->WeiXinAccessToken = $arrResult["access_token"];
                        $siteConfigData->WeiXinAccessTokenGetTime = time();

                    }


            }else{
                //已经有token了
                $result = $siteConfigData->WeiXinAccessToken;
            }




        }

        return $result;


    }

    private function RefreshWxAccessToken(SiteConfigData $siteConfigData,$weiXinAppId, $weiXinRefreshTokenOauth2){

        $url = "https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=$weiXinAppId&grant_type=refresh_token&refresh_token=$weiXinRefreshTokenOauth2";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//不直接输出，返回到变量
        $curl_result = curl_exec($ch); //json
        $arrResult = Format::FixJsonDecode($curl_result);

        curl_close($ch);
        //错误判断
        if(isset($arrResult["errcode"])){
            die($arrResult["errmsg"]);
        }elseif(isset($arrResult["access_token"])){

            //完成后，转到获取微信会员信息的接口

            $siteConfigData->WeiXinAccessTokenOauth2 = $arrResult["access_token"];
            $siteConfigData->WeiXinAccessTokenGetTimeOauth2 = time();

            $siteConfigData->WeiXinRefreshTokenOauth2 = $arrResult["refresh_token"];
            $siteConfigData->WeiXinRefreshTokenGetTimeOauth2 = time();

        }

    }


    /**
     * 微信网页的token
     */
    private function GetWxAccessTokenOauth2(){

        $siteId = parent::GetSiteIdByDomain();


        if($siteId>0){

            $userId = Control::GetUserId();
            $userPluginsPublicData = new UserPluginsPublicData();
            $wxOpenId = $userPluginsPublicData->GetWxOpenId($userId, false);

            $wxSubscribe = $userPluginsPublicData->GetWxSubscribe($userId, false);

            //die($wxOpenId.'|'.$wxSubscribe);

            if(strlen($wxOpenId)>0 && $wxSubscribe>0){//已经授权过并且已经关注了，直接转向

                //Control::GoUrl("/default.php?mod=lottery&a=default&temp=lottery_1");

                if($siteId == 1){
                    Control::GoUrl("/default.php?mod=lottery&a=default&temp=lottery_1");
                }else{
                    //echo '<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1" /><br /><br />
                    //        <center><h2>绑定成功，<a href="/default.php?mod=user&a=center">【返回】</a></h2></center>';
                    Control::GoUrl("/default.php?mod=user&a=center");
                }

                return;

            }




            $sitePublicData = new SitePublicData();

            $siteUrl = $sitePublicData->GetSiteUrl($siteId, true);

            $siteConfigData = new SiteConfigData($siteId);

            $weiXinAppId = $siteConfigData->WeiXinAppId;
            $weiXinAppSecret = $siteConfigData->WeiXinAppSecret;

            if(strlen($weiXinAppId)<=0 || strlen($weiXinAppSecret)<=0){
                die("wx app id or wx app secret is null");
            }


            $weiXinAccessTokenOauth2 = $siteConfigData->WeiXinAccessTokenOauth2;

            $weiXinAccessTokenGetTimeOauth2 = $siteConfigData->WeiXinAccessTokenGetTimeOauth2;

            $weiXinRefreshTokenOauth2 = $siteConfigData->WeiXinRefreshTokenOauth2;
            $weiXinRefreshTokenGetTimeOauth2 = $siteConfigData->WeiXinRefreshTokenGetTimeOauth2;

            //微信的access token保存时间是7200秒，所以判断当保存时间大约已经过了7000秒时，则重新请求token

            //if(  strlen($weiXinAccessTokenOauth2)<=0 || (time() - $weiXinAccessTokenGetTimeOauth2)>7000){

                echo "get access token...";

                $code = Control::GetRequest("code", "");

                if(strlen($code)>0){
                    //已经通过snsapi_userinfo拿到了code
                    //获取code后，请求以下链接获取access_token：
                    $urlGetAccessToken = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$weiXinAppId&secret=$weiXinAppSecret&code=$code&grant_type=authorization_code";

                    //执行 http 请求，返回 json

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $urlGetAccessToken);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//不直接输出，返回到变量

                    /**
                     * 正确时返回的JSON数据包如下：

                     * {
                     * "access_token":"ACCESS_TOKEN",
                     * "expires_in":7200,
                     * "refresh_token":"REFRESH_TOKEN",
                     * "openid":"OPENID",
                     * "scope":"SCOPE",
                     * "unionid": "o6_bmasdasdsad6_2sgVt7hMZOPfL"
                     * }
                     */

                    $curl_result = curl_exec($ch); //json

                    $arrResult = Format::FixJsonDecode($curl_result);
                    curl_close($ch);
                    //错误判断
                    if(isset($arrResult["errcode"])){
                        die($arrResult["errmsg"]);
                    }elseif(isset($arrResult["access_token"])){

                        //拿到了token,保存，并更新保存unix时间

                        $siteConfigData->WeiXinAccessTokenOauth2 = $arrResult["access_token"];
                        $siteConfigData->WeiXinAccessTokenGetTimeOauth2 = time();

                        $siteConfigData->WeiXinRefreshTokenOauth2 = $arrResult["refresh_token"];
                        $siteConfigData->WeiXinRefreshTokenGetTimeOauth2 = time();

                        //完成后，转到获取微信会员信息的接口

                        $openId = $arrResult["openid"];

                        $redirectUri = "/default.php?mod=user&a=bind_wx_open_id&wx_open_id=$openId&auto_create=1";
                        header("Location: $redirectUri");
                    }



                }else{
                    if(strlen($siteUrl)>0){
                        $redirectUri = urlencode("$siteUrl/default.php?mod=user&a=get_wx_access_token_oauth2");

                        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$weiXinAppId&redirect_uri=$redirectUri&response_type=code&scope=snsapi_userinfo&state=$siteId#wechat_redirect";

                        header("Location: $url");

                    }
                }
/**
            }else{
                //已经有token了，通过refresh token 取得 openid

                $url = "https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=$weiXinAppId&grant_type=refresh_token&refresh_token=$weiXinRefreshTokenOauth2";

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//不直接输出，返回到变量
                $curl_result = curl_exec($ch); //json
                $arrResult = Format::FixJsonDecode($curl_result);

                curl_close($ch);
                //错误判断
                if(isset($arrResult["errcode"])){
                    die($arrResult["errmsg"]);
                }elseif(isset($arrResult["access_token"])){

                    //完成后，转到获取微信会员信息的接口

                    $openId = $arrResult["openid"];

                    die($openId);

                    $redirectUri = "/default.php?mod=user&a=bind_wx_open_id&wx_open_id=$openId&auto_create=1";
                    header("Location: $redirectUri");
                }

            }
*/



        }


    }

    /**
     * 把微信openid和当前登录的会员绑定
     */
    private function BindWxOpenId(){

        $siteId = parent::GetSiteIdByDomain();

        $wxOpenId = Control::GetRequest("wx_open_id","");

        $autoCreate = Control::GetRequest("auto_create",0); //没有登录时，是否自动创建会员

        $userGroupId = Control::PostOrGetRequest("user_group_id","");

        if(strlen($wxOpenId)>0){
            $userPublicData = new UserPublicData();
            //是否已经绑定
            $userPluginsPublicData = new UserPluginsPublicData();
            $userId = $userPluginsPublicData->GetUserId($wxOpenId, false);

            if($userId<=0){ //没有绑定

                $userId = Control::GetUserId();

                if($userId<=0){ //没有登录，转到登录页面

                    if($autoCreate <=0){ //不自动创建会员
                        $reUrl = urlencode("/default.php?mod=user&a=bind_wx_open_id&wx_open_id=$wxOpenId");

                        $redirectUri = "/default.php?mod=user&a=login&re_url=$reUrl";
                        header("Location: $redirectUri");
                    }else{
                        //自动创建会员


                        $userPass = "111111";
                        $regIp = Control::GetIp();
                        $userName = uniqid();
                        $userId = $userPublicData->Create($siteId, $userPass, $regIp, $userName);

                        if($userId>0){
                            $userPluginsPublicData->Create($userId, $wxOpenId, $siteId);
                            $siteConfigData = new SiteConfigData($siteId);
                            self::CreateUserEx($siteId, $userId, $userName, $siteConfigData, $userGroupId);
                        }else{
                            die("auto create user failure;");
                        }

                    }




                }else{

                    //已经登录，直接绑定
                    $result = $userPluginsPublicData->Create($userId, $wxOpenId, $siteId);


                }



            }else{

                //已经绑定
                //重写cookie
                $userName = $userPublicData->GetUserName($userId, false);
                Control::SetUserCookie($userId, $userName);

            }



        }else{

            die("wx open id is null");

        }


        Control::GoUrl("/default.php?mod=user&a=get_wx_user_info");

    }



    private function GetWxUserInfo(){
        $result = "";

        $siteId = parent::GetSiteIdByDomain();

        $accessToken = self::GetWxAccessToken();

        $userId = Control::GetUserId();

        if($siteId>0 && $userId>0 && strlen($accessToken)>0){

            $userPluginsPublicData = new UserPluginsPublicData();
            $wxOpenId = $userPluginsPublicData->GetWxOpenId($userId, false);

            if(strlen($wxOpenId)>0){


            }else{

                die("wx open id is null");

            }


            if(strlen($accessToken)>0 && strlen($wxOpenId)>0){

                $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$accessToken&openid=$wxOpenId&lang=zh_CN";

                //执行 http 请求，返回 json

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//不直接输出，返回到变量

                /**
                 * 正确时返回的JSON数据包如下：

                 * {
                "subscribe": 1,
                "openid": "o6_bmjrPTlm6_2sgVt7hMZOPfL2M",
                "nickname": "Band",
                "sex": 1,
                "language": "zh_CN",
                "city": "广州",
                "province": "广东",
                "country": "中国",
                "headimgurl":    "http://wx.qlogo.cn/mmopen/g3MonUZtNHkdmzicIlibx6iaFqAc56vxLSUfpb6n5WKSYVY0ChQKkiaJSgQ1dZuTOgvLLrhJbERQQ4eMsv84eavHiaiceqxibJxCfHe/0",
                "subscribe_time": 1382694957,
                "unionid": " o6_bmasdasdsad6_2sgVt7hMZOPfL"
                "remark": "",
                "groupid": 0
                }
                 *
                 */

                $curl_result = curl_exec($ch); //json

                $arrResult = Format::FixJsonDecode($curl_result);


                //错误判断
                if(isset($arrResult["errcode"])){
                    die($arrResult["errcode"].',errmsg:'.$arrResult["errmsg"]);
                }elseif(isset($arrResult["openid"])){
                    //正确返回

                    $wxNickName = isset($arrResult["nickname"])?$arrResult["nickname"]:"";//
                    $wxSex = isset($arrResult["sex"])?$arrResult["sex"]:"";//
                    $wxProvince = isset($arrResult["province"])?$arrResult["province"]:"";//
                    $wxCity = isset($arrResult["city"])?$arrResult["city"]:"";//
                    $wxCountry = isset($arrResult["country"])?$arrResult["country"]:"";//
                    $wxHeadImgUrl = isset($arrResult["headimgurl"])?$arrResult["headimgurl"]:"";//
                    $wxUnionId = isset($arrResult["unionid"])?$arrResult["unionid"]:"";//
                    $wxSubscribe = isset($arrResult["subscribe"])?$arrResult["subscribe"]:"";//
                    $wxSubscribeTime = isset($arrResult["subscribe_time"])?$arrResult["subscribe_time"]:"";//
                    $wxRemark = isset($arrResult["remark"])?$arrResult["remark"]:"";//
                    $wxGroupId = isset($arrResult["groupid"])?$arrResult["groupid"]:"";//


                    if(strlen($wxNickName)>0){
                        $userPluginsPublicData->Modify(
                            $userId,
                            $wxNickName,
                            $wxSex,
                            $wxProvince,
                            $wxCity,
                            $wxCountry,
                            $wxHeadImgUrl,
                            $wxUnionId,
                            $wxSubscribe,
                            $wxSubscribeTime,
                            $wxRemark,
                            $wxGroupId
                        );
                    }



                    if(intval($wxSubscribe) == 1){
                        if($siteId == 1){
                            Control::GoUrl("/default.php?mod=lottery&a=default&temp=lottery_1");
                        }else{
                            //echo '<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1" /><br /><br />
                            //<center><h2>绑定成功，<a href="/default.php?mod=user&a=center">【返回】</a></h2></center>';
                            Control::GoUrl("/default.php?mod=user&a=center");
                        }

                    }else{
                        Control::GoUrl("/default.php?mod=lottery&a=default&temp=lottery_gz");
                    }


                }

                curl_close($ch);



            }else{

                die("参数错误");

            }





        }else{


            Control::GoUrl("/default.php?mod=lottery&a=default&temp=lottery_gz");



        }

        return $result;
    }

    private function GenLogin(){

        $siteId = parent::GetSiteIdByDomain();

        $temp = Control::GetRequest("temp","");
        if($temp == "forum"){
            $templateContent = parent::GetDynamicTemplateContent("user_login_for_forum", $siteId);
        }
        else{
            $templateContent = parent::GetDynamicTemplateContent("user_login", $siteId);
        }

        parent::ReplaceFirst($templateContent);
        $reUrl = Control::GetRequest("re_url", "");//这个re_url在前台已经encode过了
        $templateContent = str_ireplace("{ReUrl}", $reUrl, $templateContent);

        parent::ReplaceEnd($templateContent);

        return $templateContent;
    }

    private function GenLogout(){
        Control::DelUserCookie();
        session_start();
        session_destroy();
        $reUrl = Control::GetRequest("re_url", "");

        if(strlen($reUrl)>0){

            $reUrl = str_ireplace("&amp;","&",$reUrl);

            Control::GoUrl(urldecode($reUrl));
        }else{
            Control::GoUrl("/");
        }

    }


    private function GenRegister(){

        $siteId = parent::GetSiteIdByDomain();

        $temp = Control::GetRequest("temp","");
        if($temp == "forum"){
            $templateContent = parent::GetDynamicTemplateContent("user_register_for_forum", $siteId);
           // $templateFileUrl = "user/user_register_for_forum.html";
        }
        else{
            //$templateFileUrl = "user/user_register.html";
            $templateContent = parent::GetDynamicTemplateContent("user_register", $siteId);
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
        $userPass = Control::GetRequest("user_pass", "", false);
        $hour = Control::GetRequest("hour", 1);
        //$userPassWithMd5 = Control::GetRequest("user_pass_with_md5", "");
        $siteId = parent::GetSiteIdByDomain();

        if(!empty($userAccount) && !empty($userPass) && $siteId > 0){
            //$userPassWithMd5 = $userPass;
            $userPublicData = new UserPublicData();
            $userId = $userPublicData->Login($userAccount, $userPass);
            if($userId <= 0){
                return Control::GetRequest("jsonpcallback","").'({"result":'.self::ERROR_USER_PASS.'})';
            }else {
                $hour = 9999999;

                //


                Control::SetUserCookie($userId,$userAccount, $hour);
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
        $userPass = Format::FormatHtmlTag(Control::PostRequest("UserPass", "", false));
        $userGroupId = Format::FormatHtmlTag(Control::PostRequest("user_group_id", 0, false));
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
                    return Control::GetRequest("jsonpcallback", "") . '({"result":'.self::ERROR_FORMAT_USER_NAME.'})';
                }
            }

            if (!empty($userEmail)) {
                if (preg_match("/^([a-zA-Z0-9]*[-_]?[a-zA-Z0-9]+)*@([a-zA-Z0-9]*[-_]?[a-zA-Z0-9]+)+[\.][a-zA-Z0-9]{2,4}([\.][a-zA-Z0-9]{2,3})?$/u", $userEmail)) {
                    $isSameEmail = self::IsRepeatUserEmail($userEmail);
                    if ($isSameEmail) { //检查是否相同的邮箱
                        return Control::GetRequest("jsonpcallback", "") . '({"result":'.self::ERROR_REPEAT_USER_EMAIL.'})';
                    }
                } else {
                    return Control::GetRequest("jsonpcallback", "") . '({"result":'.self::ERROR_FORMAT_USER_EMAIL.'})';
                }
            }

            if (!empty($userMobile)) {
                if (preg_match("/^13\d{9}$|^15\d{9}$|^18\d{9}$/", $userMobile)) {
                    $isSameMobile = self::IsRepeatUserMobile($userMobile);
                    if ($isSameMobile) { //检查是否有相同的手机号码
                        return Control::GetRequest("jsonpcallback", "") . '({"result":'.self::ERROR_REPEAT_USER_MOBILE.'})';
                    }
                } else {
                    return Control::GetRequest("jsonpcallback", "") . '({"result":'.self::ERROR_FORMAT_USER_MOBILE.'})';
                }
            }
            //~~~~~~~~~~~~~~结束~~~~~~~~~~~~~~~~~
            $userPublicData = new UserPublicData();
            $siteConfigData = new SiteConfigData($siteId);


            $newUserId = $userPublicData->Create($siteId, $userPass, $regIp, $userName, $userEmail, $userMobile);
            if ($newUserId > 0) {

                self::CreateUserEx($siteId, $newUserId, $userName, $siteConfigData,$userGroupId);

                return Control::GetRequest("jsonpcallback", "") . '({"result":'.self::SUCCESS_REGISTER.'})';
            } else {
                return Control::GetRequest("jsonpcallback", "") . '({"result":'.self::ERROR_FAIL_REGISTER.'})';
            }
        } else {
            return Control::GetRequest("jsonpcallback", "") . '({"result":'.self::ERROR_PARAMETER.'})';
        }
    }


    private function CreateUserEx($siteId, $userId, $userName, $siteConfigData,$userGroupId){

        $userInfoPublicData = new UserInfoPublicData();
        $userRolePublicData = new UserRolePublicData();

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
        $lastVisitIP = Control::GetIp();
        $lastVisitTime = strval(date('Y-m-d H:i:s', time()));
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
        $schoolName = Format::FormatHtmlTag(Control::PostOrGetRequest("school_name",0));
        $className = Format::FormatHtmlTag(Control::PostOrGetRequest("class_name",0));

        $userInfoPublicData->Create(
            $userId,
            $realName,
            $nickName,
            $avatarUploadFileId,
            $userScore,
            $userMoney,
            $userCharm,
            $userExp,
            $userPoint,
            $question,
            $answer,
            $sign,
            $lastVisitIP,
            $lastVisitTime,
            $email,
            $qq,
            $country,
            $comeFrom,
            $honor,
            $birthday,
            $gender,
            $fansCount,
            $idCard,
            $postCode,
            $address,
            $tel,
            $mobile,
            $province,
            $occupational,
            $city,
            $relationship,
            $hit,
            $messageCount,
            $userPostCount,
            $userPostBestCount,
            $userActivityCount,
            $userAlbumCount,
            $userBestAlbumCount,
            $userRecAlbumCount,
            $userAlbumCommentCount,
            $userCommissionOwn,
            $userCommissionChild,
            $userCommissionGrandson,
            $schoolName,
            $className
        );


        if($userGroupId > 0){
            $userRolePublicData->Init($userId, $siteId, $userGroupId);
        }else{
            //插入会员角色表
            $newMemberGroupId = $siteConfigData->UserDefaultUserGroupIdForRole;
            $userRolePublicData->Init($userId, $siteId, $newMemberGroupId);
        }


        Control::SetUserCookie($userId, $userName);

    }


    /**
     * 临时自动注册
     */
    private function TempRegister(){
        $siteId = parent::GetSiteIdByDomain();
        $userMobile = Format::FormatHtmlTag(Control::PostOrGetRequest("UserMobile", "", false));
        $userName=$userMobile;
        $userPass = "111111";
        $userGroupId = Format::FormatHtmlTag(Control::PostRequest("user_group_id", 0, false));
        $regIp = Control::GetIp();
        $createDate = strval(date('Y-m-d H:i:s', time()));
        if ($siteId > 0 && (!empty($userMobile)) && !empty($userPass) && !empty($regIp)) {
            //重名注册检查以及格式匹配检查
            //~~~~~~~~~~~~~~开始~~~~~~~~~~~~~~~~~
            if (!empty($userMobile)) {
                if (preg_match("/^13\d{9}$|^15\d{9}$|^18\d{9}$/", $userMobile)) {
                    $isSameMobile = self::IsRepeatUserMobile($userMobile);
                    if ($isSameMobile) { //已有号码  直接登录
                        $userPublicData=new UserPublicData();
                        $userId = $userPublicData->Login($userMobile, $userPass);
                        if($userId <= 0){
                            return Control::GetRequest("jsonpcallback","").'({"result":'.self::ERROR_USER_PASS.'})';
                        }else {
                            $hour = 9999999;//直接登录
                            Control::SetUserCookie($userId,$userMobile, $hour);
                            return Control::GetRequest("jsonpcallback", "") . '({"result":'.self::SUCCESS_REGISTER.'})';
                        }
                    }
                } else {
                    return Control::GetRequest("jsonpcallback", "") . '({"result":'.self::ERROR_FORMAT_USER_MOBILE.'})';
                }
            }
            //~~~~~~~~~~~~~~结束~~~~~~~~~~~~~~~~~
            $userPublicData = new UserPublicData();
            $siteConfigData = new SiteConfigData($siteId);
            $newUserId = $userPublicData->Create($siteId, $userPass, $regIp, $userName, "", $userMobile);
            if ($newUserId > 0) {

                self::CreateUserEx($siteId, $newUserId, $userName, $siteConfigData,$userGroupId);

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


    private function GenCenter(){
        $userId =Control::GetUserId();
        $siteId = parent::GetSiteIdByDomain();

        if($userId > 0 && $siteId > 0){

            $templateContent = parent::GetDynamicTemplateContent("user_center");
            parent::ReplaceFirst($templateContent);


            $userInfoPublicData = new UserInfoPublicData();

            //--------替换会员信息--------begin
            $arrUserOne = $userInfoPublicData->GetOne($userId,$siteId);
            Template::ReplaceOne($templateContent,$arrUserOne);
            //------------------------------end

            $templateContent = str_ireplace("{SiteId}", $siteId, $templateContent);

            $templateContent = str_ireplace("{CurrentUrl}",urlencode($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']), $templateContent);

            $templateContent = parent::ReplaceTemplate($templateContent);
            parent::ReplaceSiteConfig($siteId,$templateContent);
            parent::ReplaceEnd($templateContent);
            return $templateContent;
        }else{
            Control::GoUrl("/default.php?mod=user&a=login&re_url=".urlencode($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']));
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
    private function GenModifyUserPass(){
        $userId = Control::GetUserId();
        if($userId > 0){
            $siteId = parent::GetSiteIdByDomain();
            //$templateFileUrl = "user/user_pass_modify.html";
            //$templateName = "default";
            //$templatePath = "front_template";
            //$templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);
            $templateContent = parent::GetDynamicTemplateContent("user_pass_modify",$siteId);
            parent::ReplaceFirst($templateContent);


            $templateContent = str_ireplace("{UserId}", strval($userId), $templateContent);
            parent::ReplaceSiteConfig($siteId,$templateContent);
            parent::ReplaceEnd($templateContent);
            return $templateContent;
        }else{
            return "";
        }
    }


    private function GenRetrieveUserPass()
    {
        $siteId = parent::GetSiteIdByDomain();

        $templateContent = parent::GetDynamicTemplateContent("user_password_forget", $siteId);
        parent::ReplaceFirst($templateContent);
        parent::ReplaceSiteConfig($siteId, $templateContent);
        parent::ReplaceEnd($templateContent);
        return $templateContent;
    }



    /**
     * 会员前台密码修改
     * @return string
     */
    private function AsyncModifyUserPass() {
        $result = -1;
        $oldPassWord = Control::GetRequest("old_password", "");
        $newPassWord = Control::GetRequest("new_password", "");
        $userId = Control::GetUserID();
        if ($userId > 0) {
            if (strlen($newPassWord) > 1) {
                $userData = new UserPublicData();
                $checkPassWord = $userData->CheckPassWord($userId, $oldPassWord);
                if ($checkPassWord > 0) {
                    $result = $userData->ModifyPassword($userId, $newPassWord);
                }
                else {
                    $result=-3;
                }
            }
        } else {
            $result=-2;//未登录
        }
        return Control::GetRequest("jsonpcallback","").'({"result":"'.$result.'"})';
    }

    /**
     * 会员前台密码修改
     * @return string
     */
    private function AsyncFindUserPassByEmail()
    {
        $result = -1;//参数错误
        $userAccount = Control::GetRequest("user_account", "");
        $siteId = parent::GetSiteIdByDomain();
        if (!empty($userAccount)) {
            $userInfoList = null;
            $userPublicData = new UserPublicData();
            $userInfoList = $userPublicData->GetListByUserAccount($userAccount);
            if (count($userInfoList) > 0) {
                foreach ($userInfoList as $value) {
                    $userEmail = $value["UserEmail"];
                    $email = $value["Email"];
                    if (!empty($userEmail)) {
                        $result = parent::Mail($siteId, $userEmail, "来自长沙晚报网的密码找回邮件", "您的密码是：".$value["UserPass"]);
                    } else if (!empty($email)) {
                        $result = parent::Mail($siteId, $email, "来自长沙晚报网的密码找回邮件", "您的密码是：".$value["UserPass"]);
                    }else {
                        $result=-3;//用户未填写邮箱
                    }
                }
            } else {
                $result = -2; //用户名不存在
            }
        }
        return Control::GetRequest("jsonpcallback", "") . '({"result":"' . $result . '"})';
    }

}

?>
