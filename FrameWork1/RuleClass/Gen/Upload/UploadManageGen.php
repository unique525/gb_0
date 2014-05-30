<?php

/**
 * 后台管理 上传文件 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Upload
 * @author zhangchi
 */
class UploadManageGen extends BaseManageGen implements IBaseManageGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function Gen() {
        $result = "";
        $method = Control::GetRequest("m", "");
        switch ($method) {
            case "async_upload":
                $result = self::AsyncUpload();
                break;
        }

        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    /**
     * 异步上传
     */
    private function AsyncUpload(){
        $fileElementName = Control::GetRequest("file_element_name", "file_upload");
        $tableType = Control::GetRequest("table_type", 0);
        $tableId = Control::GetRequest("table_id", 0);
        $returnJson = "";
        $uploadFileId = 0;
        parent::Upload($fileElementName,$tableType,$tableId,$returnJson,$uploadFileId);
        return $returnJson;
    }
} 