<?php

/**
 * 模板处理工具类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Tools
 * @author zhangchi
 */
class Template {

    /**
     * 读取模板内容
     * @param string $templateFileUrl 模板文件路径
     * @param string $templateName 模板名称
     * @param string $templatePath 模板路径
     * @return string 模板内容
     */
    public static function Load($templateFileUrl, $templateName = "", $templatePath = "system_template") {
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
    private static function GetTempLateUrl($templateName, $templatePath = "system_template") {
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
     * @param int $tagId 替换标签的id
     * @param string $tagName 替换标签名称
     */
    public static function ReplaceList(&$tempContent, $arrList, $tagId, $tagName = "icms_list") {
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

                        $itemRowIntroTitleCount = self::GetParamIntValue($doc, $tagName, "item_row_intro_count");
                        if ($itemRowIntroTitleCount <= 0) {
                            $itemRowIntroTitleCount = self::GetParamIntValue($doc, $tagName, "intro");
                        }

                        $headerTempContent = self::GetNodeValue($doc, "header", $tagName);
                        if (strlen($headerTempContent) > 0 && $headerRowCount <= 0) {
                            $headerRowCount = 1;
                        }
                        $footerTempContent = self::GetNodeValue($doc, "footer", $tagName);
                        if (strlen($footerTempContent) > 0 && $footerRowCount <= 0) {
                            $footerRowCount = 1;
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

                        $itemRowIntroTitleCount = intval($arrayXml[0]['attributes']['ITEM_ROW_INTRO_TITLE_COUNT']);
                        if ($itemRowIntroTitleCount <= 0) {
                            $itemRowIntroTitleCount = intval($arrayXml[0]['attributes']['INTRO']);
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
                        $itemtype = "header";
                        $list = self::ReplaceListFor($i, $type, $tagId, $itemRowTitleCount, $itemRowIntroTitleCount, $headerRowTitleCount, $footerRowTitleCount, $columns, $list, $itemtype);
                        $list = str_ireplace("{c_allcount}", count($arrList), $list);
                        $sb = $sb . $list;
                    }


                    //附加分割线
                    $sb = $sb . $headerSplitterTempContent;

                    if ($itemSplitterCount <= 0) {
                        $itemSplitterCount = 0;
                    }

                    //echo $alteritemTempContent;

                    for ($i = 0 + $headerRowCount; $i < count($arrList) - $footerRowCount; $i++) {
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
                        $itemtype = "item";
                        $list = self::ReplaceListFor($i, $type, $tagId, $itemRowTitleCount, $itemRowIntroTitleCount, $headerRowTitleCount, $footerRowTitleCount, $columns, $list, $itemtype);
                        $list = str_ireplace("{c_allcount}", count($arrList), $list);
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
                            $itemtype = "footer";
                            $list = self::ReplaceListFor($i, $type, $tagId, $itemRowTitleCount, $itemRowIntroTitleCount, $headerRowTitleCount, $footerRowTitleCount, $columns, $list, $itemtype);
                            $sb = $sb . $list;
                        }
                    }

                    $result = $sb;
                    $tempContent = str_replace($listTempContent, $result, $tempContent);
                }
            } else {
                $beginTagString = '<' . $tagName . ' id="' . $tagId . '"';
                $endTagString = '</' . $tagName . '>';
                $temp1 = substr($tempContent, 0, strpos($tempContent, $beginTagString));
                $x = strpos($tempContent, $endTagString, strpos($tempContent, $beginTagString));
                $temp2 = substr($tempContent, $x + 8);
                $tempContent = $temp1 . $temp2;

            }
        }
    }

    /**
     * 循环FOR的方法
     * @param type $type
     * @param type $id
     * @param type $itemRowShortCount
     * @param type $itemRowIntroShortCount
     * @param type $columns
     * @param type $list
     * @return type 
     */
    private static function ReplaceListFor($i, $type, $id, $itemRowShortCount, $itemRowIntroShortCount, $headerRowShortCount, $footerRowShortCount, $columns, $list, $itemtype) {
        $list = str_ireplace("{c_no}", $i + 1, $list);        //加入输出序号
        if (isset($columns["DirectUrl"]) && $columns["DirectUrl"] != '') { //链接文档
            $list = str_ireplace("{c_url}", $columns["DirectUrl"], $list); //直接输出url
        } else {
            $list = str_ireplace("{c_url}", "/h/{f_documentchannelid}/{f_year}{f_month}{f_day}/{f_documentnewsid}.html", $list);
            //$list = str_ireplace("{c_url}", "{pubrootpath}/h/{f_documentchannelid}/{f_year}{f_month}{f_day}/{f_documentnewsid}.html", $list);
        }
        foreach ($columns as $columnname => $columnvalue) {
            if (strtolower($type) === 'list') {
                self::FormatColumnValue($columnname, $columnvalue, $id, $list, $itemRowShortCount, $headerRowShortCount, $footerRowShortCount);
            } else if (strtolower($type) === 'docnewslist') {
                self::FormatDocNewsColumnValue($columnname, $columnvalue, $id, $list, $itemRowShortCount, $itemRowIntroShortCount, $headerRowShortCount, $footerRowShortCount, $itemtype);
            } else if (strtolower($type) === 'docthreadlist') {
                self::FormatDocThreadColumnValue($columnname, $columnvalue, $id, $list, $itemRowShortCount, $itemRowIntroShortCount);
            } else if (strtolower($type) === 'productlist') {
                self::FormatProductColumnValue($columnname, $columnvalue, $id, $list, $itemRowShortCount, $itemRowIntroShortCount, $headerRowShortCount, $footerRowShortCount, $itemtype);
            } else if (strtolower($type) === 'productparamparentlist' || strtolower($type) === 'productparamchildlist') {
                self::FormatProductParamColumnValue($columnname, $columnvalue, $id, $list, $itemRowShortCount, $itemRowIntroShortCount, $headerRowShortCount, $footerRowShortCount, $itemtype);
            } else if (strtolower($type) === 'productparamtypeoptionlist') {
                self::FormatProductParamTypeOptionColumnValue($columnname, $columnvalue, $id, $list, $itemRowShortCount, $itemRowIntroShortCount, $headerRowShortCount, $footerRowShortCount, $itemtype);
            } else if (strtolower($type) === 'usergrouplist') {
                self::FormatColumnValue($columnname, $columnvalue, $id, $list, $itemRowShortCount, $itemRowIntroShortCount, $headerRowShortCount, $footerRowShortCount, $itemtype);
            } else if (strtolower($type) === 'activitylist') {
                self::FormatActivityColumnValue($columnname, $columnvalue, $id, $list, $itemRowShortCount, $itemRowIntroShortCount);
            } else if (strtolower($type) === 'activityuserlist') {
                self::FormatActivityUserListColumnValue($columnname, $columnvalue, $id, $list, $itemRowShortCount, $itemRowIntroShortCount);
            } else if (strtolower($type) === 'useralbumlist') {
                self::FormatUserAlbumListColumnValue($columnname, $columnvalue, $id, $list, $itemRowShortCount, $headerRowShortCount, $itemRowIntroShortCount);
            } else if (strtolower($type) === 'commentlist') {
                self::FormatCommentListColumnValue($columnname, $columnvalue, $id, $list, $itemRowShortCount, $headerRowShortCount, $itemRowIntroShortCount);
            } else if (strtolower($type) === 'userlist') {
                self::FormatUserListColumnValue($columnname, $columnvalue, $id, $list, $itemRowShortCount, $itemRowIntroShortCount);
            } else if (strtolower($type) === 'userrolelist') {
                self::FormatUserRoleListColumnValue($columnname, $columnvalue, $id, $list, $itemRowShortCount, $itemRowIntroShortCount);
            } else if (strtolower($type) === 'sitelist') {
                self::FormatSiteListColumnValue($columnname, $columnvalue, $id, $list, $itemRowShortCount, $itemRowIntroShortCount);
            } else {
                self::FormatColumnValue($columnname, $columnvalue, $id, $list, $itemRowShortCount, $headerRowShortCount, $footerRowShortCount);
            }
        }
        return $list;
    }

    /**
     * 通用的格式化字段
     * @param type $columnname
     * @param type $columnvalue
     * @param type $listName
     * @param type $list
     * @param type $itemRowShortCount 
     */
    private static function FormatColumnValue($columnname, $columnvalue, $listName, &$list, $itemRowShortCount, $headerRowShortCount, $footerRowShortCount) {
        if (strtolower($columnname) == "state") {
            $list = str_ireplace("{f_" . $columnname . "_value}", $columnvalue, $list);
            //$columnvalue = Format::ToState($columnvalue, $listName);
        }
        //Select类型替换===Ljy
        if ((strtolower($columnname) == "tasktype") || (strtolower($columnname) == "userarea") || (strtolower($columnname) == "usertype")) {
            $list = str_ireplace("{f_" . $columnname . "_value}", $columnvalue, $list);
            //$columnvalue = Format::ToSelectType($columnvalue, $listName);
        }

        if (strtolower($columnname) == "threadstate") {
            $list = str_ireplace("{f_" . $columnname . "_value}", $columnvalue, $list);
            //$columnvalue = Format::ToState($columnvalue, $listName);
        }
        if (strtolower($columnname) == "threadid") {
            $list = str_ireplace("{f_" . $columnname . "_value}", $columnvalue, $list);
            //$columnvalue = Format::ToUrl($columnvalue, $listName);
        }

        if (strtolower($columnname) == "useralbumtag") {
            $list = str_ireplace("{f_" . $columnname . "_value}", $columnvalue, $list);
            $columnvalue = urlencode($columnvalue);
        }
        if (strtolower($columnname) == "documentnewstag") {  //标签链接
            $documentnewstag_region = urlencode($columnvalue);
            $list = str_ireplace("{f_" . $columnname . "_urlencode}", $documentnewstag_region, $list);
        }
        $pos = stripos(strtolower($columnname), "subject");
        if ($pos !== false) {
            $columnvalue = Format::FormatHtmlTag($columnvalue);
            $list = str_ireplace("{c_titleall}", $columnvalue, $list);
            if (intval($itemRowShortCount) > 0) {
                //截断字符
                $columnvalue = Format::ToShort($columnvalue, $itemRowShortCount);
            }
        }
        $pos = stripos(strtolower($columnname), "publishdate");
        if ($pos !== false) {
            $date1 = explode(' ', $columnvalue);
            $date2 = explode('-', $date1[0]);
            $year = $date2[0];
            $month = $date2[1];
            $day = $date2[2];

            $list = str_ireplace("{f_year}", $year, $list);
            $list = str_ireplace("{f_month}", $month, $list);
            $list = str_ireplace("{f_day}", $day, $list);
        }

        $pos = stripos(strtolower($columnname), "createdate");
        if ($pos !== false) {
            $date1 = explode(' ', $columnvalue);
            $date2 = explode('-', $date1[0]);
            $year = $date2[0];
            $month = $date2[1];
            $day = $date2[2];

            $list = str_ireplace("{f_c_year}", $year, $list);
            $list = str_ireplace("{f_c_month}", $month, $list);
            $list = str_ireplace("{f_c_day}", $day, $list);
        }
        //$pos = strpos(strtolower($columnname), "title");
        //if ($pos !== false) {
        //    $columnvalue = Format::FormatQuote($columnvalue);
        //}
        $list = str_ireplace("{f_" . $columnname . "}", $columnvalue, $list);
    }

    /**
     * 为documentnews 格式化字段
     * @param <type> $columnname
     * @param <type> $columnvalue
     * @param <type> $listName
     * @param <type> $list
     * @param <type> $itemRowShortCount
     */
    private static function FormatDocNewsColumnValue($columnname, $columnvalue, $listName, &$list, $itemRowShortCount, $itemRowIntroShortCount, $headerRowShortCount, $footerRowShortCount, $itemtype = "item") {
        //格式化标题
        $pos = stripos(strtolower($columnname), "title");
        $pos2 = stripos(strtolower($columnname), "titlepic");
        if ($pos !== false && $pos2 === false) {
            $columnvalue = Format::FormatHtmlTag($columnvalue);

            if ($itemtype == "header") {
                if (intval($headerRowShortCount) > 0) {
                    //截断字符
                    $columnvalue = Format::ToShort($columnvalue, $headerRowShortCount);
                }
            } else if ($itemtype == "footer") {
                if (intval($footerRowShortCount) > 0) {
                    //截断字符
                    $columnvalue = Format::ToShort($columnvalue, $footerRowShortCount);
                }
            } else {
                if (intval($itemRowShortCount) > 0) {
                    //截断字符
                    $columnvalue = Format::ToShort($columnvalue, $itemRowShortCount);
                }
            }
        }
        //格式化简介
        $pos = stripos(strtolower($columnname), "intro");
        if ($pos !== false) {
            if (intval($itemRowIntroShortCount) > 0) {
                //截断字符
                $columnvalue = Format::ToShort($columnvalue, $itemRowIntroShortCount);
            }
        }

        $pos = stripos(strtolower($columnname), "publishdate");
        if ($pos !== false) {
            $date1 = explode(' ', $columnvalue);
            $date2 = explode('-', $date1[0]);
            $year = $date2[0];
            $month = $date2[1];
            $day = $date2[2];

            $list = str_ireplace("{f_year}", $year, $list);
            $list = str_ireplace("{f_month}", $month, $list);
            $list = str_ireplace("{f_day}", $day, $list);
        }


        $pos = stripos(strtolower($columnname), "showdate");
        if ($pos !== false) {
            $date1 = explode(' ', $columnvalue);
            $date2 = explode('-', $date1[0]);
            $year = $date2[0];
            $month = $date2[1];
            $day = $date2[2];

            $list = str_ireplace("{f_showyear}", $year, $list);
            $list = str_ireplace("{f_showmonth}", $month, $list);
            $list = str_ireplace("{f_showday}", $day, $list);
        }

        $pos = stripos(strtolower($columnname), "documentnewscontent");
        if ($pos !== false) {
            $columnvalue = str_ireplace("../upload/docnews", "/upload/docnews", $columnvalue);

            if (intval($itemRowIntroShortCount) > 0) {
                //截断字符
                $columnvalue = Format::ToShort(strip_tags($columnvalue), $itemRowIntroShortCount);
            }
            $list = str_ireplace("{f_documentnewscontent_nohtml}", strip_tags($columnvalue), $list);
        }
        if (strtolower($columnname) === "documentnewscontent") {
            
        }


        //$columnvalue = str_ireplace("../upload/", "/upload/", $columnvalue);
        //echo $columnvalue;
        $columnvalue = str_ireplace("'", "&#039;", $columnvalue);
        $list = str_ireplace("{f_" . $columnname . "}", $columnvalue, $list);
    }

    /**
     * 为documentthread 格式化字段
     * @param <type> $columnname
     * @param <type> $columnvalue
     * @param <type> $listName
     * @param <type> $list
     * @param <type> $itemRowShortCount
     */
    private static function FormatDocThreadColumnValue($columnname, $columnvalue, $listName, &$list, $itemRowShortCount) {
        $pos = stripos(strtolower($columnname), "subject");
        if ($pos !== false) {
            $columnvalue = Format::FormatHtmlTag($columnvalue);
            $list = str_ireplace("{c_titleall}", $columnvalue, $list);
            if (intval($itemRowShortCount) > 0) {
                //截断字符
                $columnvalue = Format::ToShort($columnvalue, $itemRowShortCount);
            }
        }
        $pos = stripos(strtolower($columnname), "publishdate");
        if ($pos !== false) {

            $date1 = explode(' ', $columnvalue);
            $date2 = explode('-', $date1[0]);
            $year = $date2[0];
            $month = $date2[1];
            $day = $date2[2];

            $list = str_ireplace("{f_year}", $year, $list);
            $list = str_ireplace("{f_month}", $month, $list);
            $list = str_ireplace("{f_day}", $day, $list);
        }
        $pos = stripos(strtolower($columnname), "createdate");
        if ($pos !== false) {

            $date1 = explode(' ', $columnvalue);
            $date2 = explode('-', $date1[0]);
            $year = $date2[0];
            $month = $date2[1];
            $day = $date2[2];

            $list = str_ireplace("{c_year}", $year, $list);
            $list = str_ireplace("{c_month}", $month, $list);
            $list = str_ireplace("{c_day}", $day, $list);
        }
        $pos = stripos(strtolower($columnname), "guestname");
        if ($pos !== false) {
            if (strlen($columnvalue) > 1) {
                $list = str_ireplace("{c_name}", $columnvalue, $list);
            } else {
                $list = str_ireplace("{c_name}", "游客", $list);
            }
        }
        $pos = stripos(strtolower($columnname), "username");
        if ($pos !== false) {
            if (strlen($columnvalue) > 1) {
                $list = str_ireplace("{c_name}", $columnvalue, $list);
            }
        }
        $list = str_ireplace("{f_" . $columnname . "}", $columnvalue, $list);
    }

    /**
     * 为productparam 格式化字段
     * @param <type> $columnname
     * @param <type> $columnvalue
     * @param <type> $listName
     * @param <type> $list
     * @param <type> $itemRowShortCount
     */
    private static function FormatProductParamColumnValue($columnname, $columnvalue, $listName, &$list, $itemRowShortCount, $itemRowIntroShortCount, $headerRowShortCount, $footerRowShortCount, $itemtype = "item") {
        //格式化标题
        $pos = stripos(strtolower($columnname), "title");
        $pos2 = stripos(strtolower($columnname), "titlepic");
        if ($pos !== false && $pos2 === false) {
            $columnvalue = Format::FormatHtmlTag($columnvalue);

            if ($itemtype == "header") {
                if (intval($headerRowShortCount) > 0) {
                    //截断字符
                    $columnvalue = Format::ToShort($columnvalue, $headerRowShortCount);
                }
            } else if ($itemtype == "footer") {
                if (intval($footerRowShortCount) > 0) {
                    //截断字符
                    $columnvalue = Format::ToShort($columnvalue, $footerRowShortCount);
                }
            } else {
                if (intval($itemRowShortCount) > 0) {
                    //截断字符
                    $columnvalue = Format::ToShort($columnvalue, $itemRowShortCount);
                }
            }
        }
        //格式化简介
        $pos = stripos(strtolower($columnname), "intro");
        if ($pos !== false) {
            if (intval($itemRowIntroShortCount) > 0) {
                //截断字符
                $columnvalue = Format::ToShort($columnvalue, $itemRowIntroShortCount);
            }
        }

        $pos = stripos(strtolower($columnname), "publishdate");
        if ($pos !== false) {
            $date1 = explode(' ', $columnvalue);
            $date2 = explode('-', $date1[0]);
            $year = $date2[0];
            $month = $date2[1];
            $day = $date2[2];

            $list = str_ireplace("{f_year}", $year, $list);
            $list = str_ireplace("{f_month}", $month, $list);
            $list = str_ireplace("{f_day}", $day, $list);
        }


        $pos = stripos(strtolower($columnname), "showdate");
        if ($pos !== false) {
            $date1 = explode(' ', $columnvalue);
            $date2 = explode('-', $date1[0]);
            $year = $date2[0];
            $month = $date2[1];
            $day = $date2[2];

            $list = str_ireplace("{f_showyear}", $year, $list);
            $list = str_ireplace("{f_showmonth}", $month, $list);
            $list = str_ireplace("{f_showday}", $day, $list);
        }

        if (strtolower($columnname) === "documentnewscontent") {
            $columnvalue = str_ireplace("../upload/docnews", "/upload/docnews", $columnvalue);
        }

        //$columnvalue = str_ireplace("../upload/", "/upload/", $columnvalue);
        //echo $columnvalue;
        $columnvalue = str_ireplace("'", "&#039;", $columnvalue);
        $list = str_ireplace("{f_" . $columnname . "}", $columnvalue, $list);
    }

    /**
     * 为activitye 格式化字段
     * @param <type> $columnname
     * @param <type> $columnvalue
     * @param <type> $listName
     * @param <type> $list
     * @param <type> $itemRowShortCount
     */
    private static function FormatActivityColumnValue($columnname, $columnvalue, $listName, &$list, $itemRowShortCount) {
        $pos = stripos(strtolower($columnname), "activitysubject");
        if ($pos !== false) {
            $columnvalue = Format::FormatHtmlTag($columnvalue);
            $list = str_ireplace("{c_titleall}", $columnvalue, $list);
            if (intval($itemRowShortCount) > 0) {
                //截断字符
                $columnvalue = Format::ToShort($columnvalue, $itemRowShortCount);
            }
        }
        //格式化简介
        $pos = stripos(strtolower($columnname), "activityintro");
        if ($pos !== false) {
            if (intval($itemRowIntroShortCount) > 0) {
                //截断字符
                $columnvalue = Format::ToShort($columnvalue, $itemRowIntroShortCount);
            }
        }
        $pos = stripos(strtolower($columnname), "activitycontent");
        if ($pos !== false) {
            if (intval($itemRowIntroShortCount) > 0) {
                //截断字符
                $columnvalue = Format::ToShort($columnvalue, $itemRowIntroShortCount);
            } else {
                $columnvalue = str_replace(array('&nbsp;', "\r", "\n", '\'', '"'), '', Format::ToShort(strip_tags($columnvalue), 50));
            }
        }
        $pos = stripos(strtolower($columnname), "publishdate");
        if ($pos !== false) {

            $date1 = explode(' ', $columnvalue);
            $date2 = explode('-', $date1[0]);
            $year = $date2[0];
            $month = $date2[1];
            $day = $date2[2];

            $list = str_ireplace("{f_year}", $year, $list);
            $list = str_ireplace("{f_month}", $month, $list);
            $list = str_ireplace("{f_day}", $day, $list);
        }

        $pos = stripos(strtolower($columnname), "applybegindate");
        if ($pos !== false) {

            $date1 = explode(' ', $columnvalue);
            $date2 = explode('-', $date1[0]);
            $year = $date2[0];
            $month = $date2[1];
            $day = $date2[2];

            $list = str_ireplace("{f_applybeginyear}", $year, $list);
            $list = str_ireplace("{f_applybeginmonth}", $month, $list);
            $list = str_ireplace("{f_applybeginday}", $day, $list);
        }

        $pos = stripos(strtolower($columnname), "applyenddate");
        if ($pos !== false) {

            $date1 = explode(' ', $columnvalue);
            $date2 = explode('-', $date1[0]);
            $year = $date2[0];
            $month = $date2[1];
            $day = $date2[2];

            $list = str_ireplace("{f_applyendyear}", $year, $list);
            $list = str_ireplace("{f_applyendmonth}", $month, $list);
            $list = str_ireplace("{f_applyendday}", $day, $list);
        }

        $pos = stripos(strtolower($columnname), "begindate");
        if ($pos !== false) {

            $date1 = explode(' ', $columnvalue);
            $date2 = explode('-', $date1[0]);
            $year = $date2[0];
            $month = $date2[1];
            $day = $date2[2];

            $list = str_ireplace("{f_beginyear}", $year, $list);
            $list = str_ireplace("{f_beginmonth}", $month, $list);
            $list = str_ireplace("{f_beginday}", $day, $list);
        }

        $pos = stripos(strtolower($columnname), "enddate");
        if ($pos !== false) {

            $date1 = explode(' ', $columnvalue);
            $date2 = explode('-', $date1[0]);
            $year = $date2[0];
            $month = $date2[1];
            $day = $date2[2];

            $list = str_ireplace("{f_endyear}", $year, $list);
            $list = str_ireplace("{f_endmonth}", $month, $list);
            $list = str_ireplace("{f_endday}", $day, $list);
        }

        $pos = stripos(strtolower($columnname), "createdate");
        if ($pos !== false) {

            $date1 = explode(' ', $columnvalue);
            $date2 = explode('-', $date1[0]);
            $year = $date2[0];
            $month = $date2[1];
            $day = $date2[2];

            $list = str_ireplace("{c_year}", $year, $list);
            $list = str_ireplace("{c_month}", $month, $list);
            $list = str_ireplace("{c_day}", $day, $list);
        }
        $list = str_ireplace("{f_" . $columnname . "}", $columnvalue, $list);
    }

    /**
     * 替换活动报名用户相关信息
     * @param <type> $columnname
     * @param <type> $columnvalue
     * @param <type> $listName
     * @param <type> $list
     * @param <type> $itemRowShortCount 
     */
    private static function FormatActivityUserListColumnValue($columnname, $columnvalue, $listName, &$list, $itemRowShortCount) {
        $pos = stripos(strtolower($columnname), "avatar");
        $_funcurl = $domain['func'];
        $_posf = stripos($_funcurl, "http://");
        if ($_posf === false) {
            $_funcurl = "http://" . $_funcurl;
        }
        if ($pos !== false) {
            if (strlen($columnvalue) > 10) {
                $columnvalue = $_funcurl . $columnvalue;
            } else {
                $columnvalue = $_funcurl . "/upload/user/default.gif";
            }
        }

        $list = str_ireplace("{f_" . $columnname . "}", $columnvalue, $list);
    }

    /**
     * 为productparamtypeoption 格式化字段
     * @param <type> $columnname
     * @param <type> $columnvalue
     * @param <type> $listName
     * @param <type> $list
     * @param <type> $itemRowShortCount
     */
    private static function FormatProductParamTypeOptionColumnValue($columnname, $columnvalue, $listName, &$list, $itemRowShortCount, $itemRowIntroShortCount, $headerRowShortCount, $footerRowShortCount, $itemtype = "item") {
        //格式化标题        
        $pos = stripos(strtolower($columnname), "title");
        $pos2 = stripos(strtolower($columnname), "titlepic");
        if ($pos !== false && $pos2 === false) {
            $columnvalue = Format::FormatHtmlTag($columnvalue);

            if ($itemtype == "header") {
                if (intval($headerRowShortCount) > 0) {
                    //截断字符
                    $columnvalue = Format::ToShort($columnvalue, $headerRowShortCount);
                }
            } else if ($itemtype == "footer") {
                if (intval($footerRowShortCount) > 0) {
                    //截断字符
                    $columnvalue = Format::ToShort($columnvalue, $footerRowShortCount);
                }
            } else {
                if (intval($itemRowShortCount) > 0) {
                    //截断字符
                    $columnvalue = Format::ToShort($columnvalue, $itemRowShortCount);
                }
            }
        }
        //格式化简介
        $pos = stripos(strtolower($columnname), "intro");
        if ($pos !== false) {
            if (intval($itemRowIntroShortCount) > 0) {
                //截断字符
                $columnvalue = Format::ToShort($columnvalue, $itemRowIntroShortCount);
            }
        }

        $pos = stripos(strtolower($columnname), "publishdate");
        if ($pos !== false) {
            $date1 = explode(' ', $columnvalue);
            $date2 = explode('-', $date1[0]);
            $year = $date2[0];
            $month = $date2[1];
            $day = $date2[2];

            $list = str_ireplace("{f_year}", $year, $list);
            $list = str_ireplace("{f_month}", $month, $list);
            $list = str_ireplace("{f_day}", $day, $list);
        }


        $pos = stripos(strtolower($columnname), "showdate");
        if ($pos !== false) {
            $date1 = explode(' ', $columnvalue);
            $date2 = explode('-', $date1[0]);
            $year = $date2[0];
            $month = $date2[1];
            $day = $date2[2];

            $list = str_ireplace("{f_showyear}", $year, $list);
            $list = str_ireplace("{f_showmonth}", $month, $list);
            $list = str_ireplace("{f_showday}", $day, $list);
        }

        if (strtolower($columnname) === "documentnewscontent") {
            $columnvalue = str_ireplace("../upload/docnews", "/upload/docnews", $columnvalue);
        }

        //$columnvalue = str_ireplace("../upload/", "/upload/", $columnvalue);
        //echo $columnvalue;
        $columnvalue = str_ireplace("'", "&#039;", $columnvalue);
        $list = str_ireplace("{f_" . $columnname . "}", $columnvalue, $list);
    }

    /**
     * 为product 格式化字段
     * @param <type> $columnname
     * @param <type> $columnvalue
     * @param <type> $listName
     * @param <type> $list
     * @param <type> $itemRowShortCount
     */
    private static function FormatProductColumnValue($columnname, $columnvalue, $listName, &$list, $itemRowShortCount, $itemRowIntroShortCount, $headerRowShortCount, $footerRowShortCount, $itemtype = "item") {
        //格式化标题
        $pos = stripos(strtolower($columnname), "title");
        $pos2 = stripos(strtolower($columnname), "titlepic");
        if ($pos !== false && $pos2 === false) {
            $columnvalue = Format::FormatHtmlTag($columnvalue);

            if ($itemtype == "header") {
                if (intval($headerRowShortCount) > 0) {
                    //截断字符
                    $columnvalue = Format::ToShort($columnvalue, $headerRowShortCount);
                }
            } else if ($itemtype == "footer") {
                if (intval($footerRowShortCount) > 0) {
                    //截断字符
                    $columnvalue = Format::ToShort($columnvalue, $footerRowShortCount);
                }
            } else {
                if (intval($itemRowShortCount) > 0) {
                    //截断字符
                    $columnvalue = Format::ToShort($columnvalue, $itemRowShortCount);
                }
            }
        }
        //格式化简介
        $pos = stripos(strtolower($columnname), "intro");
        if ($pos !== false) {
            if (intval($itemRowIntroShortCount) > 0) {
                //截断字符
                $columnvalue = Format::ToShort($columnvalue, $itemRowIntroShortCount);
            }
        }
        $pos = stripos(strtolower($columnname), "productname");
        if ($pos !== false) {
            if (intval($itemRowShortCount) > 0) {
                //截断字符
                $columnvalue = Format::ToShort($columnvalue, $itemRowShortCount);
            }
        }

        $pos = stripos(strtolower($columnname), "publishdate");
        if ($pos !== false) {
            $date1 = explode(' ', $columnvalue);
            $date2 = explode('-', $date1[0]);
            $year = $date2[0];
            $month = $date2[1];
            $day = $date2[2];

            $list = str_ireplace("{f_year}", $year, $list);
            $list = str_ireplace("{f_month}", $month, $list);
            $list = str_ireplace("{f_day}", $day, $list);
        }

        $pos = stripos(strtolower($columnname), "createdate");
        if ($pos !== false) {
            $date1 = explode(' ', $columnvalue);
            $date2 = explode('-', $date1[0]);
            $year = $date2[0];
            $month = $date2[1];
            $day = $date2[2];

            $list = str_ireplace("{f_createyear}", $year, $list);
            $list = str_ireplace("{f_createmonth}", $month, $list);
            $list = str_ireplace("{f_createday}", $day, $list);
        }

        $pos = stripos(strtolower($columnname), "showdate");
        if ($pos !== false) {
            $date1 = explode(' ', $columnvalue);
            $date2 = explode('-', $date1[0]);
            $year = $date2[0];
            $month = $date2[1];
            $day = $date2[2];

            $list = str_ireplace("{f_showyear}", $year, $list);
            $list = str_ireplace("{f_showmonth}", $month, $list);
            $list = str_ireplace("{f_showday}", $day, $list);
        }

        $pos = stripos(strtolower($columnname), "saleprice");
        if ($pos !== false) {
            if (is_numeric($columnvalue) && $columnvalue > 0)
                $list = str_ireplace("{f_saleprice_show}", number_format($columnvalue / 10000, 2, '.', ''), $list);
            else
                $list = str_ireplace("{f_saleprice_show}", "", $list);
        }

        if (strtolower($columnname) === "productcontent") {
            $columnvalue = str_ireplace("../upload/product", "/upload/product", $columnvalue);
        }

        //$columnvalue = str_ireplace("../upload/", "/upload/", $columnvalue);
        //echo $columnvalue;
        $columnvalue = str_ireplace("'", "&#039;", $columnvalue);
        $list = str_ireplace("{f_" . $columnname . "}", $columnvalue, $list);
    }

    public static function FormatUserAlbumListColumnValue($columnname, $columnvalue, $listName, &$list, $itemRowShortCount, $headerRowShortCount, $footerRowShortCount) {
        if (strtolower($columnname) == "state") {
            $list = str_ireplace("{f_" . $columnname . "_value}", $columnvalue, $list);
            $columnvalue = Format::ToState($columnvalue, $listName);
        }
        if (strtolower($columnname) == "indextop") {
            if ($columnvalue == 1) {
                $columnvalue = "<span style='color:blue'>是</span>";
            } else {
                $columnvalue = "否";
            }
        }
        //Select类型替换===Ljy
        if ((strtolower($columnname) == "tasktype") || (strtolower($columnname) == "userarea") || (strtolower($columnname) == "usertype")) {
            $list = str_ireplace("{f_" . $columnname . "_value}", $columnvalue, $list);
            $columnvalue = Format::ToSelectType($columnvalue, $listName);
        }

        if (strtolower($columnname) == "threadstate") {
            $list = str_ireplace("{f_" . $columnname . "_value}", $columnvalue, $list);
            $columnvalue = Format::ToState($columnvalue, $listName);
        }
        if (strtolower($columnname) == "threadid") {
            $list = str_ireplace("{f_" . $columnname . "_value}", $columnvalue, $list);
            $columnvalue = Format::ToUrl($columnvalue, $listName);
        }

        if (strtolower($columnname) == "useralbumtag") {
            $list = str_ireplace("{f_" . $columnname . "_value}", $columnvalue, $list);
            $columnvalue = urlencode($columnvalue);
        }
        $pos = stripos(strtolower($columnname), "subject");
        if ($pos !== false) {
            $columnvalue = Format::FormatHtmlTag($columnvalue);
            $list = str_ireplace("{c_titleall}", $columnvalue, $list);
            if (intval($itemRowShortCount) > 0) {
                //截断字符
                $columnvalue = Format::ToShort($columnvalue, $itemRowShortCount);
            }
        }
        $pos = stripos(strtolower($columnname), "publishdate");
        if ($pos !== false) {
            $date1 = explode(' ', $columnvalue);
            $date2 = explode('-', $date1[0]);
            $year = $date2[0];
            $month = $date2[1];
            $day = $date2[2];

            $list = str_ireplace("{f_year}", $year, $list);
            $list = str_ireplace("{f_month}", $month, $list);
            $list = str_ireplace("{f_day}", $day, $list);
        }
        if (strtolower($columnname) == "region") {
            $columnvalue_region = urlencode($columnvalue);
            $list = str_ireplace("{f_region_urlencode}", $columnvalue_region, $list);
        }
        $list = str_ireplace("{f_" . $columnname . "}", $columnvalue, $list);
    }

    public static function FormatCommentListColumnValue($columnname, $columnvalue, $listName, &$list, $itemRowShortCount, $headerRowShortCount, $footerRowShortCount) {
        if (strtolower($columnname) == "state") {
            $list = str_ireplace("{f_" . $columnname . "_value}", $columnvalue, $list);
            $columnvalue = Format::ToState($columnvalue, $listName);
        }

        if (strtolower($columnname) == "username") {
            if (strlen($columnvalue) <= 0 || $columnvalue == null) {
                $columnvalue = "游客";
            }
        }

        if (strtolower($columnname) == "avatar") {
            if (strlen($columnvalue) <= 10 || $columnvalue == null) {
                $columnvalue = "/upload/user/default.gif";
            }
        }

        if (strtolower($columnname) == "avatarsmall") {
            if (strlen($columnvalue) <= 10 || $columnvalue == null) {
                $columnvalue = "/upload/user/default.gif";
            }
        }

        if (strtolower($columnname) == "avatarmedium") {
            if (strlen($columnvalue) <= 10 || $columnvalue == null) {
                $columnvalue = "/upload/user/default.gif";
            }
        }

        $pos = stripos(strtolower($columnname), "subject");
        if ($pos !== false) {
            $columnvalue = Format::FormatHtmlTag($columnvalue);
            $list = str_ireplace("{c_titleall}", $columnvalue, $list);
            if (intval($itemRowShortCount) > 0) {
                //截断字符
                $columnvalue = Format::ToShort($columnvalue, $itemRowShortCount);
            }
        }
        $pos = stripos(strtolower($columnname), "content");
        if ($pos !== false) {
            $columnvalue = Format::FormatHtmlTag($columnvalue);
            if (intval($itemRowIntroShortCount) > 0) {
                //截断字符
                $columnvalue = Format::ToShort($columnvalue, $itemRowIntroShortCount);
            }
        }
        $pos = stripos(strtolower($columnname), "publishdate");
        if ($pos !== false) {
            $date1 = explode(' ', $columnvalue);
            $date2 = explode('-', $date1[0]);
            $year = $date2[0];
            $month = $date2[1];
            $day = $date2[2];

            $list = str_ireplace("{f_year}", $year, $list);
            $list = str_ireplace("{f_month}", $month, $list);
            $list = str_ireplace("{f_day}", $day, $list);
        }
        $list = str_ireplace("{f_" . $columnname . "}", $columnvalue, $list);
        $list = str_ireplace("{box}", "", $list);
        $list = str_ireplace("{/box}", "", $list);
        $list = str_ireplace("{span}", "", $list);
        $list = str_ireplace("{/span}", "", $list);
    }

    public static function FormatUserListColumnValue($columnname, $columnvalue, $listName, &$list, $itemRowShortCount, $itemRowIntroShortCount) {
        if (strtolower($columnname) == "state") {
            $list = str_ireplace("{f_" . $columnname . "_value}", $columnvalue, $list);
            $columnvalue = Format::ToState($columnvalue, $listName);
        }
        $pos = stripos(strtolower($columnname), "username");
        if ($pos !== false) {
            if (intval($itemRowShortCount) > 0) {
                //截断字符
                $columnvalue = Format::ToShort($columnvalue, $itemRowShortCount);
            }
        }
        $pos = stripos(strtolower($columnname), "showusername");
        if ($pos !== false) {
            if (intval($itemRowShortCount) > 0) {
                //截断字符
                $columnvalue = Format::ToShort($columnvalue, $itemRowShortCount);
            }
        }
        $pos = stripos(strtolower($columnname), "nickname");
        if ($pos !== false) {
            if (intval($itemRowShortCount) > 0) {
                //截断字符
                $columnvalue = Format::ToShort($columnvalue, $itemRowShortCount);
            }
        }
        $list = str_ireplace("{f_" . $columnname . "}", $columnvalue, $list);
    }

    public static function FormatUserRoleListColumnValue($columnname, $columnvalue, $listName, &$list, $itemRowShortCount, $itemRowIntroShortCount) {
        if (strtolower($columnname) == "rolestate") {
            $columnvalue = Format::ToState($columnvalue, $listName);
        }
        $list = str_ireplace("{f_" . $columnname . "}", $columnvalue, $list);
    }

    public static function FormatSiteListColumnValue($columnname, $columnvalue, $listName, &$list, $itemRowShortCount, $itemRowIntroShortCount) {
        if (strtolower($columnname) == "state") {
            $list = str_ireplace("{f_" . $columnname . "_value}", $columnvalue, $list);
            $columnvalue = Format::ToState($columnvalue, $listName);
        }
        $pos = stripos(strtolower($columnname), "sitename");
        if ($pos !== false) {
            if (intval($itemRowShortCount) > 0) {
                //截断字符
                $columnvalue = Format::ToShort($columnvalue, $itemRowShortCount);
            }
        }
        $list = str_ireplace("{f_" . $columnname . "}", $columnvalue, $list);
    }

    /**
     * 替换详细信息页面
     * @param type $tempcontent
     * @param type $arrList
     * @param type $arrMathe 默认为0，使用二维数组存储数据
     */
    public static function ReplaceOne(&$tempcontent, $arrList, $arrMathe = 0) {
        if (count($arrList) > 0) {
            if ($arrMathe > 0) { //使用一维数组存储数据
                self::_ReplaceOne($tempcontent, $arrList);
            } else { //使用二维数组存储数据
                for ($i = 0; $i < count($arrList); $i++) {
                    $columns = $arrList[$i];
                    self::_ReplaceOne($tempcontent, $columns);
                }
            }
        }
    }

    /**
     * 替换详细信息页面(后台系统使用)
     * @param type $tempcontent
     * @param type $arrList
     * @param type $arrMathe 
     */
    public static function ReplaceOneBack(&$tempcontent, $arrList, $arrMathe = 0) {
        $isback = 1;
        if (count($arrList) > 0) {
            if ($arrMathe > 0) { //使用一维数组存储数据
                self::_ReplaceOne($tempcontent, $arrList, $isback);
            } else { //使用二维数组存储数据
                for ($i = 0; $i < count($arrList); $i++) {
                    $columns = $arrList[$i];
                    self::_ReplaceOne($tempcontent, $columns, $isback);
                }
            }
        }
    }

    /**
     * 处理详细信息子方法
     * @param type $tempcontent
     * @param type $arrList 
     */
    private static function _ReplaceOne(&$tempcontent, $arrList, $isback = 0) {
        if (!empty($arrList)) {
            foreach ($arrList as $columnname => $columnvalue) {

                //对产品相关信息字段进行格式化处理
                self::_ReplaceProductOne($tempcontent, $columnname, $columnvalue);

                //对活动相关信息字段进行格式化处理
                self::_ReplaceActivityOne($tempcontent, $columnname, $columnvalue);

                $pos = strpos(strtolower($columnname), "content");
                if ($pos !== false) {
                    $columnvalue = str_ireplace("<textarea", "<text_area", $columnvalue);
                    $columnvalue = str_ireplace("</textarea>", "</text_area>", $columnvalue);
                }

                if (strtolower($columnname) === "documentnewscontent") {
                    $columnvalue = str_ireplace("../upload/docnews", "/upload/docnews", $columnvalue);
                }

                if (strtolower($columnname) === "documentnewstitle") {
                    $columnvalue = str_ireplace('"', "&quot;", $columnvalue);
                }

                //分拆处理发布时间字段
                $pos = stripos(strtolower($columnname), "publishdate");
                if ($pos !== false) {
                    $date1 = explode(' ', $columnvalue);
                    $date2 = explode('-', $date1[0]);
                    $year = $date2[0];
                    $month = $date2[1];
                    $day = $date2[2];

                    $tempcontent = str_ireplace("{year}", $year, $tempcontent);
                    $tempcontent = str_ireplace("{month}", $month, $tempcontent);
                    $tempcontent = str_ireplace("{day}", $day, $tempcontent);
                }

                //分拆处理显示时间字段
                $pos = stripos(strtolower($columnname), "showdate");
                if ($pos !== false) {
                    $date1 = explode(' ', $columnvalue);
                    $date2 = explode('-', $date1[0]);
                    $year = $date2[0];
                    $month = $date2[1];
                    $day = $date2[2];

                    $tempcontent = str_ireplace("{showyear}", $year, $tempcontent);
                    $tempcontent = str_ireplace("{showmonth}", $month, $tempcontent);
                    $tempcontent = str_ireplace("{showday}", $day, $tempcontent);
                }


                //处理常规的值
                if ($isback === 1) {//是否是后台系统使用
                    $pre_back = "b_";
                } else {
                    $pre_back = "";
                }


                if (strtolower($columnname) === "opencomment") {
                    $tempcontent = str_ireplace("{" . $pre_back . "s_" . $columnname . "_" . $columnvalue . "}", "selected=\"selected\"", $tempcontent);
                    if (intval($columnvalue) === 0) { //关闭评论
                        $columnvalue = "display:none;";
                    } else {
                        $columnvalue = "";
                    }
                    $tempcontent = str_ireplace("{" . $columnname . "}", $columnvalue, $tempcontent);
                }

                $tempcontent = str_ireplace("{" . $pre_back . $columnname . "}", $columnvalue, $tempcontent);

                //处理常规去掉HTML代码的值
                $tempcontent = str_ireplace("{" . $pre_back . $columnname . "_nohtml}", strip_tags($columnvalue), $tempcontent);

                //处理下拉菜单的默认值
                $tempcontent = str_ireplace("{" . $pre_back . "s_" . $columnname . "_" . $columnvalue . "}", "selected=\"selected\"", $tempcontent);

                $tempcontent = str_ireplace("{" . $pre_back . "r_" . $columnname . "_" . $columnvalue . "}", "checked=\"checked\"", $tempcontent);

                if (intval($columnvalue) === 1) {
                    $tempcontent = str_ireplace("{c_" . $columnname . "}", "checked=\"checked\"", $tempcontent);
                }
            }
        }
    }

    /**
     * 处理活动Activity内容页
     * @param <type> $tempcontent
     * @param <type> $columnname
     * @param <type> $columnvalue
     */
    private static function _ReplaceActivityOne(&$tempcontent, $columnname, $columnvalue) {

        $_icmsurl = $domain['icms'];
        $_funcurl = $domain['func'];
        $_pos = stripos($_icmsurl, "http://");
        if ($_pos === false) {
            $_icmsurl = "http://" . $_icmsurl;
        }

        $_posf = stripos($_funcurl, "http://");
        if ($_posf === false) {
            $_funcurl = "http://" . $_funcurl;
        }
        //处理titlepic,如为空时则显示指定的
        if (strtolower($columnname) === "titlepic") {
            if (strlen($columnvalue) < 10) {
                $urldefault = ""; //$_icmsurl . //Language::Load('activity', 7);
                $tempcontent = str_ireplace("{titlepic}", $urldefault, $tempcontent);
            }
        }
        if (strtolower($columnname) === "avatar") {
            if (strlen($columnvalue) < 10) {
                $urldefault = $_funcurl . "/upload/user/default.gif";
                $tempcontent = str_ireplace("{avatar}", $urldefault, $tempcontent);
            } else {
                $urldefault = $_funcurl . $columnvalue;
                $tempcontent = str_ireplace("{avatar}", $urldefault, $tempcontent);
            }
        }
    }

    private static function _ReplaceProductOne(&$tempcontent) {
        //格式化saleprice以万为单位计数
        if (strtolower($columnname) === "saleprice") {
            if (is_numeric($columnvalue) && $columnvalue > 0)
                $tempcontent = str_ireplace("{" . $columnname . "_wan}", number_format($columnvalue / 10000, 2, '.', ''), $tempcontent);
            else
                $tempcontent = str_ireplace("{" . $columnname . "_wan}", "", $tempcontent);
        }
    }

    /**
     * 单个替换各表单选项
     * @param <type> $checked为0表示替换成selected方式,为1表示替换成checked方式
     * @param <type> $arrList表示要替换的名称
     * @param <type> $replacestr表示替换的值
     * @param <type> 把$arrList与$replacestr组合起来进行替换
     */
    public static function ReplaceSelect(&$tempcontent, $arrList, $replacestr, $checked = 0) {
        if (count($replacestr) > 0) {
            $replacestrs = explode(',', $replacestr);
            foreach ($replacestrs as $columnname => $columnvalue) {
                if ($checked == 1) {
                    $tempcontent = str_ireplace("{t_" . $arrList . "_" . $columnvalue . "}", "checked", $tempcontent);
                } else {
                    $tempcontent = str_ireplace("{t_" . $arrList . "_" . $columnvalue . "}", "selected=\"selected\"", $tempcontent);
                }
            }
        }
    }

    public static function GetDocParamValue($doccontent, $paramname, $keyname = "cscms") {
        if (class_exists('DOMDocument')) { //服务器是否开启了DOM
            $doc = new DOMDocument();
            $doc->loadXML($doccontent);
            $result = self::GetParamStringValue($doc, $keyname, $paramname);
            return $result;
        } else {
            //使用SAX
            $_p = xml_parser_create();
            $_arrayXml = array();
            xml_parse_into_struct($_p, $doccontent, $_arrayXml);
            xml_parser_free($_p);
            $paramname = strtoupper($paramname);
            return $_arrayXml[0]['attributes'][$paramname];
        }
    }

    /**
     *
     * @param <type> $doc
     * @param <type> $paramName
     * @return <type>
     */
    private static function GetNodeValue($doc, $paramName, $keyname = "cscms") {
        $result = "";
        $cscmsNode = $doc->getElementsByTagName($keyname)->item(0);
        if ($cscmsNode->hasChildNodes()) {
            foreach ($cscmsNode->childNodes as $childNode) {
                if (strtolower($childNode->nodeName) == strtolower($paramName)) {
                    $result = $childNode->nodeValue;
                }
            }
        }
        return $result;
    }

    /**
     * 取得节点的内容 SAX用
     * @param array $arrayXml
     * @param string $tagName
     * @return string 节点的内容
     */
    private static function GetNodeValueForSax($arrayXml, $tagName) {
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
    private static function GetParamStringValue($domDocument, $tagName, $attrName, $defaultValue = "") {
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
    private static function GetParamIntValue($domDocument, $tagName, $attrName, $defaultValue = 0) {
        $result = intval(self::GetParamStringValue($domDocument, $tagName, $attrName, strval($defaultValue)));
        return $result;
    }

    /**
     * 取得页面中所有的标记段，并存入数组
     * @param string $tempContent
     * @param string $keyName
     * @return array 返回数组
     */
    public static function GetAllCustomTag($tempContent, $keyName = "icms") {
        $preg = "/\<$keyName(.*)\<\/$keyName>/imsU";
        preg_match_all($preg, $tempContent, $result, PREG_PATTERN_ORDER);
        return $result;
    }

    /**
     * 把对应ID的CMS标记替换成指定内容
     * @param string $tempcontent
     * @param type $id
     * @param type $replace
     * @param type $keyName
     * @return string
     */
    public static function ReplaceCMS($tempcontent, $id, $replace, $keyName = "cscms") {
        $beginstr = '<' . $keyName . ' id="' . $id . '"';
        $endstr = '</' . $keyName . '>';
        $temp1 = substr($tempcontent, 0, stripos($tempcontent, $beginstr));
        $x = stripos($tempcontent, $endstr, stripos($tempcontent, $beginstr));
        $temp2 = substr($tempcontent, $x + strlen($keyName) + 3);
        $tempcontent = $temp1 . $replace . $temp2;
        return $tempcontent;
    }

    /**
     * 把对应id的自定义标记替换成指定内容，可以识别标记的type属性
     * @param string $tempContent 模板
     * @param string $id 标记的id
     * @param string $replace 要替换的内容
     * @param string $keyName 标记名称
     * @param string $type 标记的type
     * @return string 替换后的内容
     */
    public static function ReplaceCustomTag($tempContent, $id, $replace, $keyName = "icms", $type = null)
    {
        if($type != null && strlen($type)>0){
            $beginString = '<' . $keyName . ' id="' . $id . '" type="' . $type . '"';
        }else{
            $beginString = '<' . $keyName . ' id="' . $id . '"';
        }
        $endString = '</' . $keyName . '>';
        $temp1 = substr($tempContent, 0, stripos($tempContent, $beginString));
        $x = stripos($tempContent, $endString, stripos($tempContent, $beginString));
        $temp2 = substr($tempContent, $x + strlen($keyName) + 3);
        $tempContent = $temp1 . $replace . $temp2;
        return $tempContent;
    }


    /**
     * 删除模板中所有cms标记或指定ID的标记
     * @param type $tempContent
     * @param type $id
     * @param type $keyName
     */
    public static function RemoveCMS(&$tempContent, $id = "", $keyName = "cscms") {
        if (empty($id)) {
            $patterns = "/\<$keyName(.*)\<\/$keyName>/imsU";
            $tempContent = preg_replace($patterns, "", $tempContent);
        } else {
            $patterns = "/\<$keyName id=\"$id\"(.*)\<\/$keyName>/imsU";
            $tempContent = preg_replace($patterns, "", $tempContent);
        }
    }

    /**
     * 替换页面中Select Checkbox Radio的选择值
     * @param type $TempContent
     * @param type $FieldName
     * @param type $FieldValue 
     */
    public static function ReplaceSelectControl(&$TempContent, $FieldName, $FieldValue) {

        $TempContent = str_ireplace("{sel_" . $FieldName . "_" . $FieldValue . "}", "selected", $TempContent);
        $TempContent = str_ireplace("{cb_" . $FieldName . "_" . $FieldValue . "}", "checked", $TempContent);
        $TempContent = str_ireplace("{rd_" . $FieldName . "_" . $FieldValue . "}", "checked", $TempContent);
    }

}

?>
