<?php
/**
 * 后台管理 电子版文章 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Newspaper
 * @author zhangchi
 */
class NewspaperArticleManageData extends BaseManageData {
    /**
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_NewspaperArticle)
    {
        return parent::GetFields(self::TableName_NewspaperArticle);
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
                self::TableName_NewspaperArticle,
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
     * @param int $newspaperArticleId id
     * @return int 返回影响的行数
     */
    public function Modify($httpPostData, $newspaperArticleId)
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
                self::TableName_NewspaperArticle,
                self::TableId_NewspaperArticle,
                $newspaperArticleId,
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
     * @param $newspaperPageId
     * @return int
     */
    public function DeleteByNewspaperPageId($newspaperPageId){
        $result = 0;
        if ($newspaperPageId > 0) {

            $dataProperty = new DataProperty();
            $sql = "DELETE FROM " . self::TableName_NewspaperArticle . "

                        WHERE NewspaperPageId=:NewspaperPageId;";
            $dataProperty->AddField("NewspaperPageId", $newspaperPageId);

            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 修改状态
     * @param int $newspaperArticleId id
     * @param int $state 状态
     * @return int 操作结果
     */
    public function ModifyState($newspaperArticleId, $state)
    {
        $result = 0;
        if ($newspaperArticleId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_NewspaperArticle . "
                        SET `State`=:State
                        WHERE " . self::TableId_NewspaperArticle . "=:" . self::TableId_NewspaperArticle . ";";
            $dataProperty->AddField(self::TableId_NewspaperArticle, $newspaperArticleId);
            $dataProperty->AddField("State", $state);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 拖动排序
     * @param array $arrNewspaperArticleId 待处理的文档编号数组
     * @return int 操作结果
     */
    public function ModifySortForDrag($arrNewspaperArticleId)
    {
        if (count($arrNewspaperArticleId) > 1) { //大于1条时排序才有意义

            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'newspaper_article_data';
            DataCache::RemoveDir($cacheDir);

            $strNewspaperArticleId = join(',', $arrNewspaperArticleId);
            $strNewspaperArticleId = Format::FormatSql($strNewspaperArticleId);
            $sql = "SELECT min(Sort) FROM " . self::TableName_NewspaperArticle . " WHERE NewspaperArticleId IN ($strNewspaperArticleId);";
            $minSort = $this->dbOperator->GetInt($sql, null);

            if($minSort<=0){
                //$maxSort = 0;
                $minSort = 0;
            }

            $arrSql = array();
            for ($i = 0; $i < count($arrNewspaperArticleId); $i++) {
                $newSort = $minSort + $i;
                if ($newSort < 0) {
                    $newSort = 0;
                }
                $newSort = intval($newSort);
                $newspaperArticleId = intval($arrNewspaperArticleId[$i]);
                $sql = "UPDATE " . self::TableName_NewspaperArticle . " SET Sort=$newSort WHERE NewspaperArticleId=$newspaperArticleId;";
                $arrSql[] = $sql;
            }
            return $this->dbOperator->ExecuteBatch($arrSql, null);
        }else{
            return -1;
        }
    }

    /**
     * 根据后台管理员id返回此管理员可以管理的站点列表数据集
     * @param int $type 查询类型
     * @param int $newspaperId 电子报id
     * @param int $newspaperPageId 电子报版面id
     * @param int $pageBegin 分页起始位置
     * @param int $pageSize 分页大小
     * @param int $allCount 记录总数（输出参数）
     * @param string $searchKey 查询关键字
     * @param int $searchType 查询字段类型
     * @return array 站点列表数据集
     */
    public function GetList(
        $type,
        $newspaperId,
        $newspaperPageId,
        $pageBegin,
        $pageSize,
        &$allCount,
        $searchKey,
        $searchType
    )
    {
        $dataProperty = new DataProperty();
        $searchSql = "";


        //查询
        if (strlen($searchKey) > 0 && $searchKey != "undefined") {
            if ($searchType == 0) { //名称
                $searchSql = " AND (na.NewspaperArticleTitle like :SearchKey)";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            } else { //名称
                $searchSql = " AND (na.NewspaperArticleTitle like :SearchKey)";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            }
        }
        if($type == 0){
            $sql = "SELECT na.*,np.NewspaperPageNo,np.NewspaperPageName,np.NewspaperPageId
                        FROM " . self::TableName_NewspaperArticle . " na,
                                  " . self::TableName_NewspaperPage." np
                        WHERE
                            na.NewspaperPageId = np.NewspaperPageId AND
                            na.NewspaperPageId = :NewspaperPageId
                        ORDER BY na.Sort ,na.CreateDate
                        LIMIT " . $pageBegin . "," . $pageSize . ";";
            $sqlCount = "SELECT Count(*) FROM " . self::TableName_NewspaperArticle . "
                        WHERE
                            NewspaperPageId = :NewspaperPageId
                        $searchSql
                        ;";


            $dataProperty->AddField("NewspaperPageId", $newspaperPageId);
        }else{
            $sql = "SELECT na.*,np.NewspaperPageNo,np.NewspaperPageName,np.NewspaperPageId
                        FROM " . self::TableName_NewspaperArticle . " na,
                                    " . self::TableName_NewspaperPage." np
                        WHERE
                            na.NewspaperPageId = np.NewspaperPageId AND
                            na.NewspaperPageId IN
                            (SELECT NewspaperPageId
                                FROM " . self::TableName_NewspaperPage . "
                                WHERE NewspaperId = :NewspaperId)
                        ORDER BY na.NewspaperPageId,na.Sort ,na.CreateDate
                        LIMIT " . $pageBegin . "," . $pageSize . ";";
            $sqlCount = "SELECT Count(*) FROM " . self::TableName_NewspaperArticle . "
                        WHERE
                            NewspaperPageId IN
                            (SELECT NewspaperPageId
                                FROM " . self::TableName_NewspaperPage . "
                                WHERE NewspaperId = :NewspaperId)
                        $searchSql
                        ;";
            $dataProperty->AddField("NewspaperId", $newspaperId);
        }


        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        $allCount = $this->dbOperator->GetInt($sqlCount, $dataProperty);
        return $result;
    }

    /**
     * 取得状态
     * @param int $newspaperArticleId id
     * @param bool $withCache 是否从缓冲中取
     * @return string 状态
     */
    public function GetState($newspaperArticleId, $withCache)
    {
        $result = "";
        if ($newspaperArticleId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'newspaper_article_data';
            $cacheFile = 'newspaper_article_get_state.cache_' . $newspaperArticleId . '';
            $sql = "SELECT State FROM " . self::TableName_NewspaperArticle . " WHERE NewspaperArticleId=:NewspaperArticleId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperArticleId", $newspaperArticleId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }


    /**
     * 取得版面id
     * @param int $newspaperArticleId id
     * @param bool $withCache 是否从缓冲中取
     * @return string 状态
     */
    public function GetNewspaperPageId($newspaperArticleId, $withCache)
    {
        $result = "";
        if ($newspaperArticleId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'newspaper_article_data';
            $cacheFile = 'newspaper_article_get_newspaper_page_id.cache_' . $newspaperArticleId . '';
            $sql = "SELECT NewspaperPageId FROM " . self::TableName_NewspaperArticle . "
                    WHERE NewspaperArticleId=:NewspaperArticleId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperArticleId", $newspaperArticleId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 返回一行数据
     * @param int $newspaperArticleId id
     * @return array|null 取得对应数组
     */
    public function GetOne($newspaperArticleId)
    {
        $result = null;
        if ($newspaperArticleId > 0) {
            $sql = "SELECT * FROM
                        " . self::TableName_NewspaperArticle . "
                    WHERE NewspaperArticleId=:NewspaperArticleId
                    ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperArticleId", $newspaperArticleId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 由id集合组成的字符串返回对应id稿件集合的数据集
     * @param string $newspaperArticleIdString id
     * @return array|null 取得对应数组
     */
    public function GetListByIDString($newspaperArticleIdString)
    {
        $result = null;
        if ($newspaperArticleIdString !="") {
            $sql = "SELECT * FROM
                        " . self::TableName_NewspaperArticle . "
                    WHERE NewspaperArticleId IN ($newspaperArticleIdString)
                    ;";
            $dataProperty = new DataProperty();
            //$dataProperty->AddField("NewspaperArticleIdString", $newspaperArticleIdString);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 由点击量返回稿件数据集（点击越高越靠前）
     * @param int $siteId 站点id
     * @param string $stringSiteId 若为所有站点，站点id的string （”1,2,3,4“）
     * @param int $count 前多少条（默认20）
     * @param string $beginDate 起始日期
     * @param string $endDate 结束日期
     * @return array|null 取得对应数组
     */
    public function GetListByHit($siteId,$stringSiteId,$count=20,$beginDate="",$endDate=""){
        $result = null;
        if($siteId>=0){
            $siteSelection=" WHERE NewspaperArticleId>0 ";
            //if($siteId>0){   //没有siteid字段
            //    $siteSelection.=" AND SiteId=:SiteId  ";
            //}else{
            //    $siteSelection.=" AND SiteId IN ($stringSiteId) ";
            //}
            $dateSelection="";
            $limit="";
            if($beginDate!=""){
                $dateSelection.=" AND CreateDate>:BeginDate ";
            }
            if($endDate!=""){
                $dateSelection.=" AND CreateDate<:EndDate ";
            }
            if($count>0){
                $limit.=" LIMIT $count";
            }
            $sql="SELECT * FROM " . self::TableName_NewspaperArticle . $siteSelection .$dateSelection ." ORDER BY HitCount DESC ".$limit;
            $dataProperty = new DataProperty();
            $dataProperty->AddField("BeginDate", $beginDate);
            $dataProperty->AddField("EndDate", $endDate);
            //$dataProperty->AddField("SiteId", $siteId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }



    /**
     * 由id取得题图1的upload_file_id
     * @param int $newspaperArticleId id
     * @param bool $withCache 是否使用缓冲
     * @return int 取得对应数组
     */
    public function GetTitlePic1UploadFileId($newspaperArticleId,$withCache=false)
    {

        $result = -1;
        if ($newspaperArticleId >0) {

            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'newspaper_article_data';
            $cacheFile = 'newspaper_article_get_title_pic_1_upload_file_id.cache_' . $newspaperArticleId . '';

            $sql = "SELECT TitlePic1UploadFileId FROM
                        " . self::TableName_NewspaperArticle . "
                    WHERE NewspaperArticleId = :NewspaperArticleId
                    ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperArticleId", $newspaperArticleId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }


    /**
     * 更新题图1
     * @param int $newspaperArticleId id
     * @param int $UploadFileId upload_file_id
     * @return int 结果
     */
    public function UpdateTitlePic1UploadFileId($newspaperArticleId,$UploadFileId)
    {

        $result = -1;
        if ($newspaperArticleId >0&&$UploadFileId>0) {


            $sql = "update
                        " . self::TableName_NewspaperArticle . "
                    SET TitlePic1UploadFileId=:TitlePic1UploadFileId
                    WHERE NewspaperArticleId = :NewspaperArticleId
                    ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("TitlePic1UploadFileId", $UploadFileId);
            $dataProperty->AddField("NewspaperArticleId", $newspaperArticleId);
            $result = $this->dbOperator->Execute($sql,$dataProperty);
        }
        return $result;
    }
}