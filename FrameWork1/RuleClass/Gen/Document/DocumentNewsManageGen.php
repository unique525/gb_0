<?php

/**
 * 后台Gen总引导类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Document
 * @author zhangchi
 */
class DocumentNewsManageGen extends BaseManageGen implements IBaseManageGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function Gen() {
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
            case "async_modify_sort":
                self::AsyncModifySort();
                break;
            case "async_modify_state":
                $result = self::AsyncModifyState();
                break;
        }
        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    /**
     * 生成资讯管理新增页面
     * @return mixed|string
     */
    private function GenCreate() {
        $tempContent = Template::Load("document/document_news_deal.html","common");
        $channelId = Control::GetRequest("channel_id", 0);
        $manageUserId = Control::GetManageUserId();
        $manageUserName = Control::GetManageUserName();
        $pageIndex = Control::GetRequest("p", 1);

        parent::ReplaceFirst($tempContent);

        if ($channelId > 0) {

            $channelManageData = new ChannelManageData();
            $siteId = $channelManageData->GetSiteId($channelId, false);
            $channelName = $channelManageData->GetChannelName($channelId, false);

            ///////////////判断是否有操作权限///////////////////
            $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
            $can = $manageUserAuthorityManageData->CanCreate($siteId, $channelId, $manageUserId);
            if (!$can) {
                Control::ShowMessage(Language::Load('document', 26));
                return "";
            }
            ////////////////////////////////////////////////////

            $documentNewsManageData = new DocumentNewsManageData();
            $tempContent = str_ireplace("{ChannelId}", $channelId, $tempContent);
            $tempContent = str_ireplace("{ChannelName}", $channelName, $tempContent);
            $tempContent = str_ireplace("{DocumentNewsId}", "", $tempContent);
            $tempContent = str_ireplace("{SiteId}", $siteId, $tempContent);
            $tempContent = str_ireplace("{ManageUserId}", $manageUserId, $tempContent);
            $tempContent = str_ireplace("{ManageUserName}", $manageUserName, $tempContent);
            $tempContent = str_ireplace("{PageIndex}", $pageIndex, $tempContent);

            //quick content
            $documentQuickContentManageData = new DocumentQuickContentManageData();
            $tagId = "document_quick_content";
            $arrList = $documentQuickContentManageData->GetList();
            if (count($arrList) > 0) {
                Template::ReplaceList($tempContent, $arrList, $tagId);
            } else {
                Template::RemoveCustomTag($tempContent, $tagId);
            }

            //source common
            $sourceCommonManageData = new SourceCommonManageData();
            $tagId = "source_common_list";
            $arrList = $sourceCommonManageData->GetList();
            if (count($arrList) > 0) {
                Template::ReplaceList($tempContent, $arrList, $tagId);
            } else {
                Template::RemoveCustomTag($tempContent, $tagId);
            }

            parent::ReplaceWhenCreate($tempContent, $documentNewsManageData->GetFields());

            if (!empty($_POST)) {
                $httpPostData = $_POST;
                $titlePicMobile = '';
                $titlePicPad = '';
                $titlePicMobileUploadFileId = -1;
                $titlePicPadUploadFileId = -1;
                $uploadFileManageData = new UploadFileManageData();
                //title pic1
                $fileElementName = "title_pic_1_upload";
                $uploadTableType = UploadFileManageData::UPLOAD_TABLE_TYPE_DOCUMENT_NEWS_TITLE_PIC_1;
                $titlePic1UploadFileId = 0;
                $titlePic1Path = self::Upload($fileElementName, $uploadTableType, 0, $titlePic1UploadFileId);

                if(intval($titlePic1Path)<=0){
                    //上传出错或没有选择文件上传

                }else{
                    $titlePic1Path = str_ireplace("..", "", $titlePic1Path);
                    //有题图时，再生成两张小图，生成移动题图（移动客户端）及平板电脑上使用的
                    if (strlen($titlePic1Path) > 5) {
                        $siteConfigManageData = new SiteConfigManageData($siteId);
                        $documentNewsTitlePicMobileWidth = $siteConfigManageData->DocumentNewsTitlePicMobileWidth;
                        $documentNewsTitlePicPadWidth = $siteConfigManageData->DocumentNewsTitlePicPadWidth;

                        $tableId = $channelId;
                        $userId = 0;

                        if ($documentNewsTitlePicMobileWidth > 0) {
                            $thumbFileName = "mobile";
                            $titlePicMobile = ImageObject::GenThumb($titlePic1Path,$documentNewsTitlePicMobileWidth,0,$thumbFileName);
                            sleep(1);
                            $tableType = UploadFileManageData::UPLOAD_TABLE_TYPE_DOCUMENT_NEWS_TITLE_PIC_MOBILE;
                            $newFileName = FileObject::GetName($titlePicMobile);
                            $fileExtension = FileObject::GetExtension($titlePicMobile);
                            $filePath = FileObject::GetDirName($titlePicMobile);
                            $fileSize = FileObject::GetSize($titlePicMobile);
                            $titlePicMobileUploadFileId = $uploadFileManageData->Create(
                                $newFileName,
                                $fileSize,
                                $fileExtension,
                                $titlePic1Path,
                                $filePath,
                                $tableType,
                                $tableId,
                                strval(date('Y', time())),
                                strval(date('m', time())),
                                strval(date('d', time())),
                                $manageUserId,
                                $userId
                            );
                        }

                        if ($documentNewsTitlePicPadWidth > 0) {
                            $thumbFileName = "pad";
                            $titlePicPad = ImageObject::GenThumb($titlePic1Path,$documentNewsTitlePicPadWidth,0,$thumbFileName);
                            sleep(1);
                            $tableType = UploadFileManageData::UPLOAD_TABLE_TYPE_DOCUMENT_NEWS_TITLE_PIC_PAD;
                            $newFileName = FileObject::GetName($titlePicPad);
                            $fileExtension = FileObject::GetExtension($titlePicPad);
                            $filePath = FileObject::GetDirName($titlePicPad);
                            $fileSize = FileObject::GetSize($titlePicPad);
                            $titlePicPadUploadFileId = $uploadFileManageData->Create(
                                $newFileName,
                                $fileSize,
                                $fileExtension,
                                $titlePic1Path,
                                $filePath,
                                $tableType,
                                $tableId,
                                strval(date('Y', time())),
                                strval(date('m', time())),
                                strval(date('d', time())),
                                $manageUserId,
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

                $documentNewsId = $documentNewsManageData->Create($httpPostData, $titlePic1Path, $titlePic2Path, $titlePic3Path, $titlePicMobile, $titlePicPad);

                //加入操作日志
                $operateContent = 'Create DocumentNews,POST FORM:'.implode('|',$_POST).';\r\nResult:documentNewsId:'.$documentNewsId;
                self::CreateManageUserLog($operateContent);

                if ($documentNewsId > 0) {
                    //新增文档时修改排序号到当前频道的最大排序
                    $documentNewsManageData->ModifySortWhenCreate($channelId, $documentNewsId);
                    //修改上传文件的tableId;
                    $uploadFiles = Control::PostRequest("f_UploadFiles", "");

                    $arrUploadFiles = explode(",", $uploadFiles);

                    //修改题图1的tableId
                    if($titlePic1UploadFileId>0){
                        $uploadFileManageData->ModifyTableId($titlePic1UploadFileId, $documentNewsId);
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
                    if($titlePicMobileUploadFileId>0){
                        $uploadFileManageData->ModifyTableId($titlePicMobileUploadFileId, $documentNewsId);
                    }
                    //修改平板客户端题图的tableId
                    if($titlePicPadUploadFileId>0){
                        $uploadFileManageData->ModifyTableId($titlePicPadUploadFileId, $documentNewsId);
                    }


                    for ($i = 0; $i < count($arrUploadFiles); $i++) {
                        if (intval($arrUploadFiles[$i]) > 0) {
                            $uploadFileManageData->ModifyTableId(intval($arrUploadFiles[$i]), $documentNewsId);
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
            //去掉s开头的标记 {s_xxx_xxx}
            $patterns = '/\{s_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
            //去掉c开头的标记 {c_xxx}
            $patterns = '/\{c_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
            //去掉r开头的标记 {r_xxx_xxx}
            $patterns = '/\{r_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
        }
        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }

    /**
     * 生成资讯管理修改页面
     * @return mixed|string
     */
    private function GenModify() {
        $tempContent = Template::Load("document/document_news_deal.html", "common");
        $documentNewsId = Control::GetRequest("document_news_id", 0);

        $nowManageUserId = Control::GetManageUserId();
        $pageIndex = Control::GetRequest("p", 1);

        parent::ReplaceFirst($tempContent);
        if ($documentNewsId > 0) {
            $documentNewsManageData = new DocumentNewsManageData();
            $manageUserManageData = new ManageUserManageData();
            //检查编辑锁
            $lockEdit = $documentNewsManageData->GetLockEdit($documentNewsId, false);
            $lockEditDate = $documentNewsManageData->GetLockEditDate($documentNewsId, false);
            $lockEditManageUserId = $documentNewsManageData->GetLockEditManageUserId($documentNewsId, false);

            $dateNowSpan = strtotime(date("Y-m-d H:i:s", time()));
            $lockEditDateSpan = strtotime(date("Y-m-d H:i:s", strtotime($lockEditDate)) . " +5 minute");

            if ($lockEditManageUserId > 0 && $lockEdit > 0 && $lockEditDateSpan > $dateNowSpan && empty($_POST) && $lockEditManageUserId != $nowManageUserId) {
                //当前已经锁定，并且锁定时间在5分钟内

                $lockEditManageUserName = $manageUserManageData->GetManageUserName($lockEditManageUserId);
                $returnInfo = Language::Load('document', 36);
                $returnInfo = str_ireplace("{manage_user_name}", $lockEditManageUserName, $returnInfo);
                return $returnInfo;
            } else {
                //未锁定，则上锁
                $lockEdit = 1;
                $documentNewsManageData->ModifyLockEdit($documentNewsId, $lockEdit, $nowManageUserId);
            }


            $channelManageData = new ChannelManageData();
            $channelId = $documentNewsManageData->GetChannelId($documentNewsId, false);
            $withCache = FALSE;
            $siteId = $channelManageData->GetSiteId($channelId, $withCache);

            ///////////////判断是否有操作权限///////////////////
            $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
            //1 编辑本频道文档权限
            $can = $manageUserAuthorityManageData->CanModify($siteId, $channelId, $nowManageUserId);
            if ($can) { //有编辑本频道文档权限
                //2 检查是否有在本频道编辑他人文档的权限
                $documentNewsManageUserId = $documentNewsManageData->GetManageUserId($documentNewsId, false);
                if ($documentNewsManageUserId !== $nowManageUserId) { //发稿人与当前操作人不是同一人时才判断
                    $can = $manageUserAuthorityManageData->CanDoOthers($siteId, $channelId, $nowManageUserId);
                }else{
                    //如果发稿人与当前操作人是同一人，则不处理
                }
                //3 检查是否有在本频道编辑同一管理组他人文档的权限
                if(!$can){
                    //是否是同一管理组
                    $documentNewsManageUserGroupId = $manageUserManageData->GetManageUserGroupId($documentNewsManageUserId);
                    $nowManageUserGroupId = $manageUserManageData->GetManageUserGroupId($nowManageUserId);

                    if ($documentNewsManageUserGroupId == $nowManageUserGroupId) {
                        //是同一组才进行判断
                        $can = $manageUserAuthorityManageData->CanDoSameGroupOthers($siteId, $channelId, $nowManageUserId);
                    }
                }
            }
            if(!$can){
                return Language::Load('document', 26);
            }

            ////////////////////////////////////////////////////
            $replace_arr = array(
                "{ChannelId}" => $channelId,
                "{DocumentNewsId}" => $documentNewsId,
                "{SiteId}" => $siteId,
                "{PageIndex}" => $pageIndex
            );
            $tempContent = strtr($tempContent, $replace_arr);
            $tempContent = str_ireplace("{ChannelId}", $channelId, $tempContent);
            $tempContent = str_ireplace("{DocumentNewsId}", $documentNewsId, $tempContent);
            $tempContent = str_ireplace("{SiteId}", $siteId, $tempContent);
            $tempContent = str_ireplace("{PageIndex}", $pageIndex, $tempContent);

            /////////////////////////////////////////////////
            //quick content
            $documentQuickContentManageData = new DocumentQuickContentManageData();
            $tagId = "document_quick_content";
            $arrList = $documentQuickContentManageData->GetList();
            if (count($arrList) > 0) {
                Template::ReplaceList($tempContent, $arrList, $tagId);
            } else {
                Template::RemoveCustomTag($tempContent, $tagId);
            }

            //source common
            $sourceCommonManageData = new SourceCommonManageData();
            $tagId = "source_common_list";
            $arrList = $sourceCommonManageData->GetList();
            if (count($arrList) > 0) {
                Template::ReplaceList($tempContent, $arrList, $tagId);
            } else {
                Template::RemoveCustomTag($tempContent, $tagId);
            }



            $arrOne = $documentNewsManageData->GetOne($documentNewsId);
            Template::ReplaceOne($tempContent, $arrOne, false, false);
            //去掉s开头的标记 {s_xxx_xxx}
            $patterns = '/\{s_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
            //去掉c开头的标记 {c_xxx}
            $patterns = '/\{c_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
            //去掉r开头的标记 {r_xxx_xxx}
            $patterns = '/\{r_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
            if (!empty($_POST)) {

                $httpPostData = $_POST;
                $titlePicMobile = '';
                $titlePicPad = '';
                $titlePicMobileUploadFileId = -1;
                $titlePicPadUploadFileId = -1;
                $uploadFileManageData = new UploadFileManageData();
                //title pic1
                $fileElementName = "file_title_pic_1";
                $uploadTableType = UploadFileManageData::UPLOAD_TABLE_TYPE_DOCUMENT_NEWS_TITLE_PIC_1;
                $titlePic1UploadFileId = 0;
                $titlePic1Path = self::Upload($fileElementName, $uploadTableType, 0, $titlePic1UploadFileId);

                if(intval($titlePic1Path)<=0){
                    //上传出错或没有选择文件上传

                }else{
                    $titlePic1Path = str_ireplace("..", "", $titlePic1Path);
                    //有题图时，再生成两张小图，生成移动题图（移动客户端）及平板电脑上使用的
                    if (strlen($titlePic1Path) > 5) {
                        $siteConfigManageData = new SiteConfigManageData($siteId);
                        $documentNewsTitlePicMobileWidth = $siteConfigManageData->DocumentNewsTitlePicMobileWidth;
                        $documentNewsTitlePicPadWidth = $siteConfigManageData->DocumentNewsTitlePicPadWidth;

                        $tableId = 0;
                        $userId = 0;

                        if ($documentNewsTitlePicMobileWidth > 0) {
                            $thumbFileName = "mobile";
                            $titlePicMobile = ImageObject::GenThumb($titlePic1Path,$documentNewsTitlePicMobileWidth,0,$thumbFileName);
                            sleep(1);
                            $tableType = UploadFileManageData::UPLOAD_TABLE_TYPE_DOCUMENT_NEWS_TITLE_PIC_MOBILE;
                            $newFileName = FileObject::GetName($titlePicMobile);
                            $fileExtension = FileObject::GetExtension($titlePicMobile);
                            $filePath = FileObject::GetDirName($titlePicMobile);
                            $fileSize = FileObject::GetSize($titlePicMobile);
                            $titlePicMobileUploadFileId = $uploadFileManageData->Create(
                                $newFileName,
                                $fileSize,
                                $fileExtension,
                                $titlePic1Path,
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

                        if ($documentNewsTitlePicPadWidth > 0) {
                            $thumbFileName = "pad";
                            $titlePicPad = ImageObject::GenThumb($titlePic1Path,$documentNewsTitlePicPadWidth,0,$thumbFileName);
                            sleep(1);
                            $tableType = UploadFileManageData::UPLOAD_TABLE_TYPE_DOCUMENT_NEWS_TITLE_PIC_PAD;
                            $newFileName = FileObject::GetName($titlePicPad);
                            $fileExtension = FileObject::GetExtension($titlePicPad);
                            $filePath = FileObject::GetDirName($titlePicPad);
                            $fileSize = FileObject::GetSize($titlePicPad);
                            $titlePicPadUploadFileId = $uploadFileManageData->Create(
                                $newFileName,
                                $fileSize,
                                $fileExtension,
                                $titlePic1Path,
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
                $fileElementName = "file_title_pic_2";
                $uploadTableType = UploadFileManageData::UPLOAD_TABLE_TYPE_DOCUMENT_NEWS_TITLE_PIC_2;
                $titlePic2UploadFileId = 0;
                $titlePic2Path = self::Upload($fileElementName, $uploadTableType, 0, $titlePic2UploadFileId);

                if(intval($titlePic2Path) <= 0){
                    //上传出错或没有选择文件上传
                }else{
                    $titlePic2Path = str_ireplace("..", "", $titlePic2Path);
                }

                //title pic3
                $fileElementName = "file_title_pic_3";
                $uploadTableType = UploadFileManageData::UPLOAD_TABLE_TYPE_DOCUMENT_NEWS_TITLE_PIC_3;
                $titlePic3UploadFileId = 0;
                $titlePic3Path = self::Upload($fileElementName, $uploadTableType, 0, $titlePic3UploadFileId);

                if(intval($titlePic3Path) <= 0){
                    //上传出错或没有选择文件上传
                }else{
                    $titlePic3Path = str_ireplace("..", "", $titlePic3Path);
                }

                $result = $documentNewsManageData->Modify($httpPostData, $titlePic1Path, $titlePic2Path, $titlePic3Path, $titlePicMobile, $titlePicPad);

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
                    if($titlePic1UploadFileId>0){
                        $uploadFileManageData->ModifyTableId($titlePic1UploadFileId, $documentNewsId);
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
                    if($titlePicMobileUploadFileId>0){
                        $uploadFileManageData->ModifyTableId($titlePicMobileUploadFileId, $documentNewsId);
                    }
                    //修改平板客户端题图的tableId
                    if($titlePicPadUploadFileId>0){
                        $uploadFileManageData->ModifyTableId($titlePicPadUploadFileId, $documentNewsId);
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
        }
        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }

    private function GenRemoveToBin(){
        $result = "";
        return $result;
    }


    /**
     * 生成资讯管理列表页面
     */
    private function GenList() {
        $channelId = Control::GetRequest("channel_id", 0);
        if ($channelId <= 0) {
            return null;
        }
        $manageUserId = Control::GetManageUserId();
        $channelManageData = new ChannelManageData();
        $siteId = $channelManageData->GetSiteId($channelId, false);
        if($siteId<=0){
            return null;
        }

        ///////////////判断是否有操作权限///////////////////
        $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
        $canExplore = $manageUserAuthorityManageData->CanExplore($siteId, $channelId, $manageUserId);
        if (!$canExplore) {
            return Language::Load('document', 26);
        }

        //load template
        $tempContent = Template::Load("document/document_news_list.html", "common");


        parent::ReplaceFirst($tempContent);

        ////////////////////////////////////////////////////
        ///////////////输出权限到页面///////////////////
        ////////////////////////////////////////////////////
        $canRework = $manageUserAuthorityManageData->CanRework($siteId, $channelId, $manageUserId);
        $canAudit1 = $manageUserAuthorityManageData->CanAudit1($siteId, $channelId, $manageUserId);
        $canAudit2 = $manageUserAuthorityManageData->CanAudit2($siteId, $channelId, $manageUserId);
        $canAudit3 = $manageUserAuthorityManageData->CanAudit3($siteId, $channelId, $manageUserId);
        $canAudit4 = $manageUserAuthorityManageData->CanAudit4($siteId, $channelId, $manageUserId);
        $canRefused = $manageUserAuthorityManageData->CanRefused($siteId, $channelId, $manageUserId);
        $canPublish = $manageUserAuthorityManageData->CanPublish($siteId, $channelId, $manageUserId);
        $canModify = $manageUserAuthorityManageData->CanModify($siteId, $channelId, $manageUserId);
        $canCreate = $manageUserAuthorityManageData->CanCreate($siteId, $channelId, $manageUserId);


        $tempContent = str_ireplace("{CanRework}", $canRework == 1 ? "" : "display:none", $tempContent);
        $tempContent = str_ireplace("{CanAudit1}", $canAudit1 == 1 ? "" : "display:none", $tempContent);
        $tempContent = str_ireplace("{CanAudit2}", $canAudit2 == 1 ? "" : "display:none", $tempContent);
        $tempContent = str_ireplace("{CanAudit3}", $canAudit3 == 1 ? "" : "display:none", $tempContent);
        $tempContent = str_ireplace("{CanAudit4}", $canAudit4 == 1 ? "" : "display:none", $tempContent);
        $tempContent = str_ireplace("{CanRefused}", $canRefused == 1 ? "" : "display:none", $tempContent);
        $tempContent = str_ireplace("{CanPublish}", $canPublish == 1 ? "" : "display:none", $tempContent);
        $tempContent = str_ireplace("{CanModify}", $canModify == 1 ? "" : "display:none", $tempContent);
        $tempContent = str_ireplace("{CanCreate}", $canCreate == 1 ? "" : "display:none", $tempContent);

        $pageSize = Control::GetRequest("ps", 20);
        $pageIndex = Control::GetRequest("p", 1);
        $searchKey = Control::GetRequest("search_key", "");
        $searchType = Control::GetRequest("search_type", -1);
        $searchKey = urldecode($searchKey);
        if (isset($searchKey) && strlen($searchKey) > 0) {
            $canSearch = $manageUserAuthorityManageData->CanSearch($siteId, $channelId, $manageUserId);
            if (!$canSearch) {
                return Language::Load('document', 26);
            }
        }

        if ($pageIndex > 0 && $channelId > 0) {
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $tagId = "document_news_list";
            $allCount = 0;
            $isSelf = Control::GetRequest("is_self", 0);
            $documentNewsManageData = new DocumentNewsManageData();
            $arrDocumentNewsList = $documentNewsManageData->GetList($channelId, $pageBegin, $pageSize, $allCount, $searchKey, $searchType, $isSelf, $manageUserId);
            if (count($arrDocumentNewsList) > 0) {
                Template::ReplaceList($tempContent, $arrDocumentNewsList, $tagId);

                /**
                $styleNumber = 1;
                $pagerTemplate = Template::Load("../common/pager_style$styleNumber.html");
                $isJs = FALSE;
                $navUrl = "default.php?secu=manage&mod=documentnews&m=listformanage&cid=$channelId&p={0}&ps=$pageSize&isself=$isSelf";
                $jsFunctionName = "";
                $jsParamList = "";
                $pagerButton = Pager::ShowPageButton($pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex, $styleNumber, $isJs, $jsFunctionName, $jsParamList);

                $tempContent = str_ireplace("{documentchannelid}", $channelId, $tempContent);
                $tempContent = str_ireplace("{cid}", $channelId, $tempContent);
                $tempContent = str_ireplace("{pagerbutton}", $pagerButton, $tempContent);
                $tempContent = str_ireplace("{siteurl}", $siteUrl, $tempContent);
                 */
            } else {
                Template::RemoveCustomTag($tempContent, $tagId);
                $tempContent = str_ireplace("{pager_button}", Language::Load("document", 7), $tempContent);
            }
        }

        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }

    /**
     * 更新文档的排序（拖动时使用）
     */
    private function AsyncModifySort() {
        $arrDocumentNewsId = Control::GetRequest("sort", null);
        $documentNewsManageData = new DocumentNewsManageData();
        $documentNewsManageData->ModifySort($arrDocumentNewsId);
    }

    /**
     * 修改文档状态 状态值定义在Data类中
     */
    private function AsyncModifyState() {
        $result = -1;
        $documentNewsId = Control::GetRequest("document_news_id", 0);
        $state = Control::GetRequest("state", -1);
        if ($documentNewsId > 0 && $state >= 0) {
            $documentNewsManageData = new DocumentNewsManageData();
            $manageUserManageData = new ManageUserManageData();
            $channelId = $documentNewsManageData->GetChannelId($documentNewsId, false);
            $manageUserId = Control::GetManageUserId();
            $siteId = $documentNewsManageData->GetSiteId($documentNewsId, false);
            /**********************************************************************
             ******************************判断是否有操作权限**********************
             **********************************************************************/
            $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
            $can = false;
            switch ($state) {
                case DocumentNewsManageData::STATE_REDO :
                    $can = $manageUserAuthorityManageData->CanRework($siteId, $channelId, $manageUserId);
                    break;
                case DocumentNewsManageData::STATE_FIRST_VERIFY :
                    $can = $manageUserAuthorityManageData->CanAudit1($siteId, $channelId, $manageUserId);
                    break;
                case DocumentNewsManageData::STATE_SECOND_VERIFY :
                    $can = $manageUserAuthorityManageData->CanAudit2($siteId, $channelId, $manageUserId);
                    break;
                case DocumentNewsManageData::STATE_THIRD_VERIFY :
                    $can = $manageUserAuthorityManageData->CanAudit3($siteId, $channelId, $manageUserId);
                    break;
                case DocumentNewsManageData::STATE_FINAL_VERIFY :
                    $can = $manageUserAuthorityManageData->CanAudit4($siteId, $channelId, $manageUserId);
                    break;
                case DocumentNewsManageData::STATE_REFUSE :
                    $can = $manageUserAuthorityManageData->CanRefused($siteId, $channelId, $manageUserId);
                    break;
            }
            if ($can) { //有修改状态权限
                //2 检查是否有在本频道编辑他人文档的权限
                $documentNewsManageUserId = $documentNewsManageData->GetManageUserId($documentNewsId, false);
                if ($documentNewsManageUserId !== $manageUserId) { //发稿人与当前操作人不是同一人时才判断
                    $can = $manageUserAuthorityManageData->CanDoOthers($siteId, $channelId, $manageUserId);
                }else{
                    //如果发稿人与当前操作人是同一人，则不处理
                }
                //3 检查是否有在本频道编辑同一管理组他人文档的权限
                if(!$can){
                    //是否是同一管理组
                    $documentNewsManageUserGroupId = $manageUserManageData->GetManageUserGroupId($documentNewsManageUserId);
                    $nowManageUserGroupId = $manageUserManageData->GetManageUserGroupId($manageUserId);
                    if ($documentNewsManageUserGroupId == $nowManageUserGroupId) {
                        //是同一组才进行判断
                        $can = $manageUserAuthorityManageData->CanDoSameGroupOthers($siteId, $channelId, $manageUserId);
                    }
                }
            }
            if (!$can) {
                return -2; //没有权限
            }
            ////////////////////////////////////////////////////
            ////////////////////////////////////////////////////
            ////////////////////////////////////////////////////
            ////////////////////////////////////////////////////
            $oldState = $documentNewsManageData->GetState($documentNewsId, false);
            if (($oldState === DocumentNewsManageData::STATE_PUBLISHED || $oldState === DocumentNewsManageData::STATE_REFUSE) && intval($state) === DocumentNewsManageData::STATE_REFUSE) {
                $cancelPublishResult = parent::CancelPublishDocumentNews($documentNewsId);
                if($cancelPublishResult){
                    //修改状态
                    $result = $documentNewsManageData->ModifyState($documentNewsId, $state);
                }
            }else{
                //修改状态
                $result = $documentNewsManageData->ModifyState($documentNewsId, $state);
            }

            //加入操作日志
            $operateContent = 'Modify State DocumentNews,GET PARAM:'.implode('|',$_GET).';\r\nResult:'.$result;
            self::CreateManageUserLog($operateContent);
        }
        return $result;
    }

}

?>
