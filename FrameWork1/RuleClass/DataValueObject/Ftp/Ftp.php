<?php

/**
 * FTP对象类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataValueObject_Ftp
 * @property int FtpId Ftp编号
 * @property string FtpHost Ftp主机地址
 * @property int FtpPort 端口号
 * @property string FtpUser 帐号
 * @property string FtpPass 密码
 * @property string RemotePath 远程路径
 * @property string PasvMode 模式 1被动（默认） 2主动
 * @property int Timeout 超时时间（秒）
 * @property int SiteId 站点id
 * @author zhangchi
 */
class Ftp
{
    private $FtpId;
    private $FtpHost;
    private $FtpPort;
    private $FtpUser;
    private $FtpPass;
    private $RemotePath;
    private $PasvMode;
    private $Timeout;
    private $SiteId;

    /**
     * @param mixed $FtpHost
     */
    public function setFtpHost($FtpHost)
    {
        $this->FtpHost = $FtpHost;
    }

    /**
     * @return mixed
     */
    public function getFtpHost()
    {
        return $this->FtpHost;
    }

    /**
     * @param mixed $FtpId
     */
    public function setFtpId($FtpId)
    {
        $this->FtpId = $FtpId;
    }

    /**
     * @return mixed
     */
    public function getFtpId()
    {
        return $this->FtpId;
    }

    /**
     * @param mixed $FtpPass
     */
    public function setFtpPass($FtpPass)
    {
        $this->FtpPass = $FtpPass;
    }

    /**
     * @return mixed
     */
    public function getFtpPass()
    {
        return $this->FtpPass;
    }

    /**
     * @param mixed $FtpPort
     */
    public function setFtpPort($FtpPort)
    {
        $this->FtpPort = $FtpPort;
    }

    /**
     * @return mixed
     */
    public function getFtpPort()
    {
        return $this->FtpPort;
    }

    /**
     * @param mixed $FtpUser
     */
    public function setFtpUser($FtpUser)
    {
        $this->FtpUser = $FtpUser;
    }

    /**
     * @return mixed
     */
    public function getFtpUser()
    {
        return $this->FtpUser;
    }

    /**
     * @param mixed $PasvMode
     */
    public function setPasvMode($PasvMode)
    {
        $this->PasvMode = $PasvMode;
    }

    /**
     * @return mixed
     */
    public function getPasvMode()
    {
        return $this->PasvMode;
    }

    /**
     * @param mixed $RemotePath
     */
    public function setRemotePath($RemotePath)
    {
        $this->RemotePath = $RemotePath;
    }

    /**
     * @return mixed
     */
    public function getRemotePath()
    {
        return $this->RemotePath;
    }

    /**
     * @param mixed $SiteId
     */
    public function setSiteId($SiteId)
    {
        $this->SiteId = $SiteId;
    }

    /**
     * @return mixed
     */
    public function getSiteId()
    {
        return $this->SiteId;
    }

    /**
     * @param mixed $Timeout
     */
    public function setTimeout($Timeout)
    {
        $this->Timeout = $Timeout;
    }

    /**
     * @return mixed
     */
    public function getTimeout()
    {
        return $this->Timeout;
    }

    /**
     * get 配置值
     * @param string $fieldName 配置名称
     * @return string 配置值
     */
    public function __get($fieldName){
        $funcName = "get$fieldName";
        if(function_exists($funcName)){
            return $this->$funcName();
        }else{
            return null;
        }
    }
    /**
     * 设置配置值
     * @param string $fieldName 配置名称
     * @param string $fieldValue 配置值
     */
    public function __set($fieldName,$fieldValue){
        $funcName = "set$fieldName";
        if(function_exists($funcName)){
            $this->$funcName($fieldValue);
        }
    }
} 