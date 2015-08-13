<?php

/**
 * 系统名称
 */
$incSystemName = 'iCMS';

/**
 * 系统数据库配置
 */
$incDatabaseInfo = array();

$incDatabaseInfo[] = '130.1.0.134';
$incDatabaseInfo[] = 3306;
$incDatabaseInfo[] = 'dbcscms2';
$incDatabaseInfo[] = 'root';
$incDatabaseInfo[] = 'csolbbs2010';
$incDatabaseInfo[] = '0'; //debug
/*

$incDatabaseInfo[] = 'localhost';
$incDatabaseInfo[] = 3306;
$incDatabaseInfo[] = 'dbicms2';
$incDatabaseInfo[] = 'root';
$incDatabaseInfo[] = 'ZAQ1xsw2';
$incDatabaseInfo[] = '0'; //debug
*/


$incDatabaseInfo = implode('|',$incDatabaseInfo);

/**
 * 系统语言
 */
$incLanguage = "language/zh";

/**
 * 系统域名
 */
$incDomain = array();
$incDomain['webapp'] = 'http://localhost';
$incDomain['manage'] = 'http://localhost';

/**
 * 是否开启管理日志
 */
$incOpenManageUserLog = true;

/**
 * 安全ip设置
 */
$incSecurityIP = array();
$incSecurityIP = implode('|',$incSecurityIP);


$incMemcachedServers = array(
    array('130.1.0.168',11211)
);

// 选项设置
$incMemcachedOptions = array(
    'servers' => array('130.1.0.168:11211'), //memcached 服务的地址、端口，可用多个数组元素表示多个 memcached 服务
    'debug' => true, //是否打开 debug
    'compress_threshold' => 10240, //超过多少字节的数据时进行压缩
    'persistant' => false //是否使用持久连接
);


?>
