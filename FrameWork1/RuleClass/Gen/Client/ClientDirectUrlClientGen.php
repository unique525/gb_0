<?php

/**
 *
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Client
 * @author zhangchi
 */
class ClientDirectUrlClientGen extends BaseClientGen implements IBaseClientGen {

    /**
     *
     * @return string
     */
    public function GenClient(){
        $result = "";
        $function = Control::GetRequest("f", "");

        switch ($function) {
            /**

             */
            case "direct":
                self::GenDirect();
                break;

        }
        $result = str_ireplace("{function}", $function, $result);
        return $result;
    }

    private function GenDirect(){

        $clientDirectUrlId = Control::GetRequest("client_direct_url_id", 0);

        if($clientDirectUrlId > 0){
            $ClientDirectUrl = "";
            $ClientDirectUrlClientData = new ClientDirectUrlClientData();
            $ClientDirectUrl = $ClientDirectUrlClientData->GetDirectUrl($clientDirectUrlId);
            if(strlen($ClientDirectUrl) > 0){
                $result =  $ClientDirectUrlClientData->AddHit($clientDirectUrlId);
                if($result > 0){
                    Control::GoUrl($ClientDirectUrl);
                    return;
                }
            }
        }
    }

}