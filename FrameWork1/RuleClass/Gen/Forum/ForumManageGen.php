<?php

/**
 * 后台论坛生成类 
 * @category iCMS
 * @package iCMS_Rules_Gen_Forum
 * @author zhangchi
 */
class ForumManageGen extends BaseManageGen implements IBaseManageGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function Gen() {
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
        }
        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    /**
     * 新增论坛版块
     * @return string 模板内容页面
     */
    private function GenCreate(){
        $tempContent = "";
        $siteId = Control::GetRequest("site_id", 0);
        $rank = Control::GetRequest("rank", -1);

        if($siteId>0 && $rank>=0){
            $tempContent = Template::Load("forum/forum_deal.html", "common");
            parent::ReplaceFirst($tempContent);
            $forumManageData = new ForumManageData();
            if($rank>0){
                $parentId = Control::GetRequest("parent_id", -1);
                if($parentId>0){
                    $parentName = $forumManageData->GetForumName($parentId, false);
                    $tempContent = str_ireplace("{ParentName}", $parentName, $tempContent);
                }
            }

            if (!empty($_POST)) {

                $httpPostData = $_POST;
                $forumPicMobile = '';
                $forumPicPad = '';
                $forumPicMobileUploadFileId = -1;
                $forumPicPadUploadFileId = -1;
                $uploadFileManageData = new UploadFileManageData();
                //title pic1
                $fileElementName = "file_forum_pic_1";
                $uploadTableType = UploadFileManageData::UPLOAD_TABLE_TYPE_FORUM_PIC_1;
                $forumPic1UploadFileId = 0;
                $forumPic1Path = self::Upload($fileElementName, $uploadTableType, 0, $forumPic1UploadFileId);

                if(intval($forumPic1Path)<=0){
                    //上传出错或没有选择文件上传

                }else{
                    $forumPic1Path = str_ireplace("..", "", $forumPic1Path);
                    //有题图时，再生成两张小图，生成移动题图（移动客户端）及平板电脑上使用的
                    if (strlen($forumPic1Path) > 5) {
                        $siteConfigManageData = new SiteConfigManageData($siteId);
                        $forumPicMobileWidth = $siteConfigManageData->ForumPicMobileWidth;
                        $forumPicPadWidth = $siteConfigManageData->ForumPicPadWidth;

                        $tableId = 0;
                        $userId = 0;

                        if ($forumPicMobileWidth > 0) {
                            $thumbFileName = "mobile";
                            $forumPicMobile = ImageObject::GenThumb($forumPic1Path,$forumPicMobileWidth,0,$thumbFileName);
                            sleep(1);
                            $tableType = UploadFileManageData::UPLOAD_TABLE_TYPE_DOCUMENT_NEWS_TITLE_PIC_MOBILE;
                            $newFileName = FileObject::GetName($forumPicMobile);
                            $fileExtension = FileObject::GetExtension($forumPicMobile);
                            $filePath = FileObject::GetDirName($forumPicMobile);
                            $fileSize = FileObject::GetSize($forumPicMobile);
                            $forumPicMobileUploadFileId = $uploadFileManageData->Create(
                                $newFileName,
                                $fileSize,
                                $fileExtension,
                                $forumPic1Path,
                                $filePath,
                                $tableType,
                                $tableId,
                                strval(date('Y', time())),
                                strval(date('m', time())),
                                strval(date('d', time())),
                                $nowManageUserId,
                                $userId
                            );
                        }

                        if ($forumPicPadWidth > 0) {
                            $thumbFileName = "pad";
                            $forumPicPad = ImageObject::GenThumb($forumPic1Path,$forumPicPadWidth,0,$thumbFileName);
                            sleep(1);
                            $tableType = UploadFileManageData::UPLOAD_TABLE_TYPE_DOCUMENT_NEWS_TITLE_PIC_PAD;
                            $newFileName = FileObject::GetName($forumPicPad);
                            $fileExtension = FileObject::GetExtension($forumPicPad);
                            $filePath = FileObject::GetDirName($forumPicPad);
                            $fileSize = FileObject::GetSize($forumPicPad);
                            $forumPicPadUploadFileId = $uploadFileManageData->Create(
                                $newFileName,
                                $fileSize,
                                $fileExtension,
                                $forumPic1Path,
                                $filePath,
                                $tableType,
                                $tableId,
                                strval(date('Y', time())),
                                strval(date('m', time())),
                                strval(date('d', time())),
                                $nowManageUserId,
                                $userId
                            );
                        }
                    }
                }

                //title pic2
                $fileElementName = "title_pic_2_upload";
                $uploadTableType = UploadFileManageData::UPLOAD_TABLE_TYPE_DOCUMENT_NEWS_TITLE_PIC_2;
                $titlePic2UploadFileId = 0;
                $titlePic2Path = self::Upload($fileElementName, $uploadTableType, 0, $titlePic2UploadFileId);

                if(intval($titlePic2Path) <= 0){
                    //上传出错或没有选择文件上传
                }else{
                    $titlePic2Path = str_ireplace("..", "", $titlePic2Path);
                }

                //title pic3
                $fileElementName = "title_pic_3_upload";
                $uploadTableType = UploadFileManageData::UPLOAD_TABLE_TYPE_DOCUMENT_NEWS_TITLE_PIC_3;
                $titlePic3UploadFileId = 0;
                $titlePic3Path = self::Upload($fileElementName, $uploadTableType, 0, $titlePic3UploadFileId);

                if(intval($titlePic3Path) <= 0){
                    //上传出错或没有选择文件上传
                }else{
                    $titlePic3Path = str_ireplace("..", "", $titlePic3Path);
                }

                $result = $documentNewsManageData->Modify($httpPostData, $forumPic1Path, $titlePic2Path, $titlePic3Path, $forumPicMobile, $forumPicPad);

                //加入操作日志
                $operateContent = 'Modify DocumentNews,POST FORM:'.implode('|',$_POST).';\r\nResult:'.$result;
                self::CreateManageUserLog($operateContent);

                if ($result > 0) {
                    //编辑完成后，解锁
                    $lockEdit = 0;
                    $documentNewsManageData->ModifyLockEdit($documentNewsId, $lockEdit, $nowManageUserId);


                    //修改上传文件的tableId;
                    $uploadFiles = Control::PostRequest("f_UploadFiles", "");

                    $arrUploadFiles = explode(",", $uploadFiles);

                    //修改题图1的tableId
                    if($forumPic1UploadFileId>0){
                        $uploadFileManageData->ModifyTableId($forumPic1UploadFileId, $documentNewsId);
                    }
                    //修改题图2的tableId
                    if($titlePic2UploadFileId>0){
                        $uploadFileManageData->ModifyTableId($titlePic2UploadFileId, $documentNewsId);
                    }
                    //修改题图3的tableId
                    if($titlePic3UploadFileId>0){
                        $uploadFileManageData->ModifyTableId($titlePic3UploadFileId, $documentNewsId);
                    }
                    //修改移动客户端题图的tableId
                    if($forumPicMobileUploadFileId>0){
                        $uploadFileManageData->ModifyTableId($forumPicMobileUploadFileId, $documentNewsId);
                    }
                    //修改平板客户端题图的tableId
                    if($forumPicPadUploadFileId>0){
                        $uploadFileManageData->ModifyTableId($forumPicPadUploadFileId, $documentNewsId);
                    }

                    $isBatchUpload = 0;
                    if (isset($_POST['c_ShowPicMethod']) && $_POST['c_ShowPicMethod'] == 'on') {
                        $isBatchUpload = 1;
                    }
                    for ($i = 0; $i < count($arrUploadFiles); $i++) {
                        if (intval($arrUploadFiles[$i]) > 0) {
                            $uploadFileManageData->ModifyTableId(intval($arrUploadFiles[$i]), $documentNewsId);
                            if($isBatchUpload>0){
                                $uploadFileManageData->ModifyIsBatchUpload(intval($arrUploadFiles[$i]), $isBatchUpload);
                            }
                        }
                    }

                    //发布模式处理
                    $publishType = $channelManageData->GetPublishType($channelId, false);
                    if ($publishType > 0) {
                        switch ($publishType) {
                            case ChannelManageData::PUBLISH_TYPE_AUTO: //自动发布新稿
                                //修改文档状态为终审
                                $state = DocumentNewsManageData::STATE_FINAL_VERIFY;
                                $documentNewsManageData->ModifyState($documentNewsId, $state);
                                $executeFtp = true;
                                $publishChannel = true;
                                $ftpQueueManageData = new FtpQueueManageData();
                                self::PublishDocumentNews($documentNewsId, $ftpQueueManageData, $executeFtp, $publishChannel);
                                break;
                        }
                    }

                    //javascript 处理

                    $closeTab = Control::PostRequest("CloseTab",0);
                    if($closeTab == 1){
                        Control::CloseTab();
                    }else{
                        Control::GoUrl($_SERVER["PHP_SELF"]);
                    }
                } else {
                    Control::ShowMessage(Language::Load('document', 2));
                }


            }

            $fieldsOfChannel = $forumManageData->GetFields();
            parent::ReplaceWhenCreate($tempContent, $fieldsOfChannel);

            $patterns = '/\{s_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
            parent::ReplaceEnd($tempContent);
        }


        return $tempContent;
    }


    /**
     * 后台版块列表
     */
    private function GenList() {
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
            $resultTemplate = "";
            if (count($arrRankOneList) > 0) {

                $forumManageListOneTemplate = Template::Load("forum/forum_list_one.html", "common");
                $forumManageListTwoTemplate = Template::Load("forum/forum_list_two.html", "common");



                for ($i = 0; $i < count($arrRankOneList); $i++) {
                    $rankOneForumId = intval($arrRankOneList[$i]["ForumId"]);
                    $rankOneForumName = $arrRankOneList[$i]["ForumName"];
                    $rankOneForumType = intval($arrRankOneList[$i]["ForumType"]);
                    $rankOneState = intval($arrRankOneList[$i]["State"]);
                    $rankOneSort = intval($arrRankOneList[$i]["Sort"]);
                    $rankOneForumMode = intval($arrRankOneList[$i]["ForumMode"]);
                    $rankOneForumAccess = intval($arrRankOneList[$i]["ForumAccess"]);

                    $forumOneTemplate = $forumManageListOneTemplate;
                    $forumOneTemplate = str_ireplace("{f_ForumId}", $rankOneForumId, $forumOneTemplate);
                    $forumOneTemplate = str_ireplace("{f_ForumName}", $rankOneForumName, $forumOneTemplate);
                    $forumOneTemplate = str_ireplace("{f_State}", $rankOneState, $forumOneTemplate);
                    $forumOneTemplate = str_ireplace("{f_Sort}", $rankOneSort, $forumOneTemplate);

                    $resultTemplate = $resultTemplate . $forumOneTemplate;

                    for ($j = 0; $j < count($arrRankTwoList); $j++) {
                        $rankTwoForumId = intval($arrRankTwoList[$j]["ForumId"]);
                        $rankTwoParentId = intval($arrRankTwoList[$j]["ParentId"]);
                        $rankTwoForumName = $arrRankTwoList[$j]["ForumName"];
                        $rankTwoForumType = intval($arrRankTwoList[$j]["ForumType"]);
                        $rankTwoState = intval($arrRankTwoList[$j]["State"]);
                        $rankTwoSort = intval($arrRankTwoList[$j]["Sort"]);
                        $rankTwoForumMode = intval($arrRankTwoList[$j]["ForumMode"]);
                        $rankTwoForumAccess = intval($arrRankTwoList[$j]["ForumAccess"]);

                        if ($rankOneForumId === $rankTwoParentId) {
                            $forumTwoTemplate = $forumManageListTwoTemplate;
                            $forumTwoTemplate = str_ireplace("{f_ForumId}", $rankTwoForumId, $forumTwoTemplate);
                            $forumTwoTemplate = str_ireplace("{f_ForumName}", $rankTwoForumName, $forumTwoTemplate);
                            $forumTwoTemplate = str_ireplace("{f_State}", $rankTwoState, $forumTwoTemplate);
                            $forumTwoTemplate = str_ireplace("{f_Sort}", $rankTwoSort, $forumTwoTemplate);
                            $resultTemplate = $resultTemplate . $forumTwoTemplate;
                        }
                    }
                }
            }

            $tempContent = str_ireplace("{ForumList}", $resultTemplate, $tempContent);


            parent::ReplaceEnd($tempContent);
            return $tempContent;
        } else {
            return parent::ShowError(Language::Load("forum", 1));
        }
    }

    /**
     * 修改论坛状态
     */
    private function AsyncModifyState() {
        $forumId = Control::GetRequest("forum_id", 0);
        $state = Control::GetRequest("state", -1);
        $result = -1;
        if ($forumId > 0 && $state >= 0) {
            $forumManageData = new ForumManageData();
            $result = $forumManageData->ModifyState($forumId, $state);
        }
        return $result;
    }

}

?>
