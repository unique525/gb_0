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
$incDatabaseInfo[] = 'dbicms2';
$incDatabaseInfo[] = 'root';
$incDatabaseInfo[] = 'csolbbs2010';
$incDatabaseInfo[] = '0'; //debug
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
$incSecurityIP = array("127.0.0.1","::1","222.247.40.25");
$incSecurityIP = implode('|',$incSecurityIP);

?>
