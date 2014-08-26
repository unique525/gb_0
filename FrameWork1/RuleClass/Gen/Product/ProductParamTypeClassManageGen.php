<?php

/**
 * 产品参数类别后台管理生成类
 * @category iCMS
 * @package iCMS_Rules_Gen_Product
 * @author yanjiuyuan
 */
class ProductParamTypeClassManageGen extends BaseManageGen implements IBaseManageGen
{

    /**
     * 牵引生成方法(继承接口)
     * @return string 返回输出的HTML内容
     */
    public function Gen()
    {
        $result = "";
        $method = Control::GetRequest("m", "");
        switch ($method) {
            case "create":
                $result = self::GenCreate();
                break;
            case "modify_state":
                $result = self::AsyncModifyState();
                break;
            case "modify":
                $result = self::GenModify();
                break;
            case "list":
                $result = self::GenList();
                break;
        }

        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    /**
     * 生成产品参数类别管理新增页面
     * @return mixed|string
     */
    private function GenCreate()
    {
        $manageUserId = Control::GetManageUserId();
        $siteId = Control::GetRequest("site_id", 0);
        $channelId = Control::GetRequest("channel_id", 0);
        $pageIndex = Control::GetRequest("p", 0);
        $tempContent = Template::Load("product/product_param_type_class_deal.html", "common");
        if ($manageUserId > 0) {
            parent::ReplaceFirst($tempContent);
            $productManageData = new ProductParamTypeClassManageData();
            if (!empty($_POST)) {
                $httpPostData = $_POST;
                $productParamTypeClassId = $productManageData->Create($httpPostData);

                //加入操作日志
                $operateContent = 'Create ProductParamTypeClass,POST FORM:' . implode('|', $_POST) . ';\r\nResult:productParamTypeClassId:' . $productParamTypeClassId;
                self::CreateManageUserLog($operateContent);

                if ($productParamTypeClassId > 0) {
                    //javascript 处理
                    Control::ShowMessage(Language::Load('product', 1));
                    $jsCode = 'parent.location.href=parent.location.href';
                    Control::RunJavascript($jsCode);
                } else {
                    Control::ShowMessage(Language::Load('product', 2));
                }
                return "";
            }
            $tempContent = str_ireplace("{PageIndex}", $pageIndex, $tempContent);
            $tempContent = str_ireplace("{CreateDate}",date("Y-m-d H:i:s"), $tempContent);
            $tempContent = str_ireplace("{ManageUserId}", strval($manageUserId), $tempContent);
            $tempContent = str_ireplace("{SiteId}", strval($siteId), $tempContent);
            $tempContent = str_ireplace("{ChannelId}", strval($channelId), $tempContent);
            $tempContent = str_ireplace("{IpMaxCount}", "10", $tempContent);
            $fieldsOfProduct = $productManageData->GetFields();
            parent::ReplaceWhenCreate($tempContent, $fieldsOfProduct);

            $patterns = '/\{s_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
            parent::ReplaceEnd($tempContent);

        }
        return $tempContent;
    }

    /**
     * 修改产品参数类别状态
     * @return string 修改结果
     */
    private function AsyncModifyState()
    {
        //$result = -1;
        $productParamTypeClassId = Control::GetRequest("product_param_type_class_id", 0);
        $state = Control::GetRequest("state",0);
        if ($productParamTypeClassId > 0) {
            $productData = new ProductParamTypeClassManageData();
            $result = $productData->ModifyState($productParamTypeClassId,$state);
            //加入操作日志
            $operateContent = 'ModifyState ProductParamTypeClass,Get FORM:' . implode('|', $_GET) . ';\r\nResult:productParamTypeClassId:' . $productParamTypeClassId;
            self::CreateManageUserLog($operateContent);
        } else {
            $result = -1;
        }
        return Control::GetRequest("jsonpcallback","") . '({"result":"'.$result.'"})';
    }

    /**
     * 生成产品参数类别修改页面
     * @return mixed|string
     */
    private function GenModify()
    {
        $tempContent = Template::Load("product/product_param_type_class_deal.html", "common");
        $productParamTypeClassId = Control::GetRequest("product_param_type_class_id", 0);
        $pageIndex = Control::GetRequest("p", 1);
        parent::ReplaceFirst($tempContent);
        $productManageData = new ProductParamTypeClassManageData();
        if ($productParamTypeClassId > 0) {
            if (!empty($_POST)) {
                $httpPostData = $_POST;
                $result = $productManageData->Modify($httpPostData, $productParamTypeClassId);

                //加入操作日志
                $operateContent = 'Modify ProductParamTypeClass,POST FORM:' . implode('|', $_POST) . ';\r\nResult:productParamTypeClassId:' . $productParamTypeClassId;
                self::CreateManageUserLog($operateContent);

                if ($result > 0) {
                    //javascript 处理
                    Control::ShowMessage(Language::Load('product', 3));
                    $jsCode = 'parent.location.href=parent.location.href';
                    Control::RunJavascript($jsCode);
                } else {
                    Control::ShowMessage(Language::Load('product', 4));
                }
            }
            $arrList = $productManageData->GetOne($productParamTypeClassId);
            Template::ReplaceOne($tempContent, $arrList, false);
            $tempContent = str_ireplace("{PageIndex}", strval($pageIndex), $tempContent);
        }
        //替换掉{s XXX}的内容
        $patterns = '/\{s_(.*?)\}/';
        $tempContent = preg_replace($patterns, "", $tempContent);
        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }

    /**
     * 产品参数类别管理列表页面
     * @return mixed|string
     */
    private function GenList()
    {

        $templateContent = Template::Load("product/product_param_type_class_list.html", "common");

        $siteId = Control::GetRequest("site_id", 0);
        $channelId = Control::GetRequest("channel_id", 0);
        $pageSize = Control::GetRequest("ps", 20);
        $pageIndex = Control::GetRequest("p", 1);
        $searchKey = Control::GetRequest("search_key", "");
        $searchKey = urldecode($searchKey);

        if ($pageIndex > 0) {
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $tagId = "product_param_type_class_list";
            $allCount = 0;
            $productManageData = new ProductParamTypeClassManageData();
            $arrList = $productManageData->GetListForPager($siteId, $channelId, $pageBegin, $pageSize, $allCount, $searchKey);
            if (count($arrList) > 0) {
                Template::ReplaceList($tempContent, $arrList, $tagId);
                $styleNumber = 1;
                $pagerTemplate = Template::Load("pager/pager_style".$styleNumber.".html","common");
                $isJs = FALSE;
                $navUrl = "/default.php?secu=manage&mod=product_param_type_class&m=list&site_id=$siteId&channel_id=$channelId&p={0}&ps=$pageSize";
                $jsFunctionName = "";
                $jsParamList = "";
                $pagerButton = Pager::ShowPageButton($pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex, $styleNumber, $isJs, $jsFunctionName, $jsParamList);
                Template::ReplaceList($templateContent,$arrList,$tagId);
                $templateContent = str_ireplace("{pager_button}", $pagerButton, $templateContent);
            } else {
                Template::RemoveCustomTag($templateContent, $tagId);
                $templateContent = str_ireplace("{pager_button}", Language::Load("product", 101), $templateContent);
            }
        }
        parent::ReplaceEnd($templateContent);
        return $templateContent;
    }

}

?>
