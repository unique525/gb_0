<?php
/**
 * 后台管理 管理员权限 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Manage
 * @author zhangchi
 */
class ManageUserAuthorityManageGen extends BaseManageGen implements IBaseManageGen {
    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function Gen()
    {
        $result = "";
        $method = Control::GetRequest("m", "");
        switch ($method) {
            case "set_by_manage_user":
                $result = self::GenSetByManageUser();
                break;
            case "set_by_manage_user_group":
                $result = self::GenSetByManageUserGroup();
                break;
        }
        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }


    /**
     * 新增站点
     * @return string 模板内容页面
     */
    private function GenSetByManageUser()
    {
        $manageUserId = Control::GetManageUserId();

        //是否有权限


        $resultJavaScript = "";

        if ($manageUserId > 0) {
            $manageUserManageData = new ManageUserManageData();
            $tempContent = Template::Load("manage/manage_user_authority_set.html", "common");
            parent::ReplaceFirst($tempContent);

            if (!empty($_POST)) {

                $httpPostData = $_POST;

                $manageUserIdOfNew = $manageUserManageData->Create($httpPostData);
                //加入操作日志
                $operateContent = 'Create Manage User,POST FORM:' . implode('|', $_POST) . ';\r\nResult:manageUserId:' . $manageUserIdOfNew;
                self::CreateManageUserLog($operateContent);

                if ($manageUserIdOfNew > 0) {

                    $closeTab = Control::PostRequest("CloseTab", 0);
                    if ($closeTab == 1) {
                        //Control::CloseTab();
                        $resultJavaScript .= Control::GetCloseTab();
                    } else {
                        Control::GoUrl($_SERVER["PHP_SELF"] . "?" . $_SERVER['QUERY_STRING']);
                    }
                } else {
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('manage_user', 3)); //新增失败！

                }

            }

            $fields = $manageUserManageData->GetFields();
            parent::ReplaceWhenCreate($tempContent, $fields);

            $patterns = '/\{s_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);

            parent::ReplaceEnd($tempContent);


            $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);

        } else {
            $tempContent = Language::Load("manage_user", 9);
        }
        return $tempContent;
    }
} 