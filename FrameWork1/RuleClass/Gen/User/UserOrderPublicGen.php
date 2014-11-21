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
            case "detail":
                $result = self::GenDetail();
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
        $userId = Control::GetUserId();
        $siteId = parent::GetSiteIdByDomain();

        if($userId > 0 && $siteId > 0){
            $templateFileUrl = "user/user_order_list.html";
            $templateName = "default";
            $templatePath = "front_template";
            $templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);
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
            }


            $userOrderCountOfNonPayment = $userOrderPublicData->GetUserOrderCountByState($userId,$siteId,UserOrderData::STATE_NON_PAYMENT);
            $userOrderCountOfPayment = $userOrderPublicData->GetUserOrderCountByState($userId,$siteId,UserOrderData::STATE_PAYMENT);
            $userOrderCountOfUnComment = $userOrderPublicData->GetUserOrderCountByState($userId,$siteId,UserOrderData::STATE_UNCOMMENT);

            $templateContent = str_ireplace("{UserOrderCountOfNonPayment}", $userOrderCountOfNonPayment, $templateContent);
            $templateContent = str_ireplace("{UserOrderCountOfPayment}", $userOrderCountOfPayment, $templateContent);
            $templateContent = str_ireplace("{UserOrderCountOfUnComment}", $userOrderCountOfUnComment, $templateContent);
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
                $templateFileUrl = "user/user_order_detail.html";
                $templateName = "default";
                $templatePath = "front_template";
                $templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);
                parent::ReplaceFirst($templateContent);
                parent::ReplaceSiteInfo($siteId, $templateContent);

                $userOrderPublicData = new UserOrderPublicData();
                $userOrderProductPublicData = new UserOrderProductPublicData();

                $arrUserOrderOne = $userOrderPublicData->GetOne($userOrderId,$siteId);
                $arrUserOrderProductList = $userOrderProductPublicData->GetList($userOrderId,$siteId);

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