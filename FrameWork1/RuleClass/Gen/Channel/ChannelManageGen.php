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
        $tempContent = Template::Load("channel/channel_deal.html", "common");
        $parentId = Control::GetRequest("parent_id", 0);

        $manageUserId = Control::GetManageUserId();

        if ($parentId >0 && $manageUserId > 0) {

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
                print_r($httpPostData);
                die();
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
                    $fileElementName = "title_pic_upload1";
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
                    $fileElementName = "title_pic_upload2";
                    $uploadFileId2 = 0;
                    $titlePicPath2 = $this->Upload($fileElementName, $tableType, $tableId, $returnType, $uploadFileId2);
                    $titlePicPath2 = str_ireplace("..", "", $titlePicPath2);
                    if (!empty($titlePicPath2)) {
                        sleep(1);
                    }
                    //title pic3
                    $fileElementName = "title_pic_upload3";

                    $uploadFileId3 = 0;
                    $titlePicPath3 = $this->Upload($fileElementName, $tableType, $tableId, $returnType, $uploadFileId3);
                    $titlePicPath3 = str_ireplace("..", "", $titlePicPath3);

                    $channelId = $channelManageData->Create($httpPostData, $titlePicPath1, $titlePicPath2, $titlePicPath3);
                    //加入操作日志
                    $operateContent = 'POST FORM:'.implode('|',$_POST).';\r\nResult:channelId:'.$channelId;
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
                        DataCache::RemoveDir(CACHE_PATH . DIRECTORY_SEPARATOR . 'channel_data');


                        $uploadFileManageData = new UploadFileManageData();
                        //修改题图1的TableId
                        $uploadFileManageData->ModifyTableId($uploadFileId1, $channelId);
                        //修改题图2的TableId
                        $uploadFileManageData->ModifyTableId($uploadFileId2, $channelId);
                        //修改题图3的TableId
                        $uploadFileManageData->ModifyTableId($uploadFileId3, $channelId);

                        /**
                        Control::ShowMessage(Language::Load('document', 1));
                        $jsCode = 'self.parent.loadtree(' . $siteId . ');';
                        //$jscode = 'self.parent.loadtree(' . $siteid . ');self.parent.$("#tabs").tabs("select","#tabs-1");';
                        //if ($tab_index > 0) {
                        //    $jscode = $jscode . 'self.parent.$("#tabs").tabs("remove",' . ($tab_index - 1) . ');';
                        //}
                        Control::RunJS($jsCode);
                         */
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

            /*
            $tempContent = str_ireplace("{channel_id}", "0", $tempContent);
            $tempContent = str_ireplace("{parent_name}", $parentName, $tempContent);
            $tempContent = str_ireplace("{parent_id}", $parentId, $tempContent);
            $tempContent = str_ireplace("{site_id}", $siteId, $tempContent);
            $tempContent = str_ireplace("{channel_name}", "", $tempContent);
            $tempContent = str_ireplace("{publish_path}", "", $tempContent);
            $tempContent = str_ireplace("{sort}", "0", $tempContent);
            $tempContent = str_ireplace("{manage_user_id}", strval($manageUserId), $tempContent);
            $tempContent = str_ireplace("{rank}", strval($rank), $tempContent);
            $tempContent = str_ireplace("{id}", "", $tempContent);
            $tempContent = str_ireplace("{browser_title}", "", $tempContent);
            $tempContent = str_ireplace("{browser_keywords}", "", $tempContent);
            $tempContent = str_ireplace("{browser_description}", "", $tempContent);
            $tempContent = str_ireplace("{tab}", strval($tabIndex), $tempContent);
            $tempContent = str_ireplace("{channel_intro}", "", $tempContent);
            $tempContent = str_ireplace("{publish_api_url}", "", $tempContent);
            $tempContent = str_ireplace("{create_date}", strval(date('Ymd', time())), $tempContent);
            */

            $patterns = "/\{s_(.*?)\}/";
            $tempContent = preg_replace($patterns, "", $tempContent);
        }
        parent::ReplaceEnd($tempContent);
        return $tempContent;
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
