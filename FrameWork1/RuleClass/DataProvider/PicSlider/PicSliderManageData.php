<?php

/**
 * 后台管理 图片轮换 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_PicSlider
 * @author zhangchi
 */
class PicSliderManageData extends BaseManageData {
    /**
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_PicSlider)
    {
        return parent::GetFields(self::TableName_PicSlider);
    }


    /**
     * 新增图片轮换
     * @param array $httpPostData $_POST数组
     * @return int 新增的图片轮换id
     */
    public function Create($httpPostData)
    {
        $result = -1;
        $dataProperty = new DataProperty();
        $addFieldName = "";
        $addFieldValue = "";
        $preNumber = "";
        $addFieldNames = array("CreateDate");
        $addFieldValues = array(date("Y-m-d H:i:s", time()));
        if (!empty($httpPostData)) {
            $sql = parent::GetInsertSql(
                $httpPostData,
                self::TableName_PicSlider,
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
     * 修改图片轮换
     * @param array $httpPostData $_POST数组
     * @param int $picSliderId 图片轮换id
     * @return int 返回影响的行数
     */
    public function Modify($httpPostData, $picSliderId)
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
                self::TableName_PicSlider,
                self::TableId_PicSlider,
                $picSliderId,
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
     * 修改上传文件id
     * @param int $picSliderId 图片轮换id
     * @param int $uploadFileId 上传文件id
     * @return int 操作结果
     */
    public function ModifyUploadFileId($picSliderId, $uploadFileId)
    {
        $result = -1;
        if ($picSliderId > 0) {
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_PicSlider . " SET
                    UploadFileId = :UploadFileId

                    WHERE PicSliderId = :PicSliderId
                    ;";
            $dataProperty->AddField("UploadFileId", $uploadFileId);
            $dataProperty->AddField("PicSliderId", $picSliderId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }

} 