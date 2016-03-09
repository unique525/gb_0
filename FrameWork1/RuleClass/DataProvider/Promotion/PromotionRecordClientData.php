<?php
/**
 * 客户端 邀请码登记记录 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Promotion
 * @author zhangchi
 */
class PromotionRecordClientData extends BaseClientData {

    /**
     * @param String $deviceNumber 设备唯一编号
     * @return bool $result 是否已经存在重复记录标志
     */
    public function CheckIsExist($deviceNumber){
        $result = false;
        if(!empty($deviceNumber)){
            $sql = "SELECT count(*) FROM ".self::TableName_PromotionRecord." WHERE DeviceNumber = :DeviceNumber";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("DeviceNumber",$deviceNumber);
            $count = $this->dbOperator->GetInt($sql,$dataProperty);
            if($count>0){
                $result=true;
            }
        }
        return $result;
    }

    /**
     * @param int $promoterId 推广员编号
     * @param String $createDate 邀请码登记时间
     * @param int $deviceType 设备类型
     * @param String $deviceNumber 设备唯一编码
     * @param int $userId 用户Id
     * @return int 最后插入的Id
     */
    public function Create($promoterId,$createDate,$deviceType,$deviceNumber,$userId){
        $result = -1;
        if($promoterId > 0 && !empty($deviceNumber)){
            $sql = "INSERT INTO ".self::TableName_PromotionRecord
                ." (PromoterId,CreateDate,DeviceType,DeviceNumber,UserId)
                VALUES (:PromoterId,:CreateDate,:DeviceType,:DeviceNumber,:UserId);";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("PromoterId",$promoterId);
            $dataProperty->AddField("CreateDate",$createDate);
            $dataProperty->AddField("DeviceType",$deviceType);
            $dataProperty->AddField("DeviceNumber",$deviceNumber);
            $dataProperty->AddField("UserId",$userId);
            $result = $this->dbOperator->LastInsertId($sql,$dataProperty);
        }
        return $result;

    }

    /**
     * 得到一行信息
     * @param int $promotionRecordId 推广码登记记录id
     * @param bool $withCache 是否缓存
     * @return array 单表数组
     */
    public function GetOne($promotionRecordId, $withCache = false)
    {
        $result = null;
        if ($promotionRecordId > 0) {
            $sql = "
            SELECT *
            FROM " . self::TableName_PromotionRecord . "
            WHERE PromotionRecordId = :PromotionRecordId;";

            $dataProperty = new DataProperty();
            $dataProperty->AddField("PromotionRecordId", $promotionRecordId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
        }
        return $result;
    }
} 