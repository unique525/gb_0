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
     * @param $uploadFileThumbPath1
     * @param $userId
     * @return int
     */
    public function ModifyUploadFileThumbPath1($uploadFileId,$uploadFileThumbPath1,$userId){
        $result = -1;
        if($uploadFileId > 0){
            $sql = "UPDATE ".self::TableName_UploadFile." SET UploadFileThumbPath1 = :UploadFileThumbPath1 WHERE UploadFileId = :UploadFileId AND UserId = :UserId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UploadFileThumbPath1",$uploadFileThumbPath1);
            $dataProperty->AddField("UploadFileId",$uploadFileId);
            $dataProperty->AddField("UserId",$userId);
            $result = $this->dbOperator->Execute($sql,$dataProperty);
        }
        return $result;
    }

    public function Create(){

    }
} 