<?php

/**
 * 后台管理 图片轮换 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_PicSlider
 * @author zhangchi
 */
class PicSliderManageGen extends BaseManageGen implements IBaseManageGen {
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
        }

        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    /**
     * 新增
     * @return string 模板内容页面
     */
    private function GenCreate() {
        $tempContent = "";
        $parentId = Control::GetRequest("parent_id", 0);
        $resultJavaScript = "";
        $manageUserId = Control::GetManageUserId();

        if ($parentId >0 && $manageUserId > 0) {
            $tempContent = Template::Load("pic_slider/pic_slider_deal.html", "common");
            parent::ReplaceFirst($tempContent);
            $picSliderManageData = new PicSliderManageData();


            if (!empty($_POST)) {

                $httpPostData = $_POST;



                    $picSliderId = $picSliderManageData->Create($httpPostData);
                    //加入操作日志
                    $operateContent = 'Create PicSlider,POST FORM:'.implode('|',$_POST).';\r\nResult:PicSliderId:'.$picSliderId;
                    self::CreateManageUserLog($operateContent);

                    if ($picSliderId > 0) {
                        if( !empty($_FILES)){
                            //title pic1
                            $fileElementName = "file_upload_file_1";
                            $tableType = UploadFileData::UPLOAD_TABLE_TYPE_PIC_SLIDER;
                            $tableId = $picSliderId;
                            $uploadFile = new UploadFile();
                            $uploadFileId = 0;
                            $titlePic1Result = self::Upload(
                                $fileElementName,
                                $tableType,
                                $tableId,
                                $uploadFile,
                                $uploadFileId
                            );

                            if (intval($titlePic1Result) <=0){
                                //上传出错或没有选择文件上传
                            }else{

                            }

                            if($uploadFileId>0){
                                $picSliderManageData->ModifyUploadFileId($picSliderId, $uploadFileId);
                            }

                        }

                        //删除缓冲
                        DataCache::RemoveDir(CACHE_PATH . '/pic_slider_data');

                        //javascript 处理

                        $closeTab = Control::PostRequest("CloseTab",0);
                        if($closeTab == 1){
                            //Control::CloseTab();
                            $resultJavaScript .= Control::GetCloseTab();
                        }else{
                            Control::GoUrl($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']);
                        }
                    } else {
                        $resultJavaScript .= Control::GetJqueryMessage(Language::Load('pic_slider', 2)); //新增失败！
                    }

            }

            $fields = $picSliderManageData->GetFields();
            parent::ReplaceWhenCreate($tempContent, $fields);

            $patterns = '/\{s_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);

            parent::ReplaceEnd($tempContent);

            $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);

        }


        return $tempContent;
    }

    /**
     * 编辑
     * @return string 模板内容页面
     */
    private function GenModify() {
        $tempContent = Template::Load("channel/channel_deal.html", "common");
        $channelId = Control::GetRequest("channel_id", 0);
        $resultJavaScript = "";
        $manageUserId = Control::GetManageUserId();

        if ($channelId >0 && $manageUserId > 0) {

            parent::ReplaceFirst($tempContent);
            $channelManageData = new ChannelManageData();

            $channelManageData->UpdateParentChildrenChannelId($channelId);

            $parentChannelName = $channelManageData->GetParentChannelName($channelId, false);

            $tempContent = str_ireplace("{ParentName}", $parentChannelName, $tempContent);
            $tempContent = str_ireplace("{ChannelId}", $channelId, $tempContent);

            $siteId = $channelManageData->GetSiteId($channelId, false);
            $tempContent = str_ireplace("{SiteId}", strval($siteId), $tempContent);


            //加载原有数据
            $arrOne = $channelManageData->GetOne($channelId);
            Template::ReplaceOne($tempContent, $arrOne);


            if (!empty($_POST)) {
                $httpPostData = $_POST;
                $publishPath = Control::PostRequest("f_PublishPath", "");
                $hasRepeatPublishPath = false;
                if (strlen($publishPath) > 0) {
                    //判断PublishPath是否已经有重复的了
                    $result = 0;
                    $hasRepeatPublishPath = $channelManageData->CheckRepeatPublishPath($siteId, $publishPath, $result);
                    if ($hasRepeatPublishPath) {
                        Control::ShowMessage(Language::Load('channel', 2)); //同一站点内已经有重复的发布文件夹了，请修改发布文件夹！
                        return "";
                    }
                }
                if (!$hasRepeatPublishPath) {
                    $result = $channelManageData->Modify($httpPostData, $channelId);
                    //加入操作日志
                    $operateContent = 'Modify Channel,POST FORM:'.implode('|',$_POST).';\r\nResult:result:'.$result;
                    self::CreateManageUserLog($operateContent);

                    if ($result > 0) {
                        //题图操作
                        if( !empty($_FILES)){
                            //title pic1
                            $fileElementName = "file_title_pic_1";
                            $tableType = UploadFileData::UPLOAD_TABLE_TYPE_CHANNEL_1; //channel
                            $tableId = $channelId;
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
                                $oldUploadFileId1 = $channelManageData->GetTitlePic1UploadFileId($channelId, false);
                                parent::DeleteUploadFile($oldUploadFileId1);

                                //修改题图
                                $channelManageData->ModifyTitlePic1UploadFileId($channelId, $uploadFileId1);
                            }

                            //title pic2
                            $fileElementName = "file_title_pic_2";
                            $tableType = UploadFileData::UPLOAD_TABLE_TYPE_CHANNEL_2;
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
                                $oldUploadFileId2 = $channelManageData->GetTitlePic2UploadFileId($channelId, false);
                                parent::DeleteUploadFile($oldUploadFileId2);

                                //修改题图
                                $channelManageData->ModifyTitlePic2UploadFileId($channelId, $uploadFileId2);
                            }
                            //title pic3
                            $fileElementName = "file_title_pic_3";

                            $tableType = UploadFileData::UPLOAD_TABLE_TYPE_CHANNEL_3;
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
                                $oldUploadFileId3 = $channelManageData->GetTitlePic3UploadFileId($channelId, false);
                                parent::DeleteUploadFile($oldUploadFileId3);

                                //修改题图
                                $channelManageData->ModifyTitlePic3UploadFileId($channelId, $uploadFileId3);
                            }

                            //重新制作题图1的相关图片
                            $siteConfigData = new SiteConfigData($siteId);
                            if($uploadFileId1>0){
                                $channelTitlePic1MobileWidth = $siteConfigData->ChannelTitlePic1MobileWidth;
                                if($channelTitlePic1MobileWidth<=0){
                                    $channelTitlePic1MobileWidth  = 320; //默认320宽
                                }
                                self::GenUploadFileMobile($uploadFileId1,$channelTitlePic1MobileWidth);

                                $channelTitlePic1PadWidth = $siteConfigData->ChannelTitlePic1PadWidth;
                                if($channelTitlePic1PadWidth<=0){
                                    $channelTitlePic1PadWidth  = 1024; //默认1024宽
                                }
                                self::GenUploadFilePad($uploadFileId1,$channelTitlePic1PadWidth);
                            }
                        }

                        //删除缓冲
                        DataCache::RemoveDir(CACHE_PATH . '/channel_data');

                        //javascript 处理
                        //重新加载左边导航树
                        $javascriptLoadChannel = "parent._LoadChannelListForManage(parent.G_NowSiteId);";
                        Control::RunJavascript($javascriptLoadChannel);

                        $closeTab = Control::PostRequest("CloseTab",0);
                        if($closeTab == 1){
                            $resultJavaScript .= Control::GetCloseTab();
                        }else{
                            Control::GoUrl($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']);
                        }

                    } else {
                        $resultJavaScript .= Control::GetJqueryMessage(Language::Load('channel', 6)); //编辑失败！
                    }
                }
            }

            $patterns = '/\{s_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
        }
        parent::ReplaceEnd($tempContent);
        $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
        return $tempContent;
    }

    /**
     * 删除到回收站
     * @return string 模板内容页面
     */
    private function GenRemoveToBin(){
        $tempContent = Template::Load("channel/channel_remove_to_bin.html", "common");
        $channelId = Control::GetRequest("channel_id", 0);
        $channelManageData = new ChannelManageData();
        $channelManageData->UpdateParentChildrenChannelId($channelId);

        return $tempContent;
    }


    /**
     * 生成列表页面
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
        $tempContent = Template::Load("pic_slider/pic_slider_list.html", "common");


        parent::ReplaceFirst($tempContent);


        $pageSize = Control::GetRequest("ps", 20);
        $pageIndex = Control::GetRequest("p", 1);
        $searchKey = Control::GetRequest("search_key", "");
        $searchType = Control::GetRequest("search_type", -1);
        $searchKey = urldecode($searchKey);

        if (isset($searchKey) && strlen($searchKey) > 0) {
            $canSearch = $manageUserAuthorityManageData->CanSearch($siteId, $channelId, $manageUserId);
            if (!$canSearch) {
                die(Language::Load('channel', 4));
            }
        }

        if ($pageIndex > 0 && $channelId > 0) {

            $tempContent = str_ireplace("{ChannelId}", $channelId, $tempContent);

            $pageBegin = ($pageIndex - 1) * $pageSize;
            $tagId = "pic_slider_list";
            $allCount = 0;
            $isSelf = Control::GetRequest("is_self", 0);
            $picSliderManageData = new PicSliderManageData();
            $arrPicSliderList = $picSliderManageData->GetList(
                $channelId,
                $pageBegin,
                $pageSize,
                $allCount,
                $searchKey,
                $searchType,
                $isSelf,
                $manageUserId
            );
            if (count($arrPicSliderList) > 0) {
                Template::ReplaceList($tempContent, $arrPicSliderList, $tagId);

                $styleNumber = 1;
                $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html", "common");
                $isJs = FALSE;
                $navUrl = "default.php?secu=manage&mod=pic_slider&m=list&channel_id=$channelId&p={0}
                            &ps=$pageSize&isself=$isSelf";
                $jsFunctionName = "";
                $jsParamList = "";
                $pagerButton = Pager::ShowPageButton($pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex, $styleNumber, $isJs, $jsFunctionName, $jsParamList);

                $tempContent = str_ireplace("{pager_button}", $pagerButton, $tempContent);

            } else {
                Template::RemoveCustomTag($tempContent, $tagId);
                $tempContent = str_ireplace("{pager_button}", Language::Load("pic_slider", 4), $tempContent);
            }
        }

        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }
} 