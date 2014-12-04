<?php
$result="asdf";
//$result='<li><em>20121109</em>·<a href="http://news.changsha.cn/h/187/20121109/1191504.html" target="_blank">人力资源和社会保障工作 织就星城百姓幸福“民生网” </a></li><li><em>20121109</em>·<a href="http://news.changsha.cn/h/597/20121109/1191691.html" target="_blank">“汉语桥——法国中学生秋令营”活动闭营</a></li><li><em>20121108</em>·<a href="http://news.changsha.cn/h/127/20121108/1191216.html" target="_blank">国产三菱劲炫ASX预售价13.5起</a></li><li><em>20121108</em>·<a href="http://news.changsha.cn/h/597/20121108/1191194.html" target="_blank">灰汤致力打造湖南温泉旅游第一品牌</a></li><li><em>20121108</em>·<a href="http://news.changsha.cn/h/593/20121108/1191237.html" target="_blank">听孝德课 操场哭成一片 9岁女生跪求爸爸原谅</a></li>';
//echo $result;
//echo $_GET['jsonpcallback'] . $result;
echo $_GET['jsonpcallback'] . "(" . json_encode($result) . ")";	
?>