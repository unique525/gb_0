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
            case "operate":
                $result = self::GenOperate();
                break;
            case "async_remove_to_bin":
                $result = self::AsyncRemoveToBin();
                break;
            case "async_set_top":
                $result = self::AsyncSetTop();
                break;
            case "async_cancel_top":
                $result = self::AsyncCancelTop();
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

        /******************  右部推荐栏  ********************** */
        $templateForumRecTopicFileUrl = "forum/forum_rec_1_v.html";
        $templateForumRecTopic = Template::Load($templateForumRecTopicFileUrl, $templateName, $templatePath);
        $tempContent = str_ireplace("{forum_rec_1_v}", $templateForumRecTopic, $tempContent);

        $tempContent = str_ireplace("{SiteId}", $siteId, $tempContent);
        $forumPublicData = new ForumPublicData();
        $forumName = $forumPublicData->GetForumName($forumId, true);
        $tempContent = str_ireplace("{ForumName}", $forumName, $tempContent);

        parent::ReplaceTemplate($tempContent);
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

                    //删除缓冲
                    DataCache::RemoveDir(CACHE_PATH . '/forum_topic_data');
                    //删除缓冲
                    DataCache::RemoveDir(CACHE_PATH . '/forum_page');

                    $forumPublicData = new ForumPublicData();
                    $lastPostInfo = $forumPublicData->GetLastPostInfo($forumId, false);

                    $lastPostInfo = Format::AddToInfoString(
                        $forumTopicId,
                        $forumTopicTitle,
                        $lastPostInfo);

                    //更新版块信息
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
        Template::ReplaceOne($tempContent, $arrOne, false, false);

        $forumPostPublicDate = new ForumPostPublicData();
        $arrOne = $forumPostPublicDate->GetOne($forumTopicId);
        Template::ReplaceOne($tempContent, $arrOne, false, false);

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

        parent::ReplaceFirstForForum($tempContent);
        parent::ReplaceEndForForum($tempContent);
        parent::ReplaceSiteConfig($siteId, $tempContent);
        return $tempContent;
    }

    /**
     * 操作主题
     * @return string 返回模板页面
     */
    private function GenOperate(){
        $forumTopicId = Control::GetRequest("forum_topic_id", 0);
        if ($forumTopicId <= 0) {
            die("");
        }
        $siteId = Control::GetRequest("site_id", 0);
        if ($siteId <= 0) {
            $siteId = parent::GetSiteIdByDomain();
        }

        $forumTopicPublicData = new ForumTopicPublicData();
        $forumId = $forumTopicPublicData->GetForumId($forumTopicId, true);

        $userId = Control::GetUserId();
        if($userId<=0){

            $returnUrl = urlencode("/default.php?mod=forum_topic&a=operate&forum_topic_id=$forumTopicId");

            Control::GoUrl("/default.php?mod=user&a=login&re_url=$returnUrl");
            return "";
        }

        $templateFileUrl = "forum/forum_topic_operate.html";
        $templateName = "default";
        $templatePath = "front_template";
        $tempContent = Template::Load($templateFileUrl, $templateName, $templatePath);



        $tempContent = str_ireplace("{ForumTopicId}", $forumTopicId, $tempContent);
        $tempContent = str_ireplace("{ForumId}", $forumId, $tempContent);

        parent::ReplaceFirstForForum($tempContent);
        parent::ReplaceEndForForum($tempContent);
        parent::ReplaceSiteConfig($siteId, $tempContent);

        return $tempContent;
    }

    private function AsyncRemoveToBin(){


        $forumTopicId = Control::GetRequest("forum_topic_id", 0);
        $userId = Control::GetUserId();

        if ($forumTopicId > 0 && $userId >= 0) {

            //读取权限
            $forumDeleteSelfPost = parent::GetUserPopedomBoolValue(UserPopedomData::ForumDeleteSelfPost);

            if($forumDeleteSelfPost){

                $forumTopicPublicData = new ForumTopicPublicData();
                $result = $forumTopicPublicData->ModifyState(
                    $forumTopicId,
                    ForumTopicData::FORUM_TOPIC_STATE_REMOVED
                );

                if($result>0){

                    //删除缓冲
                    DataCache::RemoveDir(CACHE_PATH . '/forum_topic_data');
                    //删除缓冲
                    DataCache::RemoveDir(CACHE_PATH . '/forum_page');

                }

            }else{
                $result = -10; //没有权限
            }

        }else{
            $result = -5; //参数不正确或者没有登录
        }

        return Control::GetRequest("jsonpcallback","") . '('.$result.')';
    }

    /**
     * 设置排序
     * @return string
     */
    private function AsyncSetTop(){
        $forumTopicId = Control::GetRequest("forum_topic_id", 0);
        $mode = intval(Control::GetRequest("mode", 0)); //0 版块，1分区，2全站
        $userId = Control::GetUserId();

        if ($forumTopicId > 0 && $userId >= 0) {
            $canSetTop = false;
            //读取权限
            switch($mode){

                case 0:
                    $canSetTop = parent::GetUserPopedomBoolValue(UserPopedomData::ForumPostSetBoardTop);
                    break;
                case 1:
                    $canSetTop = parent::GetUserPopedomBoolValue(UserPopedomData::ForumPostSetRegionTop);
                    break;
                case 2:
                    $canSetTop = parent::GetUserPopedomBoolValue(UserPopedomData::ForumPostSetAllTop);
                    break;
            }

            if($canSetTop){
                $sort = 0;
                switch($mode){

                    case 0:
                        $sort = ForumTopicData::FORUM_TOPIC_SORT_BOARD_TOP;
                        break;
                    case 1:
                        $sort = ForumTopicData::FORUM_TOPIC_SORT_REGION_TOP;
                        break;
                    case 2:
                        $sort = ForumTopicData::FORUM_TOPIC_SORT_ALL_TOP;
                        break;
                }

                $forumTopicPublicData = new ForumTopicPublicData();
                $result = $forumTopicPublicData->ModifySort(
                    $forumTopicId,
                    $sort
                );

                if($result>0){

                    //删除缓冲
                    DataCache::RemoveDir(CACHE_PATH . '/forum_topic_data');
                    //删除缓冲
                    DataCache::RemoveDir(CACHE_PATH . '/forum_page');

                }

            }else{
                $result = -10; //没有权限
            }

        }else{
            $result = -5; //参数不正确或者没有登录
        }

        return Control::GetRequest("jsonpcallback","") . '('.$result.')';
    }

    /**
     * 取消排序
     * @return string
     */
    private function AsyncCancelTop(){
        $forumTopicId = Control::GetRequest("forum_topic_id", 0);
        $mode = intval(Control::GetRequest("mode", 0)); //0 版块，1分区，2全站
        $userId = Control::GetUserId();

        if ($forumTopicId > 0 && $userId >= 0) {
            $canSetTop = false;
            //读取权限
            switch($mode){

                case 0:
                    $canSetTop = parent::GetUserPopedomBoolValue(UserPopedomData::ForumPostSetBoardTop);
                    break;
                case 1:
                    $canSetTop = parent::GetUserPopedomBoolValue(UserPopedomData::ForumPostSetRegionTop);
                    break;
                case 2:
                    $canSetTop = parent::GetUserPopedomBoolValue(UserPopedomData::ForumPostSetAllTop);
                    break;
            }

            if($canSetTop){
                $sort = 0;
                switch($mode){

                    case 0:
                        $sort = ForumTopicData::FORUM_TOPIC_SORT_BOARD_TOP;
                        break;
                    case 1:
                        $sort = ForumTopicData::FORUM_TOPIC_SORT_REGION_TOP;
                        break;
                    case 2:
                        $sort = ForumTopicData::FORUM_TOPIC_SORT_ALL_TOP;
                        break;
                }

                $forumTopicPublicData = new ForumTopicPublicData();
                $result = $forumTopicPublicData->ModifySort(
                    $forumTopicId,
                    $sort
                );

                if($result>0){

                    //删除缓冲
                    DataCache::RemoveDir(CACHE_PATH . '/forum_topic_data');
                    //删除缓冲
                    DataCache::RemoveDir(CACHE_PATH . '/forum_page');

                }

            }else{
                $result = -10; //没有权限
            }

        }else{
            $result = -5; //参数不正确或者没有登录
        }

        return Control::GetRequest("jsonpcallback","") . '('.$result.')';
    }
}

?>
