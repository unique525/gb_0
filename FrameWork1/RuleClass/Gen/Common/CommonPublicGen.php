<?php

/**
 * 公开访问 通用 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Common
 * @author zhangchi
 */
class CommonPublicGen extends BasePublicGen implements IBasePublicGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenPublic() {
        $result = "";
        $action = Control::GetRequest("a", "");
        switch ($action) {
            case "gen_verify_code":
                self::GenVerifyCode();
                break;
            case "gen_gif_verify_code":
                self::GenGifVerifyCode();
                break;
            case "check_verify_code":
                $result = self::CheckVerifyCode();
                break;
        }
        return $result;
    }

    /**
     * 生成验证码
     */
    private function GenVerifyCode() {
        $sessionName = Control::GetRequest("sn", "");
        VerifyCode::Gen($sessionName);
    }

    /**
     * 生成Gif验证码
     */
    private function GenGifVerifyCode(){
        $sessionName = Control::GetRequest("sn", "");
        VerifyCode::GenGif($sessionName);
    }

    /**
     * 检查验证码是否正确
     * @return mixed 返回 -1:验证码无效 1:正确 null:默认值，未处理
     */
    private function CheckVerifyCode() {
        $sessionName = Control::GetRequest("sn", "");
        $verifyCodeType = Control::GetRequest("verify_code_type", 0);  //0:int  1:json
        $verifyCodeValue = Control::GetRequest("verify_code_value", 0);
        $result = VerifyCode::Check($sessionName, $verifyCodeType, $verifyCodeValue);
        return $result;
    }

}

?>
