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
} 