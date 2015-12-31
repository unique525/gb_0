<?php

/**
 * 投票调查题目前台数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Vote
 * @author yanjiuyuan
 */
class VoteSelectItemPublicData extends BasePublicData {
    /**
     * 题目选项提交
     * @param int $voteSelectItemId  题目选项id
     * @return int  返回执行结果
     */
    public function UpdateCount($voteSelectItemId) {
        $sqlStr = "UPDATE " . self::TableName_VoteSelectItem . " SET RecordCount = RecordCount+1 WHERE VoteSelectItemId=:VoteSelectItemId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("VoteSelectItemId", $voteSelectItemId);
        $result = $this->dbOperator->Execute($sqlStr, $dataProperty);
        return $result;
    }

    /**
     * 根据题目选项id集合数组，对应的选项票数加1
     * @param array $voteSelectItemIdArr 投票选项id集合数组
     * @return int 返回执行结果
     */
    public function UpdateCountBatch($voteSelectItemIdArr) {
        $strSql=array();
        $dataPropertyArr=array();
        foreach ($voteSelectItemIdArr as $value) {
            $dataProperty = new DataProperty();
            $strSql[] = "update " . self::TableName_VoteSelectItem . " set RecordCount = RecordCount+1 where VoteSelectItemId=:VoteSelectItemId";
            $dataProperty->AddField("VoteSelectItemId", $value);
            $dataPropertyArr[] = $dataProperty;
        }
        $result = $this->dbOperator->ExecuteBatch($strSql, $dataPropertyArr);
        return $result;
    }


    /**
     * 根据投票调查题目id取得 该题目下票数前X条的数据
     * @param int $voteItemId 投票调查题目id
     * @param int $topCount
     * @return array 返回列表数组
     */
    public function GetTopList($voteItemId,$topCount)
    {
        $result = null;
        if ($voteItemId > 0) {
            $sql = "SELECT * FROM " . self::TableName_VoteSelectItem . " WHERE VoteItemId=:VoteItemId ORDER BY RecordCount DESC LIMIT $topCount";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("VoteItemId", $voteItemId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 根据题目ID获取题目下所有选项数据
     * @param int $voteItemId   题目ID,，可以是 id,id,id 的形式
     * @param int $state    题目选项状态
     * @param string $order    排序
     * @param int $topCount    题目选项条数
     * @param string $beginDate  开始时间
     * @param string $endDate  结束时间
     * @return array  返回查询题目选项数组
     */
    public function GetList($voteItemId,$state,$order = "",$topCount = null,$beginDate="",$endDate="") {
        $result = null;
        if ($topCount != null)
            $topCount = " limit " . $topCount;
        switch ($order) {
            default:
                $order = " ORDER BY Sort,VoteSelectItemId ASC ";
                break;
        }


        if($beginDate!=""&&$endDate!=""){
            $strSelectDate=" AND PublishDate>='$beginDate' AND PublishDate<='$endDate' ";
        }else{
            $strSelectDate="";
        }

        if($voteItemId>0)
        {
            $voteItemId = Format::FormatSql($voteItemId);
            $sql = "SELECT t2.VoteItemId,t2.VoteSelectItemId,t2.VoteSelectItemTitle,t2.Sort,t2.State,t2.AddCount,t2.RecordCount,t2.DirectUrl,t2.Type,t2.Author,t2.Editor,t2.PublishDate,t2.PageNo,
                    CASE t1.VoteItemType WHEN '0' THEN 'radio' ELSE 'checkbox' END AS VoteItemTypeName,
                    t3.*
                    FROM " . self::TableName_VoteItem . " t1
                    LEFT OUTER JOIN " . self::TableName_VoteSelectItem . " t2 ON t1.VoteItemId=t2.VoteItemId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." t3 on t2.TitlePic1UploadFileId=t3.UploadFileId
                    WHERE t2.State=:State
                    AND t2.VoteItemId IN ($voteItemId) $strSelectDate "
                . $order
                . $topCount;

            $dataProperty = new DataProperty();
            $dataProperty->AddField("State", $state);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 根据题目ID获取题目下所有选项数据与关联资讯文档的相关内容
     * @param int $voteItemId   题目ID,，可以是 id,id,id 的形式
     * @param int $state    题目选项状态
     * @param string $order    排序
     * @param int $topCount    题目选项条数
     * @return array  返回查询题目选项数组
     */
    public function GetListWithDocumentNews($voteItemId,$state,$order = "",$topCount = null) {
        $result = null;
        if ($topCount != null)
            $topCount = " limit " . $topCount;
        switch ($order) {
            default:
                $order = " ORDER BY Sort,VoteSelectItemId ASC ";
                break;
        }
        if($voteItemId>0)
        {
            $voteItemId = Format::FormatSql($voteItemId);
            $sql = "SELECT
            t2.VoteItemId,
            t2.VoteSelectItemId,
            t2.VoteSelectItemTitle,
            t2.Sort,
            t2.State,
            t2.AddCount,
            t2.RecordCount,
            t2.DirectUrl,t2.Type,t2.Author,t2.Editor,t2.PublishDate,t2.PageNo,
            CASE t1.VoteItemType WHEN '0' THEN 'radio' ELSE 'checkbox' END AS VoteItemTypeName,
            t3.*,
            doc.DocumentNewsSubTitle,
            doc.DocumentNewsCiteTitle,
            doc.DocumentNewsShortTitle,
            doc.DocumentNewsIntro
                    FROM " . self::TableName_VoteItem . " t1
                    LEFT OUTER JOIN " . self::TableName_VoteSelectItem . " t2 ON t1.VoteItemId=t2.VoteItemId
                    LEFT OUTER JOIN " .self::TableName_DocumentNews." doc on t2.TableId=doc.DocumentNewsId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." t3 on doc.TitlePic1UploadFileId=t3.UploadFileId
                    WHERE t2.State=:State
                    AND t2.VoteItemId IN ($voteItemId)"
                . $order
                . $topCount;
            $dataProperty = new DataProperty();
            $dataProperty->AddField("State", $state);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

        }
        return $result;
    }


}

?>
