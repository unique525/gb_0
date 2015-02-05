<?php
/**
 * 客户端 会员订单 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_User
 * @author zhangchi
 */
class UserOrderClientGen extends BaseClientGen implements IBaseClientGen {
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

    private function GenCreate(){
        $result = "[{}]";

        $userId = parent::GetUserId();

        if ($userId <= 0) {
            $resultCode = $userId; //会员检验失败,参数错误
        } else {

            //验证数据
            $userOrderProductArray = Control::PostOrGetRequest("s_UserOrderProductArray", "", false);
            $siteId = Control::GetRequest("site_id", 0);
            $userIdDes = Des::Encrypt($userId, UserOrderData::USER_ORDER_DES_KEY);
            $cookieId = "";
            $allPrice = floatval(Control::PostOrGetRequest("s_AllPrice", ""));
            $allPriceDes = Des::Encrypt($allPrice, UserOrderData::USER_ORDER_DES_KEY);
            $sendPrice = floatval(Control::PostOrGetRequest("s_SendPrice", ""));
            $userReceiveInfoId = intval(Control::PostOrGetRequest("s_UserReceiveInfoId",""));
            $sendTime = "";
            $autoSendMessage = "";
            $createDate = strval(date('Y-m-d H:i:s', time()));
            $createDateDes = Des::Encrypt($createDate, UserOrderData::USER_ORDER_DES_KEY);
            $arrUserCarId = Control::PostOrGetRequest("s_ArrUserCarId", "");

            if(
                $userReceiveInfoId>0 &&
                strlen($userOrderProductArray)>0
                && $siteId>0
                && $userId>0
            ){

                $userOrderClientData = new UserOrderClientData();

                $userOrderName = "";
                $userOrderNumber = UserOrderData::GenUserOrderNumber();
                $userOrderNumberDes = Des::Encrypt($userOrderNumber, UserOrderData::USER_ORDER_DES_KEY);

                $userOrderId = $userOrderClientData->Create(
                    $userOrderName,
                    $userOrderNumber,
                    $userOrderNumberDes,
                    $userId,
                    $userIdDes,
                    $cookieId,
                    $allPrice,
                    $allPriceDes,
                    $sendPrice,
                    $userReceiveInfoId,
                    $sendTime,
                    $autoSendMessage,
                    $siteId,
                    $createDate,
                    $createDateDes
                );

                if($userOrderId>0){
                    $userOrderProductArray = str_ireplace("\\","",$userOrderProductArray);

                    $arrProduct = Format::FixJsonDecode($userOrderProductArray);

                    $userOrderProductPublicData = new UserOrderProductPublicData();

                    for($i = 0;$i<count($arrProduct);$i++){

                        $productId = intval($arrProduct[$i]["ProductId"]);
                        $productIdDes = Des::Encrypt($productId, UserOrderData::USER_ORDER_DES_KEY);
                        $productPriceId = intval($arrProduct[$i]["ProductPriceId"]);
                        $productPrice = floatval($arrProduct[$i]["ProductPriceValue"]);
                        $productPriceDes = Des::Encrypt($productPrice, UserOrderData::USER_ORDER_DES_KEY);
                        $salePrice = floatval($arrProduct[$i]["SalePrice"]);
                        $salePriceDes = Des::Encrypt($salePrice, UserOrderData::USER_ORDER_DES_KEY);
                        $saleCount = intval($arrProduct[$i]["SaleCount"]);
                        $saleCountDes = Des::Encrypt($saleCount, UserOrderData::USER_ORDER_DES_KEY);
                        $subtotal = floatval($arrProduct[$i]["Subtotal"]);
                        $subtotalDes = Des::Encrypt($subtotal, UserOrderData::USER_ORDER_DES_KEY);

                        //判断库存
                        $productPricePublicData = new ProductPricePublicData();
                        //即时库存，不缓存
                        $productCount = $productPricePublicData->GetProductCount($productPriceId, false);
                        if($saleCount > 0 && $saleCount <= $productCount){
                            $autoSendMessage = "";
                            $resultOfUserOrderProduct = $userOrderProductPublicData->Create(
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
                            if($resultOfUserOrderProduct>0){
                                //修改库存数
                                $newProductCount = $productCount - $saleCount;
                                $productPricePublicData->ModifyProductCount($productPriceId, $newProductCount);
                            }
                        }



                    }

                    //删除购物车
                    $userCarClientData = new UserCarClientData();
                    $arrUserCarId = str_ireplace("_",",",$arrUserCarId);
                    $userCarClientData->BatchDelete($arrUserCarId, $userId);

                    $resultCode = 1;

                }else{
                    //编号出错
                    $resultCode = -4;
                }
            }else{
                $resultCode = -5;
                //出错，返回
            }

        }
        return '{"result_code":"' . $resultCode . '","user_order_create":' . $result . '}';

    }
} 