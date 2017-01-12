<?php
return [
	'route_type'=>1,//路由类型 1常规 index.php?act=index&op=index  2美化  www.xx.com/index/index/id/1
	'log'=>[
		'drive'=>'file',//默认日志操作驱动类  mysql,mongodb
		'path'=>ROOT_PATH.'/storage/log/'.APP,//日志文件存放目录
		'mysql'=>'me/log',//mysql日志存放数据库/表
	],
	'cache'=>[
		'drive'=>'file',//默认缓存操作驱动类 redis,memcache待扩展
		'path'=>ROOT_PATH.'/storage/cache'
	],
	'loginHandler'=>'index/login',
	'errorHandler'=>'index/error',//错误、异常处理方法
	'upload_url'=>'http://127.0.0.1:200/storage/upload/'.APP,
	'upload_dir'=>ROOT_PATH.'/storage/upload/'.APP,
	'params'=>[//各种参数  支付宝,微信,极验......
		'oss-blog'=>[
			'access_key_id'=>'',
			'access_key_secret'=>'',
			'end_point'=>'',
			'hotel_bucket'=>''
		],
	],
	'captcha'=>'',
];