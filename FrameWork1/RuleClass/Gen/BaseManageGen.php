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

            /**************** 传输日志 ********************/
            $publishLogManageData = new PublishLogManageData();
            $publishLogManageData->Create(
                PublishLogManageData::PUBLISH_TYPE_NO_DEFINE,
                PublishLogManageData::TABLE_TYPE_CHANNEL,
                $channelId,
                "",
                "",
                0,
                "begin transfer"
                );


            /**************** 取得模板 ********************/
            $channelManageData = new ChannelManageData();
            $channelTemplateManageData = new ChannelTemplateManageData();
            $rank = $channelManageData->GetRank($channelId, false);
            $nowChannelId = $channelId;
            $ftpQueueManageData = new FtpQueueManageData();
            //循环Rank进行发布
            while($rank>=0){
                $timeStart = Control::GetMicroTime();
                $arrChannelTemplateList = $channelTemplateManageData->GetListForPublish($nowChannelId);
                $timeEnd = Control::GetMicroTime();

                $publishLogManageData->Create(
                    PublishLogManageData::PUBLISH_TYPE_NO_DEFINE,
                    PublishLogManageData::TABLE_TYPE_CHANNEL,
                    $channelId,
                    "",
                    "",
                    $timeEnd - $timeStart,
                    "get template list"
                );
//print_r($arrChannelTemplateList);die();
                if(!empty($arrChannelTemplateList)){
                    for($i = 0; $i < Count($arrChannelTemplateList); $i++){
                        //1.取得模板数据

                        $channelTemplateId = $arrChannelTemplateList[$i]["ChannelTemplateId"];
                        $channelTemplateContent = $arrChannelTemplateList[$i]["ChannelTemplateContent"];
                        $publishType = $arrChannelTemplateList[$i]["PublishType"];
                        $publishFileName = $arrChannelTemplateList[$i]["PublishFileName"];

                        //2.替换模板内容
                        $timeStart = Control::GetMicroTime();
                        $channelTemplateContent = self::ReplaceTemplate($channelId, $channelTemplateContent);
                        $timeEnd = Control::GetMicroTime();
                        $publishLogManageData->Create(
                            PublishLogManageData::PUBLISH_TYPE_NO_DEFINE,
                            PublishLogManageData::TABLE_TYPE_CHANNEL,
                            $channelId,
                            "",
                            "",
                            $timeEnd - $timeStart,
                            "now channel id:$nowChannelId replace template"
                        );

                        //3.根据PublishType和PublishFileName生成目标文件
                        switch($publishType){
                            case ChannelTemplateManageData::PUBLISH_TYPE_LINKAGE_ONLY_SELF:
                                //联动发布，只发布在本频道下

                                break;
                            case ChannelTemplateManageData::PUBLISH_TYPE_LINKAGE_ONLY_TRIGGER:
                                //联动发布，只发布在触发频道下，有可能是本频道，也有可能是继承频道
                                //触发频道id $channelId
                                $result = self::TransferTemplate($channelId, $channelTemplateContent, $publishFileName, $ftpQueueManageData);
                                break;
                            case ChannelTemplateManageData::PUBLISH_TYPE_LINKAGE_ALL:
                                //联动发布，发布在所有继承树关系的频道下

                                break;
                            case ChannelTemplateManageData::PUBLISH_TYPE_ONLY_SELF:
                                //非联动发布，只发布在本频道下

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
     * 替换内容
     * @param int $channelId 频道id
     * @param string $channelTemplateContent 模板内容
     * @return mixed|string 内容模板
     */
    private function ReplaceTemplate($channelId, $channelTemplateContent){
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
                $tagTopCount = Format::CheckTopCount($tagTopCount);
                if($tagTopCount == null){

                }
                //显示状态
                $state = Template::GetParamValue($tagContent, "state");

                switch($tagType){
                    case Template::TAG_TYPE_CHANNEL_LIST :
                        $channelId = intval(str_ireplace("channel_", "", $tagId));
                        if($channelId>0){
                            $channelTemplateContent = self::ReplaceTemplateOfChannelList($channelTemplateContent, $channelId, $tagId, $tagContent, $tagTopCount, $tagWhere, $tagOrder);
                        }
                        break;
                    case Template::TAG_TYPE_DOCUMENT_NEWS_LIST :
                        $documentNewsId = intval(str_ireplace("channel_", "", $tagId));
                        if($documentNewsId>0){
                            $channelTemplateContent = self::ReplaceTemplateOfDocumentNewsList($channelTemplateContent, $channelId, $tagId, $tagContent, $tagTopCount, $tagWhere, $tagOrder, $state);
                        }
                        break;
                }
            }
        }
        return $channelTemplateContent;
    }

    /**
     * 替换频道列表的内容
     * @param string $channelTemplateContent 要处理的模板内容
     * @param int $channelId 频道id
     * @param string $tagId 标签id
     * @param string $tagContent 标签内容
     * @param int $tagTopCount 显示条数
     * @param string $tagWhere 查询方式
     * @param string $tagOrder 排序方式
     * @return mixed|string 内容模板
     */
    private function ReplaceTemplateOfChannelList($channelTemplateContent, $channelId, $tagId, $tagContent, $tagTopCount, $tagWhere, $tagOrder){
        if($channelId>0){
            $arrChannelList = null;
            switch ($tagWhere) {
                case "parent":
                    $channelManageData = new ChannelManageData();
                    $arrChannelList = $channelManageData->GetListByParentId($channelId, $tagTopCount, $tagOrder);
                    break;
            }
            if(!empty($arrChannelList)){
                Template::ReplaceList($tagContent, $arrChannelList, $tagId);
                //把对应ID的CMS标记替换成指定内容
                //替换子循环里的<![CDATA[标记
                $tagContent = str_ireplace("[CDATA]", "<![CDATA[", $tagContent);
                $tagContent = str_ireplace("[/CDATA]", "]]>", $tagContent);
                $channelTemplateContent = Template::ReplaceCustomTag($channelTemplateContent, $tagId, $tagContent);
            }
        }

        return $channelTemplateContent;
    }

    /**
     * 替换资讯列表的内容
     * @param string $channelTemplateContent 要处理的模板内容
     * @param int $channelId 频道id
     * @param string $tagId 标签id
     * @param string $tagContent 标签内容
     * @param int $tagTopCount 显示条数
     * @param string $tagWhere 查询方式
     * @param string $tagOrder 排序方式
     * @param int $state 状态
     * @return mixed|string 内容模板
     */
    private function ReplaceTemplateOfDocumentNewsList($channelTemplateContent, $channelId, $tagId, $tagContent, $tagTopCount, $tagWhere, $tagOrder, $state){
        if($channelId>0){
            $arrDocumentNewsList = null;
            $documentNewsManageData = new DocumentNewsManageData();
            switch ($tagWhere) {
                case "new":
                    $arrDocumentNewsList = $documentNewsManageData->GetNewList($channelId, $tagTopCount, $state);
                    break;
                default :
                    //new
                    $arrDocumentNewsList = $documentNewsManageData->GetNewList($channelId, $tagTopCount, $state);
                    break;
            }
            if(!empty($arrDocumentNewsList)){
                Template::ReplaceList($tagContent, $arrDocumentNewsList, $tagId);
                //把对应ID的CMS标记替换成指定内容
                $channelTemplateContent = Template::ReplaceCustomTag($channelTemplateContent, $tagId, $tagContent);
            }
        }

        return $channelTemplateContent;
    }

    /**
     * 发布传输结果：未操作
     */
    const PUBLISH_TRANSFER_RESULT_NO_ACTION = -1;
    /**
     * 发布传输结果：频道id错误
     */
    const PUBLISH_TRANSFER_RESULT_CHANNEL_ID_ERROR = -5;
    /**
     * 发布传输结果：站点id错误
     */
    const PUBLISH_TRANSFER_RESULT_SITE_ID_ERROR = -10;


    private function TransferTemplate($channelId, $channelTemplateContent, $publishFileName, FtpQueueManageData $ftpQueueManageData){
        $result = self::PUBLISH_TRANSFER_RESULT_NO_ACTION;

        if($channelId>0){
            $channelManageData = new ChannelManageData();
            $siteId = $channelManageData->GetSiteId($channelId, false);
            if($siteId>0){
                $destinationPath = self::PUBLISH_PATH . '/' . strval($channelId) . '/' . $publishFileName;
                $sourcePath = '';

                $ftpManageData = new FtpManageData();
                $ftpInfo = $ftpManageData->GetOneBySiteId($siteId);
                //判断是用ftp方式传输还是直接写文件方式传输
                if(!empty($ftpInfo)){ //定义了ftp配置信息，使用ftp方式传输
                    $ftpQueueManageData->Add($destinationPath, $sourcePath, $channelTemplateContent);
                }else{ //没有定义ftp配置信息，使用直接写文件方式传输
                    $result = FileObject::Write($destinationPath, $channelTemplateContent);
                }
            }else{
                $result = self::PUBLISH_TRANSFER_RESULT_SITE_ID_ERROR;
            }
        }else{

            $result = self::PUBLISH_TRANSFER_RESULT_CHANNEL_ID_ERROR;
        }
        return $result;

    }




    protected function CancelPublishChannel(){

    }

    protected function PublishDocumentNews($documentNewsId, FtpQueueManageData $ftpQueueManageData, $executeFtp, $publishChannel){

    }

    protected function CancelPublishDocumentNews($documentNewsId){

    }

}

?>
