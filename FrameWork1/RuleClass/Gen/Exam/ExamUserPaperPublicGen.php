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
        }

        $result = str_ireplace("{action}", $action, $result);
        return $result;
    }

    private function GenExamUserPaper(){
        $userId = Control::GetUserId();
        $examQuestionClassId = Control::GetRequest("exam_question_class_id", 0);

        if($examQuestionClassId<=0){
            return "exam question class is null";
        }


        if($userId<=0){
            Control::GoUrl("/default.php?mod=user&a=login&re_url=". urlencode("/default.php?mod=exam_user_paper&a=gen&exam_question_class_id=".$examQuestionClassId));
            return "";
        }

        $examQuestionClassId = Control::GetRequest("exam_question_class_id", 0);

        $examQuestionClassPublicData = new ExamQuestionClassPublicData();
        $examUserPaperPublicData = new ExamUserPaperPublicData();
        $examQuestionPublicData = new ExamQuestionPublicData();
        $examUserAnswerPublicData = new ExamUserAnswerPublicData();

        $mustSelect = 1;
        $withCache = false;
        $mustCount = $examQuestionClassPublicData->GetMustSelectType1Count($examQuestionClassId,$withCache);
        $arrMustQuestionList = $examQuestionPublicData->GetList($mustSelect, $mustCount);


        $mustSelect = 0;
        $nonCount = $examQuestionClassPublicData->GetNonMustSelectType1Count($examQuestionClassId,$withCache);
        $arrNonMustQuestionList = $examQuestionPublicData->GetList($mustSelect, $nonCount);

        $beginTime = date("Y-m-d H:i:s", time());
        $endTime = "";
        $getScore = 0;
        $answer = "";
        $examUserPaperId = $examUserPaperPublicData->Create($userId,$beginTime,$endTime,$getScore);
        $createData = date("Y-m-d H:i:s", time());
        $lastExamUserAnswerId1 = 0;
        $lastExamUserAnswerId2 = 0;

        if($examUserPaperId > 0){

            for($i=0;$i<count($arrMustQuestionList);$i++){
                $lastExamUserAnswerId1 = $examUserAnswerPublicData->Create($examUserPaperId,$arrMustQuestionList[$i]['ExamQuestionId'],$createData,$answer,$arrMustQuestionList[$i]['State'],$getScore);
            }
            for($j=0;$j<count($arrNonMustQuestionList);$j++){
                $lastExamUserAnswerId2 = $examUserAnswerPublicData->Create($examUserPaperId,$arrNonMustQuestionList[$j]['ExamQuestionId'],$createData,$answer,$arrNonMustQuestionList[$j]['State'],$getScore);
            }
            if($lastExamUserAnswerId1 > 0 || $lastExamUserAnswerId2 > 0){

            }
            header("location: /default.php?mod=exam_user_answer&a=list&exam_user_paper_id=" .$examUserPaperId."");

        }
    }

    private function GenFinished(){
        $siteId = parent::GetSiteIdByDomain();
        $defaultTemp = "exam_user_paper_score_gen";
        $tempContent = parent::GetDynamicTemplateContent(
            $defaultTemp, $siteId);
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
                $score = $examQuestionClassPublicData->GetTypeScore1($examQuestionClassId,$withCache);

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

                        $examUserAnswerPublicData->ModifyScore($examUserAnswerId, $score);
                        $score1 = $score1 + $score;
                    }
                }
                //多选
                else if ($examQuestionType == 2) {
                    if (strtolower($answer) == strtolower($userAnswer)) {
                        $examUserAnswerPublicData->ModifyScore($examUserAnswerId, $score);
                        $score2 = $score2 + $score;
                    }
                } else if ($examQuestionType == 3) {

                    if (strpos($answer, $userAnswer) != false) {
                        $examUserAnswerPublicData->ModifyScore($examUserAnswerId, $score);
                        $score3 = $score3 + $score;
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
            $scoreResult = $examUserPaperPublicData->ModifyScore($examUserPaperId, $scoreAll);
            $endTimeResult = $examUserPaperPublicData->ModifyEndTime($examUserPaperId);
            if ($scoreResult > 0 && $endTimeResult > 0) {
                $result = $scoreAll;
            }
        } else {
            $result = -1;
        }

        return Control::GetRequest("jsonpcallback", "") . '({"result":"'.$result.'"})';
    }

}