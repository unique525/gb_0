<?php

/**
 * 前台 客户端直接转向 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Channel
 * @author zhangchi
 */
class ClientDirectUrlPublicData extends BasePublicData
{
    /**
     * 获取DirectUrl
     * @param int $clientDirectUrlId
     * @return  String DirectUrl
     */
    public function GetDirectUrl($clientDirectUrlId) {
        $result = -1;
        if ($clientDirectUrlId > 0) {
            $sql = "SELECT DirectUrl FROM  " . self::TableName_Client_Direct_Url . " WHERE ClientDirectUrlId =:ClientDirectUrlId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ClientDirectUrlId", $clientDirectUrlId);
            $result = $this->dbOperator->GetString($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 增加一个点击
     * @param int $clientDirectUrlId id
     * @return int 操作结果
     */
    public function AddHit($clientDirectUrlId){
        $result = -1;
        if($clientDirectUrlId > 0){
            $sql = "UPDATE ".self::TableName_Client_Direct_Url." SET Hit = Hit+1 WHERE ClientDirectUrlId=:ClientDirectUrlId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ClientDirectUrlId",$clientDirectUrlId);
            $result = $this->dbOperator->Execute($sql,$dataProperty);
        }
        return $result;
    }

}