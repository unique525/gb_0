<?php

/**
 * 后台管理 发布日志 数据类
 * @category iCMS
 * @package iCMS_Rules_DataProvider_Publish
 * @author zhangchi
 */
class PublishLogManageData extends BaseManageData
{
    /**
     * 发布类型：未定义
     */
    const TRANSFER_TYPE_NO_DEFINE = 0;
    /**
     * 发布类型：FTP
     */
    const TRANSFER_TYPE_FTP = 1;
    /**
     * 发布类型：FILE
     */
    const TRANSFER_TYPE_FILE = 2;
    /**
     * 对应表类型：未定义
     */
    const TABLE_TYPE_NO_DEFINE = 0;
    /**
     * 对应表类型：频道
     */
    const TABLE_TYPE_CHANNEL = 1;
    /**
     * 对应表类型：资讯
     */
    const TABLE_TYPE_DOCUMENT_NEWS = 2;
    /**
     * 对应表类型：自定义页面
     */
    const TABLE_TYPE_SITE_CONTENT = 3;
    /**
     * 对应表类型 活动
     */
    const TABLE_TYPE_ACTIVITY = 4;
    /**
     * 对应表类型：资讯
     */
    const TABLE_TYPE_INFORMATION = 5;

    /**
     * 新增发布日志
     * @param int $transferType 发布类型
     * @param int $tableType 对应表类型
     * @param int $tableId 对应表id
     * @param string $destinationPath 目标文件夹
     * @param string $sourcePath 来源文件夹
     * @param int $transferTime 传输耗时
     * @param int $transferResult 传输结果
     * @return int 新增结果
     */
    public function Create($transferType, $tableType, $tableId, $destinationPath, $sourcePath, $transferTime, $transferResult)
    {
        $sql = "INSERT INTO " . self::TableName_PublishLog . "
                (TransferType,TableType,TableId,DestinationPath,SourcePath,TransferTime,TransferResult,CreateDate)
                VALUES
                (:TransferType, :TableType,:TableId ,:DestinationPath, :SourcePath, :TransferTime, :TransferResult, now());";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("TransferType", $transferType);
        $dataProperty->AddField("TableType", $tableType);
        $dataProperty->AddField("TableId", $tableId);
        $dataProperty->AddField("DestinationPath", $destinationPath);
        $dataProperty->AddField("SourcePath", $sourcePath);
        $dataProperty->AddField("TransferTime", $transferTime);
        $dataProperty->AddField("TransferResult", $transferResult);
        $result = $this->dbOperator->Execute($sql, $dataProperty);
        return $result;
    }
}

?>