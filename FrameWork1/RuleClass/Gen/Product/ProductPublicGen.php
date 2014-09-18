<?php

/**
 * 产品前台生成类
 * @category iCMS
 * @package iCMS_Rules_Gen_Product
 * @author yanjiuyuan
 */
class ProductPublicGen extends BasePublicGen implements IBasePublicGen
{

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenPublic()
    {
        $result = "";

        $action = Control::GetRequest("a", "");
        switch ($action) {
            case "detail":
                $result = self::GenDetail();
                break;
            case "list":
                $result = self::GenList();
                break;
            default:
                break;
        }

        return $result;
    }

    /**
     * 生成产品列表
     * @return string 产品列表HTML
     */
    private function GenList()
    {
        $temp = Control::GetRequest("temp", "");
        $channelId = Control::GetRequest("channel_id", 0);
        $templateContent = self::loadListTemp($temp,$channelId);
        $templateContent = parent::ReplaceTemplate($templateContent);
        parent::ReplaceEnd($templateContent);
        return $templateContent;
    }

    private function loadListTemp($temp,$channelId)
    {

        $result='
            <icms id="product_{ChannelId}" type="product_list" where="channel">
                <item>
                    <![CDATA[
                    <div class="main_line_title" style="font-size:14px">{f_ProductName}</div>
                    <div class="main_line_title" style="font-size:14px">{f_UploadFilePath}</div>
                    <div class="spe"></div>
                    ]]>
                </item>
            </icms>';
        $result = str_ireplace("{ChannelId}", $channelId, $result);
        return $result;
    }

    /**
     * 生成产品详细页面
     * @return string 产品详细页面HTML
     */
    private function GenDetail()
    {
        $temp = Control::GetRequest("temp", "");
        $channelId = Control::GetRequest("channel_id", 0);
        $productId = Control::GetRequest("product_id", 0);
        $templateContent = self::loadDetailTemp($temp,$channelId);

        //加载产品表数据
        $productManageData = new ProductManageData();
        $arrOne = $productManageData->GetOne($productId);
        Template::ReplaceOne($templateContent, $arrOne);

        //父模板替换
        $templateContent = parent::ReplaceTemplate($templateContent);
        //把对应ID的CMS标记替换成指定内容
        //替换子循环里的<![CDATA[标记
        $templateContent = str_ireplace("<icms_child", "<icms", $templateContent);
        $templateContent = str_ireplace("</icms_child>", "</icms>", $templateContent);
        $templateContent = str_ireplace("<item_child", "<item", $templateContent);
        $templateContent = str_ireplace("</item_child>", "</item>", $templateContent);
        $templateContent = str_ireplace("[CDATA]", "<![CDATA[", $templateContent);
        $templateContent = str_ireplace("[/CDATA]", "]]>", $templateContent);
        //子模板替换
        $templateContent = parent::ReplaceTemplate($templateContent);

        //加载产品价格数据
        self::GenProductPrice($templateContent);


        $patterns = '/\{s_(.*?)\}/';
        $templateContent = preg_replace($patterns, "", $templateContent);
        parent::ReplaceEnd($templateContent);
        return $templateContent;
    }


    private function loadDetailTemp($temp,$channelId)
    {
        $templateFileUrl = "product/test_detail.html";
        $templateName = "default";
        $templatePath = "front_template";
        $templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);
        $templateContent = str_ireplace("{ChannelId}", $channelId, $templateContent);
        return $templateContent;
    }

    /**
     * 替换模板中的产品价格标记生成价格列表
     * @param string $tempContent 模板字符串
     */
    public function GenProductPrice(&$tempContent)
    {
        $keyName = "icms";
        $arr = Template::GetAllCustomTag($tempContent, $keyName);
        if (isset($arr)) {
            if (count($arr) > 1) {
                if (!empty($arr[1])) {
                    $productPriceManageData = new ProductPriceManageData();
                    $arr2 = $arr[1];
                    foreach ($arr2 as $val) {
                        $content = '<' . $keyName . '' . $val . '</' . $keyName . '>';
                        $productId = Template::GetParamValue($content, "id", $keyName);
                        $type = Template::GetParamValue($content, "type", $keyName);
                        if ($type == 'product_price_list') {
                            $arrProductPicTagList = $productPriceManageData->GetList($productId);
                            Template::ReplaceList($content, $arrProductPicTagList, $productId, $keyName);
                            $tempContent = Template::ReplaceCustomTag($tempContent, $productId, $content, $keyName);
                        }
                    }
                }
            }
        }
    }

}

?>
