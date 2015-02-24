<?php
/**
 * 客户端 会员订单产品 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_User
 * @author zhangchi
 */
class UserOrderProductClientGen extends BaseClientGen implements IBaseClientGen {
    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenClient()
    {
        $result = "";
        $function = Control::GetRequest("f", "");
        switch ($function) {

            case "create":
                $result = self::GenCreate();
                break;

            case "list":
                $result = self::GenList();
                break;

        }
        $result = str_ireplace("{function}", $function, $result);
        return $result;
    }

    private function GenCreate()
    {
        $result = "[{}]";

        $userId = parent::GetUserId();

        if ($userId <= 0) {
            $resultCode = $userId; //会员检验失败,参数错误
        } else {
            $siteId = Control::PostOrGetRequest("site_id", 0);
            $userOrderId = intval(Control::PostOrGetRequest("UserOrderId",0));
            $productId = intval(Control::PostOrGetRequest("ProductId",0));
            $productIdDes = Des::Encrypt($productId, UserOrderData::USER_ORDER_DES_KEY);
            $productPriceId = intval(Control::PostOrGetRequest("ProductPriceId",0));
            $productPrice = floatval(Control::PostOrGetRequest("ProductPriceValue",0));
            $productPriceDes = Des::Encrypt($productPrice, UserOrderData::USER_ORDER_DES_KEY);
            $salePrice = floatval(Control::PostOrGetRequest("SalePrice",0));
            $salePriceDes = Des::Encrypt($salePrice, UserOrderData::USER_ORDER_DES_KEY);
            $saleCount = intval(Control::PostOrGetRequest("SaleCount",0));
            $saleCountDes = Des::Encrypt($saleCount, UserOrderData::USER_ORDER_DES_KEY);
            $subtotal = floatval(Control::PostOrGetRequest("Subtotal",0));
            $subtotalDes = Des::Encrypt($subtotal, UserOrderData::USER_ORDER_DES_KEY);

            //购物车中的活动产品id，非必须

            $activityProductId = intval(Control::PostOrGetRequest("ActivityProductId",0));


            if ($siteId > 0
                && $userOrderId > 0
                && $productId > 0
                && $productPriceId > 0
                && $productPrice > 0
                && $salePrice > 0
                && $saleCount > 0
                && $subtotal > 0
            ){
                //判断库存
                $productPriceClientData = new ProductPriceClientData();
                //即时库存，不缓存
                $productCount = $productPriceClientData->GetProductCount($productPriceId, false);
                if($saleCount > 0 && $saleCount <= $productCount){
                    $autoSendMessage = "";
                    $userOrderProductClientData = new UserOrderProductClientData();
                    $newUserOrderProductId = $userOrderProductClientData->Create(
                        $userOrderId,
                        $siteId,
                        $productId,
                        $productIdDes,
                        $productPriceId,
                        $saleCount,
                        $saleCountDes,
                        $productPrice,
                        $productPriceDes,
                        $salePrice,
                        $salePriceDes,
                        $subtotal,
                        $subtotalDes,
                        $autoSendMessage
                    );
                    if($newUserOrderProductId>0){
                        $resultCode = 1;
                        //修改库存数
                        $newProductCount = $productCount - $saleCount;
                        $productPriceClientData->ModifyProductCount($productPriceId, $newProductCount);

                        //删除对应购物车中的产品
                        $userCarClientData = new UserCarClientData();
                        $userCarClientData->DeleteByProductAndProductPrice(
                            $userId,
                            $siteId,
                            $productId,
                            $productPriceId,
                            $activityProductId
                        );


                        //重计订单总价
                        $userOrderClientData = new UserOrderClientData();
                        $userOrderClientData->ReCountAllPrice($userOrderId);

                        $result = Format::FixJsonEncode($userOrderProductClientData->GetOne($newUserOrderProductId));

                    }else{
                        $resultCode = -2; //数据库错误
                    }
                }else{
                    $resultCode = -3; //库存数不够
                }
            }else{
                $resultCode = -5;//参数不正确
            }

        }
        return '{"result_code":"' . $resultCode . '","user_order_product_create":' . $result . '}';
    }

    private function GenList()
    {

        $result = "[{}]";

        $userId = parent::GetUserId();

        if ($userId <= 0) {
            $resultCode = $userId;
        } else {

            $userOrderId = intval(Control::GetRequest("user_order_id",0));
            $siteId = intval(Control::GetRequest("site_id",0));

            if($userOrderId>0 && $siteId>0){
                $userOrderProductClientData = new UserOrderProductClientData();

                $arrList = $userOrderProductClientData->GetList($userOrderId,$userId,$siteId);

                if (count($arrList) > 0) {
                    $resultCode = 1;

                    $result = Format::FixJsonEncode($arrList);

                } else {
                    $resultCode = -1;
                }
            }else{
                $resultCode = -5;
            }

        }
        return '{"result_code":"' . $resultCode . '","user_order_product":{"user_order_product_list":' . $result . '}}';

    }
} 