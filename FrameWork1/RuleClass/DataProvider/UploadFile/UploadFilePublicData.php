<?php
/**
 * 公共 上传文件 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_UploadFile
 * @author zhangchi
 */
class UploadFilePublicData extends BasePublicData{

    /**
     * @param $uploadFileId
     * @param $uploadFilePath
     * @param $userId
     * @return int
     */
    public function ModifyUploadFilePath($uploadFileId,$uploadFilePath,$userId){
        $result = -1;
        if($uploadFileId > 0){
            $sql = "UPDATE ".self::TableName_UploadFile." SET UploadFilePath = :UploadFilePath WHERE UploadFileId = :UploadFileId AND UserId = :UserId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UploadFilePath",$uploadFilePath);
            $dataProperty->AddField("UploadFileId",$uploadFileId);
            $dataProperty->AddField("UserId",$userId);
            $result = $this->dbOperator->Execute($sql,$dataProperty);
        }
        return $result;
    }

} 