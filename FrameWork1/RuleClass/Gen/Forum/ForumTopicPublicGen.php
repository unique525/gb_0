<?php

/**
 * 前台 论坛主题 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Forum
 * @author zhangchi
 */
class ForumTopicPublicGen extends ForumBasePublicGen implements IBasePublicGen
{

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenPublic()
    {

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
    private function GenList()
    {

        $siteId = parent::GetSiteIdByDomain();


        $forumId = Control::GetRequest("forum_id", 0);
        if ($forumId <= 0) {

            return "";
        }

        $pageSize = Control::GetRequest("ps", 30);
        $pageIndex = Control::GetRequest("p", 1);


        $forumPublicData = new ForumPublicData();

        $forumAccess = $forumPublicData->GetForumAccess($forumId, true);

        if($forumAccess == ForumData::FORUM_ACCESS_USER_GROUP){
            //按身份加密
            $userId = Control::GetUserId();


            $message = Language::Load("forum",6);
            $selfUrl = $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
            $selfUrl = urlencode($selfUrl);
            $message = str_ireplace("{re_url}", $selfUrl, $message);

            if($userId<=0){
                return $message;
            }

            $userRolePublicData = new UserRolePublicData();
            $userGroupId = $userRolePublicData->GetUserGroupId($siteId, $userId, true);

            if($userGroupId<=0){
                return $message;
            }

            $forumAccessLimit = $forumPublicData->GetForumAccessLimit($forumId, true);

            $arrAccessLimitContent = explode(',',$forumAccessLimit);
            $canExplore = false;
            if(is_array($arrAccessLimitContent)
                && !empty($arrAccessLimitContent)){

                if (in_array($userGroupId, $arrAccessLimitContent)){
                    $canExplore = true;
                }

            }else{

                if($userGroupId == $forumAccessLimit){
                    $canExplore = true;
                }

            }


            if(!$canExplore){

                return $message;

            }


        }


        /*******************页面级的缓存 begin********************** */
        $templateMode = 0;
        $defaultTemp = "forum_topic_list";
        $tempContent = parent::GetDynamicTemplateContent(
            $defaultTemp, 0, "", $templateMode); //site id 为0时，全系统搜索模板


        $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'forum_page';
        $cacheFile = 'forum_topic_list_forum_id_'
            . $forumId
            . '_mode_' . $templateMode
            . '_p_' . $pageIndex
            . '_ps_' . $pageSize;
        $withCache = true;
        if ($withCache) {
            $pageCache = DataCache::Get($cacheDir . DIRECTORY_SEPARATOR . $cacheFile);

            if ($pageCache === false) {
                $result = self::getListOfTemplateContent(
                    $siteId,
                    $forumId,
                    $tempContent,
                    $pageIndex,
                    $pageSize
                );
                DataCache::Set($cacheDir, $cacheFile, $result);
            } else {
                $result = $pageCache;
            }
        } else {
            $result = self::getListOfTemplateContent(
                $siteId,
                $forumId,
                $tempContent,
                $pageIndex,
                $pageSize
            );
        }

        /*******************页面级的缓存 end  ********************** */

        return $result;
    }

    private function getListOfTemplateContent(
        $siteId,
        $forumId,
        $templateContent,
        $pageIndex,
        $pageSize
    )
    {

        $templateContent = str_ireplace("{ForumId}", $forumId, $templateContent);
        $templateContent = str_ireplace("{SiteId}", $siteId, $templateContent);

        $pageBegin = ($pageIndex - 1) * $pageSize;
        $allCount = 0;

        $state = 0;

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

            Template::ReplaceList($templateContent, $arrForumTopicList, $tagId);
            $styleNumber = 1;
            $pagerTemplate = parent::GetDynamicTemplateContent(
                "pager_button");
            //$tempContent = Template::Load("pager/pager_style$styleNumber.html", "common");
            $isJs = FALSE;
            $navUrl = "/default.php?mod=forum_topic&a=list&forum_id=$forumId&p={0}&ps=$pageSize";
            $jsFunctionName = "";
            $jsParamList = "";
            $pagerButton = Pager::ShowPageButton(
                $pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex, $styleNumber, $isJs, $jsFunctionName, $jsParamList);

            $templateContent = str_ireplace("{pager_button}", $pagerButton, $templateContent);

        } else {
            Template::RemoveCustomTag($templateContent, $tagId);
            $templateContent = str_ireplace("{pager_button}", Language::Load("document", 7), $templateContent);
        }

        parent::ReplaceFirstForForum($templateContent);


        $templateContent = str_ireplace("{SiteId}", $siteId, $templateContent);
        $forumPublicData = new ForumPublicData();
        $forumName = $forumPublicData->GetForumName($forumId, true);
        $templateContent = str_ireplace("{ForumName}", $forumName, $templateContent);

        $backgroundUrl = $forumPublicData->GetBackgroundUrl($forumId, true);
        $templateContent = str_ireplace("{BackgroundUrl}", $backgroundUrl, $templateContent);

        $backgroundColor = $forumPublicData->GetBackgroundColor($forumId, true);
        $templateContent = str_ireplace("{BackgroundColor}", $backgroundColor, $templateContent);

        $topImageUrl = $forumPublicData->GetTopImageUrl($forumId, true);
        $templateContent = str_ireplace("{TopImageUrl}", $topImageUrl, $templateContent);


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
     * 论坛发主题帖
     * @return string 返回模板页面
     */
    private function GenCreate()
    {

        $forumId = Control::GetRequest("forum_id", 0);
        if ($forumId <= 0) {
            die("forum id is null");
        }

        $siteId = Control::GetRequest("site_id", 0);
        if ($siteId <= 0) {
            $siteId = parent::GetSiteIdByDomain();
        }

        $userId = Control::GetUserId();
        if ($userId <= 0) {
            $referUrl = urlencode("/default.php?mod=forum_topic&a=create&forum_id=$forumId");
            Control::GoUrl("/default.php?mod=user&a=login&re_url=$referUrl");
            die("user id is null");
        }

        //$templateFileUrl = "forum/forum_topic_deal.html";
        //$templateName = "default";
        //$templatePath = "front_template";
        //$tempContent = Template::Load($templateFileUrl, $templateName, $templatePath);

        $templateMode = 0;
        $defaultTemp = "forum_topic_create";
        $tempContent = parent::GetDynamicTemplateContent(
            $defaultTemp, 0, "", $templateMode);


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

        if (!empty($_POST)) {


            $forumTopicTitle = Control::PostRequest("f_ForumTopicTitle", "");
            $forumTopicTitle = Format::FormatHtmlTag($forumTopicTitle);

            $forumPostContent = Control::PostRequest("f_ForumPostContent", "", false);
            $forumPostContent = str_ireplace('\"', '"', $forumPostContent);
            //内容中不允许脚本等
            $forumPostContent = Format::RemoveScript($forumPostContent);

            $forumTopicTypeId = Control::PostRequest("f_ForumTopicTypeId", "");
            $forumTopicTypeName = Control::PostRequest("f_ForumTopicTypeName", "");
            $forumTopicAudit = Control::PostRequest("f_ForumTopicAudit", "");
            $forumTopicAccess = Control::PostRequest("f_ForumTopicAccess", "");
            $postTime = date("Y-m-d H:i:s", time());
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
            if ($forumTopicId > 0) {

                $uploadFiles = Control::PostRequest("f_UploadFiles", "");

                //直接上传内容图的处理（WAP、H5页面）
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
                        "file_upload_to_content_of_wap",
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


                        if ($attachWatermark > 0 && strlen($watermarkFilePath) > 0) {
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

                //修改上传文件的tableId;
                $uploadFiles = Control::PostRequest("f_UploadFiles", "");

                $arrUploadFilesOfPost = explode(",", $uploadFiles);

                $uploadFileData = new UploadFileData();
                for ($i = 0; $i < count($arrUploadFilesOfPost); $i++) {
                    if (intval($arrUploadFilesOfPost[$i]) > 0) {
                        $uploadFileData->ModifyTableId(intval($arrUploadFilesOfPost[$i]), $forumTopicId);
                    }
                }

                if (count($arrUploadFilesOfPost) > 0) {
                    $forumTopicPublicData->ModifyContentUploadFileId1($forumTopicId, intval($arrUploadFilesOfPost[0]));
                }

                if (count($arrUploadFilesOfPost) > 1) {
                    $forumTopicPublicData->ModifyContentUploadFileId2($forumTopicId, intval($arrUploadFilesOfPost[1]));
                }

                if (count($arrUploadFilesOfPost) > 2) {
                    $forumTopicPublicData->ModifyContentUploadFileId3($forumTopicId, intval($arrUploadFilesOfPost[2]));
                }


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


                } else {
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

    private function GenModify()
    {
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

        if (!empty($_POST)) {
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
            $userId = Control::GetUserId(); //Control::PostRequest("f_UserId", "");
            $userName = Control::GetUserName(); //Control::PostRequest("f_UserName", "");
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
            if ($result > 0) {
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
                if ($result > 0) {

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
    private function GenOperate()
    {
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
        if ($userId <= 0) {

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

    private function AsyncRemoveToBin()
    {


        $forumTopicId = Control::GetRequest("forum_topic_id", 0);
        $userId = Control::GetUserId();

        if ($forumTopicId > 0 && $userId >= 0) {
            $forumTopicPublicData = new ForumTopicPublicData();
            $forumUserId = $forumTopicPublicData->GetUserId($forumTopicId, true);
            $can = false;
            if ($forumUserId == $userId){ //自己的帖子
                //读取权限
                $can = parent::GetUserPopedomBoolValue(UserPopedomData::ForumDeleteSelfPost);

            }else{
                $can = parent::GetUserPopedomBoolValue(UserPopedomData::ForumDeleteOtherPost);


            }

            if ($can) {

                $forumTopicPublicData = new ForumTopicPublicData();
                $result = $forumTopicPublicData->ModifyState(
                    $forumTopicId,
                    ForumTopicData::FORUM_TOPIC_STATE_REMOVED
                );

                if ($result > 0) {

                    //删除缓冲
                    DataCache::RemoveDir(CACHE_PATH . '/forum_topic_data');
                    //删除缓冲
                    DataCache::RemoveDir(CACHE_PATH . '/forum_page');

                }

            } else {
                $result = -10; //没有权限
            }

        } else {
            $result = -5; //参数不正确或者没有登录
        }

        return Control::GetRequest("jsonpcallback", "") . '(' . $result . ')';
    }

    /**
     * 设置排序
     * @return string
     */
    private function AsyncSetTop()
    {
        $forumTopicId = Control::GetRequest("forum_topic_id", 0);
        $mode = intval(Control::GetRequest("mode", 0)); //0 版块，1分区，2全站
        $userId = Control::GetUserId();

        if ($forumTopicId > 0 && $userId >= 0) {
            $canSetTop = false;
            //读取权限
            switch ($mode) {

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

            if ($canSetTop) {
                $sort = 0;
                switch ($mode) {

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

                if ($result > 0) {

                    //删除缓冲
                    DataCache::RemoveDir(CACHE_PATH . '/forum_topic_data');
                    //删除缓冲
                    DataCache::RemoveDir(CACHE_PATH . '/forum_page');

                }

            } else {
                $result = -10; //没有权限
            }

        } else {
            $result = -5; //参数不正确或者没有登录
        }

        return Control::GetRequest("jsonpcallback", "") . '(' . $result . ')';
    }

    /**
     * 取消排序
     * @return string
     */
    private function AsyncCancelTop()
    {
        $forumTopicId = Control::GetRequest("forum_topic_id", 0);
        $mode = intval(Control::GetRequest("mode", 0)); //0 版块，1分区，2全站
        $userId = Control::GetUserId();

        if ($forumTopicId > 0 && $userId >= 0) {
            $canSetTop = false;
            //读取权限
            switch ($mode) {

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

            if ($canSetTop) {
                $sort = 0;
                switch ($mode) {

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

                if ($result > 0) {

                    //删除缓冲
                    DataCache::RemoveDir(CACHE_PATH . '/forum_topic_data');
                    //删除缓冲
                    DataCache::RemoveDir(CACHE_PATH . '/forum_page');

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

?>
