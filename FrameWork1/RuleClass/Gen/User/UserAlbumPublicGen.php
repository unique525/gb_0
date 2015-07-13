<?php


class UserAlbumPublicGen extends BasePublicGen implements IBasePublicGen{
    /**
     * 引导方法
     * @return string 返回执行结果11
     */
    public function GenPublic()
    {
        $result = "";
        $action = Control::GetRequest("a", "");
        switch ($action) {
            case "create":
                $result = self::GenCreate();
                break;
            case "list":
                $result = self::GenList();
                break;
            case "detail":
                $result = self::GenDetail();
                break;
        }

        $result = str_ireplace("{action}", $action, $result);
        return $result;
    }

    private function GenCreate(){

        $siteId = parent::GetSiteIdByDomain();

        $userId = Control::GetUserId();
        if ($userId <= 0) {
            $referUrl = urlencode("/default.php?mod=user_album&a=create");
            Control::GoUrl("/default.php?mod=user&a=login&temp=user_album_login&re_url=$referUrl");
            die("user id is null");
        }

        $defaultTemp = "user_album_create";
        $tempContent = parent::GetDynamicTemplateContent(
            $defaultTemp, $siteId);

        if (!empty($_POST)){
            $userAlbumIntro = Control::PostRequest("f_UserAlbumIntro", "", false);
            $userAlbumIntro = str_ireplace('\"', '"', $userAlbumIntro);
            $userAlbumIntro = Format::RemoveScript($userAlbumIntro);


            $userAlbumPublicData = new UserAlbumPublicData();
            $userAlbumPicPublicData = new UserAlbumPicPublicData();

            $goUrl = Control::PostRequest("goUrl", "", false);


            $userAlbumTypeId = Control::PostRequest("f_UserAlbumTypeId", 1, false);;
            $createDate = date("Y-m-d H:i:s", time());

            $userAlbumId = $userAlbumPublicData->Create($userAlbumTypeId,$userId,$siteId,$createDate,$userAlbumIntro);
            if($userAlbumId > 0){

                $userAlbumPicId = $userAlbumPicPublicData->Create($userAlbumId,$createDate);
                if (!empty($_FILES)) {

                    $tableType = UploadFileData::UPLOAD_TABLE_TYPE_USER_ALBUM_PIC;
                    $tableId = $userAlbumPicId;
                    $arrUploadFile = array();
                    $arrUploadFileId = array();
                    $imgMaxWidth = 0;
                    $imgMaxHeight = 0;
                    $imgMinWidth = 0;
                    $imgMinHeight = 0;

                    parent::UploadMultiple(
                        "file_upload_to_content_of_wap",
                        $tableType,
                        $tableId,
                        $arrUploadFile, //UploadFile类型的数组
                        $arrUploadFileId, //UploadFileId 数组
                        $imgMaxWidth,
                        $imgMaxHeight,
                        $imgMinWidth,
                        $imgMinHeight
                    );

                    for ($u = 0; $u < count($arrUploadFileId); $u++) {

                        //
                        if ($u == 0 ){
                            $userAlbumPublicData->ModifyCoverPicUploadFileId($userAlbumId,intval($arrUploadFileId[$u]));
                        }


                        $userAlbumPicPublicData->ModifyUploadFileId($userAlbumPicId, intval($arrUploadFileId[$u]));
                    }

                    if(count($arrUploadFile) > 0){

                        Control::GoUrl($goUrl);
                    }
                }
            }
        }

        return $tempContent;
    }
    private function GenList(){
        $siteId = parent::GetSiteIdByDomain();
        $searchKey = Control::GetRequest("search_key", "");
        $defaultTemp = "user_album_list";
        $tempContent = parent::GetDynamicTemplateContent(
            $defaultTemp, $siteId);
        $state = 10;
        $pageIndex = Control::GetRequest("p",1);
        $pageSize = 6;
        $pageBegin = ($pageIndex - 1)*$pageSize;
        $allCount = 0;

        $siteId = 2;
        if ($pageIndex > 0 && $siteId > 0) {
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $tagId = "user_album_list";
            $allCount = 0;
            $userAlbumPublicData = new UserAlbumPublicData();
            $arrUserAlbumList = $userAlbumPublicData->GetList($siteId, $pageBegin, $pageSize, $allCount,$state,$searchKey);
            //print_r($arrUserAlbumList);
            if (count($arrUserAlbumList) > 0) {
                Template::ReplaceList($tempContent, $arrUserAlbumList, $tagId);
                $styleNumber = 1;
                $pagerTemp = "pager";
                $pagerTemplate = parent::GetDynamicTemplateContent(
                    $pagerTemp, $siteId);
                $isJs = FALSE;
                $navUrl = "default.php?mod=user_album&a=list&site_id=$siteId&p={0}&ps=$pageSize";
                $jsFunctionName = "";
                $jsParamList = "";
                $pagerButton = Pager::ShowPageButton($pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex, $styleNumber, $isJs, $jsFunctionName, $jsParamList);
                $tempContent = str_ireplace("{pager_button}", $pagerButton, $tempContent);
            } else {
                Template::RemoveCustomTag($tempContent, $tagId);
                $tempContent = str_ireplace("{pager_button}", "未搜索到相关数据&nbsp;&nbsp;<a href='default.php?mod=user_album&a=list'>点击返回</a>", $tempContent);
            }
        }
        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }

    private function GenDetail()
    {
        $result = "";
        $userAlbumId = Control::GetRequest("user_album_id", 0);

        if ($userAlbumId > 0) {

            $siteId = parent::GetSiteIdByDomain();

            $defaultTemp = "user_album_detail";
            $templateContent = parent::GetDynamicTemplateContent(
                $defaultTemp,
                $siteId,
                "",
                $templateMode);
            $result = self::getDetailTemplateContent(
                $siteId, $userAlbumId, $templateContent);

        }
        return $result;
    }

    private function getDetailTemplateContent($siteId, $userAlbumId,
                                              $templateContent)
    {
        $templateContent = str_ireplace("{UserAlbumId}",
            $userAlbumId, $templateContent);

        parent::ReplaceFirst($templateContent);

        parent::ReplaceSiteInfo($siteId, $templateContent);


        $userAlbumPublicData = new UserAlbumPublicData();
        $arrOne = $userAlbumPublicData->GetOne($userAlbumId);

        if($arrOne["UserId"]>0)
        {
            //用户信息替换
            $userInfoPublicData = new UserInfoPublicData();
            $arrUserInfoOne = $userInfoPublicData->GetOne($arrOne["UserId"], $siteId);
            if (!empty($arrUserInfoOne) > 0) {
                Template::ReplaceOne($templateContent, $arrUserInfoOne);
            }
        }
        //用户相册信息替换
        Template::ReplaceOne($templateContent, $arrOne);
        parent::ReplaceEnd($templateContent);
        return $templateContent;
    }
}