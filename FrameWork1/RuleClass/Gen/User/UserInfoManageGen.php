<?php

/**
 * 后台管理 会员 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_User
 * @author zhangchi
 */
class UserInfoManageGen extends BaseManageGen implements IBaseManageGen {
    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function Gen() {
        $result = "";
        $method = Control::GetRequest("m", "");
        switch ($method) {
            case "modify":
                $result = self::GenModify();
                break;
            case "remove_to_bin":
                $result = self::GenRemoveToBin();
                break;
            case "list":
                $result = self::GenList();
                break;
            case "async_modify_state":
                $result = self::AsyncModifyState();
                break;
        }
        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    private function GenModify(){
        $userId = Control::GetRequest("user_id",0);
        $siteId = Control::GetRequest("site_id",0);
        if($userId > 0 && $siteId > 0){
            $templateContent = Template::Load("user/user_info_deal.html","common");
            $resultJavaScript = "";
            $userInfoManageData = new UserInfoManageData();

            $isExist = $userInfoManageData->CheckIsExist($userId,$siteId);

            if($isExist == 0){//检查这个会员是否创建了会员详细信息，如果没有 就创建
                $userInfoManageData->Create($userId);
            }
            $arrUserInfoOne = $userInfoManageData->GetOne($userId,$siteId);
            if(!empty($arrUserInfoOne) > 0){
                Template::ReplaceOne($templateContent,$arrUserInfoOne);
            }else{
                $templateContent =Language::Load('user_info', 7);
                return $templateContent;
            }

            if(!empty($_POST)){
                $httpPostData = Format::FormatHtmlTagInPost($_POST);

                $result = $userInfoManageData->Modify($httpPostData,$userId);

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
            $templateContent = str_ireplace("{UserId}", $userId, $templateContent);
            $templateContent = str_ireplace("{SiteId}", $siteId, $templateContent);
            parent::ReplaceEnd($templateContent);
            $templateContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $templateContent);
            return $templateContent;
        }else{
            return null;
        }
    }
}

?>
