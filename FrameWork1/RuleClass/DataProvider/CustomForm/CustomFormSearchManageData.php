<?php

/**
 * 后台管理 活动表单查询 数据类
 *
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Customform
 * @author 525
 */

class CustomFormSearchManageData extends BaseManageData {

    /**
     * 按关键词、类别 取得节点下表单列表
     * @param int $channelId 节点id
     * @param string $searchKeyOfCustomFormSubject 表单类别查询关键词
     * @param int $includeSubChannel 是否查询该节点的子节点
     * @return array 表单数据集 
     */
    public function GetCustomFormList($channelId, $searchKeyOfCustomFormSubject, $includeSubChannel = 0) {
        $_dataProperty = new DataProperty();
        $searchSql = "";
        //是否调用下级频道内容处理
        if ($channelId > 0) {
            if (intval($includeSubChannel) === 1) {
                $channelData = new ChannelManageData();
                $channelType = 10;      //调查表单类
                $arrList = $channelData->GetChildChannelID($channelId, $channelType);
                $subChannels = "";
                if (count($arrList) > 0) {
                    for ($i = 0; $i < count($arrList); $i++) {
                        $isHasSubChannel = $channelData->HasSubChannel($arrList[$i]['ChannelId']);
                        if ($isHasSubChannel > 0) {
                            $arrListOfSubChannel = $channelData->GetChildChannelID($arrList[$i]['ChannelId'], $channelType);
                            for ($k = 0; $k < count($arrListOfSubChannel); $k++) {
                                $subChannels .= "," . $arrListOfSubChannel[$k]['ChannelId'];
                            }
                        }
                        $subChannels .= "," . $arrList[$i]['ChannelId'];
                    }
                }
                $channelId = $channelId . $subChannels;
            }
            $searchSql .= " AND ChannelId IN (" . $channelId . ")";
        }

        if (!empty($searchKeyOfCustomFormSubject) && $searchKeyOfCustomFormSubject != "undefined") {
            $searchSql .= " AND (CustomFormSubject LIKE :SearchKeyOfCustomFormSubject)";
            $_dataProperty->AddField("SearchKeyOfCustomFormSubject", "%" . $searchKeyOfCustomFormSubject . "%");
        }

        $sql = "SELECT CustomFormId FROM ".self::TableName_CustomForm." WHERE CustomFormId>0 " . $searchSql . ";";

        $result = $this->dbOperator->ReturnArray($sql, $_dataProperty);
        return $result;
    }

    /**
     * 取得CustomFormRecord列表
     * @param int $customFormId 表单id
     * @param int $pageBegin 起始页
     * @param int $pageSize 分页大小
     * @param int $allCount 总大小
     * @param string $searchKey 查询关键词
     * @param string $beginDate 开始日期
     * @param string $endDate 结束日期
     * @return array 表单记录数据集
     */
    public function GetCustomFormRecordList($customFormId, $pageBegin, $pageSize, &$allCount, $searchKey, $beginDate = "", $endDate = "") {
        $dataProperty = new DataProperty();
        $searchSql = "";

        if (!empty($customFormId) && $customFormId != "undefined") {
            $searchSql .= " AND CustomFormId in (" . $customFormId . ")";
        }

        if (strlen($beginDate) > 2 && $beginDate != "" && $beginDate != "0000-00-00") {
            $searchSql .= " AND CreateDate>='" . $beginDate . "'";
        }
        if (strlen($endDate) > 2 && $endDate != "" && $endDate != "0000-00-00") {
            $searchSql .= " AND CreateDate<'" . $endDate . "'";
        }

        $sql = "SELECT CustomFormRecordId,CreateDate,CustomFormId FROM " . self::TableName_CustomFormRecord . " WHERE State<100 AND CustomFormRecordId>0 " . $searchSql . " ORDER BY Sort DESC LIMIT " . $pageBegin . "," . $pageSize . ";";

        $result = $this->dbOperator->ReturnArray($sql, $dataProperty);
        //统计总数
        $sqlCount = "SELECT count(*) FROM " . self::TableName_CustomFormRecord . " WHERE CustomFormRecordId>0 " . $searchSql . " ;";
        $allCount = $this->dbOperator->ReturnInt($sqlCount, $dataProperty);
        return $result;
    }

}

?>
