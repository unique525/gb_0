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
        $forumId = intval(Control::GetRequest("forum_id", 0));
        $channelId = intval(Control::GetRequest("channel_id", 0));

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

        $userPopedomManageData = new UserPopedomManageData();

        if(!empty($_POST)){

            //删除缓冲
            parent::DelAllCache();


            if($userGroupId>0){
                //对会员组授权

                //读取表单
                foreach ($_POST as $key => $value) {
                    if (strpos($key, "u_") === 0) { //
                        $arr = Format::ToSplit($key, '_');
                        if (count($arr) == 2) {
                            $userPopedomName = $arr[1];
                            //为数组则转化为逗号分割字符串,对应checkbox应用
                            if (is_array($value)) {
                                $value = implode(",", $value);
                            }
                            $value = stripslashes($value);

                            if($value=='on'){
                                $value = 1;
                            }else{
                                $value = 0;
                            }

                            $userPopedomManageData->SetValueBySiteIdAndUserGroupId(
                                $siteId,
                                $userGroupId,
                                $userPopedomName,
                                $value
                            );
                        }
                    }
                }



            }elseif($userId>0){
                //对会员授权

                //读取表单
                foreach ($_POST as $key => $value) {
                    if (strpos($key, "u_") === 0) { //
                        $arr = Format::ToSplit($key, '_');
                        if (count($arr) == 2) {
                            $userPopedomName = $arr[1];
                            //为数组则转化为逗号分割字符串,对应checkbox应用
                            if (is_array($value)) {
                                $value = implode(",", $value);
                            }
                            $value = stripslashes($value);

                            if($value=='on'){
                                $value = 1;
                            }else{
                                $value = 0;
                            }

                            $userPopedomManageData->SetValueBySiteIdAndUserId(
                                $siteId,
                                $userId,
                                $userPopedomName,
                                $value
                            );
                        }
                    }
                }
            }
        }

        $arrList = array();
        //load list data
        if ($userId > 0 && $forumId > 0) {
            $arrList = $userPopedomManageData->GetListByForumIdAndUserId(
                $forumId,
                $userId,
                false
            );
        } else if ($userGroupId > 0 && $forumId > 0) {
            $arrList = $userPopedomManageData->GetListByForumIdAndUserGroupId(
                $forumId,
                $userGroupId,
                false
            );
        } else if ($userId > 0 && $siteId > 0) {
            $arrList = $userPopedomManageData->GetListBySiteIdAndUserId(
                $siteId,
                $userId,
                false
            );
        } else if ($userGroupId > 0 && $siteId > 0) {
            $arrList = $userPopedomManageData->GetListBySiteIdAndUserGroupId(
                $siteId,
                $userGroupId,
                false
            );
        }

        if(empty($arrList)){
            $userPopedomManageData->Init(
                $userId,
                $userGroupId,
                $siteId,
                $channelId,
                $forumId
            );

            $selfUrl = $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];

            Control::GoUrl($selfUrl);
        }

        for ($i = 0; $i < count($arrList); $i++) {
            $userPopedomName = $arrList[$i]["UserPopedomName"];
            $userPopedomValue = $arrList[$i]["UserPopedomValue"];
            //text value
            $templateContent = str_ireplace(
                "{" . $userPopedomName . "}"
                , $userPopedomValue,
                $templateContent
            );
            //checkbox value
            if ($userPopedomValue == 'on' || $userPopedomValue == '1') {
                $templateContent = str_ireplace("{c_" . $userPopedomName . "}", "checked", $templateContent);
            } else {
                $templateContent = str_ireplace("{c_" . $userPopedomName . "}", "", $templateContent);
            }
            //radio value
        }

        $templateContent = str_ireplace("{SiteId}", $siteId, $templateContent);
        $templateContent = str_ireplace("{UserId}", $userId, $templateContent);
        $templateContent = str_ireplace("{UserGroupId}", $userGroupId, $templateContent);
        $templateContent = str_ireplace("{ForumId}", $forumId, $templateContent);
        $templateContent = str_ireplace("{ChannelId}", $channelId, $templateContent);

        parent::ReplaceEnd($templateContent);

        $templateContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $templateContent);
        return $templateContent;

    }

} 