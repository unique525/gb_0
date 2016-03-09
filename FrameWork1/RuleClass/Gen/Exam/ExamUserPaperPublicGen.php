<?php

/**
 * 公开访问 试卷 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Exam
 * @author zhangchi
 */
class ExamUserPaperPublicGen extends BasePublicGen implements IBasePublicGen{

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenPublic()
    {
        $result = "";
        $action = Control::GetRequest("a", "");
        switch ($action) {

            case "gen":
                $result = self::GenExamUserPaper();
                break;
            case "finished":
                $result = self::GenFinished();
                break;
            case "async_count_score":
                $result = self::AsyncCountScore();
                break;
            case "error_list":
                $result = self::GenErrorAnswerList();
                break;

            /** 导出临时表结果（jkq） **/

            case "get_csv":
                $result = self::GetCsv();
                break;
        }

        $result = str_ireplace("{action}", $action, $result);
        return $result;
    }

    private function GenExamUserPaper(){
        $userId = Control::GetUserId();
        $examQuestionClassId = Control::GetRequest("exam_question_class_id", 0);

        $siteId = parent::GetSiteIdByDomain();

        if($examQuestionClassId<=0){
            return "exam question class is null";
        }

        if($userId<=0){
            /*/没有登录则自动注册一个帐号

            $userName = uniqid();
            $userPass = "111111";
            $regIp = Control::GetIp();
            $userPublicData = new UserPublicData();
            $userId = $userPublicData->Create(
                $siteId,
                $userPass,
                $regIp,
                $userName
            );
            $userInfoPublicData = new UserInfoPublicData();
            $userRolePublicData = new UserRolePublicData();
            $siteConfigData = new SiteConfigData($siteId);
            $newUserId = $userId;
            if ($newUserId > 0) {

                //插入会员信息表
                $realName = Format::FormatHtmlTag(Control::PostOrGetRequest("real_name","", false));
                $nickName = Format::FormatHtmlTag(Control::PostOrGetRequest("nick_name","", false));
                $avatarUploadFileId = Format::FormatHtmlTag(Control::PostOrGetRequest("avatar_upload_fileId",0));
                $userScore = Format::FormatHtmlTag(Control::PostOrGetRequest("user_score",0));
                $userMoney = Format::FormatHtmlTag(Control::PostOrGetRequest("user_money",0));
                $userCharm = Format::FormatHtmlTag(Control::PostOrGetRequest("user_charm",0));
                $userExp = Format::FormatHtmlTag(Control::PostOrGetRequest("user_exp",0));
                $userPoint = Format::FormatHtmlTag(Control::PostOrGetRequest("user_point",0));
                $question = Format::FormatHtmlTag(Control::PostOrGetRequest("question","", false));
                $answer = Format::FormatHtmlTag(Control::PostOrGetRequest("answer","", false));
                $sign = Control::PostOrGetRequest("sign","", false);
                $lastVisitIP = $regIp;
                $lastVisitTime = date("Y-m-d H:i:s", time());
                $email = Format::FormatHtmlTag(Control::PostOrGetRequest("email","", false));
                $qq = Format::FormatHtmlTag(Control::PostOrGetRequest("qq","", false));
                $country = Format::FormatHtmlTag(Control::PostOrGetRequest("country","", false));
                $comeFrom = Format::FormatHtmlTag(Control::PostOrGetRequest("come_from","", false));
                $honor = Format::FormatHtmlTag(Control::PostOrGetRequest("honor","", false));
                $birthday = Format::FormatHtmlTag(Control::PostOrGetRequest("birthday","", false));
                $gender = Format::FormatHtmlTag(Control::PostOrGetRequest("gender",0));
                $fansCount = Format::FormatHtmlTag(Control::PostOrGetRequest("fans_count",0));
                $idCard = Format::FormatHtmlTag(Control::PostOrGetRequest("id_card","", false));
                $postCode = Format::FormatHtmlTag(Control::PostOrGetRequest("post_code","", false));
                $address = Format::FormatHtmlTag(Control::PostOrGetRequest("address","", false));
                $tel = Format::FormatHtmlTag(Control::PostOrGetRequest("tel",""));
                $mobile = Format::FormatHtmlTag(Control::PostOrGetRequest("mobile",""));
                $province = Format::FormatHtmlTag(Control::PostOrGetRequest("province",""));
                $occupational = Format::FormatHtmlTag(Control::PostOrGetRequest("occupational",""));
                $city = Format::FormatHtmlTag(Control::PostOrGetRequest("city",""));
                $relationship = Format::FormatHtmlTag(Control::PostOrGetRequest("relationship",0));
                $hit = Format::FormatHtmlTag(Control::PostOrGetRequest("hit",0));
                $messageCount = Format::FormatHtmlTag(Control::PostOrGetRequest("message_count",0));
                $userPostCount = Format::FormatHtmlTag(Control::PostOrGetRequest("user_post_count",0));
                $userPostBestCount = Format::FormatHtmlTag(Control::PostOrGetRequest("user_post_best_count",0));
                $userActivityCount = Format::FormatHtmlTag(Control::PostOrGetRequest("user_activity_count",0));
                $userAlbumCount = Format::FormatHtmlTag(Control::PostOrGetRequest("user_album_count",0));
                $userBestAlbumCount = Format::FormatHtmlTag(Control::PostOrGetRequest("user_best_album_count",0));
                $userRecAlbumCount = Format::FormatHtmlTag(Control::PostOrGetRequest("user_rec_album_count",0));
                $userAlbumCommentCount = Format::FormatHtmlTag(Control::PostOrGetRequest("user_album_comment_count",0));
                $userCommissionOwn = Format::FormatHtmlTag(Control::PostOrGetRequest("user_commission_own",0));
                $userCommissionChild = Format::FormatHtmlTag(Control::PostOrGetRequest("user_commission_child",0));
                $userCommissionGrandson = Format::FormatHtmlTag(Control::PostOrGetRequest("user_commission_grandson",0));

                $userInfoPublicData->Create($newUserId, $realName, $nickName,$avatarUploadFileId, $userScore, $userMoney, $userCharm, $userExp, $userPoint, $question, $answer, $sign, $lastVisitIP, $lastVisitTime, $email, $qq, $country, $comeFrom, $honor, $birthday, $gender, $fansCount, $idCard, $postCode, $address, $tel, $mobile, $province, $occupational, $city, $relationship, $hit, $messageCount, $userPostCount, $userPostBestCount, $userActivityCount, $userAlbumCount, $userBestAlbumCount, $userRecAlbumCount, $userAlbumCommentCount, $userCommissionOwn, $userCommissionChild, $userCommissionGrandson);

                //插入会员角色表
                $newMemberGroupId = $siteConfigData->UserDefaultUserGroupIdForRole;
                $userRolePublicData->Init($newUserId, $siteId, $newMemberGroupId);

                Control::SetUserCookie($userId,$userName, 1, 0);
            }


*/
            //Control::GoUrl("/default.php?mod=user&a=login&re_url=". urlencode("/default.php?mod=exam_user_paper&a=gen&exam_question_class_id=".$examQuestionClassId));
            //Control::GoUrl("/default.php?mod=channel&a=default&temp=login_10573");
            //return "";
            $userId=0;


        }

        $nowClock=date("H");
            if($nowClock<8||$nowClock>22){
               // Control::GoUrl("http://www.cetzgh.org.cn/h/10573/message.html");die();
            }



        $examQuestionClassId = Control::GetRequest("exam_question_class_id", 0);

        $examQuestionClassPublicData = new ExamQuestionClassPublicData();
        $examUserPaperPublicData = new ExamUserPaperPublicData();
        $examQuestionPublicData = new ExamQuestionPublicData();
        $examUserAnswerPublicData = new ExamUserAnswerPublicData();

        $mustSelect = 1;
        $withCache = false;
        $examQuestionType = 1;
        $mustCount = $examQuestionClassPublicData->GetMustSelectType1Count($examQuestionClassId,$withCache);
        $arrMustType1QuestionList = $examQuestionPublicData->GetList($examQuestionClassId, $mustSelect, $mustCount,$examQuestionType);
        $examQuestionType = 2;
        $mustCount = $examQuestionClassPublicData->GetMustSelectType2Count($examQuestionClassId,$withCache);
        $arrMustType2QuestionList = $examQuestionPublicData->GetList($examQuestionClassId, $mustSelect, $mustCount,$examQuestionType);
        $examQuestionType = 3;
        $mustCount = $examQuestionClassPublicData->GetMustSelectType3Count($examQuestionClassId,$withCache);
        $arrMustType3QuestionList = $examQuestionPublicData->GetList($examQuestionClassId, $mustSelect, $mustCount,$examQuestionType);


        $mustSelect = 0;
        $examQuestionType = 1;
        $nonCount = $examQuestionClassPublicData->GetNonMustSelectType1Count($examQuestionClassId,$withCache);
        $arrNonMustType1QuestionList = $examQuestionPublicData->GetList($examQuestionClassId, $mustSelect, $nonCount,$examQuestionType);
        $examQuestionType = 2;
        $nonCount = $examQuestionClassPublicData->GetNonMustSelectType2Count($examQuestionClassId,$withCache);
        $arrNonMustType2QuestionList = $examQuestionPublicData->GetList($examQuestionClassId, $mustSelect, $nonCount,$examQuestionType);
        $examQuestionType = 3;
        $nonCount = $examQuestionClassPublicData->GetNonMustSelectType3Count($examQuestionClassId,$withCache);
        $arrNonMustType3QuestionList = $examQuestionPublicData->GetList($examQuestionClassId, $mustSelect, $nonCount,$examQuestionType);



        $beginTime = date("Y-m-d H:i:s", time());
        $endTime = "";
        $getScore = 0;
        $answer = "";
        $examUserPaperId = $examUserPaperPublicData->Create($userId,$beginTime,$endTime,$getScore);
        $createData = date("Y-m-d H:i:s", time());

        if($examUserPaperId > 0){
            $lastExamUserAnswerId1 = 0;
            $lastExamUserAnswerId2 = 0;
            $lastExamUserAnswerId3 = 0;
            $lastExamUserAnswerId4 = 0;
            $lastExamUserAnswerId5 = 0;
            $lastExamUserAnswerId6 = 0;

            for($i=0;$i<count($arrMustType1QuestionList);$i++){
                $lastExamUserAnswerId1 = $examUserAnswerPublicData->Create($examUserPaperId,$arrMustType1QuestionList[$i]['ExamQuestionId'],$createData,$answer,$arrMustType1QuestionList[$i]['State'],$getScore);
            }
            for($j=0;$j<count($arrMustType1QuestionList);$j++){
                $lastExamUserAnswerId2= $examUserAnswerPublicData->Create($examUserPaperId,$arrMustType2QuestionList[$j]['ExamQuestionId'],$createData,$answer,$arrMustType2QuestionList[$j]['State'],$getScore);
            }
            for($k=0;$k<count($arrMustType1QuestionList);$k++){
                $lastExamUserAnswerId3 = $examUserAnswerPublicData->Create($examUserPaperId,$arrMustType3QuestionList[$k]['ExamQuestionId'],$createData,$answer,$arrMustType3QuestionList[$k]['State'],$getScore);
            }

            for($m=0;$m<count($arrNonMustType1QuestionList);$m++){
                $lastExamUserAnswerId4 = $examUserAnswerPublicData->Create($examUserPaperId,$arrNonMustType1QuestionList[$m]['ExamQuestionId'],$createData,$answer,$arrNonMustType1QuestionList[$m]['State'],$getScore);
            }
            for($n=0;$n<count($arrNonMustType2QuestionList);$n++){
                $lastExamUserAnswerId5 = $examUserAnswerPublicData->Create($examUserPaperId,$arrNonMustType2QuestionList[$n]['ExamQuestionId'],$createData,$answer,$arrNonMustType2QuestionList[$n]['State'],$getScore);
            }
            for($o=0;$o<count($arrNonMustType3QuestionList);$o++){
                $lastExamUserAnswerId6 = $examUserAnswerPublicData->Create($examUserPaperId,$arrNonMustType3QuestionList[$o]['ExamQuestionId'],$createData,$answer,$arrNonMustType3QuestionList[$o]['State'],$getScore);
            }
            $templateName=Control::GetRequest("temp","");
            header("location: /default.php?mod=exam_user_answer&a=list&temp=".$templateName."&exam_question_class_id=".$examQuestionClassId."&exam_user_paper_id=" .$examUserPaperId."&question_number=1");

        }
    }

    private function GenFinished(){
        $siteId = parent::GetSiteIdByDomain();
        $defaultTemp = "exam_user_paper_score_gen";
        $temp=Control::GetRequest("temp",$defaultTemp);
        $tempContent = parent::GetDynamicTemplateContent(
            $temp, $siteId);
        return $tempContent;
    }

    private function GenErrorAnswerList(){
        $siteId = parent::GetSiteIdByDomain();
        $examUserPaperId = Control::GetRequest("exam_user_paper_id", 0);
        $defaultTemp = "exam_user_answer_error_list";
        $tagId = "answer_error_list";
        $examUserAnswerPublicData = new ExamUserAnswerPublicData();
        $tempContent = parent::GetDynamicTemplateContent(
            $defaultTemp, $siteId);
        $arrExamUserAnswerErrorList = $examUserAnswerPublicData->GetUserAnswerErrorList($examUserPaperId);
        foreach($arrExamUserAnswerErrorList as &$answer){
            $answer["Answer"]=str_ireplace("|", "", $answer["Answer"]);
            $answer["UserAnswer"]=str_ireplace("|", "", $answer["UserAnswer"]);
        }
        Template::ReplaceList($tempContent, $arrExamUserAnswerErrorList, $tagId);
        return $tempContent;
    }


    private function AsyncCountScore(){

        $result = "";
        $withCache = false;
        $examUserPaperId = Control::GetRequest("exam_user_paper_id", 0);
        $userId = Control::GetUserId();
        if ($examUserPaperId > 0) {
            $scoreAll = 0;
            $score0 = 0;
            $score1 = 0;
            $score2 = 0;
            $score3 = 0;
            $score4 = 0;
            $score5 = 0;
            $score6 = 0;
            $score7 = 0;
            $score8 = 0;
            $score9 = 0;
            $score10 = 0;
            $rightCount = 0;
            $examUserAnswerPublicData = new ExamUserAnswerPublicData();
            $examUserPaperPublicData = new ExamUserPaperPublicData();
            $examQuestionClassPublicData = new ExamQuestionClassPublicData();
            $examUserAnswerList = $examUserAnswerPublicData->GetUserAnswerList($examUserPaperId);
            for ($i = 0; $i < count($examUserAnswerList); $i++) {
                $examUserAnswerId = intval($examUserAnswerList[$i]["ExamUserAnswerId"]);
                //题型
                $examQuestionType = intval($examUserAnswerList[$i]["ExamQuestionType"]);


                $examQuestionClassId = intval($examUserAnswerList[$i]["ExamQuestionClassId"]);
                //本题分数
                $score=0;//临时加的防止下面飘红
                $scoreDefined1 = $examQuestionClassPublicData->GetTypeScore1($examQuestionClassId,$withCache);
                $scoreDefined2 = $examQuestionClassPublicData->GetTypeScore2($examQuestionClassId,$withCache);
                $scoreDefined3 = $examQuestionClassPublicData->GetTypeScore3($examQuestionClassId,$withCache);

                //正确答案
                $answer = $examUserAnswerList[$i]["Answer"];
                //会员答案
                $userAnswer = $examUserAnswerList[$i]["UserAnswer"];
                //填空题
                if ($examQuestionType == 0) {

                }
                //单选题
                else if ($examQuestionType == 1) {
                    if (strtolower($answer) == strtolower($userAnswer)) {
                        $rightCount = $rightCount+1;
                        $examUserAnswerPublicData->ModifyScore($examUserAnswerId, $scoreDefined1);
                        $score1 = $score1 + $scoreDefined1;
                    }
                }
                //多选
                else if ($examQuestionType == 2) {
                    if (strtolower($answer) == strtolower($userAnswer)) {
                        $rightCount = $rightCount+1;
                        $examUserAnswerPublicData->ModifyScore($examUserAnswerId, $scoreDefined2);
                        $score2 = $score2 + $scoreDefined2;
                    }
                } else if ($examQuestionType == 3) {
                    if (strtolower($answer) == strtolower($userAnswer)) {
                        $rightCount = $rightCount+1;
                        $examUserAnswerPublicData->ModifyScore($examUserAnswerId, $scoreDefined3);
                        $score3 = $score3 + $scoreDefined3;
                    }
                } else if ($examQuestionType == 4) {

                } else if ($examQuestionType == 5) {

                } else if ($examQuestionType == 6) {

                } else if ($examQuestionType == 7) {

                } else if ($examQuestionType == 8) {
                    if ($answer == $userAnswer) {
                        $examUserAnswerPublicData->ModifyScore($examUserAnswerId, $score);
                        $score8 = $score8 + $score;
                    }
                } else if ($examQuestionType == 9) {
                    $ArrAnswer = split($answer, "|#|");
                    $ArrUserAnswer = split($userAnswer, "|#|");
                    $isRight = true;
                    if (count($ArrAnswer) == count($ArrUserAnswer)) {
                        $k = 0;
                        while ($k < count($ArrUserAnswer) && $isRight) {
                            for ($j = 0; $j < count($ArrAnswer); $j++) {
                                if ($ArrUserAnswer[$k] == $ArrAnswer[$j]) {
                                    break;
                                }
                                if ($j == count($ArrAnswer) - 1) {
                                    $isRight = false;
                                }
                            }
                            $k++;
                        }
                        if ($isRight) {
                            $examUserAnswerPublicData->ModifyScore($examUserAnswerId, $score);
                            $score9 = $score9 + $score;
                        }
                    }
                } else if ($examQuestionType == 10) {
                    if ($answer == $userAnswer) {
                        $examUserAnswerPublicData->ModifyScore($examUserAnswerId, $score);
                        $score10 = $score10 + $score;
                    }
                }
            }
            $scoreAll = $score0 + $score1 + $score2 + $score3 + $score4 + $score5 + $score6 + $score7 + $score8 + $score9 + $score10;
            $scoreResult = $examUserPaperPublicData->ModifyScoreAndRightCount($examUserPaperId, $scoreAll,$rightCount);
            $endTimeResult = $examUserPaperPublicData->ModifyEndTime($examUserPaperId);
            if ($scoreResult > 0 && $endTimeResult > 0) {
                $result = $scoreAll;
            }
        } else {
            $result = -1;
        }

        return Control::GetRequest("jsonpcallback", "") . '({"result":"'.$result.'"})';
    }



    private function GetCsv(){
        $time=Control::GetRequest("time","");
        $userPublicData=new UserPublicData();
        $array=$userPublicData->GetTopScoreOfDay($time);
             /*
        $csv="";
        $csv.="姓名\t";
        $csv.="部门\t";
        $csv.="是否必须答题\t";
        $csv.="分数\r\n";

        foreach($array as $k=>$rs){
            $csv.=$rs["ExamUserName"]."\t";
            $csv.=$rs["UserDepartment"]."\t";
            $csv.=$rs["IsNecessary"]."\t";
            $csv.=$rs["GetScore"]."\r\n";
        }
*/


        $csv="<table><tr>";
        $csv.="<td>姓名</td>";
        $csv.="<td>部门</td>";
        $csv.="<td>是否必须答题</td>";
        $csv.="<td>分数</td>";
        $csv.="<td>正确数</td>";
        $csv.="</tr>";

        foreach($array as $k=>$rs){
            $csv.="<tr>";
            $csv.="<td>".$rs["ExamUserName"]."</td>";
            $csv.="<td>".$rs["UserDepartment"]."</td>";
            $csv.="<td>".$rs["IsNecessary"]."</td>";
            $csv.="<td>".$rs["GetScore"]."</td>";
            $csv.="<td>".$rs["RightCount"]."</td>";
            $csv.="</tr>";
        }
        $csv.="</table>";

        $name=$time.".xls";

        Header("Content-type:application/vnd.ms-excel");
        Header('Content-type: charset=UTF-8');
        Header("Content-type: application/octet-stream");
        Header("Accept-Ranges:bytes");
        Header("Content-Disposition: attachment; filename=" . $name);
        iconv("utf-8", "gb2312", $csv);
        echo $csv;


        //header("Content-type:application/vnd.ms-excel");
        //header("Content-Disposition:attachment;filename=test_data.xls");


    }
}