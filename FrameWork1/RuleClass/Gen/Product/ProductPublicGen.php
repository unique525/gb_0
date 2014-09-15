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
        $tempContent = self::loadListTemp($temp,$channelId);
        parent::SubGenProduct($tempContent);
        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }

    private function loadListTemp($temp,$channelId)
    {

        $result='
            <icms id="{ChannelId}" type="product_list_by_channel_id">
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
        $tempContent = self::loadDetailTemp($temp,$channelId);
        parent::SubGenProduct($tempContent);

        //加载产品表数据
        $productManageData = new ProductManageData();
        $arrOne = $productManageData->GetOne($productId);
        Template::ReplaceOne($tempContent, $arrOne);

        //生成产品参数新增界面
        parent::SubGenProductParamTypeClass($tempContent);
        //把对应ID的CMS标记替换成指定内容
        //替换子循环里的<![CDATA[标记
        $tempContent = str_ireplace("<icms_child", "<icms", $tempContent);
        $tempContent = str_ireplace("</icms_child>", "</icms>", $tempContent);
        $tempContent = str_ireplace("<item_child", "<item", $tempContent);
        $tempContent = str_ireplace("</item_child>", "</item>", $tempContent);
        $tempContent = str_ireplace("[CDATA]", "<![CDATA[", $tempContent);
        $tempContent = str_ireplace("[/CDATA]", "]]>", $tempContent);
        $tempContent = str_ireplace("[CDATA]", "<![CDATA[", $tempContent);
        $tempContent = str_ireplace("[/CDATA]", "]]>", $tempContent);
        parent::SubGenProductParamType($tempContent);
        //取产品参数表数据
        $productParamManageData = new ProductParamManageData();
        $arrProductParam = $productParamManageData->GetList($productId);
        parent::SubGenProductParamTypeControl($tempContent,$arrProductParam);

        $patterns = '/\{s_(.*?)\}/';
        $tempContent = preg_replace($patterns, "", $tempContent);
        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }


    private function loadDetailTemp($temp,$channelId)
    {
        $result ='
            <div>{ProductName}</div>
            <icms id="{ChannelId}" type="product_param_type_class_list">
                <item>
                    <![CDATA[
                    <div class="main_line_title" style="font-size:14px">{f_ProductParamTypeClassName}</div>
                    <div class="main_line_body">
                        <icms_child id="{f_ProductParamTypeClassId}" type="product_param_type_list">
                            <item_child>
                                [CDATA]
                                <div class="main_line_content">
                                    <div class="main_line_content_left">{f_ParamTypeName}：</div>
                                    <div class="main_line_content_right"><icms_control id="{f_ProductParamTypeId}" product_id="{ProductId}" type="{f_ParamValueType}" input_class="input_box" ></icms_control></div>
                                </div>
                                [/CDATA]
                            </item_child>
                        </icms_child>
                        <div class="spe"></div>
                    </div>
                    ]]>
                </item>
            </icms>';
        $result = str_ireplace("{ChannelId}", $channelId, $result);
        return $result;
    }

}

?>
