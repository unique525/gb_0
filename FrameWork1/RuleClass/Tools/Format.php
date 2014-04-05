<?php

/**
 * 提供各类格式化相关的工具方法
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Tools
 * @author zhangchi
 */
class Format {

    /**
     * 把引号(')和(")转换成(&acute;)和(&quot;)
     * @param string $content 要替换的内容
     * @return string 返回替换后的内容 
     */
    public static function FormatQuote($content) {
        $content = str_replace('"', '&quot;', $content);
        $content = str_replace("'", "&acute;", $content);
        return $content;
    }

    /**
     * 删除引号
     * @param string $content 要替换的内容
     * @return string 返回替换后的内容 
     */
    public static function RemoveQuote($content) {
        $content = str_replace('"', '', $content);
        $content = str_replace("'", "", $content);
        return $content;
    }

    /**
     * 格式化HTML标记<、>为&lt;、&gt;
     * @param string $content 要替换的内容
     * @return string 返回替换后的内容 
     */
    public static function FormatHtmlTag($content) {
        $content = str_replace('<', '&lt;', $content);
        $content = str_replace(">", "&gt;", $content);
        return $content;
    }

    /**
     * 截断字符串，支持中文的处理（utf-8编码）
     * @param string $content 要处理的内容
     * @param int $shortCount 截断的长度，支持中文的处理
     * @param string $addContent 默认为''，附加字符串 
     * @return string 处理后的内容 
     */
    public static function ToShort($content, $shortCount, $addContent = '') {
        return mb_strimwidth($content, 0, $shortCount, $addContent, 'utf-8');
    }

    /**
     * 去掉脚本标记和OBJECT标记
     * @param string $content 要处理的内容
     * @return string 处理后的内容
     */
    public static function RemoveScript($content) {
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
    public static function DateStringToSimple($dateString) {
        $date1 = explode(' ', $dateString);
        $date2 = explode('-', $date1[0]);
        return $date2[0] . $date2[1] . $date2[2];
    }

    /**
     * 分隔字符串为数组
     * @param string $content 被分隔的字符串
     * @param string $char  分隔字符
     * @return string 返回处理后的数组
     */
    public static function ToSplit($content, $char) {
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
    public static function ToMkDate($date) {
        $yearStr = ((int) substr($date, 0, 4)); //取得年份
        $monthStr = ((int) substr($date, 5, 2));  //取得月份
        $dayStr = ((int) substr($date, 8, 2));   //取得几号
        $hourStr = ((int) substr($date, 11, 2)); //取得小时
        $minuteStr = ((int) substr($date, 14, 2));  //取得分钟
        $secondStr = ((int) substr($date, 17, 2));   //取得秒
        $strDate = mktime($hourStr, $minuteStr, $secondStr, $monthStr, $dayStr, $yearStr);
        return $strDate;
    }

    /**
      /// 比较两个字符串相同的字符个数
      /// </summary>
      /// <param name="str1">循环的字符串，一般是正确答案</param>
      /// <param name="str2">被比较字符串，一般是会员回答</param>
      /// <param name="str3">被比较字符串，含有错误的字符</param>
      /// <returns></returns>
     * 
     */
    public static function CompareCount($str1, $str2, $str3) {
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
    public static function FixJsonEncode($arrList) {
        if (function_exists('json_encode')) { //系统支持json_encode方法
            if($result = json_encode($arrList)){
                return $result;
            }else{
                return json_last_error();
            }
        } else {//系统不支持json_encode方法
            return self::CustomJsonEncode($arrList);
        }
    }

    /**
     * 对老版本PHP使用的json封装方法
     * @param array $arrList
     * @return string 返回Json格式的字符串
     */
    private static function CustomJsonEncode($arrList) {
        if (is_array($arrList) || is_object($arrList)) {
            $isList = is_array($arrList) && ( empty($arrList) || array_keys($arrList) === range(0, count($arrList) - 1) );
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
                    $json .= ( $c1 > 31) ? $char : sprintf("\\u%04x", $c1);
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
                if (($c1 & 8 ) === 0) {
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

}

?>
