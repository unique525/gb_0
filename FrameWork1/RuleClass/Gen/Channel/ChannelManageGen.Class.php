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
            case "removebin":
                $result = self::GenRemoveBin();
                break;
            case "publish":

                $publishedFiles = '';
                $isPubAttach = 1;
                $executeFtp = 1;
                $ftpQueueData = new FtpQueueData();

                $result = self::Publish($ftpQueueData, 0, $publishedFiles, $isPubAttach, $executeFtp);

                break;
            case "publishall":
                $result = self::PublishAll();
                break;
            case "list_for_manage_left":
                $result = self::GenListForManageLeft();
                break;
            case "tree_popedom":
                $result = self::GenTreeForPopedom();
                break;
            case "tree_deal":
                $result = self::GenTreeForDeal();
                break;
            case "move":
                $result = self::GenDeal($method);
                break;
            case "copy":
                $result = self::GenDeal($method);
                break;
            case "link":
                $result = self::GenDeal($method);
                break;
            case "property":
                $result = self::GenProperty();
                break;
            case "repair":
                $documentChannelData = new DocumentChannelData();
                $documentChannelData->Repair();
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
        $parentId = Control::GetRequest("cid", 0);
        $tabIndex = Control::GetRequest("tab", 0);

        $adminUserId = Control::GetAdminUserId();
        parent::ReplaceFirst($tempContent);

        if ($parentId > 0) {
            $documentChannelData = new DocumentChannelData();
            $parentName = $documentChannelData->GetName($parentId);
            $siteId = $documentChannelData->GetSiteID($parentId);
            $rank = $documentChannelData->GetRank($parentId);
            if ($rank < 0) {
                $rank = 0;
            }
            $rank++;
            $tempContent = str_ireplace("{documentchannelintro}", "", $tempContent);

            if (!empty($_POST)) {
                $publishPath = Control::PostRequest("f_publishpath", "");
                $hasPublishPath = 0;
                if (strlen($publishPath) > 0) {
                    //判断publishpath是否已经有重复的了
                    $hasPublishPath = $documentChannelData->HasPublishPath($siteId, 0, $publishPath);
                    if ($hasPublishPath > 0) {
                        Control::ShowMessage(Language::Load('document', 9));
                    }
                }
                if ($hasPublishPath <= 0) {
                    //titlepic1
                    $fileElementName = "titlepic_upload1";
                    $fileType = 20; //docchannel
                    $returnType = 1;
                    $commonGen = new CommonGen();
                    $uploadFileId1 = 0;
                    $titlePicPath1 = $commonGen->UploadFile($fileElementName, $fileType, $returnType, $uploadFileId1);
                    $titlePicPath1 = str_ireplace("..", "", $titlePicPath1);
                    if (!empty($titlePicPath1)) {
                        sleep(1);
                    }
                    //titlepic2
                    $fileElementName = "titlepic_upload2";
                    $fileType = 20; //docchannel
                    $returnType = 1;
                    $commonGen = new CommonGen();

                    $uploadFileId2 = 0;
                    $titlePicPath2 = $commonGen->UploadFile($fileElementName, $fileType, $returnType, $uploadFileId2);
                    $titlePicPath2 = str_ireplace("..", "", $titlePicPath2);
                    if (!empty($titlePicPath2)) {
                        sleep(1);
                    }
                    //titlepic3
                    $fileElementName = "titlepic_upload3";
                    $fileType = 20; //docchannel
                    $returnType = 1;
                    $commonGen = new CommonGen();

                    $uploadFileId3 = 0;
                    $titlePicPath3 = $commonGen->UploadFile($fileElementName, $fileType, $returnType, $uploadFileId3);
                    $titlePicPath3 = str_ireplace("..", "", $titlePicPath3);

                    $result = $documentChannelData->Create($titlePicPath1, $titlePicPath2, $titlePicPath3);
                    //加入操作log
                    $operateContent = "DocumentChannel：news id ：" . $result . "；adminuserid：" . Control::GetAdminUserID() . "；adminusername；" . Control::GetAdminUserName() . "；result：" . $result;
                    $adminUserLogData = new AdminUserLogData();
                    $adminUserLogData->Insert($operateContent);

                    if ($result > 0) {

                        //活动类的默认添加Class分类====Ljy
                        $channelType = Control::PostRequest("f_channeltype", 1);
                        if ($channelType == 6) {
                            $activityClassName = "默认";
                            $state = 0;
                            $activityType = 0;     //0为线下活动
                            $activityClsaaData = new ActivityClassData();
                            $activityClsaaData->CreateInt($siteId, $result, $activityClassName, $state, $activityType);
                        }

                        //授权给创建人
                        $adminUserId = Control::GetAdminUserID();

                        if ($adminUserId > 1) { //只有非ADMIN的要授权
                            $adminPopedomData = new AdminPopedomData();
                            $adminPopedomData->CreateForDocumentChannel($siteId, $result, $adminUserId);
                        }

                        //删除缓冲
                        $cacheDir = 'data' . DIRECTORY_SEPARATOR . 'docdata';
                        DataCache::RemoveDir($cacheDir);


                        $uploadFileData = new UploadFileData();
                        //修改题图1的TableID
                        $uploadFileData->ModifyTableID($uploadFileId1, $result);
                        //修改题图2的TableID
                        $uploadFileData->ModifyTableID($uploadFileId2, $result);
                        //修改题图3的TableID
                        $uploadFileData->ModifyTableID($uploadFileId3, $result);

                        Control::ShowMessage(Language::Load('document', 1));
                        $jsCode = 'self.parent.loadtree(' . $siteId . ');';
                        //$jscode = 'self.parent.loadtree(' . $siteid . ');self.parent.$("#tabs").tabs("select","#tabs-1");';
                        //if ($tab_index > 0) {
                        //    $jscode = $jscode . 'self.parent.$("#tabs").tabs("remove",' . ($tab_index - 1) . ');';
                        //}
                        Control::RunJS($jsCode);
                    } else {
                        Control::ShowMessage(Language::Load('document', 2));
                    }
                }
            }

            $tempContent = str_ireplace("{parentname}", $parentName, $tempContent);
            $tempContent = str_ireplace("{parentid}", $parentId, $tempContent);
            $tempContent = str_ireplace("{siteid}", $siteId, $tempContent);
            $tempContent = str_ireplace("{documentchannelname}", "", $tempContent);
            $tempContent = str_ireplace("{publishpath}", "", $tempContent);
            $tempContent = str_ireplace("{sort}", "0", $tempContent);
            $tempContent = str_ireplace("{adminuserid}", strval($adminUserId), $tempContent);
            $tempContent = str_ireplace("{rank}", strval($rank), $tempContent);
            $tempContent = str_ireplace("{id}", "", $tempContent);
            $tempContent = str_ireplace("{ietitle}", "", $tempContent);
            $tempContent = str_ireplace("{iekeywords}", "", $tempContent);
            $tempContent = str_ireplace("{iedescription}", "", $tempContent);
            $tempContent = str_ireplace("{tab}", strval($tabIndex), $tempContent);
            $tempContent = str_ireplace("{documentchannelintro}", "", $tempContent);
            $tempContent = str_ireplace("{PublishAPIUrl}", "", $tempContent);
            $tempContent = str_ireplace("{createdate}", "", $tempContent);

            //
            $patterns = "/\{s_(.*?)\}/";
            $tempContent = preg_replace($patterns, "", $tempContent);
        }
        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }

    /**
     * 频道树，后台主界面用
     * @return string 返回ztree的JSON数据结构
     */
    private function GenListForManageLeft() {
        $siteId = Control::GetRequest("sid", 0);
        $adminUserId = Control::GetAdminUserId();
        if ($siteId > 0 && $adminUserId > 0) {
            $cacheDir = CACHEDATA . DIRECTORY_SEPARATOR . 'channeldata';
            $cacheFile = 'channel_for_manage_left.cache_' . $siteId . '_' . $adminUserId . '.php';
            $result = '';
            if (strlen(DataCache::Get($cacheDir . DIRECTORY_SEPARATOR . $cacheFile)) <= 0) {
                $documentChannelManageData = new DocumentChannelManageData();
                $arrList = $documentChannelManageData->GetListAllForZtree($siteId, $adminUserId);
                $sb = '[';
                for ($i = 0; $i < count($arrList); $i++) {
                    $documentChannelName = Format::FormatQuote($arrList[$i]['ChannelName']);
                    $documentChannelId = $arrList[$i]['ChannelId'];
                    $documentChannelType = intval($arrList[$i]['ChannelType']);
                    $rank = intval($arrList[$i]['Rank']);
                    $parentId = intval($arrList[$i]['ParentId']);
                    $childCounts = intval($arrList[$i]['childcounts']);
                    $isParent = "";
                    if ($childCounts > 0) {
                        $isParent = ", isParent:true";
                    }

                    $iconSkin = ',iconSkin:"diy' . $documentChannelType . '"';

                    if ($rank == 0) {
                        $sb = $sb . '{ id:' . $documentChannelId . ', pId:' . $parentId . ', name:"' . $documentChannelName . '", "doctype":"' . $documentChannelType . '", open:true ' . $isParent . ' ' . $iconSkin . '}';
                    } else {
                        $sb = $sb . '{ id:' . $documentChannelId . ', pId:' . $parentId . ', name:"' . $documentChannelName . '", "doctype":"' . $documentChannelType . '" ' . $isParent . ' ' . $iconSkin . '}';
                    }

                    if ($i < count($arrList) - 1) {
                        $sb = $sb . ',';
                    }
                }
                $sb = $sb . ']';
                $result = 'var zNodes =' . $sb . ';';

                DataCache::Set($cacheFile, $cacheDir, $result);
            } else {
                $result = DataCache::Get($cacheDir . DIRECTORY_SEPARATOR . $cacheFile);
            }
            return $result;
        }
    }

    /**
     * 迭代循环返回后台主界面树信息
     * @param int $adminUserId 后台帐号id
     * @param int $flag 未使用
     * @param int $siteId 站点id
     * @param int $rank 频道级别
     * @param int $parentId 父频道id
     * @param string $resultString 上一次拼接结果的字符串
     * @return string 返回新的拼接结果的字符串
     */
    private function _getTree($adminUserId, $flag, $siteId, $rank, $parentId, $resultString) {
        $channelData = new DocumentChannelData();
        $arrList = null;
        $arrList = $channelData->GetList($siteId, $adminUserId, $rank, $parentId);

        if (count($arrList) > 0) {

            for ($i = 0; $i < count($arrList); $i++) {
                if ($rank == 0) {
                    $resultString = $resultString . '<ul id="browser" class="filetree"><li>';
                } else {
                    $resultString = $resultString . '<ul><li>';
                }

                $channelName = Format::FormatQuote($arrList[$i]['ChannelName']);
                $channelId = $arrList[$i]['ChannelId'];
                $channelType = $arrList[$i]['ChannelType'];
                $rank = $arrList[$i]['Rank'];
                $css = 'folder';
                $hasSubChannel = $channelData->HasSubChannel($channelId);
                if ($hasSubChannel <= 0) {
                    $css = "file";
                }
                if (intval($channelType) === 2) {
                    $css = "type2";
                } else if (intval($channelType) === 4) {
                    $css = "type4";
                } else if (intval($channelType) === 5) {
                    $css = "type5";
                } else if (intval($channelType) === 6) {
                    $css = "type6";
                } else if (intval($channelType) === 7) {
                    $css = "type7";
                } else if (intval($channelType) === 8) {
                    $css = "type8";
                } else if (intval($channelType) === 9) {
                    $css = "type9";
                } else if (intval($channelType) === 10) {
                    $css = "type10";
                } else if (intval($channelType) === 11) {
                    $css = "type11";
                } else if (intval($channelType) === 12) {
                    $css = "type12";
                } else if (intval($channelType) === 0) {
                    $css = "type0";
                }


                $resultString = $resultString . '<span id="' . $channelId . '" class="' . $css . ' tcm" title="' . $channelName . '[' . $channelId . ']" accesskey="' . $channelType . '">' . $channelName . '</span>';

                $rank = $rank + 1;
                $parentId = $channelId;
                $resultString = self::_getTree($adminUserId, $flag, $siteId, $rank, $parentId, $resultString);
                $resultString = $resultString . '</li></ul>';
            }
        }

        return $resultString;
    }

}

?>
