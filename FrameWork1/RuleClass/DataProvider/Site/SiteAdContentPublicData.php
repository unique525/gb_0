<?php

/**
 * 前台 广告内容 数据类
 *
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_site
 * @author 525
 */
class SiteAdContentPublicData extends BaseData {
    /**
     * 取得广告虚拟点击开启状态
     * @param int $adContentId
     * @return int 虚拟点击开启状态 0：未开启，1：开启
     */
    public function GetOpenVirtualClick($adContentId) {
        $result=-1;
        if($adContentId>0){
            $sql = "SELECT OpenVirtualClick FROM ".self::TableName_SiteAdContent." WHERE AdContentId=:AdContentId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("AdContentId", $adContentId);
            $result = $this->dbOperator->GetInt($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 取得广告虚拟点击每小时限制数
     * @param int $adContentId
     * @return int 虚拟点击每小时限制数
     */
    public function GetVirtualClickLimit($adContentId) {
        $result=-1;
        if($adContentId>0){
            $sql = "SELECT VirtualClickLimit FROM ".self::TableName_SiteAdContent." WHERE AdContentId=:AdContentId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("AdContentId", $adContentId);
            $result = $this->dbOperator->GetInt($sql, $dataProperty);
        }
        return $result;
    }
}

?>