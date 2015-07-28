<?php

class WxJsSDK {

    /**
     * 取得wx js api的签名数组
     * @param SiteConfigData $siteConfigData
     * @return array
     */
    public function getSignPackage(SiteConfigData $siteConfigData) {
        $wxApi = new WxApi();
        $jsapiTicket = $wxApi->GetJsApiTicket($siteConfigData);

        // 注意 URL 一定要动态获取，不能 hardcode.
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $timestamp = time();
        $nonceStr = $this->createNonceStr();

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);

        $signPackage = array(
            "WxAppId"     => $siteConfigData->WeiXinAppId,
            "WxNonceStr"  => $nonceStr,
            "WxTimestamp" => $timestamp,
            "WxUrl"       => $url,
            "WxSignature" => $signature,
            "WxRawString" => $string
        );
        return $signPackage;
    }

    private function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
} 