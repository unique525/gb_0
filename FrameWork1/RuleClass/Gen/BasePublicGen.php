<?php

/**
 * 前台Gen的基类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen
 * @author zhangchi
 */
class BasePublicGen extends BaseGen {

    /**
     * 替换论坛前部公共模板
     * @param string $tempContent 模板内容
     */
    protected function ReplaceFirstForForum(&$tempContent) {
        $templateFileUrl = "forum/forum_topnav.html";
        $templateName = "default";
        $templatePath = "front_template";
        $forumTopNavTemplate = Template::Load($templateFileUrl, $templateName, $templatePath);

        $tempContent = str_ireplace("{forum_topnav}", $forumTopNavTemplate, $tempContent);
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

    /**
     * 通过子域名取得站点id
     * @return int 站点id
     */
    protected function GetSiteIdBySubDomain() {
        $siteId = 0;
        $host = strtolower($_SERVER['HTTP_HOST']);
        $host = str_ireplace("http://", "", $host);
        if ($host === "localhost" || $host === "127.0.0.1") {
            $siteId = 1;
        } else {
            $arrDomain = explode(".", $host);
            if (count($arrDomain) > 0) {
                $subDomain = $arrDomain[0];
                if (strlen($subDomain) > 0) {
                    $siteData = new SiteData();
                    $siteId = $siteData->GetSiteId($subDomain);
                }
            }
        }
        return $siteId;
    }

    protected function GenProduct(&$tempContent){



    }

}

?>
