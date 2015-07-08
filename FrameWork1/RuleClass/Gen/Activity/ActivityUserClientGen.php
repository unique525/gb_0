<?php
/**
 * 客户端 活动会员 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Activity
 * @author zhangchi
 */
class ActivityUserClientGen extends BaseClientGen implements IBaseClientGen  {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenClient()
    {
        $result = "";
        $function = Control::GetRequest("f", "");
        switch ($function) {

            case "list":
                $result = self::GenList();
                break;

            case "create":
                $result = self::GenCreate();
                break;
        }
        $result = str_ireplace("{function}", $function, $result);
        return $result;
    }
} 