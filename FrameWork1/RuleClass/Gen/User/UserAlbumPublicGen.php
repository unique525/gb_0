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
} 