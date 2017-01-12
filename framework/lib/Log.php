<?php
namespace framework\lib;

class Log
{
	/**
	 * 1.确定日志存储方式
	 * 2.写日志
	 */
	static $class;

	public static function init()
	{
		if (!is_object(self::$class)) {
			//确定存储方式
			$log_conf = Conf::get('log','web');
			$drive = $log_conf['drive'];
			$class = '\framework\lib\drive\log\\'.ucfirst($drive);
			self::$class = new $class;
		}
	}

	public static function record($info, $level="ERROR", $drive=null)
	{
		if ($drive == null) {
			self::init();
			self::$class->log($info,$level);
		}else{
			$class = '\framework\lib\drive\log\\'.$drive;
			$log_drive = new $class;
			$log_drive->log($info,$level);
		}
	}
}