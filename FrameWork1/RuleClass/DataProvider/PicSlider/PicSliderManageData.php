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
     * @param int $manageUserId 后台管理员id
     * @return int 新增的图片轮换id
     */
    public function Create($httpPostData, $manageUserId)
    {
        $result = -1;
        $dataProperty = new DataProperty();
        $addFieldName = "";
        $addFieldValue = "";
        $preNumber = "";
        $addFieldNames = array("CreateDate","ManageUserId");
        $addFieldValues = array(date("Y-m-d H:i:s", time()),$manageUserId);
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


    /**
     * 返回一行数据
     * @param int $picSliderId 图片轮换id
     * @return array|null 取得对应数组
     */
    public function GetOne($picSliderId)
    {
        $result = null;
        if ($picSliderId > 0) {
            $sql = "SELECT * FROM " . self::TableName_PicSlider . " WHERE PicSliderId=:PicSliderId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("PicSliderId", $picSliderId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 取得上传文件id
     * @param int $picSliderId 图片轮换id
     * @param bool $withCache 是否从缓冲中取
     * @return int 上传文件id
     */
    public function GetUploadFileId($picSliderId, $withCache)
    {
        $result = -1;
        if ($picSliderId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'pic_slider_data';
            $cacheFile = 'pic_slider_get_upload_file_id.cache_' . $picSliderId . '';
            $sql = "SELECT UploadFileId FROM " . self::TableName_PicSlider . "
                    WHERE PicSliderId = :PicSliderId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("PicSliderId", $picSliderId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }


    /**
     * 取得列表数据集
     * @param int $channelId 频道id
     * @param int $pageBegin 起始记录行
     * @param int $pageSize 页大小
     * @param int $allCount 记录总数
     * @param string $searchKey 查询字符
     * @param int $searchType 查询下拉框的类别
     * @param int $isSelf 是否只显示当前登录的管理员录入的资讯
     * @param int $manageUserId 查显示某个后台管理员id
     * @return array 列表数据集
     */
    public function GetList(
        $channelId,
        $pageBegin,
        $pageSize,
        &$allCount,
        $searchKey = "",
        $searchType = 0,
        $isSelf = 0,
        $manageUserId = 0
    )
    {
        $searchSql = "";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("ChannelId", $channelId);
        if (strlen($searchKey) > 0 && $searchKey != "undefined") {
            if ($searchType == 0) { //标题
                $searchSql = " AND (PicSliderTitle like :SearchKey)";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            }
        }
        if ($isSelf === 1 && $manageUserId > 0) {
            $conditionManageUserId = ' AND ManageUserId=' . intval($manageUserId);
        } else {
            $conditionManageUserId = "";
        }

        $sql = "
            SELECT
                *,
                (SELECT UploadFilePath
                    FROM ". self::TableName_UploadFile ."
                    WHERE
                        UploadFileId=" . self::TableName_PicSlider . ".UploadFileId
                ) AS UploadFilePath

            FROM
            " . self::TableName_PicSlider . "
            WHERE
                ChannelId=:ChannelId
                AND State<100

                " . $searchSql . " " . $conditionManageUserId . "
            ORDER BY Sort DESC, CreateDate DESC LIMIT " . $pageBegin . "," . $pageSize . ";";

        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);

        $sql = "SELECT count(*) FROM " . self::TableName_PicSlider . "
                WHERE ChannelId=:ChannelId AND State<100 " . $conditionManageUserId . " " . $searchSql . ";";
        $allCount = $this->dbOperator->GetInt($sql, $dataProperty);

        return $result;
    }


} 