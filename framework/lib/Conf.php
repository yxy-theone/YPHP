<?php
namespace framework\lib;
/**
 * 1.判断配置文件是否存在
 * 2.判断配置是否存在
 * 3.缓存配置
 */
class Conf
{
	static public $conf = [];
	static public $app_conf = [];
	
	public static function get($name, $file)
	{
		if(isset(self::$app_conf[$file][$name])){
			return self::$app_conf[$file][$name];
		}else{
			$path = APP_PATH.'/conf/'.$file.'.php';
			if(is_file($path)){
				$app_conf = include $path;
				if (isset($app_conf[$name])) {
					self::$app_conf[$file] = $app_conf;
					return $app_conf[$name];
				}else{
					return self::getTopConf($name, $file);
				}
			}else{
				return self::getTopConf($name, $file);
			}
		}
	}

	public static function getTopConf($name, $file)
	{
		if(isset(self::$conf[$file][$name])){
			return self::$conf[$file][$name];
		}else{
			$path = ROOT_PATH.'/common/conf/'.$file.'.php';
			if(is_file($path)){
				$conf = include $path;
				if (isset($conf[$name])) {
					self::$conf[$file] = $conf;
					return $conf[$name];
				}else{
					if(DEBUG) exit('不存在'.$name.'配置项');
					throw new \Exception('不存在'.$name.'配置项');
				}
			}else{
				if(DEBUG) exit('不存在'.$file.'配置文件');
				throw new \Exception('不存在'.$file.'配置文件');
			}
		}
	}
}