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
        $adminUserId = Control::GetManageUserId();
        if ($adminUserId <= 0) {
            Control::GoUrl(RELATIVE_PATH . "/default.php?mod=manage&a=login");
        } else {
            $module = Control::GetRequest("mod", "");
            switch ($module) {
                case "channel":
                    $channelManageGen = new ChannelManageGen();
                    $result = $channelManageGen->Gen();
                    break;
                case "document_news":
                    $documentNewsManageGen = new DocumentNewsManageGen();
                    $result = $documentNewsManageGen->Gen();
                    break;
                case "set_template":
                    self::SetTemplate();
                    break;
                case "site":
                    $siteManageGen = new SiteManageGen();
                    $result = $siteManageGen->Gen();
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
        //is login
        $manageUserId = Control::GetManageUserId();
        if ($manageUserId <= 0) {
            die();
        }
        $manageUserName = Control::GetManageUserName();
        $clientIp = Control::GetIp();

        $tempContent = Template::Load("manage/default.html","common");
        parent::ReplaceFirst($tempContent);

        $tempContent = str_ireplace("{manage_user_id}", $manageUserId, $tempContent);
        $tempContent = str_ireplace("{manage_user_name}", $manageUserName, $tempContent);
        $tempContent = str_ireplace("{client_ip_address}", $clientIp, $tempContent);

        //manage_menu_of_column
        $tagId = "manage_menu_of_column";
        $manageMenuOfColumnManageData = new ManageMenuOfColumnManageData();
        $manageUserGroupManageData = new ManageUserGroupManageData();
        $manageMenuOfColumnIdValue = $manageUserGroupManageData->GetManageMenuOfColumnIdValue($manageUserId);

        $arrManageMenuOfColumn = $manageMenuOfColumnManageData->GetList($manageMenuOfColumnIdValue);
        Template::ReplaceList($tempContent, $arrManageMenuOfColumn, $tagId);
        $tempContent = str_ireplace("{manage_menu_of_column_count}", count($arrManageMenuOfColumn), $tempContent);

        $listName = "select_site";
        $siteManageData = new SiteManageData();
        $arrSiteList = $siteManageData->GetList($manageUserId);
        Template::ReplaceList($tempContent, $arrSiteList, $listName);

        $forumAdminLeftNavTemplateContent = Template::Load("manage/forumadminleftnav.html","common");
        $tempContent = str_ireplace("{forumadminleftnav}", $forumAdminLeftNavTemplateContent, $tempContent);
        
        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }

    private function SetTemplate(){
        $templateName = Control::GetRequest("tn", "default");
        Control::SetManageUserTemplateName($templateName);
    }
}

?>
