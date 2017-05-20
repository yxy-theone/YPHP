<?php
/**
 * 入口文件
 * 1.定义常量
 * 2.加载函数库
 * 3.启动框架
 */
define('ROOT_PATH', str_replace('\\','/',dirname(dirname(__FILE__))));
define('APP_PATH', ROOT_PATH.'/apps/blog');
define('APP', 'blog');
define('SITE_URL', 'http://blog.yuphp.cn');
define('DEBUG', true);
if (DEBUG) {
	error_reporting(E_ALL & ~E_NOTICE);
}else{
	error_reporting(0);
}
include ROOT_PATH.'/blog/blog.function.php';
include ROOT_PATH.'/common/function.php';
include ROOT_PATH.'/framework/Core.php';
include ROOT_PATH.'/vendor/autoload.php';//引入composer的自动加载文件
spl_autoload_register('\framework\Core::load');

//run   参数1 是否开启session  参数2 session前缀
\framework\Core::run(true,'ADMIN');