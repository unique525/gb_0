 <?php

/**
 * 投票调查前台生成类
 * @category iCMS
 * @package iCMS_Rules_Gen_Vote
 * @author yanjiuyuan
 */
class VotePublicGen extends BasePublicGen implements IBasePublicGen
{

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenPublic()
    {
        $result = "";

        $action = Control::GetRequest("a", "");
        switch ($action) {
            case "test":
                $result = self::GenTest();
                break;
            case "vote":
                $result = self::GenVote();
                break;
            case "score":
                $result = self::GenScore();
                break;
            case "vote_single":
                $result = self::GenVoteSingle();
                break;
            case "select_item_list":
                $result = self::GenSelectItemList();
                break;
            case "async_get_ranking_list":
                $result = self::AsyncGetRankingList();
                break;
            case "async_get_record_of_user":
                $result = self::AsyncGetRecordOfUser();
                break;
            case "async_get_score_result":
                $result = self::AsyncGetScoreResult();
                break;
            default:
                break;
        }

        return $result;
    }

    /**
     * 投票调查提交
     * @return int  返回执行结果
     */
    private function GenVote() {
        if (!empty($_GET)) {
            session_start();
            $voteId = intval(Control::GetRequest("vote_id", "0"));
            $userId = Control::GetUserID();
            $sessionName = Control::GetRequest("sn","");
            $code = Control::GetRequest("check_code" . $voteId,"");
            $createDate = date("Y-m-d H:i:s", time());
            $ipAddress = Control::GetIP();
            $agent = Control::GetOS() . "与" . Control::GetBrowser();
            $votePublicData = new VotePublicData();
            $voteItemPublicData = new VoteItemPublicData();
            $voteSelectItemPublicData = new VoteSelectItemPublicData();
            $voteRecordData = new VoteRecordData();
            $nowDate = date("Y-m-d", time());
            $voteSelectItemIdArr = array();   //题目选项id数组
            $voteRecordDetailArr = array();   //投票详细记录数组
            $arrRow = $votePublicData->GetVoteRow($voteId);
            $isCheckCode = $arrRow['IsCheckCode'];
            if ($isCheckCode == "1") {//如果启用了验证码先做验证码判断
                if (VerifyCode::Check($sessionName, 0, $code)==1)
                    return Control::GetRequest("jsonpcallback","") . '({"result":"-5"})';
            }
            //销毁验证码session
            unset($_SESSION[$sessionName]);
            $voteCheckCode=null;
            $voteItemIdArr=array();
            //解析$_GET生成对应数组
            foreach ($_GET as $key1 => $value1) {
                if (strpos($key1, "vote_select_item") === 0) {
                    $voteItemId = str_ireplace("vote_select_item", "", $key1);
                    $allNum = 0;
                    $value1 = array_flip(array_flip($value1)); //删除掉重复的投票选项（对应非正常刷票）
                    foreach ($value1 as $value2) {
                        $allNum+=1;
                        $voteSelectItemId = $value2;
                        if (!self::IsBelongVoteId($voteId, $voteItemId, $voteSelectItemId)) {//如果提交的题目id或选项id不属于vote_id，则报错返回。
                            return Control::GetRequest("jsonpcallback","") . '({"result":"-7"})';
                        }
                        $voteSelectItemIdArr[] = $voteSelectItemId;   //收集要提交的选项ID并组成数组
                        $voteSelectItemScore = intval(Control::GetRequest("score_$voteSelectItemId", "0"));
                        $voteRecordDetailArr[] = array("VoteItemId" => $voteItemId, "VoteSelectItemId" => $voteSelectItemId, "Score" => $voteSelectItemScore, "UserId"=>$userId);
                    }
                    $voteItemIdArr[] = array($voteItemId, $allNum);   //收集要提交的题目ID并组成数组
                }
            }
            //判断投票是否为空并反馈
            if ($voteItemIdArr == null) {
                return Control::GetRequest("jsonpcallback","") . '({"result":"-2"})';
            }
            $state = $arrRow['State'];
            //判断投票是否停止
            if ($state != 0) {
                return Control::GetRequest("jsonpcallback","") . '({"result":"-3"})';
            }
            //判断投票是否在有效时间段内
            $beginDate = $arrRow['BeginDate'];
            $endDate = $arrRow['EndDate'];
            if (!(strtotime($createDate) > strtotime($beginDate) && strtotime($createDate) < strtotime($endDate))) {
                return Control::GetRequest("jsonpcallback","") . '({"result":"-4"})';
            }
            //判断每个投票项（多选）是不是符合最大选择数和最小选择数目限制
            $arrItemList = $voteItemPublicData->GetListByVoteId($voteId, 0);
            $unMeetItemArr = null;
            foreach ($voteItemIdArr as $value) {
                $voteItemId = $value[0];  //题目ID
                $voteItemAllCount = $value[1];    //题目所属票数总和
                foreach ($arrItemList as $value1) {
                    if ($voteItemId == $value1['VoteItemId']) {
                        $VoteItemTitle = $value1['VoteItemTitle'];
                        $selectNumMin = $value1['SelectNumMin'];
                        $selectNumMax = $value1['SelectNumMax'];
                        //从提交数据中筛选出不合法的题目提交
                        if (($selectNumMin != '0' && $voteItemAllCount < $selectNumMin) || ($selectNumMax != '0' && $voteItemAllCount > $selectNumMax))
                            $unMeetItemArr[] = array("voteItemTitle" => $VoteItemTitle, "VoteItemAllCount" => $voteItemAllCount, "SelectNumMin" => $selectNumMin, "SelectNumMax" => $selectNumMax);
                    }
                }
            }
            if (count($unMeetItemArr) > 0) {
                $unMeetItemArr = json_encode($unMeetItemArr);
                return Control::GetRequest("jsonpcallback","") . '({"result":"-6","UnMeetItemArr":' . $unMeetItemArr . '})';
            }
            //判断投票ip限制
            $ipCount = $voteRecordData->GetIpCount($voteId, $ipAddress, $nowDate);   //获取问卷相同IP的次数
            $ipMaxCount = $arrRow["IpMaxCount"];   //获取问卷同IP准许提交次数上限
            if ($ipCount >= $ipMaxCount) {
                return Control::GetRequest("jsonpcallback","") . '({"result":"-10","IpMaxCount":"' . $ipMaxCount . '"})';   //限制问卷提交次数
            }
            //判断投票用户限制
            $userCount = $voteRecordData->GetUserCount($voteId, $userId, $nowDate);   //获取用户提交次数
            $userMaxCount = $arrRow["UserMaxCount"];   //获取问卷同用户准许提交次数上限
            if ($userCount >= $userMaxCount) {
                return Control::GetRequest("jsonpcallback","") . '({"result":"-8","UserMaxCount":"' . $userMaxCount . '"})';   //限制问卷提交次数
            }
            $result = $votePublicData->UpdateCount($voteId); //投票提交
            $result = $voteItemPublicData->UpdateCountBatch($voteItemIdArr); //题目提交
            $result = $voteSelectItemPublicData->UpdateCountBatch($voteSelectItemIdArr);  //选项提交
            $result = $voteRecordData->Create($voteId, $userId, $ipAddress, $agent, $createDate); //投票记录提交
            $voteRecordId = $result; //得到投票记录主表id
            $result = $voteRecordData->CreateDetailBatch($voteRecordId, $voteRecordDetailArr); //投票详细记录提交
            //返回页面响应结果
            if ($result == 1) {
                return Control::GetRequest("jsonpcallback","") . '({"result":"1","VoteRecordId":"' . $voteRecordId . '","IpMaxCount":"' . $ipMaxCount . '"})';
            } else {
                return Control::GetRequest("jsonpcallback","") . '({"result":"-1","VoteRecordId":"' . $voteRecordId . '","IpMaxCount":"' . $ipMaxCount . '"})';
            }
        }
        else {
            return Control::GetRequest("jsonpcallback","") . '({"result":"-1"})';
        }
    }

    /**
     * 单项提交
     * @return array  返回执行结果
     */
    private function GenVoteSingle() {
        if (!empty($_GET)) {
            session_start();
            $voteId = intval(Control::GetRequest("vote_id", "0"));
            $userId = Control::GetUserID();
            $sessionName = Control::GetRequest("sn","");
            $code = Control::GetRequest("check_code" . $voteId,"");
            $voteCheckCode = $_SESSION[$sessionName];
            $voteItemId = intval(Control::GetRequest("vote_item_id", "0"));
            $voteSelectItemId = intval(Control::GetRequest("vote_select_item_id", "0"));
            $createDate = date("Y-m-d H:i:s", time());
            $ipAddress = Control::GetIP();
            $agent = Control::GetOS() . "与" . Control::GetBrowser();
            $votePublicData = new VotePublicData();
            $voteItemData = new VoteItemPublicData();
            $voteSelectItemPublicData = new VoteSelectItemPublicData();
            $voteRecordData = new VoteRecordData();
            $nowDate = date("Y-m-d", time());
            $voteSelectItemIdArr[] = $voteSelectItemId;   //收集要提交的选项ID并组成数组
            $voteSelectItemScore = intval(Control::GetRequest("score_$voteSelectItemId", "0"));
            $voteRecordDetailArr[] = array("VoteItemId" => $voteItemId, "VoteSelectItemId" => $voteSelectItemId, "Score" => $voteSelectItemScore, "UserId"=>$userId);
            $voteItemIdArr[] = array($voteItemId, 1);   //收集要提交的题目ID并组成数组
            $arrRow = $votePublicData->GetVoteRow($voteId);
            $isCheckCode = $arrRow['IsCheckCode'];
            if ($isCheckCode == "1") {//如果启用了验证码先做验证码判断
                if (empty($voteCheckCode) || empty($code) || $voteCheckCode != $code)
                    return Control::GetRequest("jsonpcallback","") . '({"result":"-5"})';
            }
            //判断投票是否为空并反馈
            if ($voteItemIdArr == null) {
                return Control::GetRequest("jsonpcallback","") . '({"result":"-2"})';
            }
            //如果提交的题目id或选项id不属于vote_id，则报错返回。
            if (!self::IsBelongVoteId($voteId, $voteItemId, $voteSelectItemId)) {
                return Control::GetRequest("jsonpcallback","") . '({"result":"-7"})';
            }
            $state = $arrRow['State'];
            //判断投票是否停止
            if ($state != 0) {
                return Control::GetRequest("jsonpcallback","") . '({"result":"-3"})';
            }
            //判断投票是否在有效时间段内
            $beginDate = $arrRow['BeginDate'];
            $endDate = $arrRow['EndDate'];
            if (!(strtotime($createDate) > strtotime($beginDate) && strtotime($createDate) < strtotime($endDate))) {
                return Control::GetRequest("jsonpcallback","") . '({"result":"-4"})';
            }
            $ipCount = $voteRecordData->GetIpCount($voteId, $ipAddress, $nowDate);   //获取问卷相同IP的次数
            $ipMaxCount = $votePublicData->GetIpMaxCount($voteId,false);   //获取问卷同IP准许提交次数上限
            if ($ipCount >= $ipMaxCount) {
                return Control::GetRequest("jsonpcallback","") . '({"result":"-10","IpMaxCount":"' . $ipMaxCount . '"})';
            }
            $result = $votePublicData->UpdateCount($voteId); //投票提交
            $result = $voteItemData->UpdateCountBatch($voteItemIdArr); //题目提交
            $result = $voteSelectItemPublicData->UpdateCountBatch($voteSelectItemIdArr);//选项提交
            $result = $voteRecordData->Create($voteId, $userId, $ipAddress, $agent, $createDate); //创建投票记录
            $voteRecordId = $result; //得到投票记录主表id
            $result = $voteRecordData->CreateDetailBatch($voteRecordId, $voteRecordDetailArr); //投票详细记录提交
            if ($result == 1) {
                return Control::GetRequest("jsonpcallback","") . '({"result":"1","VoteRecordId":"' . $voteRecordId . '","IpMaxCount":"' . $ipMaxCount . '"})';
            } else {
                return Control::GetRequest("jsonpcallback","") . '({"result":"-1","VoteRecordId":"' . $voteRecordId . '","IpMaxCount":"' . $ipMaxCount . '"})';
            }
        }
        else {
            return Control::GetRequest("jsonpcallback","") . '({"result":"-1"})';
        }
    }


    /**
     * 打分
     * @return array 返回数据集结果
     */
    public function GenScore() {
        if (!empty($_GET)) {
            session_start();
            $voteId = intval(Control::GetRequest("vote_id", "0"));
            $userId = Control::GetUserID();
            $userPublicData=new UserPublicData();
            $userGroupId=$userPublicData->GetUserGroupId($userId,true);
            $sessionName = Control::GetRequest("sn","");
            $code = Control::GetRequest("check_code" . $voteId,"");
            $createDate = date("Y-m-d H:i:s", time());
            $ipAddress = Control::GetIP();
            $agent = Control::GetOS() . "与" . Control::GetBrowser();
            $votePublicData = new VotePublicData();
            $voteItemPublicData = new VoteItemPublicData();
            $voteSelectItemPublicData = new VoteSelectItemPublicData();
            $voteRecordData = new VoteRecordData();
            $nowDate = date("Y-m-d", time());
            $voteSelectItemIdArr = array();   //题目选项id数组
            $voteRecordDetailArr = array();   //投票详细记录数组
            $arrRow = $votePublicData->GetVoteRow($voteId);
            $isCheckCode = $arrRow['IsCheckCode'];
            if ($isCheckCode == "1") {//如果启用了验证码先做验证码判断
                if (VerifyCode::Check($sessionName, 0, $code)==1)
                    return Control::GetRequest("jsonpcallback","") . '({"result":"-5"})';
            }
            //销毁验证码session
            unset($_SESSION[$sessionName]);


            /**********************
             * user group 权限判断
             **********************/
            $strLimitUserGroupId=$votePublicData->GetLimitUserGroupId($voteId,true);
            if($strLimitUserGroupId!=""){
                $can=false;
                $arrLimitUserGroupId=str_split($strLimitUserGroupId,",");
                foreach($arrLimitUserGroupId as $limitUserGroupId){
                        if($limitUserGroupId==$userGroupId){
                            $can=true;
                        }
                }
                if(!$can){
                    return Control::GetRequest("jsonpcallback","") . '({"result":"-11"})'; //用户组无权限
                }
            }



            $voteCheckCode=null;
            $voteItemIdArr=array();
            //解析$_GET生成对应数组
            foreach ($_GET as $key1 => $value1) {
                if (strpos($key1, "vote_select_item") === 0) {
                    $voteItemId = str_ireplace("vote_select_item", "", $key1);
                    $allNum = 0;
                    $value1 = array_flip(array_flip($value1)); //删除掉重复的投票选项（对应非正常刷票）
                    foreach ($value1 as $value2) {
                        $allNum+=1;
                        $voteSelectItemId = $value2;
                        if (!self::IsBelongVoteId($voteId, $voteItemId, $voteSelectItemId)) {//如果提交的题目id或选项id不属于vote_id，则报错返回。
                            return Control::GetRequest("jsonpcallback","") . '({"result":"-7"})';
                        }
                        $voteSelectItemIdArr[] = $voteSelectItemId;   //收集要提交的选项ID并组成数组
                        $voteSelectItemScore = intval(Control::GetRequest("score_$voteSelectItemId", "0"));
                        $voteRecordDetailArr[] = array("VoteItemId" => $voteItemId, "VoteSelectItemId" => $voteSelectItemId, "Score" => $voteSelectItemScore, "UserId"=>$userId);
                    }
                    $voteItemIdArr[] = array($voteItemId, $allNum);   //收集要提交的题目ID并组成数组
                }
            }
            //判断投票是否为空并反馈
            if ($voteItemIdArr == null) {
                return Control::GetRequest("jsonpcallback","") . '({"result":"-2"})';
            }
            $state = $arrRow['State'];
            //判断投票是否停止
            if ($state != 0) {
                return Control::GetRequest("jsonpcallback","") . '({"result":"-3"})';
            }
            //判断投票是否在有效时间段内
            $beginDate = $arrRow['BeginDate'];
            $endDate = $arrRow['EndDate'];
            if (!(strtotime($createDate) > strtotime($beginDate) && strtotime($createDate) < strtotime($endDate))) {
                return Control::GetRequest("jsonpcallback","") . '({"result":"-4"})';
            }
            /*/判断每个投票项（多选）是不是符合最大选择数和最小选择数目限制
            $arrItemList = $voteItemPublicData->GetListByVoteId($voteId, 0);
            $unMeetItemArr = null;
            foreach ($voteItemIdArr as $value) {
                $voteItemId = $value[0];  //题目ID
                $voteItemAllCount = $value[1];    //题目所属票数总和
                foreach ($arrItemList as $value1) {
                    if ($voteItemId == $value1['VoteItemId']) {
                        $VoteItemTitle = $value1['VoteItemTitle'];
                        $selectNumMin = $value1['SelectNumMin'];
                        $selectNumMax = $value1['SelectNumMax'];
                        //从提交数据中筛选出不合法的题目提交
                        if (($selectNumMin != '0' && $voteItemAllCount < $selectNumMin) || ($selectNumMax != '0' && $voteItemAllCount > $selectNumMax))
                            $unMeetItemArr[] = array("voteItemTitle" => $VoteItemTitle, "VoteItemAllCount" => $voteItemAllCount, "SelectNumMin" => $selectNumMin, "SelectNumMax" => $selectNumMax);
                    }
                }
            }
            if (count($unMeetItemArr) > 0) {
                $unMeetItemArr = json_encode($unMeetItemArr);
                return Control::GetRequest("jsonpcallback","") . '({"result":"-6","UnMeetItemArr":' . $unMeetItemArr . '})';
            }
            /*判断投票ip限制
            $ipCount = $voteRecordData->GetIpCount($voteId, $ipAddress, $nowDate);   //获取问卷相同IP的次数
            $ipMaxCount = $arrRow["IpMaxCount"];   //获取问卷同IP准许提交次数上限
            if ($ipCount >= $ipMaxCount) {
                return Control::GetRequest("jsonpcallback","") . '({"result":"-10","IpMaxCount":"' . $ipMaxCount . '"})';   //限制问卷提交次数
            }*/

            //删除上次打分的数据
            $lastRecordId=$voteRecordData->GetRecordIdOfUser($voteId,$userId);
            $DeleteLastRecord=$voteRecordData->DeleteUserLastRecord($voteId, $userId);
            $DeleteLastRecordDetail=$voteRecordData->DeleteUserLastRecordDetail($lastRecordId, $userId);
            if ($DeleteLastRecord <0 || $DeleteLastRecordDetail<0) {
                return Control::GetRequest("jsonpcallback","") . '({"result":"-9","Delete old record error"})';   //删除旧记录失败
            }

            if($DeleteLastRecord==0&&$DeleteLastRecordDetail==0){ //如果之前没有数据 才新增记录
                $result = $votePublicData->UpdateCount($voteId); //投票提交
                $result = $voteItemPublicData->UpdateCountBatch($voteItemIdArr); //题目提交
                $result = $voteSelectItemPublicData->UpdateCountBatch($voteSelectItemIdArr);  //选项提交
            }
            $result = $voteRecordData->Create($voteId, $userId, $ipAddress, $agent, $createDate); //投票记录提交
            $voteRecordId = $result; //得到投票记录主表id
            $result = $voteRecordData->CreateDetailBatch($voteRecordId, $voteRecordDetailArr); //投票详细记录提交
            //返回页面响应结果
            if ($result == 1) {
                return Control::GetRequest("jsonpcallback","") . '({"result":"1","VoteRecordId":"' . $voteRecordId . '"})';
            } else {
                return Control::GetRequest("jsonpcallback","") . '({"result":"-1","VoteRecordId":"' . $voteRecordId . '"})';
            }
        }
        else {
            return Control::GetRequest("jsonpcallback","") . '({"result":"-1"})';
        }
    }

    /**
     * 获取题目选项列表
     * @return array 返回数据集结果
     */
    public function GenSelectItemList() {
        $resultArrList = null;
        $voteId = Control::GetRequest("vote_id", "0");

        $beginDate=Control::GetRequest("begin_date","");
        $endDate=Control::GetRequest("end_date","");

        $votePublicData = new VotePublicData();
        $allItem = $votePublicData->GetSelectItemList($voteId,$beginDate,$endDate);       //获取投票调查题目
        //计算百分比
        foreach ($allItem as $columnValue) {
            $voteItemAddCount = $columnValue["VoteItemAddCount"];          //题目的加票数
            $selectItemAdd = $columnValue["VoteSelectItemAddCount"];      //题目选项的加票数
            unset($columnValue["VoteItemAddCount"]);                 //隐藏掉加票数据
            unset($columnValue["VoteSelectItemAddCount"]);                //隐藏掉加票数据
            $columnValue["VoteItemRecordAllCount"]+=$voteItemAddCount;        //实际票数加上加票数得到题目总票数
            $columnValue["VoteSelectItemRecordCount"]+=$selectItemAdd;    //实际票数加上加票数得到题目选项的总票数
            $voteItemRecordAllCount = $columnValue["VoteItemRecordAllCount"];
            $voteSelectItemCount = $columnValue["VoteSelectItemRecordCount"];
            $voteSelectItemPer = 0;
            if ($voteItemRecordAllCount != 0)
                $voteSelectItemPer = round($voteSelectItemCount / $voteItemRecordAllCount, 2) * 100;    //计算选项的百分比
            $columnValue['VoteSelectItemPer'] = $voteSelectItemPer;
            $resultArrList[] = $columnValue;
        }

        $resultArrList = json_encode($resultArrList);
        return Control::GetRequest("jsonpcallback","") . '({"result":' . $resultArrList . '})';
    }

    /**
     * 判断提交的每个题目选项合法性，是不是与题目ID对应
     * @param int $voteId    投票ID
     * @param int $voteItemId    题目ID
     * @param int $voteSelectItemId    题目选项ID
     * @return string $result true为合法，false为非法
     */
    public function IsBelongVoteId($voteId, $voteItemId, $voteSelectItemId) {
        $result = false;
        $votePublicData = new VotePublicData();
        $allItem = $votePublicData->GetSelectItemList($voteId);
        foreach ($allItem as $columnValue) {
            $ItemId = $columnValue["VoteItemId"];
            $SelectItemId = $columnValue["VoteSelectItemId"];
            if ($ItemId == $voteItemId && $SelectItemId == $voteSelectItemId) {
                $result = true;
                break;
            }
        }
        return $result;
    }

    private function GenTest(){
        $siteId = parent::GetSiteIdByDomain();
        $templateContent = parent::GetDynamicTemplateContent("vote_test", $siteId);

        parent::ReplaceFirst($templateContent);
        parent::ReplaceSiteInfo($siteId, $templateContent);
        $templateContent = str_ireplace("{SiteId}", $siteId, $templateContent);
        //模板替换
        $templateContent = parent::ReplaceTemplate($templateContent);
        $patterns = '/\{s_(.*?)\}/';
        $templateContent = preg_replace($patterns, "", $templateContent);
        parent::ReplaceEnd($templateContent);
        return $templateContent;
    }


    private function AsyncGetRankingList(){
        $result=null;
        $voteItemId = Control::GetRequest("vote_item_id",0);
        $topCount = Control::GetRequest("top_count",0);
        if($voteItemId>0){
            $voteSelectItemPublicData=new VoteSelectItemPublicData();
            $result=$voteSelectItemPublicData->GetTopList($voteItemId,$topCount);
        }
        return Control::GetRequest("jsonpcallback","") . '('.json_encode($result).')';;
    }


    private function AsyncGetRecordOfUser(){
        $result=null;
        $userId=1;//Control::GetUserId();
        if($userId<=0){
            return Control::GetRequest("jsonpcallback","") . '({"result":"-1","no user id"})';   //未登录
        }

        $voteId = Control::GetRequest("vote_id","-1");
        if($voteId<0){
            return Control::GetRequest("jsonpcallback","") . '({"result":"-2","vote id error"})';   //投票id错误
        }

        $voteRecordData=new VoteRecordData();
        $voteRecordId=$voteRecordData->GetRecordIdOfUser($voteId,$userId);
        if($voteRecordId<=0){
            return Control::GetRequest("jsonpcallback","") . '({"result":"-3","vote record id error"})';   //投票记录获取失败
        }

        $result=$voteRecordData->GetRecordDetail($voteRecordId);
        return Control::GetRequest("jsonpcallback","") . '('.json_encode($result).')';
    }


    private function AsyncGetScoreResult(){
        $result = null;

        $userId=1;//Control::GetUserId();
        if($userId<=0){
            return Control::GetRequest("jsonpcallback","") . '({"result":"-1","no user id"})';   //未登录
        }

        $voteId = Control::GetRequest("vote_id","-1");
        if($voteId<0){
            return Control::GetRequest("jsonpcallback","") . '({"result":"-2","vote id error"})';   //投票id错误
        }


        $beginDate=Control::GetRequest("begin_date","");
        $endDate=Control::GetRequest("end_date","");
        $voteRecordData = new VoteRecordData();
        $result = $voteRecordData->GetScoreList($voteId,$beginDate,$endDate);       //获取结果

        if($result==null){
            return Control::GetRequest("jsonpcallback","") . '({"result":"-3","data error"})';   //获取失败
        }
        return Control::GetRequest("jsonpcallback","") . '('.json_encode($result).')';
    }
}

?>
