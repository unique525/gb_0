<?php

/**
 * 公共 上传文件 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_UploadFile
 * @author zhangchi
 */
class UploadFilePublicGen extends BasePublicGen implements IBasePublicGen
{

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenPublic()
    {
        $result = "";

        $action = Control::GetRequest("a", "");
        switch ($action) {
            case "async_get_one":
                $result = self::AsyncGetOne();
                break;
            case "async_upload":
                $result = self::AsyncUpload();
                break;
            case "async_upload_batch":
                $result = self::AsyncUploadBatch();
                break;
            case "async_save_remote_image":
                $result = self::AsyncSaveRemoteImage();
                break;
            case "async_modify_upload_file_thumb_path2":
                $result = self::AsyncModifyUploadFileThumbPath2();
                break;
            case "async_cut_image":
                $result = self::AsyncCutImage();
                break;
            case "async_modify_upload_file_path_for_cut_image":
                $result = self::AsyncModifyUploadFilePathForCutImage();
                break;
            case "async_get_count_by_table_type_and_table_id":
                $result = self::AsyncGetCountByTableTypeAndTableId();
                break;
            case "async_get_count_by_table_type_and_table_id_and_org_name":
                $result = self::AsyncGetCountByTableTypeAndTableIdAndOrgName();
                break;
            case "async_get_upload_file_id_by_table_type_and_table_id_and_org_name":
                $result = self::AsyncGetUploadFileIdByTableTypeAndTableIdAndOrgName();
                break;

        }

        return $result;
    }

    /**
     * 取得一条记录的JSON值
     * @return string
     */
    private function AsyncGetOne()
    {
        $result = "";
        $uploadFileId = Control::GetRequest("upload_file_id", 0);
        if ($uploadFileId > 0) {
            $uploadFileData = new UploadFileData();
            $uploadFile = $uploadFileData->Fill($uploadFileId);
            $result = $uploadFile->GetJson();
        }
        return $result;
    }

    /**
     *
     * @return int
     */
    private function AsyncGetCountByTableTypeAndTableId()
    {
        $result = -1;
        $tableId = Control::PostOrGetRequest("table_id", 0);
        $tableType = Control::PostOrGetRequest("table_type", 0);
        if ($tableId > 0 && $tableType > 0) {
            $uploadFileData = new UploadFileData();
            $result = $uploadFileData->GetCountByTableTypeAndTableId($tableId, $tableType);
        }
        return $result;

    }


    /**
     *
     * @return int
     */
    private function AsyncGetUploadFileIdByTableTypeAndTableIdAndOrgName()
    {
        $result = -1;
        $tableId = Control::PostOrGetRequest("table_id", 0);
        $tableType = Control::PostOrGetRequest("table_type", 0);
        $orgName = Control::PostOrGetRequest("org_name", "");
        if ($tableId > 0 && $tableType > 0) {
            $uploadFileData = new UploadFileData();
            $result = $uploadFileData->GetUploadFileIdByTableTypeAndTableIdAndOrgName($tableId, $tableType, $orgName);
        }
        return $result;

    }

    /**
     *
     * @return int
     */
    private function AsyncGetCountByTableTypeAndTableIdAndOrgName()
    {
        $result = -1;
        $tableId = Control::PostOrGetRequest("table_id", 0);
        $tableType = Control::PostOrGetRequest("table_type", 0);
        $orgName = Control::PostOrGetRequest("org_name", "");
        if ($tableId > 0 && $tableType > 0) {
            $uploadFileData = new UploadFileData();
            $result = $uploadFileData->GetCountByTableTypeAndTableIdAndOrgName($tableId, $tableType, $orgName);
        }
        return $result;

    }


    /**
     * Ajax上传文件
     * @return string 返回Json结果
     */
    private function AsyncUpload()
    {
        $fileElementName = Control::PostOrGetRequest("file_element_name", "");
        $tableType = Control::PostOrGetRequest("table_type", 0);
        $tableId = Control::PostOrGetRequest("table_id", 0);

        $attachWatermark = intval(Control::PostOrGetRequest("attach_watermark", 0));
        if (strlen($fileElementName)>0 && $tableType > 0) {

            $imgMaxWidth = 0;
            $imgMaxHeight = 0;
            $imgMinWidth = 0;
            $imgMinHeight = 0;


            if ($tableType == UploadFileData::UPLOAD_TABLE_TYPE_USER_AVATAR) {

                $siteId = parent::GetSiteIdByDomain();
                if ($siteId > 0) {

                    $siteConfigData = new SiteConfigData($siteId);
                    $imgMaxWidth = $siteConfigData->UserAvatarMaxWidth;
                    $imgMaxHeight = $siteConfigData->UserAvatarMaxHeight;
                    $imgMinWidth = $siteConfigData->UserAvatarMinWidth;
                    $imgMinHeight = $siteConfigData->UserAvatarMinHeight;

                }

            }

            $uploadFile = new UploadFile();
            $uploadFileId = Control::GetRequest("upload_file_id",0);

            parent::Upload(
                $fileElementName,
                $tableType,
                $tableId,
                $uploadFile,
                $uploadFileId,
                $imgMaxWidth,
                $imgMaxHeight,
                $imgMinWidth,
                $imgMinHeight
            );

            if($attachWatermark > 0){

                $watermarkFilePath = "";

                switch($tableType){

                    case UploadFileData::UPLOAD_TABLE_TYPE_DOCUMENT_NEWS_CONTENT:

                        $siteId = parent::GetSiteIdByDomain();
                        $siteConfigData = new SiteConfigData($siteId);

                        $watermarkUploadFileId = $siteConfigData->DocumentNewsContentPicWatermarkUploadFileId;

                        if($watermarkUploadFileId>0){

                            $uploadFileData = new UploadFileData();

                            $watermarkFilePath = $uploadFileData->GetUploadFilePath(
                                $watermarkUploadFileId,
                                true
                            );

                        }
                        break;
                }

                $sourceType = self::MAKE_WATERMARK_SOURCE_TYPE_SOURCE_PIC;
                $watermarkPosition = ImageObject::WATERMARK_POSITION_BOTTOM_RIGHT;
                $mode = ImageObject::WATERMARK_MODE_PNG;
                $alpha = 70;

                parent::GenUploadFileWatermark1(
                    $uploadFileId,
                    $watermarkFilePath,
                    $sourceType,
                    $watermarkPosition,
                    $mode,
                    $alpha,
                    $uploadFile
                );

            }
            $result = $uploadFile->GetJson();

        } else {
            $result = '{';
            $result .= '"error":"param error",';
            $result .= '"result_html":"file_element_name:'.$fileElementName.';table_type:'.$tableType.'",';
            $result .= '"upload_file_id":"",';
            $result .= '"upload_file_path":""';
            $result .= '}';
        }

        return $result;
    }

    /**
     * Ajax批量上传文件
     * @return string 返回Json结果
     */
    private function AsyncUploadBatch()
    {
        $fileElementName = Control::PostOrGetRequest("file_element_name", "");
        $tableType = Control::PostOrGetRequest("table_type", 0);
        $tableId = Control::PostOrGetRequest("table_id", 0);
        $attachWatermark = intval(Control::PostOrGetRequest("batch_attach_watermark", 0));
        if (strlen($fileElementName)>0 && $tableType > 0) {

            $imgMaxWidth = 0;
            $imgMaxHeight = 0;
            $imgMinWidth = 0;
            $imgMinHeight = 0;


            $uploadFile = new UploadFile();

            parent::UploadBatch(
                $fileElementName,
                $tableType,
                $tableId,
                $uploadFile,
                $uploadFileId,
                $imgMaxWidth,
                $imgMaxHeight,
                $imgMinWidth,
                $imgMinHeight
            );

            if($attachWatermark > 0){

                $watermarkFilePath = "";

                switch($tableType){

                    case UploadFileData::UPLOAD_TABLE_TYPE_DOCUMENT_NEWS_CONTENT:

                        $siteId = parent::GetSiteIdByDomain();
                        $siteConfigData = new SiteConfigData($siteId);

                        $watermarkUploadFileId = $siteConfigData->DocumentNewsContentPicWatermarkUploadFileId;

                        if($watermarkUploadFileId>0){

                            $uploadFileData = new UploadFileData();

                            $watermarkFilePath = $uploadFileData->GetUploadFilePath(
                                $watermarkUploadFileId,
                                true
                            );

                        }
                        break;

                    case UploadFileData::UPLOAD_TABLE_TYPE_FORUM_POST_CONTENT:
                        $siteId = parent::GetSiteIdByDomain();
                        $siteConfigData = new SiteConfigData($siteId);
                        $watermarkUploadFileId = $siteConfigData->ForumPostContentWatermarkUploadFileId;
                        if($attachWatermark > 0){
                            $uploadFileData = new UploadFileData();
                            $watermarkFilePath = $uploadFileData->GetUploadFilePath(
                                $watermarkUploadFileId,
                                true
                            );
                        }
                    break;
                }

                $sourceType = self::MAKE_WATERMARK_SOURCE_TYPE_SOURCE_PIC;
                $watermarkPosition = ImageObject::WATERMARK_POSITION_BOTTOM_RIGHT;
                $mode = ImageObject::WATERMARK_MODE_PNG;
                $alpha = 70;

                parent::GenUploadFileWatermark1(
                    $uploadFileId,
                    $watermarkFilePath,
                    $sourceType,
                    $watermarkPosition,
                    $mode,
                    $alpha,
                    $uploadFile
                );

            }

            $result = $uploadFile->GetJson();

        } else {
            $result = '{';
            $result .= '"error":"param error",';
            $result .= '"result_html":"file_element_name:'.$fileElementName.';table_type:'.$tableType.'",';
            $result .= '"upload_file_id":"",';
            $result .= '"upload_file_path":""';
            $result .= '"upload_file_watermark_path1":""';
            $result .= '}';
        }

        return $result;
    }

    /**
     * 异步保存远程图片，xhEditor用
     * @return string 返回新的URL拼接字符串
     */
    private function AsyncSaveRemoteImage()
    {
        $arrUrls = explode('|', Control::PostOrGetRequest('urls',''));
        $urlCount = count($arrUrls);
        $tableType = Control::GetRequest("table_type", 0);
        $tableId = Control::GetRequest("table_id", 0);

        if (!empty($arrUrls) && $tableType > 0) {
            for ($i = 0; $i < $urlCount; $i++) {
                $uploadFile = new UploadFile();

                parent::SaveRemoteImage($arrUrls[$i], $tableType, $tableId, $uploadFile);

                if (strlen($uploadFile->UploadFilePath) > 0) {
                    $arrUrls[$i] = $uploadFile->UploadFilePath;
                }
            }
        }

        return implode('|', $arrUrls);

    }

    private function AsyncModifyUploadFileThumbPath2()
    {
        $uploadFileId = Control::GetRequest("upload_file_id", 0);
        $width = Control::GetRequest("width", 0);
        $height = Control::GetRequest("height", 0);

        if ($uploadFileId > 0 && $width > 0) {
            if ($height <= 0) {
                $resultOfCreateThumb2 = parent::GenUploadFileThumb2($uploadFileId, $width);
            } else {
                $resultOfCreateThumb2 = parent::GenUploadFileThumb2($uploadFileId, $width, $height);
            }

            if ($resultOfCreateThumb2 > 0) {
                $uploadFileData = new UploadFileData();
                $uploadFile = $uploadFileData->Fill($uploadFileId);
                $result = $uploadFile->GetJson();
            } else {
                $result = '{';
                $result .= '"error":"system error",';
                $result .= '"result_html":"UploadFileThumbPath2",';
                $result .= '"upload_file_id":"",';
                $result .= '"upload_file_path":""';
                $result .= '}';
            }
        } else {
            $result = '{';
            $result .= '"error":"param error",';
            $result .= '"result_html":"UploadFileThumbPath2",';
            $result .= '"upload_file_id":"",';
            $result .= '"upload_file_path":""';
            $result .= '}';
        }

        return $result;
    }

    private function AsyncCutImage(){
        $uploadFileId = Control::GetRequest("upload_file_id",0);

        $result = "";
        if($uploadFileId > 0){
            $uploadFileData = new UploadFileData();
            $uploadFile = $uploadFileData->Fill($uploadFileId);

            if($uploadFile != null && isset($uploadFile)){

                $sourceX = Control::PostOrGetRequest("x",0);
                $sourceY = Control::PostOrGetRequest("y",0);
                $targetWidth = Control::PostOrGetRequest("width",0);
                $targetHeight = Control::PostOrGetRequest("height",0);
                $sourceWidth = Control::PostOrGetRequest("w",0);
                $sourceHeight = Control::PostOrGetRequest("h",0);
                $newImagePath = ImageObject::CutImg($uploadFile->UploadFilePath,$sourceX,$sourceY,$sourceWidth,$sourceHeight,$targetWidth,$targetHeight);
                if(!empty($newImagePath)){
                    $result = '{"new_image_path":"'.Format::FormatJson($newImagePath).'"}';
                }
            }
        }
        return $result;
    }

    private function AsyncModifyUploadFilePathForCutImage(){
        $uploadFileId = Control::GetRequest("upload_file_id",0);
        $uploadFilePath = Control::GetRequest("upload_file_path","");
        $userId =Control::GetUserId();

        if($uploadFileId > 0 && $uploadFilePath != ""){
            $uploadFilePublicData = new UploadFilePublicData();
            $result = $uploadFilePublicData->ModifyUploadFilePath($uploadFileId,$uploadFilePath,$userId);
            if ($result > 0) {
                $uploadFileData = new UploadFileData();
                $uploadFile = $uploadFileData->Fill($uploadFileId);
                $result = $uploadFile->GetJson();
            } else {
                $result = '{';
                $result .= '"error":"system error",';
                $result .= '"result_html":"",';
                $result .= '"upload_file_id":"",';
                $result .= '"upload_file_path":""';
                $result .= '}';
            }
        } else {
            $result = '{';
            $result .= '"error":"param error",';
            $result .= '"result_html":"UploadFilePathForCutImage",';
            $result .= '"upload_file_id":"",';
            $result .= '"upload_file_path":""';
            $result .= '}';
        }
        return $result;
    }
} 