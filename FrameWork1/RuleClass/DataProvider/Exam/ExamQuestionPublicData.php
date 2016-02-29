<?php
/**
 * Created by PhpStorm.
 * User: xzz
 * Date: 15/6/18
 * Time: 下午2:48
 */
class ExamQuestionPublicData extends BasePublicData{

    public function GetList($examQuestionClassId,$mustSelect,$count,$examQuestionType)
    {
        $result = null;
        if($count > 0){

            $dataProperty = new DataProperty();

            $sql = "SELECT * FROM " . self::TableName_ExamQuestion . " WHERE ExamQuestionClassId=:ExamQuestionClassId AND ExamQuestionType=:ExamQuestionType AND MustSelect=:MustSelect ORDER BY rand() LIMIT " . $count . "";
            $dataProperty->AddField("MustSelect", $mustSelect);
            $dataProperty->AddField("ExamQuestionClassId", $examQuestionClassId);
            $dataProperty->AddField("ExamQuestionType", $examQuestionType);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }
}