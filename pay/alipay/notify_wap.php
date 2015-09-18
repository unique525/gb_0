<?php
define('RELATIVE_PATH', '../..');
define('PHYSICAL_PATH', str_ireplace('default.php','',realpath(__FILE__)));
define("CACHE_PATH", "cache");
mb_internal_encoding('utf8');
date_default_timezone_set('Asia/Shanghai'); //'Asia/Shanghai' 亚洲/上海
//////////////////step 1 include all files///////////////////
require RELATIVE_PATH . "/FrameWork1/include_all.php";

include_all();

$alipay = new Alipay();
$siteId = GetSiteIdByDomain();
if($siteId>0){
    $alipayConfig = $alipay->InitWap($siteId);
    $alipay->NotifyUrlForWap(
        $alipayConfig
    );
}

function GetSiteIdByDomain() {
    $host = strtolower($_SERVER['HTTP_HOST']);
    $host = str_ireplace("http://", "", $host);
    if ($host === "localhost" || $host === "127.0.0.1") {
        $siteId = 1;
    } else {

        //先查绑定的一级域名
        $domain = Control::GetDomain(strtolower($_SERVER['HTTP_HOST']));
        $sitePublicData = new SitePublicData();
        $siteId = $sitePublicData->GetSiteIdByBindDomain($domain, true);

        if($siteId<=0){
            //查子域名
            $arrSubDomain = explode(".", $host);
            if (count($arrSubDomain) > 0) {
                $subDomain = $arrSubDomain[0];

                if (strlen($subDomain) > 0) {

                    $siteId = $sitePublicData->GetSiteId($subDomain, true);
                }
            }
        }

    }
    return $siteId;
}
?>