<?php
namespace framework\lib;

/**
 * 路由类
 */
class Route
{
	public $act;
	public $op;
	static $route_type;

	public function __construct(){
		$route_type = Conf::get('route_type','web');
		self::$route_type = $route_type;
		if ($route_type == 1) {//常规路由
			$this->act = preg_match('/^[\w]+$/i',$_GET['act']) ? $_GET['act'] : 'index';
			$this->op = preg_match('/^[\w]+$/i',$_GET['op']) ? $_GET['op'] : 'index';
		}else{//路由美化 1.隐藏index.php 2.获取URL 参数部分
			if (isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != '/') {
				# index/index
				$path = $_SERVER['REQUEST_URI'];
				$patharr = explode('/', trim($path,'/'));
				if (isset($patharr[0])) {
					$this->act = $patharr[0];
					unset($patharr[0]);
				}
				if (isset($patharr[1])) {
					$this->op = $patharr[1];
					unset($patharr[1]);
				}else{
					$this->op = 'index';
				}
				//url 多余部分转换成 $GET
				// id/1/str/2/stst/3
				$count = count($patharr);
				$i=2;
				while ($i <= $count) {
					if (isset($patharr[$i+1])) {
						$_GET[$patharr[$i]] = $patharr[$i+1];
					}
					$i += 2;
				}
			}else{
				$this->act = 'index';
				$this->op = 'index';
			}
		}
	}

	/**
	 * urlManager url生成方法
	 * @param  str    $url         控制器/方法
	 * @param  array  $params      参数数组
	 * @param  string $script_name 脚本名
	 * @return string              完整url
	 */
	public static function urlManager($url, $params=NULL, $script_name=NULL)
	{	
		if ($url=='index/index'&&empty($params)&&empty($script_name)&&!empty(SITE_URL)) {
			return SITE_URL;
		}
		$url_arr = explode('/',$url);
		$p = '';
		if(self::$route_type == 1){
			if (is_array($params)) {
				foreach ($params as $k => $v) {
					$p .= '&'.$k.'='.$v;
				}
			}
			$url = empty($script)?'index.php?':$script_name.'?';
			return $url.'act='.$url_arr[0].'&op='.$url_arr[1].$p;
		}else{//未完
			return $url_arr[0].'/'.$url_arr[1];
		}
	}
}