<?php

/**
 * 后台Gen的基类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen
 * @author zhangchi
 */
class BaseManageGen extends BaseGen
{
    /**
     * 系统静态页面的根目录
     */
    const PUBLISH_PATH = "h";

    /**
     * 系统广告的根目录
     */
    const SITE_AD_PATH = "ad";

    /**
     * 发布频道 返回值 未操作
     */
    const PUBLISH_CHANNEL_RESULT_NO_ACTION = -101;
    /**
     * 发布频道 返回值 频道id小于0
     */
    const PUBLISH_CHANNEL_RESULT_CHANNEL_ID_ERROR = -102;
    /**
     * 发布频道 返回值 操作完成，结果存储于结果数组中
     */
    const PUBLISH_CHANNEL_RESULT_FINISHED = 101;


    /**
     * 加入发布队列 返回值 未操作
     */
    const ADD_TO_PUBLISH_QUEUE_RESULT_NO_ACTION = -105;
    /**
     * 加入发布队列 返回值 频道id错误
     */
    const ADD_TO_PUBLISH_QUEUE_RESULT_CHANNEL_ID_ERROR = -106;
    /**
     * 加入发布队列 返回值 操作完成
     */
    const ADD_TO_PUBLISH_QUEUE_RESULT_FINISHED = 107;


    /**
     * 发布传输结果：未操作
     */
    const PUBLISH_TRANSFER_RESULT_NO_ACTION = -110;
    /**
     * 发布传输结果：频道id错误
     */
    const PUBLISH_TRANSFER_RESULT_CHANNEL_ID_ERROR = -111;
    /**
     * 发布传输结果：站点id错误
     */
    const PUBLISH_TRANSFER_RESULT_SITE_ID_ERROR = -112;
    /**
     * 发布传输结果：传输成功
     */
    const PUBLISH_TRANSFER_RESULT_SUCCESS = 110;


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
     * @param PublishQueueManageData $publishQueueManageData 发布队列对象，传出参数，包括了发布文件的结果值
     * @return int 发布结果
     */
    protected function PublishChannel($channelId, PublishQueueManageData &$publishQueueManageData)
    {
        //$result = self::PUBLISH_CHANNEL_RESULT_NO_ACTION;

        if ($channelId > 0) {
            /******************** 发布方式说明 ************************
             * 1.多节点联动发布
             * 2.优先级为Rank越高越优先
             * 3.模板类型改成了普通模板和各模块的详细模板，用发布方式代替了部分模板类型
             *
             *********************************************************/

            /**************** 传输日志 ********************/
            $publishLogManageData = new PublishLogManageData();
            $publishLogManageData->Create(
                PublishLogManageData::TRANSFER_TYPE_NO_DEFINE,
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
            $siteId = $channelManageData->GetSiteId($channelId, false);
            $currentChannelId = $channelId;
            $currentRank = $rank;

            $arrChannelIds = array();

            //循环Rank进行发布
            while ($rank >= 0) {

                $timeStart = Control::GetMicroTime();
                $arrChannelTemplateList = $channelTemplateManageData->GetListForPublish($currentChannelId);
                $timeEnd = Control::GetMicroTime();

                $publishLogManageData->Create(
                    PublishLogManageData::TRANSFER_TYPE_NO_DEFINE,
                    PublishLogManageData::TABLE_TYPE_CHANNEL,
                    $channelId,
                    "",
                    "",
                    $timeEnd - $timeStart,
                    "get template list"
                );


                //把频道id放入继承树数组，为发布在所有继承树关系下使用
                $arrChannelIds[$rank]["ChannelId"] = $currentChannelId;
                $arrChannelIds[$rank]["Rank"] = $rank;


                if (!empty($arrChannelTemplateList)) {
                    for ($i = 0; $i < count($arrChannelTemplateList); $i++) {
                        //1.取得模板数据
                        //$channelTemplateId = $arrChannelTemplateList[$i]["ChannelTemplateId"];
                        $channelTemplateContent = $arrChannelTemplateList[$i]["ChannelTemplateContent"];
                        $publishType = $arrChannelTemplateList[$i]["PublishType"];
                        $publishFileName = $arrChannelTemplateList[$i]["PublishFileName"];

                        //2.替换模板内容
                        $timeStart = Control::GetMicroTime();
                        $channelTemplateContent = self::ReplaceTemplate($channelId, $channelTemplateContent);
                        $timeEnd = Control::GetMicroTime();
                        $publishLogManageData->Create(
                            PublishLogManageData::TRANSFER_TYPE_NO_DEFINE,
                            PublishLogManageData::TABLE_TYPE_CHANNEL,
                            $channelId,
                            "",
                            "",
                            $timeEnd - $timeStart,
                            "now channel id:$currentChannelId replace template"
                        );

                        //3.根据PublishType和PublishFileName生成目标文件
                        switch ($publishType) {
                            case ChannelTemplateData::PUBLISH_TYPE_LINKAGE_ONLY_SELF:
                                //联动发布，只发布在本频道下(模板所属频道)
                                //本频道id $currentChannelId

                                $timeStart = Control::GetMicroTime();
                                $result = self::AddToPublishQueueForChannelTemplate(
                                    $currentChannelId,
                                    $rank,
                                    $channelTemplateContent,
                                    $publishType,
                                    $publishFileName,
                                    $publishQueueManageData
                                );
                                $timeEnd = Control::GetMicroTime();
                                $publishLogManageData->Create(
                                    PublishLogManageData::TRANSFER_TYPE_NO_DEFINE,
                                    PublishLogManageData::TABLE_TYPE_CHANNEL,
                                    $currentChannelId,
                                    "",
                                    "",
                                    $timeEnd - $timeStart,
                                    "now channel id:$currentChannelId add to publish queue result:$result"
                                );
                                break;
                            case ChannelTemplateData::PUBLISH_TYPE_LINKAGE_ONLY_TRIGGER:
                                //联动发布，只发布在触发频道下，有可能是本频道，也有可能是继承频道
                                //触发频道id $channelId

                                $timeStart = Control::GetMicroTime();
                                $result = self::AddToPublishQueueForChannelTemplate(
                                    $channelId,
                                    $currentRank,
                                    $channelTemplateContent,
                                    $publishType,
                                    $publishFileName,
                                    $publishQueueManageData
                                );
                                $timeEnd = Control::GetMicroTime();
                                $publishLogManageData->Create(
                                    PublishLogManageData::TRANSFER_TYPE_NO_DEFINE,
                                    PublishLogManageData::TABLE_TYPE_CHANNEL,
                                    $channelId,
                                    "",
                                    "",
                                    $timeEnd - $timeStart,
                                    "now channel id:$currentChannelId add to publish queue result:$result"
                                );
                                break;
                            case ChannelTemplateData::PUBLISH_TYPE_LINKAGE_ALL:
                                //联动发布，发布在所有继承树关系的频道下

                                for($x = 0; $x < count($arrChannelIds);$x++){

                                    $timeStart = Control::GetMicroTime();
                                    $result = self::AddToPublishQueueForChannelTemplate(
                                        intval($arrChannelIds[$x]["ChannelId"]),
                                        intval($arrChannelIds[$x]["Rank"]),
                                        $channelTemplateContent,
                                        $publishType,
                                        $publishFileName,
                                        $publishQueueManageData
                                    );
                                    $timeEnd = Control::GetMicroTime();
                                    $publishLogManageData->Create(
                                        PublishLogManageData::TRANSFER_TYPE_NO_DEFINE,
                                        PublishLogManageData::TABLE_TYPE_CHANNEL,
                                        $arrChannelIds[$x],
                                        "",
                                        "",
                                        $timeEnd - $timeStart,
                                        "now channel id:$currentChannelId add to publish queue result:$result"
                                    );
                                }



                                break;
                            case ChannelTemplateData::PUBLISH_TYPE_ONLY_SELF:
                                //非联动发布，只发布在本频道下
                                //触发频道与当前频道一致时才处理

                                if($channelId == $currentChannelId){

                                    $timeStart = Control::GetMicroTime();
                                    $result = self::AddToPublishQueueForChannelTemplate(
                                        $currentChannelId,
                                        $rank,
                                        $channelTemplateContent,
                                        $publishType,
                                        $publishFileName,
                                        $publishQueueManageData
                                    );
                                    $timeEnd = Control::GetMicroTime();
                                    $publishLogManageData->Create(
                                        PublishLogManageData::TRANSFER_TYPE_NO_DEFINE,
                                        PublishLogManageData::TABLE_TYPE_CHANNEL,
                                        $currentChannelId,
                                        "",
                                        "",
                                        $timeEnd - $timeStart,
                                        "now channel id:$currentChannelId add to publish queue result:$result"
                                    );

                                }


                                break;
                        }
                    }
                }



                $currentChannelId = $channelManageData->GetParentChannelId($currentChannelId, false);

                $rank--;
            }

            $timeStart = Control::GetMicroTime();
            self::TransferPublishQueue($publishQueueManageData, $siteId);
            $timeEnd = Control::GetMicroTime();
            $publishLogManageData->Create(
                PublishLogManageData::TRANSFER_TYPE_NO_DEFINE,
                PublishLogManageData::TABLE_TYPE_CHANNEL,
                $channelId,
                "",
                "",
                $timeEnd - $timeStart,
                "now channel id:$currentChannelId transfer publish queue"
            );
            $result = abs(DefineCode::PUBLISH) + self::PUBLISH_CHANNEL_RESULT_FINISHED;
        } else {
            $result = DefineCode::PUBLISH + self::PUBLISH_CHANNEL_RESULT_CHANNEL_ID_ERROR;
        }

        return $result;
    }

    /**
     * 替换内容
     * @param int $siteId 频道id
     * @param string $channelTemplateContent 模板内容
     * @return mixed|string 内容模板
     */
    private function ReplaceTemplate($siteId, $channelTemplateContent)
    {
        /** 1.处理预加载模板 */



        /** 2.替换模板内容 */
        $arrCustomTags = Template::GetAllCustomTag($channelTemplateContent);
        if (count($arrCustomTags) > 0) {
            $arrTempContents = $arrCustomTags[0];

            foreach ($arrTempContents as $tagContent) {
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
                if ($tagTopCount == null) {

                }
                //显示状态
                $state = Template::GetParamValue($tagContent, "state");

                switch ($tagType) {
                    case Template::TAG_TYPE_CHANNEL_LIST :
                        $siteId = intval(str_ireplace("site_", "", $tagId));
                        if ($siteId > 0) {
                            $channelTemplateContent = self::ReplaceTemplateOfChannelList(
                                $channelTemplateContent,
                                $siteId,
                                $tagId,
                                $tagContent,
                                $tagTopCount,
                                $tagWhere,
                                $tagOrder
                            );
                        }
                        break;
                    case Template::TAG_TYPE_DOCUMENT_NEWS_LIST :
                        $channelId = intval(str_ireplace("channel_", "", $tagId));
                        if ($channelId > 0) {
                            $channelTemplateContent = self::ReplaceTemplateOfDocumentNewsList(
                                $channelTemplateContent,
                                $channelId,
                                $tagId,
                                $tagContent,
                                $tagTopCount,
                                $tagWhere,
                                $tagOrder,
                                $state
                            );
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
     * @param int $siteId 站点id
     * @param string $tagId 标签id
     * @param string $tagContent 标签内容
     * @param int $tagTopCount 显示条数
     * @param string $tagWhere 查询方式
     * @param string $tagOrder 排序方式
     * @return mixed|string 内容模板
     */
    private function ReplaceTemplateOfChannelList(
        $channelTemplateContent,
        $siteId,
        $tagId,
        $tagContent,
        $tagTopCount,
        $tagWhere,
        $tagOrder
    )
    {
        if ($siteId > 0) {
            $arrChannelList = null;
            switch ($tagWhere) {
                case "parent":
                    $channelManageData = new ChannelManageData();
                    $arrChannelList = $channelManageData->GetListByParentId($siteId, $tagTopCount, $tagOrder);
                    break;
            }
            if (!empty($arrChannelList)) {
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
    private function ReplaceTemplateOfDocumentNewsList(
        $channelTemplateContent,
        $channelId,
        $tagId,
        $tagContent,
        $tagTopCount,
        $tagWhere,
        $tagOrder,
        $state
    )
    {
        if ($channelId > 0) {
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
            if (!empty($arrDocumentNewsList)) {
                Template::ReplaceList($tagContent, $arrDocumentNewsList, $tagId);
                //把对应ID的CMS标记替换成指定内容
                $channelTemplateContent = Template::ReplaceCustomTag($channelTemplateContent, $tagId, $tagContent);
            }
        }

        return $channelTemplateContent;
    }


    /**
     * 加入到发布队列
     * @param int $channelId 频道id
     * @param int $rank 频道级别
     * @param string $publishContent 模板内容
     * @param int $publishType 发布方式
     * @param string $publishFileName 发布文件名
     * @param PublishQueueManageData $publishQueueManageData 发布队列对象
     * @param string $publishPath 发布路径，一般为空，详细页模板才需要使用
     * @return int|number 发布结果
     */
    private function AddToPublishQueueForChannelTemplate(
        $channelId,
        $rank,
        $publishContent,
        $publishType,
        $publishFileName,
        PublishQueueManageData &$publishQueueManageData,
        $publishPath = ''
    )
    {
        //$result = self::ADD_TO_PUBLISH_QUEUE_RESULT_NO_ACTION;

        if ($channelId > 0) {
            switch ($publishType) {

                case ChannelTemplateData::PUBLISH_TYPE_DOCUMENT_NEWS_DETAIL: //资讯详细页模板
                    $destinationPath = self::PUBLISH_PATH . '/' . $publishPath . '/' . $publishFileName;
                    break;
                case ChannelTemplateData::PUBLISH_TYPE_ACTIVITY_DETAIL: //活动详细页模板
                    $destinationPath = self::PUBLISH_PATH . '/' . $publishPath . '/' . $publishFileName;
                    break;
                default: //ChannelTemplateManageData::CHANNEL_TEMPLATE_TYPE_NORMAL 普通模板
                    if ($rank == 0) { //如果是根结节，则不需要拼接h
                        $destinationPath = '/' . $publishFileName;
                    } else {
                        $destinationPath = self::PUBLISH_PATH . '/' . strval($channelId) . '/' . $publishFileName;
                    }

                    break;
            }
            $sourcePath = '';
            $publishQueueManageData->Add($destinationPath, $sourcePath, $publishContent);


            $result = abs(DefineCode::PUBLISH) + self::ADD_TO_PUBLISH_QUEUE_RESULT_FINISHED;
        } else {
            $result = DefineCode::PUBLISH + self::ADD_TO_PUBLISH_QUEUE_RESULT_CHANNEL_ID_ERROR;
        }
        return $result;

    }

    /**
     * 传输发布队列
     * @param PublishQueueManageData $publishQueueManageData 发布队列对象
     * @param int $siteId 站点id
     */
    private function TransferPublishQueue(PublishQueueManageData $publishQueueManageData, $siteId)
    {
        $ftpManageData = new FtpManageData();
        $ftpInfo = $ftpManageData->GetOneBySiteId($siteId);
        //判断是用ftp方式传输还是直接写文件方式传输
        if (!empty($ftpInfo)) { //定义了ftp配置信息，使用ftp方式传输
            $openFtpLog = false;
            $ftpLogManageData = new FtpLogManageData();
            Ftp::UploadQueue($ftpInfo,$publishQueueManageData, $openFtpLog, $ftpLogManageData);

        } else { //没有定义ftp配置信息，使用直接写文件方式传输
            if (!empty($publishQueueManageData->Queue)) {
                for ($i = 0; $i < count($publishQueueManageData->Queue); $i++) {
                    $destinationPath = $publishQueueManageData->Queue[$i]["DestinationPath"];
                    $channelTemplateContent = $publishQueueManageData->Queue[$i]["Content"];
                    $result = FileObject::Write($destinationPath, $channelTemplateContent);
                    if($result>0){ //成功返回成功码
                        $publishQueueManageData->Queue[$i]["Result"] =
                            abs(DefineCode::PUBLISH) + self::PUBLISH_TRANSFER_RESULT_SUCCESS;
                    }else{ //错误则返回FileObject::Write中的错误码
                        $publishQueueManageData->Queue[$i]["Result"] = $result;
                    }
                }
            }
        }
    }


    protected function CancelPublishChannel()
    {

    }


    /**
     * 发布资讯详细页 返回值 资讯id小于0
     */
    const PUBLISH_DOCUMENT_NEWS_RESULT_DOCUMENT_NEWS_ID_ERROR = -201;
    /**
     * 发布资讯详细页 返回值 频道id小于0
     */
    const PUBLISH_DOCUMENT_NEWS_RESULT_CHANNEL_ID_ERROR = -202;
    /**
     * 发布资讯详细页 返回值 状态不正确，必须为终审或已发状态的文档才能发布
     */
    const PUBLISH_DOCUMENT_NEWS_RESULT_STATE_ERROR = -203;

    /**
     * 发布资讯详细页 返回值 操作完成，结果存储于结果数组中
     */
    const PUBLISH_DOCUMENT_NEWS_RESULT_FINISHED = 201;

    /**
     * 发布资讯详细页面
     * @param int $documentNewsId
     * @param PublishQueueManageData $publishQueueManageData
     * @param bool $executeTransfer 是否执行发布
     * @param bool $publishChannel 是否同时发布频道
     * @return int 发布结果
     */
    protected function PublishDocumentNews(
        $documentNewsId,
        PublishQueueManageData $publishQueueManageData,
        $executeTransfer = false,
        $publishChannel = false
    )
    {
        $manageUserId = Control::GetManageUserId();
        if($documentNewsId>0){
            $documentNewsManageData = new DocumentNewsManageData();
            //取得并判断状态
            $state = $documentNewsManageData->GetState($documentNewsId, false);
            if($state === DocumentNewsData::STATE_FINAL_VERIFY || $state === DocumentNewsData::STATE_PUBLISHED){
                /******************** 发布方式说明 ************************
                 * 1.根据PublishType读取详细页模板
                 * 2.进行模板替换
                 * 3.优先级为Rank越高越优先
                 *
                 *********************************************************/

                /**************** 传输日志 ********************/
                $publishLogManageData = new PublishLogManageData();
                $publishLogManageData->Create(
                    PublishLogManageData::TRANSFER_TYPE_NO_DEFINE,
                    PublishLogManageData::TABLE_TYPE_DOCUMENT_NEWS,
                    $documentNewsId,
                    "",
                    "",
                    0,
                    "begin transfer"
                );

                /**************** 取得模板 ********************/

                $channelId = $documentNewsManageData->GetChannelId($documentNewsId, false);

                if($channelId>0){
                    $channelManageData = new ChannelManageData();
                    $channelTemplateManageData = new ChannelTemplateManageData();
                    $rank = $channelManageData->GetRank($channelId, false);
                    $siteId = $channelManageData->GetSiteId($channelId, false);
                    $nowChannelId = $channelId;

                    //循环Rank进行发布
                    while ($rank >= 0) {

                        $timeStart = Control::GetMicroTime();
                        $publishType = ChannelTemplateData::PUBLISH_TYPE_DOCUMENT_NEWS_DETAIL;

                        $arrChannelTemplateList = $channelTemplateManageData->GetListByPublishType($nowChannelId, $publishType);
                        $timeEnd = Control::GetMicroTime();

                        //传输日志 取得模板
                        $publishLogManageData->Create(
                            PublishLogManageData::TRANSFER_TYPE_NO_DEFINE,
                            PublishLogManageData::TABLE_TYPE_DOCUMENT_NEWS,
                            $documentNewsId,
                            "",
                            "",
                            $timeEnd - $timeStart,
                            "get template list"
                        );
                        if (!empty($arrChannelTemplateList)) {
                            for ($i = 0; $i < Count($arrChannelTemplateList); $i++) {
                                //1.取得模板数据

                                //$channelTemplateId = $arrChannelTemplateList[$i]["ChannelTemplateId"];
                                $channelTemplateContent = $arrChannelTemplateList[$i]["ChannelTemplateContent"];
                                //$publishType = $arrChannelTemplateList[$i]["PublishType"];
                                //$publishFileName = $arrChannelTemplateList[$i]["PublishFileName"];

                                //2.替换模板内容
                                $timeStart = Control::GetMicroTime();
                                $channelTemplateContent = self::ReplaceTemplate($channelId, $channelTemplateContent);
                                $timeEnd = Control::GetMicroTime();


                                //传输日志 替换模板
                                $publishLogManageData->Create(
                                    PublishLogManageData::TRANSFER_TYPE_NO_DEFINE,
                                    PublishLogManageData::TABLE_TYPE_DOCUMENT_NEWS,
                                    $documentNewsId,
                                    "",
                                    "",
                                    $timeEnd - $timeStart,
                                    "now document news id id:$documentNewsId replace template"
                                );

                                //3.根据PublishType和PublishFileName生成目标文件
                                //触发频道id $channelId
                                $timeStart = Control::GetMicroTime();

                                //发布文件名，资讯id构成
                                $publishFileName = strval($documentNewsId).'.html';
                                //发布路径，频道id+日期
                                $publishPath = strval($channelId).'/'.strval(date('Ymd', time()));

                                //修改发布时间和发布人，只有发布时间为空时才进行操作
                                $documentNewsManageData->ModifyPublishDate(
                                    $documentNewsId,
                                    date("Y-m-d H:i:s", time()),
                                    $manageUserId
                                );


                                $result = self::AddToPublishQueueForChannelTemplate(
                                    $channelId,
                                    $rank,
                                    $channelTemplateContent,
                                    $publishType,
                                    $publishFileName,
                                    $publishQueueManageData,
                                    $publishPath
                                );

                                $timeEnd = Control::GetMicroTime();
                                $publishLogManageData->Create(
                                    PublishLogManageData::TRANSFER_TYPE_NO_DEFINE,
                                    PublishLogManageData::TABLE_TYPE_DOCUMENT_NEWS,
                                    $documentNewsId,
                                    "",
                                    "",
                                    $timeEnd - $timeStart,
                                    "now document news id id:$documentNewsId add to publish queue result:$result"
                                );
                            }
                        }


                        $nowChannelId = $channelManageData->GetParentChannelId($nowChannelId, false);
                        $rank--;
                    }

                    if($executeTransfer){

                        $timeStart = Control::GetMicroTime();
                        self::TransferPublishQueue($publishQueueManageData, $siteId);
                        $timeEnd = Control::GetMicroTime();
                        $publishLogManageData->Create(
                            PublishLogManageData::TRANSFER_TYPE_NO_DEFINE,
                            PublishLogManageData::TABLE_TYPE_DOCUMENT_NEWS,
                            $documentNewsId,
                            "",
                            "",
                            $timeEnd - $timeStart,
                            "now channel id:$nowChannelId transfer publish queue"
                        );
                    }

                    $result = abs(DefineCode::PUBLISH) + self::PUBLISH_DOCUMENT_NEWS_RESULT_FINISHED;

                    //修改状态
                    $documentNewsManageData->ModifyState($documentNewsId, DocumentNewsData::STATE_PUBLISHED);

                    //同步发布频道
                    if($publishChannel){
                        self::PublishChannel($channelId, $publishQueueManageData);
                    }


                }else{
                    $result = DefineCode::PUBLISH + self::PUBLISH_DOCUMENT_NEWS_RESULT_CHANNEL_ID_ERROR;
                }
            }else{
                $result = DefineCode::PUBLISH + self::PUBLISH_DOCUMENT_NEWS_RESULT_STATE_ERROR;
            }
        }else{
            $result = DefineCode::PUBLISH + self::PUBLISH_DOCUMENT_NEWS_RESULT_DOCUMENT_NEWS_ID_ERROR;
        }

        return $result;

    }

    protected function CancelPublishDocumentNews($documentNewsId)
    {

    }



    /**
     * 发布广告 返回值 广告id小于0
     */
    const PUBLISH_SITE_AD_RESULT_SITE_AD_ID_ERROR = -301;

    /**
     * 发布广告 返回值 站点id小于0
     */
    const PUBLISH_SITE_AD_RESULT_SITE_ID_ERROR = -302;

    /**
     * 发布广告 返回值 操作完成，结果存储于结果数组中
     */
    const PUBLISH_SITE_AD_RESULT_FINISHED = 301;


    /**
     * 发布广告JS
     * @param int $siteAdId
     * @param PublishQueueManageData $publishQueueManageData
     * @param string $warns
     * @return int 发布结果
     */
    protected function PublishSiteAd(
        $siteAdId,
        PublishQueueManageData $publishQueueManageData,
        &$warns
    ){
            $result="";
            if ($siteAdId > 0) {
                $siteAdManageData=new SiteAdManageData();
                $siteId=$siteAdManageData->GetSiteId($siteAdId,FALSE);
                if ($siteId > 0) {
                $siteAdManageData = new SiteAdManageData();
                $arrayOfOneSiteAd = $siteAdManageData->GetOne($siteAdId);
                if (count($arrayOfOneSiteAd) > 0) {
                    if (intval($arrayOfOneSiteAd["State"]) === 0) {
                        $showNumber = intval($arrayOfOneSiteAd["ShowNumber"]);//轮换类广告位是否显示轮换数字
                        $siteAdWidth = intval($arrayOfOneSiteAd["SiteAdWidth"]);
                        $siteAdHeight = intval($arrayOfOneSiteAd["SiteAdHeight"]);
                        $showType = $arrayOfOneSiteAd["ShowType"];     //广告位类型 0:图片,1文字,2轮换 3随机,4落幕
                        $siteAdContentManageData = new SiteAdContentManageData();
                        if ($showType < 0) {
                            $showType = 0;
                        }
                        $listOfSiteAdContentArray = $siteAdContentManageData->GetAllAdContent($siteAdId);//取广告位下所有可用状态广告（启用，未过期）

                        if(count($listOfSiteAdContentArray)<=0){
                            $listOfSiteAdContentArray = $siteAdContentManageData->GetLastAdContent($siteAdId);//没有可用广告 则尝试取最后一条过期广告以防页面空白
                            $warns.=Language::Load('site_ad', 16); //所有广告均已过期！页面将保留最后过期的广告！
                        }

                        if(count($listOfSiteAdContentArray)>0){
                            $siteAdJsContent = Template::Load("site/site_ad_js_type_" . intval($showType) . ".html","common"); //ShowType 0为图片 1文字 2轮换 3随机 4落幕
                            $listName = "site_ad_content";
                            Template::ReplaceList($siteAdJsContent, $listOfSiteAdContentArray, $listName);

                            $replaceArr = array(
                                "{SiteAdId}" => $siteAdId,
                                "{SiteAdWidth}" => $siteAdWidth,
                                "{SiteAdHeight}" => $siteAdHeight,
                                "{ShowType}" => $showType,
                                "{ShowNumber}" => $showNumber
                            );
                            $siteAdJsContent = strtr($siteAdJsContent, $replaceArr);
                            //解jquery与其他$的JS冲突问题
                            $siteAdJsContent = str_ireplace("$", 'jQuery', $siteAdJsContent);

                        }else{
                            $warns.=Language::Load('site_ad', 11); //该广告位没有可用的广告
                            $warns.=Language::Load('site_ad', 13);//广告JS为空，广告将不会显示！
                            $siteAdJsContent="";
                        }


                    }else{
                        $warns.=Language::Load('site_ad', 9);//当前操作对象不是启用状态！
                        $warns.=Language::Load('site_ad', 13);//广告JS为空，广告将不会显示！
                        $siteAdJsContent="";
                    }


                    //生成广告调用JS文件

                    //$tempDir = '/' . '/ad/' . $siteId;
                    //$source = $tempDir . '/site_ad_' . $siteAdId . ".js";
                    //FileObject::CreateDir($tempDir);
                    //FileObject::Write($source, $siteAdJsContent);


                    $siteAdFileName = 'site_ad_' . $siteAdId . ".js";
                    $publishResult=self::AddToPublishQueueForSiteAd(
                        $siteAdId,
                        $siteAdJsContent,
                        $siteAdFileName,
                        $publishQueueManageData
                    );

                    self::TransferPublishQueue($publishQueueManageData, $siteId);

                    if($publishResult>0){
                        $result .= Language::Load('site_ad', 10) ;  //广告JS更新成功!
                        $result .= "<br><br>" . "/ad/" . $siteId . '/site_ad_' . $siteAdId . ".js";
                    }else{
                        $result.=Language::Load('site_ad', 17);//广告JS文件发布失败！
                        $result.="<br>error code:" . $publishResult;
                    }


                    //记入操作log
                    $operateContent = "CreateJs site_ad：SiteAdId：" . $siteAdId .",POST FORM:".implode("|",$_POST).";\r\nResult:". $result;
                    self::CreateManageUserLog($operateContent);

                }else{
                    $result.=Language::Load('site_ad', 8);//获取该条记录数据失败！
                }







                }else{
                $result .= Language::Load('site_ad', 5);//站点siteid错误！;
            }
            }else{
            $result .= Language::Load('site_ad', 6);//广告位site_ad_id错误！;
        }
        return $result;

    }
    /**
     * 加入到发布队列 广告
     * @param int $siteAdId 站点id
     * @param string $publishContent 广告内容
     * @param string $publishFileName 发布文件名
     * @param PublishQueueManageData $publishQueueManageData 发布队列对象
     * @return int|number 发布结果
     */
    private function AddToPublishQueueForSiteAd(
        $siteAdId,
        $publishContent,
        $publishFileName,
        PublishQueueManageData &$publishQueueManageData
    )
    {
        //$result = self::ADD_TO_PUBLISH_QUEUE_RESULT_NO_ACTION;
        if ($siteAdId > 0) {
            $siteAdManageData=new SiteAdManageData();
            $siteId=$siteAdManageData->GetSiteId($siteAdId,FALSE);
            if($siteId > 0){
                $destinationPath = self::SITE_AD_PATH . '/' .strval($siteId) . '/' . $publishFileName;
                $sourcePath = '';
                $publishQueueManageData->Add($destinationPath, $sourcePath, $publishContent);
                $result = abs(DefineCode::PUBLISH) + self::ADD_TO_PUBLISH_QUEUE_RESULT_FINISHED;
            }else{
                $result = DefineCode::PUBLISH + self::PUBLISH_SITE_AD_RESULT_SITE_ID_ERROR;
            }
        } else {
            $result = DefineCode::PUBLISH + self::PUBLISH_SITE_AD_RESULT_SITE_AD_ID_ERROR;
        }
        return $result;

    }
}

?>
