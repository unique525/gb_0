<?php

/**
 * 前台Gen的基类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen
 * @author zhangchi
 */
class BasePublicGen extends BaseGen
{

    /**
     * 替换模板内容
     * @param string $templateContent 模板内容
     * @param string $tagTopCountOfPage 默认显示条数（用于列表页分页）
     * @return mixed|string 内容模板
     */
    public function ReplaceTemplate(&$templateContent, $tagTopCountOfPage = "")
    {
        /** 1.处理预加载模板 */

        /** 替换投票调查标签为标准形式 */
        $templateContent = self::ReplaceVoteTagToCmsTag($templateContent);
        /** 2.替换模板内容 */
        $arrCustomTags = Template::GetAllCustomTag($templateContent);
        if (count($arrCustomTags) > 0) {
            $arrTempContents = $arrCustomTags[0];

            foreach ($arrTempContents as $tagContent) {
                //标签id channel_1 document_news_1
                $tagId = Template::GetParamValue($tagContent, "id");
                //关联id
                $relationId = Template::GetParamValue($tagContent, "relation_id");
                //标签类型 channel_list,document_news_list
                $tagType = Template::GetParamValue($tagContent, "type");
                //标签排序方式
                $tagOrder = Template::GetParamValue($tagContent, "order");
                //标签特殊查询条件
                $tagWhere = Template::GetParamValue($tagContent, "where");
                //标签特殊查询条件的值
                $tagWhereValue = Template::GetParamValue($tagContent, "where_value");
                //显示条数
                $withPager = Template::GetParamValue($tagContent, "with_pager"); //是否是列表页列表(是否翻页)
                $tagTopCount = Template::GetParamValue($tagContent, "top");
                if ($tagTopCountOfPage != ""&&$withPager=="1") {   //分页的标签取当前页的条数
                    $tagTopCountOfPage = Format::CheckTopCount($tagTopCountOfPage);
                    if ($tagTopCountOfPage != null) {
                        $tagTopCount = $tagTopCountOfPage;
                    }
                }

                $tagTopCount = Format::CheckTopCount($tagTopCount);
                if ($tagTopCount == null) {

                }
                //显示状态
                $state = Template::GetParamValue($tagContent, "state");

                switch ($tagType) {
                    case Template::TAG_TYPE_ACTIVITY_LIST :
                        $channelId = intval(str_ireplace("channel_", "", $tagId));
                        $templateContent = self::ReplaceTemplateOfActivityList(
                            $templateContent,
                            $channelId,
                            $tagId,
                            $tagContent,
                            $tagTopCount,
                            $tagWhere,
                            $tagWhereValue,
                            $tagOrder,
                            $state
                        );
                        break;
                    case Template::TAG_TYPE_CHANNEL_LIST :
                        $templateContent = self::ReplaceTemplateOfChannelList(
                            $templateContent,
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
                            $templateContent = self::ReplaceTemplateOfDocumentNewsList(
                                $templateContent,
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
                    case Template::TAG_TYPE_PIC_SLIDER_LIST :
                        $channelId = intval(str_ireplace("channel_", "", $tagId));
                        if ($channelId > 0) {
                            $templateContent = self::ReplaceTemplateOfPicSlider(
                                $templateContent,
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
                    case Template::TAG_TYPE_PRODUCT_LIST :
                        $templateContent = self::ReplaceTemplateOfProductList(
                            $templateContent,
                            $tagId,
                            $tagContent,
                            $tagTopCount,
                            $tagWhere,
                            $tagWhereValue,
                            $tagOrder,
                            $state
                        );
                        break;
                    case Template::TAG_TYPE_PRODUCT_PARAM_TYPE_CLASS_LIST :
                        $channelId = intval(str_ireplace("product_param_type_class_", "", $tagId));
                        if ($channelId > 0) {
                            $templateContent = self::ReplaceTemplateOfProductParamTypeClassList($templateContent, $channelId, $tagId, $tagContent, $tagTopCount, $tagWhere, $tagOrder, $state);
                        }
                        break;
                    case Template::TAG_TYPE_PRODUCT_PARAM_TYPE_LIST :
                        $productParamTypeClassId = intval(str_ireplace("product_param_type_", "", $tagId));
                        $productId = $relationId;
                        if ($productParamTypeClassId > 0) {
                            $templateContent = self::ReplaceTemplateOfProductParamTypeList($templateContent, $productParamTypeClassId, $productId, $tagId, $tagContent, $tagTopCount, $tagWhere, $tagOrder, $state);
                        }
                        break;
                    case Template::TAG_TYPE_PRODUCT_PRICE_LIST :
                        $productId = intval(str_ireplace("product_price_", "", $tagId));
                        if ($productId > 0) {
                            $templateContent = self::ReplaceTemplateOfProductPriceList($templateContent, $productId, $tagId, $tagContent, $tagTopCount, $tagWhere, $tagOrder, $state);
                        }
                        break;
                    case Template::TAG_TYPE_PRODUCT_PIC_LIST :
                        $productId = intval(str_ireplace("product_pic_", "", $tagId));
                        if ($productId > 0) {
                            $templateContent = self::ReplaceTemplateOfProductPicList($templateContent, $productId, $tagId, $tagContent, $tagTopCount, $tagWhere, $tagOrder, $state);
                        }
                        break;
                    case Template::TAG_TYPE_USER_EXPLORE_LIST :
                        $tableType = intval(str_ireplace("user_explore_", "", $tagId));
                        if ($tableType > 0) {
                            $templateContent = self::ReplaceTemplateOfUserExploreList($templateContent, $tableType, $tagId, $tagContent, $tagTopCount, $tagWhere, $tagOrder, $state);
                        }
                        break;
                    case Template::TAG_TYPE_NEWSPAPER_ARTICLE_LIST:
                        $newspaperPageId = intval(str_ireplace("newspaper_page_", "", $tagId));

                        if ($newspaperPageId > 0) {
                            $templateContent = self::ReplaceTemplateOfNewspaperArticleList(
                                $templateContent,
                                $newspaperPageId,
                                $tagId,
                                $tagContent,
                                $tagTopCount,
                                $tagWhere,
                                $tagOrder,
                                $state
                            );
                        }
                        break;
                    case Template::TAG_TYPE_NEWSPAPER_ARTICLE_PIC_LIST:
                        $newspaperArticleId = intval(str_ireplace("newspaper_article_", "", $tagId));

                        if ($newspaperArticleId > 0) {
                            $templateContent = self::ReplaceTemplateOfNewspaperArticlePicList(
                                $templateContent,
                                $newspaperArticleId,
                                $tagId,
                                $tagContent,
                                $tagTopCount,
                                $tagWhere,
                                $tagOrder,
                                $state
                            );
                        }
                        break;
                    case Template::TAG_TYPE_NEWSPAPER_ARTICLE_PIC_LIST_SLIDER:
                        $newspaperArticleId = intval(str_ireplace("newspaper_article_slider_", "", $tagId));

                        if ($newspaperArticleId > 0) {
                            $templateContent = self::ReplaceTemplateOfNewspaperArticlePicList(
                                $templateContent,
                                $newspaperArticleId,
                                $tagId,
                                $tagContent,
                                $tagTopCount,
                                $tagWhere,
                                $tagOrder,
                                $state
                            );
                        }
                        break;
                    case Template::TAG_TYPE_RECENT_USER_FAVORITE_LIST:
                        $userId = Control::GetUserId();
                        if ($userId > 0) {
                            $templateContent = self::ReplaceTemplateOfUserFavoriteList(
                                $templateContent,
                                $userId,
                                $tagId,
                                $tagContent,
                                $tagTopCount,
                                $tagWhere
                            );
                        }

                        break;
                    case Template::TAG_TYPE_FORUM_TOPIC_LIST:


                        $siteId = intval(str_ireplace("site_", "", $tagId));

                        if ($siteId > 0) {
                            $templateContent = self::ReplaceTemplateOfForumTopicList(
                                $templateContent,
                                $siteId,
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
                            $templateContent = self::ReplaceTemplateOfVoteItemList(
                                $templateContent,
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
                    case Template::TAG_TYPE_INTERFACE_CONTENT_LIST:
                        $channelId = intval(str_ireplace("channel_", "", $tagId));
                        if ($channelId > 0) {
                            $templateContent = self::ReplaceTemplateOfInterfaceContentList(
                                $templateContent,
                                $channelId,
                                $tagId,
                                $tagContent,
                                $tagTopCount
                            );
                        }
                        break;

                    case Template::TAG_TYPE_MATCH_LIST:
                        $leagueId = intval(str_ireplace("league_", "", $tagId));
                        if ($leagueId > 0) {
                            $templateContent = self::ReplaceTemplateOfMatchList(
                                $templateContent,
                                $leagueId,
                                $tagId,
                                $tagContent,
                                $tagTopCount,
                                $state
                            );
                        }
                        break;


                    case Template::TAG_TYPE_TEAM_LIST:
                        $leagueId = intval(str_ireplace("league_", "", $tagId));
                            $templateContent = self::ReplaceTemplateOfTeamList(
                                $templateContent,
                                $leagueId,
                                $tagId,
                                $tagContent,
                                $tagTopCount,
                                $state
                            );
                        break;


                    case Template::TAG_TYPE_MEMBER_LIST:
                        $teamId = intval(str_ireplace("team_", "", $tagId));
                            $templateContent = self::ReplaceTemplateOfMemberList(
                                $templateContent,
                                $teamId,
                                $tagId,
                                $tagContent,
                                $tagTopCount,
                                $state
                            );
                        break;


                    case Template::TAG_TYPE_GOAL_LIST:
                        $templateContent = self::ReplaceTemplateOfGoalList(
                            $templateContent,
                            $tagId,
                            $tagContent,
                            $tagTopCount,
                            $tagWhere,
                            $tagWhereValue,
                            $tagOrder,
                            $state
                        );
                        break;

                    case Template::TAG_TYPE_RED_YELLOW_CARD_LIST:
                        $templateContent = self::ReplaceTemplateOfRedYellowCardList(
                            $templateContent,
                            $tagId,
                            $tagContent,
                            $tagTopCount,
                            $tagWhere,
                            $tagWhereValue,
                            $tagOrder,
                            $state
                        );
                        break;

                    case Template::TAG_TYPE_MEMBER_CHANGE_LIST:
                        $templateContent = self::ReplaceTemplateOfMemberChangeList(
                            $templateContent,
                            $tagId,
                            $tagContent,
                            $tagTopCount,
                            $tagWhere,
                            $tagWhereValue,
                            $tagOrder,
                            $state
                        );
                        break;

                    case Template::TAG_TYPE_OTHER_EVENT_LIST:
                        $templateContent = self::ReplaceTemplateOfOtherEventList(
                            $templateContent,
                            $tagId,
                            $tagContent,
                            $tagTopCount,
                            $tagWhere,
                            $tagWhereValue,
                            $tagOrder,
                            $state
                        );
                        break;
                }
            }
        }
        return $templateContent;
    }




    /**
     * 替换外部接口列表的内容
     * @param string $channelTemplateContent 要处理的模板内容
     * @param int $channelId 频道id
     * @param string $tagId 标签id
     * @param string $tagContent 标签内容
     * @param int $tagTopCount 显示条数
     * @return mixed|string 内容模板
     */
    private function ReplaceTemplateOfInterfaceContentList(
        $channelTemplateContent,
        $channelId,
        $tagId,
        $tagContent,
        $tagTopCount
    )
    {
        if ($channelId > 0) {



            $arrDocumentNewsList = null;

            $channelPublicData=new ChannelPublicData();
            $interfaceData=new InterfaceData();

            $publishApiUrl=$channelPublicData->GetPublishApiUrl($channelId,true);
            $publishApiType=$channelPublicData->GetPublishApiType($channelId,true);
            $jsonType="icms1";

            switch ($publishApiType) { //外部接口数据格式
                case(0): //json
                    $arrDocumentNewsList = $interfaceData->GetList($publishApiUrl, $jsonType, true);
                    break;
                case(1): //xml
                    $rss = new RSS();
                    $rss->load($publishApiUrl);
                    $arrDocumentNewsList = $rss->getItems();
                    break;
                default: //json
                    $arrDocumentNewsList = $interfaceData->GetList($publishApiUrl, $jsonType, true);
                    break;
            }

            $array_split=explode(",",$tagTopCount);
            if(count($array_split)==1){
                $arrDocumentNewsList=array_slice($arrDocumentNewsList,0,$array_split[0]);//截取前x条
            }elseif(count($array_split)==2){
                $arrDocumentNewsList=array_slice($arrDocumentNewsList,$array_split[0],$array_split[1]);//截取x,x条
            }

            //$activityPublicData = new ActivityPublicData();

            /*/排序方式
            switch ($tagOrder) {
                case "new":
                    $orderBy = 0;
                    break;
                case "hit":
                    $orderBy = 1;
                    break;
                default:
                    $orderBy = 0;
                    break;
            }

            switch ($tagWhere) {
                case "new":
                    $arrDocumentNewsList = $activityPublicData->GetNewList($channelId, $tagTopCount, $state);
                    break;
                default :
                    $arrDocumentNewsList = $activityPublicData->GetList($channelId, $tagTopCount, $state, $orderBy);
                    break;
            }*/

            if (!empty($arrDocumentNewsList)) {
                Template::ReplaceList($tagContent, $arrDocumentNewsList, $tagId);
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
     * 替换活动列表的内容
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
    private function ReplaceTemplateOfActivityList(
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

            //资讯默认只显示已发状态的新闻
            $state = DocumentNewsData::STATE_PUBLISHED;


            $arrDocumentNewsList = null;
            $activityPublicData = new ActivityPublicData();

            //排序方式
            switch ($tagOrder) {
                case "new":
                    $orderBy = 0;
                    break;
                case "hit":
                    $orderBy = 1;
                    break;
                default:
                    $orderBy = 0;
                    break;
            }

            switch ($tagWhere) {
                case "new":
                    $arrDocumentNewsList = $activityPublicData->GetNewList($channelId, $tagTopCount, $state);
                    break;
                default :
                    $arrDocumentNewsList = $activityPublicData->GetList($channelId, $tagTopCount, $state, $orderBy);
                    break;
            }
            if (!empty($arrDocumentNewsList)) {
                Template::ReplaceList($tagContent, $arrDocumentNewsList, $tagId);
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
        }else{
            $siteId = self::GetSiteIdByDomain();
        }


        if ($siteId > 0 || $channelId > 0) {
            $arrChannelList = null;
            $arrChannelChildList = array();
            $arrItemListForChildTag=array();
            $arrChannelThirdList = null;
            $tableIdName = "ChannelId";
            $parentIdName = "ChannelId";
            $thirdTableIdName = "ChannelId";
            $thirdParentIdName = "ParentId";


            switch ($tagWhere) {
                case "parent":

                    if ($tagTopCount <= 0) {
                        $tagTopCount = 100;
                    }
                    $channelPublicData = new ChannelPublicData();

                    $arrChannelList = $channelPublicData->GetListByParentId(
                        $tagTopCount,
                        $channelId,
                        $tagOrder
                    );

                    $sbChildChannelId = '';
                    $sbThirdChannelId = '';
                    if (count($arrChannelList) > 0) {

                        for ($i = 0; $i < count($arrChannelList); $i++) {
                            $sbChildChannelId .= ',' . $arrChannelList[$i]["ChannelId"];
                        }

                        if (strpos($sbChildChannelId, ',') == 0) {
                            $sbChildChannelId = substr($sbChildChannelId, 1);
                        }

                        if (strlen($sbChildChannelId) > 0) {
                            //echo $sbChildChannelId;
                            //二级
                            $arrChannelChildList = $channelPublicData->GetListByParentId(
                                $tagTopCount,
                                $sbChildChannelId,
                                $tagOrder
                            );

                            /*** 处理子循环 ***/
                            if((Template::GetAllCustomTag($tagContent, "child"))!=null){
                                $tagTopCountChild = Template::GetParamValue($tagContent, "top_child");  // top_child="xx"  xx=显示条数
                                switch($tagId){
                                    case "document_news_list":
                                        $documentNewsPublicData=new DocumentNewsPublicData();
                                        $state=DocumentNewsData::STATE_PUBLISHED;
                                        foreach($arrChannelList as $oneChannel){
                                            $itemListInOneChannel = $documentNewsPublicData->GetNewList($siteId, $oneChannel["ChannelId"], $tagTopCountChild,$state);  //
                                            if($itemListInOneChannel==null){
                                                $itemListInOneChannel=array();
                                            }
                                            $arrItemListForChildTag=array_merge($arrItemListForChildTag,$itemListInOneChannel);
                                        }
                                        break;
                                    default:
                                        $documentNewsPublicData=new DocumentNewsPublicData();
                                        $state=DocumentNewsData::STATE_PUBLISHED;
                                        foreach($arrChannelList as $oneChannel){
                                            $itemListInOneChannel = $documentNewsPublicData->GetNewList($siteId, $oneChannel["ChannelId"], $tagTopCountChild,$state);  //
                                            if($itemListInOneChannel==null){
                                                $itemListInOneChannel=array();
                                            }
                                            $arrItemListForChildTag=array_merge($arrItemListForChildTag,$itemListInOneChannel);
                                        }
                                        break;
                                }
                            }



                            if (count($arrChannelChildList) > 0) {
                                for ($j = 0; $j < count($arrChannelChildList); $j++) {
                                    $sbThirdChannelId .= ',' . $arrChannelChildList[$j]["ChannelId"];
                                }


                                if (strpos($sbThirdChannelId, ',') == 0) {
                                    $sbThirdChannelId = substr($sbThirdChannelId, 1);
                                }

                                if (strlen($sbThirdChannelId) > 0) {
                                    //三级
                                    $arrChannelThirdList = $channelPublicData->GetListByParentId(
                                        $tagTopCount,
                                        $sbThirdChannelId,
                                        $tagOrder
                                    );
                                    if (is_array($arrChannelThirdList) && count($arrChannelThirdList) > 0) {
                                        for ($k = 0; $k < count($arrChannelThirdList); $k++) {
                                            $sbThirdChannelId .= ',' . $arrChannelThirdList[$k]["ChannelId"];
                                        }
                                    }
                                }
                            }

                        }
                    }

                    break;
                case "rank":
                    if ($siteId > 0) {
                        $rank = intval($tagWhereValue);
                        $channelPublicData = new ChannelPublicData();
                        $arrChannelList = $channelPublicData->GetListByRank(
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



            if (!empty($arrChannelList)) {
                $tagName = Template::DEFAULT_TAG_NAME;
                Template::ReplaceList(
                    $tagContent,
                    $arrChannelList,
                    $tagId,
                    $tagName,
                    $arrItemListForChildTag,
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

            //资讯默认只显示已发状态的新闻
            $state = DocumentNewsData::STATE_PUBLISHED;
            $siteId = self::GetSiteIdByDomain();

            $arrDocumentNewsList = null;
            $documentNewsPublicData = new DocumentNewsPublicData();

            //排序方式
            switch ($tagOrder) {
                case "new":
                    $orderBy = 0;
                    break;
                case "hit":
                    $orderBy = 1;
                    break;
                default:
                    $orderBy = 0;
                    break;
            }

            switch ($tagWhere) {
                case "new":
                    $arrDocumentNewsList = $documentNewsPublicData->GetNewList($siteId, $channelId, $tagTopCount, $state, 0, 0, true);
                    break;
                case "child":
                    $channelPublicData=new ChannelPublicData();
                    $strChildrenChannelId=$channelPublicData->GetChildrenChannelId($channelId,true);
                    $arrDocumentNewsList = $documentNewsPublicData->GetListOfChild($strChildrenChannelId, $channelId, $tagTopCount, $state, $orderBy, 0, true);
                    break;
                case "grandson":
                    $channelPublicData=new ChannelPublicData();
                    $strChildrenChannelId=$channelPublicData->GetChildrenChannelId($channelId,true);
                    $arrDocumentNewsList = $documentNewsPublicData->GetListOfChild($strChildrenChannelId, $channelId, $tagTopCount, $state, $orderBy, 0, true);
                    break;
                case "rec_level_child":
                    $channelPublicData=new ChannelPublicData();
                    $strChildrenChannelId=$channelPublicData->GetChildrenChannelId($channelId,true);
                    $arrDocumentNewsList = $documentNewsPublicData->GetListOfRecLevelChild($strChildrenChannelId,$channelId, $tagTopCount, $state, "", $orderBy, true);
                    break;
                case "rec_level_grandson":
                    $channelPublicData=new ChannelPublicData();
                    $strChildrenChannelId=$channelPublicData->GetChildrenChannelId($channelId,true);
                    $arrDocumentNewsList = $documentNewsPublicData->GetListOfRecLevelChild($strChildrenChannelId,$channelId, $tagTopCount, $state, "", $orderBy, true);
                    break;
                case "rec_level_belong_site":
                    $recLevel = intval($tagWhereValue);
                    if ($channelId > 0&&$recLevel > 0) {
                        $belongSiteId = $channelId;//此类方法只根据站点id取数据，站点id实际是借道channelId参数传入的
                        $arrDocumentNewsList = $documentNewsPublicData->GetListOfRecLevelBelongSite($belongSiteId, $recLevel, $tagTopCount ,$orderBy, true);
                    }
                    break;
                case "day_belong_site":
                    $recLevel = intval($tagWhereValue);
                    if ($channelId > 0&&$recLevel > 0) {
                        $belongSiteId = $channelId;//此类方法只根据站点id取数据，站点id实际是借道channelId参数传入的
                        $arrDocumentNewsList = $documentNewsPublicData->GetListOfDayBelongSite($belongSiteId, $recLevel, $tagTopCount ,$orderBy, true);
                    }
                    break;
                default :
                    $arrDocumentNewsList = $documentNewsPublicData->GetList($channelId, $tagTopCount, $state, $orderBy, true);
                    break;
            }
            if (!empty($arrDocumentNewsList)) {
                Template::ReplaceList($tagContent, $arrDocumentNewsList, $tagId);
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
            $picSliderPublicData = new PicSliderPublicData();

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
                    $arrList = $picSliderPublicData->GetList($channelId, $tagTopCount, $state, $orderBy);
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
     * 替换产品列表的内容
     * @param string $templateContent 要处理的模板内容
     * @param string $tagId 标签id
     * @param string $tagContent 标签内容
     * @param int $tagTopCount 显示条数
     * @param string $tagWhere 查询方式
     * @param string $tagWhereValue 查询条件的值
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
        $tagWhereValue,
        $tagOrder,
        $state
    )
    {
        $arrProductList = null;
        $productPublicData = new ProductPublicData();
        switch ($tagWhere) {
            case "OwnChannel":
                $channelId = intval(str_ireplace("product_", "", $tagId));
                if ($channelId > 0) {
                    $arrProductList = $productPublicData->GetListByChannelId($channelId, $tagOrder, $tagTopCount);
                }
                break;
            case "BelongChannel":
                $channelId = intval(str_ireplace("product_", "", $tagId));
                if ($channelId > 0) {
                    $belongChannelId = self::GetOwnChannelIdAndChildChannelId($channelId);
                    $arrProductList = $productPublicData->GetListByChannelId($belongChannelId, $tagOrder, $tagTopCount);
                }
                break;
            case "RecLevel":
                $siteId = intval(str_ireplace("product_", "", $tagId));
                $recLevel = intval($tagWhereValue);
                if ($recLevel > 0) {
                    $arrProductList = $arrProductList = $productPublicData->GetListByRecLevel($siteId, $recLevel, $tagOrder, $tagTopCount);
                }
                break;
            case "SaleCount":
                $arrProductList = $productPublicData->GetListBySaleCount($tagOrder, $tagTopCount);
                break;
            case "BelongChannelDiscount":
                $channelId = intval(str_ireplace("product_", "", $tagId));
                if ($channelId > 0) {
                    $OwnChannelAndChildChannelId = self::GetOwnChannelIdAndChildChannelId($channelId);
                    $arrProductList = $productPublicData->GetDiscountListByChannelId($OwnChannelAndChildChannelId, $tagOrder, $tagTopCount);
                }
                break;
            default :
                $channelId = intval(str_ireplace("product_", "", $tagId));
                if ($channelId > 0) {
                    $AllChannelId = parent::GetOwnChannelIdAndChildChannelId($channelId);
                    $arrProductList = $productPublicData->GetListByChannelId($AllChannelId, $tagOrder, $tagTopCount);
                }
        }
        if (!empty($arrProductList)) {
            Template::ReplaceList($tagContent, $arrProductList, $tagId);
            //把对应ID的CMS标记替换成指定内容
            $templateContent = Template::ReplaceCustomTag($templateContent, $tagId, $tagContent);
        } else {
            //替换为空
            $templateContent = Template::ReplaceCustomTag($templateContent, $tagId, '');
        }
        return $templateContent;
    }

    /**
     * 替换产品参数类别列表的内容
     * @param string $templateContent 要处理的模板内容
     * @param int $channelId 频道id
     * @param string $tagId 标签id
     * @param string $tagContent 标签内容
     * @param int $tagTopCount 显示条数
     * @param string $tagWhere 查询方式
     * @param string $tagOrder 排序方式
     * @param int $state 状态
     * @return mixed|string 内容模板
     */
    private function ReplaceTemplateOfProductParamTypeClassList(
        $templateContent,
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
            $arrProductParamTypeClassList = null;
            $productParamTypeClassPublicData = new ProductParamTypeClassPublicData();
            switch ($tagWhere) {
                case "new":
                    $arrProductParamTypeClassList = $productParamTypeClassPublicData->GetList($channelId, $tagOrder, $tagTopCount);
                    break;
                default :
                    //new
                    $arrProductParamTypeClassList = $productParamTypeClassPublicData->GetList($channelId, $tagOrder, $tagTopCount);
                    break;
            }
            if (!empty($arrProductParamTypeClassList)) {
                Template::ReplaceList($tagContent, $arrProductParamTypeClassList, $tagId);
                //把对应ID的CMS标记替换成指定内容
                $templateContent = Template::ReplaceCustomTag($templateContent, $tagId, $tagContent);
            } else Template::RemoveCustomTag($templateContent, $tagId);
        } else Template::RemoveCustomTag($templateContent, $tagId);

        return $templateContent;
    }

    /**
     * 替换产品参数类型列表的内容
     * @param string $templateContent 要处理的模板内容
     * @param int $productParamTypeClassId 产品参数类别ID
     * @param int $productId 产品ID
     * @param string $tagId 标签id
     * @param string $tagContent 标签内容
     * @param int $tagTopCount 显示条数
     * @param string $tagWhere 查询方式
     * @param string $tagOrder 排序方式
     * @param int $state 状态
     * @return mixed|string 内容模板
     */
    private function ReplaceTemplateOfProductParamTypeList(
        $templateContent,
        $productParamTypeClassId,
        $productId,
        $tagId,
        $tagContent,
        $tagTopCount,
        $tagWhere,
        $tagOrder,
        $state
    )
    {
        if ($productParamTypeClassId > 0 && $productId > 0) {
            $arrProductParamTypeList = null;
            $productParamTypePublicData = new ProductParamTypePublicData();
            switch ($tagWhere) {
                case "new":
                    $arrProductParamTypeList = $productParamTypePublicData->GetListWithValue($productParamTypeClassId, $productId, $tagOrder, $tagTopCount);
                    break;
                default :
                    //new
                    $arrProductParamTypeList = $productParamTypePublicData->GetListWithValue($productParamTypeClassId, $productId, $tagOrder, $tagTopCount);
                    break;
            }
            if (!empty($arrProductParamTypeList)) {
                self::MappingParamTypeValue($arrProductParamTypeList);
                Template::ReplaceList($tagContent, $arrProductParamTypeList, $tagId);
                //把对应ID的CMS标记替换成指定内容
                $templateContent = Template::ReplaceCustomTag($templateContent, $tagId, $tagContent);
            } else Template::RemoveCustomTag($templateContent, $tagId);
        } else Template::RemoveCustomTag($templateContent, $tagId);

        return $templateContent;
    }

    /**
     * 根据产品参数类型ID映射产品参数对应值到指定字段ParamTypeValue
     * @param array $arrProductParamTypeList 产品参数原始数组
     * @return string 产品参数值
     */
    public function MappingParamTypeValue(&$arrProductParamTypeList)
    {
        for ($i = 0; $i < count($arrProductParamTypeList); $i++) {
            $paramValueType = $arrProductParamTypeList[$i]["ParamValueType"];
            switch ($paramValueType) {
                case "0":
                    $paramTypeMappingValue = $arrProductParamTypeList[$i]["ShortStringValue"];
                    break;
                case "1":
                    $paramTypeMappingValue = $arrProductParamTypeList[$i]["LongStringValue"];
                    break;
                case "2":
                    $paramTypeMappingValue = $arrProductParamTypeList[$i]["MaxStringValue"];
                    break;
                case "3":
                    $paramTypeMappingValue = $arrProductParamTypeList[$i]["FloatValue"];
                    break;
                case "4":
                    $paramTypeMappingValue = $arrProductParamTypeList[$i]["MoneyValue"];
                    break;
                case "5":
                    $paramTypeMappingValue = $arrProductParamTypeList[$i]["UrlValue"];
                    break;
                case "6":
                    $paramTypeMappingValue = $arrProductParamTypeList[$i]["ShortStringValue"];
                    break;
                default:
                    $paramTypeMappingValue = "";
                    break;
            }
            $arrProductParamTypeList[$i]["ParamTypeValue"] = $paramTypeMappingValue;
        }
    }

    /**
     * 替换产品价格列表的内容
     * @param string $templateContent 要处理的模板内容
     * @param int $productId 产品id
     * @param string $tagId 标签id
     * @param string $tagContent 标签内容
     * @param int $tagTopCount 显示条数
     * @param string $tagWhere 查询方式
     * @param string $tagOrder 排序方式
     * @param int $state 状态
     * @return mixed|string 内容模板
     */
    private function ReplaceTemplateOfProductPriceList(
        $templateContent,
        $productId,
        $tagId,
        $tagContent,
        $tagTopCount,
        $tagWhere,
        $tagOrder,
        $state
    )
    {
        if ($productId > 0) {
            $arrProductPriceList = null;
            $productPricePublicData = new ProductPricePublicData();
            switch ($tagWhere) {
                case "normal":
                    $arrProductPriceList = $productPricePublicData->GetList($productId, $tagOrder, $tagTopCount);
                    break;
                default :
                    //new
                    $arrProductPriceList = $productPricePublicData->GetList($productId, $tagOrder, $tagTopCount);
                    break;
            }
            if (!empty($arrProductPriceList)) {
                Template::ReplaceList($tagContent, $arrProductPriceList, $tagId);
                //把对应ID的CMS标记替换成指定内容
                $templateContent = Template::ReplaceCustomTag($templateContent, $tagId, $tagContent);
            } else Template::RemoveCustomTag($templateContent, $tagId);
        } else Template::RemoveCustomTag($templateContent, $tagId);

        return $templateContent;
    }

    /**
     * 替换产品图片列表的内容
     * @param string $templateContent 要处理的模板内容
     * @param int $productId 产品id
     * @param string $tagId 标签id
     * @param string $tagContent 标签内容
     * @param int $tagTopCount 显示条数
     * @param string $tagWhere 查询方式
     * @param string $tagOrder 排序方式
     * @param int $state 状态
     * @return mixed|string 内容模板
     */
    private function ReplaceTemplateOfProductPicList(
        $templateContent,
        $productId,
        $tagId,
        $tagContent,
        $tagTopCount,
        $tagWhere,
        $tagOrder,
        $state
    )
    {
        if ($productId > 0) {
            $arrProductPicList = null;
            $productPicPublicData = new ProductPicPublicData();
            switch ($tagWhere) {
                case "channel":
                    $arrProductPicList = $productPicPublicData->GetList($productId, $tagOrder, $tagTopCount);
                    break;
                default :
                    //new
                    $arrProductPicList = $productPicPublicData->GetList($productId, $tagOrder, $tagTopCount);
                    break;
            }
            if (!empty($arrProductPicList)) {
                Template::ReplaceList($tagContent, $arrProductPicList, $tagId);
                //把对应ID的CMS标记替换成指定内容
                $templateContent = Template::ReplaceCustomTag($templateContent, $tagId, $tagContent);
            } else Template::RemoveCustomTag($templateContent, $tagId);
        }

        return $templateContent;
    }

    /**
     * 替换用户浏览记录的内容
     * @param string $templateContent 要处理的模板内容
     * @param int $tableType 对应表Id
     * @param string $tagId 标签id
     * @param string $tagContent 标签内容
     * @param int $tagTopCount 显示条数
     * @param string $tagWhere 查询方式
     * @param string $tagOrder 排序方式
     * @param int $state 状态
     * @return mixed|string 内容模板
     */
    private function ReplaceTemplateOfUserExploreList(
        $templateContent,
        $tableType,
        $tagId,
        $tagContent,
        $tagTopCount,
        $tagWhere,
        $tagOrder,
        $state
    )
    {
        $userId = Control::GetUserId();
        if ($userId > 0) {
            if ($tableType > 0) {
                switch ($tagWhere) {
                    default :
                        //new
                        $userExploreCollection = self::GetUserExploreArrayFromCookieByUserId($userId);
                        break;
                }

                if (count($userExploreCollection->UserExplores) > 0) {

                    //限制$tagTopCount
                    $arrList = array();

                    if ($tagTopCount > count($userExploreCollection->UserExplores)) {
                        $tagTopCount = count($userExploreCollection->UserExplores);
                    }


                    if ($tagTopCount > 0) {
                        for ($x = 0; $x < $tagTopCount; $x++) {

                            $arrList[] = $userExploreCollection->UserExplores[$x];

                        }
                    }


                    Template::ReplaceList($tagContent, $arrList, $tagId);
                    //把对应ID的CMS标记替换成指定内容
                    $templateContent = Template::ReplaceCustomTag($templateContent, $tagId, $tagContent);
                } else {
                    Template::RemoveCustomTag($templateContent, $tagId);
                }
            } else {
                Template::RemoveCustomTag($templateContent, $tagId);
            }
        } else {
            $showMessage = Language::Load("user_explore", 1);
            $showMessage = str_ireplace("{ReturnUrl}", urlencode($_SERVER["REQUEST_URI"]), $showMessage);
            $templateContent = Template::ReplaceCustomTag($templateContent, $tagId, $showMessage);
        }

        return $templateContent;
    }

    /**
     * 根据会员Id从会员浏览记录COOKIE中取得用户浏览记录数组
     * @param int $userId 会员Id
     * @return UserExploreCollection 取得会员浏览记录对应数组
     */
    protected function GetUserExploreArrayFromCookieByUserId($userId)
    {
        $userExploreCollection = new UserExploreCollection();
        if ($userId > 0) {
            if (is_string(Control::GetUserExploreCookie($userId))){
                if (strlen(Control::GetUserExploreCookie($userId)) > 0) {
                    //读取cookie
                    $cookieStr = Control::GetUserExploreCookie($userId);
                    //字符串转回原来的数组
                    $userExploreCollection->UserExplores = $cookieStr;
                }
            }

        }
        return $userExploreCollection;
    }

    /**
     * 替换电子报文章列表的内容
     * @param string $channelTemplateContent 要处理的模板内容
     * @param int $newspaperPageId 电子报版面id
     * @param string $tagId 标签id
     * @param string $tagContent 标签内容
     * @param int $tagTopCount 显示条数
     * @param string $tagWhere 查询方式
     * @param string $tagOrder 排序方式
     * @param int $state 状态
     * @return mixed|string 内容模板
     */
    private function ReplaceTemplateOfNewspaperArticleList(
        $channelTemplateContent,
        $newspaperPageId,
        $tagId,
        $tagContent,
        $tagTopCount,
        $tagWhere,
        $tagOrder,
        $state
    )
    {
        if ($newspaperPageId > 0) {

            //默认只显示已发状态的新闻
            $state = 0;

            $arrList = null;
            $newspaperArticlePublicData = new NewspaperArticlePublicData();

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
                    $arrList = $newspaperArticlePublicData->GetList($newspaperPageId, $tagTopCount, $state, $orderBy);
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
     * 替换电子报文章图片列表的内容
     * @param string $channelTemplateContent 要处理的模板内容
     * @param int $newspaperArticleId 电子报文章id
     * @param string $tagId 标签id
     * @param string $tagContent 标签内容
     * @param int $tagTopCount 显示条数
     * @param string $tagWhere 查询方式
     * @param string $tagOrder 排序方式
     * @param int $state 状态
     * @return mixed|string 内容模板
     */
    private function ReplaceTemplateOfNewspaperArticlePicList(
        $channelTemplateContent,
        $newspaperArticleId,
        $tagId,
        $tagContent,
        $tagTopCount,
        $tagWhere,
        $tagOrder,
        $state
    )
    {
        if ($newspaperArticleId > 0) {


            $arrList = null;
            $newspaperArticlePicPublicData = new NewspaperArticlePicPublicData();

            switch ($tagWhere) {
                default :
                    $arrList = $newspaperArticlePicPublicData->GetList($newspaperArticleId);
                    break;
            }

            if (!empty($arrList)) {

                if (stripos($tagId, "newspaper_article_slider_") !== false) { //轮换图
                    if (count($arrList) > 1) { //只显示多图
                        Template::ReplaceList($tagContent, $arrList, $tagId);
                    } else {
                        $tagContent = "";
                    }
                } elseif (stripos($tagId, "newspaper_article_") !== false) { //单图
                    if (count($arrList) < 2) { //只显示单图
                        Template::ReplaceList($tagContent, $arrList, $tagId);
                    } else {
                        $tagContent = "";
                    }
                }

                //Template::ReplaceList($tagContent, $arrList, $tagId);
                //把对应ID的CMS标记替换成指定内容
                $channelTemplateContent = Template::ReplaceCustomTag($channelTemplateContent, $tagId, $tagContent);
            } else {
                //替换为空
                $channelTemplateContent = Template::ReplaceCustomTag($channelTemplateContent, $tagId, '');
            }
        }

        return $channelTemplateContent;
    }

    private function ReplaceTemplateOfUserFavoriteList(
        $channelTemplateContent,
        $userId,
        $tagId,
        $tagContent,
        $tagTopCount,
        $tagWhere
    )
    {
        if ($userId > 0) {
            $siteId = self::GetSiteIdByDomain();
            $arrList = null;
            $userFavoritePublicData = new UserFavoritePublicData();

            switch ($tagWhere) {
                default :
                    $pageBegin = 1;
                    $pageSize = $tagTopCount;
                    $arrList = $userFavoritePublicData->GetListForRecentUserFavorite($userId, $siteId, $pageBegin, $pageSize, $allCount);
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
     * @param $channelTemplateContent
     * @param $siteId
     * @param $tagId
     * @param $tagContent
     * @param $tagTopCount
     * @param $tagWhere
     * @param $tagOrder
     * @param $state
     * @return string
     */
    private function ReplaceTemplateOfForumTopicList(
        $channelTemplateContent,
        $siteId,
        $tagId,
        $tagContent,
        $tagTopCount,
        $tagWhere,
        $tagOrder,
        $state
    ){
        if ($siteId > 0) {


            $arrList = null;
            $forumTopicPublicData = new ForumTopicPublicData();

            switch ($tagWhere) {
                case "new" :
                    $withCache = true;
                    $arrList = $forumTopicPublicData->GetListOfNew(
                        $siteId,
                        $tagTopCount,
                        $withCache
                    );
                    break;
                case "hot" :
                    $withCache = true;
                    $arrList = $forumTopicPublicData->GetListOfHot(
                        $siteId,
                        $tagTopCount,
                        $withCache
                    );
                    break;
                case "best" :
                    $withCache = true;
                    $arrList = $forumTopicPublicData->GetListOfBest(
                        $siteId,
                        $tagTopCount,
                        $withCache
                    );
                    break;
                case "top" :
                    $withCache = true;
                    $arrList = $forumTopicPublicData->GetListOfTop(
                        $siteId,
                        $tagTopCount,
                        $withCache
                    );
                    for($i=0;$i<count($arrList);$i++){
                        $arrList[$i]["ForumTopicTitle"] = Format::FormatHtmlTag($arrList[$i]["ForumTopicTitle"]);
                    }
                    break;
            }

            if (!empty($arrList)) {

                Template::ReplaceList($tagContent, $arrList, $tagId);
                //$tagContent = Format::FormatHtmlTag($tagContent);

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
                $votePublicData = new VotePublicData();
                //如果投票标记没有指定模板，则启用数据库配置的模板
                if ($tempName == null) {
                    $tempName = $votePublicData->GetTemplateName($voteId, false);
                    if ($tempName == null || $tempName == '') //如果数据库没有配置模板，默认启用普通模板
                        $tempName = "normal_1";
                }
                //加载对应类型模板
                $templateFileUrl = "vote/vote_front_" . $tempName . ".html";
                $templateName = "default";
                $templatePath = "front_template";
                $voteTemp = Template::Load($templateFileUrl, $templateName, $templatePath);
                $voteTemp = str_ireplace("{VoteId}",$voteId, $voteTemp);
                //根据是否启用验证码，决定是否显示验证码输入选项
                $isCheckCode = $votePublicData->GetIsCheckCode($voteId, false);
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
                if ($itemMarginLeft== null)
                    $itemMarginLeft= "40px";
                $itemTitleDisplay = Template::GetParamValue($tagContent, "item_title_display"); //是否显示题目标题
                $btnDisplay = Template::GetParamValue($tagContent, "btn_display"); //是否显示投票按钮
                $tagContent = str_ireplace("{ItemWidth}",$itemWidth, $tagContent);
                $tagContent = str_ireplace("{ItemHeight}",$itemHeight, $tagContent);
                $tagContent = str_ireplace("{ItemMarginLeft}",$itemMarginLeft, $tagContent);
                $tagContent = str_ireplace("{ItemTitleDisplay}",$itemTitleDisplay, $tagContent);
                $tagContent = str_ireplace("{BtnDisplay}",$btnDisplay, $tagContent);
            }
            $arrVoteItemList = null;
            $arrVoteSelectItemList = array();
            $tableIdName = "VoteItemId";
            $parentIdName = "VoteItemId";
            if ($tagTopCount <= 0) {
                $tagTopCount = null;
            }
            $state=VoteData::STATE_NORMAL;
            $voteItemManageData = new VoteItemManageData();
            $arrVoteItemList = $voteItemManageData->GetList($voteId,$state,$tagOrder,$tagTopCount); //读取投票调查题目

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
                    //二级(投票选项)

                    //投票选项链接的table type
                    $sbVoteItemTableType = Template::GetParamValue($tagContent, "table_type");

                    //投票选项的top count
                    $topCountOfSelectItem = Template::GetParamValue($tagContent, "child_top_count");

                    $voteSelectItemPublicData = new VoteSelectItemPublicData();
                    $tagTopCountOfSelectItem=$topCountOfSelectItem;



                    //链接table id的字段
                    switch($sbVoteItemTableType){
                        case 1: //document news
                            $arrVoteSelectItemList = $voteSelectItemPublicData->GetListWithDocumentNews(
                                $sbVoteItemId,
                                $state,
                                $tagOrder,
                                $tagTopCountOfSelectItem
                            );
                            break;
                        default; //默认无链接
                            $beginDate=Control::GetRequest("begin_date","");
                            $endDate=Control::GetRequest("end_date","");
                            $arrVoteSelectItemList = $voteSelectItemPublicData->GetList(
                                $sbVoteItemId,
                                $state,
                                $tagOrder,
                                $tagTopCountOfSelectItem,
                                $beginDate,
                                $endDate
                            );
                            break;
                    }
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

                //生成投票专用js
                $isAddJs=strpos($templateContent,'{VotePretempJs'.$voteId.'}');
                if($isAddJs){
                    $pretempJsContent = Template::Load('vote/vote_front_js_pretemp.html', 'default', 'front_template');
                    $pretempJsContent = str_ireplace('{VoteId}', $voteId, $pretempJsContent);
                    $templateContent = str_ireplace('{VotePretempJs'.$voteId.'}', $pretempJsContent, $templateContent);
                }

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
     * 根据temp参数（可以是GET也可以是POST）取得动态模板的内容
     * @param string $defaultTemp 默认模板
     * @param int $siteId 默认从域名取，可以不传入，site id可以为0，为0时，不加入siteid的条件查询
     * @param string $forceTemp 强制指定的模板名称
     * @param int $templateMode 传出参数，最后加载的模板类型 0:pc,1:mobile,2:pad,3:tv
     * @return string 模板内容
     */
    protected function GetDynamicTemplateContent($defaultTemp = "", $siteId = 0, $forceTemp = "", &$templateMode = 0)
    {


        $result = "";
        if ($siteId < 0) {
            $siteId = self::GetSiteIdByDomain();
        }

        if(strlen($forceTemp)>0){
            $channelTemplateTag = $forceTemp;
        }else{
            $channelTemplateTag = Control::PostOrGetRequest("temp", "");

            if (strlen($channelTemplateTag) <= 0) {
                $channelTemplateTag = $defaultTemp;
            }
        }

        if (strlen($channelTemplateTag) > 0) {

            $channelTemplateType = ChannelTemplateData::CHANNEL_TEMPLATE_TYPE_DYNAMIC;
            $channelTemplatePublicData = new ChannelTemplatePublicData();

            if (self::IsMobile()) {
                $templateMode = 1;
                $result = $channelTemplatePublicData->GetChannelTemplateContentForMobileForDynamic(
                    $siteId, $channelTemplateType, $channelTemplateTag, false);

                //如果不存在手机模板，则加载默认电脑模板
                if (strlen($result) <= 0) {
                    $result = $channelTemplatePublicData->GetChannelTemplateContentForDynamic(
                        $siteId, $channelTemplateType, $channelTemplateTag, false);

                    //本站模板不存在，则加载全系统模板
                    //TODO 需要研究

                }
            } elseif (self::IsPad()) {
                $templateMode = 2;
                $result = $channelTemplatePublicData->GetChannelTemplateContentForPadForDynamic(
                    $siteId, $channelTemplateType, $channelTemplateTag, false);

                //如果不存在pad模板，则加载默认电脑模板
                if (strlen($result) <= 0) {
                    $result = $channelTemplatePublicData->GetChannelTemplateContentForDynamic(
                        $siteId, $channelTemplateType, $channelTemplateTag, false);
                }
            } else {
                $templateMode = 0;
                $result = $channelTemplatePublicData->GetChannelTemplateContentForDynamic(
                    $siteId, $channelTemplateType, $channelTemplateTag, false);
            }
        }
        return $result;
    }

    /**
     * 通过子域名取得站点id
     * @return int 站点id
     */
    protected function GetSiteIdByDomain()
    {
        $host = strtolower($_SERVER['HTTP_HOST']);
        $host = str_ireplace("http://", "", $host);
        if ($host === "localhost" || $host === "127.0.0.1") {
            $siteId = 1;
        } else {

            //先查绑定的一级域名
            $domain = Control::GetDomain(strtolower($_SERVER['HTTP_HOST']));
            $sitePublicData = new SitePublicData();
            $siteId = $sitePublicData->GetSiteIdByBindDomain($domain, true);
            if ($siteId <= 0) {
                //查子域名
                $arrSubDomain = explode(".", $host);
                if (count($arrSubDomain) > 0) {
                    $subDomain = $arrSubDomain[0];
                    if (strlen($subDomain) > 0) {
                        $siteId = $sitePublicData->GetSiteId($subDomain, true);
                    }
                }
            }

        }
        return $siteId;
    }

    /**
     * 生成会员浏览记录COOKIE
     * @param int $userId 会员Id
     * @param int $tableId 对应表Id
     * @param int $tableType 对应表类型
     * @param string $url 浏览页面Url地址
     * @param string $title 标题
     * @param string $titlePic 题图地址
     * @param string $price 价格
     */
    protected function CreateUserExploreCookie($userId, $tableId, $tableType, $url, $title, $titlePic, $price)
    {

        if ($userId > 0) {

            $userExplore = new UserExplore();
            $userExploreCollection = new UserExploreCollection();
            if (is_string(Control::GetUserExploreCookie($userId))){
                if (strlen(Control::GetUserExploreCookie($userId)) > 0) {
                    //读取cookie
                    $cookieStr = Control::GetUserExploreCookie($userId);
                    $userExploreCollection->UserExplores = $cookieStr;
                } else {

                }
            }


            //将当前访问信息保存到数组中
            $userExplore->TableId = $tableId;
            $userExplore->TableType = $tableType;
            $userExplore->UserId = $userId;
            $userExplore->Url = $url;
            $userExplore->Title = $title;
            $userExplore->TitlePic = $titlePic;
            $userExplore->Price = $price;
            $userExploreCollection->AddField($userExplore->ConvertToArray());
            //存储为COOKIE


            Control::SetUserExploreCookie($userId, $userExploreCollection->UserExplores, 100);
        }

    }



    /**
     * 替换模板中的统计代码 {VisitCode}
     * @param string $templateContent 模板
     * @param int $siteId 站点id
     * @param int $channelId 频道id
     * @param int $tableType 表类型 (定义在 VisitData 中)
     * @param int $tableId 表id
     * @param string $tag 标签
     */
    protected function ReplaceVisitCode(&$templateContent, $siteId, $channelId, $tableType, $tableId, $tag = "")
    {

        $sitePublicData = new SitePublicData();
        $siteUrl = $sitePublicData->GetSiteUrl($siteId, true);


        $jsCode = '<script type="text/javascript">var visitConfig = encodeURIComponent("' . $siteUrl . '") +"||' . $siteId . '||' . $channelId . '||' . $tableType . '||' . $tableId . '||"+encodeURI("' . $tag . '");</script>';
        $scriptLoad = '<script type="text/javascript" src="/front_js/visit.js" charset="utf-8"></script>';
        $templateContent = str_ireplace("{VisitCode}", $jsCode . $scriptLoad, $templateContent);

    }


    /**
     * 替换模板中的用户信息面板 {UserInfoPanel}
     * @param string $templateContent 模板
     * @param int $siteId 站点id
     * @param string $forceLoginTemp 强制指定的已登录用户面板模板名称
     * @param string $forceLoginNotTemp 强制指定的未登录用户面板模板名称
     */
    protected function ReplaceUserInfoPanel(&$templateContent, $siteId, $forceLoginTemp = "",$forceLoginNotTemp = "")
    {
        $userId = Control::GetUserId();
        $refUrl = urlencode($_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']);

        if ($userId > 0) {
            $defaultTemp = "user_info_panel_login";
            $userInfoTemplateContent = self::GetDynamicTemplateContent(
                $defaultTemp,
                $siteId,
                $forceLoginTemp,
                $templateMode);

            if($forceLoginNotTemp == 'forum_user_no_login'){

            }

            $userInfoTemplateContent = str_ireplace("{UserInfoPanelUserName}", Control::GetUserName(), $userInfoTemplateContent);
            $userInfoTemplateContent = str_ireplace("{UserInfoPanelLoginOutUrl}", "/default.php?mod=user&a=logout&re_url=$refUrl", $userInfoTemplateContent);
            $userInfoTemplateContent = str_ireplace("{UserInfoPanelEditPassUrl}", "/default.php?mod=user&a=modify_user_pass&re_url=$refUrl", $userInfoTemplateContent);
        }
        else {
            $defaultTemp = "user_info_panel_not_login";
            $userInfoTemplateContent = self::GetDynamicTemplateContent(
                $defaultTemp,
                $siteId,
                $forceLoginNotTemp,
                $templateMode);

            $userInfoTemplateContent = str_ireplace("{UserInfoPanelRegisterUrl}", "/default.php?mod=user&a=register&re_url=$refUrl", $userInfoTemplateContent);
            $userInfoTemplateContent = str_ireplace("{UserInfoPanelLoginUrl}", "/default.php?mod=user&a=login&re_url=$refUrl", $userInfoTemplateContent);
        }

        $templateContent=str_ireplace("{UserInfoPanel}", $userInfoTemplateContent, $templateContent);

    }



    /**
     * 根据 site id 和 user id 查找会员权限
     * @param string $userPopedomName 会员权限字段名称
     * @return int
     */
    protected function GetUserPopedomIntValue($userPopedomName){

        $result = self::GetUserPopedomStringValue($userPopedomName);

        return intval($result);

    }

    /**
     * 根据 site id 和 user id 查找会员权限
     * @param string $userPopedomName 会员权限字段名称
     * @return float
     */
    protected function GetUserPopedomFloatValue($userPopedomName){

        $result = self::GetUserPopedomStringValue($userPopedomName);

        return floatval($result);

    }

    /**
     * 根据 site id 和 user id 查找会员权限
     * @param string $userPopedomName 会员权限字段名称
     * @return bool
     */
    protected function GetUserPopedomBoolValue($userPopedomName){

        $result = self::GetUserPopedomStringValue($userPopedomName);

        if(intval($result)>0){
            return true;
        }else{
            return false;
        }

    }

    /**
     * 根据 site id 和 user id 查找会员权限
     * @param string $userPopedomName 会员权限字段名称
     * @return string
     */
    protected function GetUserPopedomStringValue($userPopedomName){

        $result = "";

        $siteId = self::GetSiteIdByDomain();
        $userId = Control::GetUserId();

        if($siteId>0 && $userId>0){

            $userPopedomPublicData = new UserPopedomPublicData();

            $result = $userPopedomPublicData->GetValueBySiteIdAndUserId(
                $siteId,
                $userId,
                $userPopedomName,
                true
            );

            if(intval($result)<=0){
                //没找到权限，从会员员组中找

                $userRolePublicData = new UserRolePublicData();
                $userGroupId = $userRolePublicData->GetUserGroupId(
                    $siteId,
                    $userId,
                    false
                );
                //******上面使用缓存有问题，老是输出groupid为2或11待查//

                $result = $userPopedomPublicData->GetValueBySiteIdAndUserGroupId(
                    $siteId,
                    $userGroupId,
                    $userPopedomName,
                    true
                );
            }
        }

        return $result;
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
     * 替换微信js api相关的参数
     * @param $templateContent
     */
    protected function ReplaceWeiXinJsApi(&$templateContent){

        $siteId = self::GetSiteIdByDomain();
        if($siteId>0){


            $siteConfigData = new SiteConfigData($siteId);

            $wxJsSDK = new WxJsSDK();

            $arrWxJs = $wxJsSDK->getSignPackage($siteConfigData);

            foreach ($arrWxJs as $key => $value) {

                $templateContent = str_ireplace('{'.$key.'}',$value,$templateContent);

            }

        }

    }











    /************************************************************************
    ************************************************************************
    ************************************************************************
     ***************************               *****************************
    ************************        GOAL BALL      *************************
     ***************************               *****************************
    ************************************************************************
    ************************************************************************
    ************************************************************************
    ************************************************************************/

    /**
     * 替换比赛列表的内容
     * @param string $channelTemplateContent 要处理的模板内容
     * @param int $leagueId 频道id
     * @param string $tagId 标签id
     * @param string $tagContent 标签内容
     * @param int $tagTopCount 显示条数
     * @param int $state 状态
     * @return mixed|string 内容模板
     */
    private function ReplaceTemplateOfMatchList(
        $channelTemplateContent,
        $leagueId,
        $tagId,
        $tagContent,
        $tagTopCount,
        $state
    )
    {
        if ($leagueId > 0) {

            if($state==""){

            }

            $siteId = self::GetSiteIdByDomain();

            $arrMatchList = null;
            $matchPublicData = new MatchPublicData();

            $arrMatchList = $matchPublicData->GetAllListOfLeague($leagueId,False);

            if (!empty($arrMatchList)) {
                Template::ReplaceList($tagContent, $arrMatchList, $tagId);
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
     * 替换球队列表的内容
     * @param string $channelTemplateContent 要处理的模板内容
     * @param int $leagueId 频道id
     * @param string $tagId 标签id
     * @param string $tagContent 标签内容
     * @param int $tagTopCount 显示条数
     * @param int $state 状态
     * @return mixed|string 内容模板
     */
    private function ReplaceTemplateOfTeamList(
        $channelTemplateContent,
        $leagueId,
        $tagId,
        $tagContent,
        $tagTopCount,
        $state
    )
    {
        if ($leagueId > 0) {
            $state=TeamData::STATE_NORMAL;

            $siteId = self::GetSiteIdByDomain();

            $arrTeamList = null;
            $teamPublicData = new TeamPublicData();

            $arrTeamList = $teamPublicData->GetListOfLeague($leagueId,$state,False);

            if (!empty($arrTeamList)) {
                Template::ReplaceList($tagContent, $arrTeamList, $tagId);
                //把对应ID的CMS标记替换成指定内容
                $channelTemplateContent = Template::ReplaceCustomTag($channelTemplateContent, $tagId, $tagContent);
            } else {
                //替换为空
                $channelTemplateContent = Template::ReplaceCustomTag($channelTemplateContent, $tagId, '');
            }
        }else{

        }

        return $channelTemplateContent;
    }




    /**
     * 替换队员列表的内容
     * @param string $channelTemplateContent 要处理的模板内容
     * @param int $teamId 球队id
     * @param string $tagId 标签id
     * @param string $tagContent 标签内容
     * @param int $tagTopCount 显示条数
     * @param int $state 状态
     * @return mixed|string 内容模板
     */
    private function ReplaceTemplateOfMemberList(
        $channelTemplateContent,
        $teamId,
        $tagId,
        $tagContent,
        $tagTopCount,
        $state
    )
    {
        if ($teamId > 0) {
            $state=MemberData::STATE_NORMAL;
            $arrMemberList = null;
            $memberPublicData = new MemberPublicData();

            $arrMemberList = $memberPublicData->GetListOfTeam($teamId,$state,False);

            if (!empty($arrMemberList)) {
                Template::ReplaceList($tagContent, $arrMemberList, $tagId);
                //把对应ID的CMS标记替换成指定内容
                $channelTemplateContent = Template::ReplaceCustomTag($channelTemplateContent, $tagId, $tagContent);
            } else {
                //替换为空
                $channelTemplateContent = Template::ReplaceCustomTag($channelTemplateContent, $tagId, '');
            }
        }else{

        }

        return $channelTemplateContent;
    }


    /**
     * 替换进球列表内容
     * @param string $channelTemplateContent 要处理的模板内容
     * @param string $tagId 标签id
     * @param $tagContent
     * @param string $tagTopCount
     * @param string $tagWhere 检索条件
     * @param $tagWhereValue
     * @param $tagOrder
     * @param $state
     * @return string
     */
    private function ReplaceTemplateOfGoalList(
        $channelTemplateContent,
        $tagId,
        $tagContent,
        $tagTopCount,
        $tagWhere,
        $tagWhereValue,
        $tagOrder,
        $state
    )
    {

        $goalPublicData = new GoalPublicData();
        switch($tagWhere){
            case "match":
                $matchId = intval(str_ireplace("match_", "", $tagId));
                $arrGoalList = $goalPublicData->GetAllListOfMatch($matchId,False);
                break;
            case "member":
                break;
            case "member_of_league":
                break;
            case "league":
                break;
            case "team":
                break;
            default:
                $matchId = intval(str_ireplace("match_", "", $tagId));
                $arrGoalList = $goalPublicData->GetAllListOfMatch($matchId,False);
            break;
        }

        if (!empty($arrGoalList)) {
            Template::ReplaceList($tagContent, $arrGoalList, $tagId);
            //把对应ID的CMS标记替换成指定内容
            $channelTemplateContent = Template::ReplaceCustomTag($channelTemplateContent, $tagId, $tagContent);
        } else {
            //替换为空
            $channelTemplateContent = Template::ReplaceCustomTag($channelTemplateContent, $tagId, '');
        }

        return $channelTemplateContent;
    }





    /**
     * 替换card事件列表的内容
     * @param string $channelTemplateContent 要处理的模板内容
     * @param string $tagId 标签id
     * @param $tagContent
     * @param string $tagTopCount
     * @param string $tagWhere 检索条件
     * @param $tagWhereValue
     * @param $tagOrder
     * @param $state
     * @return string
     */
    private function ReplaceTemplateOfRedYellowCardList(
        $channelTemplateContent,
        $tagId,
        $tagContent,
        $tagTopCount,
        $tagWhere,
        $tagWhereValue,
        $tagOrder,
        $state
    ){

        $cardPublicData = new RedYellowCardPublicData();
        switch($tagWhere){
            case "match":
                $matchId = intval(str_ireplace("match_", "", $tagId));
                $arrCardList = $cardPublicData->GetAllListOfMatch($matchId,False);
                break;
            case "member":
                break;
            case "member_of_league":
                break;
            case "league":
                break;
            case "team":
                break;
            default:
                $matchId = intval(str_ireplace("match_", "", $tagId));
                $arrCardList = $cardPublicData->GetAllListOfMatch($matchId,False);
                break;
        }

        if (!empty($arrCardList)) {
            Template::ReplaceList($tagContent, $arrCardList, $tagId);
            //把对应ID的CMS标记替换成指定内容
            $channelTemplateContent = Template::ReplaceCustomTag($channelTemplateContent, $tagId, $tagContent);
        } else {
            //替换为空
            $channelTemplateContent = Template::ReplaceCustomTag($channelTemplateContent, $tagId, '');
        }

        return $channelTemplateContent;
    }



    /**
     * 替换替补事件列表的内容
     * @param string $channelTemplateContent 要处理的模板内容
     * @param string $tagId 标签id
     * @param $tagContent
     * @param string $tagTopCount
     * @param string $tagWhere 检索条件
     * @param $tagWhereValue
     * @param $tagOrder
     * @param $state
     * @return string
     */
    private function ReplaceTemplateOfMemberChangeList(
        $channelTemplateContent,
        $tagId,
        $tagContent,
        $tagTopCount,
        $tagWhere,
        $tagWhereValue,
        $tagOrder,
        $state
    ){

        $memberChangePublicData = new MemberChangePublicData();
        switch($tagWhere){
            case "match":
                $matchId = intval(str_ireplace("match_", "", $tagId));
                $arrMemberChangeList = $memberChangePublicData->GetAllListOfMatch($matchId,False);
                break;
            case "member":
                break;
            case "member_of_league":
                break;
            case "league":
                break;
            case "team":
                break;
            default:
                $matchId = intval(str_ireplace("match_", "", $tagId));
                $arrMemberChangeList = $memberChangePublicData->GetAllListOfMatch($matchId,False);
                break;
        }

        if (!empty($arrMemberChangeList)) {
            Template::ReplaceList($tagContent, $arrMemberChangeList, $tagId);
            //把对应ID的CMS标记替换成指定内容
            $channelTemplateContent = Template::ReplaceCustomTag($channelTemplateContent, $tagId, $tagContent);
        } else {
            //替换为空
            $channelTemplateContent = Template::ReplaceCustomTag($channelTemplateContent, $tagId, '');
        }

        return $channelTemplateContent;
    }


    /**
     * 替换其他事件列表的内容
     * @param string $channelTemplateContent 要处理的模板内容
     * @param string $tagId 标签id
     * @param $tagContent
     * @param string $tagTopCount
     * @param string $tagWhere 检索条件
     * @param $tagWhereValue
     * @param $tagOrder
     * @param $state
     * @return string
     */
    private function ReplaceTemplateOfOtherEventList(
        $channelTemplateContent,
        $tagId,
        $tagContent,
        $tagTopCount,
        $tagWhere,
        $tagWhereValue,
        $tagOrder,
        $state
    ){

        $otherEventPublicData = new OtherEventPublicData();
        switch($tagWhere){
            case "match":
                $matchId = intval(str_ireplace("match_", "", $tagId));
                $arrOtherEventList = $otherEventPublicData->GetAllListOfMatch($matchId,False);
                break;
            case "member":
                break;
            case "member_of_league":
                break;
            case "league":
                break;
            case "team":
                break;
            default:
                $matchId = intval(str_ireplace("match_", "", $tagId));
                $arrOtherEventList = $otherEventPublicData->GetAllListOfMatch($matchId,False);
                break;
        }

        if (!empty($arrOtherEventList)) {
            Template::ReplaceList($tagContent, $arrOtherEventList, $tagId);
            //把对应ID的CMS标记替换成指定内容
            $channelTemplateContent = Template::ReplaceCustomTag($channelTemplateContent, $tagId, $tagContent);
        } else {
            //替换为空
            $channelTemplateContent = Template::ReplaceCustomTag($channelTemplateContent, $tagId, '');
        }

        return $channelTemplateContent;
    }




    /**
     * 替换比赛事件列表的内容
     * @param string $channelTemplateContent 要处理的模板内容
     * @param int $matchId 球队id
     * @param string $tagId 标签id
     * @param string $tagContent 标签内容
     * @param int $tagTopCount 显示条数
     * @param int $state 状态
     * @return mixed|string 内容模板

    private function ReplaceTemplateOfEventList(
        $channelTemplateContent,
        $matchId,
        $tagId,
        $tagContent,
        $tagTopCount,
        $state
    )
    {
        if ($matchId > 0) {
            $arrEventList = null;

            $goalPublicData = new GoalPublicData();
            $arrGoalList = $goalPublicData->GetAllListOfMatch($matchId,False);
            $redYellowCardPublicData=new RedYellowCardPublicData();
            $arrCardList=$redYellowCardPublicData->GetAllListOfMatch($matchId,false);
            $memberChangePublicData=new MemberChangePublicData();
            $arrMemberChangeList=$memberChangePublicData->GetAllListOfMatch($matchId,false);
            $otherEventPublicData=new OtherEventPublicData();
            $arrOtherEventList=$otherEventPublicData->GetAllListOfMatch($matchId,false);

            $arrEventList=array_merge($arrGoalList,$arrCardList,$arrMemberChangeList,$arrOtherEventList);

            if (!empty($arrEventList)) {
                Template::ReplaceList($tagContent, $arrEventList, $tagId);
                //把对应ID的CMS标记替换成指定内容
                $channelTemplateContent = Template::ReplaceCustomTag($channelTemplateContent, $tagId, $tagContent);
            } else {
                //替换为空
                $channelTemplateContent = Template::ReplaceCustomTag($channelTemplateContent, $tagId, '');
            }
        }else{

        }

        return $channelTemplateContent;
    }*/

}

?>
