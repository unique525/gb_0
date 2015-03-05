<?php
/**
 * 后台管理 资讯 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Document
 * @author 525
 */
class DocumentNewsPicManageData extends BaseManageData
{


    /**
     * 取得字段数据集
     * @param string $tableName 表名
     * @return array 字段数据集
     */
    public function GetFields($tableName = self::TableName_DocumentNewsPic){
        return parent::GetFields(self::TableName_DocumentNewsPic);
    }

    /**
     * 在文档下新增内容图片
     * @param int $documentNewsId 资讯文档id
     * @param int $uploadFileId 附件id
     * @param int $showInPicSlider 是否显示在组图控件
     * @return int 新增的图片id
     */
    public function Create($documentNewsId, $uploadFileId, $showInPicSlider)
    {
        $result = -1;
        if ($documentNewsId>0&&$uploadFileId>0) {
            $dataProperty = new DataProperty();
            $dataProperty->AddField("DocumentNewsId", $documentNewsId);
            $dataProperty->AddField("UploadFileId", $uploadFileId);

            //$sql = "SELECT count(*) FROM " . self::TableName_DocumentNewsPic. " WHERE DocumentNewsId=:DocumentNewsId AND UploadFileId=:UploadFileId ;";
            //$result = $this->dbOperator->GetInt($sql, $dataProperty);


            $sql = "INSERT INTO " . self::TableName_DocumentNewsPic. "
                        (DocumentNewsId,UploadFileId,ShowInPicSlider)
                    VALUES
                        (:DocumentNewsId,:UploadFileId,:ShowInPicSlider);";
            $dataProperty->AddField("ShowInPicSlider", $showInPicSlider);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 图片是否显示在组图控件中
     * @param int $documentNewsPicId 资讯文档id
     * @param int $showInPicSlider 附件id
     * @return int 操作结果
     */
    public function ChangeShowingState($documentNewsPicId, $showInPicSlider)
    {
        $result = -1;
        if ($documentNewsPicId>0) {
            $dataProperty = new DataProperty();
            $dataProperty->AddField("DocumentNewsPicId", $documentNewsPicId);
            $dataProperty->AddField("ShowInPicSlider", $showInPicSlider);
            $sql = "UPDATE " . self::TableName_DocumentNewsPic. " SET ShowInPicSlider=:ShowInPicSlider WHERE DocumentNewsPicId=:DocumentNewsPicId;";
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 删除内容图片
     * @param int $documentNewsPicId 资讯文档id
     * @return int 新增的图片id
     */
    public function Delete($documentNewsPicId)
    {
        $result = -1;
        if ($documentNewsPicId>0) {
            $dataProperty = new DataProperty();
            $dataProperty->AddField("DocumentNewsPicId", $documentNewsPicId);
            $sql = "DELETE FROM " . self::TableName_DocumentNewsPic. " WHERE DocumentNewsPicId=:DocumentNewsPicId;";
            $result = $this->dbOperator->Execute($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 取得文档下内容图片列表,包括所有图片的地址
     * @param int $documentNewsId 资讯文档id
     * @param int $topCount 条数
     * @param int $showInPicSlider 是否显示在控件中
     * @return array 内容图片数据集
     */
    public function GetList($documentNewsId,$topCount=-1,$showInPicSlider=-1)
    {
        $result = -1;
        if ($documentNewsId>0) {
            if($showInPicSlider>=0){
                $selectShowInSlider="AND p.ShowInPicSlider=".$showInPicSlider;
            }else{
                $selectShowInSlider="";
            }
            if($topCount>=0){
                $limit="LIMIT ".$topCount;
            }else{
                $limit="";
            }
            $dataProperty = new DataProperty();
            $dataProperty->AddField("DocumentNewsId", $documentNewsId);
            $sql = "SELECT p.*,
                           u.UploadFilePath AS UploadFilePath,
                           u.UploadFileMobilePath AS UploadFileMobilePath,
                           u.UploadFilePadPath AS UploadFilePadPath,
                           u.UploadFileTitle AS UploadFileTitle,
                           u.UploadFileInfo AS UploadFileInfo
                    FROM " . self::TableName_DocumentNewsPic. " p
                    LEFT OUTER JOIN " .self::TableName_UploadFile." u on p.UploadFileId=u.UploadFileId
                    WHERE p.DocumentNewsId=:DocumentNewsId ".$selectShowInSlider." ".$limit.";";
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 取得文档下内容图片列表,包括所有图片的地址
     * @param int $documentNewsId 资讯文档id
     * @param int $topCount 条数
     * @return array 内容图片数据集
     */
    public function GetListForSlider($documentNewsId,$topCount=-1)
    {
        $result = -1;
        if ($documentNewsId>0) {
            if($topCount>=0){
                $limit="LIMIT ".$topCount;
            }else{
                $limit="";
            }
            $dataProperty = new DataProperty();
            $dataProperty->AddField("DocumentNewsId", $documentNewsId);
            $sql = "SELECT p.*,
                           u.UploadFilePath AS UploadFilePath,
                           u.UploadFileMobilePath AS UploadFileMobilePath,
                           u.UploadFilePadPath AS UploadFilePadPath
                    FROM " . self::TableName_DocumentNewsPic. " p
                    LEFT OUTER JOIN " .self::TableName_UploadFile." u on p.UploadFileId=u.UploadFileId
                    WHERE p.DocumentNewsId=:DocumentNewsId $limit;";
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 取得资讯id
     * @param int $documentNewsPicId 图片id
     * @return int 资讯id
     */
    public function GetDocumentNewsId($documentNewsPicId)
    {
        $result = -1;
        if ($documentNewsPicId>0) {
            $dataProperty = new DataProperty();
            $dataProperty->AddField("DocumentNewsPicId", $documentNewsPicId);
            $sql = "SELECT DocumentNewsId FROM " . self::TableName_DocumentNewsPic. " WHERE DocumentNewsPicId=:DocumentNewsPicId;";
            $result = $this->dbOperator->GetInt($sql, $dataProperty);
        }
        return $result;
    }


    /**
     * 取得UploadFileId
     * @param int $documentNewsPicId 图片id
     * @return int UploadFileId
     */
    public function GetUploadFileId($documentNewsPicId)
    {
        $result = -1;
        if ($documentNewsPicId>0) {
            $dataProperty = new DataProperty();
            $dataProperty->AddField("DocumentNewsPicId", $documentNewsPicId);
            $sql = "SELECT UploadFileId FROM " . self::TableName_DocumentNewsPic. " WHERE DocumentNewsPicId=:DocumentNewsPicId;";
            $result = $this->dbOperator->GetInt($sql, $dataProperty);
        }
        return $result;
    }







}

?>