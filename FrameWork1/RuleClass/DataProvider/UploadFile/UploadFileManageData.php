<?php
/**
 * 后台管理 上传文件 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_UploadFile
 * @author zhangchi
 */
class UploadFileManageData extends BaseManageData {

    /**
     * 修改是否已经批量处理
     * @param int $uploadFileId
     * @param int $isBatchOperate
     * @return int
     */
    public function ModifyIsBatchOperate($uploadFileId, $isBatchOperate)
    {
        if ($uploadFileId > 0) {
            $sql = "UPDATE " . self::TableName_UploadFile . "
                        SET IsBatchOperate=:IsBatchOperate
                        WHERE UploadFileId=:UploadFileId;";

            $dataProperty = new DataProperty();
            $dataProperty->AddField("IsBatchOperate", $isBatchOperate);
            $dataProperty->AddField("UploadFileId", $uploadFileId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
            return $result;
        } else {
            return -1;
        }
    }

    /**
     * 根据TableType取得所有未批量操作的记录
     * @param int $tableType
     * @param int $topCount
     * @return array|null 所有未批量操作的记录
     */
    public function GetListOfIsNotBatchOperate($tableType, $topCount = 10){
        $result = null;
        if ($tableType > 0) {

            $sql = "SELECT * FROM " . self::TableName_UploadFile . "
                    WHERE TableType = :TableType AND IsBatchOperate = 0 ORDER BY UploadFileId DESC LIMIT $topCount ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("TableType", $tableType);

            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

        }
        return $result;
    }

}