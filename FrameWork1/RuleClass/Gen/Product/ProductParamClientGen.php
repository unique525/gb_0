<?php
/**
 * 客户端 产品参数 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Product
 * @author hy
 */
class ProductParamClientGen extends BaseClientGen implements IBaseClientGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenClient(){
        $result = "";
        $function = Control::GetRequest("f", "");

        switch ($function) {

            case "list_by_product":
                $result = self::GetListByProduct();
                break;

        }
        $result = str_ireplace("{function}", $function, $result);
        return $result;
    }

    private function GetListByProduct(){
        $result = "[{}]";
        $productId = Control::GetRequest("product_id", 0);
        if ($productId > 0) {

            $productParamClientData = new ProductParamClientData();
            $arrList = $productParamClientData->GetList($productId);
            if (count($arrList) > 0) {
                $result = Format::FixJsonEncode($arrList);
                $resultCode = 1;
            }else{
                $resultCode = -2;
            }
        }
        else{
            $resultCode = -1;
        }
        return '{"result_code":"'.$resultCode.'","product":{"product_param_list":' . $result . '}}';
    }

} 