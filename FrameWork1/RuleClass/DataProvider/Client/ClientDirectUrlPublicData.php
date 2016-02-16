<?php

/**
 * ǰ̨ �ͻ���ֱ��ת�� ������
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Channel
 * @author zhangchi
 */
class ClientDirectUrlPublicData extends BasePublicData
{
    /**
     * ��ȡDirectUrl
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
     * ����һ�����
     * @param int $clientDirectUrlId id
     * @return int �������
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