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
		if ($route_type != 1) {//1.判断是否为原生uri 2.获取url伪静态配置 3.解析uri
			if (isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != '/') {
				$uri = ltrim($_SERVER['REQUEST_URI'],'/');
				if(strrpos($uri, '.php?act=')||strrpos($uri, '.php?op=')){//此时认为是原生uri 无需进行伪静态解析
					$this->getActAndOp();
					return;
				}else{
					$static_route = Conf::get('static_route','route');
					//此处可以配合kv缓存,以md5($uri)作为缓存的k,解析后的url为v
					foreach ($static_route as $k => $v) {
						$pattern = '/^'.$k.'$/';
						if(preg_match($pattern, $uri, $match)){
							for ($i=1; $i < count($match); $i++) {
								$s = '[$'.$i.']';
								$v = str_replace($s, $match[$i], $v);
							}
							$v = str_replace('/index.php?', '', $v);
							$v_arr = explode('&', trim($v));
							foreach ($v_arr as $value) {
								$arr = explode('=', $value);
								$_GET[$arr[0]] = $arr[1];
							}
							$this->getActAndOp();
							return;
						}
					}
					redirect(SITE_URL);
				}
			}
		}
		$this->getActAndOp();
	}

	public function getActAndOp(){
		$this->act = preg_match('/^[\w]+$/i',$_GET['act']) ? $_GET['act'] : 'index';
		$this->op = preg_match('/^[\w]+$/i',$_GET['op']) ? $_GET['op'] : 'index';
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
		if (is_array($params)) {
			foreach ($params as $k => $v) {
				$p .= '&'.$k.'='.$v;
			}
		}
		$script_name = empty($script_name)?'index.php':$script_name;
		$url = '/'.$script_name.'?act='.$url_arr[0].'&op='.$url_arr[1].$p;
		if(self::$route_type == 1){
			return $url;
		}else{//未完
			$native_route = Conf::get('native_route','route');
			$uri = ltrim($url,'/');
			$native_key = $url_arr[0].'-'.$url_arr[1];
			if(isset($native_route[$native_key])){
				foreach ($native_route[$native_key] as $k => $v) {
					$pattern = '/^'.$k.'$/';
					if(preg_match($pattern, $uri, $match)){
						for ($i=1; $i < count($match); $i++) {
							$s = '[$'.$i.']';
							$v = str_replace($s, $match[$i], $v);
						}
						return $v;
					}
				}
			}
			return $url;
		}
	}

	public static function urlTest($url, $params=NULL){
		$native_route = Conf::get('native_route','route');
		$url_arr = explode('/',$url);
		$p = '';
		if (is_array($params)) {
			foreach ($params as $k => $v) {
				$p .= '&'.$k.'='.$v;
			}
		}
		$url = '/index.php?act='.$url_arr[0].'&op='.$url_arr[1].$p;
		$uri = ltrim($url,'/');
		$native_key = $url_arr[0].'-'.$url_arr[1];
		if(isset($native_route[$native_key])){
			foreach ($native_route[$native_key] as $k => $v) {
				$pattern = '/^'.$k.'$/';
				if(preg_match($pattern, $uri, $match)){
					for ($i=1; $i < count($match); $i++) {
						$s = '[$'.$i.']';
						$v = str_replace($s, $match[$i], $v);
					}
					return $v;
				}
			}
		}
		return $url;
	}
}