<?php

/**
 * 后台Gen的基类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen
 * @author zhangchi
 */
class BaseManageGen extends BaseGen
{

    /**
     * 在模板替换前的统一替换
     * @param string $tempContent 要处理的模板
     */
    public function ReplaceFirst(&$tempContent) {
        ///////找出PreTemp标记/////////
        $keyName = "pre_temp";
        $arr = Template::GetAllCustomTag($tempContent, $keyName);
        if (isset($arr)) {
            if (count($arr) > 1) {
                $arr2 = $arr[1];
                foreach ($arr2 as $key => $val) {
                    $docContent = "<$keyName$val</$keyName>";
                    //模板ID
                    $channelTemplateId = Template::GetDocParamValue($docContent, "id", $keyName);

                    $channelTemplateData = new ChannelTemplateData();
                    $preTempContent = $channelTemplateData->GetChannelTemplateContent($channelTemplateId);

                    $tempContent = Template::ReplaceCustomTag($tempContent, $channelTemplateId, $preTempContent, $keyName);
                }
            }
        }
        ///////找出site_content标记/////////
        $keyName = "site_content";
        $arr = Template::GetAllCustomTag($tempContent, $keyName);
        if (isset($arr)) {
            if (count($arr) > 1) {
                $arr2 = $arr[1];
                foreach ($arr2 as $key => $val) {
                    $docContent = "<$keyName$val</$keyName>";
                    //模板ID
                    $siteContentId = Template::GetDocParamValue($docContent, "id", $keyName);
                    $siteContentData = new SiteContentData();
                    $siteContent = $siteContentData->GetSiteContentValue($siteContentId);
                    $tempContent = Template::ReplaceSiteContent($tempContent, $siteContentId, $siteContent);
                }
            }
        }

        ///////找出SiteAd标记/////////
        $arr = Template::GetAllSiteAd($tempContent);
        if (isset($arr)) {
            if (count($arr) > 1) {
                $arr2 = $arr[1];
                foreach ($arr2 as $key => $val) {
                    $docContent = '<sitead' . $val . '</sitead>';
                    $keyName = "sitead";
//模板ID
                    $adid = Template::GetDocParamValue($docContent, "id", $keyName);

                    $adgen = new AdGen();
                    $pre_content = $adgen->GenFormatAd($adid);
                    $tempContent = Template::ReplaceSiteAd($tempContent, $adid, $pre_content);
                }
            }
        }

        //找出icmsslider标记
        $arr = Template::GetAllIcmsSlider($tempContent);
        if (isset($arr)) {
            if (count($arr) > 1) {
                $arr2 = $arr[1];
                $keyName = "icmsslider";
                $slider_i = 0;
                if (class_exists("DocumentSliderGen")) {
                    $sliderGen = new DocumentSliderGen();
                    foreach ($arr2 as $key => $val) {
                        $docContent = '<icmsslider' . $val . '</icmsslider>';

                        $docchannelid = Template::GetDocParamValue($docContent, "id", "icmsslider");
                        $top = Template::GetDocParamValue($docContent, "top", "icmsslider");

                        $pattern = Template::GetDocParamValue($docContent, "pattern", "icmsslider");
                        $time = Template::GetDocParamValue($docContent, "time", "icmsslider");
                        $trigger = Template::GetDocParamValue($docContent, "trigger", "icmsslider");
                        $width = Template::GetDocParamValue($docContent, "width", "icmsslider");
                        $height = Template::GetDocParamValue($docContent, "height", "icmsslider");
                        $txtHeight = Template::GetDocParamValue($docContent, "txtHeight", "icmsslider");
                        $auto = Template::GetDocParamValue($docContent, "auto", "icmsslider");
                        $wrap = Template::GetDocParamValue($docContent, "wrap", "icmsslider");
                        $index = Template::GetDocParamValue($docContent, "index", "icmsslider");
                        $delay = Template::GetDocParamValue($docContent, "delay", "icmsslider");
                        $duration = Template::GetDocParamValue($docContent, "duration", "icmsslider");
                        $direction = Template::GetDocParamValue($docContent, "direction", "icmsslider");
                        $easing = Template::GetDocParamValue($docContent, "easing", "icmsslider");
                        $less = Template::GetDocParamValue($docContent, "less", "icmsslider");
                        $chip = Template::GetDocParamValue($docContent, "chip", "icmsslider");
                        $type = Template::GetDocParamValue($docContent, "type", "icmsslider");
                        $pad = Template::GetDocParamValue($docContent, "pad", "icmsslider");
                        $txtWidth = Template::GetDocParamValue($docContent, "txtWidth", "icmsslider");
                        $gray = Template::GetDocParamValue($docContent, "gray", "icmsslider");
                        $direct = Template::GetDocParamValue($docContent, "direct", "icmsslider");
                        $turn = Template::GetDocParamValue($docContent, "turn", "icmsslider");

                        $arr_par = array(
                            "{pattern}" => $pattern,
                            "{time}" => $time,
                            "{trigger}" => $trigger,
                            "{width}" => $width,
                            "{height}" => $height,
                            "{txtHeight}" => $txtHeight,
                            "{auto}" => $auto,
                            "{wrap}" => $wrap,
                            "{index}" => $index,
                            "{delay}" => $delay,
                            "{duration}" => $duration,
                            "{direction}" => $direction,
                            "{easing}" => $easing,
                            "{less}" => $less,
                            "{chip}" => $chip,
                            "{type}" => $type,
                            "{pad}" => $pad,
                            "{txtWidth}" => $txtWidth,
                            "{gray}" => $gray,
                            "{direct}" => $direct,
                            "{turn}" => $turn
                        );

                        $slidercontent = $sliderGen->ReplaceSlider($docchannelid, $top, $arr_par);
                        if ($slider_i == 0) {
                            $slidercontent = '<script type="text/javascript" src="{funcdomain}/js/myfocus-1.2.0.full.js"></script>' . $slidercontent;
                        }
                        $tempContent = Template::ReplaceIcmsSlider($tempContent, $docchannelid, $slidercontent);
                        $slider_i++;
                    }
                }
            }
        }

        //找出documentchannelname标记
        $arr = Template::GetAllCMS($tempContent, "documentchannelname");
        if (isset($arr)) {
            if (count($arr) > 1) {
                $arr2 = $arr[1];
                $documentChannelData = new DocumentChannelData();
                foreach ($arr2 as $key => $val) {
                    $docContent = '<documentchannelname' . $val . '</documentchannelname>';
                    $keyName = "documentchannelname";
                    $documentChannelId = Template::GetDocParamValue($docContent, "id", $keyName);
                    $documentChannelName = $documentChannelData->GetName($documentChannelId);
                    $tempContent = Template::ReplaceCMS($tempContent, $documentChannelId, $documentChannelName, $keyName);
                }
            }
        }
    }

    /**
     * 在模板替换后的统一替换
     * @param string $tempContent 要处理的模板
     */
    public function ReplaceEnd(&$tempContent) {
        $templateName = self::GetTemplateName();
        $selectTemplate = Template::Load("select_template.html","common");
        $tempContent = str_ireplace("{relative_path}", RELATIVE_PATH, $tempContent);
        $tempContent = str_ireplace("{manage_domain}", MANAGE_DOMAIN, $tempContent);
        $tempContent = str_ireplace("{webapp_domain}", WEBAPP_DOMAIN, $tempContent);
        $tempContent = str_ireplace("{template_name}", $templateName, $tempContent);
        $tempContent = str_ireplace("{select_template}", $selectTemplate, $tempContent);
        $tempContent = str_ireplace("{template_selected_$templateName}", "_selected", $tempContent);
        $tempContent = str_ireplace("{template_selected_default}", "", $tempContent);
        $tempContent = str_ireplace("{template_selected_deepblue}", "", $tempContent);


    }

    /**
     * 取得后台管理员使用的模板名称
     * @return string 模板名称
     */
    private function GetTemplateName() {
        $templateName = Control::GetManageUserTemplateName();
        if(strlen($templateName)<=0){
            $templateName = "default";
        }
        return $templateName;
    }

    /**
     * 检查当前管理员在页面是否有操作权限
     * @param AdminUserPopedomManageData $adminUserPopedomManageData 管理员权限管理数据类
     * @param int $adminUserId 管理员id
     * @param int $documentChannelId 频道id
     * @param int $siteId 站点id
     * @param string $op 操作类型
     * @return boolean 是否有操作权限
     */
    protected function CheckAdminUserPopedom(AdminUserPopedomManageData $adminUserPopedomManageData, $adminUserId, $documentChannelId, $siteId, $op)
    {
        $result = TRUE;
        if ($adminUserId <= 0) {
            $result = FALSE;
        } else { //检查权限
            $can = FALSE;
            switch ($op) {
                case "explore":
                    $can = $adminUserPopedomManageData->CanExplore($siteId, $documentChannelId, $adminUserId);
                    break;
            }
            if (!$can) {
                $result = FALSE;
            }
        }
        return $result;
    }
}

?>
