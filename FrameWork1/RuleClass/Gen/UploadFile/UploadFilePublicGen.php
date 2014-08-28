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

} 