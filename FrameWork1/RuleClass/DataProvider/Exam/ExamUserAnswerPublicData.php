<?php
/**
 * Created by PhpStorm.
 * User: xzz
 * Date: 15/6/18
 * Time: 下午2:48
 */
class ExamUserAnswerPublicData extends BasePublicData{

    public function GetList($examUserPaperId)
    {
        $result = null;
        if($examUserPaperId > 0){

            $dataProperty = new DataProperty();

            $sql = "SELECT eq.*,eua.ExamUserAnswerId FROM ".self::TableName_ExamUserAnswer." eua,".self::TableName_ExamQuestion." eq WHERE
                    ExamUserPaperId=:ExamUserPaperId AND eua.ExamQuestionId=eq.ExamQuestionId";
            $dataProperty->AddField("ExamUserPaperId", $examUserPaperId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }
    public function Create($examUserPaperId,$examQuestionId,$createDate,$answer,$state,$getScore){
        $result = 0;

        if($examUserPaperId > 0){

            $sql = "INSERT INTO " . self::TableName_ExamUserAnswer . "
                    (
                    ExamUserPaperId,
                    ExamQuestionId,
                    CreateDate,
                    Answer,
                    State,
                    GetScore
                    )
                    VALUES
                    (
                    :ExamUserPaperId,
                    :ExamQuestionId,
                    :CreateDate,
                    :Answer,
                    :State,
                    :GetScore
                    );";

            $dataProperty = new DataProperty();
            $dataProperty->AddField("ExamUserPaperId", $examUserPaperId);
            $dataProperty->AddField("ExamQuestionId", $examQuestionId);
            $dataProperty->AddField("CreateDate", $createDate);
            $dataProperty->AddField("Answer", $answer);
            $dataProperty->AddField("State", $state);
            $dataProperty->AddField("GetScore", $getScore);
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);

        }

        return $result;
    }

    public function  ModifyAnswer($examUserAnswerId,$answer){
        $dataProperty = new DataProperty();
        $sql = "UPDATE ".self::TableName_ExamUserAnswer." SET Answer=:Answer WHERE ExamUserAnswerId=:ExamUserAnswerId";
        $dataProperty->AddField("ExamUserAnswerId", $examUserAnswerId);
        $dataProperty->AddField("Answer", $answer);
        $this->dbOperator->Execute($sql, $dataProperty);
    }

    public function ModifyScore($examUserAnswerID, $thisScore) {
        $sql = "update " . self::TableName_ExamUserAnswer . " set GetScore= :Score where " . self::TableId_ExamUserPaper . "=:ExamUserAnswerID";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("Score", $thisScore);
        $dataProperty->AddField("ExamUserAnswerID", $examUserAnswerID);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    public function GetUserAnswerList($examUserPaperId){
        $sql = "SELECT q.Answer,q.ExamQuestionType,q.Score,a.ExamUserAnswerId,a.Answer as UserAnswer FROM ".self::TableName_ExamQuestion." q,".self::TableName_ExamUserAnswer." a WHERE a.ExamQuestionId = q.ExamQuestionId AND a.ExamUserPaperId = :ExamUserPaperId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("ExamUserPaperId", $examUserPaperId);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->ReturnArray($sql,$dataProperty);
        return $result;
    }
}