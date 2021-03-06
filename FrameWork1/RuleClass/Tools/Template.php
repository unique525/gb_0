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
     * 模板标签类型：相关资讯列表 type="related_document_news_list"
     */
    const TAG_TYPE_RELATED_DOCUMENT_NEWS_LIST = "related_document_news_list";
    /**
     * 模板标签类型：图片轮换列表 type="document_news_pic_list"
     */
    const TAG_TYPE_DOCUMENT_NEWS_PIC_LIST = "document_news_pic_list";
    /**
     * 模板标签类型：图片轮换列表 type="pic_slider_list"
     */
    const TAG_TYPE_PIC_SLIDER_LIST = "pic_slider_list";
    /**
     * 模板标签类型：产品列表 type="product_list"
     */
    const TAG_TYPE_PRODUCT_LIST = "product_list";
    /**
     * 模板标签类型：产品参数类别列表 type="product_param_type_class_list"
     */
    const TAG_TYPE_PRODUCT_PARAM_TYPE_CLASS_LIST = "product_param_type_class_list";
    /**
     * 模板标签类型：产品参数类型列表 type="product_param_type_list"
     */
    const TAG_TYPE_PRODUCT_PARAM_TYPE_LIST = "product_param_type_list";
    /**
     * 模板标签类型：产品价格列表 type="product_price_list"
     */
    const TAG_TYPE_PRODUCT_PRICE_LIST = "product_price_list";
    /**
     * 模板标签类型：产品图片列表 type="product_pic_list"
     */
    const TAG_TYPE_PRODUCT_PIC_LIST = "product_pic_list";
    /**
     * 模板标签类型：浏览记录列表 type="user_explore_list"
     */
    const TAG_TYPE_USER_EXPLORE_LIST = "user_explore_list";
    /**
     * 模板标签类型：最新收藏记录列表 type="user_explore_list"
     */
    const TAG_TYPE_RECENT_USER_FAVORITE_LIST = "recent_user_favorite_list";
    /**
     * 模板标签类型：电子报文章列表 type="newspaper_article_list"
     */
    const TAG_TYPE_NEWSPAPER_ARTICLE_LIST = "newspaper_article_list";
    /**
     * 模板标签类型：电子报文章图片列表 type="newspaper_article_pic_list"
     */
    const TAG_TYPE_NEWSPAPER_ARTICLE_PIC_LIST = "newspaper_article_pic_list";
    /**
     * 模板标签类型：电子报文章图片列表(轮换) type="newspaper_article_pic_list_slider"
     */
    const TAG_TYPE_NEWSPAPER_ARTICLE_PIC_LIST_SLIDER = "newspaper_article_pic_list_slider";
    /**
     * 模板标签类型：投票调查列表 type="vote_list"
     */
    const TAG_TYPE_VOTE_LIST = "vote_list";
    /**
     * 模板标签类型：投票调查题目列表 type="vote_item_list"
     */
    const TAG_TYPE_VOTE_ITEM_LIST = "vote_item_list";
    /**
     * 论坛 主题列表
     */
    const TAG_TYPE_FORUM_TOPIC_LIST = "forum_topic_list";
    /**
     * 模板标签类型： 活动列表
     */
    const TAG_TYPE_ACTIVITY_LIST = "activity_list";
    /**
     * 外部接口类型： 资讯列表
     */
    const TAG_TYPE_INTERFACE_CONTENT_LIST = "interface_content_list";




    /**
     * 模板标签类型： 比赛列表
     */
    const TAG_TYPE_MATCH_LIST = "match_list";
    /**
     * 模板标签类型： 球队列表
     */
    const TAG_TYPE_TEAM_LIST = "team_list";
    /**
     * 模板标签类型： 队员列表
     */
    const TAG_TYPE_MEMBER_LIST = "member_list";
    /**
     * 模板标签类型： 进球列表
     */
    const TAG_TYPE_GOAL_LIST = "goal_list";
    /**
     * 模板标签类型： 红黄牌列表
     */
    const TAG_TYPE_RED_YELLOW_CARD_LIST = "red_yellow_card_list";
    /**
     * 模板标签类型： 替补事件列表
     */
    const TAG_TYPE_MEMBER_CHANGE_LIST = "member_change_list";
    /**
     * 模板标签类型： 其他事件列表
     */
    const TAG_TYPE_OTHER_EVENT_LIST = "other_event_list";
    /**
     * 模板标签类型： 赛场事件（包括进球，红牌黄牌，上下场和其他特殊事件）列表

    const TAG_TYPE_EVENT_LIST = "event_list";*/


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
        $templateContent = file_get_contents($filePath);
        //去掉BOM
        if (preg_match('/^\xEF\xBB\xBF/', $templateContent)) {
            $templateContent = substr($templateContent, 3);
        }
        return $templateContent;
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
     * @param string $templateContent 要替换的模板内容，指针型参数，直接输出结果
     * @param array $arrList 用来替换到模板中的集合内容
     * @param string $tagId 替换标签的id
     * @param string $tagName 替换标签名称
     * @param array $arrChildList 子表数据（默认为空，不进行子父表处理）
     * @param string $tableIdName 主表主键名称
     * @param string $parentIdName 父子键名称
     * @param array $arrThirdList 第三级子表数据（默认为空，不进行第三级子父表处理）
     * @param string $thirdTableIdName 第三级表主键名称
     * @param string $thirdParentIdName 第二级子键名称
     * @param string $childArrayFieldName 第二级 可以转化为数组的字符串字段名称，用在下级数据缓存在本级字段的情况
     * @param string $thirdArrayFieldName 第三级 可以转化为数组的字符串字段名称，用在下级数据缓存在本级字段的情况
     */
    public static function ReplaceList(
        &$templateContent,
        $arrList,
        $tagId,
        $tagName = self::DEFAULT_TAG_NAME,
        $arrChildList = null,
        $tableIdName = null,
        $parentIdName = "ParentId",
        $arrThirdList = null,
        $thirdTableIdName = null,
        $thirdParentIdName = "ParentId",
        $childArrayFieldName = "",
        $thirdArrayFieldName = ""
    )
    {
        if (stripos($templateContent, $tagName) > 0
            && stripos($templateContent, $tagId) > 0
        ) {
            if ($arrList != null && count($arrList) > 0) {
                $beginTagString = '<' . $tagName . ' id="' . $tagId . '"';
                $endTagString = '</' . $tagName . '>';
                $listTempContent = substr($templateContent, strpos($templateContent, $beginTagString));
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

                        //Child标题最大字符数
                        $childRowTitleCount = self::GetParamIntValue($doc, $tagName, "child_row_title_count");
                        if ($childRowTitleCount <= 0) {
                            $childRowTitleCount = self::GetParamIntValue($doc, $tagName, "child_title");
                        }

                        //三级表标题最大字符数
                        $thirdRowTitleCount = self::GetParamIntValue($doc, $tagName, "third_row_title_count");
                        if ($thirdRowTitleCount <= 0) {
                            $thirdRowTitleCount = self::GetParamIntValue($doc, $tagName, "third_title");
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
                        //读取子表模板
                        if( $arrChildList != null ){
                            $childTempContent = self::GetNodeValue($doc, "child", $tagName);
                        }else{
                            $childTempContent = "";
                        }
                        //读取二级数组字符串模板
                        if( strlen($childArrayFieldName)>0 ){
                            $childInfoStringTempContent = self::GetNodeValue($doc, "child_info_string", $tagName);
                        }else{
                            $childInfoStringTempContent = "";
                        }
                        //读取三级模板
                        if( $arrThirdList != null ){
                            $thirdTempContent = self::GetNodeValue($doc, "third", $tagName);
                        }else{
                            $thirdTempContent = "";
                        }



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

                        //子表标题最大字符数
                        $childRowTitleCount = intval($arrayXml[0]['attributes']['CHILD_ROW_TITLE_COUNT']);
                        if ($childRowTitleCount <= 0) {
                            $childRowTitleCount = intval($arrayXml[0]['attributes']['CHILD_TITLE']);
                        }
                        //三级表标题最大字符数
                        $thirdRowTitleCount = intval($arrayXml[0]['attributes']['THIRD_ROW_TITLE_COUNT']);
                        if ($thirdRowTitleCount <= 0) {
                            $thirdRowTitleCount = intval($arrayXml[0]['attributes']['THIRD_TITLE']);
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

                        //读取子表模板
                        if( $arrChildList != null ){
                            $childTempContent = self::GetNodeValueForSax($arrayXml, "CHILD");
                        }else{
                            $childTempContent = "";
                        }

                        //读取二级数组字符串模板
                        if( strlen($childArrayFieldName)>0 ){
                            $childInfoStringTempContent = self::GetNodeValueForSax($arrayXml, "CHILD_INFO_STRING");
                        }else{
                            $childInfoStringTempContent = "";
                        }



                        //读取三级模板
                        if( $arrThirdList != null ){
                            $thirdTempContent = self::GetNodeValueForSax($arrayXml, "THIRD");
                        }else{
                            $thirdTempContent = "";
                        }
                    }
                    ///////////////////////////////////////////////////////////////////
                    $sb = "";

                    //处理头段
                    for ($i = 0; $i < $headerRowCount; $i++) {
                        $columns = $arrList[$i];
                        $list = $headerTempContent;
                        $itemType = "header";
                        $list = self::ReplaceListItem(
                            $i,
                            $type,
                            $itemRowTitleCount,
                            $itemRowIntroCount,
                            $headerRowTitleCount,
                            $footerRowTitleCount,
                            $childRowTitleCount,
                            $columns,
                            $list,
                            $itemType
                        );
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
                        $list = self::ReplaceListItem(
                            $i,
                            $type,
                            $itemRowTitleCount,
                            $itemRowIntroCount,
                            $headerRowTitleCount,
                            $footerRowTitleCount,
                            $childRowTitleCount,
                            $columns,
                            $list,
                            $itemType
                        );

                        //处理子表数据
                        $sbChild = "";
                        $childCount = 0; //匹配的子节点数量

                        if(count($arrChildList)>0){

                            //根级id赋值
                            $arrList[$i]["FirstId"] = $arrList[$i][$tableIdName];


                            $childNumber = 0;

                            for($j = 0; $j<count($arrChildList); $j++){


                                $arrChildList[$j]["FirstId"] = $arrList[$i][$tableIdName];

                                $listOfChild = $childTempContent;
                                $columnsOfChild = $arrChildList[$j];
                                if($arrList[$i][$tableIdName] == $arrChildList[$j][$parentIdName]){

                                    $childCount++;

                                    $listOfChild = self::ReplaceListItem(
                                        $j,
                                        $type,
                                        $itemRowTitleCount,
                                        $itemRowIntroCount,
                                        $headerRowTitleCount,
                                        $footerRowTitleCount,
                                        $childRowTitleCount,
                                        $columnsOfChild,
                                        $listOfChild,
                                        $itemType
                                    );

                                    //处理可转化的缓存子段数据
                                    $sbChildInfoString = "";
                                    if(strlen($childArrayFieldName)>0){
                                        //这是一个已经编码的数组字符串，存储了一个数组 {id,title}
                                        $infoString = $arrChildList[$j][$childArrayFieldName];
                                        //把字符串反解成数组
                                        $arrInfoString = Format::UnFormatInfoString($infoString);
                                        for( $info_i = 0; $info_i < count($arrInfoString); $info_i++){

                                            $listOfChildInfoString = $childInfoStringTempContent;
                                            $columnsOfChildInfoString = $arrInfoString[$info_i];
                                            $listOfChildInfoString = self::ReplaceListItem(
                                                $info_i,
                                                $type,
                                                $itemRowTitleCount,
                                                $itemRowIntroCount,
                                                $headerRowTitleCount,
                                                $footerRowTitleCount,
                                                $thirdRowTitleCount,
                                                $columnsOfChildInfoString,
                                                $listOfChildInfoString,
                                                $itemType
                                            );

                                            $sbChildInfoString = $sbChildInfoString . $listOfChildInfoString;
                                        }

                                    }

                                    //处理三级数据
                                    $sbThird = "";
                                    if(count($arrThirdList)>0){

                                        for($k = 0; $k<count($arrThirdList); $k++){
                                            $arrThirdList[$k]["SecondId"] = $arrChildList[$j][$tableIdName];
                                            $arrThirdList[$k]["FirstId"] = $arrList[$i][$tableIdName];

                                            $listOfThird = $thirdTempContent;
                                            $columnsOfThird = $arrThirdList[$k];

                                            if($arrChildList[$j][$thirdTableIdName] ==
                                                $arrThirdList[$k][$thirdParentIdName]
                                            ){

                                                $listOfThird = self::ReplaceListItem(
                                                    $k,
                                                    $type,
                                                    $itemRowTitleCount,
                                                    $itemRowIntroCount,
                                                    $headerRowTitleCount,
                                                    $footerRowTitleCount,
                                                    $thirdRowTitleCount,
                                                    $columnsOfThird,
                                                    $listOfThird,
                                                    $itemType
                                                );

                                                $sbThird = $sbThird . $listOfThird;
                                            }


                                        }



                                    }

                                    $childNumber++;
                                    $listOfChild = str_ireplace("{child_info_string}", $sbChildInfoString, $listOfChild);
                                    $listOfChild = str_ireplace("{third}", $sbThird, $listOfChild);
                                    $listOfChild = str_ireplace("{c_child_no}", $childNumber, $listOfChild);


                                    $sbChild = $sbChild . $listOfChild;
                                }

                            }


                        }


                        $list = str_ireplace("{child}", $sbChild, $list);
                        $list = str_ireplace("{child_count}", $childCount, $list);

                        $list = str_ireplace("{c_all_count}", count($arrList), $list);
                        $sb = $sb . $list;


                        //每隔一定主段条数附加分割线，最底部分割线不附加
                        //一定要放最后加载
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
                            $list = self::ReplaceListItem(
                                $i,
                                $type,
                                $itemRowTitleCount,
                                $itemRowIntroCount,
                                $headerRowTitleCount,
                                $footerRowTitleCount,
                                $childRowTitleCount,
                                $columns,
                                $list,
                                $itemType
                            );
                            $list = str_ireplace("{c_all_count}", count($arrList), $list);
                            $sb = $sb . $list;
                        }
                    }

                    $result = $sb;
                    $templateContent = str_replace($listTempContent, $result, $templateContent);
                }
            } else { //替换模板内容为空
                self::ReplaceCustomTag($templateContent, $tagId, "", $tagName);
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
     * @param int $childRowTitleCount 子记录列表项目标题最大字符数
     * @param array $columns 字段数组
     * @param string $listTemplate 要替换的列表模板内容
     * @param string $itemType 标签的类型 header item footer
     * @return mixed
     */
    private static function ReplaceListItem(
        $i,
        $tagType,
        $itemRowTitleCount,
        $itemRowIntroCount,
        $headerRowTitleCount,
        $footerRowTitleCount,
        $childRowTitleCount,
        $columns,
        $listTemplate,
        $itemType
    )
    {
        $listTemplate = str_ireplace("{c_no}", $i + 1, $listTemplate); //加入输出序号

        if (strtolower($tagType) == self::TAG_TYPE_DOCUMENT_NEWS_LIST)
        {
            self::FormatDocumentNewsRow(
                $listTemplate,
                $columns
            );
        }

        if (strtolower($tagType) == self::TAG_TYPE_ACTIVITY_LIST)
        {
            self::FormatActivityRow(
                $listTemplate,
                $columns
            );
        }


        if (strtolower($tagType) === self::TAG_TYPE_RELATED_DOCUMENT_NEWS_LIST)
        {
            self::FormatDocumentNewsRow(
                $listTemplate,
                $columns
            );
        }



        foreach ($columns as $columnName => $columnValue) {


            if (strtolower($tagType) === self::TAG_TYPE_DOCUMENT_NEWS_LIST)
            {
                self::FormatDocumentNewsColumnValue(
                    $columnName,
                    $columnValue,
                    $listTemplate,
                    $itemRowTitleCount,
                    $itemRowIntroCount,
                    $headerRowTitleCount,
                    $footerRowTitleCount,
                    $childRowTitleCount,
                    $itemType
                );
            }
            else if (strtolower($tagType) === self::TAG_TYPE_PRODUCT_LIST)
            {
                self::FormatProductColumnValue(
                    $columnName,
                    $columnValue,
                    $listTemplate,
                    $itemRowTitleCount,
                    $itemRowIntroCount,
                    $headerRowTitleCount,
                    $footerRowTitleCount,
                    $itemType
                );
            }
            else if (strtolower($tagType) === self::TAG_TYPE_ACTIVITY_LIST)
            {
                self::FormatActivityColumnValue(
                    $columnName,
                    $columnValue,
                    $listTemplate,
                    $itemRowTitleCount,
                    $headerRowTitleCount,
                    $footerRowTitleCount,
                    $itemType
                );
            }

            //公用替换
            self::FormatColumnValue(
                $columnName,
                $columnValue,
                $listTemplate,
                $itemRowTitleCount,
                $itemRowIntroCount,
                $headerRowTitleCount,
                $footerRowTitleCount,
                $childRowTitleCount,
                $itemType
            );
        }
        return $listTemplate;
    }


    /**
     * 为资讯格式化所有行的内容
     * @param string $listTemplate 列表模板
     * @param array $columns 列数组
     */
    private static function FormatDocumentNewsRow(&$listTemplate, $columns){

        if (isset($columns["DirectUrl"]) && $columns["DirectUrl"] != '') { //链接文档
            $listTemplate = str_ireplace("{c_DocumentNewsUrl}", $columns["DirectUrl"], $listTemplate); //直接输出url
        } else {
            $listTemplate = str_ireplace("{c_DocumentNewsUrl}",
                "/h/{f_ChannelId}/{f_year}{f_month}{f_day}/{f_DocumentNewsId}.html", $listTemplate);
        }

    }


    /**
     * 为活动格式化所有行的内容
     * @param string $listTemplate 列表模板
     * @param array $columns 列数组
     */
    private static function FormatActivityRow(&$listTemplate, $columns){

        if (isset($columns["DirectUrl"]) && $columns["DirectUrl"] != '') { //链接文档
            $listTemplate = str_ireplace("{c_ActivityUrl}", $columns["DirectUrl"], $listTemplate); //直接输出url
        } else {
            $listTemplate = str_ireplace("{c_ActivityUrl}",
                "/default.php?mod=activity&a=detail&temp=activity_detail&activity_id={f_ActivityId}"
                , $listTemplate);
        }

    }


    /**
     * 通用的格式化行内容
     * @param string $columnName 字段名称
     * @param string $columnValue 字段值
     * @param string $templateContent 要替换的模板内容，指针型参数，直接输出结果
     * @param int $itemRowTitleCount 主列表项目标题最大字符数
     * @param int $itemRowIntroCount 主列表项目简介最大字符数
     * @param int $headerRowTitleCount 顶部列表项目标题最大字符数
     * @param int $footerRowTitleCount 底部列表项目标题最大字符数
     * @param int $childRowTitleCount 子记录列表项目标题最大字符数
     * @param string $itemType 标签的类型 header item footer
     */
    private static function FormatColumnValue(
        $columnName,
        $columnValue,
        &$templateContent,
        $itemRowTitleCount,
        $itemRowIntroCount,
        $headerRowTitleCount,
        $footerRowTitleCount,
        $childRowTitleCount,
        $itemType
    )
    {
        if (strtolower($columnName) == "state") {
            $templateContent = str_ireplace("{f_" . $columnName . "_value}", $columnValue, $templateContent);
        }


        $pos = stripos(strtolower($columnName), strtolower("PublishDate"));
        if ($pos !== false) {
            $date1 = explode(' ', $columnValue);
            if(count($date1)>0){
                $date2 = explode('-', $date1[0]);
                if(count($date2)>2){
                    $year = $date2[0];
                    $month = $date2[1];
                    $day = $date2[2];
                    $templateContent = str_ireplace("{f_year}", $year, $templateContent);
                    $templateContent = str_ireplace("{f_month}", $month, $templateContent);
                    $templateContent = str_ireplace("{f_day}", $day, $templateContent);
                }
            }
        }

        $pos = stripos(strtolower($columnName), strtolower("CreateDate"));
        if ($pos !== false) {
            $date1 = explode(' ', $columnValue);
            if(!empty($date1)){
                $date2 = explode('-', $date1[0]);

                if(count($date2)>=3){
                    $year = $date2[0];
                    $month = $date2[1];
                    $day = $date2[2];

                    $templateContent = str_ireplace("{f_c_year}", $year, $templateContent);
                    $templateContent = str_ireplace("{f_c_month}", $month, $templateContent);
                    $templateContent = str_ireplace("{f_c_day}", $day, $templateContent);
                }

            }


        }
        $templateContent = str_ireplace("{f_" . $columnName . "}", $columnValue, $templateContent);

        //处理常规去掉HTML代码的值
        $templateContent = str_ireplace("{f_" . $columnName . "_no_html}", strip_tags($columnValue), $templateContent);

        //处理下拉菜单的默认值

        $selectedOption = '<script type="text/javascript">
                    $("#f_' . $columnName . '").find("option[value=\'' . $columnValue . '\']").attr("selected",true);
                </script>';

        $templateContent = str_ireplace("{" . "s_" . $columnName . "}", $selectedOption, $templateContent);

        $checkedOption = '<script type="text/javascript">
                    $("#f_' . $columnName . '").find("option[value=\'' . $columnValue . '\']").attr("checked",true);
                </script>';

        $templateContent = str_ireplace("{" . "r_" . $columnName . "}", $checkedOption, $templateContent);

        if (intval($columnValue) === 1) {
            $templateContent = str_ireplace("{c_" . $columnName . "}", 'checked="checked"', $templateContent);
        }

    }



    /**
     * 为资讯格式化行内容
     * @param string $columnName 字段名称
     * @param string $columnValue 字段值
     * @param string $templateContent 要替换的模板内容，指针型参数，直接输出结果
     * @param int $itemRowTitleCount 主列表项目标题最大字符数
     * @param int $itemRowIntroCount 主列表项目简介最大字符数
     * @param int $headerRowTitleCount 顶部列表项目标题最大字符数
     * @param int $footerRowTitleCount 底部列表项目标题最大字符数
     * @param int $childRowTitleCount 子项列表项目标题最大字符数
     * @param string $itemType 标签的类型 header item footer
     */
    private static function FormatDocumentNewsColumnValue(
        $columnName,
        $columnValue,
        &$templateContent,
        $itemRowTitleCount,
        $itemRowIntroCount,
        $headerRowTitleCount,
        $footerRowTitleCount,
        $childRowTitleCount,
        $itemType = "item"
    )
    {
        $pos = stripos(strtolower($columnName), strtolower("ShowDate"));
        if ($pos !== false) {
            $date1 = explode(' ', $columnValue);
            $date2 = explode('-', $date1[0]);
            $year = $date2[0];
            $month = $date2[1];
            $day = $date2[2];
            $templateContent = str_ireplace("{f_show_year}", $year, $templateContent);
            $templateContent = str_ireplace("{f_show_month}", $month, $templateContent);
            $templateContent = str_ireplace("{f_show_day}", $day, $templateContent);
        }

        //格式化标题
        $pos = stripos(strtolower($columnName), "DocumentNewsTitle");

        if ($pos !== false) {
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
            } else if ($itemType == "child") {
                if (intval($childRowTitleCount) > 0) {
                    //截断字符
                    $columnValue = Format::ToShort($columnValue, $childRowTitleCount);
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

        //处理内容
        $pos = stripos(strtolower($columnName), strtolower("DocumentNewsContent"));
        if ($pos !== false) {
            $columnValue = str_ireplace("../upload/document_news", "/upload/document_news", $columnValue);
            if (intval($itemRowIntroCount) > 0) {
                //截断字符
                $columnValue = Format::ToShort(strip_tags($columnValue), $itemRowIntroCount);
            }
            $templateContent = str_ireplace("{f_document_news_content_no_html}", strip_tags($columnValue), $templateContent);
        }
        $columnValue = str_ireplace("'", "&#039;", $columnValue);
        $templateContent = str_ireplace("{f_" . $columnName . "}", $columnValue, $templateContent);
    }

    /**
     * 为活动格式化行内容
     * @param string $columnName 字段名称
     * @param string $columnValue 字段值
     * @param string $templateContent 要替换的模板内容，指针型参数，直接输出结果
     * @param int $itemRowTitleCount 主列表项目标题最大字符数
     * @param int $itemRowIntroCount 主列表项目简介最大字符数
     * @param int $headerRowTitleCount 顶部列表项目标题最大字符数
     * @param int $footerRowTitleCount 底部列表项目标题最大字符数
     * @param string $itemType 标签的类型 header item footer
     */
    private static function FormatActivityColumnValue($columnName, $columnValue, &$templateContent, $itemRowTitleCount, $itemRowIntroCount, $headerRowTitleCount, $footerRowTitleCount, $itemType = "item")
    {
        $pos = stripos(strtolower($columnName), "ActivityContent");
        if ($pos !== false) {
            if (intval($itemRowIntroCount) > 0) {
                //截断字符
                $columnValue = Format::ToShort($columnValue, $itemRowIntroCount);
            } else {
                $columnValue = str_replace(array('&nbsp;', "\r", "\n", '\'', '"'), '', Format::ToShort(strip_tags($columnValue), $itemRowIntroCount));
            }
        }

        $pos = stripos(strtolower($columnName), "PublishDate");
        if ($pos !== false) {
            $year = '';
            $month = '';
            $day = '';
            $date1 = explode(' ', $columnValue);
            if(count($date1)>0){
                $date2 = explode('-', $date1[0]);
                if(count($date2)>2){
                    $year = $date2[0];
                    $month = $date2[1];
                    $day = $date2[2];
                }
            }


            $templateContent = str_ireplace("{f_publish_year}", $year, $templateContent);
            $templateContent = str_ireplace("{f_publish_month}", $month, $templateContent);
            $templateContent = str_ireplace("{f_publish_day}", $day, $templateContent);
        }

        $pos = stripos(strtolower($columnName), "ApplyEndDate");
        if ($pos !== false) {

            $date1 = explode(' ', $columnValue);
            $date2 = explode('-', $date1[0]);
            $year = $date2[0];
            $month = $date2[1];
            $day = $date2[2];

            $templateContent = str_ireplace("{f_apply_end_year}", $year, $templateContent);
            $templateContent = str_ireplace("{f_apply_end_month}", $month, $templateContent);
            $templateContent = str_ireplace("{f_apply_end_day}", $day, $templateContent);
        }

        $pos = stripos(strtolower($columnName), "BeginDate");
        if ($pos !== false) {

            $date1 = explode(' ', $columnValue);
            $date2 = explode('-', $date1[0]);
            $year = $date2[0];
            $month = $date2[1];
            $day = $date2[2];

            $templateContent = str_ireplace("{f_begin_year}", $year, $templateContent);
            $templateContent = str_ireplace("{f_begin_month}", $month, $templateContent);
            $templateContent = str_ireplace("{f_begin_day}", $day, $templateContent);
        }

        $pos = stripos(strtolower($columnName), "EndDate");
        if ($pos !== false) {

            $date1 = explode(' ', $columnValue);
            $date2 = explode('-', $date1[0]);
            $year = $date2[0];
            $month = $date2[1];
            $day = $date2[2];

            $templateContent = str_ireplace("{f_end_year}", $year, $templateContent);
            $templateContent = str_ireplace("{f_end_month}", $month, $templateContent);
            $templateContent = str_ireplace("{f_end_day}", $day, $templateContent);
        }
        $templateContent = str_ireplace("{f_" . $columnName . "}", $columnValue, $templateContent);
    }

    /**
     * 为product 格式化字段
     * @param string $columnName 字段名称
     * @param string $columnValue 字段值
     * @param string $templateContent 要替换的模板内容，指针型参数，直接输出结果
     * @param int $itemRowTitleCount 主列表项目标题最大字符数
     * @param int $itemRowIntroCount 主列表项目简介最大字符数
     * @param int $headerRowTitleCount 顶部列表项目标题最大字符数
     * @param int $footerRowTitleCount 底部列表项目标题最大字符数
     * @param string $itemType 标签的类型 header item footer
     */
    private static function FormatProductColumnValue($columnName, $columnValue, &$templateContent, $itemRowTitleCount, $itemRowIntroCount, $headerRowTitleCount, $footerRowTitleCount, $itemType = "item")
    {
        $pos = stripos(strtolower($columnName), "SalePrice");
        if ($pos !== false) {
            if (is_numeric($columnValue) && $columnValue > 0)
                $templateContent = str_ireplace("{f_sale_price_show}", number_format($columnValue / 10000, 2, '.', ''), $templateContent);
            else
                $templateContent = str_ireplace("{f_sale_price_show}", "", $templateContent);
        }

        if (strtolower($columnName) === "ProductContent") {
            $columnValue = str_ireplace("../upload/product", "/upload/product", $columnValue);
        }

        $columnValue = str_ireplace("'", "&#039;", $columnValue);
        $templateContent = str_ireplace("{f_" . $columnName . "}", $columnValue, $templateContent);
    }

    /**
     * 替换详细信息页面
     * @param string $templateContent 要处理的模板
     * @param array $arrInfo 要替换的数组数据
     * @param bool $isArrayList 默认为false，使用一维数组存储数据
     * @param bool $isForTemplateManage 是否后台模板管理中使用，默认false，非后台模板管理使用
     */
    public static function ReplaceOne(&$templateContent, $arrInfo, $isArrayList = false, $isForTemplateManage = false)
    {
        if (count($arrInfo) > 0) {
            if (!$isArrayList) { //使用一维数组存储数据
                self::_ReplaceOne($templateContent, $arrInfo, $isForTemplateManage);
            } else { //使用二维数组存储数据
                for ($i = 0; $i < count($arrInfo); $i++) {
                    $columns = $arrInfo[$i];
                    self::_ReplaceOne($templateContent, $columns, $isForTemplateManage);
                }
            }
        }
    }


    /**
     * 处理详细信息子方法
     * @param string $templateContent 要处理的模板
     * @param array $arrOne 要替换的数组数据
     * @param bool $isForTemplateManage 是否后台模板管理中使用，默认false，非后台模板管理使用
     */
    private static function _ReplaceOne(&$templateContent, $arrOne, $isForTemplateManage = false)
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
                if($isForTemplateManage == false){

                    if (strtolower($columnName) === strtolower("DocumentNewsContent")) {
                        $columnValue = str_ireplace('"upload/document_news', '"/upload/document_news', $columnValue);
                        $columnValue = str_ireplace("'upload/document_news", "'/upload/document_news", $columnValue);
                    }

                    if (strtolower($columnName) === strtolower("DocumentNewsContent")) {
                        $columnValue = str_ireplace('"upload/newspaper_article', '"/upload/newspaper_article', $columnValue);
                        $columnValue = str_ireplace("'upload/newspaper_article", "'/upload/newspaper_article", $columnValue);
                    }
                }



                if (strtolower($columnName) === strtolower("DocumentNewsTitle")) {
                    $columnValue = str_ireplace('"', "&quot;", $columnValue);
                }

                //分拆处理发布时间字段
                $pos = stripos(strtolower($columnName), strtolower("PublishDate"));
                if ($pos !== false) {
                    if(!empty($columnValue)){
                        $date1 = explode(' ', $columnValue);
                        if(!empty($date1)){
                            $date2 = explode('-', $date1[0]);
                            if(!empty($date2)){
                                $year = $date2[0];
                                $month = $date2[1];
                                $day = $date2[2];

                                $templateContent = str_ireplace("{publish_year}", $year, $templateContent);
                                $templateContent = str_ireplace("{publish_month}", $month, $templateContent);
                                $templateContent = str_ireplace("{publish_day}", $day, $templateContent);
                            }
                        }
                    }

                }

                //分拆处理显示时间字段
                $pos = stripos(strtolower($columnName), strtolower("ShowDate"));
                if ($pos !== false) {
                    $date1 = explode(' ', $columnValue);
                    $date2 = explode('-', $date1[0]);
                    $year = $date2[0];
                    $month = $date2[1];
                    $day = $date2[2];

                    $templateContent = str_ireplace("{show_year}", $year, $templateContent);
                    $templateContent = str_ireplace("{show_month}", $month, $templateContent);
                    $templateContent = str_ireplace("{show_day}", $day, $templateContent);
                }


                //处理常规的值
                if ($isForTemplateManage) { //是否是后台模板系统使用
                    $preManage = "b_";
                } else {
                    $preManage = "";
                }


                if (strtolower($columnName) === strtolower("OpenComment")) {
                    $templateContent = str_ireplace("{" . $preManage . "s_" . $columnName . "_" . $columnValue . "}", "selected=\"selected\"", $templateContent);
                    if (intval($columnValue) === 0) { //关闭评论
                        $replaceColumnValue = "display:none;";
                    } else {
                        $replaceColumnValue = "";
                    }
                    $templateContent = str_ireplace("{" . $columnName . "}", $replaceColumnValue, $templateContent);
                }

                $templateContent = str_ireplace("{" . $preManage . $columnName . "}", $columnValue, $templateContent);

                //处理常规去掉HTML代码的值
                $templateContent = str_ireplace("{" . $preManage . $columnName . "_no_html}", strip_tags($columnValue), $templateContent);

                //处理下拉菜单的默认值

                $selectedOption = '<script type="text/javascript">
                    $("#f_' . $columnName . '").find("option[value=\'' . $columnValue . '\']").attr("selected",true);
                </script>';

                $templateContent = str_ireplace("{" . $preManage . "s_" . $columnName . "}", $selectedOption, $templateContent);

                $checkedOption = '<script type="text/javascript">
                    $("input[name=f_' . $columnName . '][value='.$columnValue.']").attr("checked","checked");
                </script>';

                $templateContent = str_ireplace("{" . $preManage . "r_" . $columnName . "}", $checkedOption, $templateContent);

                if (intval($columnValue) === 1) {
                    $templateContent = str_ireplace("{c_" . $columnName . "}", 'checked="checked"', $templateContent);
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
     * @param string $templateContent 要替换的模板内容
     * @param string $tagName 标签名称
     * @return array 返回数组
     */
    public static function GetAllCustomTag($templateContent, $tagName = self::DEFAULT_TAG_NAME)
    {
        $preg = "/\<$tagName(.*)\<\/$tagName>/imsU";
        preg_match_all($preg, $templateContent, $result, PREG_PATTERN_ORDER);
        return $result;
    }

    /**
     * 取得页面中第一个匹配TagId的标记段
     * @param string $tagId 替换标签的id
     * @param string $templateContent 要取的模板内容
     * @param string $tagName 标签名称
     * @return string 返回标记段内容
     */
    public static function GetCustomTagByTagId($tagId, $templateContent, $tagName = self::DEFAULT_TAG_NAME){
        $result = "";
        $preg = "/\<$tagName id=\"$tagId\"(.*)\<\/$tagName>/imsU";
        preg_match_all($preg, $templateContent, $matches);
        if(count($matches)>0){
            if(count($matches[0])>0){
                $result = $matches[0][0];
            }
        }
        return $result;
    }

    /**
     * 把对应id的自定义标签替换成指定内容，可以识别标记的type属性
     * @param string $templateContent 要替换的模板内容
     * @param string $tagId 标签的id
     * @param string $replaceContent 要替换的内容
     * @param string $tagName 标签名称
     * @param string $tagType 标签的type
     * @return string 替换后的内容
     */
    public static function ReplaceCustomTag(&$templateContent, $tagId, $replaceContent, $tagName = self::DEFAULT_TAG_NAME, $tagType = null)
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
            $templateContent = preg_replace($patterns, $replaceContent, $templateContent, 1);
        } else if (!empty($tagId)) {
            $patterns = "/\<$tagName id=\"$tagId\"(.*)\<\/$tagName>/imsU";
            //    /\<icms id="newspaper_page_109"(.*)\<\/icms>/imsU
            $templateContent = preg_replace($patterns, $replaceContent, $templateContent, 1);
        } else {
            $patterns = "/\<$tagName(.*)\<\/$tagName>/imsU";
            $templateContent = preg_replace($patterns, $replaceContent, $templateContent, 1);
        }
        return $templateContent;

    }


    /**
     * 删除模板中所有自定义标签或指定ID的标签
     * @param string $templateContent 要替换的模板内容，指针型参数，直接输出结果
     * @param string $tagId 替换标签的id，如果为空，则删除所有自定义标签
     * @param string $tagName 替换标签名称
     */
    public static function RemoveCustomTag(&$templateContent, $tagId = "", $tagName = self::DEFAULT_TAG_NAME)
    {
        if (empty($tagId)) {
            $patterns = "/\<$tagName(.*)\<\/$tagName>/imsU";
            $templateContent = preg_replace($patterns, "", $templateContent);
        } else {
            $patterns = "/\<$tagName id=\"$tagId\"(.*)\<\/$tagName>/imsU";
            $templateContent = preg_replace($patterns, "", $templateContent);
        }
    }

    /**
     * 替换页面中Select Checkbox Radio的选择值
     * @param string $templateContent 要替换的模板内容，指针型参数，直接输出结果
     * @param string $fieldName 字段名
     * @param string $fieldValue 字段值
     */
    public static function ReplaceSelectControl(&$templateContent, $fieldName, $fieldValue)
    {
        $templateContent = str_ireplace("{sel_" . $fieldName . "_" . $fieldValue . "}", "selected", $templateContent);
        $templateContent = str_ireplace("{cb_" . $fieldName . "_" . $fieldValue . "}", "checked", $templateContent);
        $templateContent = str_ireplace("{rd_" . $fieldName . "_" . $fieldValue . "}", "checked", $templateContent);
    }

}

?>
