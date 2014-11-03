<?php
/**
 * 后台管理 FTP日志 数据类
 * @category iCMS
 * @package iCMS_Rules_DataProvider_Ftp
 * @author zhangchi
 */
class FtpLogManageData extends BaseManageData {


    /**
     * 新增FTP日志
     * @param int $ftpId FTP id
     * @param string $destinationPath 目标路径
     * @param string $sourcePath 来源路径
     * @param string $transferResult 传输结果
     * @param string $timeSpan 花费时间
     * @return int 新增结果
     */
    public function Create($ftpId,$destinationPath,$sourcePath,$transferResult,$timeSpan){

        $result = -1;

        if($ftpId>0){

            $dataProperty = new DataProperty();
            $sql = "INSERT INTO
                        ".self::TableName_FtpLog ."
                        (
                        FtpId,
                        DestinationPath,
                        SourcePath,
                        TransferResult,
                        TimeSpan,
                        CreateDate
                        )

                    VALUES (
                        :FtpId,
                        :DestinationPath,
                        :SourcePath,
                        :TransferResult,
                        :TimeSpan,
                        now()
                    );";
            $dataProperty->AddField("FtpId", $ftpId);
            $dataProperty->AddField("DestinationPath", $destinationPath);
            $dataProperty->AddField("SourcePath", $sourcePath);
            $dataProperty->AddField("TransferResult", $transferResult);
            $dataProperty->AddField("TimeSpan", $timeSpan);
            $result = $this->dbOperator->Execute($sql, $dataProperty);


        }

        return $result;
    }

} 