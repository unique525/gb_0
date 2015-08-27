<?php

/**
 * 公共访问 会员相册 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author yin
 */
class UserAlbumPublicData extends BasePublicData
{

    public function Create($userAlbumTypeId,
                           $userId,
                           $siteId,
                           $createDate,
                           $userAlbumIntro,
                           $userAlbumName = "",
                           $state = 0,
                           $userAlbumMainTag = "",
                           $userAlbumTag = "",
                           $equipment = "",
                           $agreeCount = 0,
                           $disagreeCount = 0,
                           $hitCount = 0,
                           $isBest = false,
                           $recLevel = 0,
                           $sort = 0,
                           $indexTop = 0,
                           $region = "",
                           $lastCommentDate = "0000-00-00 00:00:00",
                           $pushOut = 0,
                           $coverPicUploadFileId = 0
    )
    {

        $result = -1;
        if ($userId > 0) {
            $sql = "INSERT INTO "
                . self::TableName_UserAlbum . " (
                     UserAlbumTypeId, UserId, SiteId, UserAlbumName,
                     CreateDate, State, UserAlbumMainTag, UserAlbumTag,
                     UserAlbumIntro, Equipment, AgreeCount, DisagreeCount,
                     HitCount, IsBest, RecLevel, Sort, IndexTop, Region,
                     LastCommentDate, PushOut , CoverPicUploadFileId
                    ) VALUES (
                    :UserAlbumTypeId,:UserId,:SiteId,:UserAlbumName,
                    :CreateDate,:State,:UserAlbumMainTag,:UserAlbumTag,
                    :UserAlbumIntro,:Equipment,:AgreeCount,:DisagreeCount,
                    :HitCount,:IsBest,:RecLevel,:Sort,:IndexTop,:Region,
                    :LastCommentDate,:PushOut,:CoverPicUploadFileId
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
            $dataProperty->AddField("CoverPicUploadFileId", $coverPicUploadFileId);
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        }
        return $result;
    }

    public function ModifyCoverPicUploadFileId($userAlbumId, $coverPicUploadFileId)
    {
        $result = -1;
        if ($userAlbumId > 0 && $coverPicUploadFileId > 0) {
            $sql = "UPDATE " . self::TableName_UserAlbum . "

                    SET CoverPicUploadFileId=:CoverPicUploadFileId

                    WHERE UserAlbumId = :UserAlbumId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserAlbumId", $userAlbumId);
            $dataProperty->AddField("CoverPicUploadFileId", $coverPicUploadFileId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;

    }

    /**
     * 获取站点内的所有相册
     * @param int $siteId 站点ID
     * @param int $pageBegin 从pageBegin开始查询
     * @param int $pageSize 取pageSize条数据
     * @param int $allCount 总行数
     * @param int $state 取状态为state的相册
     * @param string $searchKey 搜索关键词
     * @param int $order 排序方式
     * @param int $userGroupId 分组ID
     * @return array 相册列表的数组
     */
    public function GetList($siteId, $pageBegin, $pageSize, &$allCount, $state, $searchKey, $order = 0,$userGroupId)
    {
        $result = -1;
        $searchSql = "";
        $dataProperty = new DataProperty();
        if ($siteId > 0) {
            if (strlen($searchKey) > 0 && $searchKey != "undefined") {
                $searchSql .= " AND ui.RealName LIKE :SearchKey ";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            }
            $orderBy = ' ua.CreateDate DESC ';
            if ($order == 1){
                $orderBy = ' ua.HitCount DESC ';
            }

            $sql = "SELECT
                    ui.RealName,
                    ui.UserId,
                    ui.SchoolName,
                    ui.ClassName,
                    ua.UserAlbumId,
                    ua.UserAlbumIntro,
                    ua.State,
                    ua.SiteId,
                    ua.HitCount,
                    uf.UploadFilePath,
                    uf.UploadFileId,
                    u.UserMobile
                    FROM
                    " . self::TableName_UserInfo . " ui,
                    " . self::TableName_UserAlbum . " ua,
                    " . self::TableName_UploadFile . " uf,
                    " . self::TableName_UserRole . " ur,
                    " . self::TableName_User . " u
                    WHERE
                    ui.UserId=ua.UserId
                    and u.UserId=ua.UserId
                    and uf.UploadFileId=ua.CoverPicUploadFileId
                    and ua.SiteId=:SiteId
                    and ua.UserId=ur.UserId
                    and ua.State=:State
                    and ur.UserGroupId=:UserGroupId " . $searchSql . "
                    ORDER BY $orderBy LIMIT " . $pageBegin . "," . $pageSize;

            $dataProperty->AddField("SiteId",$siteId);
            $dataProperty->AddField("State",$state);
            $dataProperty->AddField("UserGroupId",$userGroupId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);


            $sqlCount = "SELECT
                    count(*)
                    FROM
                    " . self::TableName_UserInfo . " ui,
                    " . self::TableName_UserAlbum . " ua,
                    " . self::TableName_UploadFile . " uf,
                    " . self::TableName_UserRole . " ur,
                    " . self::TableName_User . " u
                    WHERE
                    ui.UserId=ua.UserId
                    and u.UserId=ua.UserId
                    and uf.UploadFileId=ua.CoverPicUploadFileId
                    and ua.SiteId=:SiteId
                    and ua.UserId=ur.UserId
                    and ua.State=:State
                    and ur.UserGroupId=:UserGroupId " . $searchSql;

            $allCount = $this->dbOperator->GetInt($sqlCount, $dataProperty);
        }
        return $result;
    }

    /**
     * 获取一个相册的信息(checked)
     * @param int $userAlbumId 相册ID
     * @return array 一个相册的数据集
     */
    public function GetOne($userAlbumId)
    {
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
            uf1.UploadFileCutPath1 AS UserAlbumPicUploadFileCutPath1,

            uf2.UploadFilePath AS UserAlbumCoverPicUploadFilePath,
            uf2.UploadFileMobilePath AS UserAlbumCoverPicUploadFileMobilePath,
            uf2.UploadFilePadPath AS UserAlbumCoverPicUploadFilePadPath,
            uf2.UploadFileThumbPath1 AS UserAlbumCoverPicUploadFileThumbPath1,
            uf2.UploadFileThumbPath2 AS UserAlbumCoverPicUploadFileThumbPath2,
            uf2.UploadFileThumbPath3 AS UserAlbumCoverPicUploadFileThumbPath3,
            uf2.UploadFileWatermarkPath1 AS UserAlbumCoverPicUploadFileWatermarkPath1,
            uf2.UploadFileWatermarkPath2 AS UserAlbumCoverPicUploadFileWatermarkPath2,
            uf2.UploadFileCompressPath1 AS UserAlbumCoverPicUploadFileCompressPath1,
            uf2.UploadFileCompressPath2 AS UserAlbumCoverPicUploadFileCompressPath2,
            uf2.UploadFileCutPath1 AS UserAlbumCoverPicUploadFileCutPath1
            ';
            $sql = "SELECT " . $selectColumn . "
             FROM " . self::TableName_UserAlbum . " ua
             LEFT OUTER JOIN " . self::TableName_UserAlbumPic . " uap ON
uap.UserAlbumId=ua.UserAlbumId
             LEFT OUTER JOIN " . self::TableName_UploadFile . " uf1 ON
uap.UserAlbumPicId=uf1.UploadFileId
             LEFT OUTER JOIN " . self::TableName_UploadFile . " uf2 ON
ua.CoverPicUploadFileId=uf2.UploadFileId
             WHERE ua.UserAlbumId = :UserAlbumId AND ua.State <= 100 ";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserAlbumId", $userAlbumId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
            return $result;
        } else {
            return null;
        }
    }

    /**
     * 增加点击数
     * @param int $userAlbumId  相册Id
     * @return int  执行结果
     */
    public function AddHitCount($userAlbumId) {
        $sql = "UPDATE  " . self::TableName_UserAlbum . " SET HitCount=HitCount+1 WHERE UserAlbumId=:UserAlbumId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("UserAlbumId", $userAlbumId);
        $result = $this->dbOperator->GetInt($sql, $dataProperty);
        return $result;
    }
}

