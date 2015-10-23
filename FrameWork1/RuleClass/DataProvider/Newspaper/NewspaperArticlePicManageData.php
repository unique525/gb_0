<?php
/**
 * 后台管理 电子版文章图片 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Newspaper
 * @author zhangchi
 */
class NewspaperArticlePicManageData extends BaseManageData {
    /**
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_NewspaperArticlePic)
    {
        return parent::GetFields(self::TableName_NewspaperArticlePic);
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
                self::TableName_NewspaperArticlePic,
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
     * @param int $newspaperArticlePicId id
     * @return int 返回影响的行数
     */
    public function Modify($httpPostData, $newspaperArticlePicId)
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
                self::TableName_NewspaperArticlePic,
                self::TableId_NewspaperArticlePic,
                $newspaperArticlePicId,
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

    public function DeleteOfNull(){
        $sql = "DELETE FROM " . self::TableName_NewspaperArticlePic . "

                WHERE NewspaperArticleId NOT IN (
                    SELECT NewspaperArticleId FROM " . self::TableName_NewspaperArticle . "

                );";
        $result = $this->dbOperator->Execute($sql, null);
        return $result;
    }

    /**
     * 修改UploadFileId
     * @param int $newspaperArticlePicId id
     * @param int $uploadFileId 新的上传文件id
     * @return int 操作结果
     */
    public function ModifyUploadFileId($newspaperArticlePicId, $uploadFileId)
    {
        $result = 0;
        if ($newspaperArticlePicId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_NewspaperArticlePic . "
                        SET `UploadFileId`=:UploadFileId
                        WHERE " . self::TableId_NewspaperArticlePic . "=:" . self::TableId_NewspaperArticlePic . ";";
            $dataProperty->AddField(self::TableId_NewspaperArticlePic, $newspaperArticlePicId);
            $dataProperty->AddField("UploadFileId", $uploadFileId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 修改状态
     * @param int $newspaperArticlePicId id
     * @param int $state 状态
     * @return int 操作结果
     */
    public function ModifyState($newspaperArticlePicId, $state)
    {
        $result = 0;
        if ($newspaperArticlePicId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_NewspaperArticlePic . "
                        SET `State`=:State
                        WHERE " . self::TableId_NewspaperArticlePic . "=:" . self::TableId_NewspaperArticlePic . ";";
            $dataProperty->AddField(self::TableId_NewspaperArticlePic, $newspaperArticlePicId);
            $dataProperty->AddField("State", $state);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 根据后台管理员id返回此管理员可以管理的列表数据集
     * @param int $newspaperArticleId 电子报文章id
     * @param int $pageBegin 分页起始位置
     * @param int $pageSize 分页大小
     * @param int $allCount 记录总数（输出参数）
     * @param string $searchKey 查询关键字
     * @param int $searchType 查询字段类型
     * @return array 站点列表数据集
     */
    public function GetList($newspaperArticleId, $pageBegin, $pageSize, &$allCount, $searchKey, $searchType)
    {
        $dataProperty = new DataProperty();
        $searchSql = "";

        //查询
        if (strlen($searchKey) > 0 && $searchKey != "undefined") {
            if ($searchType == 0) { //名称
                $searchSql = " AND (Remark like :SearchKey)";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            } else { //名称
                $searchSql = " AND (Remark like :SearchKey)";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            }
        }
        $sql = "SELECT nap.*,uf.* FROM " . self::TableName_NewspaperArticlePic . " nap
               LEFT OUTER JOIN " .self::TableName_UploadFile." uf on nap.UploadFileId=uf.UploadFileId
                        WHERE
                            nap.NewspaperArticleId = :NewspaperArticleId
                        ORDER BY nap.CreateDate
                        LIMIT " . $pageBegin . "," . $pageSize . ";";
        $sqlCount = "SELECT Count(*) FROM " . self::TableName_NewspaperArticlePic . "
                        WHERE
                            NewspaperArticleId = :NewspaperArticleId
                        $searchSql
                        ;";


        $dataProperty->AddField("NewspaperArticleId", $newspaperArticleId);

        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        $allCount = $this->dbOperator->GetInt($sqlCount, $dataProperty);
        return $result;
    }




    /**
     * 取得状态
     * @param int $newspaperArticlePicId id
     * @param bool $withCache 是否从缓冲中取
     * @return string 状态
     */
    public function GetState($newspaperArticlePicId, $withCache)
    {
        $result = "";
        if ($newspaperArticlePicId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'newspaper_article_pic_data';
            $cacheFile = 'newspaper_article_pic_get_state.cache_' . $newspaperArticlePicId . '';
            $sql = "SELECT State FROM " . self::TableName_NewspaperArticlePic . " WHERE NewspaperArticlePicId=:NewspaperArticlePicId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperArticlePicId", $newspaperArticlePicId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }


    /**
     * 取得电子报文章id
     * @param int $newspaperArticlePicId id
     * @param bool $withCache 是否从缓冲中取
     * @return string 状态
     */
    public function GetNewspaperArticleId($newspaperArticlePicId, $withCache)
    {
        $result = "";
        if ($newspaperArticlePicId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'newspaper_article_pic_data';
            $cacheFile = 'newspaper_article_pic_get_newspaper_article_id.cache_' . $newspaperArticlePicId . '';
            $sql = "SELECT NewspaperArticleId FROM " . self::TableName_NewspaperArticlePic . "
                    WHERE NewspaperArticlePicId=:NewspaperArticlePicId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperArticlePicId", $newspaperArticlePicId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }


    /**
     * 返回一行数据
     * @param int $newspaperArticlePicId id
     * @return array|null 取得对应数组
     */
    public function GetOne($newspaperArticlePicId)
    {
        $result = null;
        if ($newspaperArticlePicId > 0) {
            $sql = "SELECT * FROM
                        " . self::TableName_NewspaperArticlePic . "
                    WHERE NewspaperArticlePicId=:NewspaperArticlePicId
                    ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperArticlePicId", $newspaperArticlePicId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 返回电子报文档所有附件的id
     * @param int $newspaperArticleId id
     * @return array|null 取得对应数组
     */
    public function GetIdList($newspaperArticleId)
    {
        $result = null;
        if ($newspaperArticleId > 0) {
            $sql = "SELECT UploadFileId FROM
                        " . self::TableName_NewspaperArticlePic . "
                    WHERE NewspaperArticleId=:NewspaperArticleId
                    ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperArticleId", $newspaperArticleId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }
} 