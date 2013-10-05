<?php

/**
 * 前台Gen的基类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen
 * @author zhangchi
 */
class BaseFrontGen extends BaseGen {



    /**
     * 新增时的页面字段替换
     * @param string $tempContent 要处理的模板
     * @param string $tableName 数据表名 
     */
    public function ReplaceWhenAdd(&$tempContent, $tableName) {
        $commonData = new CommonData();
        $arrList = $commonData->GetFields($tableName);
        if (count($arrList) > 0) {
            for ($i = 0; $i < count($arrList); $i++) {
                $columns = $arrList[$i];
                foreach ($columns as $columnName => $columnValue) {
                    $tempContent = str_ireplace("{" . $columns['Field'] . "}", '', $tempContent);
                    $tempContent = str_ireplace("{b_" . $columns['Field'] . "}", '', $tempContent);
                }
            }
        }
    }

    /**
     * parent::DoFtp($despath, realpath($path),$filecontent, $documentchannelid, $hasftp,$ftptype);
     * 进行FTP发布,
     * @param <type> $dest          目标服务器路径
     * @param <type> $source        源服务器路径
     * @param <type> $filecontent   要发布的内容,如为图片等附件否为空
     * @param <type> $childid       文件的频道栏目ID
     * @param <type> $hasftp
     * @param <type> $ftptype       FTP发布类型:0为普通文件发布,1为多服务器优化发布
     * @return <type>
     */
    public function DoFtp($dest, $source, $filecontent, $childid = 0, $hasftp = 0, $ftptype = 0) {
        $ftpresult = 0;

        if (substr($dest, 0, 1) == DIRECTORY_SEPARATOR) {
            $dest = substr($dest, 1, strlen($dest));
        }
        $documentChannelData = new DocumentChannelData();
        $siteid = $documentChannelData->GetSiteID($childid);
        $ftpData = new FtpData();
        $ftp_cong = $ftpData->GetFtpData($siteid, $childid, $hasftp);
        $ftpconnid = Ftp::Sftp_Connect($ftp_cong);
        if ($ftptype == 0) {
            if (strlen($filecontent) > 0) {
                $filename = basename($dest);
                $tempdir = 'data' . DIRECTORY_SEPARATOR . 'temphtml';
                $source = P_PATH . DIRECTORY_SEPARATOR . $tempdir . DIRECTORY_SEPARATOR . $filename;        //源服务器路径
//$source = DIRECTORY_SEPARATOR.$tempdir . DIRECTORY_SEPARATOR . $filename;

                File::Write($source, $filecontent);
                $ftpresult = Ftp::Sftp_Upload($ftpconnid, $dest, $source);
                @unlink($source);
            } else {
                $source = str_ireplace("../", "/", $source);
                $source = str_ireplace("./", "/", $source);
                $source = P_PATH . DIRECTORY_SEPARATOR . $source;       //源服务器路径
                $ftpresult = Ftp::Sftp_Upload($ftpconnid, $dest, $source);
            }
        } else {
            $ftpresult = Ftp::Sftp_Upload($ftpconnid, $dest, $source);
        }

        return $ftpresult;
    }

    /**
     *
     * @param <type> $dest_path     目标服务器路径
     * @param <type> $source_path   源服务器路径
     * @param <type> $source_content    内容
     * @param <type> $documentchannelid
     * @param <type> $hasftp
     * @param <type> $ftptype
     */
    public function Ftp($dest_path, $source_path, $source_content, $documentchannelid = 0, $hasftp = 0, $ftptype = 0) {
        include ROOTPATH . '/inc/domain.inc.php';
        //发布目录和程序目录在同一站点下时，不发布附件  func分服务器则不进行发布
        $documentChannelData = new DocumentChannelData();
        $_siteId = $documentChannelData->GetSiteID($documentchannelid);
        if ($_siteId > 0) {
            $_siteData = new SiteData();
            $_subDomain = $_siteData->GetSubDomain($_siteId); //取得子域名
            //发布目录和程序目录在同一站点下并且子域名为空时，不发布附件
            //if ($domain['icms'] == $domain['func'] && empty($_subDomain)) {
            //使用直接写文件方式
            //$ftpresult = File::WriteSingle($dest_path, $source_path, $source_content);
            //} else {
            //FTP方式
            //$siteid = $documentChannelData->GetSiteID($documentchannelid);
            $ftpData = new FtpData();
            $_ftp_info = $ftpData->GetFtpData($_siteId, $documentchannelid, $hasftp);
            if (!empty($_ftp_info)) { //定义了FTP信息
                Ftp::Upload($_ftp_info, $dest_path, $source_path, $source_content);
            } else {
                //使用直接写文件方式            
                $ftpresult = File::WriteSingle($dest_path, $source_path, $source_content);
            }

            //}
            return $ftpresult;
        }
    }

    /**
     * 多个文件上传
     * @param <type> $arr_upload  dest:目标文件路径    source:源文件路径   content:源文件内容
     * @param <type> $documentchannelid
     * @param <type> $hasftp
     * @param <type> $ftptype
     */
    public function FtpQueue(&$arr_upload, $documentchannelid = 0, $hasftp = 0, $ftptype = 0, $siteid = 0) {
        include ROOTPATH . '/inc/domain.inc.php';

        //判断是否定义了FTP信息
        if ($documentchannelid > 0) {
            $_documentChannelData = new DocumentChannelData();
            $siteid = $_documentChannelData->GetSiteID($documentchannelid);
        }
        if ($siteid > 0) {
            $_ftpData = new FtpData();
            $_arrOneFtp = $_ftpData->GetFtpData($siteid, $documentchannelid, $hasftp, $ftptype);

            if (!empty($_arrOneFtp)) { //定义了FTP信息
                //使用FTP方式
                $documentChannelData = new DocumentChannelData();
                if ($siteid <= 0) {
                    $siteid = $documentChannelData->GetSiteID($documentchannelid);
                }
                $ftpData = new FtpData();
                $_ftp_info = $ftpData->GetFtpData($siteid, $documentchannelid, $hasftp);

                //FTP传输日志
                $siteConfigData = new SiteConfigData($siteid);
                $openFtpLog = $siteConfigData->OpenFtpLog;
                $ftpLogData = new FtpLogData();

                Ftp::UploadQueue($_ftp_info, $arr_upload, $openFtpLog, $ftpLogData);
            } else {
                //使用直接写文件方式            
                File::WriteQueue($arr_upload);
            }
        }
    }

    /**
     * 删除服务上文件
     * @param <type> $dest
     * @param <type> $childid
     * @param <type> $hasftp
     * @param <type> $ftptype
     */
    public function DelFtp($dest, $documentchannelid = 0, $hasftp = 0, $ftptype = 0) {
        $_isdel = 0;
        //判断是否定义了FTP信息
        if ($documentchannelid > 0) {
            $_documentChannelData = new DocumentChannelData();
            $_siteid = $_documentChannelData->GetSiteID($documentchannelid);
        }
        if ($_siteid > 0) {
            $_ftpData = new FtpData();
            $_arrOneFtp = $_ftpData->GetFtpData($_siteid, $documentchannelid, $hasftp, $ftptype);
            if (!empty($_arrOneFtp)) { //定义了FTP信息
                $_isdel = Ftp::Sftp_Delete($_arrOneFtp, $dest);
            } else {
                //使用直接写文件方式
                $dest = P_PATH . DIRECTORY_SEPARATOR . $dest;
                $_isdel = File::DelFile($dest);
                if ($_isdel) {
                    $_isdel = 1;
                } else {
                    $_isdel = 0;
                }
            }
        }
        return $_isdel;
    }

    /**
     * 根据$documentchannelid取目录路径
     * @param type $documentchannelid
     * @param type $rank
     * @param type $templatepublishfilename
     * @param type $publishPath
     * @return string
     */
    public function Get_PublishPath($documentchannelid, $rank, $templatepublishfilename = '', $publishPath = '') {
        if ($rank >= 1) {
            if (empty($publishPath)) { //定义了发布路径则使用定义的发布路径，否则使用频道id
                $publishPath = $documentchannelid;
            }

            if (strlen($templatepublishfilename) > 0) {
                $str = 'h/' . $publishPath . '/' . $templatepublishfilename;
            } else {
                $str = 'h/' . $publishPath . '/';
            }
            return $str;
        } else {
            if (strlen($templatepublishfilename) > 0) {
                return $templatepublishfilename;
            } else {
                return 'index.html';
            }
        }
    }

    /**
     * 是否是安全IP
     * @return boolean 
     */
    public function IsSecurityIp() {
        $ip = Control::GetIP();
        $isInnerIp = false; //是否安全IP
        //
        //安全登录IP，不需要短信认证
        //$SecurityIP = array('130.1.0', '20.20.20', '40.40.40');
        $securityIp = null;
        require_once ROOTPATH . '/inc/securityip.inc.php';
        if (empty($securityIp)) { //没有设置安全IP时，默认都安全
            $isInnerIp = true;
        } else {
            for ($i = 0; $i < count($securityIp); $i++) {
                if (stripos($ip, $securityIp[$i]) === 0) {
                    $isInnerIp = true;
                    break;
                }
            }
        }
        return $isInnerIp;
    }

    /**
     * 为论坛替换SiteConfig页面信息
     * @param SiteConfigData $SiteConfigData
     * @param type $TempContent 
     */
    public function ReplaceSiteConfigForForum(SiteConfigData $SiteConfigData, &$TempContent) {
        $forumIeTitle = $SiteConfigData->ForumIeTitle;
        $forumIeKeywords = $SiteConfigData->ForumIeKeywords;
        $forumIeDescription = $SiteConfigData->ForumIeDescription;
        $forumBackground = $SiteConfigData->ForumBackground;
        $forumTopinfo = $SiteConfigData->ForumTopinfo;
        $forumAdTopindex = $SiteConfigData->ForumAdTopindex;
        $forumBotinfo = $SiteConfigData->ForumBotinfo;
        $forumAdBotindex = $SiteConfigData->ForumAdBotindex;
        $forumAdTopTopicList = $SiteConfigData->ForumAdTopTopicList;
        $forumAdBotTopicList = $SiteConfigData->ForumAdBotTopicList;
        $forumAdTopTopicContent = $SiteConfigData->ForumAdTopTopicContent;
        $forumAdBotTopicContent = $SiteConfigData->ForumAdBotTopicContent;
        $registerWindowContent = $SiteConfigData->RegisterWindowContent;

        $forumNewPostCount = $SiteConfigData->ForumNewPostCount;
        $forumYesterdayPostCount = $SiteConfigData->ForumYesterdayPostCount;
        $forumTopPostCount = $SiteConfigData->ForumTopPostCount;
        $forumTopicCount = $SiteConfigData->ForumTopicCount;
        $forumReplyCount = $SiteConfigData->ForumReplyCount;
        $userCount = $SiteConfigData->UserCount;
        $newRegisterUserID = $SiteConfigData->NewRegisterUserID;
        $newRegisterUserName = $SiteConfigData->NewRegisterUserName;
        $newUserMessageVoice = $SiteConfigData->NewUserMessageVoice;
        $forumMoneyName = $SiteConfigData->ForumMoneyName;
        if (empty($forumMoneyName)) {
            $forumMoneyName = Language::Load('siteconfig', 3);
        }
        $forumCharmName = $SiteConfigData->ForumCharmName;
        if (empty($forumCharmName)) {
            $forumCharmName = Language::Load('siteconfig', 4);
        }
        $forumScoreName = $SiteConfigData->ForumScoreName;
        if (empty($forumScoreName)) {
            $forumScoreName = Language::Load('siteconfig', 2);
        }
        $forumExpName = $SiteConfigData->ForumExpName;
        if (empty($forumExpName)) {
            $forumExpName = Language::Load('siteconfig', 5);
        }
        $forumPointName = $SiteConfigData->ForumPointName;
        if (empty($forumPointName)) {
            $forumPointName = Language::Load('siteconfig', 7);
        }
        $forumPostCountName = $SiteConfigData->ForumPostCountName;
        if (empty($forumPostCountName)) {
            $forumPostCountName = Language::Load('siteconfig', 6);
        }
        $forumCssDefault = $SiteConfigData->ForumCssDefault;
        if (empty($forumCssDefault)) {
            $forumCssDefault = "default";
        }

        $forumCssDefaultWidth = $SiteConfigData->ForumCssDefaultWidth;
        if (empty($forumCssDefaultWidth)) {
            $forumCssDefaultWidth = "w980";
        }
        $forumCssDefaultFontSize = $SiteConfigData->ForumCssDefaultFontSize;
        if (empty($forumCssDefaultFontSize)) {
            $forumCssDefaultFontSize = "12";
        }



        $TempContent = str_ireplace("{cfg_ForumIeTitle}", $forumIeTitle, $TempContent);
        $TempContent = str_ireplace("{cfg_ForumIeKeywords}", $forumIeKeywords, $TempContent);
        $TempContent = str_ireplace("{cfg_ForumIeDescription}", $forumIeDescription, $TempContent);
        $TempContent = str_ireplace("{cfg_ForumBackground}", $forumBackground, $TempContent);
        $TempContent = str_ireplace("{cfg_ForumTopinfo}", $forumTopinfo, $TempContent);
        $TempContent = str_ireplace("{cfg_ForumAdTopindex}", $forumAdTopindex, $TempContent);
        $TempContent = str_ireplace("{cfg_ForumBotinfo}", $forumBotinfo, $TempContent);
        $TempContent = str_ireplace("{cfg_ForumAdBotindex}", $forumAdBotindex, $TempContent);
        $TempContent = str_ireplace("{cfg_ForumAdTopTopicList}", $forumAdTopTopicList, $TempContent);
        $TempContent = str_ireplace("{cfg_ForumAdBotTopicList}", $forumAdBotTopicList, $TempContent);
        $TempContent = str_ireplace("{cfg_ForumAdTopTopicContent}", $forumAdTopTopicContent, $TempContent);
        $TempContent = str_ireplace("{cfg_ForumAdBotTopicContent}", $forumAdBotTopicContent, $TempContent);


        $TempContent = str_ireplace("{cfg_ForumNewPostCount}", $forumNewPostCount, $TempContent);
        $TempContent = str_ireplace("{cfg_ForumYesterdayPostCount}", $forumYesterdayPostCount, $TempContent);
        $TempContent = str_ireplace("{cfg_ForumTopPostCount}", $forumTopPostCount, $TempContent);
        $TempContent = str_ireplace("{cfg_ForumTopicCount}", $forumTopicCount, $TempContent);
        $TempContent = str_ireplace("{cfg_ForumReplyCount}", $forumReplyCount, $TempContent);
        $TempContent = str_ireplace("{cfg_UserCount}", $userCount, $TempContent);
        $TempContent = str_ireplace("{cfg_NewRegisterUserID}", $newRegisterUserID, $TempContent);
        $TempContent = str_ireplace("{cfg_NewRegisterUserName}", $newRegisterUserName, $TempContent);
        $TempContent = str_ireplace("{cfg_NewUserMessageVoice}", $newUserMessageVoice, $TempContent);
        $TempContent = str_ireplace("{cfg_RegisterWindowContent}", $registerWindowContent, $TempContent);
        $TempContent = str_ireplace("{cfg_ForumMoneyName}", $forumMoneyName, $TempContent);
        $TempContent = str_ireplace("{cfg_ForumCharmName}", $forumCharmName, $TempContent);
        $TempContent = str_ireplace("{cfg_ForumScoreName}", $forumScoreName, $TempContent);
        $TempContent = str_ireplace("{cfg_ForumExpName}", $forumExpName, $TempContent);
        $TempContent = str_ireplace("{cfg_ForumPointName}", $forumPointName, $TempContent);
        $TempContent = str_ireplace("{cfg_ForumPostCountName}", $forumPostCountName, $TempContent);
        $TempContent = str_ireplace("{cfg_ForumCssDefault}", $forumCssDefault, $TempContent);
        $TempContent = str_ireplace("{cfg_ForumCssDefaultWidth}", $forumCssDefaultWidth, $TempContent);
        $TempContent = str_ireplace("{cfg_ForumCssDefaultFontSize}", $forumCssDefaultFontSize, $TempContent);
    }

    /**
     * 发送电子邮件
     * @param type $siteId
     * @param type $mailTo
     * @param type $subject
     * @param type $body
     * @param type $mailFrom
     * @param type $mailReplyTo
     */
    public function Mail($siteId, $mailTo, $subject = "", $body = "", $mailFrom = "", $mailReplyTo = "") {
        $sendResult = -1;

        $siteConfigData = new SiteConfigData($siteId);

        if (strlen($siteConfigData->MailSmtpHost) > 0) {
            $mail = new PHPMailer(); //new一个PHPMailer对象出来  
            $body = eregi_replace("[\]", '', $body); //对邮件内容进行必要的过滤     
            $mail->CharSet = "UTF-8"; //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码     
            $mail->IsSMTP(); // 设定使用SMTP服务     
            $mail->SMTPDebug = 1;  // 启用SMTP调试功能                                            // 1 = errors and messages  // 2 = messages only     
            $mail->SMTPAuth = true;  // 启用 SMTP 验证功能     
            //$mail->SMTPSecure = "ssl";  // 安全协议     
            $mail->Host = $siteConfigData->MailSmtpHost;      // SMTP 服务器     
            $mail->Port = intval($siteConfigData->MailSmtpPort);                   // SMTP服务器的端口号     
            $mail->Username = $siteConfigData->MailSmtpUserName;  // SMTP服务器用户名     
            $mail->Password = $siteConfigData->MailSmtpPassword;            // SMTP服务器密码    

            if ($mailFrom == "") {
                $mailFrom = $siteConfigData->MailFrom;
            }
            $mail->SetFrom($mailFrom, $mailFrom);

            if ($mailReplyTo == "") {
                $mailReplyTo = $siteConfigData->MailReplyTo;
            }
            $mail->AddReplyTo($mailReplyTo, $mailReplyTo);

            $mail->Subject = $subject;
            $mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test     
            $mail->MsgHTML($body);
            $address = $mailTo;
            $mail->AddAddress($address, $address);
            //$mail->AddAttachment("images/phpmailer.gif");      
            // attachment      
            //$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment 

            if (!$mail->Send()) {
                $sendResult = $mail->ErrorInfo;
            } else {
                $sendResult = 1; //Success
            }
        } else {
            $sendResult = -10; //not config
        }

        $mailLogData = new MailLogData();
        $mailLogData->Create($mailTo, $sendResult, $siteConfigData->MailSmtpHost, $siteConfigData->MailSmtpUserName, $siteConfigData->MailSmtpPassword, $siteConfigData->MailSmtpPort, $mailFrom, $mailReplyTo);
    }

    /**
     * 通过第三方网关发送短信
     * @param type $siteId
     * @param type $mobile
     * @param type $content
     */
    public function SmsThirdSend($siteId, $mobile, $content) {
        $sendResult = -1;
        if ($siteId > 0) {
            $siteConfigData = new SiteConfigData($siteId);
            switch ($siteConfigData->SmsThirdType) {
                case "wireless-media": //中驰无线 www.wireless-media.com
                    $url = $siteConfigData->SmsThirdUrl;
                    $data ["username"] = $siteConfigData->SmsThirdUserName;
                    $data ["password"] = $siteConfigData->SmsThirdPassword;
                    $data ["mobile"] = $mobile;
                    $data ["content"] = $content;
                    $data ["mark"] = 'send';
                    $data ["sendtime"] = '';
                    $data ["fstd"] = 15;
                    $data = http_build_query($data);
                    $getdata = $url . '?' . $data;
                    $sendResult = file_get_contents($getdata);
                    break;
            }
            $smsThirdLogData = new SmsThirdLogData();
            $smsThirdLogData->Create($mobile, $content, $siteConfigData->SmsThirdType, $siteConfigData->SmsThirdUrl, $siteConfigData->SmsThirdUserName, $siteConfigData->SmsThirdPassword, $sendResult);

            return $sendResult;
        }
    }

    /**
     * 加入visit统计代码
     * @param string $tempContent
     * @param type $funcUrl
     * @param type $siteId
     * @param type $documentChannelId
     * @param type $tableType
     * @param type $tableId
     * @param type $visitTag
     */
    public function AddVisitJsToTemplate(&$tempContent, $funcUrl, $siteId, $documentChannelId, $tableType, $tableId, $visitTag = '') {
        $tempContent .='<script type="text/javascript">';
        $tempContent .='var visitconfig = encodeURIComponent("' . $funcUrl . '") +"||' . $siteId . '||' . $documentChannelId . '||' . $tableType . '||' . $tableId . '||"+encodeURI("' . $visitTag . '");';
        $tempContent .='</script>';
        $tempContent .='<script type="text/javascript" src="' . $funcUrl . '/common/js/visit.js" charset="utf-8"></script>';
    }

}

?>
