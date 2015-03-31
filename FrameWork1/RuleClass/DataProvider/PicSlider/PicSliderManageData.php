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
     * 修改排序
     * @param int $sort 大于0向上移动，否则向下移动
     * @param int $picSliderId 图片轮换id
     * @return int 返回结果
     */
    public function ModifySort($sort, $picSliderId) {
        $result = 0;
        if ($picSliderId > 0) {

            $channelId = $this->GetChannelId($picSliderId, false);

            $currentSort = $this->GetSort($picSliderId, false);

            if ($sort > 0) { //向上移动
                $dataProperty = new DataProperty();
                $sql = "SELECT
                            Sort
                        FROM " . self::TableName_PicSlider . "

                        WHERE
                            ChannelId=:ChannelId
                        AND PicSliderId<>:PicSliderId
                        AND sort>=:CurrentSort

                        ORDER BY Sort DESC LIMIT 1;
                        ";
                $dataProperty->AddField("ChannelId", $channelId);
                $dataProperty->AddField("PicSliderId", $picSliderId);
                $dataProperty->AddField("CurrentSort", $currentSort);
                $newSort = $this->dbOperator->GetInt($sql, $dataProperty);
            } else{//向下移动
                $dataProperty = new DataProperty();
                $sql = "SELECT
                            Sort
                        FROM " . self::TableName_PicSlider . "

                        WHERE ChannelId=:ChannelId
                        AND PicSliderId<>:PicSliderId
                        AND Sort<=:CurrentSort

                        ORDER BY Sort LIMIT 1;
                        ";
                $dataProperty->AddField("ChannelId", $channelId);
                $dataProperty->AddField("PicSliderId", $picSliderId);
                $dataProperty->AddField("CurrentSort", $currentSort);
                $newSort = $this->dbOperator->GetInt($sql, $dataProperty);
            }

            if ($newSort < 0) {
                $newSort = 0;
            }

            $newSort = $newSort + $sort;

            //排序号禁止负数
            if ($newSort < 0) {
                $newSort = 0;
            }

            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_PicSlider . "
                    SET `Sort`=:NewSort
                    WHERE PicSliderId=:PicSliderId;";
            $dataProperty->AddField("NewSort", $newSort);
            $dataProperty->AddField("PicSliderId", $picSliderId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 拖动排序
     * @param array $arrPicSliderId 待处理的数组
     * @return int 操作结果
     */
    public function ModifySortForDrag($arrPicSliderId)
    {
        if (count($arrPicSliderId) > 1) { //大于1条时排序才有意义
            $strId = join(',', $arrPicSliderId);
            $strId = Format::FormatSql($strId);
            $sql = "SELECT max(Sort) FROM " . self::TableName_PicSlider . " WHERE PicSliderId IN ($strId);";
            $maxSort = $this->dbOperator->GetInt($sql, null);
            $arrSql = array();
            for ($i = 0; $i < count($arrPicSliderId); $i++) {
                $newSort = $maxSort - $i;
                if ($newSort < 0) {
                    $newSort = 0;
                }
                $newSort = intval($newSort);
                $picSliderId = intval($arrPicSliderId[$i]);
                $sql = "UPDATE " . self::TableName_PicSlider . " SET Sort=$newSort WHERE PicSliderId=$picSliderId;";
                $arrSql[] = $sql;
            }
            return $this->dbOperator->ExecuteBatch($arrSql, null);
        }else{
            return -1;
        }
    }


    /**
     * 新增文档时修改排序号到当前频道的最大排序
     * @param int $channelId 频道id
     * @param int $picSliderId 图片轮换id
     * @return int 影响的记录行数
     */
    public function ModifySortWhenCreate($channelId, $picSliderId) {
        $result = -1;
        if($channelId >0 && $picSliderId>0){
            $dataProperty = new DataProperty();
            $sql = "SELECT max(Sort) FROM " . self::TableName_PicSlider . " WHERE ChannelId=:ChannelId;";
            $dataProperty->AddField("ChannelId", $channelId);
            $maxSort = $this->dbOperator->GetInt($sql, $dataProperty);
            $newSort = $maxSort + 1;
            $dataProperty = new DataProperty();
            $dataProperty->AddField("Sort", $newSort);
            $dataProperty->AddField("PicSliderId", $picSliderId);
            $sql = "UPDATE " . self::TableName_PicSlider . " SET Sort=:Sort WHERE PicSliderId=:PicSliderId;";
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 取得所属频道id
     * @param int $picSliderId 图片轮换id
     * @param bool $withCache 是否从缓冲中取
     * @return int 所属频道id
     */
    public function GetChannelId($picSliderId, $withCache)
    {
        $result = -1;
        if ($picSliderId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'pic_slider_data';
            $cacheFile = 'pic_slider_get_channel_id.cache_' . $picSliderId . '';
            $sql = "SELECT ChannelId FROM " . self::TableName_PicSlider . " WHERE PicSliderId=:PicSliderId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("PicSliderId", $picSliderId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        }
        return $result;
    }

    /**
     * 取得排序号
     * @param int $picSliderId 图片轮换id
     * @param bool $withCache 是否从缓冲中取
     * @return int 排序号
     */
    public function GetSort($picSliderId, $withCache)
    {
        $result = -1;
        if ($picSliderId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'pic_slider_data';
            $cacheFile = 'pic_slider_get_sort.cache_' . $picSliderId . '';
            $sql = "SELECT Sort FROM " . self::TableName_PicSlider . " WHERE PicSliderId=:PicSliderId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("PicSliderId", $picSliderId);
            $result = $this->GetInfoOfIntValue($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
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


    /**
     * 取得图片轮换列表
     * @param int $channelId 频道id
     * @param string $topCount 显示条数  1或 1,10
     * @param int $state 状态
     * @param int $orderBy 排序方式
     * @return array|null 返回图片轮换列表
     */
    public function GetListForPublish($channelId, $topCount, $state, $orderBy = 0) {

        $result = null;

        if($channelId>0 && !empty($topCount)){
            $orderBySql = 'ps.Sort DESC, ps.CreateDate DESC';
            switch($orderBy){
                case 0:
                    $orderBySql = 'ps.Sort DESC, ps.CreateDate DESC';
                    break;
            }

            $selectColumn = '
                ps.PicSliderId,
                ps.ChannelId,
                ps.PicSliderTitle,
                ps.DirectUrl,
                ps.TableType,
                ps.TableId,
                uf.UploadFilePath,
                uf.UploadFileMobilePath,
                uf.UploadFilePadPath,
                uf.UploadFileThumbPath1,
                uf.UploadFileThumbPath2,
                uf.UploadFileThumbPath3,
                uf.UploadFileWatermarkPath1,
                uf.UploadFileWatermarkPath2,
                uf.UploadFileCompressPath1,
                uf.UploadFileCompressPath2
            ';

            $sql = "SELECT $selectColumn FROM " . self::TableName_PicSlider . " ps,". self::TableName_UploadFile ." uf
                WHERE
                    ps.ChannelId=:ChannelId
                    AND ps.State=:State
                    AND ps.UploadFileId=uf.UploadFileId
                ORDER BY $orderBySql LIMIT " . $topCount;
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);
            $dataProperty->AddField("State", $state);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }

        return $result;
    }
} 