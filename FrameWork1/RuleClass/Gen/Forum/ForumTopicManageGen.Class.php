<?php

/**
 * 后台论坛生成类 
 * @category iCMS
 * @package iCMS_Rules_Gen_Forum
 * @author zhangchi
 */
class ForumTopicManageGen extends BaseManageGen implements IBaseManageGen  {
    
    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function Gen() {
        $result = "";
        $method = Control::GetRequest("a", "");
        switch ($method) {
            case "list":
                $result = self::GenManageList();
                break;
            case "create":
                $result = self::GenCreate();
                break;
            case "modify":
                $result = self::GenModify();
                break;
            case "async_modifystate":
                $result = self::AsyncModifyState();
        }
        $result = str_ireplace("{action}", $method, $result);
        return $result;
    }
}

?>
