<?php

/**
 * 后台Gen总引导类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen
 * @author zhangchi
 */
class DefaultManageGen extends BaseManageGen implements IBaseManageGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function Gen() {
        $result = "";
        $adminUserId = Control::GetAdminUserId();
        if ($adminUserId <= 0) {
            Control::GoUrl(RELATIVE_PATH . "/default.php?mod=manage&a=login");
        } else {
            $module = Control::GetRequest("mod", "");
            switch ($module) {
                case "documentchannel":
                    $documentChannelManageGen = new DocumentChannelManageGen();
                    $result = $documentChannelManageGen->Gen();
                    break;
                case "documentnews":
                    $documentNewsManageGen = new DocumentNewsManageGen();
                    $result = $documentNewsManageGen->Gen();
                    break;
                case "documentthreadmanage":
                    $documentThreadManageGen = new DocumentThreadManageGen();
                    $result = $documentThreadManageGen->Gen();
                    break;
                case "adminleftusermanage":
                    $adminLeftUserManageData = new AdminLeftUserManageData();
                    $result = $adminLeftUserManageData->Gen();
                    break;
                case "settemplate":
                    self::SetTemplate();
                    break;
                case "site":
                    $siteManageGen = new SiteManageGen();
                    $result = $siteManageGen->Gen();
                    break;
                case "siteconfig":
                    $siteConfigManageGen = new SiteConfigManageGen();
                    $result = $siteConfigManageGen->Gen();
                    break;
                case "forum":
                    $forumManageGen = new ForumManageGen();
                    $result = $forumManageGen->Gen();
                    break;
                default :
                    $result = self::GenDefault();
                    break;
            }
        }
        return $result;
    }

    private function GenDefault() {
        //is logined
        $adminUserId = Control::GetAdminUserId();
        if ($adminUserId <= 0) {
            return;
        }
        $adminUserName = Control::GetAdminUserName();
        $clientIp = Control::GetIp();

        $tempContent = Template::Load("manage/default.html","common");
        parent::ReplaceFirst($tempContent);

        $tempContent = str_ireplace("{adminuserid}", $adminUserId, $tempContent);
        $tempContent = str_ireplace("{adminusername}", $adminUserName, $tempContent);
        $tempContent = str_ireplace("{clientip}", $clientIp, $tempContent);

        //admin left nav
        $listName = "leftnav";
        $adminLeftNavManageData = new AdminLeftNavManageData();
        $adminUserGroupManageData = new AdminUserGroupManageData();
        $adminLeftNavIds = $adminUserGroupManageData->GetAdminLeftNavIDs($adminUserId);

        $arrAdminLeftNavList = $adminLeftNavManageData->GetList($adminLeftNavIds);
        Template::ReplaceList($tempContent, $arrAdminLeftNavList, $listName);
        $tempContent = str_ireplace("{adminleftnavcount}", count($arrAdminLeftNavList), $tempContent);

        $listName = "select_site";
        $siteManageData = new SiteManageData();
        $arrSiteList = $siteManageData->GetList($adminUserId);
        Template::ReplaceList($tempContent, $arrSiteList, $listName);

        $forumAdminLeftNavTemplateContent = Template::Load("manage/forumadminleftnav.html","common");
        $tempContent = str_ireplace("{forumadminleftnav}", $forumAdminLeftNavTemplateContent, $tempContent);
        
        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }

    private function SetTemplate(){
        $templateName = Control::GetRequest("tn", "default");
        Control::SetAdminUserTemplateName($templateName);
    }
}

?>
