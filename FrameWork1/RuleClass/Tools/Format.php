<?php

/**
 * 提供各类格式化相关的工具方法
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Tools
 * @author zhangchi
 */
class Format
{
    /**
     * 替换SQL中的引号，加强没有预处理的SQL的安全
     * @param mixed $fieldValue 要处理的字段值，或字段值数组
     * @return mixed 返回处理后的字段值，或字段值数组
     */
    public static function FormatSql($fieldValue)
    {
        if (!get_magic_quotes_gpc()) {
            if (is_array($fieldValue)) {
                foreach ($fieldValue as $key => $value) {
                    $fieldValue[$key] = addslashes($value);
                }
            } else {
                addslashes($fieldValue);
            }
        }
        return $fieldValue;
    }

    /**
     * 把引号(')和(")转换成(&acute;)和(&quot;)
     * @param string $content 要替换的内容
     * @return string 返回替换后的内容
     */
    public static function FormatQuote($content)
    {
        $content = str_replace('"', '&quot;', $content);
        $content = str_replace("'", "&acute;", $content);
        return $content;
    }

    /**
     * 删除引号
     * @param string $content 要替换的内容
     * @return string 返回替换后的内容
     */
    public static function RemoveQuote($content)
    {
        $content = str_replace('"', '', $content);
        $content = str_replace("'", "", $content);
        return $content;
    }

    /**
     * 格式化HTML标记<、>为&lt;、&gt;
     * @param string $content 要替换的内容
     * @return string 返回替换后的内容
     */
    public static function FormatHtmlTag($content)
    {
        $content = str_replace('<', '&lt;', $content);
        $content = str_replace(">", "&gt;", $content);
        return $content;
    }

    /**
     * 检查TopCount的值是否非法，非法返回null
     * @param string $content 要检查的内容，正确内容为 0,10 之类字符串
     * @return null|string 返回检查后的内容，非法返回null
     */
    public static function CheckTopCount($content){
        //如果可以直接转化为int型，则直接退出
        if(is_numeric($content)){
            return $content;
        }
        //把 0,10 这种转化为数组
        $arr = explode(",",$content);
        foreach($arr as $val){
            if(!is_numeric($val)){
                return null;
            }
        }
        return $content;
    }

    /**
     * 截断字符串，支持中文的处理（utf-8编码）
     * @param string $content 要处理的内容
     * @param int $shortCount 截断的长度，支持中文的处理
     * @param string $addContent 默认为''，附加字符串
     * @return string 处理后的内容
     */
    public static function ToShort($content, $shortCount, $addContent = '')
    {
        return mb_strimwidth($content, 0, $shortCount, $addContent, 'utf-8');
    }

    /**
     * 去掉脚本标记和OBJECT标记
     * @param string $content 要处理的内容
     * @return string 处理后的内容
     */
    public static function RemoveScript($content)
    {
        $content = str_ireplace('<textarea', "〈ＴＥＸＴＡＲＥ", $content);
        $content = str_ireplace('</textarea>', "〈/ＴＥＸＴＡＲＥ〉", $content);
        $content = str_ireplace("<script", "〈ＳＣＲＩＰＴ", $content);
        $content = str_ireplace('</script>', "〈/ＳＣＲＩＰＴ〉", $content);
        $content = str_ireplace("<object", "〈ＯＢＪＥＣＴ", $content);
        $content = str_ireplace("</object>", "〈/ＯＢＪＥＣＴ〉", $content);
        return $content;
    }

    /**
     * 格式化时间字符串为简单格式（没有-的形式）
     * @param string $dateString 要处理的字符串形式的时间 例如：2012-11-12 12:01:02
     * @return string 格式化后字符串形式的时间 例如：20121112
     */
    public static function DateStringToSimple($dateString)
    {
        $date1 = explode(' ', $dateString);
        $date2 = explode('-', $date1[0]);
        return $date2[0] . $date2[1] . $date2[2];
    }

    /**
     * 分隔字符串为数组
     * @param string $content 被分隔的字符串
     * @param string $char 分隔字符
     * @return string 返回处理后的数组
     */
    public static function ToSplit($content, $char)
    {
        if (!empty($content) && !empty($char)) {
            return explode($char, $content);
        } else {
            return null;
        }
    }

    /**
     * 把正常日期格式化为时间时间戳
     * @param string $date 要处理的时间
     * @return mixed 根据给出的参数返回 Unix 时间戳(int)。如果参数非法，本函数返回 FALSE
     */
    public static function ToMkDate($date)
    {
        $yearStr = ((int)substr($date, 0, 4)); //取得年份
        $monthStr = ((int)substr($date, 5, 2)); //取得月份
        $dayStr = ((int)substr($date, 8, 2)); //取得几号
        $hourStr = ((int)substr($date, 11, 2)); //取得小时
        $minuteStr = ((int)substr($date, 14, 2)); //取得分钟
        $secondStr = ((int)substr($date, 17, 2)); //取得秒
        $strDate = mktime($hourStr, $minuteStr, $secondStr, $monthStr, $dayStr, $yearStr);
        return $strDate;
    }

    /**
     * /// 比较两个字符串相同的字符个数
     * /// </summary>
     * /// <param name="str1">循环的字符串，一般是正确答案</param>
     * /// <param name="str2">被比较字符串，一般是会员回答</param>
     * /// <param name="str3">被比较字符串，含有错误的字符</param>
     * /// <returns></returns>
     *
     */
    public static function CompareCount($str1, $str2, $str3)
    {
        $count = 0; //默认匹配的个数为0
        for ($i = 0; $i < strlen($str1); $i++) {
            $arrStr1 = str_split($str1);
            $arrStr2 = str_split($str2);
            $arrStr3 = str_split($str3);
            if ($arrStr1[$i] !== ($arrStr3[$i])) { //题目与答案不匹配，才是应该修改的错误
                if (strlen($str2) > $i) {
                    if ($arrStr1[$i] === $arrStr2[$i]) { //会员回答与答案一致
                        $count++;
                    }
                }
            }
        }
        return $count;
    }

    /**
     * 支持所有PHP版本的JSON_ENCODE
     * @param array $arrList 要处理的数组
     * @return string 返回Json格式的字符串
     */
    public static function FixJsonEncode($arrList)
    {
        if (function_exists('json_encode')) { //系统支持json_encode方法
            if ($result = json_encode($arrList)) {
                return $result;
            } else {
                return json_last_error();
            }
        } else { //系统不支持json_encode方法
            return self::CustomJsonEncode($arrList);
        }
    }

    /**
     * 对老版本PHP使用的json封装方法
     * @param array $arrList
     * @return string 返回Json格式的字符串
     */
    private static function CustomJsonEncode($arrList)
    {
        if (is_array($arrList) || is_object($arrList)) {
            $isList = is_array($arrList) && (empty($arrList) || array_keys($arrList) === range(0, count($arrList) - 1));
            if ($isList) {
                $json = '[' . implode(',', array_map('php_json_encode', $arrList)) . ']';
            } else {
                $items = Array();
                foreach ($arrList as $key => $value) {
                    $items[] = self::CustomJsonEncode("$key") . ':' . self::CustomJsonEncode($value);
                }
                $json = '{' . implode(',', $items) . '}';
            }
        } elseif (is_string($arrList)) {
            $string = '"' . addcslashes($arrList, "\\\"\n\r\t/" . chr(8) . chr(12)) . '"';
            $json = '';
            $len = strlen($string);
            for ($i = 0; $i < $len; $i++) {
                $char = $string[$i];
                $c1 = ord($char);
                # Single byte;
                if ($c1 < 128) {
                    $json .= ($c1 > 31) ? $char : sprintf("\\u%04x", $c1);
                    continue;
                }
                # Double byte
                $c2 = ord($string[++$i]);
                if (($c1 & 32) === 0) {
                    $json .= sprintf("\\u%04x", ($c1 - 192) * 64 + $c2 - 128);
                    continue;
                }
                # Triple
                $c3 = ord($string[++$i]);
                if (($c1 & 16) === 0) {
                    $json .= sprintf("\\u%04x", (($c1 - 224) << 12) + (($c2 - 128) << 6) + ($c3 - 128));
                    continue;
                }
                # Quadruple
                $c4 = ord($string[++$i]);
                if (($c1 & 8) === 0) {
                    $u = (($c1 & 15) << 2) + (($c2 >> 4) & 3) - 1;
                    $w1 = (54 << 10) + ($u << 6) + (($c2 & 15) << 2) + (($c3 >> 4) & 3);
                    $w2 = (55 << 10) + (($c3 & 15) << 6) + ($c4 - 128);
                    $json .= sprintf("\\u%04x\\u%04x", $w1, $w2);
                }
            }
        } else {
            # int, floats, bool, null
            $json = strtolower(var_export($arrList, true));
        }
        return $json;
    }


    /**
     * 转换上传文件路径为html标签代码
     * @param string $filePath 文件路径
     * @param string $fileExtension 文件扩展名
     * @param int $uploadFileId 上传文件id
     * @param string $sourceFileName 源文件名称
     * @return string
     */
    public static function FormatUploadFileToHtml($filePath, $fileExtension, $uploadFileId, $sourceFileName)
    {
        $fileExtension = strtolower($fileExtension);
        switch ($fileExtension) {
            case "jpg":
                $result = '<img alt="' . $uploadFileId . '" src="' . $filePath . '" />';
                break;
            case "gif":
                $result = '<img alt="' . $uploadFileId . '" src="' . $filePath . '" />';
                break;
            case "png":
                $result = '<img alt="' . $uploadFileId . '" src="' . $filePath . '" />';
                break;
            case "bmp":
                $result = '<img alt="' . $uploadFileId . '" src="' . $filePath . '" />';
                break;
            case "swf":
                $result = '<embed src="' . $filePath . '" id="' . $uploadFileId . '_SWF" width="200" height="100" type="application/x-shockwave-flash" pluginspage="http://get.adobe.com/cn/flashplayer/"></embed>';
                break;

            case "flv":
                $url = '';
                $url .= '<script type="text/javascript" src="' . WEBAPP_DOMAIN . '/common/js/jwplayer.js"></script>';
                $url .= '<div id="mediaspace"></div>';
                $url .= '<script type="text/javascript">';
                $url .= 'jwplayer("mediaspace").setup({';
                $url .= '"flashplayer": "' . WEBAPP_DOMAIN . '/common/js/jwplayer.swf",';
                $url .= 'type:"http",';
                $url .= '"file": "' . $filePath . '",';
                $url .= '"image": "",';
                $url .= '"streamer": "start",';
                $url .= '"autostart": "true",';
                $url .= '"controlbar": "bottom",';
                $url .= '"width": "500",';
                $url .= '"height": "430"';
                $url .= '});';
                $url .= '</script>';

                $result = $url;
                break;
            case "mp4":
                $url = '';
                $url .= '<script type="text/javascript" src="' . WEBAPP_DOMAIN . '/common/js/jwplayer.js"></script>';
                $url .= '<div id="mediaspace"></div>';
                $url .= '<script type="text/javascript">';
                $url .= 'jwplayer("mediaspace").setup({';
                $url .= '"flashplayer": "' . WEBAPP_DOMAIN . '/common/js/jwplayer.swf",';
                $url .= 'type:"http",';
                $url .= '"file": "' . $filePath . '",';
                $url .= '"image": "",';
                $url .= '"streamer": "start",';
                $url .= '"autostart": "true",';
                $url .= '"controlbar": "bottom",';
                $url .= '"width": "500",';
                $url .= '"height": "430"';
                $url .= '});';
                $url .= '</script>';
                $result = $url;
                break;
            case "wmv":
                $result = '<object width="640" height="480" classid="clsid:6bf52a52-394a-11d3-b153-00c04f79faa6" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=5,1,52,701"><param name="url" value="' . $filePath . '" /><embed width="640" height="480" type="application/x-mplayer2" src="' . $filePath . '" /></object>';
                break;
            default:
                $result = '<a href="' . $filePath . '" target="_blank">' . $sourceFileName . '</a>';
                break;
        }
        return $result;
    }

    /**
     * @去除XSS（跨站脚本攻击）的函数
     * @par $val 字符串参数，可能包含恶意的脚本代码如<script language="javascript">alert("hello world");</script>
     * @return
     */


    /**
     * 去除XSS（跨站脚本攻击）的函数
     * @param string $val 要处理的字符串，可能包含恶意的脚本代码如<script language="javascript">alert("hello world");</script>
     * @return string 处理后的字符串
     */
    public static function RemoveXSS($val) {
        // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
        // this prevents some character re-spacing such as <java\0script>
        // note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
        $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);

        // straight replacements, the user should never need these since they're normal characters
        // this prevents like <IMG SRC=@javascript:alert('XSS')>
        $search = 'abcdefghijklmnopqrstuvwxyz';
        $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $search .= '1234567890!@#$%^&*()';
        $search .= '~`";:?+/={}[]-_\'\\';
        for ($i = 0; $i < strlen($search); $i++) {
            // ;? matches the ;, which is optional
            // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars

            // @ @ search for the hex values
            $val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;
            // @ @ 0{0,7} matches '0' zero to seven times
            $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
        }

        // now the only remaining whitespace attacks are \t, \n, and \r
        $ra1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
        $ra2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
        $ra = array_merge($ra1, $ra2);

        $found = true; // keep replacing as long as the previous round replaced something
        while ($found == true) {
            $val_before = $val;
            for ($i = 0; $i < sizeof($ra); $i++) {
                $pattern = '/';
                for ($j = 0; $j < strlen($ra[$i]); $j++) {
                    if ($j > 0) {
                        $pattern .= '(';
                        $pattern .= '(&#[xX]0{0,8}([9ab]);)';
                        $pattern .= '|';
                        $pattern .= '|(&#0{0,8}([9|10|13]);)';
                        $pattern .= ')*';
                    }
                    $pattern .= $ra[$i][$j];
                }
                $pattern .= '/i';
                $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag
                $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
                if ($val_before == $val) {
                    // no replacements were made, so exit the loop
                    $found = false;
                }
            }
        }
        return $val;
    }
}

?>
