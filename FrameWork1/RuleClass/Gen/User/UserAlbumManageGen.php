<?php

/**
 * 后台管理 会员相册 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_User
 * Time: 下午12:09
 */
class UserAlbumManageGen extends BaseManageGen implements IBaseManageGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function Gen() {

        $result = "";
        $method = Control::GetRequest("m", "");
        switch ($method) {
            case "modify":
                $result = self::GenModify();
                break;
            case "list":
                $result = self::GenList();
                break;
            case "remove_bin":
                $result = self::GenRemoveBin();
                break;
            case "create_main_pic":
                $result = self::GenCreateMainPic();
                break;
            case "async_modify_state":
                $result = self::AsyncModifyState();
                break;
        }
        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    private function GenModify() {
        $tempContent = Template::Load("user/user_album_pic_list.html");

        $userAlbumId = Control::GetRequest("user_album_id",0);
        $siteId = Control::GetRequest("site_id", 0);

        $pageIndex = Control::GetRequest("p1", 1);
        $listPageIndex = Control::GetRequest("p", 1);
        $pageSize = 15;
        $pageBegin = ($pageIndex - 1) * $pageSize;
        $allCount = 0;
        $replaceArr = array(
            "{user_album_id}" => $userAlbumId,
            "{page_index}" => $listPageIndex
        );
        $tempContent = strtr($tempContent, $replaceArr);
        $userAlbumPicManageData = new UserAlbumPicManageData();
        $arrUserAlbumPicList = $userAlbumPicManageData->GetListOfOneUserAlbum($userAlbumId);

        for ($i = 0; $i < count($arrUserAlbumPicList); $i++) {
            $compressUrl = $arrUserAlbumPicList[$i]["UserAlbumPicCompressUrl"];
            if ($compressUrl == 0 || strlen($compressUrl)) {
                $arrUserAlbumPicList[$i]["UserAlbumPicCompressUrl"] = $arrUserAlbumPicList[$i]["UserAlbumPicThumbnailUrl"];
            }
        }
        $listName = "user_album_pic_list";
        Template::ReplaceList($tempContent, $arrUserAlbumPicList, $listName);
        $userAlbumTagList = "user_album_tag_list";

        $userAlbumTypeManageData = new UserAlbumTypeManageData();
        $arrUserAlbumTypeList = $userAlbumTypeManageData->GetList($siteId);
        Template::ReplaceList($tempContent, $arrUserAlbumTypeList, $userAlbumTagList);
        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }

    private function GenList() {
        $tempContent = Template::Load("user/user_album_list.html","common");
        $pageIndex = Control::GetRequest("p",1);
        $siteId = Control::GetRequest("site_id",0);
        $state = Control::GetRequest("state",0);
        $searchKey = Control::GetRequest("search_key","");
        $searchType = Control::GetRequest("search_type",0);
        $userGroupId = Control::GetRequest("user_group_id", 2);
        $pageSize = 12;
        $pageBegin = ($pageIndex - 1)*$pageSize;
        $allCount = 0;

        $siteId = 2;
        if ($pageIndex > 0 && $siteId > 0) {
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $tagId = "user_album_list";
            $allCount = 0;
            $userAlbumManageData = new UserAlbumManageData();
            $arrUserAlbumList = $userAlbumManageData->GetList($siteId, $pageBegin, $pageSize, $allCount,$state,$searchKey,$searchType,$userGroupId);
            if (count($arrUserAlbumList) > 0) {
                Template::ReplaceList($tempContent, $arrUserAlbumList, $tagId);
                $styleNumber = 1;
                $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html", "common");
                $isJs = FALSE;
                $navUrl = "default.php?secu=manage&mod=user_album&m=list&site_id=$siteId&p={0}&ps=$pageSize&user_group_id=$userGroupId";
                $jsFunctionName = "";
                $jsParamList = "";
                $pagerButton = Pager::ShowPageButton($pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex, $styleNumber, $isJs, $jsFunctionName, $jsParamList);
                $tempContent = str_ireplace("{pager_button}", $pagerButton, $tempContent);
            } else {
                Template::RemoveCustomTag($tempContent, $tagId);
                $tempContent = str_ireplace("{pager_button}", Language::Load("document", 7), $tempContent);
            }
        }



        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }

    /**
     * 修改文档状态 状态值定义在Data类中
     * @return string 返回Jsonp修改结果
     */
    private function AsyncModifyState()
    {
        $result = -1;
        $userAlbumId = Control::GetRequest("user_album_id", 0);
        $state = Control::GetRequest("state", -1);
        $siteId = Control::GetRequest("site_id", 0);
        $manageUserId = Control::GetManageUserId();


        if ($userAlbumId > 0 && $state >= 0 && $manageUserId > 0) {
            //判断权限
            /**********************************************************************
             ******************************判断是否有操作权限**********************
             **********************************************************************/
            $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
            $channelId = 0;
            $can = $manageUserAuthorityManageData->CanManageSite($siteId, $channelId, $manageUserId);
            if (!$can) {
                $result = -10;
            } else {
                $userAlbumManageData = new UserAlbumManageData();
                $result = $userAlbumManageData->ModifyState($userAlbumId, $state);
                //删除缓冲
                parent::DelAllCache();
                //加入操作日志
                $operateContent = 'Modify State Album,GET PARAM:' . implode('|', $_GET) . ';\r\nResult:' . $result;
                self::CreateManageUserLog($operateContent);
            }
        }else{
            $result = -5;
        }
        return Control::GetRequest("jsonpcallback", "") . '({"result":' . $result . '})';
    }

    private function GenRemoveBin() {
        $userAlbumId = Control::GetRequest("user_album_id",0);
        $siteId = Control::GetRequest("sid",0);
        if($userAlbumId > 0 && $siteId > 0){
            $userAlbumManageData = new UserAlbumManageData();
            $result = $userAlbumManageData->DeleteToRecycleBin($userAlbumId);
            $userId = $userAlbumManageData->GetUserID($userAlbumId);
            $userInfoManageData = new UserInfoManageData();
            $userInfoManageData->MinusUserAlbumCount($userId);
            $userLevelManageData = new UserLevelManageData();
            $userLevelManageData->Update($userId, $siteId);
            return $_GET['jsonpcallback'] . '({"result":' . $result . '})';
        }
    }

    public function GenBatchDel(){
        $arrUserAlbumId = Control::PostRequest("del_id", array());
        $userAlbumManageData = new UserAlbumManageData();
        if (count($arrUserAlbumId) > 0) {
            foreach ($arrUserAlbumId as $userAlbumId) {
                $userAlbumManageData->DeleteToRecycleBin($userAlbumId);
            }
        }
        $userInfoManageData = new UserInfoManageData();
        $userInfoManageData->ReCountUserAlbumCount();
        return Control::GetRequest("jsonpcallback","") . "()";
    }

    /**
     * 批量审核(Ajax函数) 可以改进(在批量审核中可以将没有成功的相册ID返回前台)
     * @return string JSONP格式数据
     */
    public function GenBatchPass()
    {
        $arrUserAlbumId = Control::PostRequest("pass_id", array());
        $userAlbumManageData = new UserAlbumManageData();
        if (count($arrUserAlbumId) > 0) {
            foreach ($arrUserAlbumId as $userAlbumId) {
                $userAlbumManageData->ModifyState($userAlbumId, 20);
            }
        }
        $userInfoManageData = new UserInfoManageData();
        $userInfoManageData->ReCountUserAlbumCount();
        return Control::GetRequest("jsonpcallback","") . "()";
    }

    private function GenCreateMainPic() {
        $tempContent = Template::Load("user/user_album_create_main_pic.html");
        $userAlbumId = Control::GetRequest("aid", 0);
        $userAlbumPicData = new UserAlbumPicManageData();
        $arrAlbumPicList = $userAlbumPicData->GetListOfOneUserAlbum($userAlbumId);
        $listName = "user_album_pic_list";
        Template::ReplaceList($tempContent, $arrAlbumPicList, $listName);
        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }
}

