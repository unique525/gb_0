<?php

/**
 * 前台 论坛主题 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Forum
 * @author zhangchi
 */
class ForumTopicPublicGen extends ForumBasePublicGen implements IBasePublicGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenPublic() {
        $result = "";

        $action = Control::GetRequest("a", "");
        switch ($action) {
            case "list":
                $result = self::GenList();
                break;
            case "create":
                $result = self::GenCreate();
                break;
        }

        $result = str_ireplace("{action}", $action, $result);

        return $result;
    }

    /**
     * 生成论坛主题列表页html
     * @return string 返回模板页面
     */
    private function GenList() {
        $siteId = Control::GetRequest("site_id", 0);
        if ($siteId <= 0) {
            $siteId = parent::GetSiteIdBySubDomain();
        }
        $forumId = Control::GetRequest("forum_id", 0);
        if ($forumId <= 0) {
            return "";
        }

        $templateFileUrl = "forum/forum_topic_list.html";
        $templateName = "default";
        $templatePath = "front_template";
        $tempContent = Template::Load($templateFileUrl, $templateName, $templatePath);

        $tempContent = str_ireplace("{ForumId}",$forumId,$tempContent);


        parent::ReplaceFirstForForum($tempContent);
        parent::ReplaceEndForForum($tempContent);
        parent::ReplaceSiteConfig($siteId, $tempContent);
        return $tempContent;
    }

    /**
     * 论坛发主题帖
     * @return string 返回模板页面
     */
    private function GenCreate() {

        $forumId = Control::GetRequest("forum_id", 0);
        if ($forumId <= 0) {
            die("");
        }

        $siteId = Control::GetRequest("site_id", 0);
        if ($siteId <= 0) {
            $siteId = parent::GetSiteIdBySubDomain();
        }

        $templateFileUrl = "forum/forum_topic_deal.html";
        $templateName = "default";
        $templatePath = "front_template";
        $tempContent = Template::Load($templateFileUrl, $templateName, $templatePath);

        //forum topic type list
        $forumTopicTypePublicData = new ForumTopicTypePublicData();
        $tagId = "forum_topic_type_list";
        $arrList = $forumTopicTypePublicData->GetList($forumId);
        if (count($arrList) > 0) {
            Template::ReplaceList($tempContent, $arrList, $tagId);
        } else {
            Template::RemoveCustomTag($tempContent, $tagId);
        }

        //user group list
        $userGroupPublicData = new UserGroupPublicData();
        $tagId = "user_group_list";
        $arrList = $userGroupPublicData->GetList($siteId);
        if (count($arrList) > 0) {
            Template::ReplaceList($tempContent, $arrList, $tagId);
        } else {
            Template::RemoveCustomTag($tempContent, $tagId);
        }


        if(!empty($_POST)){

/**
        $forumTopicTypeId ,
        $forumTopicTypeName,
        $forumTopicAudit,
        $forumTopicAccess,
        $postTime,
        $userId,
        $userName,
        $forumTopicMood,
        $forumTopicAttach,
        $titleBold,
        $titleColor,
        $titleBgImage
*/

            $forumTopicTitle = Control::PostRequest("f_ForumTopicTitle", "");
            $forumTopicTitle = Format::FormatHtmlTag($forumTopicTitle);

            $forumPostContent = Control::PostRequest("f_ForumPostContent", "");
            //内容中不允许脚本等
            $forumPostContent = Format::RemoveScript($forumPostContent);



        }


        $tempContent = str_ireplace("{ForumPostContent}", "", $tempContent);

        parent::ReplaceFirstForForum($tempContent);
        parent::ReplaceEndForForum($tempContent);
        parent::ReplaceSiteConfig($siteId, $tempContent);
        return $tempContent;
    }

}

?>
