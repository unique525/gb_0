<?php
/**
 * search.php 
 * cswb 搜索项目入口文件
 * 
 * 该文件由 xunsearch PHP-SDK 工具自动生成，请根据实际需求进行修改
 * 创建时间：2012-04-27 10:15:44
 * 默认编码：UTF-8
 */
// 加载 XS 入口文件
require_once '/data/xunsearch/sdk/php/lib/author_XS.php';
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
$__ = array('q', 'm', 'f', 's', 'p', 'ie', 'oe', 'syn', 'xml', 'page', 'psize');
foreach ($__ as $_)
	$$_ = isset($_GET[$_]) ? $_GET[$_] : '';

// input encoding
//////////////if ($page!=null){
//////////////	echo $page;die();
//////////////}
if ($s==null)
{
$s="PublishDate_DESC";
}

if (!empty($ie) && !empty($q) && strcasecmp($ie, 'UTF-8'))
{
	$q = XS::convert($q, $cs, $ie);
	$eu .= '&ie=' . $ie;
}

// output encoding
if (!empty($oe) && strcasecmp($oe, 'UTF-8'))
{

	function xs_output_encoding($buf)
	{
		return XS::convert($buf, $GLOBALS['oe'], 'UTF-8');
	}
	ob_start('xs_output_encoding');
	$eu .= '&oe=' . $oe;
}
else
{
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
$count = $total = $search_cost = 0;
$docs = $related = $corrected = $hot = array();
$error = $pager = '';
$total_begin = microtime(true);

// perform the search
try
{
	$xs = new XS('cswb');
	$search = $xs->search;
	$search->setCharset('UTF-8');

	if (empty($q))
	{
		// just show hot query
		$hot = $search->getHotQuery();
	}
	else
	{
		// fuzzy search
		$search->setFuzzy($m === 'yes');

		// synonym search
		$search->setAutoSynonyms($syn === 'yes');
		
		// set query
		if (!empty($f) && $f != '_all')
		{
			$search->setQuery($f . ':(' . $q . ')');
		}
		else
		{
			$search->setQuery($q);
		}

		// set sort
		if (($pos = strrpos($s, '_')) !== false)
		{
			$sf = substr($s, 0, $pos);
			$st = substr($s, $pos + 1);
			$search->setSort($sf, $st === 'ASC');
		}

		// set offset, limit
		$p = max(1, intval($p));
		$n = (empty($psize))?XSSearch::PAGE_SIZE:$psize;
		$search->setLimit($n, ($p - 1) * $n);

		// get the result
		$search_begin = microtime(true);
		$docs = $search->searchForJson(null, $psize);
		$search_cost = microtime(true) - $search_begin;

		// get other result
		$count = $search->getLastCount();
		$total = $search->getDbTotal();

		if ($xml !== 'yes')
		{
			// try to corrected, if resul too few
			if ($count < 1 || $count < ceil(0.001 * $total))
				$corrected = $search->getCorrectedQuery();			
			// get related query
			$related = $search->getRelatedQuery();			
		}

		// gen pager
		if ($count > $n)
		{
			$pb = max($p - 5, 1);
			$pe = min($pb + 10, ceil($count / $n) + 1);
			$pager = '';
			do
			{
				//$pager .= ($pb == $p) ? '<strong>' . $p . '</strong>' : '<a href="">[' . $pb . ']</a>';
				//$pager .= ($pb == $p) ?$p : '[' . $pb . ']';
				$pager .= ($pb == $p) ? '<div class="pb2"><span style="cursor: pointer;" onclick="preshow('.$p.',q)">'.$p.'</span></div>' : '<div class="pb1"><span style="cursor: pointer;" onclick="preshow('.$pb.')">'.$pb.'</span></div>';
			}
			while (++$pb < $pe);
			
		}

		

$domain=array(
1=>array("http://news.changsha.cn","长沙新闻网"),
2=>array("http://ms.changsha.cn","星辰民声站"),
3=>array("http://photo.test.changsha.cn","星辰影像"),
8=>array("http://auto.changsha.cn","长沙汽车网"),
9=>array("http://3c.changsha.cn","湖南品牌家电网"),
10=>array("http://jj.changsha.cn","家居频道"),
11=>array("http://www.changsha.cn","星辰在线"),
12=>array("http://96333.changsha.cn","96333"),
13=>array("http://xjpl.changsha.cn","湘江评论"),
14=>array("","你说话吧"),
15=>array("","湘江手机报"),
16=>array("http://roll.changsha.cn","滚动新闻"),
17=>array("http://zt.changsha.cn","星辰专题"),
18=>array("","房产频道"),
19=>array("","星辰社区"),
20=>array("","评片系统"),
21=>array("http://health.changsha.cn/","健康频道"),
22=>array("http://caijing.changsha.cn/","财经频道"),
23=>array("http://xuexi.changsha.cn/","教育频道"),
24=>array("http://csx.changsha.cn","长沙县新闻网"),
28=>array("http://www.cslxsh.gov.cn/","长沙两型网"),
31=>array("http://www.csonline.com.cn","中国百城"),

); 


$site_avalible=array("1","16");	
//$site_avalible=array("1","2","8","11","13","14","16","17","18","21","22","23","26","27","29");

	




		$result=array();
		$j=0;
		for($i=0;$i<count($docs);$i++){
			if (in_array($docs[$i]["SiteID"],$site_avalible, TRUE)&&$docs[$i]["DirectUrl"]==""){
			foreach ($docs[$i] as $key => $val) {
					$result[$j][$key]=$val;					
					$result[$j]["pagerbutton"]=$pager;
					//$j=$i+1;
					//$result[$i]["pagerbutton"]=($j == $p) ?$p : '[' . $j . ']';
					$result[$j]["DirectUrl"]=$domain[$result[$j]["SiteID"]][0]."/h/".$result[$j]["DocumentChannelID"]."/".str_replace("-","",substr($result[$j]["CreateDate"],0,10))."/".$result[$j]['DocumentNewsID'].".html";									
				}
			$j++;
			}
		}
		

//		for($i=0;$i<count($docs);$i++){
//			if (in_array($docs[$i]["SiteID"],$site_avalible, TRUE)){
//			foreach ($docs[$i] as $key => $val) {
//					$result[$j][$key]=$val;					
//					$result[$j]["pagerbutton"]=$pager;
//					//$j=$i+1;
//					//$result[$i]["pagerbutton"]=($j == $p) ?$p : '[' . $j . ']';
//					$result[$j]["DirectUrl"]=$domain[$result[$j]["SiteID"]][0]."/h/".$result[$j]["DocumentChannelID"]."/".str_replace("-","",substr($result[$j]["CreateDate"],0,10))."/".$result[$j]['DocumentNewsID'].".html";									
//				}
//			$j++;
//			}
//		}



		
		echo $_GET['jsonpcallback'] . "(" . json_encode($result) . ")";	 		

	}
}
catch (XSException $e)
{
	$error = strval($e);
}

?>

