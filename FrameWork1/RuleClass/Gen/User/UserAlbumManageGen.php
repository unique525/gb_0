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
        $userAlbumId = Control::GetRequest("user_album_id",0);

    }

    private function GenListForManage() {
        $tempContent = Template::Load("");
        $pageIndex = Control::GetRequest("p",1);
        $siteId = Control::GetRequest("site_id",0);
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

            $pagerTemplate = Template::Load("pager_js.html");
            $isJs = true;
            $jsFunctionName = "load_user_album_list";
            $pagerButton = Pager::ShowPageButton($pagerTemplate, "", $allCount, $pageSize, $pageIndex, $isJs, $jsFunctionName, $jsParamList);
            $replaceArr = array(
                "{pager_button}" => $pagerButton
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
    }

    private function GenExport() {
        $url = $_SERVER["PHP_SELF"] . '?a=album&m=export';
        $userManageData = new UserManageData();
        $userInfoManageData = new UserInfoManageData();
        $userAlbumManageData = new UserAlbumManageData();
        $userAlbumPicManageData = new UserAlbumPicManageData();
        $limit = 3;
        $siteId = 1;
        $arrUserList = $userManageData->GetListForExport($limit);
        if (count($arrUserList) > 0) {
            for ($i = 0; $i < count($arrUserList); $i++) {
                $userId = intval($arrUserList[$i]["UserID"]);
                $parentId = intval($arrUserList[$i]["ParentID"]);

                /*                 * **author** */
                $arrUserInfoOne = $userInfoManageData->GetOne($userId, $siteId);


                if (count($arrUserInfoOne) > 0) {
                    $userInfo = '姓名：' . $arrUserInfoOne["RealName"] . "\r\n";
                    $parentName = $userInfoManageData->GetRealName($parentId);
                    $userInfo .= '推荐人：' . $parentName . "\r\n";
                    $userInfo .= '电子邮箱：' . $arrUserInfoOne["Email"] . "\r\n";
                    $userInfo .= 'QQ/MSN：' . $arrUserInfoOne["QQ"] . "\r\n";
                    $userInfo .= '生日：' . $arrUserInfoOne["Birthday"] . "\r\n";
                    $userInfo .= '地址：' . $arrUserInfoOne["Address"] . "\r\n";
                    $userInfo .= '邮编：' . $arrUserInfoOne["PostCode"] . "\r\n";
                    $userInfo .= '电话：' . $arrUserInfoOne["Tel"] . "\r\n";
                    $userInfo .= '手机：' . $arrUserInfoOne["Mobile"] . "\r\n";
                    $userInfo .= '国家：' . $arrUserInfoOne["Country"] . "\r\n";

                    $country = $arrUserInfoOne["Country"];
                    if ($country == '') {
                        $country = 'China';
                    }
                    /*
                      if ($country == 'China' || $country == '') {
                      $country = '境内';
                      } else {
                      $country = '境外';
                      }
                     */
                    $userName = str_ireplace("\\", "", $arrUserInfoOne["RealName"]);
                    $exportUserInfoPath = P_PATH . DIRECTORY_SEPARATOR . "export" . DIRECTORY_SEPARATOR . $country . DIRECTORY_SEPARATOR . $userName . '_' . $userId . DIRECTORY_SEPARATOR . "作者信息.txt";

                    File::Write($exportUserInfoPath, $userInfo);
                    echo "$userId user info ok<br />";
                }


                /*                 * ****album****** */
                $arrUserAlbumList = $userAlbumManageData->GetListByUserId($userId);

                for ($j = 0; $j < count($arrUserAlbumList); $j++) {

                    //album info
                    $userAlbumId = intval($arrUserAlbumList[$j]["UserAlbumID"]);
                    $userAlbumTag = $arrUserAlbumList[$j]["UserAlbumTag"];
                    $userAlbumName = $arrUserAlbumList[$j]["UserAlbumName"];
                    $userAlbumIntro = $arrUserAlbumList[$j]["UserAlbumIntro"];
                    $exportUserAlbumPath =
                        P_PATH . DIRECTORY_SEPARATOR . "export" .
                        DIRECTORY_SEPARATOR . $country .
                        DIRECTORY_SEPARATOR . $userName . '_' . $userId .
                        DIRECTORY_SEPARATOR . $userAlbumTag .
                        DIRECTORY_SEPARATOR . $userAlbumId .
                        DIRECTORY_SEPARATOR;

                    $exportUserAlbumInfoPath = $exportUserAlbumPath . DIRECTORY_SEPARATOR . '作品信息.txt';


                    $userAlbumInfo = "作品名称:" . $userAlbumName . "\r\n";
                    $userAlbumInfo .= "作品分类:" . $userAlbumTag . "\r\n";
                    $userAlbumInfo .= "作品介绍:" . $userAlbumIntro . "\r\n";
                    File::Write($exportUserAlbumInfoPath, $userAlbumInfo);

                    $arrUserAlbumPicList = $userAlbumPicManageData->GetUserAlbumPicUrl($userAlbumId);
                    for ($k = 0; $k < count($arrUserAlbumPicList); $k++) {
                        $oldFileName = basename($arrUserAlbumPicList[$k]["useralbumpicurl"]);
                        $sourcePath = P_PATH . $arrUserAlbumPicList[$k]["useralbumpicurl"];
                        $sourcePath = str_ireplace("/", DIRECTORY_SEPARATOR, $sourcePath);

                        $exportUserAlbumPath =
                            P_PATH . DIRECTORY_SEPARATOR . "export" .
                            DIRECTORY_SEPARATOR . $country .
                            DIRECTORY_SEPARATOR . $userName . '_' . $userId .
                            DIRECTORY_SEPARATOR . $userAlbumTag .
                            DIRECTORY_SEPARATOR . $userAlbumId .
                            DIRECTORY_SEPARATOR . $oldFileName;

                        File::Copy($sourcePath, $exportUserAlbumPath);
                    }
                }

                $userManageData->ModifyIsReCount($userId, 1);
                //sleep(1);
                header('refresh:0 ' . $url);
            }
        } else {
            echo "任务已完成";
        }
    }

    private function GenStatistics() {
        ini_set("max_execution_time", "18000000");
        $type = Control::GetRequest("type", 0);
        $userInfoManageData = new UserInfoManageData();
        $userAlbumManageData = new UserAlbumManageData();
        $userAlbumPicManageData = new UserAlbumPicManageData();
        if ($type == 0) {
            $userCount = $userInfoManageData->GetCountUserByCountry();
            $userAlbumCount = $userAlbumManageData->GetCountAlbumForStatistics();
            $userAlbumPicCount = $userAlbumPicManageData->GetCountPicForStatistics();
            $domesticUserCount = $userInfoManageData->GetCountUserByCountry('china');
            $foreignUserCount = $userInfoManageData->GetCountUserByCountry('!china');
            $domesticUserAlbumSingleCount = $userAlbumManageData->GetCountAlbumForStatistics('china');
            $foreignUserAlbumSingleCount = $userAlbumManageData->GetCountAlbumForStatistics('!china');
            $domesticUserAlbumMultiCount = $userAlbumManageData->GetCountAlbumForStatistics('china', '', 1);
            $foreignUserAlbumMultiCount = $userAlbumManageData->GetCountAlbumForStatistics('!china', '', 1);
            $domesticUserAlbumPicCount = $userAlbumPicManageData->GetCountPicForStatistics('china');
            $foreignUserAlbumPicCount = $userAlbumPicManageData->GetCountPicForStatistics('!china');
            echo "数据统计";
            echo "==============================================================<br/>";
            echo "==============================================================<br/>";
            echo "总作者数：" . $userCount . "<br/>";
            echo "总作品数：" . $userAlbumCount . "<br/>";
            echo "总图片数：" . $userAlbumPicCount . "<br/>";
            echo "国内作者数：" . $domesticUserCount . "<br/>";
            echo "国内作品数_单照：" . $domesticUserAlbumSingleCount . "<br/>";
            echo "国内作品数_组照：" . $domesticUserAlbumMultiCount . "<br/>";
            echo "国内图片数：" . $domesticUserAlbumPicCount . "<br/>";
            echo "国外作者数：" . $foreignUserCount . "<br/>";
            echo "国外作品数_单照：" . $foreignUserAlbumSingleCount . "<br/>";
            echo "国外作品数_组照：" . $foreignUserAlbumMultiCount . "<br/>";
            echo "国外图片数：" . $foreignUserAlbumPicCount . "<br/>";
            echo "==============================================================" . "<br/>";
            echo "==============================================================" . "<br/>";
            $domesticUserAlbumUserAlbumTagSingleCount = 0;
            $foreignUserAlbumUserAlbumTagSingleCount = 0;
            $domesticUserAlbumUserAlbumTagMultiCount = 0;
            $foreignUserAlbumUserAlbumTagMultiCount = 0;
            $userAlbumTagList = $userAlbumManageData->GetAllUserAlbumTag(1);
            for ($i = 0; $i < count($userAlbumTagList); $i++) {
                $userAlbumTag = $userAlbumTagList[$i]['useralbumtag'];
                if (strlen($userAlbumTag) > 2) {
                    $domesticUserAlbumUserAlbumTagSingleCount = $userAlbumManageData->GetCountAlbumForStatistics('china', $userAlbumTag, 0);
                    echo "国内-<lable style='color:red'>" . $userAlbumTag . "</lable>-单照：" . $domesticUserAlbumUserAlbumTagSingleCount . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                    $foreignUserAlbumUserAlbumTagSingleCount = $userAlbumManageData->GetCountAlbumForStatistics('!china', $userAlbumTag, 0);
                    echo "国外-<lable style='color:red'>" . $userAlbumTag . "</lable>-单照：" . $foreignUserAlbumUserAlbumTagSingleCount . "<br/>";
                    $domesticUserAlbumUserAlbumTagMultiCount = $userAlbumManageData->GetCountAlbumForStatistics('china', $userAlbumTag, 1);
                    echo "国内-<lable style='color:red'>" . $userAlbumTag . "</lable>-组照：" . $domesticUserAlbumUserAlbumTagMultiCount . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                    $foreignUserAlbumUserAlbumTagMultiCount = $userAlbumManageData->GetCountAlbumForStatistics('!china', $userAlbumTag, 1);
                    echo "国外-<lable style='color:red'>" . $userAlbumTag . "</lable>-组照：" . $foreignUserAlbumUserAlbumTagMultiCount . "<br/>";
                }
            }
            echo "<a href='../user/index.php?a=album&m=statistics&type=1'>详细国家查询</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='../user/index.php?a=album&m=statistics&type=2'>推荐人详细统计</a>";
        } else if ($type == 1) {
            echo '
                <div style="width:800px;text-align:center;line-height:200%;"><h2>来稿情况及统计表</h2></div>

                <table width="800" cellpadding="1" cellspacing="1" style="background:#000000;font-size:12px;font-family:Verdana;">
                  <tr>
                    <td rowspan="2" align="center" style="color:#ffffff;background:#128FCF">序号<br />
                      No.</td>
                    <td rowspan="2" align="center" style="background:#E7E7E9">Country/Region</td>
                    <td rowspan="2" align="center" style="background:#E7E7E9">国家/地区</td>
                    <td rowspan="2" align="center" style="background:#E7E7E9">作者<br />
                    Entrants</td>
                    <td height="78" colspan="2" align="center" style="background:#E7E7E9">纪录类</td>
                    <td colspan="2" align="center" style="background:#E7E7E9">商业类</td>
                    <td colspan="2" align="center" style="background:#E7E7E9">艺术类</td>
                    <td colspan="2" align="center" style="background:#E7E7E9">女性类</td>
                    <td rowspan="2" align="center" style="background:#E7E7E9">作品数<br />
                      Entries</td>
                  </tr>
                  <tr>
                    <td align="center" style="background:#E7E7E9">彩色</td>
                    <td align="center" style="background:#E7E7E9">黑白</td>
                    <td align="center" style="background:#E7E7E9">彩色</td>
                    <td align="center" style="background:#E7E7E9">黑白</td>
                    <td align="center" style="background:#E7E7E9">彩色</td>
                    <td align="center" style="background:#E7E7E9">黑白</td>
                    <td align="center" style="background:#E7E7E9">彩色</td>
                    <td align="center" style="background:#E7E7E9">黑白</td>
                  </tr>';
            $countryList = $userInfoManageData->GetAllCountry();
            $siteId = 1;
            $userAlbumTagList = $userAlbumManageData->GetAllUserAlbumTag($siteId);
            for ($i = 0; $i < count($countryList); $i++) {
                $country = $countryList[$i]["country"];
                $countryUserCount = 0;

                $tag1 = 0;
                $tag2 = 0;
                $tag3 = 0;
                $tag4 = 0;
                $tag5 = 0;
                $tag6 = 0;
                $tag7 = 0;
                $tag8 = 0;

                for ($j = 0; $j < count($userAlbumTagList); $j++) {
                    $userAlbumTag = $userAlbumTagList[$j]['useralbumtag'];
                    if (strlen($userAlbumTag) > 2) {
                        //echo $userAlbumTag . "                   ";
                        $countryUserCount = $userInfoManageData->GetCountUserByCountry($country);
                        //$countryUserAlbumSingleCount = $userAlbumData->GetUserAlbumCountForStatisticsAndCountry($country, $userAlbumTag);
                        //$countryUserAlbumMultiCount = $userAlbumData->GetUserAlbumCountForStatisticsAndCountry($country, $userAlbumTag, 1);
                        //全部的，不区分组照，单幅
                        $countryUserAlbumCount = intval($userAlbumManageData->GetUserAlbumCountForStatisticsAndCountry($country, $userAlbumTag, -1));
                        switch ($userAlbumTag) {
                            case '非主题-纪录类-彩色':
                                $tag1 = $countryUserAlbumCount; //intval($countryUserAlbumSingleCount) + intval($countryUserAlbumMultiCount);
                                break;
                            case '非主题-纪录类-黑白':
                                $tag2 = $countryUserAlbumCount; //intval($countryUserAlbumSingleCount) + intval($countryUserAlbumMultiCount);
                                break;
                            case '非主题-商业类-彩色':
                                $tag3 = $countryUserAlbumCount; //intval($countryUserAlbumSingleCount) + intval($countryUserAlbumMultiCount);
                                break;
                            case '非主题-商业类-黑白':
                                $tag4 = $countryUserAlbumCount; //intval($countryUserAlbumSingleCount) + intval($countryUserAlbumMultiCount);
                                break;
                            case '非主题-艺术类-彩色':
                                $tag5 = $countryUserAlbumCount; //intval($countryUserAlbumSingleCount) + intval($countryUserAlbumMultiCount);
                                break;
                            case '非主题-艺术类-黑白':
                                $tag6 = $countryUserAlbumCount; //intval($countryUserAlbumSingleCount) + intval($countryUserAlbumMultiCount);
                                break;
                            case '主题-女性类-彩色':
                                $tag7 = $countryUserAlbumCount; //intval($countryUserAlbumSingleCount) + intval($countryUserAlbumMultiCount);
                                break;
                            case '主题-女性类-黑白':
                                $tag8 = $countryUserAlbumCount; //intval($countryUserAlbumSingleCount) + intval($countryUserAlbumMultiCount);
                                break;
                        }
                        //echo $countryUserCount . "         ";
                        //echo "单照-" . $countryUserAlbumSingleCount . "    组照" . $countryUserAlbumMultiCount . "<br/>";
                    }
                }
                $allTagCount = intval($tag1) + intval($tag2) + intval($tag3) + intval($tag4) + intval($tag5) + intval($tag6) + intval($tag7) + intval($tag8);
                echo '<tr>
                    <td align="center" style="color:#ffffff;background:#128FCF">' . ($i + 1) . '</td>
                    <td align="center" style="background:#E7E7E9">' . $country . '</td>
                    <td align="center" style="background:#E7E7E9">&nbsp;</td>
                    <td align="center" style="background:#E7E7E9">' . $countryUserCount . '</td>
                    <td align="center" style="background:#E7E7E9">' . $tag1 . '</td>
                    <td align="center" style="background:#E7E7E9">' . $tag2 . '</td>
                    <td align="center" style="background:#E7E7E9">' . $tag3 . '</td>
                    <td align="center" style="background:#E7E7E9">' . $tag4 . '</td>
                    <td align="center" style="background:#E7E7E9">' . $tag5 . '</td>
                    <td align="center" style="background:#E7E7E9">' . $tag6 . '</td>
                    <td align="center" style="background:#E7E7E9">' . $tag7 . '</td>
                    <td align="center" style="background:#E7E7E9">' . $tag8 . '</td>
                    <td align="center" style="background:#E7E7E9">' . $allTagCount . '</td>
                  </tr>
                ';
            }

            echo '</table>';
        } else if ($type == 2) {
            $userData = new UserData();
            $userAlbumManageData = new UserAlbumData();

            echo '<div style="float:left">国内推荐人<table border="1" cellpadding="0" cellspacing="0" style="background:#ffffff;font-size:12px;font-family:Verdana;text-align:center;line-height:200%;">
                    <tr>
                        <td>推荐人</td>
                        <td>推荐作者数</td>
                        <td>推荐作品数</td>
                    </tr>';
            $recommenderArray = $userData->StatisticsRecommender();
            for ($i = 0; $i < count($recommenderArray); $i++) {
                $recommenderID = $recommenderArray[$i]["userid"];
                $recommenderName = $recommenderArray[$i]["realname"];
                $recommendUserCount = $recommenderArray[$i]["count"];

                $recommendAlbumCount = $userAlbumManageData->StatisticsRecommendAlbumCount($recommenderID);

                echo '<tr>
                        <td>' . $recommenderName . '</td>
                        <td>' . $recommendUserCount . '</td>
                        <td>' . $recommendAlbumCount . '</td>
                    </tr>';
            }
            echo '</table></div>';

            echo '<div style="float:left;margin-left:10px;">国外推荐人<table border="1" cellpadding="0" cellspacing="0" style="background:#ffffff;font-size:12px;font-family:Verdana;text-align:center;line-height:200%;">
                    <tr>
                        <td>推荐人</td>
                        <td>推荐作者数</td>
                        <td>推荐作品数</td>
                    </tr>';
            $recommenderArray = $userData->StatisticsRecommender("!china");
            for ($i = 0; $i < count($recommenderArray); $i++) {
                $recommenderID = $recommenderArray[$i]["userid"];
                $recommenderName = $recommenderArray[$i]["realname"];
                $recommendUserCount = $recommenderArray[$i]["count"];

                $recommendAlbumCount = $userAlbumManageData->StatisticsRecommendAlbumCount($recommenderID);

                echo '<tr>
                        <td>' . $recommenderName . '</td>
                        <td>' . $recommendUserCount . '</td>
                        <td>' . $recommendAlbumCount . '</td>
                    </tr>';
            }
            echo '</table></div>';
        }
    }

}

