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

        $action = Control::GetRequest("a", "");
        switch ($action) {
            case "list":
                $result = self::GenList();
                break;
            case "create":
                $result = self::GenCreate();
                break;
            case "modify":
                $result = self::GenModify();
                break;
            default:
                $result = self::GenList();
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
            $siteId = parent::GetSiteIdByDomain();
        }
        $forumId = Control::GetRequest("forum_id", 0);
        if ($forumId <= 0) {

            return "";
        }

        $pageSize = Control::GetRequest("ps", 30);
        $pageIndex = Control::GetRequest("p", 1);
        $pageBegin = ($pageIndex - 1) * $pageSize;
        $allCount = 0;

        $state = 0;
        $templateFileUrl = "forum/forum_topic_list.html";
        $templateName = "default";
        $templatePath = "front_template";
        $tempContent = Template::Load($templateFileUrl, $templateName, $templatePath);

        $tempContent = str_ireplace("{ForumId}",$forumId,$tempContent);

        $forumTopicPublicData = new ForumTopicPublicData();
        $arrForumTopicList = $forumTopicPublicData->GetListPager(
            $forumId,
            $pageBegin,
            $pageSize,
            $allCount,
            $state
        );
        $tagId = "forum_topic_list_normal";
        //print_r($arrForumTopicList);
        if (count($arrForumTopicList) > 0) {
            Template::ReplaceList($tempContent, $arrForumTopicList, $tagId);


            $styleNumber = 1;
            $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html", "common");
            $isJs = FALSE;
            $navUrl = "default.php?mod=forum_topic&a=list&forum_id=$forumId&p={0}&ps=$pageSize";
            $jsFunctionName = "";
            $jsParamList = "";
            $pagerButton = Pager::ShowPageButton(
            $pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex, $styleNumber, $isJs, $jsFunctionName, $jsParamList);

            $tempContent = str_ireplace("{pager_button}", $pagerButton, $tempContent);

        } else {
            Template::RemoveCustomTag($tempContent, $tagId);
            $tempContent = str_ireplace("{pager_button}", Language::Load("document", 7), $tempContent);
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
            $siteId = parent::GetSiteIdByDomain();
        }

        $userId = Control::GetUserId();
        if($userId<=0){
            $referUrl = urlencode("/default.php?mod=forum_topic&a=create&forum_id=$forumId");
            Control::GoUrl("/default.php?mod=user&a=login&re_url=$referUrl");
            return "";
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
            $forumTopicTitle = Control::PostRequest("f_ForumTopicTitle", "");
            $forumTopicTitle = Format::FormatHtmlTag($forumTopicTitle);

            $forumPostContent = Control::PostRequest("f_ForumPostContent", "", false);
            $forumPostContent = str_ireplace('\"','"',$forumPostContent);
            //内容中不允许脚本等
            $forumPostContent = Format::RemoveScript($forumPostContent);

            $forumTopicTypeId = Control::PostRequest("f_ForumTopicTypeId", "");
            $forumTopicTypeName = Control::PostRequest("f_ForumTopicTypeName", "");
            $forumTopicAudit = Control::PostRequest("f_ForumTopicAudit", "");
            $forumTopicAccess = Control::PostRequest("f_ForumTopicAccess", "");
            $postTime = date("Y-m-d H:i:s");
            $userId = Control::GetUserId();
            $userName = Control::GetUserName();
            $forumTopicMood = Control::PostRequest("f_ForumTopicMood", "");
            $forumTopicAttach = Control::PostRequest("f_ForumTopicAttach", "");
            $titleBold = Control::PostRequest("f_TitleBold", "");
            $titleColor = Control::PostRequest("f_TitleColor", "");
            $titleBgImage = Control::PostRequest("f_TitleBgImage", "");
            $forumTopicPublicData = new ForumTopicPublicData();



            $forumTopicId = $forumTopicPublicData->Create(
                $siteId,
                $forumId,
                $forumTopicTitle,
                $forumTopicTypeId,
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
            );
            if($forumTopicId>0){

                //新增到ForumPost表
                $isTopic = 1;
                $forumPostTitle = $forumTopicTitle;
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

                if($forumPostId > 0 ){
                    $forumPublicData = new ForumPublicData();
                    $lastPostInfo = $forumPublicData->GetLastPostInfo($forumId, false);

                    $lastPostInfo = Format::AddToInfoString(
                        $forumTopicId,
                        $forumTopicTitle,
                        $lastPostInfo);

                    //更新版块信息
                    $forumPublicData = new ForumPublicData();
                    $forumPublicData->UpdateForumInfoWhenCreateTopic(
                        $forumId,
                        $forumTopicId,
                        $forumPostTitle,
                        $userName,
                        $userId,
                        $postTime,
                        $lastPostInfo
                    );

                    //转到列表页
                    Control::GoUrl("/default.php?mod=forum_topic&forum_id=$forumId");


                }else{
                    echo -1;
                }
            }
        }


        $tempContent = str_ireplace("{ForumId}", $forumId, $tempContent);
        $tempContent = str_ireplace("{ForumTopicId}", "", $tempContent);
        $tempContent = str_ireplace("{ForumPostContent}", "", $tempContent);

        parent::ReplaceFirstForForum($tempContent);
        parent::ReplaceEndForForum($tempContent);
        parent::ReplaceSiteConfig($siteId, $tempContent);
        return $tempContent;
    }
    private function GenModify() {
        $forumId = Control::GetRequest("forum_id", 0);
        if ($forumId <= 0) {
            die("");
        }
        $forumTopicId = Control::GetRequest("forum_topic_id", 0);
        if ($forumTopicId <= 0) {
            die("");
        }
        $siteId = Control::GetRequest("site_id", 0);
        if ($siteId <= 0) {
            $siteId = parent::GetSiteIdByDomain();
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

        $forumTopicPublicData = new ForumTopicPublicData();
        $arrOne = $forumTopicPublicData->GetOne($forumTopicId);
        //print_r($arrOne["ForumTopicId"]);
        Template::ReplaceOne($tempContent, $arrOne, false, false);

        $forumPostPublicDate = new ForumPostPublicData();
        $arrOne = $forumPostPublicDate->GetOne($forumTopicId);
        Template::ReplaceOne($tempContent, $arrOne, false, false);

        //print_r($arrOne["UserName"]);
        //print_r($arrOne);
        if(!empty($_POST)){
            $forumTopicTitle = Control::PostRequest("f_ForumTopicTitle", "");
            $forumTopicTitle = Format::FormatHtmlTag($forumTopicTitle);

            $forumPostContent = Control::PostRequest("f_ForumPostContent", "");
            //内容中不允许脚本等
            $forumPostContent = Format::RemoveScript($forumPostContent);

            $forumTopicTypeId = Control::PostRequest("f_ForumTopicTypeId", "");
            $forumTopicTypeName = Control::PostRequest("f_ForumTopicTypeName", "");
            $forumTopicAudit = Control::PostRequest("f_ForumTopicAudit", "");
            $forumTopicAccess = Control::PostRequest("f_ForumTopicAccess", "");
            $postTime = date("Y-m-d H:i:s");
            $userId = Control::GetUserId();//Control::PostRequest("f_UserId", "");
            $userName = Control::GetUserName();//Control::PostRequest("f_UserName", "");
            $forumTopicMood = Control::PostRequest("f_ForumTopicMood", "");
            $forumTopicAttach = Control::PostRequest("f_ForumTopicAttach", "");
            $titleBold = Control::PostRequest("f_TitleBold", "");
            $titleColor = Control::PostRequest("f_TitleColor", "");
            $titleBgImage = Control::PostRequest("f_TitleBgImage", "");
            $forumTopicModify = new ForumTopicPublicData();
            $result = $forumTopicModify->Modify(
                $forumTopicId,
                $forumTopicTitle,
                $forumTopicTypeId,
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
            );
            if($result > 0){
                $siteId = Control::PostRequest("f_SiteId", "");
                $isTopic = 1;
                $forumPostTitle = $forumTopicTitle;
                $accessLimitNumber = "";
                $accessLimitContent = "";
                $showSign = 0;
                $postIp = Control::GetIp();
                $isOneSale = 0;
                $addMoney = 0;
                $addScore = 0;
                $addCharm = 0;
                $addExp = 0;
                $showBoughtUser = 0;
                $sort = 0;
                $state = 0;
                $uploadFiles = Control::PostRequest("file_upload_to_content", "");
                $forumPostPublicDate = new ForumPostPublicData();
                $result = $forumPostPublicDate->Modify(
                    $siteId,
                    $forumTopicId,
                    $isTopic,
                    $forumPostTitle,
                    $forumPostContent,
                    $postTime,
                    $forumTopicAudit,
                    $forumTopicAccess,
                    $accessLimitNumber,
                    $accessLimitContent,
                    $showSign,
                    $postIp,
                    $isOneSale,
                    $addMoney,
                    $addScore,
                    $addCharm,
                    $addExp,
                    $showBoughtUser,
                    $sort,
                    $state,
                    $uploadFiles
                );
                if($result > 0){

                }
            }
        }
        $tempContent = str_ireplace("{ForumId}", $forumId, $tempContent);
        $tempContent = str_ireplace("{ForumTopicId}", "", $tempContent);
        //$tempContent = str_ireplace("{ForumPostContent}", "", $tempContent);

        parent::ReplaceFirstForForum($tempContent);
        parent::ReplaceEndForForum($tempContent);
        parent::ReplaceSiteConfig($siteId, $tempContent);
       //echo $tempContent;
        return $tempContent;
    }

}

?>
