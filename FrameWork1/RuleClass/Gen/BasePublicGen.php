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
     * 替换内容
     * @param int $channelId 频道id
     * @param string $templateContent 模板内容
     * @return mixed|string 内容模板
     */
    private function ReplaceTemplate($channelId, $templateContent)
    {
        /** 1.处理预加载模板 */



        /** 2.替换模板内容 */
        $arrCustomTags = Template::GetAllCustomTag($templateContent);
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
     * 替换模板中的产品标记生成产品列表
     * @param string $tempContent 模板字符串
     */
    public function SubGenProduct(&$tempContent)
    {
        $keyName = "icms";
        $arr = Template::GetAllCustomTag($tempContent, $keyName);
        if (isset($arr)) {
            if (count($arr) > 1) {
                if (!empty($arr[1])) {
                    $productData = new ProductData();
                    $arr2 = $arr[1];
                    foreach ($arr2 as $val) {
                        $content = '<' . $keyName . '' . $val . '</' . $keyName . '>';
                        $channelId = Template::GetParamValue($content, "id", $keyName);
                        $type = Template::GetParamValue($content, "type", $keyName);
                        if ($type == 'product_list_by_channel_id') {
                            $arrProductList = $productData->GetList($channelId);
                            Template::ReplaceList($content, $arrProductList, $channelId, $keyName);
                            $tempContent = Template::ReplaceCustomTag($tempContent, $channelId, $content, $keyName);
                        }
                    }
                }
            }
        }
    }

    /**
     * 替换模板中的产品参数类别标记生成产品参数类别列表
     * @param string $tempContent 模板字符串
     */
    public function SubGenProductParamTypeClass(&$tempContent)
    {
        $keyName = "icms";
        $arr = Template::GetAllCustomTag($tempContent, $keyName);
        if (isset($arr)) {
            if (count($arr) > 1) {
                if (!empty($arr[1])) {
                    $productParamTypeClassData = new ProductParamTypeClassManageData();
                    $arr2 = $arr[1];
                    foreach ($arr2 as $val) {
                        $content = '<' . $keyName . '' . $val . '</' . $keyName . '>';
                        $channelId = Template::GetParamValue($content, "id", $keyName);
                        $type = Template::GetParamValue($content, "type", $keyName);
                        if ($type == 'product_param_type_class_list') {
                            $arrProductParamTypeClassList = $productParamTypeClassData->GetList($channelId);
                            Template::ReplaceList($content, $arrProductParamTypeClassList, $channelId, $keyName);
                            $tempContent = Template::ReplaceCustomTag($tempContent, $channelId, $content, $keyName);
                        }
                    }
                }
            }
        }
    }

    /**
     * 替换模板中的产品参数类别标记生成产品参数类别列表
     * @param string $tempContent 模板字符串
     */
    public function SubGenProductParamType(&$tempContent)
    {
        $keyName = "icms";
        $arr = Template::GetAllCustomTag($tempContent, $keyName);
        if (isset($arr)) {
            if (count($arr) > 1) {
                if (!empty($arr[1])) {
                    $productParamTypeData = new ProductParamTypeManageData();
                    $arr2 = $arr[1];
                    foreach ($arr2 as $val) {
                        $content = '<' . $keyName . '' . $val . '</' . $keyName . '>';
                        $productParamTypeClassId = Template::GetParamValue($content, "id", $keyName);
                        $type = Template::GetParamValue($content, "type", $keyName);
                        if ($type == 'product_param_type_list') {
                            $arrProductParamTypeList = $productParamTypeData->GetList($productParamTypeClassId);
                            Template::ReplaceList($content, $arrProductParamTypeList, $productParamTypeClassId, $keyName);
                            $tempContent = Template::ReplaceCustomTag($tempContent, $productParamTypeClassId, $content, $keyName);
                        }
                    }
                }
            }
        }
    }

    /**
     * 替换模板中的控件标记按类别生成控件
     * @param string $tempContent 模板字符串
     * @param array $arrProductParam 产品参数值数组
     */
    public function SubGenProductParamTypeControl(&$tempContent,$arrProductParam=null)
    {
        $keyName = "icms_control";
        $arr = Template::GetAllCustomTag($tempContent, $keyName);
        if (isset($arr)) {
            if (count($arr) > 1) {
                if (!empty($arr[1])) {
                    $arr2 = $arr[1];
                    foreach ($arr2 as $val) {
                        $content = '<' . $keyName . '' . $val . '</' . $keyName . '>';
                        $productParamTypeId = Template::GetParamValue($content, "id", $keyName);
                        $paramValueType = Template::GetParamValue($content, "type", $keyName);
                        $spanClass = Template::GetParamValue($content, "span_class", $keyName);
                        $content = self::GenControl($productParamTypeId, $paramValueType, $spanClass ,$arrProductParam);
                        $tempContent = Template::ReplaceCustomTag($tempContent, $productParamTypeId, $content, $keyName);
                    }
                }
            }
        }
    }

    /**
     * 根据参数类型生成控件
     * @param string $productParamTypeId 参数类型Id
     * @param string $paramValueType 参数类型值
     * @param string $spanClass 文本显示框样式名称
     * @param array $arrProductParam 产品参数值数组
     * @return string 输入框html
     */
    public function GenControl($productParamTypeId, $paramValueType, $spanClass, $arrProductParam)
    {
        switch ($paramValueType) {
            case "0":
                $columnName="ShortStringValue";
                $controlName = "ppi_".$columnName."_" . $productParamTypeId;
                $controlId = "ppi_".$columnName."_" . $productParamTypeId;
                $controlValue = self::GetProductParamValue($arrProductParam,$productParamTypeId,$columnName);
                $result = self::GenSpanControl($controlId, $controlName, $spanClass,$controlValue);
                break;
            case "1":
                $columnName="LongStringValue";
                $controlName = "ppi_".$columnName."_" . $productParamTypeId;
                $controlId = "ppi_".$columnName."_" . $productParamTypeId;
                $controlValue = self::GetProductParamValue($arrProductParam,$productParamTypeId,$columnName);
                $result = self::GenSpanControl($controlId, $controlName, $spanClass,$controlValue);
                break;
            case "2":
                $columnName="MaxStringValue";
                $controlName = "ppi_".$columnName."_" . $productParamTypeId;
                $controlId = "ppi_".$columnName."_" . $productParamTypeId;
                $controlValue = self::GetProductParamValue($arrProductParam,$productParamTypeId,$columnName);
                $result = self::GenSpanControl($controlId, $controlName, $spanClass,$controlValue);
                break;
            case "3":
                $columnName="FloatValue";
                $controlName = "ppi_".$columnName."_" . $productParamTypeId;
                $controlId = "ppi_".$columnName."_" . $productParamTypeId;
                $controlValue = self::GetProductParamValue($arrProductParam,$productParamTypeId,$columnName);
                $result = self::GenSpanControl($controlId, $controlName, $spanClass,$controlValue);
                break;
            case "4":
                $columnName="MoneyValue";
                $controlName = "ppi_".$columnName."_" . $productParamTypeId;
                $controlId = "ppi_".$columnName."_" . $productParamTypeId;
                $controlValue = self::GetProductParamValue($arrProductParam,$productParamTypeId,$columnName);
                $result = self::GenSpanControl($controlId, $controlName, $spanClass,$controlValue);
                break;
            case "5":
                $columnName="UrlValue";
                $controlName = "ppi_".$columnName."_" . $productParamTypeId;
                $controlId = "ppi_".$columnName."_" . $productParamTypeId;
                $controlValue = self::GetProductParamValue($arrProductParam,$productParamTypeId,$columnName);
                $result = self::GenSpanControl($controlId, $controlName, $spanClass,$controlValue);
                break;
            case "6":
                $columnName="ShortStringValue";
                $controlName = "pps_".$columnName."_" . $productParamTypeId;
                $controlId = "pps_".$columnName."_" . $productParamTypeId;
                $controlValue = self::GetProductParamValue($arrProductParam,$productParamTypeId,$columnName);
                $result = self::GenSpanControl($controlId, $controlName, $spanClass,$controlValue);
                break;
            default:
                $columnName="ShortStringValue";
                $controlName = "ppi_".$columnName."_" . $productParamTypeId;
                $controlId = "ppi_".$columnName."_" . $productParamTypeId;
                $controlValue = self::GetProductParamValue($arrProductParam,$productParamTypeId,$columnName);
                $result = self::GenSpanControl($controlId, $controlName, $spanClass,$controlValue);
                break;

        }
        return $result;
    }

    /**
     * 生成产品参数显示框
     * @param string $name 显示框name
     * @param string $id 显示框id
     * @param string $spanClass 显示框样式类名称
     * @param string $value 显示框的值
     * @return string 显示框html
     */
    public function GenSpanControl($id, $name, $spanClass, $value = "")
    {
        $result = '<span name="' . $name . '" id="' . $id . '" class="' . $spanClass . '" >'.$value.'</span>';
        return $result;
    }

    /**
     * 根据产品参数类型ID和产品参数类型对应值字段名称得到产品参数值
     * @param array $arrProductParam 产品参数数组
     * @param string $productParamTypeId 产品参数类型Id
     * @param string $productParamColumnName 产品参数类型对应值字段名称
     * @return string 产品参数值
     */
    public function GetProductParamValue($arrProductParam,$productParamTypeId,$productParamColumnName){
        $productParamValue="";
        for ($i = 0; $i < count($arrProductParam); $i++) {
            if($productParamTypeId===$arrProductParam[$i]["ProductParamTypeId"])
                $productParamValue=$arrProductParam[$i][$productParamColumnName];
        }
        return $productParamValue;
    }

}

?>
