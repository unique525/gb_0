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
     * @param string $templateContent 要处理的模板
     */
    public function ReplaceFirst(&$templateContent)
    {
        ///////找出PreTemp标记/////////  <pre_temp id="2"></pre_temp>
        $tagName = "pre_temp";
        $arrSimpleCustomTags = Template::GetAllCustomTag($templateContent, $tagName);
        if (isset($arrSimpleCustomTags)) {
            if (count($arrSimpleCustomTags) > 1) {
                $arrTempContents = $arrSimpleCustomTags[1];
                foreach ($arrTempContents as $val) {
                    $docContent = "<$tagName$val</$tagName>";
                    //模板ID
                    $channelTemplateId = Template::GetParamValue($docContent, "id", $tagName);
                    $channelTemplateManageData = new ChannelTemplateManageData();
                    $preTempContent = $channelTemplateManageData->GetChannelTemplateContent($channelTemplateId, false);
                    $templateContent = Template::ReplaceCustomTag($templateContent, $channelTemplateId, $preTempContent, $tagName);
                }
            }
        }
        ///////找出site_content标记/////////
        $tagName = "site_content";
        $arrSimpleCustomTags = Template::GetAllCustomTag($templateContent, $tagName);
        if (isset($arrSimpleCustomTags)) {
            if (count($arrSimpleCustomTags) > 1) {
                $arrTempContents = $arrSimpleCustomTags[1];
                foreach ($arrTempContents as $val) {
                    $docContent = "<$tagName$val</$tagName>";
                    //模板ID
                    $siteContentId = Template::GetParamValue($docContent, "id", $tagName);
                    $siteContentManageData = new SiteContentManageData();
                    $siteContent = $siteContentManageData->GetSiteContentValue($siteContentId, false);
                    $templateContent = Template::ReplaceCustomTag($templateContent, $siteContentId, $siteContent, $tagName);
                }
            }
        }

        ///////找出SiteAd标记/////////
        $keyName = "site_ad";
        $arr = Template::GetAllCustomTag($templateContent, $keyName);
        if (isset($arr)) {
            if (count($arr) > 1) {
                $arr2 = $arr[1];
                foreach ($arr2 as $key => $val) {
                    $docContent = "<$keyName$val</$keyName>";
                    $siteAdId = Template::GetParamValue($docContent, "id", $keyName);

                    $adgen = new AdGen();
                    $pre_content = $adgen->GenFormatAd($siteAdId);
                    $templateContent = Template::ReplaceSiteAd($templateContent, $siteAdId, $pre_content);
                }
            }
        }


        //channel_name
        $keyName = "channel_name";
        $arr = Template::GetAllCustomTag($templateContent, $keyName);
        if (isset($arr)) {
            if (count($arr) > 1) {
                $arr2 = $arr[1];
                if (!empty($arr2)) {
                    $channelManageData = new ChannelManageData();
                    foreach ($arr2 as $val) {
                        $docContent = "<$keyName$val</$keyName>";
                        $channelId = Template::GetParamValue($docContent, "id", $keyName);
                        $channelName = $channelManageData->GetChannelName($channelId, false);
                        $templateContent = Template::ReplaceCustomTag($templateContent, $channelId, $channelName, $tagName);
                    }
                }
            }
        }
    }

    /**
     * 在模板替换后的统一替换
     * @param string $templateContent 要处理的模板
     */
    public function ReplaceEnd(&$templateContent)
    {
        $templateName = self::GetTemplateName();
        $selectTemplate = Template::Load("select_template.html", "common");
        $commonHead = Template::Load("manage/common_head.html", "common");
        $commonBodyDeal = Template::Load("manage/common_body_deal.html", "common");
        $commonBodyList = Template::Load("manage/common_body_list.html", "common");

        $tabIndex = Control::GetRequest("tab_index", 0);

        $manageDomainForRex = str_ireplace("http://", "", MANAGE_DOMAIN);
        $manageDomainForRex = str_ireplace("https://", "", $manageDomainForRex);
        $manageDomainForRex = str_ireplace(".", "\.", $manageDomainForRex);

        $templateContent = str_ireplace("{tab_index}", $tabIndex, $templateContent);
        $templateContent = str_ireplace("{common_head}", $commonHead, $templateContent);
        $templateContent = str_ireplace("{common_body_deal}", $commonBodyDeal, $templateContent);
        $templateContent = str_ireplace("{common_body_list}", $commonBodyList, $templateContent);
        $templateContent = str_ireplace("{system_name}", SYSTEM_NAME, $templateContent);
        $templateContent = str_ireplace("{relative_path}", RELATIVE_PATH, $templateContent);
        $templateContent = str_ireplace("{manage_domain}", MANAGE_DOMAIN, $templateContent);
        $templateContent = str_ireplace("{manage_domain_rex}", $manageDomainForRex, $templateContent);
        $templateContent = str_ireplace("{webapp_domain}", WEBAPP_DOMAIN, $templateContent);
        $templateContent = str_ireplace("{template_name}", $templateName, $templateContent);
        $templateContent = str_ireplace("{select_template}", $selectTemplate, $templateContent);
        $templateContent = str_ireplace("{template_selected_$templateName}", "_selected", $templateContent);
        $templateContent = str_ireplace("{template_selected_default}", "", $templateContent);
        $templateContent = str_ireplace("{template_selected_deepblue}", "", $templateContent);
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
     * 替换模板中的站点信息
     * @param int $siteId 站点id
     * @param string $templateContent 模板内容
     */
    protected function ReplaceSiteInfo($siteId, &$templateContent){
        if($siteId>0){

            $sitePublicData = new SitePublicData();
            $arrSiteInfo = $sitePublicData->GetOne($siteId);

            Template::ReplaceOne($templateContent,$arrSiteInfo);
        }
    }

    /**
     * 替换模板中的频道信息
     * @param int $channelId 频道id
     * @param string $templateContent 模板内容
     */
    protected function ReplaceChannelInfo($channelId, &$templateContent){
        if($channelId>0){

            $channelPublicData = new ChannelPublicData();
            $arrChannelInfo = $channelPublicData->GetOne($channelId);

            Template::ReplaceOne($templateContent,$arrChannelInfo);
        }
    }

    /**
     * 替换模板中的配置标记
     * @param int $siteId 站点id
     * @param string $templateContent 模板内容
     */
    protected function ReplaceSiteConfig($siteId, &$templateContent)
    {
        $siteConfigData = new SiteConfigData($siteId);
        $uploadFileData = new UploadFileData();
        $arrSiteConfigOne = $siteConfigData->GetList($siteId, true);

        if (count($arrSiteConfigOne) > 0) {
            for ($i = 0; $i < count($arrSiteConfigOne); $i++) {
                $siteConfigName = $arrSiteConfigOne[$i]["SiteConfigName"];
                $stringNorValue = $arrSiteConfigOne[$i]["StringNorValue"];
                $stringMidValue = $arrSiteConfigOne[$i]["StringMidValue"];
                $textValue = $arrSiteConfigOne[$i]["TextValue"];
                $intValue = $arrSiteConfigOne[$i]["IntValue"];
                $numValue = $arrSiteConfigOne[$i]["NumValue"];
                $uploadFileId = $arrSiteConfigOne[$i]["UploadFileId"];
                $siteConfigType = intval($arrSiteConfigOne[$i]["SiteConfigType"]);

                switch ($siteConfigType) {
                    case SiteConfigData::SITE_CONFIG_TYPE_STRING_200:
                        $templateContent = str_ireplace("{cfg_" . $siteConfigName . "_" . $siteConfigType . "}", $stringNorValue, $templateContent);
                        $templateContent = str_ireplace("{cfg_" . $siteConfigName . "}", $stringNorValue, $templateContent);
                        break;
                    case SiteConfigData::SITE_CONFIG_TYPE_STRING_2000:
                        $templateContent = str_ireplace("{cfg_" . $siteConfigName . "_" . $siteConfigType . "}", $stringMidValue, $templateContent);
                        $templateContent = str_ireplace("{cfg_" . $siteConfigName . "}", $stringMidValue, $templateContent);
                        break;
                    case SiteConfigData::SITE_CONFIG_TYPE_TEXT:
                        $templateContent = str_ireplace("{cfg_" . $siteConfigName . "_" . $siteConfigType . "}", $textValue, $templateContent);
                        $templateContent = str_ireplace("{cfg_" . $siteConfigName . "}", $textValue, $templateContent);
                        break;
                    case SiteConfigData::SITE_CONFIG_TYPE_INT:
                        $templateContent = str_ireplace("{cfg_" . $siteConfigName . "_" . $siteConfigType . "}", $intValue, $templateContent);
                        $templateContent = str_ireplace("{cfg_" . $siteConfigName . "}", $intValue, $templateContent);
                        break;
                    case SiteConfigData::SITE_CONFIG_TYPE_NUMBER:
                        $templateContent = str_ireplace("{cfg_" . $siteConfigName . "_" . $siteConfigType . "}", $numValue, $templateContent);
                        $templateContent = str_ireplace("{cfg_" . $siteConfigName . "}", $numValue, $templateContent);
                        break;
                    case SiteConfigData::SITE_CONFIG_TYPE_UPLOAD_FILE_ID:
                        $templateContent = str_ireplace("{cfg_" . $siteConfigName . "_" . $siteConfigType . "}", $uploadFileId, $templateContent);
                        $templateContent = str_ireplace("{cfg_" . $siteConfigName . "}", $uploadFileId, $templateContent);

                        /**
                         * 上传文件id替换
                         * {cfg_$siteConfigName_$siteConfigType_upload_file_mobile_path}
                         */
                        $withCache = TRUE;

                        $uploadFilePath = $uploadFileData->GetUploadFilePath($uploadFileId,$withCache);
                        $templateContent = str_ireplace("{cfg_" . $siteConfigName . "_" . $siteConfigType . "_upload_file_path}"
                            , $uploadFilePath
                            , $templateContent
                        );

                        $uploadFileMobilePath = $uploadFileData->GetUploadFileMobilePath($uploadFileId,$withCache);
                        $templateContent = str_ireplace("{cfg_" . $siteConfigName . "_" . $siteConfigType . "_upload_file_mobile_path}"
                            , $uploadFileMobilePath
                            , $templateContent
                        );

                        $uploadFilePadPath = $uploadFileData->GetUploadFilePadPath($uploadFileId,$withCache);
                        $templateContent = str_ireplace("{cfg_" . $siteConfigName . "_" . $siteConfigType . "_upload_file_pad_path}"
                            , $uploadFilePadPath
                            , $templateContent
                        );

                        $uploadFileThumbPath1 = $uploadFileData->GetUploadFileThumbPath1($uploadFileId,$withCache);
                        $templateContent = str_ireplace("{cfg_" . $siteConfigName . "_" . $siteConfigType . "_upload_file_thumb_path_1}"
                            , $uploadFileThumbPath1
                            , $templateContent
                        );

                        $uploadFileThumbPath2 = $uploadFileData->GetUploadFileThumbPath2($uploadFileId,$withCache);
                        $templateContent = str_ireplace("{cfg_" . $siteConfigName . "_" . $siteConfigType . "_upload_file_thumb_path_2}"
                            , $uploadFileThumbPath2
                            , $templateContent
                        );

                        $uploadFileThumbPath3 = $uploadFileData->GetUploadFileThumbPath3($uploadFileId,$withCache);
                        $templateContent = str_ireplace("{cfg_" . $siteConfigName . "_" . $siteConfigType . "_upload_file_thumb_path_3}"
                            , $uploadFileThumbPath3
                            , $templateContent
                        );

                        $uploadFileWatermarkPath1 = $uploadFileData->GetUploadFileWatermarkPath1($uploadFileId,$withCache);
                        $templateContent = str_ireplace("{cfg_" . $siteConfigName . "_" . $siteConfigType . "_upload_file_watermark_path_1}"
                            , $uploadFileWatermarkPath1
                            , $templateContent
                        );

                        $uploadFileWatermarkPath2 = $uploadFileData->GetUploadFileWatermarkPath2($uploadFileId,$withCache);
                        $templateContent = str_ireplace("{cfg_" . $siteConfigName . "_" . $siteConfigType . "_upload_file_watermark_path_2}"
                            , $uploadFileWatermarkPath2
                            , $templateContent
                        );

                        $uploadFileCompressPath1 = $uploadFileData->GetUploadFileCompressPath1($uploadFileId,$withCache);
                        $templateContent = str_ireplace("{cfg_" . $siteConfigName . "_" . $siteConfigType . "_upload_file_compress_path_1}"
                            , $uploadFileCompressPath1
                            , $templateContent
                        );

                        $uploadFileCompressPath2 = $uploadFileData->GetUploadFileCompressPath2($uploadFileId,$withCache);
                        $templateContent = str_ireplace("{cfg_" . $siteConfigName . "_" . $siteConfigType . "_upload_file_compress_path_2}"
                            , $uploadFileCompressPath2
                            , $templateContent
                        );

                        break;
                    default:
                        $templateContent = str_ireplace("{cfg_" . $siteConfigName . "_" . $siteConfigType . "}", $stringNorValue, $templateContent);
                        $templateContent = str_ireplace("{cfg_" . $siteConfigName . "}", $stringNorValue, $templateContent);
                        break;
                }
            }
        } else { //移除掉标记
            $patterns = '/\{cfg_(.*)\<\/}/imsU';
            $templateContent = preg_replace($patterns, "", $templateContent);
        }
    }

    /**
     * 加入visit统计代码
     * @param string $templateContent 模板内容
     * @param string $funcUrl 统计程序url地址
     * @param string $siteId 站点Id
     * @param string $channelId 频道Id
     * @param string $tableType 对应表类型
     * @param string $tableId 对应表Id
     * @param string $visitTag 访问标签类别
     */
    protected function AddVisitJsToTemplate(&$templateContent, $funcUrl, $siteId, $channelId, $tableType, $tableId, $visitTag = '')
    {
        $templateContent .= '<script type="text/javascript">';
        $templateContent .= 'var visitConfig = encodeURIComponent("' . $funcUrl . '") +"||' . $siteId . '||' . $channelId . '||' . $tableType . '||' . $tableId . '||"+encodeURI("' . $visitTag . '");';
        $templateContent .= '</script>';
        $templateContent .= '<script type="text/javascript" src="' . $funcUrl . '/common/js/visit.js" charset="utf-8"></script>';
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

    protected function UploadMultiple(
        $fileElementName = "file_upload",
        $tableType = 0,
        $tableId = 0,
        &$arrUploadFile = null, //UploadFile类型的数组
        &$arrUploadFileId = null, //UploadFileId 数组
        $imgMaxWidth = 0,
        $imgMaxHeight = 0,
        $imgMinWidth = 0,
        $imgMinHeight = 0
    ){
        $files = $_FILES[$fileElementName];

        for($i = 0; $i<count($files["name"]); $i++){

            $file_name = $files["name"][$i];
            $file_tmp_name = $files["tmp_name"][$i];
            $file_error = $files["error"][$i];
            $file_size = $files["size"][$i];

            $errorMessage = self::UploadPreCheck(
                $file_name,
                $file_tmp_name,
                $file_error,
                $imgMaxWidth,
                $imgMaxHeight,
                $imgMinWidth,
                $imgMinHeight
            );
            $resultMessage = "";
            $uploadFilePath = "";
            $uploadFileId = 0;

            if ($errorMessage == (abs(DefineCode::UPLOAD) + self::UPLOAD_PRE_CHECK_SUCCESS)) { //没有错误
                usleep(1000000 * 0.1); //0.1秒
                $newFileName = "";
                $fileExtension = strtolower(FileObject::GetExtension($file_name));
                $manageUserId = Control::GetManageUserId();
                $userId = Control::GetUserId();



                $uploadPath = PHYSICAL_PATH . DIRECTORY_SEPARATOR . "upload" . DIRECTORY_SEPARATOR;

                $dirPath = self::GetUploadFilePath($tableType, $tableId, $manageUserId, $userId, $uploadPath, $fileExtension, $newFileName);
                if (!empty($dirPath) && strlen($dirPath) > 0 && !empty($newFileName) && strlen($newFileName) > 0) {
                    FileObject::CreateDir($dirPath);
                    $moveResult = move_uploaded_file($file_tmp_name, $dirPath . $newFileName);

                    if ($moveResult) {
                        //数据库操作
                        $uploadFileData = new UploadFileData();

                        if (!empty($arrUploadFileId)) {
                            //修改原有uploadFile数据

                            $uploadFileId = intval($arrUploadFileId[$i]);

                            //1.删除原有原图文件

                            self::ClearUploadFile($uploadFileId);

                            //2.清空数据表
                            $uploadFileData->Clear($uploadFileId);

                            //3.修改数据表
                            $uploadFileData->Modify(
                                $uploadFileId,
                                $newFileName,
                                $file_size, //文件大小，字节
                                $fileExtension,
                                $file_name, //原始文件名
                                str_ireplace(PHYSICAL_PATH, "", $dirPath) . $newFileName, //文件路径+文件名
                                $tableType,
                                $tableId,
                                $manageUserId,
                                $userId
                            );
                        }else{
                            //创建新的uploadFile数据
                            $uploadFileId = $uploadFileData->Create(
                                $newFileName,
                                $file_size, //文件大小，字节
                                $fileExtension,
                                $file_name, //原始文件名
                                str_ireplace(PHYSICAL_PATH, "", $dirPath) . $newFileName, //文件路径+文件名
                                $tableType,
                                $tableId,
                                $manageUserId,
                                $userId
                            );
                        }


                        if ($uploadFileId > 0) {
                            //返回值处理
                            $returnDirPath = str_ireplace(PHYSICAL_PATH, "", $dirPath);

                            $uploadFilePath = $returnDirPath . $newFileName;
                            $uploadFilePath = str_ireplace("\\", "/", $uploadFilePath);

                            $resultMessage = Format::FormatUploadFileToHtml(
                                $uploadFilePath,
                                $fileExtension,
                                $uploadFileId,
                                $file_name
                            );

                        }

                        $result = abs(DefineCode::UPLOAD) + self::UPLOAD_RESULT_SUCCESS;
                        $errorMessage = $result;
                    } else { //移动上传文件时失败
                        $result = DefineCode::UPLOAD + self::UPLOAD_RESULT_MOVE_FILE_TO_DESTINATION;
                        $errorMessage = $result;
                    }
                } else {
                    $result = DefineCode::UPLOAD + self::UPLOAD_RESULT_PATH;
                    $errorMessage = $result;
                }
            } else {
                $result = $errorMessage;
            }

            $uploadFile = new UploadFile(
                $errorMessage,
                $resultMessage,
                $uploadFileId,
                $uploadFilePath
            );

            $arrUploadFileId[$i] = $uploadFileId;
            $arrUploadFile[$i] = $uploadFile;

        }

    }

    /**
     * 上传文件（单个input file控件）
     * @param string $fileElementName 控件名称
     * @param int $tableType 上传文件对应的表类型
     * @param int $tableId 上传文件对应的表id
     * @param UploadFile $uploadFile 返回的上传文件对象
     * @param int $uploadFileId 返回新的上传文件id
     * @param int $imgMaxWidth 图片最大宽度限制
     * @param int $imgMaxHeight 图片最大高度限制
     * @param int $imgMinWidth 图片最小宽度限制
     * @param int $imgMinHeight 图片最小高度限制
     * @return int 返回成功或错误代码
     */
    protected function Upload(
        $fileElementName = "file_upload",
        $tableType = 0,
        $tableId = 0,
        UploadFile &$uploadFile = null,
        &$uploadFileId = 0,
        $imgMaxWidth = 0,
        $imgMaxHeight = 0,
        $imgMinWidth = 0,
        $imgMinHeight = 0
    )
    {
        $errorMessage = self::UploadPreCheck(
            $_FILES[$fileElementName]['name'],
            $_FILES[$fileElementName]['tmp_name'],
            $_FILES[$fileElementName]['error'],
            $imgMaxWidth,
            $imgMaxHeight,
            $imgMinWidth,
            $imgMinHeight
        );
        $resultMessage = "";
        $uploadFilePath = "";
        if ($errorMessage == (abs(DefineCode::UPLOAD) + self::UPLOAD_PRE_CHECK_SUCCESS)) { //没有错误
            usleep(1000000 * 0.1); //0.1秒
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
                    $uploadFileData = new UploadFileData();
                    if ($uploadFileId > 0) {
                        //修改原有uploadFile数据

                        //1.删除原有原图文件

                        self::ClearUploadFile($uploadFileId);

                        //2.清空数据表
                        $uploadFileData->Clear($uploadFileId);

                        //3.修改数据表
                        $uploadFileData->Modify(
                            $uploadFileId,
                            $newFileName,
                            $_FILES[$fileElementName]['size'], //文件大小，字节
                            $fileExtension,
                            $_FILES[$fileElementName]['name'], //原始文件名
                            str_ireplace(PHYSICAL_PATH, "", $dirPath) . $newFileName, //文件路径+文件名
                            $tableType,
                            $tableId,
                            $manageUserId,
                            $userId
                        );
                    }else{
                        //创建新的uploadFile数据
                        $uploadFileId = $uploadFileData->Create(
                            $newFileName,
                            $_FILES[$fileElementName]['size'], //文件大小，字节
                            $fileExtension,
                            $_FILES[$fileElementName]['name'], //原始文件名
                            str_ireplace(PHYSICAL_PATH, "", $dirPath) . $newFileName, //文件路径+文件名
                            $tableType,
                            $tableId,
                            $manageUserId,
                            $userId
                        );
                    }


                    if ($uploadFileId > 0) {
                        //返回值处理
                        $returnDirPath = str_ireplace(PHYSICAL_PATH, "", $dirPath);

                        $uploadFilePath = $returnDirPath . $newFileName;
                        $uploadFilePath = str_ireplace("\\", "/", $uploadFilePath);

                        $resultMessage = Format::FormatUploadFileToHtml(
                            $uploadFilePath,
                            $fileExtension,
                            $uploadFileId,
                            $_FILES[$fileElementName]['name']
                        );


                    }

                    $result = abs(DefineCode::UPLOAD) + self::UPLOAD_RESULT_SUCCESS;
                    $errorMessage = $result;
                } else { //移动上传文件时失败
                    $result = DefineCode::UPLOAD + self::UPLOAD_RESULT_MOVE_FILE_TO_DESTINATION;
                    $errorMessage = $result;
                }
            } else {
                $result = DefineCode::UPLOAD + self::UPLOAD_RESULT_PATH;
                $errorMessage = $result;
            }
        } else {
            $result = $errorMessage;
        }

        $uploadFile = new UploadFile(
            $errorMessage,
            $resultMessage,
            $uploadFileId,
            $uploadFilePath
        );

        return $result;
    }


    /**
     * 批量上传文件
     * @param string $fileElementName 控件名称
     * @param int $tableType 上传文件对应的表类型
     * @param int $tableId 上传文件对应的表id
     * @param UploadFile $uploadFile 返回的上传文件对象
     * @param int $uploadFileId 返回新的上传文件id
     * @param int $imgMaxWidth 图片最大宽度限制
     * @param int $imgMaxHeight 图片最大高度限制
     * @param int $imgMinWidth 图片最小宽度限制
     * @param int $imgMinHeight 图片最小高度限制
     * @return int 返回成功或错误代码
     */
    protected function UploadBatch(
        $fileElementName = "file",   //plUpload控件默认指定
        $tableType = 0,
        $tableId = 0,
        UploadFile &$uploadFile = null,
        &$uploadFileId = 0,
        $imgMaxWidth = 0,
        $imgMaxHeight = 0,
        $imgMinWidth = 0,
        $imgMinHeight = 0
    )
    {
        $errorMessage = self::UploadPreCheck(
            $_FILES[$fileElementName]['name'],
            $_FILES[$fileElementName]['tmp_name'],
            $_FILES[$fileElementName]['error'],
            $imgMaxWidth,
            $imgMaxHeight,
            $imgMinWidth,
            $imgMinHeight
        );
        $resultMessage = "";
        $uploadFilePath = "";
        if ($errorMessage == (abs(DefineCode::UPLOAD) + self::UPLOAD_PRE_CHECK_SUCCESS)) { //没有错误
            usleep(1000000 * 0.1); //0.1秒
            $newFileName = "";
            $fileExtension = strtolower(FileObject::GetExtension($_FILES[$fileElementName]['name']));
            $manageUserId = Control::GetManageUserId();
            $userId = Control::GetUserId();

            $uploadPath = PHYSICAL_PATH . DIRECTORY_SEPARATOR . "upload" . DIRECTORY_SEPARATOR;

            $dirPath = self::GetUploadFilePath($tableType, $tableId, $manageUserId, $userId, $uploadPath, $fileExtension, $newFileName);

            $debug=new DebugLogManageData();
            $debug->Create($dirPath);
            if (!empty($dirPath) && strlen($dirPath) > 0 && !empty($newFileName) && strlen($newFileName) > 0) {
                FileObject::CreateDir($dirPath);
                $moveResult = move_uploaded_file($_FILES[$fileElementName]['tmp_name'], $dirPath . $newFileName);

                if ($moveResult) {
                    //数据库操作
                    $uploadFileData = new UploadFileData();
                    if ($uploadFileId > 0) {
                        //修改原有uploadFile数据

                        //1.删除原有原图文件

                        self::ClearUploadFile($uploadFileId);

                        //2.清空数据表
                        $uploadFileData->Clear($uploadFileId);

                        //3.修改数据表
                        $uploadFileData->Modify(
                            $uploadFileId,
                            $newFileName,
                            $_FILES[$fileElementName]['size'], //文件大小，字节
                            $fileExtension,
                            $_FILES[$fileElementName]['name'], //原始文件名
                            str_ireplace(PHYSICAL_PATH, "", $dirPath) . $newFileName, //文件路径+文件名
                            $tableType,
                            $tableId,
                            $manageUserId,
                            $userId,
                            "",
                            "",
                            1
                        );
                    }else{
                        //创建新的uploadFile数据
                        $uploadFileId = $uploadFileData->Create(
                            $newFileName,
                            $_FILES[$fileElementName]['size'], //文件大小，字节
                            $fileExtension,
                            $_FILES[$fileElementName]['name'], //原始文件名
                            str_ireplace(PHYSICAL_PATH, "", $dirPath) . $newFileName, //文件路径+文件名
                            $tableType,
                            $tableId,
                            $manageUserId,
                            $userId,
                            "",
                            "",
                            1
                        );
                    }


                    if ($uploadFileId > 0) {
                        //返回值处理
                        $returnDirPath = str_ireplace(PHYSICAL_PATH, "", $dirPath);

                        $uploadFilePath = $returnDirPath . $newFileName;
                        $uploadFilePath = str_ireplace("\\", "/", $uploadFilePath);

                        $resultMessage = Format::FormatUploadFileToHtml(
                            $uploadFilePath,
                            $fileExtension,
                            $uploadFileId,
                            $_FILES[$fileElementName]['name']
                        );


                    }

                    $result = abs(DefineCode::UPLOAD) + self::UPLOAD_RESULT_SUCCESS;
                    $errorMessage = $result;
                } else { //移动上传文件时失败
                    $result = DefineCode::UPLOAD + self::UPLOAD_RESULT_MOVE_FILE_TO_DESTINATION;
                    $errorMessage = $result;
                }
            } else {
                $result = DefineCode::UPLOAD + self::UPLOAD_RESULT_PATH;
                $errorMessage = $result;
            }
        } else {
            $result = $errorMessage;
        }

        $uploadFile = new UploadFile($errorMessage, $resultMessage, $uploadFileId, $uploadFilePath);

        return $result;
    }
    /**
     * 根据不同的业务，构建不同的存储文件夹和文件名
     * @param int $tableType 对应表类型
     * @param int $tableId 对应表id
     * @param int $manageUserId 后台管理员id
     * @param int $userId 会员id
     * @param string $uploadPath 上传文件夹路径
     * @param string $fileExtension 上传文件扩展名
     * @param string $newFileName 新文件名
     * @return string 存储文件夹和文件名
     */
    private function GetUploadFilePath($tableType, $tableId, $manageUserId, $userId, $uploadPath, $fileExtension, &$newFileName)
    {
        //根据不同的业务，构建不同的存储文件夹和文件名
        $uploadFilePath = "";
        $date = strval(date('Ymd', time()));
        switch ($tableType) {
            case UploadFileData::UPLOAD_TABLE_TYPE_DOCUMENT_NEWS_TITLE_PIC_1:
                /**资讯题图1   tableId 为 channelId  */
                if ($tableId > 0) {
                    $uploadFilePath = $uploadPath . "document_news" . DIRECTORY_SEPARATOR . strval($tableId) . DIRECTORY_SEPARATOR . $date . DIRECTORY_SEPARATOR;
                    $newFileName = 'title_pic1_' . uniqid() . '.' . $fileExtension;
                }
                break;
            case UploadFileData::UPLOAD_TABLE_TYPE_DOCUMENT_NEWS_TITLE_PIC_2:
                /**资讯题图2   tableId 为 channelId  */
                if ($tableId > 0) {
                    $uploadFilePath = $uploadPath . "document_news" . DIRECTORY_SEPARATOR . strval($tableId) . DIRECTORY_SEPARATOR . $date . DIRECTORY_SEPARATOR;
                    $newFileName = 'title_pic2_' . uniqid() . '.' . $fileExtension;
                }
                break;
            case UploadFileData::UPLOAD_TABLE_TYPE_DOCUMENT_NEWS_TITLE_PIC_3:
                /**资讯题图3   tableId 为 channelId  */
                if ($tableId > 0) {
                    $uploadFilePath = $uploadPath . "document_news" . DIRECTORY_SEPARATOR . strval($tableId) . DIRECTORY_SEPARATOR . $date . DIRECTORY_SEPARATOR;
                    $newFileName = 'title_pic3_' . uniqid() . '.' . $fileExtension;
                }
                break;
            case UploadFileData::UPLOAD_TABLE_TYPE_DOCUMENT_NEWS_CONTENT:
                /**资讯内容图   tableId 为 channelId  */
                if ($tableId > 0) {
                    $uploadFilePath = $uploadPath . "document_news" .
                        DIRECTORY_SEPARATOR . strval($tableId) .
                        DIRECTORY_SEPARATOR . $date .
                        DIRECTORY_SEPARATOR;
                    $newFileName = 'document_news_content_' . uniqid() . '.' . $fileExtension;
                }
                break;
            case UploadFileData::UPLOAD_TABLE_TYPE_MANAGE_TASK:
                /**管理任务上传  */
                if ($manageUserId > 0) {
                    $uploadFilePath = $uploadPath . "manage_task" . DIRECTORY_SEPARATOR . strval($manageUserId) . DIRECTORY_SEPARATOR . $date . DIRECTORY_SEPARATOR;
                    $newFileName = uniqid() . '.' . $fileExtension;
                }
                break;
            case UploadFileData::UPLOAD_TABLE_TYPE_MANAGE_TASK_REPLY:
                /**管理任务回复上传  */
                if ($manageUserId > 0) {
                    $uploadFilePath = $uploadPath . "manage_task_reply" . DIRECTORY_SEPARATOR . strval($manageUserId) . DIRECTORY_SEPARATOR . $date . DIRECTORY_SEPARATOR;
                    $newFileName = uniqid() . '.' . $fileExtension;
                }
                break;
            case UploadFileData::UPLOAD_TABLE_TYPE_QUESTION: //咨询问答上传

                break;
            case UploadFileData::UPLOAD_TABLE_TYPE_PRODUCT_TITLE_PIC_1:
                /**产品题图   tableId 为 productId  */
                if ($tableId > 0) {
                    $uploadFilePath = $uploadPath . "product" . DIRECTORY_SEPARATOR
                        . strval($tableId) . DIRECTORY_SEPARATOR;
                    $newFileName = 'title_pic_1_' . uniqid() . '.' . $fileExtension;
                }
                break;
            case UploadFileData::UPLOAD_TABLE_TYPE_PRODUCT_TITLE_PIC_2:
                /**产品题图   tableId 为 productId  */
                if ($tableId > 0) {
                    $uploadFilePath = $uploadPath . "product" . DIRECTORY_SEPARATOR
                        . strval($tableId) . DIRECTORY_SEPARATOR;
                    $newFileName = 'title_pic_2_' . uniqid() . '.' . $fileExtension;
                }
                break;
            case UploadFileData::UPLOAD_TABLE_TYPE_PRODUCT_TITLE_PIC_3:
                /**产品题图   tableId 为 productId  */
                if ($tableId > 0) {
                    $uploadFilePath = $uploadPath . "product" . DIRECTORY_SEPARATOR
                        . strval($tableId) . DIRECTORY_SEPARATOR;
                    $newFileName = 'title_pic_3_' . uniqid() . '.' . $fileExtension;
                }
                break;
            case UploadFileData::UPLOAD_TABLE_TYPE_PRODUCT_TITLE_PIC_4:
                /**产品题图   tableId 为 productId  */
                if ($tableId > 0) {
                    $uploadFilePath = $uploadPath . "product" . DIRECTORY_SEPARATOR
                        . strval($tableId) . DIRECTORY_SEPARATOR;
                    $newFileName = 'title_pic_4_' . uniqid() . '.' . $fileExtension;
                }
                break;
            case UploadFileData::UPLOAD_TABLE_TYPE_PRODUCT_PIC:
                /**产品组图   tableId 为 productId  */
                if ($tableId > 0) {
                    $uploadFilePath = $uploadPath . "product_pic" . DIRECTORY_SEPARATOR
                        . strval($tableId) . DIRECTORY_SEPARATOR;
                    $newFileName = uniqid() . '.' . $fileExtension;
                }
                break;
            case UploadFileData::UPLOAD_TABLE_TYPE_PRODUCT_PARAM_TYPE_OPTION:
                /**产品参数类型选项题图   tableId 为 productParamTypeId  */
                $uploadFilePath = $uploadPath . "product_option" . DIRECTORY_SEPARATOR . strval($tableId) . DIRECTORY_SEPARATOR . $date . DIRECTORY_SEPARATOR;
                $newFileName = uniqid() . '.' . $fileExtension;
                break;
            case UploadFileData::UPLOAD_TABLE_TYPE_PRODUCT_PARAM_TYPE:
                /**产品参数类型题图   tableId 为 productParamTypeClassId  */
                $uploadFilePath = $uploadPath . "product_param_type" . DIRECTORY_SEPARATOR . strval($tableId) . DIRECTORY_SEPARATOR . $date . DIRECTORY_SEPARATOR;
                $newFileName = uniqid() . '.' . $fileExtension;
                break;
            case UploadFileData::UPLOAD_TABLE_TYPE_PRODUCT_BRAND:
                /**产品品牌题图   tableId 为 siteId  */
                $uploadFilePath = $uploadPath . "product_brand" . DIRECTORY_SEPARATOR . strval($tableId) . DIRECTORY_SEPARATOR . $date . DIRECTORY_SEPARATOR;
                $newFileName = 'logo_' . uniqid() . '.' . $fileExtension;
                break;
            case UploadFileData::UPLOAD_TABLE_TYPE_PRODUCT_BRAND_INTRO:
                /**产品品牌简介内容图   tableId 为 siteId  */
                if ($tableId > 0) {
                    $uploadFilePath = $uploadPath . "product_brand" .
                        DIRECTORY_SEPARATOR . strval($tableId) .
                        DIRECTORY_SEPARATOR . $date .
                        DIRECTORY_SEPARATOR;
                    $newFileName = 'product_brand_intro_' . uniqid() . '.' . $fileExtension;
                }
                break;
            case UploadFileData::UPLOAD_TABLE_TYPE_PRODUCT_CONTENT: /**产品内容图   tableId 为 channelId  */

                if ($tableId > 0) {
                    $uploadFilePath = $uploadPath . "product_content" .
                        DIRECTORY_SEPARATOR . strval($tableId) .
                        DIRECTORY_SEPARATOR . $date .
                        DIRECTORY_SEPARATOR;
                    $newFileName = 'product_content_' . uniqid() . '.' . $fileExtension;
                }
                break;
            case UploadFileData::UPLOAD_TABLE_TYPE_SITE_AD_CONTENT:
                /**广告图片上传 tableId 为 siteId */
                $uploadFilePath = $uploadPath . "site_ad" . DIRECTORY_SEPARATOR . strval($tableId) . DIRECTORY_SEPARATOR;
                $newFileName = uniqid() . '.' . $fileExtension;
                break;
            case UploadFileData::UPLOAD_TABLE_TYPE_ACTIVITY_TITLE_PIC_1:
                //活动类题图1上传
                $uploadFilePath = $uploadPath . "activity" .
                    DIRECTORY_SEPARATOR . $date . DIRECTORY_SEPARATOR . strval($tableId) . DIRECTORY_SEPARATOR;
                $newFileName = 'title_pic_1_' . uniqid() . '.' . $fileExtension;
                break;
            case UploadFileData::UPLOAD_TABLE_TYPE_ACTIVITY_TITLE_PIC_2:
                //活动类题图2上传
                $uploadFilePath = $uploadPath . "activity" .
                    DIRECTORY_SEPARATOR . $date . DIRECTORY_SEPARATOR . strval($tableId) . DIRECTORY_SEPARATOR;
                $newFileName = 'title_pic_2_' . uniqid() . '.' . $fileExtension;
                break;

            case UploadFileData::UPLOAD_TABLE_TYPE_ACTIVITY_TITLE_PIC_3:
                //活动类题图3上传
                $uploadFilePath = $uploadPath . "activity" .
                    DIRECTORY_SEPARATOR . $date . DIRECTORY_SEPARATOR . strval($tableId) . DIRECTORY_SEPARATOR;
                $newFileName = 'title_pic_3_' . uniqid() . '.' . $fileExtension;
                break;

            case UploadFileData::UPLOAD_TABLE_TYPE_ACTIVITY_PIC:
                //活动花絮图片上传
                $uploadFilePath = $uploadPath . "activity" .
                    DIRECTORY_SEPARATOR . $date . DIRECTORY_SEPARATOR . strval($tableId) . DIRECTORY_SEPARATOR;
                $newFileName = 'activity_pic_' . uniqid() . '.' . $fileExtension;
                break;

            case UploadFileData::UPLOAD_TABLE_TYPE_ACTIVITY_CONTENT:
                //活动内容图片上传
                $uploadFilePath = $uploadPath . "activity" .
                    DIRECTORY_SEPARATOR . $date . DIRECTORY_SEPARATOR . strval($tableId) . DIRECTORY_SEPARATOR;
                $newFileName = 'activity_content_' . uniqid() . '.' . $fileExtension;
                break;

            case UploadFileData::UPLOAD_TABLE_TYPE_INFORMATION_TITLE_PIC_1:
                //分类信息题图1上传
                $uploadFilePath = $uploadPath . "information" .
                    DIRECTORY_SEPARATOR . $date . DIRECTORY_SEPARATOR . strval($tableId) . DIRECTORY_SEPARATOR;
                $newFileName = 'title_pic_1_' . uniqid() . '.' . $fileExtension;
                break;

            case UploadFileData::UPLOAD_TABLE_TYPE_INFORMATION_CONTENT:
                //分类信息内容上传
                $uploadFilePath = $uploadPath . "information" .
                    DIRECTORY_SEPARATOR . $date . DIRECTORY_SEPARATOR . strval($tableId) . DIRECTORY_SEPARATOR;
                $newFileName = 'information_content_' . uniqid() . '.' . $fileExtension;
                break;
            case UploadFileData::UPLOAD_TABLE_TYPE_USER_GROUP:
                //会员组
                $uploadFilePath = $uploadPath . "user_group" . DIRECTORY_SEPARATOR . $date . DIRECTORY_SEPARATOR;
                $newFileName = uniqid() . '.' . $fileExtension;
                break;
            case UploadFileData::UPLOAD_TABLE_TYPE_USER_AVATAR:
                //会员头像 $tableId就是$userId

                if($tableId<=0){
                    $tableId = $userId;
                }

                $uploadFilePath = $uploadPath . "user_avatar" . DIRECTORY_SEPARATOR . strval($tableId) . DIRECTORY_SEPARATOR;
                $newFileName = uniqid() . '.' . $fileExtension;

                break;
            case UploadFileData::UPLOAD_TABLE_TYPE_USER_ALBUM_COVER:
                //会员相册封面

                break;
            case UploadFileData::UPLOAD_TABLE_TYPE_SITE_LINK:
                //友情链接类
                $uploadFilePath = $uploadPath . "site_link" . DIRECTORY_SEPARATOR . $date . DIRECTORY_SEPARATOR;
                $newFileName = uniqid() . '.' . $fileExtension;
                break;
            case UploadFileData::UPLOAD_TABLE_TYPE_SITE_CONTENT: //自定义页面类
                $uploadFilePath = $uploadPath . "site_content" . DIRECTORY_SEPARATOR . $date . DIRECTORY_SEPARATOR;
                $newFileName = uniqid() . '.' . $fileExtension;
                break;
            case UploadFileData::UPLOAD_TABLE_TYPE_SITE_CONFIG:
                /** 站点配置图片上传 tableId 为 siteId */
                $uploadFilePath = $uploadPath . "site_config" . DIRECTORY_SEPARATOR . strval($tableId) . DIRECTORY_SEPARATOR;
                $newFileName = uniqid() . '.' . $fileExtension;
                break;

            case UploadFileData::UPLOAD_TABLE_TYPE_CUSTOM_FORM: //自定义表单
                $uploadFilePath = $uploadPath . "custom_form" . DIRECTORY_SEPARATOR . $date . DIRECTORY_SEPARATOR;
                $newFileName = uniqid() . '.' . $fileExtension;
                break;
            case UploadFileData::UPLOAD_TABLE_TYPE_CHANNEL_1:
                /** 频道图片1 tableId 为 channelId */
                $uploadFilePath = $uploadPath . "channel" . DIRECTORY_SEPARATOR . "channel_id_" . strval($tableId) . DIRECTORY_SEPARATOR;
                $newFileName = 'channel_title_pic_1_' . uniqid() . '.' . $fileExtension;
                break;
            case UploadFileData::UPLOAD_TABLE_TYPE_CHANNEL_2:
                /** 频道图片1 tableId 为 channelId */
                $uploadFilePath = $uploadPath . "channel" . DIRECTORY_SEPARATOR . "channel_id_" . strval($tableId) . DIRECTORY_SEPARATOR;
                $newFileName = 'channel_title_pic_2_' . uniqid() . '.' . $fileExtension;
                break;
            case UploadFileData::UPLOAD_TABLE_TYPE_CHANNEL_3:
                /** 频道图片1 tableId 为 channelId */
                $uploadFilePath = $uploadPath . "channel" . DIRECTORY_SEPARATOR . "channel_id_" . strval($tableId) . DIRECTORY_SEPARATOR;
                $newFileName = 'channel_title_pic_3_' . uniqid() . '.' . $fileExtension;
                break;
            case UploadFileData::UPLOAD_TABLE_TYPE_USER_LEVEL:
                /** 会员等级 */
                $uploadFilePath = $uploadPath . "user_level" . DIRECTORY_SEPARATOR . $date . DIRECTORY_SEPARATOR;
                $newFileName = uniqid() . '.' . $fileExtension;
                break;
            case UploadFileData::UPLOAD_TABLE_TYPE_USER_ATTACHMENT:
                /** 会员附件 */
                if ($userId > 0) {
                    $uploadFilePath = $uploadPath . "user_attachment" . DIRECTORY_SEPARATOR . strval($userId) . DIRECTORY_SEPARATOR;
                    $newFileName = 'user_attachment_' . $userId . '_' . uniqid() . '.' . $fileExtension;
                }
                break;
            case UploadFileData::UPLOAD_TABLE_TYPE_VOTE_SELECT_ITEM_TITLE_PIC_1:
                /** 投票 题目选项题图1  tableId 为 voteItemId **/
                $uploadFilePath = $uploadPath . "vote_select_item" . DIRECTORY_SEPARATOR . strval($tableId) . DIRECTORY_SEPARATOR;
                $newFileName = 'title_pic_1_' . uniqid() . '.' . $fileExtension;
                break;
            case UploadFileData::UPLOAD_TABLE_TYPE_USER_MOOD: //会员心情图标 tableId 为 siteId
                $uploadFilePath = $uploadPath . "user_mood" . DIRECTORY_SEPARATOR . strval($tableId) . DIRECTORY_SEPARATOR . $date . DIRECTORY_SEPARATOR;
                $newFileName = $tableId . '_user_mood_' . uniqid() . '.' . $fileExtension;
                break;
            case UploadFileData::UPLOAD_TABLE_TYPE_EXAM_QUESTION: //考试类用
                $uploadFilePath = $uploadPath . "exam" . DIRECTORY_SEPARATOR . strval($tableId) . DIRECTORY_SEPARATOR . $date . DIRECTORY_SEPARATOR;
                $newFileName = uniqid() . '.' . $fileExtension;
                break;
            case UploadFileData::UPLOAD_TABLE_TYPE_USER_SIGN: //会员签名图标
                if ($userId > 0) {
                    $uploadFilePath = $uploadPath . "user_sign" . DIRECTORY_SEPARATOR . strval($userId) . DIRECTORY_SEPARATOR;
                    $newFileName = uniqid() . '.' . $fileExtension;
                }
                break;


            case UploadFileData::UPLOAD_TABLE_TYPE_FORUM_PIC_1:
                /** 论坛版块图标1 */
                $uploadFilePath =
                    $uploadPath . "forum" .
                    DIRECTORY_SEPARATOR . strval($tableId) .
                    DIRECTORY_SEPARATOR . $date .
                    DIRECTORY_SEPARATOR;
                $newFileName = $tableId . '_' . uniqid() . '.' . $fileExtension;
                break;
            case UploadFileData::UPLOAD_TABLE_TYPE_FORUM_PIC_2:
                /** 论坛版块图标2 */
                $uploadFilePath =
                    $uploadPath . "forum" .
                    DIRECTORY_SEPARATOR . strval($tableId) .
                    DIRECTORY_SEPARATOR . $date .
                    DIRECTORY_SEPARATOR;
                $newFileName = $tableId . '_' . uniqid() . '.' . $fileExtension;
                break;
            case UploadFileData::UPLOAD_TABLE_TYPE_FORUM_POST_CONTENT:
                /** 论坛帖子内容 */
                $uploadFilePath =
                    $uploadPath . "forum_post" .
                    DIRECTORY_SEPARATOR . strval($tableId) .
                    DIRECTORY_SEPARATOR . $date .
                    DIRECTORY_SEPARATOR;
                $newFileName = $tableId . '_' . uniqid() . '.' . $fileExtension;
                break;
            case UploadFileData::UPLOAD_TABLE_TYPE_FORUM_TOP_INFO_CONTENT:
                /** 论坛 顶部信息内容图 */
                $uploadFilePath =
                    $uploadPath . "forum_top_info_content" .
                    DIRECTORY_SEPARATOR . strval($tableId) .
                    DIRECTORY_SEPARATOR;
                $newFileName = $tableId . '_' . uniqid() . '.' . $fileExtension;
                break;
            case UploadFileData::UPLOAD_TABLE_TYPE_FORUM_BOT_INFO_CONTENT:
                /** 论坛 底部信息内容图 */
                $uploadFilePath =
                    $uploadPath . "forum_bot_info_content" .
                    DIRECTORY_SEPARATOR . strval($tableId) .
                    DIRECTORY_SEPARATOR;
                $newFileName = $tableId . '_' . uniqid() . '.' . $fileExtension;
                break;
            case UploadFileData::UPLOAD_TABLE_TYPE_FORUM_LOGO:
                /** 论坛 LOGO */
                $uploadFilePath =
                    $uploadPath . "forum_logo" .
                    DIRECTORY_SEPARATOR . strval($tableId) .
                    DIRECTORY_SEPARATOR;
                $newFileName = $tableId . '_' . uniqid() . '.' . $fileExtension;
                break;
            case UploadFileData::UPLOAD_TABLE_TYPE_FORUM_BACKGROUND_PIC:
                /** 论坛 背景图 */
                $uploadFilePath =
                    $uploadPath . "forum_background_pic" .
                    DIRECTORY_SEPARATOR . strval($tableId) .
                    DIRECTORY_SEPARATOR;
                $newFileName = $tableId . '_' . uniqid() . '.' . $fileExtension;
                break;

            case UploadFileData::UPLOAD_TABLE_TYPE_SITE_CONFIG_PIC:
                //站点配置
                $uploadFilePath = $uploadPath . "site_config"
                    . DIRECTORY_SEPARATOR . strval($tableId)
                    . DIRECTORY_SEPARATOR;
                $newFileName = uniqid() . '.' . $fileExtension;


                break;

            case UploadFileData::UPLOAD_TABLE_TYPE_PIC_SLIDER:
                //图片轮换
                $uploadFilePath = $uploadPath . "pic_slider"
                    . DIRECTORY_SEPARATOR . strval($tableId)
                    . DIRECTORY_SEPARATOR;
                $newFileName = uniqid() . '.' . $fileExtension;


                break;

            case UploadFileData::UPLOAD_TABLE_TYPE_NEWSPAPER_PAGE_PDF:

                $uploadFilePath = $uploadPath . "newspaper_page"
                    . DIRECTORY_SEPARATOR . strval($tableId)
                    . DIRECTORY_SEPARATOR;
                $newFileName = uniqid() . '.' . $fileExtension;
                break;

            case UploadFileData::UPLOAD_TABLE_TYPE_NEWSPAPER_PAGE_PIC:

                $uploadFilePath = $uploadPath . "newspaper_page"
                    . DIRECTORY_SEPARATOR . strval($tableId)
                    . DIRECTORY_SEPARATOR;
                $newFileName = uniqid() . '.' . $fileExtension;
                break;

            case UploadFileData::UPLOAD_TABLE_TYPE_NEWSPAPER_ARTICLE_PIC:

                $uploadFilePath = $uploadPath . "newspaper_article"
                    . DIRECTORY_SEPARATOR . strval($tableId)
                    . DIRECTORY_SEPARATOR;
                $newFileName = uniqid() . '.' . $fileExtension;


                break;
        }
        return $uploadFilePath;
    }

    /**
     * 上传文件预检查：成功
     */
    const UPLOAD_PRE_CHECK_SUCCESS = 100;
    /**
     * 上传文件结果：没有错误
     */
    const UPLOAD_RESULT_SUCCESS = 101;
    /**
     * 上传文件结果：未操作
     */
    const UPLOAD_RESULT_NO_ACTION = -100;
    /**
     * 上传文件结果：$_FILE为空
     */
    const UPLOAD_RESULT_FILE_IS_EMPTY = -121;
    /**
     * 上传文件结果：PHP temp文件夹未设置
     */
    const UPLOAD_RESULT_TMP_IS_NULL = -120;
    /**
     * 上传文件结果：文件太大
     */
    const UPLOAD_RESULT_TOO_LARGE_FOR_SERVER = -101;
    /**
     * 上传文件结果：文件太大，超出了HTML表单的限制
     */
    const UPLOAD_RESULT_TOO_LARGE_FOR_HTML = -102;
    /**
     * 上传文件结果：文件中只有一部分内容完成了上传
     */
    const UPLOAD_RESULT_ONLY_PARTIALLY_UPLOADED = -103;
    /**
     * 上传文件结果：没有找到要上传的文件
     */
    const UPLOAD_RESULT_NO_FILE = -104;
    /**
     * 上传文件结果：服务器临时文件夹丢失
     */
    const UPLOAD_RESULT_TEMPORARY_FOLDER_IS_MISSING = -105;
    /**
     * 上传文件结果： 文件写入到临时文件夹出错
     */
    const UPLOAD_RESULT_FAILED_TO_WRITE_TO_THE_TEMPORARY_FOLDER = -106;
    /**
     * 上传文件结果：文件夹没有写入权限
     */
    const UPLOAD_RESULT_NO_RIGHT_TO_WRITE_TEMPORARY = -107;
    /**
     * 上传文件结果：扩展使文件上传停止
     */
    const UPLOAD_RESULT_PLUGINS_MADE_UPLOAD_STOP = -108;
    /**
     * 上传文件结果：没有可以显示的错误信息
     */
    const UPLOAD_RESULT_NO_MESSAGE = -109;
    /**
     * 上传文件结果：文件类型错误，不允许此类文件上传
     */
    const UPLOAD_RESULT_FILE_TYPE = -110;
    /**
     * 上传文件结果：生成上传文件路径和文件名时出错
     */
    const UPLOAD_RESULT_PATH = -111;
    /**
     * 上传文件结果：移动上传文件到目标路径时失败
     */
    const UPLOAD_RESULT_MOVE_FILE_TO_DESTINATION = -112;
    /**
     * 抓取文件结果：数据不正确
     */
    const UPLOAD_RESULT_ERROR_DATA = -113;
    /**
     * MIME不是图片
     */
    const UPLOAD_RESULT_NOT_IMAGE = -114;
    /**
     * 写入失败
     */
    const UPLOAD_RESULT_WRITE_FAILURE = -115;

    /**
     * 图片文件 超过最大宽度
     */
    const UPLOAD_RESULT_OVER_MAX_WIDTH = -116;

    /**
     * 图片文件 超过最大高度
     */
    const UPLOAD_RESULT_OVER_MAX_HEIGHT = -117;

    /**
     * 图片文件 不足最小宽度
     */
    const UPLOAD_RESULT_LESS_MIN_WIDTH = -118;

    /**
     * 图片文件 不足最小高度
     */
    const UPLOAD_RESULT_LESS_MIN_HEIGHT = -119;

    /**
     * 上传文件预检查
     * @param string $name 上传文件名称 $_FILES[$fileElementName]['name']
     * @param string $tmp_name 临时文件名称 $_FILES[$fileElementName]['tmp_name']
     * @param string $error 错误消息 $_FILES[$fileElementName]['error']
     * @param int $imgMaxWidth 错误代码，默认返回1，没有错误
     * @param int $imgMaxHeight 错误代码，默认返回1，没有错误
     * @param int $imgMinWidth 错误代码，默认返回1，没有错误
     * @param int $imgMinHeight 错误代码，默认返回1，没有错误
     * @return int 错误代码，默认返回1，没有错误
     */
    private function UploadPreCheck(
        $name,
        $tmp_name, //$_FILES[$fileElementName]['tmp_name']
        $error,
        $imgMaxWidth = 0,
        $imgMaxHeight = 0,
        $imgMinWidth = 0,
        $imgMinHeight = 0
    )
    {
        $errorMessage = abs(DefineCode::UPLOAD) + self::UPLOAD_PRE_CHECK_SUCCESS;

        if (empty($_FILES)) {
            return DefineCode::UPLOAD + self::UPLOAD_RESULT_FILE_IS_EMPTY;
        }

        /////////////////////////检查temp文件夹///////////////////////////
        if (empty($tmp_name)) {
            return DefineCode::UPLOAD + self::UPLOAD_RESULT_TMP_IS_NULL;
        }
        if ($tmp_name == 'none') {
            return DefineCode::UPLOAD + self::UPLOAD_RESULT_TMP_IS_NULL;
        }
        /////////////////////////检查错误信息///////////////////////////
        if (!empty($error)) {
            switch ($error) {
                case '1':
                    $errorMessage = DefineCode::UPLOAD + self::UPLOAD_RESULT_TOO_LARGE_FOR_SERVER;
                    break;
                case '2':
                    $errorMessage = DefineCode::UPLOAD + self::UPLOAD_RESULT_TOO_LARGE_FOR_HTML;
                    break;
                case '3':
                    $errorMessage = DefineCode::UPLOAD + self::UPLOAD_RESULT_ONLY_PARTIALLY_UPLOADED;
                    break;
                case '4':
                    $errorMessage = DefineCode::UPLOAD + self::UPLOAD_RESULT_NO_FILE;
                    break;
                case '5':
                    $errorMessage = DefineCode::UPLOAD + self::UPLOAD_RESULT_TEMPORARY_FOLDER_IS_MISSING;
                    break;
                case '6':
                    $errorMessage = DefineCode::UPLOAD + self::UPLOAD_RESULT_FAILED_TO_WRITE_TO_THE_TEMPORARY_FOLDER;
                    break;
                case '7':
                    $errorMessage = DefineCode::UPLOAD + self::UPLOAD_RESULT_NO_RIGHT_TO_WRITE_TEMPORARY;
                    break;
                case '8':
                    $errorMessage = DefineCode::UPLOAD + self::UPLOAD_RESULT_PLUGINS_MADE_UPLOAD_STOP;
                    break;
                default:
                    $errorMessage = DefineCode::UPLOAD + self::UPLOAD_RESULT_NO_MESSAGE;
            }
            return $errorMessage;
        }
        /////////////////////////检查安全文件后缀///////////////////////////
        $fileExtension = FileObject::GetExtension($name);
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

            if($fileExtension == "jpg" ||
                $fileExtension == "gif" ||
                $fileExtension == "png" ||
                $fileExtension == "jpeg"
            ){
                //图片文件判断图片大小

                list($width, $height, $type) = getimagesize(
                    $tmp_name);

                if($imgMaxWidth>0){
                    if($width>$imgMaxWidth){
                        $errorMessage = DefineCode::UPLOAD + self::UPLOAD_RESULT_OVER_MAX_WIDTH;
                    }
                }

                if($imgMaxHeight>0){
                    if($height>$imgMaxHeight){
                        $errorMessage = DefineCode::UPLOAD + self::UPLOAD_RESULT_OVER_MAX_HEIGHT;
                    }
                }

                if($imgMinWidth>0){
                    if($width<$imgMinWidth){
                        $errorMessage = DefineCode::UPLOAD + self::UPLOAD_RESULT_LESS_MIN_WIDTH;
                    }
                }

                if($imgMinHeight>0){
                    if($height<$imgMinHeight){
                        $errorMessage = DefineCode::UPLOAD + self::UPLOAD_RESULT_LESS_MIN_HEIGHT;
                    }
                }

            }

        } else {
            $errorMessage = DefineCode::UPLOAD + self::UPLOAD_RESULT_FILE_TYPE;
        }
        return $errorMessage;
    }


    /**
     * 生成移动客户端使用的上传文件（图片），并存入上传文件表对应记录行
     * @param int $uploadFileId 上传文件id
     * @param int $mobileWidth 移动客户端使用的图片的宽度
     * @param int $mobileHeight 移动客户端使用的图片的高度，默认为0，不按高度缩放
     * @param int $jpgQuality 质量，默认90
     * @return int 操作结果
     */
    protected function GenUploadFileMobile($uploadFileId, $mobileWidth, $mobileHeight = 0, $jpgQuality = 90)
    {
        $result = -1;
        if ($uploadFileId > 0 && ($mobileWidth > 0 || $mobileHeight > 0)) {
            //1.取得原图
            $withCache = false;
            $uploadFileData = new UploadFileData();
            $uploadFilePath = $uploadFileData->GetUploadFilePath($uploadFileId, $withCache);
            //2.制作缩略图
            if (!empty($uploadFilePath)) {
                $thumbFileName = "mobile";

                $uploadFileMobilePath = ImageObject::GenThumb($uploadFilePath, $mobileWidth, $mobileHeight, $thumbFileName, $jpgQuality);

                if (!empty($uploadFileMobilePath)) {
                    //3.修改数据表
                    $result = $uploadFileData->ModifyUploadFileMobilePath(
                        $uploadFileId,
                        $uploadFileMobilePath
                    );
                }
            }
        }
        return $result;
    }

    /**
     * 生成平板客户端使用的上传文件（图片），并存入上传文件表对应记录行
     * @param int $uploadFileId 上传文件id
     * @param int $padWidth 图片的宽度
     * @param int $padHeight 图片的高度，默认为0，不按高度缩放
     * @param int $jpgQuality 质量，默认90
     * @return int 操作结果
     */
    protected function GenUploadFilePad($uploadFileId, $padWidth, $padHeight = 0, $jpgQuality = 90)
    {
        $result = -1;
        if ($uploadFileId > 0 && ($padWidth > 0 || $padHeight > 0)) {
            //1.取得原图
            $withCache = false;
            $uploadFileData = new UploadFileData();
            $uploadFilePath = $uploadFileData->GetUploadFilePath($uploadFileId, $withCache);
            //2.制作缩略图
            if (!empty($uploadFilePath)) {
                $thumbFileName = "pad";

                $uploadFileMobilePath = ImageObject::GenThumb($uploadFilePath, $padWidth, $padHeight, $thumbFileName, $jpgQuality);

                if (!empty($uploadFileMobilePath)) {
                    //3.修改数据表
                    $result = $uploadFileData->ModifyUploadFilePadPath(
                        $uploadFileId,
                        $uploadFileMobilePath
                    );
                }
            }
        }
        return $result;
    }

    /**
     * 生成缩略图1的上传文件（图片），并存入上传文件表对应记录行
     * @param int $uploadFileId 上传文件id
     * @param int $imageWidth 图片的宽度
     * @param int $imageHeight 图片的高度，默认为0，不按高度缩放
     * @param int $jpgQuality 质量，默认90
     * @return int 操作结果
     */
    protected function GenUploadFileThumb1($uploadFileId, $imageWidth, $imageHeight = 0, $jpgQuality = 90)
    {
        $result = -1;
        if ($uploadFileId > 0 && ($imageWidth > 0 || $imageHeight > 0)) {
            //1.取得原图
            $withCache = false;
            $uploadFileData = new UploadFileData();
            $uploadFilePath = $uploadFileData->GetUploadFilePath($uploadFileId, $withCache);
            //2.制作缩略图
            if (!empty($uploadFilePath)) {
                $thumbFileName = "thumb1";
                $newUploadFilePath = ImageObject::GenThumb($uploadFilePath, $imageWidth, $imageHeight, $thumbFileName, $jpgQuality);

                if (!empty($newUploadFilePath)) {
                    //3.修改数据表
                    $result = $uploadFileData->ModifyUploadFileThumbPath1(
                        $uploadFileId,
                        $newUploadFilePath
                    );
                }
            }
        }
        return $result;
    }

    /**
     * 生成缩略图2的上传文件（图片），并存入上传文件表对应记录行
     * @param int $uploadFileId 上传文件id
     * @param int $imageWidth 图片的宽度
     * @param int $imageHeight 图片的高度，默认为0，不按高度缩放
     * @param int $jpgQuality 质量，默认90
     * @return int 操作结果
     */
    protected function GenUploadFileThumb2($uploadFileId, $imageWidth, $imageHeight = 0, $jpgQuality = 90)
    {
        $result = -1;
        if ($uploadFileId > 0 && ($imageWidth > 0 || $imageHeight > 0)) {
            //1.取得原图
            $withCache = false;
            $uploadFileData = new UploadFileData();
            $uploadFilePath = $uploadFileData->GetUploadFilePath($uploadFileId, $withCache);
            //2.制作缩略图
            if (!empty($uploadFilePath)) {
                $thumbFileName = "thumb2";
                $newUploadFilePath = ImageObject::GenThumb($uploadFilePath, $imageWidth, $imageHeight, $thumbFileName, $jpgQuality);

                if (!empty($newUploadFilePath)) {
                    //3.修改数据表
                    $result = $uploadFileData->ModifyUploadFileThumbPath2(
                        $uploadFileId,
                        $newUploadFilePath
                    );
                }
            }
        }
        return $result;
    }

    /**
     * 生成缩略图3的上传文件（图片），并存入上传文件表对应记录行
     * @param int $uploadFileId 上传文件id
     * @param int $imageWidth 图片的宽度
     * @param int $imageHeight 图片的高度，默认为0，不按高度缩放
     * @param int $jpgQuality 质量，默认90
     * @return int 操作结果
     */
    protected function GenUploadFileThumb3($uploadFileId, $imageWidth, $imageHeight = 0, $jpgQuality = 90)
    {
        $result = -1;
        if ($uploadFileId > 0 && ($imageWidth > 0 || $imageHeight > 0)) {
            //1.取得原图
            $withCache = false;
            $uploadFileData = new UploadFileData();
            $uploadFilePath = $uploadFileData->GetUploadFilePath($uploadFileId, $withCache);
            //2.制作缩略图
            if (!empty($uploadFilePath)) {
                $thumbFileName = "thumb3";
                $newUploadFilePath = ImageObject::GenThumb($uploadFilePath, $imageWidth, $imageHeight, $thumbFileName, $jpgQuality);

                if (!empty($newUploadFilePath)) {
                    //3.修改数据表
                    $result = $uploadFileData->ModifyUploadFileThumbPath3(
                        $uploadFileId,
                        $newUploadFilePath
                    );
                }
            }
        }
        return $result;
    }

    /**
     * 在哪张图上制作水印图 0 在原图上制作
     */
    const MAKE_WATERMARK_SOURCE_TYPE_SOURCE_PIC = 0;

    /**
     * 在哪张图上制作水印图 1 在缩略图1上制作
     */
    const MAKE_WATERMARK_SOURCE_TYPE_THUMB_PIC_1 = 1;

    /**
     * 在哪张图上制作水印图 2 在缩略图2上制作
     */
    const MAKE_WATERMARK_SOURCE_TYPE_THUMB_PIC_2 = 2;

    /**
     * 在哪张图上制作水印图 3 在缩略图3上制作
     */
    const MAKE_WATERMARK_SOURCE_TYPE_THUMB_PIC_3 = 3;

    /**
     * 在哪张图上制作水印图 4 在压缩图1上制作
     */
    const MAKE_WATERMARK_SOURCE_TYPE_COMPRESS_PIC_1 = 4;

    /**
     * 在哪张图上制作水印图 4 在压缩图2上制作
     */
    const MAKE_WATERMARK_SOURCE_TYPE_COMPRESS_PIC_2 = 5;

    /**
     * 生成水印图1的上传文件（图片），并存入上传文件表对应记录行
     * @param int $uploadFileId 上传文件id
     * @param string $watermarkFilePath 水印图地址(相对地址)
     * @param int $sourceType 在哪张图上制作水印图（默认0 在原图上制作）
     * @param int $watermarkPosition   水印位置 1:顶部居左, 2:顶部居右, 3:居中, 4:底部居左, 5:底部居右
     * @param int $mode 模式 0 支持png本身透明度的方式 1 半透明格式水印
     * @param int $alpha 透明度 -- 0:完全透明, 100:完全不透明
     * @param UploadFile $uploadFile 上传文件对象
     * @return int 操作结果
     */
    protected function GenUploadFileWatermark1(
        $uploadFileId,
        $watermarkFilePath,
        $sourceType = self::MAKE_WATERMARK_SOURCE_TYPE_SOURCE_PIC,
        $watermarkPosition = ImageObject::WATERMARK_POSITION_BOTTOM_RIGHT,
        $mode = ImageObject::WATERMARK_MODE_PNG,
        $alpha = 70,
        &$uploadFile = null
    )
    {
        $result = -1;
        if ($uploadFileId > 0 && !empty($watermarkFilePath)) {
            //1.取得制作图
            $withCache = false;
            $uploadFileData = new UploadFileData();
            switch($sourceType){
                case self::MAKE_WATERMARK_SOURCE_TYPE_SOURCE_PIC:
                    $uploadFilePath = $uploadFileData->GetUploadFilePath($uploadFileId, $withCache);
                    break;
                case self::MAKE_WATERMARK_SOURCE_TYPE_THUMB_PIC_1:
                    $uploadFilePath = $uploadFileData->GetUploadFileThumbPath1($uploadFileId, $withCache);
                    break;
                case self::MAKE_WATERMARK_SOURCE_TYPE_THUMB_PIC_2:
                    $uploadFilePath = $uploadFileData->GetUploadFileThumbPath2($uploadFileId, $withCache);
                    break;
                case self::MAKE_WATERMARK_SOURCE_TYPE_THUMB_PIC_3:
                    $uploadFilePath = $uploadFileData->GetUploadFileThumbPath3($uploadFileId, $withCache);
                    break;
                case self::MAKE_WATERMARK_SOURCE_TYPE_COMPRESS_PIC_1:
                    $uploadFilePath = $uploadFileData->GetUploadFileCompressPath1($uploadFileId, $withCache);
                    break;
                case self::MAKE_WATERMARK_SOURCE_TYPE_COMPRESS_PIC_2:
                    $uploadFilePath = $uploadFileData->GetUploadFileCompressPath2($uploadFileId, $withCache);
                    break;
            }

            //2.制作水印图
            if (!empty($uploadFilePath)) {
                $addFileName = "watermark1";

                $uploadFileWatermarkPath1 = ImageObject::GenWatermark(
                    $uploadFilePath,
                    $watermarkFilePath,
                    $addFileName,
                    $watermarkPosition,
                    $mode,
                    $alpha
                );

                if (!empty($uploadFileWatermarkPath1)) {

                    $uploadFileWatermarkPath1 = str_ireplace(
                        '\\/','/',$uploadFileWatermarkPath1
                    );

                    $uploadFileWatermarkPath1 = str_ireplace(
                        '\\','/',$uploadFileWatermarkPath1
                    );

                    //3.修改数据表
                    $result = $uploadFileData->ModifyUploadFileWatermarkPath1(
                        $uploadFileId,
                        $uploadFileWatermarkPath1
                    );

                    //修改对象
                    $uploadFile->UploadFileWatermarkPath1 = $uploadFileWatermarkPath1;
                }
            }
        }
        return $result;
    }

    /**
     * 生成水印图2的上传文件（图片），并存入上传文件表对应记录行
     * @param int $uploadFileId 上传文件id
     * @param string $watermarkFilePath 水印图地址
     * @param int $sourceType 在哪张图上制作水印图（默认0 在原图上制作）
     * @param int $watermarkPosition   水印位置 1:顶部居左, 2:顶部居右, 3:居中, 4:底部居左, 5:底部居右
     * @param int $mode     模式 0 支持png本身透明度的方式 1 半透明格式水印
     * @param int $alpha     透明度 -- 0:完全透明, 100:完全不透明
     * @return int 操作结果
     */
    protected function GenUploadFileWatermark2(
        $uploadFileId,
        $watermarkFilePath,
        $sourceType = self::MAKE_WATERMARK_SOURCE_TYPE_SOURCE_PIC,
        $watermarkPosition = ImageObject::WATERMARK_POSITION_BOTTOM_RIGHT,
        $mode = ImageObject::WATERMARK_MODE_PNG,
        $alpha = 70
    )
    {
        $result = -1;
        if ($uploadFileId > 0 && !empty($watermarkFilePath)) {
            //1.取得制作图
            $withCache = false;
            $uploadFileData = new UploadFileData();
            switch($sourceType){
                case self::MAKE_WATERMARK_SOURCE_TYPE_SOURCE_PIC:
                    $uploadFilePath = $uploadFileData->GetUploadFilePath($uploadFileId, $withCache);
                    break;
                case self::MAKE_WATERMARK_SOURCE_TYPE_THUMB_PIC_1:
                    $uploadFilePath = $uploadFileData->GetUploadFileThumbPath1($uploadFileId, $withCache);
                    break;
                case self::MAKE_WATERMARK_SOURCE_TYPE_THUMB_PIC_2:
                    $uploadFilePath = $uploadFileData->GetUploadFileThumbPath2($uploadFileId, $withCache);
                    break;
                case self::MAKE_WATERMARK_SOURCE_TYPE_THUMB_PIC_3:
                    $uploadFilePath = $uploadFileData->GetUploadFileThumbPath3($uploadFileId, $withCache);
                    break;
                case self::MAKE_WATERMARK_SOURCE_TYPE_COMPRESS_PIC_1:
                    $uploadFilePath = $uploadFileData->GetUploadFileCompressPath1($uploadFileId, $withCache);
                    break;
                case self::MAKE_WATERMARK_SOURCE_TYPE_COMPRESS_PIC_2:
                    $uploadFilePath = $uploadFileData->GetUploadFileCompressPath2($uploadFileId, $withCache);
                    break;
            }

            //2.制作水印图
            if (!empty($uploadFilePath)) {
                $addFileName = "watermark2";

                $newUploadFilePath = ImageObject::GenWatermark(
                    $uploadFilePath,
                    $watermarkFilePath,
                    $addFileName,
                    $watermarkPosition,
                    $mode,
                    $alpha
                );

                if (!empty($newUploadFilePath)) {
                    //3.修改数据表
                    $result = $uploadFileData->ModifyUploadFileWatermarkPath2(
                        $uploadFileId,
                        $newUploadFilePath
                    );
                }
            }
        }
        return $result;
    }

    /**
     * 生成压缩图1的上传文件（图片），并存入上传文件表对应记录行
     * @param int $uploadFileId 上传文件id
     * @param int $imageWidth 图片的宽度
     * @param int $imageHeight 图片的高度，默认为0，不按高度缩放
     * @param int $jpgQuality 图片质量 默认90
     * @return int 操作结果
     */
    public function GenUploadFileCompress1($uploadFileId, $imageWidth, $imageHeight = 0, $jpgQuality = 90)
    {
        $result = -1;
        if ($uploadFileId > 0 && ($imageWidth > 0 || $imageHeight > 0)) {
            //1.取得原图
            $withCache = false;
            $uploadFileData = new UploadFileData();
            $uploadFilePath = $uploadFileData->GetUploadFilePath($uploadFileId, $withCache);
            //2.制作缩略图
            if (!empty($uploadFilePath)) {
                $thumbFileName = "compress1";
                $newUploadFilePath = ImageObject::GenThumb($uploadFilePath, $imageWidth, $imageHeight, $thumbFileName, $jpgQuality);

                if (!empty($newUploadFilePath)) {
                    //3.修改数据表
                    $result = $uploadFileData->ModifyUploadFileCompressPath1(
                        $uploadFileId,
                        $newUploadFilePath
                    );
                }
            }
        }
        return $result;
    }

    /**
     * 生成压缩图2的上传文件（图片），并存入上传文件表对应记录行
     * @param int $uploadFileId 上传文件id
     * @param int $imageWidth 图片的宽度
     * @param int $imageHeight 图片的高度，默认为0，不按高度缩放
     * @param int $jpgQuality 图片质量 默认90
     * @return int 操作结果
     */
    public function GenUploadFileCompress2($uploadFileId, $imageWidth, $imageHeight = 0, $jpgQuality = 90)
    {
        $result = -1;
        if ($uploadFileId > 0 && ($imageWidth > 0 || $imageHeight > 0)) {
            //1.取得原图
            $withCache = false;
            $uploadFileData = new UploadFileData();
            $uploadFilePath = $uploadFileData->GetUploadFilePath($uploadFileId, $withCache);
            //2.制作缩略图
            if (!empty($uploadFilePath)) {
                $thumbFileName = "compress2";
                $newUploadFilePath = ImageObject::GenThumb($uploadFilePath, $imageWidth, $imageHeight, $thumbFileName, $jpgQuality);

                if (!empty($newUploadFilePath)) {
                    //3.修改数据表
                    $result = $uploadFileData->ModifyUploadFileCompressPath1(
                        $uploadFileId,
                        $newUploadFilePath
                    );
                }
            }
        }
        return $result;
    }

    /**
     * 删除上传文件记录和物理文件
     * @param int $uploadFileId 上传文件id
     */
    public function DeleteUploadFile($uploadFileId)
    {
        if($uploadFileId>0){
            $uploadFileData = new UploadFileData();

            $uploadFile = $uploadFileData->Fill($uploadFileId);

            if (strlen($uploadFile->UploadFilePath)) {
                FileObject::DeleteFile($uploadFile->UploadFilePath);
            }
            if (strlen($uploadFile->UploadFileMobilePath)) {
                FileObject::DeleteFile($uploadFile->UploadFileMobilePath);
            }
            if (strlen($uploadFile->UploadFilePadPath)) {
                FileObject::DeleteFile($uploadFile->UploadFilePadPath);
            }
            if (strlen($uploadFile->UploadFileThumbPath1)) {
                FileObject::DeleteFile($uploadFile->UploadFileThumbPath1);
            }
            if (strlen($uploadFile->UploadFileThumbPath2)) {
                FileObject::DeleteFile($uploadFile->UploadFileThumbPath2);
            }
            if (strlen($uploadFile->UploadFileThumbPath3)) {
                FileObject::DeleteFile($uploadFile->UploadFileThumbPath3);
            }
            if (strlen($uploadFile->UploadFileWatermarkPath1)) {
                FileObject::DeleteFile($uploadFile->UploadFileWatermarkPath1);
            }
            if (strlen($uploadFile->UploadFileWatermarkPath2)) {
                FileObject::DeleteFile($uploadFile->UploadFileWatermarkPath2);
            }
            if (strlen($uploadFile->UploadFileCompressPath1)) {
                FileObject::DeleteFile($uploadFile->UploadFileCompressPath1);
            }
            if (strlen($uploadFile->UploadFileCompressPath2)) {
                FileObject::DeleteFile($uploadFile->UploadFileCompressPath2);
            }

            $uploadFileData->Delete($uploadFileId);
        }


    }


    /**
     * 清空某条上传文件记录和删除物理文件（不删除记录）
     * @param int $uploadFileId 上传文件id
     */
    public function ClearUploadFile($uploadFileId)
    {
        if($uploadFileId>0){
            $uploadFileData = new UploadFileData();

            $uploadFile = $uploadFileData->Fill($uploadFileId);

            if (strlen($uploadFile->UploadFilePath)) {
                FileObject::DeleteFile($uploadFile->UploadFilePath);
            }
            if (strlen($uploadFile->UploadFileMobilePath)) {
                FileObject::DeleteFile($uploadFile->UploadFileMobilePath);
            }
            if (strlen($uploadFile->UploadFilePadPath)) {
                FileObject::DeleteFile($uploadFile->UploadFilePadPath);
            }
            if (strlen($uploadFile->UploadFileThumbPath1)) {
                FileObject::DeleteFile($uploadFile->UploadFileThumbPath1);
            }
            if (strlen($uploadFile->UploadFileThumbPath2)) {
                FileObject::DeleteFile($uploadFile->UploadFileThumbPath2);
            }
            if (strlen($uploadFile->UploadFileThumbPath3)) {
                FileObject::DeleteFile($uploadFile->UploadFileThumbPath3);
            }
            if (strlen($uploadFile->UploadFileWatermarkPath1)) {
                FileObject::DeleteFile($uploadFile->UploadFileWatermarkPath1);
            }
            if (strlen($uploadFile->UploadFileWatermarkPath2)) {
                FileObject::DeleteFile($uploadFile->UploadFileWatermarkPath2);
            }
            if (strlen($uploadFile->UploadFileCompressPath1)) {
                FileObject::DeleteFile($uploadFile->UploadFileCompressPath1);
            }
            if (strlen($uploadFile->UploadFileCompressPath2)) {
                FileObject::DeleteFile($uploadFile->UploadFileCompressPath2);
            }

            $uploadFileData->Clear($uploadFileId);

        }


    }


    /**
     * 保存远程图片
     * @param string $url 要抓取的网址
     * @param int $tableType 对应表类型
     * @param int $tableId 对应表id
     * @param UploadFile $uploadFile 返回的上传文件对象
     * @param int $maxSize 文件最大大小(默认2M)
     * @param string $allowExt 允许的扩展名（默认jpg,jpeg,gif,png）
     * @return bool|string 返回结果代码
     */
    public function SaveRemoteImage($url, $tableType, $tableId, UploadFile &$uploadFile, $maxSize = 2097152, $allowExt = "jpg,jpeg,gif,png")
    {
        $errorMessage = abs(DefineCode::UPLOAD) + self::UPLOAD_PRE_CHECK_SUCCESS;

        $reExt = '(' . str_replace(',', '|', $allowExt) . ')';
        if (substr($url, 0, 10) == 'data:image') { //base64编码的图片，可能出现在firefox粘贴，或者某些网站上，例如google图片
            if (!preg_match('/^data:image\/' . $reExt . '/i', $url, $sExt)) {
                $errorMessage = DefineCode::UPLOAD + self::UPLOAD_RESULT_ERROR_DATA;
                return $errorMessage;
            }
            $fileExtension = $sExt[1];
            $imgContent = base64_decode(substr($url, strpos($url, 'base64,') + 7));
        } else {
            if (!preg_match('/\.' . $reExt . '$/i', $url, $sExt)) {
                $errorMessage = DefineCode::UPLOAD + self::UPLOAD_RESULT_ERROR_DATA;
                return $errorMessage;
            }
            $fileExtension = $sExt[1];
            $imgContent = Remote::GetUrl($url);
        }
        if (strlen($imgContent) > $maxSize) {
            $errorMessage = DefineCode::UPLOAD + self::UPLOAD_RESULT_TOO_LARGE_FOR_HTML;
            return $errorMessage;
        }


        $newFileName = "";
        $fileExtension = strtolower($fileExtension);
        $manageUserId = Control::GetManageUserId();
        $userId = Control::GetUserId();

        $uploadPath = "upload" . DIRECTORY_SEPARATOR;

        $dirPath = self::GetUploadFilePath(
            $tableType,
            $tableId,
            $manageUserId,
            $userId,
            $uploadPath,
            $fileExtension,
            $newFileName
        );


        if (!empty($dirPath) && strlen($dirPath) > 0 && !empty($newFileName) && strlen($newFileName) > 0) {
            $filePath = $dirPath . $newFileName;

            $isWriteFile = FileObject::Write($filePath, $imgContent);

            if ($isWriteFile) {
                //检查mime是否为图片，需要php.ini中开启gd2扩展
                $fileInfo = @getimagesize($filePath);
                if (!$fileInfo || !preg_match("/image\/" . $reExt . "/i", $fileInfo['mime'])) {
                    @unlink($filePath);

                    //MIME不是图片
                    $errorMessage = DefineCode::UPLOAD + self::UPLOAD_RESULT_NOT_IMAGE;
                    return $errorMessage;
                }

                $filePath = "/" . $filePath;

                //数据库操作
                $uploadFileData = new UploadFileData();
                $uploadFileId = $uploadFileData->Create(
                    $newFileName,
                    strlen($imgContent), //文件大小，字节
                    $fileExtension,
                    "", //原始文件名
                    $filePath, //文件路径+文件名
                    $tableType,
                    $tableId,
                    $manageUserId,
                    $userId
                );

                $resultMessage = "";

                //返回值处理
                $returnDirPath = "/" . str_ireplace(PHYSICAL_PATH, "", $dirPath);

                $uploadFilePath = $returnDirPath . $newFileName;
                $uploadFilePath = str_ireplace("\\", "/", $uploadFilePath);

                $uploadFile = new UploadFile($errorMessage, $resultMessage, $uploadFileId, $uploadFilePath);
            } else {
                //写入失败
                $errorMessage = DefineCode::UPLOAD + self::UPLOAD_RESULT_WRITE_FAILURE;
            }


        } else {
            $result = DefineCode::UPLOAD + self::UPLOAD_RESULT_PATH;
            $errorMessage = $result;
        }

        return $errorMessage;
    }


    /**
     * 根据频道ID获取包含本频道ID及以下子频道ID字符串，为id,id,id 的形式
     * @param int $channelId 频道id
     * @return string 频道id字符串
     */
    protected function GetOwnChannelIdAndChildChannelId($channelId)
    {
        $allChannelId="";
        if ($channelId > 0) {
            $channelPublicData = new ChannelPublicData();
            $childChannelId = $channelPublicData->GetChildrenChannelId($channelId,true);
            if(empty($childChannelId)){
                $allChannelId = $channelId;
            }
            else{
                $allChannelId = $channelId.",".$childChannelId;
            }
        }
        return $allChannelId;
    }

    protected function IsMobile(){

        $agent = self::GetUserAgent();

        if($agent == "iPhone" || $agent == "android"){
            return true;
        }else{
            return false;
        }

    }

    protected function IsPad(){
        $agent = self::GetUserAgent();

        if($agent == "iPad"){
            return true;
        }else{
            return false;
        }
    }


    protected function GetUserAgent(){
        //获取USER AGENT
        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);

        //分析数据
        $is_pc = (strpos($agent, 'windows nt')) ? true : false;
        $is_iPhone = (strpos($agent, 'iphone')) ? true : false;
        $is_iPad = (strpos($agent, 'ipad')) ? true : false;
        $is_android = (strpos($agent, 'android')) ? true : false;

        //输出数据
        if($is_pc){
            return "PC";
        }
        if($is_iPhone){
            return "iPhone";
        }
        if($is_iPad){
            return "iPad";
        }
        if($is_android){
            return "android";
        }else{
            return "unknown";
        }
    }

}

?>