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
     * 检查当前管理员在页面是否有操作权限
     * @param AdminUserPopedomManageData $adminUserPopedomManageData 管理员权限管理数据类
     * @param int $adminUserId 管理员id
     * @param int $documentChannelId 频道id
     * @param int $siteId 站点id
     * @param string $op 操作类型
     * @return boolean 是否有操作权限
     */
    protected function CheckAdminUserPopedom(AdminUserPopedomManageData $adminUserPopedomManageData, $adminUserId, $documentChannelId, $siteId, $op)
    {
        $result = TRUE;
        if ($adminUserId <= 0) {
            $result = FALSE;
        } else { //检查权限
            $can = FALSE;
            switch ($op) {
                case "explore":
                    $can = $adminUserPopedomManageData->CanExplore($siteId, $documentChannelId, $adminUserId);
                    break;
            }
            if (!$can) {
                $result = FALSE;
            }
        }
        return $result;
    }
}

?>
