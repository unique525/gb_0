<?php
/**
 * 客户端 产品图片 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Product
 * @author hy
 */
class ProductPicClientGen extends BaseClientGen implements IBaseClientGen {

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

    /**
     * 返回列表数据集
     * @return string
     */
    private function GetListByProduct(){
        $result = "[{}]";
        $resultCode = 0;
        $productId = Control::PostOrGetRequest("product_id", 0);
        if ($productId > 0) {
            $topCount = Control::GetRequest("top", 100);
            $orderBy = Control::GetRequest("order", 0);

            $productPicClientData = new ProductPicClientData();
            $arrProductPicList = $productPicClientData->GetList($productId, $orderBy, $topCount);
            if (count($arrProductPicList) > 0) {
                $resultCode = 1;
                $result = Format::FixJsonEncode($arrProductPicList);
                //$result = ;
            }else{
                $resultCode = -2;
            }
        }else{
            $resultCode = -1;
        }
        return '{"result_code":"'.$resultCode.'","product":{"product_pic_list":' . $result . '}}';
    }
} 