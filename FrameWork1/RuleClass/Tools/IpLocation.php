<?php

/**
 * ip地址位置查询类 后台
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Tools
 * @author Liujunyi edit by hy
 */
class IpLocation {

    var $fp;
    var $firstIp;  //第一条ip索引的偏移地址
    var $lastIp;   //最后一条ip索引的偏移地址
    var $totalIp;  //总ip数

    /**
     * 构造函数,初始化一些变量
     * @param string $datFile 的值为纯真IP数据库的名子,可自行修改
     */

    function ipLocation($datFile = "") {
        $datFile = ROOTPATH . DIRECTORY_SEPARATOR . "data/ipLocation.dat";
        $this->fp = fopen($datFile, 'rb');   //二制方式打开
        $this->firstIp = $this->Get4b(); //第一条ip索引的绝对偏移地址
        $this->lastIp = $this->Get4b();  //最后一条ip索引的绝对偏移地址
        $this->totalIp = ($this->lastIp - $this->firstIp) / 7; //ip总数 索引区是定长的7个字节,在此要除以7,
        register_shutdown_function(array($this, "CloseFp"));  //为了兼容php5以下版本,本类没有用析构函数,自动关闭ip库.
    }

    /**
     *  关闭ip库
     */
    function CloseFp() {
        fclose($this->fp);
    }

    /**
     * 读取4个字节并将解压成long的长模式
     * @return string 
     */
    function Get4b() {
        $str = unpack("V", fread($this->fp, 4));
        return $str[1];
    }

    /**
     * 读取重定向了的偏移地址
     * @return string 
     */
    function GetOffSet() {
        $str = unpack("V", fread($this->fp, 3) . chr(0));
        return $str[1];
    }

    /**
     * 读取ip的详细地址信息
     * @return string 
     */
    function GetStr() {
        $split = fread($this->fp, 1);
        $str = "";
        while (ord($split) != 0) {
            $str.=$split;
            $split = fread($this->fp, 1);
        }
        return $str;
    }

    /**
     * 将ip通过ip2long转成ipv4的互联网地址,再将他压缩成big-endIan字节序
     * 用来和索引区内的ip地址做比较
     * @param string $ip
     * @return string 
     */
    function IpToInt($ip) {
        return pack("N", intval(ip2long($ip)));
    }

    /**
     * 获取客户端ip地址
     * 注意:如果你想要把ip记录到服务器上,请在写库时先检查一下ip的数据是否安全
     * @return string 
     */
    function GetIp() {
        if (getenv('HTTP_CLIENT_IP')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) { //获取客户端用代理服务器访问时的真实ip 地址
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_FORWARDED')) {
            $ip = getenv('HTTP_X_FORWARDED');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
            $ip = getenv('HTTP_FORWARDED_FOR');
        } elseif (getenv('HTTP_FORWARDED')) {
            $ip = getenv('HTTP_FORWARDED');
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    /**
     * 获取地址信息
     * @return string 
     */
    function ReadAddress() {
        $now_offset = ftell($this->fp); //得到当前的指针位址
        $flag = $this->GetFlag();
        switch (ord($flag)) {
            case 0:
                $address = "";
                break;
            case 1:
            case 2:
                fseek($this->fp, $this->GetOffSet());
                $address = $this->GetStr();
                break;
            default:
                fseek($this->fp, $now_offset);
                $address = $this->GetStr();
                break;
        }
        return $address;
    }

    /**
     * 获取标志1或2
     * 用来确定地址是否重定向了.
     * @return string 
     */
    function GetFlag() {
        return fread($this->fp, 1);
    }

    /**
     * 用二分查找法在索引区内搜索ip
     * @param string $ip
     * @return string 
     */
    function SearchIp($ip) {
        $ip = gethostbyname($ip);     //将域名转成ip
        $ipOffset["ip"] = $ip;
        $ip = $this->IpToInt($ip);    //将ip转换成长整型
        $firstIp = 0;                 //搜索的上边界
        $lastIp = $this->totalIp;     //搜索的下边界
        while ($firstIp <= $lastIp) {
            $i = floor(($firstIp + $lastIp) / 2);          //计算近似中间记录 floor函数记算给定浮点数小的最大整数,说白了就是四舍五也舍
            fseek($this->fp, $this->firstIp + $i * 7);    //定位指针到中间记录
            $startIp = strrev(fread($this->fp, 4));         //读取当前索引区内的开始ip地址,并将其little-endian的字节序转换成big-endian的字节序
            if ($ip < $startIp) {
                $lastIp = $i - 1;
            } else {
                fseek($this->fp, $this->GetOffSet());
                $endIp = strrev(fread($this->fp, 4));
                if ($ip > $endIp) {
                    $firstIp = $i + 1;
                } else {
                    $ipOffset["offset"] = $this->firstIp + $i * 7;
                    break;
                }
            }
        }
        return $ipOffset;
    }

    /**
     * 获取ip地址详细信息
     * @param string $ip
     * @return string 
     */

    /**
     *
     * @param string $ip    查询的IP地址
     * @param int $returnType   城市值的返回类型 0为字符串方式, 1为按国家,省,城市的数组形式返回
     * @return string 返回内容
     */
    function GetAddress($ip, $returnType = 0) {
        $ip_offset = $this->SearchIp($ip);  //获取ip 在索引区内的绝对编移地址
        $ipOffset = $ip_offset["offset"];
        $address["ip"] = $ip_offset["ip"];
        fseek($this->fp, $ipOffset);      //定位到索引区
        $address["StartIp"] = long2ip($this->Get4b()); //索引区内的开始ip 地址
        $address_offset = $this->GetOffSet();            //获取索引区内ip在ip记录区内的偏移地址
        fseek($this->fp, $address_offset);            //定位到记录区内
        $address["EndIp"] = long2ip($this->Get4b());   //记录区内的结束ip 地址
        $flag = $this->GetFlag();                      //读取标志字节
        switch (ord($flag)) {
            case 1:  //地区1地区2都重定向
                $address_offset = $this->GetOffSet();   //读取重定向地址
                fseek($this->fp, $address_offset);     //定位指针到重定向的地址
                $flag = $this->GetFlag();               //读取标志字节
                switch (ord($flag)) {
                    case 2:  //地区1又一次重定向,
                        fseek($this->fp, $this->GetOffSet());
                        $address["area1"] = $this->GetStr();
                        fseek($this->fp, $address_offset + 4);      //跳4个字节
                        $address["area2"] = $this->ReadAddress();  //地区2有可能重定向,有可能没有
                        break;
                    default: //地区1,地区2都没有重定向
                        fseek($this->fp, $address_offset);        //定位指针到重定向的地址
                        $address["area1"] = $this->GetStr();
                        $address["area2"] = $this->ReadAddress();
                        break;
                }
                break;
            case 2: //地区1重定向 地区2没有重定向
                $address1_offset = $this->GetOffSet();   //读取重定向地址
                fseek($this->fp, $address1_offset);
                $address["area1"] = $this->GetStr();
                fseek($this->fp, $address_offset + 8);
                $address["area2"] = $this->ReadAddress();
                break;
            default: //地区1地区2都没有重定向
                fseek($this->fp, $address_offset + 4);
                $address["area1"] = $this->GetStr();
                $address["area2"] = $this->ReadAddress();
                break;
        }
        //过滤一些无用数据
        if (strpos($address["area1"], "CZ88.NET") != false) {
            $address["area1"] = "未知";
        }
        if (strpos($address["area2"], "CZ88.NET") != false) {
            $address["area2"] = " ";
        }
        //进行格式转码
        $address["area1"] = iconv("GBK", "UTF-8", $address["area1"]);
        $address["operators"] = iconv("GBK", "UTF-8", $address["area2"]);
        if ($returnType === 1) {        //按国,省市的数组形式返回城市地区  否则字符串形式返回城市地区
            $tempArr = self::FormatCityStrToArr($address["area1"]);
            $address["area0"] = $tempArr[0];
            $address["area1"] = $tempArr[1];
            $address["area2"] = $tempArr[2];
        } else {
            $address["area2"] = iconv("GBK", "UTF-8", $address["area2"]);
        }
        return $address;
    }

    /**
     *  格式化城市
     * @param string $addressStr
     * @return array 返回一维数组
     */
    function FormatCityStrToArr($addressStr) {
        $returnArr = array();
        if (!empty($addressStr)) {
            include ROOTPATH . '/inc/city.inc.php';
            foreach ($provinceArr as $val) {
                $_posf = stripos($addressStr, $val);
                if ($_posf === FALSE) {
                    //echo $val;
                } else {
                    $returnArr[1] = $val;
                    foreach ($cityArr[$val] as $cityVal) {
                        $_cityposf = stripos($addressStr, $cityVal);
                        if ($_cityposf === FALSE) {
                            
                        } else {
                            $returnArr[2] = $cityVal;
                            break;
                        }
                    }
                    if (count($returnArr) > 0) {
                        $returnArr[0] = "中国";
                    }
                    break;
                }
            }
            if (count($returnArr) <= 0) {
                foreach ($cityArr['海外'] as  $cityVal) {
                    $_cityposf = stripos($addressStr, $cityVal);
                    if ($_cityposf === FALSE) {

                    } else {
                        $returnArr[0] = "外国";
                        $returnArr[1] = $cityVal;
                        $returnArr[2] = $cityVal;
                        break;
                    }
                }
            }
        }
        return $returnArr;
    }

}

?>