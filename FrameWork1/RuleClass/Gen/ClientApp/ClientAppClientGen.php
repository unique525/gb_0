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

            case "all_child_list_by_parent_id":
                $result = self::GetAllChildListByParentId();
                break;

        }
        $result = str_ireplace("{function}", $function, $result);
        return $result;
    }
} 