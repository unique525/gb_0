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
    const SITE_AD_PATH = "front_js/site_ad";

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
     * 发布传输结果：删除成功
     */
    const PUBLISH_DELETE_RESULT_SUCCESS = 121;
    /**
     * 发布传输结果：删除失败
     */
    const PUBLISH_DELETE_RESULT_FAILURE = -121;


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
     * @param bool $executeTransfer 是否执行传送（默认执行）
     * @return int 发布结果
     */
    protected function PublishChannel($channelId, PublishQueueManageData &$publishQueueManageData, $executeTransfer = true)
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
            $templateLibraryChannelContentManageData = new TemplateLibraryChannelContentManageData();
            $rank = $channelManageData->GetRank($channelId, true);
            $siteId = $channelManageData->GetSiteId($channelId, true);
            $channelName = $channelManageData->GetChannelName($channelId, true);
            $currentChannelId = $channelId;

            $currentRank = $rank;

            $arrChannelIds = array();

            //循环Rank进行发布
            while ($rank >= 0) {

                $timeStart = Control::GetMicroTime();
                $arrChannelTemplateList = $channelTemplateManageData->GetListForPublish($currentChannelId);

                //处理模板库模板
                $arrTemplateLibraryContentList = $templateLibraryChannelContentManageData->GetListForPublish($currentChannelId);
                $arrChannelTemplateList = array_merge($arrChannelTemplateList, $arrTemplateLibraryContentList);


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
                        $arrChannelTemplateForPlatforms = array(); //不同平台的模板 分别发布

                        $channelTemplateContentForPC = $arrChannelTemplateList[$i]["ChannelTemplateContent"]; //PC
                        $publishFileNameForPC = $arrChannelTemplateList[$i]["PublishFileName"];
                        $arrChannelTemplateForPlatforms["PC"] = array("Content" => $channelTemplateContentForPC, "FileName" => $publishFileNameForPC);

                        $channelTemplateContentForMobile = $arrChannelTemplateList[$i]["ChannelTemplateContentForMobile"]; //Mobile: xx_m.html
                        $publishFileNameForMobile = str_ireplace(".", "_m.", $arrChannelTemplateList[$i]["PublishFileName"]);
                        $arrChannelTemplateForPlatforms["Mobile"] = array("Content" => $channelTemplateContentForMobile, "FileName" => $publishFileNameForMobile);

                        $channelTemplateContentForPad = $arrChannelTemplateList[$i]["ChannelTemplateContentForPad"]; //Pad: xx_p.html
                        $publishFileNameForPad = str_ireplace(".", "_p.", $arrChannelTemplateList[$i]["PublishFileName"]);
                        $arrChannelTemplateForPlatforms["Pad"] = array("Content" => $channelTemplateContentForPad, "FileName" => $publishFileNameForPad);

                        $channelTemplateContentForTV = $arrChannelTemplateList[$i]["ChannelTemplateContentForTV"]; //TV: xx_t.html
                        $publishFileNameForTV = str_ireplace(".", "_t.", $arrChannelTemplateList[$i]["PublishFileName"]);
                        $arrChannelTemplateForPlatforms["TV"] = array("Content" => $channelTemplateContentForTV, "FileName" => $publishFileNameForTV);


                        foreach ($arrChannelTemplateForPlatforms as $onePlatformTemplate) {
                            //1.取得模板数据
                            //$channelTemplateId = $arrChannelTemplateList[$i]["ChannelTemplateId"];
                            $channelTemplateContent = $onePlatformTemplate["Content"];
                            $publishType = $arrChannelTemplateList[$i]["PublishType"];
                            $publishFileName = $onePlatformTemplate["FileName"];


                            if (strlen($channelTemplateContent) > 0) {

                                //2.替换模板内容
                                $timeStart = Control::GetMicroTime();
                                self::ReplaceFirst($channelTemplateContent);

                                $currentChannelName = $channelManageData->GetChannelName($currentChannelId, true);

                                $channelTemplateContent = str_ireplace("{ChannelId}", $channelId, $channelTemplateContent);
                                $channelTemplateContent = str_ireplace("{SiteId}", $siteId, $channelTemplateContent);
                                $channelTemplateContent = str_ireplace("{ChannelName}", $channelName, $channelTemplateContent);
                                $channelTemplateContent = str_ireplace("{CurrentChannelId}", $currentChannelId, $channelTemplateContent);
                                $channelTemplateContent = str_ireplace("{CurrentChannelName}", $currentChannelName, $channelTemplateContent);

                                $channelTemplateContent = self::ReplaceTemplate($channelId, $channelTemplateContent);
                                self::ReplaceEnd($channelTemplateContent);


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

                                        for ($x = 0; $x < count($arrChannelIds); $x++) {

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

                                        if ($channelId == $currentChannelId) {

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
                    }
                }


                $currentChannelId = $channelManageData->GetParentChannelId($currentChannelId, false);

                $rank--;
            }

            if ($executeTransfer) {
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
            }

            $result = abs(DefineCode::PUBLISH) + self::PUBLISH_CHANNEL_RESULT_FINISHED;
        } else {
            $result = DefineCode::PUBLISH + self::PUBLISH_CHANNEL_RESULT_CHANNEL_ID_ERROR;
        }

        return $result;
    }

    /**
     * 替换内容
     * @param int $channelId 频道id
     * @param string $channelTemplateContent 模板内容
     * @return mixed|string 内容模板
     */
    private function ReplaceTemplate($channelId, $channelTemplateContent)
    {
        /** 1.处理预加载模板 */
        self::ReplaceFirst($channelTemplateContent);
        $channelManageData = new ChannelManageData();
        $siteId = $channelManageData->GetSiteId($channelId, true);
        $channelTemplateContent = str_ireplace("{SiteId}", $siteId, $channelTemplateContent);
        parent::ReplaceSiteInfo($siteId, $channelTemplateContent);
        parent::ReplaceChannelInfo($channelId, $channelTemplateContent);

        /** 替换投票调查标签为标准形式 */
        $channelTemplateContent = self::ReplaceVoteTagToCmsTag($channelTemplateContent);
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
                //标签特殊查询条件的值
                $tagWhereValue = Template::GetParamValue($tagContent, "where_value");
                //显示条数
                $tagTopCount = Template::GetParamValue($tagContent, "top");
                $tagTopCount = Format::CheckTopCount($tagTopCount);
                if ($tagTopCount == null) {

                }
                //显示状态
                $state = Template::GetParamValue($tagContent, "state");

                switch ($tagType) {
                    case Template::TAG_TYPE_CHANNEL_LIST :

                        $channelTemplateContent = self::ReplaceTemplateOfChannelList(
                            $channelTemplateContent,
                            $tagId,
                            $tagContent,
                            $tagTopCount,
                            $tagWhere,
                            $tagWhereValue,
                            $tagOrder
                        );

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
                                $tagWhereValue,
                                $tagOrder,
                                $state
                            );
                        }
                        break;
                    case Template::TAG_TYPE_RELATED_DOCUMENT_NEWS_LIST : //相关新闻
                        $searchType = Template::GetParamValue($tagContent, "tag_type"); //调用方式："tag", "main_tag", "all"
                        $documentNewsId = intval(str_ireplace("document_news_", "", $tagId));
                        if ($channelId > 0) {
                            $channelTemplateContent = self::ReplaceTemplateOfRelatedDocumentNewsList(
                                $channelTemplateContent,
                                $documentNewsId,
                                $tagId,
                                $tagContent,
                                $tagTopCount,
                                $tagWhere,
                                $tagOrder,
                                $state,
                                $searchType
                            );
                        }
                        break;
                    case Template::TAG_TYPE_DOCUMENT_NEWS_PIC_LIST :
                        $style = Template::GetParamValue($tagContent, "style_type"); //轮换图样式
                        $documentNewsId = intval(str_ireplace("document_news_", "", $tagId));
                        if ($documentNewsId > 0) {
                            $channelTemplateContent = self::ReplaceTemplateOfDocumentNewsPicList(
                                $channelTemplateContent,
                                $documentNewsId,
                                $tagId,
                                $tagContent,
                                $tagTopCount,
                                $tagWhere,
                                $tagOrder,
                                $style
                            );
                        }
                        break;
                    case Template::TAG_TYPE_PRODUCT_LIST :
                        $channelTemplateContent = self::ReplaceTemplateOfProductList($channelTemplateContent, $tagId, $tagContent, $tagTopCount, $tagWhere, $tagOrder, $state);
                        break;
                    case Template::TAG_TYPE_PIC_SLIDER_LIST :
                        $channelId = intval(str_ireplace("channel_", "", $tagId));
                        if ($channelId > 0) {
                            $channelTemplateContent = self::ReplaceTemplateOfPicSlider(
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
                    case Template::TAG_TYPE_VOTE_ITEM_LIST:
                        $voteId = intval(str_ireplace("vote_", "", $tagId));
                        if ($voteId > 0) {
                            $channelTemplateContent = self::ReplaceTemplateOfVoteItemList(
                                $channelTemplateContent,
                                $voteId,
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
        self::ReplaceEnd($channelTemplateContent);
        return $channelTemplateContent;
    }

    /**
     * 替换频道列表的内容
     * @param string $templateContent 要处理的模板内容
     * @param string $tagId 标签id
     * @param string $tagContent 标签内容
     * @param int $tagTopCount 显示条数
     * @param string $tagWhere 查询方式
     * @param string $tagWhereValue 查询条件的值
     * @param string $tagOrder 排序方式
     * @return mixed|string 内容模板
     */
    private function ReplaceTemplateOfChannelList(
        $templateContent,
        $tagId,
        $tagContent,
        $tagTopCount,
        $tagWhere,
        $tagWhereValue,
        $tagOrder
    )
    {
        $channelId = 0;
        $pos = stripos(strtolower($tagId), "channel_");
        if ($pos !== false) {
            $channelId = intval(str_ireplace("channel_", "", $tagId));
        }

        $siteId = 0;
        $pos = stripos(strtolower($tagId), "site_");
        if ($pos !== false) {
            $siteId = intval(str_ireplace("site_", "", $tagId));
        }


        if ($siteId > 0 || $channelId > 0) {
            $arrChannelList = null;
            $arrChannelChildList = array();
            $arrChannelThirdList = null;
            $arrItemListForChildTag = null;
            $tableIdName = "ChannelId";
            $parentIdName = "ChannelId";
            $thirdTableIdName = "ChannelId";
            $thirdParentIdName = "ParentId";

            switch ($tagWhere) {
                case "parent":

                    if ($tagTopCount <= 0) {
                        $tagTopCount = 100;
                    }
                    $channelManageData = new ChannelManageData();

                    $arrChannelList = $channelManageData->GetListByParentId(
                        $channelId,
                        $tagTopCount,
                        $tagOrder
                    );

                    break;
                case "rank":
                    if ($siteId > 0) {
                        $rank = intval($tagWhereValue);
                        $channelManageData = new ChannelManageData();
                        $arrChannelList = $channelManageData->GetListByRank(
                            $siteId,
                            $tagTopCount,
                            $rank,
                            $tagOrder
                        );
                    }


                    break;
                default:
                    //默认显示三层


                    break;
            }

            /*** 处理子循环 ***/
            if ((Template::GetAllCustomTag($tagContent, "child")) != null) {
                $tagTopCountChild = Template::GetParamValue($tagContent, "top_child"); // top_child="xx"  xx=显示条数
                switch ($tagId) {
                    case "document_news_list":
                        $documentNewsManageData = new DocumentNewsManageData();
                        $state = DocumentNewsData::STATE_PUBLISHED;
                        foreach ($arrChannelList as $oneChannel) {
                            $itemListInOneChannel = $documentNewsManageData->GetNewList($oneChannel["ChannelId"], $tagTopCountChild, $state); //
                            if ($itemListInOneChannel == null) {
                                $itemListInOneChannel = array();
                            }
                            $arrItemListForChildTag = array_merge($arrItemListForChildTag, $itemListInOneChannel);
                        }
                        break;
                    default:
                        $documentNewsManageData = new DocumentNewsManageData();
                        $state = DocumentNewsData::STATE_PUBLISHED;
                        foreach ($arrChannelList as $oneChannel) {
                            $itemListInOneChannel = $documentNewsManageData->GetNewList($oneChannel["ChannelId"], $tagTopCountChild, $state); //
                            if ($itemListInOneChannel == null) {
                                $itemListInOneChannel = array();
                            }
                            $arrItemListForChildTag = array_merge($arrItemListForChildTag, $itemListInOneChannel);
                        }
                        break;
                }
            }


            if (!empty($arrChannelList)) {
                $tagName = Template::DEFAULT_TAG_NAME;
                Template::ReplaceList(
                    $tagContent,
                    $arrChannelList,
                    $tagId,
                    $tagName,
                    $arrChannelChildList,
                    $tableIdName,
                    $parentIdName,
                    $arrChannelThirdList,
                    $thirdTableIdName,
                    $thirdParentIdName
                );
                //把对应ID的CMS标记替换成指定内容
                //替换子循环里的<![CDATA[标记
                $tagContent = str_ireplace("[CDATA]", "<![CDATA[", $tagContent);
                $tagContent = str_ireplace("[/CDATA]", "]]>", $tagContent);
                $templateContent = Template::ReplaceCustomTag($templateContent, $tagId, $tagContent);
            } else {
                $templateContent = Template::ReplaceCustomTag($templateContent, $tagId, '');
            }


        }

        return $templateContent;
    }

    /**
     * 替换资讯列表的内容
     * @param string $channelTemplateContent 要处理的模板内容
     * @param int $channelId 频道id
     * @param string $tagId 标签id
     * @param string $tagContent 标签内容
     * @param int $tagTopCount 显示条数
     * @param string $tagWhere 查询方式
     * @param string $tagWhereValue 查询条件值
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
        $tagWhereValue,
        $tagOrder,
        $state
    )
    {
        if ($channelId > 0) {
            $arrDocumentNewsList = null;
            $documentNewsManageData = new DocumentNewsManageData();

            //排序方式
            switch ($tagOrder) {
                case "new":
                    $orderBy = 0;
                    break;
                default:
                    $orderBy = 0;
                    break;
            }

            $state = DocumentNewsData::STATE_PUBLISHED;

            switch ($tagWhere) {
                case "new":
                    $arrDocumentNewsList = $documentNewsManageData->GetNewList($channelId, $tagTopCount, $state);
                    break;
                case "child":
                    $arrDocumentNewsList = $documentNewsManageData->GetListOfChild($channelId, $tagTopCount, $state, $orderBy);
                    break;
                case "grandson":
                    $arrDocumentNewsList = $documentNewsManageData->GetListOfGrandson($channelId, $tagTopCount, $state, $orderBy);
                    break;
                case "rec_level_child":
                    $arrDocumentNewsList = $documentNewsManageData->GetListOfRecLevelChild($channelId, $tagTopCount, $state, "", $orderBy);
                    break;
                case "rec_level_grandson":
                    $arrDocumentNewsList = $documentNewsManageData->GetListOfRecLevelGrandson($channelId, $tagTopCount, $state, "", $orderBy);
                    break;
                case "rec_level_belong_channel":
                    $recLevel = intval($tagWhereValue);
                    if ($channelId > 0 && $recLevel > 0) {
                        $belongChannelId = self::GetOwnChannelIdAndChildChannelId($channelId);
                        $arrDocumentNewsList = $documentNewsManageData->GetListOfRecLevelBelongChannel($belongChannelId, $recLevel, $tagTopCount, $orderBy);
                    }
                    break;
                case "day_belong_channel":
                    $recLevel = intval($tagWhereValue);
                    if ($channelId > 0 && $recLevel > 0) {
                        $belongChannelId = self::GetOwnChannelIdAndChildChannelId($channelId);
                        $arrDocumentNewsList = $documentNewsManageData->GetListOfDayBelongChannel($belongChannelId, $recLevel, $tagTopCount, $orderBy);
                    }
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
            } else {
                Template::RemoveCustomTag($channelTemplateContent, $tagId);
            }
        }

        return $channelTemplateContent;
    }


    /**
     * 通过tag查找并替换资讯列表的内容（相关新闻）
     * @param string $channelTemplateContent 要处理的模板内容
     * @param int $documentNewsId 文档id
     * @param string $tagId 标签id
     * @param string $tagContent 标签内容
     * @param int $tagTopCount 显示条数
     * @param string $tagWhere 查询方式
     * @param string $tagOrder 排序方式
     * @param int $state 状态
     * @param string $searchType 调用类型  tag,main tag,all
     * @return mixed|string 内容模板
     */
    private function ReplaceTemplateOfRelatedDocumentNewsList(
        $channelTemplateContent,
        $documentNewsId,
        $tagId,
        $tagContent,
        $tagTopCount,
        $tagWhere,
        $tagOrder,
        $state,
        $searchType = "all"
    )
    {
        if ($documentNewsId > 0) {
            $arrDocumentNewsList = null;
            $documentNewsManageData = new DocumentNewsManageData();
            $channelManageData = new ChannelManageData();
            $channelId = $documentNewsManageData->GetChannelId($documentNewsId, TRUE);
            $siteId = $channelManageData->GetSiteId($channelId, TRUE);

            //替换打错的符号或全角符号
            $replaceArray = array('：' => ',', '。' => ',', '.' => ',', '、' => ',', '，' => ',', '、' => ',', '；' => ',', '〃' => '"', '　' => ',', ";" => ",");


            $arrayTags = null;
            $mainTag = "";
            switch ($searchType) {
                case"tag":
                    $strTag = $documentNewsManageData->GetDocumentNewsTag($documentNewsId);
                    if ($strTag != "") {
                        $strTag = strtr($strTag, $replaceArray);
                        $arrayTags = preg_split("/[\s,]+/", $strTag);
                    }
                    break;
                case"main_tag":
                    $mainTag = $documentNewsManageData->GetDocumentNewsMainTag($documentNewsId);
                    break;
                case "all":
                    $strTag = $documentNewsManageData->GetDocumentNewsTag($documentNewsId);
                    if ($strTag != "") {
                        $strTag = strtr($strTag, $replaceArray);
                        $arrayTags = preg_split("/[\s,]+/", $strTag);
                    }
                    $mainTag = $documentNewsManageData->GetDocumentNewsMainTag($documentNewsId);
                    break;
            }



            //排序方式
            switch ($tagOrder) {
                case "new":
                    $orderBy = 0;
                    break;
                default:
                    $orderBy = 0;
                    break;
            }


            $state = DocumentNewsData::STATE_PUBLISHED;


            $arrDocumentNewsList = $documentNewsManageData->GetListOfRelated($mainTag, $arrayTags, $channelId, $siteId, $tagWhere, $tagTopCount, $state, "", $orderBy);

            if (!empty($arrDocumentNewsList)) {
                Template::ReplaceList($tagContent, $arrDocumentNewsList, $tagId);
                //把对应ID的CMS标记替换成指定内容
                $channelTemplateContent = Template::ReplaceCustomTag($channelTemplateContent, $tagId, $tagContent);
            } else {
                Template::RemoveCustomTag($channelTemplateContent, $tagId);
            }
        }

        return $channelTemplateContent;
    }


    /**
     * 替换资讯内容图片控件内容
     * @param string $channelTemplateContent 要处理的模板内容
     * @param int $documentNewsId 频道id
     * @param string $tagId 标签id
     * @param string $tagContent 标签内容
     * @param int $tagTopCount 显示条数
     * @param string $tagWhere 查询方式
     * @param string $tagOrder 排序方式
     * @param string $style 组图控件样式
     * @return mixed|string 内容模板
     */
    private function ReplaceTemplateOfDocumentNewsPicList(
        $channelTemplateContent,
        $documentNewsId,
        $tagId,
        $tagContent = "",
        $tagTopCount = -1,
        $tagWhere = "",
        $tagOrder = "",
        $style = "0"
    )
    {
        if ($documentNewsId > 0) {
            $arrDocumentNewsPicList = null;
            $documentNewsPicManageData = new DocumentNewsPicManageData();
            $showInPicSlider = 1;

            //排序方式
            switch ($tagOrder) {
                case "new":
                    $orderBy = 0;
                    break;
                default:
                    $orderBy = 0;
                    break;
            }


            switch ($tagWhere) {
                default :
                    //new
                    $arrDocumentNewsPicList = $documentNewsPicManageData->GetList($documentNewsId, $tagTopCount, $showInPicSlider); //top count=-1时取所有
                    break;
            }
            if (!empty($arrDocumentNewsPicList)) {
                if ($style == "" || !$style) {
                    $style = "0"; //轮换图样式模板：默认document_news_pic_slider_type_0
                }
                $sliderTypeName = "document_news_pic_slider_type_" . $style;
                $sliderTemplate = "document/" . $sliderTypeName . ".html";
                $tagContent = Template::Load($sliderTemplate, "default", "front_template");
                Template::ReplaceList($tagContent, $arrDocumentNewsPicList, $sliderTypeName);
                //把对应ID的CMS标记替换成指定内容
                $channelTemplateContent = Template::ReplaceCustomTag($channelTemplateContent, $tagId, $tagContent);
            } else Template::RemoveCustomTag($channelTemplateContent, $tagId);
        }

        return $channelTemplateContent;
    }


    /**
     * 替换产品列表的内容
     * @param string $templateContent 要处理的模板内容
     * @param string $tagId 标签id
     * @param string $tagContent 标签内容
     * @param int $tagTopCount 显示条数
     * @param string $tagWhere 查询方式
     * @param string $tagOrder 排序方式
     * @param int $state 状态
     * @return mixed|string 内容模板
     */
    private function ReplaceTemplateOfProductList(
        $templateContent,
        $tagId,
        $tagContent,
        $tagTopCount,
        $tagWhere,
        $tagOrder,
        $state
    )
    {
        $arrProductList = null;
        $productManageData = new ProductManageData();
        switch ($tagWhere) {
            case "DiscountAllChild":
                $channelId = intval(str_ireplace("product_", "", $tagId));
                if ($channelId > 0) {
                    $channelPublicData = new ChannelPublicData();
                    $ChannelRow = $channelPublicData->GetOne($channelId);
                    $ChildChannelId = $ChannelRow['ChildrenChannelId'];
                    $arrProductList = $productManageData->GetDiscountListByChannelId($ChildChannelId, $tagOrder, $tagTopCount);
                }
                break;
            default :
                $channelId = intval(str_ireplace("product_", "", $tagId));
                if ($channelId > 0) {
                    $arrProductList = $productManageData->GetListByChannelId($channelId, $tagOrder, $tagTopCount);
                }
        }
        if (!empty($arrProductList)) {
            Template::ReplaceList($tagContent, $arrProductList, $tagId);
            //把对应ID的CMS标记替换成指定内容
            $templateContent = Template::ReplaceCustomTag($templateContent, $tagId, $tagContent);
        } else Template::RemoveCustomTag($templateContent, $tagId);
        return $templateContent;
    }

    /**
     * 替换图片轮换列表的内容
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
    private function ReplaceTemplateOfPicSlider(
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

            //只显示已审状态
            $state = PicSliderData::STATE_VERIFY;


            $arrList = null;
            $picSliderManageData = new PicSliderManageData();

            //排序方式
            switch ($tagOrder) {
                case "new":
                    $orderBy = 0;
                    break;
                default:
                    $orderBy = 0;
                    break;
            }

            switch ($tagWhere) {
                default :
                    $arrList = $picSliderManageData->GetListForPublish($channelId, $tagTopCount, $state, $orderBy);
                    break;
            }
            if (!empty($arrList)) {
                Template::ReplaceList($tagContent, $arrList, $tagId);
                //把对应ID的CMS标记替换成指定内容
                $channelTemplateContent = Template::ReplaceCustomTag($channelTemplateContent, $tagId, $tagContent);
            } else {
                //替换为空
                $channelTemplateContent = Template::ReplaceCustomTag($channelTemplateContent, $tagId, '');
            }
        }

        return $channelTemplateContent;
    }

    /**
     * 替换投票调查标签为标准的cms标签形式
     * @param string $templateContent 要处理的模板内容
     * @return mixed|string 内容模板
     */
    private function ReplaceVoteTagToCmsTag($templateContent){
        $pattern = "/{icms_vote(.*?)}/ims";
        //调用VoteReplace回调方法处理
        $templateContent = preg_replace_callback($pattern, array(&$this, 'VoteReplace'), $templateContent);
        return $templateContent;
    }

    /**
     * 替换模板中的投票调查标记为标准形式的回调方法
     * @param string $source 被替换字符串
     * @return mixed|string
     */
    private function VoteReplace($source)
    {
        $result = $source[1];
        //替换掉编辑器中可能的&nbsp;为标准空格形式
        $result = str_ireplace("&nbsp;"," ", $result);
        //替换编辑器中&quot;为标准引号形式
        $result = str_ireplace("&quot;","\"", $result);
        $result = "<icms". $result . " type=\"". Template::TAG_TYPE_VOTE_ITEM_LIST ."\" temp_type=\"auto\"></icms>";
        return $result;
    }

    /**
     * 替换投票题目列表内容
     * @param string $templateContent 要处理的模板内容
     * @param string $voteId 投票调查id
     * @param string $tagId 标签id
     * @param string $tagContent 标签内容
     * @param int $tagTopCount 显示条数
     * @param string $tagOrder 排序方式
     * @return mixed|string 内容模板
     */
    private function ReplaceTemplateOfVoteItemList(
        $templateContent,
        $voteId,
        $tagId,
        $tagContent,
        $tagTopCount,
        $tagOrder
    )
    {
        if ($voteId > 0) {
            //投票模板加载类型 auto 则加载定义好的几种模板之一
            $tempType = Template::GetParamValue($tagContent, "temp_type");
            //如果配置为加载默认投票模板
            if ($tempType == "auto") {
                $tempName = Template::GetParamValue($tagContent, "temp_name");
                $voteManageData = new VoteManageData();
                //如果投票标记没有指定模板，则启用数据库配置的模板
                if ($tempName == null) {
                    $tempName = $voteManageData->GetTemplateName($voteId, false);
                    if ($tempName == null || $tempName == '') //如果数据库没有配置模板，默认启用普通模板
                        $tempName = "normal_1";
                }
                //加载对应类型模板
                $templateFileUrl = "vote/vote_front_" . $tempName . ".html";
                $templateName = "default";
                $templatePath = "front_template";
                $voteTemp = Template::Load($templateFileUrl, $templateName, $templatePath);
                $voteTemp = str_ireplace("{VoteId}", $voteId, $voteTemp);
                //根据是否启用验证码，决定是否显示验证码输入选项
                $isCheckCode = $voteManageData->GetIsCheckCode($voteId, false);
                //不启用验证码则隐藏验证码图片
                if ($isCheckCode != 1) {
                    $preg = '/\<div id=\"vote_check_code_class' . $voteId . '\">(.*)\<\/div>/imsU';
                    $voteTemp = preg_replace($preg, '', $voteTemp);
                }
                $templateContent = Template::ReplaceCustomTag($templateContent, $tagId, $voteTemp);
                $tagContent = Template::GetCustomTagByTagId($tagId, $voteTemp);
                $itemWidth = Template::GetParamValue($tagContent, "item_width"); //选项宽
                $itemHeight = Template::GetParamValue($tagContent, "item_height"); //选项高
                $itemMarginLeft = Template::GetParamValue($tagContent, "item_margin_left"); //左边距
                if ($itemMarginLeft == null)
                    $itemMarginLeft = "40px";
                $itemTitleDisplay = Template::GetParamValue($tagContent, "item_title_display"); //是否显示题目标题
                $btnDisplay = Template::GetParamValue($tagContent, "btn_display"); //是否显示投票按钮
                $tagContent = str_ireplace("{ItemWidth}", $itemWidth, $tagContent);
                $tagContent = str_ireplace("{ItemHeight}", $itemHeight, $tagContent);
                $tagContent = str_ireplace("{ItemMarginLeft}", $itemMarginLeft, $tagContent);
                $tagContent = str_ireplace("{ItemTitleDisplay}", $itemTitleDisplay, $tagContent);
                $tagContent = str_ireplace("{BtnDisplay}", $btnDisplay, $tagContent);
            }

            $arrVoteItemList = null;
            $arrVoteSelectItemList = array();
            $tableIdName = "VoteItemId";
            $parentIdName = "VoteItemId";
            $tagTopCount = Format::CheckTopCount($tagTopCount);
            $state = VoteData::STATE_NORMAL;
            $voteItemManageData = new VoteItemManageData();
            $arrVoteItemList = $voteItemManageData->GetList($voteId, $state, $tagOrder, $tagTopCount); //读取投票调查题目

            $sbVoteItemId = '';
            if (count($arrVoteItemList) > 0) {

                for ($i = 0; $i < count($arrVoteItemList); $i++) {
                    $sbVoteItemId .= ',' . $arrVoteItemList[$i]["VoteItemId"];
                }
                if (strpos($sbVoteItemId, ',') == 0) {
                    $sbVoteItemId = substr($sbVoteItemId, 1);
                }
                if (strlen($sbVoteItemId) > 0) {
                    //echo $sbVoteItemId;
                    //二级
                    $voteSelectItemManageData = new VoteSelectItemManageData();
                    $tagTopCountOfSelectItem=null;
                    $arrVoteSelectItemList = $voteSelectItemManageData->GetList(
                        $sbVoteItemId,
                        $state,
                        $tagOrder,
                        $tagTopCountOfSelectItem
                    );
                }
            }

            if (!empty($arrVoteItemList)) {
                $tagName = Template::DEFAULT_TAG_NAME;
                Template::ReplaceList(
                    $tagContent,
                    $arrVoteItemList,
                    $tagId,
                    $tagName,
                    $arrVoteSelectItemList,
                    $tableIdName,
                    $parentIdName
                );
                //把对应ID的CMS标记替换成指定内容
                //替换子循环里的<![CDATA[标记
                $tagContent = str_ireplace("[CDATA]", "<![CDATA[", $tagContent);
                $tagContent = str_ireplace("[/CDATA]", "]]>", $tagContent);
                $templateContent = Template::ReplaceCustomTag($templateContent, $tagId, $tagContent);
            } else {
                $templateContent = Template::ReplaceCustomTag($templateContent, $tagId, '');
            }


        }

        return $templateContent;
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
    public function TransferPublishQueue(PublishQueueManageData $publishQueueManageData, $siteId)
    {
        $ftpManageData = new FtpManageData();
        $ftp = new Ftp();
        $arrFtpOne = $ftpManageData->GetOneBySiteId($siteId);
        $ftpManageData->FillFtp($arrFtpOne, $ftp);

        //判断是用ftp方式传输还是直接写文件方式传输
        if (!empty($ftpInfo)) { //定义了ftp配置信息，使用ftp方式传输
            $openFtpLog = false;
            $ftpLogManageData = new FtpLogManageData();
            FtpTools::UploadQueue($ftp, $publishQueueManageData, $openFtpLog, $ftpLogManageData);

        } else { //没有定义ftp配置信息，使用直接写文件方式传输
            if (!empty($publishQueueManageData->Queue)) {
                for ($i = 0; $i < count($publishQueueManageData->Queue); $i++) {
                    $destinationPath = $publishQueueManageData->Queue[$i]["DestinationPath"];
                    $channelTemplateContent = $publishQueueManageData->Queue[$i]["Content"];
                    $result = FileObject::Write($destinationPath, $channelTemplateContent);
                    if ($result > 0) { //成功返回成功码
                        $publishQueueManageData->Queue[$i]["Result"] =
                            abs(DefineCode::PUBLISH) + self::PUBLISH_TRANSFER_RESULT_SUCCESS;
                    } else { //错误则返回FileObject::Write中的错误码
                        $publishQueueManageData->Queue[$i]["Result"] = $result;
                    }
                }
            }
        }
    }

    /**
     * 删除发布队列中的内容
     * @param PublishQueueManageData $publishQueueManageData 发布队列对象
     * @param int $siteId 站点id
     */
    private function DeleteByPublishQueue(PublishQueueManageData $publishQueueManageData, $siteId)
    {
        $ftpManageData = new FtpManageData();
        $ftp = new Ftp();
        $arrFtpOne = $ftpManageData->GetOneBySiteId($siteId);
        $ftpManageData->FillFtp($arrFtpOne, $ftp);
        //判断是用ftp方式传输还是直接写文件方式传输
        if (!empty($ftpInfo)) { //定义了ftp配置信息，使用ftp方式传输

            if (!empty($publishQueueManageData->Queue)) {
                for ($i = 0; $i < count($publishQueueManageData->Queue); $i++) {
                    $result = FtpTools::Delete($ftp, $publishQueueManageData->Queue[$i]["DestinationPath"]);
                    $publishQueueManageData->Queue[$i]["Result"] = $result;
                }
            }

        } else { //没有定义ftp配置信息，使用直接写文件方式传输
            if (!empty($publishQueueManageData->Queue)) {
                for ($i = 0; $i < count($publishQueueManageData->Queue); $i++) {
                    $destinationPath = $publishQueueManageData->Queue[$i]["DestinationPath"];

                    $result = FileObject::DeleteFile($destinationPath);
                    if ($result > 0) { //成功返回成功码
                        $publishQueueManageData->Queue[$i]["Result"] =
                            abs(DefineCode::PUBLISH) + self::PUBLISH_DELETE_RESULT_SUCCESS;
                    } else { //错误则返回FileObject::Write中的错误码
                        $publishQueueManageData->Queue[$i]["Result"] =
                            DefineCode::PUBLISH + self::PUBLISH_DELETE_RESULT_FAILURE;
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
     * 发布资讯详细页 返回值 没有权限
     */
    const PUBLISH_DOCUMENT_NEWS_RESULT_NO_RIGHT = -204;

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
        if ($documentNewsId > 0) {
            $documentNewsManageData = new DocumentNewsManageData();
            //取得并判断状态
            $state = $documentNewsManageData->GetState($documentNewsId, false);
            if ($state === DocumentNewsData::STATE_FINAL_VERIFY || $state === DocumentNewsData::STATE_PUBLISHED) {
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

                $channelId = $documentNewsManageData->GetChannelId($documentNewsId, true);

                if ($channelId > 0) {
                    $channelManageData = new ChannelManageData();
                    $channelTemplateManageData = new ChannelTemplateManageData();
                    $rank = $channelManageData->GetRank($channelId, true);
                    $siteId = $channelManageData->GetSiteId($channelId, true);
                    $channelName = $channelManageData->GetChannelName($channelId, true);
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
                                $arrChannelTemplateForPlatforms = array(); //不同平台的模板 分别发布

                                $channelTemplateContentForPC = $arrChannelTemplateList[$i]["ChannelTemplateContent"]; //PC
                                $publishFileNameForPC = strval($documentNewsId) . '.html'; //发布文件名，资讯id构成
                                $arrChannelTemplateForPlatforms["PC"] = array("Content" => $channelTemplateContentForPC, "FileName" => $publishFileNameForPC);

                                $channelTemplateContentForMobile = $arrChannelTemplateList[$i]["ChannelTemplateContentForMobile"]; //Mobile: xx_m.html
                                $publishFileNameForMobile = strval($documentNewsId) . '_m.html';
                                $arrChannelTemplateForPlatforms["Mobile"] = array("Content" => $channelTemplateContentForMobile, "FileName" => $publishFileNameForMobile);

                                $channelTemplateContentForPad = $arrChannelTemplateList[$i]["ChannelTemplateContentForPad"]; //Pad: xx_p.html
                                $publishFileNameForPad = strval($documentNewsId) . '_p.html';
                                $arrChannelTemplateForPlatforms["Pad"] = array("Content" => $channelTemplateContentForPad, "FileName" => $publishFileNameForPad);

                                $channelTemplateContentForTV = $arrChannelTemplateList[$i]["ChannelTemplateContentForTV"]; //TV: xx_t.html
                                $publishFileNameForTV = strval($documentNewsId) . '_t.html';
                                $arrChannelTemplateForPlatforms["TV"] = array("Content" => $channelTemplateContentForTV, "FileName" => $publishFileNameForTV);


                                foreach ($arrChannelTemplateForPlatforms as $onePlatformTemplate) {
                                    //1.取得模板数据

                                    //$channelTemplateId = $arrChannelTemplateList[$i]["ChannelTemplateId"];
                                    $channelTemplateContent = $onePlatformTemplate["Content"];
                                    $publishType = $arrChannelTemplateList[$i]["PublishType"];
                                    $publishFileName = $onePlatformTemplate["FileName"];


                                    if (strlen($channelTemplateContent) > 0) {


                                        //2.替换列表类的模板内容
                                        $timeStart = Control::GetMicroTime();

                                        $channelTemplateContent = str_ireplace("{DocumentNewsId}", $documentNewsId, $channelTemplateContent);
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
                                            "now document news id:$documentNewsId replace template"
                                        );




                                        //3.替换资讯内容和其他一些内容
                                        $manageUserName = Control::GetManageUserName();
                                        $arrOne = $documentNewsManageData->GetOne($documentNewsId);
                                        if($arrOne["PublishManageUserName"]==""){
                                            $arrOne["PublishManageUserName"]=$manageUserName;
                                        }

                                        ///////////处理内容分页
                                        $pagerLine = "|=================================== PAGE ====================================|";

                                        if(count($arrOne)>0){
                                            //对文档内容可能包含的如投票等标记内容进行替换处理
                                            $arrOne["DocumentNewsContent"] = self::ReplaceTemplate($channelId, $arrOne["DocumentNewsContent"]);
                                            $documentNewsContent = $arrOne["DocumentNewsContent"];
                                            $arrDocumentNewsContent = explode(
                                                $pagerLine,
                                                $documentNewsContent);




                                            //发布路径，频道id+日期

                                            $publishDate = $documentNewsManageData->GetPublishDate($documentNewsId, false);
                                            if (strtotime($publishDate)>0) {
                                                //已经有发布日期了
                                                $publishPath = strval($channelId) . '/' . Format::DateStringToSimple($publishDate);
                                            } else {
                                                $publishPath = strval($channelId) . '/' . strval(date('Ymd', time()));


                                                //修改发布时间和发布人，只有发布时间为空时才进行操作
                                                $documentNewsManageData->ModifyPublishDate(
                                                    $documentNewsId,
                                                    date("Y-m-d H:i:s", time()),
                                                    $manageUserId,
                                                    $manageUserName
                                                );
                                            }



                                            /*************************  有分页的内容 ****************************/

                                            if (count($arrDocumentNewsContent)>1){ //有分页的内容

                                                $countOfArrDocumentNewsContent = count($arrDocumentNewsContent);

                                                $pagerChannelTemplateContent = $channelTemplateContent;

                                                for ($cp = count($arrDocumentNewsContent); $cp >= 0; $cp--) {

                                                    if($cp >= 0 && $cp < count($arrDocumentNewsContent)){

                                                        if(stripos($publishFileName ,'_m')!== false){
                                                            $publishFileName = str_ireplace("_m.html", "_" . ($cp + 1) . "_m.html", $publishFileName);
                                                        }else{
                                                            $publishFileName = str_ireplace(".html", "_" . ($cp + 1) . ".html", $publishFileName);
                                                        }

                                                        $arrOne["DocumentNewsContent"] = $arrDocumentNewsContent[$cp];
                                                    }elseif($cp == count($arrDocumentNewsContent)){
                                                        //生成一个含有全文的页
                                                        if(stripos($publishFileName, '_m')!== false){
                                                            $publishFileName = str_ireplace("_m.html", "_0_m.html", $publishFileName);
                                                        }else{
                                                            $publishFileName = str_ireplace(".html", "_0.html", $publishFileName);
                                                        }
                                                        $arrOne["DocumentNewsContent"] = $documentNewsContent;
                                                    }


                                                    Template::ReplaceOne($pagerChannelTemplateContent, $arrOne);
                                                    $pageSize = 1;
                                                    $pageIndex = $cp + 1;

                                                    $styleNumber = 1;
                                                    $pagerTemplate = Template::Load("pager/pager_style_detail$styleNumber.html", "common");
                                                    $isJs = FALSE;
                                                    $navUrl = str_ireplace(".html", "_{0}.html", $onePlatformTemplate["FileName"]);
                                                    $navUrl = str_ireplace("_m", "", $navUrl); //_m在静态模板中会自动识别并加上
                                                    $jsFunctionName = "";
                                                    $jsParamList = "";
                                                    $pagerButton = Pager::ShowPageButton(
                                                        $pagerTemplate,
                                                        $navUrl,
                                                        $countOfArrDocumentNewsContent,
                                                        $pageSize,
                                                        $pageIndex,
                                                        $styleNumber,
                                                        $isJs,
                                                        $jsFunctionName,
                                                        $jsParamList
                                                    );

                                                    $pagerChannelTemplateContent = str_ireplace("{pager_button}", $pagerButton, $pagerChannelTemplateContent);
                                                    $pagerChannelTemplateContent = str_ireplace($pagerLine, "", $pagerChannelTemplateContent);

                                                    $pagerChannelTemplateContent = str_ireplace("{ChannelName}", $channelName, $pagerChannelTemplateContent);
                                                    $pagerChannelTemplateContent = str_ireplace("{CurrentChannelName}", $channelName, $pagerChannelTemplateContent);

                                                    //4.根据PublishType和PublishFileName生成目标文件
                                                    //触发频道id $channelId
                                                    $timeStart = Control::GetMicroTime();

                                                    $result = self::AddToPublishQueueForChannelTemplate(
                                                        $channelId,
                                                        $rank,
                                                        $pagerChannelTemplateContent,
                                                        $publishType,
                                                        $publishFileName,
                                                        $publishQueueManageData,
                                                        $publishPath
                                                    );

                                                    //
                                                    if($cp == 0){ //第一页，生成一个不改变文件名的文件
                                                        $result = self::AddToPublishQueueForChannelTemplate(
                                                            $channelId,
                                                            $rank,
                                                            $pagerChannelTemplateContent,
                                                            $publishType,
                                                            $onePlatformTemplate["FileName"],
                                                            $publishQueueManageData,
                                                            $publishPath
                                                        );
                                                    }

                                                    $timeEnd = Control::GetMicroTime();

                                                    $publishLogManageData->Create(
                                                        PublishLogManageData::TRANSFER_TYPE_NO_DEFINE,
                                                        PublishLogManageData::TABLE_TYPE_DOCUMENT_NEWS,
                                                        $documentNewsId,
                                                        "",
                                                        "",
                                                        $timeEnd - $timeStart,
                                                        "now document news id:$documentNewsId add to publish queue result:$result"
                                                    );

                                                    $pagerChannelTemplateContent = $channelTemplateContent;
                                                    $publishFileName = $onePlatformTemplate["FileName"];
                                                }


                                            }else{  //没有分页时

                                                Template::ReplaceOne($channelTemplateContent, $arrOne);

                                                $channelTemplateContent = str_ireplace("{pager_button}", "", $channelTemplateContent);
                                                $channelTemplateContent = str_ireplace("{ChannelName}", $channelName, $channelTemplateContent);
                                                $channelTemplateContent = str_ireplace($pagerLine, "", $channelTemplateContent);
                                                $channelTemplateContent = str_ireplace("{CurrentChannelName}", $channelName, $channelTemplateContent);

                                                //4.根据PublishType和PublishFileName生成目标文件
                                                //触发频道id $channelId
                                                $timeStart = Control::GetMicroTime();

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
                                                    "now document news id:$documentNewsId add to publish queue result:$result"
                                                );


                                            }






                                        }



                                    }
                                }
                            }
                        }


                        $nowChannelId = $channelManageData->GetParentChannelId($nowChannelId, false);
                        $rank--;
                    }

                    if ($executeTransfer) {

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
                    if ($publishChannel) {
                        self::PublishChannel($channelId, $publishQueueManageData);
                    }


                } else {
                    $result = DefineCode::PUBLISH + self::PUBLISH_DOCUMENT_NEWS_RESULT_CHANNEL_ID_ERROR;
                }
            } else {
                $result = DefineCode::PUBLISH + self::PUBLISH_DOCUMENT_NEWS_RESULT_STATE_ERROR;
            }
        } else {
            $result = DefineCode::PUBLISH + self::PUBLISH_DOCUMENT_NEWS_RESULT_DOCUMENT_NEWS_ID_ERROR;
        }

        return $result;

    }

    /**
     * 取消发布（删除已发布的文件）
     * @param PublishQueueManageData $publishQueueManageData 发布队列对象，传出参数，包括了发布文件的结果值
     * @param int $documentNewsId 资讯id
     * @param int $siteId 站点id
     * @return int 结果
     */
    protected function CancelPublishDocumentNews(PublishQueueManageData $publishQueueManageData, $documentNewsId, $siteId)
    {
        $result = -1;
        if ($documentNewsId > 0) {
            /**************** 传输日志 ********************/
            $publishLogManageData = new PublishLogManageData();
            $publishLogManageData->Create(
                PublishLogManageData::TRANSFER_TYPE_NO_DEFINE,
                PublishLogManageData::TABLE_TYPE_DOCUMENT_NEWS,
                $documentNewsId,
                "",
                "",
                0,
                "begin cancel"
            );
            $documentNewsManageData = new DocumentNewsManageData();
            $channelId = $documentNewsManageData->GetChannelId($documentNewsId, true);
            $publishDate = $documentNewsManageData->GetPublishDate($documentNewsId, true);
            //发布文件名，资讯id构成
            $publishFileName1 = strval($documentNewsId) . '.html'; //PC模板
            $publishFileName2 = strval($documentNewsId) . '_m.html'; //手机模板
            $publishFileName3 = strval($documentNewsId) . '_p.html'; //平板模板
            $publishFileName4 = strval($documentNewsId) . '_t.html'; //电视机模板
            //发布路径，频道id+日期
            $publishPath = strval($channelId) . '/' . Format::DateStringToSimple($publishDate);
            $destinationPath1 = self::PUBLISH_PATH . '/' . $publishPath . '/' . $publishFileName1;
            $destinationPath2 = self::PUBLISH_PATH . '/' . $publishPath . '/' . $publishFileName2;
            $destinationPath3 = self::PUBLISH_PATH . '/' . $publishPath . '/' . $publishFileName3;
            $destinationPath4 = self::PUBLISH_PATH . '/' . $publishPath . '/' . $publishFileName4;
            $sourcePath = '';
            $publishContent = '';
            if(file_exists($destinationPath1)){
                $publishQueueManageData->Add($destinationPath1, $sourcePath, $publishContent);
            }
            if(file_exists($destinationPath2)){
                $publishQueueManageData->Add($destinationPath2, $sourcePath, $publishContent);
            }
            if(file_exists($destinationPath3)){
                $publishQueueManageData->Add($destinationPath3, $sourcePath, $publishContent);
            }
            if(file_exists($destinationPath4)){
                $publishQueueManageData->Add($destinationPath4, $sourcePath, $publishContent);
            }

            self::DeleteByPublishQueue($publishQueueManageData, $siteId);
        }
        return $result;
    }


    /**
     * 发布自定义页面详细页 返回值 自定义页面id小于0
     */
    const PUBLISH_SITE_CONTENT_RESULT_SITE_CONTENT_ID_ERROR = -211;
    /**
     * 发布自定义页面详细页 返回值 频道id小于0
     */
    const PUBLISH_SITE_CONTENT_RESULT_CHANNEL_ID_ERROR = -212;
    /**
     * 发布自定义页面详细页 返回值 状态不正确，必须为启用状态的文档才能发布
     */
    const PUBLISH_SITE_CONTENT_RESULT_STATE_ERROR = -213;
    /**
     * 发布自定义页面详细页 返回值 操作完成，结果存储于结果数组中
     */
    const PUBLISH_SITE_CONTENT_RESULT_FINISHED = 211;


    /**
     * 发布自定义页面详细页面
     * @param int $siteContentId 自定义页面id
     * @param PublishQueueManageData $publishQueueManageData
     * @param bool $executeTransfer 是否执行发布
     * @param bool $publishChannel 是否同时发布频道
     * @return int 发布结果
     */
    protected function PublishSiteContent(
        $siteContentId,
        PublishQueueManageData $publishQueueManageData,
        $executeTransfer = false,
        $publishChannel = false
    )
    {
        $manageUserId = Control::GetManageUserId();
        if ($siteContentId > 0) {
            $siteContentManageData = new SiteContentManageData();
            //取得并判断状态
            $state = $siteContentManageData->GetState($siteContentId, true);
            if ($state === SiteContentData::STATE_ENABLE ||
                $state === SiteContentData::STATE_PUBLISHED
            ) {
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
                    PublishLogManageData::TABLE_TYPE_SITE_CONTENT,
                    $siteContentId,
                    "",
                    "",
                    0,
                    "begin transfer"
                );

                /**************** 取得模板 ********************/

                $channelId = $siteContentManageData->GetChannelId($siteContentId, true);

                if ($channelId > 0) {
                    $channelManageData = new ChannelManageData();
                    $channelTemplateManageData = new ChannelTemplateManageData();
                    $rank = $channelManageData->GetRank($channelId, true);
                    $siteId = $channelManageData->GetSiteId($channelId, true);
                    $channelName = $channelManageData->GetChannelName($channelId, true);
                    $nowChannelId = $channelId;

                    //循环Rank进行发布
                    while ($rank >= 0) {

                        $timeStart = Control::GetMicroTime();
                        $publishType = ChannelTemplateData::PUBLISH_TYPE_SITE_CONTENT_DETAIL;

                        $arrChannelTemplateList = $channelTemplateManageData->GetListByPublishType($nowChannelId, $publishType);
                        $timeEnd = Control::GetMicroTime();

                        //传输日志 取得模板
                        $publishLogManageData->Create(
                            PublishLogManageData::TRANSFER_TYPE_NO_DEFINE,
                            PublishLogManageData::TABLE_TYPE_SITE_CONTENT,
                            $siteContentId,
                            "",
                            "",
                            $timeEnd - $timeStart,
                            "get template list"
                        );
                        if (!empty($arrChannelTemplateList)) {
                            for ($i = 0; $i < Count($arrChannelTemplateList); $i++) {

                                $arrChannelTemplateForPlatforms = array(); //不同平台的模板 分别发布

                                $channelTemplateContentForPC = $arrChannelTemplateList[$i]["ChannelTemplateContent"]; //PC
                                $publishFileNameForPC = strval($siteContentId) . '.html'; //发布文件名，资讯id构成
                                $arrChannelTemplateForPlatforms["PC"] = array("Content" => $channelTemplateContentForPC, "FileName" => $publishFileNameForPC);

                                $channelTemplateContentForMobile = $arrChannelTemplateList[$i]["ChannelTemplateContentForMobile"]; //Mobile: xx_m.html
                                $publishFileNameForMobile = strval($siteContentId) . '_m.html';
                                $arrChannelTemplateForPlatforms["Mobile"] = array("Content" => $channelTemplateContentForMobile, "FileName" => $publishFileNameForMobile);

                                $channelTemplateContentForPad = $arrChannelTemplateList[$i]["ChannelTemplateContentForPad"]; //Pad: xx_p.html
                                $publishFileNameForPad = strval($siteContentId) . '_p.html';
                                $arrChannelTemplateForPlatforms["Pad"] = array("Content" => $channelTemplateContentForPad, "FileName" => $publishFileNameForPad);

                                $channelTemplateContentForTV = $arrChannelTemplateList[$i]["ChannelTemplateContentForTV"]; //TV: xx_t.html
                                $publishFileNameForTV = strval($siteContentId) . '_t.html';
                                $arrChannelTemplateForPlatforms["TV"] = array("Content" => $channelTemplateContentForTV, "FileName" => $publishFileNameForTV);


                                foreach ($arrChannelTemplateForPlatforms as $onePlatformTemplate) {

                                    //1.取得模板数据
                                    $channelTemplateContent = $onePlatformTemplate["Content"];
                                    $publishType = $arrChannelTemplateList[$i]["PublishType"];
                                    $publishFileName = $onePlatformTemplate["FileName"];


                                    if (strlen($channelTemplateContent) > 0) {


                                        //2.替换列表类的模板内容
                                        $timeStart = Control::GetMicroTime();
                                        $channelTemplateContent = self::ReplaceTemplate($channelId, $channelTemplateContent);
                                        $timeEnd = Control::GetMicroTime();

                                        //传输日志 替换模板
                                        $publishLogManageData->Create(
                                            PublishLogManageData::TRANSFER_TYPE_NO_DEFINE,
                                            PublishLogManageData::TABLE_TYPE_DOCUMENT_NEWS,
                                            $siteContentId,
                                            "",
                                            "",
                                            $timeEnd - $timeStart,
                                            "now site content id:$siteContentId replace template"
                                        );

                                        //3.替换资讯内容和其他一些内容
                                        $arrOne = $siteContentManageData->GetOne($siteContentId);
                                        Template::ReplaceOne($channelTemplateContent, $arrOne);

                                        $channelTemplateContent = str_ireplace("{ChannelName}", $channelName, $channelTemplateContent);
                                        $channelTemplateContent = str_ireplace("{CurrentChannelName}", $channelName, $channelTemplateContent);

                                        //4.根据PublishType和PublishFileName生成目标文件
                                        //触发频道id $channelId
                                        $timeStart = Control::GetMicroTime();

                                        //发布路径，频道id+日期
                                        $publishPath = strval($channelId) . '/' . strval(date('Ymd', time()));

                                        //修改发布时间和发布人，只有发布时间为空时才进行操作
                                        $siteContentManageData->ModifyPublishDate(
                                            $siteContentId,
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
                                            PublishLogManageData::TABLE_TYPE_SITE_CONTENT,
                                            $siteContentId,
                                            "",
                                            "",
                                            $timeEnd - $timeStart,
                                            "now site content id:$siteContentId add to publish queue result:$result"
                                        );

                                    }





                                }

                            }
                        }


                        $nowChannelId = $channelManageData->GetParentChannelId($nowChannelId, false);
                        $rank--;
                    }

                    if ($executeTransfer) {

                        $timeStart = Control::GetMicroTime();
                        self::TransferPublishQueue($publishQueueManageData, $siteId);
                        $timeEnd = Control::GetMicroTime();
                        $publishLogManageData->Create(
                            PublishLogManageData::TRANSFER_TYPE_NO_DEFINE,
                            PublishLogManageData::TABLE_TYPE_SITE_CONTENT,
                            $siteContentId,
                            "",
                            "",
                            $timeEnd - $timeStart,
                            "now site content id:$siteContentId transfer publish queue"
                        );
                    }

                    $result = abs(DefineCode::PUBLISH) + self::PUBLISH_SITE_CONTENT_RESULT_FINISHED;

                    //修改状态
                    $siteContentManageData->ModifyState($siteContentId, SiteContentData::STATE_PUBLISHED);

                    //同步发布频道
                    if ($publishChannel) {
                        self::PublishChannel($channelId, $publishQueueManageData);
                    }


                } else {
                    $result = DefineCode::PUBLISH + self::PUBLISH_SITE_CONTENT_RESULT_CHANNEL_ID_ERROR;
                }
            } else {
                $result = DefineCode::PUBLISH + self::PUBLISH_SITE_CONTENT_RESULT_STATE_ERROR;
            }
        } else {
            $result = DefineCode::PUBLISH + self::PUBLISH_SITE_CONTENT_RESULT_SITE_CONTENT_ID_ERROR;
        }

        return $result;

    }

    /**
     * 取消发布（删除已发布的文件）
     * @param PublishQueueManageData $publishQueueManageData 发布队列对象，传出参数，包括了发布文件的结果值
     * @param int $siteContentId 自定义页面id
     * @param int $siteId 站点id
     * @return int 结果
     */
    protected function CancelPublishSiteContent(PublishQueueManageData $publishQueueManageData, $siteContentId, $siteId)
    {
        $result = -1;
        if ($siteContentId > 0) {
            /**************** 传输日志 ********************/
            $publishLogManageData = new PublishLogManageData();
            $publishLogManageData->Create(
                PublishLogManageData::TRANSFER_TYPE_NO_DEFINE,
                PublishLogManageData::TABLE_TYPE_SITE_CONTENT,
                $siteContentId,
                "",
                "",
                0,
                "begin cancel"
            );
            $siteContentManageData = new SiteContentManageData();
            $channelId = $siteContentManageData->GetChannelId($siteContentId, true);
            $publishDate = $siteContentManageData->GetPublishDate($siteContentId, true);
            //发布文件名，资讯id构成
            $publishFileName = strval($siteContentId) . '.html';
            //发布路径，频道id+日期
            $publishPath = strval($channelId) . '/' . Format::DateStringToSimple($publishDate);
            $destinationPath = $publishPath . '/' . $publishFileName;
            $sourcePath = '';
            $publishContent = '';
            $publishQueueManageData->Add($destinationPath, $sourcePath, $publishContent);
            self::DeleteByPublishQueue($publishQueueManageData, $siteId);
        }
        return $result;
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
    )
    {
        $result = "";
        if ($siteAdId > 0) {
            $siteAdManageData = new SiteAdManageData();
            $siteId = $siteAdManageData->GetSiteId($siteAdId, FALSE);
            if ($siteId > 0) {
                $siteAdManageData = new SiteAdManageData();
                $arrayOfOneSiteAd = $siteAdManageData->GetOne($siteAdId);
                if (count($arrayOfOneSiteAd) > 0) {
                    if (intval($arrayOfOneSiteAd["State"]) === 0) {
                        $showOnce = $arrayOfOneSiteAd["ShowOnce"]; //是否只显示一次
                        $showNumber = $arrayOfOneSiteAd["ShowNumber"]; //轮换类广告位是否显示轮换数字
                        $siteAdWidth = $arrayOfOneSiteAd["SiteAdWidth"];

                        if (stripos($siteAdWidth, "%") <= 0 && strtolower($siteAdWidth) != "auto") {
                            $siteAdWidth .= "px";
                        }

                        $siteAdHeight = $arrayOfOneSiteAd["SiteAdHeight"];
                        if (stripos($siteAdHeight, "%") <= 0 && strtolower($siteAdHeight) != "auto") {
                            $siteAdHeight .= "px";
                        }
                        $showType = $arrayOfOneSiteAd["ShowType"]; //广告位类型 0:图片,1文字,2轮换 3随机,4落幕
                        $siteAdContentManageData = new SiteAdContentManageData();
                        if ($showType < 0) {
                            $showType = 0;
                        }
                        $listOfSiteAdContentArray = $siteAdContentManageData->GetAllAdContent($siteAdId); //取广告位下所有可用状态广告（启用，未过期）

                        if (count($listOfSiteAdContentArray) <= 0) {
                            $listOfSiteAdContentArray = $siteAdContentManageData->GetLastAdContent($siteAdId); //没有可用广告 则尝试取最后一条过期广告以防页面空白
                            $warns .= Language::Load('site_ad', 16); //所有广告均已过期！页面将保留最后过期的广告！
                        }

                        if (count($listOfSiteAdContentArray) > 0) {
                            $siteAdJsContent = Template::Load("site/site_ad_js_type_" . intval($showType) . ".html", "common"); //ShowType 0为图片 1文字 2轮换 3随机 4落幕
                            $listName = "site_ad_content";
                            Template::ReplaceList($siteAdJsContent, $listOfSiteAdContentArray, $listName);

                            $replaceArr = array(
                                "{SiteAdId}" => $siteAdId,
                                "{SiteAdWidth}" => $siteAdWidth,
                                "{SiteAdHeight}" => $siteAdHeight,
                                "{ShowType}" => $showType,
                                "{ShowNumber}" => $showNumber,
                                "{ShowOnce}" => $showOnce
                            );
                            $siteAdJsContent = strtr($siteAdJsContent, $replaceArr);
                            //解jquery与其他$的JS冲突问题
                            $siteAdJsContent = str_ireplace("$", 'jQuery', $siteAdJsContent);

                        } else {
                            $warns .= Language::Load('site_ad', 11); //该广告位没有可用的广告
                            $warns .= Language::Load('site_ad', 13); //广告JS为空，广告将不会显示！
                            $siteAdJsContent = "";
                        }


                    } else {
                        $warns .= Language::Load('site_ad', 9); //当前操作对象不是启用状态！
                        $warns .= Language::Load('site_ad', 13); //广告JS为空，广告将不会显示！
                        $siteAdJsContent = "";
                    }


                    //生成广告调用JS文件

                    //$tempDir = '/' . '/ad/' . $siteId;
                    //$source = $tempDir . '/site_ad_' . $siteAdId . ".js";
                    //FileObject::CreateDir($tempDir);
                    //FileObject::Write($source, $siteAdJsContent);


                    $siteAdFileName = 'site_ad_' . $siteAdId . ".js";
                    $publishResult = self::AddToPublishQueueForSiteAd(
                        $siteAdId,
                        $siteAdJsContent,
                        $siteAdFileName,
                        $publishQueueManageData
                    );

                    self::TransferPublishQueue($publishQueueManageData, $siteId);

                    if ($publishResult > 0) {
                        $result .= Language::Load('site_ad', 10); //广告JS更新成功!
                        $strCopy = '<div class="site_ad_' . $siteAdId . '"></div><script language="javascript" src="' . self::SITE_AD_PATH . '/' . $siteId . '/site_ad_' . $siteAdId . '.js" charset="utf-8"></script>';

                        $result .= "<br><br>" . htmlspecialchars($strCopy);
                        //$result .= "<br><br>" . self::SITE_AD_PATH .'/'. $siteId . '/site_ad_' . $siteAdId . ".js";
                    } else {
                        $result .= Language::Load('site_ad', 17); //广告JS文件发布失败！
                        $result .= "<br>error code:" . $publishResult;
                    }


                    //记入操作log
                    $operateContent = "CreateJs site_ad：SiteAdId：" . $siteAdId . ",POST FORM:" . implode("|", $_POST) . ";\r\nResult:" . $result;
                    self::CreateManageUserLog($operateContent);

                } else {
                    $result .= Language::Load('site_ad', 8); //获取该条记录数据失败！
                }


            } else {
                $result .= Language::Load('site_ad', 5); //站点siteid错误！;
            }
        } else {
            $result .= Language::Load('site_ad', 6); //广告位site_ad_id错误！;
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
            $siteAdManageData = new SiteAdManageData();
            $siteId = $siteAdManageData->GetSiteId($siteAdId, FALSE);
            if ($siteId > 0) {
                $destinationPath = self::SITE_AD_PATH . '/' . strval($siteId) . '/' . $publishFileName;
                $sourcePath = '';
                $publishQueueManageData->Add($destinationPath, $sourcePath, $publishContent);
                $result = abs(DefineCode::PUBLISH) + self::ADD_TO_PUBLISH_QUEUE_RESULT_FINISHED;
            } else {
                $result = DefineCode::PUBLISH + self::PUBLISH_SITE_AD_RESULT_SITE_ID_ERROR;
            }
        } else {
            $result = DefineCode::PUBLISH + self::PUBLISH_SITE_AD_RESULT_SITE_AD_ID_ERROR;
        }
        return $result;

    }

    /**
     * 发布活动详细页 返回值 资讯id小于0
     */
    const PUBLISH_ACTIVITY_RESULT_DOCUMENT_NEWS_ID_ERROR = -311;
    /**
     * 发布活动详细页 返回值 频道id小于0
     */
    const PUBLISH_ACTIVITY_RESULT_CHANNEL_ID_ERROR = -312;
    /**
     * 发布活动详细页 返回值 状态不正确，必须为终审或已发状态的文档才能发布
     */
    const PUBLISH_ACTIVITY_RESULT_STATE_ERROR = -313;

    /**
     * 发布活动详细页 返回值 操作完成，结果存储于结果数组中
     */
    const PUBLISH_ACTIVITY_RESULT_FINISHED = 311;


    /**
     * 发布活动详细页面
     * @param int $activityId
     * @param PublishQueueManageData $publishQueueManageData
     * @param bool $executeTransfer 是否执行发布
     * @param bool $publishChannel 是否同时发布频道
     * @return int 发布结果
     */
    protected function PublishActivity(
        $activityId,
        PublishQueueManageData $publishQueueManageData,
        $executeTransfer = false,
        $publishChannel = false
    )
    {
        $manageUserId = Control::GetManageUserId();
        if ($activityId > 0) {
            $activityManageData = new ActivityManageData();
            //取得并判断状态
            $state = $activityManageData->GetState($activityId, false);
            if ($state === 0 || $state === 30) { //0 活动已审状态 30 已发状态 100 停用状态
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
                    PublishLogManageData::TABLE_TYPE_ACTIVITY,
                    $activityId,
                    "",
                    "",
                    0,
                    "begin transfer"
                );

                /**************** 取得模板 ********************/

                $channelId = $activityManageData->GetChannelId($activityId, true);

                if ($channelId > 0) {
                    $channelManageData = new ChannelManageData();
                    $channelTemplateManageData = new ChannelTemplateManageData();
                    $rank = $channelManageData->GetRank($channelId, true);
                    $siteId = $channelManageData->GetSiteId($channelId, true);
                    $channelName = $channelManageData->GetChannelName($channelId, true);
                    $nowChannelId = $channelId;

                    //循环Rank进行发布
                    while ($rank >= 0) {

                        $timeStart = Control::GetMicroTime();
                        $publishType = ChannelTemplateData::PUBLISH_TYPE_ACTIVITY_DETAIL;

                        $arrChannelTemplateList = $channelTemplateManageData->GetListByPublishType($nowChannelId, $publishType);
                        $timeEnd = Control::GetMicroTime();

                        //传输日志 取得模板
                        $publishLogManageData->Create(
                            PublishLogManageData::TRANSFER_TYPE_NO_DEFINE,
                            PublishLogManageData::TABLE_TYPE_ACTIVITY,
                            $activityId,
                            "",
                            "",
                            $timeEnd - $timeStart,
                            "get template list"
                        );
                        if (!empty($arrChannelTemplateList)) {

                            for ($i = 0; $i < Count($arrChannelTemplateList); $i++) {


                                $arrChannelTemplateForPlatforms = array(); //不同平台的模板 分别发布

                                $channelTemplateContentForPC = $arrChannelTemplateList[$i]["ChannelTemplateContent"]; //PC
                                $publishFileNameForPC = 'a' . strval($activityId) . '.html'; //发布文件名，活动id前面加a构成
                                $arrChannelTemplateForPlatforms["PC"] = array("Content" => $channelTemplateContentForPC, "FileName" => $publishFileNameForPC);

                                $channelTemplateContentForMobile = $arrChannelTemplateList[$i]["ChannelTemplateContentForMobile"]; //Mobile: xx_m.html
                                $publishFileNameForMobile = 'a' . strval($activityId) . '_m.html';
                                $arrChannelTemplateForPlatforms["Mobile"] = array("Content" => $channelTemplateContentForMobile, "FileName" => $publishFileNameForMobile);

                                $channelTemplateContentForPad = $arrChannelTemplateList[$i]["ChannelTemplateContentForPad"]; //Pad: xx_p.html
                                $publishFileNameForPad = 'a' . strval($activityId) . '_p.html';
                                $arrChannelTemplateForPlatforms["Pad"] = array("Content" => $channelTemplateContentForPad, "FileName" => $publishFileNameForPad);

                                $channelTemplateContentForTV = $arrChannelTemplateList[$i]["ChannelTemplateContentForTV"]; //TV: xx_t.html
                                $publishFileNameForTV = 'a' . strval($activityId) . '_t.html';
                                $arrChannelTemplateForPlatforms["TV"] = array("Content" => $channelTemplateContentForTV, "FileName" => $publishFileNameForTV);


                                foreach ($arrChannelTemplateForPlatforms as $onePlatformTemplate) {

                                    //1.取得模板数据

                                    //$channelTemplateId = $arrChannelTemplateList[$i]["ChannelTemplateId"];
                                    $channelTemplateContent = $onePlatformTemplate["Content"];
                                    //$publishType = $arrChannelTemplateList[$i]["PublishType"];
                                    $publishFileName = $onePlatformTemplate["FileName"];


                                    if (strlen($channelTemplateContent) > 0) {

                                        //2.替换列表类的模板内容
                                        $timeStart = Control::GetMicroTime();
                                        $channelTemplateContent = self::ReplaceTemplate($channelId, $channelTemplateContent);
                                        $timeEnd = Control::GetMicroTime();


                                        //传输日志 替换模板
                                        $publishLogManageData->Create(
                                            PublishLogManageData::TRANSFER_TYPE_NO_DEFINE,
                                            PublishLogManageData::TABLE_TYPE_ACTIVITY,
                                            $activityId,
                                            "",
                                            "",
                                            $timeEnd - $timeStart,
                                            "now activity id:$activityId replace template"
                                        );


                                        //3.替换资讯内容和其他一些内容
                                        $arrOne = $activityManageData->GetOne($activityId);
                                        Template::ReplaceOne($channelTemplateContent, $arrOne);

                                        $channelTemplateContent = str_ireplace("{ChannelName}", $channelName, $channelTemplateContent);
                                        $channelTemplateContent = str_ireplace("{CurrentChannelName}", $channelName, $channelTemplateContent);


                                        //4.根据PublishType和PublishFileName生成目标文件
                                        //触发频道id $channelId
                                        $timeStart = Control::GetMicroTime();

                                        //发布路径，频道id+日期

                                        $publishDate = $activityManageData->GetPublishDate($activityId, false);
                                        if (strlen($publishDate) > 10) {
                                            //已经有发布日期了
                                            $publishPath = strval($channelId) . '/' . Format::DateStringToSimple($publishDate);
                                        } else {
                                            $publishPath = strval($channelId) . '/' . strval(date('Ymd', time()));
                                        }


                                        //修改发布时间和发布人，只有发布时间为空时才进行操作
                                        $activityManageData->ModifyPublishDate(
                                            $activityId,
                                            date("Y-m-d H:i:s", time())
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
                                            PublishLogManageData::TABLE_TYPE_ACTIVITY,
                                            $activityId,
                                            "",
                                            "",
                                            $timeEnd - $timeStart,
                                            "now document news id:$activityId add to publish queue result:$result"
                                        );
                                    }
                                }
                            }
                        }


                        $nowChannelId = $channelManageData->GetParentChannelId($nowChannelId, false);
                        $rank--;
                    }

                    if ($executeTransfer) {

                        $timeStart = Control::GetMicroTime();
                        self::TransferPublishQueue($publishQueueManageData, $siteId);
                        $timeEnd = Control::GetMicroTime();
                        $publishLogManageData->Create(
                            PublishLogManageData::TRANSFER_TYPE_NO_DEFINE,
                            PublishLogManageData::TABLE_TYPE_ACTIVITY,
                            $activityId,
                            "",
                            "",
                            $timeEnd - $timeStart,
                            "now channel id:$nowChannelId transfer publish queue"
                        );
                    }

                    $result = abs(DefineCode::PUBLISH) + self::PUBLISH_ACTIVITY_RESULT_FINISHED;

                    //修改状态
                    $activityManageData->ModifyState($activityId, 30); //30 已发

                    //同步发布频道
                    if ($publishChannel) {
                        self::PublishChannel($channelId, $publishQueueManageData);
                    }


                } else {
                    $result = DefineCode::PUBLISH + self::PUBLISH_ACTIVITY_RESULT_CHANNEL_ID_ERROR;
                }
            } else {
                $result = DefineCode::PUBLISH + self::PUBLISH_ACTIVITY_RESULT_STATE_ERROR;
            }
        } else {
            $result = DefineCode::PUBLISH + self::PUBLISH_ACTIVITY_RESULT_DOCUMENT_NEWS_ID_ERROR;
        }

        return $result;

    }

    /**
     * 取消发布（删除已发布的文件）
     * @param PublishQueueManageData $publishQueueManageData 发布队列对象，传出参数，包括了发布文件的结果值
     * @param int $activityId 活动id
     * @param int $siteId 站点id
     * @return int 结果
     */
    protected function CancelPublishActivity(PublishQueueManageData $publishQueueManageData, $activityId, $siteId)
    {
        $result = -1;
        if ($activityId > 0) {
            /**************** 传输日志 ********************/
            $publishLogManageData = new PublishLogManageData();
            $publishLogManageData->Create(
                PublishLogManageData::TRANSFER_TYPE_NO_DEFINE,
                PublishLogManageData::TABLE_TYPE_ACTIVITY,
                $activityId,
                "",
                "",
                0,
                "begin cancel"
            );
            $activityManageData = new ActivityManageData();
            $channelId = $activityManageData->GetChannelId($activityId, true);
            $publishDate = $activityManageData->GetPublishDate($activityId, true);
            //发布文件名，资讯id构成
            $publishFileName = 'a' . strval($activityId) . '.html';
            //发布路径，频道id+日期
            $publishPath = 'h/' . strval($channelId) . '/' . Format::DateStringToSimple($publishDate);
            $destinationPath = $publishPath . '/' . $publishFileName;
            $sourcePath = '';
            $publishContent = '';
            $publishQueueManageData->Add($destinationPath, $sourcePath, $publishContent);
            self::DeleteByPublishQueue($publishQueueManageData, $siteId);
        }
        return $result;
    }


    /**
     * 发布分类信息详细页 返回值 资讯id小于0
     */
    const PUBLISH_INFORMATION_RESULT_DOCUMENT_NEWS_ID_ERROR = -321;
    /**
     * 发布分类信息详细页 返回值 频道id小于0
     */
    const PUBLISH_INFORMATION_RESULT_CHANNEL_ID_ERROR = -322;
    /**
     * 发布分类信息详细页 返回值 状态不正确，必须为终审或已发状态的文档才能发布
     */
    const PUBLISH_INFORMATION_RESULT_STATE_ERROR = -323;

    /**
     * 发布分类信息详细页 返回值 操作完成，结果存储于结果数组中
     */
    const PUBLISH_INFORMATION_RESULT_FINISHED = 321;


    /**
     * 发布分类信息详细页面
     * @param int $informationId
     * @param PublishQueueManageData $publishQueueManageData
     * @param bool $executeTransfer 是否执行发布
     * @param bool $publishChannel 是否同时发布频道
     * @return int 发布结果
     */
    protected function PublishInformation(
        $informationId,
        PublishQueueManageData $publishQueueManageData,
        $executeTransfer = false,
        $publishChannel = false
    )
    {
        $manageUserId = Control::GetManageUserId();
        if ($informationId > 0) {
            $informationManageData = new InformationManageData();
            //取得并判断状态
            $state = $informationManageData->GetState($informationId, false);
            if ($state === 0 || $state === 30) { //0 活动已审状态 30 已发状态 100 停用状态
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
                    PublishLogManageData::TABLE_TYPE_INFORMATION,
                    $informationId,
                    "",
                    "",
                    0,
                    "begin transfer"
                );

                /**************** 取得模板 ********************/

                $channelId = $informationManageData->GetChannelId($informationId, true);

                if ($channelId > 0) {
                    $channelManageData = new ChannelManageData();
                    $channelTemplateManageData = new ChannelTemplateManageData();
                    $rank = $channelManageData->GetRank($channelId, true);
                    $siteId = $channelManageData->GetSiteId($channelId, true);
                    $channelName = $channelManageData->GetChannelName($channelId, true);
                    $nowChannelId = $channelId;

                    //循环Rank进行发布
                    while ($rank >= 0) {

                        $timeStart = Control::GetMicroTime();
                        $publishType = ChannelTemplateData::PUBLISH_TYPE_INFORMATION_DETAIL;

                        $arrChannelTemplateList = $channelTemplateManageData->GetListByPublishType($nowChannelId, $publishType);
                        $timeEnd = Control::GetMicroTime();

                        //传输日志 取得模板
                        $publishLogManageData->Create(
                            PublishLogManageData::TRANSFER_TYPE_NO_DEFINE,
                            PublishLogManageData::TABLE_TYPE_INFORMATION,
                            $informationId,
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

                                //2.替换列表类的模板内容
                                $timeStart = Control::GetMicroTime();
                                $channelTemplateContent = self::ReplaceTemplate($channelId, $channelTemplateContent);
                                $timeEnd = Control::GetMicroTime();


                                //传输日志 替换模板
                                $publishLogManageData->Create(
                                    PublishLogManageData::TRANSFER_TYPE_NO_DEFINE,
                                    PublishLogManageData::TABLE_TYPE_INFORMATION,
                                    $informationId,
                                    "",
                                    "",
                                    $timeEnd - $timeStart,
                                    "now activity id:$informationId replace template"
                                );


                                //3.替换资讯内容和其他一些内容
                                $arrOne = $informationManageData->GetOne($informationId);
                                Template::ReplaceOne($channelTemplateContent, $arrOne);

                                $channelTemplateContent = str_ireplace("{ChannelName}", $channelName, $channelTemplateContent);
                                $channelTemplateContent = str_ireplace("{CurrentChannelName}", $channelName, $channelTemplateContent);


                                //4.根据PublishType和PublishFileName生成目标文件
                                //触发频道id $channelId
                                $timeStart = Control::GetMicroTime();

                                //发布文件名，分类信息id前面加i构成
                                $publishFileName = 'i' . strval($informationId) . '.html';
                                //发布路径，频道id+日期

                                $publishDate = $informationManageData->GetPublishDate($informationId, false);
                                if (strlen($publishDate) > 10) {
                                    //已经有发布日期了
                                    $publishPath = strval($channelId) . '/' . Format::DateStringToSimple($publishDate);
                                } else {
                                    $publishPath = strval($channelId) . '/' . strval(date('Ymd', time()));
                                }


                                //修改发布时间和发布人，只有发布时间为空时才进行操作
                                $informationManageData->ModifyPublishDate(
                                    $informationId,
                                    date("Y-m-d H:i:s", time())
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
                                    PublishLogManageData::TABLE_TYPE_INFORMATION,
                                    $informationId,
                                    "",
                                    "",
                                    $timeEnd - $timeStart,
                                    "now document news id:$informationId add to publish queue result:$result"
                                );
                            }
                        }


                        $nowChannelId = $channelManageData->GetParentChannelId($nowChannelId, false);
                        $rank--;
                    }

                    if ($executeTransfer) {

                        $timeStart = Control::GetMicroTime();
                        self::TransferPublishQueue($publishQueueManageData, $siteId);
                        $timeEnd = Control::GetMicroTime();
                        $publishLogManageData->Create(
                            PublishLogManageData::TRANSFER_TYPE_NO_DEFINE,
                            PublishLogManageData::TABLE_TYPE_INFORMATION,
                            $informationId,
                            "",
                            "",
                            $timeEnd - $timeStart,
                            "now channel id:$nowChannelId transfer publish queue"
                        );
                    }

                    $result = abs(DefineCode::PUBLISH) + self::PUBLISH_INFORMATION_RESULT_FINISHED;

                    //修改状态
                    $informationManageData->ModifyState($informationId, 30); //30为已发

                    //同步发布频道
                    if ($publishChannel) {
                        self::PublishChannel($channelId, $publishQueueManageData);
                    }


                } else {
                    $result = DefineCode::PUBLISH + self::PUBLISH_INFORMATION_RESULT_CHANNEL_ID_ERROR;
                }
            } else {
                $result = DefineCode::PUBLISH + self::PUBLISH_INFORMATION_RESULT_STATE_ERROR;
            }
        } else {
            $result = DefineCode::PUBLISH + self::PUBLISH_INFORMATION_RESULT_DOCUMENT_NEWS_ID_ERROR;
        }

        return $result;

    }

    /**
     * 取消发布（删除已发布的文件）
     * @param PublishQueueManageData $publishQueueManageData 发布队列对象，传出参数，包括了发布文件的结果值
     * @param int $informationId 活动id
     * @param int $siteId 站点id
     * @return int 结果
     */
    protected function CancelPublishInformation(PublishQueueManageData $publishQueueManageData, $informationId, $siteId)
    {
        $result = -1;
        if ($informationId > 0) {
            /**************** 传输日志 ********************/
            $publishLogManageData = new PublishLogManageData();
            $publishLogManageData->Create(
                PublishLogManageData::TRANSFER_TYPE_NO_DEFINE,
                PublishLogManageData::TABLE_TYPE_INFORMATION,
                $informationId,
                "",
                "",
                0,
                "begin cancel"
            );
            $activityManageData = new ActivityManageData();
            $channelId = $activityManageData->GetChannelId($informationId, true);
            $publishDate = $activityManageData->GetPublishDate($informationId, true);
            //发布文件名，资讯id构成
            $publishFileName = 'i' . strval($informationId) . '.html';
            //发布路径，频道id+日期
            $publishPath = strval($channelId) . '/' . Format::DateStringToSimple($publishDate);
            $destinationPath = $publishPath . '/' . $publishFileName;
            $sourcePath = '';
            $publishContent = '';
            $publishQueueManageData->Add($destinationPath, $sourcePath, $publishContent);
            self::DeleteByPublishQueue($publishQueueManageData, $siteId);
        }
        return $result;
    }
}

?>
