<?php
/**
 * 后台管理 分类信息 数据类
 *
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Activity
 * @author 525
 */
class InformationManageData extends BaseManageData{

    /**
     * 新增分类信息
     * @param array $httpPostData $_post数组
     * @return int 新增活动id
     */
    public function Create($httpPostData) {
        $result=-1;
        $dataProperty = new DataProperty();
        $addFieldName = "";
        $addFieldValue = "";
        $preNumber = "";
        $addFieldNames = array();
        $addFieldValues = array();
        if (!empty($httpPostData)) {
            $sql = parent::GetInsertSql($httpPostData,self::TableName_Information, $dataProperty, $addFieldName, $addFieldValue, $preNumber, $addFieldNames, $addFieldValues);
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        }
        return $result;
    }
    /**
     * 修改
     * @param array $httpPostData $_post数组
     * @param string $informationId 分类信息id
     * @return int 执行结果
     */
    public function Modify($httpPostData,$informationId) {

        $result = -1;
        $dataProperty = new DataProperty();
        $addFieldName = "";
        $addFieldValue = "";
        $preNumber = "";
        $addFieldNames = array();
        $addFieldValues = array();

        if (!empty($httpPostData)) {
            $sql = parent::GetUpdateSql($httpPostData, self::TableName_Information, self::TableId_Information, $informationId, $dataProperty, $addFieldName, $addFieldValue, $preNumber, $addFieldNames, $addFieldValues);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }
    /**
     * 获取活动分页列表
     * @param int $channelId 频道id
     * @param int $pageBegin 起始页码
     * @param int $pageSize 每页大小
     * @param int $allCount 总大小
     * @param string $searchKey 搜索关键字
     * @return array 活动数据集
     */
    public function GetListPager($channelId, $pageBegin, $pageSize, &$allCount, $searchKey) {
        $result=-1;
        if($channelId>0){
            $searchSql = "";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("ChannelId", $channelId);

            if (strlen($searchKey) > 0 && $searchKey != "undefined") {
                $searchSql.=" AND InformationTitle LIKE :SearchKey ";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            }

            $sql = "
                SELECT
                *
                FROM
                " . self::TableName_Information . "
                WHERE ChannelId=:ChannelId " . $searchSql . " ORDER BY Sort DESC, CreateDate DESC LIMIT " . $pageBegin . "," . $pageSize . " ;";

            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
            $sqlCount = "SELECT count(*) FROM " . self::TableName_Information . " WHERE ChannelId=:ChannelId " . $searchSql . " ;";
            $allCount = $this->dbOperator->GetInt($sqlCount, $dataProperty);
        }
        return $result;
    }

    /**
     * 通过ID获取一条记录
     * @param int $informationId 活动id
     * @return array 活动数据
     */
    public function GetOne($informationId) {
        $result=-1;
        if($informationId>0){
            $sql = "SELECT * FROM " . self::TableName_Information . " WHERE InformationId = :InformationId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("InformationId", $informationId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_Information){
        return parent::GetFields(self::TableName_Information);
    }




    /**
     * 修改活动题图的上传文件id
     * @param int $informationId 频道id
     * @param int $titlePic1UploadFileId 题图1上传文件id
     * @return int 操作结果
     */
    public function ModifyTitlePic($informationId, $titlePic1UploadFileId)
    {
        $result = -1;
        if($informationId>0){
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_Information . " SET
                    TitlePic1UploadFileId = :TitlePic1UploadFileId,

                    WHERE InformationId = :InformationId

                    ;";
            $dataProperty->AddField("TitlePic1UploadFileId", $titlePic1UploadFileId);
            $dataProperty->AddField("InformationId", $informationId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }


    /**
     * 修改活动题图1的上传文件id
     * @param int $informationId 活动id
     * @param int $titlePicUploadFileId 题图上传文件id
     * @return int 操作结果
     */
    public function ModifyTitlePic1($informationId, $titlePicUploadFileId)
    {
        $result = -1;
        if($informationId>0){
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_Information . " SET
                    TitlePic1UploadFileId = :TitlePic1UploadFileId

                    WHERE InformationId = :InformationId

                    ;";
            $dataProperty->AddField("TitlePic1UploadFileId", $titlePicUploadFileId);
            $dataProperty->AddField("InformationId", $informationId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }

    /**
     * 修改活动状态
     * @param string $informationId 题目Id
     * @param string $state 状态
     * @return int 执行结果
     */
    public function ModifyState($informationId,$state) {
        $result = -1;
        if ($informationId < 0) {
            return $result;
        }
        $sql = "UPDATE " . self::TableName_Information . " SET State=:State WHERE InformationId=:InformationId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("InformationId", $informationId);
        $dataProperty->AddField("State", $state);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }
}
?>