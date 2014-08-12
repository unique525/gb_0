<?php
/**
 * 后台管理 上传文件 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_UploadFile
 * @author zhangchi
 */
class UploadFileManageData extends BaseManageData {


    /**
     * 上传文件增加到数据表
     * @param string $uploadFileName 文件名
     * @param int $uploadFileSize 文件大小
     * @param int $uploadFileType 文件类型（扩展名）
     * @param string $uploadFileOrgName 文件原名称
     * @param string $uploadFilePath 文件路径
     * @param int $tableType 对应表类型
     * @param int $tableId 对应表id
     * @param int $uploadYear 上传时间：年
     * @param int $uploadMonth 上传时间：月
     * @param int $uploadDay 上传时间：日
     * @param int $manageUserId 后台管理员id
     * @param int $userId 会员id
     * @param string $uploadFileTitle 文件标题
     * @param string $uploadFileInfo 文件介绍
     * @param int $isBatchUpload 是否是批量上传的文件
     * @return int 新增的上传文件id
     */
    public function Create($uploadFileName, $uploadFileSize, $uploadFileType, $uploadFileOrgName, $uploadFilePath, $tableType, $tableId, $uploadYear, $uploadMonth, $uploadDay, $manageUserId, $userId, $uploadFileTitle = '', $uploadFileInfo = '', $isBatchUpload = 0) {
        $sql = "INSERT INTO ".self::TableName_UploadFile."
            (UploadFileName,UploadFileSize,UploadFileType,UploadFileOrgName,UploadFilePath,TableType,TableId,UploadYear,UploadMonth,UploadDay,ManageUserId,UserId,CreateDate,UploadFileTitle,UploadFileInfo,IsBatchUpload) VALUES
           (:UploadFileName,:UploadFileSize,:UploadFileType,:UploadFileOrgName,:UploadFilePath,:TableType,:TableId,:UploadYear,:UploadMonth,:UploadDay,:ManageUserId,:UserId,now(),:UploadFileTitle,:UploadFileInfo,:IsBatchUpload);";

        $uploadFilePath = str_ireplace("../../", "/", $uploadFilePath);
        $uploadFilePath = str_ireplace("../", "/", $uploadFilePath);
        $uploadFilePath = str_ireplace("./", "/", $uploadFilePath);

        $dataProperty = new DataProperty();
        $dataProperty->AddField("UploadFileName", $uploadFileName);
        $dataProperty->AddField("UploadFileSize", $uploadFileSize);
        $dataProperty->AddField("UploadFileType", $uploadFileType);
        $dataProperty->AddField("UploadFileOrgName", $uploadFileOrgName);
        $dataProperty->AddField("UploadFilePath", $uploadFilePath);
        $dataProperty->AddField("TableType", $tableType);
        $dataProperty->AddField("TableId", $tableId);
        $dataProperty->AddField("UploadYear", $uploadYear);
        $dataProperty->AddField("UploadMonth", $uploadMonth);
        $dataProperty->AddField("UploadDay", $uploadDay);
        $dataProperty->AddField("ManageUserId", $manageUserId);
        $dataProperty->AddField("UserId", $userId);
        $dataProperty->AddField("UploadFileTitle", $uploadFileTitle);
        $dataProperty->AddField("UploadFileInfo", $uploadFileInfo);
        $dataProperty->AddField("IsBatchUpload", $isBatchUpload);
        $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        return $result;
    }

    /**
     * 修改TableId
     * @param int $uploadFileId 上传文件id
     * @param int $tableId 对应表id
     * @return int 操作结果
     */
    public function ModifyTableId($uploadFileId, $tableId) {
        if ($uploadFileId > 0 && $tableId > 0) {
            $sql = "UPDATE ".self::TableName_UploadFile." SET TableId=:TableId WHERE UploadFileId=:UploadFileId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("TableId", $tableId);
            $dataProperty->AddField("UploadFileId", $uploadFileId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
            return $result;
        }else{
            return -1;
        }
    }

    /**
     * 修改文件记录“是否是批量上传的文件”字段
     * @param int $uploadFileId 上传文件id
     * @param int $isBatchUpload 是否是批量上传的文件
     * @return int 返回影响的行数
     */
    public function ModifyIsBatchUpload($uploadFileId, $isBatchUpload) {
        if ($uploadFileId > 0) {
            $sql = "UPDATE ".self::TableName_UploadFile." SET IsBatchUpload=:IsBatchUpload WHERE UploadFileId=:UploadFileId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("IsBatchUpload", $isBatchUpload);
            $dataProperty->AddField("UploadFileId", $uploadFileId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
            return $result;
        }else{
            return -1;
        }
    }

    /**
     * 修改文件大小
     * @param int $uploadFileId 上传文件id
     * @param int $fileSize 文件大小(单位字节 B)
     * @return int 操作结果
     */
    public function ModifyFileSize($uploadFileId, $fileSize) {
        if ($uploadFileId > 0 && $fileSize > 0) {
            $sql = "UPDATE ".self::TableName_UploadFile." SET UploadFileSize=:UploadFileSize WHERE UploadFileId=:UploadFileId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UploadFileSize", $fileSize);
            $dataProperty->AddField("UploadFileId", $uploadFileId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
            return $result;
        }else{
            return -1;
        }
    }
}