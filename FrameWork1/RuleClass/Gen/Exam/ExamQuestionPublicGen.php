<?php
/**
 * Created by PhpStorm.
 * User: xzz
 * Date: 15/6/18
 * Time: 下午2:48
 */
class ExamQuestionPublicGen extends BasePublicGen implements IBasePublicGen{

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenPublic()
    {
        $result = "";
        $method = Control::GetRequest("m", "");
        switch ($method) {

            case "list":
                $result = self::GenList();
                break;
        }

        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    private function GenList(){
        $result = -1;
        $userId = 1;
        $userName = Control::GetUserName();
        $examQuestionClassId = Control::GetRequest("exam_question_class_id", 0);
        $pageSize = Control::GetRequest("ps", 30);
        $pageIndex = Control::GetRequest("p", 1);
        $tagId1 = "exam_must_question_list";
        $tagId2 = "exam_non_must_question_list";

        $examQuestionPublicData = new ExamQuestionPublicData();
        $examUserPaperData = new ExamUserPaperData();
        $examUserAnswerData = new ExamUserAnswerData();

        $mustSelect = 1;
        $withCache = false;
        $mustCount = $examQuestionPublicData->GetMustSelectType1Count($examQuestionClassId,$withCache);
        $arrMustQuestionList = $examQuestionPublicData->GetQuestionList($mustSelect, $mustCount);


        $mustSelect = 0;
        $nonCount = $examQuestionPublicData->GetNonMustSelectType1Count($examQuestionClassId,$withCache);
        $arrNonMustQuestionList = $examQuestionPublicData->GetQuestionList($mustSelect, $nonCount);

        $beginTime = "";
        $endTime = "";
        $getScore = 0;
        $answer = "";
        $examUserPaperId = $examUserPaperData->Create($userId,$beginTime,$endTime,$getScore);
        $createData = date("Y-m-d H:i:s", time());
        $lastExamUserAnswerId1 = 0;
        $lastExamUserAnswerId2 = 0;
        if($examUserPaperId > 0){

            for($i=0;$i<count($arrMustQuestionList);$i++){
                $lastExamUserAnswerId1 = $examUserAnswerData->Create($examUserPaperId,$arrMustQuestionList[$i]['ExamQuestionId'],$createData,$answer,$arrMustQuestionList[$i]['State'],$getScore);
            }
            for($j=0;$j<count($arrNonMustQuestionList);$j++){
                $lastExamUserAnswerId2 = $examUserAnswerData->Create($examUserPaperId,$arrNonMustQuestionList[$j]['ExamQuestionId'],$createData,$arrNonMustQuestionList[$j]['Answer'],$arrNonMustQuestionList[$j]['State'],$getScore);
            }
            if($lastExamUserAnswerId1 > 0 || $lastExamUserAnswerId2 > 0){

                $templateFileUrl = "exam/exam_question_list.html";
                $templateName = "default";
                $templatePath = "front_template";
                $tempContent = Template::Load($templateFileUrl, $templateName, $templatePath);

                Template::ReplaceList($tempContent, $arrMustQuestionList, $tagId1);
                Template::ReplaceList($tempContent, $arrNonMustQuestionList, $tagId2);
            }
        }

        return $tempContent;
    }
}