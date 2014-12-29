<?php
/**
 * 客户端 产品 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Product
 * @author zhangchi
 */
class ProductClientData extends BaseClientData {

    /**
     * @param $channelId
     * @param string $order
     * @param null $topCount
     * @return array|null
     */
    public function GetListOfChannelId($channelId, $order = "", $topCount = null)
    {
        $result = null;
        if ($topCount != null)
        {
            $topCount = " limit " . $topCount;
        }
        else {
            $topCount = "";
        }
        if (!empty($channelId)) {
            switch ($order) {
                case "time_desc":
                    $order = " ORDER BY Createdate DESC";
                    break;
                case "time_asc":
                    $order = " ORDER BY Createdate ASC";
                    break;
                case "sale_desc":
                    $order = " ORDER BY SaleCount DESC";
                    break;
                case "sale_asc":
                    $order = " ORDER BY SaleCount ASC";
                    break;
                case "price_desc":
                    $order = " ORDER BY SalePrice DESC";
                    break;
                case "price_asc":
                    $order = " ORDER BY SalePrice ASC";
                    break;
                case "comment_asc":
                    $order = " ORDER BY Sort DESC,Createdate DESC";
                    break;
                case "comment_desc":
                    $order = " ORDER BY Sort DESC,Createdate DESC";
                    break;
                default:
                    $order = " ORDER BY Sort DESC,Createdate DESC";
                    break;
            }
            $sql = "SELECT

                    t.*,

                    t1.UploadFilePath AS TitlePic1UploadFilePath,
                    t1.UploadFileMobilePath AS TitlePic1UploadFileMobilePath


                    FROM " . self::TableName_Product ." t
                    LEFT OUTER JOIN " .self::TableName_UploadFile." t1 on t.TitlePic1UploadFileId=t1.UploadFileId
                    WHERE t.ChannelId IN (".$channelId.") AND t.State<100"
                . $order
                . $topCount;
            $dataProperty = new DataProperty();
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 根据频道ID获取量贩产品记录
     * @param int $channelId 频道Id,可以是 id,id,id 的形式
     * @param string $order 排序方式
     * @param int $topCount 显示的条数
     * @return array|null  列表数组
     */
    public function GetDiscountListByChannelId($channelId, $order = "", $topCount = null)
    {
        $result = null;
        if ($topCount != null){
            $topCount = " LIMIT " . $topCount;
        }else{
            $topCount = "";
        }
        if (strlen($channelId) > 0) {
            $channelId = Format::FormatSql($channelId);
            switch ($order) {
                default:
                    $order = " ORDER BY p.Sort DESC,p.Createdate DESC";
                    break;
            }
            $sql = "SELECT

                        p.*,
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


                        uf2.UploadFilePath AS TitlePic2UploadFilePath,
                        uf2.UploadFileMobilePath AS TitlePic2UploadFileMobilePath,
                        uf2.UploadFilePadPath AS TitlePic2UploadFilePadPath,
                        uf2.UploadFileThumbPath1 AS TitlePic2UploadFileThumbPath1,
                        uf2.UploadFileThumbPath2 AS TitlePic2UploadFileThumbPath2,
                        uf2.UploadFileThumbPath3 AS TitlePic2UploadFileThumbPath3,
                        uf2.UploadFileWatermarkPath1 AS TitlePic2UploadFileWatermarkPath1,
                        uf2.UploadFileWatermarkPath2 AS TitlePic2UploadFileWatermarkPath2,
                        uf2.UploadFileCompressPath1 AS TitlePic2UploadFileCompressPath1,
                        uf2.UploadFileCompressPath2 AS TitlePic2UploadFileCompressPath2,


                        uf3.UploadFilePath AS TitlePic3UploadFilePath,
                        uf3.UploadFileMobilePath AS TitlePic3UploadFileMobilePath,
                        uf3.UploadFilePadPath AS TitlePic3UploadFilePadPath,
                        uf3.UploadFileThumbPath1 AS TitlePic3UploadFileThumbPath1,
                        uf3.UploadFileThumbPath2 AS TitlePic3UploadFileThumbPath2,
                        uf3.UploadFileThumbPath3 AS TitlePic3UploadFileThumbPath3,
                        uf3.UploadFileWatermarkPath1 AS TitlePic3UploadFileWatermarkPath1,
                        uf3.UploadFileWatermarkPath2 AS TitlePic3UploadFileWatermarkPath2,
                        uf3.UploadFileCompressPath1 AS TitlePic3UploadFileCompressPath1,
                        uf3.UploadFileCompressPath2 AS TitlePic3UploadFileCompressPath2,


                        uf4.UploadFilePath AS TitlePic4UploadFilePath,
                        uf4.UploadFileMobilePath AS TitlePic4UploadFileMobilePath,
                        uf4.UploadFilePadPath AS TitlePic4UploadFilePadPath,
                        uf4.UploadFileThumbPath1 AS TitlePic4UploadFileThumbPath1,
                        uf4.UploadFileThumbPath2 AS TitlePic4UploadFileThumbPath2,
                        uf4.UploadFileThumbPath3 AS TitlePic4UploadFileThumbPath3,
                        uf4.UploadFileWatermarkPath1 AS TitlePic4UploadFileWatermarkPath1,
                        uf4.UploadFileWatermarkPath2 AS TitlePic4UploadFileWatermarkPath2,
                        uf4.UploadFileCompressPath1 AS TitlePic4UploadFileCompressPath1,
                        uf4.UploadFileCompressPath2 AS TitlePic4UploadFileCompressPath2


                    FROM " . self::TableName_Product ." p
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf1 on p.TitlePic1UploadFileId=uf1.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf2 on p.TitlePic2UploadFileId=uf2.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf3 on p.TitlePic3UploadFileId=uf3.UploadFileId
                    LEFT OUTER JOIN " .self::TableName_UploadFile." uf4 on p.TitlePic4UploadFileId=uf4.UploadFileId
                    WHERE p.ChannelId IN (".$channelId.") AND p.IsDiscount=1 AND p.State<100"
                    . $order
                    . $topCount;

            $dataProperty = new DataProperty();
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 根据推荐级别获取产品记录
     * @param int $siteId 站点id
     * @param int $recLevel 推荐级别
     * @param string $order 排序方式
     * @param int $topCount 显示的条数
     * @return array|null  列表数组
     */
    public function GetListByRecLevel($siteId, $recLevel, $order = "", $topCount = null)
    {
        $result = null;
        if ($topCount != null)
            $topCount = " LIMIT " . $topCount;
        else $topCount = "";
        switch ($order) {
            default:
                $order = " ORDER BY p.Sort DESC,p.CreateDate DESC,p.RecLevel DESC";
                break;
        }
        $sql = "SELECT
                p.*,
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


                uf2.UploadFilePath AS TitlePic2UploadFilePath,
                uf2.UploadFileMobilePath AS TitlePic2UploadFileMobilePath,
                uf2.UploadFilePadPath AS TitlePic1Up2oadFilePadPath,
                uf2.UploadFileThumbPath1 AS TitlePic2UploadFileThumbPath1,
                uf2.UploadFileThumbPath2 AS TitlePic2UploadFileThumbPath2,
                uf2.UploadFileThumbPath3 AS TitlePic2UploadFileThumbPath3,
                uf2.UploadFileWatermarkPath1 AS TitlePic2UploadFileWatermarkPath1,
                uf2.UploadFileWatermarkPath2 AS TitlePic2UploadFileWatermarkPath2,
                uf2.UploadFileCompressPath1 AS TitlePic2UploadFileCompressPath1,
                uf2.UploadFileCompressPath2 AS TitlePic2UploadFileCompressPath2,


                uf3.UploadFilePath AS TitlePic3UploadFilePath,
                uf3.UploadFileMobilePath AS TitlePic3UploadFileMobilePath,
                uf3.UploadFilePadPath AS TitlePic3UploadFilePadPath,
                uf3.UploadFileThumbPath1 AS TitlePic3UploadFileThumbPath1,
                uf3.UploadFileThumbPath2 AS TitlePic3UploadFileThumbPath2,
                uf3.UploadFileThumbPath3 AS TitlePic3UploadFileThumbPath3,
                uf3.UploadFileWatermarkPath1 AS TitlePic3UploadFileWatermarkPath1,
                uf3.UploadFileWatermarkPath2 AS TitlePic3UploadFileWatermarkPath2,
                uf3.UploadFileCompressPath1 AS TitlePic3UploadFileCompressPath1,
                uf3.UploadFileCompressPath2 AS TitlePic3UploadFileCompressPath2,


                uf4.UploadFilePath AS TitlePic4UploadFilePath,
                uf4.UploadFileMobilePath AS TitlePic4UploadFileMobilePath,
                uf4.UploadFilePadPath AS TitlePic4UploadFilePadPath,
                uf4.UploadFileThumbPath1 AS TitlePic4UploadFileThumbPath1,
                uf4.UploadFileThumbPath2 AS TitlePic4UploadFileThumbPath2,
                uf4.UploadFileThumbPath3 AS TitlePic4UploadFileThumbPath3,
                uf4.UploadFileWatermarkPath1 AS TitlePic4UploadFileWatermarkPath1,
                uf4.UploadFileWatermarkPath2 AS TitlePic4UploadFileWatermarkPath2,
                uf4.UploadFileCompressPath1 AS TitlePic4UploadFileCompressPath1,
                uf4.UploadFileCompressPath2 AS TitlePic4UploadFileCompressPath2


                FROM " . self::TableName_Product ." p
                LEFT OUTER JOIN " .self::TableName_UploadFile." uf1 on p.TitlePic1UploadFileId=uf1.UploadFileId
                LEFT OUTER JOIN " .self::TableName_UploadFile." uf2 on p.TitlePic2UploadFileId=uf2.UploadFileId
                LEFT OUTER JOIN " .self::TableName_UploadFile." uf3 on p.TitlePic3UploadFileId=uf3.UploadFileId
                LEFT OUTER JOIN " .self::TableName_UploadFile." uf4 on p.TitlePic4UploadFileId=uf4.UploadFileId
                WHERE p.RecLevel=:RecLevel AND p.SiteId=:SiteId AND p.State<100"
            . $order
            . $topCount;
        $dataProperty = new DataProperty();
        $dataProperty->AddField("RecLevel", $recLevel);
        $dataProperty->AddField("SiteId", $siteId);
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        return $result;
    }
} 