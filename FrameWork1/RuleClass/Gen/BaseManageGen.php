<?php

/**
 * 后台Gen的基类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen
 * @author zhangchi
 */
class BaseManageGen extends BaseGen
{

    /**
     * 刷新当前的Tab页
     */
    protected function RefreshTab()
    {
        Control::RunJavascript('G_Tabs.tabs("refresh");');
    }

    /**
     * 检查当前管理员在页面是否有操作权限
     * @param ManageUserAuthorityManageData $manageUserAuthorityManageData 管理员权限管理数据类
     * @param int $manageUserId 管理员id
     * @param int $channelId 频道id
     * @param int $siteId 站点id
     * @param string $operateType 操作类型
     * @return boolean 是否有操作权限
     */
    protected function CheckAdminUserPopedom(ManageUserAuthorityManageData $manageUserAuthorityManageData, $manageUserId, $channelId, $siteId, $operateType)
    {
        $result = TRUE;
        if ($manageUserId <= 0) {
            $result = FALSE;
        } else { //检查权限
            $can = FALSE;
            switch ($operateType) {
                case "explore":
                    $can = $manageUserAuthorityManageData->CanExplore($siteId, $channelId, $manageUserId);
                    break;
            }
            if (!$can) {
                $result = FALSE;
            }
        }
        return $result;
    }

    /**
     * 替换新增时，模板里的{field}值为空，包括后台模板和模板数据库中的模板
     * @param string $tempContent 要处理的模板内容
     * @param array $arrField 字段数组
     */
    protected function ReplaceWhenCreate(&$tempContent, $arrField)
    {
        if (count($arrField) > 0) {
            for ($i = 0; $i < count($arrField); $i++) {
                $tempContent = str_ireplace("{" . $arrField[$i]['Field'] . "}", '', $tempContent);
                $tempContent = str_ireplace("{b_" . $arrField[$i]['Field'] . "}", '', $tempContent);
            }
        }
    }

}

?>
