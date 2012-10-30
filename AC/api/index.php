<?php
$plimusIps = array("62.219.121.253", "209.128.93.248", "72.20.107.242", "209.128.93.229", "209.128.93.98", "209.128.93.230", "209.128.93.245", "209.128.93.104", "209.128.93.105", "209.128.93.107", "209.128.93.108", "209.128.93.242", "209.128.93.243", "209.128.93.254", "62.216.234.216", "62.216.234.218", "62.216.234.219", "62.216.234.220", "127.0.0.1","localhost", "209.128.104.18", "209.128.104.19", "209.128.104.20", "209.128.104.21", "209.128.104.22", "209.128.104.23", "209.128.104.24", "209.128.104.25", "209.128.104.26", "209.128.104.27", "209.128.104.28", "209.128.104.29", "209.128.104.30", "209.128.104.31", "209.128.104.32", "209.128.104.33", "209.128.104.34", "209.128.104.35", "209.128.104.36", "209.128.104.37", "99.186.243.9", "99.186.243.10", "99.186.243.11", "99.186.243.12", "99.186.243.13", "99.180.227.233", "99.180.227.234", "99.180.227.235", "99.180.227.236", "99.180.227.237");
if (isset($_REQUEST['transactionType']) && array_search($_SERVER['REMOTE_ADDR'], $plimusIps) == true) {
	require_once('payment.php');
}

// change the following paths if necessary
$yii=dirname(__FILE__).'/../../../yii/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);
Yii::createWebApplication($config)->run();
