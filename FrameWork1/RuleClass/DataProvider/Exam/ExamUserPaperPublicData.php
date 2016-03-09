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
        if($userId >= 0){

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

    public function ModifyScoreAndRightCount($examUserPaperId, $getScore,$rightCount) {
        $sql = "update " . self::TableName_ExamUserPaper . " set GetScore= :GetScore,RightCount= :RightCount where " . self::TableId_ExamUserPaper . "=:ExamUserPaperId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("GetScore", $getScore);
        $dataProperty->AddField("ExamUserPaperId", $examUserPaperId);
        $dataProperty->AddField("RightCount", $rightCount);
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


    /**
     * 取得分数
     * @param int $userId 用户id
     * @param int $examUserPaperId 试题分类id
     * @param bool $withCache 是否从缓冲中取
     * @return int 单选的非必选题抽取数量
     */
    public function GetScore($userId, $examUserPaperId, $withCache)
    {
        $result = -1;
        if ($examUserPaperId > 0 && $userId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'exam_user_paper_data';
            $cacheFile = 'exam_user_paper_get_score.cache_' . $examUserPaperId . '';
            $sql = "SELECT GetScore FROM " . self::TableName_ExamUserPaper . " WHERE ExamUserPaperId=:ExamUserPaperId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ExamUserPaperId", $examUserPaperId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }


    /**
     * 取得日期与用户
     * @param int $examUserPaperId 试题分类id
     * @param bool $withCache 是否从缓冲中取
     * @return array 日期
     */
    public function GetTime($examUserPaperId, $withCache)
    {
        $result = array();
        if ($examUserPaperId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'exam_user_paper_data';
            $cacheFile = 'exam_user_paper_get_date.cache_' . $examUserPaperId . '';
            $sql = "SELECT BeginTime,EndTime,UserId FROM " . self::TableName_ExamUserPaper . " WHERE ExamUserPaperId=:ExamUserPaperId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ExamUserPaperId", $examUserPaperId);
            $result = $this->GetInfoOfArray($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }
}