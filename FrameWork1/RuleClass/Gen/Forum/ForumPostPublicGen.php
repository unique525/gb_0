<?php

/**
 * 前台 论坛帖子 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Forum
 * @author zhangchi
 */
class ForumPostPublicGen extends ForumBasePublicGen implements IBasePublicGen
{

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenPublic()
    {
        $result = "";
        $action = Control::GetRequest("a", "");
        switch ($action) {
            case "list":
                $result = self::GenList();
                break;
            case "create":
                $result = self::GenCreate();
                break;
            case "reply":
                self::Reply();
                break;
        }

        $result = str_ireplace("{action}", $action, $result);

        return $result;
    }


    /**
     * 生成论坛主题详细页html
     * @return string 返回模板页面
     */
    private function GenList()
    {
        $siteId = Control::GetRequest("site_id", 0);
        if ($siteId <= 0) {
            $siteId = parent::GetSiteIdByDomain();
        }

        $forumTopicId = Control::GetRequest("forum_topic_id", 0);
        if ($forumTopicId < 0) {
            return "topic id is error";
        }
        $forumId = -1;

        $forumTopicPublicData = new ForumTopicPublicData();

        if ($forumId <= 0) {
            $forumId = $forumTopicPublicData->GetForumId($forumTopicId, true);
        }

        $pageSize = Control::GetRequest("ps", 30);
        $pageIndex = Control::GetRequest("p", 1);
        $pageBegin = ($pageIndex - 1) * $pageSize;
        $allCount = 0;

        $templateFileUrl = "forum/forum_post_list.html";
        $templateName = "default";
        $templatePath = "front_template";
        $templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);

        $templateContent = str_ireplace("{ForumId}", $forumId, $templateContent);
        $templateContent = str_ireplace("{ForumTopicId}", $forumTopicId, $templateContent);

        $tagId = "forum_post_list";

        $forumPostPublicDate = new ForumPostPublicData();
        $arrForumPost = $forumPostPublicDate->GetListPager(
            $forumTopicId,
            $pageBegin,
            $pageSize,
            $allCount
        );

        if (count($arrForumPost) > 0) {

            Template::ReplaceList($templateContent, $arrForumPost, $tagId);

            $templateContent = str_ireplace("[ATTACHMENT]", "", $templateContent);

            $templateContent = str_ireplace("[/ATTACHMENT]", "", $templateContent);

            $styleNumber = 1;
            $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html", "common");
            $isJs = FALSE;
            $navUrl = "default.php?mod=forum_post&a=list&forum_topic_id=$forumTopicId&p={0}&ps=$pageSize";

            $jsFunctionName = "";
            $jsParamList = "";
            $pagerButton = Pager::ShowPageButton(
                $pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex, $styleNumber, $isJs, $jsFunctionName, $jsParamList);

            $templateContent = str_ireplace("{pager_button}", $pagerButton, $templateContent);

        } else {
            Template::RemoveCustomTag($templateContent, $tagId);
        }

        parent::ReplaceFirstForForum($templateContent);

        //处理会员登录相关

        $userId = Control::GetUserId();

        $userLoginUrl = "/default.php?mod=user&a=login&re_url="
            . urlencode("/default.php?mod=forum_post&a=list&forum_topic_id=$forumTopicId");
        $userRegisterUrl = "/default.php?mod=user&a=register&re_url="
            . urlencode("/default.php?mod=forum_post&a=list&forum_topic_id=$forumTopicId");

        if ($userId > 0) {
            $userIsLogin = "";
            $userUnLogin = "none";
        } else {
            $userIsLogin = "none";
            $userUnLogin = "";
        }

        $templateContent = str_ireplace("{UserIsLogin}", $userIsLogin, $templateContent);
        $templateContent = str_ireplace("{UserUnLogin}", $userUnLogin, $templateContent);
        $templateContent = str_ireplace("{UserLoginUrl}", $userLoginUrl, $templateContent);
        $templateContent = str_ireplace("{UserRegisterUrl}", $userRegisterUrl, $templateContent);


        $forumPublicData = new ForumPublicData();
        $forumName = $forumPublicData->GetForumName($forumId, true);

        $templateContent = str_ireplace("{ForumName}", $forumName, $templateContent);

        /******************  右部推荐栏  ********************** */
        $templateForumRecTopicFileUrl = "forum/forum_rec_1_v.html";
        $templateForumRecTopic = Template::Load($templateForumRecTopicFileUrl, $templateName, $templatePath);
        $templateContent = str_ireplace("{forum_rec_1_v}", $templateForumRecTopic, $templateContent);

        $templateContent = str_ireplace("{SiteId}", $siteId, $templateContent);
        parent::ReplaceTemplate($templateContent);

        parent::ReplaceEndForForum($templateContent);
        parent::ReplaceSiteConfig($siteId, $templateContent);


        /*******************过滤字符 begin********************** */
        $multiFilterContent = array();
        $multiFilterContent[0] = $templateContent;
        $useArea = 4; //过滤范围 4:评论
        $stop = FALSE; //是否停止执行
        $filterContent = null;
        $stopWord = parent::DoFilter($siteId, $useArea, $stop, $filterContent, $multiFilterContent);
        $templateContent = $multiFilterContent[0];
        /*******************过滤字符 end********************** */

        return $templateContent;
    }

    /**
     * 论坛发回复帖
     * @return string 返回模板页面
     */
    private function GenCreate()
    {

        $forumTopicId = Control::GetRequest("forum_topic_id", 0);
        if ($forumTopicId <= 0) {
            die("");
        }

        $siteId = Control::GetRequest("site_id", 0);
        if ($siteId <= 0) {
            $siteId = parent::GetSiteIdByDomain();
        }

        $userId = Control::GetUserId();
        if ($userId <= 0) {
            $referUrl = urlencode("/default.php?mod=forum_post&a=create&forum_topic_id=$forumTopicId");
            Control::GoUrl("/default.php?mod=user&a=login&re_url=$referUrl");
            return "";
        }
        $forumTopicPublicData = new ForumTopicPublicData();
        $forumId = $forumTopicPublicData->GetForumId($forumTopicId, true);

        $templateFileUrl = "forum/forum_post_deal.html";
        $templateName = "default";
        $templatePath = "front_template";
        $tempContent = Template::Load($templateFileUrl, $templateName, $templatePath);

        if (!empty($_POST)) {
            $forumPostTitle = Control::PostRequest("f_ForumPostTitle", "");
            $forumPostTitle = Format::FormatHtmlTag($forumPostTitle);

            $forumPostContent = Control::PostRequest("f_ForumPostContent", "", false);
            $forumPostContent = str_ireplace('\"', '"', $forumPostContent);
            //内容中不允许脚本等
            $forumPostContent = Format::RemoveScript($forumPostContent);

            $postTime = date("Y-m-d H:i:s");
            $userId = Control::GetUserId();
            $userName = Control::GetUserName();

            //新增到ForumPost表
            $isTopic = 0;
            $accessLimitNumber = 0;
            $accessLimitContent = "";
            $showSign = 0;
            $postIp = Control::GetIp();
            $isOneSal = 0;
            $addMoney = 0;
            $addScore = 0;
            $addCharm = 0;
            $addExp = 0;
            $showBoughtUser = 0;
            $sort = 0;
            $state = 0;
            $forumTopicAudit = 0;
            $forumTopicAccess = 0;
            $uploadFiles = Control::PostRequest("file_upload_to_content", "");
            $forumTopicPostData = new ForumPostPublicData();
            $forumPostId = $forumTopicPostData->Create(
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


            if ($forumPostId > 0) {

                //删除缓冲
                DataCache::RemoveDir(CACHE_PATH . '/forum_topic_data');
                //删除缓冲
                DataCache::RemoveDir(CACHE_PATH . '/forum_page');

                //转到列表页
                Control::GoUrl("/default.php?mod=forum_post&a=list&forum_topic_id=$forumTopicId");

            } else {
                echo -1;
            }

        }

        $tempContent = str_ireplace("{ForumId}", $forumId, $tempContent);
        $tempContent = str_ireplace("{ForumTopicId}", $forumTopicId, $tempContent);
        $tempContent = str_ireplace("{ForumPostContent}", "", $tempContent);

        parent::ReplaceFirstForForum($tempContent);
        parent::ReplaceEndForForum($tempContent);
        parent::ReplaceSiteConfig($siteId, $tempContent);
        return $tempContent;
    }

    /**
     * 快速回复处理
     */
    private function Reply()
    {

        $siteId = Control::GetRequest("site_id", 0);
        if ($siteId <= 0) {
            $siteId = parent::GetSiteIdByDomain();
        }

        $forumTopicId = Control::GetRequest("forum_topic_id", 0);

        if ($forumTopicId <= 0) {
            return;
        }

        $userId = Control::GetUserId();

        if ($userId <= 0) {
            Control::GoUrl("default.php?mod=user&a=login&re_url=" . urlencode("default.php?mod=forum_post&a=list&forum_topic_id=$forumTopicId"));
            return;
        }


        if (!empty($_POST)) {
            $forumTopicPublicData = new ForumTopicPublicData();
            $forumId = -1;
            if ($forumId <= 0) {
                $forumId = $forumTopicPublicData->GetForumId($forumTopicId, true);
            }

            $forumPostTitle = "";
            $forumPostContent = Control::PostRequest("f_ForumPostContent", "");
            $forumPostContent = str_ireplace('\"', '"', $forumPostContent);
            //内容中不允许脚本等
            $forumPostContent = Format::RemoveScript($forumPostContent);

            $postTime = date("Y-m-d H:i:s");
            $isTopic = 0;
            $forumTopicAudit = 0;
            $forumTopicAccess = 0;
            $userName = Control::GetUserName();
            $accessLimitNumber = 0;
            $accessLimitContent = "";
            $showSign = 0;
            $postIp = Control::GetIp();
            $isOneSal = 0;
            $addMoney = 0;
            $addScore = 0;
            $addCharm = 0;
            $addExp = 0;
            $showBoughtUser = 0;
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


            if ($forumPostId > 0) {

                //删除缓冲
                DataCache::RemoveDir(CACHE_PATH . '/forum_topic_data');
                //删除缓冲
                DataCache::RemoveDir(CACHE_PATH . '/forum_page');

                Control::GoUrl("/default.php?mod=forum_post&a=list&forum_topic_id=$forumTopicId");
            } else {
                Control::ShowMessage("false");
            }
        }
    }
} 