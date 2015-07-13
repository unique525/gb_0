<?php


class UserAlbumPublicGen extends BasePublicGen implements IBasePublicGen{
    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenPublic()
    {
        $result = "";
        $action = Control::GetRequest("a", "");
        switch ($action) {
            case "create":
                $result = self::GenCreate();
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
            Control::GoUrl("/default.php?mod=user&a=login&re_url=$referUrl");
            die("user id is null");
        }

        $defaultTemp = "user_album_detail";
        $tempContent = parent::GetDynamicTemplateContent(
            $defaultTemp, $siteId);

        if (!empty($_POST)){
            $userAlbumIntro = Control::PostRequest("f_UserAlbumIntro", "", false);
            $userAlbumIntro = str_ireplace('\"', '"', $userAlbumIntro);
            $userAlbumIntro = Format::RemoveScript($userAlbumIntro);


            $userAlbumPublicData = new UserAlbumPublicData();
            $userAlbumPicPublicData = new UserAlbumPicPublicData();


            $userAlbumTypeId = Control::PostRequest("f_UserAlbumTypeId", 1, false);;
            $createDate = date("Y-m-d H:i:s", time());

            $userAlbumId = $userAlbumPublicData->Create($userAlbumTypeId,$userId,$siteId,$createDate,$userAlbumIntro);

            if($userAlbumId > 0){

                $userAlbumPicId = $userAlbumPicPublicData->Create($userAlbumId,$createDate);
                echo $userAlbumPicId;
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

                    print_r($arrUploadFile);

                    for ($u = 0; $u < count($arrUploadFileId); $u++) {
                        $userAlbumPicPublicData->ModifyUploadFileId($userAlbumPicId, intval($arrUploadFileId[$u]));
                    }
                }
            }
        }

        return $tempContent;
    }

    private function GenDetail(){
        $result = "";
        $userAlbumId = Control::GetRequest("user_album_id", 0);


        if($userAlbumId>0){
            $siteId = parent::GetSiteIdByDomain();

            $defaultTemp = "user_album_detail";
            $templateContent = parent::GetDynamicTemplateContent(
                $defaultTemp,
                $siteId,
                "",
                $templateMode);
            $result = self::getDetailTemplateContent(
                    $siteId,$userAlbumId,$templateContent);

        }
        return $result;
    }

    private function getDetailTemplateContent(
        $siteId,$userAlbumId,$templateContent
    ){

        $templateContent = str_ireplace("{UserAlbumId}",
            $userAlbumId, $templateContent);

        parent::ReplaceFirst($templateContent);

        parent::ReplaceSiteInfo($siteId, $templateContent);

        $newspaperArticlePublicData = new NewspaperArticlePublicData();
        $channelId = $newspaperArticlePublicData->GetChannelId($newspaperArticleId, true);
        $templateContent = str_ireplace("{ChannelId}", $channelId, $templateContent);

        $templateContent = parent::ReplaceTemplate($templateContent);



        $arrOne = $newspaperArticlePublicData->GetOne($newspaperArticleId);

        $arrOne["NewspaperArticleContent"] =
            str_ireplace("\n","<br /><br />", $arrOne["NewspaperArticleContent"]);
        $arrOne["NewspaperArticleContent"] =
            str_ireplace("&lt;","<", $arrOne["NewspaperArticleContent"]);
        $arrOne["NewspaperArticleContent"] =
            str_ireplace("&gt;",">", $arrOne["NewspaperArticleContent"]);


        Template::ReplaceOne($templateContent, $arrOne);

        //版面选择
        $newspaperPagePublicData = new NewspaperPagePublicData();
        $newspaperPageId = $newspaperArticlePublicData->GetNewspaperPageId($newspaperArticleId, true);
        $newspaperId = $newspaperPagePublicData->GetNewspaperId($newspaperPageId, true);

        $newspaperPageUploadFilePath = $newspaperPagePublicData->GetUploadFilePath($newspaperPageId, true);



        $newspaperPublicData = new NewspaperPublicData();
        $publishDate = $newspaperPublicData->GetPublishDate($newspaperId, true);

        $templateContent = str_ireplace("{PublishDate}", $publishDate, $templateContent);
        $templateContent = str_ireplace("{NewspaperPageUploadFilePath}", $newspaperPageUploadFilePath, $templateContent);
        $templateContent = str_ireplace("{CurrentPublishDate}", $publishDate, $templateContent);

        $arrNewspaperPages = $newspaperPagePublicData -> GetListForSelectPage($newspaperId);
        $tagId = "newspaper_page";

        if(count($arrNewspaperPages)>0){
            Template::ReplaceList($templateContent, $arrNewspaperPages, $tagId);
        }else{
            Template::RemoveCustomTag($tempContent, $tagId);
        }

        //$templateContent = parent::ReplaceTemplate($templateContent);
        //左边导航菜单选择文章列表
        //默认只显示已发状态的新闻

        if(count($arrNewspaperPages)>0){
            $state = 0;
            $newspaperPageIds="";
            foreach($arrNewspaperPages as $page){
                $newspaperPageIds.=",".$page["NewspaperPageId"];
            }

            if(strpos($newspaperPageIds,',') == 0){
                $newspaperPageIds = substr($newspaperPageIds,1);
            }

            $tagId = "newspaper_page_and_article";
            if(stripos($templateContent, $tagId) > 0){
                $newspaperArticlePublicData= new NewspaperArticlePublicData();
                $arrNewspaperArticles=$newspaperArticlePublicData->GetListOfMultiPage($newspaperPageIds,$state);


                $tagName = Template::DEFAULT_TAG_NAME;
                $tableIdName = "NewspaperPageId";
                $parentIdName = "NewspaperPageId";
                Template::ReplaceList(
                    $templateContent,
                    $arrNewspaperPages,
                    $tagId,
                    $tagName,
                    $arrNewspaperArticles,
                    $tableIdName,
                    $parentIdName
                );
            }


        }
        else{
            $tagId = "newspaper_page_and_article";
            Template::RemoveCustomTag($tempContent, $tagId);
        }

        parent::ReplaceEnd($templateContent);

        return $templateContent;

    }
} 