<?php

/**
 * 前台 会员 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_User
 * @author yin
 */
class UserInfoPublicGen extends BasePublicGen implements IBasePublicGen
{

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
        }

        $result = str_ireplace("{action}", $action, $result);

        return $result;
    }

    private function GenModify(){
        $userId = Control::GetUserId();
        $siteId = parent::GetSiteIdByDomain();
        if($userId > 0 && $siteId > 0){
            $templateFileUrl = "/user/user_info_modify.html";
            $templateName = "default";
            $templatePath = "front_template";
            $templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);
            parent::ReplaceFirst($templateContent);

            $userInfoPublicData = new UserInfoPublicData();

            $isExist = $userInfoPublicData->CheckIsExist($userId,$siteId);

            if($isExist == 0){//检查这个会员是否创建了会员详细信息，如果没有 就创建
                $userInfoPublicData->Create($userId);
            }
            $arrUserInfoOne = $userInfoPublicData->GetOne($userId,$siteId);
            if(!empty($arrUserInfoOne) > 0){
                Template::ReplaceOne($templateContent,$arrUserInfoOne);
            }else{
                $templateContent =Language::Load('user_info', 7);
            }

            if(!empty($_POST)){
                $nickName = Control::PostRequest("nick_name","");
                $realName = Control::PostRequest("real_name","");
                $email = Control::PostRequest("email","");
                $qq = Control::PostRequest("qq","");
                $comeFrom = Control::PostRequest("come_from","");
                $birthday = Control::PostRequest("birthday","0000-00-00");
                $idCard = Control::PostRequest("id_card","");
                $address = Control::PostRequest("address","");
                $postCode = Control::PostRequest("post_code","");
                $mobile = Control::PostRequest("mobile","");
                $tel = Control::PostRequest("tel","");
                $province = Control::PostRequest("province","");
                $city = Control::PostRequest("city","");
                $sign = Control::PostRequest("sign","");
                $gender = Control::PostRequest("gender","");
                $bankName = Control::PostRequest("bank_name","");
                $bankOpenAddress = Control::PostRequest("bank_open_address","");
                $bankUserName = Control::PostRequest("bank_user_name","");
                $bankAccount = Control::PostRequest("bank_account","");


                $result = $userInfoPublicData->Modify($userId);

                //加入操作日志
                $operateContent = 'Modify User,POST FORM:'.implode('|',$_POST).';\r\nResult:'.$result;
                self::CreateManageUserLog($operateContent);

                if($result > 0){
                    $jsCode = 'alert("修改成功");parent.location.href=parent.location.href';
                    Control::RunJavascript($jsCode);
                    //$resultJavaScript .= Control::GetJqueryMessage(Language::Load('user', 7));
                }else{
                    $jsCode = 'alert("修改失败");';
                    Control::RunJavascript($jsCode);
                    //$resultJavaScript .= Control::GetJqueryMessage(Language::Load('user', 8));
                }
            }
            parent::ReplaceEnd($templateContent);
            return $templateContent;
        }else{
            Control::GoUrl("");
            return null;
        }
    }
}

?>
