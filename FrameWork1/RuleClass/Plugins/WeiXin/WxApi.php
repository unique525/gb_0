<?php


class WxApi {


    /**
     * 取得AccessToken
     * @param SiteConfigData $siteConfigData
     * @return string
     */
    public function GetAccessToken(SiteConfigData $siteConfigData){

        $result = "";

        $weiXinAppId = $siteConfigData->WeiXinAppId;
        $weiXinAppSecret = $siteConfigData->WeiXinAppSecret;

        if(strlen($weiXinAppId)<=0 || strlen($weiXinAppSecret)<=0){
            die("wx app id or wx app secret is null");
        }


        $weiXinAccessToken = $siteConfigData->WeiXinAccessToken;

        $weiXinAccessTokenGetTime = $siteConfigData->WeiXinAccessTokenGetTime;

        //微信的access token保存时间是7200秒，所以判断当保存时间大约已经过了7000秒时，则重新请求token

        if(  strlen($weiXinAccessToken)<=0 || (time() - $weiXinAccessTokenGetTime)>7000){


            $urlGetAccessToken = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$weiXinAppId&secret=$weiXinAppSecret";

            //执行 http 请求，返回 json


            /**
             * 正确时返回的JSON数据包如下：

             * {"access_token":"ACCESS_TOKEN","expires_in":7200}
             *
             */

            $curl_result = Control::HttpGet($urlGetAccessToken); //json

            $arrResult = Format::FixJsonDecode($curl_result);

            //错误判断
            if(isset($arrResult["errcode"])){
                die($arrResult["errmsg"]);
            }elseif(isset($arrResult["access_token"])){

                //拿到了token,保存，并更新保存unix时间
                $result = $arrResult["access_token"];
                $siteConfigData->WeiXinAccessToken = $arrResult["access_token"];
                $siteConfigData->WeiXinAccessTokenGetTime = time();

            }


        }else{
            //已经有token了
            $result = $siteConfigData->WeiXinAccessToken;
        }

        return $result;

    }



    /**
     * 取得JsApiTicket
     * @param SiteConfigData $siteConfigData
     * @return string
     */
    public function GetJsApiTicket(SiteConfigData $siteConfigData){

        $result = "";

        $weiXinJsApiTicket = $siteConfigData->WeiXinJsApiTicket;
        $weiXinJsApiTicketGetTime = $siteConfigData->WeiXinJsApiTicketGetTime;

        if(strlen($weiXinJsApiTicket)<=0 || (time() - $weiXinJsApiTicketGetTime)>7000){
            $weiXinAccessToken = self::GetAccessToken($siteConfigData);

            if(strlen($weiXinAccessToken)>=0){

                $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$weiXinAccessToken";
                //执行 http 请求，返回 json

                $curl_result = Control::HttpGet($url); //json

                $arrResult = Format::FixJsonDecode($curl_result);



                //拿到了ticket,保存，并更新保存unix时间
                $result = $arrResult["ticket"];
                $siteConfigData->WeiXinJsApiTicket = $result;
                $siteConfigData->WeiXinJsApiTicketGetTime = time();

            }
        }
        else{
            //已经有了
            $result = $siteConfigData->WeiXinJsApiTicket;
        }


        return $result;

    }
} 