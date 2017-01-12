<?php
/**
 * 入口文件
 * 1.定义常量
 * 2.加载函数库
 * 3.启动框架
 */
define('ROOT_PATH', str_replace('\\','/',dirname(dirname(__FILE__))));
define('APP_PATH', ROOT_PATH.'/apps/api');
define('APP', 'api');
define('SITE_URL', 'http://api.yjxxkj.cn');
define('DEBUG', true);

if (DEBUG) {
	error_reporting(E_ALL & ~E_NOTICE);
}else{
	error_reporting(0);
}

include ROOT_PATH.'/common/function.php';
include ROOT_PATH.'/framework/Core.php';

spl_autoload_register('\framework\Core::load');

\framework\Core::run();