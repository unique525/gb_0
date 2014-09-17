<?php

/**
 * 产品价格后台管理生成类
 * @category iCMS
 * @package iCMS_Rules_Gen_ProductPrice
 * @author yanjiuyuan
 */
class ProductPriceManageGen extends BaseManageGen implements IBaseManageGen
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
            case "async_modify_state":
                $result = self::AsyncModifyState();
                break;
            case "modify":
                $result = self::GenModify();
                break;
            case "async_list":
                $result = self::AsyncList();
                break;
        }

        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    /**
     * 生成产品价格管理新增页面
     * @return mixed|string
     */
    private function GenCreate()
    {
        $manageUserId = Control::GetManageUserId();
        $productId = Control::GetRequest("product_id", 0);
        $pageIndex = Control::GetRequest("p", 0);
        $tempContent = Template::Load("product/product_price_deal.html", "common");
        $resultJavaScript = "";
        if ($productId > 0 && $manageUserId > 0) {
            parent::ReplaceFirst($tempContent);
            $productPriceManageData = new ProductPriceManageData();
            if (!empty($_POST)) {
                $httpPostData = $_POST;
                $ProductPriceId = $productPriceManageData->Create($httpPostData);

                //加入操作日志
                $operateContent = 'Create ProductPrice,POST FORM:' . implode('|', $_POST) . ';\r\nResult:ProductPriceId:' . $ProductPriceId;
                self::CreateManageUserLog($operateContent);

                if ($ProductPriceId > 0) {
                    //javascript 处理
                    //$resultJavaScript .= Control::GetJqueryMessage(Language::Load('product', 1));
                    $resultJavaScript .= '<' . 'script type="text/javascript">window.parent.closeProductPriceDialog();window.parent.getProductPriceList();</script>';
                } else {
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('product', 2)); //新增失败！
                }
            }
            $tempContent = str_ireplace("{PageIndex}", $pageIndex, $tempContent);
            $tempContent = str_ireplace("{CreateDate}",date("Y-m-d H:i:s"), $tempContent);
            $tempContent = str_ireplace("{ManageUserId}", strval($manageUserId), $tempContent);
            $tempContent = str_ireplace("{ProductId}", strval($productId), $tempContent);
            $fieldsOfProductPrice = $productPriceManageData->GetFields();
            parent::ReplaceWhenCreate($tempContent, $fieldsOfProductPrice);

            $patterns = '/\{s_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
            parent::ReplaceEnd($tempContent);
            $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);

        }
        return $tempContent;
    }

    /**
     * 修改投票状态
     * @return string 修改结果
     */
    private function AsyncModifyState()
    {
        //$result = -1;
        $ProductPriceId = Control::GetRequest("product_price_id", 0);
        $state = Control::GetRequest("state",0);
        if ($ProductPriceId > 0) {
            $productData = new ProductPriceManageData();
            $result = $productData->ModifyState($ProductPriceId,$state);
            //加入操作日志
            $operateContent = 'ModifyState ProductPrice,Get FORM:' . implode('|', $_GET) . ';\r\nResult:ProductPriceId:' . $ProductPriceId;
            self::CreateManageUserLog($operateContent);
        } else {
            $result = -1;
        }
        return Control::GetRequest("jsonpcallback","") . '({"result":"'.$result.'"})';
    }

    /**
     * 生成产品价格修改页面
     * @return mixed|string
     */
    private function GenModify()
    {
        $manageUserId = Control::GetManageUserId();
        $tempContent = Template::Load("product/product_price_deal.html", "common");
        $resultJavaScript="";
        $ProductPriceId = Control::GetRequest("product_price_id", 0);
        $pageIndex = Control::GetRequest("p", 1);
        parent::ReplaceFirst($tempContent);
        $productPriceManageData = new ProductPriceManageData();
        if ($ProductPriceId >0 && $manageUserId > 0) {
            if (!empty($_POST)) {
                $httpPostData = $_POST;
                $result = $productPriceManageData->Modify($httpPostData, $ProductPriceId);

                //加入操作日志
                $operateContent = 'Modify ProductPrice,POST FORM:' . implode('|', $_POST) . ';\r\nResult:ProductPriceId:' . $ProductPriceId;
                self::CreateManageUserLog($operateContent);

                if ($result > 0) {
                    //javascript 处理
                    $resultJavaScript .= '<' . 'script type="text/javascript">window.parent.closeProductPriceDialog();window.parent.getProductPriceList();</script>';
                } else {
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('product', 4)); //修改失败！
                }
            }
            $arrList = $productPriceManageData->GetOne($ProductPriceId);
            Template::ReplaceOne($tempContent, $arrList, false);
            $tempContent = str_ireplace("{PageIndex}", strval($pageIndex), $tempContent);
        }
        //替换掉{s XXX}的内容
        $patterns = '/\{s_(.*?)\}/';
        $tempContent = preg_replace($patterns, "", $tempContent);
        parent::ReplaceEnd($tempContent);
        $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);
        return $tempContent;
    }

    /**
     * 获取产品价格json数组
     * @return mixed|string
     */
    private function AsyncList()
    {
        $result=array();
        $productId = Control::GetRequest("product_id", 0);
        $pageSize = Control::GetRequest("ps", -1);
        $order = Control::GetRequest("order", "");

        if ($productId > 0) {
            $productPriceManageData = new ProductPriceManageData();
            $result = $productPriceManageData->GetList($productId, $order ,$pageSize);
            $result = json_encode($result);
        }
        return Control::GetRequest("jsonpcallback","") . '({"result":' . $result . '})';
    }

}

?>
