<?php

/**
 * 提供FTP相关的工具方法
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Tools
 * @author zhangchi
 */
class Ftp {
    /*     * **
      public static function Stest() {
      $result = "ok";
      return $result;
      }
     * **** */

    //FTP上传 Sftp_Upload('./html/Rules/abc', ROOTPATH.'/Rules/Tools/source.txt');
//    public static function Sftp_Upload($ftp_stream, $dest, $source) {
//        //if(!@is_readable($source)){
//        //    return '$source file is not';
//        //}
//        $_deal_dir = TRUE;
//
//        $_dirname = dirname($dest);
//        $_filename = basename($dest);
//        $ftpconnid = $ftp_stream;
//        if (empty($dest)) {
//            return Language::Load('siteftpment', 60);    //目标服务器路径为空
//        }
//        $_ischdir = self::Sftp_Chdir($ftpconnid, $_dirname);
//        if (!$_ischdir) { //切换目录不成功
//            $_ismkdir = self::Sftp_Mkdir($ftpconnid, $_dirname);
//            if ($_ismkdir) {
//                self::Sftp_Chmod($ftpconnid, 0777, $_dirname);
//                $_ischdir = self::Sftp_Chdir($ftpconnid, $_dirname);
//                if (!$_ischdir) {
//                    echo Language::Load('siteftpment', 59) . $_dirname;
//                    $_deal_dir = FALSE;
//                    return 0;
//                }
//            } else {
//                echo Language::Load('siteftpment', 58) . $_dirname;
//                $_deal_dir = FALSE;
//                return 0;
//            }
//        }
//        if ($_deal_dir) {
//            $_isput = self::Sftp_Put($ftpconnid, $_filename, $source, FTP_BINARY);
//            if ($_isput) {
//                self::Sftp_Close($ftpconnid);
//                return 1;
//            }
//        }
//        return 0;
//    }

    /**
     * 单个文件传输
     * @param array $ftpInfo FTP连接信息
     * @param string $destPath 目标路径
     * @param string $sourcePath 来源路径
     * @param string $sourceContent 要传输的内容
     * @return int 返回传输结果 -2:连接失败  -5:连接失败 -1:登录失败 1:上传成功
     */
    public static function Upload($ftpInfo, $destPath = null, $sourcePath = null, $sourceContent = null) {
        //进行连接
        $ftpConn = self::Connect($ftpInfo);
        if (!$ftpConn) { //连接失败
            echo Language::Load('siteftpment', 19);
            $result = -2;       //连接失败
        } else { //连接成功
            //判断ftp_info
            if (isset($ftpInfo['User']) && isset($ftpInfo['Pass']) && isset($ftpInfo['Pasv']) && isset($ftpInfo['Path'])) {
                $ftpUser = $ftpInfo['User'];
                $ftpPass = $ftpInfo['Pass'];
                $ftpPass = str_replace(array("\n", "\r"), array('', ''), $ftpPass);

                $ftpPasv = intval($ftpInfo['Pasv']);
                $ftpPath = $ftpInfo['Path'];
                $ftpPath = self::Sftp_Clear($ftpPath);
                //开始登录
                $ftpIsLogined = @ftp_login($ftpConn, $ftpUser, $ftpPass);
                if ($ftpIsLogined) {//登录成功
                    if ($ftpPasv > 0) { //被动模式
                        @ftp_pasv($ftpConn, FALSE);
                    } else { //被动模式
                        @ftp_pasv($ftpConn, TRUE);
                    }
                    self::ChDirOrMkDir($ftpConn, $ftpPath);
                    $isPut = 0;
                    $openFtpLog = 0;    //是否开启日志
                    $ftpId = intval($ftpInfo['FtpID']);
                    $siteId = intval($ftpInfo['SiteID']);

                    //开始传输
                    $destPath = self::Sftp_Clear($destPath);
                    $sourcePath = self::Sftp_Clear($sourcePath);
                    $isPut = self::FtpUpload($ftpConn, $ftpPath, $destPath, $sourcePath, $sourceContent, $openFtpLog, $ftpLogData, $ftpId, $siteId);
                    if ($isPut) {
                        $result = 1;           //发布成功
                    } else {
                        $result = 0;        //发布失败
                    }
                } else { //登录失败
                    echo Language::Load('siteftpment', 68);
                    $result = -1;       //登录失败
                }
            }
            @ftp_close($ftpConn);
        }
        return $result;
    }

    /**
     * 多文件队列传输
     * @param array $ftpInfo FTP连接信息
     * @param array $arrUpload FTP队列信息
     * @param int $openFtpLog 是否开启了记录FTP传输日志的功能，默认0未开启，1为开启
     * @param FtpLogData $ftpLogData FTP日志对象
     * @return type -5:连接失败 -2:连接失败 -1:登录失败 1:上传成功 
     */
    public static function UploadQueue($ftpInfo, &$arrUpload = null, $openFtpLog = 0, FtpLogData $ftpLogData = null) {
        $time1 = date("Y-m-d H:i:s", time());
        $result = 0;
        if (empty($arrUpload)) {
            $result = -5;           //上传数组为空
            if ($openFtpLog == 1) {
                $ftpLogData->Insert("0", "", "", "error:" . $result);
            }
            return $result;
        }

        if (!isset($ftpInfo['User']) || !isset($ftpInfo['Pass']) || !isset($ftpInfo['Pasv']) || !isset($ftpInfo['Path']) || !isset($ftpInfo['FtpID'])) {
            $result = -4;           //FTP配置信息为空
            if ($openFtpLog == 1) {
                $ftpLogData->Insert("0", "", "", "error:" . $result . ";ftpinfo:" . implode("|", $ftpInfo));
            }
            return $result;
        }
        //if ($openFtpLog == 1) {
        //    $timeStart = Control::GetMicroTime();
        //}
        $ftpConn = self::Connect($ftpInfo);
        //if ($openFtpLog == 1) {
        //    $timeEnd = Control::GetMicroTime();
        //    $time = $timeEnd - $timeStart;
        //    $ftpLogData->Insert("0", "", "", "time:" . $time . ";connect to ftp;ftpinfo:".implode("|",$ftpInfo));
        //}
        if (!$ftpConn) { //连接失败
            //echo Language::Load('siteftpment', 19);
            $result = -2;           //连接失败
            if ($openFtpLog == 1) {
                $ftpLogData->Insert("0", "", "", "error:" . $result . ",ftpid:" . $ftpInfo['FtpID']);
            }
            return $result;
        }

        $ftpUser = $ftpInfo['User'];
        $ftpPass = $ftpInfo['Pass'];
        $ftpPass = str_replace(array("\n", "\r"), array('', ''), $ftpPass);

        $ftpPasv = intval($ftpInfo['Pasv']);
        $ftpPath = $ftpInfo['Path'];
        $ftpPath = self::Sftp_Clear($ftpPath);
        //开始登录
        //if ($openFtpLog == 1) {
        //    $timeStart = Control::GetMicroTime();
        //}
        $ftpIsLogined = @ftp_login($ftpConn, $ftpUser, $ftpPass);
        //if ($openFtpLog == 1) {
        //    $timeEnd = Control::GetMicroTime();
        //    $time = $timeEnd - $timeStart;
        //    $ftpLogData->Insert("0", "", "", "time:" . $time . ";login to ftp;ftpinfo:".implode("|",$ftpInfo));
        //}
        if ($ftpIsLogined) {     //登录成功
            if ($ftpPasv > 0) { //主动模式
                @ftp_pasv($ftpConn, FALSE);
            } else { //被动模式
                @ftp_pasv($ftpConn, TRUE);
            }

            self::ChDirOrMkDir($ftpConn, $ftpPath);

            //开始传输
            for ($i = 0; $i < count($arrUpload); $i++) {
                $destPath = self::Sftp_Clear($arrUpload[$i]["dest"]);
                $sourcePath = self::Sftp_Clear($arrUpload[$i]["source"]);
                $isUplod = self::FtpUpload($ftpConn, $ftpPath, $destPath, $sourcePath, $arrUpload[$i]["content"], $openFtpLog, $ftpLogData, $ftpInfo['FtpID'], $ftpInfo['SiteID']);
                if ($isUplod) {
                    $arrUpload[$i]["result"] = 1;
                } else {
                    $arrUpload[$i]["result"] = 0;
                }
            }
            $result = 1;    //上传成功
            @ftp_close($ftpConn);
        } else { //登录失败
            echo Language::Load('siteftpment', 68);
            $result = -1;       //登录失败
            if ($openFtpLog == 1) {
                $ftpLogData->Insert("0", "", "", "error:" . $result . ",ftpid:" . $ftpInfo['FtpID']);
            }
            @ftp_close($ftpConn);
            return $result;
        }

        $time2 = date("Y-m-d H:i:s", time());
        $adminUserId = Control::GetManageUserId();
        if ($adminUserId == 1) {
            $ftpLogData->Insert("0", "", "", "time:" . $time1 ."|". $time2. ";ftp;ftpinfo:" . implode("|", $ftpInfo));
        }
        return $result;
    }

    /**
     * FTP内容传输
     * @param resource $ftpConn FTP连接信息
     * @param string $ftpPath FTP的远程路径
     * @param string $destPath 目标文件夹路径
     * @param string $sourcePath 来源文件夹路径
     * @param string $sourceContent 来源文件内容
     * @param int $openFtpLog 是否开启了记录FTP传输日志的功能，默认0未开启，1为开启
     * @param FtpLogData $ftpLogData FTP日志对象
     * @param int $ftpId FTP表记录的id
     * @param int $siteId FTP所在的站点id
     * @return int 传输结果
     */
    private static function FtpUpload($ftpConn, $ftpPath, $destPath = null, $sourcePath = null, $sourceContent = null, $openFtpLog = 0, FtpLogData $ftpLogData = null, $ftpId = 0, $siteId = 0) {
        $result = 0;
        //$timeStart = Control::GetMicroTime();
        if (strlen($sourceContent) > 0) {     //发布内容上传
            $destPath = $ftpPath . "/" . $destPath;     //要生成的目标文件地址
            $sourceFileContent = $sourceContent;
            if (!empty($destPath)) {  //目标服务器路径不能为空
                $dirName = dirname($destPath);        //目标文件地址
                $fileName = basename($destPath);      //目标文件名
                self::ChDirOrMkDir($ftpConn, $dirName);
                $microTime = floor(microtime() * 1000);     //加入随机临时目录
                $rand = rand("111", "9999");
                $randStr = $microTime . $rand;
                $fileDir = 'data' . DIRECTORY_SEPARATOR . 'temphtml' . DIRECTORY_SEPARATOR . $siteId;
                $tempDir = $fileDir . DIRECTORY_SEPARATOR . $randStr;
                $sourcePath = PHYSICAL_PATH . DIRECTORY_SEPARATOR . $tempDir . DIRECTORY_SEPARATOR . $fileName;  //源文件绝对路径        //源服务器路径

                File::Write($sourcePath, $sourceFileContent);
                $result = @ftp_put($ftpConn, $fileName, $sourcePath, FTP_BINARY);
                File::DelDir(RELATIVE_PATH . DIRECTORY_SEPARATOR . $fileDir);
                //@unlink($source_path);
            }
        } else {            //附件文件上传
            $destPath = $ftpPath . "/" . $destPath;             //目标文件地址
            $sourcePath = realpath(RELATIVE_PATH . $sourcePath);       //源文件路径
            if (!empty($destPath)) {  //目标服务器路径不能为空
                $dirName = dirname($destPath);
                $fileName = basename($destPath);
                self::ChDirOrMkDir($ftpConn, $dirName);
                $result = @ftp_put($ftpConn, $fileName, $sourcePath, FTP_BINARY);
            }
        }

        //$timeEnd = Control::GetMicroTime();
        //$time = $timeEnd - $timeStart;

        if ($openFtpLog == 1) {
            //$ftpLogData->Insert($ftpId, $destPath, $sourcePath, "time:" . $time . ";result:" . $result);
        }

        return $result;
    }

    /**
     * 连接到FTP，打开端口
     * @param array $ftpInfo
     * @return resource FTP连接的信息
     */
    public static function Connect($ftpInfo) {
        $ftpConn = null;
        @set_time_limit(0);
        //取得ftp配置信息
        $connectFunc = function_exists('ftp_ssl_connect') ? 'ftp_ssl_connect' : 'ftp_connect';
        if ($connectFunc == 'ftp_connect' && !function_exists('ftp_connect')) { //判断PHP是否支持FTP
            return "FTP NOT SUPPORTED";
        }

        //判断ftp_info
        if (isset($ftpInfo['Host']) && isset($ftpInfo['Port'])) {
            $ftpHost = $ftpInfo['Host'];

            $ftpPort = 21;

            if (isset($ftpInfo['Port'])) {
                $ftpPort = intval($ftpInfo['Port']);
            }

            $ftpTimeout = 90;
            if (isset($ftpInfo['Timeout'])) {
                $ftpTimeout = intval($ftpInfo['Timeout']);
            }

            $ftpConn = @$connectFunc($ftpHost, $ftpPort, $ftpTimeout);
            if ($ftpTimeout >= 0 && function_exists('ftp_set_option')) { //设置超时时间
                @ftp_set_option($ftpConn, FTP_TIMEOUT_SEC, $ftpTimeout);
            }
        }
        return $ftpConn;
    }

    /**
     * ftp测试连接
     * @param <type> $csftp
     * @return <type>
     */
    public static function Cms_connect($csftp) {
        $res = 0;
        $func = function_exists('ftp_ssl_connect') ? 'ftp_ssl_connect' : 'ftp_connect';
        if ($func == 'ftp_connect' && !function_exists('ftp_connect')) {
            return "FTP NOT SUPPORTED";
        }
        if ($_ftp_conn = @$func($csftp['Host'], $csftp['Port'], 20)) {
            @ftp_set_option($_ftp_conn, FTP_TIMEOUT_SEC, $csftp['Timeout']);
            if (self::Sftp_Login($_ftp_conn, $csftp['User'], $csftp['Pass'])) {
                self::Sftp_Chdir($_ftp_conn, $csftp['Path']);
                if (self::Sftp_Chdir($_ftp_conn, $csftp['Path'])) {
                    $res = $_ftp_conn;
                } else {
                    $res = "not Sftp_Chdir";
                }
            } else {
                $res = "not Sftp_Login";
            }
        } else {
            $res = $func;
        }
        @ftp_close($_ftp_conn);
        return $res;
    }

//    public static function Sftp_Pasv($_ftp_conn, $pasv) {
//        $pasv = intval($pasv);
//        $_return = @ftp_pasv($_ftp_conn, $pasv);
//        if (!$_return) {          //被动模式设置失败 ftp_pasv
//            //die(Language::Load('siteftpment', 69)); //被动模式设置失败 ftp_pasv
//            echo Language::Load('siteftpment', 69);
//            return 0;
//        } else {
//            return $_return;
//        }
//    }

    public static function Sftp_Login($_ftp_conn, $username, $password) {
        $password = str_replace(array("\n", "\r"), array('', ''), $password);
        $_return = @ftp_login($_ftp_conn, $username, $password);
        if ($_return) {          //登录FTP服务器失败 ftp_login
            return $_return;
        } else {
            echo (Language::Load('siteftpment', 68)); //die("no|".$username."|".$password."|".$_ftp_conn);     //登录FTP服务器失败 ftp_login
            return 0;
        }
    }

    private static function ChDirOrMkDir($_ftp_conn, $_ftp_path) {
        $_return = @ftp_chdir($_ftp_conn, $_ftp_path);
        if (!$_return) {        //切换FTP上传目录失败 ftp_chdir
            $_ismkdir = self::Sftp_Mkdir($_ftp_conn, $_ftp_path);
            if (!$_ismkdir) {
                die(Language::Load('siteftpment', 59).$_ftp_path);
            } else {
                $_return = @ftp_chdir($_ftp_conn, $_ftp_path);
                //return $_return;
            }
        } else {
            //return $_return;
        }
    }

    public static function Sftp_Chdir($_ftp_conn, $directory) {
        $_return = @ftp_chdir($_ftp_conn, $directory);
        if (!$_return) {        //切换FTP上传目录失败 ftp_chdir
            //die(Language::Load('siteftpment', 59));         //切换FTP上传目录失败 ftp_chdir
            echo Language::Load('siteftpment', 59);
            return FALSE;
        } else {
            return $_return;
        }
    }

//    public static function Set_option($_ftp_conn, $cmd, $value) {
//        if (function_exists('ftp_set_option')) {
//            if (($cmd == "FTP_TIMEOUT_SEC" || $cmd == "FTP_AUTOSEEK") && ($value > 0)) {
//                $_return = @ftp_set_option($_ftp_conn, $cmd, $value);
//                return $_return;
//                //if (!$_return) {        //设置FTP运行时选项失败 ftp_set_option
//                //die(Language::Load('siteftpment', 66));         //设置FTP运行时选项失败 ftp_set_option
//                //die(Language::Load('siteftpment', 66));
//                //return 0;
//                //} else {
//                //return $_return;
//                //}
//            }
//        } else {
//            echo Language::Load('siteftpment', 67);           //@ftp_set_option函数不存在 function_exists
//            return 0;
//            //die(Language::Load('siteftpment', 67));         //@ftp_set_option函数不存在 function_exists
//        }
//    }

    public static function Sftp_Mkdir($_ftp_conn, $directory) {
        $epath = explode("/", $directory);
        $dir = '';
        $comma = '';
        foreach ($epath as $path) {
            $dir .= $comma . $path;
            $comma = "/";
            $_return = @ftp_mkdir($_ftp_conn, $dir);
            //self::Sftp_Chmod($_ftp_conn, 0777, $tmpdir);
        }
        return $_return;
    }

//    public static function Sftp_Chmod($_ftp_conn, $mode, $filename) {
//        $filename = self::Sftp_Clear($filename);
//        if (function_exists('ftp_chmod')) {
//            $_return = @ftp_chmod($_ftp_conn, $mode, $filename);
//            if ($_return === FALSE) {        //文件权限设置失败 chmod
//                //die(Language::Load('siteftpment', 62));         //文件权限设置失败 chmod
//                echo Language::Load('siteftpment', 62);
//                return FALSE;
//            } else {
//                return $_return;
//            }
//        } else {
//            return @ftp_site($_ftp_conn, 'CHMOD ' . $mode . ' ' . $filename);
//        }
//    }
//    public static function Sftp_Put($_ftp_conn, $remote_file, $local_file, $mode = FTP_BINARY, $startpos = 0) {
//        $_return = @ftp_put($_ftp_conn, $remote_file, $local_file, FTP_BINARY);
//        if (!$_return) {      //文件上传到服务器失败 ftp_put
//            //die(Language::Load('siteftpment', 63));         //文件上传到服务器失败 ftp_put
//            echo Language::Load('siteftpment', 63);
//            return 0;
//        } else {
//            return 1;
//        }
//    }
//    public static function Sftp_Site($_ftp_conn, $cmd) {
//        $cmd = self::Sftp_Clear($cmd);
//        $_return = @ftp_site($_ftp_conn, $cmd);
//        if (!$_return) {        //发送command指定的命令失败 site
//            //die(Language::Load('siteftpment', 61)); //发送command指定的命令失败 site
//            echo Language::Load('siteftpment', 61);
//            return 0;
//        } else {
//            return $_return;
//        }
//    }

    /**
     * ftp删除文件
     * @param <type> $ftp_info
     * @param <type> $dest
     * @return <type>
     */
    public static function Sftp_Delete($ftp_info, $dest) {
        //进行连接
        $result = 0;
        $_ftp_conn = self::Connect($ftp_info);
        if (!$_ftp_conn) { //连接失败
            echo Language::Load('siteftpment', 19);
        } else { //连接成功
            //判断ftp_info
            if (isset($ftp_info['User']) && isset($ftp_info['Pass']) && isset($ftp_info['Pasv']) && isset($ftp_info['Path'])) {
                $_ftp_user = $ftp_info['User'];
                $_ftp_pass = $ftp_info['Pass'];
                $_ftp_pass = str_replace(array("\n", "\r"), array('', ''), $_ftp_pass);

                $_ftp_pasv = intval($ftp_info['Pasv']);
                $_ftp_path = $ftp_info['Path'];
                $_ftp_path = self::Sftp_Clear($_ftp_path);
                //开始登录
                $_ftp_is_logined = @ftp_login($_ftp_conn, $_ftp_user, $_ftp_pass);
                if ($_ftp_is_logined) {//登录成功
                    if ($_ftp_pasv > 0) { //主动模式
                        @ftp_pasv($_ftp_conn, FALSE);
                    } else { //被动模式
                        @ftp_pasv($_ftp_conn, TRUE);
                    }
                    $_ischdir = @ftp_chdir($_ftp_conn, $_ftp_path);
                    $dest = str_replace('/h', 'h', $dest);
                    $_return = @ftp_delete($_ftp_conn, $dest);
                    if ($_return === FALSE) {        //删除文件失败 ftp_delete
                        echo Language::Load('siteftpment', 64);
                    } else {
                        $result = 1;
                    }
                }
            }
            @ftp_Close($_ftp_conn);
        }
        return $result;
    }

    //判断文件类型
//    public static function Sftp_IfDir($dest) {
//        $epath = explode("/", $dest);
//        $endpath = $epath[count($epath) - 1];
//        if (is_dir($endpath)) {
//            return 1;
//        } else {
//            return 0;
//        }
//    }

    public static function Sftp_Clear($str) {
        $strtmp = str_replace(array("\n", "\r", '..'), '', $str);
        $strtmp = str_replace('//', '/', $strtmp);
        $strtmp = str_replace('./', "", $strtmp);
        return $strtmp;
    }

    /**
     * 取得ftp.cache.con中的信息
     * @param <type> $siteid
     * @param <type> $documentchannelid
     * @param <type> $hasftp
     * @param <type> $ftptype
     * @return <type>
     */
    public static function GetFtpCon($siteid = 0, $documentchannelid = 0, $hasftp = 0, $ftptype = 0) {
        $cachedir = 'data' . DIRECTORY_SEPARATOR . 'sysdata';
        $cachefile = self::GetFtpConFilename($siteid, $documentchannelid, $hasftp, $ftptype);
        if (strlen(DataCache::Get($cachedir . DIRECTORY_SEPARATOR . $cachefile)) <= 0) {
            self::UpdateFtpCon($siteid, $documentchannelid, $hasftp, $ftptype);
        }
        if (strlen(DataCache::Get($cachedir . DIRECTORY_SEPARATOR . $cachefile)) <= 0) {
            return "ftp_cong error is null";
        } else {
            require_once $cachedir . DIRECTORY_SEPARATOR . $cachefile;
            return $ftp_cong;
        }
    }

    /**
     * 更新ftp.cache.con文件
     * @param <type> $siteid
     * @param <type> $documentchannelid
     * @param <type> $hasftp
     * @param <type> $ftptype
     * @param <type> $ftp_arr    FTP配置信息(数组)
     * @return <type>
     */
    public static function UpdateFtpCon($siteid = 0, $documentchannelid = 0, $hasftp = 0, $ftptype = 0, $ftp_arr = null) {
        $cachedir = 'data' . DIRECTORY_SEPARATOR . 'sysdata';
        $cachefile = self::GetFtpConFilename($siteid, $documentchannelid, $hasftp, $ftptype);
        $tempstr = "";
        $tempstr .= "<?php\r\n\r\n";
        if (count($ftp_arr) > 0) {
            $tempstr .="\$ftp_cong = Array('FtpID' => " . $ftp_arr[FtpID] . ", 'Host' => '" . $ftp_arr[Host] . "', 'Port' => " . $ftp_arr[Port] . ", 'User' => '" . $ftp_arr[User] . "', 'Pass' => '" . $ftp_arr[Pass] . "', 'Path' => '" . $ftp_arr[Path] . "', 'Pasv' => " . $ftp_arr[Pasv] . ", 'Timeout' => " . $ftp_arr[Timeout] . ", 'SiteID' => " . $ftp_arr[SiteID] . ", 'DocumentChannelID' => " . $ftp_arr[DocumentChannelID] . ", 'FtpType' => " . $ftp_arr[FtpType] . ");\r\n\r\n";
        } else {
            return "0";
        }
        $tempstr .= "\r\n\r\n?>";
        DataCache::Set($cachefile, $cachedir, $tempstr);
        if (strlen(DataCache::Get($cachedir . DIRECTORY_SEPARATOR . $cachefile)) <= 0) {
            return "ftp_cong error is null";
        }
    }

    /**
     * 取得ftpconf文件名
     * @param <type> $siteid
     * @param <type> $documentchannelid
     * @param <type> $hasftp
     * @param <type> $ftptype
     * @return string
     */
    public static function GetFtpConFilename($siteid = 0, $documentchannelid = 0, $hasftp = 0, $ftptype = 0) {
        $cachefile = 'ftpcon.cache.' . $siteid . '.' . $documentchannelid . '.' . $ftptype . '.php';
        return $cachefile;
    }

    /**
     * 删除ftpconf文件
     * @param <type> $ftpid
     * @param <type> $ftp_arr
     * @return <type>
     */
    public static function DelFtpCon($ftp_arr = null) {
        $cachedir = 'data' . DIRECTORY_SEPARATOR . 'sysdata';
        $cachefile = 'ftpcon.cache.' . $ftp_arr[SiteID] . '.' . $ftp_arr[DocumentChannelID] . '.' . $ftp_arr[FtpType] . '.php';
        DataCache::Remove($cachedir . DIRECTORY_SEPARATOR . $cachefile);
        return "";
    }

}

?>
