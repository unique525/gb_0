<?php
/**
 * 客户端 产品评论 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Product
 * @author hy
 */
class ProductCommentClientGen extends BaseClientGen implements IBaseClientGen {

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

        $productId = Control::GetRequest("product_id", 0);
        $pageIndex = Control::GetRequest("p", 1);
        $pageSize = Control::GetRequest("ps", 20);
        $order = Control::GetRequest("order", 0);
        if($pageIndex > 0 && $productId>0){
            $allCount = 0;
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $productCommentClientData = new ProductCommentClientData();
            $arrList = $productCommentClientData->GetListByProductId(
                $productId,
                $pageBegin,
                $pageSize,
                $allCount,
                $order
            );
            if (count($arrList) > 0) {
                $resultCode = 1;
                $result = Format::FixJsonEncode($arrList);
            }else{
                $resultCode = -2;
            }
        }
        else{
            $resultCode = -1;
        }

        return '{"result_code":"'.$resultCode.'","product":{"product_comment_list":' . $result . '}}';
    }

} 