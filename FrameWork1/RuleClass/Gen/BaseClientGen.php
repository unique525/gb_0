<?php

/**
 * 客户端Gen的基类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen
 * @author zhangchi
 */
class BaseClientGen extends BaseGen {

    /**
     * 根据混合登录，返回会员id
     * @return int
     */
    protected function GetUserId(){
        $userAccount = Format::FormatHtmlTag(Control::PostOrGetRequest("UserAccount", ""));
        $userPass = Control::PostOrGetRequest("UserPass", "");//经过 MD5

        if (strlen($userAccount) <= 0
            || empty($userPass)
        ) {
            $userId = -10; //会员检验失败,参数错误
        } else {

            $userClientData = new UserClientData();
            $userId = $userClientData->Login($userAccount, $userPass);
            if ($userId <= 0) {
                $userId = -11; //会员检验失败,帐号或密码错误
            }
        }

        return $userId;
    }


    /**
     * 根据 site id 和 user id 查找会员权限
     * @param int $siteId 站点id
     * @param int $userId 会员id
     * @param string $userPopedomName 会员权限字段名称
     * @return bool
     */
    protected function GetUserPopedomBoolValue($siteId, $userId, $userPopedomName){

        $result = self::GetUserPopedomStringValue($siteId, $userId, $userPopedomName);

        if(intval($result)>0){
            return true;
        }else{
            return false;
        }

    }

    /**
     * 根据 site id 和 user id 查找会员权限
     * @param int $siteId 站点id
     * @param int $userId 会员id
     * @param string $userPopedomName 会员权限字段名称
     * @return string
     */
    protected function GetUserPopedomStringValue($siteId, $userId, $userPopedomName){

        $result = "";

        if($siteId>0 && $userId>0){

            $userPopedomClientData = new UserPopedomClientData();

            $result = $userPopedomClientData->GetValueBySiteIdAndUserId(
                $siteId,
                $userId,
                $userPopedomName,
                true
            );

            if(intval($result)<=0){
                //没找到权限，从会员员组中找

                $userRoleClientData = new UserRoleClientData();
                $userGroupId = $userRoleClientData->GetUserGroupId(
                    $siteId,
                    $userId,
                    false
                );
                //******上面使用缓存有问题，老是输出group id为2或11待查//

                $result = $userPopedomClientData->GetValueBySiteIdAndUserGroupId(
                    $siteId,
                    $userGroupId,
                    $userPopedomName,
                    true
                );
            }
        }

        return $result;
    }
} 