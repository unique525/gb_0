<?php

/**
 * 后台管理 文档快速前缀 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Document
 * @author zhangchi
 */
class DocumentPreContentManageGen extends BaseManageGen implements IBaseManageGen {
    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function Gen() {
        $result = "";
        $method = Control::GetRequest("m", "");
        //switch ($method) {
        //    case "list":
        //        $result = self::GenList();
        //        break;
        //}
        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }


} 