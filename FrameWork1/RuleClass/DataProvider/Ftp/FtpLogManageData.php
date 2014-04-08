<?php

/**
 * 后台管理 FTP 数据类
 * @category iCMS
 * @package iCMS_Rules_DataProvider_Ftp
 * @author zhangchi
 */
class FtpLogManageData extends BaseManageData
{
    /**
     * 新增FTP传输日志
     * @param int $ftpId FTP数据编号
     * @param string $destination 目标文件夹
     * @param string $source 来源文件夹
     * @param float $transferTime 传输耗时
     * @param int $transferResult 传输结果
     * @return int 新增结果
     */
    public function Insert($ftpId, $destination, $source, $transferTime, $transferResult)
    {
        $sql = "INSERT INTO " . self::TableName_FtpLog . " (FtpId,Destination,Source,TransferTime,TransferResult,CreateDate) VALUES (:FtpId, :Destination, :Source, :TransferTime, :TransferResult, now());";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("FtpId", $ftpId);
        $dataProperty->AddField("Destination", $destination);
        $dataProperty->AddField("Source", $source);
        $dataProperty->AddField("TransferTime", $transferTime);
        $dataProperty->AddField("TransferResult", $transferResult);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }
}

?>