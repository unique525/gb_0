<?php

/**
 * 前台 自定义介绍 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Site
 * @author zhangchi
 */
class SiteContentPublicGen extends BasePublicGen implements IBasePublicGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenPublic() {

        $action = Control::GetRequest("a", "");
        switch ($action) {
            default:
                $result = self::GenDefault();
                break;
        }
        $result = str_ireplace("{action}", $action, $result);
        return $result;
    }

    public function GenDefault(){
        $siteId = parent::GetSiteIdByDomain();
        $siteContentId = Control::GetRequest("site_content_id", 0);
        $templateContent = "";

        if($siteContentId>0){

            $defaultTemp = Control::GetRequest("temp","site_content_default");
            $forceTemp = "";
            $templateMode = 0;


            $templateContent = parent::GetDynamicTemplateContent(
                $defaultTemp,
                $siteId,
                $forceTemp,
                $templateMode
            );



        }

        return $templateContent;
    }
} 