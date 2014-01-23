<?php

/**
 * 所有Gen的基类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen
 * @author zhangchi
 */
class BaseGen {

    
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
