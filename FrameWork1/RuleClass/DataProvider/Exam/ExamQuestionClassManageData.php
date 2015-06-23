<?php
/**
 * 后台管理 试题分类 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Exam
 * @author zhangchi
 */
class ExamQuestionClassManageData extends BaseManageData {
    /**
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_ExamQuestionClass){
        return parent::GetFields(self::TableName_ExamQuestionClass);
    }

    /**
     * 新增论坛版块
     * @param array $httpPostData $_POST数组
     * @return int 新增的论坛版块id
     */
    public function Create($httpPostData) {
        $result = -1;
        $dataProperty = new DataProperty();
        $addFieldName = "";
        $addFieldValue = "";
        $preNumber = "";
        $addFieldNames = array();
        $addFieldValues = array();
        if (!empty($httpPostData)) {
            $sql = parent::GetInsertSql(
                $httpPostData,
                self::TableName_ExamQuestionClass,
                $dataProperty,
                $addFieldName,
                $addFieldValue,
                $preNumber,
                $addFieldNames,
                $addFieldValues
            );
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 修改
     * @param array $httpPostData $_POST数组
     * @param int $examQuestionClassId 分类id
     * @return int 返回影响的行数
     */
    public function Modify($httpPostData, $examQuestionClassId) {
        $dataProperty = new DataProperty();
        $addFieldNames = array();
        $addFieldValues = array();
        $sql = parent::GetUpdateSql($httpPostData, self::TableName_ExamQuestionClass, self::TableId_ExamQuestionClass, $examQuestionClassId, $dataProperty, "", "", "", $addFieldNames, $addFieldValues);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 修改状态
     * @param int $examQuestionClassId 分类id
     * @param int $state 状态
     * @return int 操作结果
     */
    public function ModifyState($examQuestionClassId, $state) {
        $result = 0;
        if ($examQuestionClassId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_ExamQuestionClass . "
                    SET `State`=:State
                    WHERE ExamQuestionClassId=:ExamQuestionClassId;";
            $dataProperty->AddField("ExamQuestionClassId", $examQuestionClassId);
            $dataProperty->AddField("State", $state);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 返回一行数据
     * @param int $examQuestionClassId 分类id
     * @return array|null 取得对应数组
     */
    public function GetOne($examQuestionClassId)
    {
        $result = null;
        if ($examQuestionClassId > 0) {
            $sql = "SELECT *
                    FROM " . self::TableName_ExamQuestionClass . "
                    WHERE ExamQuestionClassId=:ExamQuestionClassId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ExamQuestionClassId", $examQuestionClassId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 取得分类名称
     * @param int $examQuestionClassId 分类id
     * @param bool $withCache 是否从缓冲中取
     * @return string 分类名称
     */
    public function GetExamQuestionClassName($examQuestionClassId, $withCache)
    {
        $result = "";
        if ($examQuestionClassId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'exam_question_class_data';
            $cacheFile = 'exam_question_class_get_exam_question_class_name.cache_' . $examQuestionClassId . '';
            $sql = "SELECT ExamQuestionClassName FROM " . self::TableName_ExamQuestionClass . " WHERE ExamQuestionClassId=:ExamQuestionClassId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ExamQuestionClassId", $examQuestionClassId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 取得所属站点id
     * @param int $examQuestionClassId 论坛id
     * @param bool $withCache 是否从缓冲中取
     * @return int 站点id
     */
    public function GetSiteId($examQuestionClassId, $withCache)
    {
        $result = -1;
        if ($examQuestionClassId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'exam_question_class_data';
            $cacheFile = 'exam_question_class_get_site_id.cache_' . $examQuestionClassId . '';
            $sql = "SELECT SiteId FROM " . self::TableName_ExamQuestionClass . " WHERE ExamQuestionClassId=:ExamQuestionClassId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ExamQuestionClassId", $examQuestionClassId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }


    /**
     * 根据等级取得列表
     * @param int $channelId 频道id
     * @param int $rank 等级
     * @return array 列表
     */
    public function GetListByRank($channelId, $rank) {
        $result = null;
        if($channelId>0 && $rank>=0){
            $sql = "SELECT * FROM " . self::TableName_ExamQuestionClass . "
                    WHERE Rank=:Rank AND ChannelId=:ChannelId ORDER BY Sort DESC;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("Rank", $rank);
            $dataProperty->AddField("ChannelId", $channelId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }

} 