<?php

/**
 * 站点后台数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Site
 * @author zhangchi
 */
class SiteManageData extends BaseManageData {
    /**
     * 表名
     */
    const tableName = "cst_site";

    /**
     * 表关键字段名
     */
    const tableIdName = "siteid";

    /**
     * 根据后台管理员id返回此管理员可以管理的站点列表数据集
     * @param int $adminUserId 后台管理员id
     * @return array 站点列表数据集
     */
    public function GetList($adminUserId) {
        if ($adminUserId == 1) {
            $sql = "SELECT * FROM ".self::tableName." WHERE State<10 ORDER BY Sort DESC,convert(SiteName USING gbk);";
        } else {
            $sql = "SELECT * FROM ".self::tableName." WHERE State<10 AND 
                SiteId IN 
                ( SELECT SiteId FROM cst_adminpopedom WHERE AdminUserId=:adminuserid1 
                  UNION 
                  SELECT SiteId FROM cst_adminpopedom WHERE AdminUserGroupId IN (SELECT adminusergroupid FROM cst_adminuser WHERE adminuserid=:adminuserid2)
                 ) 
                 ORDER BY Sort DESC,convert(SiteName USING gbk);";
        }
        $dataProperty = new DataProperty();
        $dataProperty->AddField("adminuserid1", $adminUserId);
        $dataProperty->AddField("adminuserid2", $adminUserId);
        $result = $this->dbOperator->ReturnArray($sql, $dataProperty);
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
        $result = $this->dbOperator->ReturnString($sql, $dataProperty);
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
        $result = $this->dbOperator->ReturnString($sql, $dataProperty);
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
        $result = $this->dbOperator->ReturnString($sql, $dataProperty);
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
        $result = $this->dbOperator->ReturnString($sql, $dataProperty);
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
        $result = $this->dbOperator->ReturnInt($sql, $dataProperty);
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
        $result = $this->dbOperator->ReturnString($sql, $dataProperty);
        return $result;
    }

}

?>
