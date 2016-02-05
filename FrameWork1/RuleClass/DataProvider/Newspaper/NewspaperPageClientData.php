<?php
/**
 * 客户端 电子报版面文章 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Newspaper
 * @author zhangchi
 */
class NewspaperPageClientData extends BaseClientData {


    /**
     * 取得版面列表
     * @param int $newspaperId 电子报id
     * @param bool $withCache 是否从缓冲中取
     * @return array 电子报版面信息数据集
     */
    public function GetList($newspaperId, $withCache=FALSE)
    {
        $result=-1;
        if($newspaperId>0){
            $cacheDir = "";//CACHE_PATH . DIRECTORY_SEPARATOR . '_data';
            $cacheFile = "";
            $sql = "SELECT
                    np.*,
                    uf.UploadFilePath AS PicUploadFilePath,
                    uf.UploadFileCompressPath1 AS PicUploadFileCompressPath1

                FROM " . self::TableName_NewspaperPage . " np," . self::TableName_UploadFile . " uf

                WHERE np.NewspaperId =:NewspaperId

                AND np.PicUploadFileId=uf.UploadFileId
                AND np.State<100

                ORDER BY np.Sort,np.NewspaperPageId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("NewspaperId", $newspaperId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);}
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
}