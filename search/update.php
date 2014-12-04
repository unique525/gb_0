<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>更新数据</title>
</head>

<body>



<?php
require_once '/data/search/lib/XS.php';

$xs = new XS('csol');
$index = $xs->index;


$pdo=new PDO("mysql:host=130.1.0.134;dbname=dbcscms","root","csolbbs2010");
$pdo->query('set names utf8');

$sql="select DocumentNewsID,SiteID,DocumentChannelID,DocumentNewsTitle,DocumentNewsSubTitle,DocumentNewsCiteTitle,DocumentNewsShortTitle,DocumentNewsIntro,CreateDate,ShowDate,PublishDate,UserName,Author,State,DirectUrl,DocumentNewsContent from cst_documentnews where State=30 and IsAddToFullText=2 order by documentnewsid asc limit 100";

$result=$pdo->query($sql);

if (!$result->rowCount()==0){

foreach ($result as $rs){

$data = array(
    'DocumentNewsID' => $rs['DocumentNewsID'], // 此字段为主键，必须指定
    'SiteID' => $rs['SiteID'],
    'DocumentChannelID' => $rs['DocumentChannelID'],
    'DocumentNewsTitle' => $rs['DocumentNewsTitle'],
    'DocumentNewsMainTag' => $rs['DocumentNewsMainTag'],
    'DocumentNewsSubTitle'=> $rs['DocumentNewsSubTitle'],
    'DocumentNewsCiteTitle' => $rs['DocumentNewsCiteTitle'],
    'DocumentNewsShortTitle' => $rs['DocumentNewsShortTitle'],
    'DocumentNewsIntro' => $rs['DocumentNewsIntro'],
    'CreateDate' => $rs['CreateDate'],
    'ShowDate' => $rs['ShowDate'],
    'PublishDate' => $rs['PublishDate'],
    'UserName' => $rs['UserName'],
    'Author' => $rs['Author'],
    'State' => $rs['State'],
    'DirectUrl' => $rs['DirectUrl'],
    'DocumentNewsContent' => $rs['DocumentNewsContent']
);

$doc = new XSDocument;
$doc->setFields($data);
 
$index->update($doc);

$ArrID[]=$rs['DocumentNewsID'];
}

$stmt=$pdo->prepare("update cst_documentnews set IsAddToFullText=1 where DocumentNewsID=:DocumentNewsID");
$stmt->bindParam(":DocumentNewsID",$DocumentNewsID);

foreach ($ArrID as $key=>$val){
	$DocumentNewsID=$val;
	if($stmt->execute()){
		echo $val."执行成功<br>";
	}else{
		echo $val."执行失败";
		}
	}
}
else{
	echo "无更新记录";
}


$db=null;

?>

</body>

</html>
