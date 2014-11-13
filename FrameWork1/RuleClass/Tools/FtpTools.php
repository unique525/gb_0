<?php

/**
 * 提供FTP相关的工具方法
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Tools
 * @author zhangchi
 */
class FtpTools
{
    /**
     * 未操作
     */
    const FTP_NO_ACTION = -101;
    /**
     * 不支持FTP
     */
    const FTP_NOT_SUPPORT = 102;
    /**
     * 连接失败
     */
    const FTP_CONNECT_FAILURE = -102;
    /**
     * 登录失败
     */
    const FTP_LOGIN_FAILURE = -103;
    /**
     * 切换或创建文件夹失败
     */
    const FTP_CHANGE_OR_MAKE_DIR_FAILURE = -104;
    /**
     * ftp信息为空
     */
    const FTP_INFO_EMPTY = -105;
    /**
     * ftp传输内容为空
     */
    const FTP_CONTENT_EMPTY = -106;
    /**
     * 传输失败
     */
    const FTP_TRANSFER_FAILURE = -107;
    /**
     * 目标路径为空
     */
    const FTP_DESTINATION_EMPTY = -108;
    /**
     * 切换文件夹失败
     */
    const FTP_CHANGE_DIR_FAILURE = -109;
    /**
     * 删除失败
     */
    const FTP_DELETE_FAILURE = -100;
    /**
     * 传输成功
     */
    const FTP_TRANSFER_SUCCESS = 100;
    /**
     * 传输完成，但不代表成功
     */
    const FTP_TRANSFER_FINISHED = 200;
    /**
     * 删除成功
     */
    const FTP_DELETE_SUCCESS = 300;

    /**
     * 单个文件传输
     * @param Ftp $ftp FTP信息对象
     * @param string $destinationPath 目标路径
     * @param string $sourcePath 来源路径
     * @param string $sourceContent 要传输的内容
     * @param bool $openFtpLog 是否记录传输日志
     * @param FtpLogManageData $ftpLogManageData 传输日志操作对象
     * @return int 返回传输结果 -1:连接失败 -2:登录失败 -5:连接失败 1:上传成功
     */
    public static function Upload(Ftp $ftp, $destinationPath, $sourcePath = null, $sourceContent = null, $openFtpLog = false, FtpLogManageData $ftpLogManageData = null)
    {
        //进行连接
        $result = DefineCode::FTP + self::FTP_NO_ACTION;
        $ftpConnect = self::Connect($ftp);
        if (!$ftpConnect) { //连接失败
            $result = DefineCode::FTP + self::FTP_CONNECT_FAILURE; //连接失败
        } else { //连接成功
            //判断
            if (isset($ftp->FtpUser)
                && isset($ftp->FtpPass)
                && isset($ftp->PassiveMode)
                && isset($ftp->RemotePath)
            ) {
                $ftpUser = $ftp->FtpUser;
                $ftpPass = $ftp->FtpPass;
                $ftpPass = str_replace(array("\n", "\r"), array('', ''), $ftpPass);

                $ftpPassiveMode = intval($ftp->PassiveMode);
                $ftpPath = self::FormatPath($ftp->RemotePath);
                //开始登录
                $ftpIsLogin = ftp_login($ftpConnect, $ftpUser, $ftpPass);
                if ($ftpIsLogin) { //登录成功
                    if ($ftpPassiveMode > 0) { //被动模式
                        ftp_pasv($ftpConnect, TRUE);
                    } else { //主动模式
                        ftp_pasv($ftpConnect, FALSE);
                    }

                    $ftpId = intval($ftp->FtpId);
                    $siteId = intval($ftp->SiteId);
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
                    if ($uploadResult == abs(DefineCode::FTP) + self::FTP_TRANSFER_SUCCESS) {
                        $result = abs(DefineCode::FTP) + self::FTP_TRANSFER_SUCCESS; //上传成功
                    }

                } else { //登录失败
                    $result = DefineCode::FTP + self::FTP_LOGIN_FAILURE; //登录失败
                }
            }
            ftp_close($ftpConnect);
        }
        return $result;
    }

    /**
     * 多文件队列传输
     * @param Ftp $ftp FTP信息对象
     * @param PublishQueueManageData $publishQueueManageData 传输队列对象
     * @param bool $openFtpLog 是否开启了记录FTP传输日志的功能，默认未开启
     * @param FtpLogManageData $ftpLogManageData FTP日志对象
     * @return int -5:连接失败 -2:连接失败 -1:登录失败 1:上传成功
     */
    public static function UploadQueue(Ftp $ftp, PublishQueueManageData &$publishQueueManageData, $openFtpLog = false, FtpLogManageData $ftpLogManageData = null)
    {
        $arrUpload = $publishQueueManageData->Queue;
        if (empty($arrUpload)) {
            return DefineCode::FTP + self::FTP_CONTENT_EMPTY; //上传数组为空
        }
        if (!isset($ftp->FtpUser) ||
            !isset($ftp->FtpPass) ||
            !isset($ftp->PasvMode) ||
            !isset($ftp->RemotePath) ||
            !isset($ftp->FtpId)
        ) {
            return DefineCode::FTP + self::FTP_INFO_EMPTY; //FTP配置信息为空
        }

        $ftpConnect = self::Connect($ftp);
        if (!$ftpConnect) { //连接失败
            return DefineCode::FTP + self::FTP_CONNECT_FAILURE;
        }

        $ftpUser = $ftp->FtpUser;
        $ftpPass = $ftp->FtpPass;
        $ftpPass = str_replace(array("\n", "\r"), array('', ''), $ftpPass);

        $ftpPassiveMode = intval($ftp->PasvMode);
        $ftpPath = $ftp->RemotePath;
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
                    $ftp->FtpId,
                    $ftp->SiteId
                );
                if ($uploadResult == abs(DefineCode::FTP) + self::FTP_TRANSFER_SUCCESS) {
                    $arrUpload[$i]["result"] = abs(DefineCode::FTP) + self::FTP_TRANSFER_SUCCESS;
                } else {
                    $arrUpload[$i]["result"] = DefineCode::FTP + self::FTP_TRANSFER_FAILURE;
                }
            }
            $result = abs(DefineCode::FTP) + self::FTP_TRANSFER_FINISHED; //操作完成，但不代表所有文件操作成功


        } else { //登录失败
            $result = DefineCode::FTP + self::FTP_LOGIN_FAILURE;
        }
        ftp_close($ftpConnect);
        return $result;
    }

    /**
     * 删除文件
     * @param Ftp $ftp FTP信息对象
     * @param string $deleteFilePath 要删除的文件路径
     * @return int 操作结果
     */
    public static function Delete(Ftp $ftp, $deleteFilePath)
    {
        $result = DefineCode::FTP + self::FTP_NO_ACTION;
        $ftpConnect = self::Connect($ftp);//进行连接
        if (!$ftpConnect) { //连接失败
            return DefineCode::FTP + self::FTP_CONNECT_FAILURE;
        } else { //连接成功
            //判断ftp info
            if (!isset($ftp->FtpUser) ||
                !isset($ftp->FtpPass) ||
                !isset($ftp->PasvMode) ||
                !isset($ftp->RemotePath) ||
                !isset($ftp->FtpId)
            ) {
                $ftpUser = $ftp->FtpUser;
                $ftpPass = $ftp->FtpPass;
                $ftpPass = str_replace(array("\n", "\r"), array('', ''), $ftpPass);

                $ftpPassiveMode = intval($ftp->PasvMode);
                $ftpPath = $ftp->RemotePath;
                $ftpPath = self::FormatPath($ftpPath);
                //开始登录
                $isLogin = @ftp_login($ftpConnect, $ftpUser, $ftpPass);
                if ($isLogin) { //登录成功
                    if ($ftpPassiveMode > 0) { //被动模式
                        ftp_pasv($ftpConnect, TRUE);
                    } else { //被动模式
                        ftp_pasv($ftpConnect, FALSE);
                    }
                    $isChangeDir = ftp_chdir($ftpConnect, $ftpPath);
                    if($isChangeDir){
                        $deleteFilePath = str_replace('/h', 'h', $deleteFilePath);
                        if(ftp_delete($ftpConnect, $deleteFilePath)){
                            $result = abs(DefineCode::FTP) + self::FTP_DELETE_SUCCESS;
                        }else{
                            $result = DefineCode::FTP + self::FTP_DELETE_FAILURE;
                        }
                    }else{
                        $result = DefineCode::FTP + self::FTP_CHANGE_DIR_FAILURE;
                    }
                }else{
                    $result = DefineCode::FTP + self::FTP_LOGIN_FAILURE;
                }
            }
            ftp_close($ftpConnect);
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
            return DefineCode::FTP + self::FTP_DESTINATION_EMPTY;
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
            $result = DefineCode::FTP + self::FTP_CHANGE_OR_MAKE_DIR_FAILURE;
        } else {
            if (ftp_put($ftpConnect, $fileName, $sourcePath, FTP_BINARY)) {
                $result = abs(DefineCode::FTP) + self::FTP_TRANSFER_SUCCESS;
            } else {
                $result = DefineCode::FTP + self::FTP_TRANSFER_FAILURE;
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
     * @param Ftp $ftp FTP信息对象
     * @return resource FTP连接的信息
     */
    private static function Connect(Ftp $ftp)
    {
        $ftpConnect = null;
        @set_time_limit(0);
        //取得ftp配置信息
        $connectFunction = function_exists('ftp_ssl_connect') ? 'ftp_ssl_connect' : 'ftp_connect';
        if ($connectFunction == 'ftp_connect' && !function_exists('ftp_connect')) { //判断PHP是否支持FTP
            return false;
        }

        //判断ftp info
        if (isset($ftp->FtpHost) && isset($ftp->FtpPort)) {
            $ftpHost = $ftp->FtpHost;
            $ftpPort = 21;
            if (isset($ftp->FtpPort)) {
                $ftpPort = intval($ftp->FtpPort);
            }
            $ftpTimeout = 90;
            if (isset($ftp->Timeout)) {
                $ftpTimeout = intval($ftp->Timeout);
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
            if (!ftp_mkdir($ftpConnect, $dir)) {
                return FALSE;
            }
        }
        return true;
    }

}

?>
