<?php

/**
 * 前台Gen频道生成类
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
            case "publish":
                $result = self::Publish();
                break;
            case "list_for_manage_left":
                $result = self::GenListForManageLeft();
                break;
        }

        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    /**
     * 新增频道
     * @return string 模板内容页面
     */
    private function GenCreate() {
        $tempContent = "";
        $parentId = Control::GetRequest("parent_id", 0);

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
                        Control::ShowMessage(Language::Load('document', 9));
                        return "";
                    }
                }
                if (!$hasRepeatPublishPath) {
                    //title pic1
                    $fileElementName = "file_title_pic_1";
                    $tableType = 20; //channel
                    $tableId = 0;
                    $returnType = 1;
                    $uploadFileId1 = 0;
                    $titlePicPath1 = $this->Upload($fileElementName, $tableType, $tableId, $returnType, $uploadFileId1);
                    $titlePicPath1 = str_ireplace("..", "", $titlePicPath1);
                    if (!empty($titlePicPath1)) {
                        sleep(1);
                    }
                    //title pic2
                    $fileElementName = "file_title_pic_2";
                    $uploadFileId2 = 0;
                    $titlePicPath2 = $this->Upload($fileElementName, $tableType, $tableId, $returnType, $uploadFileId2);
                    $titlePicPath2 = str_ireplace("..", "", $titlePicPath2);
                    if (!empty($titlePicPath2)) {
                        sleep(1);
                    }
                    //title pic3
                    $fileElementName = "file_title_pic_3";

                    $uploadFileId3 = 0;
                    $titlePicPath3 = $this->Upload($fileElementName, $tableType, $tableId, $returnType, $uploadFileId3);
                    $titlePicPath3 = str_ireplace("..", "", $titlePicPath3);

                    $channelId = $channelManageData->Create($httpPostData, $titlePicPath1, $titlePicPath2, $titlePicPath3);
                    //加入操作日志
                    $operateContent = 'Create Channel,POST FORM:'.implode('|',$_POST).';\r\nResult:channelId:'.$channelId;
                    self::CreateManageUserLog($operateContent);

                    if ($channelId > 0) {

                        /**
                        //活动类的默认添加Class分类====Ljy
                        $channelType = Control::PostRequest("f_channeltype", 1);
                        if ($channelType == 6) {
                            $activityClassName = "默认";
                            $state = 0;
                            $activityType = 0;     //0为线下活动
                            $activityClsaaData = new ActivityClassData();
                            $activityClsaaData->CreateInt($siteId, $channelId, $activityClassName, $state, $activityType);
                        }
                        */
                        //授权给创建人
                        if ($manageUserId > 1) { //只有非ADMIN的要授权
                            $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
                            $manageUserAuthorityManageData->CreateForChannel($siteId, $channelId, $manageUserId);
                        }

                        //删除缓冲
                        DataCache::RemoveDir(CACHE_PATH . '/channel_data');

                        $uploadFileManageData = new UploadFileManageData();
                        //修改题图1的TableId
                        $uploadFileManageData->ModifyTableId($uploadFileId1, $channelId);
                        //修改题图2的TableId
                        $uploadFileManageData->ModifyTableId($uploadFileId2, $channelId);
                        //修改题图3的TableId
                        $uploadFileManageData->ModifyTableId($uploadFileId3, $channelId);

                        //javascript 处理
                        //重新加载左边导航树
                        $javascriptLoadChannel = "parent.LoadChannelListForManage(parent.G_NowSiteId);";
                        Control::RunJavascript($javascriptLoadChannel);

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
        }

        return $tempContent;
    }

    /**
     * 编辑频道
     * @return string 模板内容页面
     */
    private function GenModify() {
        $tempContent = Template::Load("channel/channel_deal.html", "common");
        $channelId = Control::GetRequest("channel_id", 0);

        $manageUserId = Control::GetManageUserId();

        if ($channelId >0 && $manageUserId > 0) {

            parent::ReplaceFirst($tempContent);
            $channelManageData = new ChannelManageData();

            $parentChannelName = $channelManageData->GetParentChannelName($channelId, false);

            $tempContent = str_ireplace("{ParentName}", $parentChannelName, $tempContent);
            $tempContent = str_ireplace("{ChannelId}", $channelId, $tempContent);

            $siteId = $channelManageData->GetSiteId($channelId, false);
            $tempContent = str_ireplace("{SiteId}", strval($siteId), $tempContent);


            if (!empty($_POST)) {
                $httpPostData = $_POST;
                $publishPath = Control::PostRequest("f_PublishPath", "");
                $hasRepeatPublishPath = false;
                if (strlen($publishPath) > 0) {
                    //判断PublishPath是否已经有重复的了
                    $result = 0;
                    $hasRepeatPublishPath = $channelManageData->CheckRepeatPublishPath($siteId, $publishPath, $result);
                    if ($hasRepeatPublishPath) {
                        Control::ShowMessage(Language::Load('document', 9));
                        return "";
                    }
                }
                if (!$hasRepeatPublishPath) {
                    //title pic1
                    $fileElementName = "file_title_pic_1";
                    $tableType = 20; //channel
                    $tableId = 0;
                    $returnType = 1;
                    $uploadFileId1 = 0;
                    $titlePicPath1 = $this->Upload($fileElementName, $tableType, $tableId, $returnType, $uploadFileId1);
                    $titlePicPath1 = str_ireplace("..", "", $titlePicPath1);
                    if (!empty($titlePicPath1)) {
                        sleep(1);
                    }
                    //title pic2
                    $fileElementName = "file_title_pic_2";
                    $uploadFileId2 = 0;
                    $titlePicPath2 = $this->Upload($fileElementName, $tableType, $tableId, $returnType, $uploadFileId2);
                    $titlePicPath2 = str_ireplace("..", "", $titlePicPath2);
                    if (!empty($titlePicPath2)) {
                        sleep(1);
                    }
                    //title pic3
                    $fileElementName = "file_title_pic_3";

                    $uploadFileId3 = 0;
                    $titlePicPath3 = $this->Upload($fileElementName, $tableType, $tableId, $returnType, $uploadFileId3);
                    $titlePicPath3 = str_ireplace("..", "", $titlePicPath3);

                    $result = $channelManageData->Create($httpPostData, $titlePicPath1, $titlePicPath2, $titlePicPath3);
                    //加入操作日志
                    $operateContent = 'Modify Channel,POST FORM:'.implode('|',$_POST).';\r\nResult:channelId:'.$result;
                    self::CreateManageUserLog($operateContent);

                    if ($result > 0) {
                        //授权给创建人
                        if ($manageUserId > 1) { //只有非ADMIN的要授权
                            $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
                            $manageUserAuthorityManageData->CreateForChannel($siteId, $result, $manageUserId);
                        }

                        //删除缓冲
                        DataCache::RemoveDir(CACHE_PATH . '/channel_data');

                        $uploadFileManageData = new UploadFileManageData();
                        //修改题图1的TableId
                        $uploadFileManageData->ModifyTableId($uploadFileId1, $result);
                        //修改题图2的TableId
                        $uploadFileManageData->ModifyTableId($uploadFileId2, $result);
                        //修改题图3的TableId
                        $uploadFileManageData->ModifyTableId($uploadFileId3, $result);

                        //javascript 处理
                        //重新加载左边导航树
                        $javascriptLoadChannel = "parent.LoadChannelListForManage(parent.G_NowSiteId);";
                        Control::RunJavascript($javascriptLoadChannel);

                        $closeTab = Control::PostRequest("CloseTab",0);
                        if($closeTab == 1){
                            Control::CloseTab();
                        }

                    } else {
                        Control::ShowMessage(Language::Load('document', 2));
                    }
                }
            }


            $fieldsOfChannel = $channelManageData->GetFields();
            parent::ReplaceWhenCreate($tempContent, $fieldsOfChannel);

            $patterns = '/\{s_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
        }
        parent::ReplaceEnd($tempContent);
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
                //print_r($publishQueueManageData->Queue);
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
                $result = 'var zNodes =' . $sb . ';';

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
