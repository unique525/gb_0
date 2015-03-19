<?php
/**
 * 前台管理 会员订单 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_User
 * @author yin
 */
class UserOrderPublicGen extends BasePublicGen implements IBasePublicGen{

    const ERROR_STATE = -2;
    /**
     * @return string
     */
    public function GenPublic(){
        $method = Control::GetRequest("a","");
        $result = "";
        switch($method){
            case "list":
                $result = self::GenList();
                break;
            case "async_get_count_by_state":
                $result = self::AsyncGetCountByState();
                break;
            case "confirm":
                $result = self::GenConfirm();
                break;
            case "detail":
                $result = self::GenDetail();
                break;
            case "submit":
                $result = self::GenSubmit();
                break;
            case "pay":
                $result = self::GenPay();
                break;
            case "submit_pay":
                $result = self::GenSubmitPay();
                break;
            case "receive_pay":
                //$result = self::GenReceivePay();
                break;
            case "alipay_notify":
                self::AlipayNotify();
                break;
            case "alipay_return":
                $result = self::AlipayReturn();
                break;
            case "async_modify_state":
                $result = self::AsyncModifyState();
                break;
        }
        return $result;
    }


    private function GenList(){
        $userId = Control::GetUserId();
        $siteId = parent::GetSiteIdByDomain();

        if($userId > 0 && $siteId > 0){
            //$templateFileUrl = "user/user_order_list.html";
            //$templateName = "default";
            //$templatePath = "front_template";
            //$templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);
            $templateContent = parent::GetDynamicTemplateContent("user_order_list");
            parent::ReplaceFirst($templateContent);
            parent::ReplaceSiteInfo($siteId, $templateContent);

            $pageIndex = Control::GetRequest("p",1);
            $pageSize = Control::GetRequest("ps",30);
            $state = Control::GetRequest("state",-1);
            $pageBegin = ($pageIndex-1)*$pageSize;
            $allCount = 0;

            $tagId = "user_order_list";
            $userOrderPublicData = new UserOrderPublicData();
            if($state < 0){
                $arrUserOrderList = $userOrderPublicData->GetList($userId,$siteId,$pageBegin,$pageSize,$allCount);
            }else{
                $arrUserOrderList = $userOrderPublicData->GetListByState($userId,$siteId,$state,$pageBegin,$pageSize,$allCount);
            }

            if(count($arrUserOrderList) != 0){
                $styleNumber = 1;
                $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html","common");
                $isJs = FALSE;
                $navUrl = "/default.php?mod=user_order&a=list&p={0}&ps=$pageSize&state=".$state;
                $jsFunctionName = "";
                $jsParamList = "";
                $pagerButton = Pager::ShowPageButton($pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex, $styleNumber, $isJs, $jsFunctionName, $jsParamList);
                Template::ReplaceList($templateContent,$arrUserOrderList,$tagId);
                $templateContent = str_ireplace("{pagerButton}", $pagerButton, $templateContent);
            }else{
                $templateContent = Template::ReplaceCustomTag($templateContent,$tagId,Language::Load("user_order",7));
                $templateContent = str_ireplace("{pagerButton}", "", $templateContent);
            }


            $userOrderCountOfNonPayment = $userOrderPublicData->GetUserOrderCountByState($userId,$siteId,UserOrderData::STATE_NON_PAYMENT);
            $userOrderCountOfPayment = $userOrderPublicData->GetUserOrderCountByState($userId,$siteId,UserOrderData::STATE_PAYMENT);
            $userOrderCountOfSent = $userOrderPublicData->GetUserOrderCountByState($userId,$siteId,UserOrderData::STATE_SENT);
            $userOrderCountOfDone = $userOrderPublicData->GetUserOrderCountByState($userId,$siteId,UserOrderData::STATE_DONE);
            $userOrderCountOfUnComment = $userOrderPublicData->GetUserOrderCountByState($userId,$siteId,UserOrderData::STATE_UNCOMMENT);
            $userOrderCountOfPaymentAfterReceive  = $userOrderPublicData->GetUserOrderCountByState($userId,$siteId,UserOrderData::STATE_PAYMENT_AFTER_RECEIVE);

            $templateContent = str_ireplace("{UserOrderCountOfNonPayment}", $userOrderCountOfNonPayment, $templateContent);
            $templateContent = str_ireplace("{UserOrderCountOfPayment}", $userOrderCountOfPayment, $templateContent);
            $templateContent = str_ireplace("{UserOrderCountOfSent}", $userOrderCountOfSent, $templateContent);
            $templateContent = str_ireplace("{UserOrderCountOfDone}", $userOrderCountOfDone, $templateContent);
            $templateContent = str_ireplace("{UserOrderCountOfUnComment}", $userOrderCountOfUnComment, $templateContent);
            $templateContent = str_ireplace("{UserOrderCountOfPaymentAfterReceive}", $userOrderCountOfPaymentAfterReceive, $templateContent);
            parent::ReplaceEnd($templateContent);
            return $templateContent;
        }else{
            return "";
        }
    }

    private function GenDetail(){
        $userId =Control::GetUserId();
        if($userId > 0){
            $siteId = parent::GetSiteIdByDomain();
            $userOrderId = Control::GetRequest("user_order_id",0);
            if($userOrderId > 0 && $siteId > 0){

                /////权限验证//////////////////////
                /////会员id和订单所属会员id要一致
                //////////////////////////////////





                //$templateFileUrl = "user/user_order_detail.html";
                //$templateName = "default";
                //$templatePath = "front_template";
                //$templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);
                $templateContent = parent::GetDynamicTemplateContent("user_order_detail");
                parent::ReplaceFirst($templateContent);
                parent::ReplaceSiteInfo($siteId, $templateContent);

                $userOrderPublicData = new UserOrderPublicData();
                $userOrderProductPublicData = new UserOrderProductPublicData();

                $arrUserOrderOne = $userOrderPublicData->GetOne($userOrderId,$userId,$siteId);
                $arrUserOrderProductList = $userOrderProductPublicData->GetList($userOrderId,$userId,$siteId);

                $tagUserOrderProductListId = "user_order_product_list";
                if(count($arrUserOrderProductList) > 0){
                    Template::ReplaceList($templateContent,$arrUserOrderProductList,$tagUserOrderProductListId);
                }else{
                    Template::RemoveCustomTag($templateContent);
                }

                if(count($arrUserOrderOne) > 0){
                    Template::ReplaceOne($templateContent,$arrUserOrderOne);
                }
            }else{
                $templateContent = Language::Load("user_order",8);
            }
            parent::ReplaceEnd($templateContent);
            return $templateContent;
        }else{
            Control::GoUrl("/default.php?mod=user&a=login");
            return "";
        }
    }

    private function AsyncGetCountByState(){
        $userId = Control::GetUserId();
        $siteId = intval(Control::GetRequest("site_id",0));
        $state = Control::GetRequest("state",0);

        if($userId > 0 && $siteId > 0){
            return null;
        }
    }

    /**
     * 订单确认
     * @return null|string
     */
    private function GenConfirm(){
        $userId = Control::GetUserId();
        $siteId = parent::GetSiteIdByDomain();
        $strUserCarIds = Control::GetRequest("arr_user_car_id","");
        //$templateFileUrl = "user/user_order_confirm.html";
        //$templateName = "default";
        //$templatePath = "front_template";
        //$templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);
        $templateContent = parent::GetDynamicTemplateContent("user_order_confirm");
        if($strUserCarIds != "" && $userId > 0 && $siteId > 0){
            $userCarPublicData = new UserCarPublicData();

            /////权限验证//////////////////////
            /////会员id和购物车所属会员id要一致
            //////////////////////////////////




            parent::ReplaceFirst($templateContent);


            $templateContent = str_ireplace("{ArrUserCarId}", $strUserCarIds, $templateContent);

            $userReceiveInfoPublicData = new UserReceiveInfoPublicData();
            $activityProductPublicData = new ActivityProductPublicData();


            $tagIdUserReceive = "user_receive";
            $arrUserReceiveInfoList = $userReceiveInfoPublicData->GetList($userId);
            if(count($arrUserReceiveInfoList) > 0){
                Template::ReplaceList($templateContent,$arrUserReceiveInfoList,$tagIdUserReceive);
            }else{
                Template::ReplaceCustomTag($templateContent,$tagIdUserReceive,"");
            }


            $tagIdProductOrder = "product_with_product_price";
            $strUserCarIds = str_ireplace("_",",",$strUserCarIds);
            $arrProductOrderList = $userCarPublicData->GetListForConfirmUserOrder(
                $strUserCarIds,
                $userId
            );
            $totalProductPrice = 0;//订单总价

            $productPublicData = new ProductPublicData();

            $maxProductSendPrice = 0;
            $maxProductSendPriceAdd = 0;
            $sumProductSendPrice = 0;
            $sumProductSendPriceAdd = 0;

            for($i=0;$i<count($arrProductOrderList);$i++){
                $activityProductId = intval($arrProductOrderList[$i]["ActivityProductId"]);
                $productPriceValue = floatval($arrProductOrderList[$i]["ProductPriceValue"]);
                $buyPrice = floatval($arrProductOrderList[$i]["BuyPrice"]);
                $buyCount = intval($arrProductOrderList[$i]["BuyCount"]);
                $productId = intval($arrProductOrderList[$i]["ProductId"]);
                if($activityProductId>0){
                    $discount = $activityProductPublicData->GetDiscount($activityProductId, true);
                    $salePrice = $discount * $productPriceValue;
                }else{
                    $salePrice = $productPriceValue;
                }
                $arrProductOrderList[$i]["SalePrice"] = $salePrice;
                $arrProductOrderList[$i]["BuyPrice"] = $buyCount*$salePrice;
                $totalProductPrice = $buyPrice+$totalProductPrice;

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
            if(count($arrProductOrderList) > 0){
                Template::ReplaceList($templateContent,$arrProductOrderList,$tagIdProductOrder);
            }else{
                Template::ReplaceCustomTag($templateContent,$tagIdProductOrder,"");
            }

            //计算发货费用
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

            $totalPrice = floatval($sendPrice) + floatval($totalProductPrice);

            $replace_arr = array(
                "{SendPrice}" => sprintf("%1\$.3f",$sendPrice),
                "{TotalProductPrice}" => sprintf("%1\$.3f",$totalProductPrice),
                "{TotalPrice}" => sprintf("%1\$.3f",$totalPrice)
            );

            $templateContent = strtr($templateContent,$replace_arr);

            parent::ReplaceEnd($templateContent);
            return $templateContent;
        }else{
            Control::GoUrl("/default.php?mod=user&a=login");
            return null;
        }
    }

    /**
     * 订单提交
     */
    private function GenSubmit(){


        //验证数据
        $userOrderProductArray = Control::PostRequest("s_UserOrderProductArray", "", false);
        $siteId = parent::GetSiteIdByDomain();
        $userId = Control::GetUserId();
        $userIdDes = Des::Encrypt($userId, UserOrderData::USER_ORDER_DES_KEY);
        $cookieId = "";
        $allPrice = floatval(Control::PostRequest("s_AllPrice", ""));
        $allPriceDes = Des::Encrypt($allPrice, UserOrderData::USER_ORDER_DES_KEY);
        $sendPrice = floatval(Control::PostRequest("s_SendPrice", ""));
        $userReceiveInfoId = intval(Control::PostRequest("s_UserReceiveInfoId",""));
        $sendTime = "";
        $autoSendMessage = "";
        $createDate = strval(date('Y-m-d H:i:s', time()));
        $createDateDes = Des::Encrypt($createDate, UserOrderData::USER_ORDER_DES_KEY);
        $arrUserCarId = Control::PostRequest("s_ArrUserCarId", "");

        if(
            $userReceiveInfoId>0 &&
            strlen($userOrderProductArray)>0
            && $siteId>0
            && $userId>0
        ){

            $userOrderPublicData = new UserOrderPublicData();

            $userOrderName = "";
            $userOrderNumber = UserOrderData::GenUserOrderNumber();
            $userOrderNumberDes = Des::Encrypt($userOrderNumber, UserOrderData::USER_ORDER_DES_KEY);

            $userOrderId = $userOrderPublicData->Create(
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
                $userCarPublicData = new UserCarPublicData();



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
                    $activityProductId = intval($arrProduct[$i]["ActivityProductId"]);
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

                            //删除对应购物车中的产品

                            $userCarPublicData->DeleteByProductAndProductPrice(
                                $userId,
                                $siteId,
                                $productId,
                                $productPriceId,
                                $activityProductId
                            );


                            //重计订单总价

                            $userOrderPublicData->ReCountAllPrice($userOrderId);


                        }
                    }



                }

                //Control::GoUrl("/default.php?mod=user_order&a=pay&no=$userOrderNumber");

                //删除购物车，
                //2015.2.11 取消 by zc 已经在单项订单产品中处理
                //$userCarPublicData = new UserCarPublicData();
                //$arrUserCarId = str_ireplace("_",",",$arrUserCarId);
                //$userCarPublicData->BatchDelete($arrUserCarId, $userId);


                //支付选择页面
                Control::GoUrl("/default.php?mod=user_order&a=pay&user_order_id=$userOrderId");



                //支付方式选择
                //默认支付宝


            }else{
                //编号出错
            }
        }else{
            //出错，返回
        }
    }

    /**
     * 支付
     */
    private function GenPay(){


        $userId = Control::GetUserId();
        $siteId = parent::GetSiteIdByDomain();
        $userOrderId = Control::GetRequest("user_order_id",0);
        $templateContent = "";

        if($userOrderId > 0 && $userId > 0 && $siteId > 0){

            //$templateFileUrl = "user/user_order_pay.html";
            //$templateName = "default";
            //$templatePath = "front_template";
            //$templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);
            $templateContent = parent::GetDynamicTemplateContent("user_order_pay");

            /////权限验证//////////////////////
            /////会员id和订单所属会员id要一致
            //////////////////////////////////
            $userOrderPublicData = new UserOrderPublicData();
            $userOrderUserId = $userOrderPublicData->GetUserId($userOrderId, true);

            if($userId != $userOrderUserId){
                return "";
            }

            parent::ReplaceFirst($templateContent);

            $templateContent = str_ireplace("{UserOrderId}", $userOrderId, $templateContent);

            parent::ReplaceEnd($templateContent);
        }


        return $templateContent;



    }

    /**
     * 提交支付
     */
    private function GenSubmitPay(){

        $userId = Control::GetUserId();
        $siteId = parent::GetSiteIdByDomain();
        $userOrderId = Control::GetRequest("user_order_id",0);
        $templateContent = "";
        if($userOrderId > 0 && $userId > 0 && $siteId > 0){

            //$templateFileUrl = "user/user_order_submit_pay.html";
            //$templateName = "default";
            //$templatePath = "front_template";
            //$templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);
            $templateContent = parent::GetDynamicTemplateContent("user_order_submit_pay");
            parent::ReplaceFirst($templateContent);

            /////权限验证//////////////////////
            /////会员id和订单所属会员id要一致
            //////////////////////////////////
            $userOrderPublicData = new UserOrderPublicData();
            $userOrderUserId = $userOrderPublicData->GetUserId($userOrderId, true);

            if($userId != $userOrderUserId){
                return "";
            }

            $payMethod = Control::GetRequest("pay_method", 0);

            if($payMethod == 1){ //支付宝

                $userOrderName = $userOrderPublicData->GetUserOrderName($userOrderId, true);
                $userOrderNumber = $userOrderPublicData->GetUserOrderNumber($userOrderId, true);
                $allPrice = $userOrderPublicData->GetAllPrice($userOrderId);

                if(strlen($userOrderName)<=0){
                    $userOrderName = Control::GetUserId().'-'.strval(date('Ymd', time()));
                }

                $alipay = new Alipay();
                $userOrderIntro = "";
                $userOrderProductUrl = "http://".$_SERVER['HTTP_HOST']."/default.php?mod=user_order&a=detail&user_order_id=$userOrderId";
                $alipayConfig = $alipay->Init($siteId);
                $result = $alipay->Submit(
                    $siteId,
                    $alipayConfig,
                    $userOrderNumber,
                    $userOrderName,
                    $allPrice,
                    $userOrderIntro,
                    $userOrderProductUrl
                );
                return $result;

            }elseif ($payMethod == 2){ //货到付款

                //直接修改订单状态为货到付款
                $state = UserOrderData::STATE_PAYMENT_AFTER_RECEIVE;

                $userOrderPublicData->ModifyState($userOrderId,$userId,$state);



            }

            parent::ReplaceEnd($templateContent);
        }
        return $templateContent;

    }

    /**
     *
     */
    private function AlipayNotify(){
        //$result = "";
        $alipay = new Alipay();
        $siteId = parent::GetSiteIdByDomain();
        if($siteId>0){
            $alipayConfig = $alipay->Init($siteId);
            $alipay->NotifyUrl(
                $alipayConfig
            );
        }
        //return $result;
    }

    /**
     *
     */
    private function AlipayReturn(){
        $result = "";
        $alipay = new Alipay();
        $siteId = parent::GetSiteIdByDomain();
        if($siteId>0){
            $alipayConfig = $alipay->Init($siteId);
            $result = $alipay->ReturnUrl(
                $alipayConfig
            );
        }
        return $result;

    }

    private function AsyncModifyState(){
        $userId = Control::GetUserId();
        $userOrderId = Control::GetRequest("user_order_id",0);
        $newState = Control::GetRequest("state",-1);

        $result = -1;
        if($userId > 0 && $userOrderId > 0 && $newState > 0){
            $siteId = parent::GetSiteIdByDomain();
            $userOrderPublicData = new UserOrderPublicData();
            if($newState == UserOrderData::STATE_APPLY_REFUND){
                $oldState = $userOrderPublicData->GetState($userOrderId,false);

                if(
                    $oldState == UserOrderData::STATE_PAYMENT  ||
                    $oldState == UserOrderData::STATE_SENT  ||
                    $oldState == UserOrderData::STATE_DONE  ||
                    $oldState == UserOrderData::STATE_COMMENT_FINISHED  ||
                    $oldState == UserOrderData::STATE_UNCOMMENT
                ){

                }
                else{
                    return Control::GetRequest("jsonpcallback","").'({"result":'.self::ERROR_STATE.'})';
                }
            }
            $result = $userOrderPublicData->ModifyState($userOrderId,$userId,$newState);

            if ($result >0){

                if ($newState == UserOrderData::STATE_CLOSE || $newState == UserOrderData::STATE_REFUND_FINISHED){

                    /**
                     * @TODO 关闭交易或退款成功时，要恢复库存
                     */
                    $userOrderProductPublicData = new UserOrderProductPublicData();
                    $arrUserOrderProductList = $userOrderProductPublicData->GetList($userOrderId,$userId,$siteId);
                    $productPricePublicData = new ProductPricePublicData();
                    for($i=0;$i<count($arrUserOrderProductList);$i++){
                        $productPriceId = $arrUserOrderProductList[$i]["ProductPriceId"];
                        $saleCount = $arrUserOrderProductList[$i]["SaleCount"];
                        $productPricePublicData->AddProductCount($productPriceId,$saleCount);
                    }


                }


            }


        }

        return Control::GetRequest("jsonpcallback","").'({"result":'.$result.'})';
    }
}