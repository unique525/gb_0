<?php

/**
 * 后台管理 频道 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Channel
 * @author zhangchi
 */
class ChannelManageGen extends BaseManageGen implements IBaseManageGen {

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
            case "delete":
                $result = self::GenDelete();
                break;
            case "publish":
                $result = self::Publish();
                break;
            case "list_for_manage_left":
                $result = self::GenListForManageLeft();
                break;
            case "property":
                $result = self::GenProperty();
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
            $tempContent = Template::Load("channel/channel_deal.html", "common");
            parent::ReplaceFirst($tempContent);
            $channelManageData = new ChannelManageData();
            $parentName = $channelManageData->GetChannelName($parentId, false);
            $siteId = $channelManageData->GetSiteId($parentId, false);
            $rank = $channelManageData->GetRank($parentId, false);
            if ($rank < 0) {
                $rank = 0;
            }
            $rank++;
            if (!empty($_POST)) {

                $httpPostData = $_POST;
                $publishPath = Control::PostRequest("f_PublishPath", "");
                $hasRepeatPublishPath = false;
                if (strlen($publishPath) > 0) {
                    //判断PublishPath是否已经有重复的了
                    $channelId = 0;
                    $hasRepeatPublishPath = $channelManageData->CheckRepeatPublishPath($siteId, $publishPath, $channelId);
                    if ($hasRepeatPublishPath) {
                        $resultJavaScript = Control::GetJqueryMessage(Language::Load('channel', 2)); //同一站点内已经有重复的发布文件夹了，请修改发布文件夹！
                        //Control::ShowMessage(Language::Load('document', 9));
                    }
                }

                if (!$hasRepeatPublishPath) {

                    $channelId = $channelManageData->Create($httpPostData);
                    //加入操作日志
                    $operateContent = 'Create Channel,POST FORM:'.implode('|',$_POST).';\r\nResult:channelId:'.$channelId;
                    self::CreateManageUserLog($operateContent);

                    if ($channelId > 0) {
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

                            if (intval($titlePic1Result) <=0){
                                //上传出错或没有选择文件上传
                            }else{

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

                            }

                            if($uploadFileId1>0 || $uploadFileId2>0 || $uploadFileId3>0){
                                $channelManageData->ModifyTitlePic($channelId, $uploadFileId1, $uploadFileId2, $uploadFileId3);
                            }

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

                        $channelManageData->UpdateParentChildrenChannelId($channelId);


                        //授权给创建人
                        if ($manageUserId > 1) { //只有非ADMIN的要授权
                            $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
                            $manageUserAuthorityManageData->CreateForChannel($siteId, $channelId, $manageUserId);
                        }

                        //删除缓冲
                        DataCache::RemoveDir(CACHE_PATH . '/channel_data');

                        //javascript 处理
                        //重新加载左边导航树
                        $javascriptLoadChannel = "parent._LoadChannelListForManage(parent.G_NowSiteId);";
                        Control::RunJavascript($javascriptLoadChannel);

                        $closeTab = Control::PostRequest("CloseTab",0);
                        if($closeTab == 1){
                            //Control::CloseTab();
                            $resultJavaScript .= Control::GetCloseTab();
                        }else{
                            Control::GoUrl($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']);
                        }
                    } else {
                        $resultJavaScript .= Control::GetJqueryMessage(Language::Load('channel', 3)); //新增失败！
                        //Control::ShowMessage(Language::Load('document', 2));
                    }
                }
            }

            $tempContent = str_ireplace("{CreateDate}", strval(date('Y-m-d', time())), $tempContent);
            $tempContent = str_ireplace("{ParentName}", $parentName, $tempContent);
            $tempContent = str_ireplace("{ParentId}", strval($parentId), $tempContent);
            $tempContent = str_ireplace("{SiteId}", strval($siteId), $tempContent);
            $tempContent = str_ireplace("{Rank}", strval($rank), $tempContent);

            $fieldsOfChannel = $channelManageData->GetFields();
            parent::ReplaceWhenCreate($tempContent, $fieldsOfChannel);

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

        return $tempContent;
    }

    private function GenProperty(){
        $tempContent = Template::Load("channel/channel_property.html", "common");
        $channelId = Control::GetRequest("channel_id", 0);
        $tempContent = str_ireplace("{ChannelId}",$channelId, $tempContent);

        return $tempContent;
    }

    /**
     * 物理删除
     * @return string 模板内容页面
     */
    private function GenDelete(){
        $tempContent = "";

        return $tempContent;
    }

    /**
     * 发布频道
     * @return int 返回发布结果
     */
    private function Publish(){
        $result = -1;
        $channelId = Control::GetRequest("channel_id", -1);
        if($channelId>0){
            $publishQueueManageData = new PublishQueueManageData();
            $result = parent::PublishChannel($channelId, $publishQueueManageData);
            if($result == BaseManageGen::PUBLISH_CHANNEL_RESULT_FINISHED){
                for ($i = 0;$i< count($publishQueueManageData->Queue); $i++) {
                    $publishQueueManageData->Queue[$i]["Content"] = "";
                }
                print_r($publishQueueManageData->Queue);
            }
        }
        return $result;
    }

    /**
     * 频道树，后台主界面用
     * @return string 返回zTree的JSON数据结构
     */
    private function GenListForManageLeft() {
        $siteId = Control::GetRequest("site_id", 0);
        $adminUserId = Control::GetManageUserId();
        if ($siteId > 0 && $adminUserId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'channel_data';
            $cacheFile = 'channel_for_manage_left.cache_' . $siteId . '_' . $adminUserId . '.php';
            if (strlen(DataCache::Get($cacheDir . DIRECTORY_SEPARATOR . $cacheFile)) <= 0) {
                $channelManageData = new ChannelManageData();
                $arrList = $channelManageData->GetListForManageLeft($siteId, $adminUserId);
                $sb = '[';
                for ($i = 0; $i < count($arrList); $i++) {
                    $channelName = Format::FormatQuote($arrList[$i]['ChannelName']);
                    $channelId = $arrList[$i]['ChannelId'];
                    $channelType = intval($arrList[$i]['ChannelType']);
                    $rank = intval($arrList[$i]['Rank']);
                    $parentId = intval($arrList[$i]['ParentId']);
                    $childCounts = intval($arrList[$i]['ChildCount']);
                    $isParent = "";
                    if ($childCounts > 0) {
                        $isParent = ", isParent:true";
                    }

                    $iconSkin = ',iconSkin:"diy' . $channelType . '"';

                    if ($rank == 0) {
                        $sb = $sb . '{ id:' . $channelId . ', pId:' . $parentId . ', name:"' . $channelName . '", "channelType":"' . $channelType . '", open:true ' . $isParent . ' ' . $iconSkin . '}';
                    } else {
                        $sb = $sb . '{ id:' . $channelId . ', pId:' . $parentId . ', name:"' . $channelName . '", "channelType":"' . $channelType . '" ' . $isParent . ' ' . $iconSkin . '}';
                    }

                    if ($i < count($arrList) - 1) {
                        $sb = $sb . ',';
                    }
                }
                $sb = $sb . ']';
                $result = '' . $sb . '';

                DataCache::Set($cacheDir, $cacheFile, $result);
            } else {
                $result = DataCache::Get($cacheDir . DIRECTORY_SEPARATOR . $cacheFile);
            }
            return $result;
        }else{
            return null;
        }
    }


}

?>
