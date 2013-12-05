<?php

/**
 * 前台会员生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_User
 * @author zhangchi
 */
class UserGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenFront() {
        $result = "";
        $method = Control::GetRequest("m", "");
        switch ($method) {
            case "asyncloginforclient":
                $result = self::AsyncLoginForClient();
                break;
        }

        $result = str_ireplace("{method}", $method, $result);

        return $result;
    }

    private function AsyncLoginForClient() {
        $result = "";

        if (!empty($_GET)) {
            $username = Control::GetRequest("username", "");
            $userpass = Control::GetRequest("userpass", "");
            $siteid = Control::GetRequest("siteid", 0);

            $userdata = new UserData();
            $userid = $userdata->CheckLogin($username, $userpass, $siteid);
            switch ($userid) {
                case -2;
                    $result = $_GET['jsonpcallback'] . '({"error":"' . Language::Load('user', 2) . '"})'; //该用户名不存在
                    break;
                case -3:
                    $result = $_GET['jsonpcallback'] . '({"error":"' . Language::Load('user', 3) . '"})';        //密码不对
                    break;
                case -4:
                    $result = $_GET['jsonpcallback'] . '({"error":"' . Language::Load('user', 4) . '"})';        //insert cst_user表出错
                    break;
                case -5:
                    $result = $_GET['jsonpcallback'] . '({"error":"' . Language::Load('user', 19) . '"})';        //siteid参数传递出错
                    break;
                case 0:
                    $result = $_GET['jsonpcallback'] . '({"error":"' . Language::Load('user', 4) . '"})';        //insert cst_user表出错
                    break;
                default:
                    if (!empty($_GET['savelogintwoweeks'])) {
                        $savelogin = 24 * 14; //两周
                    } else {
                        $savelogin = 4000; //4小时
                    }
                    Control::SetUserCookie($userid, $username, $savelogin);
                    $userInfoData = new UserInfoData();
                    $arrUser = $userInfoData->GetOne($userid, $siteid);

                    if (!empty($arrUser)) {
                        $result = $_GET['jsonpcallback'] . "(" . FixJsonEncode($arrUser) . ")";
                    }
                    break;
            }
        }
        return $result;
    }

}

?>
