<?php

/**
 * 前台投票调查记录数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_VoteRecord
 * @author hy
 */
class VoteRecordData extends BasePublicData {


    /**
     * 新建投票调查记录
     * @param int $voteId  投票调查Id
     * @param int $userId  用户Id
     * @param string $ipAddress   用户IP
     * @param string $agent   用户系统信息
     * @param string $createDate  创建时间
     * @return int  返回执行结果
     */
    public function Create($voteId, $userId, $ipAddress, $agent, $createDate) {
        $sql = "INSERT INTO " . self::TableName_VoteRecord . " (VoteId,UserId,ipAddress,Agent,CreateDate) values (:VoteId,:UserId,:ipAddress,:Agent,:CreateDate) ";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("VoteId", $voteId);
        $dataProperty->AddField("UserId", $userId);
        $dataProperty->AddField("CreateDate", $createDate);
        $dataProperty->AddField("IpAddress", $ipAddress);
        $dataProperty->AddField("Agent", $agent);
        $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        return $result;
    }

    /**
     * 新建投票记录
     * @param array $voteRecordId    投票调查记录表Id
     * @param array $voteRecordDetail  投票调查明细记录数组
     * @return int 返回执行结果
     */
    public function CreateDetailBatch($voteRecordId,$voteRecordDetail) {
        $sql=array();
        $dataPropertyList=array();
        foreach ($voteRecordDetail as $Row) {
            $oneArr["VoteRecordId"]=$voteRecordId;
            $dataProperty = new DataProperty();
            $sql[] = "INSERT INTO " . self::TableName_VoteRecordDetail . " (VoteRecordId,VoteItemId,VoteSelectItemId) VALUES (:VoteRecordId,:VoteItemId,:VoteSelectItemId) ";
            $dataProperty->ArrayField = $Row;
            $dataPropertyList[] = $dataProperty;
        }
        $result = $this->dbOperator->ExecuteBatch($sql, $dataPropertyList);
        return $result;
    }

    /**
     * 获取IP当天投票数
     * @param int $voteId   投票调查Id
     * @param string $ipAddress    用户Ip
     * @param string $nowDate 当天日期
     * @return int  返回执行结果
     */
    public function GetIpCount($voteId, $ipAddress, $nowDate) {
        $sql = "SELECT COUNT(*)
        FROM " . self::TableName_VoteRecord . "
        WHERE VoteId=:VoteId AND IpAddress=:IpAddress
        AND createdate>'" . $nowDate . " 00:00:00' AND CreateDate<'" . $nowDate . " 23:59:59'";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("VoteId", $voteId);
        $dataProperty->AddField("IpAddress", $ipAddress);
        $result = $this->dbOperator->GetInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 获取用户Id当天提交数
     * @param int $voteId   投票调查Id
     * @param string $userId    用户Id
     * @param string $nowDate 当天日期
     * @return int  返回执行结果
     */
    public function GetUserCount($voteId, $userId, $nowDate) {
        $sql = "SELECT COUNT(*) FROM " . self::TableName_VoteRecord . "
         WHERE VoteId=:VoteId AND UserId=:UserId
         AND CreateDate>'" . $nowDate . " 00:00:00' AND CreateDate<'" . $nowDate . " 23:59:59'";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("VoteId", $voteId);
        $dataProperty->AddField("UserId", $userId);
        $result = $this->dbOperator->GetInt($sql, $dataProperty);
        return $result;
    }

}

?>
