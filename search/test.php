<?php
$result="asdf";
//$result='<li><em>20121109</em>��<a href="http://news.changsha.cn/h/187/20121109/1191504.html" target="_blank">������Դ����ᱣ�Ϲ��� ֯���ǳǰ����Ҹ����������� </a></li><li><em>20121109</em>��<a href="http://news.changsha.cn/h/597/20121109/1191691.html" target="_blank">�������š���������ѧ������Ӫ�����Ӫ</a></li><li><em>20121108</em>��<a href="http://news.changsha.cn/h/127/20121108/1191216.html" target="_blank">�������⾢��ASXԤ�ۼ�13.5��</a></li><li><em>20121108</em>��<a href="http://news.changsha.cn/h/597/20121108/1191194.html" target="_blank">�����������������Ȫ���ε�һƷ��</a></li><li><em>20121108</em>��<a href="http://news.changsha.cn/h/593/20121108/1191237.html" target="_blank">��Т�¿� �ٳ��޳�һƬ 9��Ů������ְ�ԭ��</a></li>';
//echo $result;
//echo $_GET['jsonpcallback'] . $result;
echo $_GET['jsonpcallback'] . "(" . json_encode($result) . ")";	
?>