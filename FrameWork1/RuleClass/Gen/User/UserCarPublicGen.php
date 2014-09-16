<?php
/**
 * 前台管理 会员购物车 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_User
 * Time: 下午12:09
 */
class UserCarPublicGen extends BasePublicGen implements IBasePublicGen{

    //修改购买数量成功
    const MODIFY_BuyCount_SUCCESS = 1;
    //修改购买数量失败
    const MODIFY_BuyCount_FAIL = -1;
    //删除购物车产品成功
    const DELETE_SUCCESS = 1;
    //删除购物车产品失败
    const DELETE_FAIL = -1;
    /**
     * 引导方法
     */
    public function GenPublic(){
        $method = Control::GetRequest("a",0);
        $result = "";
        switch($method){
            case "list":
                $result = self::GenList();
                break;
            case "async_modify_buy_count":
                $result = self::AsyncModifyBuyCount();
                break;
            case "async_remove_bin":
                $result = self::AsyncRemoveBin();
                break;
        }
        return $result;
    }

    private function GenCreate(){

    }

    private function GenList(){
        $templateFileUrl = "user/user_car.html";
        $templateName = "default";
        $templatePath = "front_template";
        Control::SetUserCookie(1,"test");
        $templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);
        $userId = Control::GetUserId();
        if($userId > 0){
            $userCarPublicData = new UserCarPublicData();
            $arrUserCarProductList = $userCarPublicData->GetList($userId);
            $tagId = "user_car";

            Template::ReplaceList($templateContent,$arrUserCarProductList,$tagId);
        }else{

        }
        return $templateContent;
    }

    private function AsyncRemoveBin(){
        $userCarId = intval(Control::GetRequest("user_car_id",0));
        $userId = intval(Control::GetUserId());

        if($userCarId > 0 && $userId > 0){
            $userCarPublicData = new UserCarPublicData();
            $result = $userCarPublicData->Delete($userCarId,$userId);
            if($result > 0){
                return Control::GetRequest("jsonpcallback","").'({"result":'.self::DELETE_SUCCESS.'})';
            }else{
                return Control::GetRequest("jsonpcallback","").'({"result":'.self::DELETE_FAIL.'})';
            }
        }else{
            return Control::GetRequest("jsonpcallback","").'({"result":'.self::DELETE_FAIL.'})';
        }
    }

    private function AsyncModifyBuyCount(){
        Control::SetUserCookie(1,"test");
        $userCarId = intval(Control::GetRequest("user_car_id",0));
        $buyCount = intval(Control::GetRequest("buy_count",0));
        $userId = intval(Control::GetUserId());
        if($userCarId > 0 && $userId > 0 && $buyCount > 0){

            $userCarPublicData = new UserCarPublicData();
            $result = $userCarPublicData->ModifyBuyCount($userCarId,$buyCount,$userId);
            if($result > 0){
                return Control::GetRequest("jsonpcallback","").'({"result":'.self::MODIFY_BuyCount_SUCCESS.'})';
            }else{
                return Control::GetRequest("jsonpcallback","").'({"result":'.self::MODIFY_BuyCount_FAIL.'})';
            }
        }else{
            return Control::GetRequest("jsonpcallback","").'({"result":'.self::MODIFY_BuyCount_FAIL.'})';
        }
    }
}