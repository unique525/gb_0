<?php
/**
 * 后台管理 客户端应用程序 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_ClientApp
 * @author zhangchi
 */
class ClientAppManageGen extends BaseManageGen implements IBaseManageGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function Gen() {
        $result = "";
        $method = Control::GetRequest("m", "");
        switch ($method) {
            case "create":
                $result = self::GenCreate();
                break;
            case "modify":
                $result = self::GenModify();
                break;
            case "remove_to_bin":
                $result = self::GenRemoveToBin();
                break;
            case "delete":
                $result = self::GenDelete();
                break;
            case "publish":
                $result = self::Publish();
                break;
            case "list_for_manage_left":
                $result = self::GenListForManageLeft();
                break;
            case "property":
                $result = self::GenProperty();
                break;
        }

        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

} 