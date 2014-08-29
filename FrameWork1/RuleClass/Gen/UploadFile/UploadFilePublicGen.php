<?php

/**
 * 公共 上传文件 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_UploadFile
 * @author zhangchi
 */
class UploadFilePublicGen extends BasePublicGen implements IBasePublicGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenPublic() {
        $result = "";

        $action = Control::GetRequest("a", "");
        switch ($action) {
            case "async_get_one":
                $result = self::AsyncGetOne();
                break;
            case "async_upload":
                $result = self::AsyncUpload();
                break;
        }

        return $result;
    }

    /**
     * 取得一条记录的JSON值
     * @return string
     */
    private function AsyncGetOne(){

        $result = "";

        $uploadFileId = Control::GetRequest("upload_file_id", 0);
        if($uploadFileId>0){
            $uploadFileData = new UploadFileData();
            $uploadFile = $uploadFileData->Fill($uploadFileId);
            $result = $uploadFile->GetJson();
        }

        return $result;


    }

    /**
     *
     */
    private function AsyncUpload(){
        $result = "";

        $fileElementName = Control::GetRequest("file_element_name", "");
        $tableType = Control::GetRequest("table_type", 0);
        $tableId = Control::GetRequest("table_id", 0);
        if(!empty($fileElementName) && $tableType>0){

            $uploadFile = new UploadFile();
            $uploadFileId = 0;

            parent::Upload($fileElementName,$tableType,$tableId,$uploadFile,$uploadFileId);

            $result = $uploadFile->FormatToJson();

        }else{
            $result = '{';
            $result .= '"error":"param error",';
            $result .= '"result_html":"",';
            $result .= '"upload_file_id":"",';
            $result .= '"upload_file_path":""';
            $result .= '}';
        }



        return $result;
    }
} 