<?php
/**
 * 后台管理 广告内容 数据类
 *
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_site
 * @author 525
 */

class SiteAdContentManageData  extends BaseManageData{

    /**
     * 新增广告
     * @param array $httpPostData $_post数组
     * @return int 新增广告id
     */
    public function Create($httpPostData){
        $result=-1;
        $dataProperty = new DataProperty();
        $addFieldName = "";
        $addFieldValue = "";
        $preNumber = "";
        $addFieldNames = array();
        $addFieldValues = array();
        if(!empty($httpPostData)){
            $sql=parent::GetInsertSql($httpPostData, self::TableName_SiteAdContent, $dataProperty, $addFieldName, $addFieldValue, $preNumber, $addFieldNames, $addFieldValues);
            $result=$this->dbOperator->LastInsertId($sql,$dataProperty);
        }
        return $result;
    }


    /**
     * 修改
     * @param array $httpPostData $_post数组
     * @param int $siteAdContentId 广告id
     * @return int 执行结果
     */
    public function Modify($httpPostData,$siteAdContentId){
        $dataProperty = new DataProperty();
        $sql = parent::GetUpdateSql($httpPostData, self::TableName_SiteAdContent, self::TableId_SiteAdContent, $siteAdContentId, $dataProperty);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }


    /**
     * 获取活动分页列表
     * @param int $siteAdId 广告位id
     * @param int $pageBegin 起始页码
     * @param int $pageSize 每页大小
     * @param int $allCount 总大小
     * @param string $searchKey 搜索关键字
     * @return array 广告数据集
     */
    public function GetListPager($siteAdId, $pageBegin, $pageSize, &$allCount, $searchKey) {
        $result=-1;
        if($siteAdId>0){
            $searchSql = "";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteAdId", $siteAdId);

            if (strlen($searchKey) > 0 && $searchKey != "undefined") {
                $searchSql.=" AND SiteAdContentTitle LIKE :SearchKey ";
                $dataProperty->AddField("SearchKey", "%" . $searchKey . "%");
            }

            $sql = "
                SELECT
                *
                FROM
                " . self::TableName_SiteAdContent . "
                WHERE SiteAdId=:SiteAdId " . $searchSql . " ORDER BY Sort DESC, CreateDate DESC LIMIT " . $pageBegin . "," . $pageSize . " ;";

            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
            $sqlCount = "SELECT count(*) FROM " . self::TableName_SiteAdContent . " WHERE SiteAdId=:SiteAdId " . $searchSql . " ;";
            $allCount = $this->dbOperator->GetInt($sqlCount, $dataProperty);
        }
        return $result;
    }

    /**
     * 获取广告位下所有启用状态广告列表
     * @param int $siteAdId 广告位id
     * @return array 广告数据集
     */
    public function GetList($siteAdId) {
        $result=-1;
        if($siteAdId>0){
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteAdId", $siteAdId);


            $sql = "
                SELECT
                *
                FROM
                " . self::TableName_SiteAdContent . "
                WHERE SiteAdId=:SiteAdId AND State=0 ORDER BY Sort DESC, CreateDate DESC ;";

            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 取得广告位所有广告 准备生成JS
     * @param  int $siteAdId
     * @return array 广告数据集
     */
    public function GetAllAdContent($siteAdId) {
        $dataProperty = new DataProperty();
        $sql = "SELECT * FROM " . self::TableName_SiteAdContent . " WHERE SiteAdId=:SiteAdId AND State=0 AND EndDate>now() ORDER BY Sort Desc, SiteAdContentId Desc ;";
        $dataProperty->AddField("SiteAdId", $siteAdId);
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        return $result;
    }

    /**
     * 取得广告位最后一条可用广告 准备生成JS
     * @param  int $siteAdId
     * @return array 广告数据集
     */
    public function GetLastAdContent($siteAdId) {
        $dataProperty = new DataProperty();
        $sql = "SELECT * FROM " . self::TableName_SiteAdContent . " WHERE SiteAdId=:SiteAdId AND State=0 ORDER BY EndDate Desc, Sort Desc, SiteAdContentId Desc limit 1;";
        $dataProperty->AddField("SiteAdId", $siteAdId);
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        return $result;
    }

    /**
     * 通过ID获取一条记录
     * @param int $siteAdContentId 广告id
     * @return array 广告数据
     */
    public function GetOne($siteAdContentId) {
        $result=-1;
        if($siteAdContentId>0){
            $sql = "SELECT * FROM " . self::TableName_SiteAdContent . " WHERE SiteAdContentId = :SiteAdContentId ;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteAdContentId", $siteAdContentId);
            $result = $this->dbOperator->GetArray($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_SiteAdContent){
        return parent::GetFields(self::TableName_SiteAdContent);
    }


    /**
     * 修改广告位状态
     * @param string $siteAdContentId 广告Id
     * @param string $state 状态
     * @return int 执行结果
     */
    public function ModifyState($siteAdContentId,$state) {
        $result = -1;
        if ($siteAdContentId < 0) {
            return $result;
        }
        $sql = "UPDATE " . self::TableName_SiteAdContent . " SET State=:State WHERE SiteAdContentId=:SiteAdContentId";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("SiteAdContentId", $siteAdContentId);
        $dataProperty->AddField("State", $state);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }

    /**
     * 修改题图的上传文件id
     * @param int $siteAdContentId 广告id
     * @param int $titlePicUploadFileId 题图上传文件id
     * @return int 操作结果
     */
    public function ModifyTitlePic($siteAdContentId, $titlePicUploadFileId)
    {
        $result = -1;
        if($siteAdContentId>0){
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_SiteAdContent . " SET
                    TitlePicUploadFileId = :TitlePicUploadFileId

                    WHERE SiteAdContentId = :SiteAdContentId

                    ;";
            $dataProperty->AddField("TitlePicUploadFileId", $titlePicUploadFileId);
            $dataProperty->AddField("SiteAdContentId", $siteAdContentId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }


    /**
     * 格式化SiteAdContent字段 保留第一个元素
     * @param int $siteAdContentId 广告id
     * @param int $siteAdContent 内容
     * @return int 操作结果
     */
    public function ModifyContent($siteAdContentId, $siteAdContent)
    {
        $result = -1;
        if($siteAdContentId>0){
            $dataProperty = new DataProperty();
            $sql = "UPDATE " . self::TableName_SiteAdContent . " SET
                    SiteAdContent = :SiteAdContent

                    WHERE SiteAdContentId = :SiteAdContentId

                    ;";
            $dataProperty->AddField("SiteAdContent", $siteAdContent);
            $dataProperty->AddField("SiteAdContentId", $siteAdContentId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }

        return $result;
    }

    /**
     * 获取SiteAdContent字段
     * @param int $siteAdContentId 广告id
     * @return int 操作结果
     */
    public function GetContent($siteAdContentId)
    {
        $result = -1;
        if($siteAdContentId>0){
            $dataProperty = new DataProperty();
            $sql = "SELECT SiteAdContent FROM " . self::TableName_SiteAdContent . " WHERE SiteAdContentId = :SiteAdContentId ;";
            $dataProperty->AddField("SiteAdContentId", $siteAdContentId);
            $result = $this->dbOperator->GetString($sql, $dataProperty);
        }

        return $result;
    }
}
?>