<?php
/**
 * Created by PhpStorm.
 * User: xzz
 * Date: 15/6/18
 * Time: 下午2:48
 */
class ExamQuestionClassPublicData extends BasePublicData{

    /**
     * 取得单选的必选题抽取数量
     * @param int $examQuestionClassId 试题分类id
     * @param bool $withCache 是否从缓冲中取
     * @return int 单选的必选题抽取数量
     */
    public function GetMustSelectType1Count($examQuestionClassId, $withCache)
    {
        $result = -1;
        if ($examQuestionClassId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'exam_question_class_data';
            $cacheFile = 'exam_question_class_get_must_select_type1_count.cache_' . $examQuestionClassId . '';
            $sql = "SELECT MustSelectType1Count FROM " . self::TableName_ExamQuestionClass . " WHERE ExamQuestionClassId=:ExamQuestionClassId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ExamQuestionClassId", $examQuestionClassId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 取得单选的非必选题抽取数量
     * @param int $examQuestionClassId 试题分类id
     * @param bool $withCache 是否从缓冲中取
     * @return int 单选的非必选题抽取数量
     */
    public function GetNonMustSelectType1Count($examQuestionClassId, $withCache)
    {
        $result = -1;
        if ($examQuestionClassId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'exam_question_class_data';
            $cacheFile = 'exam_question_class_get_select_type1_count.cache_' . $examQuestionClassId . '';
            $sql = "SELECT SelectType1Count FROM " . self::TableName_ExamQuestionClass . " WHERE ExamQuestionClassId=:ExamQuestionClassId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ExamQuestionClassId", $examQuestionClassId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 取得多选的必选题抽取数量
     * @param int $examQuestionClassId 试题分类id
     * @param bool $withCache 是否从缓冲中取
     * @return int 多选的必选题抽取数量
     */
    public function GetMustSelectType2Count($examQuestionClassId, $withCache)
    {
        $result = -1;
        if ($examQuestionClassId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'exam_question_class_data';
            $cacheFile = 'exam_question_class_get_must_select_type2_count.cache_' . $examQuestionClassId . '';
            $sql = "SELECT MustSelectType2Count FROM " . self::TableName_ExamQuestionClass . " WHERE ExamQuestionClassId=:ExamQuestionClassId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ExamQuestionClassId", $examQuestionClassId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 取得多选的非必选题抽取数量
     * @param int $examQuestionClassId 试题分类id
     * @param bool $withCache 是否从缓冲中取
     * @return int 单选的非必选题抽取数量
     */
    public function GetNonMustSelectType2Count($examQuestionClassId, $withCache)
    {
        $result = -1;
        if ($examQuestionClassId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'exam_question_class_data';
            $cacheFile = 'exam_question_class_get_select_type2_count.cache_' . $examQuestionClassId . '';
            $sql = "SELECT SelectType2Count FROM " . self::TableName_ExamQuestionClass . " WHERE ExamQuestionClassId=:ExamQuestionClassId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ExamQuestionClassId", $examQuestionClassId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 取得判断的必选题抽取数量
     * @param int $examQuestionClassId 试题分类id
     * @param bool $withCache 是否从缓冲中取
     * @return int 判断的必选题抽取数量
     */
    public function GetMustSelectType3Count($examQuestionClassId, $withCache)
    {
        $result = -1;
        if ($examQuestionClassId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'exam_question_class_data';
            $cacheFile = 'exam_question_class_get_must_select_type3_count.cache_' . $examQuestionClassId . '';
            $sql = "SELECT MustSelectType3Count FROM " . self::TableName_ExamQuestionClass . " WHERE ExamQuestionClassId=:ExamQuestionClassId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ExamQuestionClassId", $examQuestionClassId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 取得判断的非必选题抽取数量
     * @param int $examQuestionClassId 试题分类id
     * @param bool $withCache 是否从缓冲中取
     * @return int 判断的非必选题抽取数量
     */
    public function GetNonMustSelectType3Count($examQuestionClassId, $withCache)
    {
        $result = -1;
        if ($examQuestionClassId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'exam_question_class_data';
            $cacheFile = 'exam_question_class_get_select_type3_count.cache_' . $examQuestionClassId . '';
            $sql = "SELECT SelectType3Count FROM " . self::TableName_ExamQuestionClass . " WHERE ExamQuestionClassId=:ExamQuestionClassId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ExamQuestionClassId", $examQuestionClassId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    public function GetTypeScore1($examQuestionClassId, $withCache)
    {
        $result = -1;
        if ($examQuestionClassId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'exam_question_class_data';
            $cacheFile = 'exam_question_class_get_type_score_1_count.cache_' . $examQuestionClassId . '';
            $sql = "SELECT TypeScore1 FROM " . self::TableName_ExamQuestionClass . " WHERE ExamQuestionClassId=:ExamQuestionClassId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ExamQuestionClassId", $examQuestionClassId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }


    public function GetTypeScore2($examQuestionClassId, $withCache)
    {
        $result = -1;
        if ($examQuestionClassId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'exam_question_class_data';
            $cacheFile = 'exam_question_class_get_type_score_2_count.cache_' . $examQuestionClassId . '';
            $sql = "SELECT TypeScore2 FROM " . self::TableName_ExamQuestionClass . " WHERE ExamQuestionClassId=:ExamQuestionClassId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ExamQuestionClassId", $examQuestionClassId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }


    public function GetTypeScore3($examQuestionClassId, $withCache)
    {
        $result = -1;
        if ($examQuestionClassId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'exam_question_class_data';
            $cacheFile = 'exam_question_class_get_type_score_3_count.cache_' . $examQuestionClassId . '';
            $sql = "SELECT TypeScore3 FROM " . self::TableName_ExamQuestionClass . " WHERE ExamQuestionClassId=:ExamQuestionClassId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ExamQuestionClassId", $examQuestionClassId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }
}