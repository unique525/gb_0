<?php
/**
 * 公共访问 会员相片 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author yin
 */
class UserAlbumPicPublicData extends BasePublicData {

    public function Create($userAlbumId,
                           $createDate,
                           $userAlbumPicTitle="",
                           $userAlbumPicIntro="",
                           $userAlbumPicContent="",
                           $userAlbumPicUploadFileId=0,
                           $state=0,
                           $waitCreateThumb="",
                           $isCover=0,
                           $sort=0
    ){

        $result = -1;
        if ($userAlbumId > 0) {
            $sql = "INSERT INTO "
                . self::TableName_UserAlbumPic. " (UserAlbumId, UserAlbumPicTitle,UserAlbumPicIntro,
                                                   UserAlbumPicContent, UserAlbumPicUploadFileId,
                                                   CreateDate, State, WaitCreateThumb, IsCover, Sort)
                                                   VALUES (
                                                   :UserAlbumId,:UserAlbumPicTitle,:UserAlbumPicIntro,
                                                   :UserAlbumPicContent,:UserAlbumPicUploadFileId,
                                                   :CreateDate,:State,:WaitCreateThumb,:IsCover,:Sort
                                                   );";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserAlbumId", $userAlbumId);
            $dataProperty->AddField("UserAlbumPicTitle", $userAlbumPicTitle);
            $dataProperty->AddField("UserAlbumPicIntro", $userAlbumPicIntro);
            $dataProperty->AddField("UserAlbumPicContent", $userAlbumPicContent);
            $dataProperty->AddField("UserAlbumPicUploadFileId", $userAlbumPicUploadFileId);
            $dataProperty->AddField("CreateDate", $createDate);
            $dataProperty->AddField("State", $state);
            $dataProperty->AddField("WaitCreateThumb", $waitCreateThumb);
            $dataProperty->AddField("IsCover", $isCover);
            $dataProperty->AddField("Sort", $sort);
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        }

        return $result;
    }

    public function ModifyUploadFileId($userAlbumPicId,$userAlbumPicUploadFileId){
        $result = -1;
        if ($userAlbumPicId>0 && $userAlbumPicUploadFileId>0){
            $sql = "UPDATE ".self::TableName_UserAlbumPic." SET UserAlbumPicUploadFileId=:UserAlbumPicUploadFileId WHERE UserAlbumPicId = :UserAlbumPicId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserAlbumPicId", $userAlbumPicId);
            $dataProperty->AddField("UserAlbumPicUploadFileId", $userAlbumPicUploadFileId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;

    }


}
