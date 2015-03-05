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
        $method = Control::GetRequest("m","",false);
        switch($method){
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

    private function GenBatchModify(){
        //$result = -1;

        $do = intval(Control::GetRequest("do", 0));

        if ($do > 0) {




        }else{

            $url = $_SERVER["PHP_SELF"];

            //echo '正在操作，请不要关闭本窗口<br /><br />';

            $uploadFileManageData = new UploadFileManageData();

            $tableType = Control::GetRequest("table_type", 0);
            $topCount = Control::GetRequest("top_count", 10);

            $arrList = $uploadFileManageData->GetListOfIsNotBatchOperate($tableType, $topCount);

            if(count($arrList)>0){
                for($i = 0;$i < count($arrList); $i++){

                    $uploadFileId = intval($arrList[$i]["UploadFileId"]);

                    $mobileWidth = 640;

                    parent::GenUploadFileMobile($uploadFileId, $mobileWidth);

                    parent::GenUploadFileCompress1($uploadFileId, $mobileWidth,0, 80);

                    $uploadFileManageData->ModifyIsBatchOperate($uploadFileId, 1);

                    //echo $uploadFileId . ' : Gen UploadFile Mobile Done <br />';

                }
                header('refresh:0 ' . $url);
            }else{
                echo "任务已完成";
            }







        }



        //return $result;
    }


    /**
     * 异步修改图片对应附件的标题
     */
    private function AsyncModifyUploadFileTitle(){
        $result=0;
        $uploadFileId=Control::PostRequest("UploadFileId",0);
        if($uploadFileId>0){

            /*********************
             * 查看是否有修改权限 *
             *********************/
            $canModify=0;
            $channelManageData=new ChannelManageData();
            $manageUserAuthorityManageData=new ManageUserAuthorityManageData();
            $uploadFileManageData=new UploadFileManageData();

            $arrayTableInfo=$uploadFileManageData->GetTableTypeAndTableId($uploadFileId);
            $manageUserId=Control::GetManageUserId();
            if($arrayTableInfo["TableType"]==UploadFileData::UPLOAD_TABLE_TYPE_DOCUMENT_NEWS_CONTENT){ //文章内容图片
                $channelId=$arrayTableInfo["TableId"];
                $siteId=$channelManageData->GetSiteId($channelId,true);
                $canModify = $manageUserAuthorityManageData->CanChannelModify($siteId, $channelId, $manageUserId);
            }


            if (!$canModify) {
                die(Language::Load('channel', 4));
            }

            $uploadFileTitle=$_POST["UploadFileTitle"];
            $result=$uploadFileManageData->ModifyUploadFileTitle($uploadFileId,$uploadFileTitle);

        }
        return $result;
    }
} 