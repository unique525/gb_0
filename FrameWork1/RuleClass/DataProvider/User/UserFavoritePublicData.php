<?php

/**
 * 前台管理 会员收藏 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author yin
 */
class UserFavoritePublicData extends BasePublicData
{
    /**
     * @param int $userId 会员Id
     * @param int $tableId 表Id
     * @param int $tableType 收藏类型
     * @param int $siteId 站点Id
     * @param string $userFavoriteTitle 收藏的标题
     * @param string $userFavoriteUrl 收藏的地址
     * @param int $userFavoriteUploadFileId 收藏的图片Id
     * @param string $userFavoriteTag 收藏标签
     * @return int 最后插入的Id
     */
    public function Create($userId,$tableId,$tableType,$siteId,$userFavoriteTitle,$userFavoriteUrl,$userFavoriteUploadFileId,$userFavoriteTag){
        $result = -1;
        if($userId > 0 && $tableId > 0 && $tableType > 0){
            $sql = "INSERT INTO ".self::TableName_UserFavorite
                ." (UserId,TableId,TableType,SiteId,UserFavoriteTitle,UserFavoriteUrl,UserFavoriteUploadFileId,CreateDate,UserFavoriteTag)
                VALUES (:UserId,:TableId,:TableType,:SiteId,:UserFavoriteTitle,:UserFavoriteUrl,:UserFavoriteUploadFileId,now(),:UserFavoriteTag);";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId",$userId);
            $dataProperty->AddField("TableId",$tableId);
            $dataProperty->AddField("TableType",$tableType);
            $dataProperty->AddField("SiteId",$siteId);
            $dataProperty->AddField("UserFavoriteTitle",$userFavoriteTitle);
            $dataProperty->AddField("UserFavoriteUrl",$userFavoriteUrl);
            $dataProperty->AddField("UserFavoriteUploadFileId",$userFavoriteUploadFileId);
            $dataProperty->AddField("UserFavoriteTag",$userFavoriteTag);
            $result = $this->dbOperator->LastInsertId($sql,$dataProperty);

        }
        return $result;

    }

    /**
     * 获取会员收藏的列表
     * @param int $userId 用户Id
     * @param int $siteId 站点Id
     * @param int $pageBegin 从pageBegin开始
     * @param int $pageSize 查询pageSize条记录
     * @param int $allCount 总行数
     * @return array|null 会员收藏的列表
     */
    public function GetList($userId,$siteId,$pageBegin,$pageSize,&$allCount){
        $result = null;
        if($userId > 0){
            $sql = "SELECT uf.*,uf2.UploadFilePath FROM ".self::TableName_UserFavorite." uf LEFT JOIN ".self::TableName_UploadFile.
                " uf2 ON uf.UserFavoriteUploadFileId = uf2.UploadFileId WHERE uf.UserId = :UserId AND uf.SiteId = :SiteId ORDER BY CreateDate DESC LIMIT ".$pageBegin.",".$pageSize.";";
            $sqlCount = "SELECT count(*) FROM ".self::TableName_UserFavorite." uf LEFT JOIN ".self::TableName_UploadFile.
                " uf2 ON uf.UserFavoriteUploadFileId = uf2.UploadFileId WHERE uf.UserId = :UserId AND uf.SiteId = :SiteId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId",$userId);
            $dataProperty->AddField("SiteId",$siteId);
            $result = $this->dbOperator->GetArrayList($sql,$dataProperty);
            $allCount = $this->dbOperator->GetInt($sqlCount,$dataProperty);
        }
        return $result;
    }

    /**
     * 获取最近收藏的列表
     * @param int $userId 用户Id
     * @param int $siteId 站点Id
     * @param int $pageBegin 从pageBegin开始
     * @param int $pageSize 查询pageSize条记录
     * @param int $allCount 总行数
     * @return array|null 会员收藏的列表
     */
    public function GetListForRecentUserFavorite($userId,$siteId,$pageBegin,$pageSize,&$allCount){
        $result = null;
        if($userId > 0){
            $sql = "SELECT uf.*,uf2.UploadFilePath FROM ".self::TableName_UserFavorite." uf LEFT JOIN ".self::TableName_UploadFile." uf2
                            ON uf.UserFavoriteUploadFileId = uf2.UploadFileId WHERE uf.UserId = :UserId AND uf.SiteId = :SiteId
                            ORDER BY uf.CreateDate DESC LIMIT ".$pageBegin.",".$pageSize.";";
            $sqlCount = "SELECT count(*) FROM ".self::TableName_UserFavorite." WHERE UserId = :UserId AND SiteId = :SiteId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId",$userId);
            $dataProperty->AddField("SiteId",$siteId);
            $result = $this->dbOperator->GetArrayList($sql,$dataProperty);
            $allCount = $this->dbOperator->GetInt($sqlCount,$dataProperty);
        }
        return $result;
    }

    /**
     * 删除
     * @param int $userFavoriteId 会员收藏Id
     * @param int $userId 会员Id
     * @return int 影响行数
     */
    public function Delete($userFavoriteId,$userId){
        $result = -1;
        if($userFavoriteId > 0 && $userId > 0){
            $sql = "DELETE FROM ".self::TableName_UserFavorite." WHERE UserId = :UserId AND UserFavoriteId = :UserFavoriteId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId",$userId);
            $dataProperty->AddField("UserFavoriteId",$userFavoriteId);
            $result = $this->dbOperator->Execute($sql,$dataProperty);
        }
        return $result;
    }

    public function CheckIsExist($tableId,$tableType){
        $result = -1;
        if($tableId > 0 && $tableType > 0){
            $sql = "SELECT count(*) FROM ".self::TableName_UserFavorite." WHERE TableId = :TableId AND TableType = :TableType;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("TableId",$tableId);
            $dataProperty->AddField("TableType",$tableType);
            $result = $this->dbOperator->GetInt($sql,$dataProperty);
        }
        return $result;

    }
} 