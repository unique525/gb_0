<?php
function preghtml($str){
$str= preg_replace("/<(.*?)>/","",$str); 



$str=preg_replace("/\s+/", " ", $str); //���˶���س� 
$str=preg_replace("/<[ ]+/si","<",$str); //����<__("<"�ź�����ո�) 

$str=preg_replace("/<\!--.*?-->/si","",$str); //ע�� 
$str=preg_replace("/<(\!.*?)>/si","",$str); //����DOCTYPE 
$str=preg_replace("/<(\/?html.*?)>/si","",$str); //����html��ǩ 
$str=preg_replace("/<(\/?head.*?)>/si","",$str); //����head��ǩ 
$str=preg_replace("/<(\/?meta.*?)>/si","",$str); //����meta��ǩ 
$str=preg_replace("/<(\/?body.*?)>/si","",$str); //����body��ǩ 
$str=preg_replace("/<(\/?link.*?)>/si","",$str); //����link��ǩ 
$str=preg_replace("/<(\/?form.*?)>/si","",$str); //����form��ǩ 
$str=preg_replace("/cookie/si","COOKIE",$str); //����COOKIE��ǩ 

$str=preg_replace("/<(applet.*?)>(.*?)<(\/applet.*?)>/si","",$str); //����applet��ǩ 
$str=preg_replace("/<(\/?applet.*?)>/si","",$str); //����applet��ǩ 

$str=preg_replace("/<(style.*?)>(.*?)<(\/style.*?)>/si","",$str); //����style��ǩ 
$str=preg_replace("/<(\/?style.*?)>/si","",$str); //����style��ǩ 

$str=preg_replace("/<(title.*?)>(.*?)<(\/title.*?)>/si","",$str); //����title��ǩ 
$str=preg_replace("/<(\/?title.*?)>/si","",$str); //����title��ǩ 

$str=preg_replace("/<(object.*?)>(.*?)<(\/object.*?)>/si","",$str); //����object��ǩ 
$str=preg_replace("/<(\/?objec.*?)>/si","",$str); //����object��ǩ 

$str=preg_replace("/<(noframes.*?)>(.*?)<(\/noframes.*?)>/si","",$str); //����noframes��ǩ 
$str=preg_replace("/<(\/?noframes.*?)>/si","",$str); //����noframes��ǩ 

$str=preg_replace("/<(i?frame.*?)>(.*?)<(\/i?frame.*?)>/si","",$str); //����frame��ǩ 
$str=preg_replace("/<(\/?i?frame.*?)>/si","",$str); //����frame��ǩ 

$str=preg_replace("/<(script.*?)>(.*?)<(\/script.*?)>/si","",$str); //����script��ǩ 
$str=preg_replace("/<(\/?script.*?)>/si","",$str); //����script��ǩ 
$str=preg_replace("/javascript/si","Javascript",$str); //����script��ǩ 
$str=preg_replace("/vbscript/si","Vbscript",$str); //����script��ǩ 
$str=preg_replace("/on([a-z]+)\s*=/si","On\\1=",$str); //����script��ǩ 
$str=preg_replace("/&#/si","&��",$str); //����script��ǩ����javAsCript:alert( 
return $str;
}