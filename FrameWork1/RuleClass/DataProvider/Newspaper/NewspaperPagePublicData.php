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
        } else {
            $result = $newspaperPageId;
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
                        AND NewspaperId=:NewspaperId;";

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

                ORDER BY NewspaperPageId LIMIT 1;

                ";

            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperId", $newspaperId);
            $result = $this->dbOperator->GetInt($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 取得第一条记录
     * @param int $newspaperId
     * @param int $newspaperPageId
     * @return int
     */
    public function GetNewspaperPageIdOfNext($newspaperId, $newspaperPageId)
    {
        $result = -1;
        if ($newspaperId > 0) {
            $sql = "SELECT NewspaperPageId FROM " . self::TableName_NewspaperPage . "

                WHERE NewspaperId = :NewspaperId
                    AND NewspaperPageId>:NewspaperPageId

                ORDER BY NewspaperPageId LIMIT 1;

                ";

            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperPageId", $newspaperPageId);
            $dataProperty->AddField("NewspaperId", $newspaperId);
            $result = $this->dbOperator->GetInt($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 取得第一条记录
     * @param int $newspaperId
     * @param int $newspaperPageId
     * @return int
     */
    public function GetNewspaperPageIdOfPrevious($newspaperId, $newspaperPageId)
    {
        $result = -1;
        if ($newspaperId > 0) {
            $sql = "SELECT NewspaperPageId FROM " . self::TableName_NewspaperPage . "

                WHERE NewspaperId = :NewspaperId
                 AND NewspaperPageId<:NewspaperPageId

                ORDER BY NewspaperPageId DESC LIMIT 1;

                ";

            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperPageId", $newspaperPageId);
            $dataProperty->AddField("NewspaperId", $newspaperId);
            $result = $this->dbOperator->GetInt($sql, $dataProperty);
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
    public function GetListForSelectPage($newspaperId, $withCache=FALSE)
    {
        $result=-1;
        if($newspaperId>0){
            $withCache=FALSE;
            $cacheDir = "";//CACHE_PATH . DIRECTORY_SEPARATOR . '_data';
            $cacheFile = "";
            $sql = "SELECT NewsPaperPageId,NewspaperPageName


                FROM " . self::TableName_NewspaperPage . "

                WHERE NewsPaperId =:NewsPaperId ORDER BY NewspaperPageId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewsPaperId", $newspaperId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);}
        return $result;
    }
}