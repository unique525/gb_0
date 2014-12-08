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

} 