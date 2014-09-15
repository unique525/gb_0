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
     * 替换模板中的产品标记生成产品列表
     * @param string $tempContent 模板字符串
     */
    public function GenProduct(&$tempContent)
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
    public function GenProductParamTypeClass(&$tempContent)
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
    public function GenProductParamType(&$tempContent)
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
                        $productId = Template::GetParamValue($content, "product_id", $keyName);
                        $type = Template::GetParamValue($content, "type", $keyName);
                        if ($type == 'product_param_type_list') {
                            $arrProductParamTypeList = $productParamTypeData->GetListWithValue($productParamTypeClassId,$productId);
                            self::MappingParamTypeValue($arrProductParamTypeList);
                            Template::ReplaceList($content, $arrProductParamTypeList, $productParamTypeClassId, $keyName);
                            $tempContent = Template::ReplaceCustomTag($tempContent, $productParamTypeClassId, $content, $keyName);
                        }
                    }
                }
            }
        }
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

}

?>
