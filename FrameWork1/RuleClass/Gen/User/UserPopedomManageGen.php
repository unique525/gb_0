<?php

/**
 * 后台管理 会员权限 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_User
 * @author zhangchi
 */
class UserPopedomManageGen extends BaseManageGen implements IBaseManageGen
{

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function Gen()
    {
        $result = "";
        $method = Control::GetRequest("m", "");
        switch ($method) {
            case "modify":
                $result = self::GenModify();
                break;
        }
        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    private function GenModify()
    {

        $userId = intval(Control::GetRequest("user_id", 0));
        $userGroupId = intval(Control::GetRequest("user_group_id", 0));
        $siteId = intval(Control::GetRequest("site_id", 0));

        $manageUserId = Control::GetManageUserId();

        $resultJavaScript = "";

        $templateContent = Template::Load("user/user_popedom_deal.html", "common");
        parent::ReplaceFirst($templateContent);
        ///////////////判断是否有操作权限///////////////////
        $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
        $channelId = 0;
        $can = $manageUserAuthorityManageData->CanUserEdit($siteId, $channelId, $manageUserId);
        ////////////////////////////////////////////////////
        if (!$can) {
            $templateContent = Language::Load('user', 28);
        } else {

        }

        if($userGroupId>0){
            //对会员组授权



        }elseif($userId>0){
            //对会员授权
        }



        parent::ReplaceEnd($templateContent);


        $templateContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $templateContent);
        return $templateContent;


    }

} 