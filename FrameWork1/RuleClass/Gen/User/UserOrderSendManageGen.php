<?php
/**
 * 后台管理 会员订单发货信息 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_User
 * @Author yin
 */
class UserOrderSendManageGen extends BaseManageGen implements IBaseManageGen{
    public function Gen(){
        $result = "";
        $method = Control::GetRequest("m","");
        switch($method){
            case "list":
                $result = self::GenList();
                break;
            case "async_modify":
                $result = self::AsyncModify();
                break;
            case "async_create":
                $result = self::AsyncCreate();
                break;
            case "async_remove":
                $result = self::AsyncRemove();
                break;
        }
        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    private function GenList(){
        $siteId = Control::GetRequest("site_id",0);
        $userOrderId = Control::GetRequest("user_order_id",0);

        $templateContent = "";
        if($siteId > 0 && $userOrderId > 0){
            $templateContent = Template::Load("user/user_order_send_list.html","common");
            parent::ReplaceFirst($templateContent);
            $userOrderSendManageData = new UserOrderSendManageData();
            $arrUserOrderSendList = $userOrderSendManageData->GetList($userOrderId,$siteId);

            $tagId = "user_order_send_list";
            if(count($arrUserOrderSendList) > 0){
                Template::ReplaceList($templateContent,$arrUserOrderSendList,$tagId);
            }else{
                Template::ReplaceCustomTag($templateContent,$tagId,Language::Load("user_order_send",1));
            }
        }

        $templateContent = str_ireplace("{UserOrderId}",$userOrderId,$templateContent);

        parent::ReplaceEnd($templateContent);
        return $templateContent;

    }

    private function AsyncModify(){
        $siteId = Control::GetRequest("site_id",0);
        $userOrderId = Control::GetRequest("user_order_id",0);

        if($siteId > 0 && $userOrderId > 0){

        }
        return "";
    }

    private function AsyncCreate(){
        $siteId = Control::GetRequest("site_id",0);
        $userOrderId = Control::GetRequest("user_order_id",0);

        if($siteId > 0 && $userOrderId > 0){}

        return Control::GetRequest("jsonpcallback","")."";
    }

    private function AsyncRemove(){
        $siteId = Control::GetRequest("site_id",0);
        $userOrderId = Control::GetRequest("user_order_id",0);

        return Control::GetRequest("jsonpcallback","")."";
    }
}