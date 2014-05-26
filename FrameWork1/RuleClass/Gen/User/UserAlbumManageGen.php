<?php

/**
 * Created by PhpStorm.
 * User: zcmzc
 * Date: 14-1-21
 * Time: 下午12:12
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
            case "create":
                $result = self::GenCreate();
                break;
            case "modify":
                $result = self::GenModify();
                break;
            case "list_for_manage":
                $result = self::GenListForManage();
                break;
            case "remove_bin":
                $result = self::GenRemoveBin();
                break;
            case "create_main_pic":
                $result = self::GenCreateMainPic();
                break;
//            可能需要改模板路径  比如这里要加个 这样更加符合框架
//            case "useralbumpic":
//                break;
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

    private function GenListForManage() {
        $tempContent = Template::Load("user/user_album_list.html","common");
        $pageIndex = Control::GetRequest("p",1);
        $siteId = Control::GetRequest("site_id",0);
        $type = Control::GetRequest("type","");//列表的类型，（全部/搜索）
        $state = Control::GetRequest("state",0);
        $pageSize = 15;
        $pageBegin = ($pageIndex - 1)*$pageSize;
        $allCount = 0;

        $userAlbumManageData = new UserAlbumManageData();
        if($siteId > 0 && strlen($tempContent) > 0){
            if($type == "search"){
                if(!empty($_POST)){
                    $userName = $_POST["username"];
                    $userAlbumName = $_POST["user_album_name"];
                    $indexTop = $_POST["index_top"];
                    $isBest = $_POST["is_best"];
                    $equipment = $_POST["equipment"];
                    $userAlbumType = 0;
                    $beginDate = $_POST["begin_date"];
                    $endDate = $_POST["end_date"];
                    $recLevel = $_POST["rec_level"];
                    $country = $_POST["country"];
                    $result = $userAlbumManageData->GetListForSearch($pageBegin,$pageSize,$allCount,$siteId,$userName,
                    $userAlbumName,$indexTop,$isBest,$equipment,$userAlbumType,$beginDate,$endDate,$recLevel,$state,$country);
                    $param = "{";
                    foreach($_POST as $key => $value){
                        if ($param == "{") {
                            if (is_array($value)) {
                                $arrValue = "";
                                for ($i = 0; $i < count($value); $i++) {
                                    if ($i < count($value) - 1) {
                                        $arrValue = $arrValue . "'" . $value[$i] . "',";
                                    } else {
                                        $arrValue = $arrValue . "'" . $value[$i] . "'";
                                    }
                                }
                                $arrValue = "Array(" . $arrValue . ")";
                                $param = $param . $key . ":" . $arrValue;
                            } else {
                                $param = $param . $key . ":'" . $value . "'";
                            }
                        } else {
                            if (is_array($value)) {
                                $arrValue = "";
                                for ($i = 0; $i < count($value); $i++) {
                                    if ($i < count($value) - 1) {
                                        $arrValue = $arrValue . "'" . $value[$i] . "',";
                                    } else {
                                        $arrValue = $arrValue . "'" . $value[$i] . "'";
                                    }
                                }
                                $arrValue = "Array(" . $arrValue . ")";
                                $param = $param . "," . $key . ":" . $arrValue . "";
                            } else {
                                $param = $param . "," . $key . ":'" . $value . "'";
                            }
                        }
                    }
                    $param = $param . "}";
                    $jsParamList = ",'../user/index.php?a=album&m=list&type=search'," . $param . " ,'" . $state . "'";
                }else{
                    $jsParamList = "";
                    $result = "";
                }
            }else{
                $result = $userAlbumManageData->GetList($pageBegin, $pageSize, $allCount, $state, $siteId);
                $jsParamList = ",'',undefined,'" . $state . "'";
            }

            for ($i = 0; $i < count($result); $i++) {
                if ($result[$i]["NickName"] == "" || strlen($result[$i]["NickName"]) == 0) {
                    $result[$i]["NickName"] = $result[$i]["RealName"];
                }
            }
        }
        parent::ReplaceEnd($tempContent);
        return $tempContent;
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
        return $_GET["jsonpcallback"] . "()";
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
        return $_GET['jsonpcallback'] . "()";
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

