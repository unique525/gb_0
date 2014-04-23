<?php

/**
 * 投票调查 题目选项 数据类
 * @category iCMS
 * @package iCMS_Rules_DataProvider_Vote
 * @author hy
 */
class VoteSelectItemManageData extends BaseManageData {


    /**
     * 新建选项
     * @param array $httpPostData $_post数组
     * @param string $titlePicPath 题图路径
     * @return int  返回选项Id
     */
    public function Create($httpPostData,$titlePicPath = "") {
        $dataProperty = new DataProperty();
        $sql = parent::GetInsertSql($httpPostData, self::TableName_VoteSelectItem, $dataProperty,"titlePic", $titlePicPath);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->LastInsertId($sql, $dataProperty);
        return $result;
    }

    /**
     * 获取一个选项的数据
     * @param int $voteSelectItemId  选项Id号
     * @return array  选项一维数组
     */
    public function GetOne($voteSelectItemId) {
        $sql = "SELECT VoteSelectItemId,VoteItemId,Sort,State,VoteSelectItemTitle,RecordCount,AddCount,TitlePic,DirectUrl
        FROM " . self::TableName_VoteSelectItem . "
        WHERE voteSelectItemId=:voteSelectItemId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("voteSelectItemId", $voteSelectItemId);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->ReturnRow($sql, $dataProperty);
        return $result;
    }

    /**
     * 修改选项
     * @param array $httpPostData $_post数组
     * @param int $voteSelectItemId
     * @param string $titlePicPath 题图路径
     * @return int  返回执行结果
     */
    public function Modify($httpPostData,$voteSelectItemId,$titlePicPath = "") {
        $dataProperty = new DataProperty();
        $sql = parent::GetUpdateSql($httpPostData, self::TableName_VoteSelectItem, self::TableId_VoteSelectItem, $voteSelectItemId, $dataProperty,"titlePic", $titlePicPath);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 停用选项
     * @param int $voteSelectItemId  选项Id号
     * @return int  返回执行结果
     */
    public function RemoveBin($voteSelectItemId) {
        $sql = "update " . self::TableName_VoteSelectItem . " set State=100 where voteSelectItemId=:voteSelectItemId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("voteSelectItemId", $voteSelectItemId);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 获取一道题的所有选项列表
     * @param int $voteItemId  题目ID号
     * @param int $state   题目选项状态值
     * @return array  返回数据集结果
     */
    public function GetVoteSelect($voteItemId, $state) {
        $sql = "SELECT VoteSelectItemId,VoteSelectItemTitle,RecordCount,AddCount,TitlePic,DirectUrl,TableType,TableId
        FROM " . self::TableName_VoteSelectItem . "
        WHERE State=:state AND VoteItemId=:VoteItemId
        ORDER BY Sort DESC,VoteSelectItemId Asc";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("State", $state);
        $dataProperty->AddField("VoteItemId", $voteItemId);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->ReturnArray($sql, $dataProperty);
        return $result;
    }

    /**
     * 获取一道题目下选项总票数
     * @param int $VoteItemId  选项Id号
     * @return int  返回选项总票数
     */
    public function GetSum($VoteItemId) {
        $sql = "select sum(AddCount) from " . self::TableName_VoteSelectItem . " where State=0 and VoteItemId=:VoteItemId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("VoteItemId", $VoteItemId);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->ReturnInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 获取选项分页列表
     * @param int $pageBegin   起始页码
     * @param int $pageSize    每页记录数
     * @param int $allCount    记录总数
     * @param int $voteItemId  题目Id
     * @param string $searchKey   查询字符
     * @return array  选项列表数组
     */
    public function GetListPager($pageBegin, $pageSize, &$allCount, $voteItemId = 0, $searchKey = "") {
        $dataProperty = new DataProperty();
        $searchSql = "where";
        if ($voteItemId > 0) {
            $searchSql .= " VoteItemId=:VoteItemId and";
            $dataProperty->AddField("VoteItemId", $voteItemId);
        }
        if (strlen($searchKey) > 0 && $searchKey != "undefined") {
            $searchSql .= " (VoteTitle like :searchKey1) and";
            $dataProperty->AddField("searchKey1", "%" . $searchKey . "%");
        }
        if (strlen($searchSql) > 5)
            $searchSql = substr($searchSql, 0, strlen($searchSql) - 3);
        else
            $searchSql = "";
        $sql = "SELECT VoteItemId,VoteSelectItemId,VoteSelectItemTitle,Sort,State,AddCount,RecordCount
        FROM " . self::TableName_VoteSelectItem . " " . $searchSql . "
        ORDER BY Sort DESC,VoteSelectItemId Asc
        LIMIT " . $pageBegin . "," . $pageSize . "";
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->ReturnArray($sql, $dataProperty);
        $sql = "SELECT count(*) FROM " . self::TableName_VoteSelectItem . " " . $searchSql;
        $allCount = $dbOperator->ReturnInt($sql, $dataProperty);
        return $result;
    }

    /**
     * 修改选项的加票数
     * @param int $voteSelectItemId  选项Id号
     * @param int $addCount  选项加票数
     * @return int  执行结果
     */
    public function ModifyAddCount($voteSelectItemId,$addCount) {
        $sql = "update  " . self::TableName_VoteSelectItem . " set AddCount=:AddCount where State=0 and VoteSelectItemId=:VoteSelectItemId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("VoteSelectItemId", $voteSelectItemId);
        $dataProperty->AddField("AddCount", $addCount);
        $dbOperator = DBOperator::getInstance();
        $result = $dbOperator->ReturnInt($sql, $dataProperty);
        return $result;
    }
}

?>
