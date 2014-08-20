<?php

/**
 * 后台管理 产品 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Product
 * @author zhangchi
 */
class ProductManageGen extends BaseManageGen implements IBaseManageGen
{

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function Gen()
    {
        $result = "";
        $method = Control::GetRequest("m", "");
        switch ($method) {
            case "create":
                $result = self::GenCreate();
                break;
            case "modify":
                $result = self::GenModify();
                break;
            case "remove_to_bin":
                $result = self::GenRemoveToBin();
                break;
            case "list":
                $result = self::GenList();
                break;
        }

        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    /**
     * 新增
     * @return string 模板内容页面
     */
    private function GenCreate()
    {
        $tempContent = "";
        $siteId = Control::GetRequest("site_id", 0);
        $manageUserId = Control::GetManageUserId();
        $resultJavaScript = "";

        if ($siteId > 0 && $manageUserId > 0) {
            $tempContent = Template::Load("product/product_deal.html", "common");
            parent::ReplaceFirst($tempContent);
            $productManageData = new ProductManageData();

            if (!empty($_POST)) {
                $httpPostData = $_POST;

                $productId = $productManageData->Create($httpPostData);
                //加入操作日志
                $operateContent = 'Create Product,POST FORM:'
                    . implode('|', $_POST) . ';\r\nResult:productId:' . $productId;
                self::CreateManageUserLog($operateContent);

                if ($productId > 0) {
                    //授权给创建人
                    if ($manageUserId > 1) { //只有非ADMIN的要授权
                        $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
                        $manageUserAuthorityManageData->CreateForChannel($siteId, $productId, $manageUserId);
                    }

                    //删除缓冲
                    DataCache::RemoveDir(CACHE_PATH . '/product_data');

                    //javascript 处理

                    $closeTab = Control::PostRequest("CloseTab",0);
                    if($closeTab == 1){
                        //Control::CloseTab();
                        $resultJavaScript .= Control::GetCloseTab();
                    }else{
                        Control::GoUrl($_SERVER["PHP_SELF"]);
                    }
                } else {
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('product', 1)); //新增失败！
                }

            }

            $tempContent = str_ireplace("{CreateDate}", strval(date('Y-m-d', time())), $tempContent);
            $tempContent = str_ireplace("{SiteId}", strval($siteId), $tempContent);

            $fieldsOfChannel = $productManageData->GetFields();
            parent::ReplaceWhenCreate($tempContent, $fieldsOfChannel);

            $patterns = '/\{s_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
            parent::ReplaceEnd($tempContent);
            $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
        }

        return $tempContent;
    }


    /**
     * 生成资讯管理列表页面
     */
    private function GenList() {
        $channelId = Control::GetRequest("channel_id", 0);
        if ($channelId <= 0) {
            return null;
        }
        $manageUserId = Control::GetManageUserId();
        $channelManageData = new ChannelManageData();
        $siteId = $channelManageData->GetSiteId($channelId, false);
        if($siteId<=0){
            return null;
        }

        ///////////////判断是否有操作权限///////////////////
        $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
        $canExplore = $manageUserAuthorityManageData->CanExplore($siteId, $channelId, $manageUserId);
        if (!$canExplore) {
            return Language::Load('channel', 4);
        }

        //load template
        $tempContent = Template::Load("product/product_list.html", "common");

        parent::ReplaceFirst($tempContent);

        $pageSize = Control::GetRequest("ps", 20);
        $pageIndex = Control::GetRequest("p", 1);
        $searchKey = Control::GetRequest("search_key", "");
        $searchType = Control::GetRequest("search_type", -1);
        $searchKey = urldecode($searchKey);
        if (isset($searchKey) && strlen($searchKey) > 0) {
            $canSearch = $manageUserAuthorityManageData->CanSearch($siteId, $channelId, $manageUserId);
            if (!$canSearch) {
                return Language::Load('channel', 4);
            }
        }

        if ($pageIndex > 0 && $channelId > 0) {
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $tagId = "product_list";
            $allCount = 0;
            $isSelf = Control::GetRequest("is_self", 0);
            $productManageData = new ProductManageData();
            $arrProductList = $productManageData->GetList(
                $channelId,
                $pageBegin,
                $pageSize,
                $allCount,
                $searchKey,
                $searchType,
                $isSelf,
                $manageUserId
            );
            if (count($arrProductList) > 0) {
                Template::ReplaceList($tempContent, $arrProductList, $tagId);

                $styleNumber = 1;
                $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html", "common");
                $isJs = FALSE;
                $navUrl = "default.php?secu=manage&mod=product&m=list&channel_id=$channelId&p={0}&ps=$pageSize&isself=$isSelf";
                $jsFunctionName = "";
                $jsParamList = "";
                $pagerButton = Pager::ShowPageButton($pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex, $styleNumber, $isJs, $jsFunctionName, $jsParamList);

                $tempContent = str_ireplace("{pager_button}", $pagerButton, $tempContent);

            } else {
                Template::RemoveCustomTag($tempContent, $tagId);
                $tempContent = str_ireplace("{pager_button}", Language::Load('channel', 5), $tempContent);
            }
        }

        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }

} 