<?php
/**
 * 后台管理 会员订单支付信息 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_User
 * @Author yin
 */
class UserOrderPayManageGen extends BaseManageGen implements IBaseManageGen{
    public function Gen(){
        $result = "";
        $method = Control::GetRequest("m", "");
        switch($method){
            case "list":
                $result = self::GenList();
                break;
            case "async_confirm":
                $result = self::AsyncConfirm();
                break;
        }
        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    public function AsyncConfirm(){
        $userOrderPayId = Control::GetRequest("user_order_pay_id",0);
        if($userOrderPayId > 0){
            $confirmWay = Control::GetRequest("confirm_way","");
            $confirmPrice = Control::GetRequest("confirm_price",0);
            $confirmDate = date("Y-m-d");
            $manageUserId = Control::GetManageUserId();
            $manageUserName = Control::GetManageUserName();

            if($confirmWay == "" || $manageUserId == 0){
                return Control::GetRequest("jsonpcallback","").'({"result":-4})';
            }

            $userOrderPayManageData = new UserOrderPayManageData();
            $result = $userOrderPayManageData->ModifyConfirmPay($userOrderPayId,$confirmWay,$confirmPrice,$confirmDate,$manageUserId);

            $operateContent = 'Modify UserOrderPay,Get FORM:'.implode('|',$_GET).';\r\nResult::'.$result;
            self::CreateManageUserLog($operateContent);

            if($result > 0){
                return Control::GetRequest("jsonpcallback","").'({"result":"'.$result.'","confirm_way":"'.$confirmWay.'","confirm_price":"'
                .$confirmPrice.'","confirm_date":"'.$confirmDate.'","manage_username":"'.$manageUserName.'"})';
            }else{
                return Control::GetRequest("jsonpcallback","").'({"result":-2})';
            }
        }else{
            return Control::GetRequest("jsonpcallback","").'({"result":-3})';
        }
    }

    private function GenList(){
        $userOrderId = intval(Control::GetRequest("user_order_id",0));
        if($userOrderId > 0){
            $templateContent = Template::Load("user/user_order_pay_list.html","common");
            parent::ReplaceFirst($templateContent);

            $userOrderPayManageData = new UserOrderPayManageData();

            $arrUserOrderPayList = $userOrderPayManageData->GetList($userOrderId);
            $tagId = "user_order_pay_list";
            if(!empty($arrUserOrderPayList)){
                Template::ReplaceList($templateContent,$arrUserOrderPayList,$tagId);
            }else{
                Template::RemoveCustomTag($templateContent,$tagId);
            }

            parent::ReplaceEnd($templateContent);
            return $templateContent;
        }else{
            return null;
        }
    }
}