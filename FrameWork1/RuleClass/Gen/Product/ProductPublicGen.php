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
        $channelFirstId = Control::GetRequest("channel_first_id", 0);
        $templateContent = self::LoadListTemp($temp, $channelId);

        parent::ReplaceFirst($templateContent);


        //得到顶级频道名称
        $channelPublicData = new ChannelPublicData();
        $arrFirstOne = $channelPublicData->GetOne($channelFirstId);
        $channelFirstName = $arrFirstOne["ChannelName"];
        $templateContent = str_ireplace("{ChannelFirstId}", strval($channelFirstId), $templateContent);
        $templateContent = str_ireplace("{ChannelFirstName}", strval($channelFirstName), $templateContent);
        //加载产品类别数据
        $arrOne = $channelPublicData->GetOne($channelId);
        Template::ReplaceOne($templateContent, $arrOne);

        $channelId = Control::GetRequest("channel_id", 0);
        $order = Control::GetRequest("order", 0);
        $pageSize = Control::GetRequest("ps", 12);
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
                $templateFileUrl = "pager/pager_style" . $styleNumber . ".html";
                $templateName = "default";
                $templatePath = "front_template";
                $pagerTemplate = Template::Load($templateFileUrl, $templateName, $templatePath);
                $isJs = FALSE;
                $navUrl = "/default.php?mod=product&a=list&channel_first_id=$channelFirstId&channel_id=$channelId&p={0}&ps=$pageSize&order=$order#product_list_anchor";
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

    private function LoadListTemp($temp, $channelId)
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
        $channelFirstId = Control::GetRequest("channel_first_id", 0);
        $productId = Control::GetRequest("product_id", 0);
        $templateContent = "";

        if ($productId > 0) {
            $templateContent = self::loadDetailTemp($temp, $channelId);

            $templateContent = str_ireplace("{ChannelFirstId}", strval($channelFirstId), $templateContent);

            parent::ReplaceFirst($templateContent);

            //得到顶级频道名称
            $channelPublicData = new ChannelPublicData();
            $arrFirstOne = $channelPublicData->GetOne($channelFirstId);
            $channelFirstName = $arrFirstOne["ChannelName"];
            $templateContent = str_ireplace("{ChannelFirstId}", strval($channelFirstId), $templateContent);
            $templateContent = str_ireplace("{ChannelFirstName}", strval($channelFirstName), $templateContent);

            //加载产品类别数据
            $arrOne = $channelPublicData->GetOne($channelId);
            Template::ReplaceOne($templateContent, $arrOne);

            //加载产品表数据
            $productPublicData = new ProductPublicData();
            $arrOne = $productPublicData->GetOne($productId);

            if(count($arrOne)>0){
                Template::ReplaceOne($templateContent, $arrOne);

                //存入用户浏览记录进CooKie
                $tableId = $productId;
                $tableType = UserExploreData::TABLE_TYPE_PRODUCT;
                $userId = Control::GetUserId();
                $url = $_SERVER['REQUEST_URI'];
                $title = $arrOne["ProductName"];
                $titlePic = $arrOne["UploadFileThumbPath3"];
                $price = $arrOne["SalePrice"];
                parent::CreateUserExploreCookie($userId, $tableId, $tableType, $url, $title, $titlePic, $price);

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

                //产品评价内容替换
                //----------begin-----------
                $productCommentListTagId = "product_comment_list";
                $productCommentAllCount = 0;
                $productCommentPageIndex = Control::GetRequest("pc_pi", 1);
                $productCommentPageSize = Control::GetRequest("pc_ps", 10);
                $productCommentPageBegin = ($productCommentPageIndex - 1) * $productCommentPageSize;

                $productCommentPublicData = new ProductCommentPublicData();
                $arrProductCommentList = $productCommentPublicData->GetListOfParent
                    (
                        $productId,
                        $productCommentAllCount,
                        $productCommentPageBegin,
                        $productCommentPageSize
                    );

                $strParentIds = "";
                for ($i = 0; $i < count($arrProductCommentList); $i++) {
                    if ($i < count($arrProductCommentList) - 1) {
                        $strParentIds = $strParentIds . $arrProductCommentList[$i]["ProductCommentId"] . ",";
                    } else {
                        $strParentIds = $strParentIds . $arrProductCommentList[$i]["ProductCommentId"];
                    }
                }
                $arrChildProductCommentList = $productCommentPublicData->GetListOfChild($strParentIds);

                if (count($arrProductCommentList) > 0) {
                    Template::ReplaceList($templateContent, $arrProductCommentList, $productCommentListTagId, "icms", $arrChildProductCommentList, "ProductCommentId", "ParentId");
                } else {
                    Template::RemoveCustomTag($templateContent, $productCommentListTagId);
                }

                $styleNumber = 1;
                $templateFileUrl = "pager/pager_style" . $styleNumber . ".html";
                $templateName = "default";
                $templatePath = "front_template";
                $pagerTemplate = Template::Load($templateFileUrl, $templateName, $templatePath);

                $isJs = FALSE;
                $navUrl = "/default.php?mod=product&a=detail&channel_id=" . $channelId . "&product_id=" . $productId . "&pc_pi={0}&pc_ps=" . $productCommentPageSize . "#comment";
                $jsFunctionName = "";
                $jsParamList = "";
                $pageIndexName = "pc_pi";
                $pageSizeName = "pc_ps";
                $showGoTo = false;
                $pagerButton = Pager::ShowPageButton
                    (
                        $pagerTemplate,
                        $navUrl,
                        $productCommentAllCount,
                        $productCommentPageSize,
                        $productCommentPageIndex,
                        $styleNumber,
                        $isJs,
                        $jsFunctionName,
                        $jsParamList,
                        $pageIndexName,
                        $pageSizeName,
                        $showGoTo
                    );
                $templateContent = str_ireplace("{product_comment_pager_button}", $pagerButton, $templateContent);
                //----------end-------------

                $patterns = '/\{s_(.*?)\}/';
                $templateContent = preg_replace($patterns, "", $templateContent);
                parent::ReplaceEnd($templateContent);
            }

        }
        return $templateContent;
    }


    private function loadDetailTemp($temp, $channelId)
    {
        $templateFileUrl = "product/product_detail.html";
        $templateName = "default";
        $templatePath = "front_template";
        $templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);
        $templateContent = str_ireplace("{ChannelId}", $channelId, $templateContent);
        return $templateContent;
    }
}

?>
