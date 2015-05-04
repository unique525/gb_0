<?php

/**
 * 前台搜索生成类
 * @category iCMS
 * @package iCMS_Rules_Gen_Search
 * @author yanjiuyuan
 */
class SearchPublicGen extends BasePublicGen implements IBasePublicGen
{

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenPublic()
    {
        $result = self::GenSearch();
        return $result;
    }

    /**
     * 生成搜索列表
     * @return string 搜索列表HTML
     */
    private function GenSearch()
    {

        $temp = Control::GetRequest("temp", "");
        $siteId = Control::GetRequest("site_id", "");
        $templateContent = parent::GetDynamicTemplateContent("site_search", $siteId);

        parent::ReplaceFirst($templateContent);

        //加载站点信息
        if ($siteId > 0) {
            $sitePublicData = new SitePublicData();
            $arrOne = $sitePublicData->GetOne($siteId);
            Template::ReplaceOne($templateContent, $arrOne);
        }

        $order = Control::GetRequest("order", 0);
        $searchKey = Control::GetRequest("search_key", "");
        $searchKey = urldecode($searchKey);
        $pageIndex = Control::GetRequest("p", 1);

        $allCount=0;
        $arrList = self::GetSearchList($allCount);
        print_r($arrList);

//        if ($pageIndex > 0) {
//            $tagId = "site_page_search";
//            $allCount = 0;
//            $tagContent = Template::GetCustomTagByTagId($tagId, $templateContent);
//            $pageSize = Template::GetParamValue($tagContent, "top");
//            $pageBegin = ($pageIndex - 1) * $pageSize;
//
//            $AllChannelId = "";
////            if ($channelId > 0) {
////                $AllChannelId = parent::GetOwnChannelIdAndChildChannelId($channelId);
////            }
//            $productPublicData = new ProductPublicData();
//            $arrList = $productPublicData->GetListForSearchPager($AllChannelId, $pageBegin, $pageSize, $allCount, $searchKey, 0, $order);
//            if (count($arrList) > 0) {
//                Template::ReplaceList($templateContent, $arrList, $tagId);
//                $styleNumber = 1;
//                $templateFileUrl = "pager/pager_style" . $styleNumber . ".html";
//                $templateName = "default";
//                $templatePath = "front_template";
//                $pagerTemplate = Template::Load($templateFileUrl, $templateName, $templatePath);
//                $isJs = FALSE;
//                $navUrl = "/default.php?mod=product&a=search&site_id=$siteId&p={0}&ps=$pageSize&order=$order&search_key=" . urlencode($searchKey) . "#product_list_anchor";
//                $jsFunctionName = "";
//                $jsParamList = "";
//                $pagerButton = Pager::ShowPageButton($pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex, $styleNumber, $isJs, $jsFunctionName, $jsParamList);
//                $templateContent = str_ireplace("{" . $tagId . "_pager_button}", $pagerButton, $templateContent);
//                $templateContent = str_ireplace("{" . $tagId . "_item_count}", $allCount, $templateContent);
//            } else {
//                Template::RemoveCustomTag($templateContent, $tagId);
//                $templateContent = str_ireplace("{" . $tagId . "_pager_button}", Language::Load("product", 101), $templateContent);
//                $templateContent = str_ireplace("{" . $tagId . "_item_count}", 0, $templateContent);
//            }
//            $templateContent = str_ireplace("{SearchKey}", urlencode($searchKey), $templateContent);
//        }
        $templateContent = parent::ReplaceTemplate($templateContent);
        parent::ReplaceEnd($templateContent);
        return $templateContent;
    }

    /**
     * 得到搜索数据
     * @param int $allCount 记录总数
     * @return array 搜素列表数据集
     */
    private function GetSearchList(&$allCount)
    {
        /**
         * search.php
         * cswb 搜索项目入口文件
         *
         * 该文件由 xunsearch PHP-SDK 工具自动生成，请根据实际需求进行修改
         * 创建时间：2012-04-27 10:15:44
         * 默认编码：UTF-8
         */
        // 加载 XS 入口文件
        require_once '/data/xunsearch/sdk/php/lib/XS.php';
        error_reporting(E_ALL ^ E_NOTICE);

        //
        // 支持的 GET 参数列表
        // q: 查询语句
        // m: 开启模糊搜索，其值为 yes/no
        // f: 只搜索某个字段，其值为字段名称，要求该字段的索引方式为 self/both
        // s: 排序字段名称及方式，其值形式为：xxx_ASC 或 xxx_DESC
        // p: 显示第几页，每页数量为 XSSearch::PAGE_SIZE 即 10 条
        // ie: 查询语句编码，默认为 UTF-8
        // oe: 输出编码，默认为 UTF-8
        // xml: 是否将搜索结果以 XML 格式输出，其值为 yes/no
        //
        // variables
        $eu = '';
        $q = Control::GetRequest("q", "");
        $m = Control::GetRequest("m", "");
        $f = Control::GetRequest("f", "");
        $s = Control::GetRequest("s", "");
        $p = Control::GetRequest("p", "");
        $ie = Control::GetRequest("ie", "");
        $oe = Control::GetRequest("oe", "");
        $syn = Control::GetRequest("syn", "");
        $xml = Control::GetRequest("xml", "");

        $ps=Control::GetRequest("ps", 20);

        // input encoding
        if ($s == null) {
            $s = "s_create_date_DESC";
        }
        if (!empty($ie) && !empty($q) && strcasecmp($ie, 'UTF-8')) {
            $q = XS::convert($q, $cs, $ie);
            $eu .= '&ie=' . $ie;
        }

        // output encoding
        if (!empty($oe) && strcasecmp($oe, 'UTF-8')) {

            function xs_output_encoding($buf)
            {
                return XS::convert($buf, $GLOBALS['oe'], 'UTF-8');
            }

            ob_start('xs_output_encoding');
            $eu .= '&oe=' . $oe;
        } else {
            $oe = 'UTF-8';
        }

        // recheck request parameters
        $q = get_magic_quotes_gpc() ? stripslashes($q) : $q;
        $f = empty($f) ? '_all' : $f;
        ${'m_check'} = ($m == 'yes' ? ' checked' : '');
        ${'syn_check'} = ($syn == 'yes' ? ' checked' : '');
        ${'f_' . $f} = ' checked';
        ${'s_' . $s} = ' selected';

        // base url
        $bu = $_SERVER['SCRIPT_NAME'] . '?q=' . urlencode($_GET['q']) . '&m=' . $m . '&f=' . $f . '&s=' . $s . $eu;

        // other variable maybe used in tpl
        $allCount = $total = $search_cost = 0;
        $docs = $related = $corrected = $hot = array();
        $error = $pager = '';
        $total_begin = microtime(true);

        // perform the search
        try {
            $xs = new XS('cswb');
            $search = $xs->search;
            $search->setCharset('UTF-8');

            if (empty($q)) {
                // just show hot query
                $hot = $search->getHotQuery();
            } else {
                // fuzzy search
                $search->setFuzzy($m === 'yes');

                // synonym search
                $search->setAutoSynonyms($syn === 'yes');

                // set query
                if (!empty($f) && $f != '_all') {
                    $search->setQuery($f . ':(' . $q . ')');
                } else {
                    $search->setQuery($q);
                }

                // set sort
                if (($pos = strrpos($s, '_')) !== false) {
                    $sf = substr($s, 0, $pos);
                    $st = substr($s, $pos + 1);
                    $search->setSort($sf, $st === 'ASC');
                }

                // set offset, limit
                $p = max(1, intval($p));
                //$n = XSSearch::PAGE_SIZE;
                $search->setLimit($ps, ($p - 1) * $ps);

                // get the result
                $search_begin = microtime(true);
                $docs = $search->search();
                $search_cost = microtime(true) - $search_begin;

                // get other result
                $allCount = $search->getLastCount();
                $total = $search->getDbTotal();

                if ($xml !== 'yes') {
                    // try to corrected, if resul too few
                    if ($allCount < 1 || $allCount < ceil(0.001 * $total))
                        $corrected = $search->getCorrectedQuery();
                    // get related query
                    $related = $search->getRelatedQuery();
                }

//                // gen pager
//                if ($count > $ps) {
//                    $pb = max($p - 5, 1);
//                    $pe = min($pb + 10, ceil($count / $ps) + 1);
//                    $pager = '';
//                    do {
//                        $pager .= ($pb == $p) ? '<strong>' . $p . '</strong>' : '<a href="' . $bu . '&p=' . $pb . '">[' . $pb . ']</a>';
//                    } while (++$pb < $pe);
//                }
            }
        } catch (XSException $e) {
            $error = strval($e);
        }

    // calculate total time cost
        $total_cost = microtime(true) - $total_begin;

        return $docs;
    }

}

?>
