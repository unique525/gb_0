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
            case "foreign_list":
                $result = self::GenForeignList();
                break;
            case "create_main_pic":
                $result = self::GenCreateMainPic();
                break;
            case "export":
                $result = self::GenExport();
                break;
            case "statistics":
                $result = self::GenStatistics();
                break;
//            可能需要改模板路径  比如这里要加个 这样更加符合框架
//            case "useralbumpic":
//                break;
        }
        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    private function GenModify() {
        $userAlbumId = Control::GetRequest("useralbumid",0);

    }

    private function GenListForManage() {
        $tempContent = Template::Load("");
        $pageIndex = Control::GetRequest("p",1);
        $siteId = Control::GetRequest("siteid",0);
        $type = Control::GetRequest("type","");
        $state = Control::GetRequest("state",0);
        $pageSize = 15;
        $pageBegin = ($pageIndex - 1)*$pageSize;
        $allCount = 0;

        $userAlbumManageData = new UserAlbumManageData();
        if($siteId > 0 && strlen($tempContent) > 0){
            if($type == "search"){
                if(!empty($_POST)){
                    $userName = $_POST["username"];
                    $userAlbumName = $_POST["useralbumname"];
                    $indexTop = $_POST["indextop"];
                    $isBest = $_POST["isbest"];
                    $equipment = $_POST["equipment"];
                    $userAlbumType = 0;
                    $beginDate = $_POST["begindate"];
                    $endDate = $_POST["enddate"];
                    $recLevel = $_POST["reclevel"];
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

            $pagerTemplate = Template::Load("pager_js.html");
            $isJs = true;
            $jsFunctionName = "loaduseralbumlist";
            $pagerButton = Pager::ShowPageButton($pagerTemplate, "", $allCount, $pageSize, $pageIndex, $isJs, $jsFunctionName, $jsParamList);
            $replaceArr = array(
                "{pagerbutton}" => $pagerButton
            );
            $tempContent = strtr($tempContent, $replaceArr);

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
        $userAlbumId = Control::GetRequest("useralbumid",0);
        $siteId = Control::GetRequest("sid",0);
        if($userAlbumId > 0 && $siteId > 0){
            $userAlbumManageData = new UserAlbumManageData();
            $result = $userAlbumManageData->DeleteToRecycleBin($userAlbumId);
            $userId = $userAlbumManageData->GetUserID($userAlbumId);
            $userInfoData = new UserInfoData();
            $userInfoData->MinusUserAlbumCount($userId);
            $userLevelData = new UserLevelData();
            $userLevelData->Update($userId, $siteId);
            return $_GET['jsonpcallback'] . '({"result":' . $result . '})';
        }
    }

    public function GenBatchDel(){
        $delId = Control::PostRequest("delid", array());
        $userAlbumManageData = new UserAlbumManageData();
        if (count($delId) > 0) {
            foreach ($delId as $value) {
                $userAlbumManageData->DeleteToRecycleBin($value);
            }
        }
        $userInfoManageData = new UserInfoManageData();
        $userInfoManageData->ReCountUserAlbumCount();
        return $_GET["jsonpcallback"] . "()";
    }

    /**
     * 批量审核(Ajax函数) 可以改进(在批量审核中可以将没有成功的相册ID返回前台)
     * @return jsonpcallback
     */
    public function GenBatchPass()
    {
        $passId = Control::PostRequest("passid", array());
        $userAlbumManageData = new UserAlbumManageData();
        if (count($passId) > 0) {
            foreach ($passId as $value) {
                $userAlbumManageData->ModiftState($value, 20);
            }
        }
        $userInfoManageData = new UserInfoManageData();
        $userInfoManageData->ReCountUserAlbumCount();
        return $_GET['jsonpcallback'] . "()";
    }

    private function GenForeignList() {
    }

    private function GenCreateMainPic() {
    }

    private function GenExport() {
        
    }

    private function GenStatistics() {
        
    }

}

