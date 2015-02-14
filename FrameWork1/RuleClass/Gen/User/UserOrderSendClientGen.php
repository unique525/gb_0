<?php
/**
 * 客户端 会员订单发货信息 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_User
 * @author zhangchi
 */
class UserOrderSendClientGen extends BaseClientGen implements IBaseClientGen {
    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenClient()
    {
        $result = "";
        $function = Control::GetRequest("f", "");
        switch ($function) {


            case "list":
                $result = self::GenList();
                break;


        }
        $result = str_ireplace("{function}", $function, $result);
        return $result;
    }

    private function GenList(){

        $result = "[{}]";
        $userId = parent::GetUserId();

        if ($userId <= 0) {
            $resultCode = $userId;
        } else {

            $userOrderId = intval(Control::GetRequest("user_order_id", 0));

            if ($userOrderId>0){
                $userOrderSendClientData = new UserOrderSendClientData();
                $arrUserOrderSendList = $userOrderSendClientData->GetList($userOrderId, $userId);

                if (count($arrUserOrderSendList) > 0) {
                    $resultCode = 1;

                    $result = Format::FixJsonEncode($arrUserOrderSendList);

                } else {
                    $resultCode = -1;
                }

            }else{
                $resultCode = -2;
            }
        }
        return '{"result_code":"' . $resultCode . '","user_order":{"user_order_list":' . $result . '}}';


    }

} 