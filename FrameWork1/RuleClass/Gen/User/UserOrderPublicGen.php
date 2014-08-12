<?php
/**
 * 前台 会员订单 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_User
 * Time: 下午12:09
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
}