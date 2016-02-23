<?php

/**
 *
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Client
 * @author zhangchi
 */
class ClientDirectUrlPublicGen extends BasePublicGen implements IBasePublicGen {

    /**
     *
     * @return string
     */
    public function GenPublic() {
        $result = "";

        $action = Control::GetRequest("a", "");
        switch ($action) {
            case "direct":
                self::GenDirect();
                break;
        }

        $result = str_ireplace("{action}", $action, $result);

        return $result;
    }

    private function GenDirect(){

        $clientDirectUrlId = Control::GetRequest("client_direct_url_id", 0);

        if($clientDirectUrlId > 0){
            $clientDirectUrlPublicData = new ClientDirectUrlPublicData();
            $clientDirectUrl = $clientDirectUrlPublicData->GetDirectUrl($clientDirectUrlId);
            if(strlen($clientDirectUrl) > 0){
                $result =  $clientDirectUrlPublicData->AddHit($clientDirectUrlId);
                if($result > 0){
                    Control::GoUrl($clientDirectUrl);
                    return;
                }
            }
        }
    }
}