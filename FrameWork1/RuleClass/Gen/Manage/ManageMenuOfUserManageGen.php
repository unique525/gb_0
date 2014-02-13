<?php

/**
 * 后台框架左部导航生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Manage
 * @author zhangchi
 */
class ManageMenuOfUserManageGen extends BaseManageGen implements IBaseManageGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function Gen() {
        $result = "";
        $method = Control::GetRequest("m", "");
        switch ($method) {
            case "async_list":
                $result = self::GenAsyncList();
                break;
        }
        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    private function GenAsyncList() {
        $manageMenuOfUserManageData = new ManageMenuOfUserManageData();
        $siteId = Control::GetRequest("site_id", 0);
        $arrList = $manageMenuOfUserManageData->GetList();
        if (!empty($arrList)) {
            $manageUserId = Control::GetManageUserId();
            $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
            $arrWaitForDelete = array();

            for ($i = 0; $i < count($arrList); $i++) {
                //$adminLeftUserManageName = $arrList[$i]["AdminLeftUserManageName"];
                $manageMenuOfUserTagName = $arrList[$i]["ManageMenuOfUserTagName"];
                $can = $manageUserAuthorityManageData->GetPopedomField($siteId, 0, $manageUserId, $manageMenuOfUserTagName);
                if (!$can) {
                    $arrWaitForDelete[] = $i;
                }
            }

            for ($j = 0; $j < count($arrWaitForDelete); $j++) {
                unset($arrList[$arrWaitForDelete[$j]]);
            }
        }
        return $_GET['jsonpcallback'] . "(" . Format::FixJsonEncode($arrList) . ")";
    }

}

?>
