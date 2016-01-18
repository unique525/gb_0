<?php

/**
 * 客户端 客户端顶部栏目 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_ClientColumn
 * @author zhangchi
 */
class ClientColumnClientData extends BaseClientData
{

    /**
     * 取得列表数据集
     * @param int $siteId 站点id
     * @return array 列表数据集
     */
    public function GetListOfSite($siteId)
    {
        $dataProperty = new DataProperty();
        $dataProperty->AddField("SiteId", $siteId);

        $sql = "SELECT * FROM " . self::TableName_ClientColumn . "
                WHERE SiteId=:SiteId AND State<100
                ORDER BY CurrentIndex";

        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

        return $result;
    }


}