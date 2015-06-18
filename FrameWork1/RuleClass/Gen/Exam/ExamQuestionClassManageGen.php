<?php
/**
 * 后台管理 试题分类 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Exam
 * @author zhangchi
 */
class ExamQuestionClassManageGen extends BaseManageGen implements IBaseManageGen {
    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function Gen()
    {
        $result = "";
        $method = Control::GetRequest("m", "");
        switch ($method) {
            case "create":
                $result = self::GenCreate();
                break;
            case "modify":
                $result = self::GenModify();
                break;
            case "remove_to_bin":
                $result = self::GenRemoveToBin();
                break;
            case "list":
                $result = self::GenList();
                break;
            case "async_modify_state":
                $result = self::AsyncModifyState();
                break;
        }
        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    /**
     * 新增
     * @return string 模板内容页面
     */
    private function GenCreate()
    {
        $templateContent = "";
        $siteId = Control::GetRequest("site_id", 0);
        $rank = Control::GetRequest("rank", -1);
        $parentId = Control::GetRequest("parent_id", 0);

        if ($siteId > 0 && $rank >= 0) {
            $templateContent = Template::Load("exam/exam_question_class_deal.html", "common");
            $resultJavaScript = "";
            parent::ReplaceFirst($templateContent);

            $examQuestionClassManageData = new ExamQuestionClassManageData();
            if ($rank > 0) {

                if ($parentId > 0) {
                    $parentName = $examQuestionClassManageData->GetExamQuestionClassName($parentId, false);
                    $templateContent = str_ireplace("{ParentName}", $parentName, $templateContent);
                } else {
                    $parentId = 0;
                }

            }


            $templateContent = str_ireplace("{SiteId}", $siteId, $templateContent);
            $templateContent = str_ireplace("{ForumRank}", $rank, $templateContent);
            $templateContent = str_ireplace("{ParentId}", $parentId, $templateContent);
            $templateContent = str_ireplace("{Sort}", "0", $templateContent);

            if (!empty($_POST)) {
                $httpPostData = $_POST;

                $forumId = $forumManageData->Create($httpPostData);

                //加入操作日志
                $operateContent = 'Create Forum,POST FORM:' . implode('|', $_POST) . ';\r\nResult:' . $forumId;
                self::CreateManageUserLog($operateContent);

                if ($forumId > 0) {

                    //title pic1
                    $fileElementName = "file_forum_pic_1";
                    $tableType = UploadFileData::UPLOAD_TABLE_TYPE_FORUM_PIC_1; //
                    $tableId = $forumId;
                    $uploadFile1 = new UploadFile();
                    $uploadFileId1 = 0;
                    $titlePic1Result = self::Upload(
                        $fileElementName,
                        $tableType,
                        $tableId,
                        $uploadFile1,
                        $uploadFileId1
                    );

                    if (intval($titlePic1Result) <=0){
                        //上传出错或没有选择文件上传
                    }else{

                    }

                    //title pic2
                    $fileElementName = "file_forum_pic_2";
                    $tableType = UploadFileData::UPLOAD_TABLE_TYPE_FORUM_PIC_2;
                    $uploadFileId2 = 0;
                    $uploadFile2 = new UploadFile();
                    $titlePic2Result = self::Upload(
                        $fileElementName,
                        $tableType,
                        $tableId,
                        $uploadFile2,
                        $uploadFileId2
                    );
                    if (intval($titlePic2Result) <=0){
                        //上传出错或没有选择文件上传
                    }else{

                    }

                    if($uploadFileId1>0 || $uploadFileId2>0){
                        $forumManageData->ModifyForumPic($forumId, $uploadFileId1, $uploadFileId2);
                    }

                    $siteConfigData = new SiteConfigData($siteId);
                    if($uploadFileId1>0){
                        $forumPicMobileWidth = $siteConfigData->ForumPicMobileWidth;
                        if($forumPicMobileWidth<=0){
                            $forumPicMobileWidth  = 320; //默认320宽
                        }
                        self::GenUploadFileMobile($uploadFileId1,$forumPicMobileWidth);

                        $forumPicPadWidth = $siteConfigData->ForumPicPadWidth;
                        if($forumPicPadWidth<=0){
                            $forumPicPadWidth  = 1024; //默认1024宽
                        }
                        self::GenUploadFilePad($uploadFileId1,$forumPicPadWidth);
                    }


                    //javascript 处理

                    $closeTab = Control::PostRequest("CloseTab", 0);
                    if ($closeTab == 1) {
                        //$resultJavaScript .= Control::GetCloseTab();
                        Control::GoUrl("/default.php?secu=manage&mod=forum&m=list&site_id=$siteId");
                    } else {
                        Control::GoUrl($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']);
                    }
                } else {
                    $resultJavaScript = Control::GetJqueryMessage(Language::Load('forum', 2));
                }
            }

            $fields = $forumManageData->GetFields();
            parent::ReplaceWhenCreate($templateContent, $fields);

            $patterns = '/\{s_(.*?)\}/';
            $templateContent = preg_replace($patterns, "", $templateContent);
            parent::ReplaceEnd($templateContent);

            $templateContent = str_ireplace("{ResultJavaScript}", $resultJavaScript, $templateContent);

        }


        return $templateContent;
    }


    private function GenModify(){
        $tempContent = "";
        $forumId = Control::GetRequest("forum_id", 0);
        $siteId = Control::GetRequest("site_id", 0);

        if ($siteId>0 && $forumId >= 0) {
            $tempContent = Template::Load("forum/forum_deal.html", "common");
            $resultJavaScript = "";
            parent::ReplaceFirst($tempContent);


            $forumManageData = new ForumManageData();


            if (!empty($_POST)) {
                $httpPostData = $_POST;

                $result = $forumManageData->Modify($httpPostData, $forumId);

                //加入操作日志
                $operateContent = 'Modify Forum,POST FORM:' .
                    implode('|', $_POST) . ';\r\nResult:' . $result;
                self::CreateManageUserLog($operateContent);

                if ($result > 0) {

                    //删除缓冲
                    DataCache::RemoveDir(CACHE_PATH . '/forum_data');


                    if( !empty($_FILES)){
                        //title pic1
                        $fileElementName = "file_forum_pic_1";
                        $tableType = UploadFileData::UPLOAD_TABLE_TYPE_FORUM_PIC_1; //
                        $tableId = $forumId;
                        $uploadFile1 = new UploadFile();
                        $uploadFileId1 = 0;
                        $titlePic1Result = self::Upload(
                            $fileElementName,
                            $tableType,
                            $tableId,
                            $uploadFile1,
                            $uploadFileId1
                        );

                        if (intval($titlePic1Result) <=0){
                            //上传出错或没有选择文件上传
                        }else{
                            //删除原有题图
                            $oldUploadFileId1 = $forumManageData->GetForumPic1UploadFileId(
                                $forumId, false);
                            parent::DeleteUploadFile($oldUploadFileId1);
                            //修改题图
                            $forumManageData->ModifyForumPic1UploadFileId(
                                $forumId, $uploadFileId1);

                        }

                        //title pic2
                        $fileElementName = "file_forum_pic_2";
                        $tableType = UploadFileData::UPLOAD_TABLE_TYPE_FORUM_PIC_2;
                        $uploadFileId2 = 0;
                        $uploadFile2 = new UploadFile();
                        $titlePic2Result = self::Upload(
                            $fileElementName,
                            $tableType,
                            $tableId,
                            $uploadFile2,
                            $uploadFileId2
                        );
                        if (intval($titlePic2Result) <=0){
                            //上传出错或没有选择文件上传
                        }else{
                            //删除原有题图
                            $oldUploadFileId2 = $forumManageData->GetForumPic2UploadFileId(
                                $forumId, false);
                            parent::DeleteUploadFile($oldUploadFileId2);
                            //修改题图
                            $forumManageData->ModifyForumPic2UploadFileId(
                                $forumId, $uploadFileId2);
                        }

                        $siteConfigData = new SiteConfigData($siteId);
                        if($uploadFileId1>0){
                            $forumPicMobileWidth = $siteConfigData->ForumPicMobileWidth;
                            if($forumPicMobileWidth<=0){
                                $forumPicMobileWidth  = 320; //默认320宽
                            }
                            self::GenUploadFileMobile($uploadFileId1,$forumPicMobileWidth);

                            $forumPicPadWidth = $siteConfigData->ForumPicPadWidth;
                            if($forumPicPadWidth<=0){
                                $forumPicPadWidth  = 1024; //默认1024宽
                            }
                            self::GenUploadFilePad($uploadFileId1,$forumPicPadWidth);
                        }

                    }



                    //javascript 处理

                    $closeTab = Control::PostRequest("CloseTab", 0);
                    if ($closeTab == 1) {
                        //$resultJavaScript .= Control::GetCloseTab();
                        Control::GoUrl("/default.php?secu=manage&mod=forum&m=list&site_id=$siteId");
                    } else {
                        Control::GoUrl($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']);
                    }
                } else {
                    $resultJavaScript = Control::GetJqueryMessage(Language::Load('forum', 4));
                }
            }



            $arrOne = $forumManageData->GetOne($forumId);
            Template::ReplaceOne($tempContent, $arrOne);
            $parentId = intval($arrOne["ParentId"]);
            if($parentId>0){
                $parentName = $forumManageData->GetForumName($parentId, false);
                $tempContent = str_ireplace("{ParentName}", $parentName, $tempContent);
            }else{
                $tempContent = str_ireplace("{ParentName}", "无", $tempContent);
            }


            $tempContent = str_ireplace("{SiteId}", $siteId, $tempContent);
            $tempContent = str_ireplace("{ForumId}", $forumId, $tempContent);
            $tempContent = str_ireplace("{Sort}", "0", $tempContent);

            $patterns = '/\{s_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
            parent::ReplaceEnd($tempContent);

            $tempContent = str_ireplace("{ResultJavaScript}", $resultJavaScript, $tempContent);

        }


        return $tempContent;
    }


    /**
     * 生成列表页面
     */
    private function GenList()
    {
        $channelId = Control::GetRequest("channel_id", 0);
        if ($channelId <= 0) {
            return null;
        }
        $manageUserId = Control::GetManageUserId();
        $channelManageData = new ChannelManageData();
        $siteId = $channelManageData->GetSiteId($channelId, false);
        if ($siteId <= 0) {
            return null;
        }

        ///////////////判断是否有操作权限///////////////////
        $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
        $canExplore = $manageUserAuthorityManageData->CanChannelExplore($siteId, $channelId, $manageUserId);
        if (!$canExplore) {
            die(Language::Load('channel', 4));
        }

        $templateContent = "";

        if($channelId>0){

            //load template
            $templateContent = Template::Load(
                "exam/exam_question_class_list.html",
                "common");

            parent::ReplaceFirst($templateContent);

            $examQuestionClassManageData = new ExamQuestionClassManageData();
            $rank = 0;
            $arrRankOneList = $examQuestionClassManageData->GetListByRank($siteId, $rank);
            $rank = 1;
            $arrRankTwoList = $examQuestionClassManageData->GetListByRank($siteId, $rank);
            $rank = 2;
            $arrRankThreeList = $examQuestionClassManageData->GetListByRank($siteId, $rank);
            $tagId = "exam_question_class_list";
            if(count($arrRankOneList)>0){

                Template::ReplaceList(
                    $tempContent,
                    $arrRankOneList,
                    $tagId,
                    Template::DEFAULT_TAG_NAME,
                    $arrRankTwoList,
                    "ExamQuestionClassId",
                    "ParentId",
                    $arrRankThreeList,
                    "ExamQuestionClassId",
                    "ParentId"
                );
            }else{
                Template::RemoveCustomTag($templateContent, $tagId);
                $templateContent = str_ireplace("{pager_button}", Language::Load("document", 7), $templateContent);

            }



            parent::ReplaceEnd($templateContent);



        }

        return $templateContent;
    }

} 