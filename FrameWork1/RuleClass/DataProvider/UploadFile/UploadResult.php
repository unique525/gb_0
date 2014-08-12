<?php

/**
 * 后台管理 上传文件结果类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_UploadFile
 * @property string $UploadFilePath
 * @author zhangchi
 */
class UploadResult {

    private $ErrorMessage;

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
    public function getResultMessage()
    {
        return $this->ResultMessage;
    }

    /**
     * 上传文件id
     * @return int
     */
    public function getUploadFileId()
    {
        return $this->UploadFileId;
    }

    /**
     * 上传文件路径
     * @return string
     */
    public function getUploadFilePath()
    {
        return $this->UploadFilePath;
    }
    private $ResultMessage;
    private $UploadFileId;
    private $UploadFilePath;

    /**
     * 构造上传结果类
     * @param int $errorMessage 错误代码
     * @param string $resultMessage 输出结果字符串，一般是html的
     * @param int $uploadFileId 上传文件id
     * @param string $uploadFilePath 上传文件路径
     */
    public function __construct($errorMessage = 0,$resultMessage = "",$uploadFileId = -1,$uploadFilePath = "") {
        $this->ErrorMessage = $errorMessage;
        $this->ResultMessage = $resultMessage;
        $this->UploadFileId = $uploadFileId;
        $this->UploadFilePath = $uploadFilePath;
    }

    /**
     * 将对象转换成JSON结果
     * @return string JSON结果
     */
    public function FormatToJson(){


        $returnJson = "{";
        $returnJson .= "error: '" . $this->ErrorMessage . "',";
        $returnJson .= "result: '" . $this->ResultMessage . "',";
        $returnJson .= "upload_file_id: '" . $this->UploadFileId . "',";
        $returnJson .= "upload_file_url: '" . $this->UploadFilePath . "'";
        $returnJson .= "}";

        return $returnJson;
    }
} 