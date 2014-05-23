<?php

/**
 * 模板处理工具类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Tools
 * @author zhangchi
 */
class Template
{

    /**
     * 默认模板标签名称定义
     */
    const DEFAULT_TAG_NAME = "icms";
    /**
     * 模板标签类型：站点列表 type="site_list"
     */
    const TAG_TYPE_SITE_LIST = "site_list";
    /**
     * 模板标签类型：频道列表 type="channel_list"
     */
    const TAG_TYPE_CHANNEL_LIST = "channel_list";
    /**
     * 模板标签类型：资讯列表 type="document_news_list"
     */
    const TAG_TYPE_DOCUMENT_NEWS_LIST = "document_news_list";


    /**
     * 读取模板内容
     * @param string $templateFileUrl 模板文件路径
     * @param string $templateName 模板名称
     * @param string $templatePath 模板路径
     * @return string 模板内容
     */
    public static function Load($templateFileUrl, $templateName = "", $templatePath = "system_template")
    {
        $filePath = RELATIVE_PATH . '/' . self::GetTempLateUrl($templateName, $templatePath) . '/' . $templateFileUrl . '.php';
        if (!file_exists($filePath)) {
            die("can not found template file:" . $filePath);
        }
        $tempContent = file_get_contents($filePath);
        //去掉BOM
        if (preg_match('/^\xEF\xBB\xBF/', $tempContent)) {
            $tempContent = substr($tempContent, 3);
        }
        return $tempContent;
    }

    /**
     * 返回系统模板路径
     * @param string $templateName 模板名称
     * @param string $templatePath 模板路径
     * @return string 系统模板路径
     */
    private static function GetTempLateUrl($templateName, $templatePath = "system_template")
    {
        if (!empty($templateName)) {
            return $templatePath . '/' . $templateName;
        } else {
            return $templatePath . '/default';
        }
    }

    /**
     * 替换列表类的模板
     * @param string $tempContent 要替换的模板内容，指针型参数，直接输出结果
     * @param array $arrList 用来替换到模板中的集合内容
     * @param string $tagId 替换标签的id
     * @param string $tagName 替换标签名称
     */
    public static function ReplaceList(&$tempContent, $arrList, $tagId, $tagName = self::DEFAULT_TAG_NAME)
    {
        if (stripos($tempContent, $tagName) > 0) {
            if ($arrList != null && count($arrList) > 0) {

                $beginTagString = '<' . $tagName . ' id="' . $tagId . '"';
                $endTagString = '</' . $tagName . '>';
                $listTempContent = substr($tempContent, strpos($tempContent, $beginTagString));
                $listTempContent = substr($listTempContent, 0, strpos($listTempContent, $endTagString) + strlen($endTagString));

                if (strlen($listTempContent) > 0) {
                    $isDomOpen = TRUE;
                    if (!class_exists('DOMDocument')) { //服务器是否开启了DOM
                        $isDomOpen = FALSE;
                    }

                    if ($isDomOpen) {
                        $doc = new DOMDocument();
                        $doc->loadXML($listTempContent);
                        //////////////////////////////param list///////////////////////////
                        $type = self::GetParamStringValue($doc, $tagName, "type");
                        if ($type == '') {
                            $type = self::GetParamStringValue($doc, $tagName, "t");
                        }
                        //头段行数
                        $headerRowCount = self::GetParamIntValue($doc, $tagName, "header_row_count");
                        if ($headerRowCount <= 0) {
                            $headerRowCount = self::GetParamIntValue($doc, $tagName, "hrc");
                        }
                        //主段行数
                        $itemRowCount = self::GetParamIntValue($doc, $tagName, "item_row_count");
                        if ($itemRowCount <= 0) {
                            $itemRowCount = self::GetParamIntValue($doc, $tagName, "irc");
                        }
                        //尾段行数
                        $footerRowCount = self::GetParamIntValue($doc, $tagName, "footer_row_count");
                        if ($footerRowCount <= 0) {
                            $footerRowCount = self::GetParamIntValue($doc, $tagName, "frc");
                        }

                        //HEADER标题最大字符数
                        $headerRowTitleCount = self::GetParamIntValue($doc, $tagName, "header_row_title_count");
                        if ($headerRowTitleCount <= 0) {
                            $headerRowTitleCount = self::GetParamIntValue($doc, $tagName, "header_title");
                        }

                        //Footer标题最大字符数
                        $footerRowTitleCount = self::GetParamIntValue($doc, $tagName, "footer_row_title_count");
                        if ($footerRowTitleCount <= 0) {
                            $footerRowTitleCount = self::GetParamIntValue($doc, $tagName, "footer_title");
                        }

                        //标题最大字符数
                        $itemRowTitleCount = self::GetParamIntValue($doc, $tagName, "item_row_title_count");
                        if ($itemRowTitleCount <= 0) {
                            $itemRowTitleCount = self::GetParamIntValue($doc, $tagName, "title");
                        }

                        $itemRowIntroCount = self::GetParamIntValue($doc, $tagName, "item_row_intro_count");
                        if ($itemRowIntroCount <= 0) {
                            $itemRowIntroCount = self::GetParamIntValue($doc, $tagName, "intro");
                        }

                        $headerTempContent = self::GetNodeValue($doc, "header", $tagName);
                        if (strlen($headerTempContent) > 0 && $headerRowCount <= 0) {
                            $headerRowCount = 1; //找到header段时，如果没手动设置header段行数，则默认设置行数为1
                        }
                        $footerTempContent = self::GetNodeValue($doc, "footer", $tagName);
                        if (strlen($footerTempContent) > 0 && $footerRowCount <= 0) {
                            $footerRowCount = 1; //找到footer段时，如果没手动设置footer段行数，则默认设置行数为1
                        }
                        //读取头段到主段的分割线
                        $headerSplitterTempContent = self::GetNodeValue($doc, "header_splitter", $tagName);
                        //读取主段
                        $itemTempContent = self::GetNodeValue($doc, "item", $tagName);

                        $alterItemTempContent = self::GetNodeValue($doc, "alter_item", $tagName);

                        //读取主段的分割线
                        $itemSplitterTempContent = self::GetNodeValue($doc, "item_splitter", $tagName);
                        //读取分割线出现的间隔数
                        $itemSplitterCount = self::GetParamIntValue($doc, $tagName, "item_splitter_count");
                        //读取主段到尾段的分割线
                        $footerSplitterTempContent = self::GetNodeValue($doc, "footer_splitter", $tagName);
                    } else {
                        //使用SAX
                        $parser = xml_parser_create();
                        $arrayXml = array();
                        xml_parse_into_struct($parser, $listTempContent, $arrayXml);
                        xml_parser_free($parser);

                        $type = $arrayXml[0]['attributes']['TYPE'];
                        if ($type == '') {
                            $type = $arrayXml[0]['attributes']['T'];
                        }
                        //头段行数
                        $headerRowCount = intval($arrayXml[0]['attributes']['HEADER_ROW_COUNT']);
                        if ($headerRowCount <= 0) {
                            $headerRowCount = intval($arrayXml[0]['attributes']['HRC']);
                        }
                        //主段行数
                        $itemRowCount = intval($arrayXml[0]['attributes']['ITEM_ROW_COUNT']);
                        if ($itemRowCount <= 0) {
                            $itemRowCount = intval($arrayXml[0]['attributes']['IRC']);
                        }
                        //尾段行数
                        $footerRowCount = intval($arrayXml[0]['attributes']['FOOTER_ROW_COUNT']);
                        if ($footerRowCount <= 0) {
                            $footerRowCount = intval($arrayXml[0]['attributes']['FRC']);
                        }

                        //HEADER标题最大字符数
                        $headerRowTitleCount = intval($arrayXml[0]['attributes']['HEADER_ROW_TITLE_COUNT']);
                        if ($headerRowTitleCount <= 0) {
                            $headerRowTitleCount = intval($arrayXml[0]['attributes']['HEADER_TITLE']);
                        }

                        //Footer标题最大字符数
                        $footerRowTitleCount = intval($arrayXml[0]['attributes']['FOOTER_ROW_TITLE_COUNT']);
                        if ($footerRowTitleCount <= 0) {
                            $footerRowTitleCount = intval($arrayXml[0]['attributes']['FOOTER_TITLE']);
                        }

                        //标题最大字符数
                        $itemRowTitleCount = intval($arrayXml[0]['attributes']['ITEM_ROW_TITLE_COUNT']);
                        if ($itemRowTitleCount <= 0) {
                            $itemRowTitleCount = intval($arrayXml[0]['attributes']['TITLE']);
                        }

                        $itemRowIntroCount = intval($arrayXml[0]['attributes']['ITEM_ROW_INTRO_COUNT']);
                        if ($itemRowIntroCount <= 0) {
                            $itemRowIntroCount = intval($arrayXml[0]['attributes']['INTRO']);
                        }

                        $headerTempContent = self::GetNodeValueForSax($arrayXml, "HEADER");
                        if (strlen($headerTempContent) > 0 && $headerRowCount <= 0) {
                            $headerRowCount = 1;
                        }
                        $footerTempContent = self::GetNodeValueForSax($arrayXml, "FOOTER");
                        if (strlen($footerTempContent) > 0 && $footerRowCount <= 0) {
                            $footerRowCount = 1;
                        }
                        //读取头段到主段的分割线
                        $headerSplitterTempContent = self::GetNodeValueForSax($arrayXml, "HEADER_SPLITTER");
                        //读取主段
                        $itemTempContent = self::GetNodeValueForSax($arrayXml, "ITEM");

                        $alterItemTempContent = self::GetNodeValueForSax($arrayXml, "ALTER_ITEM");

                        //读取主段的分割线
                        $itemSplitterTempContent = self::GetNodeValueForSax($arrayXml, "ITEM_SPLITTER");
                        //读取分割线出现的间隔数
                        $itemSplitterCount = intval($arrayXml[0]['attributes']['ITEM_SPLITTER_COUNT']);
                        //读取主段到尾段的分割线
                        $footerSplitterTempContent = self::GetNodeValueForSax($arrayXml, "FOOTER_SPLITTER");
                    }
                    ///////////////////////////////////////////////////////////////////
                    $sb = "";

                    //处理头段
                    for ($i = 0; $i < $headerRowCount; $i++) {
                        $columns = $arrList[$i];
                        $list = $headerTempContent;
                        $itemType = "header";
                        $list = self::ReplaceListItem($i, $type, $itemRowTitleCount, $itemRowIntroCount, $headerRowTitleCount, $footerRowTitleCount, $columns, $list, $itemType);
                        $list = str_ireplace("{c_all_count}", count($arrList), $list);
                        $sb = $sb . $list;
                    }

                    //附加分割线
                    $sb = $sb . $headerSplitterTempContent;

                    if ($itemSplitterCount <= 0) {
                        $itemSplitterCount = 0;
                    }

                    if ($itemRowCount <= 0) { //主段显示行数，如果未设置，则默认
                        $itemRowCount = count($arrList) - $footerRowCount;
                    }

                    for ($i = 0 + $headerRowCount; $i < $itemRowCount; $i++) {

                        //如果有交替行
                        if (strlen($alterItemTempContent) > 0) {
                            if ($i % 2 === 1) {
                                $list = $alterItemTempContent;
                            } else {
                                $list = $itemTempContent;
                            }
                        } else {
                            $list = $itemTempContent;
                        }

                        $columns = $arrList[$i];
                        $itemType = "item";
                        $list = self::ReplaceListItem($i, $type, $itemRowTitleCount, $itemRowIntroCount, $headerRowTitleCount, $footerRowTitleCount, $columns, $list, $itemType);
                        $list = str_ireplace("{c_all_count}", count($arrList), $list);
                        $sb = $sb . $list;
                        //每隔一定主段条数附加分割线，最底部分割线不附加
                        if ($itemSplitterCount > 0) {
                            if ($i < count($arrList) - $footerRowCount - 1 && ($i - $headerRowCount + 1) % ($itemSplitterCount) == 0) {
                                $sb = $sb . $itemSplitterTempContent;
                            }
                        }
                    }


                    //附加分割线
                    $sb = $sb . $footerSplitterTempContent;

                    //处理尾段
                    if ($footerRowCount > 0) {
                        for ($i = count($arrList) - $footerRowCount; $i < count($arrList); $i++) {
                            $columns = $arrList[$i];
                            $list = $footerTempContent;
                            $itemType = "footer";
                            $list = self::ReplaceListItem($i, $type, $itemRowTitleCount, $itemRowIntroCount, $headerRowTitleCount, $footerRowTitleCount, $columns, $list, $itemType);
                            $list = str_ireplace("{c_all_count}", count($arrList), $list);
                            $sb = $sb . $list;
                        }
                    }

                    $result = $sb;
                    $tempContent = str_replace($listTempContent, $result, $tempContent);
                }
            } else { //替换模板内容为空
                self::ReplaceCustomTag($tempContent, $tagId, "", $tagName);
            }
        }
    }

    /**
     * 子方法，替换数据集的每一行内容
     * @param int $i 行号
     * @param string $tagType 标签的type属性
     * @param int $itemRowTitleCount 主列表项目标题最大字符数
     * @param int $itemRowIntroCount 主列表项目简介最大字符数
     * @param int $headerRowTitleCount 顶部列表项目标题最大字符数
     * @param int $footerRowTitleCount 底部列表项目标题最大字符数
     * @param array $columns 字段数组
     * @param string $listTemplate 要替换的列表模板内容
     * @param string $itemType 标签的类型 header item footer
     * @return mixed
     */
    private static function ReplaceListItem($i, $tagType, $itemRowTitleCount, $itemRowIntroCount, $headerRowTitleCount, $footerRowTitleCount, $columns, $listTemplate, $itemType)
    {
        $listTemplate = str_ireplace("{c_no}", $i + 1, $listTemplate); //加入输出序号
        if (isset($columns["DirectUrl"]) && $columns["DirectUrl"] != '') { //链接文档
            $listTemplate = str_ireplace("{c_url}", $columns["DirectUrl"], $listTemplate); //直接输出url
        } else {
            $listTemplate = str_ireplace("{c_url}", "/h/{f_channel_id}/{f_year}{f_month}{f_day}/{f_document_news_id}.html", $listTemplate);
        }
        foreach ($columns as $columnName => $columnValue) {
            //公用替换
            self::FormatColumnValue($columnName, $columnValue, $listTemplate, $itemRowTitleCount, $itemRowIntroCount, $headerRowTitleCount, $footerRowTitleCount, $itemType);
            if (strtolower($tagType) === 'document_news_list') {
                self::FormatDocumentNewsColumnValue($columnName, $columnValue, $listTemplate, $itemRowTitleCount, $itemRowIntroCount, $headerRowTitleCount, $footerRowTitleCount, $itemType);
            } else if (strtolower($tagType) === 'product_list') {
                self::FormatProductColumnValue($columnName, $columnValue, $listTemplate, $itemRowTitleCount, $itemRowIntroCount, $headerRowTitleCount, $footerRowTitleCount, $itemType);
            } else if (strtolower($tagType) === 'activity_list') {
                self::FormatActivityColumnValue($columnName, $columnValue, $listTemplate, $itemRowTitleCount, $headerRowTitleCount, $footerRowTitleCount, $itemType);
            }
        }
        return $listTemplate;
    }

    /**
     * 通用的格式化行内容
     * @param string $columnName 字段名称
     * @param string $columnValue 字段值
     * @param string $tempContent 要替换的模板内容，指针型参数，直接输出结果
     * @param int $itemRowTitleCount 主列表项目标题最大字符数
     * @param int $itemRowIntroCount 主列表项目简介最大字符数
     * @param int $headerRowTitleCount 顶部列表项目标题最大字符数
     * @param int $footerRowTitleCount 底部列表项目标题最大字符数
     * @param string $itemType 标签的类型 header item footer
     */
    private static function FormatColumnValue($columnName, $columnValue, &$tempContent, $itemRowTitleCount, $itemRowIntroCount, $headerRowTitleCount, $footerRowTitleCount, $itemType)
    {
        if (strtolower($columnName) == "state") {
            $tempContent = str_ireplace("{f_" . $columnName . "_value}", $columnValue, $tempContent);
        }
        $pos = stripos(strtolower($columnName), "subject");
        if ($pos !== false) {
            $columnValue = Format::FormatHtmlTag($columnValue);
            $tempContent = str_ireplace("{c_title_all}", $columnValue, $tempContent);
            if (intval($itemRowTitleCount) > 0) {
                //截断字符
                $columnValue = Format::ToShort($columnValue, $itemRowTitleCount);
            }
        }
        //格式化标题
        $pos = stripos(strtolower($columnName), "title");
        $pos2 = stripos(strtolower($columnName), "title_pic");
        if ($pos !== false && $pos2 === false) {
            $columnValue = Format::FormatHtmlTag($columnValue);
            if ($itemType == "header") {
                if (intval($headerRowTitleCount) > 0) {
                    //截断字符
                    $columnValue = Format::ToShort($columnValue, $headerRowTitleCount);
                }
            } else if ($itemType == "footer") {
                if (intval($footerRowTitleCount) > 0) {
                    //截断字符
                    $columnValue = Format::ToShort($columnValue, $footerRowTitleCount);
                }
            } else {
                if (intval($itemRowTitleCount) > 0) {
                    //截断字符
                    $columnValue = Format::ToShort($columnValue, $itemRowTitleCount);
                }
            }
        }
        //格式化简介
        $pos = stripos(strtolower($columnName), "intro");
        if ($pos !== false) {
            if (intval($itemRowIntroCount) > 0) {
                //截断字符
                $columnValue = Format::ToShort($columnValue, $itemRowIntroCount);
            }
        }
        $pos = stripos(strtolower($columnName), "publish_date");
        if ($pos !== false) {
            $date1 = explode(' ', $columnValue);
            $date2 = explode('-', $date1[0]);
            $year = $date2[0];
            $month = $date2[1];
            $day = $date2[2];

            $tempContent = str_ireplace("{f_year}", $year, $tempContent);
            $tempContent = str_ireplace("{f_month}", $month, $tempContent);
            $tempContent = str_ireplace("{f_day}", $day, $tempContent);
        }

        $pos = stripos(strtolower($columnName), "create_date");
        if ($pos !== false) {
            $date1 = explode(' ', $columnValue);
            $date2 = explode('-', $date1[0]);
            $year = $date2[0];
            $month = $date2[1];
            $day = $date2[2];

            $tempContent = str_ireplace("{f_c_year}", $year, $tempContent);
            $tempContent = str_ireplace("{f_c_month}", $month, $tempContent);
            $tempContent = str_ireplace("{f_c_day}", $day, $tempContent);
        }
        $tempContent = str_ireplace("{f_" . $columnName . "}", $columnValue, $tempContent);
    }

    /**
     * 为资讯格式化行内容
     * @param string $columnName 字段名称
     * @param string $columnValue 字段值
     * @param string $tempContent 要替换的模板内容，指针型参数，直接输出结果
     * @param int $itemRowTitleCount 主列表项目标题最大字符数
     * @param int $itemRowIntroCount 主列表项目简介最大字符数
     * @param int $headerRowTitleCount 顶部列表项目标题最大字符数
     * @param int $footerRowTitleCount 底部列表项目标题最大字符数
     * @param string $itemType 标签的类型 header item footer
     */
    private static function FormatDocumentNewsColumnValue($columnName, $columnValue, &$tempContent, $itemRowTitleCount, $itemRowIntroCount, $headerRowTitleCount, $footerRowTitleCount, $itemType = "item")
    {
        $pos = stripos(strtolower($columnName), strtolower("ShowDate"));
        if ($pos !== false) {
            $date1 = explode(' ', $columnValue);
            $date2 = explode('-', $date1[0]);
            $year = $date2[0];
            $month = $date2[1];
            $day = $date2[2];
            $tempContent = str_ireplace("{f_show_year}", $year, $tempContent);
            $tempContent = str_ireplace("{f_show_month}", $month, $tempContent);
            $tempContent = str_ireplace("{f_show_day}", $day, $tempContent);
        }
        $pos = stripos(strtolower($columnName), strtolower("DocumentNewsContent"));
        if ($pos !== false) {
            $columnValue = str_ireplace("../upload/document_news", "/upload/document_news", $columnValue);
            if (intval($itemRowIntroCount) > 0) {
                //截断字符
                $columnValue = Format::ToShort(strip_tags($columnValue), $itemRowIntroCount);
            }
            $tempContent = str_ireplace("{f_document_news_content_no_html}", strip_tags($columnValue), $tempContent);
        }
        $columnValue = str_ireplace("'", "&#039;", $columnValue);
        $tempContent = str_ireplace("{f_" . $columnName . "}", $columnValue, $tempContent);
    }

    /**
     * 为活动格式化行内容
     * @param string $columnName 字段名称
     * @param string $columnValue 字段值
     * @param string $tempContent 要替换的模板内容，指针型参数，直接输出结果
     * @param int $itemRowTitleCount 主列表项目标题最大字符数
     * @param int $itemRowIntroCount 主列表项目简介最大字符数
     * @param int $headerRowTitleCount 顶部列表项目标题最大字符数
     * @param int $footerRowTitleCount 底部列表项目标题最大字符数
     * @param string $itemType 标签的类型 header item footer
     */
    private static function FormatActivityColumnValue($columnName, $columnValue, &$tempContent, $itemRowTitleCount, $itemRowIntroCount, $headerRowTitleCount, $footerRowTitleCount, $itemType = "item")
    {
        $pos = stripos(strtolower($columnName), "activity_content");
        if ($pos !== false) {
            if (intval($itemRowIntroCount) > 0) {
                //截断字符
                $columnValue = Format::ToShort($columnValue, $itemRowIntroCount);
            } else {
                $columnValue = str_replace(array('&nbsp;', "\r", "\n", '\'', '"'), '', Format::ToShort(strip_tags($columnValue), $itemRowIntroCount));
            }
        }

        $pos = stripos(strtolower($columnName), "apply_begin_date");
        if ($pos !== false) {

            $date1 = explode(' ', $columnValue);
            $date2 = explode('-', $date1[0]);
            $year = $date2[0];
            $month = $date2[1];
            $day = $date2[2];

            $tempContent = str_ireplace("{f_apply_begin_year}", $year, $tempContent);
            $tempContent = str_ireplace("{f_apply_begin_month}", $month, $tempContent);
            $tempContent = str_ireplace("{f_apply_begin_day}", $day, $tempContent);
        }

        $pos = stripos(strtolower($columnName), "apply_end_date");
        if ($pos !== false) {

            $date1 = explode(' ', $columnValue);
            $date2 = explode('-', $date1[0]);
            $year = $date2[0];
            $month = $date2[1];
            $day = $date2[2];

            $tempContent = str_ireplace("{f_apply_end_year}", $year, $tempContent);
            $tempContent = str_ireplace("{f_apply_end_month}", $month, $tempContent);
            $tempContent = str_ireplace("{f_apply_end_day}", $day, $tempContent);
        }

        $pos = stripos(strtolower($columnName), "begin_date");
        if ($pos !== false) {

            $date1 = explode(' ', $columnValue);
            $date2 = explode('-', $date1[0]);
            $year = $date2[0];
            $month = $date2[1];
            $day = $date2[2];

            $tempContent = str_ireplace("{f_begin_year}", $year, $tempContent);
            $tempContent = str_ireplace("{f_begin_month}", $month, $tempContent);
            $tempContent = str_ireplace("{f_begin_day}", $day, $tempContent);
        }

        $pos = stripos(strtolower($columnName), "end_date");
        if ($pos !== false) {

            $date1 = explode(' ', $columnValue);
            $date2 = explode('-', $date1[0]);
            $year = $date2[0];
            $month = $date2[1];
            $day = $date2[2];

            $tempContent = str_ireplace("{f_end_year}", $year, $tempContent);
            $tempContent = str_ireplace("{f_end_month}", $month, $tempContent);
            $tempContent = str_ireplace("{f_end_day}", $day, $tempContent);
        }
        $tempContent = str_ireplace("{f_" . $columnName . "}", $columnValue, $tempContent);
    }

    /**
     * 为product 格式化字段
     * @param string $columnName 字段名称
     * @param string $columnValue 字段值
     * @param string $tempContent 要替换的模板内容，指针型参数，直接输出结果
     * @param int $itemRowTitleCount 主列表项目标题最大字符数
     * @param int $itemRowIntroCount 主列表项目简介最大字符数
     * @param int $headerRowTitleCount 顶部列表项目标题最大字符数
     * @param int $footerRowTitleCount 底部列表项目标题最大字符数
     * @param string $itemType 标签的类型 header item footer
     */
    private static function FormatProductColumnValue($columnName, $columnValue, &$tempContent, $itemRowTitleCount, $itemRowIntroCount, $headerRowTitleCount, $footerRowTitleCount, $itemType = "item")
    {
        $pos = stripos(strtolower($columnName), "sale_price");
        if ($pos !== false) {
            if (is_numeric($columnValue) && $columnValue > 0)
                $tempContent = str_ireplace("{f_sale_price_show}", number_format($columnValue / 10000, 2, '.', ''), $tempContent);
            else
                $tempContent = str_ireplace("{f_sale_price_show}", "", $tempContent);
        }

        if (strtolower($columnName) === "product_content") {
            $columnValue = str_ireplace("../upload/product", "/upload/product", $columnValue);
        }

        $columnValue = str_ireplace("'", "&#039;", $columnValue);
        $tempContent = str_ireplace("{f_" . $columnName . "}", $columnValue, $tempContent);
    }

    /**
     * 替换详细信息页面
     * @param string $tempContent
     * @param array $arrInfo 要替换的数组数据
     * @param bool $isList 默认为false，使用一维数组存储数据
     * @param bool $isManage 默认为false，使用二维数组存储数据
     */
    public static function ReplaceOne(&$tempContent, $arrInfo, $isList = false, $isManage = false)
    {
        if (count($arrInfo) > 0) {
            if ($isList > 0) { //使用一维数组存储数据
                self::_ReplaceOne($tempContent, $arrInfo, $isManage);
            } else { //使用二维数组存储数据
                for ($i = 0; $i < count($arrInfo); $i++) {
                    $columns = $arrInfo[$i];
                    self::_ReplaceOne($tempContent, $columns, $isManage);
                }
            }
        }
    }


    /**
     * 处理详细信息子方法
     * @param string $tempContent
     * @param array $arrOne
     * @param bool $isForTemplate 是否后台模板系统中使用，默认false，非后台模板系统中使用
     */
    private static function _ReplaceOne(&$tempContent, $arrOne, $isForTemplate = false)
    {
        if (!empty($arrOne)) {
            foreach ($arrOne as $columnName => $columnValue) {

                $pos = strpos(strtolower($columnName), "content");
                if ($pos !== false) {
                    $columnValue = str_ireplace("<textarea", "<text_area", $columnValue);
                    $columnValue = str_ireplace("</textarea>", "</text_area>", $columnValue);
                }

                if (strtolower($columnName) === strtolower("DocumentNewsContent")) {
                    $columnValue = str_ireplace("../upload/document_news", "/upload/document_news", $columnValue);
                }

                if (strtolower($columnName) === strtolower("DocumentNewsTitle")) {
                    $columnValue = str_ireplace('"', "&quot;", $columnValue);
                }

                //分拆处理发布时间字段
                $pos = stripos(strtolower($columnName), strtolower("PublishDate"));
                if ($pos !== false) {
                    $date1 = explode(' ', $columnValue);
                    $date2 = explode('-', $date1[0]);
                    $year = $date2[0];
                    $month = $date2[1];
                    $day = $date2[2];

                    $tempContent = str_ireplace("{publish_year}", $year, $tempContent);
                    $tempContent = str_ireplace("{publish_month}", $month, $tempContent);
                    $tempContent = str_ireplace("{publish_day}", $day, $tempContent);
                }

                //分拆处理显示时间字段
                $pos = stripos(strtolower($columnName), strtolower("ShowDate"));
                if ($pos !== false) {
                    $date1 = explode(' ', $columnValue);
                    $date2 = explode('-', $date1[0]);
                    $year = $date2[0];
                    $month = $date2[1];
                    $day = $date2[2];

                    $tempContent = str_ireplace("{show_year}", $year, $tempContent);
                    $tempContent = str_ireplace("{show_month}", $month, $tempContent);
                    $tempContent = str_ireplace("{show_day}", $day, $tempContent);
                }


                //处理常规的值
                if ($isForTemplate) { //是否是后台模板系统使用
                    $preManage = "b_";
                } else {
                    $preManage = "";
                }


                if (strtolower($columnName) === strtolower("OpenComment")) {
                    $tempContent = str_ireplace("{" . $preManage . "s_" . $columnName . "_" . $columnValue . "}", "selected=\"selected\"", $tempContent);
                    if (intval($columnValue) === 0) { //关闭评论
                        $columnValue = "display:none;";
                    } else {
                        $columnValue = "";
                    }
                    $tempContent = str_ireplace("{" . $columnName . "}", $columnValue, $tempContent);
                }

                $tempContent = str_ireplace("{" . $preManage . $columnName . "}", $columnValue, $tempContent);

                //处理常规去掉HTML代码的值
                $tempContent = str_ireplace("{" . $preManage . $columnName . "_no_html}", strip_tags($columnValue), $tempContent);

                //处理下拉菜单的默认值

                $selectedOption = '<script type="text/javascript">
                    $("#f_' . $columnName . '").find("option[value=\'' . $columnValue . '\']").attr("selected",true);
                </script>';

                $tempContent = str_ireplace("{" . $preManage . "s_" . $columnName . "}", $selectedOption, $tempContent);

                $checkedOption = '<script type="text/javascript">
                    $("#f_' . $columnName . '").find("option[value=\'' . $columnValue . '\']").attr("checked",true);
                </script>';

                $tempContent = str_ireplace("{" . $preManage . "r_" . $columnName . "}", $checkedOption, $tempContent);

                if (intval($columnValue) === 1) {
                    $tempContent = str_ireplace("{c_" . $columnName . "}", 'checked="checked"', $tempContent);
                }
            }
        }
    }


    /**
     * 取得XML标签参数的值
     * @param string $documentContent XML标签内容
     * @param string $paramName 参数名称
     * @param string $tagName 标签名称
     * @return string 返回参数的值
     */
    public static function GetParamValue($documentContent, $paramName, $tagName = self::DEFAULT_TAG_NAME)
    {
        if (class_exists('DOMDocument')) { //服务器是否开启了DOM
            $doc = new DOMDocument();
            $doc->loadXML($documentContent);
            return self::GetParamStringValue($doc, $tagName, $paramName);
        } else {
            //使用SAX
            $parser = xml_parser_create();
            $arrayXml = array();
            xml_parse_into_struct($parser, $documentContent, $arrayXml);
            xml_parser_free($parser);
            $paramName = strtoupper($paramName);
            return $arrayXml[0]['attributes'][$paramName];
        }
    }

    /**
     * 取得Document对象的节点内容
     * @param DOMDocument $document document对象
     * @param string $paramName 参数名称
     * @param string $tagName 标签名称
     * @return string 返回Document对象的节点内容
     */
    private static function GetNodeValue($document, $paramName, $tagName = self::DEFAULT_TAG_NAME)
    {
        $result = "";
        $node = $document->getElementsByTagName($tagName)->item(0);
        if ($node->hasChildNodes()) {
            foreach ($node->childNodes as $childNode) {
                if (strtolower($childNode->nodeName) == strtolower($paramName)) {
                    $result = $childNode->nodeValue;
                }
            }
        }
        return $result;
    }

    /**
     * 取得XML节点的内容 SAX用
     * @param array $arrayXml XML对象数组
     * @param string $tagName 标签名称
     * @return string 节点的内容
     */
    private static function GetNodeValueForSax($arrayXml, $tagName = self::DEFAULT_TAG_NAME)
    {
        $result = "";
        for ($i = 0; $i < count($arrayXml); $i++) {
            if ($arrayXml[$i]['tag'] == $tagName && $arrayXml[$i]['type'] == 'complete') {
                $result = $arrayXml[$i]['value'];
                $i = count($arrayXml);
            }
        }
        return $result;
    }

    /**
     * 取得String类型的参数的值
     * @param DOMDocument $domDocument DOM对象
     * @param string $tagName 标签名称
     * @param string $attrName 属性名称
     * @param string $defaultValue 默认值
     * @return string 参数的值
     */
    private static function GetParamStringValue($domDocument, $tagName, $attrName, $defaultValue = "")
    {
        $result = $defaultValue;
        $node = $domDocument->getElementsByTagName($tagName)->item(0);
        if ($node->hasAttributes()) {
            foreach ($node->attributes as $attr) {
                if (strtolower($attr->name) == strtolower($attrName)) {
                    $result = $attr->value;
                }
            }
        }
        return $result;
    }

    /**
     * 取得Int类型的参数的值
     * @param DOMDocument $domDocument DOM对象
     * @param string $tagName 标签名称
     * @param string $attrName 属性名称
     * @param int $defaultValue 默认值
     * @return int 参数的值
     */
    private static function GetParamIntValue($domDocument, $tagName, $attrName, $defaultValue = 0)
    {
        $result = intval(self::GetParamStringValue($domDocument, $tagName, $attrName, strval($defaultValue)));
        return $result;
    }

    /**
     * 取得页面中所有的标记段，并存入数组
     * @param string $tempContent 要替换的模板内容
     * @param string $tagName 标签名称
     * @return array 返回数组
     */
    public static function GetAllCustomTag($tempContent, $tagName = self::DEFAULT_TAG_NAME)
    {
        $preg = "/\<$tagName(.*)\<\/$tagName>/imsU";
        preg_match_all($preg, $tempContent, $result, PREG_PATTERN_ORDER);
        return $result;
    }

    /**
     * 把对应id的自定义标签替换成指定内容，可以识别标记的type属性
     * @param string $tempContent 要替换的模板内容
     * @param string $tagId 标签的id
     * @param string $replaceContent 要替换的内容
     * @param string $tagName 标签名称
     * @param string $tagType 标签的type
     * @return string 替换后的内容
     */
    public static function ReplaceCustomTag($tempContent, $tagId, $replaceContent, $tagName = self::DEFAULT_TAG_NAME, $tagType = null)
    {
        /**
         * if($tagType != null && strlen($tagType)>0){
         * $beginString = '<' . $tagName . ' id="' . $tagId . '" type="' . $tagType . '"';
         * }else{
         * $beginString = '<' . $tagName . ' id="' . $tagId . '"';
         * }
         * $endString = '</' . $tagName . '>';
         * $temp1 = substr($tempContent, 0, stripos($tempContent, $beginString));
         * $x = stripos($tempContent, $endString, stripos($tempContent, $beginString));
         * $temp2 = substr($tempContent, $x + strlen($tagName) + 3);
         * $tempContent = $temp1 . $replaceContent . $temp2;
         * return $tempContent;
         */
        if (!empty($tagId) && !empty($tagType)) {
            $patterns = "/\<$tagName id=\"$tagId\" type=\"$tagType\"(.*)\<\/$tagName>/imsU";
            $tempContent = preg_replace($patterns, $replaceContent, $tempContent);
        } else if (!empty($tagId)) {
            $patterns = "/\<$tagName id=\"$tagId\"(.*)\<\/$tagName>/imsU";
            $tempContent = preg_replace($patterns, $replaceContent, $tempContent);
        } else {
            $patterns = "/\<$tagName(.*)\<\/$tagName>/imsU";
            $tempContent = preg_replace($patterns, $replaceContent, $tempContent);
        }
        return $tempContent;

    }


    /**
     * 删除模板中所有自定义标签或指定ID的标签
     * @param string $tempContent 要替换的模板内容，指针型参数，直接输出结果
     * @param string $tagId 替换标签的id，如果为空，则删除所有自定义标签
     * @param string $tagName 替换标签名称
     */
    public static function RemoveCustomTag(&$tempContent, $tagId = "", $tagName = self::DEFAULT_TAG_NAME)
    {
        if (empty($tagId)) {
            $patterns = "/\<$tagName(.*)\<\/$tagName>/imsU";
            $tempContent = preg_replace($patterns, "", $tempContent);
        } else {
            $patterns = "/\<$tagName id=\"$tagId\"(.*)\<\/$tagName>/imsU";
            $tempContent = preg_replace($patterns, "", $tempContent);
        }
    }

    /**
     * 替换页面中Select Checkbox Radio的选择值
     * @param string $tempContent 要替换的模板内容，指针型参数，直接输出结果
     * @param string $fieldName 字段名
     * @param string $fieldValue 字段值
     */
    public static function ReplaceSelectControl(&$tempContent, $fieldName, $fieldValue)
    {
        $tempContent = str_ireplace("{sel_" . $fieldName . "_" . $fieldValue . "}", "selected", $tempContent);
        $tempContent = str_ireplace("{cb_" . $fieldName . "_" . $fieldValue . "}", "checked", $tempContent);
        $tempContent = str_ireplace("{rd_" . $fieldName . "_" . $fieldValue . "}", "checked", $tempContent);
    }

}

?>
