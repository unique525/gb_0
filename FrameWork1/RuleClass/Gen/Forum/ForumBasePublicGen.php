<?php

/**
 * 前台 论坛基类 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Forum
 * @author zhangchi
 */
class ForumBasePublicGen extends BasePublicGen {

    /**
     * 替换论坛前部公共模板
     * @param string $tempContent 模板内容
     */
    protected function ReplaceFirstForForum(&$tempContent) {
        //$templateFileUrl = "forum/forum_top_nav.html";
        //$templateName = "default";
        //$templatePath = "front_template";
        //$forumTopNavTemplate = Template::Load($templateFileUrl, $templateName, $templatePath);

        //$templateFileUrl = "forum/forum_common_head.html";
        //$forumCommonHeadTemplate = Template::Load($templateFileUrl, $templateName, $templatePath);

        $forumTopNavTemplate = "";
        $forumCommonHeadTemplate = "";

        $tempContent = str_ireplace("{forum_top_nav}", $forumTopNavTemplate, $tempContent);
        $tempContent = str_ireplace("{forum_common_head}", $forumCommonHeadTemplate, $tempContent);
    }

    /**
     * 替换论坛底部公共模板
     * @param string $tempContent 模板内容
     */
    protected function ReplaceEndForForum(&$tempContent) {
        //$templateFileUrl = "forum/forum_topnav.html";
        //$templateName = "default";
        //$templatePath = "front_template";
        //$forumTopNavTemplate = Template::Load($templateFileUrl, $templateName, $templatePath);
        //$tempContent = str_ireplace("{forum_topnav}", $forumTopNavTemplate, $tempContent);
    }
} 