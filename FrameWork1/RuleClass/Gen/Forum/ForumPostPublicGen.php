<?php

/**
 * 前台 论坛帖子 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Forum
 * @author zhangchi
 */
class ForumPostPublicGen extends ForumBasePublicGen implements IBasePublicGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenPublic() {

        $action = Control::GetRequest("a", "");
        switch ($action) {
            case "list":
                $result = self::GenList();
                break;
            case "reply":
                $result = self::GenCreate();
                break;
            default:
                $result = self::GenDefault();
                break;
        }

        return $result;
    }
    /**
     * 生成论坛主题详细页html
     * @return string 返回模板页面
     */
    private function GenList() {
        $siteId = Control::GetRequest("site_id", 0);

        if ($siteId <= 0) {
            $siteId = parent::GetSiteIdByDomain();
        }
        $forumId = Control::GetRequest("forum_id", 0);
        $forumTopicId = Control::GetRequest("forum_topic_id", 0);
        if ($forumId <= 0 && $forumTopicId < 0) {
            return "";
        }

        $templateFileUrl = "forum/forum_post_list.html";
        $templateName = "default";
        $templatePath = "front_template";
        $tempContent = Template::Load($templateFileUrl, $templateName, $templatePath);
        $tagId = "forum_post_list";
        $tempContent = str_ireplace("{ForumId}", $forumId, $tempContent);
        $forumPostPublicDate = new ForumPostPublicData();
        $arrForumPost = $forumPostPublicDate->GetList($forumTopicId);

        if (count($arrForumPost) > 0) {
            Template::ReplaceList($tempContent, $arrForumPost, $tagId);
        } else {
            Template::RemoveCustomTag($tempContent, $tagId);
        }
        parent::ReplaceFirstForForum($tempContent);
        parent::ReplaceEndForForum($tempContent);
        parent::ReplaceSiteConfig($siteId, $tempContent);
        return $tempContent;
    }

    private function GenCreate() {
        $siteId = Control::GetRequest("site_id", 0);

        if ($siteId <= 0) {
            $siteId = parent::GetSiteIdByDomain();
        }
        $forumId = Control::GetRequest("forum_id", 0);
        $forumTopicId = Control::GetRequest("forum_topic_id", 0);
        if ($forumId <= 0 && $forumTopicId < 0) {
            return "";
        }

        $templateFileUrl = "forum/forum_post_list.html";
        $templateName = "default";
        $templatePath = "front_template";
        $tempContent = Template::Load($templateFileUrl, $templateName, $templatePath);

        $tempContent = str_ireplace("{ForumId}", $forumId, $tempContent);
        $tempContent = str_ireplace("{ForumTopicId}", $forumTopicId, $tempContent);
        $forumPostPublicDate = new ForumPostPublicData();
        $arrForumPost = $forumPostPublicDate->GetList($forumTopicId);

        if(!empty($_POST)){
            $forumPostId = 0;

            $forumPostTitle = "";
            $forumPostContent = Control::PostRequest("f_ForumPostContent", "");
            $postTime = date("Y-m-d H:i:s");
            $isTopic = 0;
            $forumTopicAudit = 0;
            $forumTopicAccess = 0;
            $userId = Control::GetUserId();//Control::PostRequest("f_UserId", "");
            $userName = Control::GetUserName();//Control::PostRequest("f_UserName", "");
            $accessLimitNumber = 0;
            $accessLimitContent = "";
            $showSign = 0;
            $postIp = Control::GetIp();
            $isOneSal = 0;
            $addMoney = 0;
            $addScore = 0;
            $addCharm = 0;
            $addExp = 0;
            $showBoughtUser =0;
            $sort = 0;
            $state = 0;
            $uploadFiles = "";
            $forumPostCreate = new ForumTopicPublicData();
            $forumPostId = $forumPostCreate->Create(
                $siteId,
                $forumId,
                $forumTopicId,
                $isTopic,
                $userId,
                $userName,
                $forumPostTitle,
                $forumPostContent,
                $postTime,
                $forumTopicAudit,
                $forumTopicAccess,
                $accessLimitNumber,
                $accessLimitContent,
                $showSign,
                $postIp,
                $isOneSal,
                $addMoney,
                $addScore,
                $addCharm,
                $addExp,
                $showBoughtUser,
                $sort,
                $state,
                $uploadFiles
            );
            if($forumPostId > 0 ){

            }
        }
        parent::ReplaceFirstForForum($tempContent);
        parent::ReplaceEndForForum($tempContent);
        parent::ReplaceSiteConfig($siteId, $tempContent);
        return $tempContent;
    }
} 