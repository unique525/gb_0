<?php
/**
 * 后台管理 会员订单发货信息 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_User
 * @Author yin
 */
class UserOrderSendManageGen extends BaseManageGen implements IBaseManageGen{
    /**
     *
     */
    const FAIL = 0;

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
            $arrUserOrderSendList = $userOrderSendManageData->GetList($userOrderId);

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
        $userOrderSendId = Control::GetRequest("user_order_send_id",0);

        $result = self::FAIL;
        if($userOrderSendId > 0){
            $acceptPersonName = Control::PostRequest("accept_person_name","");
            $acceptAddress = Control::PostRequest("accept_address","");
            $acceptTel = Control::PostRequest("accept_tel","");
            $acceptTime = Control::PostRequest("accept_time","");
            $sendCompany = Control::PostRequest("send_company","");

            $userOrderSendManageData = new UserOrderSendManageData();
            $result = $userOrderSendManageData->Modify($userOrderSendId,$acceptPersonName,$acceptAddress,$acceptTel,$acceptTime,$sendCompany);
        }
        return Control::GetRequest("jsonpcallback","").'({"result":'.$result.'})';
    }

    private function AsyncCreate(){
        $siteId = Control::GetRequest("site_id",0);
        $userOrderId = Control::GetRequest("user_order_id",0);

        $result = self::FAIL;
        if($siteId > 0 && $userOrderId > 0){
            $acceptPersonName = Control::PostRequest("acceptPersonName","");
            $acceptAddress = Control::PostRequest("acceptAddress","");
            $acceptTel = Control::PostRequest("acceptTel","");
            $acceptTime = Control::PostRequest("acceptTime","");
            $sendCompany = Control::PostRequest("sendCompany","");

            $userOrderSendManageData = new UserOrderSendManageData();
            $result = $userOrderSendManageData->Create($userOrderId,$acceptPersonName,$acceptAddress,$acceptTel,$acceptTime,$sendCompany);
        }
        return Control::GetRequest("jsonpcallback","").'({"result":'.$result.'})';
    }

    private function AsyncRemove(){
        $userOrderSendId = Control::GetRequest("user_order_send_id",0);

        $result = self::FAIL;
        if($userOrderSendId > 0){
            $userOrderSendManageData = new UserOrderSendManageData();
            $result = $userOrderSendManageData->Delete($userOrderSendId);
        }

        return Control::GetRequest("jsonpcallback","").'({"result":'.$result.'})';
    }
}