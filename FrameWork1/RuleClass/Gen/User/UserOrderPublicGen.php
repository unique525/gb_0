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
        $method = Control::GetRequest("m","");
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
        return "";
    }

    private function AsyncGetOrderCountByState(){
        $userId = Control::GetUserId();
        $siteId = Control::GetRequest("site_id",0);
        $state = Control::GetRequest("state",0);

        if($userId > 0 && $siteId > 0){
            return null;
        }
    }

    private function GenConfirmOrder(){
        $arrProductList = json_decode(Control::PostRequest("arr_product",array()));
        $userId = Control::GetUserId();
        $siteId = Control::PostRequest("site_id",0);

        if(count($arrProductList) > 0 && $userId > 0 && $siteId > 0){
            $templateFileUrl = "user/user_confirm_order.html";
            $templateName = "default";
            $templatePath = "front_template";
            $templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);

            $tagId = "product_order";
            Template::ReplaceList($templateContent,$arrProductList,$tagId);

            return $templateContent;
        }
    }
}