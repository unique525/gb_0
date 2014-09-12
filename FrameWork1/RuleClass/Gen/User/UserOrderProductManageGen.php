<?php
/**
 * 后台管理 会员订单产品 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_User
 * @Author yin
 */
class UserOrderProductManageGen extends BaseManageGen implements IBaseManageGen{

    const ERROR_FAIL= -1;
    /**
     * 引导方法
     * @return mixed|string 执行结果
     */
    public function Gen(){
        $result = "";
        $method = Control::GetRequest("m", "");
        switch($method){
            case "modify":
                $result = self::GenModify();
                break;
            case "async_modify_state":
                $result = self::AsyncModifyState();
                break;
        }
        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }


    /**
     * 会员订单中的产品修改
     * @return mixed|string
     */
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

            if(count($arrUserOrderProductOne) > 0){
                Template::ReplaceOne($templateContent,$arrUserOrderProductOne);
            }else{
                parent::ReplaceWhenCreate($templateContent,$userOrderProductManageData->GetFields());
            }
        }

        parent::ReplaceEnd($templateContent);
        $templateContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $templateContent);
        return $templateContent;
    }

    /**
     * AJAX 修改订单中产品的状态 0:正常，100:删除
     * @return mixed|string
     */
    private function AsyncModifyState(){
        $userOrderProductId = intval(Control::GetRequest("user_order_product_id",0));
        $state = Control::GetRequest("state",0);
        $userOrderId = Control::GetRequest("user_order_id",0);

        if($userOrderProductId > 0 && $state >= 0 && $userOrderId > 0){
            $userOrderProductManageData = new UserOrderProductManageData();
            $userOrderManageData = new UserOrderManageData();
            $result = $userOrderProductManageData->ModifyState($userOrderId,$userOrderProductId,$state);
            $allPrice = floatval($userOrderManageData->GetAllPrice($userOrderId));

            $operateContent = 'Modify UserOrderProduct,GET FORM:'.implode('|',$_GET).';\r\nResult::'.$result;
            self::CreateManageUserLog($operateContent);
            if($result > 0){
                return Control::GetRequest("jsonpcallback","").'({"result":"'.$result.'","all_price":"'.$allPrice.'"})';
            }else{
                return Control::GetRequest("jsonpcallback","").'({"result":'.self::ERROR_FAIL.'})';
            }
        }else{
            return Control::GetRequest("jsonpcallback","").'({"result":'.self::ERROR_FAIL.'})';
        }
    }
}