<?php
/**
 * 后台管理 管理员权限 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Manage
 * @author zhangchi
 */
class ManageUserAuthorityManageGen extends BaseManageGen implements IBaseManageGen {
    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function Gen()
    {
        $result = "";
        $method = Control::GetRequest("m", "");
        switch ($method) {
            case "set_by_manage_user":
                $result = self::GenSetByManageUser();
                break;
            case "set_by_manage_user_group":
                $result = self::GenSetByManageUserGroup();
                break;
            case "get_child_channel_list":
                $result = self::GenGetChildChannelList();
                break;
        }
        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }


    /**
     * 新增站点
     * @return string 模板内容页面
     */
    private function GenSetByManageUser()
    {
        $siteId=Control::GetRequest("site_id",-1);
        $manageUserIdForSet=Control::GetRequest("manage_user_id",0);
        $channelId=0; //未展开频道树，默认操作站点下所有频道权限

        $manageUserAuthorityManageData=new ManageUserAuthorityManageData();
        /**检查登陆管理员是否有该站点的权限**/
        $manageUserId = Control::GetManageUserId();
        $can_group=$manageUserAuthorityManageData->CanManageUserModify($siteId,$channelId,$manageUserId);
        $can_site=$manageUserAuthorityManageData->CanManageSite($siteId,$channelId,$manageUserId);
        if(!$can_group||!$can_site){
            return Language::Load('document', 26);
        }
        /**                           **/

        $resultJavaScript = "";

        if ($manageUserId > 0) {
            if($manageUserIdForSet > 0){
                $arrayOfOneSiteAuthority=$manageUserAuthorityManageData->GetOneByManageUserId($siteId,$channelId,$manageUserIdForSet);  //siteId 的站点权限
                $arrayOfOneSystemAuthority=$manageUserAuthorityManageData->GetOneByManageUserId(0,$channelId,$manageUserIdForSet); //系统权限
                $tempContent = Template::Load("manage/manage_user_authority_set.html", "common");
                parent::ReplaceFirst($tempContent);

                if (!empty($_POST)) {
                    if($siteId>=0){
                        $explore = Control::PostRequest("f_ChannelExplore", "0");
                        if ($explore == "on") {
                            $explore = "1";
                        }

                        $create = Control::PostRequest("f_ChannelCreate", "0");
                        if ($create == "on") {
                            $create = "1";
                        }

                        $modify = Control::PostRequest("f_ChannelModify", "0");
                        if ($modify == "on") {
                            $modify = "1";
                        }

                        $delete = Control::PostRequest("f_ChannelDelete", "0");
                        if ($delete == "on") {
                            $delete = "1";
                        }

                        $disabled = Control::PostRequest("f_ChannelDisabled", "0");
                        if ($disabled == "on") {
                            $disabled = "1";
                        }

                        $search = Control::PostRequest("f_ChannelSearch", "0");
                        if ($search == "on") {
                            $search = "1";
                        }

                        $rework = Control::PostRequest("f_ChannelRework", "0");
                        if ($rework == "on") {
                            $rework = "1";
                        }

                        $audit1 = Control::PostRequest("f_ChannelAudit1", "0");
                        if ($audit1 == "on") {
                            $audit1 = "1";
                        }

                        $audit2 = Control::PostRequest("f_ChannelAudit2", "0");
                        if ($audit2 == "on") {
                            $audit2 = "1";
                        }

                        $audit3 = Control::PostRequest("f_ChannelAudit3", "0");
                        if ($audit3 == "on") {
                            $audit3 = "1";
                        }

                        $audit4 = Control::PostRequest("f_ChannelAudit4", "0");
                        if ($audit4 == "on") {
                            $audit4 = "1";
                        }

                        $refused = Control::PostRequest("f_ChannelRefused", "0");
                        if ($refused == "on") {
                            $refused = "1";
                        }

                        $doOthers = Control::PostRequest("f_ChannelDoOthers", "0");
                        if ($doOthers == "on") {
                            $doOthers = "1";
                        }

                        $doOthersInSameGroup = Control::PostRequest("f_ChannelDoOthersInSameGroup", "0");
                        if ($doOthersInSameGroup == "on") {
                            $doOthersInSameGroup = "1";
                        }

                        $publish = Control::PostRequest("f_ChannelPublish", "0");
                        if ($publish == "on") {
                            $publish = "1";
                        }

                        $channelManageTemplate = Control::PostRequest("f_ChannelManageTemplate", "0");
                        if ($channelManageTemplate == "on") {
                            $channelManageTemplate = "1";
                        }

                        $userExplore = Control::PostRequest("f_UserExplore", "0");
                        if ($userExplore == "on") {
                            $userExplore = "1";
                        }

                        $userAdd = Control::PostRequest("f_UserAdd", "0");
                        if ($userAdd == "on") {
                            $userAdd = "1";
                        }

                        $userEdit = Control::PostRequest("f_UserEdit", "0");
                        if ($userEdit == "on") {
                            $userEdit = "1";
                        }

                        $userDelete = Control::PostRequest("f_UserDelete", "0");
                        if ($userDelete == "on") {
                            $userDelete = "1";
                        }

                        $userRoleExplore = Control::PostRequest("f_UserRoleExplore", "0");
                        if ($userRoleExplore == "on") {
                            $userRoleExplore = "1";
                        }

                        $userRoleAdd = Control::PostRequest("f_UserRoleAdd", "0");
                        if ($userRoleAdd == "on") {
                            $userRoleAdd = "1";
                        }

                        $userRoleEdit = Control::PostRequest("f_UserRoleEdit", "0");
                        if ($userRoleEdit == "on") {
                            $userRoleEdit = "1";
                        }

                        $userRoleDelete = Control::PostRequest("f_UserRoleDelete", "0");
                        if ($userRoleDelete == "on") {
                            $userRoleDelete = "1";
                        }

                        $userAlbumExplore = Control::PostRequest("f_UserAlbumExplore", "0");
                        if ($userAlbumExplore == "on") {
                            $userAlbumExplore = "1";
                        }

                        $userAlbumAdd = Control::PostRequest("f_UserAlbumAdd", "0");
                        if ($userAlbumAdd == "on") {
                            $userAlbumAdd = "1";
                        }

                        $userAlbumEdit = Control::PostRequest("f_UserAlbumEdit", "0");
                        if ($userAlbumEdit == "on") {
                            $userAlbumEdit = "1";
                        }

                        $userAlbumDelete = Control::PostRequest("f_UserAlbumDelete", "0");
                        if ($userAlbumDelete == "on") {
                            $userAlbumDelete = "1";
                        }

                        $userGroupExplore = Control::PostRequest("f_UserGroupExplore", "0");
                        if ($userGroupExplore == "on") {
                            $userGroupExplore = "1";
                        }

                        $userLevelExplore = Control::PostRequest("f_UserLevelExplore", "0");
                        if ($userLevelExplore == "on") {
                            $userLevelExplore = "1";
                        }

                        $userOrderExplore = Control::PostRequest("f_UserOrderExplore", "0");
                        if ($userOrderExplore == "on") {
                            $userOrderExplore = "1";
                        }

                        $manageSite = Control::PostRequest("auth_ManageSite", "0");
                        if ($manageSite == "on") {
                            $manageSite = "1";
                        }

                        $manageComment = Control::PostRequest("auth_ManageComment", "0");
                        if ($manageComment == "on") {
                            $manageComment = "1";
                        }

                        $manageTemplateLibrary = Control::PostRequest("auth_ManageTemplateLibrary", "0");
                        if ($manageTemplateLibrary == "on") {
                            $manageTemplateLibrary = "1";
                        }

                        $manageFilter = Control::PostRequest("auth_ManageFilter", "0");
                        if ($manageFilter == "on") {
                            $manageFilter = "1";
                        }

                        $manageFtp = Control::PostRequest("auth_ManageFtp", "0");
                        if ($manageFtp == "on") {
                            $manageFtp = "1";
                        }

                        $manageAd = Control::PostRequest("auth_ManageAd", "0");
                        if ($manageAd == "on") {
                            $manageAd = "1";
                        }

                        $manageDocumentTag = Control::PostRequest("auth_ManageDocumentTag", "0");
                        if ($manageDocumentTag == "on") {
                            $manageDocumentTag = "1";
                        }

                        $manageConfig = Control::PostRequest("auth_ManageConfig", "0");
                        if ($manageConfig == "on") {
                            $manageConfig = "1";
                        }


                        $manageUserTaskManageState = Control::PostRequest("f_ManageUserTaskManageState", "0");
                        if ($manageUserTaskManageState == "on") {
                            $manageUserTaskManageState = "1";
                        }

                        $manageUserTaskViewAll = Control::PostRequest("f_ManageUserTaskViewAll", "0");
                        if ($manageUserTaskViewAll == "on") {
                            $manageUserTaskViewAll = "1";
                        }

                        $manageUserTaskViewSameGroup = Control::PostRequest("f_ManageUserTaskViewSameGroup", "0");
                        if ($manageUserTaskViewSameGroup == "on") {
                            $manageUserTaskViewSameGroup = "1";
                        }

                        $manageUserExplore = Control::PostRequest("f_ManageUserExplore", "0");
                        if ($manageUserExplore == "on") {
                            $manageUserExplore = "1";
                        }

                        $manageUserCreate = Control::PostRequest("f_ManageUserCreate", "0");
                        if ($manageUserCreate == "on") {
                            $manageUserCreate = "1";
                        }

                        $manageUserModify = Control::PostRequest("f_ManageUserModify", "0");
                        if ($manageUserModify == "on") {
                            $manageUserModify = "1";
                        }

                        $manageUserGroupExplore = Control::PostRequest("f_ManageUserGroupExplore", "0");
                        if ($manageUserGroupExplore == "on") {
                            $manageUserGroupExplore = "1";
                        }


                        $manageUserGroupCreate = Control::PostRequest("f_ManageUserGroupCreate", "0");
                        if ($manageUserGroupCreate == "on") {
                            $manageUserGroupCreate = "1";
                        }

                        $manageUserGroupModify = Control::PostRequest("f_ManageUserGroupModify", "0");
                        if ($manageUserGroupModify == "on") {
                            $manageUserGroupModify = "1";
                        }

                        $manageUserAuthorityIdOfNew = $manageUserAuthorityManageData->CreateOrModifyForSiteAuthorityOfManageUser(
                            $siteId,
                            $manageUserIdForSet,
                            $explore,
                            $create,
                            $modify,
                            $delete,
                            $disabled,
                            $search,
                            $rework,
                            $audit1,
                            $audit2,
                            $audit3,
                            $audit4,
                            $refused,
                            $doOthers,
                            $doOthersInSameGroup,
                            $publish,
                            $channelManageTemplate,
                            $userExplore,
                            $userAdd,
                            $userEdit,
                            $userDelete,
                            $userRoleExplore,
                            $userRoleAdd,
                            $userRoleEdit,
                            $userRoleDelete,
                            $userAlbumExplore,
                            $userAlbumAdd,
                            $userAlbumEdit,
                            $userAlbumDelete,
                            $userGroupExplore,
                            $userLevelExplore,
                            $userOrderExplore,
                            $manageSite,
                            $manageComment,
                            $manageTemplateLibrary,
                            $manageFilter,
                            $manageFtp,
                            $manageAd,
                            $manageDocumentTag,
                            $manageConfig
                        );



                        //系统授权


                        $manageUserSystemAuthorityIdOfNew = $manageUserAuthorityManageData->CreateOrModifyForSystemAuthorityOfManageUser(
                            $manageUserIdForSet,
                            $manageUserTaskManageState,
                            $manageUserTaskViewAll,
                            $manageUserTaskViewSameGroup,
                            $manageUserExplore,
                            $manageUserCreate,
                            $manageUserModify,
                            $manageUserGroupExplore,
                            $manageUserGroupCreate,
                            $manageUserGroupModify
                        );

                        //频道授权

                        $strChannelsForSet="";
                        $arrValuesForSet= Array();
                        foreach ($_POST as $key => $value) {
                            if(strpos($key, "ChannelId_") === 0){
                                $strChannelsForSet.=",".$value;
                            }
                            if (strpos($key, "Channel_") === 0) { //
                                $keyName = substr($key, 8);
                                $setChannelId = substr($keyName, 0, strpos($keyName, "_"));
                                $setField = substr($keyName, strpos($keyName, "_") + 1);
                                $pushArr = array(
                                    "ChannelId" => $setChannelId,
                                    "Field" => $setField
                                );
                                array_push($arrValuesForSet,$pushArr);
                            }
                        }
                        $strChannelsForSet=substr($strChannelsForSet,1);
                        $arrChannelsForSet=explode(",",$strChannelsForSet);
                        $manageUserGroupIdForSet=0;
                        $manageUserAuthorityIdForChannel=$manageUserAuthorityManageData->ChannelAuthorityModify($siteId,$manageUserIdForSet,$manageUserGroupIdForSet,$arrChannelsForSet,$arrValuesForSet);


                        //加入操作日志
                        $operateContent = 'Create Manage User Site Authority,POST FORM:' . implode('|', $_POST) . ';\r\nResult:manageUserAuthorityId:' . $manageUserAuthorityIdOfNew;  //站点
                        self::CreateManageUserLog($operateContent);

                        $operateContent = 'Create Manage User System Authority,POST FORM:' . implode('|', $_POST) . ';\r\nResult:manageUserAuthorityId:' . $manageUserSystemAuthorityIdOfNew;  //站点
                        self::CreateManageUserLog($operateContent);

                        $operateContent = 'Create Manage User Channel Authority For Channel,POST FORM:' . implode('|', $_POST) . ';\r\nResult:manageUserAuthorityId:' . $manageUserAuthorityIdForChannel;  //频道
                        self::CreateManageUserLog($operateContent);

                        if ($manageUserAuthorityIdOfNew > 0) {

                            //删除缓冲
                            parent::DelAllCache();


                            $closeTab = Control::PostRequest("CloseTab", 0);
                            if ($closeTab == 1) {
                                //Control::CloseTab();
                                $resultJavaScript .= Control::GetCloseTab();
                            } else {
                                Control::GoUrl($_SERVER["PHP_SELF"] . "?" . $_SERVER['QUERY_STRING']);
                            }


                        } else {
                            $resultJavaScript .= Control::GetJqueryMessage(Language::Load('manage_user', 2)); //新增失败！
                        }
                    }else{
                        $resultJavaScript .= Control::GetJqueryMessage(Language::Load('manage_user', 11)); //授权站点信息不正确！
                    }
                }
                if($siteId<0){
                      //没选择站点
                }elseif($siteId==0){
                    if(count($arrayOfOneSystemAuthority)>0)
                        Template::ReplaceOne($tempContent, $arrayOfOneSystemAuthority);  //siteid=0 仅系统权限
                }else{
                  if  (count($arrayOfOneSiteAuthority)>0){     //siteid存在
                    if(count($arrayOfOneSystemAuthority)>0){  //将系统权限替换站点权限一起做标签替换
                        $arrayOfOneSiteAuthority["ManageUserTaskManageState"]=$arrayOfOneSystemAuthority["ManageUserTaskManageState"];
                        $arrayOfOneSiteAuthority["ManageUserTaskViewAll"]=$arrayOfOneSystemAuthority["ManageUserTaskViewAll"];
                        $arrayOfOneSiteAuthority["ManageUserTaskViewSameGroup"]=$arrayOfOneSystemAuthority["ManageUserTaskViewSameGroup"];
                        $arrayOfOneSiteAuthority["ManageUserExplore"]=$arrayOfOneSystemAuthority["ManageUserExplore"];
                        $arrayOfOneSiteAuthority["ManageUserCreate"]=$arrayOfOneSystemAuthority["ManageUserCreate"];
                        $arrayOfOneSiteAuthority["ManageUserModify"]=$arrayOfOneSystemAuthority["ManageUserModify"];
                        $arrayOfOneSiteAuthority["ManageUserGroupExplore"]=$arrayOfOneSystemAuthority["ManageUserGroupExplore"];
                        $arrayOfOneSiteAuthority["ManageUserGroupCreate"]=$arrayOfOneSystemAuthority["ManageUserGroupCreate"];
                        $arrayOfOneSiteAuthority["ManageUserGroupModify"]=$arrayOfOneSystemAuthority["ManageUserGroupModify"];
                    }
                    Template::ReplaceOne($tempContent, $arrayOfOneSiteAuthority);
                    }
                else{
                        $fields = $manageUserAuthorityManageData->GetFields();  //如果没有则新增
                        parent::ReplaceWhenCreate($tempContent, $fields);
                    }
                }


                $siteManageData=new SiteManageData();//授权的站点选择框
                $arrayOfSiteList=$siteManageData->GetListForSelect($manageUserId);
                $listName="site_list";
                Template::ReplaceList($tempContent,$arrayOfSiteList,$listName);
                $channelManageData=new ChannelManageData();
                $rootChannelId=$channelManageData->GetRootChannelId($siteId);


                $tempContent = str_ireplace("{RootChannelId}", $rootChannelId, $tempContent);
                $tempContent = str_ireplace("{ManageUserId}", $manageUserIdForSet, $tempContent);
                $tempContent = str_ireplace("{SiteId}", $siteId, $tempContent);

                $patterns = '/\{s_(.*?)\}/';
                $tempContent = preg_replace($patterns, "", $tempContent);

                parent::ReplaceEnd($tempContent);


                $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);

            }else{
                $tempContent = Language::Load("manage_user", 9); //授权对象信息不正确
            }
        } else {
            $tempContent = Language::Load("manage_user", 9);
        }
        return $tempContent;
    }


    /**
     * 用户组站点授权
     * @return string 模板内容页面
     */
    private function GenSetByManageUserGroup()
    {
        $siteId=Control::GetRequest("site_id",-1);
        $manageUserGroupIdForSet=Control::GetRequest("manage_user_group_id",0);
        $channelId=0; //未展开频道树，默认操作站点下所有频道权限

        $manageUserAuthorityManageData=new ManageUserAuthorityManageData();
        /**检查登陆管理员是否有该站点的权限**/
        $manageUserId = Control::GetManageUserId();
        $can_group=$manageUserAuthorityManageData->CanManageUserGroupModify($siteId,$channelId,$manageUserId);
        $can_site=$manageUserAuthorityManageData->CanManageSite($siteId,$channelId,$manageUserId);
        if(!$can_group||!$can_site){
            return Language::Load('document', 26);
        }
        /**                           **/

        $resultJavaScript = "";

        if ($manageUserId > 0) {
            if($manageUserGroupIdForSet > 0){
                $arrayOfOneSiteAuthority=$manageUserAuthorityManageData->GetOneByManageUserGroupId($siteId,$channelId,$manageUserGroupIdForSet); //站点=siteId的站点权限
                $arrayOfOneSystemAuthority=$manageUserAuthorityManageData->GetOneByManageUserGroupId(0,$channelId,$manageUserGroupIdForSet); //系统权限
                $tempContent = Template::Load("manage/manage_user_group_authority_set.html", "common");
                parent::ReplaceFirst($tempContent);

                if (!empty($_POST)) {
                    if($siteId>=0){
                        $explore = Control::PostRequest("f_ChannelExplore", "0");
                        if ($explore == "on") {
                            $explore = "1";
                        }

                        $create = Control::PostRequest("f_ChannelCreate", "0");
                        if ($create == "on") {
                            $create = "1";
                        }

                        $modify = Control::PostRequest("f_ChannelModify", "0");
                        if ($modify == "on") {
                            $modify = "1";
                        }

                        $delete = Control::PostRequest("f_ChannelDelete", "0");
                        if ($delete == "on") {
                            $delete = "1";
                        }

                        $disabled = Control::PostRequest("f_ChannelDisabled", "0");
                        if ($disabled == "on") {
                            $disabled = "1";
                        }

                        $search = Control::PostRequest("f_ChannelSearch", "0");
                        if ($search == "on") {
                            $search = "1";
                        }

                        $rework = Control::PostRequest("f_ChannelRework", "0");
                        if ($rework == "on") {
                            $rework = "1";
                        }

                        $audit1 = Control::PostRequest("f_ChannelAudit1", "0");
                        if ($audit1 == "on") {
                            $audit1 = "1";
                        }

                        $audit2 = Control::PostRequest("f_ChannelAudit2", "0");
                        if ($audit2 == "on") {
                            $audit2 = "1";
                        }

                        $audit3 = Control::PostRequest("f_ChannelAudit3", "0");
                        if ($audit3 == "on") {
                            $audit3 = "1";
                        }

                        $audit4 = Control::PostRequest("f_ChannelAudit4", "0");
                        if ($audit4 == "on") {
                            $audit4 = "1";
                        }

                        $refused = Control::PostRequest("f_ChannelRefused", "0");
                        if ($refused == "on") {
                            $refused = "1";
                        }

                        $doOthers = Control::PostRequest("f_ChannelDoOthers", "0");
                        if ($doOthers == "on") {
                            $doOthers = "1";
                        }

                        $doOthersInSameGroup = Control::PostRequest("f_ChannelDoOthersInSameGroup", "0");
                        if ($doOthersInSameGroup == "on") {
                            $doOthersInSameGroup = "1";
                        }

                        $publish = Control::PostRequest("f_ChannelPublish", "0");
                        if ($publish == "on") {
                            $publish = "1";
                        }

                        $channelManageTemplate = Control::PostRequest("f_ChannelManageTemplate", "0");
                        if ($channelManageTemplate == "on") {
                            $channelManageTemplate = "1";
                        }

                        $userExplore = Control::PostRequest("f_UserExplore", "0");
                        if ($userExplore == "on") {
                            $userExplore = "1";
                        }

                        $userAdd = Control::PostRequest("f_UserAdd", "0");
                        if ($userAdd == "on") {
                            $userAdd = "1";
                        }

                        $userEdit = Control::PostRequest("f_UserEdit", "0");
                        if ($userEdit == "on") {
                            $userEdit = "1";
                        }

                        $userDelete = Control::PostRequest("f_UserDelete", "0");
                        if ($userDelete == "on") {
                            $userDelete = "1";
                        }

                        $userRoleExplore = Control::PostRequest("f_UserRoleExplore", "0");
                        if ($userRoleExplore == "on") {
                            $userRoleExplore = "1";
                        }

                        $userRoleAdd = Control::PostRequest("f_UserRoleAdd", "0");
                        if ($userRoleAdd == "on") {
                            $userRoleAdd = "1";
                        }

                        $userRoleEdit = Control::PostRequest("f_UserRoleEdit", "0");
                        if ($userRoleEdit == "on") {
                            $userRoleEdit = "1";
                        }

                        $userRoleDelete = Control::PostRequest("f_UserRoleDelete", "0");
                        if ($userRoleDelete == "on") {
                            $userRoleDelete = "1";
                        }

                        $userAlbumExplore = Control::PostRequest("f_UserAlbumExplore", "0");
                        if ($userAlbumExplore == "on") {
                            $userAlbumExplore = "1";
                        }

                        $userAlbumAdd = Control::PostRequest("f_UserAlbumAdd", "0");
                        if ($userAlbumAdd == "on") {
                            $userAlbumAdd = "1";
                        }

                        $userAlbumEdit = Control::PostRequest("f_UserAlbumEdit", "0");
                        if ($userAlbumEdit == "on") {
                            $userAlbumEdit = "1";
                        }

                        $userAlbumDelete = Control::PostRequest("f_UserAlbumDelete", "0");
                        if ($userAlbumDelete == "on") {
                            $userAlbumDelete = "1";
                        }

                        $userGroupExplore = Control::PostRequest("f_UserGroupExplore", "0");
                        if ($userGroupExplore == "on") {
                            $userGroupExplore = "1";
                        }

                        $userLevelExplore = Control::PostRequest("f_UserLevelExplore", "0");
                        if ($userLevelExplore == "on") {
                            $userLevelExplore = "1";
                        }

                        $userOrderExplore = Control::PostRequest("f_UserOrderExplore", "0");
                        if ($userOrderExplore == "on") {
                            $userOrderExplore = "1";
                        }

                        $manageSite = Control::PostRequest("auth_ManageSite", "0");
                        if ($manageSite == "on") {
                            $manageSite = "1";
                        }

                        $manageComment = Control::PostRequest("auth_ManageComment", "0");
                        if ($manageComment == "on") {
                            $manageComment = "1";
                        }

                        $manageTemplateLibrary = Control::PostRequest("auth_ManageTemplateLibrary", "0");
                        if ($manageTemplateLibrary == "on") {
                            $manageTemplateLibrary = "1";
                        }

                        $manageFilter = Control::PostRequest("auth_ManageFilter", "0");
                        if ($manageFilter == "on") {
                            $manageFilter = "1";
                        }

                        $manageFtp = Control::PostRequest("auth_ManageFtp", "0");
                        if ($manageFtp == "on") {
                            $manageFtp = "1";
                        }

                        $manageAd = Control::PostRequest("auth_ManageAd", "0");
                        if ($manageAd == "on") {
                            $manageAd = "1";
                        }

                        $manageDocumentTag = Control::PostRequest("auth_ManageDocumentTag", "0");
                        if ($manageDocumentTag == "on") {
                            $manageDocumentTag = "1";
                        }

                        $manageConfig = Control::PostRequest("auth_ManageConfig", "0");
                        if ($manageConfig == "on") {
                            $manageConfig = "1";
                        }


                        $manageUserTaskManageState = Control::PostRequest("f_ManageUserTaskManageState", "0");
                        if ($manageUserTaskManageState == "on") {
                            $manageUserTaskManageState = "1";
                        }

                        $manageUserTaskViewAll = Control::PostRequest("f_ManageUserTaskViewAll", "0");
                        if ($manageUserTaskViewAll == "on") {
                            $manageUserTaskViewAll = "1";
                        }

                        $manageUserTaskViewSameGroup = Control::PostRequest("f_ManageUserTaskViewSameGroup", "0");
                        if ($manageUserTaskViewSameGroup == "on") {
                            $manageUserTaskViewSameGroup = "1";
                        }

                        $manageUserExplore = Control::PostRequest("f_ManageUserExplore", "0");
                        if ($manageUserExplore == "on") {
                            $manageUserExplore = "1";
                        }

                        $manageUserCreate = Control::PostRequest("f_ManageUserCreate", "0");
                        if ($manageUserCreate == "on") {
                            $manageUserCreate = "1";
                        }

                        $manageUserModify = Control::PostRequest("f_ManageUserModify", "0");
                        if ($manageUserModify == "on") {
                            $manageUserModify = "1";
                        }

                        $manageUserGroupExplore = Control::PostRequest("f_ManageUserGroupExplore", "0");
                        if ($manageUserGroupExplore == "on") {
                            $manageUserGroupExplore = "1";
                        }


                        $manageUserGroupCreate = Control::PostRequest("f_ManageUserGroupCreate", "0");
                        if ($manageUserGroupCreate == "on") {
                            $manageUserGroupCreate = "1";
                        }

                        $manageUserGroupModify = Control::PostRequest("f_ManageUserGroupModify", "0");
                        if ($manageUserGroupModify == "on") {
                            $manageUserGroupModify = "1";
                        }

                        //站点授权
                        $manageUserSiteAuthorityIdOfNew = $manageUserAuthorityManageData->CreateOrModifyForSiteAuthorityOfManageUserGroup(
                            $siteId,
                            $manageUserGroupIdForSet,
                            $explore,
                            $create,
                            $modify,
                            $delete,
                            $disabled,
                            $search,
                            $rework,
                            $audit1,
                            $audit2,
                            $audit3,
                            $audit4,
                            $refused,
                            $doOthers,
                            $doOthersInSameGroup,
                            $publish,
                            $channelManageTemplate,
                            $userExplore,
                            $userAdd,
                            $userEdit,
                            $userDelete,
                            $userRoleExplore,
                            $userRoleAdd,
                            $userRoleEdit,
                            $userRoleDelete,
                            $userAlbumExplore,
                            $userAlbumAdd,
                            $userAlbumEdit,
                            $userAlbumDelete,
                            $userGroupExplore,
                            $userLevelExplore,
                            $userOrderExplore,
                            $manageSite,
                            $manageComment,
                            $manageTemplateLibrary,
                            $manageFilter,
                            $manageFtp,
                            $manageAd,
                            $manageDocumentTag,
                            $manageConfig
                        );


                        //系统授权


                        $manageUserSystemAuthorityIdOfNew = $manageUserAuthorityManageData->CreateOrModifyForSystemAuthorityOfManageUserGroup(
                            $manageUserGroupIdForSet,
                            $manageUserTaskManageState,
                            $manageUserTaskViewAll,
                            $manageUserTaskViewSameGroup,
                            $manageUserExplore,
                            $manageUserCreate,
                            $manageUserModify,
                            $manageUserGroupExplore,
                            $manageUserGroupCreate,
                            $manageUserGroupModify
                        );

                        //频道授权

                        $strChannelsForSet="";
                        $arrValuesForSet= Array();
                        foreach ($_POST as $key => $value) {
                            if(strpos($key, "ChannelId_") === 0){
                                $strChannelsForSet.=",".$value;
                            }
                            if (strpos($key, "Channel_") === 0) { //
                                $keyName = substr($key, 8);
                                $setChannelId = substr($keyName, 0, strpos($keyName, "_"));
                                $setField = substr($keyName, strpos($keyName, "_") + 1);
                                $pushArr = array(
                                    "ChannelId" => $setChannelId,
                                    "Field" => $setField
                                );
                                array_push($arrValuesForSet,$pushArr);
                            }
                        }
                        $strChannelsForSet=substr($strChannelsForSet,1);
                        $arrChannelsForSet=explode(",",$strChannelsForSet);
                        $manageUserIdForSet=0;
                        $manageUserAuthorityIdForChannel=$manageUserAuthorityManageData->ChannelAuthorityModify($siteId,$manageUserIdForSet,$manageUserGroupIdForSet,$arrChannelsForSet,$arrValuesForSet);


                        //加入操作日志
                        $operateContent = 'Create Manage User Group Site Authority,POST FORM:' . implode('|', $_POST) . ';\r\nResult:manageUserAuthorityId:' . $manageUserSiteAuthorityIdOfNew;//站点
                        self::CreateManageUserLog($operateContent);

                        $operateContent = 'Create Manage User Group System Authority,POST FORM:' . implode('|', $_POST) . ';\r\nResult:manageUserAuthorityId:' . $manageUserSystemAuthorityIdOfNew;//系统
                        self::CreateManageUserLog($operateContent);

                        $operateContent = 'Create Manage User Group Channel Authority,POST FORM:' . implode('|', $_POST) . ';\r\nResult:manageUserAuthorityId:' . $manageUserAuthorityIdForChannel;//频道
                        self::CreateManageUserLog($operateContent);

                        if ($manageUserSiteAuthorityIdOfNew > 0) {

                            //删除缓冲
                            parent::DelAllCache();


                            $closeTab = Control::PostRequest("CloseTab", 0);
                            if ($closeTab == 1) {
                                //Control::CloseTab();
                                $resultJavaScript .= Control::GetCloseTab();
                            } else {
                                Control::GoUrl($_SERVER["PHP_SELF"] . "?" . $_SERVER['QUERY_STRING']);
                            }


                        } else {
                            $resultJavaScript .= Control::GetJqueryMessage(Language::Load('manage_user', 2)); //新增失败！
                        }
                    }else{
                        $resultJavaScript .= Control::GetJqueryMessage(Language::Load('manage_user_group', 11)); //授权站点信息不正确！
                    }
                }
                if($siteId<0){
                      //没选择站点
                }elseif($siteId==0){
                    if(count($arrayOfOneSystemAuthority)>0)
                        Template::ReplaceOne($tempContent, $arrayOfOneSystemAuthority);  //siteid=0 仅系统权限
                }else{
                    if  (count($arrayOfOneSiteAuthority)>0){     //siteid存在
                        if(count($arrayOfOneSystemAuthority)>0){  //将系统权限替换站点权限一起做标签替换
                            $arrayOfOneSiteAuthority["ManageUserTaskManageState"]=$arrayOfOneSystemAuthority["ManageUserTaskManageState"];
                            $arrayOfOneSiteAuthority["ManageUserTaskViewAll"]=$arrayOfOneSystemAuthority["ManageUserTaskViewAll"];
                            $arrayOfOneSiteAuthority["ManageUserTaskViewSameGroup"]=$arrayOfOneSystemAuthority["ManageUserTaskViewSameGroup"];
                            $arrayOfOneSiteAuthority["ManageUserExplore"]=$arrayOfOneSystemAuthority["ManageUserExplore"];
                            $arrayOfOneSiteAuthority["ManageUserCreate"]=$arrayOfOneSystemAuthority["ManageUserCreate"];
                            $arrayOfOneSiteAuthority["ManageUserModify"]=$arrayOfOneSystemAuthority["ManageUserModify"];
                            $arrayOfOneSiteAuthority["ManageUserGroupExplore"]=$arrayOfOneSystemAuthority["ManageUserGroupExplore"];
                            $arrayOfOneSiteAuthority["ManageUserGroupCreate"]=$arrayOfOneSystemAuthority["ManageUserGroupCreate"];
                            $arrayOfOneSiteAuthority["ManageUserGroupModify"]=$arrayOfOneSystemAuthority["ManageUserGroupModify"];
                        }
                        Template::ReplaceOne($tempContent, $arrayOfOneSiteAuthority);
                    }
                    else{
                        $fields = $manageUserAuthorityManageData->GetFields();  //如果没有则新增
                        parent::ReplaceWhenCreate($tempContent, $fields);
                    }
                }


                $siteManageData=new SiteManageData();//授权的站点选择框
                $arrayOfSiteList=$siteManageData->GetListForSelect($manageUserId);
                $listName="site_list";
                Template::ReplaceList($tempContent,$arrayOfSiteList,$listName);


                $channelManageData=new ChannelManageData();
                $rootChannelId=$channelManageData->GetRootChannelId($siteId);


                $tempContent = str_ireplace("{RootChannelId}", $rootChannelId, $tempContent);
                //$manageUserGroupManageData=new ManageUserGroupData();
                //$manageUserGroupNameForSet=$manageUserGroupManageData->GetName();
                $tempContent = str_ireplace("{ManageUserGroupId}", $manageUserGroupIdForSet, $tempContent);
                //$tempContent = str_ireplace("{ManageUserGroupName}", $manageUserGroupNameForSet, $tempContent);
                $tempContent = str_ireplace("{SiteId}", $siteId, $tempContent);

                $patterns = '/\{s_(.*?)\}/';
                $tempContent = preg_replace($patterns, "", $tempContent);

                parent::ReplaceEnd($tempContent);


                $tempContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $tempContent);

            }else{
                $tempContent = Language::Load("manage_user", 9); //授权对象信息不正确
            }
        } else {
            $tempContent = Language::Load("manage_user", 9);
        }
        return $tempContent;
    }


    /**
     * 取得展开的子频道授权表格
     * @return string 模板内容页面
     */
    private function GenGetChildChannelList(){
        $result="";
        $type=Control::GetRequest("type","");
        $manageUserId=Control::PostRequest("ManageUserId",-1);
        $manageUserGroupId=Control::PostRequest("ManageUserGroupId",-1);
        $parentId=Control::PostRequest("ParentId",-1);
        $order=Control::PostRequest("order","channel_id");
        $limit=Control::PostRequest("limit",20);
        if($parentId>0){
            $channelManageData=new ChannelManageData();

            switch ($type){
                case "manage_user":
                    $arrayOfChildChannelList=$channelManageData->GetListByParentIdForManageUserAuthority($parentId,$manageUserId,$limit,$order);
                    break;
                case "manage_user_group":
                    $arrayOfChildChannelList=$channelManageData->GetListByParentIdForManageUserGroupAuthority($parentId,$manageUserGroupId,$limit,$order);
                    break;
                default:
                    $arrayOfChildChannelList=null;
                    $result=$result=Control::GetJqueryMessage(Language::Load('manage_user', 12)); //业务类型不明确！！;
                    break;
            }
            if(count($arrayOfChildChannelList)>0){
                $tempContent = Template::Load("manage/manage_user_authority_channel_list.html", "common");


                $tempContent = str_ireplace("{ParentId}", $parentId, $tempContent);

                $listName="children_channel_list";
                Template::ReplaceList($tempContent,$arrayOfChildChannelList,$listName);
                $result=$tempContent;
            }else{
                $result.=Control::GetJqueryMessage(Language::Load('manage_user', 8)); //未查到任何相关数据！！;
            }
        }else{
            $result=Control::GetJqueryMessage(Language::Load('manage_user_group', 11)); //授权站点信息不正确！;
        }
        $result = Format::FixJsonEncode($result);
        echo $result;
    }

}