<?php
/**
 * Created by PhpStorm.
 * User: zcoffice
 * Date: 14-8-14
 * Time: 下午5:36
 */

class UploadFile {

    private $UploadFileId = 0;
    private $UploadFilePath = "";
    private $UploadFileMobilePath = "";

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
} 