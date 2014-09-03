<?php

class Remote {

    /**
     * 根据URL抓数据
     * @param string $url 要抓取的网址
     * @param int $jumpNumber
     * @return array|bool|string
     */
    public static function GetUrl($url,$jumpNumber=0){
        $arrUrl = parse_url(trim($url));
        if(!$arrUrl)return false;
        $host=$arrUrl['host'];
        $port=isset($arrUrl['port'])?$arrUrl['port']:80;
        $path=$arrUrl['path'].(isset($arrUrl['query'])?"?".$arrUrl['query']:"");
        $fp = @fsockopen($host,$port,$errorNumber, $errorString, 30);
        if(!$fp)return false;
        $output="GET $path HTTP/1.0\r\nHost: $host\r\nReferer: $url\r\nConnection: close\r\n\r\n";
        stream_set_timeout($fp, 60);
        @fputs($fp,$output);
        $Content='';
        while(!feof($fp))
        {
            $buffer = fgets($fp, 4096);
            $info = stream_get_meta_data($fp);
            if($info['timed_out'])return false;
            $Content.=$buffer;
        }
        @fclose($fp);
        //global $jumpCount;//重定向
        if(preg_match("/^HTTP\/\d.\d (301|302)/is",$Content)&&$jumpNumber<5)
        {
            if(preg_match("/Location:(.*?)\r\n/is",$Content,$mUrl))return getUrl($mUrl[1],$jumpNumber+1);
        }
        if(!preg_match("/^HTTP\/\d.\d 200/is", $Content))return false;
        $Content=explode("\r\n\r\n",$Content,2);
        $Content=$Content[1];
        if($Content)return $Content;
        else return false;
    }
} 