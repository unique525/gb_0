<?php
/**
 * 后台管理 电子版版面 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Newspaper
 * @author zhangchi
 */
class NewspaperPageManageData extends BaseManageData {
    /**
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_NewspaperPage)
    {
        return parent::GetFields(self::TableName_NewspaperPage);
    }

    /**
     * 新增
     * @param array $httpPostData $_POST数组
     * @return int 新增的id
     */
    public function Create($httpPostData)
    {
        $result = -1;
        $dataProperty = new DataProperty();
        $addFieldName = "";
        $addFieldValue = "";
        $preNumber = "";
        $addFieldNames = array();
        $addFieldValues = array();
        if (!empty($httpPostData)) {
            $sql = parent::GetInsertSql(
                $httpPostData,
                self::TableName_NewspaperPage,
                $dataProperty,
                $addFieldName,
                $addFieldValue,
                $preNumber,
                $addFieldNames,
                $addFieldValues
            );
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 修改
     * @param array $httpPostData $_POST数组
     * @param int $newspaperPageId id
     * @return int 返回影响的行数
     */
    public function Modify($httpPostData, $newspaperPageId)
    {
        $result = -1;
        $dataProperty = new DataProperty();
        $addFieldName = "";
        $addFieldValue = "";
        $preNumber = "";
        $addFieldNames = array();
        $addFieldValues = array();
        if (!empty($httpPostData)) {
            $sql = parent::GetUpdateSql(
                $httpPostData,
                self::TableName_NewspaperPage,
                self::TableId_NewspaperPage,
                $newspaperPageId,
                $dataProperty,
                $addFieldName,
                $addFieldValue,
                $preNumber,
                $addFieldNames,
                $addFieldValues
            );
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 修改状态
     * @param int $newspaperPageId id
     * @param int $state 状态
     * @return int 操作结果
     */
    public function ModifyState($newspaperPageId, $state)
    {
        $result = 0;
        if ($newspaperPageId > 0) {

            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'newspaper_page_data';
            $cacheFile = 'newspaper_page_get_state.cache_' . $newspaperPageId . '';
            DataCache::Remove($cacheDir . DIRECTORY_SEPARATOR . $cacheFile);

            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_NewspaperPage . "
                        SET `State`=:State
                        WHERE " . self::TableId_NewspaperPage . "=:" . self::TableId_NewspaperPage . ";";
            $dataProperty->AddField(self::TableId_NewspaperPage, $newspaperPageId);
            $dataProperty->AddField("State", $state);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * @param $newspaperPageId
     * @return int
     */
    public function Delete($newspaperPageId){
        $result = 0;
        if ($newspaperPageId > 0) {

            $dataProperty = new DataProperty();
            $sql = "DELETE FROM " . self::TableName_NewspaperPage . "

                        WHERE " . self::TableId_NewspaperPage . "=:" . self::TableId_NewspaperPage . ";";
            $dataProperty->AddField(self::TableId_NewspaperPage, $newspaperPageId);

            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 修改排序
     * @param int $sort 大于0向上移动，否则向下移动
     * @param int $newspaperPageId 资讯id
     * @return int 返回结果
     */
    public function ModifySort($sort, $newspaperPageId) {
        $result = 0;
        if ($newspaperPageId > 0) {

            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'newspaper_page_data';
            DataCache::RemoveDir($cacheDir);


            $newspaperId = $this->GetNewspaperId($newspaperPageId, false);

            $currentSort = $this->GetSort($newspaperPageId, false);

            if ($sort > 0) { //向上移动
                $dataProperty = new DataProperty();
                $sql = "SELECT
                            Sort
                        FROM " . self::TableName_NewspaperPage . "

                        WHERE
                            NewspaperId=:NewspaperId
                        AND NewspaperPageId<>:NewspaperPageId
                        AND sort>=:CurrentSort

                        ORDER BY Sort DESC LIMIT 1;
                        ";
                $dataProperty->AddField("NewspaperId", $newspaperId);
                $dataProperty->AddField("NewspaperPageId", $newspaperPageId);
                $dataProperty->AddField("CurrentSort", $currentSort);
                $newSort = $this->dbOperator->GetInt($sql, $dataProperty);
            } else{//向下移动
                $dataProperty = new DataProperty();
                $sql = "SELECT
                            Sort
                        FROM " . self::TableName_NewspaperPage . "

                        WHERE NewspaperId=:NewspaperId
                        AND NewspaperPageId<>:NewspaperPageId
                        AND Sort<=:CurrentSort

                        ORDER BY Sort LIMIT 1;
                        ";
                $dataProperty->AddField("NewspaperId", $newspaperId);
                $dataProperty->AddField("NewspaperPageId", $newspaperPageId);
                $dataProperty->AddField("CurrentSort", $currentSort);
                $newSort = $this->dbOperator->GetInt($sql, $dataProperty);
            }

            if ($newSort < 0) {
                $newSort = 0;
            }

            $newSort = $newSort + $sort;


            //2011.12.8 zc 排序号禁止负数
            if ($newSort < 0) {
                $newSort = 0;
            }

            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_NewspaperPage . "
                    SET `Sort`=:NewSort
                    WHERE NewspaperPageId=:NewspaperPageId;";
            $dataProperty->AddField("NewSort", $newSort);
            $dataProperty->AddField("NewspaperPageId", $newspaperPageId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 拖动排序
     * @param array $arrNewspaperPageId 待处理的文档编号数组
     * @return int 操作结果
     */
    public function ModifySortForDrag($arrNewspaperPageId)
    {
        if (count($arrNewspaperPageId) > 1) { //大于1条时排序才有意义

            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'newspaper_page_data';
            DataCache::RemoveDir($cacheDir);

            $strNewspaperPageId = join(',', $arrNewspaperPageId);
            $strNewspaperPageId = Format::FormatSql($strNewspaperPageId);
            $sql = "SELECT min(Sort) FROM " . self::TableName_NewspaperPage . " WHERE NewspaperPageId IN ($strNewspaperPageId);";
            $minSort = $this->dbOperator->GetInt($sql, null);

            if($minSort<=0){
                $minSort = 0;
                //$maxSort = 100;
            }

            $arrSql = array();
            for ($i = 0; $i < count($arrNewspaperPageId); $i++) {
                $newSort = $minSort + $i;
                if ($newSort < 0) {
                    $newSort = 0;
                }
                $newSort = intval($newSort);
                $newspaperPageId = intval($arrNewspaperPageId[$i]);
                $sql = "UPDATE " . self::TableName_NewspaperPage . " SET Sort=$newSort WHERE NewspaperPageId=$newspaperPageId;";
                $arrSql[] = $sql;
            }
            return $this->dbOperator->ExecuteBatch($arrSql, null);
        }else{
            return -1;
        }
    }

    /**
     * 取得所属电子报id
     * @param int $newspaperPageId 电子报id
     * @param bool $withCache 是否从缓冲中取
     * @return int 电子报id
     */
    public function GetNewspaperId($newspaperPageId, $withCache)
    {
        $result = -1;
        if ($newspaperPageId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'newspaper_page_data';
            $cacheFile = 'newspaper_page_get_newspaper_id.cache_' . $newspaperPageId . '';
            $sql = "SELECT NewspaperId FROM " . self::TableName_NewspaperPage . " WHERE NewspaperPageId=:NewspaperPageId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperPageId", $newspaperPageId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 取得排序号
     * @param int $newspaperPageId 电子报id
     * @param bool $withCache 是否从缓冲中取
     * @return int 排序号
     */
    public function GetSort($newspaperPageId, $withCache)
    {
        $result = -1;
        if ($newspaperPageId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'newspaper_page_data';
            $cacheFile = 'newspaper_page_get_sort.cache_' . $newspaperPageId . '';
            $sql = "SELECT Sort FROM " . self::TableName_NewspaperPage . " WHERE NewspaperPageId=:NewspaperPageId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperPageId", $newspaperPageId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }


    /**
     * 根据后台管理员id返回此管理员可以管理的站点列表数据集
     * @param int $newspaperId 电子报id
     * @param int $pageBegin 分页起始位置
     * @param int $pageSize 分页大小
     * @param int $allCount 记录总数（输出参数）
     * @param string $searchKey 查询关键字
     * @param int $searchType 查询字段类型
     * @return array 站点列表数据集
     */
    public function GetList($newspaperId, $pageBegin, $pageSize, &$allCount, $searchKey, $searchType)
    {
        $dataProperty = new DataProperty();
        $searchSql = "";

        //查询
        if (strlen($searchKey) > 0 && $searchKey != "undefined") {
            if ($searchType == 0) { //名称
                $searchSql = " AND (NewspaperPageName like :SearchKey)";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            } else { //名称
                $searchSql = " AND (NewspaperPageName like :SearchKey)";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            }
        }
        $sql = "SELECT * FROM " . self::TableName_NewspaperPage . "
                        WHERE
                            NewspaperId = :NewspaperId
                        ORDER BY Sort,NewspaperPageNo
                        LIMIT " . $pageBegin . "," . $pageSize . ";";
        $sqlCount = "SELECT Count(*) FROM " . self::TableName_NewspaperPage . "
                        WHERE
                            NewspaperId = :NewspaperId
                        $searchSql;";


        $dataProperty->AddField("NewspaperId", $newspaperId);

        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        $allCount = $this->dbOperator->GetInt($sqlCount, $dataProperty);
        return $result;
    }

    /**
     * 取得状态
     * @param int $newspaperPageId id
     * @param bool $withCache 是否从缓冲中取
     * @return string 状态
     */
    public function GetState($newspaperPageId, $withCache)
    {
        $result = "";
        if ($newspaperPageId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'newspaper_page_data';
            $cacheFile = 'newspaper_page_get_state.cache_' . $newspaperPageId . '';
            $sql = "SELECT State FROM " . self::TableName_NewspaperPage . " WHERE NewspaperPageId=:NewspaperPageId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperPageId", $newspaperPageId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }


    /**
     * 返回一行数据
     * @param int $newspaperPageId id
     * @return array|null 取得对应数组
     */
    public function GetOne($newspaperPageId)
    {
        $result = null;
        if ($newspaperPageId > 0) {
            $sql = "SELECT * FROM
                        " . self::TableName_NewspaperPage . "
                    WHERE NewspaperPageId=:NewspaperPageId
                    ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperPageId", $newspaperPageId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
        }
        return $result;
    }
}