<?php

/**
 * 前台 电子报版面 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Newspaper
 * @author zhangchi
 */
class NewspaperPagePublicData extends BasePublicData
{

    /**
     * 创建电子报版面（导入使用）
     * @param int $newspaperId 电子报id
     * @param string $newspaperPageName 版面名称
     * @param string $newspaperPageNo 版面序号
     * @param int $articleCount 文章数
     * @param int $picCount 图片数
     * @param string $editor 版面负责人
     * @param int $pageWidth 版面宽度
     * @param int $pageHeight 版面高度
     * @param string $issueDepartment 签发部门
     * @param string $issuer 签发人
     * @return int 电子报版面id
     */
    public function CreateForImport(
        $newspaperId,
        $newspaperPageName,
        $newspaperPageNo,
        $articleCount,
        $picCount,
        $editor,
        $pageWidth,
        $pageHeight,
        $issueDepartment,
        $issuer
    )
    {

        $newspaperPageId = self::GetNewspaperPageIdForImport($newspaperPageNo, $newspaperId);
        if ($newspaperPageId <= 0 && $newspaperId > 0) {
            $sql = "INSERT INTO " . self::TableName_NewspaperPage . " (
                NewspaperId,
                NewspaperPageName,
                CreateDate,
                NewspaperPageNo,
                ArticleCount,
                PicCount,
                Editor,
                PageWidth,
                PageHeight,
                IssueDepartment,
                Issuer
                )
                VALUES
                (
                :NewspaperId,
                :NewspaperPageName,
                now(),
                :NewspaperPageNo,
                :ArticleCount,
                :PicCount,
                :Editor,
                :PageWidth,
                :PageHeight,
                :IssueDepartment,
                :Issuer
                );";

            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperId", $newspaperId);
            $dataProperty->AddField("NewspaperPageName", $newspaperPageName);
            $dataProperty->AddField("NewspaperPageNo", $newspaperPageNo);
            $dataProperty->AddField("ArticleCount", $articleCount);
            $dataProperty->AddField("PicCount", $picCount);
            $dataProperty->AddField("Editor", $editor);
            $dataProperty->AddField("PageWidth", $pageWidth);
            $dataProperty->AddField("PageHeight", $pageHeight);
            $dataProperty->AddField("IssueDepartment", $issueDepartment);
            $dataProperty->AddField("Issuer", $issuer);
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);

            if($result > 0){

                self::ModifySort($result, $result); //修改排序号为本身的id

            }

        } else {
            $result = $newspaperPageId;
        }

        return $result;

    }


    /**
     * 修改排序号
     * @param int $newspaperPageId 版面id
     * @param int $sort 排序号
     * @return int 修改结果
     */
    public function ModifySort($newspaperPageId, $sort)
    {
        $result = -1;

        if ($newspaperPageId > 0 && $sort > 0) {

            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'newspaper_page_data';
            $cacheFile = 'newspaper_page_get_sort.cache_' . $newspaperPageId . '';
            DataCache::Remove($cacheDir . DIRECTORY_SEPARATOR . $cacheFile);


            $sql = "UPDATE " . self::TableName_NewspaperPage . "
                            SET Sort=:Sort WHERE NewspaperPageId=:NewspaperPageId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("Sort", $sort);
            $dataProperty->AddField("NewspaperPageId", $newspaperPageId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }

    /**
     * 修改版面PDF上传文件id
     * @param int $newspaperPageId 版面id
     * @param int $pdfUploadFileId 版面PDF上传文件id
     * @return int 修改结果
     */
    public function ModifyPdfUploadFileIdForImport($newspaperPageId, $pdfUploadFileId)
    {
        $result = -1;

        if ($newspaperPageId > 0 && $pdfUploadFileId > 0) {
            $sql = "UPDATE " . self::TableName_NewspaperPage . "
                            SET PdfUploadFileId=:PdfUploadFileId WHERE NewspaperPageId=:NewspaperPageId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("PdfUploadFileId", $pdfUploadFileId);
            $dataProperty->AddField("NewspaperPageId", $newspaperPageId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }

    /**
     * 修改版面图id
     * @param int $newspaperPageId 版面id
     * @param int $picUploadFileId 上传文件id
     * @return int 修改结果
     */
    public function ModifyPicUploadFileIdForImport($newspaperPageId, $picUploadFileId)
    {
        $result = -1;

        if ($newspaperPageId > 0 && $picUploadFileId > 0) {
            $sql = "UPDATE " . self::TableName_NewspaperPage . "
                            SET PicUploadFileId=:PicUploadFileId WHERE NewspaperPageId=:NewspaperPageId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("PicUploadFileId", $picUploadFileId);
            $dataProperty->AddField("NewspaperPageId", $newspaperPageId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }

    /**
     * 根据版面序号和电子报id返回版面id
     * @param string $newspaperPageNo 版面序号
     * @param int $newspaperId 电子报id
     * @return int 版面id
     */
    public function GetNewspaperPageIdForImport($newspaperPageNo, $newspaperId)
    {
        $result = -1;
        if (strlen($newspaperPageNo) > 0 && $newspaperId > 0) {
            $sql = "SELECT NewspaperPageId FROM " . self::TableName_NewspaperPage . "

                        WHERE
                        NewspaperPageNo=:NewspaperPageNo
                        AND NewspaperId=:NewspaperId
                        AND State<100;";

            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperPageNo", $newspaperPageNo);
            $dataProperty->AddField("NewspaperId", $newspaperId);
            $result = $this->dbOperator->GetInt($sql, $dataProperty);
        }

        return $result;
    }

    //////////////////////////////////////////////////////////////////////////////
    //////////////////////////////Get Info////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////

    /**
     * 取得第一条记录
     * @param int $newspaperId
     * @return int
     */
    public function GetNewspaperPageIdOfFirst($newspaperId)
    {
        $result = -1;
        if ($newspaperId > 0) {
            $sql = "SELECT NewspaperPageId FROM " . self::TableName_NewspaperPage . "

                WHERE NewspaperId = :NewspaperId

                AND State<100

                ORDER BY Sort,NewspaperPageId LIMIT 1;

                ";

            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperId", $newspaperId);
            $result = $this->dbOperator->GetInt($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 取得下一条记录
     * @param int $newspaperId
     * @param int $newspaperPageId
     * @param bool $withCache 是否从缓冲中取
     * @return int
     */
    public function GetNewspaperPageIdOfNext($newspaperId, $newspaperPageId, $withCache = false)
    {

        $result = -1;
        if ($newspaperId > 0 && $newspaperPageId >0) {

            $sort = self::GetSort($newspaperPageId, true);

            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'newspaper_page_data';
            $cacheFile = 'newspaper_page_get_next_newspaper_page_id.cache_' . $newspaperId . '_' . $newspaperPageId;
            $sql = "SELECT NewspaperPageId FROM " . self::TableName_NewspaperPage . "

                WHERE NewspaperId = :NewspaperId
                    AND Sort>:Sort

                    AND State<100

                ORDER BY Sort LIMIT 1;

                ";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("Sort", $sort);
            $dataProperty->AddField("NewspaperId", $newspaperId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);

        }
        return $result;
    }

    /**
     * 取得上一条记录
     * @param int $newspaperId
     * @param int $newspaperPageId
     * @param bool $withCache 是否从缓冲中取
     * @return int
     */
    public function GetNewspaperPageIdOfPrevious($newspaperId, $newspaperPageId, $withCache = false)
    {
        $result = -1;
        if ($newspaperId > 0 && $newspaperPageId >0) {

            $sort = self::GetSort($newspaperPageId, true);

            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'newspaper_page_data';
            $cacheFile = 'newspaper_page_get_previous_newspaper_page_id.cache_' . $newspaperId . '_' . $newspaperPageId;
            $sql = "SELECT NewspaperPageId FROM " . self::TableName_NewspaperPage . "
                        WHERE NewspaperId = :NewspaperId
                            AND Sort<:Sort
                            AND State<100
                        ORDER BY Sort DESC LIMIT 1;
                    ";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("Sort", $sort);
            $dataProperty->AddField("NewspaperId", $newspaperId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);

        }
        return $result;
    }

    /**
     * 取得版面名称
     * @param int $newspaperPageId 电子报版面id
     * @param bool $withCache 是否从缓冲中取
     * @return string 版面名称
     */
    public function GetNewspaperPageName($newspaperPageId, $withCache)
    {
        $result = "";
        if ($newspaperPageId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'newspaper_page_data';
            $cacheFile = 'newspaper_page_get_newspaper_page_name.cache_' . $newspaperPageId . '';
            $sql = "SELECT NewspaperPageName FROM " . self::TableName_NewspaperPage . " WHERE NewspaperPageId=:NewspaperPageId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperPageId", $newspaperPageId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 取得版面序号
     * @param int $newspaperPageId 电子报版面id
     * @param bool $withCache 是否从缓冲中取
     * @return string 版面序号
     */
    public function GetNewspaperPageNo($newspaperPageId, $withCache)
    {
        $result = "";
        if ($newspaperPageId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'newspaper_page_data';
            $cacheFile = 'newspaper_page_get_newspaper_page_no.cache_' . $newspaperPageId . '';
            $sql = "SELECT NewspaperPageNo FROM " . self::TableName_NewspaperPage . " WHERE NewspaperPageId=:NewspaperPageId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperPageId", $newspaperPageId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }


    /**
     * 取得版面图片文件
     * @param int $newspaperPageId 电子报版面id
     * @param bool $withCache 是否从缓冲中取
     * @return string 版面图片文件
     */
    public function GetUploadFilePath($newspaperPageId, $withCache)
    {
        $result = "";
        if ($newspaperPageId > 0) {


            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'newspaper_page_data';
            $cacheFile = 'newspaper_page_get_upload_file_path.cache_' . $newspaperPageId . '';
            $sql = "SELECT uf.UploadFilePath

                    FROM " . self::TableName_NewspaperPage . " np," . self::TableName_UploadFile . " uf

                    WHERE np.NewspaperPageId=:NewspaperPageId

                        AND np.PicUploadFileId=uf.UploadFileId

                    ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperPageId", $newspaperPageId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 取得版面PDF文件
     * @param int $newspaperPageId 电子报版面id
     * @param bool $withCache 是否从缓冲中取
     * @return string 版面PDF文件
     */
    public function GetPdfUploadFilePath($newspaperPageId, $withCache)
    {
        $result = "";
        if ($newspaperPageId > 0) {


            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'newspaper_page_data';
            $cacheFile = 'newspaper_page_get_pdf_upload_file_path.cache_' . $newspaperPageId . '';
            $sql = "SELECT uf.UploadFilePath

                    FROM " . self::TableName_NewspaperPage . " np," . self::TableName_UploadFile . " uf

                    WHERE np.NewspaperPageId=:NewspaperPageId

                        AND np.PdfUploadFileId=uf.UploadFileId

                    ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperPageId", $newspaperPageId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 取得所属电子报id
     * @param int $newspaperPageId 电子报版面id
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
     * 取得是否必须付费阅读
     * @param int $newspaperPageId 电子报id
     * @param bool $withCache 是否从缓冲中取
     * @return int 是否必须付费阅读
     */
    public function GetMustPay($newspaperPageId, $withCache)
    {
        $result = -1;
        if ($newspaperPageId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'newspaper_page_data';
            $cacheFile = 'newspaper_page_get_must_pay.cache_' . $newspaperPageId . '';
            $sql = "SELECT MustPay FROM " . self::TableName_NewspaperPage . "
                    WHERE NewspaperPageId=:NewspaperPageId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperPageId", $newspaperPageId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 取得一条信息
     * @param int $newspaperPageId 电子报版面id
     * @return array 电子报版面信息数组
     */
    public function GetOne($newspaperPageId)
    {

        $sql = "SELECT np.*,uf.*


                FROM " . self::TableName_NewspaperPage . " np LEFT JOIN
                     " . self::TableName_UploadFile . " uf
                     ON np.PicUploadFileId=uf.UploadFileId

                WHERE " . self::TableId_NewspaperPage . "=:" . self::TableId_NewspaperPage . ";";
        $dataProperty = new DataProperty();
        $dataProperty->AddField(self::TableId_NewspaperPage, $newspaperPageId);
        $result = $this->dbOperator->GetArray($sql, $dataProperty);
        return $result;
    }


    /**
     * 取得版面列表
     * @param int $newspaperId 电子报id
     * @param bool $withCache 是否从缓冲中取
     * @return array 电子报版面信息数据集
     */
    public function GetListForSelectPage($newspaperId, $withCache = FALSE)
    {
        $result = null;
        if($newspaperId>0){
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'newspaper_article_data';
            $cacheFile = "newspaper_article_get_all_list_". $newspaperId;
            $sql = "SELECT NewspaperPageId,NewspaperPageName,NewspaperPageNo


                FROM " . self::TableName_NewspaperPage . "

                WHERE NewspaperId =:NewspaperId

                AND State<100

                ORDER BY Sort,NewspaperPageId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperId", $newspaperId);

            $result = parent::GetInfoOfArrayList($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);

        }

            //$result = $this->dbOperator->GetArrayList($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);}
        return $result;
    }

    /**
     * 根据报纸id取得所有版面id列表
     * @param int $newspaperId 电子报id
     * @param bool $withCache 是否从缓冲中取
     * @return array 电子报版面id数据集
     */
    public function GetNewspaperIdList($newspaperId, $withCache)
    {
        $result = null;
        if ($newspaperId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'newspaper_page_data';
            $cacheFile = 'newspaper_page_get_newspaper_id_list.cache_' . $newspaperId . '';
            $sql = "SELECT NewspaperPageId FROM " . self::TableName_NewspaperPage . "
                    WHERE NewspaperId=:NewspaperId
                    AND State<100
                    ORDER BY Sort,NewspaperPageId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperId", $newspaperId);
            $result = parent::GetInfoOfArrayList($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }
}