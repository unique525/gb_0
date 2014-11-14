<?php
/**
 * Created by PhpStorm.
 * User: zcoffice
 * Date: 14-8-22
 * Time: 下午12:13
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

    public function Create(){

    }
} 