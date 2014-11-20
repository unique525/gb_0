<?php

/**
 * 前台 会员 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_User
 * @author yin
 */
class UserInfoPublicGen extends BasePublicGen implements IBasePublicGen
{

    const ASYNC_SUCCESS = 1;
    const ASYNC_FAILED = 0;

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenPublic()
    {
        $result = "";
        $action = Control::GetRequest("a", "");
        switch ($action) {
            case "modify":
                $result = self::GenModify();
                break;
            case "modify_avatar": //生成修改头像界面
                $result = self::GenModifyAvatar();
                break;
            case "async_modify_avatar_upload_file_id": //生成头像并插入数据库
                $result = self::AsyncModifyAvatarUploadFileId();
                break;
            case "async_get_avatar_upload_file_id":
                $result = self::AsyncGetAvatarUploadFileId();
                break;
        }

        $result = str_ireplace("{action}", $action, $result);

        return $result;
    }

    private function GenModify()
    {
        $userId = Control::GetUserId();
        $siteId = parent::GetSiteIdByDomain();
        if ($userId > 0 && $siteId > 0) {
            $templateFileUrl = "user/user_info_modify.html";
            $templateName = "default";
            $templatePath = "front_template";
            $templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);
            parent::ReplaceFirst($templateContent);

            $userInfoPublicData = new UserInfoPublicData();

            $isExist = $userInfoPublicData->CheckIsExist($userId, $siteId);

            if ($isExist == 0) { //检查这个会员是否创建了会员详细信息，如果没有 就创建
                $userInfoPublicData->Create($userId);
            }
            $arrUserInfoOne = $userInfoPublicData->GetOne($userId, $siteId);
            if (!empty($arrUserInfoOne) > 0) {
                Template::ReplaceOne($templateContent, $arrUserInfoOne);
            } else {
                $templateContent = Language::Load('user_info', 7);
            }

            if (!empty($_POST)) {
                $nickName = Control::PostRequest("NickName", "");
                $realName = Control::PostRequest("RealName", "");
                $email = Control::PostRequest("Email", "");
                $qq = Control::PostRequest("QQ", "");
                $comeFrom = Control::PostRequest("ComeFrom", "");
                $birthday = Control::PostRequest("Birthday", "0000-00-00");
                $idCard = Control::PostRequest("IdCard", "");
                $address = Control::PostRequest("Address", "");
                $postCode = Control::PostRequest("PostCode", "");
                $mobile = Control::PostRequest("Mobile", "");
                $tel = Control::PostRequest("Tel", "");
                $province = Control::PostRequest("Province", "");
                $city = Control::PostRequest("City", "");
                $sign = Control::PostRequest("Sign", "");
                $gender = Control::PostRequest("Gender", "");
//                $bankName = Control::PostRequest("bank_name","");
//                $bankOpenAddress = Control::PostRequest("bank_open_address","");
//                $bankUserName = Control::PostRequest("bank_user_name","");
//                $bankAccount = Control::PostRequest("bank_account","");


                $result = $userInfoPublicData->Modify($userId, $nickName, $realName, $email, $qq,
                    $comeFrom, $birthday, $idCard, $address, $postCode,
                    $mobile, $tel, $province, $city, $sign, $gender
                );


                //加入操作日志
                $operateContent = 'Modify User,POST FORM:' . implode('|', $_POST) . ';\r\nResult:' . $result;
                self::CreateManageUserLog($operateContent);

                if ($result > 0) {
                    $jsCode = 'alert("修改成功");parent.location.href=parent.location.href';
                    Control::RunJavascript($jsCode);
                } else {
                    $jsCode = 'alert("修改失败");';
                    Control::RunJavascript($jsCode);
                }
            }
            parent::ReplaceEnd($templateContent);
            return $templateContent;
        } else {
            Control::GoUrl("");
            return null;
        }
    }

    /**
     * @return mixed|string
     */
    private function GenModifyAvatar()
    {
        $userId = Control::GetUserId();
        $siteId =parent::GetSiteIdByDomain();
        if ($userId > 0) {
            $templateFileUrl = "user/user_modify_avatar.html";
            $templateName = "default";
            $templatePath = "front_template";
            $templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);
            parent::ReplaceFirst($templateContent);


            $templateContent = str_ireplace("{UserId}", strval($userId), $templateContent);
            parent::ReplaceSiteConfig($siteId,$templateContent);
            parent::ReplaceEnd($templateContent);
            return $templateContent;
        } else {
            Control::GoUrl("/default.php?mod=user&a=login");
            return "";
        }
    }

    /**
     *
     * @return string
     */
    private function AsyncGetAvatarUploadFileId()
    {
        $result = -1;
        $userId = Control::GetUserId();
        if ($userId > 0) {
            $userInfoPublicData = new UserInfoPublicData();
            $result = $userInfoPublicData->GetAvatarUploadFileId($userId, true);
        }
        return Control::GetRequest("jsonpcallback", "") . '({"result":"' . $result . '"})';
    }

    private function AsyncModifyAvatarUploadFileId()
    {
        $userId = Control::GetUserId();
        $result = -1;
        if ($userId > 0) {
            $uploadFileId = Control::GetRequest("upload_file_id", "");
            if ($uploadFileId > 0) {
                $userInfoPublicData = new UserInfoPublicData();
                $result = $userInfoPublicData->ModifyAvatarUploadFileId($userId, $uploadFileId);
            }
        }
        return Control::GetRequest("jsonpcallback","").'({"result":'.$result.'})';
    }
}

?>
