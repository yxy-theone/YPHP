# YPHP
my php framework

#配置文件示例
###全局配置文件：在common下新建conf目录,然后conf目录下新建db.php和web.php

###db.php
```a
    <?php
    return [
		'drive'=>'mysqli',//数据库驱动  mysqli,pdo
		'me'=>[
			'dbhost'=>'',
			'dbport'=>'3306',
			'dbuser'=>'root',
			'dbpwd'=>'',
			'dbname'=>''
		],
	];
```

###web.php
```a
    <?php
    return [
		'route_type'=>1,//路由类型 1常规 index.php?act=index&op=detail&id=1  2开启伪静态  www.xx.com/1.html
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
```


###route.php
```a
    <?php
    return [
    	'static_route'=>[
    		'(\d+).html'=>'/index.php?act=index&op=detail&id=[$1]',
    		'category\/(\d+).html'=>'/index.php?act=index&op=index&category=[$1]',
    	],
    	'native_route'=>[
    		'index-index'=>[
    			'index.php\?act=index&op=index&category=(\d+)'=>'/category/[$1].html',
    		],
    		'index-detail'=>[
    			'index.php\?act=index&op=detail&id=(\d+)'=>'/[$1].html',
    		]
    	]
    ];
```

###具体应用配置文件
比如是admin应用，则在apps/admin下新建conf目录，文件及配置同上。。
配置文件是覆盖作用的

Developing...