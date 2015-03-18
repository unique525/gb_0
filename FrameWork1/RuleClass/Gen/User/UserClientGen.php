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

    private function GenLogin()
    {
        $result = "[{}]";

        $userAccount = Format::FormatHtmlTag(Control::PostOrGetRequest("UserAccount", ""));
        $userPass = Format::FormatSql(Control::PostOrGetRequest("UserPass", ""));
        $regIp = Control::GetIp();

        if (strlen($userAccount) > 0
            && !empty($userPass)
            && !empty($regIp)
        ) {

            $userClientData = new UserClientData();

            $userId = $userClientData->Login($userAccount, $userPass);

            if($userId <= 0){
                $resultCode = UserBaseGen::LOGIN_ERROR_USER_PASS;
            }else {
                $resultCode = UserBaseGen::LOGIN_SUCCESS;
                $withCache = FALSE;
                $arrUserOne = $userClientData->GetOne($userId,$withCache);

                //将user pass 进行md5加密返回
                if($arrUserOne["UserPass"]){
                    $arrUserOne["UserPass"] = md5($arrUserOne["UserPass"]);
                }

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
                        //插入会员信息表

                        $realName = Format::FormatHtmlTag(Control::PostOrGetRequest("real_name",""));
                        $nickName = Format::FormatHtmlTag(Control::PostOrGetRequest("nick_name",""));
                        $avatarUploadFileId = Format::FormatHtmlTag(Control::PostOrGetRequest("avatar_upload_fileId",0));
                        $userScore = Format::FormatHtmlTag(Control::PostOrGetRequest("user_score",0));
                        $userMoney = Format::FormatHtmlTag(Control::PostOrGetRequest("user_money",0));
                        $userCharm = Format::FormatHtmlTag(Control::PostOrGetRequest("user_charm",0));
                        $userExp = Format::FormatHtmlTag(Control::PostOrGetRequest("user_exp",0));
                        $userPoint = Format::FormatHtmlTag(Control::PostOrGetRequest("user_point",0));
                        $question = Format::FormatHtmlTag(Control::PostOrGetRequest("question",""));
                        $answer = Format::FormatHtmlTag(Control::PostOrGetRequest("answer",""));
                        $sign = Control::PostOrGetRequest("sign","");
                        $lastVisitIP = $regIp;
                        $lastVisitTime = strval(date('Y-m-d H:i:s', time()));
                        $email = Format::FormatHtmlTag(Control::PostOrGetRequest("email",""));
                        $qq = Format::FormatHtmlTag(Control::PostOrGetRequest("qq",""));
                        $country = Format::FormatHtmlTag(Control::PostOrGetRequest("country",""));
                        $comeFrom = Format::FormatHtmlTag(Control::PostOrGetRequest("come_from",""));
                        $honor = Format::FormatHtmlTag(Control::PostOrGetRequest("honor",""));
                        $birthday = Format::FormatHtmlTag(Control::PostOrGetRequest("birthday",""));
                        $gender = Format::FormatHtmlTag(Control::PostOrGetRequest("gender",0));
                        $fansCount = Format::FormatHtmlTag(Control::PostOrGetRequest("fans_count",0));
                        $idCard = Format::FormatHtmlTag(Control::PostOrGetRequest("id_card",""));
                        $postCode = Format::FormatHtmlTag(Control::PostOrGetRequest("post_code",""));
                        $address = Format::FormatHtmlTag(Control::PostOrGetRequest("address",""));
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

                        $userInfoClientData->Create(
                            $newUserId,
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
                            $userCommissionGrandson
                        );


                        $newMemberGroupId = $siteConfigData->UserDefaultUserGroupIdForRole;
                        $userRoleClientData->Init($newUserId, $siteId, $newMemberGroupId); //插入会员角色表

                        $withCache = FALSE;
                        $arrUserOne = $userClientData->GetOne($newUserId,$withCache);
                        //将user pass 进行md5加密返回
                        if($arrUserOne["UserPass"]){
                            $arrUserOne["UserPass"] = md5($arrUserOne["UserPass"]);
                        }

                        $result = Format::FixJsonEncode($arrUserOne);

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