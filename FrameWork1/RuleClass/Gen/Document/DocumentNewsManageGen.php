<?php

/**
 * 后台管理 资讯 生成类
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
            case "publish":
                $result = self::AsyncPublish();
                break;
            case "async_modify_sort":
                $result = self::AsyncModifySort();
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
        $resultJavaScript = "";
        parent::ReplaceFirst($tempContent);

        if ($channelId > 0) {

            $channelManageData = new ChannelManageData();
            $siteId = $channelManageData->GetSiteId($channelId, false);
            $channelName = $channelManageData->GetChannelName($channelId, false);

            ///////////////判断是否有操作权限///////////////////
            $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
            $can = $manageUserAuthorityManageData->CanCreate($siteId, $channelId, $manageUserId);
            if (!$can) {
                $resultJavaScript = Control::GetJqueryMessage(Language::Load('document', 26));
                //Control::ShowMessage(Language::Load('document', 26));
                //return "";
            }else{
                $documentNewsManageData = new DocumentNewsManageData();
                $tempContent = str_ireplace("{ChannelId}", $channelId, $tempContent);
                $tempContent = str_ireplace("{ChannelName}", $channelName, $tempContent);
                $tempContent = str_ireplace("{DocumentNewsId}", "", $tempContent);
                $tempContent = str_ireplace("{SiteId}", $siteId, $tempContent);
                $tempContent = str_ireplace("{ManageUserId}", $manageUserId, $tempContent);
                $tempContent = str_ireplace("{ManageUserName}", $manageUserName, $tempContent);
                $tempContent = str_ireplace("{PageIndex}", $pageIndex, $tempContent);

                //pre content
                $documentPreContentManageData = new DocumentPreContentManageData();
                $tagId = "document_pre_content";
                $arrList = $documentPreContentManageData->GetList();
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


                    $documentNewsId = $documentNewsManageData->Create($httpPostData);

                    //加入操作日志
                    $operateContent = 'Create DocumentNews,POST FORM:'.implode('|',$_POST).';\r\nResult:documentNewsId:'.$documentNewsId;
                    self::CreateManageUserLog($operateContent);

                    if ($documentNewsId > 0) {

                        //title pic1
                        $fileElementName = "file_title_pic_1";
                        $tableType = UploadFileData::UPLOAD_TABLE_TYPE_DOCUMENT_NEWS_TITLE_PIC_1; //
                        $tableId = $documentNewsId;
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
                        $fileElementName = "file_title_pic_2";
                        $tableType = UploadFileData::UPLOAD_TABLE_TYPE_DOCUMENT_NEWS_TITLE_PIC_2;
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
                        //title pic3
                        $fileElementName = "file_title_pic_3";

                        $tableType = UploadFileData::UPLOAD_TABLE_TYPE_DOCUMENT_NEWS_TITLE_PIC_3;
                        $uploadFileId3 = 0;

                        $uploadFile3 = new UploadFile();

                        $titlePic3Result = self::Upload(
                            $fileElementName,
                            $tableType,
                            $tableId,
                            $uploadFile3,
                            $uploadFileId3)
                        ;
                        if (intval($titlePic3Result) <=0){
                            //上传出错或没有选择文件上传
                        }else{

                        }

                        if($uploadFileId1>0 || $uploadFileId2>0 || $uploadFileId3>0){
                            $documentNewsManageData->ModifyTitlePic($documentNewsId, $uploadFileId1, $uploadFileId2, $uploadFileId3);
                        }

                        $siteConfigData = new SiteConfigData($siteId);
                        if($uploadFileId1>0){
                            $documentNewsTitlePic1MobileWidth = $siteConfigData->DocumentNewsTitlePicMobileWidth;
                            if($documentNewsTitlePic1MobileWidth<=0){
                                $documentNewsTitlePic1MobileWidth  = 320; //默认320宽
                            }
                            self::GenUploadFileMobile($uploadFileId1,$documentNewsTitlePic1MobileWidth);

                            $documentNewsTitlePicPadWidth = $siteConfigData->DocumentNewsTitlePicPadWidth;
                            if($documentNewsTitlePicPadWidth<=0){
                                $documentNewsTitlePicPadWidth  = 1024; //默认1024宽
                            }
                            self::GenUploadFilePad($uploadFileId1,$documentNewsTitlePicPadWidth);
                        }


                        //新增文档时修改排序号到当前频道的最大排序
                        $documentNewsManageData->ModifySortWhenCreate($channelId, $documentNewsId);
                        //修改上传文件的tableId;
                        $uploadFiles = Control::PostRequest("f_UploadFiles", "");

                        $arrUploadFiles = explode(",", $uploadFiles);

                        $uploadFileData = new UploadFileData();
                        for ($i = 0; $i < count($arrUploadFiles); $i++) {
                            if (intval($arrUploadFiles[$i]) > 0) {
                                $uploadFileData->ModifyTableId(intval($arrUploadFiles[$i]), $documentNewsId);
                            }
                        }

                        //发布模式处理
                        $publishType = $channelManageData->GetPublishType($channelId, false);
                        if ($publishType > 0) {
                            switch ($publishType) {
                                case ChannelManageData::PUBLISH_TYPE_AUTO: //自动发布新稿
                                    //修改文档状态为终审
                                    $state = DocumentNewsData::STATE_FINAL_VERIFY;
                                    $documentNewsManageData->ModifyState($documentNewsId, $state);
                                    $executeTransfer = true; //是否执行发布
                                    $publishChannel = true; //是否同时发布频道
                                    $publishQueueManageData = new PublishQueueManageData();
                                    self::PublishDocumentNews($documentNewsId, $publishQueueManageData, $executeTransfer, $publishChannel);
                                    break;
                            }
                        }

                        //javascript 处理

                        $closeTab = Control::PostRequest("CloseTab",0);
                        if($closeTab == 1){
                            $resultJavaScript .= Control::GetCloseTab();
                        }else{
                            Control::GoUrl($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']);
                        }
                    } else {
                        $resultJavaScript = Control::GetJqueryMessage(Language::Load('document', 2));
                        //Control::ShowMessage(Language::Load('document', 2));
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
            ////////////////////////////////////////////////////
        }

        parent::ReplaceEnd($tempContent);
        $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
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
        $resultJavaScript = "";
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

            $tempContent = str_ireplace("{ChannelId}", $channelId, $tempContent);
            $tempContent = str_ireplace("{DocumentNewsId}", $documentNewsId, $tempContent);
            $tempContent = str_ireplace("{SiteId}", $siteId, $tempContent);
            $tempContent = str_ireplace("{PageIndex}", $pageIndex, $tempContent);

            /////////////////////////////////////////////////
            //pre content
            $documentPreContentManageData = new DocumentPreContentManageData();
            $tagId = "document_pre_content";
            $arrList = $documentPreContentManageData->GetList();
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

                $result = $documentNewsManageData->Modify($httpPostData, $documentNewsId);

                //加入操作日志
                $operateContent = 'Modify DocumentNews,POST FORM:'.implode('|',$_POST).';\r\nResult:'.$result;
                self::CreateManageUserLog($operateContent);

                if ($result > 0) {
                    //编辑完成后，解锁
                    $lockEdit = 0;
                    $documentNewsManageData->ModifyLockEdit($documentNewsId, $lockEdit, $nowManageUserId);

                    $state = DocumentNewsData::STATE_MODIFY; //修改状态为已编
                    $documentNewsManageData->ModifyState($documentNewsId, $state);

                    //题图操作
                    if( !empty($_FILES)){

                        $tableId = $documentNewsId;

                        //title pic1
                        $fileElementName = "file_title_pic_1";
                        $tableType = UploadFileData::UPLOAD_TABLE_TYPE_DOCUMENT_NEWS_TITLE_PIC_1; //channel

                        $uploadFile1 = new UploadFile();
                        $uploadFileId1 = 0;
                        $titlePic1Result = self::Upload(
                            $fileElementName,
                            $tableType,
                            $tableId,
                            $uploadFile1,
                            $uploadFileId1
                        );

                        if (intval($titlePic1Result) <=0 && $uploadFileId1<=0){
                            //上传出错或没有选择文件上传
                        }else{
                            //删除原有题图
                            $oldUploadFileId1 = $documentNewsManageData->GetTitlePic1UploadFileId($documentNewsId, false);
                            parent::DeleteUploadFile($oldUploadFileId1);

                            //修改题图
                            $documentNewsManageData->ModifyTitlePic1UploadFileId($documentNewsId, $uploadFileId1);
                        }

                        //title pic2
                        $fileElementName = "file_title_pic_2";
                        $tableType = UploadFileData::UPLOAD_TABLE_TYPE_DOCUMENT_NEWS_TITLE_PIC_2;
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
                            $oldUploadFileId2 = $documentNewsManageData->GetTitlePic2UploadFileId($documentNewsId, false);
                            parent::DeleteUploadFile($oldUploadFileId2);

                            //修改题图
                            $documentNewsManageData->ModifyTitlePic2UploadFileId($documentNewsId, $uploadFileId2);
                        }
                        //title pic3
                        $fileElementName = "file_title_pic_3";

                        $tableType = UploadFileData::UPLOAD_TABLE_TYPE_DOCUMENT_NEWS_TITLE_PIC_3;
                        $uploadFileId3 = 0;

                        $uploadFile3 = new UploadFile();

                        $titlePic3Result = self::Upload(
                            $fileElementName,
                            $tableType,
                            $tableId,
                            $uploadFile3,
                            $uploadFileId3
                        );
                        if (intval($titlePic3Result) <=0){
                            //上传出错或没有选择文件上传
                        }else{
                            //删除原有题图
                            $oldUploadFileId3 = $documentNewsManageData->GetTitlePic3UploadFileId($documentNewsId, false);
                            parent::DeleteUploadFile($oldUploadFileId3);

                            //修改题图
                            $documentNewsManageData->ModifyTitlePic3UploadFileId($documentNewsId, $uploadFileId3);
                        }

                        //重新制作题图1的相关图片
                        $siteConfigData = new SiteConfigData($siteId);
                        if($uploadFileId1>0){
                            $documentNewsTitlePic1MobileWidth = $siteConfigData->DocumentNewsTitlePicMobileWidth;
                            if($documentNewsTitlePic1MobileWidth<=0){
                                $documentNewsTitlePic1MobileWidth  = 320; //默认320宽
                            }
                            self::GenUploadFileMobile($uploadFileId1,$documentNewsTitlePic1MobileWidth);

                            $documentNewsTitlePicPadWidth = $siteConfigData->DocumentNewsTitlePicPadWidth;
                            if($documentNewsTitlePicPadWidth<=0){
                                $documentNewsTitlePicPadWidth  = 1024; //默认1024宽
                            }
                            self::GenUploadFilePad($uploadFileId1,$documentNewsTitlePicPadWidth);
                        }
                    }


                    //修改上传文件的tableId;
                    $uploadFileData = new UploadFileData();
                    $uploadFiles = Control::PostRequest("f_UploadFiles", "");
                    $arrUploadFiles = explode(",", $uploadFiles);
                    $isBatchUpload = 0;
                    if (isset($_POST['c_ShowPicMethod']) && $_POST['c_ShowPicMethod'] == 'on') {
                        $isBatchUpload = 1;
                    }
                    for ($i = 0; $i < count($arrUploadFiles); $i++) {
                        if (intval($arrUploadFiles[$i]) > 0) {
                            $uploadFileData->ModifyTableId(intval($arrUploadFiles[$i]), $documentNewsId);
                            if($isBatchUpload>0){
                                $uploadFileData->ModifyIsBatchUpload(intval($arrUploadFiles[$i]), $isBatchUpload);
                            }
                        }
                    }

                    //发布模式处理
                    $publishType = $channelManageData->GetPublishType($channelId, false);
                    if ($publishType > 0) {
                        switch ($publishType) {
                            case ChannelManageData::PUBLISH_TYPE_AUTO: //自动发布新稿
                                //修改文档状态为终审
                                $state = DocumentNewsData::STATE_FINAL_VERIFY;
                                $documentNewsManageData->ModifyState($documentNewsId, $state);
                                $executeTransfer = true; //是否执行发布
                                $publishChannel = true; //是否同时发布频道
                                $publishQueueManageData = new PublishQueueManageData();
                                self::PublishDocumentNews($documentNewsId, $publishQueueManageData, $executeTransfer, $publishChannel);
                                break;
                        }
                    }

                    //javascript 处理

                    $closeTab = Control::PostRequest("CloseTab",0);
                    if($closeTab == 1){
                        Control::CloseTab();
                    }else{
                        Control::GoUrl($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']);
                    }
                } else {
                    Control::ShowMessage(Language::Load('document', 2));
                }
            }
        }
        parent::ReplaceEnd($tempContent);
        $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
        return $tempContent;
    }

    private function GenRemoveToBin(){
        $result = "";
        return $result;
    }


    /**
     * 发布资讯详细页面
     * @return int 返回发布结果
     */
    private function AsyncPublish(){
        $result = -1;
        $documentNewsId = Control::GetRequest("document_news_id", -1);
        if($documentNewsId>0){
            $publishQueueManageData = new PublishQueueManageData();
            $executeTransfer = true;
            $publishChannel = true;
            $result = parent::PublishDocumentNews($documentNewsId, $publishQueueManageData, $executeTransfer, $publishChannel);
            if($result == BaseManageGen::PUBLISH_DOCUMENT_NEWS_RESULT_FINISHED){
                for ($i = 0;$i< count($publishQueueManageData->Queue); $i++) {
                    $publishQueueManageData->Queue[$i]["Content"] = "";
                }
                //print_r($publishQueueManageData->Queue);
            }
        }
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
            die(Language::Load('channel', 4));
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

        $sort = Control::GetRequest("sort", "");
        $hit = Control::GetRequest("hit", "");

        if (isset($searchKey) && strlen($searchKey) > 0) {
            $canSearch = $manageUserAuthorityManageData->CanSearch($siteId, $channelId, $manageUserId);
            if (!$canSearch) {
                die(Language::Load('channel', 4));
            }
        }

        if ($pageIndex > 0 && $channelId > 0) {

            $tempContent = str_ireplace("{ChannelId}", $channelId, $tempContent);

            $pageBegin = ($pageIndex - 1) * $pageSize;
            $tagId = "document_news_list";
            $allCount = 0;
            $isSelf = Control::GetRequest("is_self", 0);
            $documentNewsManageData = new DocumentNewsManageData();
            $arrDocumentNewsList = $documentNewsManageData->GetList(
                $channelId,
                $pageBegin,
                $pageSize,
                $allCount,
                $searchKey,
                $searchType,
                $isSelf,
                $manageUserId,
                $sort,
                $hit
            );
            if (count($arrDocumentNewsList) > 0) {
                Template::ReplaceList($tempContent, $arrDocumentNewsList, $tagId);

                $styleNumber = 1;
                $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html", "common");
                $isJs = FALSE;
                $navUrl = "default.php?secu=manage&mod=document_news&m=list&channel_id=$channelId&p={0}&ps=$pageSize&isself=$isSelf&sort=$sort&hit=$hit";
                $jsFunctionName = "";
                $jsParamList = "";
                $pagerButton = Pager::ShowPageButton($pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex, $styleNumber, $isJs, $jsFunctionName, $jsParamList);

                $tempContent = str_ireplace("{pager_button}", $pagerButton, $tempContent);

            } else {
                Template::RemoveCustomTag($tempContent, $tagId);
                $tempContent = str_ireplace("{pager_button}", Language::Load("document", 7), $tempContent);
            }
        }

        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }

    /**
     * 批量修改排序号
     * @return string 返回Jsonp修改结果
     */
    private function AsyncModifySort() {
        $arrDocumentNewsId = Control::GetRequest("sort", null);
        if(!empty($arrDocumentNewsId)){
            $documentNewsManageData = new DocumentNewsManageData();
            $result = $documentNewsManageData->ModifySort($arrDocumentNewsId);
            return Control::GetRequest("jsonpcallback","").'({"result":'.$result.'})';
        }  else{
            return "";
        }
    }

    /**
     * 修改文档状态 状态值定义在Data类中
     * @return string 返回Jsonp修改结果
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
                case DocumentNewsData::STATE_REDO :
                    $can = $manageUserAuthorityManageData->CanRework($siteId, $channelId, $manageUserId);
                    break;
                case DocumentNewsData::STATE_FIRST_VERIFY :
                    $can = $manageUserAuthorityManageData->CanAudit1($siteId, $channelId, $manageUserId);
                    break;
                case DocumentNewsData::STATE_SECOND_VERIFY :
                    $can = $manageUserAuthorityManageData->CanAudit2($siteId, $channelId, $manageUserId);
                    break;
                case DocumentNewsData::STATE_THIRD_VERIFY :
                    $can = $manageUserAuthorityManageData->CanAudit3($siteId, $channelId, $manageUserId);
                    break;
                case DocumentNewsData::STATE_FINAL_VERIFY :
                    $can = $manageUserAuthorityManageData->CanAudit4($siteId, $channelId, $manageUserId);
                    break;
                case DocumentNewsData::STATE_REFUSE :
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
                $result = -2; //没有权限
            }
            ////////////////////////////////////////////////////
            ////////////////////////////////////////////////////
            ////////////////////////////////////////////////////
            ////////////////////////////////////////////////////
            $oldState = $documentNewsManageData->GetState($documentNewsId, false);
            if (($oldState === DocumentNewsData::STATE_PUBLISHED
                    || $oldState === DocumentNewsData::STATE_REFUSE
                )
                && intval($state) === DocumentNewsData::STATE_REFUSE)
            {
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
