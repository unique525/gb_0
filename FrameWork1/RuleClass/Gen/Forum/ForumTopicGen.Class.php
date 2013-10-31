<?php

/**
 * 前台论坛生成类 
 * @category iCMS
 * @package iCMS_Rules_Gen_Forum
 * @author zhangchi
 */
class ForumTopicGen extends BaseFrontGen implements IBaseFrontGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenFront() {
        $result = "";

        $action = Control::GetRequest("a", "");
        switch ($action) {
            case "list":
                $result = self::GenList();
                break;
            default:
                $result = self::GenList();
                break;
        }

        return $result;
    }

    private function GenList() {
        $siteId = Control::GetRequest("siteid", 0);
        if ($siteId <= 0) {
            $siteId = parent::GetSiteIdBySubDomain();
        }
        $forumId = Control::GetRequest("forumid", 0);
        if($forumId<=0){
            return;
        }

        $templateFileUrl = "forum/forum_topic_list.html";
        $templateName = "default";
        $templatePath = "front_template";
        $tempContent = Template::Load($templateFileUrl, $templateName, $templatePath);


        parent::ReplaceFirstForForum($tempContent);
        parent::ReplaceEndForForum($tempContent);
        parent::ReplaceSiteConfig($siteId, $tempContent);
        return $tempContent;
    }

}

?>
