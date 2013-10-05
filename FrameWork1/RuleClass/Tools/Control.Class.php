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
                return getInt($_GET[$paramName]);
            } else if (is_array($_GET[$paramName])) {
                return $_GET[$paramName];
            } else {
                return getStr($_GET[$paramName]);
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
     * 删除用户COOKIE
     */
    public static function DelUserCookie() {
        setcookie("UID", 0, time() - 1);
        setcookie("USERNAME", "", time() - 1);
    }

    /**
     * 写入后台帐号cookie
     * @param int $adminUserId 后台帐号id
     * @param string $adminUserName 后台帐号名
     * @param int $hour 保存时间（单位小时），默认24小时
     * @param string $domain 保存路径，默认""
     */
    public static function SetAdminUserCookie($adminUserId, $adminUserName, $hour = 24, $domain = "") {
        if (!empty($domain)) {
            setcookie("ICMSADMINUSERID", $adminUserId, time() + $hour * 3600, "/", $domain);
            $adminUserName = urlencode($adminUserName);
            setcookie("ICMSADMINUSERNAME", $adminUserName, time() + $hour * 3600, "/", $domain);
        } else {
            setcookie("ICMSADMINUSERID", $adminUserId, time() + $hour * 3600, "/");
            $adminUserName = urlencode($adminUserName);
            setcookie("ICMSADMINUSERNAME", $adminUserName, time() + $hour * 3600, "/");
        }
    }

    /**
     * get admin user id by cookie
     * @return int 返回后台帐号用户id
     */
    public static function GetAdminUserId() {
        if (isset($_COOKIE["ICMSADMINUSERID"])) {
            return intval($_COOKIE["ICMSADMINUSERID"]);
        } else {
            return -1;
        }
    }

    /**
     * get admin user name by cookie
     * @return string 返回后台帐号用户名
     */
    public static function GetAdminUserName() {
        if (isset($_COOKIE["ICMSADMINUSERNAME"])) {
            return urldecode($_COOKIE["ICMSADMINUSERNAME"]);
        } else {
            return "";
        }
    }

    /**
     * 删除管理员COOKIE
     */
    public static function DelAdminUserCookie() {
        setcookie("ICMSADMINUSERID", 0, time() - 1);
        setcookie("ICMSADMINUSERNAME", "", time() - 1);
        setcookie("UID", 0, time() - 1);
        setcookie("USERNAME", "", time() - 1);
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
     * 设置购物车cookieid
     */
    public static function SetCookieID() {
        $cookieid = md5(uniqid(rand()));
        setcookie("COOKIEID", $cookieid, time() + 720 * 3600);
    }

    /**
     * 获取购物车cookieid
     * @return string 返回 cookieid的cookie值 
     */
    public static function GetCookieId() {
        if (isset($_COOKIE["COOKIEID"])) {
            return $_COOKIE["COOKIEID"];
        } else {
            return "";
        }
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
            $browserVersion = $browserVersion;
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
            $browserVersion = $browserVersion;
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
            $browserVersion = $browserVersion;
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
        $os = false;
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
        } else if (preg_match('/win/i', $strAgent) && preg_match('/nt 6.0/i', $strAgent)) {
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
     * @param <type> $string
     * @return <type>
     */
    public static function isUTF8($string) {
        $r_is_utf8 = preg_match('%^(?:
            [\x09\x0A\x0D\x20-\x7E]            # ASCII
            | [\xC2-\xDF][\x80-\xBF]            # non-overlong 2-byte
            |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
            | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
            |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
            |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
            | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
            |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
            )*$%xs', $string);
        return $r_is_utf8;
    }

    /**
     * 计算时间
     * @return float 返回时间 
     */
    public static function GetMicroTime() {
        list($usec, $sec) = explode(" ", microtime());
        return ((float) $usec + (float) $sec);
    }

    /**
     * 判断是否为移动浏览器
     * @return boolean 是否为移动浏览器 
     */
    public static function IsMobile() {
        global $_SERVER;
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $mobileAgents = Array("240x320", "acer", "acoon", "acs-", "abacho", "ahong", "airness", "alcatel", "amoi", "android", "anywhereyougo.com", "applewebkit/525", "applewebkit/532", "asus", "audio", "au-mic", "avantogo", "becker", "benq", "bilbo", "bird", "blackberry", "blazer", "bleu", "cdm-", "compal", "coolpad", "danger", "dbtel", "dopod", "elaine", "eric", "etouch", "fly ", "fly_", "fly-", "go.web", "goodaccess", "gradiente", "grundig", "haier", "hedy", "hitachi", "htc", "huawei", "hutchison", "inno", "ipad", "ipaq", "ipod", "jbrowser", "kddi", "kgt", "kwc", "lenovo", "lg ", "lg2", "lg3", "lg4", "lg5", "lg7", "lg8", "lg9", "lg-", "lge-", "lge9", "longcos", "maemo", "mercator", "meridian", "micromax", "midp", "mini", "mitsu", "mmm", "mmp", "mobi", "mot-", "moto", "nec-", "netfront", "newgen", "nexian", "nf-browser", "nintendo", "nitro", "nokia", "nook", "novarra", "obigo", "palm", "panasonic", "pantech", "philips", "phone", "pg-", "playstation", "pocket", "pt-", "qc-", "qtek", "rover", "sagem", "sama", "samu", "sanyo", "samsung", "sch-", "scooter", "sec-", "sendo", "sgh-", "sharp", "siemens", "sie-", "softbank", "sony", "spice", "sprint", "spv", "symbian", "tablet", "talkabout", "tcl-", "teleca", "telit", "tianyu", "tim-", "toshiba", "tsm", "up.browser", "utec", "utstar", "verykool", "virgin", "vk-", "voda", "voxtel", "vx", "wap", "wellco", "wig browser", "wii", "windows ce", "wireless", "xda", "xde", "zte");
        $isMobile = false;
        foreach ($mobileAgents as $device) {
            if (stristr($userAgent, $device)) {
                $isMobile = true;
                break;
            }
            return $isMobile;
        }
    }

}

function getInt($number) {
    return intval($number);
}

function getStr($string) {
    //if (!get_magic_quotes_gpc()) {
    //    $string = addslashes($string);
    //$string = mysql_real_escape_string($string);
    //}
    $string = stripslashes($string);
    return $string;
}

?>
