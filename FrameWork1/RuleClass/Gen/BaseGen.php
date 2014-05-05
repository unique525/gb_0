<?php

/**
 * 所有Gen的基类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen
 * @author zhangchi
 */
class BaseGen
{

    /**
     * 在模板替换前的统一替换
     * @param string $tempContent 要处理的模板
     */
    public function ReplaceFirst(&$tempContent)
    {
        ///////找出PreTemp标记/////////
        $keyName = "pre_temp";
        $arr = Template::GetAllCustomTag($tempContent, $keyName);
        if (isset($arr)) {
            if (count($arr) > 1) {
                $arr2 = $arr[1];
                foreach ($arr2 as $val) {
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
                if (!empty($arr2)) {
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
    public function ReplaceEnd(&$tempContent)
    {
        $templateName = self::GetTemplateName();
        $selectTemplate = Template::Load("select_template.html", "common");
        $commonHead = Template::Load("manage/common_head.html", "common");
        $commonBodyDeal = Template::Load("manage/common_body_deal.html", "common");
        $commonBodyList = Template::Load("manage/common_body_list.html", "common");
        $tempContent = str_ireplace("{common_head}", $commonHead, $tempContent);
        $tempContent = str_ireplace("{common_body_deal}", $commonBodyDeal, $tempContent);
        $tempContent = str_ireplace("{common_body_list}", $commonBodyList, $tempContent);
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
    private function GetTemplateName()
    {
        $templateName = Control::GetManageUserTemplateName();
        if (strlen($templateName) <= 0) {
            $templateName = "default";
        }
        return $templateName;
    }

    /**
     * 是否是安全IP
     * @return boolean
     */
    public function IsSecurityIp()
    {
        $ip = Control::GetIP();
        $isInnerIp = false; //是否安全IP
        //
        //安全登录IP，不需要短信认证
        //$SecurityIP = array('130.1.0', '20.20.20', '40.40.40');
        $securityIp = explode('|', SECURITY_IP);
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
    protected function ShowError($errorContent)
    {
        $errorTemplate = Template::Load("error.html", "common");
        $errorTemplate = str_ireplace("{error_content}", $errorContent, $errorTemplate);
        self::ReplaceEnd($errorTemplate);
        return $errorTemplate;
    }

    /**
     * 替换模板中的配置标记
     * @param int $siteId 站点id
     * @param string $tempContent 模板内容
     */
    protected function ReplaceSiteConfig($siteId, &$tempContent)
    {
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
        } else { //移除掉标记
            $patterns = '/\{cfg_(.*)\<\/}/imsU';
            $tempContent = preg_replace($patterns, "", $tempContent);
        }
    }


    /**
     * 取得Web客户端的综合信息
     * @return array 返回储存信息的数据集
     */
    protected function GetWebClientInfo()
    {
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
        $refererDomain = strtolower(preg_replace('/https?:\/\/([^\:\/]+).*/i', "\\1", $refererUrl));
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
     * @return string|int 返回结果字符串，或错误代码
     */
    protected function Upload($fileElementName = "fileToUpload", $tableType = 0, $tableId = 0, $returnType = 0, &$uploadFileId = 0)
    {
        $result = "";
        $errorMessage = self::UploadPreCheck($fileElementName);

        if ($errorMessage > 0) { //没有错误
            sleep(1);
            $newFileName = "";
            $fileExtension = strtolower(FileObject::GetExtension($_FILES[$fileElementName]['name']));
            $manageUserId = Control::GetManageUserId();
            $userId = Control::GetUserId();

            $uploadPath = PHYSICAL_PATH . DIRECTORY_SEPARATOR . "upload" . DIRECTORY_SEPARATOR;

            $dirPath = self::GetUploadFilePath($tableType, $tableId, $manageUserId, $userId, $uploadPath, $fileExtension, $newFileName);

            if (!empty($dirPath) && strlen($dirPath) > 0 && !empty($newFileName) && strlen($newFileName) > 0) {
                FileObject::CreateDir($dirPath);
                $moveResult = move_uploaded_file($_FILES[$fileElementName]['tmp_name'], $dirPath . $newFileName);

                if ($moveResult) {
                    //数据库操作
                    $uploadFileManageData = new UploadFileManageData();
                    $uploadFileId = $uploadFileManageData->Create(
                        $newFileName,
                        $_FILES[$fileElementName]['size'],
                        $fileExtension,
                        strtolower($_FILES[$fileElementName]['name']),
                        str_ireplace(PHYSICAL_PATH, "", $dirPath),
                        $tableType,
                        $tableId,
                        strval(date('Y', time())),
                        strval(date('m', time())),
                        strval(date('d', time())),
                        $manageUserId,
                        $userId
                    );

                    //返回值处理
                    $returnDirPath = str_ireplace(PHYSICAL_PATH, "", $dirPath);

                    $returnFilePath = $returnDirPath . $newFileName;
                    $returnFilePath = str_ireplace("\\", "/", $returnFilePath);

                    $resultMessage = Format::FormatUploadFileToHtml($returnFilePath, $fileExtension, $uploadFileId, $_FILES[$fileElementName]['name']);
                    if ($returnType === 0) {
                        $result .= "{";
                        $result .= "error: '" . $errorMessage . "',\n";
                        $result .= "result: '" . $resultMessage . "',\n";
                        $result .= "file_id: '" . $uploadFileId . "',\n";
                        $result .= "file_url: '" . $returnFilePath . "'\n";
                        $result .= "}";
                    } else if ($returnType === 1) {
                        $result = $returnFilePath;
                    } else if ($returnType === 2) {
                        $result = $uploadFileId;
                    }
                } else { //移动上传文件时失败
                    $result = self::UPLOAD_ERROR_MOVE_FILE_TO_DESTINATION;
                }
            }else{
                $result = self::UPLOAD_ERROR_PATH;
            }
        } else {
            $result = $errorMessage;
        }
        UnLink($_FILES[$fileElementName]);
        return $result;
    }

    private function GetUploadFilePath($tableType, $tableId, $manageUserId, $userId, $uploadPath, $fileExtension, &$newFileName)
    {
        //根据不同的业务，构建不同的存储文件夹和文件名
        $uploadFilePath = "";
        $date = strval(date('Ymd', time()));
        switch ($tableType) {
            case UploadFileManageData::UPLOAD_TABLE_TYPE_DOCUMENT_NEWS_TITLE_PIC_1:
                /**资讯题图1   tableId 为 channelId  */
                if ($tableId > 0) {
                    $uploadFilePath = $uploadPath . "document_news" . DIRECTORY_SEPARATOR . strval($tableId) . DIRECTORY_SEPARATOR . $date . DIRECTORY_SEPARATOR;
                    $newFileName = 'title_pic1_' . time() . '.' . $fileExtension;
                }
                break;
            case UploadFileManageData::UPLOAD_TABLE_TYPE_DOCUMENT_NEWS_TITLE_PIC_2:
                /**资讯题图2   tableId 为 channelId  */
                if ($tableId > 0) {
                    $uploadFilePath = $uploadPath . "document_news" . DIRECTORY_SEPARATOR . strval($tableId) . DIRECTORY_SEPARATOR . $date . DIRECTORY_SEPARATOR;
                    $newFileName = 'title_pic2_' . time() . '.' . $fileExtension;
                }
                break;
            case UploadFileManageData::UPLOAD_TABLE_TYPE_DOCUMENT_NEWS_TITLE_PIC_3:
                /**资讯题图3   tableId 为 channelId  */
                if ($tableId > 0) {
                    $uploadFilePath = $uploadPath . "document_news" . DIRECTORY_SEPARATOR . strval($tableId) . DIRECTORY_SEPARATOR . $date . DIRECTORY_SEPARATOR;
                    $newFileName = 'title_pic3_' . time() . '.' . $fileExtension;
                }
                break;
            case UploadFileManageData::UPLOAD_TABLE_TYPE_DOCUMENT_NEWS_CONTENT:
                /**资讯内容图   tableId 为 channelId  */
                if ($tableId > 0) {
                    $uploadFilePath = $uploadPath . "document_news" . DIRECTORY_SEPARATOR . strval($tableId) . DIRECTORY_SEPARATOR . $date . DIRECTORY_SEPARATOR;
                    $newFileName = 'document_news_content_' . time() . '.' . $fileExtension;
                }
                break;
            case UploadFileManageData::UPLOAD_TABLE_TYPE_MANAGE_TASK:
                /**管理任务上传  */
                if ($manageUserId > 0) {
                    $uploadFilePath = $uploadPath . "manage_task" . DIRECTORY_SEPARATOR . strval($manageUserId) . DIRECTORY_SEPARATOR . $date . DIRECTORY_SEPARATOR;
                    $newFileName = time() . '.' . $fileExtension;
                }
                break;
            case UploadFileManageData::UPLOAD_TABLE_TYPE_MANAGE_TASK_REPLY:
                /**管理任务回复上传  */
                if ($manageUserId > 0) {
                    $uploadFilePath = $uploadPath . "manage_task_reply" . DIRECTORY_SEPARATOR . strval($manageUserId) . DIRECTORY_SEPARATOR . $date . DIRECTORY_SEPARATOR;
                    $newFileName = time() . '.' . $fileExtension;
                }
                break;
            case UploadFileManageData::UPLOAD_TABLE_TYPE_QUESTION: //咨询问答上传

                break;
            case UploadFileManageData::UPLOAD_TABLE_TYPE_PRODUCT_TITLE_PIC:
                /**产品题图   tableId 为 channelId  */
                if ($tableId > 0) {
                    $uploadFilePath = $uploadPath . "product" . DIRECTORY_SEPARATOR . strval($tableId) . DIRECTORY_SEPARATOR . $date . DIRECTORY_SEPARATOR;
                    $newFileName = time() . '.' . $fileExtension;
                }
                break;
            case UploadFileManageData::UPLOAD_TABLE_TYPE_PRODUCT_PARAM_OPTION:
                //产品参数类型选项
                $uploadFilePath = $uploadPath . "product_option" . DIRECTORY_SEPARATOR . strval($tableId) . DIRECTORY_SEPARATOR . $date . DIRECTORY_SEPARATOR;
                $newFileName = time() . '.' . $fileExtension;
                break;
            case UploadFileManageData::UPLOAD_TABLE_TYPE_PRODUCT_PARAM_TYPE:
                //产品参数类型
                $uploadFilePath = $uploadPath . "product_param_type" . DIRECTORY_SEPARATOR . strval($tableId) . DIRECTORY_SEPARATOR . $date . DIRECTORY_SEPARATOR;
                $newFileName = time() . '.' . $fileExtension;
                break;
            case UploadFileManageData::UPLOAD_TABLE_TYPE_AD_CONTENT:
                /**广告图片上传 tableId 为 siteId */
                $uploadFilePath = $uploadPath . "ad" . DIRECTORY_SEPARATOR . strval($tableId) . DIRECTORY_SEPARATOR;
                $newFileName = time() . '.' . $fileExtension;
                break;
            case UploadFileManageData::UPLOAD_TABLE_TYPE_ACTIVITY_TITLE_PIC:
                //活动类题图上传
                $uploadFilePath = $uploadPath . "activity" . DIRECTORY_SEPARATOR . $date . DIRECTORY_SEPARATOR . strval($userId) . "_";
                $newFileName = time() . '.' . $fileExtension;
                break;
            case UploadFileManageData::UPLOAD_TABLE_TYPE_ACTIVITY_PIC:
                //活动花絮图片上传

                break;
            case UploadFileManageData::UPLOAD_TABLE_TYPE_USER_GROUP:
                //会员组
                $uploadFilePath = $uploadPath . "user_group" . DIRECTORY_SEPARATOR . $date . DIRECTORY_SEPARATOR;
                $newFileName = time() . '.' . $fileExtension;
                break;
            case UploadFileManageData::UPLOAD_TABLE_TYPE_USER_AVATAR:
                //会员头像

                break;
            case UploadFileManageData::UPLOAD_TABLE_TYPE_USER_ALBUM_COVER:
                //会员相册封面

                break;
            case UploadFileManageData::UPLOAD_TABLE_TYPE_SITE_LINK:
                //友情链接类
                $uploadFilePath = $uploadPath . "site_link" . DIRECTORY_SEPARATOR . $date . DIRECTORY_SEPARATOR;
                $newFileName = time() . '.' . $fileExtension;
                break;
            case UploadFileManageData::UPLOAD_TABLE_TYPE_SITE_CONTENT: //自定义页面类
                $uploadFilePath = $uploadPath . "site_content" . DIRECTORY_SEPARATOR . $date . DIRECTORY_SEPARATOR;
                $newFileName = time() . '.' . $fileExtension;
                break;
            case UploadFileManageData::UPLOAD_TABLE_TYPE_SITE_CONFIG:
                /** 站点配置图片上传 tableId 为 siteId */
                $uploadFilePath = $uploadPath . "site_config" . DIRECTORY_SEPARATOR . strval($tableId) . DIRECTORY_SEPARATOR;
                $newFileName = time() . '.' . $fileExtension;
                break;
            case UploadFileManageData::UPLOAD_TABLE_TYPE_FORUM_PIC_1:
                /** 论坛版块图标1 */
                $uploadFilePath = $uploadPath . "forum" . DIRECTORY_SEPARATOR . strval($tableId) . DIRECTORY_SEPARATOR . $date . DIRECTORY_SEPARATOR;
                $newFileName = $tableId . '_' . time() . '.' . $fileExtension;
                break;
            case UploadFileManageData::UPLOAD_TABLE_TYPE_FORUM_PIC_2:
                /** 论坛版块图标2 */
                $uploadFilePath = $uploadPath . "forum" . DIRECTORY_SEPARATOR . strval($tableId) . DIRECTORY_SEPARATOR . $date . DIRECTORY_SEPARATOR;
                $newFileName = $tableId . '_' . time() . '.' . $fileExtension;
                break;
            case UploadFileManageData::UPLOAD_TABLE_TYPE_FORUM_POST_CONTENT:
                /** 论坛帖子内容 */
                $uploadFilePath = $uploadPath . "forum_post" . DIRECTORY_SEPARATOR . strval($tableId) . DIRECTORY_SEPARATOR . $date . DIRECTORY_SEPARATOR;
                $newFileName = $tableId . '_' . time() . '.' . $fileExtension;
                break;
            case 19: //自定义表单
                $uploadFilePath = $uploadPath . "custom_form" . DIRECTORY_SEPARATOR . $date . DIRECTORY_SEPARATOR;
                $newFileName = time() . '.' . $fileExtension;
                break;
            case 20:
                /** 频道图片1 tableId 为 channelId */
                $uploadFilePath = $uploadPath . "channel" . DIRECTORY_SEPARATOR . "parent_id_" . strval($tableId) . DIRECTORY_SEPARATOR;
                $newFileName = 'parent_id_' . $tableId . '_' . time() . '.' . $fileExtension;
                break;
            case UploadFileManageData::UPLOAD_TABLE_TYPE_USER_LEVEL:
                /** 会员等级 */
                $uploadFilePath = $uploadPath . "user_level" . DIRECTORY_SEPARATOR . $date . DIRECTORY_SEPARATOR;
                $newFileName = time() . '.' . $fileExtension;
                break;
            case UploadFileManageData::UPLOAD_TABLE_TYPE_USER_ATTACHMENT:
                /** 会员附件 */
                if ($userId > 0) {
                    $uploadFilePath = $uploadPath . "user_attachment" . DIRECTORY_SEPARATOR . strval($userId) . DIRECTORY_SEPARATOR;
                    $newFileName = 'user_attachment_' . $userId . '_' . time() . '.' . $fileExtension;
                }
                break;
            case 22: //投票选项图片
                $uploadFilePath = $uploadPath . "vote_item" . DIRECTORY_SEPARATOR . strval($tableId) . DIRECTORY_SEPARATOR . $date . DIRECTORY_SEPARATOR;
                $newFileName = time() . '.' . $fileExtension;
                break;
            case UploadFileManageData::UPLOAD_TABLE_TYPE_DOCUMENT_NEWS_TITLE_PIC_MOBILE: //新闻题图,移动终端使用
                //由系统自动生成
                break;
            case UploadFileManageData::UPLOAD_TABLE_TYPE_DOCUMENT_NEWS_TITLE_PIC_PAD: //新闻题图,平板电脑使用
                //由系统自动生成
                break;
            case 25: //移动客户端投稿
                break;
            case 26: //会员心情图标 tableId 为 siteId
                $uploadFilePath = $uploadPath . "user_mood" . DIRECTORY_SEPARATOR . strval($tableId) . DIRECTORY_SEPARATOR . $date . DIRECTORY_SEPARATOR;
                $newFileName = $tableId . '_user_mood_' . time() . '.' . $fileExtension;
                break;
            case 27: //考试类用
                $uploadFilePath = $uploadPath . "exam" . DIRECTORY_SEPARATOR . strval($tableId) . DIRECTORY_SEPARATOR . $date . DIRECTORY_SEPARATOR;
                $newFileName = time() . '.' . $fileExtension;
                break;
            case 28: //会员签名图标
                if ($userId > 0) {
                    $uploadFilePath = $uploadPath . "user_sign" . DIRECTORY_SEPARATOR . strval($userId) . DIRECTORY_SEPARATOR;
                    $newFileName = time() . '.' . $fileExtension;
                }
                break;
        }
        return $uploadFilePath;
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
     * 上传文件错误：生成上传文件路径和文件名时出错
     */
    const UPLOAD_ERROR_PATH = -11;
    /**
     * 上传文件错误：移动上传文件到目标路径时失败
     */
    const UPLOAD_ERROR_MOVE_FILE_TO_DESTINATION = -12;

    /**
     * 上传文件预检查
     * @param string $fileElementName 上传控件名称
     * @return int 错误代码，默认返回1，没有错误
     */
    private function UploadPreCheck($fileElementName)
    {
        $errorMessage = self::UPLOAD_ERROR_NO_ERROR;

        if(empty($_FILES)){
            return self::UPLOAD_ERROR_NO_ACTION;
        }

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
