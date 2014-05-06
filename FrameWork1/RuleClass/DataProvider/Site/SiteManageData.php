<?php

/**
 * 后台管理 站点 后台数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Site
 * @author zhangchi
 */
class SiteManageData extends BaseManageData {

    /**
     * 根据后台管理员id返回此管理员可以管理的站点列表数据集
     * @param int $adminUserId 后台管理员id
     * @return array 站点列表数据集
     */
    public function GetList($adminUserId) {
        $dataProperty = new DataProperty();
        if ($adminUserId == 1) {
            $sql = "SELECT * FROM ".self::TableName_Site." WHERE State<10 ORDER BY Sort DESC,convert(SiteName USING gbk);";
        } else {
            $sql = "SELECT * FROM ".self::TableName_Site." WHERE State<10 AND
                SiteId IN 
                ( SELECT SiteId FROM ".self::T." WHERE AdminUserId=:adminuserid1
                  UNION 
                  SELECT SiteId FROM cst_adminpopedom WHERE AdminUserGroupId IN (SELECT adminusergroupid FROM cst_adminuser WHERE adminuserid=:adminuserid2)
                 ) 
                 ORDER BY Sort DESC,convert(SiteName USING gbk);";


            $dataProperty->AddField("adminuserid1", $adminUserId);
            $dataProperty->AddField("adminuserid2", $adminUserId);
        }
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        return $result;
    }
    
    
    /**
     * 取得站点IETitle
     * @param <type> $siteId
     * @return <type>
     */
    public function GetIETitle($siteId) {
        $sql = "SELECT IETitle FROM ".self::tableName." WHERE siteid=:siteid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("siteid", $siteId);
        $result = $this->dbOperator->GetString($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得站点IEDescription
     * @param <type> $siteId
     * @return <type>
     */
    public function GetIEDescription($siteId) {
        $sql = "SELECT IEDescription FROM ".self::tableName." WHERE siteid=:siteid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("siteid", $siteId);
        $result = $this->dbOperator->GetString($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得站点IEKeywords
     * @param <type> $siteId
     * @return <type>
     */
    public function GetIEKeywords($siteId) {
        $sql = "SELECT IEKeywords FROM ".self::tableName." WHERE siteid=:siteid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("siteid", $siteId);
        $result = $this->dbOperator->GetString($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得站点类型
     * @param <type> $siteId
     * @return <type>
     */
    public function GetSiteType($siteId) {
        $sql = "SELECT SiteType FROM ".self::tableName." WHERE siteid=:siteid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("siteid", $siteId);
        $result = $this->dbOperator->GetString($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得站点状态
     * @param type $siteId
     * @return type 
     */
    public function GetState($siteId) {
        $sql = "SELECT State FROM ".self::tableName." WHERE siteid=:siteid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("siteid", $siteId);
        $result = $this->dbOperator->GetInt($sql, $dataProperty);
        return $result;
    }
    
    /**
     * 取得站点URL
     * @param int $siteId
     * @return string 
     */
    public function GetSiteUrl($siteId) {
        $sql = "SELECT siteurl FROM ".self::tableName." WHERE siteid=:siteid";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("siteid", $siteId);
        $result = $this->dbOperator->GetString($sql, $dataProperty);
        return $result;
    }

}

?>
