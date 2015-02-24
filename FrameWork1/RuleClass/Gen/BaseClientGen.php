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


} 