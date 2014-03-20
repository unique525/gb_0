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
        $keyName = "pre_temp";
        $arr = Template::GetAllCustomTag($tempContent, $keyName);
        if (isset($arr)) {
            if (count($arr) > 1) {
                $arr2 = $arr[1];
                foreach ($arr2 as $key => $val) {
                    $docContent = "<$keyName$val</$keyName>";
                    //模板ID
                    $channelTemplateId = Template::GetParamValue($docContent, "id", $keyName);
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
                    $siteContentId = Template::GetParamValue($docContent, "id", $keyName);
                    $siteContentData = new SiteContentData();
                    $siteContent = $siteContentData->GetSiteContentValue($siteContentId);
                    $tempContent = Template::ReplaceSiteContent($tempContent, $siteContentId, $siteContent);
                }
            }
        }

        ///////找出SiteAd标记/////////
        $keyName = "site_ad";
        $arr = Template::GetAllCustomTag($tempContent, $keyName);
        if (isset($arr)) {
            if (count($arr) > 1) {
                $arr2 = $arr[1];
                foreach ($arr2 as $key => $val) {
                    $docContent = "<$keyName$val</$keyName>";
                    $siteAdId = Template::GetParamValue($docContent, "id", $keyName);

                    $adgen = new AdGen();
                    $pre_content = $adgen->GenFormatAd($siteAdId);
                    $tempContent = Template::ReplaceSiteAd($tempContent, $siteAdId, $pre_content);
                }
            }
        }

        //找出icms slider标记
        $keyName = "pic_slider";
        $arr = Template::GetAllCustomTag($tempContent, $keyName);
        if (isset($arr)) {
            if (count($arr) > 1) {
                $arr2 = $arr[1];
                $slider_i = 0;
                if (class_exists("DocumentSliderGen")) {
                    $sliderGen = new DocumentSliderGen();
                    foreach ($arr2 as $val) {
                        $docContent = '<icmsslider' . $val . '</icmsslider>';

                        $docchannelid = Template::GetParamValue($docContent, "id", "icmsslider");
                        $top = Template::GetParamValue($docContent, "top", "icmsslider");

                        $pattern = Template::GetParamValue($docContent, "pattern", "icmsslider");
                        $time = Template::GetParamValue($docContent, "time", "icmsslider");
                        $trigger = Template::GetParamValue($docContent, "trigger", "icmsslider");
                        $width = Template::GetParamValue($docContent, "width", "icmsslider");
                        $height = Template::GetParamValue($docContent, "height", "icmsslider");
                        $txtHeight = Template::GetParamValue($docContent, "txtHeight", "icmsslider");
                        $auto = Template::GetParamValue($docContent, "auto", "icmsslider");
                        $wrap = Template::GetParamValue($docContent, "wrap", "icmsslider");
                        $index = Template::GetParamValue($docContent, "index", "icmsslider");
                        $delay = Template::GetParamValue($docContent, "delay", "icmsslider");
                        $duration = Template::GetParamValue($docContent, "duration", "icmsslider");
                        $direction = Template::GetParamValue($docContent, "direction", "icmsslider");
                        $easing = Template::GetParamValue($docContent, "easing", "icmsslider");
                        $less = Template::GetParamValue($docContent, "less", "icmsslider");
                        $chip = Template::GetParamValue($docContent, "chip", "icmsslider");
                        $type = Template::GetParamValue($docContent, "type", "icmsslider");
                        $pad = Template::GetParamValue($docContent, "pad", "icmsslider");
                        $txtWidth = Template::GetParamValue($docContent, "txtWidth", "icmsslider");
                        $gray = Template::GetParamValue($docContent, "gray", "icmsslider");
                        $direct = Template::GetParamValue($docContent, "direct", "icmsslider");
                        $turn = Template::GetParamValue($docContent, "turn", "icmsslider");

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

        //channel_name
        $keyName = "channel_name";
        $arr = Template::GetAllCustomTag($tempContent, $keyName);
        if (isset($arr)) {
            if (count($arr) > 1) {
                $arr2 = $arr[1];
                if(!empty($arr2)){
                    $documentChannelData = new DocumentChannelData();
                    foreach ($arr2 as $val) {
                        $docContent = '<documentchannelname' . $val . '</documentchannelname>';
                        $keyName = "documentchannelname";
                        $channelId = Template::GetParamValue($docContent, "id", $keyName);
                        $documentChannelName = $documentChannelData->GetName($channelId);
                        $tempContent = Template::ReplaceCMS($tempContent, $channelId, $documentChannelName, $keyName);
                    }
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
        $tempContent = str_ireplace("{system_name}", SYSTEM_NAME, $tempContent);
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
     * 是否是安全IP
     * @return boolean 
     */
    public function IsSecurityIp() {
        $ip = Control::GetIP();
        $isInnerIp = false; //是否安全IP
        //
        //安全登录IP，不需要短信认证
        //$SecurityIP = array('130.1.0', '20.20.20', '40.40.40');
        $securityIp = explode('|',SECURITY_IP);
        if (empty($securityIp)) { //没有设置安全IP时，默认都安全
            $isInnerIp = true;
        } else {
            for ($i = 0; $i < count($securityIp); $i++) {
                if (stripos($ip, $securityIp[$i]) === 0) {
                    $isInnerIp = true;
                    break;
                }
            }
        }
        return $isInnerIp;
    }
    
    /**
     * 返回错误内容模板
     * @param string $errorContent 错误提示内容
     * @return string 错误内容模板
     */
    protected function ShowError($errorContent){
        $errorTemplate = Template::Load("error.html","common");        
        $errorTemplate = str_ireplace("{errorcontent}", $errorContent, $errorTemplate);
        self::ReplaceEnd($errorTemplate);        
        return $errorTemplate;
    }

    /**
     * 替换模板中的配置标记
     * @param int $siteId 站点id
     * @param string $tempContent 模板内容
     */
    protected function ReplaceSiteConfig($siteId, &$tempContent) {
        $siteConfigData = new SiteConfigData($siteId);
        $arrSiteConfigOne = $siteConfigData->GetList($siteId);
        if (count($arrSiteConfigOne) > 0) {
            for ($i = 0; $i < count($arrSiteConfigOne); $i++) {
                $siteConfigName = $arrSiteConfigOne[$i]["SiteConfigName"];
                $stringNorValue = $arrSiteConfigOne[$i]["StringNorValue"];
                $stringMidValue = $arrSiteConfigOne[$i]["StringMidValue"];
                $textValue = $arrSiteConfigOne[$i]["TextValue"];
                $intValue = $arrSiteConfigOne[$i]["IntValue"];
                $numValue = $arrSiteConfigOne[$i]["NumValue"];
                $siteConfigType = intval($arrSiteConfigOne[$i]["SiteConfigType"]);
                switch ($siteConfigType) {
                    case 0:
                        $tempContent = str_ireplace("{cfg_" . $siteConfigName . "_" . $siteConfigType . "}", $stringNorValue, $tempContent);
                        $tempContent = str_ireplace("{cfg_" . $siteConfigName . "}", $stringNorValue, $tempContent);
                        break;
                    case 1:
                        $tempContent = str_ireplace("{cfg_" . $siteConfigName . "_" . $siteConfigType . "}", $stringMidValue, $tempContent);
                        $tempContent = str_ireplace("{cfg_" . $siteConfigName . "}", $stringMidValue, $tempContent);
                        break;
                    case 2:
                        $tempContent = str_ireplace("{cfg_" . $siteConfigName . "_" . $siteConfigType . "}", $textValue, $tempContent);
                        $tempContent = str_ireplace("{cfg_" . $siteConfigName . "}", $textValue, $tempContent);
                        break;
                    case 3:
                        $tempContent = str_ireplace("{cfg_" . $siteConfigName . "_" . $siteConfigType . "}", $intValue, $tempContent);
                        $tempContent = str_ireplace("{cfg_" . $siteConfigName . "}", $intValue, $tempContent);
                        break;
                    case 4:
                        $tempContent = str_ireplace("{cfg_" . $siteConfigName . "_" . $siteConfigType . "}", $numValue, $tempContent);
                        $tempContent = str_ireplace("{cfg_" . $siteConfigName . "}", $numValue, $tempContent);
                        break;
                    default:
                        $tempContent = str_ireplace("{cfg_" . $siteConfigName . "_" . $siteConfigType . "}", $stringNorValue, $tempContent);
                        $tempContent = str_ireplace("{cfg_" . $siteConfigName . "}", $stringNorValue, $tempContent);
                        break;
                }
            }
        } else {//移除掉标记
            $patterns = "/\{cfg_(.*)\<\/}/imsU";
            $tempContent = preg_replace($patterns, "", $tempContent);
        }
    }


    /**
     * 取得Web客户端的综合信息
     * @return array 返回储存信息的数据集
     */
    protected function GetWebClientInfo(){
        $arrayOfWebClientInfo = array();

        return $arrayOfWebClientInfo;
    }


    /**
     * 新增管理员操作日志
     * @param string $operateContent 操作内容
     */
    protected function CreateManageUserLog($operateContent)
    {
        $manageUserId = Control::GetManageUserId();
        $manageUserName = Control::GetManageUserName();
        $ipAddress = Control::GetIp();
        $webAgent = Control::GetOs() . " and " . Control::GetBrowser();
        $refererUrl = $_SERVER["HTTP_REFERER"];
        $refererDomain = strtolower(preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $refererUrl));
        $selfUrl = $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
        $userId = Control::GetUserId();
        $userName = Control::GetUserName();
        $manageUserLogManageData = new ManageUserLogManageData();
        $manageUserLogManageData->Create($manageUserId, $manageUserName, $ipAddress, $webAgent, $selfUrl, $refererUrl, $refererDomain, $userId, $userName, $operateContent);
    }
}

?>
