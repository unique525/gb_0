<?php
/**
 * 公开访问 客户端应用程序 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_ClientApp
 * @author zhangchi
 */
class ClientAppPublicGen extends BasePublicGen implements IBasePublicGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenPublic() {
        $result = "";

        $action = Control::GetRequest("a", "");
        switch ($action) {
            case "default":
                $result = self::GenDefault();
                break;
        }

        $result = str_ireplace("{action}", $action, $result);

        return $result;
    }

} 