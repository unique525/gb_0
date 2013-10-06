<?php

/**
 * 后台Gen的基类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen
 * @author zhangchi
 */
class BaseManageGen extends BaseGen {

    /**
     * 检查当前管理员在页面是否有操作权限
     * @param AdminUserPopedomManageData $adminUserPopedomManageData 管理员权限管理数据类
     * @param int $adminUserId 管理员id
     * @param int $documentChannelId 频道id
     * @param int $siteId 站点id
     * @param string $op 操作类型
     * @return boolean 是否有操作权限
     */
    protected function CheckAdminUserPopedom(AdminUserPopedomManageData $adminUserPopedomManageData,$adminUserId, $documentChannelId, $siteId, $op) {
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

    /**
     * 取得文档的发布路径
     * @param int $documentChannelId 频道id
     * @param int $rank 频道rank
     * @param string $publishFileName 发布文件名
     * @param string $publishPath 发布路径
     * @return string 文档的发布路径
     */
    protected function GetPublishPath($documentChannelId, $rank, $publishFileName = '', $publishPath = '') {
        if ($rank >= 1) {
            if (empty($publishPath)) { //定义了发布路径则使用定义的发布路径，否则使用频道id
                $publishPath = $documentChannelId;
            }

            if (strlen($publishFileName) > 0) {
                $str = 'h/' . $publishPath . '/' . $publishFileName;
            } else {
                $str = 'h/' . $publishPath . '/';
            }
            return $str;
        } else {
            if (strlen($publishFileName) > 0) {
                return $publishFileName;
            } else {
                return 'index.html';
            }
        }
    }
}

?>
