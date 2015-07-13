<?php

/**
 * 公共访问 会员相册 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author yin
 */
class UserAlbumPublicData extends BasePublicData {

    public function Create($userAlbumTypeId,$userId,$siteId,
                           $createDate,$userAlbumIntro,
                           $userAlbumName="",$state=0,
                           $userAlbumMainTag="",$userAlbumTag="",
                           $equipment="",$agreeCount=0,
                           $disagreeCount=0,$hitCount=0,$isBest=false,
                           $recLevel=0,$sort=0,$indexTop=0,
                           $region="",$lastCommentDate="0000-00-00 00:00:00",$pushOut=0,
                           $coverPicUrl=""){

        $result = -1;
        if ($userId > 0) {
            $sql = "INSERT INTO "
                . self::TableName_UserAlbum. " (
                     UserAlbumTypeId, UserId, SiteId, UserAlbumName,
                     CreateDate, State, UserAlbumMainTag, UserAlbumTag,
                     UserAlbumIntro, Equipment, AgreeCount, DisagreeCount,
                     HitCount, IsBest, RecLevel, Sort, IndexTop, Region,
                     LastCommentDate, PushOut, CoverPicUrl
                    ) VALUES (
                    :UserAlbumTypeId,:UserId,:SiteId,:UserAlbumName,
                    :CreateDate,:State,:UserAlbumMainTag,:UserAlbumTag,
                    :UserAlbumIntro,:Equipment,:AgreeCount,:DisagreeCount,
                    :HitCount,:IsBest,:RecLevel,:Sort,:IndexTop,:Region,
                    :LastCommentDate,:PushOut,:CoverPicUrl
                    );";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserAlbumTypeId", $userAlbumTypeId);
            $dataProperty->AddField("UserId", $userId);
            $dataProperty->AddField("SiteId", $siteId);
            $dataProperty->AddField("UserAlbumName", $userAlbumName);
            $dataProperty->AddField("CreateDate", $createDate);
            $dataProperty->AddField("State", $state);
            $dataProperty->AddField("UserAlbumMainTag", $userAlbumMainTag);
            $dataProperty->AddField("UserAlbumTag", $userAlbumTag);
            $dataProperty->AddField("UserAlbumIntro", $userAlbumIntro);
            $dataProperty->AddField("Equipment", $equipment);
            $dataProperty->AddField("AgreeCount", $agreeCount);
            $dataProperty->AddField("DisagreeCount", $disagreeCount);
            $dataProperty->AddField("HitCount", $hitCount);
            $dataProperty->AddField("IsBest", $isBest);
            $dataProperty->AddField("RecLevel", $recLevel);
            $dataProperty->AddField("Sort", $sort);
            $dataProperty->AddField("IndexTop", $indexTop);
            $dataProperty->AddField("Region", $region);
            $dataProperty->AddField("LastCommentDate", $lastCommentDate);
            $dataProperty->AddField("PushOut", $pushOut);
            $dataProperty->AddField("CoverPicUrl", $coverPicUrl);

            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 获取一个相册的信息(checked)
     * @param int $userAlbumId 相册ID
     * @return array 一个相册的数据集
     */
    public function GetOne($userAlbumId) {
        if ($userAlbumId > 0) {
            $selectColumn = '
            ua.*,

            uf1.UploadFilePath AS UserAlbumPicUploadFilePath,
            uf1.UploadFileMobilePath AS UserAlbumPicUploadFileMobilePath,
            uf1.UploadFilePadPath AS UserAlbumPicUploadFilePadPath,
            uf1.UploadFileThumbPath1 AS UserAlbumPicUploadFileThumbPath1,
            uf1.UploadFileThumbPath2 AS UserAlbumPicUploadFileThumbPath2,
            uf1.UploadFileThumbPath3 AS UserAlbumPicUploadFileThumbPath3,
            uf1.UploadFileWatermarkPath1 AS UserAlbumPicUploadFileWatermarkPath1,
            uf1.UploadFileWatermarkPath2 AS UserAlbumPicUploadFileWatermarkPath2,
            uf1.UploadFileCompressPath1 AS UserAlbumPicUploadFileCompressPath1,
            uf1.UploadFileCompressPath2 AS UserAlbumPicUploadFileCompressPath2,
            uf1.UploadFileCutPath1 AS UserAlbumPicUploadFileCutPath1
            ';
            $sql = "SELECT ".$selectColumn."
             FROM " . self::TableName_UserAlbum . " ua
             LEFT OUTER JOIN " . self::TableName_UserAlbumPic . " uap ON uap.UserAlbumId=ua.UserAlbumId
             LEFT OUTER JOIN " . self::TableName_UploadFile . " uf1 ON uap.UserAlbumPicId=uf1.UploadFileId
             WHERE ua.UserAlbumId = :UserAlbumId AND ua.State <= 100 ";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserAlbumId", $userAlbumId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
            return $result;
        } else {
            return null;
        }
    }
} 