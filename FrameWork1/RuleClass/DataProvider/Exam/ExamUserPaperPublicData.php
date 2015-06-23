<?php
/**
 * Created by PhpStorm.
 * User: xzz
 * Date: 15/6/20
 * Time: 下午3:27
 */
class ExamUserPaperPublicData extends BasePublicData{

    public function Create($userId,$beginTime,$endTime,$getScore){
        $result = 0;
        if($userId > 0){

            $sql = "INSERT INTO " . self::TableName_ExamUserPaper . "
                    (
                    UserId,
                    BeginTime,
                    EndTime,
                    GetScore
                    )
                    VALUES
                    (
                    :UserId,
                    :BeginTime,
                    :EndTime,
                    :GetScore
                    );";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId", $userId);
            $dataProperty->AddField("BeginTime", $beginTime);
            $dataProperty->AddField("EndTime", $endTime);
            $dataProperty->AddField("GetScore", $getScore);
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);

        }

        return $result;
    }

    public function ModifyScore($examUserPaperId, $getScore) {
        $sql = "update " . self::TableName_ExamUserPaper . " set GetScore= :GetScore where " . self::TableId_ExamUserPaper . "=:ExamUserPaperId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("GetScore", $getScore);
        $dataProperty->AddField("ExamUserPaperId", $examUserPaperId);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    public function ModifyEndTime($examUserPaperId){
        $sql = "UPDATE ".self::TableName_ExamUserPaper." SET EndTime = now() WHERE ExamUserPaperId = :ExamUserPaperId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("ExamUserPaperId", $examUserPaperId);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

}