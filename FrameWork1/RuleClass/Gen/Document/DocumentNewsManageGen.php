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
            $siteid = $channelManageData->GetSiteId($channelId, false);
            $channelName = $channelManageData->GetChannelName($channelId, false);

            ///////////////判断是否有操作权限///////////////////
            $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
            $can = $manageUserAuthorityManageData->CanCreate($siteid, $channelId, $manageUserId);
            if (!$can) {
                Control::ShowMessage(Language::Load('document', 26));
                //删除标签页

                return "";
            }
            ////////////////////////////////////////////////////

            $documentNewsManageData = new DocumentNewsManageData();

            $replace_arr = array(
                "{channel_id}" => $channelId,
                "{channel_name}" => $channelName,
                "{document_news_id}" => "",
                "{site_id}" => $siteid,
                "{manage_user_id}" => $manageUserId,
                "{manage_user_name}" => $manageUserName,
                "{page_index}" => $pageIndex
            );
            $tempContent = strtr($tempContent, $replace_arr);

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
                //title pic1
                $fileElementName = "title_pic_1_upload";
                $uploadTableType = 1; //document news
                $titlePic1UploadFileId = 0;
                $titlePic1Path = self::Upload($fileElementName, $uploadTableType, 0, $titlePic1UploadFileId);
                $titlePic1Path = str_ireplace("..", "", $titlePic1Path);

                if (!empty($titlePic1Path)) {
                    sleep(1);
                }
                //有题图时，再生成两张小图，生成移动题图（移动客户端）及平板电脑上使用的
                if (strlen($titlePic1Path) > 5) {
                    $siteConfigManageData = new SiteConfigManageData($siteid);
                    $documentNewsTitleMobileWidth = $siteConfigManageData->DocumentNewsTitlePic1WidthForMobile;
                    $documentNewsTitlePadWidth = $siteConfigManageData->DocumentNewsTitlePic1WidthForPad;

                    $toHeight = 0;      //缩略后的高度
                    $creatUploadFileTableId = 0;

                    if ($documentNewsTitleMobileWidth > 0) {
                        $toWidth = $documentNewsTitleMobileWidth;      //缩略后的宽度
                        $thumbFileName = "mobile";
                        $creatUploadFileTableType = 23; //新闻题图,移动终端使用
                        $creatDocumentNewsTitleMobile = self::CreatDocumentNewsTitlePic($titlePic1Path, $toWidth, $toHeight, $thumbFileName, $creatUploadFileTableType, $creatUploadFileMobileId);
                        sleep(1);
                    } else {
                        $creatDocumentNewsTitleMobile = "";
                        $creatUploadFileMobileId = 0;
                    }

                    if ($documentNewsTitlePadWidth > 0) {
                        $toWidth = $documentNewsTitlePadWidth;      //缩略后的宽度
                        $thumbFileName = "pad";
                        $creatUploadFileTableType = 24; //新闻题图,移动终端使用
                        $creatDocumentNewsTitlePad = self::CreatDocumentNewsTitlePic($titlePic1Path, $toWidth, $toHeight, $thumbFileName, $creatUploadFileTableType, $creatUploadFilePadId);
                        sleep(1);
                    } else {
                        $creatDocumentNewsTitlePad = "";
                        $creatUploadFilePadId = 0;
                    }
                } else {
                    $creatDocumentNewsTitleMobile = "";
                    $creatDocumentNewsTitlePad = "";
                    $creatUploadFileMobileId = 0;
                    $creatUploadFilePadId = 0;
                }
                $fileElementName = "titlepic_upload2";
                $filetype = 29; //docnews 题图2
                $commongen = new CommonGen();
                $titlePicPath2 = $commongen->UploadFile($fileElementName, $filetype, 1, $uploadfileid2);
                $titlePicPath2 = str_ireplace("..", "", $titlePicPath2);

                if (!empty($titlePicPath2)) {
                    sleep(1);
                }
                $fileElementName = "titlepic_upload3";
                $filetype = 30; //docnews 题图3
                $commongen = new CommonGen();
                $titlePicPath3 = $commongen->UploadFile($fileElementName, $filetype, 1, $uploadfileid3);
                $titlePicPath3 = str_ireplace("..", "", $titlePicPath3);
                $newid = $documentNewsManageData->Create($titlePic1Path, $titlePicPath2, $titlePicPath3, $creatDocumentNewsTitleMobile, $creatDocumentNewsTitlePad);


//加入操作log
                $operatecontent = "DocumentNews：New id ：" . $newid . "；userid：" . $manageUserId . "；username；" . $manageUserName . "；result：" . $newid . "；title：" . Control::PostRequest("f_documentnewstitle", "");
                $adminuserlogData = new AdminUserLogData();
                $adminuserlogData->Insert($operatecontent);



                if ($newid > 0) {
//新增文档时修改排序号到当前频道的最大排序
                    $documentNewsManageData->UpdateSortWhenAdd($channelId, $newid);
//修改上传文件的tableid;
                    $uploadfiles = Control::PostRequest("f_uploadfiles", "");

                    $uploadfile_arr = split(",", $uploadfiles);

                    $uploadFileData = new UploadFileData();

//修改题图的FILEID
                    $uploadFileData->ModifyTableID($uploadfileid, $newid);

                    for ($i = 0; $i < count($uploadfile_arr); $i++) {
                        if (intval($uploadfile_arr[$i]) > 0) {
                            $uploadFileData->ModifyTableID(intval($uploadfile_arr[$i]), $newid);
                        }
                    }
//修改DocumentNewsTitleMobile 在UploadFile表中的tableid
                    if ($creatUploadFileMobileId > 0) {
                        $uploadFileData->ModifyTableID(intval($creatUploadFileMobileId), $newid);
                    }
//修改DocumentNewsTitlePad 在UploadFile表中的tableid
                    if ($creatUploadFilePadId > 0) {
                        $uploadFileData->ModifyTableID(intval($creatUploadFilePadId), $newid);
                    }

                    //修改题图的FILEID 题图2
                    $uploadFileData->ModifyTableID($uploadfileid2, $newid);

                    //修改题图的FILEID 题图3
                    $uploadFileData->ModifyTableID($uploadfileid3, $newid);

///////////////发布模式处理
                    $_publishType = $channelManageData->GetPublishType($channelId);
                    if ($_publishType > 0) {
                        switch ($_publishType) {
                            case 1: //自动发布新稿
//修改文档状态为终审
                                $state = 14;
                                $documentNewsManageData->UpdateState($newid, $state);
                                $execute_ftp = 1;
                                $pub_channel = 1;
                                $ftpQueueData = new FtpQueueData();
                                self::Publish($newid, $ftpQueueData, $execute_ftp, $pub_channel);
                                break;
                        }
                    }
///////////////////////////
                    Control::ShowMessage(Language::Load('document', 1));
                    $jscode = 'self.parent.loaddocnewslist(1,"","");self.parent.$("#tabs").tabs("select","#tabs-1");';

                    if ($tab_index > 0) {
                        $jscode = $jscode . 'self.parent.$("#tabs").tabs("remove",' . ($tab_index - 1) . ');';
                    }
                    Control::RunJS($jscode);
                } else {
                    Control::ShowMessage(Language::Load('document', 2));
                }
            }
//去掉s开头的标记 {s_xxx_xxx}
            $patterns = "/\{s_(.*?)\}/";
            $tempContent = preg_replace($patterns, "", $tempContent);
//去掉c开头的标记 {c_xxx}
            $patterns = "/\{c_(.*?)\}/";
            $tempContent = preg_replace($patterns, "", $tempContent);
//去掉r开头的标记 {r_xxx_xxx}
            $patterns = "/\{r_(.*?)\}/";
            $tempContent = preg_replace($patterns, "", $tempContent);
        }
        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }

    /**
     * 生成资讯管理修改页面
     */
    private function GenModify() {
        $tempContent = Template::Load("document/document_news_deal.html", "common");
        $documentNewsId = Control::GetRequest("document_news_id", 0);

        $nowManageUserId = Control::GetManageUserId();
        $pageIndex = Control::GetRequest("p", 1);

        $nowManageUserName = Control::GetManageUserName();

        parent::ReplaceFirst($tempContent);
        if ($documentNewsId > 0) {
            $documentNewsManageData = new DocumentNewsManageData();

            //检查编辑锁
            $lockEdit = $documentNewsManageData->GetLockEdit($documentNewsId, false);
            $lockEditDate = $documentNewsManageData->GetLockEditDate($documentNewsId, false);
            $lockEditManageUserId = $documentNewsManageData->GetLockEditManageUserId($documentNewsId, false);

            $dateNowSpan = strtotime(date("Y-m-d H:i:s", time()));
            $lockEditDateSpan = strtotime(date("Y-m-d H:i:s", strtotime($lockEditDate)) . " +5 minute");

            if ($lockEditManageUserId > 0 && $lockEdit > 0 && $lockEditDateSpan > $dateNowSpan && empty($_POST) && $lockEditManageUserId != $nowManageUserId) {
                //当前已经锁定，并且锁定时间在5分钟内
                $manageUserManageData = new ManageUserManageData();
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

                }

                //Control::ShowMessage(Language::Load('document', 26));
                //parent::RefreshTab();
                //$jscode = 'self.parent.loaddocnewslist(1,"","");self.parent.$("#tabs").tabs("select","#tabs-1");';
                //if ($tab_index > 0) {
                //    $jscode = $jscode . 'self.parent.$("#tabs").tabs("remove",' . ($tab_index - 1) . ');';
                //}
                //Control::RunJS($jscode);
                //return "";
            }
            //操作他人的权限

             //操作人不是发布人




                $adminuserData = new AdminUserData(); //组内可操作他人 FOR芙蓉区食安网
                $docAdminUserGroupId = $adminuserData->GetAdminUserGroupID($documentNewsManageUserId);
                $adminUserGroupId = $adminuserData->GetAdminUserGroupID($nowManageUserId);

                if (!$can && $docAdminUserGroupId != $adminUserGroupId) {
                    Control::ShowMessage(Language::Load('document', 26));
                    $jscode = 'self.parent.loaddocnewslist(1,"","");self.parent.$("#tabs").tabs("select","#tabs-1");';
                    if ($tab_index > 0) {
                        $jscode = $jscode . 'self.parent.$("#tabs").tabs("remove",' . ($tab_index - 1) . ');';
                    }
                    Control::RunJS($jscode);
                    return "";
                }

            ////////////////////////////////////////////////////
            $documentchannelname = $channelManageData->GetName($channelId);
            $replace_arr = array(
                "{documentchannelid}" => $channelId,
                "{cid}" => $channelId,
                "{id}" => $documentNewsId,
                "{siteid}" => $siteId,
                "{tab}" => $tab_index,
                "{pageindex}" => $pageIndex
            );
            $tempContent = strtr($tempContent, $replace_arr);

            /////////////////////////////////////////////////
//quick content
            $documentQuickContentData = new DocumentQuickContentData();
            $listName = "documentquickcontent";
            $arrList = $documentQuickContentData->GetList();
            if (count($arrList) > 0) {
                Template::ReplaceList($tempContent, $arrList, $listName);
            } else {
                Template::RemoveCMS($tempContent, $listName);
            }


//sourcecommon
            $sourceCommonData = new SourceCommonData();
            $listName = "sourcecommonlist";
            $arrList = $sourceCommonData->GetList();
            if (count($arrList) > 0) {
                Template::ReplaceList($tempContent, $arrList, $listName);
            } else {
                Template::RemoveCMS($tempContent, $listName);
            }
            $arrlist = $documentNewsManageData->GetOne($documentNewsId);
            Template::ReplaceOne($tempContent, $arrlist, 1);
//去掉s开头的标记 {s_xxx_xxx}
            $patterns = "/\{s_(.*?)\}/";
            $tempContent = preg_replace($patterns, "", $tempContent);
//去掉c开头的标记 {c_xxx}
            $patterns = "/\{c_(.*?)\}/";
            $tempContent = preg_replace($patterns, "", $tempContent);
//去掉r开头的标记 {r_xxx_xxx}
            $patterns = "/\{r_(.*?)\}/";
            $tempContent = preg_replace($patterns, "", $tempContent);
            if (!empty($_POST)) {
//Control::ShowMessage($_POST["c_ishot"]);
//titlepic
                $fileElementName = "titlepic_upload";
                $filetype = 1; //docnews
                $commongen = new CommonGen();
                $titlePicPath = $commongen->UploadFile($fileElementName, $filetype, 1, $uploadfileid);
                $titlePicPath = str_ireplace("..", "", $titlePicPath);
                if (!empty($titlePicPath)) {
                    sleep(1);
                }

//有题图时，再生成两张小图，生成移动题图（移动客户端）及平板电脑上使用的
                if (strlen($titlePicPath) > 5) {
                    $siteConfigData = new SiteConfigData($siteId);
                    $documentNewsTitleMobileWidth = $siteConfigData->DocumentNewsTitleMobileWidth;
                    $documentNewsTitlePadWidth = $siteConfigData->DocumentNewsTitlePadWidth;

                    $toHeight = 0;      //缩略后的高度
                    $creatUploadFileTableId = 0;

                    if ($documentNewsTitleMobileWidth > 0) {
                        $toWidth = $documentNewsTitleMobileWidth;      //缩略后的宽度
                        $creatUploadFileTableType = 23;
                        $thumbFileName = "mobile";
                        $creatDocumentNewsTitleMobile = self::CreatDocumentNewsTitlePic($titlePicPath, $toWidth, $toHeight, $thumbFileName, $creatUploadFileTableType, $creatUploadFileMobileId);
                    } else {
                        $creatDocumentNewsTitleMobile = "";
                        $creatUploadFileMobileId = 0;
                    }
                    if ($documentNewsTitlePadWidth > 0) {
                        $toWidth = $documentNewsTitlePadWidth;      //缩略后的宽度
                        $creatUploadFileTableType = 24;
                        $thumbFileName = "pad";
                        $creatDocumentNewsTitlePad = self::CreatDocumentNewsTitlePic($titlePicPath, $toWidth, $toHeight, $thumbFileName, $creatUploadFileTableType, $creatUploadFilePadId);
                    } else {
                        $creatDocumentNewsTitlePad = "";
                        $creatUploadFilePadId = 0;
                    }
                } else {
                    $creatDocumentNewsTitleMobile = "";
                    $creatDocumentNewsTitlePad = "";
                    $creatUploadFileMobileId = 0;
                    $creatUploadFilePadId = 0;
                }

                $fileElementName = "titlepic_upload2";
                $filetype = 1; //docnews
                $commongen = new CommonGen();
                $titlePicPath2 = $commongen->UploadFile($fileElementName, $filetype, 1, $uploadfileid2);
                $titlePicPath2 = str_ireplace("..", "", $titlePicPath2);
                if (!empty($titlePicPath2)) {
                    sleep(1);
                }
                $fileElementName = "titlepic_upload3";
                $filetype = 1; //docnews
                $commongen = new CommonGen();
                $titlePicPath3 = $commongen->UploadFile($fileElementName, $filetype, 1, $uploadfileid3);
                $titlePicPath3 = str_ireplace("..", "", $titlePicPath3);

                $result = $documentNewsManageData->Modify($documentNewsId, $titlePicPath, $titlePicPath2, $titlePicPath3, $creatDocumentNewsTitleMobile, $creatDocumentNewsTitlePad);

//加入操作log
                $operatecontent = "DocumentNews：Edit id ：" . $documentNewsId . "；userid：" . Control::GetManageUserId() . "；username；" . Control::GetManageUserName() . "；result：" . $result . "；title：" . Control::PostRequest("f_documentnewstitle", "");
                $adminuserlogData = new AdminUserLogData();
                $adminuserlogData->Insert($operatecontent);
                if ($result > 0) {
//编辑完成后，解锁
                    $lockEdit = 0;
                    $documentNewsManageData->ModifyLockEdit($documentNewsId, $lockEdit, $nowManageUserId);

//改变文档状态为 已编
//$state = 1; //已编
//$documentNewsData->UpdateState($documentnewsid, $state);
//修改上传文件的tableid;
                    $uploadfiles = Control::PostRequest("f_uploadfiles", "");
                    $uploadfile_arr = split(",", $uploadfiles);
                    $uploadFileData = new UploadFileData();
//修改题图的FILEID
                    $uploadFileData->ModifyTableID($uploadfileid, $documentNewsId);
                    for ($i = 0; $i < count($uploadfile_arr); $i++) {
                        if (intval($uploadfile_arr[$i]) > 0) {
                            $uploadFileData->ModifyTableID(intval($uploadfile_arr[$i]), $documentNewsId);
                        }
                    }
//修改DocumentNewsTitleMobile 在UploadFile表中的tableid
                    if ($creatUploadFileMobileId > 0) {
                        $uploadFileData->ModifyTableID(intval($creatUploadFileMobileId), $documentNewsId);
                    }
//修改DocumentNewsTitlePad 在UploadFile表中的tableid
                    if ($creatUploadFilePadId > 0) {
                        $uploadFileData->ModifyTableID(intval($creatUploadFilePadId), $documentNewsId);
                    }

///////////////发布模式处理
                    $_publishType = $channelManageData->GetPublishType($channelId);
                    if ($_publishType > 0) {
                        switch ($_publishType) {
                            case 1: //自动发布新稿
//修改文档状态为终审
                                $state = 14;
                                $documentNewsManageData->UpdateState($documentNewsId, $state);
                                $execute_ftp = 1;
                                $pub_channel = 1;
                                $ftpQueueData = new FtpQueueData();
                                self::Publish($documentNewsId, $ftpQueueData, $execute_ftp, $pub_channel);
                                break;
                        }
                    }
//Control::ShowMessage(Language::Load('document', 3));
                    $jscode = 'self.parent.loaddocnewslist(' . $pageIndex . ',"","");self.parent.$("#tabs").tabs("select","#tabs-1");';
                    if ($tab_index > 0) {
                        $jscode = $jscode . 'self.parent.$("#tabs").tabs("remove",' . ($tab_index - 1) . ');';
                    }
                    Control::RunJS($jscode);
                } else {
                    Control::ShowMessage(Language::Load('document', 4));
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
        $documentChannelId = Control::GetRequest("cid", 0);
        if ($documentChannelId <= 0) {
            return "";
        }
        $adminUserId = Control::GetManageUserId();
        $documentChannelManageData = new DocumentChannelManageData();
        $siteId = $documentChannelManageData->GetSiteId($documentChannelId);

        ///////////////判断是否有操作权限///////////////////
        $adminUserPopedomManageData = new AdminUserPopedomManageData();
        $op = "explore";
        $hasPopedom = parent::CheckAdminUserPopedom($adminUserPopedomManageData, $adminUserId, $documentChannelId, $siteId, $op);
        if (!$hasPopedom) {
            Control::ShowMessage(Language::Load('document', 26));
            return "";
        }
        $adminUserName = Control::GetManageUserName();
        $clientIp = Control::GetIp();

        $siteManageData = new SiteManageData();
        $siteUrl = $siteManageData->GetSiteUrl($siteId);
        $_pos = stripos($siteUrl, "http://");
        if ($_pos === false) {
            $siteUrl = "http://" . $siteUrl;
        }
        //Language::Load('document', 7);
        //
        //load template
        $documentChannelType = $documentChannelManageData->GetDocumentChannelType($documentChannelId);
        if ($documentChannelType === 1) {
            $tempContent = Template::Load("document/documentnews_list.html", "common");
        } else {
            $tempContent = Template::Load("document/documentnews_slider_list.html", "common");
        }

        parent::ReplaceFirst($tempContent);

        ////////////////////////////////////////////////////
        ///////////////输出权限到页面///////////////////
        ////////////////////////////////////////////////////
        $canRework = $adminUserPopedomManageData->CanRework($siteId, $documentChannelId, $adminUserId);
        $canAudit1 = $adminUserPopedomManageData->CanAudit1($siteId, $documentChannelId, $adminUserId);
        $canAudit2 = $adminUserPopedomManageData->CanAudit2($siteId, $documentChannelId, $adminUserId);
        $canAudit3 = $adminUserPopedomManageData->CanAudit3($siteId, $documentChannelId, $adminUserId);
        $canAudit4 = $adminUserPopedomManageData->CanAudit4($siteId, $documentChannelId, $adminUserId);
        $canRefused = $adminUserPopedomManageData->CanRefused($siteId, $documentChannelId, $adminUserId);
        $canPublish = $adminUserPopedomManageData->CanPublish($siteId, $documentChannelId, $adminUserId);
        $canModify = $adminUserPopedomManageData->CanModify($siteId, $documentChannelId, $adminUserId);
        $canCreate = $adminUserPopedomManageData->CanCreate($siteId, $documentChannelId, $adminUserId);


        $tempContent = str_ireplace("{CanRework}", $canRework == 1 ? "" : "display:none", $tempContent);
        $tempContent = str_ireplace("{CanAudit1}", $canAudit1 == 1 ? "" : "display:none", $tempContent);
        $tempContent = str_ireplace("{CanAudit2}", $canAudit2 == 1 ? "" : "display:none", $tempContent);
        $tempContent = str_ireplace("{CanAudit3}", $canAudit3 == 1 ? "" : "display:none", $tempContent);
        $tempContent = str_ireplace("{CanAudit4}", $canAudit4 == 1 ? "" : "display:none", $tempContent);
        $tempContent = str_ireplace("{CanRefused}", $canRefused == 1 ? "" : "display:none", $tempContent);
        $tempContent = str_ireplace("{CanPublish}", $canPublish == 1 ? "" : "display:none", $tempContent);
        $tempContent = str_ireplace("{CanModify}", $canModify == 1 ? "" : "display:none", $tempContent);
        $tempContent = str_ireplace("{CanCreate}", $canCreate == 1 ? "" : "display:none", $tempContent);

        //$type = Control::GetRequest("type", "");
        $pageSize = Control::GetRequest("ps", 20);
        if ($documentChannelType !== 1) {
            $pageSize = 16;
        }
        $pageIndex = Control::GetRequest("p", 1);
        $searchKey = Control::GetRequest("searchkey", "");
        $searchKey = urldecode($searchKey);
        $searchTypeBox = Control::GetRequest("searchtype_box", "");
        if (isset($searchKey) && strlen($searchKey) > 0) {
            $can = $adminUserPopedomManageData->CanSearch($siteId, $documentChannelId, $adminUserId);
            if (!$can) {
                Control::ShowMessage(Language::Load('document', 26));
                return "";
            }
        }
        if ($pageIndex > 0 && $documentChannelId > 0) {
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $listName = "document_news_list";
            $allCount = 0;
            $isSelf = Control::GetRequest("isself", 0);
            $documentNewsManageData = new DocumentNewsManageData();
            $arrDocumentNewsList = $documentNewsManageData->GetListForManage($documentChannelId, $pageBegin, $pageSize, $allCount, $searchKey, $searchTypeBox, $isSelf, $adminUserId);

            if (count($arrDocumentNewsList) > 0) {
                Template::ReplaceList($tempContent, $arrDocumentNewsList, $listName);

                $styleNumber = 1;
                $pagerTemplate = Template::Load("../common/pager_style$styleNumber.html");
                $isJs = FALSE;
                $navUrl = "default.php?secu=manage&mod=documentnews&m=listformanage&cid=$documentChannelId&p={0}&ps=$pageSize&isself=$isSelf";
                $jsFunctionName = "";
                $jsParamList = "";
                $pagerButton = Pager::ShowPageButton($pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex, $styleNumber, $isJs, $jsFunctionName, $jsParamList);

                $tempContent = str_ireplace("{documentchannelid}", $documentChannelId, $tempContent);
                $tempContent = str_ireplace("{cid}", $documentChannelId, $tempContent);
                $tempContent = str_ireplace("{pagerbutton}", $pagerButton, $tempContent);
                $tempContent = str_ireplace("{siteurl}", $siteUrl, $tempContent);
            } else {
                Template::RemoveCMS($tempContent, $listName);
                $tempContent = str_ireplace("{pagerbutton}", Language::Load("document", 7), $tempContent);
            }
        }

        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }

    /**
     * 更新文档的排序（前台拖动时使用）
     */
    private function AsyncModifySort() {
        $arrDocumentNewsId = Control::GetRequest("docnewssort", null);
        $documentNewsManageData = new DocumentNewsManageData();
        $documentNewsManageData->ModifySort($arrDocumentNewsId);
    }

    /**
     * 修改文档状态  0 新稿 1 已编 2  返工 11 一审 12 二审 13 三审 14 终审 20 已否   
     */
    private function AsyncModifyState() {
        $documentNewsId = Control::GetRequest("documentnewsid", 0);
        $state = Control::GetRequest("state", -1);
        if ($documentNewsId > 0 && $state >= 0) {
            $documentNewsManageData = new DocumentNewsManageData();
            $documentChannelId = $documentNewsManageData->GetDocumentChannelID($documentNewsId);
            $adminUserId = Control::GetManageUserId();
            $documentChannelManageData = new DocumentChannelManageData();
            $siteId = $documentChannelManageData->GetSiteId($documentChannelId);
////////////////////////////////////////////////////
///////////////判断是否有操作权限///////////////////
////////////////////////////////////////////////////
            $adminUserPopedomManageData = new AdminUserPopedomManageData();
            $can = TRUE;
            switch ($state) {
                case 2 :
                    $can = $adminUserPopedomManageData->CanRework($siteId, $documentChannelId, $adminUserId);
                    break;
                case 11 :
                    $can = $adminUserPopedomManageData->CanAudit1($siteId, $documentChannelId, $adminUserId);
                    break;
                case 12 :
                    $can = $adminUserPopedomManageData->CanAudit2($siteId, $documentChannelId, $adminUserId);
                    break;
                case 13 :
                    $can = $adminUserPopedomManageData->CanAudit3($siteId, $documentChannelId, $adminUserId);
                    break;
                case 14 :
                    $can = $adminUserPopedomManageData->CanAudit4($siteId, $documentChannelId, $adminUserId);
                    break;
                case 20 :
                    $can = $adminUserPopedomManageData->CanRefused($siteId, $documentChannelId, $adminUserId);
                    break;
            }
            if (!$can) {
                return -2;
            }
            //操作他人的权限
            $documentNewsAdminUserId = $documentNewsManageData->GetAdminUserId($documentNewsId);
            if ($documentNewsAdminUserId !== $adminUserId) { //操作人不是发布人
                $can = $adminUserPopedomManageData->CanDoOthers($siteId, $documentChannelId, $adminUserId);

                if (!$can) { //不能操作他人文档时，查询是否有可以操作同组人权限
                    $adminUserManageData = new AdminUserManageData(); //组内可操作他人 FOR芙蓉区食安网
                    $documentNewsAdminUserGroupId = $adminUserManageData->GetAdminUserGroupId($documentNewsAdminUserId);
                    $adminUserGroupId = $adminUserManageData->GetAdminUserGroupID($adminUserId);

                    if ($documentNewsAdminUserGroupId == $adminUserGroupId) { //是同一组时才进行判断
                        $can = $adminUserPopedomManageData->CanDoSameGroupOthers($siteId, $documentChannelId, $adminUserId);
                    }
                }

                if (!$can) {
                    return -2;
                }
            }
////////////////////////////////////////////////////
////////////////////////////////////////////////////
////////////////////////////////////////////////////
////////////////////////////////////////////////////
            $oldState = $documentNewsManageData->GetState($documentNewsId);
            if (($oldState === 30 || $oldState === 20) && intval($state) === 20) { //从发布状态改为已否状态，从FTP上删除文件及相关附件，重新发布相关频道
                //第1步，从FTP删除文档
                $publishDate = $documentNewsManageData->GetPublishDate($documentNewsId);
                $documentNewsContent = $documentNewsManageData->GetDocumentNewsContent($documentNewsId);
                $datePath = Format::DateStringToSimple($publishDate);
                $publishFileName = $documentNewsId . '.html';
                $rank = $documentChannelManageData->GetRank($documentChannelId);
                $publishPath = parent::GetPublishPath($documentChannelId, $rank);
                $hasftp = $documentChannelManageData->GetHasFtp($documentChannelId);
                $ftptype = 0; //HTML和相关CSS,IMAGE
                $despath = $publishPath . $datePath . DIRECTORY_SEPARATOR . $publishFileName;

                $isDel = parent::DelFtp($despath, $documentChannelId, $hasftp, $ftptype);

//有详细页面分页的，循环删除各个分页页面
                $arrnewscontent = explode("<!-- pagebreak -->", $documentNewsContent);
                if (count($arrnewscontent) > 0) { //有分页的内容
                    for ($cp = 0; $cp < count($arrnewscontent); $cp++) {
                        $publishFileName = $documentNewsId . '_' . ($cp + 1) . '.html';
                        $despath = "/" . $publishPath . $datePath . '/' . $publishFileName;
                        parent::DelFtp($despath, $documentChannelId, $hasftp, $ftptype);
                    }
                }
//第2步，从FTP删除上传文件
                $ftptype = 0; //HTML和相关CSS,IMAGE
                $uploadFileData = new UploadFileData();
                $tabletype = 1; //docnews
                $arrfiles = $uploadFileData->GetList($documentNewsId, $tabletype);
//取得相关的附件文件
                if (count($arrfiles) > 0) {
                    for ($i = 0; $i < count($arrfiles); $i++) {
                        $uploadFileName = $arrfiles[$i]["UploadFileName"];
                        $uploadFilePath = $arrfiles[$i]["UploadFilePath"];
                        parent::DelFtp($uploadFilePath . $uploadFileName, $documentChannelId, $hasftp, $ftptype);
                    }
                }
//联动发布所在频道和上级频道
                $documentChannelGen = new DocumentChannelGen();
                $documentChannelGen->PublishMuti($documentChannelId);
/////////////////////////////////////////////////////////////
////////////////xunsearch全文检索引擎 索引更新///////////////
/////////////////////////////////////////////////////////////
//                global $xunfile;
//                if (file_exists($xunfile)) {
//                    require_once $xunfile;
//                    try {
//                        $xs = new XS('icms');
//                        $index = $xs->index;
//                        $index->del($documentnewsid);
//                        $index->flush();
//                    } catch (XSException $e) {
//                        $error = strval($e);
//                    }
//                }
/////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////
            }
//修改状态
            $result = $documentNewsManageData->ModifyState($documentNewsId, $state);
//加入操作log
            $operatecontent = "DocumentNews：UpdateState id ：" . $documentNewsId . "；userid：" . Control::GetManageUserId() . "；username；" . Control::GetManageUserName() . "；oldstate：" . $oldState . "；tostate：" . $state . "；result：" . $result;
            $adminuserlogData = new AdminUserLogData();
            $adminuserlogData->Insert($operatecontent);
            return $result;
        } else {
            return -1;
        }
    }

}

?>
