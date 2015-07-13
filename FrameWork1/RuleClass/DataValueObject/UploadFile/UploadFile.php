<?php

/**
 * 上传文件对象类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataValueObject_UploadFile
 * @property string $ErrorMessage 错误代码
 * @property string $ResultHtml 输出结果字符串，一般是html的
 * @property string $UploadFileId 上传文件id
 * @property string $UploadFilePath 上传文件路径（文件夹+文件名）
 * @property string $UploadFileMobilePath 上传文件路径（移动客户端用）（文件夹+文件名）
 * @property string $UploadFilePadPath 上传文件路径（平板客户端用）（文件夹+文件名）
 * @property string UploadFileExtentionName
 * @property int UploadFileSize
 * @property int UploadFileType
 * @property string UploadFileOrgName
 * @property string UploadFileThumbPath1
 * @property string UploadFileThumbPath2
 * @property string UploadFileThumbPath3
 * @property string UploadFileWatermarkPath1
 * @property string UploadFileWatermarkPath2
 * @property string UploadFileCompressPath1
 * @property string UploadFileCompressPath2
 * @property string UploadFileCutPath1
 * @property string UploadFileTitle
 * @property string UploadFileInfo
 * @property int TableType
 * @property int TableId
 * @property int ManageUserId
 * @property int UserId
 * @property string CreateDate
 * @property int IsBatchUpload
 * @author zhangchi
 */
class UploadFile {

    private $ErrorMessage = "";
    private $ResultHtml = "";
    private $UploadFileId = 0;
    private $UploadFilePath = "";

    private $UploadFileMobilePath = "";
    private $UploadFilePadPath = "";
    private $UploadFileExtentionName = "";
    private $UploadFileSize = 0;
    private $UploadFileType = 0;
    private $UploadFileOrgName = "";
    private $UploadFileThumbPath1 = "";
    private $UploadFileThumbPath2 = "";
    private $UploadFileThumbPath3 = "";
    private $UploadFileWatermarkPath1 = "";
    private $UploadFileWatermarkPath2 = "";
    private $UploadFileCompressPath1 = "";
    private $UploadFileCompressPath2 = "";
    private $UploadFileCutPath1 = "";
    private $UploadFileTitle = "";
    private $UploadFileInfo = "";
    private $TableType = 0;
    private $TableId = 0;
    private $ManageUserId = 0;
    private $UserId = 0;
    private $CreateDate = "";
    private $IsBatchUpload = 0;

    /**
     * 构造上传文件类
     * @param int $errorMessage 错误代码
     * @param string $resultHtml 输出结果字符串，一般是html的
     * @param int $uploadFileId 上传文件id
     * @param string $uploadFilePath 上传文件路径
     */
    public function __construct($errorMessage = 0,$resultHtml = "",$uploadFileId = -1,$uploadFilePath = "") {
        $this->ErrorMessage = $errorMessage;
        $this->ResultHtml = $resultHtml;
        $this->UploadFileId = $uploadFileId;
        $this->UploadFilePath = $uploadFilePath;
    }

    /**
     * 取得属性值
     * @param string $name 属性字段名称
     * @return mixed 属性值
     */
    public function __get($name)
    {
        if (method_exists($this, ($method = 'get'.$name)))
        {
            return $this->$method();
        }
        else return NULL;
    }

    /**
     * 设置属性值
     * @param string $name 属性字段名称
     * @param string $value 属性值
     */
    public function __set($name, $value)
    {
        if (method_exists($this, ($method = 'set'.$name)))
        {
            //format value

            $value = str_ireplace('"', '', $value);
            $value = str_ireplace('\\', '', $value);

            $this->$method($value);
        }
    }

    /**
     * @param int $UploadFileId
     */
    public function setUploadFileId($UploadFileId)
    {
        $this->UploadFileId = $UploadFileId;
    }

    /**
     * @return int
     */
    public function getUploadFileId()
    {
        return $this->UploadFileId;
    }



    /**
     * @param string $UploadFileMobilePath
     */
    public function setUploadFileMobilePath($UploadFileMobilePath)
    {
        $this->UploadFileMobilePath = $UploadFileMobilePath;

    }

    /**
     * @return string
     */
    public function getUploadFileMobilePath()
    {
        return $this->UploadFileMobilePath;
    }

    /**
     * @param string $UploadFilePath
     */
    public function setUploadFilePath($UploadFilePath)
    {
        $this->UploadFilePath = $UploadFilePath;
    }

    /**
     * @return string
     */
    public function getUploadFilePath()
    {
        return $this->UploadFilePath;
    }


    /**
     * 错误代码
     * @return int
     */
    public function getErrorMessage()
    {
        return $this->ErrorMessage;
    }

    /**
     * 输出结果字符串，一般是html的
     * @return string
     */
    public function getResultHtml()
    {
        return $this->ResultHtml;
    }


    /**
     * @param mixed $CreateDate
     */
    public function setCreateDate($CreateDate)
    {
        $this->CreateDate = $CreateDate;
    }

    /**
     * @return mixed
     */
    public function getCreateDate()
    {
        return $this->CreateDate;
    }

    /**
     * @param mixed $IsBatchUpload
     */
    public function setIsBatchUpload($IsBatchUpload)
    {
        $this->IsBatchUpload = $IsBatchUpload;
    }

    /**
     * @return mixed
     */
    public function getIsBatchUpload()
    {
        return $this->IsBatchUpload;
    }

    /**
     * @param mixed $ManageUserId
     */
    public function setManageUserId($ManageUserId)
    {
        $this->ManageUserId = $ManageUserId;
    }

    /**
     * @return mixed
     */
    public function getManageUserId()
    {
        return $this->ManageUserId;
    }

    /**
     * @param mixed $TableId
     */
    public function setTableId($TableId)
    {
        $this->TableId = $TableId;
    }

    /**
     * @return mixed
     */
    public function getTableId()
    {
        return $this->TableId;
    }

    /**
     * @param mixed $TableType
     */
    public function setTableType($TableType)
    {
        $this->TableType = $TableType;
    }

    /**
     * @return mixed
     */
    public function getTableType()
    {
        return $this->TableType;
    }

    /**
     * @param mixed $UploadFileCompressPath1
     */
    public function setUploadFileCompressPath1($UploadFileCompressPath1)
    {
        $this->UploadFileCompressPath1 = $UploadFileCompressPath1;
    }

    /**
     * @return mixed
     */
    public function getUploadFileCompressPath1()
    {
        return $this->UploadFileCompressPath1;
    }

    /**
     * @param mixed $UploadFileCompressPath2
     */
    public function setUploadFileCompressPath2($UploadFileCompressPath2)
    {
        $this->UploadFileCompressPath2 = $UploadFileCompressPath2;
    }

    /**
     * @return mixed
     */
    public function getUploadFileCompressPath2()
    {
        return $this->UploadFileCompressPath2;
    }

    /**
     * @param mixed $UploadFileCutPath1
     */
    public function setUploadFileCutPath1($UploadFileCutPath1)
    {
        $this->UploadFileCutPath1 = $UploadFileCutPath1;
    }

    /**
     * @return mixed
     */
    public function getUploadFileCutPath1()
    {
        return $this->UploadFileCutPath1;
    }


    /**
     * @param mixed $UploadFileExtentionName
     */
    public function setUploadFileExtentionName($UploadFileExtentionName)
    {
        $this->UploadFileExtentionName = $UploadFileExtentionName;
    }

    /**
     * @return mixed
     */
    public function getUploadFileExtentionName()
    {
        return $this->UploadFileExtentionName;
    }

    /**
     * @param mixed $UploadFileInfo
     */
    public function setUploadFileInfo($UploadFileInfo)
    {
        $this->UploadFileInfo = $UploadFileInfo;
    }

    /**
     * @return mixed
     */
    public function getUploadFileInfo()
    {
        return $this->UploadFileInfo;
    }

    /**
     * @param mixed $UploadFileOrgName
     */
    public function setUploadFileOrgName($UploadFileOrgName)
    {
        $this->UploadFileOrgName = $UploadFileOrgName;
    }

    /**
     * @return mixed
     */
    public function getUploadFileOrgName()
    {
        return $this->UploadFileOrgName;
    }

    /**
     * @param mixed $UploadFilePadPath
     */
    public function setUploadFilePadPath($UploadFilePadPath)
    {
        $this->UploadFilePadPath = $UploadFilePadPath;
    }

    /**
     * @return mixed
     */
    public function getUploadFilePadPath()
    {
        return $this->UploadFilePadPath;
    }

    /**
     * @param mixed $UploadFileSize
     */
    public function setUploadFileSize($UploadFileSize)
    {
        $this->UploadFileSize = $UploadFileSize;
    }

    /**
     * @return mixed
     */
    public function getUploadFileSize()
    {
        return $this->UploadFileSize;
    }

    /**
     * @param mixed $UploadFileThumbPath1
     */
    public function setUploadFileThumbPath1($UploadFileThumbPath1)
    {
        $this->UploadFileThumbPath1 = $UploadFileThumbPath1;
    }

    /**
     * @return mixed
     */
    public function getUploadFileThumbPath1()
    {
        return $this->UploadFileThumbPath1;
    }

    /**
     * @param mixed $UploadFileThumbPath2
     */
    public function setUploadFileThumbPath2($UploadFileThumbPath2)
    {
        $this->UploadFileThumbPath2 = $UploadFileThumbPath2;
    }

    /**
     * @return mixed
     */
    public function getUploadFileThumbPath2()
    {
        return $this->UploadFileThumbPath2;
    }

    /**
     * @param mixed $UploadFileThumbPath3
     */
    public function setUploadFileThumbPath3($UploadFileThumbPath3)
    {
        $this->UploadFileThumbPath3 = $UploadFileThumbPath3;
    }

    /**
     * @return mixed
     */
    public function getUploadFileThumbPath3()
    {
        return $this->UploadFileThumbPath3;
    }

    /**
     * @param mixed $UploadFileTitle
     */
    public function setUploadFileTitle($UploadFileTitle)
    {
        $this->UploadFileTitle = $UploadFileTitle;
    }

    /**
     * @return mixed
     */
    public function getUploadFileTitle()
    {
        return $this->UploadFileTitle;
    }

    /**
     * @param mixed $UploadFileType
     */
    public function setUploadFileType($UploadFileType)
    {
        $this->UploadFileType = $UploadFileType;
    }

    /**
     * @return mixed
     */
    public function getUploadFileType()
    {
        return $this->UploadFileType;
    }

    /**
     * @param mixed $UploadFileWatermarkPath1
     */
    public function setUploadFileWatermarkPath1($UploadFileWatermarkPath1)
    {
        $this->UploadFileWatermarkPath1 = $UploadFileWatermarkPath1;
    }

    /**
     * @return mixed
     */
    public function getUploadFileWatermarkPath1()
    {
        return $this->UploadFileWatermarkPath1;
    }

    /**
     * @param mixed $UploadFileWatermarkPath2
     */
    public function setUploadFileWatermarkPath2($UploadFileWatermarkPath2)
    {
        $this->UploadFileWatermarkPath2 = $UploadFileWatermarkPath2;
    }

    /**
     * @return mixed
     */
    public function getUploadFileWatermarkPath2()
    {
        return $this->UploadFileWatermarkPath2;
    }

    /**
     * @param mixed $UserId
     */
    public function setUserId($UserId)
    {
        $this->UserId = $UserId;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->UserId;
    }

    /**
     * 将对象转换成JSON结果
     * @return string JSON结果
     */
    public function FormatToJson(){

        $uploadFilePath = $this->UploadFilePath;
        $uploadFilePath = str_ireplace('"',"",$uploadFilePath);

        $returnJson = '{';
        $returnJson .= '"error":"' . Format::FormatJson($this->ErrorMessage) . '",';
        $returnJson .= '"upload_file_id":"' . Format::FormatJson($this->UploadFileId) . '",';
        $returnJson .= '"upload_file_path":"' .Format::FormatJson($uploadFilePath). '"';
        $returnJson .= '"upload_file_watermark_path1":"' .Format::FormatJson($this->UploadFileWatermarkPath1). '"';
        $returnJson .= '}';

        return $returnJson;
    }

    /**
     * 返回对象的Json格式字符串
     * @return string 对象的Json格式字符串
     */
    public function GetJson(){
        $returnJson = '{';
        if($this->UploadFileId>0){
            $returnJson .= '"upload_file_id":"' .
                Format::FormatJson($this->UploadFileId) . '"';

            if(!empty($this->UploadFilePath)){
                $returnJson .= ',"upload_file_path":"'.
                    Format::FormatJson($this->UploadFilePath) . '"';
            }
            if(!empty($this->UploadFileMobilePath)){
                $returnJson .= ',"upload_file_mobile_path":"'.
                    Format::FormatJson($this->UploadFileMobilePath) . '"';
            }
            if(!empty($this->UploadFilePadPath)){
                $returnJson .= ',"upload_file_pad_path":"'.
                    Format::FormatJson($this->UploadFilePadPath) . '"';
            }
            if(!empty($this->UploadFileThumbPath1)){
                $returnJson .= ',"upload_file_thumb_path1":"'.
                    Format::FormatJson($this->UploadFileThumbPath1) . '"';
            }
            if(!empty($this->UploadFileThumbPath2)){
                $returnJson .= ',"upload_file_thumb_path2":"'.
                    Format::FormatJson($this->UploadFileThumbPath2) . '"';
            }
            if(!empty($this->UploadFileThumbPath3)){
                $returnJson .= ',"upload_file_thumb_path3":"'.
                    Format::FormatJson($this->UploadFileThumbPath3) . '"';
            }
            if(!empty($this->UploadFileWatermarkPath1)){
                $returnJson .= ',"upload_file_watermark_path1":"'.
                    Format::FormatJson($this->UploadFileWatermarkPath1) . '"';
            }
            if(!empty($this->UploadFileWatermarkPath2)){
                $returnJson .= ',"upload_file_watermark_path2":"'.
                    Format::FormatJson($this->UploadFileWatermarkPath2) . '"';
            }
            if(!empty($this->UploadFileCompressPath1)){
                $returnJson .= ',"upload_file_compress_path1":"'.
                    Format::FormatJson($this->UploadFileCompressPath1) . '"';
            }
            if(!empty($this->UploadFileCompressPath2)){
                $returnJson .= ',"upload_file_compress_path2":"'.
                    Format::FormatJson($this->UploadFileCompressPath2) . '"';
            }
            if(!empty($this->UploadFileCutPath1)){
                $returnJson .= ',"upload_file_cut_path1":"'.
                    Format::FormatJson($this->UploadFileCutPath1) . '"';
            }

        }
        $returnJson .= '}';
        return $returnJson;
    }

} 