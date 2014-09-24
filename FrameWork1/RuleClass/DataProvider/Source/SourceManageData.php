<?php
/**
 * 后台管理 来源 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Source
 * @author zhangchi
 */
class SourceManageData extends BaseManageData {

    /**
     * 为选择页面选择列表数据
     * @param string $sourceTag 来源标签
     * @return array 列表数据
     */
    public function GetListForSelect($sourceTag = '') {
        if($sourceTag === ''){
            $sql = "SELECT * FROM " . self::TableName_Source . "

                    WHERE State<100

                    ORDER BY Sort DESC,IndexWord;";

            $result = $this->dbOperator->GetArrayList($sql, null);
        }
        else {
            $sql = "SELECT * FROM " . self::TableName_Source . "

                    WHERE SourceTag=:SourceTag AND State<100

                    ORDER BY Sort DESC,IndexWord;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SourceTag", $sourceTag);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 查询来源数据
     * @param int $pageBegin 分页第一条序号
     * @param int $pageSize 分页条数
     * @param int $allCount 总条数
     * @param string $searchKey 搜索关键字
     * @param string $sourceTag 来源标签
     * @return array 查询结果
     */
    public function GetList(
        $pageBegin,
        $pageSize,
        &$allCount,
        $searchKey="",
        $sourceTag =""
    ) {
        $sql = "SELECT * FROM " . self::TableName_Source . "  WHERE SourceName != '' ";
        $countSql = "SELECT count(*) FROM " . self::TableName_Source ."  WHERE SourceName != '' ";
        $dataProperty = new DataProperty();
        if (strlen($searchKey) > 0 && $searchKey != "undefined") {
            $sql .= " AND SourceName LIKE :SearchKey";
            $countSql .= " AND SourceName LIKE :SearchKey";
            $dataProperty->AddField("SearchKey", "%".$searchKey."%");
        }
        if (strlen($sourceTag) > 0 && $sourceTag != "undefined") {
            $sql .= " AND SourceTag = :SourceTag";
            $countSql .= " AND SourceTag = :SourceTag";
            $dataProperty->AddField("SourceTag", $sourceTag);
        }
        $sql .=" ORDER BY sort DESC,SourceTag,SourceId LIMIT " . $pageBegin . "," . $pageSize . "";

        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        $allCount = $this->dbOperator->GetInt($countSql, $dataProperty);
        return $result;
    }
} 