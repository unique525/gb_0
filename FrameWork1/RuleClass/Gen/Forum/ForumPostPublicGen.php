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
            case "create":
                $result = self::GenCreate();
                break;
            case "reply":
                $result = self::Reply();
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
        $pageSize = Control::GetRequest("ps", 30);
        $pageIndex = Control::GetRequest("p", 1);
        $pageBegin = ($pageIndex - 1) * $pageSize;
        $allCount = 0;

        $templateFileUrl = "forum/forum_post_list.html";
        $templateName = "default";
        $templatePath = "front_template";
        $tempContent = Template::Load($templateFileUrl, $templateName, $templatePath);
        $tagId = "forum_post_list";
        $tempContent = str_ireplace("{ForumId}", $forumId, $tempContent);
        $tempContent = str_ireplace("{ForumTopicId}", $forumTopicId, $tempContent);
        $forumPostPublicDate = new ForumPostPublicData();
        $arrForumPost = $forumPostPublicDate->GetListPager(
            $forumTopicId,
            $pageBegin,
            $pageSize,
            $allCount
        );

        if (count($arrForumPost) > 0) {
            Template::ReplaceList($tempContent, $arrForumPost, $tagId);

            $styleNumber = 1;
            $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html", "common");
            $isJs = FALSE;
            $navUrl = "default.php?mod=forum_post&a=list&forum_topic_id=$forumTopicId&p={0}&ps=$pageSize";

            $jsFunctionName = "";
            $jsParamList = "";
            $pagerButton = Pager::ShowPageButton(
                $pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex, $styleNumber, $isJs, $jsFunctionName, $jsParamList);

            $tempContent = str_ireplace("{pager_button}", $pagerButton, $tempContent);

        } else {
            Template::RemoveCustomTag($tempContent, $tagId);
        }

        parent::ReplaceFirstForForum($tempContent);
        parent::ReplaceEndForForum($tempContent);
        parent::ReplaceSiteConfig($siteId, $tempContent);


        /*******************过滤字符 begin********************** */
        $multiFilterContent = array();
        $multiFilterContent[0] = $tempContent;
        $useArea = 4; //过滤范围 4:评论
        $stop = FALSE; //是否停止执行
        $filterContent = null;
        $stopWord = parent::DoFilter($siteId, $useArea, $stop, $filterContent, $multiFilterContent);
        $tempContent = $multiFilterContent[0];
        /*******************过滤字符 end********************** */


        return $tempContent;
    }

    private function Reply(){

        $siteId = Control::GetRequest("site_id", 0);
        if ($siteId <= 0) {
            $siteId = parent::GetSiteIdByDomain();
        }
        $forumId = Control::GetRequest("forum_id", 0);
        $forumTopicId = Control::GetRequest("forum_topic_id", 0);

        if(!empty($_POST)){

            $forumPostTitle = "";
            $forumPostContent = Control::PostRequest("f_ForumPostContent", "");
            $postTime = date("Y-m-d H:i:s");
            $isTopic = 0;
            $forumTopicAudit = 0;
            $forumTopicAccess = 0;
            $userId = 1;//Control::GetUserId();//Control::PostRequest("f_UserId", "");
            $userName = "aaa";//Control::GetUserName();//Control::PostRequest("f_UserName", "");
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
            $forumPostPublicData = new ForumPostPublicData();
            $forumPostId = $forumPostPublicData->Create(
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
                Control::GoUrl("/default.php?mod=forum_post&a=list&forum_id=$forumId&forum_topic_id=$forumTopicId");
            }
        }
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
        //$forumPostPublicDate = new ForumPostPublicData();
        //$arrForumPost = $forumPostPublicDate->GetList($forumTopicId);
        echo $_POST[0];
        if(!empty($_POST)){
            echo "111";
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
        $tempContent = str_ireplace("{ForumId}", $forumId, $tempContent);

        parent::ReplaceFirstForForum($tempContent);
        parent::ReplaceEndForForum($tempContent);
        parent::ReplaceSiteConfig($siteId, $tempContent);
        return $tempContent;
    }
} 