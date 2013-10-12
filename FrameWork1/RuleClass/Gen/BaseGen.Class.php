<?php

/**
 * 所有Gen的基类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen
 * @author zhangchi
 */
class BaseGen {

    /**
     * 在模板替换前的统一替换
     * @param string $tempContent 要处理的模板
     */
    public function ReplaceFirst(&$tempContent) {
        ///////找出PreTemp标记/////////
        $arr = Template::GetAllPreTemp($tempContent);
        if (isset($arr)) {
            if (count($arr) > 1) {
                $arr2 = $arr[1];
                foreach ($arr2 as $key => $val) {
                    $docontent = '<pretemp' . $val . '</pretemp>';
                    $keyname = "pretemp";
                    //模板ID
                    $documentchanneltemplateid = Template::GetDocParamValue($docontent, "id", $keyname);

                    $documentChannelTemplateData = new DocumentChannelTemplateData();
                    $pre_content = $documentChannelTemplateData->GetContentByID($documentchanneltemplateid);

                    $tempContent = Template::ReplacePreTemp($tempContent, $documentchanneltemplateid, $pre_content);
                }
            }
        }
        ///////找出sitecontent标记/////////
        $arr = Template::GetAllSiteContent($tempContent);
        if (isset($arr)) {
            if (count($arr) > 1) {
                $arr2 = $arr[1];
                foreach ($arr2 as $key => $val) {
                    $docontent = '<sitecontent' . $val . '</sitecontent>';
                    $keyname = "sitecontent";
                    //模板ID
                    $siteContentId = Template::GetDocParamValue($docontent, "id", $keyname);

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
                    $docontent = '<sitead' . $val . '</sitead>';
                    $keyname = "sitead";
//模板ID
                    $adid = Template::GetDocParamValue($docontent, "id", $keyname);

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
                $keyname = "icmsslider";
                $slider_i = 0;
                if (class_exists("DocumentSliderGen")) {
                    $sliderGen = new DocumentSliderGen();
                    foreach ($arr2 as $key => $val) {
                        $docontent = '<icmsslider' . $val . '</icmsslider>';

                        $docchannelid = Template::GetDocParamValue($docontent, "id", "icmsslider");
                        $top = Template::GetDocParamValue($docontent, "top", "icmsslider");

                        $pattern = Template::GetDocParamValue($docontent, "pattern", "icmsslider");
                        $time = Template::GetDocParamValue($docontent, "time", "icmsslider");
                        $trigger = Template::GetDocParamValue($docontent, "trigger", "icmsslider");
                        $width = Template::GetDocParamValue($docontent, "width", "icmsslider");
                        $height = Template::GetDocParamValue($docontent, "height", "icmsslider");
                        $txtHeight = Template::GetDocParamValue($docontent, "txtHeight", "icmsslider");
                        $auto = Template::GetDocParamValue($docontent, "auto", "icmsslider");
                        $wrap = Template::GetDocParamValue($docontent, "wrap", "icmsslider");
                        $index = Template::GetDocParamValue($docontent, "index", "icmsslider");
                        $delay = Template::GetDocParamValue($docontent, "delay", "icmsslider");
                        $duration = Template::GetDocParamValue($docontent, "duration", "icmsslider");
                        $direction = Template::GetDocParamValue($docontent, "direction", "icmsslider");
                        $easing = Template::GetDocParamValue($docontent, "easing", "icmsslider");
                        $less = Template::GetDocParamValue($docontent, "less", "icmsslider");
                        $chip = Template::GetDocParamValue($docontent, "chip", "icmsslider");
                        $type = Template::GetDocParamValue($docontent, "type", "icmsslider");
                        $pad = Template::GetDocParamValue($docontent, "pad", "icmsslider");
                        $txtWidth = Template::GetDocParamValue($docontent, "txtWidth", "icmsslider");
                        $gray = Template::GetDocParamValue($docontent, "gray", "icmsslider");
                        $direct = Template::GetDocParamValue($docontent, "direct", "icmsslider");
                        $turn = Template::GetDocParamValue($docontent, "turn", "icmsslider");

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
                    $docontent = '<documentchannelname' . $val . '</documentchannelname>';
                    $keyname = "documentchannelname";
                    $documentChannelId = Template::GetDocParamValue($docontent, "id", $keyname);
                    $documentChannelName = $documentChannelData->GetName($documentChannelId);
                    $tempContent = Template::ReplaceCMS($tempContent, $documentChannelId, $documentChannelName, $keyname);
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
        $selectTemplate = Template::Load("selecttemplate.html","common");
        $domain = null;
        require ROOTPATH . '/FrameWork1/SystemInc/domain.inc.php';
        $tempContent = str_ireplace("{rootpath}", ROOTPATH, $tempContent);
        $tempContent = str_ireplace("{icmsdomain}", $domain['icms'], $tempContent);
        $tempContent = str_ireplace("{funcdomain}", $domain['func'], $tempContent);
        $tempContent = str_ireplace("{usercenterdomain}", $domain['user'], $tempContent);
        $tempContent = str_ireplace("{templatename}", $templateName, $tempContent);
        $tempContent = str_ireplace("{selecttemplate}", $selectTemplate, $tempContent);
        $tempContent = str_ireplace("{templateselected_$templateName}", "_selected", $tempContent);
        $tempContent = str_ireplace("{templateselected_default}", "", $tempContent);
        $tempContent = str_ireplace("{templateselected_deepblue}", "", $tempContent);
        
        
    }

    /**
     * 取得后台管理员使用的模板名称
     * @return string 模板名称
     */
    private function GetTemplateName() {
        $templateName = Control::GetAdminUserTemplateName();
        if(strlen($templateName)<=0){
            $templateName = "default";
        }
        return $templateName;
    }

}

?>
