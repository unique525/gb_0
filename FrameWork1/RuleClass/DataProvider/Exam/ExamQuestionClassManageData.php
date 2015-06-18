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
     * 修改版块
     * @param array $httpPostData $_POST数组
     * @param int $forumId 版块id
     * @return int 返回影响的行数
     */
    public function Modify($httpPostData, $forumId) {
        $dataProperty = new DataProperty();
        $addFieldNames = array();
        $addFieldValues = array();
        $sql = parent::GetUpdateSql($httpPostData, self::TableName_ExamQuestionClass, self::TableId_ExamQuestionClass, $forumId, $dataProperty, "", "", "", $addFieldNames, $addFieldValues);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 修改版块状态
     * @param int $forumId 论坛版块id
     * @param int $state 状态
     * @return int 操作结果
     */
    public function ModifyState($forumId, $state) {
        $result = 0;
        if ($forumId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_ExamQuestionClass . " SET `State`=:State WHERE ExamQuestionClassId=:ExamQuestionClassId;";
            $dataProperty->AddField("ExamQuestionClassId", $forumId);
            $dataProperty->AddField("State", $state);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 返回一行数据
     * @param int $forumId 论坛id
     * @return array|null 取得对应数组
     */
    public function GetOne($forumId)
    {
        $result = null;
        if ($forumId > 0) {
            $sql = "SELECT * FROM " . self::TableName_ExamQuestionClass . " WHERE ExamQuestionClassId=:ExamQuestionClassId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ExamQuestionClassId", $forumId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
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
     * 根据版块等级取得版块列表
     * @param int $siteId 站点id
     * @param int $rank 版块等级
     * @return array 版块列表
     */
    public function GetListByRank($siteId, $rank) {
        $result = null;
        if($siteId>0 && $rank>=0){
            $sql = "SELECT * FROM " . self::TableName_ExamQuestionClass . "
                    WHERE Rank=:Rank AND SiteId=:SiteId ORDER BY Sort DESC;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("Rank", $rank);
            $dataProperty->AddField("SiteId", $siteId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }

} 