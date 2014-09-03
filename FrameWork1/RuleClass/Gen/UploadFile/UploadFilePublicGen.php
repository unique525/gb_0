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
            case "async_save_remote_image":
                $result = self::AsyncSaveRemoteImage();
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
     * Ajax上传文件
     * @return string 返回Json结果
     */
    private function AsyncUpload()
    {

        $fileElementName = Control::GetRequest("file_element_name", "");
        $tableType = Control::GetRequest("table_type", 0);
        $tableId = Control::GetRequest("table_id", 0);
        if (!empty($fileElementName) && $tableType > 0) {

            $uploadFile = new UploadFile();
            $uploadFileId = 0;

            parent::Upload($fileElementName, $tableType, $tableId, $uploadFile, $uploadFileId);

            $result = $uploadFile->FormatToJson();

        } else {
            $result = '{';
            $result .= '"error":"param error",';
            $result .= '"result_html":"",';
            $result .= '"upload_file_id":"",';
            $result .= '"upload_file_path":""';
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
        $arrUrls = explode('|', $_POST['urls']);
        $urlCount = count($arrUrls);
        $tableType = Control::GetRequest("table_type", 0);
        $tableId = Control::GetRequest("table_id", 0);

        if (!empty($arrUrls) && $tableType > 0) {
            for ($i = 0; $i < $urlCount; $i++) {
                $uploadFile = new UploadFile();

                parent::SaveRemoteImage($arrUrls[$i], $tableType, $tableId, $uploadFile);

                if (strlen($uploadFile->UploadFilePath)>0) {
                    $arrUrls[$i] = $uploadFile->UploadFilePath;
                }
            }
        }

        return implode('|', $arrUrls);

    }
} 