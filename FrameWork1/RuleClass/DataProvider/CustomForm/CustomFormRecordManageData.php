<?php

/**
 * 后台管理 活动表单记录 数据类
 *
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Customform
 * @author 525
 */
class CustomFormRecordManageData extends BaseManageData {
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
     * 修改
     * @param array $httpPostData $_post数组
     * @param int $customFormRecordId 表单记录id
     * @return int 执行结果
     */
    public function Modify($httpPostData,$customFormRecordId) {
        $result=-1;
        if(!empty($httpPostData)){
            $dataProperty = new DataProperty();
            $sql = parent::GetUpdateSql($httpPostData,self::TableName_CustomFormRecord, self::TableId_CustomFormRecord, $customFormRecordId, $dataProperty);
           $result = $this->dbOperator->Execute($sql, $dataProperty);
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
        $dataProperty = new DataProperty();
        $dataProperty->AddField("CustomFormId", $customFormId);
        $sql = "SELECT * FROM " . self::TableName_CustomFormRecord . " WHERE CustomFormId=:CustomFormId ORDER BY Sort DESC,CreateDate DESC LIMIT " . $pageBegin . "," . $pageSize . " ;";
        $result = $this->dbOperator->ReturnArray($sql, $dataProperty);

        $sqlCount = "SELECT count(*) FROM ".self::TableName_CustomFormRecord." WHERE CustomFormId=:CustomFormId ;";
        $allCount = $this->dbOperator->ReturnInt($sqlCount, $dataProperty);

        return $result;
    }


    /**
     * 通过ID获取一条记录
     * @param int $customFormRecordId
     * @return array 取得的表单记录
     */
    public function GetOne($customFormRecordId) {

        $sql = "SELECT * FROM " . self::TableName_CustomFormRecord . " WHERE CustomFormRecordID = :CustomFormRecordID ;";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("CustomFormRecordID", $customFormRecordId);
        $result = $this->dbOperator->ReturnRow($sql, $dataProperty);
        return $result;
    }
}

?>
