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
        $userId = 1;
        $siteId = parent::GetSiteIdByDomain();
        $userName = Control::GetUserName();
        $examUserPaperId = Control::GetRequest("exam_user_paper_id", 0);
        $tagId = "exam_user_answer_list";
        $examUserAnswerPublicData = new ExamUserAnswerPublicData();

        $arrUserAnswerList = $examUserAnswerPublicData->GetList($examUserPaperId);
        //$templateFileUrl = "exam/exam_user_answer_list.html";
        //$templateName = "default";
        //$templatePath = "front_template";
        //$tempContent = Template::Load($templateFileUrl, $templateName, $templatePath);

        $templateMode = 0;
        $defaultTemp = "exam_user_answer_list";
        $tempContent = parent::GetDynamicTemplateContent(
        $defaultTemp, $siteId, "", $templateMode);

        Template::ReplaceList($tempContent, $arrUserAnswerList, $tagId);
        return $tempContent;
    }

    private function AsyncSubAnswer(){
        $examUserAnswerId = Control::GetRequest("exam_user_answer_id", 0);
        $answerValue = Control::GetRequest("answer_value", 0);

        $examUserAnswerPublicData = new ExamUserAnswerPublicData();

        $examUserAnswerPublicData->ModifyAnswer($examUserAnswerId,$answerValue);
    }
}