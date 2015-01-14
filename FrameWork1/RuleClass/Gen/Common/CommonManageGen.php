<?php
/**
 * 公共 后台 生成类
 * @category iCMS
 * @package iCMS_Rules_Gen_Comment
 * @author yin
 */
class CommonManageGen extends BaseManageGen implements IBaseManageGen {

    public function Gen(){
        $result = "";
        $method = Control::GetRequest("m","");

        switch($method){
            case "batch_publish":
                $result = self::GenBatchPublish();
                break;

        }
        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }


    private function GenBatchPublish(){

        $templateContent = Template::Load("common/common_publish.html","common");;

        $siteId = Control::GetRequest("site_id", 0);
        $do = Control::GetRequest("do", 0);
        $manageUserId = Control::GetManageUserId();
        $resultJavaScript = "";
        $result = "";

        parent::ReplaceFirst($templateContent);

        if ($manageUserId > 0 && $siteId>0 && $do>0){

            $publishType = Control::GetRequest("publish_type", 0);

            if($publishType == 1){ //发布站点下所有频道

                $publishQueueManageData = new PublishQueueManageData();

                $channelManageData = new ChannelManageData();
                $topCount = 1000;
                $arrChannelList = $channelManageData->GetListBySiteId($siteId, $topCount);

                for($i=0;$i<count($arrChannelList);$i++){

                    $channelId = intval($arrChannelList[$i]["ChannelId"]);
                    if($channelId>0){
                        $executeTransfer = false; //不分别执行传送
                        parent::PublishChannel($channelId, $publishQueueManageData, $executeTransfer);
                    }
                }

                //执行传输
                parent::TransferPublishQueue($publishQueueManageData, $siteId);
                $result = '';
                for ($i = 0;$i< count($publishQueueManageData->Queue); $i++) {

                    $publishResult = "";

                    if(intval($publishQueueManageData->Queue[$i]["Result"]) ==
                        abs(DefineCode::PUBLISH) + BaseManageGen::PUBLISH_TRANSFER_RESULT_SUCCESS
                    ){
                        $publishResult = "Ok";
                    }


                    $result .= $publishQueueManageData->Queue[$i]["DestinationPath"].' -> '.$publishResult
                        .'<br />'
                    ;
                }



            }
        }

        parent::ReplaceEnd($templateContent);

        $templateContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $templateContent);
        $templateContent = str_ireplace("{Result}", $result, $templateContent);

        return $templateContent;

    }
} 