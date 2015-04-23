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

            $nowManageUserId = Control::GetManageUserId();


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
        }
        return $result;
    }

}

?>
