<?php

/**
 * 产品参数类型管理生成类
 * @category iCMS
 * @package iCMS_Rules_Gen_Vote
 * @author yanjiuyuan
 */
class ProductParamTypeManageGen extends BaseManageGen implements IBaseManageGen {

    /**
     * 主生成方法(继承接口)
     * @return string 返回输出的HTML内容
     */
    function Gen() {
        $result = "";
        $method = Control::GetRequest("m", "");
        switch ($method) {
            case "create":
                $result = self::GenCreate();
                break;
            case "async_modify_state":
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
     * 生成产品管理参数类型新增页面
     * @return mixed|string
     */
    private function GenCreate()
    {
        $manageUserId = Control::GetManageUserId();
        $productParamTypeClassId = Control::GetRequest("product_param_type_class_id", 0);
        $pageIndex = Control::GetRequest("p", 0);
        $tempContent = Template::Load("product/product_param_type_deal.html", "common");
        if ($manageUserId > 0) {
            parent::ReplaceFirst($tempContent);
            $productParamTypeManageData = new ProductParamTypeManageData();
            if (!empty($_POST)) {
                $httpPostData = $_POST;
                $productParamTypeId = $productParamTypeManageData->Create($httpPostData);

                //加入操作日志
                $operateContent = 'Create ProductParamType,POST FORM:' . implode('|', $_POST) . ';\r\nResult:productParamTypeId:' . $productParamTypeId;
                self::CreateManageUserLog($operateContent);

                if ($productParamTypeId > 0) {
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
            $tempContent = str_ireplace("{ProductParamTypeClassId}", strval($productParamTypeClassId), $tempContent);

            $fieldsOfProductParamType = $productParamTypeManageData->GetFields();
            parent::ReplaceWhenCreate($tempContent, $fieldsOfProductParamType);

            $patterns = '/\{s_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
            parent::ReplaceEnd($tempContent);
        }
        return $tempContent;
    }

    /**
     * 停用产品参数类型
     * @return mixed|string
     */
    private function AsyncModifyState() {
        //$result = -1;
        $productParamTypeId = Control::GetRequest("product_param_type_id", 0);
        $state = Control::GetRequest("state",0);
        if ($productParamTypeId > 0) {
            $productParamTypeData = new ProductParamTypeManageData();
            $result = $productParamTypeData->ModifyState($productParamTypeId,$state);
            //加入操作日志
            $operateContent = 'ModifyState ProductParamType,Get FORM:' . implode('|', $_GET) . ';\r\nResult:productParamTypeId:' . $productParamTypeId;
            self::CreateManageUserLog($operateContent);
        } else {
            $result = -1;
        }
        return Control::GetRequest("jsonpcallback","") . '({"result":"'.$result.'"})';
    }

    /**
     * 生成产品参数类型修改页面
     * @return mixed|string
     */
    private function GenModify() {
        $tempContent = Template::Load("product/product_param_type_deal.html", "common");
        $productParamTypeId = Control::GetRequest("product_param_type_id", 0);
        $pageIndex = Control::GetRequest("p", 0);
        parent::ReplaceFirst($tempContent);
        $productParamTypeManageData = new ProductParamTypeManageData();
        if ($productParamTypeId > 0) {
            if (!empty($_POST)) {
                $httpPostData = $_POST;
                $result = $productParamTypeManageData->Modify($httpPostData, $productParamTypeId);

                //加入操作日志
                $operateContent = 'Modify ProductParamType,POST FORM:' . implode('|', $_POST) . ';\r\nProductParamTypeId:' . $productParamTypeId;
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
            $arrList = $productParamTypeManageData->GetOne($productParamTypeId);
            Template::ReplaceOne($tempContent, $arrList, false, false);
            $tempContent = str_ireplace("{PageIndex}", strval($pageIndex), $tempContent);
        }
        //替换掉{s XXX}的内容
        $patterns = '/\{s_(.*?)\}/';
        $tempContent = preg_replace($patterns, "", $tempContent);
        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }

    /**
     * 产品参数类型管理列表页面
     * @return mixed|string
     */
    private function GenList() {
        $templateContent = Template::Load("product/product_param_type_list.html", "common");
        $productParamTypeClassId = Control::GetRequest("product_param_type_class_id", 0);
        $pageSize = Control::GetRequest("ps", 20);
        $searchKey = Control::GetRequest("search_key", "");
        $searchKey = urldecode($searchKey);
        $pageIndex = Control::GetRequest("p", 1);

        if ($pageIndex > 0 && $productParamTypeClassId > 0) {
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $tagId = "product_param_type_list";
            $allCount = 0;
            $productParamTypeManageData = new ProductParamTypeManageData();
            $arrList = $productParamTypeManageData->GetListForPager($productParamTypeClassId, $pageBegin, $pageSize, $allCount, $searchKey);
            if (count($arrList) > 0) {
                Template::ReplaceList($tempContent, $arrList, $tagId);
                $styleNumber = 1;
                $pagerTemplate = Template::Load("pager/pager_style".$styleNumber.".html","common");
                $isJs = FALSE;
                $navUrl = "/default.php?secu=manage&mod=product_param_type&m=list&product_param_type_class_id=$productParamTypeClassId&p={0}&ps=$pageSize";
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
