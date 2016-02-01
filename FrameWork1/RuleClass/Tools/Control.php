<?php

/**
 * 页面控件相关的工具方法
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Tools
 * @author zhangchi
 */
class Control {

    /**
     * Javascript方法，弹出提示窗口
     * @param string $message 提示信息的内容
     */
    public static function ShowMessage($message) {
        echo '<script type="text/javascript">alert("' . $message . '");</script>';
    }

    /**
     * Javascript方法，弹出确认提示窗口
     * @param string $message 提示信息的内容
     */
    public static function ShowConfirm($message) {
        echo '<script type="text/javascript">confirm("' . $message . '");</script>';
    }

    /**
     * Javascript方法，关闭选项卡
     */
    public static function CloseTab(){
        echo '<' . 'script type="text/javascript">closeTab();</script>';
    }

    /**
     * Javascript方法，调用关闭选项卡
     * @return string
     */
    public static function GetCloseTab(){
        return '<' . 'script type="text/javascript">closeTab();</script>';
    }

    /**
     * 刷新当前的Tab页
     */
    public static function RefreshTab()
    {
        echo '<' . 'script type="text/javascript">window.G_Tabs.tabs("refresh");</script>';
    }

    /**
     * Javascript方法，转到地址
     * @param string $url 要转向到的地址
     */
    public static function GoUrl($url) {
        echo '<script type="text/javascript">window.location.href="' . $url . '";</script>';
    }

    /**
     * Javascript方法，运行指定的JS代码
     * @param string $jsCode 要运行的Javascript代码串
     */
    public static function RunJavascript($jsCode) {
        echo '<script type="text/javascript">' . $jsCode . '</script>';
    }

    /**
     * 返回Jqeury的执行代码
     * @param string $jsCode 要执行的Jqeury代码
     * @return string Jqeury的执行代码
     */
    public static function GetJquery($jsCode){
        return '<script type="text/javascript">$(function () {' . $jsCode . '});</script>';
    }

    /**
     * 返回Jqeury的消息框
     * @param string $message 消息内容
     * @param int $boxHeight 消息框高度(px)
     * @return string Jqeury的执行代码
     */
    public static function GetJqueryMessage($message, $boxHeight = 140){
        $jsCode = '$("#dialog_box").dialog({height: '.$boxHeight.',modal: true});
                var dialogContent = $("#dialog_content");
                dialogContent.html("'.$message.'");';
        return self::GetJquery($jsCode);
    }

    /**
     * 返回Get方式得到的传参数据
     * @param string $paramName 参数名称
     * @param mixed $defaultValue 参数默认值，支持string,int,float
     * @param bool $removeXSS 是否删除XSS信息
     * @return mixed 返回值
     */
    public static function GetRequest($paramName, $defaultValue, $removeXSS = true) {
        $paramName = strtolower($paramName);
        if (isset($_GET[$paramName])) {
            if (is_float($_GET[$paramName])) {
                $result = floatval($_GET[$paramName]);
            } else if (is_int($_GET[$paramName])) {
                $result = intval($_GET[$paramName]);
            } else if (is_array($_GET[$paramName])) {
                $result = $_GET[$paramName];
            } else {
                $result = stripslashes($_GET[$paramName]);
            }
        } else {
            $result = $defaultValue;
        }
        if($removeXSS){
            $result = Format::RemoveXSS($result);
            $result = Format::FormatHtmlTag($result);
        }

        return $result;
    }

    /**
     * 取得Post方式传输的表单请求
     * @param string $paramName 参数名称
     * @param mixed $defaultValue 参数默认值，支持string,int,float
     * @param bool $removeXSS 是否删除XSS信息
     * @return mixed 返回值
     */
    public static function PostRequest($paramName, $defaultValue, $removeXSS = true) {
        if (isset($_POST[$paramName])) {
            if (strlen($_POST[$paramName]) <= 0) {
                $result = $defaultValue;
            } else {
                $result = $_POST[$paramName];
            }
        } else {
            $result = $defaultValue;
        }
        if($removeXSS){
            $result = Format::RemoveXSS($result);
            $result = Format::FormatHtmlTag($result);
        }

        return $result;
    }


    /**
     * 从POST请求取数据，如果没找到则从GET请求里找
     * @param string $paramName
     * @param mixed $defaultValue
     * @param bool $removeXSS
     * @return float|int|string 请求数据值
     */
    public static function PostOrGetRequest($paramName, $defaultValue, $removeXSS = true) {

        if (isset($_POST[$paramName])) {
            if (strlen($_POST[$paramName]) <= 0) {
            } else {
                return $_POST[$paramName];
            }
        } else {

        }

        if (isset($_GET[$paramName])) {
            if (is_float($_GET[$paramName])) {
                return floatval($_GET[$paramName]);
            } else if (is_int($_GET[$paramName])) {
                return intval($_GET[$paramName]);
            } else {
                if($removeXSS){
                    return Format::RemoveXSS($_GET[$paramName]);
                }else{
                    return $_GET[$paramName];
                }

            }
        } else {
            return $defaultValue;
        }
    }

    /**
     * 写入会员cookie
     * @param int $userId 会员id
     * @param string $userName 会员帐号
     * @param int $hour 保存时间（单位小时），默认24小时
     */
    public static function SetUserCookie($userId, $userName, $hour = 24) {
        setcookie("UID", Des::Encrypt($userId,"su141022"), time() + $hour * 3600);
        $userName = urlencode($userName);//Des::Encrypt($userName,"su141022");
        setcookie("USERNAME", $userName, time() + $hour * 3600);
    }

    /**
     * 从cookie中取得会员id
     * @return int 会员id,没找到时返回-1
     */
    public static function GetUserId() {
        if (isset($_COOKIE["UID"])) {
            return intval(Des::Decrypt($_COOKIE["UID"],"su141022"));
        } else {
            return -1;
        }
    }

    /**
     * 从cookie中取得会员名
     * @return string 会员名,没找到时返回""
     */
    public static function GetUserName() {
        if (isset($_COOKIE["USERNAME"])) {
            return urldecode($_COOKIE["USERNAME"]);
            //return Des::Decrypt($_COOKIE["USERNAME"],"su141022");
        } else {
            return "";
        }
    }

    /**
     * 删除会员COOKIE
     */
    public static function DelUserCookie() {
        setcookie("UID", 0, time() - 1);
        setcookie("USERNAME", "", time() - 1);
    }


    /**
     * 写入会员浏览记录cookie
     * @param int $userId 会员id
     * @param string $content cookie内容
     * @param int $hour 保存时间（单位小时），默认24小时
     * @param int $second 保存时间（单位秒），默认0
     */
    public static function SetUserExploreCookie($userId, $content, $hour = 24, $second = 0) {
        $cookieStr = base64_encode(Format::FixJsonEncode($content));
        //保存到cookie当中

        if($second>0){
            $saveTime = time() + $second;
        }else{
            $saveTime = time() + $hour * 3600;
        }

        setcookie('UserExploreHistory'.'_'.$userId, $cookieStr, $saveTime);
    }

    /**
     * 从cookie中取得会员浏览记录
     * @param int $userId 会员id
     * @return array 会员浏览记录
     */
    public static function GetUserExploreCookie($userId) {
        if (isset($_COOKIE['UserExploreHistory'.'_'.$userId])) {

            $cookieStr = $_COOKIE['UserExploreHistory'.'_'.$userId];
            return Format::FixJsonDecode(base64_decode($cookieStr));

        } else {
            return null;
        }
    }

    /**
     * 删除会员浏览记录COOKIE
     * @param int $userId 会员id
     */
    public static function DelUserExploreCookie($userId) {
        setcookie('UserExploreHistory'.'_'.$userId, 0, time() - 1);
    }

    /**
     * 写入后台管理员cookie
     * @param int $manageUserId 后台帐号id
     * @param string $manageUserName 后台帐号名
     * @param int $hour 保存时间（单位小时），默认1小时
     * @param string $domain 保存路径，默认""
     */
    public static function SetManageUserCookie($manageUserId, $manageUserName, $hour = 1, $domain = "") {
        if (!empty($domain)) {
            setcookie('ICMS_MANAGE_USER_ID', Des::Encrypt($manageUserId,"mu141022"), time() + $hour * 3600, "/", $domain);
            //$manageUserName = Des::Encrypt($manageUserName,"mu141022");
            $manageUserName = urlencode($manageUserName);
            setcookie('ICMS_MANAGE_USER_NAME', $manageUserName, time() + $hour * 3600, "/", $domain);
        } else {
            setcookie('ICMS_MANAGE_USER_ID', Des::Encrypt($manageUserId,"mu141022"), time() + $hour * 3600, "/");
            //$manageUserName = Des::Encrypt($manageUserName,"mu141022");
            $manageUserName = urlencode($manageUserName);
            setcookie('ICMS_MANAGE_USER_NAME', $manageUserName, time() + $hour * 3600, "/");
        }
    }

    /**
     * 从cookie中取得后台管理员id
     * @return int 返回后台管理员id
     */
    public static function GetManageUserId() {
        if (isset($_COOKIE["ICMS_MANAGE_USER_ID"])) {
            return intval(Des::Decrypt($_COOKIE["ICMS_MANAGE_USER_ID"],"mu141022"));
        } else {
            return -1;
        }
    }

    /**
     * get admin user name by cookie
     * @return string 返回后台帐号用户名
     */
    public static function GetManageUserName() {
        if (isset($_COOKIE["ICMS_MANAGE_USER_NAME"])) {
            //return Des::Decrypt($_COOKIE["ICMS_MANAGE_USER_NAME"],"mu141022");
            return urldecode($_COOKIE["ICMS_MANAGE_USER_NAME"]);
        } else {
            return "";
        }
    }

    /**
     * 删除管理员COOKIE
     */
    public static function DelManageUserCookie() {
        setcookie("ICMS_MANAGE_USER_ID", 0, time() - 1);
        setcookie("ICMS_MANAGE_USER_NAME", "", time() - 1);
        setcookie("UID", 0, time() - 1);
        setcookie("USERNAME", "", time() - 1);
    }
    
    /**
     * 取得后台管理员使用的模板名称COOKIE
     * @return string 模板名称
     */
    public static function GetManageUserTemplateName(){
        if (isset($_COOKIE["ICMS_MANAGE_USER_TEMPLATE_NAME"])) {
            return $_COOKIE["ICMS_MANAGE_USER_TEMPLATE_NAME"];
        } else {
            return null;
        }
    }
    
    /**
     * 设置后台管理员使用的模板名称COOKIE
     * @param string $templateName 模板名称
     * @param int $hour 保存时间（小时，默认50000）
     */
    public static function SetManageUserTemplateName($templateName, $hour = 50000) {
        setcookie("ICMS_MANAGE_USER_TEMPLATE_NAME", $templateName, time() + $hour * 3600);
    }

    /**
     * 取得客户端IP
     * @return string 返回客户端ip
     */
    public static function GetIp() {
        global $_SERVER;
        //$ip = getenv("REMOTE_ADDR");
        //被注释的代码有ip仿造漏洞和sql注入漏洞

        if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) {
            $ip = getenv("HTTP_CLIENT_IP");
        } else
        if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) {
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        } else
        if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) {
            $ip = getenv("REMOTE_ADDR");
        } else
        if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip = "unknown";
        }

        return ($ip);
    }

    /**
     * 取得客户端浏览器的信息
     * @global  $_SERVER
     * @return string 返回客户端浏览器的信息
     */
    public static function GetBrowser() {
        global $_SERVER;
        $agent = $_SERVER['HTTP_USER_AGENT'];
        $browserType = '';
        $browserVersion = '';
        $allBrowserType = array('Lynx', 'MOSAIC', 'AOL', 'Opera', 'JAVA', 'MacWeb', 'WebExplorer', 'OmniWeb');
        for ($i = 0; $i <= 7; $i++) {
            if (strpos($agent, $allBrowserType[$i])) {
                $browserType = $allBrowserType[$i];
                $browserVersion = '';
            }
        }
        if (preg_match('/Mozilla/i', $agent) && !preg_match('/MSIE/i', $agent)) {
            $temp = explode('(', $agent);
            $part = $temp[0];
            $temp = explode('/', $part);
            $browserVersion = $temp[1];
            $temp = explode(' ', $browserVersion);
            $browserVersion = $temp[0];
            $browserVersion = preg_replace('/([d.]+)/', '1', $browserVersion);
            $browserType = 'Netscape Navigator';
        }
        if (preg_match('/Mozilla/i', $agent) && preg_match('/Opera/i', $agent)) {
            $temp = explode('(', $agent);
            $part = $temp[1];
            $temp = explode(')', $part);
            $browserVersion = $temp[1];
            $temp = explode(' ', $browserVersion);
            $browserVersion = $temp[2];
            $browserVersion = preg_replace('/([d.]+)/', '1', $browserVersion);
            $browserType = 'Opera';
        }
        if (preg_match('/Mozilla/i', $agent) && preg_match('/MSIE/i', $agent)) {
            $temp = explode('(', $agent);
            $part = $temp[1];
            $temp = explode(';', $part);
            $part = $temp[1];
            $temp = explode(' ', $part);
            $browserVersion = $temp[2];
            $browserVersion = preg_replace('/([d.]+)/', '1', $browserVersion);
            $browserType = 'Internet Explorer';
        }
        if ($browserType != '') {
            $browseInfo = $browserType . ' ' . $browserVersion;
        } else {
            $browseInfo = false;
        }
        return $browseInfo;
    }

    /**
     * 取得客户端操作系统信息
     * @global  $_SERVER
     * @return string 返回客户端操作系统信息
     */
    public static function GetOs() {
        global $_SERVER;
        $agent = $_SERVER['HTTP_USER_AGENT'];
        if (preg_match('/win/i', $agent) && strpos($agent, '95')) {
            $os = 'Windows 95';
        } else if (preg_match('/win 9x/i', $agent) && strpos($agent, '4.90')) {
            $os = 'Windows ME';
        } else if (preg_match('/win/i', $agent) && preg_match('/98/', $agent)) {
            $os = 'Windows 98';
        } else if (preg_match('/win/i', $agent) && preg_match('/nt 5.1/i', $agent)) {
            $os = 'Windows XP';
        } else if (preg_match('/win/i', $agent) && preg_match('/nt 5/i', $agent)) {
            $os = 'Windows 2000';
        } else if (preg_match('/win/i', $agent) && preg_match('/nt/i', $agent)) {
            $os = 'Windows NT';
        } else if (preg_match('/win/i', $agent) && preg_match('/32/', $agent)) {
            $os = 'Windows 32';
        } else if (preg_match('/win/i', $agent) && preg_match('/nt 5.2/i', $agent)) {
            $os = 'Windows 2003 Server';
        } else if (preg_match('/win/i', $agent) && preg_match('/nt 6.1/i', $agent)) {
            $os = 'Windows 7';
        } else if (preg_match('/win/i', $agent) && preg_match('/nt 6.0/i', $agent)) {
            $os = 'Windows Vista';
        } else if (preg_match('/linux/i', $agent)) {
            $os = 'Linux';
        } else if (preg_match('/unix/i', $agent)) {
            $os = 'Unix';
        } else if (preg_match('/sun/i', $agent) && preg_match('/os/i', $agent)) {
            $os = 'SunOS';
        } else if (preg_match('/ibm/i', $agent) && preg_match('/os/i', $agent)) {
            $os = 'IBM OS/2';
        } else if (preg_match('/Mac/i', $agent) && preg_match('/PC/i', $agent)) {
            $os = 'Macintosh';
        } else if (preg_match('/PowerPC/i', $agent)) {
            $os = 'PowerPC';
        } else if (preg_match('/AIX/i', $agent)) {
            $os = 'AIX';
        } else if (preg_match('/HPUX/i', $agent)) {
            $os = 'HPUX';
        } else if (preg_match('/NetBSD/i', $agent)) {
            $os = 'NetBSD';
        } else if (preg_match('/BSD/i', $agent)) {
            $os = 'BSD';
        } else if (preg_match('/OSF1/i', $agent)) {
            $os = 'OSF1';
        } else if (preg_match('/IRIX/i', $agent)) {
            $os = 'IRIX';
        } else if (preg_match('/FreeBSD/i', $agent)) {
            $os = 'FreeBSD';
        } else if (preg_match('/teleport/i', $agent)) {
            $os = 'teleport';
        } else if (preg_match('/flashget/i', $agent)) {
            $os = 'flashget';
        } else if (preg_match('/webzip/i', $agent)) {
            $os = 'webzip';
        } else if (preg_match('/offline/i', $agent)) {
            $os = 'offline';
        } else {
            $os = 'Unknown';
        }
        return $os;
    }

    /**
     * 判断是否为utf8   http://w3.org/International/questions/qa-forms-utf-8.html
     * @param string $content
     * @return boolean
     */
    public static function isUtf8($content) {
        $isUtf8 = preg_match('%^(?:
            [\x09\x0A\x0D\x20-\x7E]            # ASCII
            | [\xC2-\xDF][\x80-\xBF]            # non-overlong 2-byte
            |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
            | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
            |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
            |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
            | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
            |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
            )*$%xs', $content);
        return $isUtf8;
    }

    /**
     * 取得毫秒时间戳
     * @return float 毫秒时间戳
     */
    public static function GetMicroTime() {
        $time = explode(" ", microtime());
        $time = $time[1] . ($time [0] * 1000);
        $time2 = explode(".", $time);
        $time = $time2[0];
        if(strlen($time) == 11){
            $time = $time.'000';
        }
        if(strlen($time) == 12){
            $time = $time.'00';
        }
        if(strlen($time) == 13){
            $time = $time.'0';
        }
        return floatval($time);
    }

    /**
     * 判断是否为移动浏览器
     * @return boolean 是否为移动浏览器 
     */
    public static function IsMobile() {

            // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
            if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])) {
                return true;
            }
            //如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
            if (isset ($_SERVER['HTTP_VIA'])) {
                //找不到为false,否则为true
                return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
            }
            //脑残法，判断手机发送的客户端标志,兼容性有待提高
            if (isset ($_SERVER['HTTP_USER_AGENT'])) {
                $clientKeywords = array (
                    'nokia',
                    'sony',
                    'ericsson',
                    'mot',
                    'samsung',
                    'htc',
                    'sgh',
                    'lg',
                    'sharp',
                    'sie-',
                    'philips',
                    'panasonic',
                    'alcatel',
                    'lenovo',
                    'iphone',
                    'ipod',
                    'blackberry',
                    'meizu',
                    'android',
                    'netfront',
                    'symbian',
                    'ucweb',
                    'windowsce',
                    'palm',
                    'operamini',
                    'operamobi',
                    'openwave',
                    'nexusone',
                    'cldc',
                    'midp',
                    'wap',
                    'mobile'
                );
                // 从HTTP_USER_AGENT中查找手机浏览器的关键字
                if (preg_match("/(" . implode('|', $clientKeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
                    return true;
                }
            }
            //协议法，因为有可能不准确，放到最后判断
            if (isset ($_SERVER['HTTP_ACCEPT'])) {
                // 如果只支持wml并且不支持html那一定是移动设备
                // 如果支持wml和html但是wml在html之前则是移动设备
                if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
                    return true;
                }
            }
            return false;

    }

    /**
     * 发送GET请求
     * @param string $url 请求地址
     * @return string 返回结果
     */
    public static function SendGet($url){
        return file_get_contents($url);
    }

    /**
     * 发送POST请求
     * @param string $url 请求地址
     * @param array $postData POST数组数据
     * @return string 返回结果
     */
    public static function SendPost($url, $postData) {
        $content = http_build_query($postData);
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-type:application/x-www-form-urlencoded',
                'content' => $content,
                'timeout' => 15 * 60 // 超时时间（单位:s）
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        return $result;
    }

    /**
     * 取一级域名
     * @param string $url 网址
     * @return string 一级域名
     */
    public static function GetDomain($url){
        //preg_match('/[\w][\w-]*\.(?:com\.cn|com|cn|co|net|org|gov|cc|biz|info)(\/|$)/isU', $url, $domain);
        preg_match("/^(http:\/\/)?([^\/]+)/i", $url, $domain);
        return rtrim($domain[0], '/');
    }

    /**
     * 通过http get 方式获取目标内容
     * @param $url
     * @return mixed
     */
    public static function HttpGet($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $url);

        $res = curl_exec($curl);
        curl_close($curl);

        return $res;
    }

    /**
     * 发起一个post请求到指定接口
     * @param string $url 请求的接口
     * @param array $params post参数
     * @param int $timeout 超时时间
     * @return string 请求结果
     */
    public static function CurlPostRequest($url, array $params = array(), $timeout = 30 ) {
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $url );
        // 以返回的形式接收信息
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        // 设置为POST方式
        curl_setopt( $ch, CURLOPT_POST, 1 );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $params ) );
        // 不验证https证书
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
        curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
            'Accept: application/json',
        ) );
        // 发送数据
        $response = curl_exec( $ch );
        // 不要忘记释放资源
        curl_close( $ch );
        return $response;
    }
}
?>
