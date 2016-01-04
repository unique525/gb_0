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
            case "modify":
                $result = self::GenModify();
                break;
            case "operate":
                $result = self::GenOperate();
                break;
            case "async_remove_to_bin":
                $result = self::AsyncRemoveToBin();
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
        $siteId = parent::GetSiteIdByDomain();

        $forumTopicId = Control::GetRequest("forum_topic_id", 0);
        if ($forumTopicId < 0) {
            return "topic id is error";
        }

        $pageSize = Control::GetRequest("ps", 30);
        $pageIndex = Control::GetRequest("p", 1);


        /*******************页面级的缓存 begin********************** */
        $templateMode = 0;
        $defaultTemp = "forum_post_list";
        $tempContent = parent::GetDynamicTemplateContent(
            $defaultTemp, 0, "", $templateMode);

        //$templateFileUrl = "forum/forum_default.html";
        //$templateName = "default";
        //$templatePath = "front_template";
        //$tempContent = Template::Load($templateFileUrl, $templateName, $templatePath);

        $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'forum_page';
        $cacheFile = 'forum_post_list_forum_topic_id_'
            . $forumTopicId
            . '_mode_' . $templateMode
            . '_p_' . $pageIndex
            . '_ps_' . $pageSize
        ;
        $withCache = false;
        if($withCache){
            $pageCache = parent::GetCache($cacheDir, $cacheFile);

            if ($pageCache === false) {
                $result = self::getListOfTemplateContent(
                    $siteId,
                    $forumTopicId,
                    $tempContent,
                    $pageIndex,
                    $pageSize
                );
                parent::AddCache($cacheDir, $cacheFile, $result, 60);
            } else {
                $result = $pageCache;
            }
        }else{
            $result = self::getListOfTemplateContent(
                $siteId,
                $forumTopicId,
                $tempContent,
                $pageIndex,
                $pageSize
            );
        }

        /*******************页面级的缓存 end  ********************** */


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

        $result = str_ireplace("{UserIsLogin}", $userIsLogin, $result);
        $result = str_ireplace("{UserUnLogin}", $userUnLogin, $result);
        $result = str_ireplace("{UserLoginUrl}", $userLoginUrl, $result);
        $result = str_ireplace("{UserRegisterUrl}", $userRegisterUrl, $result);


        parent::ReplaceUserInfoPanel($result, $siteId, "forum_user_is_login", "forum_user_no_login");
        return $result;





    }

    private function getListOfTemplateContent(
        $siteId,
        $forumTopicId,
        $templateContent,
        $pageIndex,
        $pageSize
    ){
        $forumId = -1;

        $forumTopicPublicData = new ForumTopicPublicData();

        if ($forumId <= 0) {
            $forumId = $forumTopicPublicData->GetForumId($forumTopicId, true);
        }

        $pageBegin = ($pageIndex - 1) * $pageSize;
        $allCount = 0;

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

            $pagerTemplate = parent::GetDynamicTemplateContent("pager_button", 0, '',$templateMode);
            $navUrl = "default.php?mod=forum_post&a=list&forum_topic_id=$forumTopicId&p={n}&ps=$pageSize";

            $pagerButtonListTemplateContent = Template::Load('pager_new/pager_list_style_default.html', 'default', 'front_template');
            $pagerButton = Pager::CreatePageButtons($pagerTemplate,true,true,'',$pageIndex,$pageSize,$allCount,$pagerButtonListTemplateContent,$navUrl);

            $templateContent = str_ireplace("{pager_button}", $pagerButton, $templateContent);

        } else {
            Template::RemoveCustomTag($templateContent, $tagId);
        }

        parent::ReplaceFirstForForum($templateContent);

        parent::ReplaceFirst($templateContent);


        $forumPublicData = new ForumPublicData();
        $forumName = $forumPublicData->GetForumName($forumId, true);

        $templateContent = str_ireplace("{ForumName}", $forumName, $templateContent);

        $backgroundUrl = $forumPublicData->GetBackgroundUrl($forumId, true);
        $templateContent = str_ireplace("{BackgroundUrl}", $backgroundUrl, $templateContent);

        $backgroundColor = $forumPublicData->GetBackgroundColor($forumId, true);
        $templateContent = str_ireplace("{BackgroundColor}", $backgroundColor, $templateContent);

        $topImageUrl = $forumPublicData->GetTopImageUrl($forumId, true);
        $templateContent = str_ireplace("{TopImageUrl}", $topImageUrl, $templateContent);

        $templateContent = str_ireplace("{SiteId}", $siteId, $templateContent);
        parent::ReplaceTemplate($templateContent);

        parent::ReplaceEndForForum($templateContent);

        parent::ReplaceEnd($templateContent);
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

        $templateMode = 0;
        $defaultTemp = "forum_post_create";
        $tempContent = parent::GetDynamicTemplateContent(
            $defaultTemp, 0, "", $templateMode);


        if (!empty($_POST)) {
            $forumPostTitle = Control::PostRequest("f_ForumPostTitle", "");
            $forumPostTitle = Format::FormatHtmlTag($forumPostTitle);

            $forumPostContent = Control::PostRequest("f_ForumPostContent", "", false);
            $forumPostContent = str_ireplace('\"', '"', $forumPostContent);
            //内容中不允许脚本等
            $forumPostContent = Format::RemoveScript($forumPostContent);

            $postTime = date("Y-m-d H:i:s", time());
            $userId = Control::GetUserId();
            $userName = Control::GetUserName();

            $uploadFiles = "";

            //直接上传内容图的处理
            if (!empty($_FILES)) {

                $tableType = UploadFileData::UPLOAD_TABLE_TYPE_FORUM_POST_CONTENT;
                $tableId = $forumTopicId;
                $arrUploadFile = array();
                $arrUploadFileId = array();
                $imgMaxWidth = 0;
                $imgMaxHeight = 0;
                $imgMinWidth = 0;
                $imgMinHeight = 0;
                $attachWatermark = intval(Control::PostOrGetRequest("attach_watermark", 0));

                parent::UploadMultiple(
                    "file_upload_to_content",
                    $tableType,
                    $tableId,
                    $arrUploadFile, //UploadFile类型的数组
                    $arrUploadFileId, //UploadFileId 数组
                    $imgMaxWidth,
                    $imgMaxHeight,
                    $imgMinWidth,
                    $imgMinHeight
                );

                $watermarkFilePath = "";

                if ($attachWatermark > 0) {


                    switch ($tableType) {
                        //帖子内容图
                        case UploadFileData::UPLOAD_TABLE_TYPE_FORUM_POST_CONTENT:
                            $siteId = parent::GetSiteIdByDomain();
                            $siteConfigData = new SiteConfigData($siteId);
                            $watermarkUploadFileId = $siteConfigData->ForumPostContentWatermarkUploadFileId;
                            if ($watermarkUploadFileId > 0) {

                                $uploadFileData = new UploadFileData();

                                $watermarkFilePath = $uploadFileData->GetUploadFilePath(
                                    $watermarkUploadFileId,
                                    true
                                );
                            }
                            break;

                    }


                }

                $sourceType = self::MAKE_WATERMARK_SOURCE_TYPE_SOURCE_PIC;
                $watermarkPosition = ImageObject::WATERMARK_POSITION_BOTTOM_RIGHT;
                $mode = ImageObject::WATERMARK_MODE_PNG;
                $alpha = 70;

                for ($u = 0; $u < count($arrUploadFileId); $u++) {


                    if($attachWatermark>0 && strlen($watermarkFilePath)>0){
                        parent::GenUploadFileWatermark1(
                            $arrUploadFileId[$u],
                            $watermarkFilePath,
                            $sourceType,
                            $watermarkPosition,
                            $mode,
                            $alpha,
                            $arrUploadFile[$u]
                        );
                    }

                    $uploadFiles = $uploadFiles . "," . $arrUploadFileId[$u];

                    //直接上传时，在内容中插入上传的图片

                    if (strlen($arrUploadFile[$u]->UploadFileWatermarkPath1) > 0
                    ) {
                        //有水印图时，插入水印图

                        $insertHtml = Format::FormatUploadFileToHtml(
                            $arrUploadFile[$u]->UploadFileWatermarkPath1,
                            FileObject::GetExtension($arrUploadFile[$u]->UploadFileWatermarkPath1),
                            $arrUploadFileId[$u],
                            ""
                        );

                    } else {
                        //没有水印图时，插入原图
                        $insertHtml = Format::FormatUploadFileToHtml(
                            $arrUploadFile[$u]->UploadFilePath,
                            FileObject::GetExtension($arrUploadFile[$u]->UploadFilePath),
                            $arrUploadFileId[$u],
                            ""
                        );
                    }
                    $forumPostContent = $forumPostContent . "<br />" . $insertHtml;


                }

            }

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
                parent::DelAllCache();
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

            $postTime = date("Y-m-d H:i:s", time());
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
                parent::DelAllCache();
                Control::GoUrl("/default.php?mod=forum_post&a=list&forum_topic_id=$forumTopicId");
            } else {
                Control::ShowMessage("false");
            }
        }
    }

    private function GenModify()
    {
        $forumTopicId = Control::GetRequest("forum_topic_id", 0);
        $forumId = Control::GetRequest("forum_id", 0);
        if ($forumId <= 0) {
            die("");
        }
        $forumPostId = Control::GetRequest("forum_post_id", 0);
        if ($forumPostId <= 0) {
            die("");
        }
        $siteId = Control::GetRequest("site_id", 0);
        if ($siteId <= 0) {
            $siteId = parent::GetSiteIdByDomain();
        }
        $templateMode = 0;
        $defaultTemp = "forum_post_modify";
        $tempContent = parent::GetDynamicTemplateContent(
            $defaultTemp, 0, "", $templateMode); //site id 为0时，全系统搜索模板

        $forumPostPublicDate = new ForumPostPublicData();
        $arrOne = $forumPostPublicDate->GetOne($forumPostId);
        Template::ReplaceOne($tempContent, $arrOne, false, false);
        if (!empty($_POST)) {
            $forumPostContent = Control::PostRequest("f_ForumPostContent", "", false);
            $forumPostContent = str_ireplace('\"', '"', $forumPostContent);
            //内容中不允许脚本等
            $forumPostContent = Format::RemoveScript($forumPostContent);
            $forumPostModify = new ForumPostPublicData();
            $result = $forumPostModify->ModifyContent($forumPostId,$forumPostContent );
            if ($result > 0) {
                Control::GoUrl("/default.php?mod=forum_post&a=list&forum_topic_id=$forumTopicId");
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
     * 操作帖子
     * @return string 返回模板页面
     */
    private function GenOperate()
    {
        $forumPostId = Control::GetRequest("forum_post_id", 0);
        if ($forumPostId <= 0) {
            die("");
        }
        $siteId = Control::GetRequest("site_id", 0);
        if ($siteId <= 0) {
            $siteId = parent::GetSiteIdByDomain();
        }

        $forumTopicPublicData = new ForumTopicPublicData();
        $forumId = $forumTopicPublicData->GetForumId($forumPostId, true);

        $userId = Control::GetUserId();
        if ($userId <= 0) {

            $returnUrl = urlencode("/default.php?mod=forum_post&a=operate&forum_post_id=$forumPostId");

            Control::GoUrl("/default.php?mod=user&a=login&re_url=$returnUrl");
            return "";
        }

        $templateFileUrl = "forum/forum_post_operate.html";
        $templateName = "default";
        $templatePath = "front_template";
        $tempContent = Template::Load($templateFileUrl, $templateName, $templatePath);


        $tempContent = str_ireplace("{ForumPostId}", $forumPostId, $tempContent);
        $tempContent = str_ireplace("{ForumId}", $forumId, $tempContent);

        parent::ReplaceFirstForForum($tempContent);
        parent::ReplaceEndForForum($tempContent);
        parent::ReplaceSiteConfig($siteId, $tempContent);

        return $tempContent;
    }

    private function AsyncRemoveToBin()
    {


        $forumPostId = Control::GetRequest("forum_post_id", 0);
        $userId = Control::GetUserId();

        if ($forumPostId > 0 && $userId >= 0) {
            $forumPostPublicData = new ForumPostPublicData();
            $forumUserId = $forumPostPublicData->GetUserId($forumPostId, true);
            $can = false;
            if ($forumUserId == $userId){ //自己的帖子
                //读取权限
                $can = parent::GetUserPopedomBoolValue(UserPopedomData::ForumDeleteSelfPost);

            }else{
                $can = parent::GetUserPopedomBoolValue(UserPopedomData::ForumDeleteOtherPost);


            }

            if ($can) {

                $forumPostPublicData = new ForumPostPublicData();
                $result = $forumPostPublicData->ModifyState(
                    $forumPostId,
                    ForumPostData::FORUM_POST_STATE_REMOVED
                );

                if ($result > 0) {

                    //删除缓冲
                    parent::DelAllCache();

                }

            } else {
                $result = -10; //没有权限
            }

        } else {
            $result = -5; //参数不正确或者没有登录
        }

        return Control::GetRequest("jsonpcallback", "") . '(' . $result . ')';
    }
} 