<?php
namespace framework;

use framework\lib\Route;
use framework\lib\Conf;
final class Core
{
	public static function run($is_session_start=false,$session_name='PHPSESSID')
	{
		if($is_session_start) self::start_session($session_name);
		define('TIMESTAMP', time());
		date_default_timezone_set('Asia/Shanghai');
		try {
			$route = new Route();
			$act = $route->act;
			$op = $route->op.'Op';
			$actFile = APP_PATH.'/controller/'.ucfirst($act).'Controller.php';
			$actClass = '\\apps\\'.APP.'\controller\\'.ucfirst($act).'Controller';
			if (is_file($actFile)) {
				$act = new $actClass($route->act,$route->op);
				if(method_exists($act, $op)){
					$act->$op();
				}else{
					throw new \Exception('找不到方法'.$route->op,404);
				}
			}else{
				throw new \Exception('找不到控制器'.$actClass,404);
			}
		} catch (\Exception $e) {
			if($e->getCode() == 401){
				redirect(Route::urlManager(Conf::get('loginHandler','web')));
			}else{
				redirect(Route::urlManager(Conf::get('errorHandler','web'),['code'=>$e->getCode(),'message'=>$e->getMessage()]));
			}
		}
	}

	/**
	 * 自动加载方法
	 */
	public static function load($class)
	{	
		$class = str_replace('\\', '/', $class);
		$file = ROOT_PATH.'/'.$class.'.php';
		if(is_file($file))
			include $file;
		else
			throw new \Exception("找不到类文件".$file);
	}

	/**
	 * 开启session
	 */
	private static function start_session($session_name='PHPSESSID'){
		@ini_set('session.name',$session_name);
		session_save_path(ROOT_PATH.'/storage/sessions');
		session_start();
	}

	/**
	 * 过滤$_POST数据
	 * 1.将空格全部替换成$nbsp;
	 */
	private static function filter_post_data($array){
		if (!empty($array)){
			while (list($k,$v) = each($array)) {
				if (is_string($v)) {
					$array[$k]= str_replace(' ','&nbsp;',$v);
				} else if (is_array($v))  {
					$array[$k] = self::filter_post_data($v);
				}
			}
		}
		return $array;
	}
}