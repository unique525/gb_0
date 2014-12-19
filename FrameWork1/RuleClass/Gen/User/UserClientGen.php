<?php
/**
 * 客户端 会员 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_User
 * @author zhangchi
 */
class UserClientGen extends BaseClientGen implements IBaseClientGen  {
    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenClient(){
        $result = "";
        $function = Control::GetRequest("f", "");
        switch ($function) {

            case "login":
                $result = self::GenLogin();
                break;

            case "register":
                $result = self::GenRegister();
                break;

        }
        $result = str_ireplace("{function}", $function, $result);
        return $result;
    }

    private function GenLogin(){

    }
}
?>