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
        $manageUserId = Control::GetManageUserId();
        if ($manageUserId <= 0) {
            Control::GoUrl(RELATIVE_PATH . "/default.php?mod=manage&a=login");
        } else {
            $module = Control::GetRequest("mod", "");
            switch ($module) {
                case "upload":
                    $uploadManageGen = new UploadManageGen();
                    $result = $uploadManageGen->Gen();
                    break;
                case "channel":
                    $channelManageGen = new ChannelManageGen();
                    $result = $channelManageGen->Gen();
                    break;
                case "user":
                    $userManageGen = new UserManageGen();
                    $result = $userManageGen->Gen();
                    break;
                case "user_album":
                    $userAlbumManageGen = new UserAlbumManageGen();
                    $result = $userAlbumManageGen->Gen();
                    break;
                case "user_album_type":
                    $userAlbumTypeManageGen = new UserAlbumTypeManageGen();
                    $result = $userAlbumTypeManageGen->Gen();
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
                case "manage_menu_of_user":
                    $manageMenuOfUserManageGen = new ManageMenuOfUserManageGen();
                    $result = $manageMenuOfUserManageGen->Gen();
                    break;
                case "forum":
                    $forumManageGen = new ForumManageGen();
                    $result = $forumManageGen->Gen();
                    break;
                case "custom_form":
                    $customFormManageGen = new CustomFormManageGen();
                    $result = $customFormManageGen->Gen();
                    break;
                case "custom_form_field":
                    $customFormFieldManageGen = new CustomFormFieldManageGen();
                    $result = $customFormFieldManageGen->Gen();
                    break;
                case "custom_form_record":
                    $customFormRecordManageGen = new CustomFormRecordManageGen();
                    $result = $customFormRecordManageGen->Gen();
                    break;
                case "custom_form_content":
                    $customFormContentManageGen = new CustomFormContentManageGen();
                    $result = $customFormContentManageGen->Gen();
                    break;
                case "vote":
                    $voteManageGen = new VoteManageGen();
                    $result = $voteManageGen->Gen();
                    break;
                case "vote_item":
                    $voteItemManageGen = new VoteItemManageGen();
                    $result = $voteItemManageGen->Gen();
                    break;
                case "vote_select_item":
                    $voteSelectItemManageGen = new VoteSelectItemManageGen();
                    $result = $voteSelectItemManageGen->Gen();
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

        $manageMenuOfSiteChannelTemplateContent = Template::Load("manage/manage_menu_of_site_channel.html","common");
        $tempContent = str_ireplace("{manage_menu_of_site_channel}", $manageMenuOfSiteChannelTemplateContent, $tempContent);
        $manageMenuOfForumTemplateContent = Template::Load("manage/manage_menu_of_forum.html","common");
        $tempContent = str_ireplace("{manage_menu_of_forum}", $manageMenuOfForumTemplateContent, $tempContent);
        $manageMenuOfUserManageTemplateContent = Template::Load("manage/manage_menu_of_user_manage.html","common");
        $tempContent = str_ireplace("{manage_menu_of_user_manage}", $manageMenuOfUserManageTemplateContent, $tempContent);
        $manageMenuOfSearchTemplateContent = Template::Load("manage/manage_menu_of_search.html","common");
        $tempContent = str_ireplace("{manage_menu_of_search}", $manageMenuOfSearchTemplateContent, $tempContent);
        $manageMenuOfSystemConfigTemplateContent = Template::Load("manage/manage_menu_of_system_config.html","common");
        $tempContent = str_ireplace("{manage_menu_of_system_config}", $manageMenuOfSystemConfigTemplateContent, $tempContent);
        $manageMenuOfTaskTemplateContent = Template::Load("manage/manage_menu_of_task.html","common");
        $tempContent = str_ireplace("{manage_menu_of_task}", $manageMenuOfTaskTemplateContent, $tempContent);


        $tagId = "select_site";
        $siteManageData = new SiteManageData();
        $arrSiteList = $siteManageData->GetList($manageUserId);
        Template::ReplaceList($tempContent, $arrSiteList, $tagId);


        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }

    private function SetTemplate(){
        $templateName = Control::GetRequest("tn", "default");
        Control::SetManageUserTemplateName($templateName);
    }
}

?>
