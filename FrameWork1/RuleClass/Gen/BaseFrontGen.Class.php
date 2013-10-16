<?php

/**
 * 前台Gen的基类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen
 * @author zhangchi
 */
class BaseFrontGen extends BaseGen {

    /**
     * 
     * @param string $tempContent
     */
    protected function ReplaceFirstForForum(&$tempContent) {
        $templateFileUrl = "forum/forum_topnav.html";
        $templateName = "default";
        $templatePath = "front_template";
        $forumTopNavTemplate = Template::Load($templateFileUrl, $templateName, $templatePath);
        
        $tempContent = str_ireplace("{forum_topnav}", $forumTopNavTemplate, $tempContent);
    }

}

?>
