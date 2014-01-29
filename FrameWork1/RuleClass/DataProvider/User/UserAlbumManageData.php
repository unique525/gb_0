<?php

/**
 * 后台管理 会员相册 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author zhangchi
 */
class UserAlbumManageData extends BaseManageData {

    /**
     * 获取一个相册的信息
     * @param int $albumId 相册ID
     * @return array 一个相册的数据集
     */
    public function GetOne($albumId) {
        if ($albumId > 0) {
            $sql = "SELECT ua.*,uap.UserAlbumPicThumbnailUrl,ui.NickName,ui.RealName,u.UserName,ui.UserId FROM " . self::TableName_UserAlbum . " ua 
                LEFT JOIN " . self::TableName_UserAlbumPicture . " uap ON uap.UserAlbumId=ua.UserAlbumId AND uap.IsCover = 1," . self::TableName_UserInfo . " ui," . self::TableName_User . " u
                WHERE ua.UserAlbumId = :useralbumid AND ua.State <= 100 AND ui.UserId = ua.UserId AND u.UserId=ui.UserId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("useralbumid", $albumId);
            $dbOperator = DBOperator::getInstance();
            $result = $dbOperator->GetArrayList($sql, $dataProperty);
            return $result;
        } else {
            return null;
        }
    }

    /**
     * 根据useralbumid取得UserAlbumIntro
     * @param int $userAlbumId 相册ID
     * @return string 相册简介
     */
    public function GetUserAlbumIntro($userAlbumId) {
        if ($userAlbumId > 0) {
            $sql = "SELECT UserAlbumIntro FROM " . self::TableName_UserAlbum . " WHERE UserAlbumId=:useralbumid";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("useralbumid", $userAlbumId);
            $dbOperator = DBOperator::getInstance();
            $result = $dbOperator->GetString($sql, $dataProperty);
            return $result;
        } else {
            return null;
        }
    }

    /**
     * 获取站点内的所有相册
     * @param int $pageBegin 从pageBegin开始查询
     * @param int $pageSize 取pageSize条数据
     * @param int $allCount 总行数
     * @param int $state 取状态为state的相册
     * @param int $siteId 站点ID
     * @return array 相册列表的数组
     */
    public function GetAll($pageBegin, $pageSize, &$allCount, $state, $siteId) {
        if ($state == 100) {
            //countpic 用于统计相册有多少图片
            $sql = "SELECT ui.NickName AS nickname,ui.RealName AS realname,ui.UserId,ua.UserAlbumId,ua.UserAlbumName,ua.State,ua.SupportCount,ua.HitCount,
                (SELECT count(*) FROM " . self::TableName_UserAlbumPicture . " uap WHERE uap.UserAlbumId = ua.UserAlbumId) as countpic,
                ua.UserAlbumTag,ua.CreateDate,ua.IsBest,ua.RecLevel,ua.IndexTop,uap.UserAlbumPicThumbnailUrl 
                FROM " . self::TableName_UserInfo . " ui," . self::TableName_UserAlbum . " ua LEFT JOIN " . self::TableName_UserAlbumPicture . " uap ON ua.UserAlbumId = uap.UserAlbumId AND uap.IsCover = 1 
                WHERE ua.State <= 100 AND ua.UserId = ui.UserId ORDER BY CreateDate DESC LIMIT " . $pageBegin . "," . $pageSize;
            //$dataProperty = new DataProperty();
            //$dataProperty->AddField("SiteId", $siteId);
            $dbOperator = DBOperator::getInstance();
            $result = $dbOperator->GetArrayList($sql, null);

            $sqlCount = "SELECT count(*) FROM " . self::TableName_UserInfo . " ui," . self::TableName_UserAlbum . " ua WHERE ua.UserId = ui.UserId AND ua.State <= 100";
            $allCount = $dbOperator->GetInt($sqlCount, null);
        } else {
            if ($siteId > 0) {
                //countpic 用于统计相册有多少图片
                $sql = "SELECT ui.NickName AS nickname,ui.RealName AS realname,ui.UserId,ua.UserAlbumId,ua.UserAlbumName,ua.State,ua.SupportCount,ua.HitCount,
                (SELECT count(*) FROM " . self::TableName_UserAlbunPicture . " uap WHERE uap.UserAlbumID = ua.UserAlbumID) AS countpic,
                ua.UserAlbumTag,ua.CreateDate,ua.IsBest,ua.RecLevel,ua.IndexTop,uap.UserAlbumPicThumbnailUrl 
                FROM " . self::TableName_UserInfo . " ui," . self::TableName_UserAlbum . " ua LEFT JOIN " . self::TableName_UserAlbumPicture . " uap ON ua.UserAlbumId = uap.UserAlbumId AND uap.IsCover = 1 
                WHERE ua.State = :state AND ua.UserId = ui.UserId AND ua.SiteId = :siteid ORDER BY CreateDate DESC LIMIT " . $pageBegin . "," . $pageSize;
                $dataProperty = new DataProperty();
                $dbOperator = DBOperator::getInstance();
                $dataProperty->AddField("state", $state);
                $dataProperty->AddField("siteid", $siteId);
                $result = $dbOperator->GetArrayList($sql, $dataProperty);

                $sqlCount = "SELECT count(*) FROM " . self::TableName_UserInfo . " ui," . self::TableName_UserAlbum . " ua where ua.UserId = ui.UserId AND ua.SiteId = :siteid and ua.state = :state";
                $allCount = $dbOperator->GetInt($sqlCount, $dataProperty);
            } else {
                return null;
            }
        }
        return $result;
    }

    /**
     * 修改该相册是否为精华
     * @param int $userAlbumId 相册ID
     * @return int 影响行数
     */
    public function ModifyIsBest($userAlbumId) {
        if ($userAlbumId > 0) {
            $sql = "UPDATE " . self::TableName_UserAlbum . " SET IsBest = 1 WHERE " . self::TableId_UserAlbum . " = :useralbumid";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("useralbumid", $userAlbumId);
            $dbOperator = DBOperator::getInstance();
            $result = $dbOperator->Execute($sql, $dataProperty);
            return $result;
        } else {
            return null;
        }
    }

    /**
     * 获取相册名
     * @param int $userAlbumId 相册ID
     * @return string 相册名
     */
    public function GetName($userAlbumId) {
        if ($userAlbumId > 0) {
            $sql = "SELECT UserAlbumName FROM " . self::TableName_UserAlbum . " WHERE " . TableId_UserAlbum . " = :useralbumid";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("useralbumid", $userAlbumId);
            $dbOperator = DBOperator::getInstance();
            $result = $dbOperator->GetString($sql, $dataProperty);
            return $result;
        } else {
            return null;
        }
    }

    /**
     * 获取相册类别
     * @param int $userAlbumId 相册ID
     * @return string 相册名
     */
    public function GetTag($userAlbumId) {
        if ($userAlbumId > 0) {
            $sql = "SELECT UserAlbumTag FROM " . self::TableName_UserAlbum . " WHERE " . self::TableId_UserAlbum . " = :useralbumid";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("useralbumid", $userAlbumId);
            $dbOperator = DBOperator::getInstance();
            $result = $dbOperator->GetString($sql, $dataProperty);
            return $result;
        } else {
            return null;
        }
    }

}

