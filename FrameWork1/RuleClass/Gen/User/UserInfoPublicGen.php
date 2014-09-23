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
        $method = Control::GetRequest("a", "");
        switch ($method) {
            case "modify":
                $result = self::GenModify();
                break;
        }

        $result = str_ireplace("{method}", $method, $result);

        return $result;
    }

    public function GenModify(){
        $userId = Control::GetUserId();
        $siteId = Control::GetRequest("site_id",0);
        if($userId > 0 && $siteId > 0){
            $templateFileUrl = "user/user_info_deal.html";
            $templateName = "default";
            $templatePath = "front_template";
            $templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);

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
                $httpPostData = Format::FormatHtmlTagInPost($_POST);

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

            return $templateContent;
        }else{
            Control::GoUrl("");
            return null;
        }
    }
}

?>
