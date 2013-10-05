<?php

class CommonFrontGen extends BaseFrontGen implements IBaseFrontGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenFront() {
        $result = "";
        $action = Control::GetRequest("a", "");
        switch ($action) {
            case "genverifycode":
                self::GenVerifyCode();
                break;
            case "checkverifycode":
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

    private function CheckVerifyCode() {
        $sessionName = Control::GetRequest("sn", "");
        $verifyCodeType = Control::GetRequest("vct", 0);  //0:int  1:jsonp      
        $verifyCodeValue = Control::GetRequest("vcv", 0);
        $result = VerifyCode::Check($sessionName, $verifyCodeType, $verifyCodeValue);
        return $result;
    }

}

?>
