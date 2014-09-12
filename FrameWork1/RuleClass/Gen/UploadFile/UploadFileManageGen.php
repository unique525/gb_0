<?php

/**
 * 公共 上传文件 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_UploadFile
 * @author zhangchi
 */
class UploadFileManageGen extends BaseManageGen implements IBaseManageGen
{
    public function Gen(){
        $result = "";
        $method = Control::GetRequest("m","");
        switch($method){
            case "modify":
                $result = self::GenModify();
                break;
            case "modify_by_table_id":
                $result = self::GenModifyByTableId();
                break;
        }
        return $result;
    }

    private function GenModify(){
        $templateContent = Template::Load("upload/upload_deal.html");
        return $templateContent;
    }

    private function GenModifyByTableId(){
        $tableId = Control::GetRequest("table_id",0);
        $tableType = Control::GetRequest("table_type",0);
        if($tableId > 0 && $tableType > 0){
            $templateContent = Template::Load("upload/upload_deal_by_table_id.html");

            $uploadFileManageData = new UploadFileManagedData();
            return $templateContent;
        }else{
            return null;
        }
    }
} 