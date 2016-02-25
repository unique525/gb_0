<?php
/**
 * 后台管理 论坛 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Forum
 * @author zhangchi
 */
class ForumManageGen extends BaseManageGen implements IBaseManageGen
{

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function Gen()
    {
        $result = "";
        $method = Control::GetRequest("m", "");
        switch ($method) {
            case "list":
                $result = self::GenList();
                break;
            case "create":
                $result = self::GenCreate();
                break;
            case "modify":
                $result = self::GenModify();
                break;
            case "async_modify_state":
                $result = self::AsyncModifyState();
                break;
            case "reset_last_info":
                $result = self::ResetLastInfo();
                break;
            case "get_move_window":
                $result = self::getMoveWindow();
                break;

        }
        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    /**
     * 新增论坛版块
     * @return string 模板内容页面
     */
    private function GenCreate()
    {
        $tempContent = "";
        $siteId = Control::GetRequest("site_id", 0);
        $forumRank = Control::GetRequest("forum_rank", -1);
        $parentId = Control::GetRequest("parent_id", 0);

        if ($siteId > 0 && $forumRank >= 0) {
            $tempContent = Template::Load("forum/forum_deal.html", "common");
            $resultJavaScript = "";
            parent::ReplaceFirst($tempContent);

            $forumManageData = new ForumManageData();
            if ($forumRank > 0) {

                if ($parentId > 0) {
                    $parentName = $forumManageData->GetForumName($parentId, false);
                    $tempContent = str_ireplace("{ParentName}", $parentName, $tempContent);
                } else {
                    $parentId = 0;
                }

            }


            $tempContent = str_ireplace("{SiteId}", $siteId, $tempContent);
            $tempContent = str_ireplace("{ForumRank}", $forumRank, $tempContent);
            $tempContent = str_ireplace("{ParentId}", $parentId, $tempContent);
            $tempContent = str_ireplace("{Sort}", "0", $tempContent);

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
            parent::ReplaceWhenCreate($tempContent, $fields);

            $patterns = '/\{s_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
            parent::ReplaceEnd($tempContent);

            $tempContent = str_ireplace("{ResultJavaScript}", $resultJavaScript, $tempContent);

        }


        return $tempContent;
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
                    parent::DelAllCache();

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
     * 后台版块列表
     */
    private function GenList()
    {
        $siteId = intval(Control::GetRequest("site_id", 0));

        if ($siteId > 0) {

            $tempContent = Template::Load("forum/forum_list.html", "common");
            parent::ReplaceFirst($tempContent);

            $forumManageData = new ForumManageData();
            $forumRank = 0;
            $arrRankOneList = $forumManageData->GetListByRank($siteId, $forumRank);

            $forumRank = 1;
            $arrRankTwoList = $forumManageData->GetListByRank($siteId, $forumRank);
            $forumRank = 2;
            $arrRankThreeList = $forumManageData->GetListByRank($siteId, $forumRank);

            $tagId = "forum_list";
            Template::ReplaceList(
                $tempContent,
                $arrRankOneList,
                $tagId,
                Template::DEFAULT_TAG_NAME,
                $arrRankTwoList,
                "ForumId",
                "ParentId",
                $arrRankThreeList,
                "ForumId",
                "ParentId"
            );

            $tempContent = str_ireplace("{SiteId}", $siteId, $tempContent);


            parent::ReplaceEnd($tempContent);
            return $tempContent;
        } else {
            return parent::ShowError(Language::Load("forum", 1));
        }
    }

    /**
     * 修改论坛状态
     */
    private function AsyncModifyState()
    {
        $forumId = Control::GetRequest("forum_id", 0);
        $state = Control::GetRequest("state", -1);
        $result = -1;
        if ($forumId > 0 && $state >= 0) {
            $forumManageData = new ForumManageData();
            $result = $forumManageData->ModifyState($forumId, $state);

            //删除缓冲
            parent::DelAllCache();
        }
        return $result;
    }

    /**
     * 重置最后发布主题的信息
     */
    private function ResetLastInfo(){

        $siteId = Control::GetRequest("site_id",0);

        echo "begin<br>";

        if($siteId>0){

            $forumManageData = new ForumManageData();
            $forumTopicManageData = new ForumTopicManageData();
            $forumPostManageData = new ForumPostManageData();

            $forumManageData->ResetIsOperate($siteId);

            $arr = $forumManageData->GetListForReset($siteId);

            while(count($arr)>0){

                $forumId = intval($arr[0]["ForumId"]);
                $limit = 8;
                $arrTopicList = $forumTopicManageData->GetList($forumId, $limit);


                $lastPostInfo = "";

                $lastForumTopicId = 0;
                $lastForumTopicTitle = "";
                $lastUserName = "";
                $lastUserId = "";
                $lastPostTime = "";

                for($j = 0; $j<count($arrTopicList);$j++){

                    $forumTopicId = intval($arrTopicList[$j]["ForumTopicId"]);
                    $forumTopicTitle = $arrTopicList[$j]["ForumTopicTitle"];
                    $userId = intval($arrTopicList[$j]["UserId"]);
                    $userName = $arrTopicList[$j]["UserName"];
                    $postTime = $arrTopicList[$j]["PostTime"];

                    if($j == 0){
                        $lastForumTopicId = $forumTopicId;
                        $lastForumTopicTitle = $forumTopicTitle;
                        $lastUserId = $userId;
                        $lastUserName = $userName;
                        $lastPostTime = $postTime;
                    }


                    $lastPostInfo = Format::AddToInfoString(
                        $forumTopicId,
                        $forumTopicTitle,
                        $lastPostInfo);

                }


                $newCount = $forumPostManageData->GetNewCount($forumId);

                $topicCount = $forumTopicManageData->GetTopicCount($forumId);
                $postCount = $forumPostManageData->GetPostCount($forumId);

                $forumManageData->UpdateForumInfo(
                    $forumId,
                    $newCount,
                    $topicCount,
                    $postCount,
                    $lastForumTopicId,
                    $lastForumTopicTitle,
                    $lastUserName,
                    $lastUserId,
                    $lastPostTime,
                    $lastPostInfo
                );

                $forumManageData->ModifyIsOperate($forumId, 1);

                echo "$forumId is finished<br>";

                $arr = $forumManageData->GetListForReset($siteId);

            }

            //$url = "/default.php?secu=manage&mod=forum&m=reset_last_info&site_id=$siteId";
            //header('refresh:0 ' . $url);

        }else{
            echo "site id is error<br>";
        }

        return "finished";

    }

    private function getMoveWindow()
    {
        $result = null;

        $siteId = Control::GetRequest("site_id", 0);
        $forumId = Control::GetRequest("forum_id", 0);
        $forumTopicIds = Control::GetRequest("forum_ids",0);

        ///////////////判断是否有操作权限///////////////////
        $manageUserId = Control::GetManageUserId();
        $manageUserAuthority = new ManageUserAuthorityManageData();
        $can = $manageUserAuthority->CanManageUserModify($siteId, 0, $manageUserId);
        if ($can == 1) {
            $forumManageData = new ForumManageData();
            $forumRank = 0;
            $arrRankOneList = $forumManageData->GetListByRank($siteId, $forumRank);
            $forumRank = 1;
            $arrRankTwoList = $forumManageData->GetListByRank($siteId, $forumRank);
            $forumRank = 2;
            $arrRankThreeList = $forumManageData->GetListByRank($siteId, $forumRank);

            $tempContent = Template::Load("forum/forum_topic_move.html","common");

            $tagId = "forum_list";
            Template::ReplaceList(
                $tempContent,
                $arrRankOneList,
                $tagId,
                Template::DEFAULT_TAG_NAME,
                $arrRankTwoList,
                "ForumId",
                "ParentId",
                $arrRankThreeList,
                "ForumId",
                "ParentId"
            );

            $tempContent = str_ireplace("{SiteId}", $siteId, $tempContent);
            $tempContent = str_ireplace("{forumId}", $forumId, $tempContent);
            $tempContent = str_ireplace("{forumIds}", $forumTopicIds, $tempContent);

            parent::ReplaceEnd($tempContent);
            $result = $tempContent;
        }
        return $result;
    }
}

?>
