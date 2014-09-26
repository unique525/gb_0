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
    protected function GetSiteIdBySubDomain() {
        $siteId = 0;
        $host = strtolower($_SERVER['HTTP_HOST']);
        $host = str_ireplace("http://", "", $host);
        if ($host === "localhost" || $host === "127.0.0.1") {
            $siteId = 1;
        } else {
            $arrDomain = explode(".", $host);
            if (count($arrDomain) > 0) {
                $subDomain = $arrDomain[0];
                if (strlen($subDomain) > 0) {
                    $siteData = new SiteData();
                    $siteId = $siteData->GetSiteId($subDomain);
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
                //显示条数
                $tagTopCount = Template::GetParamValue($tagContent, "top");
                $tagTopCount = Format::CheckTopCount($tagTopCount);
                if ($tagTopCount == null) {

                }
                //显示状态
                $state = Template::GetParamValue($tagContent, "state");

                switch ($tagType) {
                    case Template::TAG_TYPE_CHANNEL_LIST :
                        $channelId = intval(str_ireplace("channel_", "", $tagId));
                        if ($channelId > 0) {
                            $templateContent = self::ReplaceTemplateOfChannelList($templateContent, $channelId, $tagId, $tagContent, $tagTopCount, $tagWhere, $tagOrder);
                        }
                        break;
                    case Template::TAG_TYPE_DOCUMENT_NEWS_LIST :
                        $documentNewsId = intval(str_ireplace("channel_", "", $tagId));
                        if ($documentNewsId > 0) {
                            $templateContent = self::ReplaceTemplateOfDocumentNewsList($templateContent, $channelId, $tagId, $tagContent, $tagTopCount, $tagWhere, $tagOrder, $state);
                        }
                        break;
                    case Template::TAG_TYPE_PRODUCT_LIST :
                        $templateContent = self::ReplaceTemplateOfProductList($templateContent, $channelId, $tagId, $tagContent, $tagTopCount, $tagWhere, $tagOrder, $state);
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
                }
            }
        }
        return $templateContent;
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
    private function ReplaceTemplateOfChannelList(
        $channelTemplateContent,
        $channelId,
        $tagId,
        $tagContent,
        $tagTopCount,
        $tagWhere,
        $tagOrder
    )
    {
        if ($channelId > 0) {
            $arrChannelList = null;
            switch ($tagWhere) {
                case "parent":
                    $channelManageData = new ChannelManageData();
                    $arrChannelList = $channelManageData->GetListByParentId($channelId, $tagTopCount, $tagOrder);
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
     * 替换产品列表的内容
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
    private function ReplaceTemplateOfProductList(
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
        $arrProductList = null;
        $productPublicData = new ProductPublicData();
        switch ($tagWhere) {
            case "channel":
                $channelId = intval(str_ireplace("product_", "", $tagId));
                if ($channelId > 0) {
                    $arrProductList = $productPublicData->GetListByChannelId($channelId, $tagOrder, $tagTopCount);
                }
                break;
            case "RecLevel":
                $arrProductList = $productPublicData->GetListByRecLevel($tagOrder, $tagTopCount);
                break;
            case "SaleCount":
                $arrProductList = $productPublicData->GetListBySaleCount($tagOrder, $tagTopCount);
                break;
            default :
                //new
                $arrProductList = $productPublicData->GetListByChannelId($channelId, $tagOrder, $tagTopCount);
                break;
        }
        if (!empty($arrProductList)) {
            Template::ReplaceList($tagContent, $arrProductList, $tagId);
            //把对应ID的CMS标记替换成指定内容
            $templateContent = Template::ReplaceCustomTag($templateContent, $tagId, $tagContent);
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
            }
        }

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
        }

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
        }

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
        }

        return $templateContent;
    }

}

?>
