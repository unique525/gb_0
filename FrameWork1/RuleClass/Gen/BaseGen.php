<?php

/**
 * 所有Gen的基类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen
 * @author zhangchi
 */
class BaseGen {

    /**
     * 在模板替换前的统一替换
     * @param string $tempContent 要处理的模板
     */
    public function ReplaceFirst(&$tempContent) {
        ///////找出PreTemp标记/////////
        $keyName = "pre_temp";
        $arr = Template::GetAllCustomTag($tempContent, $keyName);
        if (isset($arr)) {
            if (count($arr) > 1) {
                $arr2 = $arr[1];
                foreach ($arr2 as $key => $val) {
                    $docContent = "<$keyName$val</$keyName>";
                    //模板ID
                    $channelTemplateId = Template::GetParamValue($docContent, "id", $keyName);
                    $channelTemplateData = new ChannelTemplateData();
                    $preTempContent = $channelTemplateData->GetChannelTemplateContent($channelTemplateId);
                    $tempContent = Template::ReplaceCustomTag($tempContent, $channelTemplateId, $preTempContent, $keyName);
                }
            }
        }
        ///////找出site_content标记/////////
        $keyName = "site_content";
        $arr = Template::GetAllCustomTag($tempContent, $keyName);
        if (isset($arr)) {
            if (count($arr) > 1) {
                $arr2 = $arr[1];
                foreach ($arr2 as $key => $val) {
                    $docContent = "<$keyName$val</$keyName>";
                    //模板ID
                    $siteContentId = Template::GetParamValue($docContent, "id", $keyName);
                    $siteContentData = new SiteContentData();
                    $siteContent = $siteContentData->GetSiteContentValue($siteContentId);
                    $tempContent = Template::ReplaceSiteContent($tempContent, $siteContentId, $siteContent);
                }
            }
        }

        ///////找出SiteAd标记/////////
        $keyName = "site_ad";
        $arr = Template::GetAllCustomTag($tempContent, $keyName);
        if (isset($arr)) {
            if (count($arr) > 1) {
                $arr2 = $arr[1];
                foreach ($arr2 as $key => $val) {
                    $docContent = "<$keyName$val</$keyName>";
                    $siteAdId = Template::GetParamValue($docContent, "id", $keyName);

                    $adgen = new AdGen();
                    $pre_content = $adgen->GenFormatAd($siteAdId);
                    $tempContent = Template::ReplaceSiteAd($tempContent, $siteAdId, $pre_content);
                }
            }
        }

        //找出icms slider标记
        $keyName = "pic_slider";
        $arr = Template::GetAllCustomTag($tempContent, $keyName);
        if (isset($arr)) {
            if (count($arr) > 1) {
                $arr2 = $arr[1];
                $slider_i = 0;
                if (class_exists("DocumentSliderGen")) {
                    $sliderGen = new DocumentSliderGen();
                    foreach ($arr2 as $val) {
                        $docContent = '<icmsslider' . $val . '</icmsslider>';

                        $docchannelid = Template::GetParamValue($docContent, "id", "icmsslider");
                        $top = Template::GetParamValue($docContent, "top", "icmsslider");

                        $pattern = Template::GetParamValue($docContent, "pattern", "icmsslider");
                        $time = Template::GetParamValue($docContent, "time", "icmsslider");
                        $trigger = Template::GetParamValue($docContent, "trigger", "icmsslider");
                        $width = Template::GetParamValue($docContent, "width", "icmsslider");
                        $height = Template::GetParamValue($docContent, "height", "icmsslider");
                        $txtHeight = Template::GetParamValue($docContent, "txtHeight", "icmsslider");
                        $auto = Template::GetParamValue($docContent, "auto", "icmsslider");
                        $wrap = Template::GetParamValue($docContent, "wrap", "icmsslider");
                        $index = Template::GetParamValue($docContent, "index", "icmsslider");
                        $delay = Template::GetParamValue($docContent, "delay", "icmsslider");
                        $duration = Template::GetParamValue($docContent, "duration", "icmsslider");
                        $direction = Template::GetParamValue($docContent, "direction", "icmsslider");
                        $easing = Template::GetParamValue($docContent, "easing", "icmsslider");
                        $less = Template::GetParamValue($docContent, "less", "icmsslider");
                        $chip = Template::GetParamValue($docContent, "chip", "icmsslider");
                        $type = Template::GetParamValue($docContent, "type", "icmsslider");
                        $pad = Template::GetParamValue($docContent, "pad", "icmsslider");
                        $txtWidth = Template::GetParamValue($docContent, "txtWidth", "icmsslider");
                        $gray = Template::GetParamValue($docContent, "gray", "icmsslider");
                        $direct = Template::GetParamValue($docContent, "direct", "icmsslider");
                        $turn = Template::GetParamValue($docContent, "turn", "icmsslider");

                        $arr_par = array(
                            "{pattern}" => $pattern,
                            "{time}" => $time,
                            "{trigger}" => $trigger,
                            "{width}" => $width,
                            "{height}" => $height,
                            "{txtHeight}" => $txtHeight,
                            "{auto}" => $auto,
                            "{wrap}" => $wrap,
                            "{index}" => $index,
                            "{delay}" => $delay,
                            "{duration}" => $duration,
                            "{direction}" => $direction,
                            "{easing}" => $easing,
                            "{less}" => $less,
                            "{chip}" => $chip,
                            "{type}" => $type,
                            "{pad}" => $pad,
                            "{txtWidth}" => $txtWidth,
                            "{gray}" => $gray,
                            "{direct}" => $direct,
                            "{turn}" => $turn
                        );

                        $slidercontent = $sliderGen->ReplaceSlider($docchannelid, $top, $arr_par);
                        if ($slider_i == 0) {
                            $slidercontent = '<script type="text/javascript" src="{funcdomain}/js/myfocus-1.2.0.full.js"></script>' . $slidercontent;
                        }
                        $tempContent = Template::ReplaceIcmsSlider($tempContent, $docchannelid, $slidercontent);
                        $slider_i++;
                    }
                }
            }
        }

        //channel_name
        $keyName = "channel_name";
        $arr = Template::GetAllCustomTag($tempContent, $keyName);
        if (isset($arr)) {
            if (count($arr) > 1) {
                $arr2 = $arr[1];
                if(!empty($arr2)){
                    $documentChannelData = new DocumentChannelData();
                    foreach ($arr2 as $val) {
                        $docContent = '<documentchannelname' . $val . '</documentchannelname>';
                        $keyName = "documentchannelname";
                        $channelId = Template::GetParamValue($docContent, "id", $keyName);
                        $documentChannelName = $documentChannelData->GetName($channelId);
                        $tempContent = Template::ReplaceCMS($tempContent, $channelId, $documentChannelName, $keyName);
                    }
                }
            }
        }
    }

    /**
     * 在模板替换后的统一替换
     * @param string $tempContent 要处理的模板
     */
    public function ReplaceEnd(&$tempContent) {
        $templateName = self::GetTemplateName();
        $selectTemplate = Template::Load("select_template.html","common");
        $commonJavaScriptAndCss = Template::Load("manage/common_javascript_and_css.html","common");
        $tempContent = str_ireplace("{common_javascript_and_css}", $commonJavaScriptAndCss, $tempContent);
        $tempContent = str_ireplace("{system_name}", SYSTEM_NAME, $tempContent);
        $tempContent = str_ireplace("{relative_path}", RELATIVE_PATH, $tempContent);
        $tempContent = str_ireplace("{manage_domain}", MANAGE_DOMAIN, $tempContent);
        $tempContent = str_ireplace("{webapp_domain}", WEBAPP_DOMAIN, $tempContent);
        $tempContent = str_ireplace("{template_name}", $templateName, $tempContent);
        $tempContent = str_ireplace("{select_template}", $selectTemplate, $tempContent);
        $tempContent = str_ireplace("{template_selected_$templateName}", "_selected", $tempContent);
        $tempContent = str_ireplace("{template_selected_default}", "", $tempContent);
        $tempContent = str_ireplace("{template_selected_deepblue}", "", $tempContent);
    }

    /**
     * 取得后台管理员使用的模板名称
     * @return string 模板名称
     */
    private function GetTemplateName() {
        $templateName = Control::GetManageUserTemplateName();
        if(strlen($templateName)<=0){
            $templateName = "default";
        }
        return $templateName;
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
        $securityIp = explode('|',SECURITY_IP);
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
     * 返回错误内容模板
     * @param string $errorContent 错误提示内容
     * @return string 错误内容模板
     */
    protected function ShowError($errorContent){
        $errorTemplate = Template::Load("error.html","common");        
        $errorTemplate = str_ireplace("{error_content}", $errorContent, $errorTemplate);
        self::ReplaceEnd($errorTemplate);        
        return $errorTemplate;
    }

    /**
     * 替换模板中的配置标记
     * @param int $siteId 站点id
     * @param string $tempContent 模板内容
     */
    protected function ReplaceSiteConfig($siteId, &$tempContent) {
        $siteConfigManageData = new SiteConfigManageData($siteId);
        $arrSiteConfigOne = $siteConfigManageData->GetList($siteId);
        if (count($arrSiteConfigOne) > 0) {
            for ($i = 0; $i < count($arrSiteConfigOne); $i++) {
                $siteConfigName = $arrSiteConfigOne[$i]["SiteConfigName"];
                $stringNorValue = $arrSiteConfigOne[$i]["StringNorValue"];
                $stringMidValue = $arrSiteConfigOne[$i]["StringMidValue"];
                $textValue = $arrSiteConfigOne[$i]["TextValue"];
                $intValue = $arrSiteConfigOne[$i]["IntValue"];
                $numValue = $arrSiteConfigOne[$i]["NumValue"];
                $siteConfigType = intval($arrSiteConfigOne[$i]["SiteConfigType"]);
                switch ($siteConfigType) {
                    case 0:
                        $tempContent = str_ireplace("{cfg_" . $siteConfigName . "_" . $siteConfigType . "}", $stringNorValue, $tempContent);
                        $tempContent = str_ireplace("{cfg_" . $siteConfigName . "}", $stringNorValue, $tempContent);
                        break;
                    case 1:
                        $tempContent = str_ireplace("{cfg_" . $siteConfigName . "_" . $siteConfigType . "}", $stringMidValue, $tempContent);
                        $tempContent = str_ireplace("{cfg_" . $siteConfigName . "}", $stringMidValue, $tempContent);
                        break;
                    case 2:
                        $tempContent = str_ireplace("{cfg_" . $siteConfigName . "_" . $siteConfigType . "}", $textValue, $tempContent);
                        $tempContent = str_ireplace("{cfg_" . $siteConfigName . "}", $textValue, $tempContent);
                        break;
                    case 3:
                        $tempContent = str_ireplace("{cfg_" . $siteConfigName . "_" . $siteConfigType . "}", $intValue, $tempContent);
                        $tempContent = str_ireplace("{cfg_" . $siteConfigName . "}", $intValue, $tempContent);
                        break;
                    case 4:
                        $tempContent = str_ireplace("{cfg_" . $siteConfigName . "_" . $siteConfigType . "}", $numValue, $tempContent);
                        $tempContent = str_ireplace("{cfg_" . $siteConfigName . "}", $numValue, $tempContent);
                        break;
                    default:
                        $tempContent = str_ireplace("{cfg_" . $siteConfigName . "_" . $siteConfigType . "}", $stringNorValue, $tempContent);
                        $tempContent = str_ireplace("{cfg_" . $siteConfigName . "}", $stringNorValue, $tempContent);
                        break;
                }
            }
        } else {//移除掉标记
            $patterns = "/\{cfg_(.*)\<\/}/imsU";
            $tempContent = preg_replace($patterns, "", $tempContent);
        }
    }


    /**
     * 取得Web客户端的综合信息
     * @return array 返回储存信息的数据集
     */
    protected function GetWebClientInfo(){
        $arrayOfWebClientInfo = array();

        return $arrayOfWebClientInfo;
    }


    /**
     * 新增管理员操作日志
     * @param string $operateContent 操作内容
     */
    protected function CreateManageUserLog($operateContent)
    {
        $manageUserId = Control::GetManageUserId();
        $manageUserName = Control::GetManageUserName();
        $ipAddress = Control::GetIp();
        $webAgent = Control::GetOs() . " and " . Control::GetBrowser();
        $refererUrl = $_SERVER["HTTP_REFERER"];
        $refererDomain = strtolower(preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $refererUrl));
        $selfUrl = $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
        $userId = Control::GetUserId();
        $userName = Control::GetUserName();
        $manageUserLogManageData = new ManageUserLogManageData();
        $manageUserLogManageData->Create($manageUserId, $manageUserName, $ipAddress, $webAgent, $selfUrl, $refererUrl, $refererDomain, $userId, $userName, $operateContent);
    }

    /**
     * 上传文件
     * @param string $fileElementName 控件名称
     * @param int $tableType 上传文件对应的表类型
     * @param int $tableId 上传文件对应的表id
     * @param int $returnType 返回值的类型
     * @param int $uploadFileId 返回新的上传文件id
     * @return string 返回结果字符串
     */
    protected function Upload($fileElementName = "fileToUpload", $tableType = 0, $tableId = 0, $returnType = 0, &$uploadFileId = 0){
        $result = "";
        $errorMessage = self::UploadPreCheck($fileElementName);

        if (empty($errorMessage) || strlen($errorMessage) <= 0) { //没有错误
            sleep(1);
            $newFileName = "";
            $fileExtension = strtolower(FileObject::GetExtension($_FILES[$fileElementName]['name']));
            $manageUserId = Control::GetManageUserId();
            $userId = Control::GetUserId();

            $uploadPath = PHYSICAL_PATH . DIRECTORY_SEPARATOR . "upload" . DIRECTORY_SEPARATOR;
            //根据不同的业务，构建不同的存储文件夹和文件名
            switch ($tableType) {
                case 1: //资讯题图1
                    if($tableId>0){
                        $dirPath = $uploadPath . "document_news" . DIRECTORY_SEPARATOR . strval($tableId) . DIRECTORY_SEPARATOR . strval(date('Ymd', time())) . DIRECTORY_SEPARATOR;
                        $newFileName = strval($tableId) . '_' . time() . '.' . $fileExtension;
                    }
                    break;
                case 2: //管理任务上传
                    if($manageUserId>0){
                        $dirPath = $uploadPath . "manage_task" . DIRECTORY_SEPARATOR . strval($manageUserId) . DIRECTORY_SEPARATOR . strval(date('Ymd', time())) . DIRECTORY_SEPARATOR;
                        $newFileName = time() . '.' . $fileExtension;
                    }
                    break;
                case 3: //管理任务回复上传
                    if($manageUserId>0){
                        $dirPath = $uploadPath . "manage_task_reply" . DIRECTORY_SEPARATOR . strval($manageUserId) . DIRECTORY_SEPARATOR . strval(date('Ymd', time())) . DIRECTORY_SEPARATOR;
                        $newFileName = time() . '.' . $fileExtension;
                    }
                    break;
                case 4: //咨询问答上传

                    break;
                case 5:
                    if($tableId>0){
                        $dirPath = $uploadPath . "product" . DIRECTORY_SEPARATOR . strval($tableId) . DIRECTORY_SEPARATOR . strval(date('Ymd', time())) . DIRECTORY_SEPARATOR;
                        $newFileName = time() . '.' . $fileExtension;
                    }
                    break;
                case 6: //广告图片上传
                    $dirPath = $uploadPath . "ad" . DIRECTORY_SEPARATOR . strval($tableId) . DIRECTORY_SEPARATOR;
                    $newFileName = time() . '.' . $fileExtension;
                    break;
                case 7:         //活动类题图上传
                    $dirPath = $uploadPath . "activity" . DIRECTORY_SEPARATOR . strval(date('Ymd', time())) . DIRECTORY_SEPARATOR . strval($userId) . "_";
                    $newFileName = time() . '.' . $fileExtension;
                    break;
                case 8://产品参数类型选项
                    $dirPath = $uploadPath . "productoption" . DIRECTORY_SEPARATOR . strval($tableId) . DIRECTORY_SEPARATOR . strval(date('Ymd', time())) . DIRECTORY_SEPARATOR;
                    $newFileName = time() . '.' . $fileExtension;
                    break;
                case 9://用户组
                    $dirPath = $uploadPath . "usergroup" . DIRECTORY_SEPARATOR . strval(date('Ymd', time())) . DIRECTORY_SEPARATOR;
                    $newFileName = time() . '.' . $fileExtension;
                    break;
                case 10: //会员头像

                    break;
                case 11: //用户相册

                    break;
                case 12://产品参数类型
                    $dirPath = $uploadPath . "productparamtype" . DIRECTORY_SEPARATOR . strval($documentchannelid) . DIRECTORY_SEPARATOR . strval(date('Ymd', time())) . DIRECTORY_SEPARATOR;
                    $newFileName = time() . '.' . strtolower(File::GetEx($_FILES[$fileElementName]['name']));
                    break;
                case 13://友情链接类
                    $dirPath = $uploadPath . "sitelink" . DIRECTORY_SEPARATOR . strval(date('Ymd', time())) . DIRECTORY_SEPARATOR;
                    $newFileName = time() . '.' . strtolower(File::GetEx($_FILES[$fileElementName]['name']));
                    break;
                case 14://自定义页面类
                    $dirPath = $uploadPath . "sitecontent" . DIRECTORY_SEPARATOR . strval(date('Ymd', time())) . DIRECTORY_SEPARATOR;
                    $newFileName = time() . '.' . strtolower(File::GetEx($_FILES[$fileElementName]['name']));
                    break;
                case 15://用户活动花絮图片上传

                    break;
                case 16:         //站点配置图片上传
                    $dirPath = $uploadPath . "siteconfig" . DIRECTORY_SEPARATOR . strval($siteid) . DIRECTORY_SEPARATOR;
                    $newFileName = time() . '.' . strtolower(File::GetEx($_FILES[$fileElementName]['name']));
                    break;
                case 17: //论坛帖子
                    $dirPath = $uploadPath . "forum" . DIRECTORY_SEPARATOR . "postcontent" . DIRECTORY_SEPARATOR . strval($_forumId) . DIRECTORY_SEPARATOR . strval(date('Ymd', time())) . DIRECTORY_SEPARATOR;
                    $newFileName = $_forumId . '_' . time() . '.' . strtolower(File::GetEx($_FILES[$fileElementName]['name']));
                    break;
                case 18://用户等级
                    $dirPath = $uploadPath . "userlevel" . DIRECTORY_SEPARATOR . strval(date('Ymd', time())) . DIRECTORY_SEPARATOR;
                    $newFileName = time() . '.' . strtolower(File::GetEx($_FILES[$fileElementName]['name']));
                    break;
                case 19://自定义表单
                    $dirPath = $uploadPath . "customform" . DIRECTORY_SEPARATOR . strval(date('Ymd', time())) . DIRECTORY_SEPARATOR;
                    $newFileName = time() . '.' . strtolower(File::GetEx($_FILES[$fileElementName]['name']));
                    break;
                case 20://频道图片1
                    $dirPath = $uploadPath . "docchannel" . DIRECTORY_SEPARATOR . "parentid_" . strval($documentchannelid) . DIRECTORY_SEPARATOR;
                    $newFileName = 'parentid_' . $documentchannelid . '_' . time() . '.' . strtolower(File::GetEx($_FILES[$fileElementName]['name']));
                    break;
                case 21://会员附件
                    if ($userId == "" || $userId < 0) {
                        $userId = Control::GetRequest("userid", 0);
                    }
                    $dirPath = $uploadPath . "userattachment" . DIRECTORY_SEPARATOR . strval($userId) . DIRECTORY_SEPARATOR;
                    $newFileName = 'useratt_' . $userId . '_' . time() . '.' . strtolower(File::GetEx($_FILES[$fileElementName]['name']));
                    break;
                case 22://投票选项图片
                    $dirPath = $uploadPath . "voteitem" . DIRECTORY_SEPARATOR . strval(date('Ymd', time())) . DIRECTORY_SEPARATOR;
                    $newFileName = time() . '.' . strtolower(File::GetEx($_FILES[$fileElementName]['name']));
                    break;
                case 23://新闻题图,移动终端使用
                    break;
                case 24://新闻题图,平板电脑使用
                    break;
                case 25://移动客户端投稿
                    break;
                case 26:        //会员心情图标
                    $dirPath = $uploadPath . "usermood" . DIRECTORY_SEPARATOR . strval($documentchannelid) . DIRECTORY_SEPARATOR . strval(date('Ymd', time())) . DIRECTORY_SEPARATOR;
                    $newFileName = $documentchannelid . '_usermood_' . time() . '.' . strtolower(File::GetEx($_FILES[$fileElementName]['name']));
                    break;
                case 27://考试类用
                    $dirPath = $uploadPath . "exam" . DIRECTORY_SEPARATOR . strval($documentchannelid) . DIRECTORY_SEPARATOR . strval(date('Ymd', time())) . DIRECTORY_SEPARATOR;
                    $newFileName = time() . '.' . strtolower(File::GetEx($_FILES[$fileElementName]['name']));
                    break;
                case 28://会员签名图标
                    if ($userId == "" || $userId < 0) {
                        $userId = Control::GetRequest("userid", 0);
                    }
                    $dirPath = $uploadPath . "usersign" . DIRECTORY_SEPARATOR . strval($userId) . DIRECTORY_SEPARATOR;
                    $newFileName = time() . '.' . strtolower(File::GetEx($_FILES[$fileElementName]['name']));
                    break;
                case 29:  //资讯题图2
                    $dirPath = $uploadPath . "docnews" . DIRECTORY_SEPARATOR . strval($documentchannelid) . DIRECTORY_SEPARATOR . strval(date('Ymd', time())) . DIRECTORY_SEPARATOR;
                    $newFileName = $documentchannelid . '_' . time() . '.' . strtolower(File::GetEx($_FILES[$fileElementName]['name']));
                    break;
                case 30:   //资讯题图3
                    $dirPath = $uploadPath . "docnews" . DIRECTORY_SEPARATOR . strval($documentchannelid) . DIRECTORY_SEPARATOR . strval(date('Ymd', time())) . DIRECTORY_SEPARATOR;
                    $newFileName = $documentchannelid . '_' . time() . '.' . strtolower(File::GetEx($_FILES[$fileElementName]['name']));
                    break;
                case 31:   //资讯内容图
                    $dirPath = $uploadPath . "docnews" . DIRECTORY_SEPARATOR . strval($documentchannelid) . DIRECTORY_SEPARATOR . strval(date('Ymd', time())) . DIRECTORY_SEPARATOR;
                    $newFileName = $documentchannelid . '_' . time() . '.' . strtolower(File::GetEx($_FILES[$fileElementName]['name']));
                    break;
            }

            if (!empty($dirPath) && strlen($dirPath) > 0 && !empty($newFileName) && strlen($newFileName) > 0) {
                FileObject::CreateDir($dirPath);
                $moveResult = move_uploaded_file($_FILES[$fileElementName]['tmp_name'], $dirPath . $newFileName);

                if ($moveResult) {
                    //数据库操作
                    $uploadFileData = new UploadFileData();
                    $uploadFileId = $uploadFileData->Create(
                        $newFileName, $_FILES[$fileElementName]['size'], $fileExtension, strtolower($_FILES[$fileElementName]['name']), str_ireplace(P_PATH, "", $dirPath), $tableType, $tableId, strval(date('Y', strtotime('-8 hour'))), strval(date('m', strtotime('-8 hour'))), strval(date('d', strtotime('-8 hour'))), $manageUserId, $userId
                    );

                    //返回值处理
                    $returnDirPath = str_ireplace(P_PATH, "", $dirPath);

                    $returnFilePath = $returnDirPath . $newFileName;
                    $returnFilePath = str_ireplace("\\", "/", $returnFilePath);

                    $resultMessage = Format::FormatUploadFileToHtml($returnFilePath, $fileExtension, $uploadFileId, $_FILES[$fileElementName]['name']);
                    if ($returnType === 0) {
                        $result .= "{";
                        $result .= "error: '" . $errorMessage . "',\n";
                        $result .= "result: '" . $resultMessage . "',\n";
                        $result .= "fileid: '" . $uploadFileId . "',\n";
                        $result .= "fileurl: '" . $returnFilePath . "'\n";
                        $result .= "}";
                    } else if ($returnType === 1) {
                        $result = $returnFilePath;
                    } else if ($returnType === 2) {
                        $result = $uploadFileId;
                    }
                } else { //移动上传文件时失败
                    $result = Language::Load('uploadfile', 20);
                }
            }
        } else {
            $result = $errorMessage;
        }
        @unlink($_FILES[$fileElementName]);
        return $result;
    }


    /**
     * 上传文件错误代码：没有错误
     */
    const UPLOAD_ERROR_NO_ERROR = 1;
    /**
     * 上传文件错误代码：未操作
     */
    const UPLOAD_ERROR_NO_ACTION = 0;
    /**
     * 上传文件错误：PHP temp文件夹未设置
     */
    const UPLOAD_ERROR_TMP_IS_NULL = -5;
    /**
     * 上传文件错误：文件太大
     */
    const UPLOAD_ERROR_TOO_LARGE_FOR_SERVER = -1;
    /**
     * 上传文件错误：文件太大，超出了HTML表单的限制
     */
    const UPLOAD_ERROR_TOO_LARGE_FOR_HTML = -2;
    /**
     * 上传文件错误：文件中只有一部分内容完成了上传
     */
    const UPLOAD_ERROR_ONLY_PARTIALLY_UPLOADED = -3;
    /**
     * 上传文件错误：没有找到要上传的文件
     */
    const UPLOAD_ERROR_NO_FILE = -4;
    /**
     * 上传文件错误：服务器临时文件夹丢失
     */
    const UPLOAD_ERROR_TEMPORARY_FOLDER_IS_MISSING = -5;
    /**
     * 上传文件错误： 文件写入到临时文件夹出错
     */
    const UPLOAD_ERROR_FAILED_TO_WRITE_TO_THE_TEMPORARY_FOLDER = -6;
    /**
     * 上传文件错误：文件夹没有写入权限
     */
    const UPLOAD_ERROR_NO_RIGHT_TO_WRITE_TEMPORARY = -7;
    /**
     * 上传文件错误：扩展使文件上传停止
     */
    const UPLOAD_ERROR_PLUGINS_MADE_UPLOAD_STOP = -8;
    /**
     * 上传文件错误：没有可以显示的错误信息
     */
    const UPLOAD_ERROR_NO_MESSAGE = -9;
    /**
     * 上传文件错误：文件类型错误，不允许此类文件上传
     */
    const UPLOAD_ERROR_FILE_TYPE = -10;

    /**
     * 上传文件预检查
     */
    private function UploadPreCheck($fileElementName)
    {
        $errorMessage = self::UPLOAD_ERROR_NO_ERROR;

        /////////////////////////检查temp文件夹///////////////////////////
        if (empty($_FILES[$fileElementName]['tmp_name'])) {
            return self::UPLOAD_ERROR_TMP_IS_NULL;
        }
        if ($_FILES[$fileElementName]['tmp_name'] == 'none') {
            return self::UPLOAD_ERROR_TMP_IS_NULL;
        }
        /////////////////////////检查错误信息///////////////////////////
        if (!empty($_FILES[$fileElementName]['error'])) {
            switch ($_FILES[$fileElementName]['error']) {
                case '1':
                    $errorMessage = self::UPLOAD_ERROR_TOO_LARGE_FOR_SERVER;
                    break;
                case '2':
                    $errorMessage = self::UPLOAD_ERROR_TOO_LARGE_FOR_HTML;
                    break;
                case '3':
                    $errorMessage = self::UPLOAD_ERROR_ONLY_PARTIALLY_UPLOADED;
                    break;
                case '4':
                    $errorMessage = self::UPLOAD_ERROR_NO_FILE;
                    break;
                case '5':
                    $errorMessage = self::UPLOAD_ERROR_TEMPORARY_FOLDER_IS_MISSING;
                    break;
                case '6':
                    $errorMessage = self::UPLOAD_ERROR_FAILED_TO_WRITE_TO_THE_TEMPORARY_FOLDER;
                    break;
                case '7':
                    $errorMessage = self::UPLOAD_ERROR_NO_RIGHT_TO_WRITE_TEMPORARY;
                    break;
                case '8':
                    $errorMessage = self::UPLOAD_ERROR_PLUGINS_MADE_UPLOAD_STOP;
                    break;
                default:
                    $errorMessage = self::UPLOAD_ERROR_NO_MESSAGE;
            }
            return $errorMessage;
        }
        /////////////////////////检查安全文件后缀///////////////////////////
        $fileExtension = FileObject::GetExtension($_FILES[$fileElementName]['name']);
        $fileExtension = strtolower($fileExtension);

        //判断文件类型
        if ($fileExtension == "jpg" ||
            $fileExtension == "gif" ||
            $fileExtension == "png" ||
            $fileExtension == "bmp" ||
            $fileExtension == "swf" ||
            $fileExtension == "doc" ||
            $fileExtension == "docx" ||
            $fileExtension == "xls" ||
            $fileExtension == "xlsx" ||
            $fileExtension == "csv" ||
            $fileExtension == "pdf" ||
            $fileExtension == "wmv" ||
            $fileExtension == "mp4" ||
            $fileExtension == "flv" ||
            $fileExtension == "rar" ||
            $fileExtension == "jpeg"
        ) {
        } else {
            $errorMessage = self::UPLOAD_ERROR_FILE_TYPE;
        }
        return $errorMessage;
    }

}

?>
