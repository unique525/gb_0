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
            case "async_set_wait_publish":
                $result = self::AsyncSetWaitPublish();
                break;
            case "async_cancel_wait_publish":
                $result = self::AsyncCancelWaitPublish();
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

            //检查操作权限
            $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
            $can=$manageUserAuthorityManageData->CanChannelPublish($siteId,0,$manageUserId); //channelId=0:站点发布权限
            if(!$can){
                return -2;
            }

            $publishType = Control::GetRequest("publish_type", 0);

            if($publishType == 1){ //发布站点下所有频道

                $publishQueueManageData = new PublishQueueManageData();

                $documentNewsManageData = new ChannelManageData();
                $topCount = 1000;
                $arrChannelList = $documentNewsManageData->GetListBySiteId($siteId, $topCount);

                for($i=0;$i<count($arrChannelList);$i++){

                    $documentNewsId = intval($arrChannelList[$i]["ChannelId"]);
                    if($documentNewsId>0){
                        $executeTransfer = false; //不分别执行传送
                        parent::PublishChannel($documentNewsId, $publishQueueManageData, $executeTransfer);
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



            }elseif($publishType == 2){ //发布站点下所有资讯文档
                $publishQueueManageData = new PublishQueueManageData();

                $documentNewsManageData = new DocumentNewsManageData();

                $eachTimeCount=10;//每10条发一下
                $arrDocumentNewsList=$documentNewsManageData->GetWaitPublishListOfSiteId($siteId,$eachTimeCount);
                $strDocumentNewsId="";
                for($i=0;$i<count($arrDocumentNewsList);$i++){
                    $strDocumentNewsId.=",".$arrDocumentNewsList[$i]["DocumentNewsId"];
                    $documentNewsId = intval($arrDocumentNewsList[$i]["DocumentNewsId"]);
                    if($documentNewsId>0){
                        $executeTransfer = false; //不分别执行传送
                        parent::PublishDocumentNews($documentNewsId, $publishQueueManageData, $executeTransfer);
                    }
                }

                //执行传输
                parent::TransferPublishQueue($publishQueueManageData, $siteId);
                $result = '';
                $error='';
                for ($i = 0;$i< count($publishQueueManageData->Queue); $i++) {

                    $publishResult = "";

                    if(intval($publishQueueManageData->Queue[$i]["Result"]) ==
                        abs(DefineCode::PUBLISH) + BaseManageGen::PUBLISH_TRANSFER_RESULT_SUCCESS
                    ){
                        $publishResult = "Ok";
                        $result .= $publishQueueManageData->Queue[$i]["DestinationPath"].' -> '.$publishResult
                            .'<br />'
                        ;
                    }else{
                        $publishResult = "<span style='color:red'>ERROR</span>";
                        $error .= $publishQueueManageData->Queue[$i]["DestinationPath"].' -> '.$publishResult
                            .'<br />'
                        ;
                    }
                }
                $strDocumentNewsId=substr($strDocumentNewsId,1);
                $waitPublish=0; //还原状态
                $state=-1;  //所有状态
                $documentNewsManageData->UpdateWaitPublishForSite($waitPublish,$siteId,$state,$strDocumentNewsId);
                $restCount=$documentNewsManageData->GetCountOfWaitPublishList($siteId);
                $result="还剩下".$restCount."条<br>".$result;
                $url = $_SERVER["PHP_SELF"] . "?" . $_SERVER['QUERY_STRING'];
                if($error!=''){
                    $error.='<a href="'.$url.'" style="color: rgb(82, 89, 107); cursor: pointer;">[继续发布]</a>';
                    $templateContent = str_ireplace("{Result}", $error, $templateContent);
                }else{
                    $templateContent = str_ireplace("{Result}", $result, $templateContent);
                    if($restCount>0)
                        header('refresh:0 ' . $url);
                }

            }
        }

        parent::ReplaceEnd($templateContent);

        $templateContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $templateContent);
        $templateContent = str_ireplace("{Result}", $result, $templateContent);

        return $templateContent;

    }

    /***
     * 异步设置批量发布状态
     * @return int|string
     */
    public function AsyncSetWaitPublish(){
        $manageUserId = Control::GetManageUserId();
        $siteId = Control::GetRequest("site_id", 0);
        $state = Control::PostRequest("State",30);//默认标记已发

        //检查操作权限
        $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
        $can=$manageUserAuthorityManageData->CanChannelPublish($siteId,0,$manageUserId); //channelId=0:站点发布权限
        if(!$can){
            return -2;
        }

        $documentNewsManageData = new DocumentNewsManageData();
        $waitPublish=1; //等待发布状态
        $result=$documentNewsManageData->UpdateWaitPublishForSite($waitPublish,$siteId,$state); //全设为1
        $result=json_encode($result);
        return $result;
    }


    /***
     * 异步取消批量发布状态
     * @return int|string
     */
    public function AsyncCancelWaitPublish(){
        $manageUserId = Control::GetManageUserId();
        $siteId = Control::GetRequest("site_id", 0);

        //检查操作权限
        $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
        $can=$manageUserAuthorityManageData->CanChannelPublish($siteId,0,$manageUserId); //channelId=0:站点发布权限
        if(!$can){
            return -2;
        }

        $documentNewsManageData = new DocumentNewsManageData();
        $waitPublish=0; //取消
        $result=$documentNewsManageData->UpdateWaitPublishForSite($waitPublish,$siteId);
        $result=json_encode($result);
        return $result;
    }
} 