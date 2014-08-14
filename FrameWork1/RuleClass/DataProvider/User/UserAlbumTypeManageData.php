<?php

/**
 * 后台管理 会员相册类别 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author yin
 */
class UserAlbumTypeManageData extends BaseManageData {

    /**
     * 新增一个相册类别
     * @return int 最后插入的Id
     */
    public function Create($siteId){
        $dataProperty = new DataProperty();
        if (!empty($httpPostData)) {
            $sql = parent::GetInsertSql($httpPostData, self::TableName_UserAlbumType, $dataProperty,"siteid",$siteId);
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
            return $result;
        }else{
            return null;
        }
    }

    /**
     * 修改
     * @param int $userAlbumTypeId 相册类别Id
     * @return int 影响的行数
     */
    public function Modify($userAlbumTypeId){
        if($userAlbumTypeId > 0 && !empty($httpPostData)){
            $sql = parent::GetUpdateSql($httpPostData, self::TableName_UserAlbumType, self::TableId_UserAlbumType, $userAlbumTypeId, $dataProperty);
            $dataProperty = new DataProperty();
            $result = $this->dbOperator->Execute($sql,$dataProperty);
            return $result;
        }else{
            return null;
        }
    }

    /**
     * 获取相册类别列表
     * @param int $siteId 站点Id
     * @return int 站点下所有的相册类别
     */
    public function GetList($siteId) {
        if($siteId > 0){
            $sql = "SELECT UserAlbumTypeId,UserAlbumTypeName,SiteId,State FROM ".self::TableName_UserAlbumType." WHERE SiteId=:SiteId AND State<100;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
            return $result;
        }else{
            return null;
        }
    }

    /**
     * 获取一个相册类别的信息
     * @param int $userAlbumTypeId 相册类别Id
     * @return array 一个相册类别的信息列表
     */
    public function GetOne($userAlbumTypeId){
        if($userAlbumTypeId > 0){
            $sql = "SELECT * FROM ".self::TableName_UserAlbumType." WHERE UserAlbumTypeId = :UserAlbumTypeId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserAlbumTypeId",$userAlbumTypeId);
            $result = $this->dbOperator->GetArray($sql,$dataProperty);
            return $result;
        }else{
            return null;
        }
    }

    /**
     * 删除
     * @param int $userAlbumTypeId 相册类别Id
     * @return int 影响的行数
     */
    public function Delete($userAlbumTypeId){
        if($userAlbumTypeId > 0){
            $sql = "DELETE FROM ".self::TableName_UserAlbumType." WHERE UserAlbumTypeId = :UserAlbumTypeId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserAlbumTypeId",$userAlbumTypeId);
            $result = $this->dbOperator->Execute($sql,$dataProperty);
            return $result;
        }else{
            return null;
        }
    }
} 