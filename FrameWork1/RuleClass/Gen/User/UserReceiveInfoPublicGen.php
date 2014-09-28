<?php
/**
 * 前台管理 会员订单 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_User
 * @author yin
 */
class UserReceiveInfoPublicGen extends BasePublicGen implements IBasePublicGen{
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
            case "modify":
                $result = self::GenModify();
                break;
        }
        return $result;
    }

    private function GenCreate(){
        $userId = Control::GetUserId();
        $address = Control::PostRequest("address",0);
        $postcode = Control::PostRequest("postcode",0);
        $receive_person_name = Control::PostRequest("receive_person_name","");
        $homeTel = Control::PostRequest("tel","");
        $mobile = Control::PostRequest("mobile","");
        if($userId > 0){
            if(!empty($address) && !empty($postcode) && !empty($receive_person_name) && (!empty($homeTel) || !empty($mobile))){
                $userReceiveInfoData = new UserReceiveInfoPublicData();
                $result = $userReceiveInfoData->Create($userId,$address,$postcode,$receive_person_name,$homeTel,$mobile);

                if($result > 0){
                    return Control::GetRequest("jsonpcallback","").'({"result":'.$result.'})';
                }else{
                    return Control::GetRequest("jsonpcallback","").'({"result":-1})';
                }
            }else{
                return Control::GetRequest("jsonpcallback","").'({"result":-1})';
            }
        }else{
            Control::GoUrl("login.html");
            return "";
        }
    }

    private function GenModify(){
        $userId = Control::GetUserId();
        $userReceiveInfoId = intval(Control::GetRequest("user_receive_info_id",0));
        $address = Control::PostRequest("address",0);
        $postcode = Control::PostRequest("postcode",0);
        $receive_person_name = Control::PostRequest("receive_person_name","");
        $homeTel = Control::PostRequest("tel","");
        $mobile = Control::PostRequest("mobile","");
        if($userId > 0){
            if($userReceiveInfoId > 0 && !empty($address) && !empty($postcode) && !empty($receive_person_name) && (!empty($homeTel) || !empty($mobile))){
                $userReceiveInfoData = new UserReceiveInfoPublicData();
                $result = $userReceiveInfoData->Modify($userId,$userReceiveInfoId,$address,$postcode,$receive_person_name,$homeTel,$mobile);

                if($result > 0){
                    return Control::GetRequest("jsonpcallback","").'({"result":'.$result.'})';
                }else{
                    return Control::GetRequest("jsonpcallback","").'({"result":-2})';
                }
            }else{
                return Control::GetRequest("jsonpcallback","").'({"result":-2})';
            }
        }else{
            Control::GoUrl("login.html");
            return "";
        }
    }
}