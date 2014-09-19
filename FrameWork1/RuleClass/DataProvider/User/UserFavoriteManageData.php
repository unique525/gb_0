<?php

/**
 * 后台管理 会员收藏 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author yin
 */
class UserFavoriteManageData extends BaseManageData
{
    /**
     * 获取会员收藏的列表
     * @param int $siteId 站点Id
     * @param int $pageBegin 从pageBegin开始查询
     * @param int $pageSize 取pageSize条数据
     * @param int $allCount 所有行数
     * @param string $searchKey 搜索关键字
     * @return array|null 会员收藏的列表
     */
    public function GetList($siteId,$pageBegin,$pageSize,&$allCount,$searchKey){
        $result = null;
        if($siteId > 0){
            $dataProperty = new DataProperty();
            $searchSql = "";
            if(!empty($searchKey) && $searchKey != "undefined"){
                $searchSql = " AND ((u.UserName LIKE '%".$searchKey."%') OR (uf.UserFavoriteTitle LIKE '%".$searchKey."%'))";
            }
            $sql = "SELECT uf.*,u.UserName FROM ".self::TableName_UserFavorite." uf,".self::TableName_User." u
                WHERE u.UserId = uf.UserId".$searchSql." AND uf.SiteId = :SiteId LIMIT " . $pageBegin . "," . $pageSize .";";
            $sqlCount = "SELECT count(*) FROM ".self::TableName_UserFavorite." uf,".self::TableName_User." u
                WHERE u.UserId = uf.UserId".$searchSql." AND uf.SiteId = :SiteId;";
            $dataProperty->AddField("SiteId",$siteId);
            $result = $this->dbOperator->GetArrayList($sql,$dataProperty);
            $allCount = $this->dbOperator->GetInt($sqlCount, $dataProperty);
        }
        return $result;
    }

    /**
     * 删除
     * @param int $userFavoriteId 会员收藏Id
     * @param int $siteId 站点Id
     * @return int 影响行数
     */
    public function Delete($userFavoriteId,$siteId){
        $result = -1;
        if($userFavoriteId > 0 && $siteId > 0){
            $sql = "DELETE FROM ".self::TableName_UserFavorite." WHERE SiteId = :SiteId AND UserFavoriteId = :UserFavoriteId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserFavoriteId",$userFavoriteId);
            $dataProperty->AddField("SiteId",$siteId);
            $result = $this->dbOperator->Execute($sql,$dataProperty);
        }
        return $result;
    }
} 