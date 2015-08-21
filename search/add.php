<?php
require_once 'lib/XS.php';

$xs = new XS('cswb');
$index = $xs->index;


$pdo=new PDO("mysql:host=130.1.0.134;dbname=dbicms2","root","csolbbs2010");
$pdo->query('set names utf8');

$sql="			SELECT na.*,s.*,c.*,p.PublishDate

				FROM 
					cst_newspaper_article na,
					cst_site s,
					cst_channel c,
					cst_newspaper_page np,
					cst_newspaper p

				WHERE 
					na.NewspaperPageId=np.NewspaperPageId
					AND np.NewspaperId = p.NewspaperId
					AND p.SiteId = s.SiteId
					AND p.ChannelId = c.ChannelId
					AND na.State=0 
					AND na.IsAddToFullText=0 
					AND s.SiteId=2
					
				Order by na.NewspaperArticleId ASC limit 10000;";

$result=$pdo->query($sql);

if (!$result->rowCount()==0){

foreach ($result as $rs){

$data = array(
    's_id' => $rs['NewspaperArticleId'], // 此字段为主键，必须指定
    's_site_id' => $rs['SiteId'],
    's_channel_id' => $rs['ChannelId'],
    's_parent_id' => 0,
    's_column_id' => $rs['NewspaperPageId'],
    's_title' => $rs['NewspaperArticleTitle'],
    's_main_tag' => '',
    's_tag' => '',
    's_sub_title'=> $rs['NewspaperArticleSubTitle'],
    's_cite_title' => $rs['NewspaperArticleCiteTitle'],
    's_short_title' => '',
    's_intro' => '',
    's_create_date' => $rs['CreateDate'],
    's_show_date' => $rs['PublishDate'],
    's_publish_date' => $rs['PublishDate'],
    's_user_name' => '',
    's_author' => $rs['Author'],
    's_state' => $rs['State'],
    's_sort' => $rs['Sort'],
    's_direct_url' => $rs['DirectUrl'],
    's_content' => $rs['NewspaperArticleContent'],
	's_site_url' => $rs['SiteUrl'],
	's_source' => $rs['Source']
);

$doc = new XSDocument;
$doc->setFields($data);
 
$index->add($doc);

$ArrID[]=$rs['NewspaperArticleId'];
}

$stmt=$pdo->prepare("update cst_newspaper_article set IsAddToFullText=1 where NewspaperArticleId=:NewspaperArticleId;");
$stmt->bindParam(":NewspaperArticleId",$NewspaperArticleId);

foreach ($ArrID as $key=>$val){
$NewspaperArticleId=$val;
echo $NewspaperArticleId;
if($stmt->execute()){
	echo $val."执行成功<br>";
}else{
	echo $val."执行失败";
}
}
}
else{
	echo "无入库记录";
}


$db=null;

?>
