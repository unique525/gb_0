<?php
/**
 * Created by PhpStorm.
 * User: zcoffice
 * Date: 14-6-5
 * Time: 下午1:34
 */

class SiteAdPublicGen extends BasePublicGen implements IBasePublicGen {


    /**
     * 广告点击记录添加：成功
     */
    const INSERT_AD_LOG_DATA_SUCCESS = 1;
    /**
     * 广告点击记录添加：失败
     */
    const INSERT_AD_LOG_DATA_FAILURE = -1;
    /**
     * 广告 site_content_id 错误
     */
    const FALSE_SITE_AD_CONTENT_ID = -2;

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
                //$result = self::GenAddSiteAdVirtualClick();
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
        $result="";
        if (!empty($_GET)) {
            $siteAdContentId = intval(Control::GetRequest("id", "0"));
            if ($siteAdContentId > 0) {
                $createDate = date("Y-m-d H:i:s", time());
                $ip = Control::GetIP();
                $agent = Control::GetOS();
                $agent = $agent . "与" . Control::GetBrowser();
                $referenceUrl = $_SERVER['HTTP_REFERER'];                                                                 //来路url
                $referenceDomain = strtolower(preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $referenceUrl));                //来路域名
                $isVirtualClick = 0;  //1为虚拟点击,其他为正常点击
                $adLogPublicData = new SiteAdLogPublicData();
                $insertId = $adLogPublicData->InsertData($siteAdContentId, $createDate, $ip, $agent, $referenceDomain, $referenceUrl, $isVirtualClick);
                if ($insertId > 0) {
                    $result.=abs(DefineCode::SITE_AD_LOG_PUBLIC)+self::INSERT_AD_LOG_DATA_SUCCESS;//广告点击记录添加：成功
                } else {
                    $result.=DefineCode::SITE_AD_LOG_PUBLIC+self::INSERT_AD_LOG_DATA_FAILURE;//广告点击记录添加：失败
                }
            }else{
                $result.=DefineCode::SITE_AD_LOG_PUBLIC+self::FALSE_SITE_AD_CONTENT_ID;//广告 site_content_id 错误
            }
            if (isset($_GET['jsonpcallback'])) {
                echo Control::GetRequest("jsonpcallback","") . '([{ReCommon:"' . $result . '"}])';
            }
        }
        return $result;
    }

    /**
     * 统计虚拟点击
     * @return string
     */
    private function GenAddSiteAdVirtualClick() {
        $result = "";
        if (!empty($_GET)){

            $siteAdContentId = intval(Control::GetRequest("id", "0"));
            if($siteAdContentId > 0) {
                $referenceUrl = $_SERVER['HTTP_REFERER'];                                                                 //来路url
                $referenceDomain = strtolower(preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $referenceUrl));                //来路域名
                $siteAdContentPublicData = new SiteAdContentPublicData();
                $openVClick = $siteAdContentPublicData->GetOpenVirtualClick($siteAdContentId);
                if (intval($openVClick) === 1) {
                    $createDate = date("Y-m-d H:i:s", time());
                    $hourSection = date("H", time());
                    $VClickLimit = $siteAdContentPublicData->GetVirtualClickLimit($siteAdContentId);
                    $siteAdLogPublicData = new SiteAdLogPublicData();
                    $VClickCount = $siteAdLogPublicData->GetVClickCount($siteAdContentId, $hourSection);

                    if ($VClickCount < $VClickLimit) {   //实际点击数少于设置的虚拟点击VClickLimit则新增
                        $ip = Control::GetIP();
                        $agent = Control::GetOS();
                        $agent = $agent . "与" . Control::GetBrowser();
                        $isVirtualClick = 1;  //1为虚拟点击,其他为正常点击
                        $adLogPublicData = new SiteAdLogPublicData();
                        $insertId = $adLogPublicData->InsertData($siteAdContentId, $createDate, $ip, $agent, $referenceDomain, $referenceUrl, $isVirtualClick);
                        if ($insertId > 0) {
                            $result.=abs(DefineCode::SITE_AD_LOG_PUBLIC)+self::INSERT_AD_LOG_DATA_SUCCESS;//广告点击记录添加：成功
                        } else {
                            $result.=DefineCode::SITE_AD_LOG_PUBLIC+self::INSERT_AD_LOG_DATA_FAILURE;//广告点击记录添加：失败
                        }
                    }
                }
            }else{
                $result.=DefineCode::SITE_AD_LOG_PUBLIC+self::FALSE_SITE_AD_CONTENT_ID;//广告 site_content_id 错误
            }
            if (isset($_GET['jsonpcallback'])) {
                echo Control::GetRequest("jsonpcallback","") . '([{ReCommon:"' . $result . '"}])';
                return "";
            }
        }
    }

}

?>