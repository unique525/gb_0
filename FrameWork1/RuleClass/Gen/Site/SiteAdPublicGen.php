<?php
/**
 * Created by PhpStorm.
 * User: zcoffice
 * Date: 14-6-5
 * Time: 下午1:34
 */

class SiteAdPublicGen extends BasePublicGen implements IBasePublicGen {


    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenPublic() {
        $result = "";
        $method = Control::GetRequest("m", "");
        switch ($method) {
            case "site_ad_click":
                $result = self::GenAddSiteAdClick();
                break;
            case "site_ad_virtual_click":
                $result = self::GenAddSiteAdViltualClick();
                break;
        }
        $replace_arr = array(
            "{method}" => $method
        );
        $result = strtr($result, $replace_arr);
        return $result;
    }

    /**
     * 统计广告点击
     * @return string
     */
    private function GenAddSiteAdClick() {
        if (!empty($_GET)) {
            $siteAdContentId = intval(Control::GetRequest("id", "0"));
            if ($siteAdContentId > 0) {
                $createdate = date("Y-m-d H:i:s", time());
                $ip = Control::GetIP();
                $agent = Control::GetOS();
                $agent = $agent . "与" . Control::GetBrowser();
                $referenceUrl = $_SERVER['HTTP_REFERER'];                                                                 //来路url
                $referenceDomain = strtolower(preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $referenceUrl));                //来路域名
                $isVirtualClick = 0;  //1为虚拟点击,其他为正常点击
                $adLogPublicData = new AdLogPublicData();
                $insertId = $adLogPublicData->InsertData($siteAdContentId, $createdate, $ip, $agent, $referenceDomain, $referenceUrl, $isVirtualClick);
                if ($insertId > 0) {
                    $recommon = 1;
                } else {
                    $recommon = -1;
                }
                if (isset($_GET['jsonpcallback'])) {
                    echo Control::GetRequest("jsonpcallback","") . '([{recommon:"' . $recommon . '"}])';
                    return "";
                }
            }
        }
    }

    /**
     * 统计虚拟点击
     * @return string
     */
    private function GenVClick() {
        $recommon = 0;
        $adcontentid = intval(Control::GetRequest("id", "0"));
        if (!empty($_GET) && $adcontentid > 0) {
            $refurl = $_SERVER['HTTP_REFERER'];                                                                 //来路url
            $refdomain = strtolower(preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $refurl));                //来路域名
//检查来路是否是changsha.cn域名下的
            if (stristr($refdomain, 'changsha.cn') != FALSE) {
                $adcontentData = new AdContentData();
                $openvclick = $adcontentData->GetOpenVClick($adcontentid);
                $createdate = date("Y-m-d H:i:s", time());
                $hoursection = date("H", time());
                if (intval($openvclick) === 1) {
                    $vclicklimit = $adcontentData->GetVClickLimit($adcontentid);
                    $advclickData = new AdVClickData();
                    $vclickcount = $advclickData->GetVClickCount($adcontentid, $hoursection);
//实际点击数少于设置的虚拟点击$vclicklimit则新增
                    if ($vclickcount < $vclicklimit) {
                        $recommon = $advclickData->Create($adcontentid);
                        $ip = Control::GetIP();
                        $agent = Control::GetOS();
                        $agent = $agent . "与" . Control::GetBrowse();
                        $isvclick = 1;      //点击记录类型:1为虚拟
                        $adlogData = new AdLogData();
                        $insertid = $adlogData->InsertData($adlogid, $adcontentid, $createdate, $ip, $agent, $refdomain, $refurl, $isvclick);
                        $recommon = $insertid;
                    }
                }
            }
        }
        if (isset($_GET['jsonpcallback'])) {
            echo Control::GetRequest("jsonpcallback","") . '([{recommon:"' . $recommon . '"}])';
            return "";
        }
    }

} 