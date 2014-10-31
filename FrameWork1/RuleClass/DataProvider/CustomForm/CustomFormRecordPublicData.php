<?php

/**
 * 前台 活动表单记录 数据类
 *
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Customform
 * @author 525
 */
class CustomFormRecordPublicData extends BaseManageData {
    /**
     * 新增一条记录
     * @param array $httpPostData $_post数组
     * @return int 新增表单记录id
     */
    public function Create($httpPostData) {
        $result=-1;
        if(!empty($httpPostData)){
            $dataProperty = new DataProperty();
            $sql = parent::GetInsertSql($httpPostData,self::TableName_CustomFormRecord, $dataProperty);
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        }
        return $result;
    }



    /**
     * 获取表单记录分页列表
     * @param int $customFormId 表单id
     * @param int $pageBegin 起始页码
     * @param int $pageSize 每页大小
     * @param int $allCount 总大小
     * @return array 表单记录数据集
     */
    public function GetListPager($customFormId, $pageBegin, $pageSize, &$allCount) {
        $result=-1;
        if($customFormId>0){
            $dataProperty = new DataProperty();
            $dataProperty->AddField("CustomFormId", $customFormId);
            $sql = "SELECT * FROM " . self::TableName_CustomFormRecord . " WHERE CustomFormId=:CustomFormId ORDER BY Sort DESC,CreateDate DESC LIMIT " . $pageBegin . "," . $pageSize . " ;";
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

            $sqlCount = "SELECT count(*) FROM ".self::TableName_CustomFormRecord." WHERE CustomFormId=:CustomFormId ;";
            $allCount = $this->dbOperator->GetInt($sqlCount, $dataProperty);
        }
        return $result;
    }



}

?>
