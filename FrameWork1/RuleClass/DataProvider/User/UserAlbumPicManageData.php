<?php

/**
 * 后台管理 会员相册图片 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author yin
 */
class UserAlbumPicManageData extends BaseManageData
{

    /**
     * 创建新图片
     * @param int $userAlbumId 相册Id
     * @param string $userAlbumPicTitle 图片标题
     * @param string $userAlbumPicUrl 原图片路径
     * @param string $userAlbumPicCompressUrl 压缩图路径
     * @param string $userAlbumPicIntro 图片简介
     * @param string $userAlbumPicContent 图片介绍
     * @param int $state 状态
     * @return int 新添加的图片Id
     */
    public function Create($userAlbumId, $userAlbumPicTitle, $userAlbumPicUrl, $userAlbumPicCompressUrl, $userAlbumPicIntro = "", $userAlbumPicContent = "", $state = 0)
    {
        $dataProperty = new DataProperty();
        $sql = "INSERT INTO " . self::TableName_UserAlbumPic . "
                        (UserAlbumId,UserAlbumPicTitle,UserAlbumPicIntro,UserAlbumPicContent,UserAlbumPicUrl,UserAlbumPicCompressUrl,CreateDate,State)
                     VALUES
                        (:UserAlbumId,:UserAlbumPicTitle,:UserAlbumPicIntro,:UserAlbumPicContent,:UserAlbumPicUrl,:UserAlbumPicCompressUrl,now(),:State);";
        $dataProperty->AddField("UserAlbumId", $userAlbumId);
        $dataProperty->AddField("UserAlbumPicTitle", $userAlbumPicTitle);
        $dataProperty->AddField("UserAlbumPicIntro", $userAlbumPicIntro);
        $dataProperty->AddField("UserAlbumPicContent", $userAlbumPicContent);
        $dataProperty->AddField("UserAlbumPicUrl", $userAlbumPicUrl);
        $dataProperty->AddField("UserAlbumPicCompressUrl", $userAlbumPicCompressUrl);
        $dataProperty->AddField("State", $state);
        $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        return $result;
    }

    /**
     * 获取一个相册(不是已否的)的图片列表
     * @param int $userAlbumId 相册Id
     * @return array 一个相册的图片集合
     */
    public function GetListOfOneUserAlbum($userAlbumId)
    {
        if ($userAlbumId > 0) {
            $sql = "SELECT uap.*,ua.* FROM " . self::TableName_UserAlbumPic . " uap," . self::TableName_UserAlbum . " ua WHERE uap.UserAlbumId= :UserAlbumId
            AND ua.State <= 100 AND ua.State != 40 AND ua." . self::TableId_UserAlbum . " = uap.UserAlbumId ORDER BY " . self::TableId_UserAlbumPic . ";";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserAlbumId", $userAlbumId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
            return $result;
        } else {
            return null;
        }
    }

    /**
     * 根据图片Id来查找图片
     * @param int $userAlbumPicId 图片Id
     * @return array 一张图片的所有信息集合
     */
    public function GetOne($userAlbumPicId)
    {
        if ($userAlbumPicId > 0) {
            $sql = "SELECT * FROM " . self::TableName_UserAlbumPic . " WHERE " . self::TableId_UserAlbumPic . "=:UserAlbumPicId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserAlbumPicId", $userAlbumPicId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
            return $result;
        } else {
            return null;
        }
    }

    /**
     * 通过图片Id获取相册Id
     * @param int $userAlbumPicId 图片Id
     * @return int 相册Id
     */
    public function GetUserAlbumId($userAlbumPicId)
    {
        if ($userAlbumPicId > 0) {
            $sql = "SELECT UserAlbumId FROM " . self::TableName_UserAlbumPic . " WHERE UserAlbumPicId = :UserAlbumPicId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserAlbumPicId", $userAlbumPicId);
            $result = $this->dbOperator->GetInt($sql, $dataProperty);
            return $result;
        } else {
            return null;
        }
    }

    /**
     * 获取一个相册的所有图片URL
     * @param int $userAlbumId 相册Id
     * @return array 一个相册的图片URL(原图URL,缩略图URL,压缩图URL)
     */
    public function GetPicUrlListOfOneUserAlbum($userAlbumId)
    {
        if ($userAlbumId > 0) {
            $sql = "SELECT UserAlbumPicUrl,UserAlbumPicThumbnailUrl,UserAlbumPicCompressUrl FROM " . self::TableName_UserAlbumPic . " WHERE UserAlbumId = :UserAlbumId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserAlbumId", $userAlbumId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
            return $result;
        } else {
            return null;
        }
    }

    /**
     * 根据国家和类别统计数据库的相册
     * @param string $country 国家
     * @param string $userAlbumTag 相册类别
     * @return int 统计结果
     */
    public function GetPicCountForStatistics($country = '', $userAlbumTag = '')
    {
        $dataProperty = new DataProperty();
        if ($userAlbumTag == '' && $country == '') { //所有的相册数 只包括已编辑 未审核 已审核的
            $sql = "SELECT count(uap.UserAlbumPicId) FROM " . self::TableName_UserAlbum . " ua," . self::TableName_UserAlbumPic . " uap WHERE ua.State < 40 AND ua.UserAlbumId = uap.UserAlbumId;";
        } else if ($country == 'china' && $userAlbumTag == '') { //国内的相册数 只包括已编辑 未审核 已审核的
            $sql = "SELECT count(uap.UserAlbumPicId) FROM ".self::TableId_UserAlbum." ua,".self::TableName_UserInfo." ui,".self::TableName_UserAlbumPic." uap WHERE ua.State < 40 AND ui.Country = 'china' AND ua.UserId = ui.UserId AND ua.UserAlbumId = uap.UserAlbumId;";
        } else if ($country == '!china' && $userAlbumTag == '') { //国外的相册数 只包括已编辑 未审核 已审核的
            $sql = "SELECT count(uap.UserAlbumPicId) FROM ".self::TableName_UserAlbum." ua,".self::TableName_UserInfo." ui,".self::TableName_UserAlbumPic." uap WHERE ua.State < 40 AND ui.Country != 'china' AND ua.UserId = ui.UserId AND ua.UserAlbumId = uap.UserAlbumId;";
        } else if ($country == 'china' && $userAlbumTag != '') { //国内的带类别的相册数 只包括已编辑 未审核 已审核的
            $sql = "SELECT count(uap.UserAlbumPicId) FROM ".self::TableName_UserAlbum." ua,".self::TableName_UserInfo." ui,".self::TableName_UserAlbumPic." uap WHERE ua.State < 40 AND ui.Country = 'china' AND ua.UserId = ui.UserId AND ua.UserAlbumTag = :UserAlbumTag AND ua.UserAlbumId = uap.UserAlbumId;";
            $dataProperty->AddField("UserAlbumTag", $userAlbumTag);
        } else{ //国外的带类别的相册数 只包括已编辑 未审核 已审核的
            $sql = "SELECT count(uap.UserAlbumPicId) FROM ".self::TableName_UserAlbum." ua,".self::TableName_UserInfo." ui,".self::TableName_UserAlbumPic." uap WHERE ua.State < 40 AND ui.Country != 'china' AND ua.UserId = ui.UserId AND ua.UserAlbumTag = :UserAlbumTag AND ua.UserAlbumId = uap.UserAlbumId;";
            $dataProperty->AddField("UserAlbumTag", $userAlbumTag);
        }
        $result = $this->dbOperator->GetInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 修改封面图
     * @param int $userAlbumPicId 图片Id
     * @param int $userAlbumId 相册Id
     * @return int 影响的行数
     */
    public function ModifyIsCover($userAlbumPicId, $userAlbumId)
    {
        if ($userAlbumPicId > 0 && $userAlbumId > 0) {
            $sqlIsNotCover = "UPDATE " . self::TableName_UserAlbumPic . " SET IsCover = 0 WHERE UserAlbumId = :UserAlbumId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserAlbumId", $userAlbumId);
            $isNoCoverResult = $this->dbOperator->Execute($sqlIsNotCover, $dataProperty);

            if ($isNoCoverResult > 0) {
                $sqlIsCover = "UPDATE " . self::TableName_UserAlbumPic . " SET IsCover = 1 WHERE UserAlbumPicId = :UserAlbumPicId;";
                $dataProperty = new DataProperty();
                $dataProperty->AddField("UserAlbumPicId", $userAlbumPicId);
                $result = $this->dbOperator->Execute($sqlIsCover, $dataProperty);
                return $result;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    /**
     * 修改图片简介
     * @param int $userAlbumPicId 图片ID
     * @param string $userAlbumPicIntro 图片简介
     * @return int 影响的行数
     */
    public function ModifyUserAlbumPicIntro($userAlbumPicId, $userAlbumPicIntro)
    {
        if ($userAlbumPicId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_UserAlbumPic . " SET UserAlbumPicIntro = :UserAlbumPicIntro WHERE " . self::TableId_UserAlbumPic . " = :UserAlbumPicId;";
            $dataProperty->AddField("UserAlbumPicId", $userAlbumPicId);
            $dataProperty->AddField("UserAlbumPicIntro", $userAlbumPicIntro);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
            return $result;
        } else {
            return null;
        }
    }

    /**
     * 修改压缩图地址
     * @param string $userAlbumPicCompressUrl 压缩图地址
     * @param int $userAlbumPicId 图片Id
     * @return int 影响的行数
     */
    public function ModifyUserAlbumPicCompressUrl($userAlbumPicCompressUrl, $userAlbumPicId)
    {
        if ($userAlbumPicId > 0) {
            $sql = "UPDATE " . self::TableName_UserAlbumPic . " SET UserAlbumPicCompressUrl = :UserAlbumPicCompressUrl WHERE " . self::TableId_UserAlbumPic . " = :UserAlbumPicId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserAlbumPicCompressUrl", $userAlbumPicCompressUrl);
            $dataProperty->AddField("UserAlbumPicId", $userAlbumPicId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
            return $result;
        } else {
            return null;
        }
    }


    /**
     * 修改图片的路径
     * @param int $userAlbumPicId 图片Id
     * @param $userAlbumPicUrl
     * @param $userAlbumPicThumbnailUrl
     * @return int 影响行数
     */
    public function ModifyUserAlbumPicUrl($userAlbumPicId, $userAlbumPicUrl, $userAlbumPicThumbnailUrl) {
        if($userAlbumPicId > 0){
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_UserAlbumPic . " SET UserAlbumPicUrl = :UserAlbumPicUrl,
                UserAlbumPicThumbnailUrl = :UserAlbumPicThumbnailUrl WHERE UserAlbumPicId = :UserAlbumPicId;";
            $dataProperty->AddField("UserAlbumPicId", $userAlbumPicId);
            $dataProperty->AddField("UserAlbumPicUrl", $userAlbumPicUrl);
            $dataProperty->AddField("UserAlbumPicThumbnailUrl", $userAlbumPicThumbnailUrl);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
            return $result;
        }else{
            return null;
        }
    }

    public function RemoveBin($userAlbumPicId){

    }

    /**
     * 删除图片
     * @param int $userAlbumPicId 图片Id
     * @return int 影响行数
     */
    public function Delete($userAlbumPicId) {
        if($userAlbumPicId > 0){
            $sql = "DELETE FROM " . self::TableName_UserAlbumPic . " WHERE UserAlbumPicId = :UserAlbumPicId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserAlbumPicId", $userAlbumPicId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
            return $result;
        }else{
            return null;
        }
    }
}