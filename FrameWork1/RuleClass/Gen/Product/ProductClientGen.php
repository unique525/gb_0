<?php
/**
 * 客户端 产品 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Product
 * @author zhangchi
 */
class ProductClientGen extends BaseClientGen implements IBaseClientGen {

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
            case "list_for_search":
                $result = self::GetListForSearch();
                break;
            case "list_by_channel":
                $result = self::GetListByChannel();
                break;
            case "list_of_discount": //打折商品
                $result = self::GetListOfDiscount();
                break;
            case "list_of_rec_level"://推荐商品
                $result = self::GetListOfRecLevel();
                break;
            case "get_one":
                $result = self::GetOne();
                break;

        }
        $result = str_ireplace("{function}", $function, $result);
        return $result;
    }

    /**
     * 根据搜索条件返回列表数据集
     * @return string
     */
    private function GetListForSearch(){

        $result = "[{}]";

        $channelId = Control::GetRequest("channel_id", 0);

        if($channelId>0){
            $pageSize = Control::GetRequest("ps", 20);
            $order = Control::GetRequest("order", 0);
            $pageIndex = Control::GetRequest("p", 1);
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $searchKey = Control::GetRequest("search_key", "");
            $searchKey = urldecode($searchKey);
            $channelClientData = new ChannelClientData();
            $channelIds = $channelClientData->GetChildrenChannelId($channelId, true);
            if(strlen($channelIds)>0){
                $channelIds = $channelIds . ',' . $channelId;
            }else{
                $channelIds = $channelId;
            }
            if(strlen($channelIds)>0){
                $channelIds = $channelIds . ',' . $channelId;
            }else{
                $channelIds = $channelId;
            }
            $productClientData = new ProductClientData();
            $arrList = $productClientData->GetListForSearch($channelIds, $pageBegin, $pageSize, $allCount, $searchKey, 0, $order);
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

        return '{"result_code":"'.$resultCode.'","product":{"product_list":' . $result . '}}';
    }

    /**
     * 根据频道返回列表数据集
     * @return string
     */
    private function GetListByChannel(){

        $result = "[{}]";

        $channelId = Control::GetRequest("channel_id", 0);

        if($channelId>0){
            $pageIndex = intval(Control::GetRequest("p", 1));
            $pageSize = intval(Control::GetRequest("ps", 20));
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $orderBy = Control::GetRequest("order", "");

            $channelClientData = new ChannelClientData();
            $channelIds = $channelClientData->GetChildrenChannelId($channelId, true);
            if(strlen($channelIds)>0){
                $channelIds = $channelIds . ',' . $channelId;
            }else{
                $channelIds = $channelId;
            }
            $productClientData = new ProductClientData();
            $arrList = $productClientData->GetListOfChannelId(
                $channelIds,
                $orderBy,
                $pageBegin,
                $pageSize
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

        return '{"result_code":"'.$resultCode.'","product":{"product_list":' . $result . '}}';
    }

    private function GetListOfDiscount(){
        $result = "[{}]";
        $channelId = Control::GetRequest("channel_id", 0);
        if ($channelId > 0) {
            $topCount = Control::GetRequest("top", 5);
            $orderBy = Control::GetRequest("order", 0);

            $productClientData = new ProductClientData();
            $ownChannelAndChildChannelId = parent::GetOwnChannelIdAndChildChannelId($channelId);
            $arrProductList = $productClientData->GetDiscountListByChannelId($ownChannelAndChildChannelId, $orderBy, $topCount);
            if (count($arrProductList) > 0) {
                $resultCode = 1;
                $result = Format::FixJsonEncode($arrProductList);
            }else{
                $resultCode = -2;
            }
        }
        else{
            $resultCode = -1;
        }
        return '{"result_code":"'.$resultCode.'","product":{"product_list":' . $result . '}}';
    }

    private function GetListOfRecLevel(){
        $result = "[{}]";
        $resultCode = 0;
        $siteId = Control::GetRequest("site_id", 0);
        if ($siteId > 0) {
            $topCount = Control::GetRequest("top", 5);
            $recLevel = Control::GetRequest("rec_level", 0);
            $orderBy = Control::GetRequest("order", 0);

            $productClientData = new ProductClientData();
            $arrProductList = $productClientData->GetListByRecLevel($siteId, $recLevel, $orderBy, $topCount);
            if (count($arrProductList) > 0) {
                $result = Format::FixJsonEncode($arrProductList);
            }else{
                $resultCode = -2;
            }
        }
        else{
            $resultCode = -1;
        }
        return '{"result_code":"'.$resultCode.'","product":{"product_list":' . $result . '}}';
    }

    private function GetListByProduct(){
        $result = "[{}]";
        $productId = Control::GetRequest("product_id", 0);
        if ($productId > 0) {

            $productClientData = new ProductClientData();
            $arrOne = $productClientData->GetOne($productId);
            if (count($arrOne) > 0) {
                $resultCode = 1;
                $result = Format::FixJsonEncode($arrOne);
            }else{
                $resultCode = -2;
            }
        }
        else{
            $resultCode = -1;
        }
        return '{"result_code":"'.$resultCode.'","product":{"product_list":' . $result . '}}';
    }

    private function GetOne(){

        $result = "[{}]";

        $productId = intval(Control::PostOrGetRequest("product_id",0));


            if(
                $productId > 0
            ){
                $productClientData = new ProductClientData();
                $arrOne = $productClientData->GetOne($productId, TRUE);

                $result = Format::FixJsonEncode($arrOne);
                $resultCode = 1; //

            }else{
                $resultCode = -6; //参数错误;
            }


        return '{"result_code":"' . $resultCode . '","product":' . $result . '}';


    }
} 