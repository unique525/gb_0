<?php
/**
 * 前台管理 会员订单 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_User
 * @author yin
 */
class UserOrderPublicGen extends BasePublicGen implements IBasePublicGen{
    /**
     * @return string
     */
    public function GenPublic(){
        $method = Control::GetRequest("a","");
        $result = "";
        switch($method){
            case "create":
                $result = self::GenCreate();
                break;
            case "list":
                $result = self::GenList();
                break;
            case "async_get_order_count_by_state":
                $result = self::AsyncGetOrderCountByState();
                break;
            case "confirm_order":
                $result = self::GenConfirmOrder();
                break;
        }
        return $result;
    }

    private function GenCreate(){
        $userId = Control::GetUserId();
        return "";
    }

    private function GenList(){
        $state = Control::GetRequest("state",-1);
        $userId = Control::GetUserId();
        $siteId = intval(Control::GetRequest("site_id",0));

        if($userId > 0 && $siteId > 0){
            $templateFileUrl = "user/order_list.html";
            $templateName = "default";
            $templatePath = "front_template";
            $templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);

            $userOrderPublicData = new UserOrderPublicData();
            //$userOrderPublicData->GetList();

            return $templateContent;
        }else{
            return "";
        }
    }

    private function AsyncGetOrderCountByState(){
        $userId = Control::GetUserId();
        $siteId = intval(Control::GetRequest("site_id",0));
        $state = Control::GetRequest("state",0);

        if($userId > 0 && $siteId > 0){
            return null;
        }
    }

    private function GenConfirmOrder(){
        $userId = Control::GetUserId();
        $siteId = intval(Control::GetRequest("site_id",0));
        $arrCarIdString = Control::GetRequest("arr_user_car_id","");

        if($arrCarIdString != "" && $userId > 0 && $siteId > 0){
            $templateFileUrl = "user/order.html";
            $templateName = "default";
            $templatePath = "front_template";
            $templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);

            $userReceiveInfoPublicData = new UserReceiveInfoPublicData();
            $userCarPublicData = new UserCarPublicData();

            $tagIdUserReceive = "user_receive";
            $arrUserReceiveInfoList = $userReceiveInfoPublicData->GetList($userId);
            if(count($arrUserReceiveInfoList) > 0){
                Template::ReplaceList($templateContent,$arrUserReceiveInfoList,$tagIdUserReceive);
            }else{
                Template::ReplaceCustomTag($templateContent,$tagIdUserReceive,"");
            }


            $tagIdProductOrder = "product_order";
            $arrCarIdString = str_ireplace("_",",",$arrCarIdString);
            $arrProductOrderList = $userCarPublicData->GetListForConfirmOrder($arrCarIdString,$userId);
            if(count($arrProductOrderList) > 0){
                Template::ReplaceList($templateContent,$arrProductOrderList,$tagIdProductOrder);
            }else{
                Template::ReplaceCustomTag($templateContent,$tagIdProductOrder,"");
            }

            $sendPrice = $userCarPublicData->GetSendPriceForConfirmOrder($arrCarIdString,$userId);
            $totalProductPrice = $userCarPublicData->GetTotalProductPriceForConfirmOrder($arrCarIdString,$userId);
            $totalPrice = floatval($sendPrice) + floatval($totalProductPrice);

            $replace_arr = array(
                "{SendPrice}" => sprintf("%1\$.3f",$sendPrice),
                "{TotalProductPrice}" => sprintf("%1\$.3f",$totalProductPrice),
                "{TotalPrice}" => sprintf("%1\$.3f",$totalPrice)
            );

            $templateContent = strtr($templateContent,$replace_arr);

            return $templateContent;
        }else{
            Control::GoUrl("/login.htm");
            return null;
        }
    }
}