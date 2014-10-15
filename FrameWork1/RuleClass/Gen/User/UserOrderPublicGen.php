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
            case "async_get_count_by_state":
                $result = self::AsyncGetCountByState();
                break;
            case "confirm":
                $result = self::GenConfirm();
                break;
            case "submit":
                $result = self::GenSubmit();
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

    private function AsyncGetCountByState(){
        $userId = Control::GetUserId();
        $siteId = intval(Control::GetRequest("site_id",0));
        $state = Control::GetRequest("state",0);

        if($userId > 0 && $siteId > 0){
            return null;
        }
    }

    private function GenConfirm(){
        $userId = Control::GetUserId();
        $siteId = intval(Control::GetRequest("site_id",0));
        $strUserCarIds = Control::GetRequest("arr_user_car_id","");
        $templateFileUrl = "user/user_order_confirm.html";
        $templateName = "default";
        $templatePath = "front_template";
        $templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);

        if($strUserCarIds != "" && $userId > 0 && $siteId > 0){
            parent::ReplaceFirst($templateContent);

            $userReceiveInfoPublicData = new UserReceiveInfoPublicData();
            $activityProductPublicData = new ActivityProductPublicData();
            $userCarPublicData = new UserCarPublicData();

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
            for($i=0;$i<count($arrProductOrderList);$i++){
                $activityProductId = intval($arrProductOrderList[$i]["ActivityProductId"]);
                $productPriceValue = floatval($arrProductOrderList[$i]["ProductPriceValue"]);
                if($activityProductId>0){
                    $discount = $activityProductPublicData->GetDiscount($activityProductId);
                    $salePrice = $discount * $productPriceValue;
                }else{
                    $salePrice = $productPriceValue;
                }
                $arrProductOrderList[$i]["SalePrice"] = $salePrice;
                $arrProductOrderList[$i]["BuyPrice"] = $arrProductOrderList[$i]["BuyCount"]*$salePrice;
                $totalProductPrice = $arrProductOrderList[$i]["BuyPrice"]+$totalProductPrice;
            }
            if(count($arrProductOrderList) > 0){
                Template::ReplaceList($templateContent,$arrProductOrderList,$tagIdProductOrder);
            }else{
                Template::ReplaceCustomTag($templateContent,$tagIdProductOrder,"");
            }

            $sendPrice = $userCarPublicData->GetSendPriceForConfirmUserOrder($strUserCarIds,$userId);
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
}