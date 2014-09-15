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
                        $selectClass = Template::GetParamValue($content, "select_class", $keyName);
                        $content = self::GenControl($productParamTypeId, $paramValueType, $spanClass, $selectClass ,$arrProductParam);
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
     * @param string $selectClass 文本选择框样式名称
     * @param array $arrProductParam 产品参数值数组
     * @return string 输入框html
     */
    public function GenControl($productParamTypeId, $paramValueType, $spanClass, $selectClass,$arrProductParam)
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
                $controlName = "pps_".$columnName."_" . $productParamTypeId;
                $controlId = "pps_".$columnName."_" . $productParamTypeId;
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
