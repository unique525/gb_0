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

            case "gen":
                $result = self::GenUserAlbum();
                break;
            case "create":
                $result = self::GenCreate();
                break;
        }

        $result = str_ireplace("{action}", $action, $result);
        return $result;
    }

    private function GenUserAlbum(){

        $siteId = parent::GetSiteIdByDomain();

        $userId = Control::GetUserId();
        if ($userId <= 0) {
            $referUrl = urlencode("/default.php?mod=user_album&a=gen");
            Control::GoUrl("/default.php?mod=user&a=login&re_url=$referUrl");
            die("user id is null");
        }

        $defaultTemp = "user_album_detail";
        $tempContent = parent::GetDynamicTemplateContent(
            $defaultTemp, $siteId);
        return $tempContent;
    }

    private function GenCreate(){

        $siteId = parent::GetSiteIdByDomain();

        $userId = Control::GetUserId();
        if ($userId <= 0) {
            $referUrl = urlencode("/default.php?mod=user_album&a=create");
            Control::GoUrl("/default.php?mod=user&a=login&re_url=$referUrl");
            die("user id is null");
        }

        $schoolName = Control::PostRequest("f_SchoolName", "");
        $schoolName = Format::FormatHtmlTag($schoolName);

        $className = Control::PostRequest("f_ClassName", "");
        $className = Format::FormatHtmlTag($className);

        $realName = Control::PostRequest("f_RealName", "");
        $realName = Format::FormatHtmlTag($realName);

        $mobile = Control::PostRequest("f_Mobile", "");
        $mobile = Format::FormatHtmlTag($mobile);

        $userAlbumPicContent = Control::PostRequest("f_UserAlbumPicContent", "", false);
        $userAlbumPicContent = str_ireplace('\"', '"', $userAlbumPicContent);
        $userAlbumPicContent = Format::RemoveScript($userAlbumPicContent);

        $uploadFiles = Control::PostRequest("f_UploadFiles", "");



        $userAlbumPicId = 0;
        if (!empty($_FILES)) {

            $tableType = UploadFileData::UPLOAD_TABLE_TYPE_FORUM_POST_CONTENT;
            $tableId = $userAlbumPicId;
            $arrUploadFile = array();
            $arrUploadFileId = array();
            $imgMaxWidth = 0;
            $imgMaxHeight = 0;
            $imgMinWidth = 0;
            $imgMinHeight = 0;
            $attachWatermark = intval(Control::PostOrGetRequest("attach_watermark", 0));

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

            $watermarkFilePath = "";

            if ($attachWatermark > 0) {


                switch ($tableType) {
                    //帖子内容图
                    case UploadFileData::UPLOAD_TABLE_TYPE_FORUM_POST_CONTENT:

                        $siteId = parent::GetSiteIdByDomain();
                        $siteConfigData = new SiteConfigData($siteId);

                        $watermarkUploadFileId = $siteConfigData->ForumPostContentWatermarkUploadFileId;

                        if ($watermarkUploadFileId > 0) {

                            $uploadFileData = new UploadFileData();

                            $watermarkFilePath = $uploadFileData->GetUploadFilePath(
                                $watermarkUploadFileId,
                                true
                            );

                        }

                        break;


                }

            }

            $sourceType = self::MAKE_WATERMARK_SOURCE_TYPE_SOURCE_PIC;
            $watermarkPosition = ImageObject::WATERMARK_POSITION_BOTTOM_RIGHT;
            $mode = ImageObject::WATERMARK_MODE_PNG;
            $alpha = 70;

            for ($u = 0; $u < count($arrUploadFileId); $u++) {


                if($attachWatermark>0 && strlen($watermarkFilePath)>0){
                    parent::GenUploadFileWatermark1(
                        $arrUploadFileId[$u],
                        $watermarkFilePath,
                        $sourceType,
                        $watermarkPosition,
                        $mode,
                        $alpha,
                        $arrUploadFile[$u]
                    );
                }


                $uploadFiles = $uploadFiles . "," . $arrUploadFileId[$u];

                //直接上传时，在内容中插入上传的图片

                if (strlen($arrUploadFile[$u]->UploadFileWatermarkPath1) > 0
                ) {
                    //有水印图时，插入水印图

                    $insertHtml = Format::FormatUploadFileToHtml(
                        $arrUploadFile[$u]->UploadFileWatermarkPath1,
                        FileObject::GetExtension($arrUploadFile[$u]->UploadFileWatermarkPath1),
                        $arrUploadFileId[$u],
                        ""
                    );

                } else {
                    //没有水印图时，插入原图
                    $insertHtml = Format::FormatUploadFileToHtml(
                        $arrUploadFile[$u]->UploadFilePath,
                        FileObject::GetExtension($arrUploadFile[$u]->UploadFilePath),
                        $arrUploadFileId[$u],
                        ""
                    );


                }
                $forumPostContent = $forumPostContent . "<br />" . $insertHtml;


            }

        }

    }
} 