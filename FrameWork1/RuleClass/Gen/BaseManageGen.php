<?php

/**
 * 后台Gen的基类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen
 * @author zhangchi
 */
class BaseManageGen extends BaseGen
{
    const PUBLISH_PATH = "h";

    /**
     * 替换新增时，模板里的{field}值为空，包括后台模板和模板数据库中的模板
     * @param string $tempContent 要处理的模板内容
     * @param array $arrField 字段数组
     */
    protected function ReplaceWhenCreate(&$tempContent, $arrField)
    {
        if (count($arrField) > 0) {
            for ($i = 0; $i < count($arrField); $i++) {
                $tempContent = str_ireplace("{" . $arrField[$i]['Field'] . "}", '', $tempContent);
                $tempContent = str_ireplace("{b_" . $arrField[$i]['Field'] . "}", '', $tempContent);
            }
        }
    }

    /**
     * 发布频道
     * @param int $channelId 频道id
     * @return int 发布结果
     */
    protected function PublishChannel($channelId){
        $result = -1;

        if($channelId>0){
            /******************** 发布方式说明 ************************
             * 1.多节点联动发布
             * 2.优先级为Rank越高越优先
             * 3.取消了模板类型，用发布方式完全代替了模板类型
             *
            *********************************************************/
            $channelManageData = new ChannelManageData();
            $channelTemplateManageData = new ChannelTemplateManageData();
            $rank = $channelManageData->GetRank($channelId, false);
            $nowChannelId = $channelId;
            //循环Rank进行发布
            while($rank>=0){
                $arrChannelTemplateList = $channelTemplateManageData->GetListForPublish($nowChannelId);
                if(!empty($arrChannelTemplateList)){
                    for($i = 0; $i < Count($arrChannelTemplateList); $i++){
                        //1.取得模板数据
                        $channelTemplateId = $arrChannelTemplateList[$i]["ChannelTemplateId"];
                        $channelTemplateContent = $arrChannelTemplateList[$i]["ChannelTemplateContent"];
                        $publishType = $arrChannelTemplateList[$i]["PublishType"];
                        $publishFileName = $arrChannelTemplateList[$i]["PublishFileName"];
                        //2.根据PublishType和PublishFileName生成目标文件
                        switch($publishType){
                            case ChannelTemplateManageData::PUBLISH_TYPE_LINKAGE_ONLY_SELF:
                                //联动发布，只发布在本频道下
                                self::ReplaceAndTransfer($nowChannelId, $channelTemplateContent, $publishFileName);
                                break;
                            case ChannelTemplateManageData::PUBLISH_TYPE_LINKAGE_ONLY_TRIGGER:
                                self::ReplaceAndTransfer($channelId, $channelTemplateContent, $publishFileName);
                                break;
                            case ChannelTemplateManageData::PUBLISH_TYPE_LINKAGE_ALL:
                                self::ReplaceAndTransfer($nowChannelId, $channelTemplateContent, $publishFileName);
                                self::ReplaceAndTransfer($channelId, $channelTemplateContent, $publishFileName);
                                break;
                            case ChannelTemplateManageData::PUBLISH_TYPE_ONLY_SELF:
                                self::ReplaceAndTransfer($channelId, $channelTemplateContent, $publishFileName);
                                break;
                        }

                    }

                }
                $nowChannelId = $channelManageData->GetParentChannelId($nowChannelId, false);

                $rank--;
            }
        }

        return $result;
    }

    /**
     * 替换和传输内容到目标路径
     * @param int $channelId 频道id
     * @param string $channelTemplateContent 模板内容
     * @param string $publishFileName 发布文件名
     */
    private function ReplaceAndTransfer($channelId, $channelTemplateContent, $publishFileName){
        /** 1.替换模板内容 */

        $arrCustomTags = Template::GetAllCustomTag($channelTemplateContent);

        if(count($arrCustomTags)>0){
            $arrTempContents = $arrCustomTags[0];

            foreach($arrTempContents as $tagContent){
                //标签id channel_1 document_news_1
                $tagId = Template::GetParamValue($tagContent, "id");
                //标签类型 channel_list,document_news_list
                $tagType = Template::GetParamValue($tagContent, "type");
                //标签排序方式
                $tagOrder = Template::GetParamValue($tagContent, "order");
                //标签特殊查询条件
                $tagWhere = Template::GetParamValue($tagContent, "where");
                //显示条数
                $tagTopCount = Template::GetParamValue($tagContent, "top");
                //显示状态
                $state = Template::GetParamValue($tagContent, "state");

                switch($tagType){

                    case Template::TAG_TYPE_CHANNEL_LIST :

                        break;
                    case Template::TAG_TYPE_DOCUMENT_NEWS_LIST :

                        break;


                }


            }



        }

        //print_r($arrCustomTags);



        /** 2.推送文件到目标路径 */
    }


    protected function CancelPublishChannel(){

    }

    protected function PublishDocumentNews($documentNewsId, FtpQueueManageData $ftpQueueManageData, $executeFtp, $publishChannel){

    }

    /**
     * 取消资讯的发布（删除所有发布的文件）
     * @param int $documentNewsId 资讯id
     * @return
     */
    protected function CancelPublishDocumentNews($documentNewsId){
        //从发布或已否状态改为已否状态，从FTP上删除文件及相关附件，重新发布相关频道
        //第1步，从FTP删除文档
        $publishDate = $documentNewsManageData->GetPublishDate($documentNewsId, false);
        $documentNewsContent = $documentNewsManageData->GetDocumentNewsContent($documentNewsId, false);
        $datePath = Format::DateStringToSimple($publishDate);
        $publishFileName = $documentNewsId . '.html';
        $channelManageData = new ChannelManageData();
        $rank = $channelManageData->GetRank($channelId, false);
        $publishPath = parent::GetPublishPath($documentChannelId, $rank);
        $hasftp = $documentChannelManageData->GetHasFtp($documentChannelId);
        $ftptype = 0; //HTML和相关CSS,IMAGE
        $despath = $publishPath . $datePath . DIRECTORY_SEPARATOR . $publishFileName;

        $isDel = parent::DelFtp($despath, $documentChannelId, $hasftp, $ftptype);

//有详细页面分页的，循环删除各个分页页面
        $arrnewscontent = explode("<!-- pagebreak -->", $documentNewsContent);
        if (count($arrnewscontent) > 0) { //有分页的内容
            for ($cp = 0; $cp < count($arrnewscontent); $cp++) {
                $publishFileName = $documentNewsId . '_' . ($cp + 1) . '.html';
                $despath = "/" . $publishPath . $datePath . '/' . $publishFileName;
                parent::DelFtp($despath, $documentChannelId, $hasftp, $ftptype);
            }
        }
//第2步，从FTP删除上传文件
        $ftptype = 0; //HTML和相关CSS,IMAGE
        $uploadFileData = new UploadFileData();
        $tabletype = 1; //docnews
        $arrfiles = $uploadFileData->GetList($documentNewsId, $tabletype);
//取得相关的附件文件
        if (count($arrfiles) > 0) {
            for ($i = 0; $i < count($arrfiles); $i++) {
                $uploadFileName = $arrfiles[$i]["UploadFileName"];
                $uploadFilePath = $arrfiles[$i]["UploadFilePath"];
                parent::DelFtp($uploadFilePath . $uploadFileName, $documentChannelId, $hasftp, $ftptype);
            }
        }
//联动发布所在频道和上级频道
        $documentChannelGen = new DocumentChannelGen();
        $documentChannelGen->PublishMuti($documentChannelId);
/////////////////////////////////////////////////////////////
////////////////xunsearch全文检索引擎 索引更新///////////////
/////////////////////////////////////////////////////////////
//                global $xunfile;
//                if (file_exists($xunfile)) {
//                    require_once $xunfile;
//                    try {
//                        $xs = new XS('icms');
//                        $index = $xs->index;
//                        $index->del($documentnewsid);
//                        $index->flush();
//                    } catch (XSException $e) {
//                        $error = strval($e);
//                    }
//                }
        /////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////
    }

}

?>
