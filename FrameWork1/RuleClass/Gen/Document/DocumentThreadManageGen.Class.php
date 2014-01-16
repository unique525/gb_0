<?php

/**
 * Description of DocumentThreadManageGen
 * 咨询问答模块后台程序处理类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Document
 * @author Liujunyi
 */
class DocumentThreadManageGen extends BaseManageGen implements IBaseManageGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function Gen() {
        $reSult = "";
        $method = Control::GetRequest("m", "");
        switch ($method) {
            case "edit":
                $reSult = self::GenEditThread();
                break;
            case "list":
                $reSult = self::GenListThread();
                break;
            case "post":
                $reSult = self::GenPost();
                break;
            case "changestate":
                $reSult = self::GenChangeState();
                break;
            case "publish":
                $reSult = self::Publish(0);
                break;
            case "publishall":
                $reSult = self::PublishAll();
                break;
            case "preview":         //民声站后台预览及管理回复
                $reSult = self::GenPreviewThread();
                break;
            case "delete":          //删除主题信息并一起删除该主题下的回复信息
                $reSult = self::GenDeleteThread();
                break;
        }

        $replaceArr = array(
            "{method}" => $method
        );
        $reSult = strtr($reSult, $replaceArr);
        return $reSult;
    }

    /**
     * 后台咨询回复用
     * @return string 返回执行结果
     */
    public function GenPost() {
        $reSult = "";
        $faction = Control::GetRequest("f", "");
        switch ($faction) {
            case "view":
                $reSult = self::GenViewPost();
                break;
            case "edit":
                $reSult = self::GenEditPost();
                break;
            case "list":
                $reSult = self::GenListPost();
                break;
        }

        $replaceArr = array(
            "{faction}" => $faction
        );
        $reSult = strtr($reSult, $replaceArr);
        return $reSult;
    }

    /**
     * 后台预览及管理回复
     * @return string 返回执行结果
     */
    private function GenPreviewThread() {
        $documentThreadId = Control::GetRequest("tid", 0);
        $viewType = Control::GetRequest("viewtype", "");
        $adminUserId = Control::GetAdminUserID();
        $adminUserName = Control::GetAdminUserName();
        include RELATIVE_PATH . '/inc/domain.inc.php';
        $icmsUrl = $domain['icms'];
        $pos = stripos($icmsUrl, "http://");
        if ($pos === false) {
            $icmsUrl = "http://" . $icmsUrl;
        }
        $reUrl = $icmsUrl . '/default.php?mod=documentthreadmanage&m=preview&tid=' . $documentThreadId;
        if ($adminUserId <= 0) {
            Control::ShowMessage(Language::Load('user', 18));
            Control::GoUrl($reUrl);
            return "";
        }
        $documentThreadManageData = new DocumentThreadManageData();
        $documentPostManageData = new DocumentPostManageData();
        if (!empty($_POST)) {
            $formDocumentThreadId = Control::PostRequest("tid", "0");
            $formDocumentChannelId = Control::PostRequest("cid", "0");
            $formSiteId = Control::PostRequest("siteid", "0");
            $formGuestName = Format::RemoveScritpt(Control::PostRequest("guestname", ""));
            $formTxtcontent = Format::RemoveScritpt(Control::PostRequest("txtcontent", "不错，支持下"));
            $adminuserdata = new AdminUserData();
            $userId = $adminuserdata->GetUserID($adminUserId);
            $userName = $adminuserdata->GetUserName($adminUserId);
            //$userId = Control::GetUserID();
            //$userName = Control::GetUserName();
            //判断主题是否异常
            $state = $documentThreadManageData->GetState($formDocumentThreadId);
            if ($state < 10 || $state == 100) {
                Control::ShowMessage(Language::Load('siteftpment', 47));
                Control::GoUrl($reUrl);
                return "";
            }
            //取得usertype
            if ($userId > 0) {
                $userRoleData = new UserRoleData();
                $userGroupId = $userRoleData->GetUserGroupID($userId, $formSiteId, 0);
            } else {
                $userGroupId = 0;
            }
            $userGroupId = intval($userGroupId);
            if ($userGroupId >= 3) {
                $reSult = $documentThreadManageData->GetDealUserGroup($formDocumentThreadId);
                if (strlen($reSult) > 1) {
                    if (!in_array($userGroupId, $reSult)) {
                        Control::ShowMessage(Language::Load('document', 11));
                        Control::GoUrl($reUrl);
                        return '';
                    }
                }
            } elseif ($userGroupId <= 0) {
                Control::ShowMessage(Language::Load('document', 11));
                Control::GoUrl($reUrl);
                return '';
            }
            $isThread = 0;
            $formDealUserGroupIdubject = '';
            $createDate = date("Y-m-d H:i:s", time());
            $adminUserId = 0;
            $adminUserName = '';
            $formDealUserGroupIdort = 0;
            $uploadFiles = '';
            //民声站后台回复自动审核
            //if ($userGroupId == 2) {
            if ($userGroupId >= 2) {
                $state = 10;
            } else {
                $state = 0;
            }
            $insertId = $documentPostManageData->CreatePost($formDocumentThreadId, $formDocumentChannelId, $isThread, $formDealUserGroupIdubject, $createDate, $adminUserId, $adminusername, $userId, $userName, $userGroupId, $formGuestName, $formDealUserGroupIdort, $formTxtcontent, $uploadFiles, $state);
            //加入操作log
            $operateContent = "Thread：PreviewThread id ：" . $formDocumentThreadId . "；name；" . Control::GetAdminUserName() . "；adminuserid：" . Control::GetAdminUserID() . "；result：" . $insertId . "；title：" . Control::PostRequest("txtcontent", "");
            $adminUserLogData = new AdminUserLogData();
            $adminUserLogData->Insert($operateContent);
            if ($insertId > 0) {
                //回复数只统计普通网友
                $reSult = $documentThreadManageData->UpdateCount('postcount', $formDocumentThreadId);
                //民声站后台回复自动发布
                if ($userGroupId == 2) {
                    self::Publish(intval($formDocumentThreadId));
                }
                Control::ShowMessage(Language::Load('siteftpment', 45));
            } else {
                Control::ShowMessage(Language::Load('user', 4));     //insert cst_user表出错
            }
            $jsCode = 'javascript:window.opener=null;window.open("","_self");window.close();';
            Control::RunJS($jsCode);
            return '';
        }

        if ($documentThreadId <= 0) {
            Control::ShowMessage(Language::Load('siteftpment', 35));
            $reSult = -2;   //发布失败,参数传递出错
            return $reSult;
        }
        $state = $documentThreadManageData->GetState($documentThreadId);
        if ($state < 10 || $state == 100) {
            Control::ShowMessage(Language::Load('siteftpment', 47));
            $reSult = -1;   //发布失败,文档必须为终审或已发状态才能发布//Language::Load('document', 23);
            return $reSult;
        }

        $documentChannelId = $documentThreadManageData->GetChannelID($documentThreadId);
        if ($documentChannelId <= 0) {
            $reSult = -2;   //发布失败,参数传递出错
            return $reSult;
        }
        $documentChannelTemplateData = new DocumentChannelTemplateData();
        $documentChannelData = new DocumentChannelData();

        $tempContent = Template::Load("thread/thread_view.tpl");

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
                foreach ($arr2 as $key => $val) {
                    $docContent = '<cscms' . $val . '</cscms>';
                    $id = Template::GetDocParamValue($docContent, "id");
                    $type = strtolower(Template::GetDocParamValue($docContent, "type"));
                    $where = strtolower(Template::GetDocParamValue($docContent, "where"));
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
                        $arrList = $documentPostManageData->GetTedail($documentThreadId, $pageBegin, $topCount, $isThread, $userGroupId);
                        Template::ReplaceList($docContent, $arrList, $id);
                        $tempContent = Template::ReplaceCMS($tempContent, $id, $docContent);
                    } elseif ($type == 'detail') {
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
                                $topCount = 20;
                                break;
                            case "postlist":
                                $isThread = 0;
                                $userGroupId = 0;
                                $topCount = 20;
                                break;
                        }
                        $pageBegin = 0;

                        $arrList = $documentPostManageData->GetTedail($documentThreadId, $pageBegin, $topCount, $isThread, $userGroupId);
                        Template::ReplaceList($docContent, $arrList, $id);
                        $tempContent = Template::ReplaceCMS($tempContent, $id, $docContent);
                    }
                }
            }
        }

        $formDealUserGroupIdubject = $documentThreadManageData->GetSubject($documentThreadId);      //标题信息
        $formSiteId = $documentChannelData->GetSiteID($documentChannelId);
        $replaceArr = array(
            "{rootpath}" => "",
            "{tid}" => $documentThreadId,
            "{cid}" => $documentChannelId,
            "{subject}" => $formDealUserGroupIdubject,
            "{siteid}" => $formSiteId
        );
        $tempContent = strtr($tempContent, $replaceArr);
        return $tempContent;
    }

    /**
     * 修改Thread信息
     * @return string 返回执行结果
     */
    private function GenEditThread() {
        $tempContent = Template::Load("thread/thread_edit.tpl");
        $documentThreadId = Control::GetRequest("id", 0);
        $tabIndex = Control::GetRequest("tab", 1);
        $adminUserId = Control::GetAdminUserID();
        parent::ReplaceFirst($tempContent);
        $documentThreadManageData = new DocumentThreadManageData();
        $documentPostManageData = new DocumentPostManageData();
        if ($documentThreadId > 0) {
            if (!empty($_POST)) {
                $formDocumentChannelId = Control::PostRequest("f_documentchannelid", "");
                $formDealUserGroupId = Control::PostRequest("f_dealusergroupid", "");
                $formSubject = Control::PostRequest("f_subject", "");
                $formContent = Control::PostRequest("s_content", "");
                //$formUserName = Control::PostRequest("f_uesrname", "");
                $formUserName = "";
                $formGuestName = Control::PostRequest("f_guestname", "");
                $uploadFiles = Control::PostRequest("s_uploadfiles", "");

                $reSult = $documentThreadManageData->Modify($documentThreadId);

                $publishDate = $documentThreadManageData->GetPublishDate($documentThreadId);
                //发布的显示被修改过的状态
                if (!empty($publishDate)) {
                    $state = 1;
                    $documentThreadManageData->UpdateState($documentThreadId, $state);
                }
                if ($reSult > 0) {
                    //修改上传文件的tableid;
                    $formPostId = Control::PostRequest("s_documentpostid", "");
                    $uploadFileArr = split(",", $uploadFiles);
                    $uploadFileData = new UploadFileData();
                    for ($i = 0; $i < count($uploadFileArr); $i++) {
                        if (intval($uploadFileArr[$i]) > 0) {
                            $formContent = str_ireplace('id=\"msimg\"', 'id=\"msimg\" onload=\"AutoChangeImg(this)\"', $formContent);
                            $uploadFileData->ModifyTableID(intval($uploadFileArr[$i]), $formPostId);
                        }
                    }

                    //处理Tag;
                    $formThreadTag = Control::PostRequest("f_threadtag", "");
                    $formOldThreadTag = Control::PostRequest("s_threadtag", "");
                    $formThreadTag = str_ireplace("}{", ",", $formThreadTag);
                    $formThreadTag = str_ireplace("}", "", $formThreadTag);
                    $formThreadTag = str_ireplace("{", "", $formThreadTag);
                    $formThreadTagArr = split(",", $formThreadTag);
                    $documentTagDate = new DocumentTagData();
                    $documentTagTopData = new DocumentTagTopData();

                    $documentChannelData = new DocumentChannelData();
                    $formSiteId = $documentChannelData->GetSiteID($formDocumentChannelId);
                    $documentThreadTypeId = Control::PostRequest("f_documentthreadtypeid", "0");
                    for ($i = 0; $i < count($formThreadTagArr); $i++) {
                        if (strlen($formThreadTagArr[$i]) > 0) {
                            $tagId = $documentTagDate->GetDocumentTagID($formThreadTagArr[$i]);
                            //tag热度处理, 没有就insert tagtop,有就statcount累加
                            if (intval($tagId) > 0) {
                                $countTagId = $documentTagTopData->GetCount($tagId, $formSiteId, $documentThreadTypeId);
                                if (intval($countTagId) > 0) {
                                    $oldTag = "{" . $formThreadTagArr[$i] . "}";
                                    //防重复统计TAG热度
                                    if (empty($formOldThreadTag) || (stristr($oldTag, $formOldThreadTag) === false)) {
                                        $formDealUserGroupIdtr = "statcount";     //statcount 热度进行累加
                                        $documentTagTopData->UpdateCount($formDealUserGroupIdtr, $tagId, $formSiteId, $documentThreadTypeId);
                                    }
                                } else {
                                    $formDealUserGroupIdtatcount = 0;
                                    $documentChannelId = 0;
                                    $documentTagTopData->CreateTagTop($tagId, $documentChannelId, $formSiteId, $formDealUserGroupIdtatcount, $documentThreadTypeId);
                                }
                            }
                        }
                    }
                    //TAG处理end

                    $postResult = $documentPostManageData->UpdateThread($formDocumentChannelId, $formSubject, $formContent, $documentThreadId, $formUserName, $formGuestName, $uploadFiles);

                    if ($postResult > 0) {
                        //Control::ShowMessage(Language::Load('document', 3));
                    } else {
                        Control::ShowMessage(Language::Load('document', 4));
                    }
                } else {
                    Control::ShowMessage(Language::Load('document', 4));
                }
                //加入操作log
                $operateContent = "Thread：editpost id ：" . $documentThreadId . "；name；" . Control::GetAdminUserName() . "；adminuserid：" . Control::GetAdminUserID() . "；result：" . $reSult . "；title：" . Control::PostRequest("f_subject", "");
                $adminUserLogData = new AdminUserLogData();
                $adminUserLogData->Insert($operateContent);


                $jsCode = 'self.parent.loaddocthreadlist(1,"",-1,-1);self.parent.$("#tabs").tabs("select","#tabs-1");';
                if ($tabIndex > 0) {
                    $jsCode = $jsCode . 'self.parent.$("#tabs").tabs("remove",' . ($tabIndex - 1) . ');';
                }


                Control::RunJS($jsCode);
                return "";
            }

            $listName = "usergrouplist";
            $parentId = 3;          //取长沙市各行政回复部门
            $userGroupData = new UserGroupData();
            $arrList = $userGroupData->GetUserGroupList($parentId);
            Template::ReplaceList($tempContent, $arrList, $listName);

            $listName = "threadtypelist";
            $documentThreadTypeData = new DocumentThreadTypeData();
            $formSiteId = $documentThreadManageData->GetSiteID($documentThreadId);
            $type = 0;  //0为按site查询,1为按documentthreadtypename查询
            $arrListt = $documentThreadTypeData->GetDocumentThreadTypeIDList($formSiteId, $type);
            Template::ReplaceList($tempContent, $arrListt, $listName);

            $type = 1;      //1根据threadid取内容，0为根据postid取内容
            $isThread = 1;      //取得主题内容
            $arrlist = $documentPostManageData->GetOne($documentThreadId, $type, $isThread);
            Template::ReplaceOne($tempContent, $arrlist, 1);

            $threadStr = $documentThreadManageData->GetOne($documentThreadId);

            $replaceArr = array(
                "{id}" => $documentThreadId,
                "{username}" => $threadStr['username'],
                //"{guestname}" => $threadStr[guestname],
                "{realname}" => $threadStr['realname'],
                "{threadtag}" => $threadStr['threadtag'],
                "{tel}" => $threadStr['tel'],
                "{email}" => $threadStr['email'],
                "{address}" => $threadStr['address'],
                "{remark}" => $threadStr['remark'],
                "{f_documentthreadtypeid}" => $threadStr['documentthreadtypeid'],
                "{adminuserid}" => $adminUserId
            );
            $tempContent = strtr($tempContent, $replaceArr);
            //单独替换表单选项
            $listName = "usergroupid";
            $checked = 1;           //替换为checked
            $threadStr['dealusergroupid'] = str_ireplace("{", "", $threadStr['dealusergroupid']);
            $threadStr['dealusergroupid'] = str_ireplace("}", ",", $threadStr['dealusergroupid']);
            Template::ReplaceSelect($tempContent, $listName, $threadStr['dealusergroupid'], $checked);
            //单独替换表单选项
            $listName = "documentthreadtypeid";
            $checked = 0;          //替换为checked
            Template::ReplaceSelect($tempContent, $listName, $threadStr['documentthreadtypeid'], $checked);
            //替换掉{t XXX}的内容
            $patterns = "/\{t(.*?)\}/";
            $tempContent = preg_replace($patterns, "", $tempContent);
            //替换掉{s XXX}的内容
            $patterns = "/\{s(.*?)\}/";
            $tempContent = preg_replace($patterns, "", $tempContent);
        } else {
            return "";
        }
        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }

    /**
     * 后台thread列表
     * @return arr 返回列表
     */
    private function GenListThread() {
        $reSult = Language::Load('document', 7);
        $tempContent = Template::Load("thread/thread_list.tpl");
        $documentChannelId = Control::GetRequest("cid", 0);
        $documentThreadTypeId = Control::GetRequest("documentthreadtypeid", 0);
        $state = Control::GetRequest("state", 30);
        $pageSize = Control::GetRequest("ps", 20);
        $pageIndex = Control::GetRequest("p", 1);
        $formDealUserGroupIdearchkey = Control::GetRequest("searchkey", "");
        $formDealUserGroupIdearchkey = urldecode($formDealUserGroupIdearchkey);
        if ($pageIndex > 0 && $documentChannelId > 0) {
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $listName = "documentthreadlist";
            $allCount = 0;
            $documentThreadManageData = new DocumentThreadManageData();
            $arrList = $documentThreadManageData->GetListPager($documentChannelId, $pageBegin, $pageSize, $allCount, $formDealUserGroupIdearchkey, $documentThreadTypeId, $state);

            if (count($arrList) > 0) {
                Template::ReplaceList($tempContent, $arrList, $listName);

                $pagerTemplate = Template::Load("pager_js.html");
                $isJs = true;
                $jsFunCtionName = "loaddocthreadlist";
                $jsParamList = ",'" . urlencode($formDealUserGroupIdearchkey) . "'," . $documentThreadTypeId . "," . $state;
                $pagerButton = Pager::ShowPageButton($pagerTemplate, "", $allCount, $pageSize, $pageIndex, $isJs, $jsFunCtionName, $jsParamList);
                $replaceArr = array(
                    "{documentchannelid}" => 0,
                    "{cid}" => 0,
                    "{pageindex}" => $pageIndex,
                    "{id}" => 0,
                    "{pagerbutton}" => $pagerButton
                );
                $tempContent = strtr($tempContent, $replaceArr);
                parent::ReplaceEnd($tempContent);
                $reSult = $tempContent;
            }
        }
        return $reSult;
    }

    /**
     * 后台跟贴回复管理
     * @return arr 返回回复列表
     */
    private function GenListPost() {
        $tempContent = Template::Load("thread/post_list.tpl");
        $adminUserId = Control::GetAdminUserID();
        $documentChannelId = Control::GetRequest("cid", 0);
        $formDocumentThreadId = Control::GetRequest("tid", -1);
        $userGroupId = Control::GetRequest("usergroupid", -1);
        $userId = Control::GetRequest("userid", -1);
        $state = Control::GetRequest("state", -1);
        $pageSize = Control::GetRequest("ps", 40);
        $formDealUserGroupIdearchkey = Control::GetRequest("searchkey", "");
        $formDealUserGroupIdearchkey = urldecode($formDealUserGroupIdearchkey);
        $pageIndex = Control::GetRequest("p", 1);
        if ($pageIndex > 0) {
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $listName = "managepostlist";
            $allCount = 0;
            $documentPostManageData = new DocumentPostManageData();
            $arrList = $documentPostManageData->GetListPager($pageBegin, $pageSize, $allCount, $formDealUserGroupIdearchkey, $documentChannelId, $formDocumentThreadId, $userGroupId, $userId, $state);
            if (count($arrList) > 0) {
                Template::ReplaceList($tempContent, $arrList, $listName);
                $pagerTemplate = Template::Load("pager.html");
                $isJs = false;
                $jsFunCtionName = "";
                $jsParamList = "";
                $pagerUrl = "default.php?mod=documentthreadmanage&m=post&f=list&cid=" . $formDocumentChannelId . "&tid=" . $formDocumentThreadId . "&usergroupid=" . $userGroupId . "&userid=" . $userId . "&state=" . $state . "&searchkey=" . urlencode($formDealUserGroupIdearchkey) . "&ps=" . $pageSize . "&p={0}";
                $pagerButton = Pager::ShowPageButton($pagerTemplate, $pagerUrl, $allCount, $pageSize, $pageIndex, $isJs, $jsFunCtionName, $jsParamList);

                $replaceArr = array(
                    "{r_cid}" => $documentChannelId,
                    "{r_tid}" => $formDocumentThreadId,
                    "{r_usergroupid}" => $userGroupId,
                    "{r_userid}" => $userId,
                    "{r_state}" => $state,
                    "{r_searchkey}" => $formDealUserGroupIdearchkey,
                    "{r_ps}" => $pageSize,
                    "{r_p}" => $pageIndex,
                    "{pagerbutton}" => $pagerButton
                );
                $tempContent = strtr($tempContent, $replaceArr);
                parent::ReplaceEnd($tempContent);
                $reSult = $tempContent;
            } else {
                echo (Language::Load('common', 2));
            }
        }
        return $reSult;
    }

    /**
     * 后台回复编辑
     * @return string 返回执行结果
     */
    private function GenEditPost() {
        $tempContent = Template::Load("thread/post_edit.tpl");
        $postId = Control::GetRequest("pid", 0);
        $adminUserId = Control::GetAdminUserID();
        parent::ReplaceFirst($tempContent);
        if ($postId > 0) {
            $documentPostManageData = new DocumentPostManageData();
            if (!empty($_POST)) {
                $formDocumentThreadId = Control::PostRequest("f_documentthreadid", "");
                $formSubject = Control::PostRequest("f_subject", "");
                $formContent = Control::PostRequest("f_content", "");
                $formUserName = Control::PostRequest("f_username", "");
                $reSult = $documentPostManageData->Modify($postId);
                //加入操作log
                $operateContent = "Thread：editpost id ：" . $postId . "；name；" . Control::GetAdminUserName() . "；adminuserid：" . Control::GetAdminUserID() . "；result：" . $reSult . "；title：" . Control::PostRequest("f_subject", "");
                $adminUserLogData = new AdminUserLogData();
                $adminUserLogData->Insert($operateContent);
                if ($reSult > 0) {
                    Control::ShowMessage(Language::Load('document', 3));
                    $jsCode = 'self.parent.loadmanagepostlist(1,0,' . $formDocumentThreadId . ',-1,-1,-1,"");self.parent.tb_remove();';
                } else {
                    Control::ShowMessage(Language::Load('document', 4));
                    $jsCode = '/default.php?mod=documentthreadmanage&m=post&f=edit&pid=' . $postId . '&height=&width=&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=true';
                }
                Control::RunJS($jsCode);
                return "";
            }

            $type = 0;
            $arrlist = $documentPostManageData->GetOne($postId, $type);
            Template::ReplaceOne($tempContent, $arrlist, 1);
            //替换掉{t XXX}的内容
            $patterns = "/\{t(.*?)\}/";
            $tempContent = preg_replace($patterns, "", $tempContent);
            //替换掉{s XXX}的内容
            $patterns = "/\{s(.*?)\}/";
            $tempContent = preg_replace($patterns, "", $tempContent);
        } else {
            return "";
        }
        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }

    /**
     * 详细页面发布
     * @param int $documentThreadId  咨询主题ID
     * @return string 返回发布后结果
     */
    public function Publish($documentThreadId) {
        $adminUserId = Control::GetAdminUserID();
        if ($documentThreadId <= 0) {
            $documentThreadId = Control::GetRequest("id", 0);
        }
        if ($documentThreadId <= 0) {
            Control::ShowMessage(Language::Load('siteftpment', 35));
            return "";
        }
        $documentThreadManageData = new DocumentThreadManageData();
        $state = $documentThreadManageData->GetState($documentThreadId);
        if ($state < 10 || $state == 100) {
            Control::ShowMessage(Language::Load('siteftpment', 47));
            return "";
        }
        $documentChannelId = $documentThreadManageData->GetChannelID($documentThreadId);
        $tempLateType = Control::GetRequest("t", 13); //附件默认存在index模板中
        if (intval($documentChannelId) <= 0) {
            return "";
        }
        $documentChannelTemplateData = new DocumentChannelTemplateData();
        $documentChannelData = new DocumentChannelData();
        $documentPostManageData = new DocumentPostManageData();
        $tempLateType = 13; //内容页
        //=======第1步：读取频道模板
        $tempContent = $documentChannelTemplateData->GetForeachContent($documentChannelId, $tempLateType);
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
                foreach ($arr2 as $key => $val) {
                    $docContent = '<cscms' . $val . '</cscms>';
                    $id = Template::GetDocParamValue($docContent, "id");
                    $type = strtolower(Template::GetDocParamValue($docContent, "type"));
                    $where = strtolower(Template::GetDocParamValue($docContent, "where"));
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
                                $topCount = 20;
                                break;
                            case "postlist":
                                $isThread = 0;
                                $userGroupId = 0;
                                $topCount = 20;
                                break;
                        }
                        $pageBegin = 0;
                        $arrList = $documentPostManageData->GetTedail($documentThreadId, $pageBegin, $topCount, $isThread, $userGroupId);
                        Template::ReplaceList($docContent, $arrList, $id);
                        $tempContent = Template::ReplaceCMS($tempContent, $id, $docContent);
                    } elseif ($type == 'detail') {
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
                                $topCount = 20;
                                break;
                            case "postlist":
                                $isThread = 0;
                                $userGroupId = 0;
                                $topCount = 20;
                                break;
                        }
                        $pageBegin = 0;

                        $arrList = $documentPostManageData->GetTedail($documentThreadId, $pageBegin, $topCount, $isThread, $userGroupId);
                        Template::ReplaceList($docContent, $arrList, $id);
                        $tempContent = Template::ReplaceCMS($tempContent, $id, $docContent);
                    }
                }
            }
        }
        $pagerButton = "";
        $userGroupId = 0;
        $state = 10;
        $pageSize = 20;
        $pageIndex = 1;
        $pagerTemplate = Template::Load("pager_js.html");
        $isJs = true;
        $jsFunCtionName = "showpage";
        $jsParamList = "," . $documentThreadId . "";
        $allCount = $documentPostManageData->GetRowNum($formDealUserGroupIdearchkey, $documentThreadId, $userGroupId, $state);
        $pagerButton = Pager::ShowPageButton($pagerTemplate, "", $allCount, $pageSize, $pageIndex, $isJs, $jsFunCtionName, $jsParamList);

        $formDealUserGroupIdubject = $documentThreadManageData->GetSubject($documentThreadId);      //标题信息
        $description = $documentPostManageData->GetContentByThreadID($documentThreadId);
        $description = str_replace(array('&nbsp;', "\r", "\n", '\'', '"'), '', Format::CutStr(trim(strip_tags($description)), 200));      //摘要信息


        $formSiteId = $documentChannelData->GetSiteID($documentChannelId);
        $formDealUserGroupIditedata = new SiteData();
        $formDealUserGroupIditeurl = $formDealUserGroupIditedata->GetSiteUrl($formSiteId);
        $formDealUserGroupIditename = $formDealUserGroupIditedata->GetSiteName($formSiteId);
        $documentChannelName = $documentChannelData->GetName($documentChannelId);

        include RELATIVE_PATH . '/inc/domain.inc.php';
        $funcUrl = $domain['func'];

        $pos = stripos($funcUrl, "http://");
        if ($pos === false) {
            $funcUrl = "http://" . $funcUrl;
        }

        $formUrl = $funcUrl . "/default.php?mod=documentthread&m=reply&tid=" . $documentThreadId;
        $formDealUserGroupIdubbutton = "<input type=\"button\" class=\"btn2\" tabindex=\"0\" onclick=\"subform()\" value=\"\" accesskey=\"s\" />";
        $replaceArr = array(
            "{pubrootpath}" => "",
            "{rootpath}" => "",
            "{tid}" => $documentThreadId,
            "{cid}" => $documentChannelId,
            "{documentchannelid}" => $documentChannelId,
            "{documentchannelname}" => $documentChannelName,
            "{subject}" => $formDealUserGroupIdubject,
            "{description}" => $description,
            "{pagerbutton}" => $pagerButton,
            "{formurl}" => $formUrl,
            "{siteid}" => $formSiteId,
            "{siteurl}" => $formDealUserGroupIditeurl,
            "{sitename}" => $formDealUserGroupIditename,
            "{subbutton}" => $formDealUserGroupIdubbutton
        );
        $tempContent = strtr($tempContent, $replaceArr);

        //发布目录和程序目录在同一站点下时，不发布附件  func分服务器则不进行发布
//        if ($domain['icms'] != $domain['func']) {
        $ftpType = 0; //HTML和相关CSS,IMAGE
        $isThread = 1;          //附件进行发布 $isThread为1根据threadid
        $publishUploadFiles = $documentPostManageData->GetUploaFiles($documentThreadId, $isThread);
        $publishUploadFiles = split(",", $publishUploadFiles);
        $uploadFileData = new UploadFileData();
        //发布图片信息到目标服务器
        for ($i = 0; $i < count($publishUploadFiles); $i++) {
            if (intval($publishUploadFiles[$i]) > 0) {
                $publishFilePath = $uploadFileData->GetUploadFile(intval($publishUploadFiles[$i]));
                parent::Ftp($publishFilePath, $publishFilePath, "", $documentChannelId, $hasFtp, $ftpType);
            }
        }
//        }

        $publishDate = $documentThreadManageData->GetPublishDate($documentThreadId);       //发布时间
        if ($publishDate == '' || $publishDate == '0000-00-00 00:00:00' || empty($publishDate)) {
            $state = 30;
            //按首次发布操作取发布时间
            //$publishDate = $documentThreadManageData->GetCreateDate($documentThreadId);          //导入数据时完全发布时取CreateDate否则取首次发布操作
            //if ($publishDate === '' || $publishDate === '0000-00-00 00:00:00' || empty($publishDate)) {
            $publishDate = date("Y-m-d H:i:s", time());
            //}
            $documentThreadManageData->ModifyPublishDate($publishDate, $state, $documentThreadId);
        } else {
            $documentThreadManageData->UpdateState($documentThreadId, 30);
        }
        $pubDir = parent::Get_PublishPath($documentChannelId, $rank);
        $datePath = Format::Get_DetailName($publishDate);
        $templatePublishFileName = $documentThreadId . '.html';

        if (strlen($tempContent) > 0) {
            //站点信息
            $siteGen = new SiteGen();
            $siteGen->SubGen($formSiteId, $tempContent);
            //站点配置信息
            $siteConfigGen = new SiteConfigGen();
            $siteConfigGen->SubGen($formSiteId, $tempContent);

            //友情链接
            $siteLinkGen = new SiteLinkGen();
            $siteLinkGen->GenSub($tempContent);
        }

        $tableType = 2;     //documentthread表为2
        parent::AddVisitJsToTemplate($tempContent, $funcUrl, $formSiteId, $documentChannelId, $tableType, $documentThreadId);
        $desPath = "/" . $pubDir . $datePath . '/' . $templatePublishFileName;
        parent::Ftp($desPath, "", $tempContent, $documentChannelId, $hasFtp, $ftpType);
        //联动发布所在频道和上级频道
        $documentChannelGen = new DocumentChannelGen();
        $documentChannelGen->PublishMuti($documentChannelId);
        //$reSult = Language::Load('document', 8);
        $reSult = 1;
        //加入操作log
        $operateContent = "Thread：Publish id ：" . $documentThreadId . "；name；" . Control::GetAdminUserName() . "；adminuserid：" . Control::GetAdminUserID();
        $adminUserLogData = new AdminUserLogData();
        $adminUserLogData->Insert($operateContent);
        return $reSult;
    }

    /**
     * 批量发布
     */
    private function PublishAll() {
        $url = $_SERVER["PHP_SELF"];
        $documentThreadManageData = new DocumentThreadManageData();
        $documentPostManageData = new DocumentPostManageData();
        $state = 10;  //终审
        $limit = 100;  //一次处理10条
        $idArrList = $documentThreadManageData->GetThreadidListAll($state, $limit);
        if (count($idArrList) > 0) {
            foreach ($idArrList as $cname => $id) {
                $formDocumentThreadId = $id["documentthreadid"];
                if (intval($formDocumentThreadId) > 0) {
                    //$postcount = $documentPostManageData->GetPostCount($formDocumentThreadId);
                    //$formGuestName = $documentPostManageData->GetGuestName($formDocumentThreadId);
                    //$reSult = $documentThreadManageData->UpdateGuest($formDocumentThreadId, $formGuestName, $postcount);
                    $reSult = self::Publish(intval($id["documentthreadid"]));
                    echo "id:" . intval($id["documentthreadid"]) . $reSult . "<br />";
                }
            }
            header('refresh:0 ' . $url);
        } else {
            echo "任务已完成";
        }
        // 用于调试时便于显示任务运行状态而增加的延迟
        sleep(1);
    }

    /**
     * 改变信息状态
     * state 为要改变的状态值：0为初始未审核,10为正常,100为删除
     * statetype 为要改变状态的信息对应的属性：1为主贴，0为跟贴回复post
     * @return jsonp 返回执行结果
     */
    private function GenChangeState() {
        $id = Control::GetRequest("id", 0);
        $state = Control::GetRequest("state", 1);
        $stateType = Control::GetRequest("statetype", 1);
        $adminUserId = Control::GetAdminUserID();
        $reSult = 0;
        $documentThreadManageData = new DocumentThreadManageData();
        $documentPostManageData = new DocumentPostManageData();
        if ($id > 0 && $state >= 0) {
            $adminUserLogData = new AdminUserLogData();
            if ($stateType == 1) {
                $reSultt = $documentThreadManageData->UpdateState($id, $state);
                $reSultp = $documentPostManageData->UpdateState($id, $state, $stateType);
                //加入操作log
                $operateContent = "Thread：UpdateState id ：" . $id . "；state：" . $state . "；name；" . Control::GetAdminUserName() . "；adminuserid：" . Control::GetAdminUserID();
                $adminUserLogData->Insert($operateContent);
                if ($state == 100) {
                    $publishDate = $documentThreadManageData->GetPublishDate($id);
                    if (!empty($publishDate)) {
                        $datePath = Format::Get_DetailName($publishDate);
                        $documentChannelId = $documentThreadManageData->GetChannelID($id);
                        $documentChannelData = new DocumentChannelData();
                        $rank = $documentChannelData->GetRank($documentChannelId);
                        $hasFtp = $documentChannelData->GetHasFtp($documentChannelId);
                        $ftpType = 0;   //HTML和相关CSS,IMAGE
                        $pubDir = parent::Get_PublishPath($documentChannelId, $rank);
                        $desPath = $pubDir . $datePath . DIRECTORY_SEPARATOR . $id . '.html';

                        $isThread = 1;
                        $publishUploadFiles = $documentPostManageData->GetUploaFiles($id, $isThread);
                        $publishUploadFiles = split(",", $publishUploadFiles);
                        $uploadFileData = new UploadFileData();
                        //删除稿子中的附件
                        for ($i = 0; $i < count($publishUploadFiles); $i++) {
                            if (intval($publishUploadFiles[$i]) > 0) {
                                $publishFilePath = $uploadFileData->GetUploadFile(intval($publishUploadFiles[$i]));
                                parent::DelFtp($publishFilePath, $documentChannelId, $hasFtp, $ftpType);
                            }
                        }
                        parent::DelFtp($desPath, $documentChannelId, $hasFtp, $ftpType);
                    }
                }
                if ($reSultt > 0 && $reSultp > 0) {
                    //成功  返回result为1
                    $reSult = 1;
                } else {
                    //数据处理出错信息  返回result为-2
                    $reSult = -2;
                }
                return $reSult;
            } elseif ($stateType == 0) {            //post表中的审核等改变状态信息
                //自动发布关联参数
                $userGroupId = Control::GetRequest("usergroupid", 0);
                $documentThreadId = Control::GetRequest("documenttid", 0);
                $reSultp = $documentPostManageData->UpdateState($id, $state, $stateType);
                //加入操作log
                $operateContent = "Thread：post UpdateState id ：" . $id . "；state：" . $state . "；name；" . Control::GetAdminUserName() . "；adminuserid：" . Control::GetAdminUserID();
                $adminUserLogData->Insert($operateContent);
                if ($reSultp > 0) {
                    //成功  返回result为1
                    $reSult = 1;
                } else {
                    //数据处理出错信息  返回result为-2
                    $reSult = -2;
                }
                //部门及律师等的审核否自动关联发布
                if (intval($state) == 10 && $documentThreadId > 0) {
                    self::Publish($documentThreadId);
                }
                if (intval($state) == 100 && $documentThreadId > 0) {
                    self::Publish($documentThreadId);
                }
                Control::ShowMessage($documentThreadId);
            }
        } else {
            //参数传递出错信息 返回result为-1
            if ($stateType == 1) {
                return -1;
            } elseif ($stateType == 0) {
                $reSult = -1;
            }
        }
        return $_GET['jsonpcallback'] . '([{state:"' . $_GET["state"] . '",result:"' . $reSult . '"}])';
    }

    /**
     *  生成列表型子内容
     * @param string $tempContent 返回执行结果
     */
    public function GenSub(&$tempContent) {
        $arr = Template::GetAllCMS($tempContent);
        if (isset($arr)) {
            if (count($arr) > 1) {
                if (!empty($arr[1])) {
                    $documentThreadManageData = new DocumentThreadManageData();
                    //处理模板内容
                    ///////////////////////////////////////////////////////////
                    ///////找出cscms列表模板////////////////////////////////////
                    ////////////////////////////////////////////////////////////
                    $replaceArr = array(
                        "{pubrootpath}" => "",
                        "{documentchannelname}" => "",
                        "{navigation}" => $navigation,
                        "{navigationorder}" => $navigationorder,
                        "{pagerbutton}" => ""
                    );
                    $tempContent = strtr($tempContent, $replaceArr);

                    $arr2 = $arr[1];
                    foreach ($arr2 as $key => $val) {
                        $docContent = '<cscms' . $val . '</cscms>';
                        //查询类型，比如新闻类，咨询类
                        $type = strtolower(Template::GetDocParamValue($docContent, "type"));
                        //查询排序方式，比如“最新”、“热门”
                        $order = Template::GetDocParamValue($docContent, "order");
                        //查询条件
                        $where = strtolower(Template::GetDocParamValue($docContent, "where"));
                        //频道ID
                        $documentChannelId = Template::GetDocParamValue($docContent, "id");
                        //是否调用下级频道内容
                        $hassub = Template::GetDocParamValue($docContent, "hassub");
                        //显示条数
                        $topCount = Template::GetDocParamValue($docContent, "top");
                        //分页情况下的每页条数
                        $perpage = Template::GetDocParamValue($docContent, "perpage");
                        //标题的长度
                        $formSubjectLen = Template::GetDocParamValue($docContent, "subjectlen");
                        //标题是否加...
                        $formSubjectDot = Template::GetDocParamValue($docContent, "subjectdot");
                        //按分类取数据...
                        $typeid = Template::GetDocParamValue($docContent, "typeid");
                        //按分类取数据...
                        $userGroupId = Template::GetDocParamValue($docContent, "usergroupid");

                        //按时间范围取INT格式...
                        $srchFrom = Template::GetDocParamValue($docContent, "srchfrom");
                        if (empty($formSubjectLen) || $formSubjectLen < 0) {
                            $formSubjectLen = 0;
                        }
                        if (!empty($formSubjectDot) && $formSubjectDot > 0) {
                            $formSubjectDot = 1;
                        } else {
                            $formSubjectDot = 0;
                        }
                        if (empty($userGroupId) || $userGroupId < 0) {
                            $userGroupId = -1;
                        }
                        if (empty($documentChannelId) || $documentChannelId <= 0) {
                            $documentChannelId = 0;
                        }
                        //调用下级频道内容
                        if (intval($hassub) < 0) {
                            $hassub = 0;
                        }
                        if (empty($typeid) || $typeid <= 0) {
                            $typeid = 0;
                        }
                        if (empty($srchFrom) || $srchFrom <= 0) {
                            $srchFrom = 0;
                        }
                        ////////////////////投诉，咨询类文档处理/////////////////////////////
                        if (($type == 'docthreadlist') || ($type == 'threadlist')) {
                            if ($where == 'threadtypelist') {
                                //站点id
                                $typeSiteId = Template::GetDocParamValue($docContent, "siteid");
                                $typeParentId = Template::GetDocParamValue($docContent, "parentid");
                                $documentThreadTypeData = new DocumentThreadTypeData();
                                $arrList = $documentThreadTypeData->GetThreadTypeList($typeSiteId, $typeParentId);
                                Template::ReplaceList($docContent, $arrList, $documentChannelId);
                                $tempContent = Template::ReplaceCMS($tempContent, $documentChannelId, $docContent);
                            } elseif ($where == 'postlist') {    //咨询主题与回复内容列表(回复按usergroupid只取一条)
                                $state = 30;
                                $arrDoc = $documentThreadManageData->GetThreadPostList($documentChannelId, $topCount, $order, $typeid, $state, $srchFrom, $hassub, $userGroupId);
                                Template::ReplaceList($docContent, $arrDoc, $documentChannelId);
                                $tempContent = Template::ReplaceCMS($tempContent, $documentChannelId, $docContent);
                            } else {
                                //只生成已发状态的文档
                                $state = 30;
                                $reSult = $documentThreadManageData->GetList($documentChannelId, $topCount, $order, $typeid, $state, $srchFrom, $hassub, $userGroupId);
                                $arrDoc = array();
                                if (count($reSult) > 0) {
                                    foreach ($reSult as $columnName => $columnValue) {
                                        if (!empty($columnValue['dealusergroupid'])) {
                                            $userGroupData = new UserGroupData();
                                            $dealUserGroupId = $columnValue['dealusergroupid'];
                                            $dealUserGroupId = str_ireplace("{", "", $dealUserGroupId);
                                            $dealUserGroupId = str_ireplace("}", "", $dealUserGroupId);
                                            $dealUserGroupShortName = $userGroupData->GetUserGroupShortName($dealUserGroupId);
                                            $columnValue[usergroupnameintro] = $dealUserGroupShortName;
                                        } else {
                                            $columnValue[usergroupnameintro] = "";
                                        }
                                        $arrDoc[] = $columnValue;
                                    }
                                } else {
                                    $arrDoc = $reSult;
                                }

                                Template::ReplaceList($docContent, $arrDoc, $documentChannelId);
                                $tempContent = Template::ReplaceCMS($tempContent, $documentChannelId, $docContent);
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * 删除主题信息并一起删除该主题下的回复信息
     * @return string 返回执行结果
     */
    private function GenDeleteThread() {
        $documentThreadId = Control::GetRequest("id", 0);
        $adminUserId = Control::GetAdminUserID();
        $pageIndex = Control::GetRequest("pageindex", 1);
        $reSult = 0;
        if ($documentThreadId > 0) {
            $documentThreadManageData = new DocumentThreadManageData();
            $state = $documentThreadManageData->GetState($documentThreadId);
            if ($state === 0 || $state === 100) {       //判断处理状态，只能删除处于未审或是已删状态
                $reSult = $documentThreadManageData->DeleteThread($documentThreadId);
                if ($reSult > 0) {
                    //加入删除操作记录
                    $adminUserLogData = new AdminUserLogData();
                    $operateContent = "Thread DeleteThread：ID ：" . $documentThreadId . "；name；" . Control::GetAdminUserName() . "；adminuserid：" . $adminUserId;
                    $adminUserLogData->Insert($operateContent);
                } else {
                    $reSult = -1;
                }
            } else {
                $reSult = -10;
            }
        }
        return $reSult;
    }

}

?>
