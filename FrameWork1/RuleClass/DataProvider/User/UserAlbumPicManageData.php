<?php
/**
 * 后台管理 会员相册图片 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author zhangchi
 */
class UserAlbumPicManageData extends BaseManageData {

    /**
     * 创建新照片
     * @param int $userAlbumId 相册Id
     * @param string $userAlbumPicTitle 图片标题
     * @param string $userAlbumPicUrl 图片路径
     * @param $userAlbumPicCompressUrl
     * @param string $userAlbumPicIntro 图片简介
     * @param string $userAlbumPicContent 图片介绍
     * @param int $state 状态
     * @return int 新添加的照片Id
     */
    public function Create($userAlbumId, $userAlbumPicTitle, $userAlbumPicUrl, $userAlbumPicCompressUrl,$userAlbumPicIntro = "", $userAlbumPicContent = "", $state = 0) {
        $dataProperty = new DataProperty();
        $sql = "INSERT INTO " . self::TableName_UserAlbumPic . " (UserAlbumId,UserAlbumPicTitle,"
            . "UserAlbumPicIntro,UserAlbumPicContent,UserAlbumPicUrl,UserAlbumPicCompressUrl,CreateDate,State) VALUES "
            . "(:UserAlbumId,:UserAlbumPicTitle,"
            . ":UserAlbumPicIntro,:UserAlbumPicContent,:UserAlbumPicUrl,:UserAlbumPicCompressUrl,now(),:State)";
        $dataProperty->AddField("UserAlbumId", $userAlbumId);
        $dataProperty->AddField("UserAlbumPicTitle", $userAlbumPicTitle);
        $dataProperty->AddField("UserAlbumPicIntro", $userAlbumPicIntro);
        $dataProperty->AddField("UserAlbumPicContent", $userAlbumPicContent);
        $dataProperty->AddField("UserAlbumPicUrl", $userAlbumPicUrl);
        $dataProperty->AddField("UserAlbumPicCompressUrl", $userAlbumPicCompressUrl);
        $dataProperty->AddField("State", $state);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->LastInsertId($sql, $dataProperty);
        return $result;
    }

    /**
     * 修改图片简介
     * @param int $userAlbumPicId 图片ID
     * @param string $userAlbumPicIntro 图片简介
     * @return int 影响的行数
     */
    public function ModifyAlbumPicIntro($userAlbumPicId, $userAlbumPicIntro) {
        $dataProperty = new DataProperty();
        $sql = "UPDATE " . self::TableName_UserAlbumPic . " SET UserAlbumPicIntro = :UserAlbumPicIntro WHERE ".self::TableId_UserAlbumPic." = :UserAlbumPicId;";
        $dataProperty->AddField("UserAlbumId", $userAlbumPicId);
        $dataProperty->AddField("UserAlbumPicIntro", $userAlbumPicIntro);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 获取一个相册(不是已否的)的图片列表
     * @param int $userAlbumId 相册Id
     * @return array 一个相册的图片集合
     */
    public function GetOneAlbumPicList($userAlbumId) {
        $sql = "SELECT uap.*,ua.* FROM ".self::TableName_UserAlbumPic." uap,".self::TableName_UserAlbum." ua WHERE uap.UserAlbumId= :UserAlbumId
            AND ua.State <= 100 AND ua.State != 40 AND ua.".self::TableId_UserAlbum." = uap.UserAlbumId ORDER BY ".self::TableId_UserAlbumPic.";";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("UserAlbumId", $userAlbumId);
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        return $result;
    }

    /**
     * 根据图片Id来查找照片
     * @param int $userAlbumPicId 图片Id
     * @return array 一张图片的集合
     */
    public function GetOne($userAlbumPicId) {
        $sql = "SELECT * FROM ".self::TableName_UserAlbumPic." WHERE ".self::TableId_UserAlbumPic."=:UserAlbumPicId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("UserAlbumPicId", $userAlbumPicId);
        $result = $this->dbOperator->GetArray($sql, $dataProperty);
        return $result;
    }
}