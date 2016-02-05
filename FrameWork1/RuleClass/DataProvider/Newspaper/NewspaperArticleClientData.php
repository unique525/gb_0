<?php
/**
 * 客户端 电子报文章 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Newspaper
 * @author zhangchi
 */
class NewspaperArticleClientData extends BaseClientData {

    /**
     * 取得电子报文章列表
     * @param int $newspaperPageId 电子报版面id
     * @return array|null 返回资讯列表
     */
    public function GetList($newspaperPageId)
    {

        $result = null;

        if ($newspaperPageId > 0) {

            $orderBySql = 'na.Sort , na.CreateDate DESC';


            $selectColumn = '
            na.*,
                uf1.UploadFilePath AS TitlePic1UploadFilePath,
                uf1.UploadFileMobilePath AS TitlePic1UploadFileMobilePath,
                uf1.UploadFilePadPath AS TitlePic1UploadFilePadPath,
                uf1.UploadFileThumbPath1 AS TitlePic1UploadFileThumbPath1,
                uf1.UploadFileThumbPath2 AS TitlePic1UploadFileThumbPath2,
                uf1.UploadFileThumbPath3 AS TitlePic1UploadFileThumbPath3,
                uf1.UploadFileWatermarkPath1 AS TitlePic1UploadFileWatermarkPath1,
                uf1.UploadFileWatermarkPath2 AS TitlePic1UploadFileWatermarkPath2,
                uf1.UploadFileCompressPath1 AS TitlePic1UploadFileCompressPath1,
                uf1.UploadFileCompressPath2 AS TitlePic1UploadFileCompressPath2,
                uf1.UploadFileCutPath1 AS TitlePic1UploadFileCutPath1
            ';

            $sql = "SELECT $selectColumn FROM " . self::TableName_NewspaperArticle  . " na
            LEFT OUTER JOIN " .self::TableName_UploadFile." uf1 on na.TitlePic1UploadFileId=uf1.UploadFileId
                WHERE
                    na.NewspaperPageId=:NewspaperPageId
                    AND na.State<100
                ORDER BY $orderBySql";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperPageId", $newspaperPageId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

        }


        return $result;
    }

    /**
     * 取得整期电子报文章列表
     * @param int $newspaperId 电子报id
     * @param bool $withCache
     * @return array|null 返回资讯列表
     */
    public function GetListByNewspaperId($newspaperId,$withCache = false)
    {

        $result = null;

        if ($newspaperId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'newspaper_article_data';
            $cacheFile = "newspaper_article_get_all_list_client_". $newspaperId;



            $orderBySql = 'na.Sort , na.CreateDate DESC';

            $selectColumn = '
            na.*,
                uf1.UploadFilePath AS TitlePic1UploadFilePath,
                uf1.UploadFileMobilePath AS TitlePic1UploadFileMobilePath,
                uf1.UploadFilePadPath AS TitlePic1UploadFilePadPath,
                uf1.UploadFileThumbPath1 AS TitlePic1UploadFileThumbPath1,
                uf1.UploadFileThumbPath2 AS TitlePic1UploadFileThumbPath2,
                uf1.UploadFileThumbPath3 AS TitlePic1UploadFileThumbPath3,
                uf1.UploadFileWatermarkPath1 AS TitlePic1UploadFileWatermarkPath1,
                uf1.UploadFileWatermarkPath2 AS TitlePic1UploadFileWatermarkPath2,
                uf1.UploadFileCompressPath1 AS TitlePic1UploadFileCompressPath1,
                uf1.UploadFileCompressPath2 AS TitlePic1UploadFileCompressPath2,
                uf1.UploadFileCutPath1 AS TitlePic1UploadFileCutPath1
            ';

            $sql = "SELECT $selectColumn FROM " . self::TableName_NewspaperArticle . " na
            LEFT OUTER JOIN " .self::TableName_UploadFile." uf1 on na.TitlePic1UploadFileId=uf1.UploadFileId
                WHERE
                    na.NewspaperPageId IN (SELECT NewspaperPageId FROM ".self::TableName_NewspaperPage." WHERE NewspaperId=:NewspaperId)
                    AND na.State<100
                ORDER BY $orderBySql";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperId", $newspaperId);
            $result = parent::GetInfoOfArrayList($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);

        }


        return $result;
    }

    /**
     * 返回一行数据
     * @param int $newspaperArticleId 电子报文章id
     * @param bool $withCache 是否使用缓存
     * @return array|null 取得对应数组
     */
    public function GetOne($newspaperArticleId, $withCache = FALSE){
        $result = null;
        if($newspaperArticleId>0){
            $sql = "
            SELECT na.*,
                uf1.UploadFilePath AS TitlePic1UploadFilePath,
                uf1.UploadFileMobilePath AS TitlePic1UploadFileMobilePath,
                uf1.UploadFilePadPath AS TitlePic1UploadFilePadPath,
                uf1.UploadFileThumbPath1 AS TitlePic1UploadFileThumbPath1,
                uf1.UploadFileThumbPath2 AS TitlePic1UploadFileThumbPath2,
                uf1.UploadFileThumbPath3 AS TitlePic1UploadFileThumbPath3,
                uf1.UploadFileWatermarkPath1 AS TitlePic1UploadFileWatermarkPath1,
                uf1.UploadFileWatermarkPath2 AS TitlePic1UploadFileWatermarkPath2,
                uf1.UploadFileCompressPath1 AS TitlePic1UploadFileCompressPath1,
                uf1.UploadFileCompressPath2 AS TitlePic1UploadFileCompressPath2,
                uf1.UploadFileCutPath1 AS TitlePic1UploadFileCutPath1
            FROM
            " . self::TableName_NewspaperArticle . " na
            LEFT OUTER JOIN " .self::TableName_UploadFile." uf1 on na.TitlePic1UploadFileId=uf1.UploadFileId

            WHERE na.NewspaperArticleId=:NewspaperArticleId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperArticleId", $newspaperArticleId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);

        }
        return $result;
    }


    /**
     * 增加评论数
     * @param int $newspaperArticleId 文章id
     * @return int
     */
    public function AddCommentCount($newspaperArticleId){
        $result = -1;

        if($newspaperArticleId>0){

            $sql = "UPDATE ".self::TableName_NewspaperArticle."
                    SET CommentCount=CommentCount+1
                    WHERE NewspaperArticleId=:NewspaperArticleId;";

            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperArticleId", $newspaperArticleId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);

        }

        return $result;
    }

    /**
     * 取得文章的版面id
     * @param int $newspaperArticleId 文章id
     * @param bool $withCache 是否从缓冲中取
     * @return string 版面id
     */
    public function GetNewspaperPageId($newspaperArticleId, $withCache){

        $result = "";
        if ($newspaperArticleId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'newspaper_article_data';
            $cacheFile = 'newspaper_article_get_newspaper_page_id.cache_' . $newspaperArticleId . '';
            $sql = "SELECT NewspaperPageId FROM " . self::TableName_NewspaperArticle . "

                    WHERE NewspaperArticleId=:NewspaperArticleId
                     ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperArticleId", $newspaperArticleId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }

        return $result;


    }
}