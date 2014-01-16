<?php

/**
 * Description of DocumentThreadGen
 * 咨询问答模块前台程序处理类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Document
 * @author Liujunyi
 */
class DocumentThreadGen extends BasePublicGen implements IBasePublicGen {

    /**
     * 前台引导方法
     * @return string 返回执行结果
     */
    public function GenFront() {
        $result = "";
        $method = Control::GetRequest("m", "");
        switch ($method) {
            case "new":
                $result = self::GenNewThread();
                break;
            case "view":
                $result = self::GenViewThread();
                break;
            case "list":
                $result = self::GenHtmlListThread();
                break;
            case "frontlist":
                $result = self::GenFrontList();
                break;
            case "frontbyusergroup":    //前台咨询调用,根据用户组ID进行
                $result = self::GenFrontListByUserGroupID();
                break;
            case "reply":
                $result = self::GenReplyThread();
                break;
            case "guestreply":
                $result = self::GenGuestReply();
                break;
            case "htmllist":
                $result = self::GenHtmlListPost();
                break;
            case "search":
                $result = self::GenFrontSearch();
                break;
            case "rates":   //评分计算
                $result = self::GenRates();
                break;
            case "viewcount":   //更新浏览数
                $result = self::GenViewCount();
                break;
            case "frontview":       //前台调用内容信息 jsonp
                $result = self::GenFrontView();
                break;
            case "multichannellist":    //前台列表页面调用多个节点使用
                $result = self::GenFrontMultiChannelList();
                break;
            case "listforusergroupid":      //根据网友所在用户组进行显示
                $result = self::GenFrontListForUserGroupId();
                break;
        }

        $replaceArr = array(
            "{method}" => $method
        );
        $result = strtr($result, $replaceArr);
        return $result;
    }

    /**
     * 信息预览使用  viewtype为preview是预览  publish为发布
     * @return string 返回预览内容
     */
    private function GenViewThread() {
        $documentThreadid = Control::GetRequest("tid", 0);
        $viewType = Control::GetRequest("viewtype", "");
        $userId = Control::GetUserID();
        $userName = Control::GetUserName();
        include RELATIVE_PATH . '/inc/domain.inc.php';
        $_icmsUrl = $domain['icms'];

        $_pos = stripos($_icmsUrl, "http://");
        if ($_pos === false) {
            $_icmsUrl = "http://" . $_icmsUrl;
        }
        $_funcUrl = $domain['func'];

        $_pos = stripos($_funcUrl, "http://");
        if ($_pos === false) {
            $_funcUrl = "http://" . $_funcUrl;
        }
        if ($documentThreadid <= 0) {
            Control::ShowMessage(Language::Load('siteftpment', 35));
            $result = -2;   //发布失败,参数传递出错
            return $result;
        }
        $documentThreadData = new DocumentThreadData();
        $state = $documentThreadData->GetState($documentThreadid);
        if ($state < 10 || $state == 100) {
            Control::ShowMessage(Language::Load('siteftpment', 47));
            $result = -1;   //发布失败,文档必须为终审或已发状态才能发布//Language::Load('document', 23);
            return $result;
        }

        $documentChannelId = $documentThreadData->GetChannelID($documentThreadid);
        if ($documentChannelId <= 0) {
            $result = -2;   //发布失败,参数传递出错
            return $result;
        }
        $documentChannelTemplateData = new DocumentChannelTemplateData();
        $documentChannelData = new DocumentChannelData();
        if ($viewType == 'preview') {
            $tempContent = Template::Load("user/user_app_1_preview.html");
        } elseif ($viewType == 'publish') {
            //=======第1步：读取频道模板
            $templateType = 13; //内容页
            $tempContent = $documentChannelTemplateData->GetForeachContent($documentChannelId, $templateType);
        }
        $rank = $documentChannelData->GetRank($documentChannelId);
        $hasFtp = $documentChannelData->GetHasFtp($documentChannelId);
        $ftpType = 0;

        //预加载模板
        parent::ReplaceFirst($tempContent);

        ///////找出cscms列表替换////////////////////////////////////
        $arr = Template::GetAllCMS($tempContent);
        if (isset($arr)) {
            if (count($arr) > 1) {
                $arr2 = $arr[1];
                $documentPostData = new DocumentPostData();
                foreach ($arr2 as $key => $val) {
                    $documentContent = '<cscms' . $val . '</cscms>';
                    $id = Template::GetDocParamValue($documentContent, "id");
                    $type = strtolower(Template::GetDocParamValue($documentContent, "type"));
                    $where = strtolower(Template::GetDocParamValue($documentContent, "where"));
                    $pageBegin = 0;
                    $topCount = 1;
                    if ($type == 'docthreadlist') {
                        switch ($where) {
                            case "thread":
                                $isThread = 1;
                                $userGroupId = -1;
                                $topCount = 2;
                                break;
                            case "usertype_3":
                                $isThread = 0;
                                $userGroupId = 3;
                                $topCount = 20;
                                break;
                            case "usertype_2":
                                $isThread = 0;
                                $userGroupId = 2;
                                $topCount = 20;
                                break;
                            case "usertype_1":
                                $isThread = 0;
                                $userGroupId = 1;
                                break;
                            case "postlist":
                                $isThread = 0;
                                $userGroupId = 0;
                                $topCount = 20;
                                break;
                        }
                        $pageBegin = 0;
                        $arrList = $documentPostData->GetTedail($documentThreadid, $pageBegin, $topCount, $isThread, $userGroupId);
                        Template::ReplaceList($documentContent, $arrList, $id);
                        $tempContent = Template::ReplaceCMS($tempContent, $id, $documentContent);
                    }
                }
            }
        }

        if ($viewType == 'preview') {       //预览
            //预览及部门回复时使用
            $formUrl = $_icmsUrl . "/default.php?mod=documentthread&m=reply&tid=" . $documentThreadid;
            $fromls = "mceEditor";
            $subButton = "<input type='submit' class='btn2'   value='' name='btn2' />";
        } elseif ($viewType == 'publish') {         //发布
            $formUrl = $_icmsUrl . "/default.php?mod=documentthread&m=guestreply&tid=" . $documentThreadid;
            $subButton = "<input type='button' class='btn2' tabindex='0' onclick='subform()' value=''  />";
        }
        $pagerButton = "";
        $userGroupId = 0;
        $state = 10;
        $pageSize = 20;
        $pageIndex = 1;
        $pagerTemplate = Template::Load("pager_js.html");
        $isJs = true;
        $jsFunctionName = "showpage";
        $jsParamList = "," . $documentThreadid . "";
        $documentPostData = new DocumentPostData();
        $allCount = $documentPostData->GetRowNum($searchKey, $documentThreadid, $userGroupId, $state);
        $pagerButton = Pager::ShowPageButton($pagerTemplate, "", $allCount, $pageSize, $pageIndex, $isJs, $jsFunctionName, $jsParamList);
        $subject = $documentThreadData->GetSubject($documentThreadid);      //标题信息
        $description = $documentPostData->GetContentByThreadID($documentThreadid);
        $description = str_replace(array('&nbsp;', "\r", "\n", '\'', '"'), '', Format::CutStr(trim(strip_tags($description)), 200));      //摘要信息
        $siteId = $documentChannelData->GetSiteID($documentChannelId);
        $siteData = new SiteData();
        $siteUrl = $siteData->GetSiteUrl($siteId);
        $siteName = $siteData->GetSiteName($siteId);
        $documentChannelName = $documentChannelData->GetName($documentChannelId);

        $replaceArr = array(
            "{pubrootpath}" => "",
            "{f_rootpath}" => $_funcUrl,
            "{rootpath}" => "",
            "{tid}" => $documentThreadid,
            "{cid}" => $documentChannelId,
            "{documentchannelid}" => $documentChannelId,
            "{documentchannelname}" => $documentChannelName,
            "{userid}" => $userId,
            "{username}" => $userName,
            "{subject}" => $subject,
            "{description}" => $description,
            "{pagerbutton}" => $pagerButton,
            "{siteurl}" => $siteUrl,
            "{sitename}" => $siteName,
            "{siteid}" => $siteId,
            "{formurl}" => $formUrl,
            "{subbutton}" => $subButton
        );
        $tempContent = strtr($tempContent, $replaceArr);
        if ($viewType == 'preview') {
            return $tempContent;
        } elseif ($viewType == 'publish') {
            /* 发布文档  */
            //发布目录和程序目录在同一站点下时，不发布附件
            if ($domain['icms'] != $domain['func']) {
                $ftpType = 0; //HTML和相关CSS,IMAGE
                $isThread = 1;              //附件进行发布 $isThread为1根据threadid
                $publisHupLoadFiles = $documentPostData->GetUploaFiles($documentThreadid, $isThread);
                $publisHupLoadFiles = split(",", $publisHupLoadFiles);
                $uploadFileData = new UploadFileData();
                //发布图片信息到目标服务器
                for ($i = 0; $i < count($publisHupLoadFiles); $i++) {
                    if (intval($publisHupLoadFiles[$i]) > 0) {
                        $publishFilePath = $uploadFileData->GetUploadFile(intval($publisHupLoadFiles[$i]));
                        parent::Ftp($publishFilePath, $publishFilePath, "", $documentChannelId, $hasFtp, $ftpType);
                    }
                }
            }

            //发布时间
            $publishDate = $documentThreadData->GetPublishDate($documentThreadid);       //发布时间
            if ($publishDate == '' || $publishDate == '0000-00-00 00:00:00' || empty($publishDate)) {
                $state = 30;
                //按首次发布操作取发布时间
                //$publishDate = $documentThreadData->GetCreateDate($documentThreadid);              //导入数据时完全发布时取CreateDate否则取首次发布操作
                //if ($publishDate === '' || $publishDate === '0000-00-00 00:00:00' || empty($publishDate)) {
                $publishDate = date("Y-m-d H:i:s", time());
                //}
                $documentThreadData->ModifyPublishDate($publishDate, $state, $documentThreadid);
            } else {
                $documentThreadData->UpdateState($documentThreadid, 30);
            }
            /*
              $publishDate = $documentThreadData->GetPublishDate($documentThreadid);
              if ($publishDate == '') {
              $state = 30;
              $publishDate = date("Y-m-d H:i:s", time());
              $documentThreadData->ModifyPublishDate($publishDate, $state, $documentThreadid);
              } else {
              $state = 30;
              $documentThreadData->ModifyPublishDate($publishDate, $state, $documentThreadid);
              }
             * */
            $pubDir = parent::Get_PublishPath($documentChannelId, $rank);
            $datePath = Format::Get_DetailName($publishDate);
            $templatePublishFileName = $documentThreadid . '.html';
            if (strlen($tempContent) > 0) {
                //站点信息
                $siteGen = new SiteGen();
                $siteGen->SubGen($siteId, $tempContent);
                //站点配置信息
                $siteConfigGen = new SiteConfigGen();
                $siteConfigGen->SubGen($siteId, $tempContent);
                //友情链接
                $siteLinkGen = new SiteLinkGen();
                $siteLinkGen->GenSub($tempContent);
            }

            $tableType = 2;     //documentthread表为2
            parent::AddVisitJsToTemplate($tempContent, $_funcUrl, $siteId, $documentChannelId, $tableType, $documentThreadid);

            $desPath = DIRECTORY_SEPARATOR . $pubDir . $datePath . DIRECTORY_SEPARATOR . $templatePublishFileName;

            parent::Ftp($desPath, "", $tempContent, $documentChannelId, $hasFtp, $ftpType);
            //联动发布所在频道和上级频道
            //$documentChannelGen = new DocumentChannelGen();
            //$documentChannelGen->PublishMuti($documentChannelId);
            $result = 1;
            //加入操作log
            $operateContent = "Thread：Publish id ：" . $documentThreadid . "；name；" . Control::GetAdminUserName() . "；adminuserid：" . Control::GetAdminUserID() . "；title：" . $subject;
            $adminUserLogData = new AdminUserLogData();
            $adminUserLogData->Insert($operateContent);
            return $result;
        }
    }

    /**
     * 前台调用内容信息 jsonp
     * @return arr jsonp 返回查询结果
     */
    public function GenFrontView() {
        $documentThreadId = Control::GetRequest("id", 0);
        if ($documentThreadId > 0) {
            $state = 30;
            $documentThreadData = new DocumentThreadData();
            $arrList = $documentThreadData->GetFrontViewById($documentThreadId, $state);
            $result = json_encode($arrList);
            return $_GET['jsonpcallback'] . "(" . $result . ")";
        } else {
            return $_GET['jsonpcallback'] . '([{"result":"0"}])';
        }
    }

    /**
     * 前台搜索时用到 返回jsonp格式
     * @return arr 返回查询结果(jsonp)
     */
    public function GenFrontSearch() {
        $result = Language::Load('document', 7);
        $pageSize = Format::RemoveScritpt(Control::GetRequest("ps", 30));
        $pageIndex = Format::RemoveScritpt(Control::GetRequest("p", 1));
        $documentThreadTypeId = Format::RemoveScritpt(Control::GetRequest("threadtypeid", 0));
        if (empty($pageIndex) || $pageIndex <= 0) {
            $pageIndex = 1;
        }
        $searchKey = Format::RemoveScritpt(Control::GetRequest("searchkey", ""));
        $searchKey = urldecode($searchKey);
        $threadTag = Format::RemoveScritpt(Control::GetRequest("tag", ""));
        $threadTag = urldecode($threadTag);
        if ($pageIndex > 0) {
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $allCount = 0;
            $state = 30;
            $order = "1";
            $documentChannelId = 0;
            $documentThreadData = new DocumentThreadData();
            $arrList = $documentThreadData->GetHtmlList($documentChannelId, $pageBegin, $pageSize, $allCount, $searchKey, $order, $documentThreadTypeId, $state, $threadTag);
            if (count($arrList) > 0) {
                $pagerTemplate = Template::Load("pager_js.html");
                $isJs = true;
                $jsFunctionName = "preshow";
                $jsParamList = ",'" . $searchKey . "'," . $documentThreadTypeId . ",'" . $threadTag . "'";
                $pagerButton = Pager::ShowPageButton($pagerTemplate, "", $allCount, $pageSize, $pageIndex, $isJs, $jsFunctionName, $jsParamList);
                //在jsonp中加分页信息
                $tempArrList = array();
                foreach ($arrList as $columnname => $columnvalue) {
                    $date2 = Format::GenFormtData($columnvalue['createdate']);
                    $columnvalue[c_year] = $date2[0];
                    $columnvalue[c_month] = $date2[1];
                    $columnvalue[c_day] = $date2[2];

                    $date2 = Format::GenFormtData($columnvalue['publishdate']);
                    $columnvalue[f_year] = $date2[0];
                    $columnvalue[f_month] = $date2[1];
                    $columnvalue[f_day] = $date2[2];
                    $columnvalue[result] = 1;           //返回参数判断
                    $columnvalue[pagerbutton] = $pagerButton;
                    $tempArrList[] = $columnvalue;
                }
                $result = json_encode($tempArrList);
                return $_GET['jsonpcallback'] . "(" . $result . ")";
            } else {
                return $_GET['jsonpcallback'] . '([{"result":"0"}])';
            }
        }
    }

    /**
     * 前台列表页面使用
     * @return arr 返回查询结果集(jsonp)
     */
    public function GenFrontList() {
        $result = Language::Load('document', 7);
        $documentChannelId = Control::GetRequest("cid", 0);
        $pageSize = Control::GetRequest("ps", 30);
        $pageIndex = Control::GetRequest("p", 1);
        if (empty($pageIndex) || $pageIndex <= 0) {
            $pageIndex = 1;
        }
        $searchKey = Control::GetRequest("searchkey", "");
        $searchKey = urldecode($searchKey);
        $order = Control::GetRequest("order", "");
        $documentThreadTypeId = Control::GetRequest("documentthreadtypeid", "0");
        if ($pageIndex > 0 && $documentChannelId > 0) {
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $allCount = 0;
            $state = 30;
            $order = 1;
            $documentThreadData = new DocumentThreadData();
            $arrList = $documentThreadData->GetHtmlList($documentChannelId, $pageBegin, $pageSize, $allCount, $searchKey, $order, $documentThreadTypeId, $state);

            if (count($arrList) > 0) {
                $pagerTemplate = Template::Load("pager_js.html");
                $isJs = true;
                $jsFunctionName = "preshow";
                $jsParamList = "," . $documentChannelId . "";
                $pagerButton = Pager::ShowPageButton($pagerTemplate, "", $allCount, $pageSize, $pageIndex, $isJs, $jsFunctionName, $jsParamList);
                //在jsonp中加分页信息
                $tempArrList = array();
                foreach ($arrList as $columnname => $columnvalue) {
                    $date2 = Format::GenFormtData($columnvalue['createdate']);
                    $columnvalue[c_year] = $date2[0];
                    $columnvalue[c_month] = $date2[1];
                    $columnvalue[c_day] = $date2[2];

                    $date2 = Format::GenFormtData($columnvalue['publishdate']);
                    $columnvalue[f_year] = $date2[0];
                    $columnvalue[f_month] = $date2[1];
                    $columnvalue[f_day] = $date2[2];
                    $columnvalue[pagerbutton] = $pagerButton;
                    $tempArrList[] = $columnvalue;
                }
                $result = $tempArrList;
            }
        }
        if (isset($_GET['jsonpcallback'])) {
            return $_GET['jsonpcallback'] . '(' . json_encode($result) . ')';
        }
    }

    /**
     * 前台列表按用户取
     * @return arr 返回jsonp格式数据
     */
    public function GenFrontListByUserGroupID() {
        $result = Language::Load('document', 7);
        $userGroupId = intval(Control::GetRequest("usergroupid", 0));
        $pageSize = Control::GetRequest("ps", 30);
        $pageIndex = Control::GetRequest("p", 1);
        $siteId = Control::GetRequest("siteid", 0);
        if (empty($pageIndex) || $pageIndex <= 0) {
            $pageIndex = 1;
        }
        if ($pageIndex > 0 && $userGroupId > 0 && $siteId > 0) {
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $allCount = 0;
            $state = 30;
            $order = 1;
            $threadTag = "";
            $documentThreadData = new DocumentThreadData();
            $arrList = $documentThreadData->GetFrontListByUserGroupID($userGroupId, $pageBegin, $pageSize, $allCount, $siteId, $order, $state, $threadTag);

            if (count($arrList) > 0) {
                $pagerTemplate = Template::Load("pager_js.html");
                $isJs = true;
                $jsFunctionName = "prethreadbyusergroupid";
                $jsParamList = "," . $siteId . "," . $userGroupId;
                $pagerButton = Pager::ShowPageButton($pagerTemplate, "", $allCount, $pageSize, $pageIndex, $isJs, $jsFunctionName, $jsParamList);
                //在jsonp中加分页信息
                $tempArrList = array();
                foreach ($arrList as $columnname => $columnvalue) {
                    $date2 = Format::GenFormtData($columnvalue['createdate']);
                    $columnvalue[c_year] = $date2[0];
                    $columnvalue[c_month] = $date2[1];
                    $columnvalue[c_day] = $date2[2];

                    $date2 = Format::GenFormtData($columnvalue['publishdate']);
                    $columnvalue[f_year] = $date2[0];
                    $columnvalue[f_month] = $date2[1];
                    $columnvalue[f_day] = $date2[2];
                    $columnvalue[pagerbutton] = $pagerButton;
                    $tempArrList[] = $columnvalue;
                }
                $result = $tempArrList;
            }
        }
        if (isset($_GET['jsonpcallback'])) {
            return $_GET['jsonpcallback'] . '(' . json_encode($result) . ')';
        }
    }

    /**
     * 前台列表页面调用多个节点使用
     * @return arr 返回jsonp格式数据
     */
    public function GenFrontMultiChannelList() {
        $result = Language::Load('document', 7);
        $documentChannelId = Control::GetRequest("cid", "");
        $topCount = Control::GetRequest("top", 4);
        $searchKey = Control::GetRequest("searchkey", "");
        $searchKey = urldecode($searchKey);
        $order = Control::GetRequest("order", "");
        $documentThreadTypeId = Control::GetRequest("documentthreadtypeid", "0");
        if ($documentChannelId > 0) {
            $allCount = 0;
            $state = 30;
            $order = 1;
            $srchFrom = 0;  //时间间隔
            $documentThreadData = new DocumentThreadData();
            $arrList = $documentThreadData->GetOrderList($documentChannelId, $topCount, $order, $documentThreadTypeId, $state, $srchFrom);

            if (count($arrList) > 0) {
                $tempArrList = array();
                foreach ($arrList as $columnname => $columnvalue) {
                    $date2 = Format::GenFormtData($columnvalue['createdate']);
                    $columnvalue[c_year] = $date2[0];
                    $columnvalue[c_month] = $date2[1];
                    $columnvalue[c_day] = $date2[2];

                    $date2 = Format::GenFormtData($columnvalue['publishdate']);
                    $columnvalue[f_year] = $date2[0];
                    $columnvalue[f_month] = $date2[1];
                    $columnvalue[f_day] = $date2[2];
                    $tempArrList[] = $columnvalue;
                }
                $result = $tempArrList;
            }
        }
        if (isset($_GET['jsonpcallback'])) {
            return $_GET['jsonpcallback'] . '(' . json_encode($result) . ')';
        }
    }

    /**
     * 会员中心律师等职能部门回复调用
     * @return arr 返回jsonp格式数据
     */
    public function GenFrontListForUserGroupId() {
        $resultType = Control::GetRequest("resulttype", 0);     //为0表示查询全部,2回复,1未回复
        $documentChannelId = Control::GetRequest("cid", 0);
        $siteId = Control::GetRequest("siteid", 0);
        $pageSize = Control::GetRequest("ps", 20);
        $pageIndex = Control::GetRequest("p", 1);
        if (empty($pageIndex) || $pageIndex <= 0) {
            $pageIndex = 1;
        }
        $userId = Control::GetUserID();
        $userName = Control::GetUserName();

        if ($pageIndex > 0 && $userId > 0) {
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $allCount = 0;
            $state = 30;    //只显示发布的信息
            $threadTag = "";
            $order = 0;         //排序显示方式
            $documentThreadData = new DocumentThreadData();
            $userRoleDocumentChannelId = 0;
            $userRoleData = new UserRoleData();
            $myUserGroupId = $userRoleData->GetUserGroupID($userId, $siteId, $userRoleDocumentChannelId);

            $arrList = $documentThreadData->GetFrontListByUserPost($myUserGroupId, $pageBegin, $pageSize, $allCount, $siteId, $order, $state, $userId, $resultType, $documentChannelId);

            if (!empty($arrList) && count($arrList) > 0) {
                $pagerTemplate = Template::Load("pager_js.html");
                $isJs = true;
                $jsFunctionName = "threadlistbyusergroupid";
                $jsParamList = "," . $documentChannelId . "," . $siteId . "," . $resultType;
                $pagerButton = Pager::ShowPageButton($pagerTemplate, "", $allCount, $pageSize, $pageIndex, $isJs, $jsFunctionName, $jsParamList);
                //在jsonp中加分页信息                
                $tempArrList = array();
                foreach ($arrList as $columnname => $columnvalue) {
                    $date2 = Format::GenFormtData($columnvalue['createdate']);

                    $date2 = Format::GenFormtData($columnvalue['publishdate']);
                    $columnvalue[f_year] = $date2[0];
                    $columnvalue[f_month] = $date2[1];
                    $columnvalue[f_day] = $date2[2];
                    $columnvalue[pagerbutton] = $pagerButton;
                    $tempArrList[] = $columnvalue;
                }
                $result = $tempArrList;
            }
        }
        if (isset($_GET['jsonpcallback'])) {
            return $_GET['jsonpcallback'] . '(' . json_encode($result) . ')';
        }
    }

    /**
     * 前台网友发布咨询信息
     * @return string 返回新增执行结果
     */
    private function GenNewThread() {
        $userId = Control::GetUserID();
        $userName = Control::GetUserName();
        if ($userId <= 0) {
            $userId = 0;
            $userName = "";
        }
        if (!empty($_POST)) {
            //修改上传文件的tableid;
            $formDocumentChannelId = Control::PostRequest("f_documentchannelid", "");
            $formSubject = Format::RemoveScritpt(Control::PostRequest("f_subject", ""));
            $formContent = Format::RemoveScritpt(Control::PostRequest("content", ""));
            if (strlen(trim($formContent)) < 10) {
                $formContent = Format::RemoveScritpt(Control::PostRequest("t_content", ""));
            }
            $formContent = str_replace("\n", "<br />", $formContent);
            $formCreateDate = Control::PostRequest("f_createdate", "");
            $formRealName = Format::RemoveScritpt(Control::PostRequest("f_realname", ""));
            $formWangName = Format::RemoveScritpt(Control::PostRequest("f_guestname", ""));
            $documentThreadData = new DocumentThreadData();
            $newId = $documentThreadData->Create();
            if ($newId > 0) {
                $documentPostData = new DocumentPostData();
                $isThread = 1;
                $adminUserId = 0;
                $adminUserName = "";
                $sort = 0;
                $userGroupId = 0;
                $postId = $documentPostData->CreatePost($newId, $formDocumentChannelId, $isThread, $formSubject, $formCreateDate, $adminUserId, $adminUserName, $userId, $userName, $userGroupId, $formWangName, $sort, $formContent, $uploadFiles);
                //加入操作log
                $operateContent = "Thread：NewThread id ：" . $newId . "；name；" . $formRealName . "；adminuserid：；result：" . $postId . "；title：" . $formContent;
                $adminUserLogData = new AdminUserLogData();
                $adminUserLogData->Insert($operateContent);
                if ($postId > 0) {
                    include RELATIVE_PATH . '/inc/domain.inc.php';
                    $_funcUrl = $domain['func'];

                    $_pos = stripos($_funcUrl, "http://");
                    if ($_pos === false) {
                        $_funcUrl = "http://" . $_funcUrl;
                    }

                    //修改上传文件的tableid;
                    $fileType = 4; //咨询问答上传
                    $commonGen = new CommonGen();
                    $commonGen->UploadFile("fileToUpload", $fileType, 2, $uploadFileId);
                    if (intval($uploadFileId) > 0) {
                        $uploadFileData = new UploadFileData();
                        $uploadFileData->ModifyTableID(intval($uploadFileId), $postId);
                        //修改post表中的图片上传
                        $uploadFiles = "," . $uploadFileId;
                        $uploadFilePath = $uploadFileData->GetUploadFile($uploadFileId);
                        $content = $formContent . '<p>&nbsp;<a href="' . $_funcUrl . $uploadFilePath . '" target="_blank" alt="点击查看原图"><img src="' . $_funcUrl . $uploadFilePath . '" alt="' . $uploadFileId . '" id="msimg" onload="AutoChangeImg(this)" /></a></p>';
                        $content = str_ireplace("../", "/", $content);
                        $content = str_ireplace("./", "/", $content);
                        $content = str_ireplace("\\", "/", $content);
                        $documentPostData->ModifyUploadFile($uploadFiles, $content, $postId);
                    }
                    Control::ShowMessage(Language::Load('document', 18));
                } else {
                    $delId = $documentThreadData->DelPost($newId);
                    Control::ShowMessage(Language::Load('document', 2));
                }
            } else {
                Control::ShowMessage(Language::Load('document', 2));
            }
            $jsCode = 'javascript:window.opener=null;window.open("","_self");window.close();';
            Control::RunJS($jsCode);
            return '';
        }
    }

    /**
     * 前台 网友回复信息
     * @return string 返回网友回复执行结果
     */
    private function GenGuestReply() {
        if (!empty($_POST)) {
            $documentThreadId = Control::PostRequest("tid", "0");
            $documentChannelId = Control::PostRequest("cid", "0");
            $siteId = Control::PostRequest("siteid", "0");
            $guestName = Format::RemoveScritpt(Control::PostRequest("guestname", ""));
            $txtContent = nl2br(Format::RemoveScritpt(Control::PostRequest("txtcontent", "")));
            $fromUrl = Control::PostRequest("fromurl", "");     //返回url
            $txtContent = str_replace("\n", "<br />", $txtContent);
            $userId = Control::GetUserID();
            $userName = Control::GetUserName();

            //判断主题是否异常
            $documentThreadData = new DocumentThreadData();
            $documentThreadstate = $documentThreadData->GetState($documentThreadId);
            //未审核或被删除信息不许进行任何回复
            if ($documentThreadstate < 10 || $documentThreadstate == 100) {
                //返回信息未审核提示0
                Control::ShowMessage(Language::Load('user', 13));
                Control::GoUrl($fromUrl);
                return "";
            }
            $isThread = 0;
            $subject = '';
            $createDate = date("Y-m-d H:i:s", time());
            $adminUserId = 0;
            $adminUserName = '';
            $sort = 0;
            $uploadFiles = "";
            $documentPostData = new DocumentPostData();
            $adminUserLogData = new AdminUserLogData();
            //取得会员用户组ID
            if ($userId > 0) {      //登陆网友
                $userRoledata = new UserRoleData();
                $userGroupId = $userRoledata->GetUserGroupID($userId, $siteId, 0);      //取用户组
                //主办方管理员人及各职能部门回复时就自动审核
                if ($userGroupId == 2 || $userGroupId >= 3) {       //审核特殊处理
                    $state = 10;
                } else {
                    $state = 0;
                }
                if ($userGroupId >= 3) {        //usergroupid >=3 为各职能部门会员回复 需验证信息职权信息
                    $dealUserGroupid = $documentThreadData->GetDealUserGroup($documentThreadId);      //取原待审核部门
                    if (in_array(intval($userGroupId), $dealUserGroupid)) {       //符号进进行
                        $insertId = $documentPostData->CreatePost($documentThreadId, $documentChannelId, $isThread, $subject, $createDate, $adminUserId, $adminUserName, $userId, $userName, $userGroupId, $guestName, $sort, $txtContent, $uploadFiles, $state);
                        //加入操作log
                        $operateContent = "Thread：ReplyThread id ：" . $documentThreadId . "；name；" . $userName . "；adminuserid：" . $userId . "；result：" . $insertId . "；title：" . $txtContent;
                        $adminUserLogData->Insert($operateContent);
                        if ($insertId > 0) {
                            //回复数只统计普通网友
                            $result = $documentThreadData->UpdateCount('postcount', $documentThreadId);
                            //各职能部门会员回复进行自动发布
                            self::Publish(intval($documentThreadId));
                            Control::ShowMessage(Language::Load('siteftpment', 45));
                        } else {
                            Control::ShowMessage(Language::Load('user', 4));     //insert cst_user表出错
                        }
                    } else {
                        Control::ShowMessage(Language::Load('user', 37));     //该信息还不属于您所在部门的处理范畴内
                    }
                } else {      //usergroupid =1 为律师发布  usergroupid =2 为主办方管理员人发布  usergroupid = 0 为普通发布时
                    if ($userGroupId <= 0) {
                        $userGroupId = 0;
                    }
                    $insertId = $documentPostData->CreatePost($documentThreadId, $documentChannelId, $isThread, $subject, $createDate, $adminUserId, $adminUserName, $userId, $userName, $userGroupId, $guestName, $sort, $txtContent, $uploadFiles, $state);
                    //加入操作log
                    $operateContent = "Thread：ReplyThread id ：" . $documentThreadId . "；name；" . $userName . "；adminuserid：" . $userId . "；result：" . $insertId . "；title：" . $txtContent;
                    $adminUserLogData->Insert($operateContent);
                    if ($insertId > 0) {
                        //回复数只统计普通网友
                        $result = $documentThreadData->UpdateCount('postcount', $documentThreadId);
                        if ($userGroupId == 2) {    // usergroupid =2 为主办方管理员人发布
                            //主办方管理员人回复进行自动发布
                            self::Publish(intval($documentThreadId));
                        }
                        Control::ShowMessage(Language::Load('siteftpment', 45));
                    } else {
                        Control::ShowMessage(Language::Load('user', 4));     //insert cst_user表出错
                    }
                }
            } else {        //未登陆网友(游客)发布时
                $insertId = $documentPostData->CreatePost($documentThreadId, $documentChannelId, $isThread, $subject, $createDate, $adminUserId, $adminUserName, $userId, $userName, $userGroupId, $guestName, $sort, $txtContent, $uploadFiles, $state);
                //加入操作log
                $operateContent = "Thread：ReplyThread id ：" . $documentThreadId . "；name；" . $userName . "；adminuserid：" . $userId . "；result：" . $insertId . "；title：" . $txtContent;
                $adminUserLogData->Insert($operateContent);
                if ($insertId > 0) {
                    //回复数只统计普通网友
                    $result = $documentThreadData->UpdateCount('postcount', $documentThreadId);
                    Control::ShowMessage(Language::Load('siteftpment', 45));
                } else {
                    Control::ShowMessage(Language::Load('user', 4));     //insert cst_user表出错
                }
            }
            Control::GoUrl($fromUrl);       //返回原页面
            return "";
        }
    }

    /**
     * 前台职能部门,律师等回复 回复免审核并自动发布 $userGroupId == 2,3
     * @return string 返回职能部门等回复处理结果
     */
    private function GenReplyThread() {
        if (!empty($_POST)) {
            $documentThreadId = Control::PostRequest("tid", "0");
            $documentChannelId = Control::PostRequest("cid", "0");
            $siteId = Control::PostRequest("siteid", "0");
            $guestName = Format::RemoveScritpt(Control::PostRequest("guestname", ""));
            $txtContent = nl2br(Format::RemoveScritpt(Control::PostRequest("txtcontent", "不错，支持下")));
            $userId = Control::GetUserID();
            $userName = Control::GetUserName();
            include RELATIVE_PATH . '/inc/domain.inc.php';
            $_funcUrl = $domain['func'];

            $_pos = stripos($_funcUrl, "http://");
            if ($_pos === false) {
                $_funcUrl = "http://" . $_funcUrl;
            }
            $reurl = $_funcUrl . '/default.php?mod=documentthread&viewtype=preview&m=view&tid=' . $documentThreadId;
            //判断主题是否异常
            $documentThreadData = new DocumentThreadData();
            $state = $documentThreadData->GetState($documentThreadId);
            if ($state < 10 || $state == 100) {
                Control::ShowMessage(Language::Load('siteftpment', 47));
                Control::GoUrl($reurl);
                return "";
            }
            //取得会员用户组ID
            if ($userId > 0) {
                $userRoleData = new UserRoleData();
                $userGroupId = $userRoleData->GetUserGroupID($userId, $siteId, 0);
            } else {
                $userGroupId = 0;
            }
            $userGroupId = intval($userGroupId);
            if ($userGroupId >= 3) {
                $result = $documentThreadData->GetDealUserGroup($documentThreadId);
                if (strlen($result) > 1) {
                    if (!in_array($userGroupId, $result)) {
                        Control::ShowMessage(Language::Load('document', 11));
                        Control::GoUrl($reurl);
                        return '';
                    }
                }
            } elseif ($userGroupId <= 0) {
                Control::ShowMessage(Language::Load('document', 11));
                Control::GoUrl($reurl);
                return '';
            }
            $userGroupData = new UserGroupData();
            $userGroupParentId = $userGroupData->GetParentIDByUserGroupID($userGroupId);
            $isThread = 0;
            $subject = '';
            $createDate = date("Y-m-d H:i:s", time());
            $adminUserId = 0;
            $adminUserName = '';
            $sort = 0;
            $uploadFiles = '';
            $documentPostData = new DocumentPostData();
            //民声站回复就自动审核
            if ($userGroupId == 2 || $userGroupParentId == 3) {
                $state = 10;
                // $state = 0;
            } else {
                $state = 0;
            }
            $insertId = $documentPostData->CreatePost($documentThreadId, $documentChannelId, $isThread, $subject, $createDate, $adminUserId, $adminUserName, $userId, $userName, $userGroupId, $guestName, $sort, $txtContent, $uploadFiles, $state);

            //加入操作log
            $operateContent = "Thread：ReplyThread id ：" . $documentThreadId . "；name；" . Control::GetAdminUserName() . "；adminuserid：" . Control::GetAdminUserID() . "；result：" . $insertId . "；title：" . Control::PostRequest("txtcontent", "");
            $adminUserLogData = new AdminUserLogData();
            $adminUserLogData->Insert($operateContent);
            if ($insertId > 0) {
                //回复数只统计普通网友
                $result = $documentThreadData->UpdateCount('postcount', $documentThreadId);
                //民声站回复就自动发布
                if ($userGroupId == 1 || $userGroupParentId == 3) {
                    self::Publish(intval($documentThreadId));
                }
                Control::ShowMessage(Language::Load('siteftpment', 45));
            } else {
                Control::ShowMessage(Language::Load('user', 4));     //insert cst_user表出错
            }

            $jsCode = 'javascript:window.opener=null;window.open("","_self");window.close();';
            Control::RunJS($jsCode);
            return "";
        }
    }

    /**
     * 前台静态页面中调用网友回复信息
     * @return arr 返回jsonp格式数据 
     */
    private function GenHtmlListPost() {
        $documentThreadId = Control::GetRequest("tid", -1);
        $userGroupId = Control::GetRequest("usergroupid", -1);
        $state = Control::GetRequest("state", -1);
        $pageSize = Control::GetRequest("ps", 20);
        $searchKey = Control::GetRequest("searchkey", "");
        $searchKey = urldecode($searchKey);
        $pageIndex = Control::GetRequest("p", 1);
        if (intval($userGroupId) < 0) {
            $userGroupId = 0;
        }   //按用户组进行显示
        if (intval($state) < 0) {
            $state = 10;
        }  //按信息状态进行显示

        $pageBegin = ($pageIndex - 1) * $pageSize;
        $documentPostData = new DocumentPostData();
        $arrList = $documentPostData->GetHtmlListPager($searchKey, $pageBegin, $pageSize, $documentThreadId, $userGroupId, $state);
        $tempArrList = array();
        if (count($arrList) > 0) {
            $pagerTemplate = Template::Load("pager_js.html");
            $isJs = true;
            $jsFunctionName = "preshow";
            $jsParamList = "," . $documentThreadId . "";
            $allCount = $documentPostData->GetRowNum($searchKey, $documentThreadId, $userGroupId, $state);
            $pagerButton = Pager::ShowPageButton($pagerTemplate, "", $allCount, $pageSize, $pageIndex, $isJs, $jsFunctionName, $jsParamList);
            //在jsonp中加分页信息
            $tempArrList = array();
            foreach ($arrList as $columnname => $columnvalue) {
                $columnvalue[pagerbutton] = $pagerButton;
                $tempArrList[] = $columnvalue;
            }
        }

        if (isset($_GET['jsonpcallback'])) {
            return $_GET['jsonpcallback'] . '(' . json_encode($tempArrList) . ')';
        }
    }

    /**
     * 网友评分处理,可对主题及回复跟贴进行评分操作
     * rate 分数  countrate评分次数 recommon状态标识 1评分成功  0只读取评分信息 -1tid传递出错 -2评分数据处理出错
     * id 评分对应的id
     * ratestype 表示评分的种类
     * rates 评的分数 1000 为只读取分值操作
     * @return arr 返回jsonp格式数据
     */
    private function GenRates() {
        if (!empty($_GET)) {
            $id = Control::GetRequest("id", "0");
            $ratesType = Control::GetRequest("ratestype", "post");
            $rates = Control::GetRequest("rates", "0");
            $userId = Control::GetUserID();
            $documentTable = 0;         //评分对象对应的表编号0表示documentpost
            $ratingType = 0;            //评分对象的分类      0表示是documentpost中的对象,民声站中的咨询互动类信息

            $documentRateData = new DocumentRatingData();
            if ($id > 0) {
                if ($rates != 1000) {
                    //进行评分类操作
                    $rateIp = Control::GetIP();
                    if (strtolower($ratesType) == 'thread') {
                        
                    } elseif (strtolower($ratesType) == 'post') {
                        $createDate = date("Y-m-d H:i:s", time());
                        $insertId = $documentRateData->CreateRates($id, $documentTable, $rates, $ratingType, $userId, $createDate, $rateIp);
                        if ($insertId > 0) {
                            $reupRate = $documentRateData->GetRstes($id, $documentTable, $ratingType);
                            $upDate = round($reupRate[sumrate] / $reupRate[countrate], 2);
                            $upStr = "rating";
                            $documentPostData = new DocumentPostData();
                            $documentPostData->UpdateRates($id, $upStr, $upDate);
                            if (isset($_GET['jsonpcallback'])) {
                                //评分成功类信息 返回recommon为1
                                return $_GET['jsonpcallback'] . '([{rate:"' . $upDate . '",countrate:"' . $reupRate[countrate] . '",recommon:"1"}])';
                            }
                        } else {
                            if (isset($_GET['jsonpcallback'])) {
                                //评分系统出错 返回recommon为-2
                                return $_GET['jsonpcallback'] . '([{rate:"' . $upDate . '",countrate:"' . $reupRate[countrate] . '",recommon:"-2"}])';
                            }
                        }
                    }
                } else {
                    if (strtolower($ratesType) == 'thread') {
                        
                    } elseif (strtolower($ratesType) == 'post') {
                        if (isset($_GET['jsonpcallback'])) {
                            //只读取评分信息 返回recommon为0
                            $reupRate = $documentRateData->GetRstes($id, $documentTable, $ratingType);
                            if (intval($reupRate['sumrate']) <= 0) {
                                $upDate = 0;
                                $upCount = 0;
                            } else {
                                $upDate = round(intval($reupRate['sumrate']) / intval($reupRate['countrate']), 2);
                                $upCount = $reupRate['countrate'];
                            }
                            return $_GET['jsonpcallback'] . '([{rate:"' . $upDate . '",countrate:"' . $upCount . '",recommon:"0"}])';
                        }
                    }
                }
            } else {
                if (isset($_GET['jsonpcallback'])) {
                    //参数tid传递出错信息  返回recommon为-1
                    $reupRate = $documentRateData->GetRstes($id, $documentTable, $ratingType);
                    if (intval($reupRate[sumrate]) <= 0) {
                        $rateCount = 0;
                        $rateUp = 0;
                    } else {
                        $rateCount = intval($reupRate['countrate']);
                        $rateUp = round(intval($reupRate['sumrate']) / intval($reupRate['countrate']), 2);
                    }
                    return $_GET['jsonpcallback'] . '([{rate:"' . $rateUp . '",countrate:"' . $rateCount . '",recommon:"-1"}])';
                }
            }
        }
    }

    /**
     * 更新浏览数
     * @return arr 返回jsonp格式数据
     */
    private function GenViewCount() {
        if (!empty($_GET)) {
            $id = Control::GetRequest("id", "0");
            $reType = Control::GetRequest("retype", "0");
            $reCommon = 0;
            if ($id > 0) {
                $documentThreadData = new DocumentThreadData();
                $result = $documentThreadData->UpdateCount('viewcount', $id);
                if ($result) {
                    //成功 返回recommon为1
                    $reCommon = 1;
                } else {
                    //评分系统出错 返回recommon为-2
                    $reCommon = -2;
                }
            } else {
                //参数tid传递出错信息  返回recommon为-1
                $reCommon = -1;
            }
            //返回更新结果
            if ($reType > 0) {
                if (isset($_GET['jsonpcallback'])) {
                    return $_GET['jsonpcallback'] . '([{recommon:"' . $reCommon . '"}])';
                }
            }
        }
    }

}

?>
