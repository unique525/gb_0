<?php
/**
 * 前台管理 会员订单发货信息 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_User
 * @author yin
 */
class UserOrderSendPublicGen extends BasePublicGen implements IBasePublicGen{
    /**
     * @return string
     */
    public function GenPublic(){
        $method = Control::GetRequest("a","");
        $result = "";
        switch($method){
            case "list":
                $result = self::GenList();
                break;

        }
        return $result;
    }

    private function GenList(){
        $userOrderNumber = Control::GetRequest("user_order_number","");
        $userOrderId = Control::GetRequest("user_order_id",0);

        $templateContent = "";
        if($userOrderId > 0 && !empty($userOrderNumber)){
            //$templateFileUrl = "user/user_order_send_list.html";
            //$templateName = "default";
            //$templatePath = "front_template";
            //$templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);
            $templateContent = parent::GetDynamicTemplateContent("user_register_for_forum");
            parent::ReplaceFirst($templateContent);
            $userOrderSendPublicData = new UserOrderSendPublicData();
            $arrUserOrderSendList = $userOrderSendPublicData->GetList($userOrderId);

            $tagId = "user_order_send_list";
            if(count($arrUserOrderSendList) > 0){
                Template::ReplaceList($templateContent,$arrUserOrderSendList,$tagId);
            }else{
                Template::ReplaceCustomTag($templateContent,$tagId,Language::Load("user_order_send",1));
            }
        }

        $templateContent = str_ireplace("{UserOrderId}",$userOrderId,$templateContent);
        $templateContent = str_ireplace("{UserOrderNumber}",$userOrderNumber,$templateContent);

        parent::ReplaceEnd($templateContent);
        return $templateContent;

    }
}