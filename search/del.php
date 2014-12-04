

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题 1</title>
</head>

<body>



<?php
require_once './lib/XS.php';

$xs = new XS('csol');
$index = $xs->index;


#$pdo=new PDO("mysql:host=130.1.0.134;dbname=dbcscms","root","csolbbs2010");
#$pdo->query('set names utf8');

#$sql="SELECT documentnewsid FROM cst_documentnews WHERE siteid=31 AND IsAddToFullText=1 ORDER BY documentnewsid desc limit 10000";

#$result=$pdo->query($sql);

#if (!$result->rowCount()==0){

#foreach ($result as $rs){



$index->del('1389076');

#$ArrID[]=$rs['DocumentNewsID'];
#}

#$stmt=$pdo->prepare("update cst_documentnews set IsAddToFullText=0 where DocumentNewsID=:DocumentNewsID");
#$stmt->bindParam(":DocumentNewsID",$DocumentNewsID);

#foreach ($ArrID as $key=>$val){
#$DocumentNewsID=$val;
echo $DocumnetNewsID;
#if($stmt->execute()){
#	echo $val."执行成功<br>";
#}else{
#	echo $val."执行失败";
#}
#}
#}
#else{
#	echo "无入库记录";
#}


$db=null;

?>

</body>

</html>
