<?php

/**
 * 前台 电子报文章 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Newspaper
 * @author zhangchi
 */
class NewspaperArticlePublicData extends BasePublicData
{

    /**
     * 创建电子报文章（导入使用）
     * @param int $newspaperPageId
     * @param string $newspaperArticleTitle
     * @param string $newspaperArticleContent
     * @param string $newspaperArticleSubTitle
     * @param string $newspaperArticleCiteTitle
     * @param string $publishType
     * @param string $published
     * @param string $informationId
     * @param string $source
     * @param string $author
     * @param string $column
     * @param string $picRemark
     * @param string $next
     * @param string $previous
     * @param string $no
     * @param string $className
     * @param string $genre
     * @param string $reprint
     * @param string $fileName
     * @param string $abstractInfo
     * @param string $wordCount
     * @param string $picMapping
     * @return int 电子报文章id
     */
    public function CreateForImport(
        $newspaperPageId,
        $newspaperArticleTitle,
        $newspaperArticleContent,
        $newspaperArticleSubTitle,
        $newspaperArticleCiteTitle,
        $publishType,
        $published,
        $informationId,
        $source,
        $author,
        $column,
        $picRemark,
        $next,
        $previous,
        $no,
        $className,
        $genre,
        $reprint,
        $fileName,
        $abstractInfo,
        $wordCount,
        $picMapping

    )
    {

        $newspaperArticleId = self::GetNewspaperArticleIdForImport($newspaperArticleTitle, $newspaperPageId);
        if ($newspaperArticleId <= 0 && $newspaperPageId > 0) {
            $sql = "INSERT INTO " . self::TableName_NewspaperArticle . "
                    (
                    `NewspaperPageId`,
                    `NewspaperArticleTitle`,
                    `CreateDate`,
                    `NewspaperArticleContent`,
                    `NewspaperArticleSubTitle`,
                    `NewspaperArticleCiteTitle`,
                    `PublishType`,
                    `Published`,
                    `InformationId`,
                    `Source`,
                    `Author`,
                    `Column`,
                    `PicRemark`,
                    `Next`,
                    `Previous`,
                    `No`,
                    `ClassName`,
                    `Genre`,
                    `Reprint`,
                    `FileName`,
                    `Abstract`,
                    `WordCount`,
                    `PicMapping`,
                    `Sort`
                    ) VALUES (
                    :NewspaperPageId,
                    :NewspaperArticleTitle,
                    now(),
                    :NewspaperArticleContent,
                    :NewspaperArticleSubTitle,
                    :NewspaperArticleCiteTitle,
                    :PublishType,
                    :Published,
                    :InformationId,
                    :Source,
                    :Author,
                    :Column,
                    :PicRemark,
                    :Next,
                    :Previous,
                    :No,
                    :ClassName,
                    :Genre,
                    :Reprint,
                    :FileName,
                    :Abstract,
                    :WordCount,
                    :PicMapping,
                    :Sort
                    );";

            $sort = intval($no);

            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperPageId", $newspaperPageId);
            $dataProperty->AddField("NewspaperArticleTitle", $newspaperArticleTitle);
            $dataProperty->AddField("NewspaperArticleContent", $newspaperArticleContent);
            $dataProperty->AddField("NewspaperArticleSubTitle", $newspaperArticleSubTitle);
            $dataProperty->AddField("NewspaperArticleCiteTitle", $newspaperArticleCiteTitle);
            $dataProperty->AddField("PublishType", $publishType);
            $dataProperty->AddField("Published", $published);
            $dataProperty->AddField("InformationId", $informationId);
            $dataProperty->AddField("Source", $source);
            $dataProperty->AddField("Author", $author);
            $dataProperty->AddField("Column", $column);
            $dataProperty->AddField("PicRemark", $picRemark);
            $dataProperty->AddField("Next", $next);
            $dataProperty->AddField("Previous", $previous);
            $dataProperty->AddField("No", $no);
            $dataProperty->AddField("ClassName", $className);
            $dataProperty->AddField("Genre", $genre);
            $dataProperty->AddField("Reprint", $reprint);
            $dataProperty->AddField("FileName", $fileName);
            $dataProperty->AddField("Abstract", $abstractInfo);
            $dataProperty->AddField("WordCount", $wordCount);
            $dataProperty->AddField("PicMapping", $picMapping);
            $dataProperty->AddField("Sort", $sort);
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        } else {
            $result = $newspaperArticleId;
        }

        return $result;
    }

    /**
     * 更新文章里的TitlePic1UploadFileId
     * @param int $newspaperArticleId
     * @param int $titlePic1UploadFileId
     * @return int 执行结果
     */
    public function ModifyTitlePic1UploadFileId($newspaperArticleId,$titlePic1UploadFileId){

        $result = -1;
        if ($newspaperArticleId > 0 && $titlePic1UploadFileId > 0) {

            $sql = "UPDATE " . self::TableName_NewspaperArticle . "

                SET TitlePic1UploadFileId=:TitlePic1UploadFileId

                WHERE NewspaperArticleId=:NewspaperArticleId
                ;";

            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperArticleId", $newspaperArticleId);
            $dataProperty->AddField("TitlePic1UploadFileId", $titlePic1UploadFileId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);

        }

        return $result;

    }

    /**
     * 根据文章标题和版面id返回文章id
     * @param string $newspaperArticleTitle 文章标题
     * @param int $newspaperPageId 版面id
     * @return int 文章id
     */
    public function GetNewspaperArticleIdForImport($newspaperArticleTitle, $newspaperPageId)
    {
        $result = -1;
        if (strlen($newspaperArticleTitle) > 0 && $newspaperPageId > 0) {

            $sql = "SELECT NewspaperArticleId FROM " . self::TableName_NewspaperArticle . "

                WHERE NewspaperArticleTitle=:NewspaperArticleTitle
                AND NewspaperPageId=:NewspaperPageId
                AND State<100
                ;";

            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperArticleTitle", $newspaperArticleTitle);
            $dataProperty->AddField("NewspaperPageId", $newspaperPageId);
            $result = $this->dbOperator->GetInt($sql, $dataProperty);

        }

        return $result;
    }

    /**
     * 取得文章标题
     * @param int $newspaperArticleId 文章id
     * @param bool $withCache 是否从缓冲中取
     * @return string 标题
     */
    public function GetNewspaperArticleTitle($newspaperArticleId, $withCache){

        $result = "";
        if ($newspaperArticleId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'newspaper_article_data';
            $cacheFile = 'newspaper_article_get_newspaper_article_title.cache_' . $newspaperArticleId . '';
            $sql = "SELECT NewspaperArticleTitle FROM " . self::TableName_NewspaperArticle . "

                    WHERE NewspaperArticleId=:NewspaperArticleId
                     ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperArticleId", $newspaperArticleId);
            $result = $this->GetInfoOfStringValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }

        return $result;


    }

    /**
     * 取得文章类型
     * @param int $newspaperArticleId 文章id
     * @param bool $withCache 是否从缓冲中取
     * @return string 标题
     */
    public function GetNewspaperArticleType($newspaperArticleId, $withCache){

        $result = "";
        if ($newspaperArticleId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'newspaper_article_data';
            $cacheFile = 'newspaper_article_get_newspaper_article_type.cache_' . $newspaperArticleId . '';
            $sql = "SELECT NewspaperArticleType FROM " . self::TableName_NewspaperArticle . "

                    WHERE NewspaperArticleId=:NewspaperArticleId
                     ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperArticleId", $newspaperArticleId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
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

    /**
     * 取得state
     * @param int $newspaperArticleId 文章id
     * @param bool $withCache 是否从缓冲中取
     * @return string state
     */
    public function GetState($newspaperArticleId, $withCache){

        $result = "";
        if ($newspaperArticleId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'newspaper_article_data';
            $cacheFile = 'newspaper_article_get_state.cache_' . $newspaperArticleId . '';
            $sql = "SELECT State FROM " . self::TableName_NewspaperArticle . "

                    WHERE NewspaperArticleId=:NewspaperArticleId
                     ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperArticleId", $newspaperArticleId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }

        return $result;


    }

    /**
     * 取得文章的频道id
     * @param int $newspaperArticleId 文章id
     * @param bool $withCache 是否从缓冲中取
     * @return string 文章的频道id
     */
    public function GetChannelId($newspaperArticleId, $withCache){

        $result = "";
        if ($newspaperArticleId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'newspaper_article_data';
            $cacheFile = 'newspaper_article_get_channel_id.cache_' . $newspaperArticleId . '';
            $sql = "SELECT ChannelId FROM " . self::TableName_Newspaper . "

                    WHERE NewspaperId =
                    (
                    SELECT NewspaperId FROM ".self::TableName_NewspaperPage." WHERE NewspaperPageId =
                    (SELECT NewspaperPageId FROM ".self::TableName_NewspaperArticle." WHERE NewspaperArticleId=:NewspaperArticleId)


                    );";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperArticleId", $newspaperArticleId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }

        return $result;


    }


    /**
     * 取得电子报文章列表
     * @param int $newspaperPageId 电子报版面id
     * @param string $topCount 显示条数  1或 1,10
     * @param int $state 状态
     * @param int $orderBy 排序方式
     * @return array|null 返回资讯列表
     */
    public function GetList($newspaperPageId, $topCount, $state, $orderBy = 0)
    {

        $result = null;

        if ($newspaperPageId > 0 && !empty($topCount)) {

            $orderBySql = 'na.Sort , na.CreateDate DESC';

            switch ($orderBy) {

                case 0:
                    $orderBySql = 'na.Sort , na.CreateDate DESC';
                    break;

            }


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
                    na.NewspaperPageId=:NewspaperPageId
                    AND na.State=:State
                ORDER BY $orderBySql LIMIT " . $topCount;
            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperPageId", $newspaperPageId);
            $dataProperty->AddField("State", $state);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

        }


        return $result;
    }


    /**
     * 返回一行数据
     * @param int $newspaperArticleId 电子报文章id
     * @return array|null 取得对应数组
     */
    public function GetOne($newspaperArticleId)
    {
        $result = null;
        if ($newspaperArticleId > 0) {
            $sql = "
            SELECT *
            FROM
            " . self::TableName_NewspaperArticle . "
            WHERE NewspaperArticleId=:NewspaperArticleId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperArticleId", $newspaperArticleId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 取得多个版面的电子报文章列表
     * @param string $newspaperPageId 电子报版面id集合
     * @param int $state 状态
     * @param int $orderBy 排序方式
     * @return array|null 返回资讯列表
     */
    public function GetListOfMultiPage($newspaperPageId, $state, $orderBy = 0)
    {
        $result = null;

        if ($newspaperPageId > 0) {

            $orderBySql = 'na.Sort , na.CreateDate DESC';

            switch ($orderBy) {

                case 0:
                    $orderBySql = 'na.Sort , na.CreateDate DESC';
                    break;

            }


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
                    na.NewspaperPageId IN ($newspaperPageId)
                    AND na.State=:State
                ORDER BY $orderBySql " ;
            $dataProperty = new DataProperty();
            $dataProperty->AddField("State", $state);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }


        return $result;
    }

    /**
     * 增加点击数
     * @param int $newspaperArticleId 文章id
     * @return int
     */
    public function AddHitCount($newspaperArticleId){
        $result = -1;

        if($newspaperArticleId>0){

            $sql = "UPDATE ".self::TableName_NewspaperArticle."
                    SET HitCount=HitCount+1
                    WHERE NewspaperArticleId=:NewspaperArticleId;";

            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperArticleId", $newspaperArticleId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);

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

} 