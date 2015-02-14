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

            case "modify_state":
                $result = self::ModifyState();

                break;

            case "send_price":
                $result = self::GenSendPrice();
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
            $siteId = Control::GetRequest("site_id", 0);
            $userIdDes = Des::Encrypt($userId, UserOrderData::USER_ORDER_DES_KEY);
            $cookieId = "";
            $allPrice = floatval(Control::PostOrGetRequest("AllPrice", ""));
            $allPriceDes = Des::Encrypt($allPrice, UserOrderData::USER_ORDER_DES_KEY);
            $sendPrice = floatval(Control::PostOrGetRequest("SendPrice", ""));
            $userReceiveInfoId = intval(Control::PostOrGetRequest("UserReceiveInfoId",""));
            $sendTime = "";
            $autoSendMessage = "";
            $createDate = strval(date('Y-m-d H:i:s', time()));
            $createDateDes = Des::Encrypt($createDate, UserOrderData::USER_ORDER_DES_KEY);

            if(
                $userReceiveInfoId>0
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
                    $result = Format::FixJsonEncode($userOrderClientData->GetOne($userOrderId,$userId,$siteId));


                    //客户端上，单独调用订单产品表新增接口

                    //客户端上，单独调用购物车删除接口


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

    private function ModifyState(){
        $result = "[{}]";

        $userId = parent::GetUserId();

        if ($userId <= 0) {
            $resultCode = $userId; //会员检验失败,参数错误
        } else {

            //验证数据
            $siteId = Control::GetRequest("site_id", 0);
            $userOrderId = intval(Control::PostOrGetRequest("UserOrderId",""));
            $state = intval(Control::PostOrGetRequest("State",""));

            if(
                $userOrderId>0
                && $siteId>0
                && $userId>0
            ){

                $userOrderClientData = new UserOrderClientData();

                $resultOfModify = $userOrderClientData->ModifyState(
                    $userOrderId,
                    $userId,
                    $state
                );

                if($resultOfModify>0){

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
        return '{"result_code":"' . $resultCode . '","user_order_modify_state":' . $result . '}';

    }




    /**
     * 计算发货费用（订单产品已经全部新增完成的情况下）
     * @return string
     */
    private function GenSendPrice(){

        $result = "[{}]";

        $userId = parent::GetUserId();

        if ($userId <= 0) {
            $resultCode = $userId; //会员检验失败,参数错误
        } else {

            //验证数据
            $siteId = Control::GetRequest("site_id", 0);

            $userOrderId = intval(Control::PostOrGetRequest("UserOrderId", ""));

            if(
                $userOrderId>0
                && $siteId>0
                && $userId>0
            ){
                $resultCode = 1;

                $userOrderProductClientData = new UserOrderProductClientData();
                $arrUserOrderProductList = $userOrderProductClientData->GetList(
                    $userOrderId,
                    $userId,
                    $siteId
                );

                $totalProductPrice = 0;//订单总价

                $productPublicData = new ProductPublicData();

                $maxProductSendPrice = 0;
                $maxProductSendPriceAdd = 0;
                $sumProductSendPrice = 0;
                $sumProductSendPriceAdd = 0;

                for($i=0;$i<count($arrUserOrderProductList);$i++){
                    $buyCount = intval($arrUserOrderProductList[$i]["SaleCount"]);
                    $subtotal = intval($arrUserOrderProductList[$i]["Subtotal"]);
                    $productId = intval($arrUserOrderProductList[$i]["ProductId"]);

                    $totalProductPrice = $subtotal;

                    $currentProductSendPrice = $productPublicData->GetSendPrice($productId, TRUE);
                    $currentProductSendPriceAdd = $productPublicData->GetSendPriceAdd($productId, TRUE);

                    $sumProductSendPrice = $sumProductSendPrice + $currentProductSendPrice;
                    if($buyCount>1){
                        //续重费
                        $sumProductSendPriceAdd = $sumProductSendPrice + $currentProductSendPriceAdd*($buyCount-1);
                    }

                    if ($currentProductSendPrice > $maxProductSendPrice){
                        $maxProductSendPrice = $currentProductSendPrice;
                        if($buyCount>1){
                            //续重费
                            $maxProductSendPriceAdd = $currentProductSendPriceAdd*($buyCount-1);
                        }
                    }

                }


                /******
                 * 发货费用模式
                 *   （0）全场免费
                 *   （1）达到某金额免费，否则取最高项运费
                 *   （2）所有运费累加，并计算续重费，然后客服手动修改运费
                 *   （3）取最高的运费，并计算最高项的续重费
                 */
                $siteConfigData = new SiteConfigData($siteId);
                $productSendPriceMode = $siteConfigData->ProductSendPriceMode;
                $productSendPriceFreeLimit = $siteConfigData->ProductSendPriceFreeLimit;
                $sendPrice = 0;
                switch($productSendPriceMode){
                    case 0:
                        //全场免费
                        $sendPrice = 0;
                        break;
                    case 1:
                        //达到某金额免费，否则取最高项运费
                        if ($totalProductPrice>= $productSendPriceFreeLimit){
                            $sendPrice = 0;
                        }else{
                            $sendPrice = $maxProductSendPrice + $maxProductSendPriceAdd;
                        }
                        break;
                    case 2:
                        //所有运费累加，并计算续重费，然后客服手动修改运费
                        $sendPrice = $sumProductSendPrice + $sumProductSendPriceAdd;
                        break;
                    case 3:
                        $sendPrice = $maxProductSendPrice + $maxProductSendPriceAdd;
                        break;
                }

                $result = $sendPrice;
            }else{
                $resultCode = -5;
                //出错，返回
            }

        }
        return '{"result_code":"' . $resultCode . '","user_order_send_price":' . $result . '}';


    }

    private function GenList(){

        $result = "[{}]";
        $userId = parent::GetUserId();

        if ($userId <= 0) {
            $resultCode = $userId;
        } else {

            $siteId = intval(Control::GetRequest("site_id", 0));

            $pageSize = intval(Control::GetRequest("ps", 20));
            $pageIndex = intval(Control::GetRequest("p", 1));
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $state = intval(Control::PostOrGetRequest("State", 20));

            $userOrderClientData = new UserOrderClientData();

            if($state<0){
                $arrUserOrderList = $userOrderClientData->GetList($userId, $siteId, $pageBegin, $pageSize);
            }else{
                $arrUserOrderList = $userOrderClientData->GetListByState($userId, $siteId, $state, $pageBegin, $pageSize);
            }

            if (count($arrUserOrderList) > 0) {
                $resultCode = 1;

                $result = Format::FixJsonEncode($arrUserOrderList);

            } else {
                $resultCode = -1;
            }

        }
        return '{"result_code":"' . $resultCode . '","user_order":{"user_order_list":' . $result . '}}';




    }
} 