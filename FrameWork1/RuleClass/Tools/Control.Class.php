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
    public static function RunJS($jsCode) {
        echo '<script type="text/javascript">' . $jsCode . '</script>';
    }

    /**
     * 返回Get方式得到的传参数据
     * @param string $paramName 参数名称
     * @param mixed $defaultValue 参数默认值，支持string,int,float
     * @return mixed 返回值
     */
    public static function GetRequest($paramName, $defaultValue) {
        if (isset($_GET[$paramName])) {
            if (is_float($_GET[$paramName])) {
                return floatval($_GET[$paramName]);
            } else if (is_int($_GET[$paramName])) {
                return intval($_GET[$paramName]);
            } else if (is_array($_GET[$paramName])) {
                return $_GET[$paramName];
            } else {
                return stripslashes($_GET[$paramName]);
            }
        } else {
            return $defaultValue;
        }
    }

    /**
     * 取得Post方式传输的表单请求
     * @param string $paramName 参数名称
     * @param mixed $defaultValue 参数默认值，支持string,int,float
     * @return mixed 返回值
     */
    public static function PostRequest($paramName, $defaultValue) {
        if (isset($_POST[$paramName])) {
            //if (is_numeric($_POST[$paramname])) { //有BUG 2011.1.13停用
            //    return get_int($_POST[$paramname]);
            //} else {
            if (strlen($_POST[$paramName]) <= 0) {
                return $defaultValue;
            } else {
                return $_POST[$paramName];
            }
            //}
        } else {
            return $defaultValue;
        }
    }

    /**
     * 写入会员cookie
     * @param int $userId
     * @param string $userName
     * @param int $hour 保存时间（单位小时），默认24小时
     */
    public static function SetUserCookie($userId, $userName, $hour = 24) {
        setcookie("UID", $userId, time() + $hour * 3600);
        $userName = urlencode($userName);
        setcookie("USERNAME", $userName, time() + $hour * 3600);
    }

    /**
     * 从cookie中取得会员id
     * @return int 会员id,没找到时返回-1
     */
    public static function GetUserId() {
        if (isset($_COOKIE["UID"])) {
            return intval($_COOKIE["UID"]);
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
     * 写入后台管理员cookie
     * @param int $manageUserId 后台帐号id
     * @param string $manageUserName 后台帐号名
     * @param int $hour 保存时间（单位小时），默认24小时
     * @param string $domain 保存路径，默认""
     */
    public static function SetManageUserCookie($manageUserId, $manageUserName, $hour = 1, $domain = "") {
        if (!empty($domain)) {
            setcookie('ICMS_MANAGE_USER_ID', $manageUserId, time() + $hour * 3600, "/", $domain);
            $manageUserName = urlencode($manageUserName);
            setcookie('ICMS_MANAGE_USER_NAME', $manageUserName, time() + $hour * 3600, "/", $domain);
        } else {
            setcookie('ICMS_MANAGE_USER_ID', $manageUserId, time() + $hour * 3600, "/");
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
            return intval($_COOKIE["ICMS_MANAGE_USER_ID"]);
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
     * @return string 返回时间
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
        return $time;
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
}
?>
