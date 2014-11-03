<?php

/**
 * 提供FTP相关的工具方法
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Tools
 * @author zhangchi
 */
class Ftp
{
    /**
     * 未操作
     */
    const FTP_NO_ACTION = -101;
    /**
     * 不支持FTP
     */
    const FTP_NOT_SUPPORT = 002;
    /**
     * 连接失败
     */
    const FTP_CONNECT_FAILURE = -2;
    /**
     * 登录失败
     */
    const FTP_LOGIN_FAILURE = -3;
    /**
     * 切换或创建文件夹失败
     */
    const FTP_CHANGE_OR_MAKE_DIR_FAILURE = -4;
    /**
     * ftp信息为空
     */
    const FTP_INFO_EMPTY = -5;
    /**
     * ftp传输内容为空
     */
    const FTP_CONTENT_EMPTY = -6;
    /**
     * 传输失败
     */
    const FTP_TRANSFER_FAILURE = -7;
    /**
     * 目标路径为空
     */
    const FTP_DESTINATION_EMPTY = -8;
    /**
     * 切换文件夹失败
     */
    const FTP_CHANGE_DIR_FAILURE = -9;
    /**
     * 删除失败
     */
    const FTP_DELETE_FAILURE = -10;
    /**
     * 传输成功
     */
    const FTP_TRANSFER_SUCCESS = 1;
    /**
     * 传输完成，但不代表成功
     */
    const FTP_TRANSFER_FINISHED = 2;
    /**
     * 删除成功
     */
    const FTP_DELETE_SUCCESS = 3;

    /**
     * 单个文件传输
     * @param array $ftpInfo FTP连接信息
     * @param string $destinationPath 目标路径
     * @param string $sourcePath 来源路径
     * @param string $sourceContent 要传输的内容
     * @param bool $openFtpLog 是否记录传输日志
     * @param FtpLogManageData $ftpLogManageData 传输日志操作对象
     * @return int 返回传输结果 -1:连接失败 -2:登录失败 -5:连接失败 1:上传成功
     */
    public static function Upload($ftpInfo, $destinationPath, $sourcePath = null, $sourceContent = null, $openFtpLog = false, FtpLogManageData $ftpLogManageData = null)
    {
        //进行连接
        $result = DefineCode::FTP + self::FTP_NO_ACTION;
        $ftpConnect = self::Connect($ftpInfo);
        if (!$ftpConnect) { //连接失败
            $result = self::FTP_CONNECT_FAILURE; //连接失败
        } else { //连接成功
            //判断
            if (isset($ftpInfo['FtpUser'])
                && isset($ftpInfo['FtpPass'])
                && isset($ftpInfo['PassiveMode'])
                && isset($ftpInfo['RemotePath'])
            ) {
                $ftpUser = $ftpInfo['FtpUser'];
                $ftpPass = $ftpInfo['FtpPass'];
                $ftpPass = str_replace(array("\n", "\r"), array('', ''), $ftpPass);

                $ftpPassiveMode = intval($ftpInfo['PassiveMode']);
                $ftpPath = self::FormatPath($ftpInfo['RemotePath']);
                //开始登录
                $ftpIsLogin = ftp_login($ftpConnect, $ftpUser, $ftpPass);
                if ($ftpIsLogin) { //登录成功
                    if ($ftpPassiveMode > 0) { //被动模式
                        ftp_pasv($ftpConnect, TRUE);
                    } else { //主动模式
                        ftp_pasv($ftpConnect, FALSE);
                    }

                    $ftpId = intval($ftpInfo['FtpId']);
                    $siteId = intval($ftpInfo['SiteId']);
                    $destinationPath = self::FormatPath($destinationPath);
                    $sourcePath = self::FormatPath($sourcePath);
                    //开始传输
                    $uploadResult = self::BeginUpload(
                        $ftpConnect,
                        $ftpPath,
                        $destinationPath,
                        $sourcePath,
                        $sourceContent,
                        $openFtpLog,
                        $ftpLogManageData,
                        $ftpId,
                        $siteId
                    );
                    if ($uploadResult == self::FTP_TRANSFER_SUCCESS) {
                        $result = self::FTP_TRANSFER_SUCCESS; //上传成功
                    }

                } else { //登录失败
                    $result = self::FTP_LOGIN_FAILURE; //登录失败
                }
            }
            Ftp_Close($ftpConnect);
        }
        return $result;
    }

    /**
     * 多文件队列传输
     * @param array $ftpInfo FTP连接信息
     * @param PublishQueueManageData $publishQueueManageData 传输队列对象
     * @param bool $openFtpLog 是否开启了记录FTP传输日志的功能，默认未开启
     * @param FtpLogManageData $ftpLogManageData FTP日志对象
     * @return int -5:连接失败 -2:连接失败 -1:登录失败 1:上传成功
     */
    public static function UploadQueue($ftpInfo, PublishQueueManageData &$publishQueueManageData, $openFtpLog = false, FtpLogManageData $ftpLogManageData = null)
    {
        $arrUpload = $publishQueueManageData->Queue;
        if (empty($arrUpload)) {
            return self::FTP_CONTENT_EMPTY; //上传数组为空
        }
        if (!isset($ftpInfo['FtpUser']) ||
            !isset($ftpInfo['FtpPass']) ||
            !isset($ftpInfo['PassiveMode']) ||
            !isset($ftpInfo['RemotePath']) ||
            !isset($ftpInfo['FtpId'])
        ) {
            return self::FTP_INFO_EMPTY; //FTP配置信息为空
        }

        $ftpConnect = self::Connect($ftpInfo);
        if (!$ftpConnect) { //连接失败
            return self::FTP_CONNECT_FAILURE;
        }

        $ftpUser = $ftpInfo['FtpUser'];
        $ftpPass = $ftpInfo['FtpPass'];
        $ftpPass = str_replace(array("\n", "\r"), array('', ''), $ftpPass);

        $ftpPassiveMode = intval($ftpInfo['PassiveMode']);
        $ftpPath = $ftpInfo['RemotePath'];
        $ftpPath = self::FormatPath($ftpPath);
        //开始登录
        $ftpIsLogin = ftp_login($ftpConnect, $ftpUser, $ftpPass);
        if ($ftpIsLogin) { //登录成功
            if ($ftpPassiveMode > 0) { //被动模式
                ftp_pasv($ftpConnect, TRUE);
            } else { //主动模式
                ftp_pasv($ftpConnect, FALSE);
            }

            //开始传输

            for ($i = 0; $i < count($arrUpload); $i++) {
                $destinationPath = self::FormatPath($arrUpload[$i]["DestinationPath"]);
                $sourcePath = self::FormatPath($arrUpload[$i]["SourcePath"]);
                $uploadResult = self::BeginUpload(
                    $ftpConnect,
                    $ftpPath,
                    $destinationPath,
                    $sourcePath,
                    $arrUpload[$i]["Content"],
                    $openFtpLog,
                    $ftpLogManageData,
                    $ftpInfo['FtpId'],
                    $ftpInfo['SiteId']
                );
                if ($uploadResult == self::FTP_TRANSFER_SUCCESS) {
                    $arrUpload[$i]["result"] = 1;
                } else {
                    $arrUpload[$i]["result"] = 0;
                }
            }
            $result = self::FTP_TRANSFER_FINISHED; //操作完成，但不代表所有文件操作成功


        } else { //登录失败
            $result = self::FTP_LOGIN_FAILURE;
        }
        ftp_close($ftpConnect);
        return $result;
    }

    /**
     * 删除文件
     * @param array $ftpInfo FTP连接信息
     * @param string $deleteFilePath 要删除的文件路径
     * @return int 操作结果
     */
    public static function Delete($ftpInfo, $deleteFilePath)
    {
        $result = self::FTP_NO_ACTION;
        $ftpConnect = self::Connect($ftpInfo);//进行连接
        if (!$ftpConnect) { //连接失败
            return self::FTP_CONNECT_FAILURE;
        } else { //连接成功
            //判断ftp info
            if (isset($ftpInfo['FtpUser']) &&
                isset($ftpInfo['FtpPass']) &&
                isset($ftpInfo['PassiveMode']) &&
                isset($ftpInfo['RemotePath'])
            ) {
                $ftpUser = $ftpInfo['FtpUser'];
                $ftpPass = $ftpInfo['FtpPass'];
                $ftpPass = str_replace(array("\n", "\r"), array('', ''), $ftpPass);

                $ftpPassiveMode = intval($ftpInfo['PassiveMode']);
                $ftpPath = $ftpInfo['RemotePath'];
                $ftpPath = self::FormatPath($ftpPath);
                //开始登录
                $isLogin = @ftp_login($ftpConnect, $ftpUser, $ftpPass);
                if ($isLogin) { //登录成功
                    if ($ftpPassiveMode > 0) { //被动模式
                        Ftp_Pasv($ftpConnect, TRUE);
                    } else { //被动模式
                        Ftp_Pasv($ftpConnect, FALSE);
                    }
                    $isChangeDir = Ftp_ChDir($ftpConnect, $ftpPath);
                    if($isChangeDir){
                        $deleteFilePath = str_replace('/h', 'h', $deleteFilePath);
                        if(Ftp_Delete($ftpConnect, $deleteFilePath)){
                            $result = self::FTP_DELETE_SUCCESS;
                        }else{
                            $result = self::FTP_DELETE_FAILURE;
                        }
                    }else{
                        $result = self::FTP_CHANGE_DIR_FAILURE;
                    }
                }else{
                    $result = self::FTP_LOGIN_FAILURE;
                }
            }
            Ftp_Close($ftpConnect);
        }
        return $result;
    }

    /**
     * FTP内容传输
     * @param resource $ftpConnect FTP连接信息
     * @param string $ftpPath FTP的远程路径
     * @param string $destinationPath 目标文件夹路径
     * @param string $sourcePath 来源文件夹路径
     * @param string $sourceContent 来源文件内容
     * @param int $openFtpLog 是否记录传输日志
     * @param FtpLogManageData $ftpLogManageData 传输日志操作对象
     * @param int $ftpId FTP表记录的id
     * @param int $siteId FTP所在的站点id
     * @return int 传输结果
     */
    private static function BeginUpload($ftpConnect, $ftpPath, $destinationPath = null, $sourcePath = null, $sourceContent = null, $openFtpLog = 0, FtpLogManageData $ftpLogManageData = null, $ftpId = 0, $siteId = 0)
    {
        $timeStart = Control::GetMicroTime();

        if (empty($destinationPath)) { //目标服务器路径不能为空
            return self::FTP_DESTINATION_EMPTY;
        }

        $dirName = dirname($destinationPath); //目标文件地址
        $fileName = basename($destinationPath); //目标文件名
        $destinationPath = $ftpPath . "/" . $destinationPath; //要生成的目标文件地址

        if (strlen($sourceContent) > 0) { //发布内容上传
            $sourceFileContent = $sourceContent;
            $microTime = floor(microtime() * 1000); //加入随机临时目录
            $rand = rand("111", "9999");
            $randPath = $microTime . $rand;
            $sourcePath =
                PHYSICAL_PATH . DIRECTORY_SEPARATOR .
                CACHE_PATH . DIRECTORY_SEPARATOR . 'ftp_temp' . DIRECTORY_SEPARATOR . $siteId . DIRECTORY_SEPARATOR . $randPath . DIRECTORY_SEPARATOR . $fileName; //源文件绝对路径        //源服务器路径
            FileObject::Write($sourcePath, $sourceFileContent);
        } else { //附件文件上传
            $sourcePath = realpath(RELATIVE_PATH . $sourcePath); //源文件路径
        }

        //切换或创建目录
        $changeOrMakeDirResult = self::ChangeOrMakeDir($ftpConnect, $dirName);
        if (!$changeOrMakeDirResult) {
            $result = self::FTP_CHANGE_OR_MAKE_DIR_FAILURE;
        } else {
            if (Ftp_Put($ftpConnect, $fileName, $sourcePath, FTP_BINARY)) {
                $result = self::FTP_TRANSFER_SUCCESS;
            } else {
                $result = self::FTP_TRANSFER_FAILURE;
            }
            if (strlen($sourceContent) > 0) { //发布内容上传时，删除临时文件
                FileObject::DeleteDir($sourcePath);
            }
        }

        $timeEnd = Control::GetMicroTime();
        $timeSpan = $timeEnd - $timeStart;

        if ($openFtpLog) {
            $ftpLogManageData->Create($ftpId, $destinationPath, $sourcePath, $timeSpan, $result);
        }

        return $result;
    }

    /**
     * 连接到FTP，打开端口
     * @param array $ftpInfo
     * @return resource FTP连接的信息
     */
    private static function Connect($ftpInfo)
    {
        $ftpConnect = null;
        @set_time_limit(0);
        //取得ftp配置信息
        $connectFunction = function_exists('ftp_ssl_connect') ? 'ftp_ssl_connect' : 'ftp_connect';
        if ($connectFunction == 'ftp_connect' && !function_exists('ftp_connect')) { //判断PHP是否支持FTP
            return false;
        }

        //判断ftp info
        if (isset($ftpInfo['FtpHost']) && isset($ftpInfo['FtpPort'])) {
            $ftpHost = $ftpInfo['FtpHost'];
            $ftpPort = 21;
            if (isset($ftpInfo['FtpPort'])) {
                $ftpPort = intval($ftpInfo['FtpPort']);
            }
            $ftpTimeout = 90;
            if (isset($ftpInfo['Timeout'])) {
                $ftpTimeout = intval($ftpInfo['Timeout']);
            }
            $ftpConnect = @$connectFunction($ftpHost, $ftpPort, $ftpTimeout);
            if ($ftpTimeout >= 0 && function_exists('ftp_set_option')) { //设置超时时间
                @ftp_set_option($ftpConnect, FTP_TIMEOUT_SEC, $ftpTimeout);
            }
        }
        return $ftpConnect;
    }

    /**
     * 格式化传输路径
     * @param string $path 要格式化的传输路径
     * @return string 格式化结果
     */
    private static function FormatPath($path)
    {
        $path = str_replace(array("\n", "\r", '..'), '', $path);
        $path = str_replace('//', '/', $path);
        $path = str_replace('./', "", $path);
        return $path;
    }

    /**
     * 切换或创建目录
     * @param resource $ftpConnect FTP连接对象
     * @param string $dirPath 文件夹路径
     * @return bool 处理结果
     */
    private static function ChangeOrMakeDir($ftpConnect, $dirPath)
    {
        $result = ftp_chdir($ftpConnect, $dirPath);
        if (!$result) { //切换FTP上传目录失败
            $isMakeDir = self::MakeDir($ftpConnect, $dirPath);
            if (!$isMakeDir) { //创建文件夹失败
                return FALSE;
            } else {
                $result = ftp_chdir($ftpConnect, $dirPath);
                return $result;
            }
        }
        return $result;
    }

    /**
     * 循环创建文件夹
     * @param resource $ftpConnect FTP连接对象
     * @param string $dirPath 文件夹路径
     * @return bool 创建结果
     */
    private static function MakeDir($ftpConnect, $dirPath)
    {
        $arrPath = explode("/", $dirPath);
        $dir = '';
        $separator = '';
        foreach ($arrPath as $path) { //循环创建文件夹
            $dir .= $separator . $path;
            $separator = "/";
            if (!Ftp_MkDir($ftpConnect, $dir)) {
                return FALSE;
            }
        }
        return true;
    }

}

?>
