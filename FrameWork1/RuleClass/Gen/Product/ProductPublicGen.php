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
            case "ajax_list":
                $result = self::AjaxList();
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

        //加载产品类别数据
        $channelPublicData = new ChannelPublicData();
        $arrOne = $channelPublicData->GetOne($channelId);
        Template::ReplaceOne($templateContent, $arrOne);

        $channelId = Control::GetRequest("channel_id", 0);
        $order= Control::GetRequest("order", 0);
        $pageSize = Control::GetRequest("ps", 20);
        $searchKey = Control::GetRequest("search_key", "");
        $searchKey = urldecode($searchKey);
        $pageIndex = Control::GetRequest("p", 1);

        if ($pageIndex > 0 && $channelId > 0) {
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $tagId = "product_list";
            $allCount = 0;
            $productPublicData = new ProductPublicData();
            $arrList = $productPublicData->GetListForPager($channelId, $pageBegin, $pageSize, $allCount, $searchKey, 0, $order);
            if (count($arrList) > 0) {
                Template::ReplaceList($templateContent, $arrList, $tagId);
                $styleNumber = 1;
                $pagerTemplate = Template::Load("pager/pager_style".$styleNumber.".html","common");
                $isJs = FALSE;
                $navUrl = "/default.php?mod=product&a=list&channel_id=$channelId&p={0}&ps=$pageSize&order=$order#product_list_anchor";
                $jsFunctionName = "";
                $jsParamList = "";
                $pagerButton = Pager::ShowPageButton($pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex, $styleNumber, $isJs, $jsFunctionName, $jsParamList);
                $templateContent = str_ireplace("{pager_button}", $pagerButton, $templateContent);
            } else {
                Template::RemoveCustomTag($templateContent, $tagId);
                $templateContent = str_ireplace("{pager_button}", Language::Load("product", 101), $templateContent);
            }
        }
        $templateContent = parent::ReplaceTemplate($templateContent);
        parent::ReplaceEnd($templateContent);
        return $templateContent;
    }

    private function loadListTemp($temp,$channelId)
    {
        $templateFileUrl = "product/product_list.html";
        $templateName = "default";
        $templatePath = "front_template";
        $templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);
        $templateContent = str_ireplace("{ChannelId}", $channelId, $templateContent);
        return $templateContent;
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

        //加载产品类别数据
        $channelPublicData = new ChannelPublicData();
        $arrOne = $channelPublicData->GetOne($channelId);
        Template::ReplaceOne($templateContent, $arrOne);

        //加载产品表数据
        $productPublicData = new ProductPublicData();
        $arrOne = $productPublicData->GetOne($productId);
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


        $patterns = '/\{s_(.*?)\}/';
        $templateContent = preg_replace($patterns, "", $templateContent);
        parent::ReplaceEnd($templateContent);

        //存入用户浏览记录进CooKie
        $tableId = $productId;
        $tableType = 1;
        $userId = Control::GetUserId();
        $url = $_SERVER['REQUEST_URI'];
        $titlePic = $arrOne["UploadFilePath"];
        parent::CreateExploreCookie($userId,$tableId,$tableType,$url,$titlePic);
        return $templateContent;
    }


    private function loadDetailTemp($temp,$channelId)
    {
        $templateFileUrl = "product/product_detail.html";
        $templateName = "default";
        $templatePath = "front_template";
        $templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);
        $templateContent = str_ireplace("{ChannelId}", $channelId, $templateContent);
        return $templateContent;
    }

    /**
     * ajax方法得到产品列表数据
     * @return string 产品列表HTML
     */
    private function AjaxList()
    {
        $channelId = Control::GetRequest("channel_id", 0);
        $order = Control::GetRequest("order", "");
        $top = Control::GetRequest("ps", 12);
        $ProductPublicData = new ProductPublicData();
        $arrList = $ProductPublicData->GetList($channelId,$order,$top);
        $tempArrList = json_encode($arrList);
        return Control::GetRequest("jsonpcallback","") . '({"result":' . $tempArrList . '})';
    }

}

?>
