<?php

/**
 * 提供各类格式化相关的工具方法
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Tools
 * @author zhangchi
 */
class Format {

    /**
     * 处理状态
     * @param int $state 状态值
     * @param int $type 对应类型
     * @return string 返回对应状态的中文说明文字 
     */
    public static function ToState($state, $type) {
        switch ($type) {
            case 'customform':
                switch ($state) {
                    case 0:
                        return '正常';
                        break;
                    case 100:
                        return '停用';
                        break;
                }
                break;
            case 'customformfield':
                switch ($state) {
                    case 0:
                        return '正常';
                        break;
                    case 100:
                        return '停用';
                        break;
                }
                break;
            case 'customformcontent':
                switch ($state) {
                    case 0:
                        return '正常';
                        break;
                    case 100:
                        return '停用';
                        break;
                }
                break;
            case 'customformrecord':
                switch ($state) {
                    case 0:
                        return '未审';
                        break;
                    case 1:
                        return '已审';
                        break;
                    case 100:
                        return '停用';
                        break;
                }
                break;
            case 'site':
                switch ($state) {
                    case 0:
                        return '正常';
                        break;
                    case 100:
                        return '停用';
                        break;
                }
                break;
            case 'ftptype':
                switch ($state) {
                    case 0:
                        return 'HTML';
                        break;
                    case 1:
                        return '附件';
                        break;
                }
                break;
            case 'adminuserlist':
                switch ($state) {
                    case 0:
                        return '正常';
                        break;
                    case 100:
                        return '停用';
                        break;
                }
                break;
            case 'votelist':
                switch ($state) {
                    case 0:
                        return '正常';
                        break;
                    case 100:
                        return '停用';
                        break;
                }
                break;
            case 'adminusergrouplist':
                switch ($state) {
                    case 0:
                        return '正常';
                        break;
                    case 100:
                        return '停用';
                        break;
                }
                break;
            case 'adminusertasklist':
                switch ($state) {
                    case 0:
                        return '<span style="color:red">未完成</span>';
                        break;
                    case 2:
                        return '<span style="color:blue">已回复</span>';
                        break;
                    case 4:
                        return '<span style="color:red">尚未解决</span>';
                        break;
                    case 10:
                        return '<span style="color:green">完成</span>';
                        break;
                    case 100:
                        return '删除';
                        break;
                }
                break;
            case 'documentchanneltemplate':
                switch ($state) {
                    case 0:
                        return '正常';
                        break;
                    case 100:
                        return '停用';
                        break;
                }
                break;
            case 'documentnewslist':
                switch ($state) {
                    case 0:
                        return '新稿';
                        break;
                    case 1:
                        return '已编';
                        break;
                    case 2:
                        return '返工';
                        break;
                    case 11:
                        return '一审';
                        break;
                    case 12:
                        return '二审';
                        break;
                    case 13:
                        return '三审';
                        break;
                    case 14:
                        return '终审';
                        break;
                    case 20:
                        return '<span style="color:#990000">已否</span>';
                        break;
                    case 25:
                        return '已撤';
                        break;
                    case 30:
                        return '<span style="color:#006600">已发</span>';
                        break;
                    case 100:
                        return '停用';
                        break;
                }
                break;
            case 'documentthreadlist':
                switch ($state) {
                    case 0:
                        return '未审';
                        break;
                    case 1:
                        return '已编';
                        break;
                    case 10:
                        return '已审';
                        break;
                    case 30:
                        return '<span style="color:#006600">已发</span>';
                        break;
                    case 100:
                        return '<span style="color:#990000">删除</span>';
                        break;
                }
                break;
            case 'userlist':
                switch ($state) {
                    case 0:
                        return '正常';
                        break;
                    case 10:
                        return '未激活';
                        break;
                    case 20:
                        return '被推荐，未激活';
                        break;
                    case 30:
                        return '已激活，未审核';
                        break;
                    case 100:
                        return '删除';
                        break;
                }
                break;
            case 'userrolelist':
                switch ($state) {
                    case 0:
                        return '已审核';
                        break;
                    case 10:
                        return '未审核';
                        break;
                }
                break;
            case 'usergrouplist':
                switch ($state) {
                    case 0:
                        return '正常';
                        break;
                    case 100:
                        return '停用';
                        break;
                }
                break;
            case 'productlist':
                switch ($state) {
                    case 0:
                        return '新稿';
                        break;
                    case 1:
                        return '已编';
                        break;
                    case 2:
                        return '返工';
                        break;
                    case 11:
                        return '一审';
                        break;
                    case 12:
                        return '二审';
                        break;
                    case 13:
                        return '三审';
                        break;
                    case 14:
                        return '终审';
                        break;
                    case 20:
                        return '已否';
                        break;
                    case 25:
                        return '已撤';
                        break;
                    case 30:
                        return '已发';
                        break;
                    case 100:
                        return '停用';
                        break;
                }
                break;
            case 'managepostlist':
                switch ($state) {
                    case 0:
                        return '未审';
                        break;
                    case 10:
                        return '已审';
                        break;
                    case 100:
                        return '删除';
                        break;
                }
                break;
            case 'activitylist':        //活动类审核处理L   
                switch ($state) {
                    case 0:
                        return '未审';
                        break;
                    case 1:
                        return '已编';
                        break;
                    case 14:
                        return '已审';
                        break;
                    case 30:
                        return '<span style="color:#006600">已发</span>';
                        break;
                    case 100:
                        return '<span style="color:#990000">删除</span>';
                        break;
                }
                break;
            case 'activityuserlist':        //活动报名用户审核处理L   
                switch ($state) {
                    case 0:
                        return '未审';
                        break;
                    case 14:
                        return '通过';
                        break;
                    case 30:
                        return '<span style="color:#006600">已发</span>';
                        break;
                    case 100:
                        return '<span style="color:#990000">删除</span>';
                        break;
                }
                break;
            case 'sitecontentlist':
                switch ($state) {
                    case 20:
                        return '<span style="color:#990000">已否</span>';
                        break;
                    case 30:
                        return '<span style="color:#006600">已发</span>';
                        break;
                    case 100:
                        return '停用';
                        break;
                }
                break;
            case 'sitelinklist':
                switch ($state) {
                    case 0:
                        return "未启用";
                        break;
                    case 30:
                        return "<span style='color:#006600'>已启用</span>";
                        break;
                    case 40:
                        return '<span style="color:#990000">已停用</span>';
                        break;
                }
                break;
            case 'userorderlist':
                switch ($state) {
                    case 10:
                        return "未付款";
                        break;
                    case 20:
                        return "<span style='color:#006600'>已付款</span>";
                        break;
                    case 30:
                        return '<span style="color:#990000">已发货</span>';
                        break;
                    case 40:
                        return '<span style="color:#990000">交易完成</span>';
                        break;
                }
                break;
            case 'useralbumlist':
                switch ($state) {
                    case 0:
                        return "<span>未审核</span>";
                        break;
                    case 20:
                        return "<span style='color:#006600'>已审核</span>";
                        break;
                    case 40:
                        return "<span style='color:#990000'>已否</span>";
                        break;
                    case 30:
                        return "<span style='color:blue'>已编辑</span>";
                        break;
                    case 70:
                        return "<span style='color:blue'>无图片</span>";
                        break;
                    case 80:
                        return "<span style='color:blue'>需审核</span>";
                        break;
                }
                break;
            case 'forumtype':
                switch ($state) {
                    case 0:
                        return "正常访问";
                        break;
                    case 3:
                        return "用户加密";
                        break;
                    case 4:
                        return "按身份加密";
                        break;
                    case 5:
                        return "按发帖加密";
                        break;
                    case 6:
                        return "按积分加密";
                        break;
                    case 7:
                        return "按金钱加密";
                        break;
                    case 8:
                        return "按魅力加密";
                        break;
                    case 9:
                        return "按经验加密";
                        break;
                    case 10:
                        return "作为子分区，不能发帖";
                        break;
                }
                break;
            default:
                return strval($state);
                break;
        }
    }

    /**
     * 格式化下拉框的项目值为项目说明文字
     * @param int $state 状态值
     * @param int $type 对应类型
     * @return string 返回对应状态的中文说明文字 
     */
    public static function ToSelectType($state, $type) {
        switch ($type) {
            case 'adminusertasklist':
                switch ($state) {
                    case 0:
                        return '常规';
                        break;
                    case 1:
                        return '维护';
                        break;
                    case 2:
                        return '设计';
                        break;
                    case 3:
                        return '程序';
                        break;
                    case 4:
                        return '项目';
                        break;
                }
                break;
            case 'userlist':
                switch ($state) {
                    case 0:
                        return '网友';
                        break;
                    case 1:
                        return '律师';
                        break;
                    case 2:
                        return '民声站';
                        break;
                    case 3:
                        return '部门';
                        break;
                }
                break;
            default:
                return strval($state);
                break;
        }
    }

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
     * 格式化抽奖程序用的说明文字，1:幸运二等奖;2:幸运一等奖;3:幸运之星奖;4:新年幸运福星奖;5:购物消费幸运福星三等奖;6:购物消费幸运福星二等奖;7:购物消费幸运福星一等奖
     * @param int $winType 奖项类型
     * @return string 返回奖项中文名称
     */
    public static function FormatLottery($winType) {
        switch ($winType) {
            case 1:
                $string = '幸运二等奖';
                break;
            case 2:
                $string = '幸运一等奖';
                break;
            case 3:
                $string = '幸运之星奖';
                break;
            case 4:
                $string = '新年幸运福星奖';
                break;
            case 5:
                $string = '购物消费幸运福星三等奖';
                break;
            case 6:
                $string = '购物消费幸运福星二等奖';
                break;
            case 7:
                $string = '购物消费幸运福星一等奖';
                break;
            default:
                $string = "";
                break;
        }
        return $string;
    }

    /**
     * 格式化HTML标记<、>为&lt;、&gt;
     * @param string $content 要替换的内容
     * @return string 返回替换后的内容 
     */
    public static function FormatHtmlTag($string) {
        $string = str_replace('<', '&lt;', $string);
        $string = str_replace(">", "&gt;", $string);
        return $string;
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
     * 截断字符串，应作废
     * @param string $string
     * @param int $length
     * @param string $addDot
     * @param string $charset
     * @return string
     */
    public static function CutStr($string, $length, $dot = '...', $charset = 'utf-8') {
        if (strlen($string) <= $length) {
            return $string;
        }

        $string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array('&', '"', '<', '>'), $string);
        $strcut = '';
        if (strtolower($charset) == 'utf-8') {
            $n = $tn = $noc = 0;
            while ($n < strlen($string)) {
                $t = ord($string[$n]);
                if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                    $tn = 1;
                    $n++;
                    $noc++;
                } elseif (194 <= $t && $t <= 223) {
                    $tn = 2;
                    $n += 2;
                    $noc += 2;
                } elseif (224 <= $t && $t <= 239) {
                    $tn = 3;
                    $n += 3;
                    $noc += 2;
                } elseif (240 <= $t && $t <= 247) {
                    $tn = 4;
                    $n += 4;
                    $noc += 2;
                } elseif (248 <= $t && $t <= 251) {
                    $tn = 5;
                    $n += 5;
                    $noc += 2;
                } elseif ($t == 252 || $t == 253) {
                    $tn = 6;
                    $n += 6;
                    $noc += 2;
                } else {
                    $n++;
                }
                if ($noc >= $length) {
                    break;
                }
            }
            if ($noc > $length) {
                $n -= $tn;
            }
            $strcut = substr($string, 0, $n);
        } else {
            for ($i = 0; $i < $length; $i++) {
                $strcut .= ord($string[$i]) > 127 ? $string[$i] . $string[++$i] : $string[$i];
            }
        }
        $strcut = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);
        return $strcut . $dot;
    }

    /**
     * 去掉脚本标记和OBJECT标记
     * @param string $content 要处理的内容
     * @return string 处理后的内容
     */
    public static function RemoveScritpt($content) {
        $content = str_ireplace("<textarea", "〈ＴＥＸＴＡＲＥ", $content);
        $content = str_ireplace("</textarea>", "〈/ＴＥＸＴＡＲＥ〉", $content);
        $content = str_ireplace("<script", "〈ＳＣＲＩＰＴ", $content);
        $content = str_ireplace("</script>", "〈/ＳＣＲＩＰＴ〉", $content);
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
        $strDate = 0;
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
        if (function_exists(json_encode)) { //系统支持json_encode方法
            return json_encode($arrList);
        } else {//系统不支持json_encode方法
            return self::OldPhpJsonEncode($arrList);
        }
    }

    /**
     * 对老版本PHP使用的json封装方法
     * @param type $arrList
     * @return type
     */
    private static function OldPhpJsonEncode($arrList) {
        if (is_array($arrList) || is_object($arrList)) {
            $islist = is_array($arrList) && ( empty($arrList) || array_keys($arrList) === range(0, count($arrList) - 1) );
            if ($islist) {
                $json = '[' . implode(',', array_map('php_json_encode', $arrList)) . ']';
            } else {
                $items = Array();
                foreach ($arrList as $key => $value) {
                    $items[] = php_json_encode("$key") . ':' . php_json_encode($value);
                }
                $json = '{' . implode(',', $items) . '}';
            }
        } elseif (is_string($arrList)) {
            # Escape non-printable or Non-ASCII characters.
            # I also put the \\ character first, as suggested in comments on the 'addclashes' page.
            $string = '"' . addcslashes($arrList, "\\\"\n\r\t/" . chr(8) . chr(12)) . '"';
            $json = '';
            $len = strlen($string);
            # Convert UTF-8 to Hexadecimal Codepoints.
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
            # int, floats, bools, null
            $json = strtolower(var_export($arrList, true));
        }
        return $json;
    }

}

?>
