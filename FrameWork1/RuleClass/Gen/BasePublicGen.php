<?php

/**
 * 前台Gen的基类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen
 * @author zhangchi
 */
class BasePublicGen extends BaseGen {

    /**
     * 通过子域名取得站点id
     * @return int 站点id
     */
    protected function GetSiteIdByDomain() {
        $host = strtolower($_SERVER['HTTP_HOST']);
        $host = str_ireplace("http://", "", $host);
        if ($host === "localhost" || $host === "127.0.0.1") {
            $siteId = 1;
        } else {

            //先查绑定的一级域名
            $domain = Control::GetDomain(strtolower($_SERVER['HTTP_HOST']));
            $sitePublicData = new SitePublicData();
            $siteId = $sitePublicData->GetSiteIdByBindDomain($domain, true);

            if($siteId<=0){
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
     * 替换模板内容
     * @param string $templateContent 模板内容
     * @return mixed|string 内容模板
     */
    public function ReplaceTemplate($templateContent)
    {
        /** 1.处理预加载模板 */

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
                $tagTopCount = Template::GetParamValue($tagContent, "top");
                $tagTopCount = Format::CheckTopCount($tagTopCount);
                if ($tagTopCount == null) {

                }
                //显示状态
                $state = Template::GetParamValue($tagContent, "state");

                switch ($tagType) {
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
                        $templateContent = self::ReplaceTemplateOfProductList($templateContent, $tagId, $tagContent, $tagTopCount, $tagWhere, $tagOrder, $state);
                        break;
                    case Template::TAG_TYPE_PRODUCT_PARAM_TYPE_CLASS_LIST :
                        $channelId = intval(str_ireplace("product_param_type_class_", "", $tagId));
                        if ($channelId > 0) {
                            $templateContent = self::ReplaceTemplateOfProductParamTypeClassList($templateContent, $channelId, $tagId, $tagContent, $tagTopCount, $tagWhere, $tagOrder, $state);
                        }
                        break;
                    case Template::TAG_TYPE_PRODUCT_PARAM_TYPE_LIST :
                        $productParamTypeClassId = intval(str_ireplace("product_param_type_", "", $tagId));
                        $productId=$relationId;
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
                }
            }
        }
        return $templateContent;
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
            $arrChannelChildList = null;
            $arrChannelThirdList = null;
            $tableIdName = "ChannelId";
            $parentIdName = "ParentId";
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

            //资讯默认只显示已发状态的新闻
            $state = DocumentNewsData::STATE_PUBLISHED;


            $arrDocumentNewsList = null;
            $documentNewsPublicData = new DocumentNewsPublicData();

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
                    $arrDocumentNewsList = $documentNewsPublicData->GetList($channelId, $tagTopCount, $state, $orderBy);
                    break;
            }
            if (!empty($arrDocumentNewsList)) {
                Template::ReplaceList($tagContent, $arrDocumentNewsList, $tagId);
                //把对应ID的CMS标记替换成指定内容
                $channelTemplateContent = Template::ReplaceCustomTag($channelTemplateContent, $tagId, $tagContent);
            }else{
                //替换为空
                $channelTemplateContent = Template::ReplaceCustomTag($channelTemplateContent, $tagId, '');
            }
        }

        return $channelTemplateContent;
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
            }else{
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

            //默认只显示已发状态的新闻
            $state = 0;

            $arrList = null;
            $newspaperArticlePicPublicData = new NewspaperArticlePicPublicData();

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
                    $arrList = $newspaperArticlePicPublicData->GetList($newspaperArticleId);
                    break;
            }


            if (!empty($arrList)) {
                Template::ReplaceList($tagContent, $arrList, $tagId);
                //把对应ID的CMS标记替换成指定内容
                $channelTemplateContent = Template::ReplaceCustomTag($channelTemplateContent, $tagId, $tagContent);
            }else{
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
            }else{
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
                    $belongChannelId=self::GetOwnChannelIdAndChildChannelId($channelId);
                    $arrProductList = $productPublicData->GetListByChannelId($belongChannelId, $tagOrder, $tagTopCount);
                }
                break;
            case "RecLevel":
                $recLevel = intval(str_ireplace("product_", "", $tagId));
                if ($recLevel > 0) {
                    $arrProductList = $arrProductList = $productPublicData->GetListByRecLevel($recLevel, $tagOrder, $tagTopCount);
                }
                break;
            case "SaleCount":
                $arrProductList = $productPublicData->GetListBySaleCount($tagOrder, $tagTopCount);
                break;
            case "BelongChannelDiscount":
                $channelId = intval(str_ireplace("product_", "", $tagId));
                if ($channelId > 0) {
                    $OwnChannelAndChildChannelId=self::GetOwnChannelIdAndChildChannelId($channelId);
                    $arrProductList = $productPublicData->GetDiscountListByChannelId($OwnChannelAndChildChannelId, $tagOrder, $tagTopCount);
                }
                break;
            default :
                $channelId = intval(str_ireplace("product_", "", $tagId));
                if ($channelId > 0) {
                    $AllChannelId=self::GetOwnChannelIdAndChildChannelId($channelId);
                    $arrProductList = $productPublicData->GetListByChannelId($AllChannelId, $tagOrder, $tagTopCount);
                }
        }
        if (!empty($arrProductList)) {
            Template::ReplaceList($tagContent, $arrProductList, $tagId);
            //把对应ID的CMS标记替换成指定内容
            $templateContent = Template::ReplaceCustomTag($templateContent, $tagId, $tagContent);
        }
        else Template::RemoveCustomTag($templateContent, $tagId);
        return $templateContent;
    }

    /**
     * 根据频道ID获取包含本频道ID及以下子频道ID字符串，为id,id,id 的形式
     * @param int $channelId 频道id
     * @return string 频道id字符串
     */
    protected function GetOwnChannelIdAndChildChannelId($channelId)
    {
        $allChannelId="";
        if ($channelId > 0) {
            $channelPublicData = new ChannelPublicData();
            $childChannelId = $channelPublicData->GetChildrenChannelId($channelId,true);
            if(empty($childChannelId)){
                $allChannelId = $channelId;
            }
            else{
                $allChannelId = $channelId.",".$childChannelId;
            }
        }
        return $allChannelId;
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
            }
            else Template::RemoveCustomTag($templateContent, $tagId);
        }
        else Template::RemoveCustomTag($templateContent, $tagId);

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
                    $arrProductParamTypeList = $productParamTypePublicData->GetListWithValue($productParamTypeClassId,$productId, $tagOrder, $tagTopCount);
                    break;
                default :
                    //new
                    $arrProductParamTypeList = $productParamTypePublicData->GetListWithValue($productParamTypeClassId,$productId, $tagOrder, $tagTopCount);
                    break;
            }
            if (!empty($arrProductParamTypeList)) {
                self::MappingParamTypeValue($arrProductParamTypeList);
                Template::ReplaceList($tagContent, $arrProductParamTypeList, $tagId);
                //把对应ID的CMS标记替换成指定内容
                $templateContent = Template::ReplaceCustomTag($templateContent, $tagId, $tagContent);
            }
            else Template::RemoveCustomTag($templateContent, $tagId);
        }
        else Template::RemoveCustomTag($templateContent, $tagId);

        return $templateContent;
    }

    /**
     * 根据产品参数类型ID映射产品参数对应值到指定字段ParamTypeValue
     * @param array $arrProductParamTypeList 产品参数原始数组
     * @return string 产品参数值
     */
    public function MappingParamTypeValue(&$arrProductParamTypeList){
        for ($i = 0; $i < count($arrProductParamTypeList); $i++) {
            $paramValueType=$arrProductParamTypeList[$i]["ParamValueType"];
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
            $arrProductParamTypeList[$i]["ParamTypeValue"]=$paramTypeMappingValue;
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
            }
            else Template::RemoveCustomTag($templateContent, $tagId);
        }
        else Template::RemoveCustomTag($templateContent, $tagId);

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
            }
            else Template::RemoveCustomTag($templateContent, $tagId);
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

                if (count($userExploreCollection->UserExplores)>0) {
                    Template::ReplaceList($tagContent, $userExploreCollection->UserExplores, $tagId);
                    //把对应ID的CMS标记替换成指定内容
                    $templateContent = Template::ReplaceCustomTag($templateContent, $tagId, $tagContent);
                } else {
                    Template::RemoveCustomTag($templateContent, $tagId);
                }
            } else {
                Template::RemoveCustomTag($templateContent, $tagId);
            }
        }
        else {
            $showMessage = Language::Load("user_explore",1);
            $showMessage = str_ireplace("{ReturnUrl}", urlencode($_SERVER["REQUEST_URI"]),$showMessage);
            $templateContent = Template::ReplaceCustomTag($templateContent, $tagId, $showMessage);
        }

        return $templateContent;
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
    protected function CreateUserExploreCookie($userId,$tableId,$tableType,$url,$title,$titlePic,$price)
    {

        if ($userId > 0) {

            $userExplore = new UserExplore();
            $userExploreCollection = new UserExploreCollection();
            if (strlen(Control::GetUserExploreCookie($userId))>0) {

            } else {
                //读取cookie
                $cookieStr = Control::GetUserExploreCookie($userId);
                $userExploreCollection->UserExplores = $cookieStr;
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
     * 根据会员Id从会员浏览记录COOKIE中取得用户浏览记录数组
     * @param int $userId 会员Id
     * @return UserExploreCollection 取得会员浏览记录对应数组
     */
    protected function GetUserExploreArrayFromCookieByUserId($userId)
    {
        $userExploreCollection = new UserExploreCollection();
        if ($userId > 0) {
            if (strlen(Control::GetUserExploreCookie($userId))>0) {
                //读取cookie
                $cookieStr = Control::GetUserExploreCookie($userId);
                //字符串转回原来的数组
                $userExploreCollection->UserExplores = $cookieStr;
            }
        }
        return $userExploreCollection;
    }

}

?>
