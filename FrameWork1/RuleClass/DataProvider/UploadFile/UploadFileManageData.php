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
     * 取得tableType
     * @param int $uploadFileId 上传文件id
     * @param bool $withCache 是否从缓冲中取
     * @return string tableId
     */
    public function GetTableType($uploadFileId, $withCache)
    {
        $result = "";
        if ($uploadFileId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'upload_file_data';
            $cacheFile = 'upload_file_get_table_type.cache_' . $uploadFileId . '';
            $sql = "SELECT TableType
                    FROM " . self::TableName_UploadFile . "
                    WHERE UploadFileId=:UploadFileId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UploadFileId", $uploadFileId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }

        return $result;
    }

    /**
     * 取得tableId
     * @param int $uploadFileId 上传文件id
     * @param bool $withCache 是否从缓冲中取
     * @return string tableId
     */
    public function GetTableId($uploadFileId, $withCache)
    {
        $result = "";
        if ($uploadFileId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'upload_file_data';
            $cacheFile = 'upload_file_get_table_id.cache_' . $uploadFileId . '';
            $sql = "SELECT TableId
                    FROM " . self::TableName_UploadFile . "
                    WHERE UploadFileId=:UploadFileId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UploadFileId", $uploadFileId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }

        return $result;
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

    /**
     * 根据TableId,TableType取得记录
     * @param int $tableId
     * @param int $tableType
     * @return array|null id数组数据集
     */
    public function GetListByTableId($tableId,$tableType){
        $result = null;
        if ($tableId>0 && $tableType > 0) {

            $sql = "SELECT * FROM " . self::TableName_UploadFile . "
                    WHERE TableType = :TableType AND TableId=:TableId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("TableId", $tableId);
            $dataProperty->AddField("TableType", $tableType);

            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

        }
        return $result;
    }

    /**
     * 根据id取得记录
     * @param string $strUploadFileIds
     * @return array|null id数组数据集
     */
    public function GetListById($strUploadFileIds){
        $result = null;
        if (strlen($strUploadFileIds)>0) {
            $whereSql="";
            if(strripos($strUploadFileIds,",")===0){ //若为 ",xxx,zzz" 则消掉最开始的逗号
                $strUploadFileIds=substr($strUploadFileIds,1);
            }
            if(!strstr($strUploadFileIds,",")){
                $whereSql=" WHERE UploadFileId=$strUploadFileIds ";
            }else{
                $whereSql=" WHERE UploadFileId IN ($strUploadFileIds) ";
            }

            $sql = "SELECT * FROM " . self::TableName_UploadFile . $whereSql . " ;";
            $dataProperty = new DataProperty();

            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

        }
        return $result;
    }

    /**
     * 复制一个table_id的UploadFile数据到另外的table_id
     * @param int $tableId    原table_id
     * @param int $tableType  原table_type
     * @param int $toTableId    目的table_id
     * @param int $toTableType     目的table_type
     * @return array|null 所有未批量操作的记录
     */
    public function DuplicateForOtherTableType($tableId, $tableType, $toTableId, $toTableType){
        $result = -1;
        if ($tableType > 0 && $tableId>0 && $toTableId>0 && $toTableType>0) {
            $sql = "INSERT INTO " . self::TableName_UploadFile . "
                    (
                    `UploadFileName`,
                    `UploadFileExtentionName`,
                    `UploadFileSize`,
                    `UploadFileType`,
                    `UploadFileOrgName`,
                    `UploadFilePath`,
                    `UploadFileMobilePath`,
                    `UploadFilePadPath`,
                    `UploadFileThumbPath1`,
                    `UploadFileThumbPath2`,
                    `UploadFileThumbPath3`,
                    `UploadFileWatermarkPath1`,
                    `UploadFileWatermarkPath2`,
                    `UploadFileCompressPath1`,
                    `UploadFileCompressPath2`,
                    `UploadFileTitle`,
                    `UploadFileInfo`,
                    `TableType`,
                    `TableId`,
                    `ManageUserId`,
                    `UserId`,
                    `CreateDate`,
                    `IsBatchUpload`,
                    `IsBatchOperate`
                    )
                    SELECT
                    `UploadFileName`,
                    `UploadFileExtentionName`,
                    `UploadFileSize`,
                    `UploadFileType`,
                    `UploadFileOrgName`,
                    `UploadFilePath`,
                    `UploadFileMobilePath`,
                    `UploadFilePadPath`,
                    `UploadFileThumbPath1`,
                    `UploadFileThumbPath2`,
                    `UploadFileThumbPath3`,
                    `UploadFileWatermarkPath1`,
                    `UploadFileWatermarkPath2`,
                    `UploadFileCompressPath1`,
                    `UploadFileCompressPath2`,
                    `UploadFileTitle`,
                    `UploadFileInfo`,
                    $toTableType,
                    $toTableId,
                    `ManageUserId`,
                    `UserId`,
                    `CreateDate`,
                    `IsBatchUpload`,
                    `IsBatchOperate`
                     FROM " . self::TableName_UploadFile . " WHERE TableId=:TableId AND TableType=:TableType ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("TableId", $tableId);
            $dataProperty->AddField("TableType", $tableType);

            $result = $this->dbOperator->Execute($sql, $dataProperty);

        }
        return $result;
    }


    /**
     * 复制数据到另外的table_id
     * @param string $strUploadFileIds 要复制的UploadFile的id
     * @param int $toTableId    目的table_id
     * @param int $toTableType     目的table_type
     * @return string 已复制的结果
     */
    public function DuplicateByUploadFileId($strUploadFileIds, $toTableId, $toTableType){
        $result = "";
        if ($strUploadFileIds!="" && $toTableId>0 && $toTableType>0) {
            $dataProperty = new DataProperty();
            $whereSql="";

            $arrUploadFileIds=explode(",",$strUploadFileIds);
            if(count($arrUploadFileIds)>0){
                foreach($arrUploadFileIds as $uploadFileId){

                    $whereSql=" WHERE UploadFileId=$uploadFileId ";

                    $sql = "INSERT INTO " . self::TableName_UploadFile . "
                    (
                    `UploadFileName`,
                    `UploadFileExtentionName`,
                    `UploadFileSize`,
                    `UploadFileType`,
                    `UploadFileOrgName`,
                    `UploadFilePath`,
                    `UploadFileMobilePath`,
                    `UploadFilePadPath`,
                    `UploadFileThumbPath1`,
                    `UploadFileThumbPath2`,
                    `UploadFileThumbPath3`,
                    `UploadFileWatermarkPath1`,
                    `UploadFileWatermarkPath2`,
                    `UploadFileCompressPath1`,
                    `UploadFileCompressPath2`,
                    `UploadFileTitle`,
                    `UploadFileInfo`,
                    `TableType`,
                    `TableId`,
                    `ManageUserId`,
                    `UserId`,
                    `CreateDate`,
                    `IsBatchUpload`,
                    `IsBatchOperate`
                    )
                    SELECT
                    `UploadFileName`,
                    `UploadFileExtentionName`,
                    `UploadFileSize`,
                    `UploadFileType`,
                    `UploadFileOrgName`,
                    `UploadFilePath`,
                    `UploadFileMobilePath`,
                    `UploadFilePadPath`,
                    `UploadFileThumbPath1`,
                    `UploadFileThumbPath2`,
                    `UploadFileThumbPath3`,
                    `UploadFileWatermarkPath1`,
                    `UploadFileWatermarkPath2`,
                    `UploadFileCompressPath1`,
                    `UploadFileCompressPath2`,
                    `UploadFileTitle`,
                    `UploadFileInfo`,
                    $toTableType,
                    $toTableId,
                    `ManageUserId`,
                    `UserId`,
                    `CreateDate`,
                    `IsBatchUpload`,
                    `IsBatchOperate`
                     FROM " . self::TableName_UploadFile . "
                     $whereSql;";
                    $result .= ",".$insertId=$this->dbOperator->LastInsertId($sql, $dataProperty);  //所有insert的结果合并到字符串
                }
                $result=substr($result,1);
            }
        }
        return $result;
    }



    /**
     * 修改UploadFileTitle
     * @param int $uploadFileId
     * @param string $uploadFileTitle
     * @return bool 执行结果
     */
    public function ModifyUploadFileTitle($uploadFileId,$uploadFileTitle){
        $result = false;
        if ($uploadFileId > 0) {

            $sql = "UPDATE " . self::TableName_UploadFile . "
                    SET UploadFileTitle = :UploadFileTitle WHERE UploadFileId=:UploadFileId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UploadFileId", $uploadFileId);
            $dataProperty->AddField("UploadFileTitle", $uploadFileTitle);

            $result = $this->dbOperator->Execute($sql, $dataProperty);

        }
        return $result;
    }


    /**
     * 获取TableType跟TableId
     * @param int $uploadFileId
     * @return array tableType,tableId
     */
    public function GetTableTypeAndTableId($uploadFileId){
        $result = null;
        if ($uploadFileId>0) {

            $sql = "SELECT TableType,TableId FROM " . self::TableName_UploadFile . "
                    WHERE UploadFileId = :UploadFileId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UploadFileId", $uploadFileId);

            $result = $this->dbOperator->GetArray($sql, $dataProperty);

        }
        return $result;
    }
}