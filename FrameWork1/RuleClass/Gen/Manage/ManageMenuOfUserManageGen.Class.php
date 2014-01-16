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
        $adminLeftUserManageData = new AdminLeftUserManageData();
        $siteId = Control::GetRequest("siteid", 0);
        $arrList = $adminLeftUserManageData->GetList();
        if (!empty($arrList)) {
            $adminUserId = Control::GetAdminUserId();
            $adminUserPopedomManageData = new AdminUserPopedomManageData();
            $arrWaitForDelete = array();

            for ($i = 0; $i < count($arrList); $i++) {
                //$adminLeftUserManageName = $arrList[$i]["AdminLeftUserManageName"];
                $adminPopedomName = $arrList[$i]["AdminPopedomName"];
                $can = $adminUserPopedomManageData->GetPopedomField($siteId, 0, $adminUserId, $adminPopedomName);
                if (!$can) {
                    $arrWaitForDelete[] = $i;
                }
            }

            for ($j = 0; $j < count($arrWaitForDelete); $j++) {
                unset($arrList[$arrWaitForDelete[$j]]);
            }

            return $_GET['jsonpcallback'] . "(" . FixJsonEncode($arrList) . ")";
        }
    }

}

?>
