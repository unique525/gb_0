<?php
/**
 * 客户端 客户端应用程序 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_ClientApp
 * @author zhangchi
 */
class ClientAppClientGen extends BaseClientGen implements IBaseClientGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenClient(){
        $result = "";
        $function = Control::GetRequest("f", "");

        switch ($function) {
            /**
             * APP 启动时的检查操作
             */
            case "start_check":
                $result = self::GenStartCheck();
                break;

        }
        $result = str_ireplace("{function}", $function, $result);
        return $result;
    }

    /**
     * APP 启动时的检查操作
     * @return string
     */
    private function GenStartCheck(){
        $result = "[{}]";

        $siteId = intval(Control::GetRequest("site_id", 0));
        $clientAppType = intval(Control::GetRequest("client_app_type", 0));

        if($siteId>0 && $clientAppType>0){
            /** 返回最新版本信息 **/
            $clientAppClientData = new ClientAppClientData();

            $arrOne = $clientAppClientData->GetNewVersion(
                $siteId,
                $clientAppType,
                false
            );

            $resultCode = 1;

            $result = Format::FixJsonEncode($arrOne);

        }else{
            $resultCode = -6; //参数错误
        }

        return '{"result_code":"' . $resultCode . '","client_app_start_check":' . $result . '}';
    }
} 