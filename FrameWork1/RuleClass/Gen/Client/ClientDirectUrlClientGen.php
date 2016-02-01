<?php

/**
 * 客户端 客户端网址转向 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Client
 * @author zhangchi
 */
class ClientDirectUrlClientGen extends BaseClientGen implements IBaseClientGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenClient(){
        $result = "";
        $function = Control::GetRequest("f", "");

        switch ($function) {
            /**
             * 转向
             */
            case "direct":
                self::GenDirect();
                break;

        }
        $result = str_ireplace("{function}", $function, $result);
        return $result;
    }

    private function GenDirect(){


        


    }

}