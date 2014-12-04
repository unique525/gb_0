<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $oe; ?>" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta name="googlebot" content="index,noarchive,nofollow,noodp" />
<meta name="robots" content="index,nofollow,noarchive,noodp" />
<title><?php if (!empty($q)) echo "搜索：" . strip_tags($q) . " - "; ?>星辰在线 站内搜索</title>
<meta http-equiv="keywords" content="Fulltext Search Engine Csol xunsearch" />
<meta http-equiv="description" content="Fulltext Search for Csol, Powered by xunsearch/1.3.1 " />
<style>
*{margin:0px; padding:0px;}
body{background:url(images1203/bg.jpg) repeat-x;font-size:12px;font-family:"宋体",verdana;}
div{margin:0px auto;}
ul,li{list-style:none;}
img{border:0px;}
a{color:#4C4C4C;font-size:12px;text-decoration:none;}
a:hover{color:#BA020C;font-size:12px;text-decoration:noneline;}

#banner{width:100%;height:389px;background:url(images1203/top.jpg) center no-repeat;}
#cont_k{width:998px; border:solid 1px #D1D3D2;background-color:#FFFFFF;}
#tx{width:85px;height:115px;margin:0px auto;line-height:30px;text-align:center;font-size:14px;font-weight:bold;color:#4D4D4D;}
#tx img{padding:3px;background:url(images1203/tx_bg.jpg) no-repeat;}
#jianjie{width:280px;height:50px;background:url(images1203/zzjj.jpg) no-repeat;}
#jj_cont{width:280px;line-height:28px;text-indent:24px;color:#838181;}

.list{width:100%;margin:0px;color:#4D4D4D;background:url(images/author/line_bg.jpg) repeat;font-size:14px;}
.list a{color:#4D4D4D;font-size:14px;}
.list a:hover{color:#FF6600;font-size:14px;text-decoration:none;}
.list ul{margin:0px;padding:0px;}
.list li{line-height:36px;}
.list em{float:right;font-style:normal;font-size:12px;color:#cbcccd;}
</style>
<style type="text/css">
/* result */
.res_div2 { overflow: hidden; zoom: 1; clear: both; margin-top: 10px; }
.res_div2 h4 { font-size: 16px; font-weight: bold; float: left; margin: 0; padding: 0; }
.res_div2 ul { float: left; margin: 0; padding: 0; }
.res_div2 li { float: left; margin: 0 0 0 0; padding: 0; list-style: none; }
#hot-search h4 { margin: 10px 0; float: none; }
#hot-search li { margin-right: 10px; }
#hot-search li small { font-size: 80%; color: #aaa; }
#res-neck { margin-top: 10px; border-bottom: 1px solid #46a; background: #def; font-size: 12px; padding: 5px; }
#res-error { margin: 20px 0; color: red; }
#res-error strong { font-weight: bold; font-size: 120%; }
#res-fixed h4 { font-size: 14px; font-weight: normal; font-style: italic; }
#res-fixed li { margin: 0 5px; }
#res-fixed li a { color: #a00; }
#res-empty { margin-top: 10px; }
#res-pager a { margin: 0 5px; }
#res-pager strong { margin: 0 5px; }
#res-related { padding: 10px; background: #eee; }
#res-related h4 { font-size: 14px; }
#res-related ul { margin-left: 20px; width: 600px; }
#res-related li { width: 120px; padding: 0 5px; }
#res-related li a { text-decoration: underline; font-size: 12px; }
.res-doc { margin-top: 15px; }
.res-doc em { font-style: normal; color: red; }
.res-doc h2 { font-size: 16px; margin: 0; font-weight: normal; }
.res-doc h2 small { font-size: 12px; color: #aaa; }
.res-doc p { font-size: 12px; color: #666; margin: 4px 0; }
.res-doc ul { overflow: hidden; zoom: 1; margin: 0; padding: 0; }
.res-doc li { list-style: none; padding: 0; margin: 0 20px 0 0; float: left; font-size: 12px; color: #666; }
.res-doc li span { border-bottom: 1px dotted #aaa; font-family: arial; color: #444; }
.ui-autocomplete li.ui-menu-item { font-size: 12px; }
</style>
</head>
<!-- search.tpl Csol 搜索模板 -->	
<body>
	<!-- begin search result -->
	<?php if (!empty($q)): ?>
	

	<!-- error -->
	<?php if (!empty($error)): ?>
	<div id="res-error"><strong>错误：</strong><?php echo $error; ?></div>
	<?php endif; ?>

	<!-- fixed query -->
	<?php if (count($corrected) > 0): ?>
	<div id="res-fixed" class="res_div2">
		<h4>您是不是要找：</h4>
		<ul>
			<?php foreach ($corrected as $word): ?>
			<li><a href="<?php echo $_SERVER['SCRIPT_NAME'] . '?q=' . urlencode($word); ?>"><?php echo $word; ?></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php endif; ?>
	
	<!-- empty result -->
	<?php if ($count === 0 && empty($error)): ?>
	<div id="res-empty">
		<p>找不到和 <strong><?php echo htmlspecialchars($q); ?></strong> 相符的内容或信息。建议您：</p>
		<ul>
			<li>请检查输入字词有无错误。</li>
			<li>请换用另外的查询字词。</li>
			<li>请改用较短、较为常见的字词。</li>
		</ul>
	</div>
	<?php endif; ?>
<?php
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
31=>array("http://www.csonline.com.cn","中国百城")
); 


?>	
	<!-- result doc list -->
	<div class="list">
	<ul>
		<?php foreach ($docs as $doc): ?>
<?php
if ($doc->DirectUrl==null){
    $DirectUrl=$domain[$doc->SiteID][0]."/h/".$doc->DocumentChannelID."/".str_replace("-","",substr($doc->CreateDate,0,10))."/".$doc->DocumentNewsID.".html";
}else{
    $DirectUrl=$doc->DirectUrl;
}


?>

		<li><em><?php echo $doc->ShowDate; ?></em>·<a href="<?php echo $DirectUrl?>" target="_blank"><?php echo $search->highlight(htmlspecialchars($doc->DocumentNewsTitle)); ?></a></li>

		<?php endforeach; ?>
	</ul>
	</div>

	<!-- pager -->
	<?php if (!empty($pager)): ?>
	<div id="res-pager" class="res_div2">
		<strong>分页：</strong>
		<?php echo $pager; ?>
	</div>
	<?php endif; ?>
	


	<!-- end search result -->
	<?php endif; ?>

	<!-- footer -->


<!-- load jquery from google -->
<link rel="stylesheet" href="http://func.changsha.cn/common/css/jquery.ui.all.css" type="text/css" media="all" />
<script type="text/javascript" src="http://func.changsha.cn/common/js/jquery.min.js"></script>
<script type="text/javascript" src="http://func.changsha.cn/common/js/jquery-ui-1.8.2.custom.min.js"></script>

<!-- ready script -->
<script language="javascript">
$(function(){
	// input tips
	$('#q-input .text').focus(function(){
		if ($(this).val() == $(this).attr('title')) {
			$(this).val('').removeClass('tips');
		}
	}).blur(function(){
		if ($(this).val() == '' || $(this).val() == $(this).attr('title')) {
			$(this).addClass('tips').val($(this).attr('title'));
		}
	}).blur().autocomplete({
		'source':'suggest.php',
		'select':function(ev,ui) {
			$('#q-input .text').val(ui.item.label);
			$('#q-form').submit();
		}
	});
	// submit check
	$('#q-form').submit(function(){
		var $input = $('#q-input .text');
		if ($input.val() == $input.attr('title')) {
			alert('请先输入关键词');
			$input.focus();
			return false;
		}
	});	
});	
</script>
</body>
</html>
