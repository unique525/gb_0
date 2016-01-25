<?php

/**
 * 前台投票调查记录数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_VoteRecord
 * @author hy
 */
class VoteRecordData extends BasePublicData
{


    /**
     * 新建投票调查记录
     * @param int $voteId 投票调查Id
     * @param int $userId 用户Id
     * @param string $ipAddress 用户IP
     * @param string $agent 用户系统信息
     * @param string $createDate 创建时间
     * @return int  返回执行结果
     */
    public function Create($voteId, $userId, $ipAddress, $agent, $createDate)
    {
        $sql = "INSERT INTO " . self::TableName_VoteRecord . " (VoteId,UserId,ipAddress,Agent,CreateDate) values (:VoteId,:UserId,:IpAddress,:Agent,:CreateDate) ";
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
     * 新建投票调查记录
     * @param int $voteId 投票调查Id
     * @param int $voteItemId 投票题目Id
     * @param int $userId 用户Id
     * @param string $ipAddress 用户IP
     * @param string $agent 用户系统信息
     * @param string $createDate 创建时间
     * @return int  返回执行结果
     */
    public function CreateScoreRecord($voteId, $voteItemId, $userId, $ipAddress, $agent, $createDate)
    {
        $sql = "INSERT INTO " . self::TableName_VoteRecord . " (VoteId,VoteItemId,UserId,ipAddress,Agent,CreateDate) values (:VoteId,:VoteItemId,:UserId,:IpAddress,:Agent,:CreateDate) ";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("VoteId", $voteId);
        $dataProperty->AddField("VoteItemId", $voteItemId);
        $dataProperty->AddField("UserId", $userId);
        $dataProperty->AddField("CreateDate", $createDate);
        $dataProperty->AddField("IpAddress", $ipAddress);
        $dataProperty->AddField("Agent", $agent);
        $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        return $result;
    }


    /**
     * 批量新建投票调查明细记录
     * @param int $voteRecordId 投票调查记录表Id
     * @param array $voteRecordDetail 投票调查明细记录数组
     * @return int 返回执行结果
     */
    public function CreateDetailBatch($voteRecordId, $voteRecordDetail)
    {
        $sql = array();
        $dataPropertyList=array();
        foreach ($voteRecordDetail as $Row) {
            $Row["VoteRecordId"] = $voteRecordId;
            $dataProperty = new DataProperty();
            $sql[] = "INSERT INTO " . self::TableName_VoteRecordDetail . " (VoteRecordId,VoteItemId,VoteSelectItemId,Score,UserId,Comment) VALUES (:VoteRecordId,:VoteItemId,:VoteSelectItemId,:Score,:UserId,:Comment) ";
            $dataProperty->ArrayField = $Row;
            $dataPropertyList[] = $dataProperty;
        }
        $result = $this->dbOperator->ExecuteBatch($sql, $dataPropertyList);
        return $result;
    }

    /**
     * 获取IP当天投票数
     * @param int $voteId 投票调查Id
     * @param string $ipAddress 用户Ip
     * @param string $nowDate 当天日期
     * @return int  返回执行结果
     */
    public function GetIpCount($voteId, $ipAddress, $nowDate)
    {
        $result = -1;
        if ($voteId > 0) {
            $sql = "SELECT COUNT(*)
        FROM " . self::TableName_VoteRecord . "
        WHERE VoteId=:VoteId AND IpAddress=:IpAddress
        AND createdate>'" . $nowDate . " 00:00:00' AND CreateDate<'" . $nowDate . " 23:59:59'";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("VoteId", $voteId);
            $dataProperty->AddField("IpAddress", $ipAddress);
            $result = $this->dbOperator->GetInt($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 获取用户Id当天提交数
     * @param int $voteId 投票调查Id
     * @param string $userId 用户Id
     * @param string $nowDate 当天日期
     * @return int  返回执行结果
     */
    public function GetUserCount($voteId, $userId, $nowDate)
    {
        $result = -1;
        if ($voteId > 0 && $userId > 0) {
            $sql = "SELECT COUNT(*) FROM " . self::TableName_VoteRecord . "
         WHERE VoteId=:VoteId AND UserId=:UserId
         AND CreateDate>'" . $nowDate . " 00:00:00' AND CreateDate<'" . $nowDate . " 23:59:59'";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("VoteId", $voteId);
            $dataProperty->AddField("UserId", $userId);
            $result = $this->dbOperator->GetInt($sql, $dataProperty);
        }
        return $result;
    }
    /**
     * 删除用户在该投票的投票记录
     * @param int $voteId 投票调查Id
     * @param string $userId 用户Id
     * @return int  返回执行结果
     */
    public function DeleteUserLastRecord($voteId, $userId)
    {
        $result = -1;
        if ($voteId > 0 && $userId > 0) {
            $sql = "DELETE FROM " . self::TableName_VoteRecord . "
         WHERE VoteId=:VoteId AND UserId=:UserId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("VoteId", $voteId);
            $dataProperty->AddField("UserId", $userId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 删除用户在该投票的详细投票记录
     * @param int $voteRecordId 投票记录Id
     * @param string $userId 用户Id
     * @return int  返回执行结果
     */
    public function DeleteUserLastRecordDetail($voteRecordId, $userId)
    {
        $result = -1;
        if ($voteRecordId > 0 && $userId > 0) {
            $sql = "DELETE FROM " . self::TableName_VoteRecordDetail . "
         WHERE VoteRecordId=:VoteRecordId AND UserId=:UserId";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("VoteRecordId", $voteRecordId);
            $dataProperty->AddField("UserId", $userId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }



    /**
     * 取得一条user的投票记录id
     * @param int $voteId 投票调查id
     * @param int $userId
     * @return int 返回id
     */
    public function GetRecordIdOfUser($voteId,$userId)
    {
        $result = -1;
        if ($voteId > 0&&$userId>0) {
            $sql = "SELECT VoteRecordId FROM " . self::TableName_VoteRecord . "
            WHERE UserId=:UserId AND VoteItemId=(SELECT VoteItemId FROM ". self::TableName_VoteItem." WHERE VoteId=:VoteId AND State!=100 LIMIT 1)
            ORDER BY CreateDate DESC LIMIT 1 ;";
            //$sql = "SELECT
            //    detail.*,
            //    rcd.
            //    FROM " . self::TableName_VoteRecordDetail . " detail
            //    LEFT OUTER JOIN " . self::TableName_VoteRecord . " rcd ON detail.VoteSelectItemId=rcd.VoteSelectItemId
            //    WHERE VoteItemId=:VoteItemId ORDER BY RecordCount DESC LIMIT $topCount";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UserId", $userId);
            $dataProperty->AddField("VoteId", $voteId);
            $result = $this->dbOperator->GetInt($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 取得一条投票记录的详细的投票内容
     * @param int $voteRecordId 投票记录id
     * @return array 返回列表数组
     */
    public function GetRecordDetail($voteRecordId)
    {
        $result = null;
        if ($voteRecordId > 0) {
            $sql = "SELECT *
                FROM " . self::TableName_VoteRecordDetail . "
                WHERE VoteRecordId=:VoteRecordId ";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("VoteRecordId", $voteRecordId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 取得打分数据集
     * @param int $voteId 投票id
     * @param string $beginDate
     * @param string $endDate
     * @return array 返回列表数组
     */
    public function GetScoreList($voteId,$beginDate="",$endDate="")
    {
        $result = null;
        if ($voteId > 0) {

            if($beginDate!=""&&$endDate!=""){
                $strSelectDate=" AND PublishDate>='$beginDate' AND PublishDate<='$endDate' ";
            }else{
                $strSelectDate="";
            }


            $sql = "SELECT SUM(vrd.Score) AS Score ,vsi.*
                FROM " . self::TableName_VoteRecordDetail . " vrd
                LEFT OUTER JOIN " . self::TableName_VoteSelectItem . " vsi ON vrd.VoteSelectItemId=vsi.VoteSelectItemId
                INNER JOIN " . self::TableName_VoteItem . " vi ON vi.VoteItemId=vsi.VoteItemId
                WHERE vrd.VoteItemId=(SELECT VoteItemId FROM " . self::TableName_VoteItem . " WHERE VoteId=:VoteId AND State!=100 LIMIT 1) $strSelectDate
                GROUP BY vrd.VoteSelectItemId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("VoteId", $voteId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 取得一条稿件的所有打分记录
     * @param int $voteSelectItemId 选项id
     * @param string $beginDate
     * @param string $endDate
     * @return array 返回列表数组
     */
    public function GetOneScoreDetail($voteSelectItemId,$beginDate="",$endDate="")
    {
        $result = null;
        if ($voteSelectItemId > 0) {

            if($beginDate!=""&&$endDate!=""){
                $strSelectDate=" AND PublishDate>='$beginDate' AND PublishDate<='$endDate' ";
            }else{
                $strSelectDate="";
            }


            $sql = "SELECT vrd.Score, vrd.Comment, u.UserName,u.UserMobile,ui.RealName
                FROM " . self::TableName_VoteRecordDetail . " vrd
                LEFT OUTER JOIN " . self::TableName_User . " u ON vrd.UserId=u.UserId
                LEFT OUTER JOIN " . self::TableName_UserInfo . " ui ON vrd.UserId=ui.UserId
                WHERE vrd.VoteSelectItemId=:VoteSelectItemId $strSelectDate ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("VoteSelectItemId", $voteSelectItemId);

            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;


    }

    /**
     * 获取一条选项的分数分布情况（1分的X个，2分的Y个...）
     * @param $voteSelectItemId
     * @return array
     */
    public function GetScoreDetailOfOneSelectItem($voteSelectItemId){

        $result = array();
        if ($voteSelectItemId > 0) {
            $sql = "SELECT COUNT(*) as Count,Score
                    FROM " . self::TableName_VoteRecordDetail . "
                    WHERE VoteSelectItemId=:VoteSelectItemId group by Score";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("VoteSelectItemId", $voteSelectItemId);

            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }
}

?>
