<?php
/**
 * Created by PhpStorm.
 * User: zhangchi
 * Date: 15-8-14
 * Time: 下午6:12
 */

class SearchClientGen extends BaseClientGen implements IBaseClientGen
{

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenClient()
    {
        $function = Control::GetRequest("f", "");

        switch ($function) {

            default :
                $result = self::GenSearch();
                break;

        }
        $result = str_ireplace("{function}", $function, $result);
        return $result;
    }

    /**
     * 生成搜索列表
     * @return string 搜索列表HTML
     */
    private function GenSearch()
    {
        $result = "[{}]";

        parent::ReplaceFirst($templateContent);

        $searchKey = Control::PostOrGetRequest("search_key", "");
        $pageSize = intval(Control::GetRequest("ps", 20));
        $pageIndex = intval(Control::GetRequest("p", 1));
        $f = Control::GetRequest("f", "_all");
        $m = Control::GetRequest("m", "no");
        $syn = Control::GetRequest("syn", "no");
        $s = Control::GetRequest("s", "s_create_date_DESC");

        if (strlen($searchKey) > 0) {
            if ($pageIndex > 0) {

                $allCount = 0;

                    $arrList = self::GetSearchList($allCount, $searchKey, $pageIndex, $pageSize, $m, $syn, $f, $s);
                    if (count($arrList) > 0) {
                        $resultCode = 1;
                        $result = Format::FixJsonEncode($arrList);
                    }else{

                        $resultCode = -3;

                    }



            }else{

                $resultCode = -2;

            }
        }else{

            $resultCode = -5;

        }


        return '{"result_code":"'.$resultCode.'","search_result":{"search_result_list":' . $result . '}}';
    }

    /**
     * 得到搜索数据
     * @param int $allCount 记录总数
     * @param string $q 查询语句
     * @param int $p 第几页
     * @param int $ps 每页显示条数
     * @param string $m 开启模糊搜索，其值为 yes/no
     * @param string $syn 开启同义词搜索，其值为 yes/no
     * @param string $f 只搜索某个字段，其值为字段名称，要求该字段的索引方式为 self/both
     * @param string $s : 排序字段名称及方式，其值形式为：xxx_ASC 或 xxx_DESC
     * @param string  xml: 是否将搜索结果以 XML 格式输出，其值为 yes/no
     * @return array 搜素列表数据集
     */
    private function GetSearchList(&$allCount, $q, $p, $ps, $m = "no", $syn = "no", $f = "_all", $s = "s_create_date_DESC", $xml = "no")
    {

        // 加载 XS 入口文件
        require_once 'search/lib/XS.php';

        // recheck request parameters
        $q = get_magic_quotes_gpc() ? stripslashes($q) : $q;
        $f = empty($f) ? '_all' : $f;

        // other variable maybe used in tpl
        $allCount = $total = $search_cost = 0;
        $docs = $related = $corrected = $hot = array();
        $error = $pager = '';
        $total_begin = microtime(true);

        // perform the search
        $search = null;
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
            }
        } catch (XSException $e) {
            $error = strval($e);
        }

        // calculate total time cost
        $total_cost = microtime(true) - $total_begin;
        $arrList = self::ConvertToArray($search, $docs);
        return $arrList;
    }

    /**
     * 把XSDocument Object对象数组转化为标准php数组
     * @param  全文搜索对象
     * @param  XObjects对象数组
     * @return array 标准php数组
     */
    private function ConvertToArray($search, $XObjects)
    {
        $sitePublicData = new SitePublicData();
        $arrList = array();
        foreach ($XObjects as $XObject) {
            $title = $XObject->s_title;
            $content = strip_tags($XObject->s_content);
            $userName = $XObject->s_user_name;
            $showDate = $XObject->s_show_date;
            $showDate = date("Y-m-d", strtotime($showDate));
            $siteId = $XObject->s_site_id;
            $siteName = $sitePublicData->GetSiteName($siteId, true);
            $siteUrl = $XObject->s_site_url;
            if ($XObject->DirectUrl == null) {
                $directUrl = $siteUrl . "/default.php?mod=newspaper_article&a=detail&newspaper_article_id=" . $XObject->s_id;
            } else {
                $directUrl = $XObject->DirectUrl;
            }
            array_push($arrList,
                array(
                    "DirectUrl" => $directUrl,
                    "Title" => $search->highlight($title),
                    "Content" => $search->highlight($content),
                    "UserName" => $userName,
                    "ShowDate" => $showDate,
                    "SiteId" => $siteId,
                    "SiteName" => $siteName,
                    "SiteUrl" => $siteUrl
                ));
        }
        return $arrList;

    }

} 