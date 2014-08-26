<?php
/**
 * 后台管理 会员订单产品 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_User
 * @Author yin
 */
class UserOrderProductManageGen extends BaseManageGen implements IBaseManageGen{
    public function Gen(){
        $result = "";
        $method = Control::GetRequest("m", "");
        switch($method){
            case "modify":
                $result = self::GenModify();
                break;
            case "delete":
                $result = self::GenDelete();
                break;
        }
        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    private function GenModify(){
        $templateContent = Template::Load("user/user_order_product_deal.html","common");
        $userOrderProductId = intval(Control::GetRequest("user_order_product_id",0));
        $userOrderId = intval(Control::GetRequest("user_order_id",0));
        $siteId = intval(Control::GetRequest("site_id",0));

        parent::ReplaceFirst($templateContent);
        $resultJavaScript = "";
        if($userOrderProductId > 0 && $userOrderId > 0){
            $userOrderProductManageData = new UserOrderProductManageData();

            if(!empty($_POST)){
                $httpPostData = $_POST;
                $result = $userOrderProductManageData->Modify($httpPostData,$userOrderProductId,$userOrderId);
                if($result > 0){
                    $jsCode = 'parent.location.href=parent.location.href';
                    Control::RunJavascript($jsCode);
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('user_order', 3));
                }else{
                    $resultJavaScript .= Control::GetJqueryMessage(Language::Load('user_order', 4));
                }
                $operateContent = 'Modify UserOrderProduct,POST FORM:'.implode('|',$_POST).';\r\nResult::'.$result;
                self::CreateManageUserLog($operateContent);
            }
            $arrUserOrderProductOne = $userOrderProductManageData->GetOne($userOrderProductId,$siteId);

            Template::ReplaceOne($templateContent,$arrUserOrderProductOne);
        }

        parent::ReplaceEnd($templateContent);
        $templateContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $templateContent);
        return $templateContent;
    }

    private function GenDelete(){
        $userOrderProductId = Control::GetRequest("user_order_pay_id",0);

        if($userOrderProductId > 0){

        }
    }
}