<?php


/*****
 *
 *
 * 清空命令
 * /usr/local/php-5.2.17/bin/php /data/xunsearch/sdk/php/util/Indexer.php --clean cswb
 *
 * 加入新文档命令
 * /usr/local/php-5.2.17/bin/php /data/cswb/96333.changsha.cn/so/add.php
 *
 *
 *
 */


require_once 'lib/XS.php';

$xs = new XS('cswb');
$index = $xs->index;


$pdo = new PDO("mysql:host=130.1.0.134;dbname=dbicms2", "root", "csolbbs2010");
$pdo->query('set names utf8');



/*************************************** Newspaper *************************************************/



$sql = "			SELECT na.*,s.*,c.*,p.PublishDate

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
					
				Order by na.NewspaperArticleId ASC limit 1000;";

$result = $pdo->query($sql);

if (!$result->rowCount() == 0) {

    foreach ($result as $rs) {

        $data = array(
            's_id' => $rs['NewspaperArticleId'], // 此字段为主键，必须指定
            's_site_id' => $rs['SiteId'],
            's_channel_id' => $rs['ChannelId'],
            's_channel_type' => $rs['ChannelType'],
            's_parent_id' => 0,
            's_column_id' => $rs['NewspaperPageId'],
            's_title' => $rs['NewspaperArticleTitle'],
            's_main_tag' => '',
            's_tag' => '',
            's_sub_title' => $rs['NewspaperArticleSubTitle'],
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

        $ArrID[] = $rs['NewspaperArticleId'];
    }

    $stmt = $pdo->prepare("update cst_newspaper_article set IsAddToFullText=1 where NewspaperArticleId=:NewspaperArticleId;");
    $stmt->bindParam(":NewspaperArticleId", $NewspaperArticleId);

    foreach ($ArrID as $key => $val) {
        $NewspaperArticleId = $val;
        echo $NewspaperArticleId;
        if ($stmt->execute()) {
            echo $val . " cst_newspaper_article success<br>";
        } else {
            echo $val . " cst_newspaper_article failure<br>";
        }
    }
} else {
    echo "no cst_newspaper_article record<br>";
}



/*************************************** DocumentNews *************************************************/



$sql = "			SELECT dn.*,s.*,c.*

				FROM
					cst_document_news dn,
					cst_site s,
					cst_channel c

				WHERE
					c.SiteId = s.SiteId
					AND dn.ChannelId = c.ChannelId
					AND dn.State=30
					AND dn.IsAddToFullText=0

				Order by dn.DocumentNewsId ASC limit 1000;";

$result = $pdo->query($sql);

if (!$result->rowCount() == 0) {

    foreach ($result as $rs) {

        $data = array(
            's_id' => $rs['DocumentNewsId'], // 此字段为主键，必须指定
            's_site_id' => $rs['SiteId'],
            's_channel_id' => $rs['ChannelId'],
            's_channel_type' => $rs['ChannelType'],
            's_parent_id' => 0,
            's_column_id' => $rs['ChannelId'],
            's_title' => $rs['DocumentNewsTitle'],
            's_main_tag' => $rs['DocumentNewsMainTag'],
            's_tag' => $rs['DocumentNewsTag'],
            's_sub_title' => $rs['DocumentNewsSubTitle'],
            's_cite_title' => $rs['DocumentNewsCiteTitle'],
            's_short_title' => $rs['DocumentNewsShortTitle'],
            's_intro' => $rs['DocumentNewsShortIntro'],
            's_create_date' => $rs['CreateDate'],
            's_show_date' => $rs['ShowDate'],
            's_publish_date' => $rs['PublishDate'],
            's_user_name' => $rs['UserName'],
            's_author' => $rs['Author'],
            's_state' => $rs['State'],
            's_sort' => $rs['Sort'],
            's_direct_url' => $rs['DirectUrl'],
            's_content' => $rs['DocumentNewsContent'],
            's_site_url' => $rs['SiteUrl'],
            's_source' => $rs['SourceName']
        );

        $doc = new XSDocument;
        $doc->setFields($data);

        $index->add($doc);

        $ArrID[] = $rs['DocumentNewsId'];
    }

    $stmt = $pdo->prepare("update cst_document_news set IsAddToFullText=1 where DocumentNewsId=:DocumentNewsId;");
    $stmt->bindParam(":DocumentNewsId", $documentNewsId);

    foreach ($ArrID as $key => $val) {
        $documentNewsId = $val;
        echo $documentNewsId;
        if ($stmt->execute()) {
            echo $val . " DocumentNews success<br>";
        } else {
            echo $val . " DocumentNews failure<br>";
        }
    }
} else {
    echo "no DocumentNews record<br>";
}

$db = null;

?>
