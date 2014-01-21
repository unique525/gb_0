<?php

/**
 * 后台管理 频道模板 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Channel
 * @author zhangchi
 */
class ChannelTemplateManageData extends BaseManageData
{

    /**
     * 新增频道模板
     * @param array $httpPostData $_POST数组
     * @return int 新增的频道模板id
     */
    public function Create($httpPostData)
    {
        $result = -1;
        $dataProperty = new DataProperty();
        if (!empty($httpPostData)) {
            $sql = parent::GetInsertSql($httpPostData, self::TableName_Channel, $dataProperty);
            $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        }
        return $result;
    }

    /**
     * 根据模板id取得模板内容
     * @param int $channelTemplateId 模板id
     * @return null|string 返回模板内容
     */
    public function GetChannelTemplateContent($channelTemplateId)
    {
        $result = null;
        if ($channelTemplateId > 0) {
            $sql = "SELECT ChannelTemplateContent FROM " . self::TableName_ChannelTemplate . " where " . self::TableId_ChannelTemplate . "=:" . self::TableId_ChannelTemplate . " AND State<100;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField(self::TableId_ChannelTemplate, $channelTemplateId);
            $result = $this->dbOperator->GetString($sql, $dataProperty);
        }
        return $result;
    }
} 