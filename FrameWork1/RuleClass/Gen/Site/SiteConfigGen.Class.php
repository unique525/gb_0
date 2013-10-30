<?php

/**
 * 前台站点配置生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Site
 * @author zhangchi
 */
class SiteConfigGen extends BaseFrontGen implements IBaseFrontGen {
        
    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenFront() {
        $result = "";

        $action = Control::GetRequest("a", "");
        switch ($action) {            
            default:
                
                break;
        }

        return $result;
    }

}

?>
