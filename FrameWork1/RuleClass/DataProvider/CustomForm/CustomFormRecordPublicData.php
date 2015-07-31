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
     * @param $customFormId
     * @param $userId
     * @param $createDate
     * @return int
     */
    public function Create($customFormId,$userId,$createDate) {
        $result=-1;
        if($customFormId>0){
            $dataProperty = new DataProperty();
            $dataProperty->AddField("CustomFormId", $customFormId);
            $dataProperty->AddField("UserId", $userId);
            $dataProperty->AddField("CreateDate", $createDate);
            $sql = "INSERT INTO ".self::TableName_CustomFormRecord." (CustomFormId,UserId,CreateDate) VALUES(:CustomFormId,:UserId,:CreateDate)";
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

    /**
     * 获取表单记录的计数
     * @param $customFormId
     * @return int
     */
    public function GetCount($customFormId){
        $result=0;
        if($customFormId>0){
            $dataProperty=new DataProperty();
            $dataProperty->AddField("CustomFormId", $customFormId);
            $sql = "SELECT COUNT(*) FROM " . self::TableName_CustomFormRecord . " WHERE CustomFormId=:CustomFormId ;";
            $result = $this->dbOperator->GetInt($sql, $dataProperty);
        }
        return $result;
    }


}

?>
