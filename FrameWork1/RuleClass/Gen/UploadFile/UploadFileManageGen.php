<?php

/**
 * 公共 上传文件 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_UploadFile
 * @author zhangchi
 */
class UploadFileManageGen extends BaseManageGen implements IBaseManageGen
{
    public function Gen()
    {
        $result = "";
        $method = Control::GetRequest("m", "", false);
        switch ($method) {
            case "async_get_one":
                $result = self::AsyncGetOne();
                break;
            case "modify":
                $result = self::GenModify();
                break;
            case "modify_by_table_id":
                $result = self::GenModifyByTableId();
                break;
            case "batch_modify":
                self::GenBatchModify();
                break;
            case "async_modify_upload_file_title":
                $result = self::AsyncModifyUploadFileTitle();
                break;
            case "async_cut_image":
                $result = self::AsyncCutImage();
                break;
            case "create_cut_image":
                $result = self::CreateCutImage();
                break;
            case "async_rotate_image":
                $result = self::AsyncRotateImage();
                break;
            case "async_modify_upload_file_cut_path1":
                $result = self::AsyncModifyUploadFileCutPath1();
                break;
        }
        return $result;
    }

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

    private function GenModify()
    {
        $templateContent = Template::Load("upload_file/upload_file_deal.html", "common");
        parent::ReplaceFirst($templateContent);
        $resultJavaScript = "";

        $uploadFileId = intval(Control::GetRequest("upload_file_id", 0));
        $tableId = intval(Control::GetRequest("table_id", 0));
        $siteId = intval(Control::GetRequest("site_id", 0));

        if ($uploadFileId > 0 && $tableId > 0 && $siteId > 0) {


            $templateContent = str_ireplace("{UploadFileId}", $uploadFileId, $templateContent);
            $templateContent = str_ireplace("{TableId}", $uploadFileId, $templateContent);
            $templateContent = str_ireplace("{SiteId}", $uploadFileId, $templateContent);

            $uploadFileData = new UploadFileData();

            $arrOne = $uploadFileData->GetOne($uploadFileId);

            Template::ReplaceOne($templateContent, $arrOne);

            if (!empty($_POST)) {
                $fileElementName = "file_UploadFile";

                $uploadFileManageData = new UploadFileManageData();

                $tableType = $uploadFileManageData->GetTableType($uploadFileId, false); //channel

                //if($tableId<=0){
                //    $tableId = $uploadFileManageData->GetTableId($uploadFileId, false);
                //}

                $uploadFile = new UploadFile();
                $newUploadFileId = 0;
                $uploadResult = self::Upload(
                    $fileElementName,
                    $tableType,
                    $tableId,
                    $uploadFile,
                    $newUploadFileId
                );

                if (intval($uploadResult) <= 0 && $newUploadFileId <= 0) {
                    //上传出错或没有选择文件上传
                } else {
                    //删除原有题图
                    parent::DeleteUploadFile($uploadFileId);

                    switch ($tableType) {

                        case UploadFileData::UPLOAD_TABLE_TYPE_NEWSPAPER_ARTICLE_PIC:
                            //修改原图
                            $newspaperArticlePicId = $tableId;

                            $newspaperArticlePicManageData = new NewspaperArticlePicManageData();

                            $modifyResult = $newspaperArticlePicManageData->ModifyUploadFileId(
                                $newspaperArticlePicId, $newUploadFileId
                            );

                            if ($modifyResult > 0) {

                                $mobileWidth = 640;

                                parent::GenUploadFileMobile($newUploadFileId, $mobileWidth);

                                parent::GenUploadFileCompress1($newUploadFileId, $mobileWidth, 0, 60);

                                //水印图
                                if ($siteId > 0) {
                                    $siteConfigData = new SiteConfigData($siteId);
                                    $watermarkUploadFileId = $siteConfigData->NewspaperArticlePicWatermarkUploadFileId;
                                    if ($watermarkUploadFileId > 0) {
                                        $uploadFileData = new UploadFileData();
                                        $watermarkUploadFilePath =
                                            $uploadFileData->GetUploadFilePath(
                                                $watermarkUploadFileId, true);
                                        parent::GenUploadFileWatermark1(
                                            $newUploadFileId,
                                            $watermarkUploadFilePath);
                                    }
                                }


                            }

                            break;


                    }

                }
            }


        }
        parent::ReplaceEnd($templateContent);
        $templateContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $templateContent);
        return $templateContent;
    }

    private function GenModifyByTableId()
    {
        $tableId = Control::GetRequest("table_id", 0);
        $tableType = Control::GetRequest("table_type", 0);
        if ($tableId > 0 && $tableType > 0) {
            $templateContent = Template::Load("upload/upload_deal_by_table_id.html");

            $uploadFileManageData = new UploadFileManageData();
            return $templateContent;
        } else {
            return null;
        }
    }

    private function GenBatchModify()
    {
        //$result = -1;

        $do = intval(Control::GetRequest("do", 0));

        if ($do > 0) {


        } else {

            $url = $_SERVER["PHP_SELF"];

            //echo '正在操作，请不要关闭本窗口<br /><br />';

            $uploadFileManageData = new UploadFileManageData();

            $tableType = Control::GetRequest("table_type", 0);
            $topCount = Control::GetRequest("top_count", 10);

            $arrList = $uploadFileManageData->GetListOfIsNotBatchOperate($tableType, $topCount);

            if (count($arrList) > 0) {
                for ($i = 0; $i < count($arrList); $i++) {

                    $uploadFileId = intval($arrList[$i]["UploadFileId"]);

                    $mobileWidth = 640;

                    parent::GenUploadFileMobile($uploadFileId, $mobileWidth);

                    parent::GenUploadFileCompress1($uploadFileId, $mobileWidth, 0, 80);

                    $uploadFileManageData->ModifyIsBatchOperate($uploadFileId, 1);

                    //echo $uploadFileId . ' : Gen UploadFile Mobile Done <br />';

                }
                header('refresh:0 ' . $url);
            } else {
                echo "任务已完成";
            }


        }


        //return $result;
    }


    /**
     * 异步修改图片对应附件的标题
     */
    private function AsyncModifyUploadFileTitle()
    {
        $result = 0;
        $uploadFileId = Control::PostRequest("UploadFileId", 0);
        if ($uploadFileId > 0) {

            /*********************
             * 查看是否有修改权限 *
             *********************/
            $canModify = 0;
            $channelManageData = new ChannelManageData();
            $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
            $uploadFileManageData = new UploadFileManageData();

            $arrayTableInfo = $uploadFileManageData->GetTableTypeAndTableId($uploadFileId);
            $manageUserId = Control::GetManageUserId();
            if ($arrayTableInfo["TableType"] == UploadFileData::UPLOAD_TABLE_TYPE_DOCUMENT_NEWS_CONTENT) { //文章内容图片
                $channelId = $arrayTableInfo["TableId"];
                $siteId = $channelManageData->GetSiteId($channelId, true);
                $canModify = $manageUserAuthorityManageData->CanChannelModify($siteId, $channelId, $manageUserId);
            }


            if (!$canModify) {
                die(Language::Load('channel', 4));
            }

            $uploadFileTitle = $_POST["UploadFileTitle"];
            $result = $uploadFileManageData->ModifyUploadFileTitle($uploadFileId, $uploadFileTitle);

        }
        return $result;
    }

    /**
     * 异步截图 后台
     * @return string
     */
    private function AsyncCutImage()
    {
        $uploadFileId = Control::GetRequest("upload_file_id", 0);

        $result = "";
        if ($uploadFileId > 0) {

            $uploadFileData = new UploadFileData();
            $uploadFile = $uploadFileData->Fill($uploadFileId);

            if ($uploadFile != null && isset($uploadFile)) {

                $sourceX = Control::PostOrGetRequest("x", 0, false);
                $sourceY = Control::PostOrGetRequest("y", 0, false);
                $targetWidth = Control::PostOrGetRequest("width", 0, false);
                $targetHeight = Control::PostOrGetRequest("height", 0, false);
                $sourceWidth = Control::PostOrGetRequest("w", 0, false);
                $sourceHeight = Control::PostOrGetRequest("h", 0, false);
                $sourceType = intval(Control::PostOrGetRequest("source_type", 0, false));

                $filePath = $uploadFile->UploadFilePath;
                if($sourceType == 1){
                    $filePath = $uploadFile->UploadFileCompressPath1;
                }

                $newImagePath = ImageObject::CutImg($filePath, $sourceX, $sourceY, $sourceWidth, $sourceHeight, $targetWidth, $targetHeight);
                if (!empty($newImagePath)) {
                    $result = '{"new_image_path":"'
                        . Format::FormatJson($newImagePath)
                        . '"}';
                }
            }
        }
        return $result;
    }

    private function CreateCutImage()
    {
        $templateContent = Template::Load("upload_file/upload_file_cut.html", "common");

        parent::ReplaceFirst($templateContent);
        parent::ReplaceEnd($templateContent);

        return $templateContent;
    }

    private function AsyncRotateImage(){
        $uploadFileId = Control::GetRequest("upload_file_id", 0);
        $angle = Control::GetRequest("angle", 90);
        if($uploadFileId > 0){

            $uploadFileData = new UploadFileData();
            $uploadFilePath = $uploadFileData->GetUploadFilePath($uploadFileId, false);

            if(strlen($uploadFilePath)>0){

                $result = ImageObject::Rotate($uploadFilePath, $angle);

            }else{
                $result = -10; //没有原文件路径
            }

        }else{
            $result = -5; //参数错误
        }

        return Control::GetRequest("jsonpcallback","") . '('.$result.')';
    }

    private function AsyncModifyUploadFileCutPath1(){
        $uploadFileId = Control::GetRequest("upload_file_id", 0);
        $uploadFileCutPath = Control::PostOrGetRequest("upload_file_cut_path","", false);

        if($uploadFileId > 0 && !empty($uploadFileCutPath)){
            $uploadFileData = new UploadFileData();
            $result = $uploadFileData->ModifyUploadFileCutPath1($uploadFileId,$uploadFileCutPath);
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