<?php
/**
 * Created by PhpStorm.
 * User: xzz
 * Date: 15/6/18
 * Time: 下午2:48
 */
class ExamUserAnswerPublicGen extends BasePublicGen implements IBasePublicGen{

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenPublic()
    {
        $result = "";
        $method = Control::GetRequest("a", "");
        switch ($method) {

            case "list":
                $result = self::GenList();
                break;
            case "async_sub_answer":
                self::AsyncSubAnswer();
        }

        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    private function GenList(){
        $result = -1;
        $userId = Control::GetUserId();
        $siteId = parent::GetSiteIdByDomain();
        $examUserPaperId = Control::GetRequest("exam_user_paper_id", 0);
        $examQuestionClassId = Control::GetRequest("exam_question_class_id", 0);
        $defaultTemp = "exam_user_answer_list";
        $temp=Control::GetRequest("temp", $defaultTemp);

        /** 检查该试卷是否完成（防止回来改答案满分）**/
        $examUserPaperPublicData = new ExamUserPaperPublicData();
        $timeArray=$examUserPaperPublicData->GetTime($examUserPaperId,false);
        if($timeArray["BeginTime"]<$timeArray["EndTime"]){
            //Control::GoUrl("default.php?mod=exam_user_paper&a=gen&temp=$temp&exam_question_class_id=".$examQuestionClassId);
            //return "";
        }





        $number = Control::GetRequest("question_number", "");
        if($number!=""){
            $number=($number-1).",1";
        }

        $tagId = "exam_user_answer_list";
        $examUserAnswerPublicData = new ExamUserAnswerPublicData();

        $arrUserAnswerList = $examUserAnswerPublicData->GetList($examUserPaperId,$number);

        $templateMode = 0;
        $tempContent = parent::GetDynamicTemplateContent(
            $temp, $siteId, "", $templateMode);

        Template::ReplaceList($tempContent, $arrUserAnswerList, $tagId);
        return $tempContent;
    }

    private function AsyncSubAnswer(){
        $examUserAnswerId = Control::GetRequest("exam_user_answer_id", 0);
        $answerValue = Control::GetRequest("answer_value", "");

        //TODO 加上是否本人试卷检查

        $examUserAnswerPublicData = new ExamUserAnswerPublicData();

        if ($examUserAnswerId>0){

            $examUserAnswerPublicData->ModifyAnswer($examUserAnswerId,$answerValue);
        }
    }
}