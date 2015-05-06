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
            case "async_create":
                $result = self::AsyncCreate();
                break;
            case "async_modify":
                $result = self::AsyncModify();
                break;
            case "async_get_one":
                $result = self::AsyncGetOne();
                break;
            case "list":
                $result = self::GenList();
                break;
        }
        return $result;
    }

    private function AsyncCreate(){
        $userId = Control::GetUserId();
        $address = Control::PostRequest("address",0);
        $postcode = Control::PostRequest("postcode",0);
        $receive_person_name = Control::PostRequest("receive_person_name","");
        $homeTel = Control::PostRequest("tel","");
        $mobile = Control::PostRequest("mobile","");
        $city = Control::PostRequest("city","");
        $district = Control::PostRequest("district","");
        if($userId > 0){
            if(!empty($address) && !empty($postcode) && !empty($receive_person_name) && (!empty($homeTel) || !empty($mobile))){
                $userReceiveInfoData = new UserReceiveInfoPublicData();
                $result = $userReceiveInfoData->Create(
                    $userId,
                    $address,
                    $postcode,
                    $receive_person_name,
                    $homeTel,
                    $mobile,
                    $city,
                    $district
                );

                if($result > 0){
                    return Control::GetRequest("jsonpcallback","").'({"result":'.$result.'})';
                }else{
                    return Control::GetRequest("jsonpcallback","").'({"result":-1})';
                }
            }else{
                return Control::GetRequest("jsonpcallback","").'({"result":-1})';
            }
        }else{
            return "";
        }
    }

    private function AsyncModify(){
        $userId = Control::GetUserId();
        $userReceiveInfoId = intval(Control::GetRequest("user_receive_info_id",0));
        $address = Control::PostRequest("address",0);
        $postcode = Control::PostRequest("postcode",0);
        $receive_person_name = Control::PostRequest("receive_person_name","");
        $homeTel = Control::PostRequest("tel","");
        $mobile = Control::PostRequest("mobile","");
        $city = Control::PostRequest("city","");
        $district = Control::PostRequest("district","");
        if($userId > 0){
            if($userReceiveInfoId > 0 && !empty($address) && !empty($postcode) && !empty($receive_person_name) && (!empty($homeTel) || !empty($mobile))){
                $userReceiveInfoData = new UserReceiveInfoPublicData();
                $result = $userReceiveInfoData->Modify(
                    $userId,
                    $userReceiveInfoId,
                    $address,
                    $postcode,
                    $receive_person_name,
                    $homeTel,
                    $mobile,
                    $city,
                    $district
                );

                if($result > 0){
                    return Control::GetRequest("jsonpcallback","").'({"result":'.$result.'})';
                }else{
                    return Control::GetRequest("jsonpcallback","").'({"result":-2})';
                }
            }else{
                return Control::GetRequest("jsonpcallback","").'({"result":-2})';
            }
        }else{
            return "";
        }
    }

    private function GenList(){
        $userId = Control::GetUserId();
        if($userId > 0){
            $siteId = parent::GetSiteIdByDomain();
//            $templateFileUrl = "user/user_receive_info_list.html";
//            $templateName = "default";
//            $templatePath = "front_template";
//            $templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);
            $templateContent = parent::GetDynamicTemplateContent("user_receive_info_list");
            parent::ReplaceFirst($templateContent);
            parent::ReplaceSiteInfo($siteId, $templateContent);

            $tagId = "user_receive_info_list";
            $userReceiveInfoPublicData = new UserReceiveInfoPublicData();
            $arrUserReceiveInfoList = $userReceiveInfoPublicData->GetList($userId);

            if(count($arrUserReceiveInfoList) > 0){
                Template::ReplaceList($templateContent,$arrUserReceiveInfoList,$tagId);
            }else{
                $templateContent = Template::ReplaceCustomTag($templateContent,$tagId,Language::Load("user_receive_info",7));
            }

            parent::ReplaceEnd($templateContent);
            return $templateContent;
        }else{
            Control::GoUrl("/default.php?mod=user&a=login");
            return null;
        }
    }

    private function AsyncGetOne(){
        $userReceiveInfoId = Control::GetRequest("user_receive_info_id",0);
        $userId = Control::GetUserId();
        if($userReceiveInfoId > 0 && $userId > 0){
            $userReceiveInfoPublicData = new UserReceiveInfoPublicData();
            $arrUserReceiveInfoOne = $userReceiveInfoPublicData->GetOne($userReceiveInfoId,$userId);
            return Control::GetRequest("jsonpcallback","")."(".json_encode($arrUserReceiveInfoOne).")";
        }else{
            if($userId < 0){
                return Control::GetRequest("jsonpcallback","").'({"result":0})';
            }else{
                return "";
            }
        }
    }
}