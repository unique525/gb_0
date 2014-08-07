<?php
/**
 * 后台管理 产品 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Product
 * @author zhangchi
 */
class ProductManageData extends BaseManageData {

    /**
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_Product){
        return parent::GetFields(self::TableName_Product);
    }

    /**
     * 新增产品
     * @param array $httpPostData $_POST数组
     * @param string $titlePic 题图
     * @param string $titlePicMobile 题图-客户端
     * @param string $titlePicPad 题图-平板
     * @return int 新增的产品id
     */
    public function Create($httpPostData, $titlePic, $titlePicMobile, $titlePicPad)
    {
        $result = -1;
        $dataProperty = new DataProperty();
        $addFieldName = "";
        $addFieldValue = "";
        $preNumber = "";
        $addFieldNames = array("TitlePic", "TitlePicMobile", "TitlePicPad");
        $addFieldValues = array($titlePic, $titlePicMobile, $titlePicPad);
        if (!empty($httpPostData)) {
            $sql = parent::GetInsertSql(
                $httpPostData,
                self::TableName_Product,
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
     * 修改产品
     * @param array $httpPostData $_POST数组
     * @param int $productId 产品id
     * @param string $titlePic 题图1，默认为空，不修改
     * @param string $titlePicMobile 题图-客户端，默认为空，不修改
     * @param string $titlePicPad 题图-平板，默认为空，不修改
     * @return int 返回影响的行数
     */
    public function Modify($httpPostData, $productId, $titlePic = '', $titlePicMobile = '', $titlePicPad = '')
    {
        $result = -1;
        $dataProperty = new DataProperty();
        $addFieldName = "";
        $addFieldValue = "";
        $preNumber = "";
        $addFieldNames = array();
        $addFieldValues = array();
        if (!empty($titlePic)) {
            $addFieldNames[] = "TitlePic";
            $addFieldValues[] = $titlePic;
        }
        if (!empty($titlePicMobile)) {
            $addFieldNames[] = "TitlePicMobile";
            $addFieldValues[] = $titlePicMobile;
        }
        if (!empty($titlePicPad)) {
            $addFieldNames[] = "TitlePicPad";
            $addFieldValues[] = $titlePicPad;
        }
        if (!empty($httpPostData)) {
            $sql = parent::GetUpdateSql(
                $httpPostData,
                self::TableName_Product,
                self::TableId_Product,
                $productId,
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
} 